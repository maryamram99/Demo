<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0
 */

							do_action( 'junotoys_action_page_content_end_text' );
							
							// Widgets area below the content
							junotoys_create_widgets_area( 'widgets_below_content' );
						
							do_action( 'junotoys_action_page_content_end' );
							?>
						</div>
						<?php
						
						do_action( 'junotoys_action_after_page_content' );

						// Show main sidebar
						get_sidebar();

						do_action( 'junotoys_action_content_wrap_end' );
						?>
					</div>
					<?php

					do_action( 'junotoys_action_after_content_wrap' );

					// Widgets area below the page and related posts below the page
					$junotoys_body_style = junotoys_get_theme_option( 'body_style' );
					$junotoys_widgets_name = junotoys_get_theme_option( 'widgets_below_page', 'hide' );
					$junotoys_show_widgets = ! junotoys_is_off( $junotoys_widgets_name ) && is_active_sidebar( $junotoys_widgets_name );
					$junotoys_show_related = junotoys_is_single() && junotoys_get_theme_option( 'related_position', 'below_content' ) == 'below_page';
					if ( $junotoys_show_widgets || $junotoys_show_related ) {
						if ( 'fullscreen' != $junotoys_body_style ) {
							?>
							<div class="content_wrap">
							<?php
						}
						// Show related posts before footer
						if ( $junotoys_show_related ) {
							do_action( 'junotoys_action_related_posts' );
						}

						// Widgets area below page content
						if ( $junotoys_show_widgets ) {
							junotoys_create_widgets_area( 'widgets_below_page' );
						}
						if ( 'fullscreen' != $junotoys_body_style ) {
							?>
							</div>
							<?php
						}
					}
					do_action( 'junotoys_action_page_content_wrap_end' );
					?>
			</div>
			<?php
			do_action( 'junotoys_action_after_page_content_wrap' );

			// Don't display the footer elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ( ! junotoys_is_singular( 'post' ) && ! junotoys_is_singular( 'attachment' ) ) || ! in_array ( junotoys_get_value_gp( 'action' ), array( 'full_post_loading', 'prev_post_loading' ) ) ) {
				
				// Skip link anchor to fast access to the footer from keyboard
				?>
				<a id="footer_skip_link_anchor" class="junotoys_skip_link_anchor" href="#"></a>
				<?php

				do_action( 'junotoys_action_before_footer' );

				// Footer
				$junotoys_footer_type = junotoys_get_theme_option( 'footer_type' );
				if ( 'custom' == $junotoys_footer_type && ! junotoys_is_layouts_available() ) {
					$junotoys_footer_type = 'default';
				}
				get_template_part( apply_filters( 'junotoys_filter_get_template_part', "templates/footer-" . sanitize_file_name( $junotoys_footer_type ) ) );

				do_action( 'junotoys_action_after_footer' );

			}
			?>

			<?php do_action( 'junotoys_action_page_wrap_end' ); ?>

		</div>

		<?php do_action( 'junotoys_action_after_page_wrap' ); ?>

	</div>

	<?php do_action( 'junotoys_action_after_body' ); ?>

	<?php wp_footer(); ?>

</body>
</html>