<div class="row">
	<div class="span11 offset2">
		<?php $this->load->view('partials/message_error.tpl.php');?>
		<h3>Edycja profilu <small>Zmiana danych użytkownika</small></h3>
		<hr>
		<?php echo form_open_multipart("profile/edit", $form_attr); ?>
		<?php //echo form_fieldset('Edycja profilu'); ?>
		<div>
			<p>Twoje dane:</p>
			<div class="control-group<?php echo $control_groups['first_name'];?>">
				<?php echo form_label($first_name_label['text'], $first_name_label['for'], $first_name_label['attributes']); ?>
				<div class="controls">
					<?php echo form_input($first_name); ?>
					<?php if (form_error('first_name')): ?>
						<?php
							echo '<span class="help-inline">';
							echo form_error('first_name');
							echo '</span>';
						?>
					<?php endif;?>
					<p class="help-block">...Twoje imię</p>
				</div>
			</div>
			<div class="control-group<?php echo $control_groups['last_name'];?>">
				<?php echo form_label($last_name_label['text'], $last_name_label['for'], $last_name_label['attributes']); ?>
				<div class="controls">
					<?php echo form_input($last_name); ?>
					<?php if (form_error('last_name')): ?>
						<?php
							echo '<span class="help-inline">';
							echo form_error('last_name');
							echo '</span>';
						?>
					<?php endif;?>
					<p class="help-block">...Twoje nazwisko</p>
				</div>
			</div>
			<div class="control-group<?php echo $control_groups['city'];?>">
				<?php echo form_label($city_label['text'], $city_label['for'], $city_label['attributes']); ?>
				<div class="controls">
					<?php echo form_input($city); ?>
					<?php if (form_error('city')): ?>
						<?php
							echo '<span class="help-inline">';
							echo form_error('city');
							echo '</span>';
						?>
					<?php endif;?>
					<p class="help-block">...gdzie mieszkasz</p>
				</div>
			</div>
			<div class="control-group<?php echo $control_groups['sex'] ?>">
				<?php echo form_label($sex_label['text'], $sex_label['for'], $sex_label['attributes']); ?>
				<div class="controls">
					<label class="radio">
						<?php echo form_radio($sex_woman); ?>
						kobieta
					</label>
					<label class="radio">
						<?php echo form_radio($sex_man); ?>
						mężczyzna
					</label>
				</div>
			</div>
			<div class="control-group">
				<?php echo form_label($avatar_file_label['text'], $avatar_file_label['for'], $avatar_file_label['attributes']); ?>
				<div class="controls well">
					<?php echo img($avatar_properties); ?>
					<?php echo form_upload($avatar_file); ?>
					<dl class="dl-horizontal" id="avatar-help-file">
						<dt>maks. wymiary grafiki:</dt>
						<dd>szer. 50px, wys. 50px</dd>
						<dt>maks. rozmiar pliku:</dt>
						<dd>100KB</dd>
						<dt>typ pliku:</dt>
						<dd>jpg | gif | png</dd>
					</dl>
					<hr>
					<label class="checkbox">
						<?php echo form_checkbox($delete_avatar); ?>
						usuń plik avatara
					</label>
				</div>
			</div>
			<div class="control-group">
				<?php echo form_label($picture_file_label['text'], $picture_file_label['for'], $picture_file_label['attributes']); ?>
				<div class="controls well">
					<?php echo img($picture_properties); ?>
					<?php echo form_upload($picture_file); ?>
					<dl class="dl-horizontal" id="avatar-help-file">
						<dt>maks. wymiary grafiki:</dt>
						<dd>szer. 150px, wys. 150px</dd>
						<dt>maks. rozmiar pliku:</dt>
						<dd>150KB</dd>
						<dt>typ pliku:</dt>
						<dd>jpg | gif | png</dd>
					</dl>
					<hr>
					<label class="checkbox">
						<?php echo form_checkbox($delete_picture); ?>
						usuń plik ze zdjęciem
					</label>
				</div>
			</div>
			<div class="control-group">
				<?php echo form_label($signature_label['text'], $signature_label['for'], $signature_label['attributes']); ?>
				<div class="controls">
					<?php echo form_textarea($signature); ?>
					<p class="help-block">...sygnaturka</p>
				</div>
			</div>
			<div class="control-group">
				<?php echo form_label($signature_profile_label['text'], $signature_profile_label['for'], $signature_profile_label['attributes']); ?>
				<div class="controls">
					<?php echo form_textarea($signature_profile); ?>
					<p class="help-block">...opis profilu</p>
				</div>
			</div>
			
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Zapisz</button>
			</div>
		</div>
		<?php //echo form_fieldset_close(); ?>
		<?php echo form_close(); ?>
	</div>
</div>