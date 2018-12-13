<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_Controller extends Site_Controller
{

    public function __construct ()
    {
        parent::__construct();

        $this->lang->load('auth');

        $this->load->helper('cookie');

        if ($this->session->userdata('user_id') != "" || get_cookie('unique_token') != "") {
            redirect('membership/'. strtolower(Settings_model::$db_config['home_page']));
        }

    }

}