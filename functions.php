<?php
require_once('functions/base.php');   			# Base theme functions
require_once('custom-taxonomies.php');  		# Where per theme taxonomies are defined
require_once('custom-post-types.php');  		# Where per theme post types are defined
require_once('functions/admin.php');  			# Admin/login functions
require_once('functions/config.php');			# Where per theme settings are registered
require_once('shortcodes.php');         		# Per theme shortcodes

//Add theme-specific functions here.
/**
 * Returns published images as html string
 *
 * @return void
 * @author Jared Lang
 **/
function get_home_images($limit=null, $orderby='menu_order'){
	$limit       = ($limit) ? $limit : -1;
	$home_images = new HomeImage();
	$images      = get_posts(array(
		'numberposts' => -1,
		'orderby'     => $orderby,
		'order'       => 'DESC',
		'post_type'   => $home_images->options('name'),
	));
	if ($images){
		$html = '';
		foreach($images as $image){
			$html .= '<div class="'.($html == '' ? 'active ' : '').'item">'.get_the_post_thumbnail($image->ID, 'full').'</div>';
		}
		return $html;
	}else{
		return '';
	}
}

/**
 * Returns pages associated with the menu defined by $c;
 *
 * @return array
 * @author Jared Lang
 **/
function get_menu_pages($c){
	return get_posts(array(
		'numberposts' => -1,
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
		'post_type'   => 'page',
		'category'    => get_category_by_slug($c)->term_id,
	));
}

function hyphenate($string){
	# Automatic hyphentation is difficult so here's a really stupid solution
	$words = array(
		'Commercialization' => 'Commercializ-<br>ation',
		//'Commercialization' => 'Commercializ<wbr>ation',
	);

	return str_replace(array_keys($words), array_values($words), $string);
}
?>