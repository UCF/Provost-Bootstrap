<?php
require_once 'functions/base.php';    // Base theme functions
require_once 'custom-taxonomies.php'; // Where per theme taxonomies are defined
require_once 'custom-post-types.php'; // Where per theme post types are defined
require_once 'functions/admin.php';   // Admin/login functions
require_once 'functions/config.php';  // Where per theme settings are registered
require_once 'shortcodes.php';        // Per theme shortcodes

// Add theme-specific functions here.


/**
 * Remove height and width
 */
function remove_thumbnail_dimensions( $html, $post_id, $post_image_id ) {
	$post_template = get_post_meta( $post_id, '_post_template', true );
	if ( $post_template === 'provost-update-dale.php' ) {
		$html = preg_replace( '/(width|height)=\"\d*\"\s/', '', $html );
		$html = preg_replace( '/ wp-post-image/', '', $html );
	}
	return $html;
}
add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10, 3 );


/**
 * Adds styles set in the Customizer to the frontend.
 **/
function frontend_customizer_css() {
	$home_nav_color = get_theme_mod_or_default( 'home_nav_color' ); // TODO: rename theme mod
	$primary_color  = get_theme_mod_or_default( 'primary_color' );

	ob_start();
?>
	<style>
		.site-header {
			background-color: <?php echo $primary_color; ?>;
		}

		.site-header a {
			color: <?php echo $home_nav_color; ?>;
		}
	</style>
<?php
	echo ob_get_clean();
}
add_action( 'wp_head', 'frontend_customizer_css' );


/**
 * Sets up home page Feature Image/Content CSS for the site head.
 **/
function home_feature_css() {
	$home_nav_color = get_theme_mod_or_default( 'home_nav_color' );
	$home_img_lg = get_theme_mod( 'home_img_lg' ); // TODO: add default
	$home_img_xs = get_theme_mod( 'home_img_xs' ); // TODO: add default
	$home_feature_content = get_theme_mod_or_default( 'home_feature_content' );
	$home_feature_content_color = get_theme_mod_or_default( 'home_feature_content_color' );
	$home_feature_content_shadow = get_theme_mod_or_default( 'home_feature_content_shadow' );
	$home_feature_content_bgcolor = get_theme_mod_or_default( 'home_feature_content_bgcolor' );


	// The Customizer saves uploaded image theme mods as the full-size URL
	// of the attachment, so get the needed thumbnail sizes here:

	$home_img_lg_sized = null;
	$home_img_xs_sized = null;

	if ( $home_img_lg ) {
		$home_img_lg_attachment = attachment_url_to_postid( $home_img_lg );

		if ( $home_img_lg_attachment !== 0 ) {
			$home_img_lg_sized = wp_get_attachment_image_src( $home_img_lg_attachment, 'home_feature_lg' );
		}
	}
	if ( $home_img_xs ) {
		$home_img_xs_attachment = attachment_url_to_postid( $home_img_xs );

		if ( $home_img_xs_attachment !== 0 ) {
			$home_img_xs_sized = wp_get_attachment_image_src( $home_img_xs_attachment, 'home_feature_xs' );
		}
	}

	ob_start();
?>
	<style>
		.home .site-header {
			background-color: transparent;
		}

		#header-pulldown-toggle.active,
		#header-pulldown-toggle:hover,
		#header-pulldown-toggle:active,
		#header-pulldown-toggle:focus {
		  color: <?php echo $home_feature_content_color; ?>;
		}

		#header-pulldown-toggle.active .hamburger,
		#header-pulldown-toggle:hover .hamburger,
		#header-pulldown-toggle:active .hamburger,
		#header-pulldown-toggle:focus .hamburger {
		  border-bottom-color: <?php echo $home_feature_content_color; ?>;
		  border-top-color: <?php echo $home_feature_content_color; ?>;
		}


		<?php if ( $home_img_lg_sized ): ?>
		@media (min-width: 768px) {
			#home-feature {
				background-image: url('<?php echo $home_img_lg_sized[0]; ?>');
			}
		}

		/* IE8 fallback */
		.ie8 #home-feature {
			background-image: url('<?php echo $home_img_lg_sized[0]; ?>');
		}
		<?php endif; ?>

		<?php if ( $home_img_xs_sized ): ?>
		@media (max-width: 767px) {
			#home-feature {
				background-image: url('<?php echo $home_img_xs_sized[0]; ?>');
			}
		}
		<?php endif; ?>

		#home-feature {
			background-color: <?php echo $home_feature_content_bgcolor; ?>;
		}


		<?php if ( $home_img_xs_sized ): ?>
		.home-feature-mobile-placeholder {
			width: <?php echo $home_img_xs_sized[1]; ?>px;
		}

		.home-feature-mobile-placeholder div {
			padding-top: <?php echo ( $home_img_xs_sized[2] / $home_img_xs_sized[1] ) * 100; ?>%;
		}
		<?php endif; ?>


		.site-title,
		.home-feature-content,
		.home-feature-content a {
			color: <?php echo $home_feature_content_color; ?>;
			<?php if ( $home_feature_content_shadow ): ?>
			text-shadow: 0 2px 10px rgba(0, 0, 0, .2);
			<?php endif; ?>
		}

		.site-title {
			display: none;
		}

		@media (max-width: 991px) {
			.site-title {
				color: <?php echo $home_nav_color; ?>;
				display: block;
				text-shadow: 0 0 0 transparent;
			}
		}
	</style>
<?php
	echo ob_get_clean();
}
add_action( 'wp_head', 'home_feature_css' );


/**
 * Returns markup for the home page Feature Image/Content.
 **/
function get_home_feature() {
	$home_feature_content = wptexturize( do_shortcode( get_theme_mod_or_default( 'home_feature_content' ) ) );

	ob_start();
?>
	<div id="home-feature">
		<div class="home-feature-mobile-placeholder"><div></div></div>
		<div class="home-feature-content">
			<div class="container">
				<div class="row">
					<div class="col-md-7 col-sm-7">
						<h1 class="site-title" id="home-site-title"><?php echo bloginfo( 'name' ); ?></h1>
						<?php echo $home_feature_content; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	return ob_get_clean();
}


/**
 * Returns a theme option, the option's default defined in
 * Config::$setting_defaults, or $fallback.
 **/
function get_option_or_default( $option, $fallback='' ) {
	return get_option( $option, get_setting_default( $option, $fallback ) );
}


/**
 * Returns a theme mod, the theme mod's default defined in
 * Config::$setting_defaults, or $fallback.
 **/
function get_theme_mod_or_default( $mod, $fallback='' ) {
	return get_theme_mod( $mod, get_setting_default( $mod, $fallback ) );
}

?>
