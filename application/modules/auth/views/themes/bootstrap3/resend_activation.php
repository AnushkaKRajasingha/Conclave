<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['active_theme'] .'/partials/content_head.php'); ?>

<div class="row">

    <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">

        <div>
            <?php
            $this->load->view('generic/flash_error');
            ?>
        </div>

        <?php print form_open('auth/resend_activation/send_link', array('id' => 'resend_activation_form', 'class' => 'js-parsley', 'data-parsley-submit' => 'resend_activation_submit')) ."\r\n"; ?>

        <div class="form-group">
            <input type="text" name="email" id="email" class="form-control input-lg"
                   placeholder="<?php print $this->lang->line('resend_activation_email_address'); ?>"
                   data-parsley-type="email"
                   required>
        </div>

        <div class="form-group">
        <?php
        if (Settings_model::$db_config['recaptchav2_enabled'] == true) {
            //print $this->recaptcha->get_html();
            echo $this->recaptchav2->render();
        }
        ?>
        </div>

        <div class="form-group">
            <button type="submit" name="resend_activation_submit" id="resend_activation_submit" class="resend_activation_submit check_email_empty btn btn-primary btn-lg" data-loading-text="<?php print $this->lang->line('resend_activation_loading_text'); ?>">
                <i class="fa fa-check pd-r-5"></i> <?php print $this->lang->line('resend_activation_email_send'); ?>
            </button>
        </div>

        <?php print form_close() ."\r\n"; ?>
    </div>

</div>
