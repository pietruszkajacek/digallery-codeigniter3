<ul class="nav pull-right">
	<?php if (!$this->ion_auth->logged_in()) : ?>
		<li class="dropdown" id="login-drop-menu">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#login-drop-menu">
				Masz konto? <strong>Zaloguj się</strong>
				<b class="caret"></b>
			</a>
			<ul id="signin-dropdown" class="dropdown-menu">
				<li>
					<?php echo form_open("user/login", array('id' => 'signin-form')); ?>
					<?php echo form_label('Email', 'email'); ?>
					<?php echo form_input(array('name' => 'email', 'id' => 'email', 'class' => 'span4', 'type' => 'text')); ?>
					<?php echo form_label('Hasło', 'password'); ?>
					<?php echo form_input(array('name' => 'password', 'id' => 'password', 'class' => 'span4', 'type' => 'password')); ?>
					<label class="checkbox">
						<?php echo form_checkbox(array('name' => 'remember', 'value' => '1', 'checked' => FALSE)); ?>
						Zapamiętaj mnie...
					</label>
					<button type="submit" class="btn btn-primary">Zaloguj mnie...</button>
					<div><?php echo anchor('user/forgot_password', 'Nie możesz się zalogować?'); ?></div>
					<?php echo form_close(); ?>
				</li>
			</ul>
		</li>
	<?php else : ?>
		<li class="dropdown" id="logged-in-drop-menu">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#logged-in-drop-menu">
				<strong><?php echo $this->ion_auth->user()->row()->email; ?></strong>
				<b class="caret"></b>
			</a>
			<ul class="dropdown-menu">
				<li><a href="<?php echo '/profile/' . $this->ion_auth->user()->row()->id; ?>"><i class="icon-home"></i><strong> Strona profilu...</strong></a></li>
				<li class="divider"></li>
				<li><a href="/profile/edit"><i class="icon-user"></i> Edycja danych profilu...</a></li>
				<li><a href="/posts/inbox"><i class="icon-envelope"></i> Poczta...</a></li>
				<li><a href="/profile/submit"><i class="icon-picture"></i> Wyślij pracę...</a></li>
				<li><a href="/profile/add_edit_gallery"><i class="icon-th"></i> Utwórz galerie...</a></li>
				<li class="divider"></li>
				<li><a href="/user/logout"><i class="icon-off"></i> Wyloguj się</a></li>
			</ul>
		</li>
	<?php endif; ?>
</ul>



