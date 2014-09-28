<div class="container" id="main-content">
	<div class="row">
		<div class="span11 offset2">
			<?php $this->load->view('partials/message_error.tpl.php'); ?>
			<?php $this->load->view('partials/validation_error.tpl.php'); ?>
			<h3>Rejestracja <small>Zarejestruj się</small></h3>
			<hr>
			<?php echo form_open("user/register", $form_attr); ?>
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
					<p class="help-block">...oraz podaj hasło (min. 6, max. 20 znaków)</p>
				</div>
			</div>
			<div class="control-group<?php echo $control_groups['password_confirm'] ?>">
				<?php echo form_label($password_confirm_label['text'], $password_confirm_label['for'], $password_confirm_label['attributes']); ?>
				<div class="controls">
					<?php echo form_input($password_confirm); ?>
					<p class="help-block">...wpisz hasło jeszcze raz</p>
				</div>
			</div>

			<div class="control-group<?php echo $control_groups['math_captcha'] ?>">
				<?php echo form_label($math_captcha_label['text'], $math_captcha_label['for'], $math_captcha_label['attributes']); ?>
				<div class="controls">
					<div class="input-append">
						<?php echo form_input($math_captcha); ?>
						<span class="add-on"><?php echo $math_captcha_question; ?></span>
					</div>				
				</div>
			</div>


			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Utwórz konto...</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>