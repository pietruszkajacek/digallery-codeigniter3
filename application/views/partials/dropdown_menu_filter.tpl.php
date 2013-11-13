<li class="dropdown">
	<a class="dropdown-toggle"
	   data-toggle="dropdown"
	   href="#">
		   <?php echo $nav_list_filter[$filter]['desc']; ?>
		<b class="caret"></b>
	</a>
	<ul class="dropdown-menu">
		<li class="nav-header">Filtruj:</li>
		<li class="divider"></li>
		<?php foreach ($nav_list_filter as $filter_key => $nav_item_filter): ?>
			<li <?php echo ($filter_key == $filter ? 'class="active"' : '') ?>>
				<?php
					echo anchor(base_url() . $this->uri->uri_string()
						. ($filter_key ? "?filter={$filter_key}" : '' )
						. ($sort != 'dd' ? ($filter_key ? '&' : '?') . "sort={$sort}" : '') 
						. (!empty($search) ? ($filter_key || $sort != 'dd' ? '&' : '?') . "search={$search}" : ''), $nav_item_filter['desc']);
				?>
			</li>
		<?php endforeach; ?>
	</ul>
</li>