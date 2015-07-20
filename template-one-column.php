<?php
/**
 * Template Name: One Column
 **/
?>
<?php get_header(); the_post();?>
	<div class="row page-content" id="<?php echo $post->post_name; ?>">
		<div class="span12">
			<article>
				<?php if ( !is_front_page() ) { ?>
					<h1><?php the_title(); ?></h1>
				<?php } ?>
				<?php the_content(); ?>
			</article>
		</div>
	</div>

	<?php
	$hide_fold = get_post_meta( $post->ID, 'page_hide_fold', True );
	if ( $hide_fold && $hide_fold[0] !== 'On' ) {
		get_template_part( 'includes/below-the-fold' );
	}
	?>
<?php get_footer();?>
