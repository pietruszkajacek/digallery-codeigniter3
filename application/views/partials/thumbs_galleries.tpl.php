<ul class="thumbnails">
	<?php foreach ($thumbs_small_gallery as $thumb): ?>
		<li class="span2 thumbnail-small">
			<div class="thumbnail thumbnail-browse">
				<a href="<?php echo '/gallery/view/' . $thumb['id'] . '/1/' . url_slug($thumb['name'], array('transliterate' => TRUE)) ?>">
				<div class="container-thumb-gallery">
					<?php if (isset($thumb['gallery_thumb_images'][1])): ?>
						<div class="left-top-thumb-gallery">
							<?php
								echo img(($thumb['gallery_thumb_images'][1]['plus_18'] && !$adult_user)	&& !(isset($logged_in_user) && $logged_in_user->id === $thumb['owner'])
									? array('src' => 'assets/img/stop_mini.png')
									: array('src' => $thumb_mini_config['path'] . $thumb['gallery_thumb_images'][1]['file_name']));
							?>
						</div>
					<?php endif; ?>
					<?php if (isset($thumb['gallery_thumb_images'][3])): ?>
						<div class="right-top-thumb-gallery">
							<?php
								echo img(($thumb['gallery_thumb_images'][3]['plus_18'] && !$adult_user)	&& !(isset($logged_in_user) && $logged_in_user->id === $thumb['owner'])
									? array('src' => 'assets/img/stop_mini.png')
									: array('src' => $thumb_mini_config['path'] . $thumb['gallery_thumb_images'][3]['file_name']));
							?>
						</div>
					<?php endif; ?>
					<?php if (isset($thumb['gallery_thumb_images'][4])): ?>
						<div class="left-bottom-thumb-gallery">
							<?php
								echo img(($thumb['gallery_thumb_images'][4]['plus_18'] && !$adult_user)	&& !(isset($logged_in_user) && $logged_in_user->id === $thumb['owner'])
									? array('src' => 'assets/img/stop_mini.png')
									: array('src' => $thumb_mini_config['path'] . $thumb['gallery_thumb_images'][4]['file_name']));							
							?>
						</div>
					<?php endif; ?>
					<?php if (isset($thumb['gallery_thumb_images'][2])): ?>
						<div class="right-bottom-thumb-gallery">
							<?php
								echo img(($thumb['gallery_thumb_images'][2]['plus_18'] && !$adult_user)	&& !(isset($logged_in_user) && $logged_in_user->id === $thumb['owner'])
									? array('src' => 'assets/img/stop_mini.png')
									: array('src' => $thumb_mini_config['path'] . $thumb['gallery_thumb_images'][2]['file_name']));							
							?>
						</div>
					<?php endif; ?>	
				</div>
				<div class="container-thumb-browse-image">
					<div class="outer-block-thumb-browse-image">
						<?php
							echo img(($thumb['gallery_thumb_images'][0]['plus_18'] && !$adult_user)	&& !(isset($logged_in_user) && $logged_in_user->id === $thumb['owner'])
									? array('src' => 'assets/img/stop_mini.png', 'class' => 'bordered')
									: array('src' => $thumb_mini_config['path'] . $thumb['gallery_thumb_images'][0]['file_name'], 'class' => 'bordered'));						
						?>
					</div>
				</div>
				
				</a>
					
				<?php echo anchor('/gallery/view/' . $thumb['id'], '<span style="color: grey;">' . $thumb['name'] . '</span>', array('class' => 'thumb')); ?>
				<span class="cat-thumb"><?php echo $thumb['name_cat']; ?></span>
				
			</div>
		</li>
	<?php endforeach; ?>
</ul>		
