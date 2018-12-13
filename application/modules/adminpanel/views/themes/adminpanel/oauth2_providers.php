<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<?php $this->load->view('generic/flash_error'); ?>

<?php
if (!Settings_model::$db_config['oauth_enabled']) {
    print '<p class="f900 bg-danger pd-5 fg-white mg-b-15 text-center"><i class="fa fa-warning fg-warning pd-r-5"></i>'. $this->lang->line('oauth_disabled_warning'). '</p>';
}
?>

    <p>
        <a href="<?php print base_url(); ?>adminpanel/oauth_new_provider" class="btn btn-default"><?php print $this->lang->line('provider_add_title'); ?></a>
    </p>

<?php if (!empty($providers)) { ?>

    <p><strong><?php print $this->lang->line('provider_subtitle'); ?></strong></p>
    <div class="table-responsive">
        <table  class="table table-hover">
            <thead>
            <tr>
                <th style="width: 100px"><?php print $this->lang->line('provider_order'); ?></th>
                <th><?php print $this->lang->line('provider_name'); ?></th>
                <th><?php print $this->lang->line('provider_client_id'); ?></th>
                <th><?php print $this->lang->line('provider_client_secret'); ?></th>
                <th><?php print $this->lang->line('provider_enabled'); ?></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($providers as $provider) { ?>

                <?php print form_open('adminpanel/oauth2_providers/action', array('id' => 'save_provider_form', 'autocomplete' => 'off', 'class' => 'js-parsley', 'data-parsley-submit' => 'save_provider')) ."\r\n"; ?>

                <tr>
                    <td><input type="text" name="order" class="form-control input-lg" value="<?php print $provider->order; ?>"></td>
                    <td><input type="text" name="name" class="form-control input-lg" value="<?php print $provider->name; ?>"></td>
                    <td><input type="text" name="client_id" class="form-control input-lg" value="<?php print $provider->client_id; ?>"></td>
                    <td><input type="text" name="client_secret" class="form-control input-lg" value="<?php print $provider->client_secret; ?>"></td>
                    <td>
                        <?php print form_dropdown('enabled', $enabled, $provider->enabled, 'class="form-control input-lg"'); ?>
                    </td>
                    <td>
                        <button type="submit" name="save" class="save_provider btn-lg btn btn-primary"><i class="fa fa-check pd-r-5"></i> <?php print $this->lang->line('provider_save'); ?></button>
                        <button type="submit" name="delete" class="btn btn-danger btn-lg js-confirm-delete"><i class="fa fa-trash-o pd-r-5"></i> <?php print $this->lang->line('provider_delete'); ?></button>
                        <input type="hidden" name="id" value="<?php print $provider->id; ?>">
                    </td>
                </tr>

                <?php print form_close() ."\r\n"; ?>

            <?php } ?>

            </tbody>
        </table>
    </div>
<?php } ?>