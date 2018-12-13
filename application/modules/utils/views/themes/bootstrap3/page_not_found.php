<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>


<?php $this->load->view('themes/'. Settings_model::$db_config['active_theme'] .'/partials/content_head.php'); ?>

<div class="row text-center">

    <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">

        <h1 class="text-primary f900"><?php print $this->lang->line('pnf_error_404_title'); ?></h1>

        <h3><?php print $this->lang->line('pnf_error_404_msg'); ?></h3>

        <p>
            <?php print $this->lang->line('pnf_explanation'); ?>
        </p>

    </div>

</div>
