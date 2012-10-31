<?php 

	the_post();
	$post_template = get_post_meta( $post->ID, 'custom_post_template', true );
	if(empty($post_template)){
		get_template_part('includes/update/tony');
	} else {
		$post_template_parts = explode('-', $post_template);

		if(count($post_template_parts) == 3) {
			$file_parts = explode('.', $post_template_parts[2]);
			if(count($file_parts) == 2) {
				get_template_part('includes/update/'.$file_parts[0]);
			}
		}
	}

?>