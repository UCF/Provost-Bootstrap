		</main>
		<footer class="site-footer">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<?php wp_nav_menu( array(
							'theme_location' => 'footer-menu',
							'container' => false,
							'menu_class' => 'list-inline',
							'menu_id' => 'footer-menu',
							'fallback_cb' => false,
							'depth' => 1,
							'walker' => new Bootstrap_Walker_Nav_Menu()
						) );
						?>
					</div>
				</div>
			</div>
		</footer>
	</body>
	<?php echo footer_(); ?>
</html>
