<div class="row">
	<div class="span15">
		<h3><?php echo $user->email; ?><small> użytkownik</small></h3>
		<?php $this->load->view('partials/profile_menu.tpl.php');?>
	</div>
</div>
<div class="row">	
	<div class="span11">
		<?php
			$counts_comments = count($object_comments);			
		?>
		<?php foreach ($object_comments as $object_comment): ?>
			
			<?php	
				switch ($object_comment->type)
				{
					case "image":
						$href = base_url() . 'image/preview/' . $object_comment->object_id;
						break;
					case "gallery":
						$href = base_url() . 'gallery/view/' . $object_comment->object_id;
						break;
					case "profile":
						$href = base_url() . 'profile/' . $object_comment->object_id;
						break;
				}			
			?>
			
			<a href="<?php echo $href; ?>" class="comments-profile">
			<div class="comment" id="<?php echo $object_comment->comment_id; ?>">
				<?php
					echo '<div class="avatar-comment comment-type-' . $object_comment->type . '"></div>';
				?>
				<div class="content-comment">
					<div id="comment_<?php echo $object_comment->comment_id; ?>">
						<?php echo $object_comment->comment; ?>
					</div>
					<p class="comment-last-modify">
						<small>
							<em>
								<?php
									if ($object_comment->last_edit !== NULL)
									{
										echo 'Ostatnia zmiana: ' . $object_comment->last_edit;
									}
								?>
							</em>
						</small>
					</p>
				</div>
			</div>
			</a>
			<?php
				if (--$counts_comments)
				{
					echo '<hr>';
				}
			?>			
		<?php endforeach; ?>
		<?php if (isset($pagination_links)): ?>
			<div class="pagination pagination-right">
				<?php echo $pagination_links; ?>
			</div>
		<?php endif; ?>			
	</div>
	<div class="span1">
	</div>
	<div class="span3">
		<?php $this->load->view('partials/profile_type_comments_menu.tpl.php');?>
	</div>		
</div>