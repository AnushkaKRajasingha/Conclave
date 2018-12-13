<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['active_theme'] .'/partials/content_head.php'); ?>


<div class="row">

    <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">


            <?php if (isset($error)) { ?>
            <div id="error" class="alert alert-danger">
                <div>
                    <h4><?php print $this->lang->line('message_error_heading'); ?></h4>
                    <span><?php print $error; ?></span>
                </div>
            </div>
            <?php } ?>


            <?php if (isset($success)) { ?>
            <div id="success" class="alert alert-success">
                <div>
                    <h4><?php print $this->lang->line('message_success_heading'); ?></h4>
                    <span><?php print $success; ?></span>
                </div>
            </div>
            <?php } ?>

    </div>

</div>