<div id="container-full-view">
	<div id="img-clip">
		<div class="thumbnail thumbnail-zoom">
			<div class="container-thumb-zoom">
				<div class="outer-block-thumb-zoom" >
					<?php if (($image->plus_18 && !$adult_user) && !(isset($logged_in_user) && $logged_in_user->id === $image->user_id)) : ?>
						<?php
							echo img(array('src' => $images_config['path'] . 'stop_preview.png', 'alt' => $image->title,
								'class' => "stop18"));
						?>
						<div class="alert alert-block alert-error fade in alert-stop18">
							<button type="button" class="close" data-dismiss="alert">×</button>
							<h4 class="alert-heading">Niestety nie możesz zobaczyć tej pracy!</h4>
							<p>Praca może być przeglądana wyłącznie przez osoby pełnoletnie. <br />Aby ją zobaczyć musisz potwierdzić, że masz skończone 18 lat.</p>
							<p>
								<a href="#stop18-confirm-modal" role="button" data-toggle="modal" class="btn btn-danger">Kliknij aby potwierdzić.</a>
							</p>
						</div>
					<?php else : ?>
						<img id="img-full-view" width="<?php echo $image->image_width; ?>" height="<?php echo $image->image_height; ?>" 
							src="<?php echo '/' . $images_config['path'] . $image->file_name; ?>" alt="<?php echo $image->title; ?>">
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<ul class="pager">
		<?php if (isset($previous_image_id)) : ?>
			<li class="previous">
				<?php echo anchor("image/zoom/$previous_image_id", '&larr; poprzednia', array()); ?>
			</li>				
		<?php else : ?>
			<li class="previous disabled">
				<?php echo anchor("#", '&larr; poprzednia', array('onclick' => 'return false;')); ?>
			</li>							
		<?php endif; ?>
		<?php if (isset($next_image_id)) : ?>
			<li class="next">
				<?php echo anchor("image/zoom/$next_image_id", 'następna &rarr;', array()); ?>
			</li>				
		<?php else : ?>
			<li class="next disabled">
				<?php echo anchor("#", 'następna &rarr;', array('onclick' => 'return false;')); ?>
			</li>				
		<?php endif; ?>	
	</ul>
	<div class="zoom-image-description">
		<div class="zoom-image-avatar">
			<?php echo anchor("profile/{$user_image->id}", img(array('src' => $avatars_config['path'] . ($user_image->avatar ? $user_image->avatar : 'profile_comment.png')))); ?>
		</div>
		<div class="zoom-image-content">
			<h5>
				<?php
					for ($i = 0; $i < count($cats_path); $i++)
					{
						echo anchor($cats_path[$i]['path'], $cats_path[$i]['long_name_cat']) . ($i < count($cats_path) - 1 ? ' / ' : '');
					}
				?>
			</h5>
			<h5><em><?php echo $image->title ?></em></h5>
			<h5><?php echo anchor("profile/{$user_image->id}", $user_image->email); ?></h5>
		</div>
	</div>	
</div>
<?php if (($image->plus_18 && !$adult_user) && !(isset($logged_in_user) && $logged_in_user->id === $image->user_id)) : ?>
	<div id="stop18-confirm-modal" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4>Praca przeznaczona dla osób pełnoletnich</h4>
		</div>
		<div class="modal-body">
			<p>Czy chcesz przeglądać prace przeznaczone wyłącznie dla osób <strong>pełnoletnich</strong>?</p>
		</div>
		<div class="modal-footer">
			<a href="#" id="stop18-confirm-btn" class="btn btn-danger">Tak, mam skończone 18 lat</a>
			<a href="#" class="btn btn-primary" data-dismiss="modal">Nie</a>
		</div>
	</div>
<?php endif; ?>