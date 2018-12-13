<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['active_theme'] .'/partials/content_head.php'); ?>

<div class="row">

    <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 mg-t-15">

        <?php
        $this->load->view('generic/flash_error');
        ?>

        <?php
        if ($token) { // do not show form when $token is not found in controller
            print form_open('new_password/change_password', 'id="new_password_form" class="js-parsley mg-b-15" data-parsley-submit="new_password_submit"') . "\r\n"; ?>

        <div class="form-group pd-10 bg-info fg-white text-center f700">
            <?php print $this->lang->line('new_password_warning'); ?>
        </div>

        <div class="form-group">
            <div class="btn-group" role="group" aria-label="...">
                <a href="javascript:" class="btn btn-default js-genWordsButton">Generate</a>
                <a href="javascript:" class="btn btn-default js-show-pwd">Show</a>
                <a href="javascript:" class="btn btn-default js-copy-to-clipboard">Copy</a>
            </div>
        </div>

        <div class="form-group">
                <input type="password"
                       name="password"
                       id="password"
                       class="form-control input-lg"
                       placeholder="<?php print $this->lang->line('new_password_placeholder'); ?>"
                       data-parsley-errors-container="#new_password_parsley"
                       data-parsley-pattern="^(?=.{9,})(?=.*[a-z])(?=.*[A-Z])(?=.*[\.\@\#\$\%\^\|\?\*\!\:\-\;\&\+\=\{\}\[\]]).*$"
                       data-parsley-maxlength="255"
                       data-parsley-pattern-message="<?php print $this->lang->line('register_req_password_parsley'); ?>"
                       required>
            <div id="new_password_parsley"></div>
            <p class="small pd-5">
                <strong><?php print $this->lang->line('new_req_password'); ?></strong>
            </p>
        </div>

        <div class="form-group">
            <button type="submit"
                    name="new_password_submit"
                    id="new_password_submit"
                    class="new_password_submit btn btn-primary btn-lg"
                    data-loading-text="<?php print $this->lang->line('new_password_button_loading_text'); ?>">
                <i class="fa fa-check pd-r-5"></i> <?php print $this->lang->line('new_password_button_text'); ?>
            </button>
        </div>

        <?php print form_close() . "\r\n";
        }
        ?>

    </div>
</div>
