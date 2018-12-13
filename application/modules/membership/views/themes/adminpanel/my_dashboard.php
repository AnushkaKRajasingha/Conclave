<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>


<div>
    <?php $this->load->view('generic/flash_error'); ?>
</div>

<div class="row text-center">
 	<div class="col-sm-6" style="text-align: left;">
    	<h4 class="text-uppercase f900 h4-title" >
				<?php print  $this->lang->line('list_post_sharedwithme'); ?></span>
		</h4>
			<ul id="list_shared_posts" class="list-posts">
			<?php
			if(!empty($_sharedposts))
			foreach ($_sharedposts as $value) {
				//var_dump($value);
				$this->load->view('post_item',array('item'=>$value));
			}?>
		</ul>
    </div>
 	<div class="col-sm-6">
    <div class="col-sm-6 ">
        <div class="panel card bd-0">
            <div class="panel-body bg-primary">
                <h4><?php print $this->lang->line('dash_total_posts'); ?></h4>
            </div>
            <div class="panel-body bg-white">
                <h3 class="mg-0 f900"><?php if(!empty($_postsummary)) print number_format(count($_postsummary)); else print number_format(0); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel card bd-0">
            <div class="panel-body bg-primary">
                <h4><?php print $this->lang->line('dash_total_con'); ?></h4>
            </div>
            <div class="panel-body bg-white">
                <h3 class="mg-0 f900"><?php if(!empty($_connections)) print number_format(count($_connections)); else print number_format(0); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel card bd-0">
            <div class="panel-body bg-primary">
                <h4><?php print $this->lang->line('dash_total_sharing'); ?></h4>
            </div>
            <div class="panel-body bg-white">
                <h3 class="mg-0 f900"><?php if(!empty($_sharings)) print number_format(count($_sharings)); else print number_format(0); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel card bd-0">
            <div class="panel-body bg-primary">
                <h4><?php print $this->lang->line('dash_total_points'); ?></h4>
            </div>
            <div class="panel-body bg-white">
           
                <h3 class="mg-0 f900"><?php if(!empty($_poinssummary)) print number_format($_poinssummary->total); else print number_format(0); ?></h3>
            </div>
        </div>
    </div>
    </div>
   
</div>