<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if(base_url() == 'http://localhost/conclave/' ){ ?>
<script src="<?php print base_url(); ?>assets/js/ckeditor/ckeditor.js"></script>
<script src="<?php print base_url(); ?>assets/js/tagsinput/bootstrap-tagsinput.js"></script>
<script src="<?php print base_url(); ?>assets/js/typeahead/typeahead.bundle.js"></script>
<link href="<?php print base_url(); ?>assets/js/typeahead/bootstrap-tagsinput.css" rel="stylesheet">
<?php }else{?>
<script src="//cdn.ckeditor.com/4.7.0/standard/ckeditor.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<link href="//bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet">
<?php } ?>
<script src="<?php print base_url(); ?>assets/js/posts.js"></script>