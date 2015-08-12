<?php
/**
 * Template Name: JSON Only
 **/
disallow_direct_load( 'template-json-only.php' );
the_post();

if ( $post ):

	if ( is_preview() ) : get_header();
	?>

	<div class="row" id="<?php echo $post->post_name; ?>">
		<div class="span-10">
			<h2><?php echo the_title(); ?>
			<?php echo the_content(); ?>
		</div>
	</div>

	<?php get_footer(); else:

	$stylesheet_id = get_post_meta( $post->ID, 'page_stylesheet', True );
	$stylesheet_url = '';
	if ( $stylesheet_id !== False ) {
		$stylesheet_url = wp_get_attachment_url( $stylesheet_id );
	}

	$json = array(
		'title' => $post->post_title,
		'content' => apply_filters( 'the_content', $post->post_content ),
		'stylesheet' => $stylesheet_url
	);

	header( 'Content-Type:application/json' );

	echo json_encode( $json );

	endif;

endif;

?>