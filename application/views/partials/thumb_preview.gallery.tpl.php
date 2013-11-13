<ul class="thumbnails ">
	<li class="span11">
		<div class="thumbnail thumbnail-preview">
			<div class="container-thumb-preview">
				<div class="outer-block-thumb-preview">
					<?php if (($preview_image->plus_18 && !$adult_user) && !(isset($logged_in_user) && $logged_in_user->id === $preview_image->user_id)) : ?>
						<?php echo img(array('src' => $thumb_preview_config['path'] . 'stop_preview.png', 'alt' => $preview_image->title,
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
						<?php echo anchor("/image/preview/{$preview_image->id}", img(array('src' => $thumb_preview_config['path'] . $preview_image->file_name, 'alt' => $preview_image->title))); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</li>	
</ul>
<?php if (($preview_image->plus_18 && !$adult_user) && !(isset($logged_in_user) && $logged_in_user->id === $preview_image->user_id)) : ?>
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