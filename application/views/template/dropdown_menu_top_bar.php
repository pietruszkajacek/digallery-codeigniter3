<ul class="nav navbar-nav navbar-right">
	<?php if (!$this->ion_auth->logged_in()) : ?>
		<li class="dropdown" id="login-drop-menu">
			<a href="#login-drop-menu" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				Masz konto? <strong>Zaloguj się</strong> <b class="caret"></b>
			</a>
			<ul id="signin-dropdown" class="dropdown-menu" role="menu">
				<li>
					<?php echo form_open("user/login", array('id' => 'signin-form', 'role' => 'form')); ?>
						<div class="form-group">
							<?php echo form_label('Email', 'email'); ?>
							<?php echo form_input(array('name' => 'email', 'id' => 'email', 'class' => 'form-control', 'type' => 'email', 'placeholder' => 'Email')); ?>
						</div>
						<div class="form-group">
							<?php echo form_label('Hasło', 'password'); ?>
							<?php echo form_input(array('name' => 'password', 'id' => 'password', 'class' => 'form-control', 'type' => 'password', 'placeholder' => 'Hasło')); ?>
						</div>
						<div class="checkbox">
							<label>
								<?php echo form_checkbox(array('name' => 'remember', 'value' => '1', 'checked' => FALSE)); ?>
								Zapamiętaj mnie...
							</label>
						</div>
						<button type="submit" class="btn btn-default">Zaloguj mnie...</button>
						<div id="forgot-password"><?php echo anchor('user/forgot_password', 'Nie możesz się zalogować?'); ?></div>
					<?php echo form_close(); ?>
				</li>
			</ul>
		</li>
	<?php else : ?>
		<li class="dropdown" id="logged-in-drop-menu">
			<a href="#logged-in-drop-menu" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
				<strong><?php echo $this->ion_auth->user()->row()->email; ?></strong> <b class="caret"></b>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li>
					<a href="<?php echo '/profile/' . $this->ion_auth->user()->row()->id; ?>">
						<span class="glyphicon glyphicon-home" aria-hidden="true"></span>
						<strong>Strona profilu...</strong>
					</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="/profile/edit">
						<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
						Edycja danych profilu...
					</a>
				</li>
				<li>
					<a href="/posts/inbox">
						<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
						Poczta...
					</a>
				</li>
				<li>
					<a href="/profile/submit">
						<span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
						Wyślij pracę...
					</a>
				</li>
				<li>
					<a href="/profile/add_edit_gallery">
						<span class="glyphicon glyphicon-th" aria-hidden="true"></span>
						Utwórz galerie...
					</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="/user/logout">
						<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
						Wyloguj się
					</a>
				</li>
			</ul>
		</li>
	<?php endif; ?>
</ul>



