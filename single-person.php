<?php disallow_direct_load( 'single-person.php' ); ?>
<?php get_header(); the_post(); ?>

<article class="person">
	<div class="row">
		<div class="col-md-10 col-md-push-2">
			<h1><?php echo $post->post_title; ?><?php echo ( $title == '' ) ?: ' - ' . $title; ?></h1>
			<?php echo $content = str_replace( ']]>', ']]>', apply_filters( 'the_content', $post->post_content ) ); ?>
		</div>
		<div class="col-md-2 col-md-pull-10 person-details">

			<?php
			$title     = get_post_meta( $post->ID, 'person_jobtitle', True );
			$image_url = get_featured_image_url( $post->ID );
			$email     = get_post_meta( $post->ID, 'person_email', True );
			$phones    = Person::get_phones( $post );
			?>

			<img src="<?php echo $image_url ? $image_url : get_bloginfo( 'stylesheet_directory' ).'/static/img/no-photo.jpg'; ?>">

			<?php if ( count( $phones ) ): ?>
			<ul class="person-phones list-unstyled">
				<?php foreach ( $phones as $phone ): ?>
				<li><?php echo $phone; ?></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>

			<?php if ( $email != '' ): ?>
			<hr>
			<a class="person-email" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
			<?php endif; ?>

		</div>
	</div>
</article>

<?php get_footer(); ?>
