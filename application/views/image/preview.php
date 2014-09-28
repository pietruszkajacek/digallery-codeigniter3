<div class="container" id="main-content">
	<div class="row">
		<div class="span11">
			<?php $this->load->view('partials/thumb_preview.tpl.php'); ?>
			<ul class="pager">
				<?php if (isset($previous_image_id_name)) : ?>
					<li class="previous">
						<?php echo anchor("image/preview/{$previous_image_id_name->id}" . '/' . url_slug($previous_image_id_name->title, array('transliterate' => TRUE)), '&larr; poprzednia', array());
						?>
					</li>				
				<?php else : ?>
					<li class="previous disabled">
						<?php echo anchor("#", '&larr; poprzednia', array('onclick' => 'return false;')); ?>
					</li>							
				<?php endif; ?>
				<?php if (isset($next_image_id_name)) : ?>
					<li class="next">
						<?php echo anchor("image/preview/{$next_image_id_name->id}" . '/' . url_slug($next_image_id_name->title, array('transliterate' => TRUE)), 'następna &rarr;', array());
						?>
					</li>				
				<?php else : ?>
					<li class="next disabled">
						<?php echo anchor("#", 'następna &rarr;', array('onclick' => 'return false;')); ?>
					</li>				
				<?php endif; ?>	
			</ul>
			<div class="btn-toolbar" style="text-align: center; ">
				<div class="btn-group">
					<?php if (isset($logged_in_user)) : ?>
						<?php if ($preview_image->user_id === $logged_in_user->id) : ?>
							<a class="btn" href="/profile/submit/<?php echo $preview_image->id; ?>"><i class="icon-edit"></i></a>
							<a data-toggle="modal" href="#delete-image-confirm-modal" class="btn" onclick="return false;"><i class="icon-trash"></i></a>
							<a class="btn disabled" href="#" onclick="return false;"><i class="icon-star"></i></a>
						<?php else: ?>
							<a class="btn disabled" href="#" onclick="return false;"><i class="icon-edit"></i></a>
							<a class="btn disabled" href="#" onclick="return false;"><i class="icon-trash"></i></a>
							<a id="favs-btn" class="btn" href="#" title="<?php echo $image_added_to_favs ? '...usuń z ulubionych.' : '...dodaj do ulubionych.'; ?>">
								<i id="icon-favs-btn" class="icon-star<?php echo $image_added_to_favs ? '-empty' : ''; ?>"></i></a>
						<?php endif; ?>
					<?php else: ?>
						<a class="btn disabled" href="#" onclick="return false;"><i class="icon-edit"></i></a>
						<a class="btn disabled" href="#" onclick="return false;"><i class="icon-trash"></i></a>
						<a class="btn disabled" href="#" onclick="return false;"><i class="icon-star"></i></a>
					<?php endif; ?>
					<?php if (($preview_image->plus_18 && !$adult_user) && !(isset($logged_in_user) && $logged_in_user->id === $preview_image->user_id)) : ?>
						<a class="btn disabled" href="#" onclick="return false;"><i class="icon-download"></i></a>
					<?php else: ?>
						<a class="btn" href="/image/download/<?php echo $preview_image->id; ?>" title="pobierz..."><i class="icon-download"></i></a>
					<?php endif; ?>
				</div>
			</div>
			<div class="preview-image-description">
				<div class="preview-image-avatar">
					<?php echo anchor("profile/{$user_image->id}", img(array('src' => $avatars_config['path'] . ($user_image->avatar ? $user_image->avatar : 'profile_comment.png')))); ?>
				</div>
				<div class="preview-image-content">
					<h5>
						<?php
						for ($i = 0; $i < count($cats_path); $i++) {
							echo anchor($cats_path[$i]['path'], $cats_path[$i]['long_name_cat']) . ($i < count($cats_path) - 1 ? ' / ' : '');
						}
						?>
					</h5>
					<h5><em><?php echo $preview_image->title ?></em></h5>
					<h5><?php echo anchor("profile/{$user_image->id}", $user_image->email); ?></h5>
				</div>
			</div>
			<hr>
			<?php if (($preview_image->description) != ''): ?>
				<p><?php echo $preview_image->description; ?></p>
				<hr>
			<?php endif; ?>
			<?php $this->load->view('partials/comments.tpl.php'); ?>
		</div>
		<div class="span4">
			<h5>Więcej prac od:</h5>
			<h5><?php echo anchor("profile/{$user_image->id}", $user_image->email); ?></h5>
			<hr class="average-margin">
			<?php $this->load->view('partials/thumbs_mini.tpl.php'); ?>
			<h5>Szczegóły:</h5>
			<hr class="small-margin">
			<dl class="dl-horizontal details-preview">
				<dt>Data dodania:</dt>
				<dd><?php echo $preview_image->submitted; ?></dd>
				<dt>Wymiary:</dt>
				<dd><?php echo $preview_image->image_width . '×' . $preview_image->image_height; ?></dd>
				<dt>Rozmiar pliku:</dt>
				<dd><?php echo $preview_image->file_size . ' KB'; ?></dd>
			</dl>
			<h5>Statystyki:</h5>
			<hr class="small-margin">
			<dl class="dl-horizontal stats-preview">
				<dt><i class="icon-comment"></i> Komentarze:</dt>
				<dd><?php echo count($object_comments); ?></dd>
				<dt><i class="icon-star"></i> Ulubione:</dt>
				<dd id="number-favs"><?php echo $number_favs; ?>
					<?php if ($number_favs > 0) : ?>
						[ <a id="who-added-to-favorites" href="#">kto?</a> ]
					<?php endif; ?>
				</dd>
				<dt><i class="icon-eye-open"></i> Wejść na strone:</dt>
				<dd><?php echo $number_views . ' [' . $number_views_today . ' dzis.]'; ?></dd>
				<dt><i class="icon-fullscreen"></i> Ściągnięć / pow.:</dt>
				<dd><?php echo $number_downloads . ' [' . $number_downloads_today . ' dzis.]'; ?></dd>
			</dl>
			<!-- exif -->
		</div>
		<div id="who-favorites-modal" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h4>Osoby, które dodały zdjęcie do ulubionych:</h4>
			</div>
			<div class="modal-body modal-body-who-favs">
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-primary" data-dismiss="modal">Powrót</a>
			</div>
		</div>
		<div id="delete-image-confirm-modal" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h4>Czy chcesz usunąć bezpowrotnie pracę?</h4>
			</div>
			<div class="modal-body">
				<p>Ta praca zostanie trwale usunięta i nie będzie można jej odzyskać.</p>
				<p><strong>Czy jesteś pewny, że chcesz wykonać tą operację?</strong></p>
			</div>
			<div class="modal-footer">
				<a href="#" id="delete-image-btn" class="btn btn-danger">Usuń</a>
				<a href="#" class="btn btn-primary" data-dismiss="modal">Anuluj</a>
			</div>
		</div>
	</div>
</div>