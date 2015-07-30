<?php
/**
 * Template Name: One Column
 **/
?>
<?php disallow_direct_load( 'template-one-column.php' ); ?>
<?php get_header(); the_post(); ?>

<?php
if ( is_front_page() ) {
	echo get_home_feature();
}
?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			<article>
				<?php if ( !is_front_page() ): ?>
				<h1 class="article-title">
					<?php the_title(); ?>
				</h1>
				<?php endif; ?>

				<?php the_content(); ?>
			</article>
		</div>
	</div>
</div>


<?php get_footer(); ?>
