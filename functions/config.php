<?php

/**
 * Responsible for running code that needs to be executed as wordpress is
 * initializing.  Good place to register theme support options, widgets,
 * menu locations, etc.
 *
 * @return void
 * @author Jared Lang
 * */
function __init__() {
	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );

	register_nav_menu( 'header-menu', __( 'Header Menu' ) );
	register_nav_menu( 'footer-menu', __( 'Footer Menu' ) );

	register_sidebar( array(
		'name'          => __( 'Sidebar' ),
		'id'            => 'sidebar',
		'description'   => 'Sidebar found on two column page templates and search pages.',
	) );

	global $timer;
	$timer = Timer::start();
}
add_action( 'after_setup_theme', '__init__' );


/**
 * Register frontend scripts and stylesheets.
 **/
function enqueue_frontend_theme_assets() {
	wp_deregister_script( 'l10n' );

	// Register Config css, js
	foreach( Config::$styles as $style ) {
		if ( !isset( $style['admin'] ) || ( isset( $style['admin'] ) && $style['admin'] !== true ) ) {
			Config::add_css( $style );
		}
	}
	foreach( Config::$scripts as $script ) {
		if ( !isset( $script['admin'] ) || ( isset( $script['admin'] ) && $script['admin'] !== true ) ) {
			Config::add_script( $script );
		}
	}

	// Re-register jquery in document head
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', '//code.jquery.com/jquery-1.11.0.min.js' );
	wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_frontend_theme_assets' );


/**
 * Register backend scripts and stylesheets.
 **/
function enqueue_backend_theme_assets() {
	// Register Config css, js
	foreach( Config::$styles as $style ) {
		if ( isset( $style['admin'] ) && $style['admin'] == true ) {
			Config::add_css( $style );
		}
	}
	foreach( Config::$scripts as $script ) {
		if ( isset( $script['admin'] ) && $script['admin'] == true ) {
			Config::add_script( $script );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'enqueue_backend_theme_assets' );


/**
 * Unregisters sidebar widgets that this theme doesn't need.
 **/
function widget_cleanup() {
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Meta' );
	unregister_widget( 'WP_Widget_Categories' );
	unregister_widget( 'WP_Widget_Recent_Comments' );
	unregister_widget( 'WP_Widget_RSS' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
}
add_action( 'widgets_init', 'widget_cleanup', 11 );


/**
 * Set theme constants
 **/

define( 'THEME_OPTIONS_NAME', 'theme' );
define( 'THEME_CUSTOMIZER_PREFIX', 'ucfgeneric_' ); // a unique prefix for panel/section IDs

$theme_options = get_option( THEME_OPTIONS_NAME );

function get_theme_option( $key ) {
	global $theme_options;
	return isset( $theme_options[$key] ) ? $theme_options[$key] : null;
}

// define( 'DEBUG', True );   # Always on
// define( 'DEBUG', False );  # Always off
define( 'DEBUG', isset( $_GET['debug'] ) ); // Enable via get parameter
define( 'THEME_URL', get_bloginfo( 'stylesheet_directory' ) );
define( 'THEME_ADMIN_URL', get_admin_url() );
define( 'THEME_DIR', get_stylesheet_directory() );
define( 'THEME_INCLUDES_DIR', THEME_DIR.'/includes' );
define( 'THEME_STATIC_URL', THEME_URL.'/static' );
define( 'THEME_IMG_URL', THEME_STATIC_URL.'/img' );
define( 'THEME_JS_URL', THEME_STATIC_URL.'/js' );
define( 'THEME_CSS_URL', THEME_STATIC_URL.'/css' );
define( 'GA_ACCOUNT', get_theme_option( 'ga_account' ) );
define( 'CB_UID', get_theme_option( 'cb_uid' ) );
define( 'CB_DOMAIN', get_theme_option( 'cb_domain' ) );


/**
 * Set config values including meta tags, registered custom post types, styles,
 * scripts, and any other statically defined assets that belong in the Config
 * object.
 **/

Config::$custom_post_types = array(
	'Document',
	'Page',
	'Person',
	'Post',
	'Help',
	'Update',
	'Unit',
	'AwardProgram',
    'ProcessImprovement'
);


Config::$custom_taxonomies = array(
	'OrganizationalGroups'
);


Config::$body_classes = array( 'default', );


/**
 * Configure the WP Customizer with panels, sections, settings and
 * controls.
 *
 * Serves as a replacement for Config::$theme_options in this theme.
 *
 * NOTE: Panel and Section IDs should be prefixed with THEME_CUSTOMIZER_PREFIX
 * to avoid conflicts with plugins that may add their own panels/sections to
 * the Customizer.
 *
 * See developer docs for more info:
 * https://developer.wordpress.org/themes/advanced-topics/customizer-api/
 **/

function define_customizer_panels( $wp_customize ) {
	$wp_customize->add_panel(
		THEME_CUSTOMIZER_PREFIX . 'home',
		array(
			'title' => 'Home Page'
		)
	);
}
add_action( 'customize_register', 'define_customizer_panels' );


function define_customizer_sections( $wp_customize ) {
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'home_nav',
		array(
			'title' => 'Main Navigation Styles',
			'panel' => THEME_CUSTOMIZER_PREFIX . 'home'
		)
	);
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'home_image',
		array(
			'title' => 'Feature Image',
			'panel' => THEME_CUSTOMIZER_PREFIX . 'home'
		)
	);
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'home_feature_content',
		array(
			'title' => 'Feature Content',
			'panel' => THEME_CUSTOMIZER_PREFIX . 'home'
		)
	);
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'analytics',
		array(
			'title' => 'Analytics'
		)
	);
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'news',
		array(
			'title'       => 'News',
			'description' => 'Settings for news feeds used throughout the site.'
		)
	);
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'search',
		array(
			'title' => 'Search'
		)
	);
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'contact_info',
		array(
			'title' => 'Contact Information'
		)
	);
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'social',
		array(
			'title' => 'Social Media'
		)
	);

	// Move 'Static Front Page' section to new 'Home Page' panel
	$wp_customize->get_section( 'static_front_page' )->panel = THEME_CUSTOMIZER_PREFIX . 'home';
}
add_action( 'customize_register', 'define_customizer_sections' );


/**
 * Register Customizer Controls and Settings here.
 *
 * NOTE: theme options carried over from the old version of this theme are
 * registered as type 'option', NOT 'theme_mod', to preserve their existing
 * values.
 *
 * Any new settings should be registered here with type 'theme_mod' (and NOT
 * use an array key structure for ID names).
 **/

function define_customizer_fields( $wp_customize ) {

	// Colors
	$wp_customize->add_setting(
		'primary_color',
		array(
			'default'           => '#ffc904',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'primary_color',
			array(
				'label'       => 'Primary Color',
				'section'     => 'colors'
			)
		)
	);


	// Home Page Navigation Styles
	$wp_customize->add_setting(
		'home_nav_color',
		array(
			'default'           => '#000',
			'sanitize_callback' => 'sanitize_hex_color'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'home_nav_color',
			array(
				'label'       => 'Main Navigation Color',
				'description' => 'Modifies the color of the Header Nav menu on the Home Page.  Update this color when black text is not legible against the Home Page Feature Image.',
				'section'     => THEME_CUSTOMIZER_PREFIX . 'home_nav'
			)
		)
	);


	// Home Page Featured Image
	$wp_customize->add_setting(
		'home_img_lg',
		array()
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'home_img_lg',
			array(
				'label'       => 'Home Page Featured Image (large)',
				'description' => 'Featured image shown on desktop and tablet-sized devices.',
				'section'     => THEME_CUSTOMIZER_PREFIX . 'home_image'
			)
		)
	);

	$wp_customize->add_setting(
		'home_img_xs',
		array()
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'home_img_xs',
			array(
				'label'       => 'Home Page Featured Image (x-small)',
				'description' => 'Featured image shown on mobile devices.',
				'section'     => THEME_CUSTOMIZER_PREFIX . 'home_image'
			)
		)
	);


	// Home Page Feature Content
	$wp_customize->add_setting(
		'home_feature_content',
		array()
	);
	$wp_customize->add_control(
		'home_feature_content',
		array(
			'type'        => 'textarea',
			'label'       => 'Feature Content',
			'description' => 'Accepts HTML and shortcode content.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'home_feature_content'
		)
	);

	$wp_customize->add_setting(
		'home_feature_content_color',
		array(
			'default'     => '#fff'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'home_feature_content_color',
			array(
				'label'       => 'Featured Content Color',
				'section'     => THEME_CUSTOMIZER_PREFIX . 'home_feature_content'
			)
		)
	);

	$wp_customize->add_setting(
		'home_feature_content_shadow',
		array(
			'default' => 1
		)
	);
	$wp_customize->add_control(
		'home_feature_content_shadow',
		array(
			'type'        => 'checkbox',
			'label'       => 'Apply shadow on featured content',
			'description' => 'Recommended when the Featured Content Color is light or pure white.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'home_feature_content'
		)
	);

	$wp_customize->add_setting(
		'home_feature_content_bgcolor',
		array(
			'default'     => '#dbb630'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'home_feature_content_bgcolor',
			array(
				'label'       => 'Featured Content Background Color',
				'description' => 'Used behind the Featured Content when displayed on mobile devices.',
				'section'     => THEME_CUSTOMIZER_PREFIX . 'home_feature_content'
			)
		)
	);


	// Analytics
	$wp_customize->add_setting(
		THEME_OPTIONS_NAME . '[gw_verify]',
		array(
			'type'        => 'option',
		)
	);
	$wp_customize->add_control(
		THEME_OPTIONS_NAME . '[gw_verify]',
		array(
			'type'        => 'text',
			'label'       => 'Google WebMaster Verification',
			'description' => 'Example: <em>9Wsa3fspoaoRE8zx8COo48-GCMdi5Kd-1qFpQTTXSIw</em>',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'analytics',
		)
	);

	$wp_customize->add_setting(
		THEME_OPTIONS_NAME . '[gw_account]',
		array(
			'type'        => 'option',
		)
	);
	$wp_customize->add_control(
		THEME_OPTIONS_NAME . '[gw_account]',
		array(
			'type'        => 'text',
			'label'       => 'Google Analytics Account',
			'description' => 'Example: <em>UA-9876543-21</em>. Leave blank for development.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'analytics'
		)
	);


	// News
	$wp_customize->add_setting(
		THEME_OPTIONS_NAME . '[news_max_items]',
		array(
			'default'     => 2,
			'type'        => 'option'
		)
	);
	$wp_customize->add_control(
		THEME_OPTIONS_NAME . '[news_max_items]',
		array(
			'type'        => 'select',
			'label'       => 'News Max Items',
			'description' => 'Maximum number of articles to display when outputting news information.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'news',
			'choices'     => array(
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5
			)
		)
	);

	$wp_customize->add_setting(
		THEME_OPTIONS_NAME . '[news_url]',
		array(
			'default'     => 'http://today.ucf.edu/feed/',
			'type'        => 'option'
		)
	);
	$wp_customize->add_control(
		THEME_OPTIONS_NAME . '[news_url]',
		array(
			'type'        => 'text',
			'label'       => 'News Feed',
			'description' => 'Use the following URL for the news RSS feed <br>Example: <em>http://today.ucf.edu/feed/</em>',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'news'
		)
	);


	// Search
	$wp_customize->add_setting(
		THEME_OPTIONS_NAME . '[enable_google]',
		array(
			'default'     => 1,
			'type'        => 'option'
		)
	);
	$wp_customize->add_control(
		THEME_OPTIONS_NAME . '[enable_google]',
		array(
			'type'        => 'checkbox',
			'label'       => 'Enable Google Search',
			'description' => 'Enable to use the google search appliance to power the search functionality.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'search'
		)
	);

	$wp_customize->add_setting(
		THEME_OPTIONS_NAME . '[search_domain]',
		array(
			'type'        => 'option'
		)
	);
	$wp_customize->add_control(
		THEME_OPTIONS_NAME . '[search_domain]',
		array(
			'type'        => 'text',
			'label'       => 'Search Domain',
			'description' => 'Domain to use for the built-in google search.  Useful for development or if the site needs to search a domain other than the one it occupies. Example: <em>some.domain.com</em>',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'search'
		)
	);

	$wp_customize->add_setting(
		THEME_OPTIONS_NAME . '[search_per_page]',
		array(
			'default'     => 10,
			'type'        => 'option'
		)
	);
	$wp_customize->add_control(
		THEME_OPTIONS_NAME . '[search_per_page]',
		array(
			'type'        => 'number',
			'label'       => 'Search Results Per Page',
			'description' => 'Number of search results to show per page of results',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'search',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 50,
				'step' => 1
			)
		)
	);


	// Contact Info
	$wp_customize->add_setting(
		THEME_OPTIONS_NAME . '[site_contact]',
		array(
			'type'        => 'option'
		)
	);
	$wp_customize->add_control(
		THEME_OPTIONS_NAME . '[site_contact]',
		array(
			'type'        => 'email',
			'label'       => 'Contact Email',
			'description' => 'Contact email address that visitors to your site can use to contact you.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'contact_info'
		)
	);


	// Social Media
	$wp_customize->add_setting(
		THEME_OPTIONS_NAME . '[facebook_url]',
		array(
			'type'        => 'option'
		)
	);
	$wp_customize->add_control(
		THEME_OPTIONS_NAME . '[facebook_url]',
		array(
			'type'        => 'url',
			'label'       => 'Facebook URL',
			'description' => 'URL to the Facebook page you would like to direct visitors to.  Example: <em>https://www.facebook.com/UCF</em>',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'social'
		)
	);

	$wp_customize->add_setting(
		THEME_OPTIONS_NAME . '[twitter_url]',
		array(
			'type'        => 'option'
		)
	);
	$wp_customize->add_control(
		THEME_OPTIONS_NAME . '[twitter_url]',
		array(
			'type'        => 'url',
			'label'       => 'Twitter URL',
			'description' => 'URL to the Twitter user account you would like to direct visitors to.  Example: <em>http://twitter.com/UCF</em>',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'social'
		)
	);


	/**
	 * If Yoast SEO is activated, assume we're handling ALL SEO-related
	 * modifications with it.  Don't add Facebook Opengraph theme options.
	 **/
	include_once ABSPATH . 'wp-admin/includes/plugin.php';

	if ( !is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {

		$wp_customize->add_setting(
			THEME_OPTIONS_NAME . '[enable_og]',
			array(
				'default'     => 1,
				'type'        => 'option'
			)
		);
		$wp_customize->add_control(
			THEME_OPTIONS_NAME . '[enable_og]',
			array(
				'type'        => 'checkbox',
				'label'       => 'Enable Opengraph',
				'description' => 'Turn on the Opengraph meta information used by Facebook.',
				'section'     => THEME_CUSTOMIZER_PREFIX . 'social'
			)
		);

		$wp_customize->add_setting(
			THEME_OPTIONS_NAME . '[fb_admins]',
			array(
				'type'        => 'option'
			)
		);
		$wp_customize->add_control(
			THEME_OPTIONS_NAME . '[fb_admins]',
			array(
				'type'        => 'textarea',
				'label'       => 'Facebook Admins',
				'description' => 'Comma separated facebook usernames or user ids of those responsible for administrating any facebook pages created from pages on this site. Example: <em>592952074, abe.lincoln</em>',
				'section'     => THEME_CUSTOMIZER_PREFIX . 'social'
			)
		);
	}

}
add_action( 'customize_register', 'define_customizer_fields' );



Config::$links = array(
	array( 'rel' => 'shortcut icon', 'href' => THEME_IMG_URL.'/favicon.ico', ),
	array( 'rel' => 'alternate', 'type' => 'application/rss+xml', 'href' => get_bloginfo( 'rss_url' ), ),
);


Config::$styles = array(
	array( 'admin' => True, 'src' => THEME_CSS_URL.'/admin.css', ),
	THEME_CSS_URL . '/style.min.css'
);


// Scripts (output in footer)
Config::$scripts = array(
	array( 'admin' => True, 'src' => THEME_JS_URL.'/admin.js', ),
	array( 'name' => 'ucfhb-script', 'src' => '//universityheader.ucf.edu/bar/js/university-header.js?use-1200-breakpoint=true', ),
	array( 'name' => 'theme-script', 'src' => THEME_JS_URL.'/script.min.js', ),
);


Config::$metas = array(
	array( 'charset' => 'utf-8', ),
	array( 'http-equiv' => 'X-UA-Compatible', 'content' => 'IE=Edge' ),
	array( 'name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0' ),
);

if ( get_theme_option( 'gw_verify' ) ) {
	Config::$metas[] = array(
		'name'    => 'google-site-verification',
		'content' => htmlentities( get_theme_option( 'gw_verify' ) ),
	);
}
