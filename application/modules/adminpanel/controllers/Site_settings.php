<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_settings extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('encrypt');
        $this->load->library('form_validation');
        $this->lang->load('site_settings');
    }

    public function index() {

        if (! self::check_permissions(3)) {
            redirect("/adminpanel/no_access");
        }

        $content_data['private_pages'] = $this->_load_membership_pages();

        $this->quick_page_setup(
            $this->_theme,
            $this->_layout,
            $this->lang->line('site_settings_title'),
            'site_settings',
            $this->_header,
            $this->_footer,
            '',
            $content_data);
    }

    /**
     *
     * _load_membership_pages: loads all the pages from the membership module to make them selectable in the site settings page
     *
     */

    private function _load_membership_pages() {
        if ($handle = opendir(APPPATH. 'modules/membership/controllers')) {
            $pages = array();
            while (false !== ($file = readdir($handle))) {
                $last_four = substr($file, -4);
                $newfile = str_replace(".php", "", $file);
                if ($last_four == ".php") {
                    $pages[$newfile] = strtolower(str_replace("_", " ", $newfile));
                }
            }

            closedir($handle);

            return $pages;
        }

        return false;
    }

    /**
     *
     * update_settings: save all settings
     *
     *
     */

    public function update_settings() {
        if (! self::check_permissions(11)) {
            redirect('/adminpanel/site_settings');
        }

        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('site_title', $this->lang->line('site_title'), 'trim|required|max_length[60]');
        $this->form_validation->set_rules('members_per_page', $this->lang->line('members_per_page'), 'trim|required|numeric');
        $this->form_validation->set_rules('admin_email', $this->lang->line('admin_email'), 'trim|required|max_length[255]|is_valid_email');
        $this->form_validation->set_rules('home_page', $this->lang->line('post_login_page'), 'trim|required|max_length[50]');
        $this->form_validation->set_rules('active_theme', $this->lang->line('active_theme'), 'trim|required|max_length[40]');
        $this->form_validation->set_rules('adminpanel_theme', $this->lang->line('adminpanel_theme'), 'trim|required|max_length[40]');
        $this->form_validation->set_rules('login_attempts', $this->lang->line('login_attempts_trigger'), 'trim|required|numeric');
        $this->form_validation->set_rules('max_login_attempts', $this->lang->line('max_login_attempts'), 'trim|required|numeric');
        $this->form_validation->set_rules('sendmail_path', $this->lang->line('sendmail_path'), 'trim');
        $this->form_validation->set_rules('email_protocol', $this->lang->line('sendmail_path'), 'trim');
        $this->form_validation->set_rules('smtp_host', $this->lang->line('smtp_host'), 'trim');
        $this->form_validation->set_rules('smtp_port', $this->lang->line('smtp_port'), 'trim');
        $this->form_validation->set_rules('smtp_user', $this->lang->line('smtp_user'), 'trim');
        $this->form_validation->set_rules('smtp_pass', $this->lang->line('smtp_pass'), 'trim');
        $this->form_validation->set_rules('cookie_expires', $this->lang->line('cookie_expiration'), 'trim|required|numeric');
        $this->form_validation->set_rules('password_link_expires', $this->lang->line('password_link_expiration'), 'trim|required|numeric');
        $this->form_validation->set_rules('activation_link_expires', $this->lang->line('activation_link_expiration'), 'trim|required|numeric');
        $this->form_validation->set_rules('site_disabled_text', $this->lang->line('disabled_text'), 'trim');
        $this->form_validation->set_rules('recaptchav2_site_key', $this->lang->line('site_key'), 'trim|max_length[40]');
        $this->form_validation->set_rules('recaptchav2_secret', $this->lang->line('site_secret'), 'trim|max_length[40]');
        $this->form_validation->set_rules('picture_max_upload_size', $this->lang->line('picture_max_upload_size'), 'trim|numeric|max_length[10]');
        $this->form_validation->set_rules('allow_login_by_email', $this->lang->line('allow_login_by_email'), 'trim|alpha');
        $this->form_validation->set_rules('max_noof_post', $this->lang->line('max_noof_post_title'), 'trim|required|numeric');
        $this->form_validation->set_rules('max_noof_con', $this->lang->line('max_noof_con_title'), 'trim|required|numeric');
        $this->form_validation->set_rules('max_noof_point', $this->lang->line('max_noof_point_title'), 'trim|required|numeric');
        $this->form_validation->set_rules('min_noof_point', $this->lang->line('min_noof_point_title'), 'trim|required|numeric');
        $this->form_validation->set_rules('min_noof_pointtocon', $this->lang->line('min_noof_pointtocon_title'), 'trim|required|numeric');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('/adminpanel/site_settings');
        }


        // recaptcha enabled with empty fields?
        if ($this->input->post('recaptchav2_enabled') != "" && ($this->input->post('recaptchav2_site_key') == "" || $this->input->post('recaptchav2_secret') == "")) {
            $this->session->set_flashdata('error', 'Please fill out both recaptcha fields.');
            redirect('/adminpanel/site_settings');
        }

        $active_theme = FALSE;
        $list = glob(APPPATH .'views/themes/'. $this->input->post('active_theme') .'/layouts/*.php');
        if (!empty($list)) {
            $active_theme = TRUE;
        }else{
            $this->session->set_flashdata('error', '<p>'. sprintf($this->lang->line('main_not_found'), $this->input->post('active_theme')) .'</p>');
            redirect('/adminpanel/site_settings');
            exit();
        }

        $adminpanel_theme = FALSE;
        if (file_exists(APPPATH .'views/themes/'. $this->input->post('adminpanel_theme') .'/layouts/adminpanel.php')) {
            $adminpanel_theme = TRUE;
        }else{
            $this->session->set_flashdata('error', '<p>'. sprintf($this->lang->line('main_not_found'), $this->input->post('adminpanel_theme')) .'</p>');
            redirect('/adminpanel/site_settings');
            exit();
        }

        $home_page = FALSE;
        if (file_exists(APPPATH .'modules/membership/controllers/'. $this->input->post('home_page') .'.php')) {
            $home_page = TRUE;
        }else{
            $this->session->set_flashdata('error', '<p>'. sprintf($this->lang->line('controller_not_found'), $this->input->post('home_page')) .'</p>');
            redirect('/adminpanel/site_settings');
            exit();
        }

        // delete cache before prepping and inserting data
        $this->cache->delete('settings');

        $data = array(
            'site_title' => $this->input->post('site_title'),
            'login_enabled' => ($this->input->post('login_enabled') == "" ? 1 : 0),
            'register_enabled' => ($this->input->post('register_enabled') == "" ? 1 : 0),
            'members_per_page' => ($this->input->post('members_per_page') > 0 ? $this->input->post('members_per_page') : 10),
            'admin_email' => $this->input->post('admin_email'),
            'home_page' => ($home_page == TRUE ? $this->input->post('home_page') : strtolower(Settings_model::$db_config['home_page'])),
            'previous_url_after_login' => ($this->input->post('previous_url_after_login') == "" ? 0 : 1),
            'active_theme' => ($active_theme == TRUE ? $this->input->post('active_theme') : Settings_model::$db_config['active_theme']),
            'adminpanel_theme' => ($adminpanel_theme == TRUE ? $this->input->post('adminpanel_theme') : Settings_model::$db_config['adminpanel_theme']),
            'login_attempts' => $this->input->post('login_attempts'),
            'max_login_attempts' => $this->input->post('max_login_attempts'),
            'email_protocol' => $this->input->post('email_protocol'),
            'sendmail_path' => $this->input->post('sendmail_path'),
            'smtp_host' => $this->input->post('smtp_host'),
            'smtp_port' => $this->input->post('smtp_port'),
            'smtp_user' => $this->encrypt->encode($this->input->post('smtp_user')),
            'smtp_pass' => $this->encrypt->encode($this->input->post('smtp_pass')),
            'cookie_expires' => $this->input->post('cookie_expires'),
            'password_link_expires' => $this->input->post('password_link_expires'),
            'activation_link_expires' => $this->input->post('activation_link_expires'),
            'disable_all' => ($this->input->post('disable_all') == "" ? 0 : 1),
            'site_disabled_text' => $this->input->post('site_disabled_text'),
            'remember_me_enabled' => ($this->input->post('remember_me_enabled') != "" ? true : false),
            'recaptchav2_enabled' => ($this->input->post('recaptchav2_enabled') != "" ? true : false),
            'recaptchav2_site_key' => $this->input->post('recaptchav2_site_key'),
            'recaptchav2_secret' => $this->input->post('recaptchav2_secret'),
            'oauth2_enabled' => ($this->input->post('oauth2_enabled') == "" ? 0 : 1),
            'picture_max_upload_size' => $this->input->post('picture_max_upload_size'),
            'allow_login_by_email' => ($this->input->post('allow_login_by_email') != "" ? true : false),
        	'max_noof_post' => ($this->input->post('max_noof_post')),
        		'max_noof_con' => ($this->input->post('max_noof_con')),
        		'max_noof_point' => ($this->input->post('max_noof_point')),
        		'min_noof_point' => ($this->input->post('min_noof_point')),
        		'min_noof_pointtocon' => ($this->input->post('min_noof_pointtocon')) 
        );

        $this->load->model('adminpanel/site_settings_model');
        if ($this->site_settings_model->save_settings($data)) {
            $this->session->set_flashdata('success', '<p>'. $this->lang->line('settings_update') .'</p>');
            //unlink(APPPATH .'cache/settings.cache');
            $this->load->library('cache');
            $this->cache->delete('settings');
        }

        redirect('/adminpanel/site_settings');
    }

    /**
     *
     * clear_sessions: force relogins for all users by clearing the sessions table (except for the main admin account)
     *
     */

    public function clear_sessions() {
        if (! self::check_permissions(12)) {
            redirect('/adminpanel/site_settings');
        }
        $this->load->model('adminpanel/site_settings_model');
        if ($this->site_settings_model->clear_sessions()) {
            $this->session->set_flashdata('sessions_message', '<p>'. $this->lang->line('sessions_cleared') .'</p>');
        }else{
            $this->session->set_flashdata('sessions_message', '<p>'. $this->lang->line('sessions_not_cleared') .'</p>');
        }

        redirect('/adminpanel/site_settings#clear_sessions');
    }

}