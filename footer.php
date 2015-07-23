			<div id="footer">

				<?php wp_nav_menu( array(
					'theme_location' => 'footer-menu',
					'container' => 'false',
					'menu_class' => 'menu horizontal',
					'menu_id' => 'footer-menu',
					'fallback_cb' => false,
					'depth' => 1,
					'walker' => new Bootstrap_Walker_Nav_Menu()
				) );
				?>
				<div class="row" id="footer-widget-wrap">
					<div class="footer-widget-1 span3">
						<?php if ( !function_exists( 'dynamic_sidebar' ) or !dynamic_sidebar( 'Footer - Column One' ) ): ?>
							<a class="ignore-external" href="http://www.ucf.edu"><img src="<?php echo THEME_IMG_URL; ?>/logo.png" alt="" title=""></a>
						<?php endif; ?>
					</div>
					<div class="footer-widget-2 span3">
						<?php if ( !function_exists( 'dynamic_sidebar' ) or !dynamic_sidebar( 'Footer - Column Two' ) ): ?>
						&nbsp;
						<?php endif; ?>
					</div>
					<div class="footer-widget-3 span3">
						<?php if ( !function_exists( 'dynamic_sidebar' ) or !dynamic_sidebar( 'Footer - Column Three' ) ): ?>
						&nbsp;
						<?php endif; ?>
					</div>
					<div class="footer-widget-4 span3">
						<?php if ( !function_exists( 'dynamic_sidebar' ) or !dynamic_sidebar( 'Footer - Column Four' ) ): ?>
							<?php
							$site_contact = get_theme_option( 'site_contact' );
							$org_name = get_theme_option( 'organization_name' );

							if ( $site_contact || $org_name ):
							?>
								<div class="maintained">
									Site maintained by the <br>
									<?php if ( $site_contact && $org_name ): ?>
										<a href="mailto:<?php echo $site_contact; ?>"><?php echo $org_name; ?></a>
									<?php elseif ( $site_contact ):?>
										<a href="mailto:<?php echo $site_contact; ?>"><?php echo $site_contact; ?></a>
									<?php elseif ( $org_name ): ?>
										<?php echo $org_name; ?>
									<?php endif; ?>
								</div>
								<?php endif; ?>
							<div class="copyright">&copy; University of Central Florida</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</body>
	<?php echo "\n".footer_()."\n"; ?>
</html>
