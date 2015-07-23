<?php disallow_direct_load( 'page.php' ); ?>
<?php get_header(); the_post(); ?>

<article class="page">
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
</article>

<?php get_footer(); ?>
