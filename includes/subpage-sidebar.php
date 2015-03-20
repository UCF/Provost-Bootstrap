<?php 
	global $sidebar_width;
	if(isset($sidebar_width)) {
		$width = 'span'.$sidebar_width;
	} else {
		$width = 'span3'; 
	}
 ?>
<div class="row">
	<h3>Events at UCF</h3>
	<div id="sidebar-events" class="<?php echo $width; ?>">
		<?php display_events(); ?>
	</div>
</div>
<div class="row">
	<h3>Resources &amp; Links</h3>
	<div id="sidebar-hrlinks" class="<?php echo $width; ?>">
		<?=wp_nav_menu(array(
			'menu'           => 'Resources and Links', 
			'container'      => 'false', 
			'menu_class'     => 'unstyled', 
			'menu_id'        => '', 
			'walker'         => new Bootstrap_Walker_Nav_Menu()
			));
		?>
	</div>
</div>