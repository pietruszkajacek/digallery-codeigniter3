<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="pl"> <![endif]-->
<!--[if IE 7]>		   <html class="no-js lt-ie9 lt-ie8" lang="pl"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="pl"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="pl"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title><?php echo $title ?></title>
		<meta name="description" content="<?php echo $description; ?>" />
		<meta name="keywords" content="<?php echo $keywords; ?>" />
		<meta name="author" content="<?php echo $author; ?>" />

		<!-- Place favicon.ico and apple-touch-icon(s) in the root directory -->
		
		<link href="https://code.jquery.com/ui/1.11.1/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/bootstrap3/dist/css/bootstrap.css" rel="stylesheet">

		<!-- main CSS -->
		<link href="<?php echo base_url(); ?>assets/css/styles_b3.css" rel="stylesheet">
		
		<!-- extra CSS -->
		<?php foreach($css as $c): ?>
			<link rel="stylesheet" href="<?php echo base_url().'assets/css/'.$c; ?>">
		<?php endforeach; ?>
		
		<script src="<?php echo base_url() ?>assets/js/vendor/modernizr-2.8.3.min.js"></script>
	</head>
	<body data-controller="<?php echo $controller_name; ?>" data-action="<?php echo $action_name; ?>">
		<!--[if lt IE 8]>
		<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/?locale=en">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->
		
		<?php echo $body ?>
		
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>"assets/js/vendor/jquery-1.11.0.min.js"><\/script>')</script>
		<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
		<script src="<?php echo base_url(); ?>assets/bootstrap3/dist/js/bootstrap.js"></script>
		
		<!-- extra js-->
		<?php foreach($javascript as $js):?>
			<script src="<?php echo base_url().'assets/js/'.$js; ?>"></script>
		<?php endforeach;?>
			
	</body>
</html>

