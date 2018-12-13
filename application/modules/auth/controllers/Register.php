<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends Auth_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
       // $this->load->library('recaptcha');
        //$this->lang->load('recaptcha');
        if (Settings_model::$db_config['recaptchav2_enabled'] == true) {
            $this->load->library('recaptchaV2');
        }
    }

    public function index() {
        $data = array();

        // if OAuth2 enabled
        if (Settings_model::$db_config['oauth_enabled']) {
            // generate all active OAuth2 Providers
            $this->load->model('auth/Oauth_model');
            $data['providers'] = $this->Oauth_model->get_all_providers();
        }

        $this->template->set_js('big-min', base_url() .'assets/vendor/diceware/components/big.min.js');
        $this->template->set_js('special-min', base_url() .'assets/vendor/diceware/lists/special-min.js');
        $this->template->set_js('diceware-min', base_url() .'assets/vendor/diceware/lists/diceware-min.js');
        $this->template->set_js('eff', base_url() .'assets/vendor/diceware/lists/eff.js');
        $this->template->set_js('password-gen', base_url() .'assets/vendor/diceware/password_generator.js');

        $this->quick_page_setup(Settings_model::$db_config['active_theme'], 'main', $this->lang->line('register_title'), 'register', 'header', 'footer', '', $data);
    }

    /**
     *
     * add_member: insert a new member into the database after all input fields have met the requirements
     *
     *
     */

    public function add_member() {
        // check whether creating member is allowed
        if (Settings_model::$db_config['register_enabled'] == 0) {
            $this->session->set_flashdata('error', $this->lang->line('registration_disabled'));
            redirect('register');
        }

        // form input validation
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('first_name', $this->lang->line('register_first_name'), 'trim|required|max_length[40]|min_length[2]');
        $this->form_validation->set_rules('last_name', $this->lang->line('register_last_name'), 'trim|required|max_length[60]|min_length[2]');
        $this->form_validation->set_rules('email', $this->lang->line('register_email_address'), 'trim|required|max_length[255]|is_valid_email|is_db_cell_available[user.email]');
        $this->form_validation->set_rules('username', $this->lang->line('register_username'), 'trim|required|max_length[24]|min_length[6]|is_valid_username|is_db_cell_available[user.username]');
        $this->form_validation->set_rules('password', $this->lang->line('register_password'), 'trim|required|max_length[255]|min_length[9]|is_valid_password');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('register_password_confirm'), 'trim|required|max_length[255]|min_length[9]|matches[password]');
        if (Settings_model::$db_config['recaptchav2_enabled'] == true) {
            //$this->form_validation->set_rules('recaptcha_response_field', 'captcha response field', 'required|check_captcha'); // captcha v1
            $this->form_validation->set_rules('g-recaptcha-response', $this->lang->line('recaptchav2_response'), 'required|check_captcha');
        }

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            $data['post'] = $_POST;
            $this->session->set_flashdata($data['post']);
            redirect('register');
        }

        $this->load->model('auth/register_model');

        if ($return_array = $this->register_model->create_member($this->input->post('username'), $this->input->post('password'), $this->input->post('email'), $this->input->post('first_name'), $this->input->post('last_name'))) {

            // add default member role
            $this->load->model('utils/rbac_model');
            $this->rbac_model->create_user_role(array('user_id' => $return_array['user_id'], 'role_id' => 4));

            // create directory
            if (!file_exists(FCPATH .'assets/img/members/'. $this->input->post('username'))) {
                mkdir(FCPATH .'assets/img/members/'. $this->input->post('username'));
            }else{
                $this->session->set_flashdata('error', $this->lang->line('create_imgfolder_failed'));
                redirect('register');
            }

            // send email
            $this->load->helper('send_email');
            $this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
            $this->email->from(Settings_model::$db_config['admin_email'], $_SERVER['HTTP_HOST']);
            $this->email->to($this->input->post('email'));
            $this->email->subject($this->lang->line('register_email_subject'));
            $this->email->message($this->lang->line('email_greeting') . " ".
                $this->input->post('username') .
                $this->lang->line('register_email_message').
                base_url() ."activate_account/check/".
                urlencode($this->input->post('email')) ."/". $return_array['cookie_part']);
            $this->email->send();
            $this->session->set_flashdata('success', '<p>'. $this->lang->line('register_email_success') .'</p>');

            redirect('register');

        }else{
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('register_failed_db') .'</p>');
            redirect('register');
        }
    }
    
}
