<div class="container" id="main-content">
	<div class="row">
		<div class="span15">
			<?php $this->load->view('partials/message_error.tpl.php'); ?>
		</div>
	</div>
	<div class="row">
		<div class="span3">
			<?php echo $navi_cats; ?>
		</div>
		<div class="span12">
			<ul class="nav nav-pills">
				<?php $this->load->view('partials/dropdown_menu_filter.tpl.php'); ?>
				<?php $this->load->view('partials/search_input.tpl.php'); ?>
			</ul>
			<?php $this->load->view('partials/thumbs_galleries.tpl.php'); ?>
			<ul class="pager">
				<li class="<?php echo ($preview ? '' : 'disabled'); ?>">
					<a <?php if ($preview) echo 'href="' . base_url() . $this->uri->uri_string() . (empty($get_uri) ? '?' : $get_uri . '&') . 'page=' . ($current_page - 1) . '"'; ?>>Poprzednia strona</a>
				</li>
				<li class="<?php echo ($next ? '' : 'disabled'); ?>">
					<a <?php if ($next) echo 'href="' . base_url() . $this->uri->uri_string() . (empty($get_uri) ? '?' : $get_uri . '&') . 'page=' . ($current_page + 1) . '"'; ?>>Następna strona</a>
				</li>
			</ul>
		</div>
	</div>
</div>