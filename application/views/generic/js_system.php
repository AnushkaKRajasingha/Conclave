<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
        /* http://www.jigniter.com/use-your-site-url-or-base-url-in-javascript-functions/comment-page-1/#comment-654 */
    <!--
        var CI = {
          'base_url': '<?php echo base_url(); ?>',
          'username': '<?php echo ($this->session->userdata('username') != "" ? $this->session->userdata('username') : ""); ?>',
          'expand' : "<?php echo $this->lang->line('list_members_search_expand'); ?>",
          'collapse' : "<?php echo $this->lang->line('list_members_search_collapse'); ?>",
          'search' : "<?php echo $this->lang->line('list_members_search'); ?>",
          'confirm_delete' : "<?php echo $this->lang->line('confirm_delete'); ?>",
          'csrf_token_name' : "<?php echo $this->security->get_csrf_token_name(); ?>",
          'csrf_cookie_name' : "<?php echo $this->security->get_csrf_hash(); ?>",
          'picture_max_upload_size' : "<?php echo Settings_model::$db_config['picture_max_upload_size']; ?>"
        };
    -->
    </script>
