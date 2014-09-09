<div class="row">
	<div class="span15">
		<h3><?php echo $user->email; ?><small> użytkownik</small></h3>
		<?php $this->load->view('partials/profile_menu.tpl.php');?>
	</div>
</div>
<div class="row">
	<div class="span15">
		<ul class="nav nav-pills">
			<?php $this->load->view('partials/dropdown_menu_filter.tpl.php');?>
		</ul>
		<div class="profile-browse-galleries">
			<?php $this->load->view('partials/thumbs_galleries.tpl.php'); ?>
			<?php if (!count($thumbs_small_gallery)) : ?>
				<div class="alert alert-info">
					Brak galerii spełniających wybrane kryteria.
				</div>
			<?php endif; ?>			
		</div>
		<ul class="pager">
			<li class="<?php echo ($preview ? '' : 'disabled');?>">
				<a <?php if ($preview) echo 'href="' . base_url() . $this->uri->uri_string() . (empty($get_uri) ? '?' : $get_uri . '&') . 'page=' . ($current_page - 1) . '"';?>>Poprzednia strona</a>
			</li>
			<li class="<?php echo ($next ? '' : 'disabled');?>">
				<a <?php if ($next) echo 'href="' . base_url() . $this->uri->uri_string() . (empty($get_uri) ? '?' : $get_uri . '&') . 'page=' . ($current_page + 1) . '"';?>>Następna strona</a>
			</li>
		</ul>
	</div>
</div>