<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Private_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->lang->load('membership');
        $this->load->model('profile_model');
    }

    public function index() {

        // set content data
        $content_data = $this->profile_model->get_profile();

        $this->template->set_js('widget', base_url() .'assets/vendor/jquery/jquery.ui.widget.js');
        $this->template->set_js('upload', base_url() .'assets/vendor/fileupload/jquery.fileupload.js');

        $this->template->set_js('big-min', base_url() .'assets/vendor/diceware/components/big.min.js');
        $this->template->set_js('special-min', base_url() .'assets/vendor/diceware/lists/special-min.js');
        $this->template->set_js('diceware-min', base_url() .'assets/vendor/diceware/lists/diceware-min.js');
        $this->template->set_js('eff', base_url() .'assets/vendor/diceware/lists/eff.js');
        $this->template->set_js('password-gen', base_url() .'assets/vendor/diceware/password_generator.js');

        if ($glob = glob(FCPATH .'assets/img/members/'. $this->session->userdata('username') .'/*.{jpg,jpeg,png}', GLOB_BRACE)) {
            $content_data->profile_image = basename($glob[0]);
        }

        $content_data->picture_max_upload_size = Settings_model::$db_config['picture_max_upload_size'];

        $this->quick_page_setup(Settings_model::$db_config['adminpanel_theme'], 'adminpanel', $this->lang->line('profile_title'), 'profile', 'header', 'footer', '', $content_data);
    }

    /**
     *
     * update_account: change member info

     */

    public function update_account() {
        // form input validation
        if ($this->input->post('user_id') != strval(intval($this->input->post('user_id')))) {
            redirect('private/profile');
        }
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('first_name', $this->lang->line('profile_first_name'), 'trim|required|max_length[40]|min_length[2]');
        $this->form_validation->set_rules('last_name', $this->lang->line('profile_last_name'), 'trim|required|max_length[60]|min_length[2]');
        $this->form_validation->set_rules('email', $this->lang->line('profile_email_address'), 'trim|max_length[255]|is_valid_email|is_db_cell_available_by_id[user.email.'. $this->input->post('user_id') .'.user_id]');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('membership/profile');
            exit();
        }

        $this->profile_model->set_profile($this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('email'),$this->input->post('gender'));
        $this->session->set_flashdata('success', '<p>'. $this->lang->line('profile_updated') .'</p>');
        redirect('membership/profile');
        exit();
    }

    /**
     *
     * update_password: change member password
     *
     */

    public function update_password() {
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('current_password', $this->lang->line('profile_current_password'), 'trim|required|max_length[255]|is_member_password');
        $this->form_validation->set_rules('password', $this->lang->line('profile_new_password'), 'trim|required|max_length[255]|min_length[9]|is_valid_password');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('profile_new_password_repeat'), 'trim|required|max_length[255]|min_length[9]|matches[password]');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('pwd_error', validation_errors());
            redirect('membership/profile#profile_pwd_form');
            exit();
        }

        if ($this->profile_model->set_password($this->input->post('password'))) {
            if ($this->input->post('send_copy') != "") {
                $this->load->helper('send_email');
                $this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
                $this->email->from(Settings_model::$db_config['admin_email'], $_SERVER['HTTP_HOST']);
                $this->email->to($this->input->post('email'));
                $this->email->subject($this->lang->line('profile_email_subject'));
                $this->email->message($this->lang->line('email_greeting') ." ". $this->session->userdata('username') . $this->lang->line('profile_email_message') . addslashes($this->input->post('password')));
                $this->email->send();
            }
            $this->session->set_flashdata('pwd_success', '<p>'. $this->lang->line('profile_password_change_success') .'</p>');
        }
        redirect('membership/profile');
    }

    /**
     *
     * delete_account: change member password
     *
     */
	
	public function delete_account() {
		if ($this->session->userdata('username') == Settings_model::$db_config['root_admin_username']) {
			$this->session->set_flashdata('error', '<p>'. $this->lang->line('profile_admin_nodelete') .'</p>');
			redirect('membership/profile');
		}

		if ($this->profile_model->delete_membership()) {

            // delete img folders
            $path = FCPATH .'assets/img/members/'. $this->session->userdata('username');
            $this->load->helper("file"); // load the helper
            delete_files($path, true); // delete all files/folders
            rmdir($path); // remove member folder

			redirect("logout"); // logout controller destroys session and cookies
		}
		$this->session->set_flashdata('error', '<p>'. $this->lang->line('profile_remove_error') .'</p>');
		redirect('membership/profile');
	}

    /**
     *
     * upload_profile_picture
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

            // update user profile_img
            $this->profile_model->update_profile_img($upload_data['new_name']);

            // update session profile_img
            $this->session->set_userdata('profile_img', $upload_data['new_name']);

            echo json_encode(array('files' => $upload_data['files'], 'new_name' => $upload_data['new_name']));
            //delete_cookie('csrf_cookie_name');
        }else{
            echo false;
        }
    }

    /**
     *
     * delete_profile_picture
     *
     */

    public function delete_profile_picture() {

        $this->load->library('upload_profile_picture');

        if ($this->upload_profile_picture->delete($this->session->userdata('username'))) {
            $this->session->set_flashdata('success', '<p>Profile picture was successfully deleted.</p>');
            // reset user profile_img
            $this->profile_model->delete_profile_img();
        }else{
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('nothing_deleted') .'</p>');
        }

        redirect('membership/profile');
    }
    
    public function publicprofile($id){
    	
    	$data = $this->profile_model->get_publicprofile($id);
    	if ($glob = glob(FCPATH .'assets/img/members/'. $data->username.'/*.{jpg,jpeg,png}', GLOB_BRACE)) {
    		$data->profile_image = $glob[0];
    	}
    	$this->quick_page_setup(Settings_model::$db_config['adminpanel_theme'], 'adminpanel', $this->lang->line('profile_title'), 'userprofile', 'header', 'footer', '', $data);
    }

}