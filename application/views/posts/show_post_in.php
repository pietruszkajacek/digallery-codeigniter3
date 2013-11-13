<div class="row">
	<div class="span10">
		<h3>Poczta <small>skrzynka odbiorcza</small></h3>
		<hr>
		<ul class="nav nav-pills">
			<li><?php echo anchor('posts/compose/', 'Napisz wiadomość'); ?></li>
			<li class="active"><?php echo anchor('posts/inbox/', 'Odebrane'); ?></li>
			<li><?php echo anchor('posts/outbox/', 'Wysłane'); ?></li>
		</ul>
		<?php $this->load->view('partials/message_error.tpl.php');?>
		<h3><?php echo $post->subject; ?></h3>
		<hr>
		<blockquote>
			<p><?php echo $post->message; ?></p>
			<small><?php echo anchor('profile/'.$user_from->id, $user_from->email) . ' | ' . $post->date; ?></small>
		</blockquote>
		<hr>
		<?php echo form_open("posts/{$post->id}/in", $form_attr); ?>
			<div id="ctrl-gr-subject" class="control-group<?php echo $control_groups['subject'] ?>">
				<?php echo form_label($subject_label['text'], $subject_label['for'], $subject_label['attributes']); ?>
				<div class="controls">
					<?php echo form_input($subject); ?>
				</div>
			</div>
			<div id="ctrl-gr-message" class="control-group<?php echo $control_groups['post_message'];?>">
				<?php echo form_label($post_label['text'], $post_label['for'], $post_label['attributes']); ?>
				<div class="controls">
					<?php echo form_textarea($post_message); ?>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Wyślij odpowiedź</button>
				<button id="del-btn-show-post-in" type="button" class="btn btn-danger">Usuń wiadomość</button>
			</div>
		<?php echo form_close(); ?>
		<?php echo form_open("posts/inbox/", $del_post_form_attr); ?>
			<?php echo form_hidden($hidden_post_id); ?>
		<?php echo form_close(); ?>
	</div>
</div>