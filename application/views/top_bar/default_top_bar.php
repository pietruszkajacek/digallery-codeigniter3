<div class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
	<div class="container">
		<?php if (!$this->ion_auth->logged_in()) : ?>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li>
						<?php echo anchor('browse/images/', 'Prace'); ?>
					</li>
					<li>
						<?php echo anchor('browse/galleries/', 'Galerie'); ?>
					</li>					
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown" id="login-drop-menu">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#login-drop-menu">
							Masz konto? <strong>Zaloguj się</strong>
							<b class="caret"></b>
						</a>
						<ul id="signin-dropdown" class="dropdown-menu">
							<li>
								<?php echo form_open("user/login", $nav_bar_top['form_attr']); ?>
								<div class="form-group">
									<?php echo form_label($nav_bar_top['email_label']['text'], $nav_bar_top['email_label']['for']); ?>
									<?php echo form_input($nav_bar_top['email']); ?>
								</div>
								<div class="form-group">
									<?php echo form_label($nav_bar_top['password_label']['text'], $nav_bar_top['password_label']['for']); ?>
									<?php echo form_input($nav_bar_top['password']); ?>
								</div>
								<div class="checkbox">
									<label>
										<?php echo form_checkbox($nav_bar_top['remember']); ?>
										Zapamiętaj mnie...
									</label>	
								</div>
								<button type="submit" class="btn btn-default">Zaloguj mnie...</button>
								<div><?php echo anchor('user/forgot_password', 'Nie możesz się zalogować?'); ?></div>
								<?php echo form_close(); ?>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		<?php else : ?>
			<ul class="nav navbar-nav">
				<li>
					<?php echo anchor('browse/images/', 'Prace'); ?>
				</li>
				<li>
					<?php echo anchor('browse/galleries/', 'Galerie'); ?>
				</li>					
			</ul>			
			<ul class="nav pull-right">
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
			</ul>
		<?php endif; ?>
	</div>

</div>