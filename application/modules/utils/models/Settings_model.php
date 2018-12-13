<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model {

    /* is autoloaded */

    public static $db_config = array();

    public function __construct() {
        parent::__construct();
        
        $this->_load_settings();
    }

    /**
     *
     * _load_settings: load the settings from database
     *
     *
     */

    protected function _load_settings() {
        $this->load->library('cache');

        $data = $this->cache->get('settings');

   //     if (empty($data)) {

            $this->db->select('name, value')->from(DB_PREFIX .'setting');

            $q = $this->db->get();

            if ($q->num_rows() > 0) {
                foreach($q->result() as $row) {
                    self::$db_config[$row->name] = $row->value;
                }

                $this->cache->write(self::$db_config, 'settings');
            }

   /*     }else{
            self::$db_config['login_enabled'] = $data['login_enabled'];
            self::$db_config['register_enabled'] = $data['register_enabled'];
            self::$db_config['members_per_page'] = $data['members_per_page'];
            self::$db_config['admin_email'] = $data['admin_email'];
            self::$db_config['home_page'] = $data['home_page'];
            self::$db_config['previous_url_after_login'] = $data['previous_url_after_login'];
            self::$db_config['active_theme'] = $data['active_theme'];
            self::$db_config['adminpanel_theme'] = $data['adminpanel_theme'];
            self::$db_config['login_attempts'] = $data['login_attempts'];
            self::$db_config['max_login_attempts'] = $data['max_login_attempts'];
            self::$db_config['email_protocol'] = $data['email_protocol'];
            self::$db_config['sendmail_path'] = $data['sendmail_path'];
            self::$db_config['smtp_host'] = $data['smtp_host'];
            self::$db_config['smtp_port'] = $data['smtp_port'];
            self::$db_config['smtp_user'] = $data['smtp_user'];
            self::$db_config['smtp_pass'] = $data['smtp_pass'];
            self::$db_config['site_title'] = $data['site_title'];
            self::$db_config['cookie_expires'] = $data['cookie_expires'];
            self::$db_config['password_link_expires'] = $data['password_link_expires'];
            self::$db_config['activation_link_expires'] = $data['activation_link_expires'];
            self::$db_config['disable_all'] = $data['disable_all'];
            self::$db_config['site_disabled_text'] = $data['site_disabled_text'];
            self::$db_config['remember_me_enabled'] = $data['remember_me_enabled'];
            self::$db_config['recaptchav2_enabled'] = $data['recaptchav2_enabled'];
            self::$db_config['recaptchav2_site_key'] = $data['recaptchav2_site_key'];
            self::$db_config['recaptchav2_secret'] = $data['recaptchav2_secret'];
            self::$db_config['oauth_enabled'] = $data['oauth_enabled'];
            self::$db_config['picture_max_upload_size'] = $data['picture_max_upload_size'];
            self::$db_config['allow_login_by_email'] = $data['allow_login_by_email'];
            self::$db_config['cim_version'] = '1.0.0 (beta)';// $data['cim_version'];
            self::$db_config['root_admin_username'] = $data['root_admin_username'];
           // self::$db_config['picture_max_upload_size'] = $data['picture_max_upload_size'];
            self::$db_config['max_noof_post'] = $data['max_noof_post'];
            self::$db_config['max_noof_con'] = $data['max_noof_con'];
            self::$db_config['max_noof_point'] = $data['max_noof_point'];
            self::$db_config['min_noof_point'] = $data['min_noof_point'];
        }*/
    }

}

