<?php disallow_direct_load('single.php');?>
<?php get_header(); the_post();?>
<div class="row" id="<?=$post->post_name?>">
	<div class="span9 page-content">
		<h2><?php the_title();?></h2>
		<?php if($post->post_type == 'profile'): ?>
		<div id="profile" class="pull-left">
			<?=get_the_post_thumbnail($person->ID)?>
			<strong><?=get_post_meta($post->ID, 'profile_description', True)?></strong>
			<?php
				$categories = get_the_category();
				foreach($categories as $c){
					echo $c->name;
				}
			?>
		</div>
		<?php endif; ?>
		<article>
		<?php 
			$content = $post->post_content;
			if(!empty($content)){
				the_content();
			} else {
				echo "Coming soon...";
			}
		?>
		</article>
	</div>
	<div id="sidebar" class="span3">
		<?php get_template_part('includes/sidebar'); ?>
	</div>
	
</div>
<?php get_footer();?>