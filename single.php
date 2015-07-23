<?php disallow_direct_load( 'single.php' ); ?>
<?php get_header(); the_post(); ?>

<article id="<?php echo $post->post_name; ?>">
	<?php if ( !is_front_page() ): ?>
		<h1><?php the_title(); ?></h1>
	<?php endif; ?>
	<?php the_content(); ?>
</article>

<?php get_footer(); ?>
