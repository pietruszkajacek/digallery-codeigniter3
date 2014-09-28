<div class="container" id="main-content">
	<div class="row">
		<div class="span15">
			<h3><?php echo $user->email; ?><small> użytkownik</small></h3>
			<?php $this->load->view('partials/profile_menu.tpl.php'); ?>
		</div>
	</div>
	<div class="row">
		<div class="span11">


			<div class="row">
				<div class="span6">
					<dl class="dl-horizontal profile-user-data">
						<dt>Imię:</dt>
						<dd><?php echo $user->first_name != '' ? $user->first_name : '---'; ?></dd>
						<dt>Nazwisko:</dt>
						<dd><?php echo $user->last_name != '' ? $user->last_name : '---'; ?></dd>
						<dt>Płeć:</dt>
						<dd>
							<?php
							switch ($user->sex) {
								case 'k':
									echo 'kobieta';
									break;
								case 'm':
									echo 'mężczyzna';
									break;
								default:
									echo 'nie określono';
									break;
							}
							?>
						</dd>
						<dt>Miasto:</dt>
						<dd><?php echo $user->city != '' ? $user->city : '---'; ?></dd>
						<dt>Zarejestrowany:</dt>
						<dd><?php echo $user->register_date != '' ? $user->register_date : '---'; ?></dd>
					</dl>			
				</div>
				<div class="span5">
					<?php echo img($picture_properties); ?>
				</div>			
			</div>
			<?php if (!empty($profile_signature)) : ?>
				<hr>
				<?php echo $profile_signature; ?>
			<?php endif; ?>
			<hr>
			<?php $this->load->view('partials/comments.tpl.php'); ?>
		</div>
		<div class="span4">
			<div class="last-added-images">
				<h5>Ostatnio dodane prace:</h5>
				<hr class="average-margin">
				<?php $this->load->view('partials/thumbs_mini.tpl.php'); ?>
				<?php if (count($thumbs_mini) > 0): ?>
					<a class="btn btn-small pull-right" href="<?php echo "/profile/{$user->id}/images/"; ?>">Więcej</a>
				<?php else: ?>
					Brak prac.
				<?php endif; ?>
			</div>
			<div class="last-added-galleries">
				<h5>Ostatnio utworzone galerie:</h5>
				<hr class="average-margin">
				<?php $this->load->view('partials/thumbs_galleries.tpl.php'); ?>
				<?php if (count($thumbs_small_gallery) > 0): ?>
					<a class="btn btn-small pull-right" href="<?php echo "/profile/{$user->id}/galleries/"; ?>">Więcej</a>
				<?php else: ?>
					Brak galerii.
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>