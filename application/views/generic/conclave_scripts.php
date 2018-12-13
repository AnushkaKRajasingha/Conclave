<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<link href="<?php print base_url(); ?>assets/js/data-tables/DT_bootstrap.css" rel="stylesheet">
<link href="<?php print base_url(); ?>assets/js/data-tables/dataTableCustom.css" rel="stylesheet">
<script src="<?php print base_url(); ?>assets/js/fnDateTime/date.format.js"></script>
<?php if(base_url() == 'http://localhost/conclave/' ){ ?>
<script src="<?php print base_url(); ?>assets/js/data-tables/jquery.dataTables.min.js"></script>
<script src="<?php print base_url(); ?>assets/js/moment/moment.min.js"></script>
<script src="<?php print base_url(); ?>assets/js/datetime/datetime.js"></script>
<script src="<?php print base_url(); ?>assets/js/jeditable/jeditable.min.js"></script>
<!-- <script src="//cdn.bootcss.com/jeditable.js/1.7.3/jeditable.min.js"></script> -->
<script src="<?php print base_url(); ?>assets/js/chance/chance.min.js"></script>
<?php } else { ?>
<script src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
<script src="//momentjs.com/downloads/moment.min.js"></script>
<script src="//cdn.datatables.net/plug-ins/1.10.15/dataRender/datetime.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jeditable.js/1.7.3/jeditable.min.js"></script>
<!-- <script src="//cdn.bootcss.com/jeditable.js/1.7.3/jeditable.min.js"></script> -->
<script src="//chancejs.com/chance.min.js"></script>
<?php } ?>
<script src="<?php print base_url(); ?>assets/js/data-tables/DT_bootstrap.js"></script>


<!-- bootbox -->
<script src="<?php print base_url(); ?>assets/js/bootbox/bootbox.min.js"></script>

<script src="<?php print base_url(); ?>assets/js/plugin_utilities.js"></script>
<script src="<?php print base_url(); ?>assets/js/dummy-data/common_page_ini.js"></script>
<script src="<?php print base_url(); ?>assets/js/dummy-data/common-data.js"></script>
<script src="<?php print base_url(); ?>assets/js/dummy-data/demo_data.js"></script>