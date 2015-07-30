<?php disallow_direct_load( 'page.php' ); ?>
<?php get_header(); the_post(); ?>

<?php
if ( is_front_page() ) {
	echo get_home_feature();
}
?>

<div class="container">
	<div class="row">
		<div class="col-md-7">
			<article>
				<?php if ( !is_front_page() ): ?>
				<h1 class="article-title">
					<?php the_title(); ?>
				</h1>
				<?php endif; ?>

				<?php the_content(); ?>
			</article>
		</div>
		<div class="col-md-4 col-md-offset-1">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
