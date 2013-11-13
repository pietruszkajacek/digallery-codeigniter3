<!DOCTYPE html>
<html lang="pl">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="no-cache">
	<meta name="description" content="Fotograficzny Portal Społecznościowy - digallery">
	<meta name="keywords" content="fotografia cyfrowa, zdjęcia">
	<title>Fotograficzny Portal Społecznościowy - digallery</title>
	<link href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" rel="stylesheet">
	<link href="<?php echo base_url();?>assets/bootstrap/css/bootstrap.css" rel="stylesheet">
	<link href="<?php echo base_url();?>assets/css/styles.css" rel="stylesheet">
	
	<?php //echo '<script src="' . base_url() . 'assets/js/modernizr.custom.29278.js"></script>'; ?>
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
			<p class="last-update"><?php echo 'Ostatnia aktualizacja: ' . date('Y-m-d H:i:s', filemtime($_SERVER['SCRIPT_FILENAME'])); ?></p>
		</div>
	</footer>

	<?php echo '<script src="http://code.jquery.com/jquery-1.9.0.js"></script>'; ?>
	<?php echo '<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>'; ?>
	<?php echo '<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>'; ?>
	
	<?php echo '<script src="' . base_url() . 'assets/bootstrap/js/bootstrap.min.js"></script>'; ?>
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