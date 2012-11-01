<?php
/**
 * Template Name: One Column
 **/
?>
<?php disallow_direct_load('template-one-column.php');?>
<?php get_header(); the_post();?>
	<div class="row" id="<?=$post->post_name?>">
		<div class="span12 page-content">
			<h2><?php the_title();?></h2>
			<?php the_content();?>
		</div>
	</div>
<?php get_footer();?>