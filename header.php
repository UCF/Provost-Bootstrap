<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?php echo header_(); ?>

		<!--[if lte IE 9]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<?php if ( GA_ACCOUNT or CB_UID ): ?>
		<script>

			<?php if ( GA_ACCOUNT ): ?>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			ga('create', '<?php echo GA_ACCOUNT; ?>', 'auto');
			ga('send', 'pageview');
			<?php endif; ?>

			<?php if ( CB_UID ): ?>
			var CB_UID      = '<?php echo CB_UID; ?>';
			var CB_DOMAIN   = '<?php echo CB_DOMAIN; ?>';
			<?php endif; ?>

		</script>
		<?php endif; ?>

		<script>
			var PostTypeSearchDataManager = {
				'searches' : [],
				'register' : function(search) {
					this.searches.push(search);
				}
			};
			var PostTypeSearchData = function(column_count, column_width, data) {
				this.column_count = column_count;
				this.column_width = column_width;
				this.data         = data;
			};
		</script>


		<?php
		global $post;
		if ( $post ):
			$post_type = get_post_type( $post->ID );
			if (
				( $stylesheet_id = get_post_meta( $post->ID, $post_type . '_stylesheet', True ) ) !== False
				&& ( $stylesheet_url = wp_get_attachment_url( $stylesheet_id ) ) !== False
			):
		?>
				<link rel="stylesheet" href="<?php echo $stylesheet_url; ?>" type="text/css" media="all">
		<?php
			endif;
		endif;
		?>

	</head>
	<body ontouchstart class="<?php echo body_classes(); ?>">
		<header class="site-header">
			<?php if ( is_home() || is_front_page() ): ?>
				<h1 class="site-title">
					<a href="<?php echo home_url(); ?>">
						<?php echo bloginfo( 'name' ); ?>
					</a>
				</h1>
			<?php else: ?>
				<span class="site-title">
					<a href="<?php echo home_url(); ?>">
						<?php echo bloginfo( 'name' ); ?>
					</a>
				</span>
			<?php endif; ?>

			<nav>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'header-menu',
					'container' => false,
					'menu_class' => 'list-inline',
					'menu_id' => 'header-menu',
					'walker' => new Bootstrap_Walker_Nav_Menu()
				) );
				?>
			</nav>
		</header>
		<main class="site-main">
