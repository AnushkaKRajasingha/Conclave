<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<?php $this->load->view('generic/flash_error'); ?>

<h2>
    <?php print $this->lang->line('member_detail_viewing_member'); ?>: <strong class="text-primary"><?php print $member->username; ?> (ID: <?php print $member->user_id; ?>)</strong>
</h2>

<p>
    <span class="label label-primary"><?php print $this->lang->line('last_login'); ?>:</span>
    <strong><?php print $member->last_login; ?></strong>
</p>

<p>
    <span class="label label-primary"><?php print $this->lang->line('member_detail_date_registered'); ?>:</span>
    <strong><?php print $member->date_registered; ?></strong>
</p>

<div class="row">
    <div class="col-sm-6">

        <p>
            <em><?php print sprintf($this->lang->line('member_detail_picture_max_size'), $picture_max_upload_size); ?></em>
        </p>

        <div id="dropzone" class="text-center text-uppercase bd-db-gray bd-5x mg-b-15">DROP IMAGE HERE</div>

        <span class="btn btn-success fileinput-button mg-b-10">
            <i class="fa fa-plus pd-r-5"></i>
            <span><?php print $this->lang->line('member_detail_picture_select_button'); ?></span>
            <input id="fileupload" type="file" name="files[]" data-path="adminpanel/member_detail/upload_profile_picture/<?php print $member->username; ?>">
            <input type="hidden" name="profile_username" id="profile_username" value="<?php print $member->username; ?>">
        </span>

        <div id="progress" class="progress hidden">
            <div class="progress-bar progress-bar-success"></div>
        </div>

        <div id="files" class="files text-primary f900">
            <?php if (!isset($profile_image)) {
                print $this->lang->line('member_detail_picture_not_present');
            } ?>
        </div>

        <?php if (isset($profile_image)) { ?>
            <div class="mg-t-10">
                <img class="js_profile_image profile-img img-thumbnail" src="<?php print base_url(); ?>assets/img/members/<?php print $member->username; ?>/<?php print $profile_image; ?>">
            </div>
        <?php }else{ ?>
            <div class="mg-t-10">
                <img class="js_profile_image profile-img img-thumbnail" src="<?php print base_url(); ?>assets/img/members/<?php print MEMBERS_GENERIC; ?>">
            </div>
        <?php } ?>

        <?php print form_open('adminpanel/member_detail/delete_profile_picture/'. $member->username .'/'. $member->user_id, array('id' => 'delete_profile_picture')) ."\r\n"; ?>
        <button id="delete_profile_picture_submit" name="delete_profile_picture_submit" class="btn btn-danger mg-t-10 mg-b-5" data-loading-text="<?php print $this->lang->line('member_detail_picture_delete_loading_text'); ?>">
            <i class="fa fa-trash-o pd-r-5"></i> <?php print $this->lang->line('member_detail_picture_delete_button'); ?>
        </button>
        <?php print form_close() ."\r\n"; ?>

    </div>
</div>

<div class="row">

    <?php print form_open('adminpanel/member_detail/save', array('id' => 'save_member_form', 'autocomplete' => 'off', 'class' => 'js-parsley', 'data-parsley-submit' => 'save_member')) ."\r\n"; ?>

    <div class="col-sm-6">

        <div class="form-group">
            <?php if (Settings_model::$db_config['root_admin_username'] == $member->username) { ?>
            <div><i class="fa fa-star fg-warning"></i> <?php print $this->lang->line('member_detail_root_admin_text'); ?></div>
            <?php } ?>
            <label for="username"><?php print $this->lang->line('member_detail_username'); ?></label>
            <input type="text" name="username" id="username" value="<?php print $member->username; ?>"
                   class="form-control"
                   required>
        </div>

        <div class="form-group">
            <label for="email"><?php print $this->lang->line('member_detail_email_address'); ?></label>
            <input type="text" name="email" id="email" value="<?php print $member->email; ?>"
                   class="form-control"
                   required>
        </div>

        <div class="form-group">
            <label for="first_name"><?php print $this->lang->line('member_detail_first_name'); ?></label>
            <input type="text" name="first_name" id="first_name" value="<?php print $member->first_name; ?>"
                   class="form-control"
                   required>
        </div>

        <div class="form-group">
            <label for="last_name"><?php print $this->lang->line('member_detail_last_name'); ?></label>
            <input type="text" name="last_name" id="last_name" value="<?php print $member->last_name; ?>"
                   class="form-control"
                   required>
        </div>

        <div class="form-group">
            <label for="send_copy" class="inline"><?php print $this->lang->line('member_detail_send_copy'); ?></label>
            <div class="app-checkbox pull-left mg-b-5">
                <label class="pd-r-10">
                    <input type="checkbox" name="send_copy" id="send_copy" value="accept" class="form_control label_checkbox">
                    <span class="fa fa-check"></span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="save_member btn btn-primary btn-lg" data-loading-text="<?php print $this->lang->line('member_detail_loading_text'); ?>">
                <i class="fa fa-check pd-r-5"></i> <?php print $this->lang->line('member_detail_save'); ?>
            </button>
            <input type="hidden" name="user_id" value="<?php print $member->user_id; ?>">
            <input type="hidden" name="old_username" value="<?php print $member->username; ?>">
        </div>
    </div>

    <div class="col-sm-6">
        <div class="form-group">
            <label><?php print $this->lang->line('roles_title'); ?></label>
            <?php foreach($roles as $role) {?>
                <div class="app-checkbox">
                    <label class="pd-r-10">
                        <input type="checkbox" name="roles[]" value="<?php print $role->role_id; ?>" class="list_members_checkbox"<?php foreach($member_roles as $mr) {if ($role->role_id == $mr->role_id) {print ' checked="checked"';}}; ?>>
                        <span class="fa fa-check"></span> <?php print $role->role_name; ?>
                    </label>
                </div>
            <?php } ?>
        </div>

        <div class="form-group">
            <label for="banned"><?php print $this->lang->line('banned'); ?>?</label>
            <select name="banned" id="banned" class="form-control">
                <option value="0"<?php print ($member->banned == false ? ' selected="selected"' : ''); ?>><?php print $this->lang->line('no'); ?></option>
                <option value="1"<?php print ($member->banned == true ? ' selected="selected"' : ''); ?>><?php print $this->lang->line('yes'); ?></option>
            </select>
        </div>

        <div class="form-group">
            <label for="active"><?php print $this->lang->line('activated'); ?>?</label>
            <select name="active" id="active" class="form-control">
                <option value="1"<?php print ($member->active == true ? ' selected="selected"' : ''); ?>><?php print $this->lang->line('yes'); ?></option>
                <option value="0"<?php print ($member->active == false ? ' selected="selected"' : ''); ?>><?php print $this->lang->line('no'); ?></option>
            </select>
        </div>

        <div class="form-group">
            <label for="password"><?php print $this->lang->line('member_detail_new_password'); ?></label>
            <input type="password" name="password" id="password"
                   data-parsley-maxlength="255"
                   class="form-control">
        </div>

        <div class="form-group">
            <div class="btn-group" role="group" aria-label="...">
                <a href="javascript:" class="btn btn-default js-genWordsButton">Generate</a>
                <a href="javascript:" class="btn btn-default js-show-pwd">Show</a>
                <a href="javascript:" class="btn btn-default js-copy-to-clipboard">Copy</a>
            </div>
        </div>

    </div>

    <?php print form_close() ."\r\n"; ?>

</div>