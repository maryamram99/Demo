<?php
/**
 * The template to display the page title and breadcrumbs
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0
 */

// Page (category, tag, archive, author) title

if ( junotoys_need_page_title() ) {
	junotoys_sc_layouts_showed( 'title', true );
	junotoys_sc_layouts_showed( 'postmeta', true );
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( is_single() ) {
							?>
							<div class="sc_layouts_title_meta">
							<?php
								junotoys_show_post_meta(
									apply_filters(
										'junotoys_filter_post_meta_args', array(
											'components' => join( ',', junotoys_array_get_keys_by_value( junotoys_get_theme_option( 'meta_parts' ) ) ),
											'counters'   => join( ',', junotoys_array_get_keys_by_value( junotoys_get_theme_option( 'counters' ) ) ),
											'seo'        => junotoys_is_on( junotoys_get_theme_option( 'seo_snippets' ) ),
										), 'header', 1
									)
								);
							?>
							</div>
							<?php
						}

						// Blog/Post title
						?>
						<div class="sc_layouts_title_title">
							<?php
							$junotoys_blog_title           = junotoys_get_blog_title();
							$junotoys_blog_title_text      = '';
							$junotoys_blog_title_class     = '';
							$junotoys_blog_title_link      = '';
							$junotoys_blog_title_link_text = '';
							if ( is_array( $junotoys_blog_title ) ) {
								$junotoys_blog_title_text      = $junotoys_blog_title['text'];
								$junotoys_blog_title_class     = ! empty( $junotoys_blog_title['class'] ) ? ' ' . $junotoys_blog_title['class'] : '';
								$junotoys_blog_title_link      = ! empty( $junotoys_blog_title['link'] ) ? $junotoys_blog_title['link'] : '';
								$junotoys_blog_title_link_text = ! empty( $junotoys_blog_title['link_text'] ) ? $junotoys_blog_title['link_text'] : '';
							} else {
								$junotoys_blog_title_text = $junotoys_blog_title;
							}
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr( $junotoys_blog_title_class ); ?>">
								<?php
								$junotoys_top_icon = junotoys_get_term_image_small();
								if ( ! empty( $junotoys_top_icon ) ) {
									$junotoys_attr = junotoys_getimagesize( $junotoys_top_icon );
									?>
									<img src="<?php echo esc_url( $junotoys_top_icon ); ?>" alt="<?php esc_attr_e( 'Site icon', 'junotoys' ); ?>"
										<?php
										if ( ! empty( $junotoys_attr[3] ) ) {
											junotoys_show_layout( $junotoys_attr[3] );
										}
										?>
									>
									<?php
								}
								echo wp_kses_data( $junotoys_blog_title_text );
								?>
							</h1>
							<?php
							if ( ! empty( $junotoys_blog_title_link ) && ! empty( $junotoys_blog_title_link_text ) ) {
								?>
								<a href="<?php echo esc_url( $junotoys_blog_title_link ); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html( $junotoys_blog_title_link_text ); ?></a>
								<?php
							}

							// Category/Tag description
							if ( ! is_paged() && ( is_category() || is_tag() || is_tax() ) ) {
								the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
							}

							?>
						</div>
						<?php

						// Breadcrumbs
						ob_start();
						do_action( 'junotoys_action_breadcrumbs' );
						$junotoys_breadcrumbs = ob_get_contents();
						ob_end_clean();
						junotoys_show_layout( $junotoys_breadcrumbs, '<div class="sc_layouts_title_breadcrumbs">', '</div>' );
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
