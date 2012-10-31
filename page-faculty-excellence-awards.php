<?php disallow_direct_load('page-faculty-excellence-awards.php');?>
<?php get_header(); the_post();?>
<div class="row" id="<?=$post->post_name?>">
	<div class="span12 page-content">
		<h2 class="page-header"><?php the_title();?></h2>
		<?php the_content();?>
	</div>
</div>
<?php get_footer();?>