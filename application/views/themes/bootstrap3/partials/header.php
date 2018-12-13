<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="header-fixed">

    <header class="navbar header" role="menu">

        <div class="navbar-header">
            <a class="navbar-brand block" href="<?php print base_url(); ?>">
                <img class="navbar-brand-logo" src="<?php print base_url(); ?>assets/img/public/logo-header.png" alt="logo">
                <div class="navbar-brand-title">
                    <span class="navbar-brand-title-small">Conclave <small class="f900"><em>v<?php print Settings_model::$db_config['cim_version']; ?></em></small></span>
                </div>
            </a>
        </div>

        <a href="<?php print base_url(); ?><?php print ($this->session->userdata('user_id') != "" ? "logout" : "login"); ?>" class="btn navbar-btn text-uppercase f700">
            <i class="fa fa-sign-in"></i><span class="pd-l-5 hidden-xs"> <?php print ($this->session->userdata('user_id') != "" ? $this->lang->line('header_logout') : $this->lang->line('header_login')); ?></span>
        </a>

        <?php
        if($this->session->userdata('user_id') == "") {
            ?>
            <a href="<?php print base_url(); ?>register" class="btn navbar-btn text-uppercase f700">
                <i class="fa fa-pencil"></i><span class="pd-l-5 hidden-xs"> Register</span>
            </a>
        <?php }else{ ?>
            <a href="<?php print base_url(); ?>membership/profile" class="btn navbar-btn text-uppercase f700">
                <i class="fa fa-user"></i><span class="pd-l-5 hidden-xs"> Profile</span>
            </a>
            <a href="<?php print base_url(); ?>adminpanel" class="btn navbar-btn text-uppercase f700">
                <i class="fa fa-dashboard"></i><span class="pd-l-5 hidden-xs"> Adminpanel</span>
            </a>
        <?php } ?>

        <a id="js-extramenu" href="javascript:" class="btn navbar-btn pull-right">
            <i class="fa fa-sticky-note-o"></i>
        </a>

        <?php
        if($this->session->userdata('user_id')) {
        ?>
            <ul class="nav navbar-nav navbar-nav-member mg-l-5 pull-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <?php
                        if ($this->session->userdata('profile_img') != MEMBERS_GENERIC) {
                            ?>
                            <img class="thumbnail-wrapper-navbar" src="<?php print base_url(); ?>assets/img/members/<?php print $this->session->userdata('username'); ?>/<?php print $this->session->userdata('profile_img'); ?>">
                        <?php }else{ ?>
                            <img class="thumbnail-wrapper-navbar" src="<?php print base_url(); ?>assets/img/members/<?php print MEMBERS_GENERIC; ?>">
                        <?php } ?>
                    </a>
                    <div class="dropdown-menu pull-right">

                        <div class="dropdown-menu-member">

                            <a href="<?php print base_url(); ?>membership/my_dashboard" class="block bg-white bg-darken pd-10">
                                <i class="fa fa-dashboard pd-l-10 pd-r-10"></i> Dashboard
                            </a>

                            <a href="<?php print base_url(); ?>membership/profile" class="block bg-white bg-darken pd-10">
                                <i class="fa fa-user pd-l-10 pd-r-10"></i> Profile
                            </a>

                            <!--a href="<?php print base_url(); ?>messenger/messages" class="block bg-white bg-darken pd-10">
                            <i class="fa fa-comments-o pd-l-10 pd-r-10"></i> Messages <!--span class="label label-success pull-right" style="position:relative; top: 3px;">0</span-->
                            </a-->

                            <a href="<?php print base_url(); ?>logout" class="block bg-success bg-darken fg-white pd-10">
                                <i class="fa fa-power-off pd-l-10 pd-r-10"></i> Log out
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        <?php } ?>

    </header>

</div>