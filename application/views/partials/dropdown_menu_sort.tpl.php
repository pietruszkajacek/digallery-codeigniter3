<li class="dropdown">
	<a class="dropdown-toggle"
	   data-toggle="dropdown"
	   href="#">
		   <?php echo $nav_list_sort[$sort]; ?>
		<b class="caret"></b>
	</a>
	<ul class="dropdown-menu">
		<li class="nav-header">Sortuj:</li>
		<li class="divider"></li>
		<?php foreach ($nav_list_sort as $sort_key => $nav_item_sort): ?>
			<li <?php echo ($sort_key == $sort ? 'class="active"' : '') ?>>
				<?php
					echo anchor(base_url() . $this->uri->uri_string()
						. ($filter ? "?filter={$filter}" : '' )
						. ($sort_key != 'dd' ? ($filter ? '&' : '?') . "sort={$sort_key}" : '')
						. (!empty($search) ? ($filter || $sort_key != 'dd' ? '&' : '?') . "search={$search}" : ''), $nav_item_sort);
				?>
			</li>
		<?php endforeach; ?>
	</ul>
</li>