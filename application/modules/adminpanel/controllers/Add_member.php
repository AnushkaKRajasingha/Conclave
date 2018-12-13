<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add_member extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index() {

        // get all roles
        $this->load->model('utils/rbac_model');
        $content_data['roles'] = $this->rbac_model->get_roles();

        $this->template->set_js('big-min', base_url() .'assets/vendor/diceware/components/big.min.js');
        $this->template->set_js('special-min', base_url() .'assets/vendor/diceware/lists/special-min.js');
        $this->template->set_js('diceware-min', base_url() .'assets/vendor/diceware/lists/diceware-min.js');
        $this->template->set_js('eff', base_url() .'assets/vendor/diceware/lists/eff.js');
        $this->template->set_js('password-gen', base_url() .'assets/vendor/diceware/password_generator.js');

        $this->template->set_metadata('description', 'Add member');

        $this->quick_page_setup(
            $this->_theme,
            $this->_layout,
            $this->lang->line('add_member'),
            'add_member',
            $this->_header,
            $this->_footer,
            '',
            $content_data
        );
    }

    /**
     *
     * add: add member from post data.
     *
     */

    public function add() {

        if (! self::check_permissions(4)) {
            redirect("/adminpanel/no_access");
        }

        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('first_name', $this->lang->line('add_member_first_name'), 'trim|required|max_length[40]|min_length[2]');
        $this->form_validation->set_rules('last_name', $this->lang->line('add_member_last_name'), 'trim|required|max_length[60]|min_length[2]');
        $this->form_validation->set_rules('email', $this->lang->line('add_member_email_address'), 'trim|required|max_length[255]|is_valid_email|is_db_cell_available[user.email]');
        $this->form_validation->set_rules('username', $this->lang->line('add_member_username'), 'trim|required|max_length[24]|min_length[6]|is_valid_username|is_db_cell_available[user.username]');
        $this->form_validation->set_rules('password', $this->lang->line('add_member_password'), 'trim|required|max_length[255]|min_length[9]|is_valid_password');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('add_member_password_confirm'), 'trim|required|max_length[255]|min_length[9]|matches[password]');

        if (isset($_POST['roles'])) {
            foreach ($_POST['roles'] as $role) {
                $role = trim($role);
                if( ! $this->form_validation->is_natural_no_zero($role)) {
                    $this->session->set_flashdata('error', '<p>'. $this->lang->line('illegal_input') .'</p>');
                    redirect('adminpanel/add_member');
                }
            }
        }

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            $this->session->set_flashdata($_POST);
            redirect('/adminpanel/add_member');
            exit();
        }


        // create directory
        if (!file_exists(FCPATH .'assets/img/members/'. $this->input->post('username'))) {
            mkdir(FCPATH .'assets/img/members/'. $this->input->post('username'));
        }else{
            $this->session->set_flashdata('error', $this->lang->line('create_imgfolder_failed'));
            redirect('adminpanel/add_member');
        }

        // load membership model
        $this->load->model('auth/register_model');
        if($return_array = $this->register_model->create_member($this->input->post('username'), $this->input->post('password'), $this->input->post('email'), $this->input->post('first_name'), $this->input->post('last_name'))) {

            // set roles
            $this->load->model('utils/rbac_model');
            if (isset($_POST['roles'])) {
                foreach($_POST['roles'] as $role) {
                    $this->rbac_model->create_user_role(array('user_id' => $return_array['user_id'], 'role_id' => $role));
                }
            }
            // add default member role
            $this->rbac_model->create_user_role(array('user_id' => $return_array['user_id'], 'role_id' => 4));

            // send confirmation email
            $this->load->helper('send_email');
            $this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
            $this->email->from(Settings_model::$db_config['admin_email'], $_SERVER['HTTP_HOST']);
            $this->email->to($this->input->post('email'));
            $this->email->subject($this->lang->line('add_member_email_subject'));
            $this->email->message($this->lang->line('email_greeting') . " ". $this->input->post('username') . $this->lang->line('add_member_email_message'). base_url() ."auth/activate_account/check/". urlencode($this->input->post('email')) ."/". $return_array['cookie_part']);
            $this->email->send();
            $this->session->set_flashdata('success', '<p>'. $this->lang->line('add_member_created') .'</p>');
        }else{
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('add_member_unable') .'</p>');
        }
        redirect('/adminpanel/add_member');
    }

}