<?php

/**
 * Include the defined publication, referenced by pub title:
 *
 *     [publication name="Where are the robots Magazine"]
 **/
function sc_publication($attr, $content=null){
	$pub      = @$attr['pub'];
	$pub_name = @$attr['name'];
	$pub_id   = @$attr['id'];
	
	if (!$pub and is_numeric($pub_id)){
		$pub = get_post($pub);
	}
	if (!$pub and $pub_name){
		$pub = get_page_by_title($pub_name, OBJECT, 'publication');
	}
	
	$pub->url   = get_post_meta($pub->ID, "publication_url", True);
	$pub->thumb = get_the_post_thumbnail($pub->ID, 'publication-thumb');
	
	ob_start(); ?>
	
	<div class="pub">
		<a class="track pub-track" title="<?=$pub->post_title?>" data-toggle="modal" href="#pub-modal-<?=$pub->ID?>">
			<?=$pub->thumb?>
			<span><?=$pub->post_title?></span>
		</a>
		<p class="pub-desc"><?=$pub->post_content?></p>
		<div class="modal hide fade" id="pub-modal-<?=$pub->ID?>" role="dialog" aria-labelledby="<?=$pub->post_title?>" aria-hidden="true">
			<iframe src="<?=$pub->url?>" width="100%" height="100%" scrolling="no"></iframe>
			<a href="#" class="btn" data-dismiss="modal">Close</a>
		</div>
	</div>
	
	<?php
	return ob_get_clean();
}
add_shortcode('publication', 'sc_publication');



function sc_person_picture_list($atts) {
	$atts['type']	= ($atts['type']) ? $atts['type'] : null;
	$row_size 		= ($atts['row_size']) ? (intval($atts['row_size'])) : 5;
	$categories		= ($atts['categories']) ? $atts['categories'] : null;
	$org_groups		= ($atts['org_groups']) ? $atts['org_groups'] : null;
	$limit			= ($atts['limit']) ? (intval($atts['limit'])) : -1;
	$join			= ($atts['join']) ? $atts['join'] : 'or';
	$people 		= sc_object_list(
						array(
							'type' => 'person', 
							'limit' => $limit,
							'join' => $join,
							'categories' => $categories, 
							'org_groups' => $org_groups
						), 
						array(
							'objects_only' => True,
						));
	
	ob_start();
	
	?><div class="person-picture-list"><?
	$count = 0;
	foreach($people as $person) {
		
		$image_url = get_featured_image_url($person->ID);
		
		$link = ($person->post_content != '') ? True : False;
		if( ($count % $row_size) == 0) {
			if($count > 0) {
				?></div><?
			}
			?><div class="row"><?
		}
		
		?>
		<div class="span2 person-picture-wrap">
			<? if($link) {?><a href="<?=get_permalink($person->ID)?>"><? } ?>
				<img src="<?=$image_url ? $image_url : get_bloginfo('stylesheet_directory').'/static/img/no-photo.jpg'?>" />
				<div class="name"><?=Person::get_name($person)?></div>
				<div class="title"><?=get_post_meta($person->ID, 'person_jobtitle', True)?></div>
				<? if($link) {?></a><?}?>
		</div>
		<?
		$count++;
	}
	?>	</div>
	</div>
	<?
	return ob_get_clean();
}
add_shortcode('person-picture-list', 'sc_person_picture_list');

/**
 * Post search
 *
 * @return string
 * @author Chris Conover
 **/
function sc_post_type_search($params=array(), $content='') {
	$defaults = array(
		'post_type_name'         => 'post',
		'taxonomy'               => 'category',
		'show_empty_sections'    => false,
		'non_alpha_section_name' => 'Other',
		'column_width'           => 'span4',
		'column_count'           => '3',
		'order_by'               => 'post_title',
		'order'                  => 'ASC',
		'show_sorting'           => True,
		'default_sorting'        => 'term',
		'show_sorting'           => True
	);

	$params = ($params === '') ? $defaults : array_merge($defaults, $params);

	$params['show_empty_sections'] = (bool)$params['show_empty_sections'];
	$params['column_count']        = is_numeric($params['column_count']) ? (int)$params['column_count'] : $defaults['column_count'];
	$params['show_sorting']        = (bool)$params['show_sorting'];

	if(!in_array($params['default_sorting'], array('term', 'alpha'))) {
		$params['default_sorting'] = $default['default_sorting'];
	}

	// Resolve the post type class
	if(is_null($post_type_class = get_custom_post_type($params['post_type_name']))) {
		return '<p>Invalid post type.</p>';
	}
	$post_type = new $post_type_class;

	// Set default search text if the user didn't
	if(!isset($params['default_search_text'])) {
		$params['default_search_text'] = 'Find a '.$post_type->singular_name;
	}

	// Register if the search data with the JS PostTypeSearchDataManager
	// Format is array(post->ID=>terms) where terms include the post title
	// as well as all associated tag names
	$search_data = array();
	foreach(get_posts(array('numberposts' => -1, 'post_type' => $params['post_type_name'])) as $post) {
		$search_data[$post->ID] = array($post->post_title);
		foreach(wp_get_object_terms($post->ID, 'post_tag') as $term) {
			$search_data[$post->ID][] = $term->name;
		}
	}
	?>
	<script type="text/javascript">
		if(typeof PostTypeSearchDataManager != 'undefined') {
			PostTypeSearchDataManager.register(new PostTypeSearchData(
				<?=json_encode($params['column_count'])?>,
				<?=json_encode($params['column_width'])?>,
				<?=json_encode($search_data)?>
			));
		}
	</script>
	<?

	// Split up this post type's posts by term
	$by_term = array();
	foreach(get_terms($params['taxonomy']) as $term) {
		$posts = get_posts(array(
			'numberposts' => -1,
			'post_type'   => $params['post_type_name'],
			'tax_query'   => array(
				array(
					'taxonomy' => $params['taxonomy'],
					'field'    => 'id',
					'terms'    => $term->term_id
				)
			),
			'orderby'     => $params['order_by'],
			'order'       => $params['order']
		));

		if(count($posts) == 0 && $params['show_empty_sections']) {
			$by_term[$term->name] = array();
		} else {
			$by_term[$term->name] = $posts;
		}
	}

	// Split up this post type's posts by the first alpha character
	$by_alpha = array();
	$by_alpha_posts = get_posts(array(
		'numberposts' => -1,
		'post_type'   => $params['post_type_name'],
		'orderby'     => 'post_title',
		'order'       => 'alpha'
	));
	foreach($by_alpha_posts as $post) {
		if(preg_match('/([a-zA-Z])/', $post->post_title, $matches) == 1) {
			$by_alpha[strtoupper($matches[1])][] = $post;
		} else {
			$by_alpha[$params['non_alpha_section_name']][] = $post;
		}
	}
	ksort($by_alpha);

	if($params['show_empty_sections']) {
		foreach(range('a', 'z') as $letter) {
			if(!isset($by_alpha[strtoupper($letter)])) {
				$by_alpha[strtoupper($letter)] = array();
			}
		}
	}

	$sections = array(
		'post-type-search-term'  => $by_term,
		'post-type-search-alpha' => $by_alpha,
	);

	ob_start();
	?>
	<div class="post-type-search">
		<div class="post-type-search-header">
			<form class="post-type-search-form" action="." method="get">
				<label style="display:none;">Search</label>
				<input type="text" class="span3" placeholder="<?=$params['default_search_text']?>" />
			</form>
		</div>
		<div class="post-type-search-results "></div>
		<? if($params['show_sorting']) { ?>
		<div class="btn-group post-type-search-sorting">
			<button class="btn<?if($params['default_sorting'] == 'term') echo ' active';?>"><i class="icon-list-alt"></i></button>
			<button class="btn<?if($params['default_sorting'] == 'alpha') echo ' active';?>"><i class="icon-font"></i></button>
		</div>
		<? } ?>
	<?

	foreach($sections as $id => $section) {
		$hide = false;
		switch($id) {
			case 'post-type-search-alpha':
				if($params['default_sorting'] == 'term') {
					$hide = True;
				}
				break;
			case 'post-type-search-term':
				if($params['default_sorting'] == 'alpha') {
					$hide = True;
				}
				break;
		}
		?>
		<div class="<?=$id?>"<? if($hide) echo ' style="display:none;"'; ?>>
			<? foreach($section as $section_title => $section_posts) { ?>
				<? if(count($section_posts) > 0 || $params['show_empty_sections']) { ?>
					<div>
						<h3><?=esc_html($section_title)?></h3>
						<div class="row">
							<? if(count($section_posts) > 0) { ?>
								<? $posts_per_column = ceil(count($section_posts) / $params['column_count']); ?>
								<? foreach(range(0, $params['column_count'] - 1) as $column_index) { ?>
									<? $start = $column_index * $posts_per_column; ?>
									<? $end   = $start + $posts_per_column; ?>
									<? if(count($section_posts) > $start) { ?>
									<div class="<?=$params['column_width']?>">
										<ul>
										<? foreach(array_slice($section_posts, $start, $end) as $post) { ?>
											<li data-post-id="<?=$post->ID?>"><?=$post_type->toHTML($post)?></li>
										<? } ?>
										</ul>
									</div>
									<? } ?>
								<? } ?>
							<? } ?>
						</div>
					</div>
				<? } ?>
			<? } ?>
		</div>
		<?
	}
	?> </div> <?
	return ob_get_clean();
}
add_shortcode('post-type-search', 'sc_post_type_search');

function sc_faculty_award_programs($attrs) {
	$provost_award_program = new AwardProgram();
	$programs = get_posts(array(
		'numberposts' => -1,
		'orderby'     => $orderby,
		'order'       => 'ASC',
		'post_type'   => $provost_award_program->options('name'),
	));
	ob_start();
	?>
	<div class="faculty-award-programs">
		<h3>Faculty Award Programs</h3>
		<div class="row">
		<?php 
			$count = 0;
			foreach($programs as $program) {
				if($count > 0 && ($count % 4) == 0 ) {
					echo '</div><div class="row">';
				}
		?>
				<div class="span2">
					<?php
						$url = get_post_meta($program->ID, 'provost_award_url', True);
						if($url[0] == "/") $url = site_url() . $url;
						printf('<a href="%s">%s<span class="caption">%s</span></a>',
							$url,
							get_the_post_thumbnail($program->ID),
							$program->post_title
						);
					?>
				</div>
		<?php 
				$count++;
			} 
		?>
		</div>
	</div>
	<?
	return ob_get_clean();
}
add_shortcode('sc-faculty-award-programs', 'sc_faculty_award_programs');

function sc_org_chart($attrs) {
	$deans_list = get_posts(array(
		'numberposts' => 1,
		'post_type'   => 'provost_form',
		'category'    => get_category_by_slug('deans-list')->term_id,
	));
	if (count($deans_list)){
		$deans_list = $deans_list[0];
	}
	$org_chart = get_posts(array(
		'numberposts' => 1,
		'post_type'   => 'provost_form',
		'category'    => get_category_by_slug('org-chart')->term_id,
	));
	if (count($org_chart)){
		$org_chart = $org_chart[0];
	}
	
	
	$category = get_category_by_slug('college-deans');
	$college_deans = ($category) ? get_posts(array(
		'numberposts' => -1,
		'post_type'   => 'profile',
		'category'    => $category->term_id,
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
	)) : false;
	
	$category = get_category_by_slug('academic-officers');
	$academic_officers = ($category) ? get_posts(array(
		'numberposts' => -1,
		'post_type'   => 'profile',
		'category'    => $category->term_id,
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
	)) : false;
	
	$category = get_category_by_slug('administrative-staff');
	$administrative_staff = ($category) ? get_posts(array(
		'numberposts' => -1,
		'post_type'   => 'profile',
		'category'    => get_category_by_slug('administrative-staff')->term_id,
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
	)) : false;
	
	function display_people($people, $id=null){
		?>
		<div class="row"><div class="span8">
		<?
		$count = 0;
		foreach($people as $person) {
			if($count > 0 && ($count % 5) == 0) {
				echo '</div></div><div class="row"><div class="span8">';
			}
			?>
			<div class="person">
			<a href="<?=get_permalink($person->ID)?>">
			<?php
				$img = get_the_post_thumbnail($person->ID);
				if ($img):?>
				<?=$img?>
				<?php else:?>
					<img src="<?=THEME_IMG_URL?>/no-photo.png" alt="Photo Unavailable" />
				<?php endif;?>
				<span class="name"><?=str_replace('', '&nbsp;', $person->post_title)?></span>
			</a>
			<span class="description"><?=get_post_meta($person->ID, 'profile_description', True)?></span>	
			</div>
			<?
			$count++;
		}
		?> </div></div> <?
	}
	ob_start();
	?>
	<div id="org-chart">
		<?php if ($academic_officers):?>
		<h3><?=get_category_by_slug('academic-officers')->name ?> <small><a href="<?=Document::get_url($org_chart)?>">Download PDF <?=$org_chart->post_title?></a></small></h3>
		<?php display_people($academic_officers, 'academic-officers');?>
		<?php endif;?>
		
		<?php if ($college_deans):?>
		<h3><?=get_category_by_slug('college-deans')->name ?> <small><a href="<?=Document::get_url($deans_list)?>">Download PDF <?=$deans_list->post_title?></a></small></h3>
		<?php display_people($college_deans, 'college-deans');?>
		<?php endif;?>
		
		<?php if ($administrative_staff):?>
		<h3><?=get_category_by_slug('administrative-staff')->name ?></h3>
		<?php display_people($administrative_staff, 'administrative-staff');?>
		<?php endif;?>
	</div>
	<?
	return ob_get_clean();
}
add_shortcode('sc-org-chart', 'sc_org_chart');

function sc_recent_proposals($attrs) {
    $proposal_limit = $attrs['limit'];
    $proposals = get_posts(array(
        'post_type'   => 'process_improvement',
        'numberposts' => $proposal_limit,
        'orderby'     => 'date',
        'order'       => 'DESC',
    ));
    ob_start();
    ?>
    <div>
        <h3>Submitted Proposals</h3>
        <table id="pi_proposal_list" class="table table-striped">
            <thead>
                <tr>
                    <th class="submitted">SUBMITTED</th>
                    <th class="description">DESCRIPTION</th>
                    <th>ACTION</th>
                    <th>STATUS</th>
                    <th>OUTCOME</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($proposals as $post): $date = new DateTime($post->post_date);?>
                <tr>
                    <td class="submitted"><?=$date->format('m/d/Y'); ?></td>
                    <td class="description"><?=get_post_meta($post->ID, 'process_improvement_description', true); ?></td>
                   <td><?= get_post_meta($post->ID, 'process_improvement_action', true); ?></td>
                   <td>
                    <?php $status_image = get_post_meta($post->ID, 'process_improvement_status_icon', true); ?>
                    <?php if (!empty($status_image)): ?>
                        <img src="<?=THEME_IMG_URL . '/' . $status_image; ?>" />
                    <?php endif; ?>
                       <?=get_post_meta($post->ID, 'process_improvement_status', true); ?>
                    </td>
                     <td>
                    <?php $meta_value = get_post_meta($post->ID, 'process_improvement_outcome_doc', true); ?>
                    <?php if(!empty($meta_value)): ?>
                        <a href="<?=wp_get_attachment_url($meta_value); ?>">Outcome</a><a class="<?=ProcessImprovement::get_document_application($post); ?>" href="<?=wp_get_attachment_url($meta_value); ?>"></a>
                    <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('sc-recent-proposals', 'sc_recent_proposals');

function sc_all_proposals($attrs) {
    $proposals = get_posts(array(
        'post_type'   => 'process_improvement',
        'numberposts' => -1,
        'orderby'     => 'date',
        'order'       => 'DESC',
    ));

    if (count($proposals) > 0) {
        $proposal = $proposals[0];
        $last_proposal = $proposals[count($proposals) - 1];

        $date = new DateTime($proposal->post_date);
        $end_date = new DateTime($last_proposal->post_date);
        $interval = new DateInterval("P1M");

        // So the first year will be printed
        $year = intval($date->format('Y')) + 1;

        $proposal_index = 0;
        $proposal_date = $date;

        ob_start();
        while ($date >= $end_date) {
            if ($year != intval($date->format('Y'))) {
                $year = intval($date->format('Y'));
            ?>
                <br />
                <h3 class="pi_proposal_year"><?=$year; ?></h3>
            <?php } ?>
            <h4><?=$date->format('F'); ?></h4>

            <?php if ($proposal_date->format('Ym') == $date->format('Ym')): ?>
                <table id="pi_proposal_list" class="table table-striped">
                    <thead>
                    <tr>
                        <th class="submitted">SUBMITTED</th>
                        <th class="description">DESCRIPTION</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                        <th>OUTCOME</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while($proposal_date->format('Ym') == $date->format('Ym') && $proposal_index < count($proposals)): setup_postdata($proposal); ?>

                        <tr>
                            <td class="submitted"><?=$proposal_date->format('m/d/Y'); ?></td>
                            <td class="description"><?=get_post_meta($proposal->ID, 'process_improvement_description', true); ?></td>
                            <td>
                                <?php $status_image = get_post_meta($proposal->ID, 'process_improvement_status_icon', true); ?>
                                <?php if (!empty($status_image)): ?>
                                <img src="<?=THEME_IMG_URL . '/' . $status_image; ?>" />
                                <?php endif; ?>
                                <?=get_post_meta($proposal->ID, 'process_improvement_status', true); ?>
                            </td>
                            <td><?=get_post_meta($proposal->ID, 'process_improvement_action', true); ?></td>
                            <td>
                                <?php $meta_value = get_post_meta($proposal->ID, 'process_improvement_outcome_doc', true); ?>
                                <?php if(!empty($meta_value)): ?>
                                <a href="<?=wp_get_attachment_url($meta_value); ?>">Outcome</a><a class="<?=ProcessImprovement::get_document_application($proposal); ?>" href="<?=wp_get_attachment_url($meta_value); ?>"></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php $proposal = $proposals[(++$proposal_index)] ?>
                        <?php $proposal_date = new DateTime($proposal->post_date); ?>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>


            <?php $date->sub($interval); ?>
    <?php
        }
    }
    return ob_get_clean();
}
add_shortcode('sc-all-proposals', 'sc_all_proposals');