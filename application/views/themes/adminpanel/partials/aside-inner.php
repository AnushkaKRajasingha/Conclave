<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<ul id="demo1" class="menu">

    <?php if (Site_Controller::check_roles(array(1,2,3))) {  ?>
    <li class="hidden-folded">
        <div class="menu-section-title text-left"><?php print $this->lang->line('menu_adminpanel'); ?></div>
    </li>
    <li<?php print ((Site_Controller::$page == "dashboard") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>adminpanel/dashboard"><span class="menu-link-icon fa fa-dashboard"></span> <span class="menu-link-title"><?php print $this->lang->line('menu_dashboard'); ?></span></a></li>
    <li<?php print ((Site_Controller::$page == "site_settings") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>adminpanel/site_settings"><span class="menu-link-icon fa fa-cogs"></span> <span class="menu-link-title"><?php print $this->lang->line('menu_site_settings'); ?></span></a>
    <li><a href="javascript:"><span class="menu-link-icon fa fa-users"></span> <span class="menu-link-title"><?php print $this->lang->line('menu_membership'); ?></span></a>
        <ul>
            <li<?php print ((Site_Controller::$page == "list_members") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>adminpanel/list_members"><span class="menu-link-icon fa fa-angle-right"></span> <?php print $this->lang->line('menu_list_members'); ?></a>
            <li<?php print ((Site_Controller::$page == "add_member") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>adminpanel/add_member"><span class="menu-link-icon fa fa-angle-right"></span> <?php print $this->lang->line('menu_add_member'); ?></a>
        </ul>
    </li>
    <li><a href="javascript:"><span class="menu-link-icon fa fa-diamond"></span> <span class="menu-link-title"><?php print $this->lang->line('menu_roles_permissions'); ?></span></a>
        <ul>
            <li<?php print ((Site_Controller::$page == "roles") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>adminpanel/roles"><span class="menu-link-icon fa fa-angle-right"></span> <?php print $this->lang->line('menu_roles'); ?></a>
            <li<?php print ((Site_Controller::$page == "permissions") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>adminpanel/permissions"><span class="menu-link-icon fa fa-angle-right"></span> <?php print $this->lang->line('menu_permissions'); ?></a>
        </ul>
    </li>
    <li<?php print ((Site_Controller::$page == "oauth2_providers") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>adminpanel/oauth2_providers"><span class="menu-link-icon fa fa-plug"></span> <span class="menu-link-title"><?php print $this->lang->line('menu_oauth_providers'); ?></span></a>
    <li<?php print ((Site_Controller::$page == "backup_export") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>adminpanel/backup_export"><span class="menu-link-icon fa fa-database"></span> <span class="menu-link-title"><?php print $this->lang->line('menu_backup_export'); ?></span></a>
    <?php } ?>

    <li class="hidden-folded">
        <div class="menu-section-title text-left"><?php print $this->lang->line('menu_member'); ?></div>
    </li>

    <li<?php print ((Site_Controller::$page == "my_dashboard") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>membership/my_dashboard"><span class="menu-link-icon fa fa-th-list"></span> <span class="menu-link-title"><?php print $this->lang->line('menu_my_dashboard'); ?></span></a></li>
   <?php if (Site_Controller::check_roles(array(4))) {  ?> 
    <li<?php print ((Site_Controller::$page == "posts") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>posts"><span class="menu-link-icon fa fa-file"></span> <span class="menu-link-title"><?php print $this->lang->line('menu_my_posts'); ?></span></a></li>
    <li<?php print ((Site_Controller::$page == "connections") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>connections"><span class="menu-link-icon fa fa-link"></span> <span class="menu-link-title"><?php print $this->lang->line('menu_my_connections'); ?></span></a></li>
   <?php }?>
   <?php if (Site_Controller::check_roles(array(5))) {  ?> 
    <li<?php print ((Site_Controller::$page == "sharecenter") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>sharecenter"><span class="menu-link-icon fa fa-share-alt"></span> <span class="menu-link-title"><?php print $this->lang->line('menu_my_sharingcenter'); ?></span></a></li>
   <?php } ?>
    <li<?php print ((Site_Controller::$page == "history") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>sharecenter/history"><span class="menu-link-icon fa fa-history"></span> <span class="menu-link-title"><?php print $this->lang->line('menu_sharehistory'); ?></span></a></li>
    <li<?php print ((Site_Controller::$page == "pointhistory") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>sharecenter/points"><span class="menu-link-icon fa fa-money"></span> <span class="menu-link-title"><?php print $this->lang->line('menu_pointhistory'); ?></span></a></li>
    <li<?php print ((Site_Controller::$page == "profile") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>membership/profile"><span class="menu-link-icon fa fa-user"></span> <span class="menu-link-title"><?php print $this->lang->line('menu_profile'); ?></span></a></li>

   <!--  <li class="hidden-folded">
        <div class="menu-section-title text-left"><?php print $this->lang->line('menu_layout_options'); ?></div>
    </li>
    <li><a href="javascript:"><span class="menu-link-icon fa fa-list-alt"></span> <span class="menu-link-title"><?php print $this->lang->line('menu_page_layouts'); ?></span></a>
        <ul>
            <li<?php print ((Site_Controller::$page == "left_menu_fluid") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>page_layouts/left_menu_fluid"><span class="menu-link-icon fa fa-angle-right"></span> <?php print $this->lang->line('menu_left_menu_fluid'); ?></a></li>
            <li<?php print ((Site_Controller::$page == "left_menu_fixed") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>page_layouts/left_menu_fixed"><span class="menu-link-icon fa fa-angle-right"></span> <?php print $this->lang->line('menu_left_menu_fixed'); ?></a></li>
            <li<?php print ((Site_Controller::$page == "right_menu_fluid") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>page_layouts/right_menu_fluid"><span class="menu-link-icon fa fa-angle-right"></span> <?php print $this->lang->line('menu_right_menu_fluid'); ?></a></li>
            <li<?php print ((Site_Controller::$page == "right_menu_fixed") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>page_layouts/right_menu_fixed"><span class="menu-link-icon fa fa-angle-right"></span> <?php print $this->lang->line('menu_right_menu_fixed'); ?></a></li>
            <li<?php print ((Site_Controller::$page == "header_fluid") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>page_layouts/header_fluid"><span class="menu-link-icon fa fa-angle-right"></span> <?php print $this->lang->line('menu_header_fluid'); ?></a></li>
            <li<?php print ((Site_Controller::$page == "fixed_header_fluid") ? ' class="open"' : ""); ?>><a href="<?php print base_url(); ?>page_layouts/fixed_header_fluid"><span class="menu-link-icon fa fa-angle-right"></span><?php print $this->lang->line('menu_fixed_header_fluid'); ?></a></li>
        </ul>
    </li> -->
</ul>