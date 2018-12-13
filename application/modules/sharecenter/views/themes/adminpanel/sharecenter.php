<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>
<?php $this->load->view('generic/flash_error'); ?>

<div class="row margin-top-30">

	<div class="col-xs-12 col-sm-4">
		<div class="row">
		<h4 class="text-uppercase f900 h4-title">
				<?php print  $this->lang->line('listmy_posts'); ?></span>
		</h4>
		<ul id="listmy_posts" class="list-posts">
			<?php
			if(!empty($_myposts))
			foreach ($_myposts as $value) {
				$this->load->view('post_item',array('item'=>$value));
			}?>
		</ul>
		</div>
		<div class="row">
		<h4 class="text-uppercase f900 h4-title">
				<?php print  $this->lang->line('list_post_sharedwithme'); ?></span>
		</h4>
			<ul id="list_shared_posts" class="list-posts">
			<?php
			if(!empty($_sharedposts))
			foreach ($_sharedposts as $value) {
				$this->load->view('post_item',array('item'=>$value));
			}?>
		</ul>
		</div>
	</div>
	<div class="col-xs-12 col-sm-4">
		<h4 class="text-uppercase f900 h4-title">
				<?php print  $this->lang->line('create_post_andshare'); ?></span>
		</h4>
		<div id="post-data">
		<div id="loading-img"></div>
				<h3 id="post-title"></h3>
				<span id="post-meta"></span>
				<p id="post-content"></p>
				<input type="hidden" id="post-shareby">
				<input type="hidden" id="post-sharedfrom">
			</div>
	</div>

	<div class="col-xs-12 col-sm-4">
		<h4 class="text-uppercase f900 h4-title">
				<?php print  $this->lang->line('available_con_toshare'); ?></span>
		</h4>
		<div id="con-check-list-cntr">
			<div id="loading-img-con"></div>
			<ul id="con-check-list" class="row">
			
			</ul>
		</div>
	</div>
</div>
<div class="row row navbar-fixed-bottom share-action-row">
 <div class="col-sm-12">
<div class="form-group">
    <button type="submit" id="post_share_button" class="post_share_button btn btn-primary btn-lg pull-right" data-loading-text="<?php print $this->lang->line('share_loading_text'); ?>"><i class="fa fa-share pd-r-5"></i>Share</button>
</div>
</div>
</div>
