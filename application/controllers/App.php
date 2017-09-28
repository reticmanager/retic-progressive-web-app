<?php

class App extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url'));
    }

    public function index($code) {
        if (!isset($this->user)) {
            redirect('/account/login');
            return;
        }

        if (!$this->admin && $this->council->code != $code) {
            redirect('/');
            return;
        }

        $query = $this->db->query('SELECT * FROM council WHERE code = ?', array($code));
        $council = $query->row();
        $admin = $this->admin;
        $user = $this->user;

        if (!isset($council)) {
            redirect('/');
            return;
        }

        $data = array(
            'council' => $council
        );
		$admin_data = array(
            'admin' => $admin
        );
        $user_data = array(
            'user' => $user->first_name . " " . $user->last_name
        );

        $error = array('error' => '');

        // Simple check for URL page match and load view
        if (strpos(current_url(), 'surveys') !== false) {
          $this->load->view('survey', array_merge($error, $data, $admin_data, $user_data));
        } else {
          $this->load->view('app', array_merge($data,$admin_data,$user_data));
        }
    }

// Upload function for survey form
    public function do_upload()
{
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 1000;
        $config['max_width'] = 1024;
        $config['max_height'] = 768;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
                $error = array('error' => $this->upload->display_errors());

                $this->load->view('upload_form', $error);
        }
        else
        {
                $data = array('upload_data' => $this->upload->data());

                $this->load->view('upload_success', $data);
        }
}
  }
