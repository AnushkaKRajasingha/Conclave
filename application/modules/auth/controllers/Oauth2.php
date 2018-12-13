<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class OAuth2 extends Auth_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function index() {
        redirect('/login');
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
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('oauth2_illegal_provider_name') .'</p>');
            redirect('login');
        }

        if (!Settings_model::$db_config['oauth_enabled']) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('oauth2_social_login_disabled') .'</p>');
            redirect('login');
        }

        $this->load->model('auth/Oauth_model');
        $row = $this->Oauth_model->get_provider_data($provider);

        if(!$row->enabled) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('oauth2_provider_disabled') .'</p>');
            redirect('login');
        }

        if ($row) {
            require APPPATH . 'vendor/PHPLeague-OAuth2/autoload.php';
            $this->load->library('OAuth2/'. $provider);
            $url = $this->{strtolower($provider)}->loadProviderClass($row);
            $_SESSION[strtolower($provider) .'state'] = $this->{strtolower($provider)}->getState();
            redirect($url);
        }else{
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('oauth2_illegal_provider_init') .'</p>');
            redirect('login');
        }
    }

    /**
     *
     * verify: continue after returning from external app and check incoming data
     *
     * @param string $provider
     *
     */

    public function verify($provider) {

        if (!$this->form_validation->alpha_numeric($provider)) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('oauth2_illegal_provider_name') .'</p>');
            redirect('login');
        }

        // check site settings
        if(Settings_model::$db_config['login_enabled'] == 0) {
            $this->session->set_flashdata('error', '<p>'. $this->lang->line('oauth2_login_disabled') .'</p>');
            redirect('login');
        }

        // check state and cross site forgery mitigation
        if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION[strtolower($provider) .'state'])) {
            unset($_SESSION[strtolower($provider) .'state']);
            $this->session->set_flashdata('error', $this->lang->line('oauth2_invalid_state'));
            redirect('login');
        }

        // only if OAuth2 enabled we allow continuing
        if (Settings_model::$db_config['oauth_enabled']) {

            $this->load->model('auth/Oauth_model');

            $row = $this->Oauth_model->get_provider_data($provider);

            // no provider found - die
            if (!$row) {
                $this->session->set_flashdata('error', $this->lang->line('oauth2_no_provider_found'));
                redirect('login');
            }

            // set and get providerObject
            require APPPATH . 'vendor/PHPLeague-OAuth2/autoload.php';
            $this->load->library('OAuth2/'. $provider);
            $this->{strtolower($provider)}->setProvider($row);
            $providerObject = $this->{strtolower($provider)}->getProvider();

            // Validate the token and die if not OK
            try {
                // Try to get an access token (using the authorization code grant)
                $token = $providerObject->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);
            } catch (Exception $e) {
                //print $e->getMessage();die;
                $this->session->set_flashdata('error', $this->lang->line('oauth2_invalid_token'));
                redirect('login');
            }

            // Get profile data
            try {
                // Grab user details
                $user = $providerObject->getResourceOwner($token);

            } catch (Exception $e) {

            	//var_dump($e->getMessage());
                $this->session->set_flashdata('error', $this->lang->line('oauth2_load_userdata_failed'));
                redirect('login');
            }

            // Check db for existing e-mail
            $email = $user->getEmail();

            if (empty($email)) {
                $this->session->set_flashdata('error', $this->lang->line('oauth2_email_not_returned'));
                redirect('login');
            }

            $this->load->model('auth/Login_model');
            $userData = $this->Login_model->validate_login(null, null, false, false, null, true, $email);

            if ($userData) {

                if ($userData->banned) {
                    $this->session->set_flashdata('error', $this->lang->line('account_is_banned'));
                    redirect('login');
                }

                if (!$userData->active) {
                    $this->session->set_flashdata('error', $this->lang->line('oauth2_not_active'));
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
                // user does not exist: show username creation form
                $this->session->set_flashdata('provider', $provider);
                $this->session->set_flashdata('email', $email);

                $content_data['email'] = $email;

                $this->quick_page_setup(Settings_model::$db_config['active_theme'], 'main', $this->lang->line('oauth2_add_username'), 'oauth2_user', 'header', 'footer', '', $content_data);
            }
        }else{
            $this->session->set_flashdata('error', $this->lang->line('oauth2_login_disabled'));
            redirect('login');
        }
    }

    /**
     *
     * finalize: OAuth login is successful: finalize by creating account
     *
     */

    public function finalize() {

        // only if OAuth2 enabled we allow continuing
        if (Settings_model::$db_config['oauth_enabled']) {

            // check site settings
            if (Settings_model::$db_config['login_enabled'] == 0) {
                $this->session->set_flashdata('error', '<p>'. $this->lang->line('site_disabled') .'</p>');
                redirect('login');
            }

            // form input validation
            $this->form_validation->set_error_delimiters('<p>', '</p>');
            $this->form_validation->set_rules('oauth2_username', $this->lang->line('oauth2_username'), 'trim|required|max_length[24]|min_length[6]|is_valid_username|is_db_cell_available[user.username]');
            $this->form_validation->set_rules('provider', $this->lang->line('oauth2_provider'), 'trim|required|alpha');
            $this->form_validation->set_rules('email', $this->lang->line('oauth2_email_address'), 'trim|required|max_length[255]|is_valid_email|is_db_cell_available[user.email]');


            // request new Object, need to reinit to get new url and state
            $this->load->model('auth/Oauth_model');
            $row = $this->Oauth_model->get_provider_data($this->input->post('provider'));

            // build the new provider data
            if ($row) {
                require APPPATH . 'vendor/PHPLeague-OAuth2/autoload.php';
                $this->load->library('OAuth2/'. $this->input->post('provider'));
                // set the new url
                $newUrl = $this->{strtolower($this->input->post('provider'))}->loadProviderClass($row);
                // renew state with updated token data
                $_SESSION[strtolower($this->input->post('provider')) .'state'] = $this->{strtolower($this->input->post('provider'))}->getState();
            }else{
                $this->session->set_flashdata('error', $this->lang->line('oauth2_refresh_token_failed'));
                redirect('login');
            }

            // return form errors
            if (!$this->form_validation->run()) {
                $this->session->set_flashdata('error', validation_errors());
                redirect($newUrl);
            }

            // create member
            if (!$userData = $this->Oauth_model->create_member_oauth($this->input->post('oauth2_username'), $this->input->post('email'))) {
                $this->session->set_flashdata('error', 'oauth2_member_creation_failed');
                redirect('/login');
            }

            // add roles
            $this->load->model('utils/rbac_model');
            if (!$this->rbac_model->create_user_role(array('user_id' => $userData['last_id'], 'role_id' => 4)))
            {
                $this->session->set_flashdata('error', 'oauth2_roles_creation_failed');
                redirect('/login');
            }

            // create directory
            if (!file_exists(FCPATH .'assets/img/members/'. $this->input->post('oauth2_username'))) {
                mkdir(FCPATH .'assets/img/members/'. $this->input->post('oauth2_username'));
            }else{
                $this->session->set_flashdata('error', $this->lang->line('create_imgfolder_failed'));
                redirect($newUrl);
            }

            // set session data and log in
            $this->load->helper('session');
            $sessionData = session_oauth_prepare($userData['last_id'], $this->input->post('oauth2_username'));
            session_init($sessionData);

            // send confirmation email
            $this->load->helper('send_email');
            $this->load->library('email', load_email_config(Settings_model::$db_config['email_protocol']));
            $this->email->from(Settings_model::$db_config['admin_email'], $_SERVER['HTTP_HOST']);
            $this->email->to($this->input->post('email'));
            $this->email->subject($this->lang->line('oauth2_welcome_subject'));
            $this->email->message($this->lang->line('email_greeting') ." ". $this->input->post('oauth2_username') . "\r\n\r\n". $this->lang->line('oauth2_welcome_msg') ."\r\n\r\n". $this->lang->line('oauth2_email_thank_you'));
            $this->email->send();

            redirect('membership/'. strtolower(Settings_model::$db_config['home_page']));
        }else{
            $this->session->set_flashdata('error', $this->lang->line('oauth2_login_disabled'));
            redirect('login');
        }
    }

}

