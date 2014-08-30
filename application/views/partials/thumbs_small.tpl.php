<ul class="thumbnails">
	<?php foreach ($thumbs_small as $thumb): ?>
		<li class="span2 thumbnail-small">
			<div class="thumbnail thumbnail-browse">
				<div class="container-thumb-browse">
					<div class="outer-block-thumb-browse-image">
						<?php
							echo anchor('/image/preview/' . $thumb['imgs_id'] . '/' . url_slug($thumb['title'], array('transliterate' => TRUE)), img(array('src' => $thumb_small_config['path']
									. (($thumb['plus_18'] && !$adult_user) && !(isset($logged_in_user) && $logged_in_user->id === $thumb['owner']) ? 'stop_small.png' : $thumb['file_name']), 'alt' => $thumb['title'])));
						?>
					</div>
				</div>
				<?php echo anchor('/image/preview/' . $thumb['imgs_id'], '<span style="color: grey;">' . $thumb['title'] . '</span>', array('class' => 'thumb')); ?>
				<span class="cat-thumb"><?php echo $thumb['name_cat']; ?></span>
			</div>
		</li>
	<?php endforeach; ?>
</ul>
