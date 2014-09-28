<div class="container" id="main-content">
	<div class="row">
		<div class="span10">
			<?php $this->load->view('partials/message_error.tpl.php'); ?>
			<h3>Poczta <small>skrzynka odbiorcza</small></h3>
			<hr>
			<ul class="nav nav-pills">
				<li><?php echo anchor('posts/compose/', 'Napisz wiadomość'); ?></li>
				<li class="active"><?php echo anchor('posts/inbox/', 'Odebrane'); ?></li>
				<li><?php echo anchor('posts/outbox/', 'Wysłane'); ?></li>
			</ul>
			<?php echo form_open('posts/inbox/' . $current_page, $form_attr); ?>
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th><input type="checkbox" id="select_all" <?php echo (empty($posts) ? 'disabled' : ''); ?>></th>
						<th>Tytuł</th>
						<th>Autor</th>
						<th>Data</th>
						<th>Usuń</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($posts as $post): ?>
						<tr <?php echo (!$post['viewed'] ? 'style="font-weight: bold;"' : '') ?> >
							<td><input type="checkbox" name="posts[]" value="<?php echo $post['message_id']; ?>"></td>
							<td><?php echo anchor('posts/' . $post['message_id'] . '/in/', $post['subject']); ?></td>
							<td><?php echo anchor('profile/' . $post['from_id'], $post['from']); ?></td>
							<td><?php echo $post['date']; ?></td>
							<td><a class="btn btn-mini" href="#"><i class="icon-trash"></i></a></td>
						</tr>
					<?php endforeach; ?>
					<?php if (empty($posts)) : ?>
						<tr>
							<td></td>
							<td colspan="5">Nie masz żadnych wiadomości...</td>
						</tr>
					<?php endif; ?>
				</tbody>
            </table>
			<?php if (!empty($posts)) : ?>
				<div class="form-actions">
					<button type="submit" class="btn btn-danger">Usuń zaznaczone</button>
				</div>
			<?php endif; ?>
			<?php echo form_close(); ?>
			<div class="pagination pagination-right">
				<?php echo $pagination_links; ?>
			</div>
		</div>
	</div>
</div>