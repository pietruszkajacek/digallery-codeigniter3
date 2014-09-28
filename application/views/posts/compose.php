<div class="container" id="main-content">
	<div class="row">
		<div id="compose-message" class="span10">
			<?php $this->load->view('partials/message_error.tpl.php'); ?>
			<?php $this->load->view('partials/validation_error.tpl.php'); ?>
			<h3>Poczta <small>wyślij wiadomość</small></h3>
			<hr>
			<ul class="nav nav-pills">
				<li class="active"><?php echo anchor('posts/compose/', 'Napisz wiadomość'); ?></li>
				<li><?php echo anchor('posts/inbox/', 'Odebrane'); ?></li>
				<li><?php echo anchor('posts/outbox/', 'Wysłane'); ?></li>
			</ul>
			<?php echo form_open("posts/compose", $form_attr); ?>
			<div id="ctrl-gr-recipient" class="control-group<?php echo $control_groups['recipient'] ?>">
				<?php echo form_label($recipient_label['text'], $recipient_label['for'], $recipient_label['attributes']); ?>
				<div class="controls">
					<?php echo form_input($recipient); ?>
				</div>
			</div>
			<div id="ctrl-gr-subject" class="control-group<?php echo $control_groups['subject']; ?>">
				<?php echo form_label($subject_label['text'], $subject_label['for'], $subject_label['attributes']); ?>
				<div class="controls">
					<?php echo form_input($subject); ?>
				</div>
			</div>
			<div id="ctrl-gr-message" class="control-group<?php echo $control_groups['post_message']; ?>">
				<?php echo form_label($post_label['text'], $post_label['for'], $post_label['attributes']); ?>
				<div class="controls">
					<?php echo form_textarea($post_message); ?>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Wyślij wiadomość</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>