<li class="pull-right">
	<?php echo form_open(base_url() . $this->uri->uri_string(), $form_attr); ?>
		<?php if ($filter !== 0) : ?>
			<?php echo form_hidden('filter', $filter); ?>
		<?php endif; ?>
		<?php if ($sort !== 'dd') : ?>
			<?php echo form_hidden('sort', $sort); ?>
		<?php endif; ?>
		<div class="search <?php echo empty($search) ? '' : 'input-append'; ?>">
			<?php echo form_input($search_input); ?>
			<?php if (!empty($search)) : ?>
				<?php
					echo anchor(base_url() . $this->uri->uri_string() . $get_uri_clear_search, '&times;', array('class' => 'btn'));
				?>
			<?php endif; ?>
		</div>
		<button type="submit" class="btn">Szukaj</button>
	<?php echo form_close(); ?>
</li>