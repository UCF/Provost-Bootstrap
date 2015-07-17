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
		<?php endif;?>

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
		$post_type = get_post_type( $post->ID );
		if (
			( $stylesheet_id = get_post_meta( $post->ID, $post_type.'_stylesheet', True ) ) !== False
			&& ( $stylesheet_url = wp_get_attachment_url( $stylesheet_id ) ) !== False
		):
		?>
		<link rel="stylesheet" href="<?php echo $stylesheet_url; ?>" type="text/css" media="all">
		<?php endif; ?>

	</head>
	<body ontouchstart class="<?php echo body_classes(); ?>">
		<div class="container">
			<div class="row">
				<div id="header" class="row-border-bottom-top">
					<h1 class="span9"><a href="<?php echo bloginfo('url'); ?>"><?php echo bloginfo('name'); ?></a></h1>
					<?php $options = get_option( THEME_OPTIONS_NAME ); ?>
					<?php if ( $options['facebook_url'] or $options['twitter_url'] ): ?>
					<ul class="social menu horizontal span3">
						<?php if ( $options['facebook_url'] ): ?>
						<li><a class="ignore-external facebook" href="<?php echo $options['facebook_url']; ?>">Facebook</a></li>
						<?php endif;?>
						<?php if ( $options['twitter_url'] ): ?>
						<li><a class="ignore-external twitter" href="<?php echo $options['twitter_url']; ?>">Twitter</a></li>
						<?php endif;?>
					</ul>
					<?php else:?>
					<div class="social span3">&nbsp;</div>
					<?php endif;?>
				</div>
			</div>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'header-menu',
				'container' => false,
				'menu_class' => 'menu ' . get_header_styles(),
				'menu_id' => 'header-menu',
				'walker' => new Bootstrap_Walker_Nav_Menu()
			) );
			?>
