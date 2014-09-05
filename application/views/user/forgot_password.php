<div class="row">
	<div class="span11 offset2">
		<?php $this->load->view('partials/message_error.tpl.php');?>
		<?php $this->load->view('partials/validation_error.tpl.php');?>
		<h3>Problem z zalogowaniem <small>Nie pamiętasz hasła?</small></h3>
		<hr>
		<?php echo form_open("user/forgot_password", $form_attr); ?>
		<?php if (isset($email_OK) && $email_OK):?>
			<div class="control-group">
				<div class="controls">
					<?php echo form_hidden($email); ?>
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<?php echo $cap_image; ?>
				</div>
			</div>
			<div class="control-group<?php echo $control_groups['captcha'];?>">
				<?php echo form_label($captcha_label['text'], $captcha_label['for'], $captcha_label['attributes']); ?>
				<div class="controls">
					<?php echo form_input($captcha); ?>
					<p class="help-block">...wpisz znaki widoczne na obrazku powyżej.</p>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Kontynuuj</button>
			</div>
		<?php else:?>
			<div class="control-group<?php echo $control_groups['email'] ?>">
				<p>Aby zresetować hasło, wpisz swoją nazwę użytkownika konta digallery.pl (Twój adres email w digallery.pl)</p>
				<?php echo form_label($email_label['text'], $email_label['for'], $email_label['attributes']); ?>
				<div class="controls">
					<?php echo form_input($email); ?>
					<p class="help-block">Podaj swój adres email...</p>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Prześlij</button>
			</div>
		<?php endif;?>
		<?php echo form_close(); ?>
	</div>
</div>