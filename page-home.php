<?php disallow_direct_load('page-home.php');?>
<?php get_header(); the_post();?>
<div class="row page-content" id="<?=$post->post_name?>">
	<div class="span12">
		<div class="row">
			<div class="span5">
			<!-- Provost Quote/Marketing -->
				<div class="row">
					<div id="quote" class="span5">
						<?php the_content()?>
					</div>
				</div>
				<div class="row">
					<!-- News and Announcement Posts -->
					<div id="announcements" class="span5">
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
				</div>
				<div class="row">
					<div class="span5" id="help-search">
						<section>
							<?php $help = get_posts(array(
								'numberposts' => -1,
								'orderby'     => 'title',
								'order'       => 'ASC',
								'post_type'   => 'provost_help',
							));?>
							<label for="help-select">Need Help Finding:</label>
							<select id="help-select" class="select-links">
								<option value="null" selected="selected">(Select a Topic)</option>
								<?php foreach($help as $link):?>
								<option value="<?=get_post_meta($link->ID, 'provost_help_url', True)?>"><?=$link->post_title?></option>
								<?php endforeach;?>
							</select>

							<?php get_search_form();?>
						</section>
					</div>
				</div>
			</div>
			<div class="span7">
				<!-- Slideshow-->
				<?php $gallery = get_home_images();?>
				<?php if ($gallery):?>
				<div id="home-images-carousel" class="carousel slide">
					<!-- Carousel items -->
					<div class="carousel-inner">
						<?=$gallery?>	
					</div>
				</div>
				<?php endif;?>
			</div>
		</div>
		<div class="row" id="middle">
						<div class="span12">
							<div class="row">
					<?php foreach(get_menu_pages('home-menu') as $i=>$page):?>
						<div class="span2"><a href="<?=get_page_link($page->ID)?>">
							<?=get_the_post_thumbnail($page->ID)?>
							<span class="title"><?=$page->post_title?></span>
						</a></div>
					<?php endforeach;?>
				</div>
			</div>
		</div>
		<div id="bottom" class="row">
			<div class="span12">
				<div class="row">
					<!-- Colleges-->
					<div id="home-colleges" class="span5">
						<h3>Colleges</h3>
						<?php foreach(get_posts(array(
							'numberposts' => -1,
							'orderby'     => 'post_title',
							'order'       => 'ASC',
							'post_type'   => 'provost_unit',
							'category'    => get_category_by_slug('college')->term_id,
						)) as $i=>$college): ?>
							<div class="college">
								<?php $url = get_post_meta($college->ID, 'provost_unit_url', True); ;?>
								<?php if($url):?>
								<a href="<?=get_post_meta($college->ID, 'provost_unit_url', True)?>">
									<?=get_the_post_thumbnail($college->ID)?>
									<span class="name"><?=hyphenate($college->post_title)?></span>
								</a>
								<?php else:?>
								<div>
									<?=get_the_post_thumbnail($college->ID)?>
									<span class="name"><?=hyphenate($college->post_title)?></span>
								</div>
								<?php endif;?>
							</div>
						<?php endforeach;?>
					</div>
					<!--Units -->
					<div id="home-resources" class="span4">
						<h3>Resources&nbsp;&amp;&nbsp;Links</h3>
						<div class="sidebar">
						<?php $sidebar_width = 4; get_template_part('includes/resources-menu'); ?>
						</div>
					</div>	
					<div id="sidebar" class="span3">
					<h3>Events at UCF</h3>
						<?php $sidebar_width = 3; get_template_part('includes/sidebar'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer();?>