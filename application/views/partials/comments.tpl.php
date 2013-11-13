<?php if ($can_comment) : ?>
	<?php if (isset($logged_in_user)) : ?>
		<div class="comment">
			<div class="avatar-comment">
				<?php echo img(array('src' => $avatars_config['path'] . ($logged_in_user->avatar ? $logged_in_user->avatar : 'profile_comment.png'))); ?>
			</div>
			<div class="content-comment">
				<?php echo form_textarea(array('name' => 'send-comment-textarea', 'id' => 'send-comment-textarea', 'rows' => 4)); ?>
				<div>
					<?php if ($can_evaluate): ?>
						<?php if ($comment_object_owner !== $logged_in_user->id && !$object_rated) : ?>
							Oceń: 
							<select id="object-rate" class="span3">
								<?php
									foreach ($name_of_ratings as $name_rate)
									{
										echo '<option ' . 'value="' . $name_rate['rate'] . '">' . $name_rate['name'] . '</option>';
									}
								?>
							</select>
						<?php endif; ?>
					<?php endif; ?>
					<a id="send-comment-btn" class="btn btn-primary" href="">Wyślij</a>
				</div>	
			</div>
		</div>
	<?php else: ?>
		<div class="comment">
			<div class="avatar-comment">
				<?php echo img(array('src' => $avatars_config['path'] . 'profile_comment.png')); ?>
			</div>
			<div class="content-comment">
				<p>Aby dodać komentarz musisz się zalogować</p>
				<p>Jeżeli nie masz jeszcze konta w digallery.pl załóż je!</p>
				<div>
					<a class="btn btn-primary" href="/user/register/">Załóż konto</a>
					<a id="login-comment-btn" class="btn btn-primary" href="">Zaloguj się...</a>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php else : ?>
	<p class="text-warning">Użytkownik nie wyraźił zgody na komentowanie.</p>
<?php endif; ?>
<hr>
<?php
	$counts_comments = count($object_comments);
?>
<?php foreach ($object_comments as $object_comment): ?>
	<div class="comment" id="<?php echo $object_comment->comment_id; ?>">
		<div class="avatar-comment">
			<?php
				echo anchor("profile/{$object_comment->user_id}", img(array('src' => $avatars_config['path']
						. ($object_comment->avatar ? $object_comment->avatar : 'profile_comment.png'))));
			?>
		</div>
		<div class="content-comment">
			<div class="header-comment">
				<div class="email">
					<p>
						<?php echo anchor("profile/{$object_comment->user_id}", $object_comment->email); ?> / 
						<?php echo $object_comment->commenting_time; ?>
						<?php if ($can_evaluate): ?>
							<?php echo $name_of_ratings[intval($object_comment->rate)]['name'] !== '' ? ' / '
								. $name_of_ratings[intval($object_comment->rate)]['name'] : '';
							?>
						<?php endif; ?>
					</p>
				</div>
				<?php if (isset($logged_in_user)) : ?>
						<?php if ($object_comment->user_id === $logged_in_user->id) : ?>
							<div class="edit-trash-btns">
								<a href="#" class="btn btn-mini btn-comment-edit"><i class="icon-edit"></i></a>
								<a href="#" class="btn btn-mini btn-comment-trash"><i class="icon-trash"></i></a>
							</div>
						<?php elseif ($comment_object_owner === $logged_in_user->id) :  ?>
							<div class="edit-trash-btns">
								<a href="#" class="btn btn-mini btn-danger btn-comment-trash"><i class="icon-trash"></i></a>
							</div>
						<?php endif; ?>
				<?php endif; ?>
			</div>
			<hr class="small-margin">
			<div id="comment_<?php echo $object_comment->comment_id; ?>">
				<?php echo $object_comment->comment; ?>
			</div>
			<p class="comment-last-modify">
				<small><em>
						<?php
						if ($object_comment->last_edit !== NULL)
						{
							echo 'Ostatnia zmiana: ' . $object_comment->last_edit;
						}
						?>
					</em>
				</small>
			</p>
			<?php if (!($object_comment->signature == NULL)): ?>
				<div class="signature-comment">
					<hr class="small-margin">
					<?php echo $object_comment->signature; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
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
<div id="delete-comment-confirm-modal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Czy chcesz usunąć komentarz?</h3>
	</div>
	<div class="modal-body">
		<p>Ten komentarz zostanie trwale usunięty.</p>
		<p><strong>Czy jesteś pewny, że chcesz wykonać tą operację?</strong></p>
	</div>
	<div class="modal-footer">
		<a href="#" id="delete-comment-btn" class="btn btn-danger">Usuń</a>
		<a href="#" class="btn btn-primary" data-dismiss="modal">Anuluj</a>
	</div>
</div>
<div id="edit-comment-modal" class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3>Edycja komentarza</h3>
	</div>
	<div class="modal-body modal-body-edit-comment">
		<?php echo form_textarea(array('name' => 'new_comment', 'id' => 'edit-comment-textarea', 'rows' => 10)); ?>
	</div>
	<div class="modal-footer">
		<a href="#" id="save-comment-btn" class="btn btn-danger">Zapisz</a>
		<a href="#" class="btn btn-primary" data-dismiss="modal">Anuluj</a>
	</div>
</div>