<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OAuth1 extends Auth_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    /**
     *
     * init: prepare OAuth login and redirect to external app if checks pass
     *
     * @param string $provider
     *
     */

    public function init($provider) {

        if (!$this->form_validation->alpha_numeric($provider)) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('oauth1_illegal_provider_name') .'</p>');
            redirect('login');
        }

        if (!Settings_model::$db_config['oauth_enabled']) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('oauth1_social_login_disabled') .'</p>');
            redirect('login');
        }

        $this->load->model('oauth_model');
        if (!$providerData = $this->oauth_model->get_provider_data($provider)) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('oauth1_illegal_provider_init') .'</p>');
            redirect('login');
        }

        if(!$providerData->enabled) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('oauth1_provider_disabled') .'</p>');
            redirect('login');
        }

        require APPPATH . 'vendor/PHPLeague-OAuth1/autoload.php';

        // Create server
        $this->load->library('OAuth1/'. $provider);
        $server = $this->{strtolower($provider)}->setProvider($providerData);

        // First part of OAuth 1.0 authentication is retrieving temporary credentials.
        // These identify you as a client to the server.
        $temporaryCredentials = $server->getTemporaryCredentials();
        // Store the credentials in the session.
        $this->session->set_userdata('temporary_credentials', serialize($temporaryCredentials));

        // Second part of OAuth 1.0 authentication is to redirect the
        // resource owner to the login screen on the server.
        $server->authorize($temporaryCredentials);
    }

    public function verify($provider) {

        // checking get variable existence
        if (!isset($_GET['oauth_token']) || ! isset($_GET['oauth_verifier'])) {
            $this->session->set_flashdata('error', $this->lang->line('oauth1_invalid_session'));
            redirect('/login');
        }

        // checking for empty session data to avoid abuse
        if ($this->session->userdata('temporary_credentials') == "") {
            $this->session->set_flashdata('error', $this->lang->line('oauth1_invalid_session'));
            redirect('/login');
        }

        // basic validation for $provider
        if (!$this->form_validation->alpha_numeric($provider)) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('oauth1_illegal_provider_name') .'</p>');
            redirect('login');
        }

        // check site settings
        if(! Settings_model::$db_config['login_enabled']) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('login_disabled') .'</p>');
            redirect('login');
        }elseif (! Settings_model::$db_config['oauth_enabled']) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('oauth1_login_disabled') .'</p>');
            redirect('login');
        }

        $this->load->model('oauth_model');
        if (!$providerData = $this->oauth_model->get_provider_data($provider)) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('oauth1_illegal_provider_init') .'</p>');
            redirect('login');
        }

        require APPPATH . 'vendor/PHPLeague-OAuth1/autoload.php';

        // Create server
        $this->load->library('OAuth1/'. $provider);
        $server = $this->{strtolower($provider)}->setProvider($providerData);

        // Retrieve the temporary credentials from step 2
        $temporaryCredentials = unserialize($this->session->userdata('temporary_credentials'));

        // Third and final part to OAuth 1.0 authentication is to retrieve token
        // credentials (formally known as access tokens in earlier OAuth 1.0
        // specs).
        $tokenCredentials = $server->getTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);

        // Now, we'll store the token credentials and discard the temporary
        // ones - they're irrelevant at this stage.
        $this->session->unset_userdata('temporary_credentials');
        $this->session->set_userdata('token_credentials', serialize($tokenCredentials));

        $user = $server->getUserDetails($tokenCredentials);

        $this->load->model('auth/Login_model');
        $userData = $this->Login_model->validate_login(null, null, false, false, null, true, $user->email);

        if ($userData) {

            // check banned
            if ($userData->banned) {
                $this->session->set_flashdata('error', $this->lang->line('account_is_banned'));
                redirect('login');
            }

            // check active
            if (!$userData->active) {
                $this->session->set_flashdata('error', $this->lang->line('oauth1_not_active'));
                redirect('login');
            }

            // user exists - set session data and log in
            $this->load->helper('session');
            session_init($userData);

            // create or renew cookie
            $this->load->helper('cookie');
            $cookie_domain = config_item('cookie_domain');
            $cookie = get_cookie('unique_token');

            if ($cookie) {
                // cookie is already set, renew it
                setcookie("unique_token", $cookie, time() + Settings_model::$db_config['cookie_expires'], '/', $cookie_domain, false, false);
            }else{
                // needs new cookie
                setcookie("unique_token", $userData->cookie_part . substr(uniqid(mt_rand(), true), -10) . $userData->cookie_part, time() + Settings_model::$db_config['cookie_expires'], '/', $cookie_domain, false, false);
            }

            // redirect to private section
            redirect('membership/'. strtolower(Settings_model::$db_config['home_page']));

        }else{
            // user does not exist
            $this->session->set_flashdata('provider', $provider);
            $this->session->set_flashdata('email', $user->email);

            // pass $user->email to view
            $content_data['nickname'] = $user->nickname;
            $content_data['email'] = $user->email;

            // show username creation form
            $this->quick_page_setup(Settings_model::$db_config['active_theme'], 'main', $this->lang->line('oauth1_add_username'), 'oauth1_user', 'header', 'footer', '', $content_data);
        }
    }


    public function finalize() {

        // token_credentials session must exist
        if ( $this->session->userdata('token_credentials') == "") {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('invalid_session') .'</p>');
            redirect('login');
        }

        // always unset token_credentials no matter what happens we don't need it anymore
        $this->session->unset_userdata('token_credentials');

        // check site settings
        if(! Settings_model::$db_config['login_enabled']) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('login_disabled') .'</p>');
            redirect('login');
        }elseif (! Settings_model::$db_config['oauth_enabled']) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('oauth1_login_disabled') .'</p>');
            redirect('login');
        }

        // form input validation
        $this->form_validation->set_error_delimiters('<p>', '</p>');
        $this->form_validation->set_rules('oauth1_username', $this->lang->line('oauth1_username'), 'trim|required|max_length[24]|min_length[6]|is_valid_username|is_db_cell_available[user.username]');
        $this->form_validation->set_rules('provider', $this->lang->line('provider'), 'trim|required|alpha');
        $this->form_validation->set_rules('email', $this->lang->line('oauth1_email_address'), 'trim|required|max_length[255]|is_valid_email|is_db_cell_available[user.email]');

        // return form errors
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('/login');
        }

        // create member
        $this->load->model('auth/Oauth_model');
        if (!$userData = $this->Oauth_model->create_member_oauth($this->input->post('oauth1_username'), $this->input->post('email'))) {
            $this->session->set_flashdata('error', 'oauth1_member_creation_failed');
            redirect('/login');
        }

        // add roles
        $this->load->model('utils/rbac_model');
        if (!$this->rbac_model->create_user_role(array('user_id' => $userData['last_id'], 'role_id' => 4)))
        {
            $this->session->set_flashdata('error', 'oauth1_roles_creation_failed');
            redirect('/login');
        }

        // create directory
        if (!file_exists(FCPATH .'assets/img/members/'. $this->input->post('oauth1_username'))) {
            mkdir(FCPATH .'assets/img/members/'. $this->input->post('oauth1_username'));
        }else{
            $this->session->set_flashdata('error', $this->lang->line('create_imgfolder_failed'));
            redirect('/login');
        }

        // set session data and log in
        $this->load->helper('session');
        $sessionData = session_oauth_prepare($userData['last_id'], $this->input->post('oauth1_username'));
        session_init($sessionData);

        // send confirmation email
        $this->load->helper('send_email');
        $this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
        $this->email->from(Settings_model::$db_config['admin_email'], $_SERVER['HTTP_HOST']);
        $this->email->to($this->input->post('email'));
        $this->email->subject($this->lang->line('oauth1_welcome_subject'));
        $this->email->message($this->lang->line('email_greeting') ." ". $this->input->post('oauth1_username') ."\r\n\r\n". $this->lang->line('oauth1_welcome_msg') ."\r\n\r\n". $this->lang->line('oauth1_email_thank_you'));
        $this->email->send();

        redirect('membership/'. strtolower(Settings_model::$db_config['home_page']));

    }

}