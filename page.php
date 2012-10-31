<?php get_header(); the_post();?>
	<div class="row" id="<?=$post->post_name?>">
		<div class="span9 page-content">
			<h2 class="page-header"><?php the_title();?></h2>
			<?php the_content();?>
		</div>
		
		<div id="sidebar" class="span3">
			<?php get_template_part('includes/sidebar') ?>
		</div>
	</div>
<?php get_footer();?>