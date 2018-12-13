<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Private_Controller extends Site_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->lang->load('private');

        $this->output->set_header("HTTP/1.0 200 OK");
        $this->output->set_header("HTTP/1.1 200 OK");
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
        $this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

        if (!self::check_roles(1)) {
            if(Settings_model::$db_config['disable_all'] == 1) {
                $this->session->set_flashdata('error', '<p>'. $this->lang->line('site_disabled') .'</p>');
                redirect('login');
            }
        }

        $this->load->helper('cookie');
        $cookie_domain = config_item('cookie_domain');

        // get cookie data
        $cookie = get_cookie('unique_token');

        // no session data but cookie is set
        if ($this->session->userdata('user_id') == "") {

            if (!$cookie) {
                // no session data, no cookie data found: end now
                setcookie("unique_token", null, time() - 60*60*24*3, '/', $cookie_domain, false, false);
                redirect("login");
            }

            $cookie_part = substr($cookie, -32);

            // check cookie data
            $this->load->model('auth/login_model');
            $userData = $this->login_model->validate_login(null, null, false, true, $cookie_part);

            if (!empty($userData)) {
                // check banned and active
                if ($userData->banned == true) {
                    $this->session->set_flashdata('error', '<p>You are banned.</p>');
                    setcookie("unique_token", null, time() - 60*60*24*3, '/', $cookie_domain, false, false);
                    redirect("login");
                }elseif($userData->active == false) {
                    $this->session->set_flashdata('error', '<p>Your acount is inactive.</p>');
                    setcookie("unique_token", null, time() - 60*60*24*3, '/', $cookie_domain, false, false);
                    redirect("login");
                }

                // renew cookie
                setcookie("unique_token", get_cookie('unique_token'), time() + Settings_model::$db_config['cookie_expires'], '/', $cookie_domain, false, false);

                // set session data
                $this->load->helper('session');
                session_init($userData);

                // get permissions
                $this->permissions_roles($userData->user_id);

                redirect('membership/'. strtolower(Settings_model::$db_config['home_page']));
            }else{
                setcookie("unique_token", null, time() - 60*60*24*3, '/', $cookie_domain, false, false);
                redirect("login");
            }
        }
    }

}
