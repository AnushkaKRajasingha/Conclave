<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// only used by admin controller (loaded in Admin_Controller.php)

// dash
$lang['dashboard_title']           = 'Dashboard';
$lang['dash_new_members_week']     = "New members <small class=\"fg-white\"><em>this week</em></small>";
$lang['dash_new_members_month']    = "New members <small class=\"fg-white\"><em>this month</em></small>";
$lang['dash_new_members_year']     = "New members <small class=\"fg-white\"><em>this year</em></small>";
$lang['dash_total_members']        = "Total member count";
$lang['dash_latest_members_title'] = "Latest members";
$lang['dashboard_username']        = 'Username';
$lang['dashboard_first_name']      = 'First name';
$lang['dashboard_last_name']       = 'Last name';
$lang['dashboard_email_address']   = 'Email address';
$lang['dashboard_last_login']      = 'Last login';

// backup & export
$lang['backup_and_export_title']        = 'Backup & export';
$lang['backup_title']                   = "Backup your database";
$lang['backup_e-mail_text_title']       = 'Database backup';
$lang['backup_e-mail_text']             = "The database file is attached as zip file.";
$lang['backup_text']                    = "Backups will be stored in /tmp.";
$lang['backup_warning1']                = "WARNING 1: for very large databases this might not be possible and you will have to export directly from the MySQL command line.";
$lang['backup_warning2']                = "WARNING 2: you might want to take your MySQL server offline before backing up. Disable site login before doing this.";
$lang['export_title']                   = "Export members list";
$lang['export_database_title']          = "Export database";
$lang['export_database_text']           = 'You requested a database backup to be sent to your admin email.';
$lang['export_email_text_title']        = 'Members list';
$lang['export_email_text']              = 'You requested a member list backup to be sent to your admin email.';
$lang['export_send']                    = 'Also send a copy to my email.';
$lang['export_members_success']         = 'The members export has been saved in /tmp.';
$lang['export_members_failed']          = 'The members export has failed to save to /tmp.';
$lang['export_members_success_send']    = 'The members export has been saved to /tmp and was send to the admin email address.';
$lang['export_members_failed_send']     = 'The members export was saved to /tmp but the email to admin has failed.';
$lang['export_database_success']        = 'The database export has been saved in /tmp.';
$lang['export_database_failed']         = 'The database export has failed to save to /tmp.';
$lang['export_database_success_send']   = 'The database export has been saved in /tmp and was send to the admin email address.';
$lang['export_database_failed_send']    = 'The database export has failed to save to /tmp but the email to admin has failed.';
$lang['export_members_loading_text']    = 'Working...';
$lang['export_database_loading_text']   = 'Working...';

// roles
$lang['roles_title']                    = "Roles";
$lang['roles_admin_noedit']             = "Not allowed to change admin role.";
$lang['roles_admin_noedit_permissions'] = "Not allowed to change admin permissions.";
$lang['role_updated']                   = "Role with name \"%s\" was successfully updated.";
$lang['roles_admin_nodelete']           = "Not allowed to delete admin role.";
$lang['roles_member_nodelete']          = "Not allowed to delete member role - is needed by system.";
$lang['role_removed']                   = "Role with name \"%s\" and all links to it were successfully removed.";
$lang['role_added']                     = "Role added.";
$lang['role_permission_updated']        = "Role permissions updated.";
$lang['role_name']                      = "Role name";
$lang['roles_description']              = "Description";
$lang['roles_warning']                  = "Warning: deleting roles will also remove all associations to users and permissions.";
$lang['roles_btn_toggle']               = "Toggle permissions";
$lang['roles_btn_save']                 = "Save";
$lang['roles_btn_delete']               = "Delete";
$lang['roles_btn_save_roles']           = "Save roles";
$lang['roles_add_title']                = "Create";
$lang['roles_manage']                   = "Manage";
$lang['roles_loading_text']             = 'Creating...';

// permissions
$lang['permissions_title']                  = "Permissions";
$lang['permission_description']             = "Description";
$lang['permission_order']                   = "Order";
$lang['permission_create']                  = "Create";
$lang['permission_manage']                  = "Manage";
$lang['permission_warning']                 = "Warning: deleting permissions will also remove all associations to users and roles.";
$lang['permission_system']                  = "System";
$lang['permission_yes']                     = "yes";
$lang['permission_no']                      = "no";
$lang['permission_edit']                    = "Edit";
$lang['permission_delete']                  = "Delete";
$lang['permission_system_noedit']           = "System permissions can not be edited.";
$lang['permission_updated']                 = "Permission with id %s updated";
$lang['permission_system_nodelete']         = "System permissions can not be deleted.";
$lang['permission_removed']                 = "Permission and all links to it removed.";
$lang['permission_unable_add']              = "Unable to add permission.";
$lang['permission_created']                 = "Permission %s created.";
$lang['permission_btn_create']              = "Create";
$lang['permission_loading_text']            = 'Creating...';
$lang['permission_delete_loading_text']     = 'Deleting...';

// list members and member detail
$lang['last_login']         = 'Last login';
$lang['banned']             = "banned";
$lang['unbanned']           = "unbanned";
$lang['activated']          = "activated";
$lang['deactivated']        = "deactivated";

// list members
$lang['list_members_title']                         = 'List members';
$lang['list_members_total']                         = "Total members";
$lang['list_members_empty_search']                  = 'Please enter some search data.';
$lang['list_members_search_member']                 = "Search member";
$lang['list_members_search_expand']                 = "expand";
$lang['list_members_search_collapse']               = "collapse";
$lang['list_members_search']                        = "search";
$lang['list_members_no_results']                    = "No results found.";
$lang['list_members_nothing_selected']              = "Nothing selected.";
$lang['list_members_username']                      = "Username";
$lang['list_members_first_name']                    = 'First name';
$lang['list_members_last_name']                     = 'Last name';
$lang['list_members_email']                         = "Email";
$lang['list_members_email_address']                 = 'Email address';
$lang['list_members_activate']                      = "activate account";
$lang['list_members_deactivate']                    = "deactivate account";
$lang['list_members_ban']                           = "ban account";
$lang['list_members_unban']                         = "unban account";
$lang['list_members_action_title']                  = "With selected:";
$lang['list_members_toggle_ban']                    = "Member with username %s ";
$lang['list_members_toggle_active']                 = "Member with username %s ";
$lang['list_members_deleted']                       = "Selected members deleted.";
$lang['list_members_banned']                        = "Banned %s members.";
$lang['list_members_unbanned']                      = "Unbanned %s members.";
$lang['list_members_nobody_banned']                 = "Nobody was banned.";
$lang['list_members_nobody_unbanned']               = "Nobody was unbanned.";
$lang['list_members_nobody_activated']              = "Nobody was activated.";
$lang['list_members_nobody_deactivated']            = "Nobody was deactivated.";
$lang['list_members_activated']                     = "Activated %s members.";
$lang['list_members_deactivated']                   = "Deactivated %s members.";
$lang['list_members_admin_noban']                   = 'Not allowed to ban root administrator account.';
$lang['list_members_admin_nodeactivate']            = 'Not allowed to deactivate root administrator account.';
$lang['list_members_delete']                        = "Are you sure you want to delete those members?";
$lang['list_members_activate']                      = "Are you sure you want to activate those members?";
$lang['list_members_deactivate']                    = "Are you sure you want to deactivate those members?";
$lang['list_members_ban']                           = "Are you sure you want to ban those members?";
$lang['list_members_unban']                         = "Are you sure you want to unban those members?";
$lang['list_members_could_not_remove_file']         = 'Could not remove file - aborted at %s.';
$lang['list_members_deletion_failed']               = "Deletion failed - aborted at %s.";
$lang['list_members_failed_to_remove_member_dir']   = "Failed to remove member directory - aborted at %s.";
$lang['list_members_search_loading_text']           = 'Searching...';

// member detail
$lang['member_detail']                                  = 'Member detail';
$lang['member_detail_username']                         = "Username";
$lang['member_detail_first_name']                       = 'First name';
$lang['member_detail_last_name']                        = 'Last name';
$lang['member_detail_email_address']                    = 'Email address';
$lang['member_detail_date_registered']                  = 'Date registered';
$lang['member_detail_new_password']                     = 'New password';
$lang['member_detail_picture_select_button']            = "Select files";
$lang['member_detail_picture_delete_button']            = "Delete";
$lang['member_detail_picture_delete_loading_text']      = 'Deleting...';
$lang['member_detail_picture_not_present']              = 'No profile image uploaded yet.';
$lang['member_detail_updated']                          = 'Member with username %s and ID %s updated.';
$lang['member_detail_viewing_member']                   = 'Viewing member';
$lang['member_detail_send_copy']                        = 'E-mail member about profile updates made here.';
$lang['member_detail_save']                             = 'Save member data';
$lang['member_detail_edited_subject']                   = 'Your account info was changed';
$lang['member_detail_edited_msg']                       = "Your account has been edited by us, please visit your profile to view the changes. In case we have changed your password you will not be able to log on: in that case, use the reset password procedure. \r\n Kind regards - the admin";
$lang['member_detail_loading_text']                     = 'Updating...';
$lang['member_detail_root_admin_text']                  = "THIS IS THE ROOT ADMINISTRATOR ACCOUNT";
$lang['member_detail_picture_max_size']                 = 'Maximum %s kilobytes';

// add member
$lang['add_member']                     = 'Add member';
$lang['add_member_username']            = 'Username';
$lang['add_member_first_name']          = 'First name';
$lang['add_member_last_name']           = 'Last name';
$lang['add_member_email_address']       = 'E-mail address';
$lang['add_member_password']            = 'Password';
$lang['add_member_password_confirm']    = 'Confirm password';
$lang['add_member_loading_text']        = 'Adding...';
$lang['add_member_unable']              = 'Unable to create member account.';
$lang['add_member_email_subject']       = 'Activation required';
$lang['add_member_email_message']       = ",\r\n\r\nThank you for registering with us. To activate your account please visit the link below (or copy-paste into your browser).";
$lang['add_member_success']             = 'Account created - please check the link in your mailbox to activate your membership.';
$lang['add_member_created']             = 'New member has been created.';

// contact member
$lang['contact_member_title']           = "Contact member";
$lang['contact_member_message']         = "Message to member: ";
$lang['contact_member_success']         = "Message was sent correctly.";
$lang['contact_member_loading_text']    = 'Sending...';
$lang['contact_member_button']          = 'Send message';

// oauth providers
$lang['oauth_providers_title']          = "OAuth2 Providers";
$lang['oauth_new_provider_title']       = "OAuth2: new provider";
$lang['oauth_disabled_warning']         = 'OAuth2 providers has been turned off in global settings and will not work until it is enabled there!';
$lang['provider_saved']                 = "Provider saved.";
$lang['provider_deleted']               = "Provider deleted.";
$lang['provider_order']                 = 'Order';
$lang['provider_name']                  = 'Name';
$lang['provider_enabled']               = 'Enabled';
$lang['provider_btn_add']               = 'Add provider';
$lang['provider_subtitle']              = 'The name must be exactly the same as the provider for example "Google", not "google+".';
$lang['provider_delete']                = 'Delete';
$lang['provider_save']                  = 'Save';
$lang['provider_name']                  = 'Name';
$lang['provider_client_id']             = 'Client ID';
$lang['provider_client_secret']         = 'Client secret';
$lang['provider_success_add']           = 'New provider added.';
$lang['provider_add_title']             = "Add new";
$lang['provider_new_loading_text']      = 'Creating...';

// root admin
$lang['root_admin_username_noedit']     = "The root admin account username can not be edited.";
$lang['root_admin_nodelete']            = "The root admin account username can not be deleted.";
$lang['root_admin_noban']               = "The root admin account username can not be banned.";
$lang['root_admin_nodeactivate']        = "The root admin account username can not be deactivated.";
$lang['root_admin_minimum_role']        = "The root admin account username must have at least the role of administrator.";