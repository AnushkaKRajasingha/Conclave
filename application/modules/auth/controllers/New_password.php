<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class New_password extends Auth_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function _remap($method, $params = array()) {

        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $params);
        }

        // email validation
        if (! $this->form_validation->is_valid_email(urldecode($this->uri->segment(2)))) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('new_password_error_email') .'</p>');
            redirect('login');
        }

        // token validation
        if (! $this->form_validation->alpha_numeric($this->uri->segment(3))
            || !$this->form_validation->exact_length($this->uri->segment(3), 40)) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('new_password_error_token') .'</p>');
            redirect ('login');
        }

        $this->db->select('user_id, token')->from(DB_PREFIX .'recover_password')
            ->where(array(
                    'token' => $this->uri->segment(3),
                    'email' => urldecode($this->uri->segment(2))
                )
            );
        $q = $this->db->get();

        if ($q->num_rows() != 1) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('new_password_error_db') .'</p>');
            redirect('login');
        }

        $this->template->set_js('big-min', base_url() .'assets/vendor/diceware/components/big.min.js');
        $this->template->set_js('special-min', base_url() .'assets/vendor/diceware/lists/special-min.js');
        $this->template->set_js('diceware-min', base_url() .'assets/vendor/diceware/lists/diceware-min.js');
        $this->template->set_js('eff', base_url() .'assets/vendor/diceware/lists/eff.js');
        $this->template->set_js('password-gen', base_url() .'assets/vendor/diceware/password_generator.js');

        $content_data['token'] = $q->row()->token;

        // set a flashdata to avoid abuse
        $this->session->set_flashdata('temp_user_id', $q->row()->user_id);
        $this->session->set_flashdata('temp_token', $content_data['token']);

        $this->quick_page_setup(Settings_model::$db_config['active_theme'], 'main',  $this->lang->line('new_password_title'), 'new_password', 'header', 'footer', '', $content_data);

    }

    public function change_password() {

        // check flashdata
        if ($this->session->flashdata('temp_token') == "" || $this->session->flashdata('temp_user_id') == "") {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('new_password_no_flash') .'</p>');
            redirect('login');
        }

        // form input validation
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('password', $this->lang->line('new_password_password'), 'trim|required|max_length[255]|min_length[9]|is_valid_password');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('login');
        }

        $this->load->model('new_password_model');
        if ($this->new_password_model->change_password($this->input->post('password'))) {
            $this->session->set_flashdata('success', $this->lang->line('new_password_done'));
        }else{
            $this->session->set_flashdata('error', $this->lang->line('new_password_fail'));
        }

        redirect('login');



        // actual method the new password form goes to
        // make sure to check token here, also! so this means a new helper? or just a private function here
        // use is_valid_password
    }

}



