<?php
	$rseg2 = $this->uri->rsegment(2);
	$rseg3 = $this->uri->rsegment(3);
	$rseg4 = $this->uri->rsegment(4);
?>
<ul class="nav nav-pills nav-stacked">
	<li <?php echo ($rseg4 == 'all') ? 'class="active"' : '' ?>><a href="<?php echo ($rseg4 == 'all') ? "#" : "/profile/{$rseg3}/comments/"; ?>">Wszystkie komentarze</a></li>
	<li <?php echo ($rseg4 == "image") ? 'class="active"' : '' ?>><a href="<?php echo ($rseg4 == "image") ? "#" : "/profile/{$rseg3}/comments/image/"; ?>">Komentarze do prac</a></li>
	<li <?php echo ($rseg4 == "gallery") ? 'class="active"' : '' ?>><a href="<?php echo ($rseg4 == "gallery") ? "#" : "/profile/{$rseg3}/comments/gallery/"; ?>">Komentarze do galerii</a></li>
	<li <?php echo ($rseg4 == "profile") ? 'class="active"' : '' ?>><a href="<?php echo ($rseg4 == "profile") ? "#" : "/profile/{$rseg3}/comments/profile/"; ?>">Komentarze w profilach</a></li>	
</ul>