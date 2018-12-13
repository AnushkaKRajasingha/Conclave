<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<?php $this->load->view('generic/flash_error'); ?>

<?php 
$_postcout = !empty($post_count) ? number_format(count($post_count)) :  0;
$max_postcout =  isset(Settings_model::$db_config['max_noof_post']) ?  Settings_model::$db_config['max_noof_post'] : 0;
$max_nocon =  isset(Settings_model::$db_config['max_noof_con']) ?  Settings_model::$db_config['max_noof_con'] : 0;
$pointtocon =  isset(Settings_model::$db_config['min_noof_pointtocon']) ?  Settings_model::$db_config['min_noof_pointtocon'] : 0; 
$avail_points = isset($avail_points) ? number_format($avail_points->total) : 0;
?>
<script type="text/javascript">
	var CCSET = {'pointtocon':<?php print $pointtocon; ?>
	, 'avail_points' : <?php print $avail_points;?>,
			'post_count' : <?php print $_postcout;?>,
			'max_postcout' : <?php print $max_postcout;?>,
			'max_nocon' : <?php print $max_nocon;?>
	};
	</script>
<?php if($max_postcout > $_postcout) {?>	
<h2><?php if(isset($id)) print $this->lang->line('update_post_title');  else  print $this->lang->line('create_post_title'); ?></h2>
<?php print form_open(isset($id) ? 'posts/update/'.$id : 'posts/posts/add_post', array('id' => 'add_post_form', 'class' => 'js-parsley mg-t-20', 'data-parsley-submit' => 'add_post_save')) ."\r\n"; ?>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">            
            <input type="text" name="post_title" id="post_title" class="form-control input-lg" placeholder="<?php print $this->lang->line('post_title'); ?>"
                   data-parsley-maxlength="50"
                   required value="<?php if(isset($post_title)) print $post_title;?>">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">            
            <textarea type="text" name="post_content" id="post_content" class="form-control input-lg" placeholder="<?php print $this->lang->line('post_content_p'); ?>" required><?php if(isset($post_content)) print $post_content;?></textarea>
        </div>
    </div>
</div>
<div class="row">
 <div class="col-sm-2">
     <div class="form-group"> 
    <select name="post_status" class="form-control input-lg">
    <option value="publish">Publish</option>
    <option value="draft">Draft</option>
    <option value="deleted">Deleted</option>
    <option value="pending">Pending</option>
    </select>
    <?php if(isset($id)){?>    	
    	<script>
    	(function(){
    			document.querySelector('select[name=post_status] option[value=<?php print $post_status?>]').selected = 'selected';
        	})();
    	</script>    	
    <?php }?>
    </div>
    </div>
 <div class="col-sm-2">
<div class="form-group ">	
<h4 class="avail_points">Available Points :<span class="label label-default" id="avail_points"><?php print $avail_points;?></span></h4>
</div>
</div>
 <div class="col-sm-6">
	<div class="form-group" id="connect-search">
		<input type="text" value="" class="tags form-control input-lg" placeholder="Connections to share"/>
		<div id="share_user_cntr"></div>
	</div>
</div>
 <div class="col-sm-2">
<div class="form-group">
    <button type="submit" class="add_post_save btn btn-primary btn-lg" data-loading-text="<?php print $this->lang->line('create_loading_text'); ?>"><i class="fa fa-plus pd-r-5"></i><?php if(isset($id)) print 'Update'; else print 'Create';?></button>
</div>
</div>
</div>
<?php print form_close() ."\r\n"; ?>
<?php }else{
	print "<h3 class='label label-danger'>You have exceed maximum number of post.So you won't be able to create new post untill you delete posts.</h3>";
} ?>

<div class="row margin-top-30">
		<div class="col-xs-12">
			<h4 class="text-uppercase f900">
				<?php print  $this->lang->line('list_post_total') ." : <span id='postCount'> "; ?></span>
			</h4>
			            <div class="table-responsive">
			                <table class="table table-hover table-list-posts" id="table-list-posts">
			                </table>
		                </div>
		</div>
</div>
		