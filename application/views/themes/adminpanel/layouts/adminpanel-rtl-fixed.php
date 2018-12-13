<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php print Settings_model::$db_config['site_title']; ?>: <?php print $template['title']; ?></title>
	<?php print $template['metadata']; ?>

	<!-- Stylesheet -->
    <link href="<?php print base_url(); ?>assets/css/adminpanel/bootstrap.min.css" rel="stylesheet">
    <link href="<?php print base_url(); ?>assets/css/adminpanel/custom-conclave.css" rel="stylesheet">

    <!-- Google web font -->
    <link href='https://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="icon" href="/favicon.ico">
</head>

<body class="notransition<?php print " ". Site_controller::$page; ?>">
<div class="wrap header-fixed aside-right page-boxed">


    <?php print $template['partials']['header']; ?>


    <aside class="aside">

        <div>

            <?php $this->load->view('themes/'. Settings_model::$db_config['adminpanel_theme'] .'/partials/aside-inner.php'); ?>

            <?php $this->load->view('themes/adminpanel/partials/aside-below.php'); ?>

        </div>

    </aside>

    <aside id="extramenu" class="extramenu left">
        <div id="extramenu-content" class="pd-15">
            <h4>
                <strong>The state of this panel is remembered with localStorage. You can disable this in our JavaScript file.</strong>
            </h4>

            <p>
                Refresh the page and you will notice that the panel stays open. The same goes for the main side menu opposite to this menu.
            </p>

            <p>
                What should I put here? A chat system, quick navigation, tools or maybe profile info? Something will be added in future versions.
            </p>

            <p>
                In the meantime if you have any ideas you are most welcome to post them on <a href="http://www.anushkar.com"><em>my profile page on Anushkar.com</em></a>.
            </p>
        </div>
    </aside>

    <div class="content">

        <?php print $template['body']; ?>

    </div>


    <footer class="footer">
        <?php print $template['partials']['footer']; ?>
    </footer>
</div>
	


<!-- Bootstrap core JavaScript
================================================== -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php print base_url(); ?>assets/vendor/jquery/jquery-2.2.4.min.js">\x3C/script>')</script>
<script src="<?php print base_url(); ?>assets/vendor/bootstrap/bootstrap.min.js"></script>
<script src="<?php print base_url(); ?>assets/vendor/navgoco/jquery.navgoco.js"></script>
<script src="<?php print base_url(); ?>assets/vendor/slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?php print base_url(); ?>assets/vendor/bootbox/bootbox.min.js"></script>
<script src="<?php print base_url(); ?>assets/vendor/cookie-js/js.cookie.js"></script>
<script src="<?php print base_url(); ?>assets/vendor/parsley/parsley.min.js"></script>
<?php print $template['js']; ?>
<?php $this->load->view('generic/js_system'); ?>
<script src="<?php print base_url(); ?>assets/js/app.js"></script>
</body>
</html>