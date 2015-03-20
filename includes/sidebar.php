<?php 
	global $sidebar_width;
	if(isset($sidebar_width)) {
		$width = 'span'.$sidebar_width;
	} else {
		$width = 'span3'; 
	}
 ?>
<div class="row">
	<div id="sidebar-events" class="<?php echo $width; ?>">
		<?php display_events(); ?>
	</div>
</div>