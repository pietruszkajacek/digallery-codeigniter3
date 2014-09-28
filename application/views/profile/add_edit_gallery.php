<div class="container" id="main-content">
	<div class="row">
		<div id="content-add-edit-gallery" class="span11">
			<?php $this->load->view('partials/message_error.tpl.php'); ?>
			<?php $this->load->view('partials/validation_error.tpl.php'); ?>
			<?php if (isset($gallery)) : ?>
				<h3>Edycja galerii... <small>Zmień parametry galerii</small></h3>
			<?php else : ?>
				<h3>Dodaj galerię... <small>Umieszczanie galerii w serwisie</small></h3>
			<?php endif; ?>
			<hr>			
			<?php echo form_open("profile/add_edit_gallery/" . (isset($gallery) ? $gallery->id : ''), $form_attr); ?>
			<div>
				<div id="ctrl-gr-gallery-name" class="control-group<?php echo $control_groups['gallery_name']; ?>">
					<?php echo form_label($gallery_name_label['text'], $gallery_name_label['for'], $gallery_name_label['attributes']); ?>
					<div class="controls">
						<?php echo form_input($gallery_name); ?>
						<p class="help-block">...nazwa galerii</p>
					</div>
				</div>
				<div class="control-group">
					<?php echo form_label($gallery_description_label['text'], $gallery_description_label['for'], $gallery_description_label['attributes']); ?>
					<div class="controls">
						<?php echo form_textarea($gallery_description); ?>
						<p class="help-block">...krótki opis galerii</p>
					</div>
				</div>
				<div class="control-group">
					<?php echo form_label($tags_label['text'], $tags_label['for'], $tags_label['attributes']); ?>
					<div class="controls">
						<?php echo form_input($tags); ?>
						<p class="help-block">...słowa kluczowe związane z galerią (do roździelania słów użyj odstępu)</p>
					</div>
				</div>			
				<div class="control-group">
					<?php echo form_label($allow_comm_label['text'], $allow_comm_label['for'], $allow_comm_label['attributes']); ?>
					<div class="controls">
						<label class="checkbox">
							<?php echo form_checkbox($allow_comments); ?>
							galerię będzie można komentować.
						</label>
					</div>
				</div>
				<div class="alert alert-info">
					Aby <spam class="text-success"><strong>dodać</strong></spam> zdjęcie do galerii przeciągnij dane zdjęcie z panelu lewego w obszar panelu prawego.</br>
					Aby <spam class="text-error"><strong>usunąć</strong></spam> zdjęcie z galerii przeciągnij dane zdjęcie z panelu prawego w obszar panelu lewego.<br>
					Aby <spam class="text-info"><strong>zmienić kolejność</strong></spam> zdjęć w galerii (prawy panel) użyj myszki.
				</div>	
				<div id="ctrl-gr-images" class="control-group<?php echo $control_groups['gallery_images']; ?>" style="overflow: auto;">
					<div id="thumbs-images" class="well well-small">
					</div>
					<div id="gallery" class="well well-small">
						<ul class="thumbnails thumbs-add-edit-gallery">
							<?php if (isset($images_in_gallery)) : ?>
								<?php foreach ($images_in_gallery as $image_in_gallery): ?>
									<li data-image-id="<?php echo $image_in_gallery->id; ?>" class="span1 add-edit-gallery-thumb thumbnail-mini dragdrop">
										<div class="thumbnail">
											<div class="container-thumb-mini">
												<div class="outer-block-thumb-mini">
													<?php
													echo img(array('src' => $thumb_mini_config['path'] . $image_in_gallery->file_name, 'alt' => $image_in_gallery->title));
													?>
												</div>
											</div>
										</div>
									</li>
								<?php endforeach; ?>							
							<?php endif; ?>
						</ul>
						<input type="hidden" name="gallery_images_in_gallery">
					</div>
					<div id="wrapper-thumbs-images-pagination">
						<div id="thumbs-images-pagination" class="pagination pagination-mini pagination-right">
						</div>
					</div>
				</div>
				<div class="form-actions">
					<button id="gallery_submit_btn" type="submit" class="btn btn-large btn-block btn-primary">Wyślij</button>
				</div>
			</div>
			<?php echo form_close(); ?>
		</div>
		<div class="span4">
		</div>		
	</div>
</div>