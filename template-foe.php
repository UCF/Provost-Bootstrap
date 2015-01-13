<?php
/**
 * Template Name: Foundations of Excellence
 **/
disallow_direct_load( 'page.php' );
get_header();
the_post();
?>
	<div class="row" id="<?php echo $post->post_name; ?>">
		<div class="span8 page-content">
			<h2><?php the_title(); ?></h2>
			<?php the_content(); ?>
		</div>

		<div id="sidebar" class="span3 offset1">
			<?php
				global $sidebar_width;
				if ( isset( $sidebar_width ) ) {
					$width = 'span'.$sidebar_width;
				} else {
					$width = 'span3';
				}
			 ?>
			<div class="row">
				<div id="sidebar-events" class="<?php echo $width; ?>">
					<h3>Events</h3>
					<?php
						$options = get_option( THEME_OPTIONS_NAME );
						display_events( 'h2', $options[ 'foe_events_url' ] );
					?>
				</div>
			</div>
			<div class="row">
				<div id="sidebar-foe-links" class="<?php echo $width; ?>">
					<?php
						// Get the menu name to set as the header
						$menu_location = 'foe-menu';
						$menu_locations = get_nav_menu_locations();
						$menu_object = ( isset( $menu_locations[ $menu_location ] ) ? wp_get_nav_menu_object( $menu_locations[ $menu_location ] ) : null );
						$menu_name = ( isset( $menu_object->name ) ? $menu_object->name : 'Resource Links' );
					?>
					<h3><?php echo esc_html( $menu_name ); ?></h3>
					<?php
						echo wp_nav_menu( array(
							'menu'           => $menu_name,
							'container'      => 'false',
							'menu_class'     => 'unstyled',
							'menu_id'        => '',
							'walker'         => new Bootstrap_Walker_Nav_Menu()
						) );
					?>
				</div>
			</div>
		</div>
	</div>
<?php get_footer(); ?>
