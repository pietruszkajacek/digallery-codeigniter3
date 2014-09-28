<script>
	var DIGALLERY = (function () {
		var _baseUrl = "<?php echo base_url(); ?>";
		return {
			"language": "<?php echo $this->config->item('language'); ?>",
			"baseUrl": _baseUrl,
		}
	})();
</script>