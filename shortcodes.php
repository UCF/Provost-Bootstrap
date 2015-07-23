<?php

function sc_search_form() {
	ob_start();
?>
	<div class="search">
		<?php get_search_form(); ?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'search_form', 'sc_search_form' );


function sc_person_picture_list( $atts ) {
	$atts['type'] = ( $atts['type'] ) ? $atts['type'] : null;
	$row_size     = ( $atts['row_size'] ) ? ( intval( $atts['row_size'] ) ) : 5;
	$categories   = ( $atts['categories'] ) ? $atts['categories'] : null;
	$org_groups   = ( $atts['org_groups'] ) ? $atts['org_groups'] : null;
	$limit        = ( $atts['limit'] ) ? ( intval( $atts['limit'] ) ) : -1;
	$join         = ( $atts['join'] ) ? $atts['join'] : 'or';
	$people       = sc_object_list(
		array(
			'type' => 'person',
			'limit' => $limit,
			'join' => $join,
			'categories' => $categories,
			'org_groups' => $org_groups
		),
		array(
			'objects_only' => True,
	) );

	ob_start();
?>
<div class="person-picture-list">
	<?php
	$count = 0;
	foreach ( $people as $person ) {

		$image_url = get_featured_image_url( $person->ID );

		$link = ( $person->post_content != '' ) ? True : False;
		if ( ( $count % $row_size ) == 0 ) {
			if ( $count > 0 ) {
				?></div><?php
			}
			?><div class="row"><?php
		}
	?>
		<div class="person-picture-wrap">
			<?php if ( $link ): ?><a href="<?php echo get_permalink( $person->ID ); ?>"><?php endif; ?>
				<img src="<?php echo $image_url ? $image_url : get_bloginfo( 'stylesheet_directory' ).'/static/img/no-photo.jpg'; ?>">
				<span class="name"><?php echo Person::get_name( $person ); ?></span>
				<span class="title"><?php echo get_post_meta( $person->ID, 'person_jobtitle', True ); ?></span>
			<?php if ( $link ): ?></a><?php endif; ?>
		</div>
		<?php
		$count++;
	}
	?>
	</div>
</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'person-picture-list', 'sc_person_picture_list' );


/**
 * Post search
 *
 * @return string
 * @author Chris Conover
 * */
function sc_post_type_search( $params=array(), $content='' ) {
	$defaults = array(
		'post_type_name'          => 'post',
		'taxonomy'                => 'category',
		'meta_key'                => '',
		'meta_value'              => '',
		'show_empty_sections'     => false,
		'non_alpha_section_name'  => 'Other',
		'column_width'            => 'col-md-4',
		'column_count'            => '3',
		'order_by'                => 'title',
		'order'                   => 'ASC',
		'show_sorting'            => true,
		'default_sorting'         => 'term',
		'show_sorting'            => true,
		'show_uncategorized'      => false,
		'uncategorized_term_name' => 'Uncategorized'
	);

	$params = ( $params === '' ) ? $defaults : array_merge( $defaults, $params );

	$params['show_empty_sections'] = filter_var( $params['show_empty_sections'], FILTER_VALIDATE_BOOLEAN );
	$params['column_count']        = is_numeric( $params['column_count'] ) ? (int)$params['column_count'] : $defaults['column_count'];
	$params['show_sorting']        = filter_var( $params['show_sorting'], FILTER_VALIDATE_BOOLEAN );

	if ( !in_array( $params['default_sorting'], array( 'term', 'alpha' ) ) ) {
		$params['default_sorting'] = $default['default_sorting'];
	}

	// Resolve the post type class
	if ( is_null( $post_type_class = get_custom_post_type( $params['post_type_name'] ) ) ) {
		return '<p>Invalid post type.</p>';
	}
	$post_type = new $post_type_class;

	// Set default search text if the user didn't
	if ( !isset( $params['default_search_text'] ) ) {
		$params['default_search_text'] = 'Find a '.$post_type->singular_name;
	}

	// Set default search label if the user didn't
	if ( !isset( $params['default_search_label'] ) ) {
		$params['default_search_label'] = 'Find a '.$post_type->singular_name;
	}

	// Register the search data with the JS PostTypeSearchDataManager.
	// Format is array(post->ID=>terms) where terms include the post title
	// as well as all associated tag names
	$search_data = array();
	foreach ( get_posts( array( 'numberposts' => -1, 'post_type' => $params['post_type_name'] ) ) as $post ) {
		$search_data[$post->ID] = array( $post->post_title );
		foreach ( wp_get_object_terms( $post->ID, 'post_tag' ) as $term ) {
			$search_data[$post->ID][] = $term->name;
		}
	}
?>
	<script type="text/javascript">
		if(typeof PostTypeSearchDataManager != 'undefined') {
			PostTypeSearchDataManager.register(new PostTypeSearchData(
				<?php echo json_encode( $params['column_count'] ); ?>,
				<?php echo json_encode( $params['column_width'] ); ?>,
				<?php echo json_encode( $search_data ); ?>
			));
		}
	</script>
	<?php

	// Set up a post query
	$args = array(
		'numberposts' => -1,
		'post_type'   => $params['post_type_name'],
		'tax_query'   => array(
			array(
				'taxonomy' => $params['taxonomy'],
				'field'    => 'id',
				'terms'    => '',
			)
		),
		'orderby'     => $params['order_by'],
		'order'       => $params['order'],
	);

	// Handle meta key and value query
	if ($params['meta_key'] && $params['meta_value']) {
		$args['meta_key'] = $params['meta_key'];
		$args['meta_value'] = $params['meta_value'];
	}

	// Split up this post type's posts by term
	$by_term = array();
	foreach ( get_terms( $params['taxonomy'] ) as $term ) { // get_terms defaults to an orderby=name, order=asc value
		$args['tax_query'][0]['terms'] = $term->term_id;
		$posts = get_posts( $args );

		if ( count( $posts ) == 0 && $params['show_empty_sections'] ) {
			$by_term[$term->name] = array();
		} else {
			$by_term[$term->name] = $posts;
		}
	}

	// Add uncategorized items to posts by term if parameter is set.
	if ( $params['show_uncategorized'] ) {
		$terms = get_terms( $params['taxonomy'], array( 'fields' => 'ids', 'hide_empty' => false ) );
		$args['tax_query'][0]['terms'] = $terms;
		$args['tax_query'][0]['operator'] = 'NOT IN';
		$uncat_posts = get_posts( $args );
		if ( count( $uncat_posts == 0 ) && $params['show_empty_sections'] ) {
			$by_term[$params['uncategorized_term_name']] = array();
		} else {
			$by_term[$params['uncategorized_term_name']] = $uncat_posts;
		}
	}

	// Split up this post type's posts by the first alpha character
	$args['orderby'] = 'title';
	$args['order'] = 'ASC';
	$args['tax_query'] = '';
	$by_alpha_posts = get_posts( $args );
	foreach( $by_alpha_posts as $post ) {
		if ( preg_match( '/([a-zA-Z])/', $post->post_title, $matches ) == 1 ) {
			$by_alpha[strtoupper($matches[1])][] = $post;
		} else {
			$by_alpha[$params['non_alpha_section_name']][] = $post;
		}
	}
	ksort( $by_alpha );
	if( $params['show_empty_sections'] ) {
		foreach( range( 'a', 'z' ) as $letter ) {
			if ( !isset( $by_alpha[strtoupper( $letter )] ) ) {
				$by_alpha[strtoupper( $letter )] = array();
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
				<label><?php echo $params['default_search_label']; ?></label>
				<input type="text" placeholder="<?php echo $params['default_search_text']; ?>">
			</form>
		</div>
		<div class="post-type-search-results"></div>
		<?php if ( $params['show_sorting'] ) { ?>
		<div class="btn-group post-type-search-sorting">
			<button class="btn<?php if ( $params['default_sorting'] == 'term' ) echo ' active'; ?>"><span class="glyphicon glyphicon-list-alt"></i></button>
			<button class="btn<?php if ( $params['default_sorting'] == 'alpha' ) echo ' active'; ?>"><span class="glyphicon glyphicon-font"></i></button>
		</div>
		<?php } ?>
	<?php

	foreach ( $sections as $id => $section ):
		$hide = false;
		switch ( $id ) {
			case 'post-type-search-alpha':
				if ( $params['default_sorting'] == 'term' ) {
					$hide = True;
				}
				break;
			case 'post-type-search-term':
				if ( $params['default_sorting'] == 'alpha' ) {
					$hide = True;
				}
				break;
		}
?>
		<div class="<?php echo $id; ?>"<?php if ( $hide ) { echo ' style="display:none;"'; } ?>>
			<div class="row">
			<?php
			$count = 0;
			foreach ( $section as $section_title => $section_posts ):
				if ( count( $section_posts ) > 0 || $params['show_empty_sections'] ):
			?>

				<?php if ( $section_title == $params['uncategorized_term_name'] ): ?>
					</div>
						<div class="row">
							<div class="<?php echo $params['column_width']; ?>">
								<h3><?php echo esc_html( $section_title ); ?></h3>
							</div>
						</div>

						<div class="row">
						<?php
						// $split_size must be at least 1
						$split_size = max( floor( count( $section_posts ) / $params['column_count'] ), 1 );
						$split_posts = array_chunk( $section_posts, $split_size );
						foreach ( $split_posts as $index => $column_posts ):
						?>
							<div class="<?php echo $params['column_width']; ?>">
								<ul>
								<?php foreach( $column_posts as $key => $post ): ?>
									<li data-post-id="<?php echo $post->ID; ?>">
										<?php echo $post_type->toHTML( $post ); ?><span class="search-post-pgsection"><?php echo $section_title; ?></span>
									</li>
								<?php endforeach; ?>
								</ul>
							</div>
						<?php endforeach; ?>

				<?php else: ?>

					<?php if ( $count % $params['column_count'] == 0 && $count !== 0 ): ?>
						</div><div class="row">
					<?php endif; ?>

					<div class="<?php echo $params['column_width']; ?>">
						<h3><?php echo esc_html( $section_title ); ?></h3>
						<ul>
						<?php foreach( $section_posts as $post ):  ?>
							<li data-post-id="<?php echo $post->ID; ?>">
								<?php echo $post_type->toHTML( $post ); ?><span class="search-post-pgsection"><?php echo $section_title; ?></span>
							</li>
						<?php endforeach; ?>
						</ul>
					</div>

			<?php
					endif;

				$count++;
				endif;

			endforeach;
			?>
			</div><!-- .row -->
		</div><!-- term/alpha section -->

	<?php endforeach; ?>

	</div><!-- .post-type-search -->

<?php
	return ob_get_clean();
}
add_shortcode( 'post-type-search', 'sc_post_type_search' );


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
		foreach ( $programs as $program ) {
			if ( $count > 0 && ( $count % 4 ) == 0 ) {
				echo '</div><div class="row">';
			}
		?>
			<div class="col-md-2">
				<?php
				$url = get_post_meta( $program->ID, 'provost_award_url', True );
				if ( $url[0] == '/' ) { $url = site_url() . $url; }
				printf( '<a href="%s">%s<span class="caption">%s</span></a>',
					$url,
					get_the_post_thumbnail( $program->ID ),
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
<?php
	return ob_get_clean();
}
add_shortcode( 'sc-faculty-award-programs', 'sc_faculty_award_programs' );


function sc_org_chart( $attrs ) {
	$deans_list = get_posts( array(
		'numberposts' => 1,
		'post_type'   => 'provost_form',
		'category'    => get_category_by_slug( 'deans-list' )->term_id,
	) );
	if ( count( $deans_list ) ) {
		$deans_list = $deans_list[0];
	}
	$org_chart = get_posts( array(
		'numberposts' => 1,
		'post_type'   => 'provost_form',
		'category'    => get_category_by_slug( 'org-chart' )->term_id,
	) );
	if ( count( $org_chart ) ) {
		$org_chart = $org_chart[0];
	}


	$category = get_category_by_slug( 'college-deans' );
	$college_deans = ( $category ) ? get_posts( array(
		'numberposts' => -1,
		'post_type'   => 'profile',
		'category'    => $category->term_id,
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
	) ) : false;

	$category = get_category_by_slug( 'academic-officers' );
	$academic_officers = ( $category ) ? get_posts( array(
		'numberposts' => -1,
		'post_type'   => 'profile',
		'category'    => $category->term_id,
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
	) ) : false;

	$category = get_category_by_slug( 'administrative-staff' );
	$administrative_staff = ( $category ) ? get_posts( array(
		'numberposts' => -1,
		'post_type'   => 'profile',
		'category'    => get_category_by_slug( 'administrative-staff' )->term_id,
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
	) ) : false;


	function display_people( $people, $id=null ) {
		ob_start();
	?>
		<div class="row">
			<div class="col-md-8">
			<?php
			$count = 0;
			foreach ( $people as $person ) {
				if ( $count > 0 && ( $count % 5 ) == 0 ) {
					echo '</div></div><div class="row"><div class="col-md-8">';
				}
			?>
				<div class="person">
					<?php
					$img = get_the_post_thumbnail( $person->ID );
					if ( $img ):
					?>
						<?php echo $img; ?>
					<?php else: ?>
						<img src="<?php echo THEME_IMG_URL; ?>/no-photo.png" alt="Photo Unavailable">
					<?php endif;?>

					<span class="name"><?php echo str_replace( '', '&nbsp;', $person->post_title ); ?></span>
					<span class="description"><?php echo get_post_meta( $person->ID, 'profile_description', True ); ?></span>
				</div>
				<?php
				$count++;
			}
			?>
			</div>
		</div>
	<?php
		return ob_get_clean();
	}

	ob_start();
?>
	<div id="org-chart">

		<?php if ( $academic_officers ): ?>
		<h3>
			<?php echo get_category_by_slug( 'academic-officers' )->name . ' '; ?>
			<small>
				<a href="<?php echo Document::get_url( $org_chart ); ?>">Download PDF <?php echo $org_chart->post_title; ?></a>
			</small>
		</h3>
		<?php display_people( $academic_officers, 'academic-officers' ); ?>
		<?php endif; ?>

		<?php if ( $administrative_staff ): ?>
		<h3><?php echo get_category_by_slug( 'administrative-staff' )->name ?></h3>
		<?php display_people( $administrative_staff, 'administrative-staff' ); ?>
		<?php endif; ?>

		<?php if ( $college_deans ): ?>
		<h3>
			<?php echo get_category_by_slug( 'college-deans' )->name . ' '; ?>
			<small>
				<a href="<?php echo Document::get_url( $deans_list ); ?>">Download PDF <?php echo $deans_list->post_title; ?></a>
			</small>
		</h3>
		<?php display_people( $college_deans, 'college-deans' ); ?>
		<?php endif; ?>

	</div>
<?php
	return ob_get_clean();
}
add_shortcode( 'sc-org-chart', 'sc_org_chart' );


function sc_recent_proposals( $attrs ) {
	$proposal_limit = $attrs['limit'];
	$proposals = get_posts( array(
		'post_type'   => 'process_improvement',
		'numberposts' => $proposal_limit,
		'orderby'     => 'date',
		'order'       => 'DESC',
	) );

	ob_start();
?>
	<div>
		<h3>Submitted Proposals</h3>
		<table id="pi-proposal-list" class="table table-striped">
			<thead>
				<tr>
					<th class="submitted">Submitted</th>
					<th class="description">Description</th>
					<th>Status</th>
					<th>Action</th>
					<th>Outcome</th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $proposals as $post ):
				$date = new DateTime( $post->post_date );
			?>
				<tr>
					<td class="submitted">
						<?php echo $date->format( 'm/d/Y' ); ?>
					</td>
					<td class="description">
						<?php echo get_post_meta( $post->ID, 'process_improvement_description', true ); ?>
					</td>
					<td>
						<?php
						$status_image = get_post_meta( $post->ID, 'process_improvement_status_icon', true );
						if ( !empty( $status_image ) ):
						?>
							<img src="<?php echo THEME_IMG_URL . '/' . $status_image; ?>">
						<?php endif; ?>

						<?php echo get_post_meta( $post->ID, 'process_improvement_status', true ); ?>
					</td>
					<td>
						<?php echo get_post_meta( $post->ID, 'process_improvement_action', true ); ?>
					</td>
					<td>
						<?php
						$meta_value = get_post_meta( $post->ID, 'process_improvement_outcome_doc', true );
						$meta_url_value = get_post_meta( $post->ID, 'process_improvement_outcome_url', true );

						if ( !empty( $meta_url_value ) ):
						?>
							<a href="<?php echo $meta_url_value; ?>">Outcome</a>
							<a class="html" href="<?php echo $meta_url_value; ?>"></a>
							<?php if ( !empty( $meta_value ) ): ?>
								<br>
							<?php endif; ?>
						<?php endif; ?>

						<?php if ( !empty( $meta_value ) ): ?>
							<a href="<?php echo wp_get_attachment_url( $meta_value ); ?>">Outcome</a>
							<a class="<?php echo ProcessImprovement::get_document_application( $post ); ?>" href="<?php echo wp_get_attachment_url( $meta_value ); ?>"></a>
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
add_shortcode( 'sc-recent-proposals', 'sc_recent_proposals' );


function sc_all_proposals( $attrs ) {
	$proposals = get_posts( array(
		'post_type'   => 'process_improvement',
		'numberposts' => -1,
		'orderby'     => 'date',
		'order'       => 'DESC',
	) );

	if ( count( $proposals ) > 0 ):
		$proposal = $proposals[0];
		$last_proposal = $proposals[count( $proposals ) - 1];

		$date = new DateTime( $proposal->post_date );
		$end_date = new DateTime( $last_proposal->post_date );
		$interval = new DateInterval( 'P1M' );

		// So the first year will be printed
		$year = intval( $date->format( 'Y' ) ) + 1;

		$proposal_index = 0;
		$proposal_date = $date;

		ob_start();

		while ( $date->format( 'Ym' ) >= $end_date->format( 'Ym' ) ):
			if ( $year != intval( $date->format( 'Y' ) ) ):
				$year = intval( $date->format( 'Y' ) );
			?>
				<br>
				<h3 class="pi-proposal-year"><?php echo $year; ?></h3>
			<?php endif; ?>

			<h4><?php echo $date->format( 'F' ); ?></h4>

			<?php if ( $proposal_date->format( 'Ym' ) == $date->format( 'Ym' ) ): ?>
				<table id="pi-proposal-list" class="table table-striped">
					<thead>
					<tr>
						<th class="submitted">Submitted</th>
						<th class="description">Description</th>
						<th>Status</th>
						<th>Action</th>
						<th>Outcome</th>
					 </tr>
					</thead>
					<tbody>
					<?php while ( $proposal_date->format( 'Ym' ) == $date->format( 'Ym' ) && $proposal_index < count( $proposals ) ): setup_postdata( $proposal ); ?>

						<tr>
							<td class="submitted">
								<?php echo $proposal_date->format( 'm/d/Y' ); ?>
							</td>
							<td class="description">
								<?php echo get_post_meta( $proposal->ID, 'process_improvement_description', true ); ?>
							</td>
							<td>
								<?php
								$status_image = get_post_meta( $proposal->ID, 'process_improvement_status_icon', true );
								if ( !empty( $status_image ) ):
								?>
									<img src="<?php echo THEME_IMG_URL . '/' . $status_image; ?>">
								<?php endif; ?>

								<?php echo get_post_meta( $proposal->ID, 'process_improvement_status', true ); ?>
							</td>
							<td>
								<?php echo get_post_meta( $proposal->ID, 'process_improvement_action', true ); ?>
							</td>
							<td>
								<?php
								$meta_value = get_post_meta( $proposal->ID, 'process_improvement_outcome_doc', true );
								$meta_url_value = get_post_meta( $proposal->ID, 'process_improvement_outcome_url', true );
								if ( !empty( $meta_url_value ) ):
								?>
									<a href="<?php echo $meta_url_value; ?>">Outcome</a>
									<a class="html" href="<?php echo $meta_url_value; ?>"></a>
									<?php if ( !empty( $meta_value ) ): ?>
										<br>
									<?php endif; ?>
								<?php endif; ?>

								<?php if ( !empty( $meta_value ) ): ?>
									<a href="<?php echo wp_get_attachment_url( $meta_value ); ?>">Outcome</a>
									<a class="<?php echo ProcessImprovement::get_document_application( $proposal ); ?>" href="<?php echo wp_get_attachment_url( $meta_value ); ?>"></a>
								<?php endif; ?>
							</td>
						</tr>
						<?php
						$proposal = $proposals[( ++$proposal_index )];
						$proposal_date = new DateTime( $proposal->post_date );
						?>
					<?php endwhile; ?>
					</tbody>
				</table>
			<?php endif; ?>

			<?php $date->sub( $interval ); ?>

<?php
		endwhile;
	endif;

	return ob_get_clean();
}
add_shortcode( 'sc-all-proposals', 'sc_all_proposals' );

?>
