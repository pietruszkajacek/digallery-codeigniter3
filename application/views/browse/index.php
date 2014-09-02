<div class="jumbotron-wrapper">
	<div class="container" id="jumbotron-container">
		<div class="row">
			<div class="span15">
				<?php $this->load->view('partials/message_error.tpl.php'); ?>
			</div>
		</div>
		<div class="row">
			<div class="span15">
				<div class="jumbotron">
					<h1>Digallery</h1>
					<p>Pokaż światu swoje prace niezależnie czy jesteś amatorem czy profesjonalistą.</p>
					<?php echo form_open("", array('class' => 'form-inline', 'id' => 'register-form')); ?>
					<input type="text" name="email" class="input-medium" placeholder="Email">
					<?php echo form_hidden($csrf); ?>
					<input type="password" name="password" class="input-medium" placeholder="Hasło">
					<input type="password" name="password_confirm" class="input-medium" placeholder="Powtórz hasło">
					<button type="submit" class="btn btn-success btn-large">Zarejestruj się za darmo</button>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="span14 thumbs-mini-images-row">
			<?php $this->load->view('partials/thumbs_mini.tpl.php'); ?>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="span14 thumbs-gallery-row">
			<?php $this->load->view('partials/thumbs_galleries.tpl.php'); ?>	
		</div>
	</div>	
</div>

