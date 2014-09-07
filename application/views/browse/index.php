<div class="jumbotron-wrapper">
	<div class="container" id="jumbotron-container">
		<div class="row">
			<div class="col-xs-15">
				<?php $this->load->view('partials/message_error.tpl.php'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-15">
				<div class="jumbotron">
					<h1>Digallery</h1>
					<p>Pokaż światu swoje prace niezależnie czy jesteś amatorem czy profesjonalistą.</p>
					<?php echo form_open("", array('class' => 'form-inline', 'id' => 'register-form', 'role' => 'form')); ?>
					<div class="form-group">
						<label class="sr-only" for="email">Email</label>
						<input type="email" name="email" class="form-control" placeholder="Email">
					</div>
					<div class="form-group">
						<label class="sr-only" for="password">Hasło</label>
						<input type="password" class="form-control" name="password" placeholder="Hasło">
					</div>					
					<?php echo form_hidden($csrf); ?>
					<div class="form-group">
						<label class="sr-only" for="password_confirm">Powtórz hasło</label>
						<input type="password" class="form-control" name="password_confirm" placeholder="Powtórz hasło">
					</div>	
					<button type="submit" class="btn btn-success btn-lg">Zarejestruj się za darmo</button>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-xs-15 thumbs-mini-images-row">
			<?php $this->load->view('partials/thumbs_mini.tpl.php'); ?>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-xs-14 thumbs-gallery-row">
			<?php $this->load->view('partials/thumbs_galleries.tpl.php'); ?>	
		</div>
	</div>	
</div>

