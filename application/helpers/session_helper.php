<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// serves as a starting point to load and fine-tune which userdata will be needed for persistent session storage
if (!function_exists('session_init')) {
    function session_init($userData) {
        set_session_data(array(
                'user_id' => $userData->user_id,
                'username' => $userData->username,
                'profile_img' => $userData->profile_img)
        );
    }
}


// the actual factory that churns out session data indiscriminately
if (!function_exists('set_session_data')) {
    function set_session_data($data) {
        $CI = & get_instance();

        foreach($data as $key => $value) {
            if (is_array($value)) {
                set_session_data($value);
            }
            $CI->session->set_userdata($key, $value);
        }
    }
}


// when registering via oauth we immediately need to set session data
if (!function_exists('session_oauth_prepare')) {
    function session_oauth_prepare($user_id, $username) {
        $userData = new stdClass();
        $userData->user_id = $user_id;
        $userData->username = $username;
        $userData->profile_img = MEMBERS_GENERIC;

        return $userData;
    }
}