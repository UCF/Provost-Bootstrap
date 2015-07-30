<div class="sidebar">
<?php if ( !is_active_sidebar( 'sidebar' ) ):
	echo get_search_form();
else:
	dynamic_sidebar( 'sidebar' );
endif;
?>
</div>
