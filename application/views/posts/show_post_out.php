<div class="row">
	<div class="span10">
		<?php $this->load->view('partials/message_error.tpl.php');?>
		<h3>Poczta <small>skrzynka nadawcza</small></h3>
		<hr>
		<ul class="nav nav-pills">
			<li><?php echo anchor('posts/compose/', 'Napisz wiadomość'); ?></li>
			<li><?php echo anchor('posts/inbox/', 'Odebrane'); ?></li>
			<li class="active"><?php echo anchor('posts/outbox/', 'Wysłane'); ?></li>
		</ul>
		<h3><?php echo $post->subject; ?></h3>
		<hr>
		<blockquote>
			<p><?php echo $post->message; ?></p>
			<small><?php echo anchor('profile/'.$user_from->id, $user_from->email) . ' | ' . $post->date; ?></small>
		</blockquote>
		<blockquote>
			<small><?php echo 'do: ' . anchor('profile/'.$user_to->id, $user_to->email); ?></small>
		</blockquote>
		<hr>
		<?php echo form_open("posts/outbox/"); ?>
		<?php echo form_hidden($hidden_post_id); ?>
		<div class="form-actions">
			<button type="submit" class="btn btn-danger">Usuń wiadomość</button>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>