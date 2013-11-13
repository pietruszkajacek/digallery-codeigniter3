<?php if (validation_errors()): ?>
	<?php
		echo '<div class="alert alert-error" id="validation-alert">';
		echo '<a class="close" data-dismiss="alert" href="#">×</a>';
		echo '<h4 class="alert-heading">Błąd!</h4>';
		echo validation_errors('', '<br />');
		echo '</div>';
	?>
<?php endif;?>