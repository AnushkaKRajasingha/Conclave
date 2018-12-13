<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Retrieve_username extends Auth_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        //$this->load->library('recaptcha');
        if (Settings_model::$db_config['recaptchav2_enabled'] == 1) {
            $this->load->library('recaptchaV2');
        }
        //$this->lang->load('recaptcha');
    }

    public function index() {
        $this->quick_page_setup(Settings_model::$db_config['active_theme'], 'main', $this->lang->line('retrieve_username_title'), 'retrieve_username', 'header', 'footer');
    }

    /**
     *
     * send_username: send the username to the member e-mail
     *
     *
     */

    public function send_username() {
        // form input validation
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('email', $this->lang->line('retrieve_username_email_address'), 'trim|required|is_valid_email');
        if (Settings_model::$db_config['recaptcha_enabled'] == true) {
            //$this->form_validation->set_rules('recaptcha_response_field', 'captcha response field', 'required|check_captcha');
            // this is the Recaptcha V2 code, above is for V1 but it's commented out, same in login view
            $this->form_validation->set_rules('g-recaptcha-response', $this->lang->line('recaptchav2_response'), 'required|check_captcha');
        }

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('retrieve_username');
            exit();
        }

        $this->load->model('auth/data_by_email_model');
        $data = $this->data_by_email_model->get_data_by_email($this->input->post('email'));

        if (!$data['active']) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('account_is_active') .'</p>');
            redirect('retrieve_username');
        }elseif (!empty($data['cookie_part'])) {
            $this->load->helper('send_email');
            $this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
            $this->email->from(Settings_model::$db_config['admin_email'], $_SERVER['HTTP_HOST']);
            $this->email->to($this->input->post('email'));
            $this->email->subject($this->lang->line('forgot_username_subject'));
            $this->email->message($this->lang->line('email_greeting') . $this->lang->line('forgot_username_message') . $data['username']);
            $this->email->send();
            $this->session->set_flashdata('success', '<p>'. $this->lang->line('forgot_username_success') .'</p>');
            redirect('retrieve_username');
        }else{
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('email_not_found') .'</p>');
        }

        $this->session->set_flashdata('email', $this->input->post('email'));
        redirect('retrieve_username');
    }

}
