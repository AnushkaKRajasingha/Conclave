<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['active_theme'] .'/partials/content_head.php'); ?>

<div class="row">

    <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">

        <div>
            <?php $this->load->view('generic/flash_error'); ?>
        </div>

        <?php print form_open('auth/oauth1/finalize','id="oauth1_finalize_form" class="js-parsley mg-b-15" data-parsley-submit="oauth1_finalize_submit"') ."\r\n"; ?>

        <div class="form-group">
            <label for="oauth1_username"><?php print $this->lang->line('oauth1_choose_username'); ?></label>
            <input type="text" name="oauth1_username" id="oauth1_username" class="form-control input-lg"
                   value="<?php print $nickname; ?>"
                   placeholder="Username"
                   data-parsley-pattern="^[a-zA-Z0-9_-]+$"
                   data-parsley-trigger="change keyup"
                   data-parsley-minlength="6"
                   data-parsley-maxlength="24"
                   required>
        </div>

        <?php sprintf($this->lang->line('oauth1_email_found'), $email) ?>

        <div class="form-group">
            <button type="submit"
                    name="oauth1_finalize_submit"
                    id="oauth1_finalize_submit"
                    class="oauth1_finalize_submit btn btn-primary btn-lg"
                    data-loading-text="<?php print $this->lang->line('oauth1_finalize_loading_text'); ?>">
                <i class="fa fa-check pd-r-5"></i> <?php print $this->lang->line('oauth1_finish_account_creation'); ?>
            </button>
            <input type="hidden" name="provider" value="<?php print $this->session->flashdata('provider'); ?>">
            <input type="hidden" name="email" value="<?php print $email; ?>">
        </div>

        <?php print form_close() ."\r\n"; ?>

    </div>
</div>