<?php @header( 'HTTP/1.1 404 Not found', true, 404 ); ?>
<?php disallow_direct_load( '404.php' ); ?>

<?php get_header(); the_post(); ?>

<article class="page">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1 class="article-title">Page Not Found</h1>
				<p>The page you requested doesn't exist.  Sorry about that.</p>
			</div>
		</div>
	</div>
</article>

<?php get_footer(); ?>
