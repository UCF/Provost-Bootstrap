<?php disallow_direct_load( 'home.php' ); ?>
<?php get_header(); the_post(); ?>

<?php
if ( is_front_page() ) {
	echo get_home_feature();
}
?>

TODO: what goes here when no static front page has been set?

<?php get_footer(); ?>
