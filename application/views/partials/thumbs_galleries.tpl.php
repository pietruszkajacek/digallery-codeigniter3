<ul class="thumbnails">
	<?php foreach ($thumbs_small_gallery as $thumb): ?>
		<li class="span2 thumbnail-small">
			<div class="thumbnail thumbnail-browse">
				<a href="<?php echo '/gallery/view/' . $thumb['id']?>">
				<div class="container-thumb-gallery">
					<?php if (isset($thumb['gallery_thumb_images'][1])): ?>
						<div class="left-top-thumb-gallery">
							<?php
								echo img(array('src' => $thumb_mini_config['path'] . (($thumb['gallery_thumb_images'][1]['plus_18'] && !$adult_user)
										&& !(isset($logged_in_user) && $logged_in_user->id === $thumb['owner'])
										? 'stop_mini.png' : $thumb['gallery_thumb_images'][1]['file_name'])));
							?>
						</div>
					<?php endif; ?>
					<?php if (isset($thumb['gallery_thumb_images'][3])): ?>
						<div class="right-top-thumb-gallery">
							<?php
								echo img(array('src' => $thumb_mini_config['path'] . (($thumb['gallery_thumb_images'][3]['plus_18'] && !$adult_user) 
										&& !(isset($logged_in_user) && $logged_in_user->id === $thumb['owner']) 
										? 'stop_mini.png' : $thumb['gallery_thumb_images'][3]['file_name'])));
							?>
						</div>
					<?php endif; ?>
					<?php if (isset($thumb['gallery_thumb_images'][4])): ?>
						<div class="left-bottom-thumb-gallery">
							<?php
								echo img(array('src' => $thumb_mini_config['path'] . (($thumb['gallery_thumb_images'][4]['plus_18'] && !$adult_user) 
										&& !(isset($logged_in_user) && $logged_in_user->id === $thumb['owner']) 
										? 'stop_mini.png' : $thumb['gallery_thumb_images'][4]['file_name'])));
							?>
						</div>
					<?php endif; ?>
					<?php if (isset($thumb['gallery_thumb_images'][2])): ?>
						<div class="right-bottom-thumb-gallery">
							<?php
								echo img(array('src' => $thumb_mini_config['path'] . (($thumb['gallery_thumb_images'][2]['plus_18'] && !$adult_user) 
										&& !(isset($logged_in_user) && $logged_in_user->id === $thumb['owner']) 
										? 'stop_mini.png' : $thumb['gallery_thumb_images'][2]['file_name'])));
							?>
						</div>
					<?php endif; ?>	
				</div>
				<div class="container-thumb-browse">
					<div class="outer-block-thumb-browse-gallery">
						<?php
							echo img(array('src' => $thumb_mini_config['path'] 
								. (($thumb['gallery_thumb_images'][0]['plus_18'] && !$adult_user) && !(isset($logged_in_user) 
								&& $logged_in_user->id === $thumb['owner']) ? 'stop_mini.png' : $thumb['gallery_thumb_images'][0]['file_name']), 'alt' => $thumb['name']));
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
