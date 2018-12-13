<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resend_activation extends Auth_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'send_email'));
        $this->load->library('form_validation');
        //$this->load->library('recaptcha');
        if (Settings_model::$db_config['recaptchav2_enabled'] == 1) {
            $this->load->library('recaptchaV2');
        }
        //$this->lang->load('recaptcha');
    }

    public function index() {
        $this->quick_page_setup(Settings_model::$db_config['active_theme'], 'main', $this->lang->line('resend_activation_title'), 'resend_activation', 'header', 'footer');
    }

    /**
     *
     * send_link: resend activation link
     *
     *
     */

    public function send_link() {
        // form input validation
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('email', $this->lang->line('resend_activation_email_address'), 'trim|required|is_valid_email');
        if (Settings_model::$db_config['recaptchav2_enabled'] == true) {
            //$this->form_validation->set_rules('recaptcha_response_field', 'captcha response field', 'required|check_captcha');
            // this is the Recaptcha V2 code, above is for V1 but it's commented out, same in login view
            $this->form_validation->set_rules('g-recaptcha-response', $this->lang->line('recaptchav2_response'), 'required|check_captcha');
        }

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('resend_activation');
            exit();
        }

        $this->load->model('auth/data_by_email_model');
        $data = $this->data_by_email_model->get_data_by_email($this->input->post('email'));

        if ($data['active']) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('account_active') .'</p>');
            redirect('resend_activation');

        }elseif (!empty($data['cookie_part'])) {

            // todo: create token here, too - probalby needs new helper

            $this->load->model('auth/resend_activation_model');
            $this->resend_activation_model->update_last_login($data['username']);
            $this->load->helper('send_email');
            $this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
            $this->email->from(Settings_model::$db_config['admin_email'], $_SERVER['HTTP_HOST']);
            $this->email->to($this->input->post('email'));
            $this->email->subject($this->lang->line('resend_activation_subject'));
            $this->email->message($this->lang->line('email_greeting') ." ". $data['username'] . $this->lang->line('resend_activation_message') . base_url() ."activate_account/check/". urlencode($this->input->post('email')) ."/". $data['cookie_part']);
            if ($this->email->send()) {
                $this->session->set_flashdata('success', '<p>'. $this->lang->line('resend_activation_success') .'</p>');
            }
            redirect('resend_activation');
        }else{
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('email_not_found') .'</p>');
        }

        $this->session->set_flashdata('email', $this->input->post('email'));
        redirect('resend_activation');
    }

}
