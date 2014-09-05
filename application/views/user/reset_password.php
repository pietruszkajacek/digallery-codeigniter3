<div class="row">
	<div class="span11 offset2">
		<?php $this->load->view('partials/message_error.tpl.php');?>
		<?php $this->load->view('partials/validation_error.tpl.php');?>
		<h3>Reset hasła <small>Resetowanie hasła</small></h3>
		<hr>
		<?php echo form_open('user/reset_password/' . $code, $form_attr); ?>
		<div class="control-group<?php echo $control_groups['new'] ?>">
			<?php echo form_label($new_label['text'], $new_label['for'], $new_label['attributes']); ?>
			<div class="controls">
				<?php echo form_input($new); ?>
				<p class="help-block">Podaj nowe hasło...</p>
			</div>
		</div>
		<div class="control-group<?php echo $control_groups['new_confirm'] ?>">
			<?php echo form_label($new_confirm_label['text'], $new_confirm_label['for'], $new_confirm_label['attributes']); ?>
			<div class="controls">
				<?php echo form_input($new_confirm); ?>
				<p class="help-block">...wpisz hasło jeszcze raz.</p>
			</div>
		</div>
		<?php echo form_input($user_id);?>
		<?php echo form_hidden($csrf); ?>
		<div class="form-actions">
			<button type="submit" class="btn btn-primary">Zmień hasło</button>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>