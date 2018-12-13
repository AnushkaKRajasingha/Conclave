<li class="post-item post-item-my" data-id="<?php print $item->id; ?>" title="<?php print $item->post_title;?>" data-sharedfrom="<?php if(isset($item->psid)) print $item->psid; else print 0; ?>">
<?php
$baseurl = base_url();
$imgsrc = base_url().'assets/img/members/'.$item->profile_img ;
if($item->profile_img == 'members_generic.png'){ 
	$imgsrc = base_url().'assets/img/members/'.$item->profile_img ;
}
else
{
	$imgsrc = base_url() .'assets/img/members/'.$item->username.'/'.$item->profile_img;
}
?>

<img src="<?php echo $imgsrc;?>" alt="<?php print $item->first_name;?>" titale="<?php print $item->first_name;?>" />
<h4 class="post-title"><?php print $item->post_title;?> </h4>
<strong>by <?php print $item->first_name;?></strong>
<div class="clear"></div>
<hr/>
<div class="post-content"><?php print substr($item->post_content,0,150);?></div>
<!-- </a> -->

</li>
