<div class="navbar navbar-fixed-top navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<?php if (isset($nav)) : ?>
				<?php echo $nav; ?>
			<?php endif; ?>
			<?php if (isset($dropdown_menu)) : ?>
				<?php echo $dropdown_menu; ?>
			<?php endif; ?>
			<?php if (isset($info_panel)) : ?>
				<?php echo $info_panel; ?>
			<?php endif; ?>
		</div>
	</div>
</div>