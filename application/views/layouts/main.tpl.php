<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<!--	<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
	<meta name="robots" content="no-cache">
	<meta name="description" content="Fotograficzny Portal Społecznościowy - digallery">
	<meta name="keywords" content="fotografia cyfrowa, zdjęcia">
	<title>Fotograficzny Portal Społecznościowy - digallery</title>
	<link href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" rel="stylesheet">
	<link href="<?php echo base_url();?>assets/bootstrap3/css/bootstrap.css" rel="stylesheet">
	<link href="<?php echo base_url();?>assets/css/styles.css" rel="stylesheet">
	
	<?php //echo '<script src="' . base_url() . 'assets/js/modernizr.custom.29278.js"></script>'; ?>
	
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->	
</head>
<body>
	<noscript>
		<div class="container no-script-info">
			<div class="alert alert-error">
				Do poprawnego korzystania z serwisu konieczne jest włączenie obsługi języka <strong>Javascript!</strong>
			</div>
		</div>
	</noscript>
	<!--[if lte IE 6]>
		<div class="container">
			<div class="alert alert-error">
				Do poprawnego korzystania z serwisu konieczna jest nowsza wersja przeglądarki Internet Explorer.
			</div>
		</div>
	<![endif]-->

	<?php echo $top_bar; ?>
	
	<?php echo $header_bar; ?>
	
	<div <?php echo (isset($no_container_class)) ? '' : 'class="container" id="main-content"'; ?>>
		<?php echo $content; ?>
	</div>

	<footer>
		<div class="container footer">
			<p class='copyText'>&copy; <?php echo date('Y') . $this->lang->line('dc_all_r_res'); ?></p>
			<p class="last-update"><?php echo 'Ostatnia aktualizacja: ' . date('Y-m-d H:i:s', filemtime($_SERVER['SCRIPT_FILENAME'])) . ' (CodeIgniter ' . CI_VERSION . ')'; ?></p>
		</div>
	</footer>

	<?php //echo '<script src="http://code.jquery.com/jquery-1.9.0.js"></script>'; ?>
	<?php echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>'; ?>
	<?php echo '<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>'; ?>
	<?php echo '<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>'; ?>
	
	<?php //echo '<script src="' . base_url() . 'assets/bootstrap/js/bootstrap.min.js"></script>'; ?>
	<?php echo '<script src="' . base_url() . 'assets/bootstrap3/js/bootstrap.min.js"></script>'; ?>
	<?php echo '<script src="' . base_url() . 'assets/js/common.js"></script>'; ?>

	<?php
		if (isset($js))
		{
			if (is_array($js))
			{
				foreach ($js as $js_script)
				{
					echo '<script src="' . base_url() . 'assets/js/'. $js_script . '"></script>';
				}
			}
			else
			{
				echo '<script src="' . base_url() . 'assets/js/'. $js . '"></script>';
			}
		}
	?>
</body>
</html>