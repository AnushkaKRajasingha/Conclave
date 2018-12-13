<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<ul class="list-unstyled">
    <li><a href="<?php print base_url(); ?>renew_password"><?php print $this->lang->line('auth_renew'); ?></a></li>
    <li><a href="<?php print base_url(); ?>retrieve_username"><?php print $this->lang->line('auth_retrieve'); ?></a></li>
    <li><a href="<?php print base_url(); ?>resend_activation"><?php print $this->lang->line('auth_resend'); ?></a></li>
</ul>