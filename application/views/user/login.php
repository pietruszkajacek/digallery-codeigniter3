<div class="row">
	<div class="span11 offset2">
		<?php $this->load->view('partials/message_error.tpl.php');?>
		<?php $this->load->view('partials/validation_error.tpl.php');?>
		<h3>Logowanie <small>Zaloguj się do portalu</small></h3>
		<hr>
		<?php echo form_open("user/login", $form_attr); ?>
		<div class="control-group<?php echo $control_groups['email'] ?>">
			<?php echo form_label($email_label['text'], $email_label['for'], $email_label['attributes']); ?>
			<div class="controls">
				<?php echo form_input($email); ?>
				<p class="help-block">Podaj swój adres email...</p>
			</div>
		</div>
		<div class="control-group<?php echo $control_groups['password'] ?>">
			<?php echo form_label($password_label['text'], $password_label['for'], $password_label['attributes']); ?>
			<div class="controls">
				<?php echo form_input($password); ?>
				<p class="help-block">...oraz wprowadź hasło.</p>
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<?php echo form_label($remember_label['text'], $remember_label['for'], $remember_label['attributes']); ?>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary">Zaloguj mnie...</button>
		</div>
		<?php echo form_close(); ?>
		<?php echo anchor('user/forgot_password', 'Nie możesz się zalogować?'); ?>
	</div>
</div>