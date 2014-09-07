<div class="thumbnails">
	<?php foreach ($thumbs_mini as $thumb): ?>
		<div class="col-xs-1">
			<a href="<?php echo '/image/preview/' . $thumb['imgs_id'] . '/' . url_slug($thumb['title'], array('transliterate' => TRUE)); ?>" alt="<?php echo $thumb['title']; ?>" 
			   class="thumbnail">
			   <?php
					echo img(($thumb['plus_18'] && !$adult_user) && !(isset($logged_in_user) && $logged_in_user->id === $thumb['owner']) 
							? array('src' => 'assets/img/stop_mini.png') : array('src' => $thumb_mini_config['path'] . $thumb['file_name']));
			   ?>
			</a>
		</div>
	<?php endforeach; ?>
</div>