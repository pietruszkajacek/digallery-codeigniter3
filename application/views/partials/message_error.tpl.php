<?php if ($message['msg']): ?>
	<?php
		echo '<div class="alert ' . (($message['type'] === 'error') ? 'alert-error' : 'alert-info') . '">';
		echo '<a class="close" data-dismiss="alert" href="#">×</a>';
		echo '<h4 class="alert-heading">' . (($message['type'] === 'error') ? 'Błąd!' : 'Komunikat!') . '</h4>';
		echo $message['msg'];
		echo '</div>';
	?>
<?php endif;?>