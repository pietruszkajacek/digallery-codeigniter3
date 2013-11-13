<?php
	$rseg2 = $this->uri->rsegment(2);
	$rseg3 = $this->uri->rsegment(3);
?>
<ul class="nav nav-tabs">
	<li <?php echo ($rseg2 == "index") ? 'class="active"' : '' ?>><a href="<?php echo ($rseg2 == "index") ? "#" : "/profile/{$rseg3}/"; ?>">Profil</a></li>
	<li <?php echo ($rseg2 == "images") ? 'class="active"' : '' ?>><a href="<?php echo ($rseg2 == "images") ? "#" : "/profile/{$rseg3}/images/"; ?>">Prace</a></li>
	<li <?php echo ($rseg2 == "galleries") ? 'class="active"' : '' ?>><a href="<?php echo ($rseg2 == "galleries") ? "#" : "/profile/{$rseg3}/galleries/"; ?>">Galerie</a></li>
	<li <?php echo ($rseg2 == "comments") ? 'class="active"' : '' ?>><a href="<?php echo ($rseg2 == "comments") ? "#" : "/profile/{$rseg3}/comments/"; ?>">Komentarze</a></li>
</ul>