<ul class="thumbnails">
	<?php foreach ($thumbs_mini as $thumb): ?>
		<li class="span1 thumbnail-mini">
			<div class="thumbnail">
				<a href="<?php echo '/image/preview/' . $thumb['imgs_id']; ?>" alt="<?php echo $thumb['title']; ?>">
				<div class="container-thumb-mini">
					<div class="outer-block-thumb-mini">
						<?php
							echo img(($thumb['plus_18'] && !$adult_user) && !(isset($logged_in_user) && $logged_in_user->id === $thumb['owner'])
								? array('src' => 'assets/img/stop_mini.png')
								: array('src' => $thumb_mini_config['path'] . $thumb['file_name']));
						?>
					</div>
				</div>
				</a>
			</div>
		</li>
	<?php endforeach; ?>
</ul>
