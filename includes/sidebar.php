<div class="row">
	<div class="span3">
		<h3>Events at UCF</h3>
	</div>
</div>
<div class="row">
	<div class="span3">
		<h3>Human Resource Links</h3>
		<?=wp_nav_menu(array(
			'menu'           => 'Human Resource Links', 
			'container'      => 'false', 
			'menu_class'     => 'unstyled', 
			'menu_id'        => '', 
			'walker'         => new Bootstrap_Walker_Nav_Menu()
			));
		?>
	</div>
</div>
<div class="row">
	<div class="span3">
		<h3>Academic Resources and Links</h3>
		<?=wp_nav_menu(array(
			'menu'           => 'Academic Resources and Links', 
			'container'      => 'false', 
			'menu_class'     => 'unstyled', 
			'menu_id'        => '', 
			'walker'         => new Bootstrap_Walker_Nav_Menu()
			));
		?>
	</div>
</div>