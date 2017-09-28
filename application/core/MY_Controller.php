<?php

class MY_Controller extends CI_Controller {

    public $layout;
    public $user;
    public $council;
    public $admin;

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('cookie');

        $this->layout = 'layout/master';

        $email = null;
        $token = null;
        if (isset($_SESSION['logged_in']) && isset($_SESSION['email']) && $_SESSION['logged_in'] == true) {
            $query = $this->db->query('SELECT * FROM user WHERE email = ?', array($_SESSION['email']));
            $user = $query->row();
            $this->user = $user;
        } else if (get_cookie('remember_me_email', true)) {

            $email = get_cookie('remember_me_email', true);
            $token = get_cookie('remember_me_token', true);

            $query = $this->db->query('SELECT * FROM user WHERE email = ? AND remember_me_token = ?', array($email, $token));
            $user = $query->row();
            if (isset($user) && ($user->remember_me_timestamp + 60 * 60 * 24 * 14) > round(microtime(true))) {
                $new_token = $this->get_token(32);
                $updated_user = array(
                    'remember_me_token' => $new_token,
                    'remember_me_timestamp' => round(microtime(true))
                );

                $this->db->where('id', $user->id);
                $this->db->update('user', $updated_user);

                $this->input->set_cookie('remember_me_token', $new_token, 60 * 60 * 24 * 14);

                $this->user = $user;

                $_SESSION['logged_in'] = true;
                $_SESSION['email'] = $user->email;
            }
        }

        if (isset($this->user)) {

            // logging
            $log = array(
                'user_id' => $this->user->id,
                'email' => $this->user->email,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'url' => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'timestamp' => round(microtime(true)),
				'role' => (!empty($this->user->role)?$this->user->role:"")
//				if ([$this->user->role] == ""){
//					'role' => 'NULL'
//				}
//				else {
//					'role' => $this->user->role	
//				}  
            );


            // resolve council for this user
            if (isset($this->user->council_id)) {
                $query = $this->db->query('SELECT * FROM council WHERE id = ?', array($this->user->council_id));
                $council = $query->row();
                if (isset($council)) {
                    $this->council = $council;
                    $log['council_id'] = $this->council->id;
                    $log['council_code'] = $this->council->code;
                }
            }

            // check if admin user
            if ($this->user->role == 'admin') {
                $this->admin = true;
            } else {
                $this->admin = false;
            }

            // log user activity
            $this->db->insert('activity_log', $log);
        }
    }

    private function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    protected function get_token($length=32){
        $token = "";
        $code_alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $code_alphabet.= "abcdefghijklmnopqrstuvwxyz";
        $code_alphabet.= "0123456789";
        for($i=0;$i<$length;$i++){
            $token .= $code_alphabet[$this->crypto_rand_secure(0,strlen($code_alphabet))];
        }
        return $token;
    }
}