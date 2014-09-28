<footer>
	<div class="container footer">
		<p class='copyText'>&copy; <?php echo date('Y') . $this->lang->line('dc_all_r_res'); ?></p>
		<p class="last-update"><?php echo 'Ostatnia aktualizacja: ' . date('Y-m-d H:i:s', filemtime($_SERVER['SCRIPT_FILENAME'])) . ' (CodeIgniter ' . CI_VERSION . ')'; ?></p>
	</div>
</footer>