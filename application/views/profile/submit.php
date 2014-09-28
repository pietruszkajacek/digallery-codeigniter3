<div class="container" id="main-content">
	<div class="row">
		<div id="content-submit" class="span11 offset2">
			<?php $this->load->view('partials/message_error.tpl.php'); ?>
			<?php $this->load->view('partials/validation_error.tpl.php'); ?>
			<?php if (isset($image)) : ?>
				<h3>Edycja pracy... <small>Zmień parametry pracy</small></h3>
			<?php else : ?>
				<h3>Dodaj pracę... <small>Umieszczanie prac w serwisie</small></h3>
			<?php endif; ?>
			<hr>
			<?php if (isset($image)) : ?>
				<?php echo form_open("profile/submit/" . (isset($image) ? $image->id : ''), $form_attr); ?>
			<?php else : ?>
				<?php echo form_open_multipart("profile/submit/", $form_attr); ?>
			<?php endif; ?>
			<div>
				<div id="ctrl-gr-title" class="control-group<?php echo $control_groups['title']; ?>">
					<?php echo form_label($title_label['text'], $title_label['for'], $title_label['attributes']); ?>
					<div class="controls">
						<?php echo form_input($title); ?>
						<p class="help-block">...tytuł umieszczanej pracy</p>
					</div>
				</div>
				<?php if (!isset($image)) : ?>
					<div id="ctrl-gr-file" class="control-group<?php echo $control_groups['file']; ?>">
						<?php echo form_label($file_label['text'], $file_label['for'], $file_label['attributes']); ?>
						<div class="controls well">
							<?php echo form_upload($file); ?>
							<p class="help-block">
								typy plików <span class="label label-success">.jpg</span>, <span class="label label-success">.gif</span>, <span class="label label-success">.png</span>.<br>
								rozmiar pliku nie może być większy niż <span class="label label-important">500kb</span>
							</p>
						</div>
					</div>
				<?php endif; ?>
				<div id="ctrl-gr-category" class="control-group<?php echo $control_groups['categories']; ?>">
					<?php echo form_label($categories_label['text'], $categories_label['for'], $categories_label['attributes']); ?>
					<div class="controls">
						<div id="select-categories-modal" class="modal hide fade">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">×</button>
								<h3>Wybór kategorii</h3>
							</div>
							<div class="modal-body modal-body-cats">
								<div id="wrapper-categories">

								</div>
							</div>
							<div class="modal-footer">
								<a href="#" id="select-btn" class="btn btn-success disabled">Wybierz</a>
								<a href="#" class="btn btn-primary" data-dismiss="modal">Powrót</a>
							</div>
						</div>
						<a id="select-categories-button" href="#select-categories-modal" class="btn btn-info disabled"><?php echo $category_path; ?></a>
						<?php
						//echo form_hidden($category_name);
						echo form_hidden($category_id);
						?>
						<p class="help-block">...kliknij na przycisk by wybrać bądź zmienić kategorię</p>
					</div>
				</div>
				<div class="control-group">
					<?php echo form_label($description_label['text'], $description_label['for'], $description_label['attributes']); ?>
					<div class="controls">
						<?php echo form_textarea($description); ?>
						<p class="help-block">...krótki opis pracy</p>
					</div>
				</div>
				<div class="control-group">
					<?php echo form_label($tags_label['text'], $tags_label['for'], $tags_label['attributes']); ?>
					<div class="controls">
						<?php echo form_input($tags); ?>
						<p class="help-block">...słowa kluczowe związane z pracą (do roździelania słów użyj odstępu)</p>
					</div>
				</div>
				<div class="control-group">
					<?php echo form_label($allow_comm_label['text'], $allow_comm_label['for'], $allow_comm_label['attributes']); ?>
					<div class="controls">
						<label class="checkbox">
							<?php echo form_checkbox($allow_comments); ?>
							pracę będzie można komentować.
						</label>
					</div>
				</div>
				<div class="control-group">
					<?php echo form_label($allow_eval_label['text'], $allow_eval_label['for'], $allow_eval_label['attributes']); ?>
					<div class="controls">
						<label class="checkbox">
							<?php echo form_checkbox($allow_evaluated); ?>
							pracę będzie można ocenić.
						</label>
					</div>
				</div>			
				<div class="control-group">
					<?php echo form_label($mature_label['text'], $mature_label['for'], $mature_label['attributes']); ?>
					<div class="controls">
						<label class="checkbox">
							<?php echo form_checkbox($mature); ?>
							praca zawiera treści przeznaczone wyłącznie dla osób pełnoletnich.
						</label>
					</div>
				</div>
				<div id="ctrl-gr-statement" class="control-group<?php echo $control_groups['statement'] . ' ' . (isset($image) ? 'no-visible' : ''); ?>">
					<?php echo form_label($statement_label['text'], $statement_label['for'], $statement_label['attributes']); ?>
					<div class="controls">
						<label class="checkbox">
							<?php echo form_checkbox($statement); ?>
							umieszczając tę pracę w serwisie <strong>digallery.pl</strong> oświadczam, że praca jest mojego autorstwa i stanowi moją własność.
						</label>
					</div>
				</div>

				<div class="form-actions">
					<button type="submit" class="btn btn-primary">Wyślij</button>
				</div>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>