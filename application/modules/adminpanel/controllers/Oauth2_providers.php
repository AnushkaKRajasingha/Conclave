<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OAuth2_providers extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('adminpanel/oauth2_providers_model');

        if (! self::check_permissions(7)) {
            redirect("/adminpanel/no_access");
        }
    }

    public function index() {
        $content_data['providers'] = $this->oauth2_providers_model->get_providers();
        $content_data['enabled'] = array('1' => 'Yes', '0' => 'No');
        $content_data['enabled_selected'] = '0';

        $this->quick_page_setup(
            $this->_theme,
            $this->_layout,
            $this->lang->line('oauth_providers_title'),
            'oauth2_providers',
            $this->_header,
            $this->_footer,
            '',
            $content_data);
    }

    /**
     *
     * action: used to handle both save and delete below
     *
     */

    public function action() {
        if (isset($_POST['delete'])) {
            $this->_delete();
        }else{ // delete needs to be sent or else it will always save, for example when hitting enter on keyboard
            $this->_save();
        }
    }

    /**
     *
     * _save: store provider data
     *
     */

    private function _save() {
        if ($this->input->post('id') != strval(intval($this->input->post('id')))) {
            redirect('/adminpanel/oauth2_providers');
        }

        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('order', $this->lang->line('provider_name'), 'trim|required|integer');
        $this->form_validation->set_rules('name', $this->lang->line('provider_name'), 'trim|required|max_length[50]|min_length[2]');
        $this->form_validation->set_rules('client_id', $this->lang->line('provider_client_id'), 'trim|required|max_length[255]|min_length[2]');
        $this->form_validation->set_rules('client_secret', $this->lang->line('provider_client_secret'), 'trim|required|max_length[255]|min_length[2]');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('/adminpanel/oauth2_providers');
            exit();
        }

        $data = array(
            'id' => $this->input->post('id'),
            'name' => $this->input->post('name'),
            'client_id' => $this->input->post('client_id'),
            'client_secret' => $this->input->post('client_secret'),
            'enabled' => $this->input->post('enabled') == 1 ? true : false,
            'order' => $this->input->post('order')
        );

        if ($this->oauth2_providers_model->save_provider($data)) {
            $this->session->set_flashdata('success', '<p>'. $this->lang->line('provider_saved') .'</p>');
        }

        redirect('/adminpanel/oauth2_providers');
    }

    /**
     *
     * _delete: remove provider data
     *
     */

    private function _delete() {
        if ($this->input->post('id') != strval(intval($this->input->post('id')))) {
            redirect('/adminpanel/oauth2_providers');
        }

        if ($this->oauth2_providers_model->delete_provider($this->input->post('id'))) {
            $this->session->set_flashdata('success', '<p>'. $this->lang->line('provider_deleted') .'</p>');
        }

        redirect('/adminpanel/oauth2_providers');
    }

}
