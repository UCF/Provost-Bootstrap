<?php @header( 'HTTP/1.1 404 Not found', true, 404 ); ?>
<?php disallow_direct_load( '404.php' ); ?>

<?php get_header(); the_post(); ?>

<article class="page">
	<h1>Page Not Found</h1>
	<p>The page you requested doesn't exist.  Sorry about that.</p>
</article>

<?php get_footer(); ?>
