<?php disallow_direct_load( 'search.php' ); ?>
<?php get_header(); ?>

<?php if ( get_theme_option( 'enable_google' ) ): ?>

	<?php
		$domain  = get_theme_option( 'search_domain' );
		$limit   = intval( get_theme_option( 'search_per_page' ) );
		$start   = ( isset( $_GET['start'] ) && is_numeric( $_GET['start'] ) ) ? (int)$_GET['start'] : 0;
		$results = get_search_results( $_GET['s'], $start, $limit, $domain );
	?>

	<article class="page search-results">
		<h1>Search Results</h1>

		<?php if ( count( $results['items'] ) ):?>
			<ul class="result-list">
				<?php foreach ( $results['items'] as $result ):?>
				<li class="item">
					<h2>
						<a class="<?php echo mimetype_to_application( ( $result['mime'] ) ? $result['mime'] : 'text/html' ); ?>" href="<?php echo $result['url']; ?>">
							<?php if ( $result['title'] ):?>
							<?php echo $result['title']; ?>
							<?php else: ?>
							<?php echo substr( $result['url'], 0, 45 ); ?>...
							<?php endif; ?>
						</a>
					</h2>
					<a href="<?php echo $result['url']; ?>" class="ignore-external url sans"><?php echo $result['url']; ?></a>
					<div class="snippet">
						<?php echo str_replace( '<br>', '', $result['snippet'] ); ?>
					</div>
				</li>
			<?php endforeach; ?>
			</ul>

			<?php if ( $start + $limit < $results['number'] ): ?>
			<a class="button more" href="./?s=<?php echo $_GET['s']; ?>&amp;start=<?php echo $start + $limit; ?>">More Results</a>
			<?php endif; ?>

		<?php else: ?>
			<p>No results found for "<?php echo htmlentities( $_GET['s'] ); ?>".</p>
		<?php endif;?>
	</article>

<?php else: ?>

	<?php the_post(); ?>

	<article class="page search-results">
		<h1>Search Results</h1>

		<?php if ( have_posts() ):?>
			<ul class="result-list">
				<?php while ( have_posts() ): the_post(); ?>
				<li class="item">
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<a href="<?php the_permalink(); ?>"><?php the_permalink(); ?></a>
					<div class="snippet">
						<?php the_excerpt(); ?>
					</div>
				</li>
				<?php endwhile; ?>
			</ul>

		<?php else: ?>
			<p>No results found for "<?php echo htmlentities( $_GET['s'] )?>".</p>
		<?php endif;?>
	</article>

<?php endif; ?>

<?php get_footer();?>
