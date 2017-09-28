<?php

class Account extends MY_Controller {

    public function __construct() {
        parent::__construct();

        // Load helper libraries
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('email');
    }

    public function forgot_password() {
        if ($this->input->method() == 'get') {
            $data['content'] = 'forgot_password';
            $this->load->view($this->layout, $data);
        } else if ($this->input->method() == 'post') {
            $this->forgot_password_post();
        } else {
            show_404();
        }
    }

    private function forgot_password_post()
    {
        // prepare view data
        $data['content'] = 'forgot_password';

        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');

        if ($this->form_validation->run() == FALSE)
        {
            // fails
            $this->load->view($this->layout, $data);
            return;
        }
        else
        {
            // valid form
            $query = $this->db->query('SELECT * FROM user WHERE email = ?', array($this->input->post('email')));
            $user = $query->row();

            // make sure it is a valid council
            if (!isset($user)) {
                $this->session->set_flashdata('message','<div class="alert alert-danger text-center">The user doesn\'t exist.</div>');
                $this->load->view($this->layout, $data);
                return;
            }

            $token = $this->get_token(32);

            $link = 'https://' . $_SERVER["SERVER_NAME"] . base_url('/account/reset_password/') . $token;
            $message = '
<html>
<head>
  <title>Password Reset</title>
</head>
<body>
  <p>Hi ' . $user->first_name . '</p>
  <p>Click this link to recover your password: <a href=\'' . $link . '\'>' . $link  . '</a></p>
</body>
</html>
';
            $this->email->from('noreply@reticmanager.com', 'ReticManager');
            $this->email->to($user->email);

            $this->email->subject('Password Reset');
            $this->email->set_mailtype("html");
            $this->email->message($message);

            $this->email->send();

            $updated_user = array(
                'reset_password_token' => $token,
                'reset_password_timestamp' => round(microtime(true))
            );

            $this->db->where('id', $user->id);

            // add the user into the database
            if ($this->db->update('user', $updated_user)) {
                // success
                $this->session->set_flashdata('login_email',$user->email);
                $this->session->set_flashdata('message','<div class="alert alert-success text-center">An email has been sent with instructions to reset your password.</div>');
                redirect('/account/login');
                return;
            } else {
                // error
                $this->session->set_flashdata('message','<div class="alert alert-danger text-center">There was an error resetting your password. Please try again later.</div>');
                $this->load->view($this->layout, $data);
                return;
            }
        }

        // render view within master layout
        $this->load->view($this->layout, $data);
    }


    public function reset_password($token) {

        // get the user we are trying to reset the password for
        $query = $this->db->query('SELECT * FROM user WHERE reset_password_token = ?', array($token));
        $user = $query->row();

        // make sure the token is valid and hasn't expired (tokens expire in 60 minutes from when they were created)
        if (!isset($user) || ($user->reset_password_timestamp + 60 * 60) < round(microtime(true))) {
            $this->session->set_flashdata('login_email',$user->email);
            $this->session->set_flashdata('message','<div class="alert alert-danger text-center">Your reset password link has expired.</div>');
            redirect('/account/login');
            return;
        }

        if ($this->input->method() == 'get') {
            $data['email'] = $user->email;
            $data['token'] = $token;
            $data['content'] = 'reset_password';
            $this->load->view($this->layout, $data);
        } else if ($this->input->method() == 'post') {
            $this->reset_password_post($user, $token);
        } else {
            show_404();
        }
    }

    private function reset_password_post($user, $token)
    {
        // prepare view data
        $data['email'] = $user->email;
        $data['token'] = $token;
        $data['content'] = 'reset_password';

        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[50]|matches[confirm_password]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            // fails
            $this->load->view($this->layout, $data);
            return;
        }
        else
        {
            // valid form
            $updated_user = array(
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'reset_password_token' => null,
                'reset_password_timestamp' => null
            );

            $this->db->where('id', $user->id);

            // add the user into the database
            if ($this->db->update('user', $updated_user)) {
                // success
                $this->session->set_flashdata('login_email',$user->email);
                $this->session->set_flashdata('message','<div class="alert alert-success text-center">Your password has been reset successfully.</div>');
                redirect('/account/login');
                return;
            } else {
                // error
                $this->session->set_flashdata('message','<div class="alert alert-danger text-center">There was an error resetting your password. Please try again later.</div>');
                $this->load->view($this->layout, $data);
                return;
            }
        }

        // render view within master layout
        $this->load->view($this->layout, $data);
    }

    public function login() {
        if ($this->input->method() == 'get') {
            $data['content'] = 'login';
            $this->load->view($this->layout, $data);
        } else if ($this->input->method() == 'post') {
            $this->login_post();
        } else {
            show_404();
        }
    }

    public function logout() {
        $this->do_logout();

        redirect('/account/login');
    }

    private function do_logout() {
        unset(
            $_SESSION['logged_in'],
            $_SESSION['email']
        );

        delete_cookie('remember_me_email');
        delete_cookie('remember_me_token');
    }

    private function login_post() {
        // if we are trying to login, lets logout if we are already logged in
        $this->do_logout();

        $data['content'] = 'login';

        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            // fails
            $this->load->view($this->layout, $data);
            return;
        } else {
            // get the council by the code from the database
            $query = $this->db->query('SELECT * FROM user WHERE email = ?', array($this->input->post('email')));
            $user = $query->row();
            if (!isset($user)) {
                $this->session->set_flashdata('message','<div class="alert alert-danger text-center">Invalid email or password.</div>');
                $this->load->view($this->layout, $data);
                return;
            } else if (!password_verify($this->input->post('password'), $user->password)) {
                $this->session->set_flashdata('message','<div class="alert alert-danger text-center">Invalid email or password.</div>');
                $this->load->view($this->layout, $data);
                return;
            } else {

                $_SESSION['logged_in'] = true;
                $_SESSION['email'] = $user->email;

                if ($this->input->post('remember_me')) {
                    $new_token = $this->get_token(32);
                    $updated_user = array(
                        'remember_me_token' => $new_token,
                        'remember_me_timestamp' => round(microtime(true))
                    );

                    $this->db->where('id', $user->id);
                    $this->db->update('user', $updated_user);

                    $this->input->set_cookie('remember_me_email', $user->email, 60 * 60 * 24 * 14);
                    $this->input->set_cookie('remember_me_token', $new_token, 60 * 60 * 24 * 14);
                }

                redirect('/');
                return;
            }
        }

        // render view within master layout
        $this->load->view($this->layout, $data);
    }

    public function register($code) {
        // make sure the $code parameter is set
        if (!isset($code)) {
            show_404();
            return;
        }

        // get the council by the code from the database
        $query = $this->db->query('SELECT * FROM council WHERE code = ?', array($code));
        $council = $query->row();

        // make sure it is a valid council
        if (!isset($council)) {
            show_404();
            return;
        }

        if ($this->input->method() == 'get') {
            $data['council'] = $council;
            $data['content'] = 'register';
            $this->load->view($this->layout, $data);
        } else if ($this->input->method() == 'post') {
            $this->register_post($council);
        } else {
            show_404();
        }
    }

    private function register_post($council) {

        // prepare view data
        $data['council'] = $council;
        $data['content'] = 'register';

        //set validation rules
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('organisation', 'Organisation', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('position', 'Organisation', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('phone', 'Phone Number', 'trim|required|max_length[50]');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[50]|matches[confirm_password]');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            // fails
            $this->load->view($this->layout, $data);
            return;
        }
        else
        {
            $user = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'organisation' => $this->input->post('organisation'),
                'position' => $this->input->post('position'),
                'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'council_id' => $council->id
            );

            // add the user into the database
            if ($this->db->insert('user', $user)) {
                // success
                $this->session->set_flashdata('login_email',$user->email);
                $this->session->set_flashdata('message','<div class="alert alert-success text-center">Your account has been created successfully.</div>');
                redirect('/account/login');
                return;
            } else {
                // error
                $this->session->set_flashdata('message','<div class="alert alert-danger text-center">There was an error creating your account. Please try again later.</div>');
                $this->load->view($this->layout, $data);
                return;
            }
        }

        // render view within master layout
        $this->load->view($this->layout, $data);
    }
}