<?php get_header(); the_post();?>
<div class="row page-content" id="<?=$post->post_name?>">
	<div class="span4">
	<!-- Provost Quote/Marketing -->
		<div id="quote">
			<?php the_content()?>
		</div>
		
		<!-- News and Announcement Posts -->
		<div id="announcements">
			<ul class="unstyled">
				<?php foreach(get_posts(array(
					'numberposts' => 3,
					'orderby'     => 'date',
					'order'       => 'DESC',
					'post_type'   => 'post',
					'category'    => get_category_by_slug('announcements')->term_id,
				)) as $post):?>
				<li><a href="<?=get_page_link($post->ID)?>"><?=$post->post_title?></a></li>
				<?php endforeach;?>
			</ul>
		</div>
		<div id="help">
			<?php $help = get_posts(array(
				'numberposts' => -1,
				'orderby'     => 'title',
				'order'       => 'ASC',
				'post_type'   => get_custom_post_type('ProvostHelp'),
			));?>
			<label for="help-select">Need Help Finding:</label>
			<select id="help-select" class="select-links">
				<option value="null" selected="selected">(Select a Topic)</option>
				<?php foreach($help as $link):?>
				<option value="<?=get_post_meta($link->ID, 'provost_help_url', True)?>"><?=$link->post_title?></option>
				<?php endforeach;?>
			</select>
		</div>
		
		<div id="search">
			<?php get_search_form();?>
		</div>
	</div>
	<div class="span8">
		<!-- Slideshow-->
		<?php $gallery = get_home_images();?>
		<?php if ($gallery):?>
		<div class="slideshow">
			<?=$gallery?>
		</div>
		<?php endif;?>
	</div>
</div>
<?php get_footer();?>