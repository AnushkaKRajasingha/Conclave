<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$lang['site_settings_title']                = "Site settings";
$lang['clear_sessions_title']               = "Clear your sessions";
$lang['clear_sessions_title_explanation']   = "Can be used to gracefully make all members log out by removing their session data.";
$lang['clear_sessions']                     = "Clear sessions";
$lang['general_settings_title']             = "General settings";
$lang['save_all_settings']                  = "Save all settings";
$lang['settings_update']                    = 'Settings successfully updated.';
$lang['sessions_cleared']                   = 'Sessions successfully deleted.';
$lang['sessions_not_cleared']               = 'Nothing to clear.';
$lang['site_title']                         = "Site title";
$lang['site_title_p']                       = 'The site title appears in the title bar as it is used in the <code>&lt;title&gt;</code> tag. Can be a maximum of 60 characters long.';
$lang['disable_whole_app']                  = "Disable whole application";
$lang['disable_whole_app_p']                = "Deny access to all pages, both public and private. The main administrator account will still be able to log in.</p>";
$lang['disabled_text']                      = "Text to display when website is disabled:";
$lang['enable_remember_me']                 = "Enable remember me";
$lang['enable_remember_me_p']               = "Allow the remember me functionality to be used on the login page (based on cookies).";
$lang['disable_login_access']               = "Disable login access";
$lang['disable_login_access_p']             = "Turn off login ability for all members except the main administrator account.";
$lang['max_login_attempts']                 = "Maximum login attempts";
$lang['max_login_attempts_p']               = "Security measure to disallow account access after this many failed login attempts (only works for non-OAuth2 accounts as it is based on the username).";
$lang['allow_login_by_email']               = "Allow login by email";
$lang['allow_login_by_email_p']             = "Whether registered members can log on using their email address on top of being able to use their username.";
$lang['post_login_page']                    = "Post-login display page";
$lang['post_login_page_p']                  = "The page to display right after logging in - should be a controller that extends Private_Controller that resides in application/controllers/private.";
$lang['previous_url_after_login']           = "Display previous page after login?";
$lang['previous_url_after_login_p']         = "When the previous url is found after login you will be redirected to that page. This setting overrides the post-login display page where applicable.";
$lang['members_per_page']                   = "Members per page";
$lang['members_per_page_p']                 = "The number of members per page to display on the list members page.";
$lang['admin_email']                        = "Administrator e-mail account";
$lang['admin_email_p']                      = "Primary application e-mail address to be used for sending e-mails - by default the same as the main administrator e-mail.";
$lang['active_theme']                       = "Currently active theme";
$lang['active_theme_p']                     = "Fill out the theme folder name. Can be used as frontend theme or to override the adminpanel theme.";
$lang['adminpanel_theme']                   = "Theme folder name for the backend also called adminpanel which is the default theme.";
$lang['adminpanel_theme_p']                 = "Use the exact theme folder name here.";
$lang['cookie_expiration']                  = "Cookie expiration";
$lang['cookie_expiration_p']                = "Cookies set will receive this number in seconds as their future expiry time.";
$lang['password_link_expiration']           = "Password link expiration";
$lang['password_link_expiration_p']         = "Make the reset password activation link expire in this many seconds in the future.";
$lang['activation_link_expiration']         = "Activation link expiration";
$lang['activation_link_expiration_p']       = "Make the account activation link expire in this many seconds in the future.";
$lang['picture_max_upload_size']            = "Profile picture max size in Kilobytes.";
$lang['picture_max_upload_size_p']          = "Set the maximum allowed file size for user uploaded profile pictures, for example 50 = 50Kb = 50000 bytes.";
$lang['mail_settings_title']                = "Mail settings";
$lang['sendmail_path']                      = "Path to sendmail";
$lang['sendmail_path_p']                    = "For most servers this is /usr/sbin/sendmail";
$lang['smtp_host']                          = "SMTP host";
$lang['smtp_port']                          = "SMTP port";
$lang['smtp_user']                          = "SMTP user";
$lang['smtp_password']                      = "SMTP password";
$lang['smtp_encrypt']                       = "Will be encrypted before saving to database.";
$lang['recaptcha_settings_title']           = "reCAPTCHA V2 settings";
$lang['enable_recaptcha']                   = "Enable reCAPTCHA V2";
$lang['enable_recaptcha_p']                 = "Turn on recaptcha site-wide to better protect the membership module.";
$lang['site_key']                           = "Site key";
$lang['site_secret']                        = "Site secret";
$lang['disable_registration_p']             = "Turn off the ability for new people to register on the site.";
$lang['registration_disable']               = "Disable member registration";
$lang['registration_disabled']              = 'Registration has been disabled.';
$lang['login_attempts_trigger']             = "reCAPTCHA V2 login attempts trigger";
$lang['login_attempts_trigger_p']           = "Shows a reCAPTCHA form after this many failed login attempts.";
$lang['enable_oauth']                       = "Enable social login globally";
$lang['enable_oauth_p']                     = "Disable or enable the social login integration completely.";
$lang['main_not_found']                     = 'Theme file not found: %s.';
$lang['controller_not_found']               = 'Controller %s.php not found.';
$lang['sessions_loading_text']              = 'Clearing...';
$lang['site_settings_loading_text']         = 'Saving...';

// Conclave Related
$lang['post_setting_title']         = 'Member Settings';
$lang['post_setting_title_explanation']         = 'Member related settings to save in database';
$lang['max_noof_post_title']         = 'Maximum no of posts';
$lang['max_noof_post_title_p']         = 'Maximum no of posts that one person can create.';
$lang['max_noof_con_title'] = "Maximum number of connections";
$lang['max_noof_con_title_p'] = "Application can allocate maximum number of connection that singale user can have.";
$lang['max_noof_point_title'] = "Maximum amount of points";
$lang['max_noof_point_title_p'] = "Application can allocate the maximum amount of poins that single use can have";
$lang['min_noof_point_title'] = "Minimum amount of points";
$lang['min_noof_point_title_p'] = "Application can allocate the minimum amount of poins that single use can have";
$lang['min_noof_pointtocon_title'] = "Minimum no of points to make new connection";
$lang['min_noof_pointtocon_title_p'] = "Application can allocate the minimum number of poins that need to make new connection";
