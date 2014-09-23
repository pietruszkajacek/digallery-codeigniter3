<!DOCTYPE html>
<html lang="pl">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="no-cache">
	<meta name="description" content="Fotograficzny Portal Społecznościowy - digallery">
	<meta name="keywords" content="fotografia cyfrowa, zdjęcia">
	<title>Fotograficzny Portal Społecznościowy - digallery</title>
	<link href="https://code.jquery.com/ui/1.11.1/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
	<link href="<?php echo base_url();?>assets/bootstrap/css/bootstrap.css" rel="stylesheet">
	<link href="<?php echo base_url();?>assets/css/styles.css" rel="stylesheet">
	
	<?php //echo '<script src="' . base_url() . 'assets/js/modernizr.custom.29278.js"></script>'; ?>
</head>
<body data-controller="<?php echo $controller_name; ?>" data-action="<?php echo $action_name; ?>">
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
	
	<?php if (isset($no_container_class)) : ?>
		<?php echo $content; ?>
	<?php else : ?>
		<div class="container" id="main-content">
			<?php echo $content; ?>
		</div>
	<?php endif; ?>
	
	<footer>
		<div class="container footer">
			<p class='copyText'>&copy; <?php echo date('Y') . $this->lang->line('dc_all_r_res'); ?></p>
			<p class="last-update"><?php echo 'Ostatnia aktualizacja: ' . date('Y-m-d H:i:s', filemtime($_SERVER['SCRIPT_FILENAME'])) . ' (CodeIgniter ' . CI_VERSION . ')'; ?></p>
		</div>
	</footer>

	<?php echo '<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>'; ?>
	<?php echo '<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>'; ?>
	<?php echo '<script src="http://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>'; ?>
	
	<?php echo '<script src="' . base_url() . 'assets/bootstrap/js/bootstrap.min.js"></script>'; ?>

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