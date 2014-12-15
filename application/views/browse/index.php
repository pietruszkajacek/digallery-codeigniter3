<main role="main">
	<div class="container">
		<h1>Digallery</h1>
		<p>Pokaż światu swoje prace niezależnie czy jesteś amatorem czy profesjonalistą.</p>
		<?php echo form_open("", array('class' => 'form-inline', 'id' => 'register-form', 'role' => 'form')); ?>
		<div class="form-group">
			<div class="form-group">
				<label class="sr-only" for="email">Adres email</label>
				<input type="email" name="email" class="form-control input-lg" placeholder="Podaj email">
			</div>
		</div>
		<div class="form-group">
			<label class="sr-only" for="password">Hasło</label>
			<input type="password" name="password" class="form-control input-lg" placeholder="Hasło">
		</div>
		<div class="form-group">
			<label class="sr-only" for="password_confirm">Hasło</label>
			<input type="password" name="password_confirm" class="form-control input-lg" placeholder="Powtórz hasło">
		</div>
		<br />
		<br />
		<button type="button" class="btn btn-success btn-lg">Zarejestruj się za darmo</button>
		<?php echo form_close(); ?>
	</div>
</main>

<div class="container">
	<div class="row">
		<div class="span14 thumbs-mini-images-row">
			<?php //$this->load->view('partials/thumbs_mini.tpl.php'); ?>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="span14 thumbs-gallery-row">
			<?php //$this->load->view('partials/thumbs_galleries.tpl.php'); ?>	
		</div>
	</div>	
</div>

