<?php disallow_direct_load( 'home.php' ); ?>
<?php get_header(); the_post(); ?>

<?php
if ( is_front_page() ) {
	echo get_home_feature();
}
?>

<div class="container">
	<div class="row">
		<div class="col-md-12">
			TODO: what goes here when no static front page has been set?
		</div>
	</div>
</div>

<?php get_footer(); ?>
