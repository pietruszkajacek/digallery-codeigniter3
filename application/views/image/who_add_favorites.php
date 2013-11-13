<table class="table table-hover table-striped">
	<thead>
		<tr>
			<th></th>
			<th>Użytkownik</th>
			<th>Data dodania </th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($favorites as $favorite): ?>
			<tr>
				<td><?php echo ++$offset ?></td>
				<td><?php echo anchor("/profile/{$favorite->user_id}", $favorite->email, array('target' => "_blank")); ?></td>
				<td><?php echo $favorite->add_to_favorites; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php if (isset($pagination_links)) : ?>
	<div class="pagination pagination-right" style="padding-right: 10px; ">
		<?php echo $pagination_links; ?>
	</div>
<?php endif; ?>