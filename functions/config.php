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

	add_image_size( 'homepage', 620 );
	add_image_size( 'homepage-secondary', 540 );

	register_nav_menu( 'header-menu', __( 'Header Menu' ) );
	register_nav_menu( 'footer-menu', __( 'Footer Menu' ) );

	register_sidebar( array(
		'name'          => __( 'Sidebar' ),
		'id'            => 'sidebar',
		'description'   => 'Sidebar found on two column page templates and search pages',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	) );
	register_sidebar( array(
		'name'          => __( 'Below the Fold - Left' ),
		'id'            => 'bottom-left',
		'description'   => 'Left column on the bottom of pages, after flickr images if enabled.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	) );
	register_sidebar( array(
		'name'          => __( 'Below the Fold - Center' ),
		'id'            => 'bottom-center',
		'description'   => 'Center column on the bottom of pages, after news if enabled.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	) );
	register_sidebar( array(
		'name'          => __( 'Below the Fold - Right' ),
		'id'            => 'bottom-right',
		'description'   => 'Right column on the bottom of pages, after events if enabled.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	) );
	register_sidebar( array(
		'name' => __( 'Footer - Column One' ),
		'id' => 'bottom-one',
		'description' => 'Far left column in footer on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
	) );
	register_sidebar( array(
		'name' => __( 'Footer - Column Two' ),
		'id' => 'bottom-two',
		'description' => 'Second column from the left in footer, on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
	) );
	register_sidebar( array(
		'name' => __( 'Footer - Column Three' ),
		'id' => 'bottom-three',
		'description' => 'Third column from the left in footer, on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
	) );
	register_sidebar( array(
		'name' => __( 'Footer - Column Four' ),
		'id' => 'bottom-four',
		'description' => 'Far right in footer on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
	) );

	global $timer;
	$timer = Timer::start();

	set_defaults_for_options();
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
 * Set theme constants
 **/

define( 'THEME_OPTIONS_GROUP', 'settings' );
define( 'THEME_OPTIONS_NAME', 'theme' );
define( 'THEME_OPTIONS_PAGE_TITLE', 'Theme Options' );

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
	'Video',
	'Document',
	'Publication',
	'Page',
	'Person',
	'Post'
);


Config::$custom_taxonomies = array(
	'OrganizationalGroups'
);


Config::$body_classes = array( 'default', );


/**
 * Configure theme settings, see abstract class Field's descendants for
 * available fields. -- functions/base.php
 * */
Config::$theme_settings = array(
	'Analytics' => array(
		new TextField( array(
			'name'        => 'Google WebMaster Verification',
			'id'          => THEME_OPTIONS_NAME.'[gw_verify]',
			'description' => 'Example: <em>9Wsa3fspoaoRE8zx8COo48-GCMdi5Kd-1qFpQTTXSIw</em>',
			'default'     => null,
			'value'       => get_theme_option( 'gw_verify' ),
		) ),
		new TextField( array(
			'name'        => 'Google Analytics Account',
			'id'          => THEME_OPTIONS_NAME.'[ga_account]',
			'description' => 'Example: <em>UA-9876543-21</em>. Leave blank for development.',
			'default'     => null,
			'value'       => get_theme_option( 'ga_account' ),
		) ),
	),
	'Events' => array(
		new RadioField( array(
			'name'        => 'Enable Events Below the Fold',
			'id'          => THEME_OPTIONS_NAME.'[enable_events]',
			'description' => 'Display events in the bottom page content, appearing on most pages.',
			'default'     => 1,
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
			'value'       => get_theme_option( 'enable_events' ),
		) ),
		new RadioField( array(
			'name'        => 'Enable Events on Search Page',
			'id'          => THEME_OPTIONS_NAME.'[enable_search_events]',
			'description' => 'Display events on the search results page.',
			'value'       => get_theme_option( 'enable_search_events' ),
			'default'     => 1,
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
		) ),
		new SelectField( array(
			'name'        => 'Events Max Items',
			'id'          => THEME_OPTIONS_NAME.'[events_max_items]',
			'description' => 'Maximum number of events to display whenever outputting event information.',
			'value'       => get_theme_option( 'events_max_items' ),
			'default'     => 4,
			'choices'     => array(
				'1' => 1,
				'2' => 2,
				'3' => 3,
				'4' => 4,
				'5' => 5,
			),
		) ),
		new TextField( array(
			'name'        => 'Events Calendar URL',
			'id'          => THEME_OPTIONS_NAME.'[events_url]',
			'description' => 'Base URL for the calendar you wish to use. Example: <em>http://events.ucf.edu/mycalendar</em>',
			'value'       => get_theme_option( 'events_url' ),
			'default'     => 'http://events.ucf.edu/upcoming/feed.rss',
		) ),
	),
	'News' => array(
		new RadioField( array(
			'name'        => 'Enable News Below the Fold',
			'id'          => THEME_OPTIONS_NAME.'[enable_news]',
			'description' => 'Display UCF Today news in the bottom page content, appearing on most pages.',
			'default'     => 1,
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
			'value'       => get_theme_option( 'enable_news' ),
		) ),
		new SelectField( array(
			'name'        => 'News Max Items',
			'id'          => THEME_OPTIONS_NAME.'[news_max_items]',
			'description' => 'Maximum number of articles to display when outputting news information.',
			'value'       => get_theme_option( 'news_max_items' ),
			'default'     => 2,
			'choices'     => array(
				'1' => 1,
				'2' => 2,
				'3' => 3,
				'4' => 4,
				'5' => 5,
			),
		) ),
		new TextField( array(
			'name'        => 'News Feed',
			'id'          => THEME_OPTIONS_NAME.'[news_url]',
			'description' => 'Use the following URL for the news RSS feed <br />Example: <em>http://today.ucf.edu/feed/</em>',
			'value'       => get_theme_option( 'news_url' ),
			'default'     => 'http://today.ucf.edu/feed/',
		) ),
	),
	'Search' => array(
		new RadioField( array(
			'name'        => 'Enable Google Search',
			'id'          => THEME_OPTIONS_NAME.'[enable_google]',
			'description' => 'Enable to use the google search appliance to power the search functionality.',
			'default'     => 1,
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
			'value'       => get_theme_option( 'enable_google' ),
		) ),
		new TextField( array(
			'name'        => 'Search Domain',
			'id'          => THEME_OPTIONS_NAME.'[search_domain]',
			'description' => 'Domain to use for the built-in google search.  Useful for development or if the site needs to search a domain other than the one it occupies. Example: <em>some.domain.com</em>',
			'default'     => null,
			'value'       => get_theme_option( 'search_domain' ),
		) ),
		new TextField( array(
			'name'        => 'Search Results Per Page',
			'id'          => THEME_OPTIONS_NAME.'[search_per_page]',
			'description' => 'Number of search results to show per page of results',
			'default'     => 10,
			'value'       => get_theme_option( 'search_per_page' ),
		) ),
	),
	'Site' => array(
		new TextField( array(
			'name'        => 'Contact Email',
			'id'          => THEME_OPTIONS_NAME.'[site_contact]',
			'description' => 'Contact email address that visitors to your site can use to contact you.',
			'value'       => get_theme_option( 'site_contact' ),
		) ),
		new TextField( array(
			'name'        => 'Organization Name',
			'id'          => THEME_OPTIONS_NAME.'[organization_name]',
			'description' => 'Your organization\'s name',
			'value'       => get_theme_option( 'organization_name' ),
		) ),
		new FileField( array(
			'name'        => 'Home Image',
			'id'          => THEME_OPTIONS_NAME.'[site_image]',
			'description' => 'Image to feature on the homepage.',
			'value'       => get_theme_option( 'site_image' ),
		) ),
		new TextareaField( array(
			'name'        => 'Site Description',
			'id'          => THEME_OPTIONS_NAME.'[site_description]',
			'description' => 'A quick description of your organization and its role.',
			'default'     => 'This is the site\'s default description, change or remove it on the <a href="'.get_admin_url().'admin.php?page=theme-options#site">theme options page</a> in the admin site.',
			'value'       => get_theme_option( 'site_description' ),
		) ),
	),
	'Social' => array(
		new TextField( array(
			'name'        => 'Facebook URL',
			'id'          => THEME_OPTIONS_NAME.'[facebook_url]',
			'description' => 'URL to the facebook page you would like to direct visitors to.  Example: <em>https://www.facebook.com/CSBrisketBus</em>',
			'default'     => null,
			'value'       => get_theme_option( 'facebook_url' ),
		) ),
		new TextField( array(
			'name'        => 'Twitter URL',
			'id'          => THEME_OPTIONS_NAME.'[twitter_url]',
			'description' => 'URL to the twitter user account you would like to direct visitors to.  Example: <em>http://twitter.com/csbrisketbus</em>',
			'value'       => get_theme_option( 'twitter_url' ),
		) ),
		new RadioField( array(
			'name'        => 'Enable Flickr',
			'id'          => THEME_OPTIONS_NAME.'[enable_flickr]',
			'description' => 'Automatically display flickr images throughout the site',
			'default'     => 1,
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
			'value'       => get_theme_option( 'enable_flickr' ),
		) ),
		new TextField( array(
			'name'        => 'Flickr Photostream ID',
			'id'          => THEME_OPTIONS_NAME.'[flickr_id]',
			'description' => 'ID of the flickr photostream you would like to show pictures from.  Example: <em>65412398@N05</em>',
			'default'     => '36226710@N08',
			'value'       => get_theme_option( 'flickr_id' ),
		) ),
		new SelectField( array(
			'name'        => 'Flickr Max Images',
			'id'          => THEME_OPTIONS_NAME.'[flickr_max_items]',
			'description' => 'Maximum number of flickr images to display',
			'value'       => get_theme_option( 'flickr_max_items' ),
			'default'     => 12,
			'choices'     => array(
				'6'  => 6,
				'12' => 12,
				'18' => 18,
			),
		) ),
	),
	'Styles' => array(
		new SelectField( array(
			'name'        => 'Header Menu Styles',
			'id'          => THEME_OPTIONS_NAME.'[bootstrap_menu_styles]',
			'description' => 'Adjust the styles that the header menu links will use.  Non-default options Twitter Bootstrap navigation components for sub-navigation support.',
			'default'     => 'default',
			'choices'     => array(
				'Default (list of links with dropdowns)'  => 'default',
				'Tabs with dropdowns' => 'nav-tabs',
				'Pills with dropdowns' => 'nav-pills'
			),
			'value'       => get_theme_option( 'bootstrap_menu_styles' ),
		) ),
	),
);


/**
 * If Yoast SEO is activated, assume we're handling ALL SEO-related
 * modifications with it.  Don't add Facebook Opengraph theme options.
 **/
if ( !is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
	array_unshift( Config::$theme_settings['Social'],
		new RadioField(array(
			'name'        => 'Enable OpenGraph',
			'id'          => THEME_OPTIONS_NAME.'[enable_og]',
			'description' => 'Turn on the opengraph meta information used by Facebook.',
			'default'     => 1,
			'choices'     => array(
				'On'  => 1,
				'Off' => 0,
			),
			'value'       => get_theme_option( 'enable_og' ),
	    )),
		new TextField(array(
			'name'        => 'Facebook Admins',
			'id'          => THEME_OPTIONS_NAME.'[fb_admins]',
			'description' => 'Comma seperated facebook usernames or user ids of those responsible for administrating any facebook pages created from pages on this site. Example: <em>592952074, abe.lincoln</em>',
			'default'     => null,
			'value'       => get_theme_option( 'fb_admins' ),
		))
	);
}


Config::$links = array(
	array( 'rel' => 'shortcut icon', 'href' => THEME_IMG_URL.'/favicon.ico', ),
	array( 'rel' => 'alternate', 'type' => 'application/rss+xml', 'href' => get_bloginfo( 'rss_url' ), ),
);


Config::$styles = array(
	array( 'admin' => True, 'src' => THEME_CSS_URL.'/admin.css', ),
	THEME_CSS_URL . '/style.min.css'
);


Config::$scripts = array(
	array( 'admin' => True, 'src' => THEME_JS_URL.'/admin.js', ),
	array( 'name' => 'ucfhb-script', 'src' => '//universityheader.ucf.edu/bar/js/university-header.js', ),
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
