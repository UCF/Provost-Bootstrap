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


function get_events($start=null, $limit=null){
	$options = get_option(THEME_OPTIONS_NAME);
	$qstring = (bool)strpos($options['events_url'], '?');
	$url     = $options['events_url'];
	if (!$qstring){
		$url .= '?';
	}else{
		$url .= '&';
	}
	$url    .= 'upcoming=upcoming&format=rss';
	$events  = array_reverse(FeedManager::get_items($url));
	$events  = array_slice($events, $start, $limit);
	return $events;
}

function display_events($header='h2'){?>
	<?php $options = get_option(THEME_OPTIONS_NAME);?>
	<?php $count   = $options['events_max_items']?>
	<?php $events  = get_events(0, ($count) ? $count : 3);?>
	<?php if(count($events)):?>
		<table class="table table-condensed events">
			<?php foreach($events as $item):?>
			<tr class="item">
				<td class="date">
					<?php
						$month = $item->get_date("M");
						$day   = $item->get_date("j");
					?>
					<div class="month"><?=$month?></div>
					<div class="day"><?=$day?></div>
				</td>
				<td class="title">
					<a href="<?=$item->get_link()?>" class="wrap ignore-external"><?=$item->get_title()?></a>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	<?php else:?>
		<p>Unable to fetch events</p>
	<?php endif;?>
<?php
}

/**
 * Handles fetching and processing of feeds.  Currently uses SimplePie to parse
 * retrieved feeds, and automatically handles caching of content fetches.
 * Multiple calls to the same feed url will not result in multiple parsings, per
 * request as they are stored in memory for later use.
 **/
class FeedManager{
	static private
		$feeds        = array(),
		$cache_length = 3600;
	
	/**
	 * Provided a URL, will return an array representing the feed item for that
	 * URL.  A feed item contains the content, url, simplepie object, and failure
	 * status for the URL passed.  Handles caching of content requests.
	 *
	 * @return array
	 * @author Jared Lang
	 **/
	static protected function __new_feed($url){
		$timer = Timer::start();
		require_once(ABSPATH . WPINC . '/class-feed.php');
		
		$simplepie = null;
		$failed    = False;
		$cache_key = 'feedmanager-'.md5($url);
		$content   = get_site_transient($cache_key);
		
		if ($content === False){
			$content = @file_get_contents($url);
			if ($content === False){
				$failed  = True;
				$content = null;
				error_log('FeedManager failed to fetch data using url of '.$url);
			}else{
				set_site_transient($cache_key, $content, self::$cache_length);
			}
		}
		
		if ($content){
			$simplepie = new SimplePie();
			$simplepie->set_raw_data($content);
			$simplepie->init();
			$simplepie->handle_content_type();
			
			if ($simplepie->error){
				error_log($simplepie->error);
				$simplepie = null;
				$failed    = True;
			}
		}else{
			$failed = True;
		}
		
		$elapsed = round($timer->elapsed() * 1000);
		debug("__new_feed: {$elapsed} milliseconds");
		return array(
			'content'   => $content,
			'url'       => $url,
			'simplepie' => $simplepie,
			'failed'    => $failed,
		);
	}
	
	
	/**
	 * Returns all the items for a given feed defined by URL
	 *
	 * @return array
	 * @author Jared Lang
	 **/
	static protected function __get_items($url){
		if (!array_key_exists($url, self::$feeds)){
			self::$feeds[$url] = self::__new_feed($url);
		}
		if (!self::$feeds[$url]['failed']){
			return self::$feeds[$url]['simplepie']->get_items();
		}else{
			return array();
		}
		
	}
	
	
	/**
	 * Retrieve the current cache expiration value.
	 *
	 * @return void
	 * @author Jared Lang
	 **/
	static public function get_cache_expiration(){
		return self::$cache_length;
	}
	
	
	/**
	 * Set the cache expiration length for all feeds from this manager.
	 *
	 * @return void
	 * @author Jared Lang
	 **/
	static public function set_cache_expiration($expire){
		if (is_number($expire)){
			self::$cache_length = (int)$expire;
		}
	}
	
	
	/**
	 * Returns all items from the feed defined by URL and limited by the start
	 * and limit arguments.
	 *
	 * @return array
	 * @author Jared Lang
	 **/
	static public function get_items($url, $start=null, $limit=null){
		if ($start === null){$start = 0;}
		
		$items = self::__get_items($url);
		$items = array_slice($items, $start, $limit);
		return $items;
	}
}
?>