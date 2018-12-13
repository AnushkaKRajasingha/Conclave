<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
The reCaptcha server keys and API locations

Obtain your own keys from:
http://www.recaptcha.net

CIMembership: this is the old recaptcha file still present for people in need of this way of working
*/
$config['recaptcha'] = array(
  'public'=>Settings_model::$db_config['recaptchav2_site_key'],
  'private'=>Settings_model::$db_config['recaptchav2_secret'],
  'RECAPTCHA_API_SERVER' =>'http://www.google.com/recaptcha/api',
  'RECAPTCHA_API_SECURE_SERVER'=>'https://www.google.com/recaptcha/api',
  'RECAPTCHA_VERIFY_SERVER' =>'www.google.com',
  'RECAPTCHA_SIGNUP_URL' => 'https://www.google.com/recaptcha/admin/create',
  'theme' => Settings_model::$db_config['recaptcha_theme']
);
