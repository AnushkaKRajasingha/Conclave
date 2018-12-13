<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->session->sess_destroy();
        $this->load->helper('cookie');
        delete_cookie('unique_token');
        redirect('login');
    }

    public function index() {
        redirect('login');
    }

}

