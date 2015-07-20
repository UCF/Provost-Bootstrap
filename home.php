<?php
if ( get_theme_option( 'site_description' ) ) {
	include_once 'template-home-description.php';
}
else {
	include_once 'template-home-nodescription.php';
}
?>
