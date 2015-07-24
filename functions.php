<?php
require_once 'functions/base.php';    // Base theme functions
require_once 'custom-taxonomies.php'; // Where per theme taxonomies are defined
require_once 'custom-post-types.php'; // Where per theme post types are defined
require_once 'functions/admin.php';   // Admin/login functions
require_once 'functions/config.php';  // Where per theme settings are registered
require_once 'shortcodes.php';        // Per theme shortcodes

// Add theme-specific functions here.


/**
 * Remove height and width
 */
function remove_thumbnail_dimensions( $html, $post_id, $post_image_id ) {
	$post_template = get_post_meta( $post_id, '_post_template', true );
	if ( $post_template === 'provost-update-dale.php' ) {
		$html = preg_replace( '/(width|height)=\"\d*\"\s/', '', $html );
		$html = preg_replace( '/ wp-post-image/', '', $html );
	}
	return $html;
}
add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10, 3 );

?>
