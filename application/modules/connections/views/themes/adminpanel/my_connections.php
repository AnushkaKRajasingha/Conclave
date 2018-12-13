<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/content_head.php'); ?>

<?php $this->load->view('generic/flash_error'); ?>

<div class="row margin-top-30">
		<div class="col-xs-12 col-sm-6">
			<h4 class="text-uppercase f900">
				<?php print  $this->lang->line('list_myconnects') ." : <span id='myconnects'> ". $total_posts; ?></span>
			</h4>
			            <div class="table-responsive">
			                <table class="table table-hover table-list-posts" id="table-list-mycon">
			                </table>
		                </div>
		</div>
		<div class="col-xs-12 col-sm-6">
			<h4 class="text-uppercase f900">
				<?php print  $this->lang->line('list_available_connects') ." : <span id='available_connects'> ". $total_posts; ?></span>
			</h4>
			            <div class="table-responsive">
			                <table class="table table-hover table-list-posts" id="table-list-availablecon">
			                </table>
		                </div>
		</div>
		
</div>
