<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="page-title mg-b-20">
    <h2 class="f900 mg-0 pd-t-20 pd-b-5"><?php print $first_name;?>'s Profile</h2>
</div>
<div class="row">
<?php 
$imgsrc = $profile_img == 'members_generic.png' ? base_url().'assets/img/members/'.$profile_img : base_url().'assets/img/members/'.$username.'/'.$profile_img;
?>
	<div class="col-sm-6">

		<h2><?php print $this->lang->line('profile_personal_details'); ?></h2>


		<div class="form-group">
			<label for="profile_first_name"><?php print $this->lang->line('profile_first_name'); ?> :</label>
			<?php print $first_name;?>
		</div>
		
		<div class="form-group">
			<label for="profile_last_name"><?php print $this->lang->line('profile_last_name'); ?> : </label>
			<?php print $last_name;?>
		</div>
		<div class="form-group">
			<label for="profile_email"><?php print $this->lang->line('profile_gender'); ?> : </label>
			<?php print $gender;?>
		</div>
		<div class="form-group">
			<label for="profile_email"><?php print $this->lang->line('profile_email_address'); ?> : </label>
			<?php print $email;?>
		</div>

	</div>
<div class="col-md-6"><img src="<?php print $imgsrc;?>" alt="<?php print $first_name;?>" /></div>

</div>


