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

<article class="page">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php if ( !is_front_page() ): ?>
				<h1 class="article-title">
					<?php the_title(); ?>
				</h1>
				<?php endif; ?>

				<?php the_content(); ?>
			</div>
		</div>
	</div>
</article>


<?php get_footer(); ?>
