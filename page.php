<?php disallow_direct_load( 'page.php' ); ?>
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
			</div>
		</div>
		<div class="row">
			<div class="col-md-7">
				<?php the_content(); ?>
			</div>
			<div class="col-md-4 col-md-offset-1">
				<?php get_sidebar(); ?>
			</div>
		</div>
</div>

<?php get_footer(); ?>
