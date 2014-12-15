<nav id="top-bar" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
	  <div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			  <span class="sr-only">Toggle navigation</span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="/">Digallery</a>
	  </div>
	  <div class="navbar-collapse collapse">
		  <?php if (isset($nav)) : ?>
			  <?php echo $nav; ?>
		  <?php endif; ?>
		  <?php if (isset($dropdown_menu)) : ?>
			  <?php echo $dropdown_menu; ?>
		  <?php endif; ?>
		  <?php if (isset($info_panel)) : ?>
			  <?php echo $info_panel; ?>
		  <?php endif; ?>
	  </div>
  </div>
</nav>