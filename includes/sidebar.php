<?php 
	global $sidebar_width;
	if(isset($sidebar_width)) {
		$width = 'span'.$sidebar_width;
	} else {
		$width = 'span3'; 
	}
 ?>
<div class="row">
	<div class="<?php echo $width; ?>">
		<h3>Events at UCF</h3>
	</div>
</div>
<div class="row">
	<div class="<?php echo $width; ?>">
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
	<div class="<?php echo $width; ?>">
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