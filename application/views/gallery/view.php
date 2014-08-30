<div class="row">
	<div class="span11">
		<div class="gallery-name-header">
			<div class="pull-left"><h5><em><?php echo $gallery->name; ?></em></h5></div>
			<div class="pull-right"><h4><em><?php echo  '(' . $current_image_index . ' / ' . count($gallery_images) . ')'; ?></em></h4></div>
		</div>
		<div class="jcarousel-wrapper">
		<div class="jcarousel" data-jcar-start="<?php echo $current_image_index; ?>">
			<ul class="thumbnails">
				<?php foreach ($gallery_images as $gallery_image): ?>
					<li class="span1">
						<div class="thumbnail thumbnail-mini">
							<div class="container-thumb-mini">
								<div class="outer-block-thumb-mini">
									<?php 
										echo anchor("gallery/view/{$gallery->id}/" . ($gallery_image->order + 1) . '/' . url_slug($gallery->name, array('transliterate' => TRUE)), 	
												img(($gallery_image->plus_18 && !$adult_user) && !(isset($logged_in_user) && $logged_in_user->id === $gallery_image->user_id) 
														? array('src' => 'assets/img/stop_mini.png')
														: array('src' => $thumb_mini_config['path'] . $gallery_image->file_name)));
									?>
								</div>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
			<div class="jcarousel-prev-horizontal">
				<?php echo anchor("#", '&larr;', array('id' => 'jcarousel-prev')); ?>
			</div>
			<div class="jcarousel-next-horizontal">
				<?php echo anchor("#", '&rarr;', array('id' => 'jcarousel-next')); ?>
			</div>
		</div>
		<?php $this->load->view('partials/thumb_preview.gallery.tpl.php');?>
		<ul class="pager">
			<?php if (isset($previous_image_index)) :?>
				<li class="previous">
					<?php echo anchor("gallery/view/$gallery->id/$previous_image_index" . '/' . url_slug($gallery->name, array('transliterate' => TRUE)), 
							'&larr; poprzednia', array()); ?>
				</li>				
			<?php else : ?>
				<li class="previous disabled">
					<?php echo anchor("#", '&larr; poprzednia', array('onclick' => 'return false;' )); ?>
				</li>							
			<?php endif; ?>
			<?php if (isset($next_image_index)) :?>
				<li class="next">
					<?php echo anchor("gallery/view/$gallery->id/$next_image_index" . '/' . url_slug($gallery->name, array('transliterate' => TRUE)),
							'następna &rarr;', array()); ?>
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
					<?php if ($gallery->user_id === $logged_in_user->id) : ?>
						<a class="btn" href="/profile/add_edit_gallery/<?php echo $gallery->id; ?>"><i class="icon-edit"></i></a>
						<a data-toggle="modal" href="#delete-image-confirm-modal" class="btn" onclick="return false;"><i class="icon-trash"></i></a>
					<?php else: ?>
						<a class="btn disabled" href="#" onclick="return false;"><i class="icon-edit"></i></a>
						<a class="btn disabled" href="#" onclick="return false;"><i class="icon-trash"></i></a>
					<?php endif; ?>
				<?php else: ?>
					<a class="btn disabled" href="#" onclick="return false;"><i class="icon-edit"></i></a>
					<a class="btn disabled" href="#" onclick="return false;"><i class="icon-trash"></i></a>
				<?php endif; ?>
			</div>
		</div>
		<div class="preview-image-description">
			<div class="preview-image-avatar">
				<?php echo anchor("profile/{$user_gallery->id}", img(array('src' => $avatars_config['path'] . ($user_gallery->avatar ? $user_gallery->avatar : 'profile_comment.png')))); ?>
			</div>
			<div class="preview-image-content">
				<h5>
					<?php
					for ($i = 0; $i < count($current_image_cats_path); $i++)
					{
						echo anchor($current_image_cats_path[$i]['path'], $current_image_cats_path[$i]['long_name_cat']) . ($i < count($current_image_cats_path) - 1 ? ' / ' : '');
					}
					?>
				</h5>
				<h5><em><?php echo $gallery_images[$current_image_index - 1]->title; ?></em></h5>
				<h5><?php echo anchor("profile/{$user_gallery->id}", $user_gallery->email); ?></h5>
			</div>
		</div>
		<hr>
		<?php if (($gallery->description) != ''): ?>
			<p><?php echo $gallery->description; ?></p>
			<hr>
		<?php endif; ?>
		<?php $this->load->view('partials/comments.tpl.php'); ?>
	</div>
	<div class="span4">
	</div>
	<div id="delete-image-confirm-modal" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4>Czy chcesz usunąć bezpowrotnie galerię?</h4>
		</div>
		<div class="modal-body">
			<p>Galeria zostanie trwale usunięta i nie będzie można jej odzyskać.</p>
			<p><strong>Czy jesteś pewny, że chcesz wykonać tą operację?</strong></p>
		</div>
		<div class="modal-footer">
			<a href="#" id="delete-image-btn" class="btn btn-danger">Usuń</a>
			<a href="#" class="btn btn-primary" data-dismiss="modal">Anuluj</a>
		</div>
	</div>	
</div>