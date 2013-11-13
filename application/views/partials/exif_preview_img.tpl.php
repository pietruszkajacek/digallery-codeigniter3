<h5>Dane EXIF:</h5>
<hr class="small-margin">
<dl class="dl-horizontal details-preview">
	<dt>Producent aparatu:</dt>
	<dd><?php echo (!empty($preview_image->exif_make) ? $preview_image->exif_make : 'b.d.'); ?></dd>
	<dt>Model aparatu:</dt>
	<dd><?php echo (!empty($preview_image->exif_model) ? $preview_image->exif_model : 'b.d.'); ?></dd>
	<dt>Czas ekspozycji:</dt>
	<dd><?php echo (!empty($preview_image->exif_exposure) ? $preview_image->exif_exposure . ' s' : 'b.d.'); ?></dd>
	<dt>Jednostka przysłony:</dt>
	<dd><?php echo (!empty($preview_image->exif_aperturefnumber) ? $preview_image->exif_aperturefnumber : 'b.d.'); ?></dd>			
	<dt>Data wykonania:</dt>
	<dd><?php echo (!empty($preview_image->exif_date_time) ? $preview_image->exif_date_time : 'b.d.'); ?></dd>
	<dt>Nazwa programu:</dt>
	<dd><?php echo (!empty($preview_image->exif_software) ? $preview_image->exif_software : 'b.d.'); ?></dd>
	<dt>Długość ogniskowej:</dt>
	<dd><?php echo (!empty($preview_image->exif_focal_length) ? $preview_image->exif_focal_length . 'mm' : 'b.d.'); ?></dd>			
	<dt>Szybkość ISO:</dt>
	<dd><?php echo (!empty($preview_image->exif_iso_speed) ? $preview_image->exif_iso_speed : 'b.d.'); ?></dd>						
</dl>		
