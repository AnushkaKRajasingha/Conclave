<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

		<div id="post-data">
		<div id="loading-img"></div>
				<h3 id="post-title"><?php if(!empty($post_title)) print $post_title;?></h3>
				<span id="post-meta"><?php if(!empty($post_title)) print 'Post created by '.$first_name;?> on <?php print $post_date;?> ,  Shared by <?php print $shareusernaem;?></span>
				<div class="alsoshared">Also shared with : 
				
				<?php foreach ($alsoshare as $value) {
					?>
					<label class="label label-info"><a href="<?php print base_url()?>/membership/profile/publicprofile/<?php print $value->user_id; ?>"><?php print $value->first_name;?></a></label>
					<?php 
				}?>
				</div>
				<div id="post-content"><?php if(!empty($post_content)) print $post_content;?></div>
				<input type="hidden" id="post-shareby" value="<?php if(!empty($sharedby)) print $sharedby;?>">
				<input type="hidden" id="post-sharedfrom" >
			</div>