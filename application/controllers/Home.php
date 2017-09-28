<?php

class Home extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
    }

    public function index()
    {
        if (!isset($this->user)) {
            redirect('/account/login');
            return;
        }

        if ($this->admin) {

			$query = $this->db->query("SELECT * FROM council");
            $council_data = array(
                'councils' => $query->result_array()
            );
			$query = $this->db->query("SELECT * FROM user WHERE role <> 'admin' OR role IS NULL");
            $user_data = array(
                'users' => $query->result_array()
            );

			$query = $this->db->query("SELECT * FROM activity_log WHERE role <> 'admin' OR role IS NULL");
            $activity_data = array(
                'activities' => $query->result_array()
            );

            $this->load->view('councils', array_merge($council_data, $user_data, $activity_data));
            return;
        } else if (!isset($this->council)) {
            echo 'There was an error trying to access the site with your account.';
            return;
        } else {
            redirect('/view/'.$this->council->code);
            return;
        }
    }
}
