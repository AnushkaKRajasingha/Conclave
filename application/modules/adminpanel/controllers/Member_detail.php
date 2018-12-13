<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_detail extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        // pre-load
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('adminpanel/member_detail_model');
    }

    public function _remap($method, $params = array()) {

        if (method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $params);
        }

        if (! $this->form_validation->is_natural_no_zero($this->uri->segment(3))) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('illegal_request') .'</p>');
            redirect('adminpanel/list_members');
        }

        if (! self::check_permissions(1)) {
            redirect("/adminpanel/no_access");
        }

        $content_data['member'] = $this->member_detail_model->get_member_data($this->uri->segment(3));

        $this->load->model('utils/rbac_model');
        $content_data['roles'] = $this->rbac_model->get_roles();
        $content_data['member_roles'] = $this->rbac_model->get_member_roles($this->uri->segment(3));

        if (! $content_data['member']) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('illegal_request') .'</p>');
            redirect('adminpanel/list_members');
        }

        $this->template->set_js('widget', base_url() .'assets/vendor/jquery/jquery.ui.widget.js');
        $this->template->set_js('upload', base_url() .'assets/vendor/fileupload/jquery.fileupload.js');

        $this->template->set_js('big-min', base_url() .'assets/vendor/diceware/components/big.min.js');
        $this->template->set_js('special-min', base_url() .'assets/vendor/diceware/lists/special-min.js');
        $this->template->set_js('diceware-min', base_url() .'assets/vendor/diceware/lists/diceware-min.js');
        $this->template->set_js('eff', base_url() .'assets/vendor/diceware/lists/eff.js');
        $this->template->set_js('password-gen', base_url() .'assets/vendor/diceware/password_generator.js');

        if ($glob = glob(FCPATH .'assets/img/members/'. $content_data['member']->username .'/*.{jpg,jpeg,png}', GLOB_BRACE)) {
            $content_data['profile_image'] = basename($glob[0]);
        }

        $content_data['picture_max_upload_size'] = Settings_model::$db_config['picture_max_upload_size'];

        $this->quick_page_setup(
            $this->_theme,
            $this->_layout,
            $this->lang->line('member_detail'),
            'member_detail',
            $this->_header,
            $this->_footer,
            '',
            $content_data);

        return $this;
    }

    /**
     *
     * save: store data about member
     *
     */

    public function save() {

        if (! self::check_permissions(5)) {
            redirect("/adminpanel/no_access");
        }

        if ($this->input->post('old_username') == Settings_model::$db_config['root_admin_username']) {

            if ($this->input->post('username') != Settings_model::$db_config['root_admin_username']) {
                $this->session->set_flashdata('error', $this->lang->line('root_admin_username_noedit'));
                redirect("/adminpanel/member_detail/". $this->input->post('user_id'));
            }

            if ($this->input->post('banned') == true) {
                $this->session->set_flashdata('error', $this->lang->line('root_admin_noban'));
                redirect("/adminpanel/member_detail/". $this->input->post('user_id'));
            }

            if ($this->input->post('active') == false) {
                $this->session->set_flashdata('error', $this->lang->line('root_admin_nodeactivate'));
                redirect("/adminpanel/member_detail/". $this->input->post('user_id'));
            }

            if(!isset($_POST["roles"]) || !in_array(1, $_POST["roles"])) {
                $this->session->set_flashdata('error', $this->lang->line('root_admin_minimum_role'));
                redirect("/adminpanel/member_detail/". $this->input->post('user_id'));
            }

        }

        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('user_id', 'user_id', 'trim|required');
        $this->form_validation->set_rules('username', $this->lang->line('member_detail_username'), 'trim|required|max_length[24]|min_length[6]|is_valid_username|is_db_cell_available_by_id[user.username.'. $this->input->post('user_id') .'.user_id]');
        $this->form_validation->set_rules('email', $this->lang->line('member_detail_email_address'), 'trim|required|max_length[255]|is_valid_email|is_db_cell_available_by_id[user.email.'. $this->input->post('user_id') .'.user_id]');
        $this->form_validation->set_rules('first_name', $this->lang->line('member_detail_first_name'), 'trim|required|max_length[40]|min_length[2]');
        $this->form_validation->set_rules('last_name', $this->lang->line('member_detail_last_name'), 'trim|required|max_length[60]|min_length[2]');
        $this->form_validation->set_rules('banned', $this->lang->line('banned'), 'trim|required|is_natural');
        $this->form_validation->set_rules('active', $this->lang->line('activated'), 'trim|required|is_natural');
        $this->form_validation->set_rules('password', $this->lang->line('member_detail_new_password'), 'trim|max_length[255]|min_length[9]|is_valid_password');

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
            redirect('/adminpanel/member_detail/'. $this->input->post('user_id'));
        }

        $data = array(
            'user_id' => $this->input->post('user_id'),
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'banned' => ($this->input->post('banned') == true ? true : false),
            'active' => ($this->input->post('active') == true ? true : false)
        );
		
		if ($this->input->post('password') != "") {
			$data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
		}

        // rename username folder
        // grab username, check if same as post data
        // if its the same then do nothing
        // else rename user folder
        if (!file_exists(FCPATH .'assets/img/members/'. $this->input->post('username'))) {
            // get current username
            $user = $this->member_detail_model->get_username();

            if ($user) {
                $old_username = $user->username;
            }
            rename(FCPATH .'assets/img/members/'. $old_username, FCPATH .'assets/img/members/'. $this->input->post('username'));
        }

        $this->db->trans_start();

        // check permission changes
        // check all permissions and delete or insert accordingly depending on selected or not (updating will be too complex)
        $this->load->model('utils/rbac_model');

        if (!isset($_POST['roles'])) {
            // remove all roles except id 4
            $this->rbac_model->delete_user_roles($this->input->post('user_id'));
        }else{
            // verify current roles: we might need to add and remove roles
            
            // prepare delete data
            $delete_arr = $_POST['roles'];
            $delete_arr[] = "4";

            // get member roles
            $roles = $this->rbac_model->get_member_roles($this->input->post('user_id'));

            // check each selected role against a set of exisiting roles
            foreach($_POST['roles'] as $selected_role) {
                $found = false;
                foreach ($roles as $role) {
                    //print $selected_role ."<br>";
                    if ($selected_role == $role->role_id) {
                        // selected role already exists, do nothing
                        $found = true;
                    }
                }

                if (!$found) {
                    // role is not found but checked, add new role to member
                    $this->rbac_model->add_role_to_member($selected_role);
                }
            }

            // delete unchecked roles
            $this->rbac_model->delete_unchecked_roles($delete_arr);
        }

        // save profile data
        $this->member_detail_model->save($data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            $this->session->set_flashdata('error', 'Member save aborted (DB transaction failed).');
        }

        // send email if checked
        if ($this->input->post('send_copy') != "") {
            $this->load->helper('send_email');
            $this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
            $this->email->from(Settings_model::$db_config['admin_email'], $_SERVER['HTTP_HOST']);
            $this->email->to($this->input->post('email'));
            $this->email->subject($this->lang->line('member_detail_edited_subject'));
            $this->email->message($this->lang->line('member_detail_edited_msg'));
            $this->email->send();
        }

        $this->session->set_flashdata('success', sprintf($this->lang->line('member_detail_updated'), $this->input->post('username'), $this->input->post('user_id')));

        redirect('/adminpanel/member_detail/'. $this->input->post('user_id'));
    }

    /**
     *
     * upload_profile_picture: setting a new profile picture through AJAX contacting the Gargron-fileupload library
     *
     * @param string $username the username of the member that needs a new profile picture
     *
     */

    public function upload_profile_picture($username) {

        $this->load->library('upload_profile_picture');

        if ($this->input->is_ajax_request()) {

            // validation
            if (!$this->form_validation->is_valid_username($username)) {
                exit(); // silent death, the username was possibly forged, falsified
            }

            $upload_data = $this->upload_profile_picture->upload($username);

            // update session profile_img but only if this person is logged in atm!
            if ($username == $this->session->userdata('username')) {
                $this->session->set_userdata('profile_img', $upload_data['new_name']);
            }

            // update user profile_img
            $this->member_detail_model->update_profile_img($upload_data['new_name'], $username);

            echo json_encode(array('files' => $upload_data['files'], 'new_name' => $upload_data['new_name']));
        }else{
            echo false;
        }
    }

    /**
     *
     * delete_profile_picture: remove current profile picture
     *
     * @param string $username the username of the member that needs a new profile picture
     * @param int $user_id used to know where to redirect to after performing delete operation
     *
     */

    public function delete_profile_picture($username, $user_id) {

        $this->load->library('upload_profile_picture');

        if ($this->upload_profile_picture->delete($username)) {
            $this->session->set_flashdata('success', '<p>Profile picture was successfully deleted.</p>');
            // reset user profile_img

            $this->member_detail_model->delete_profile_img();
        }else{
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('nothing_deleted') .'</p>');
        }

        redirect('adminpanel/member_detail/'. $user_id);
    }

}