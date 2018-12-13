<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Renew_password extends Auth_Controller {

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
        $this->quick_page_setup(Settings_model::$db_config['active_theme'], 'main', $this->lang->line('renew_password_title'), 'renew_password', 'header', 'footer');
    }

    /**
     *
     * send_password: send the reset member password link
     *
     *
     */

    public function send_password() {
        // form input validation
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('email', $this->lang->line('renew_password_email_address'), 'trim|required|is_valid_email');
        if (Settings_model::$db_config['recaptchav2_enabled'] == true) {
            //$this->form_validation->set_rules('recaptcha_response_field', 'captcha response field', 'required|check_captcha');
            // this is the Recaptcha V2 code, above is for V1 but it's commented out, same in login view
            $this->form_validation->set_rules('g-recaptcha-response', $this->lang->line('recaptchav2_response'), 'required|check_captcha');
        }

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('renew_password');
            exit();
        }

        $this->load->model('auth/data_by_email_model');
        $data = $this->data_by_email_model->get_data_by_email($this->input->post('email'));

        if (isset($data['active']) && $data['active'] != 1) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('account_is_active') .'<p>');
            redirect('renew_password');

        }elseif (!empty($data['cookie_part'])) {

            // create a token from the received data to use in the email
            $token = hash_hmac('ripemd160', md5($data['cookie_part'] . uniqid(mt_rand(), true)), SITE_KEY);
            $this->load->model('auth/renew_password_model');

            // first remove old tokens
            $this->renew_password_model->delete_tokens_by_email($this->input->post('email'));

            if ($this->renew_password_model->insert_recover_password_data($data['user_id'], $token, $this->input->post('email'))) {
                $this->load->helper('send_email');
                $this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
                $this->email->from(Settings_model::$db_config['admin_email'], $_SERVER['HTTP_HOST']);
                $this->email->to($this->input->post('email'));
                $this->email->subject($this->lang->line('renew_password_email_subject'));
                $this->email->message($this->lang->line('email_greeting') ." ". $data['username'] . $this->lang->line('renew_password_message') ."\r\n\r\n". base_url() ."new_password/". urlencode($this->input->post('email')) ."/". $token);
                $this->email->send();
                $this->session->set_flashdata('success', '<p>'. $this->lang->line('renew_password_success') .'</p>');
            }else{
                $this->session->set_flashdata('error', '<p>'. $this->lang->line('renew_password_failed_db') .'</p>');
            }

            redirect('renew_password');
        }else{
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('email_not_found') .'</p>');
        }

        $this->session->set_flashdata('email', $this->input->post('email'));
        redirect('renew_password');
    }

}
