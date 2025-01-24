<?php
$junotoys_woocommerce_sc = junotoys_get_theme_option( 'front_page_woocommerce_products' );
if ( ! empty( $junotoys_woocommerce_sc ) ) {
	?><div class="front_page_section front_page_section_woocommerce<?php
		$junotoys_scheme = junotoys_get_theme_option( 'front_page_woocommerce_scheme' );
		if ( ! empty( $junotoys_scheme ) && ! junotoys_is_inherit( $junotoys_scheme ) ) {
			echo ' scheme_' . esc_attr( $junotoys_scheme );
		}
		echo ' front_page_section_paddings_' . esc_attr( junotoys_get_theme_option( 'front_page_woocommerce_paddings' ) );
		if ( junotoys_get_theme_option( 'front_page_woocommerce_stack' ) ) {
			echo ' sc_stack_section_on';
		}
	?>"
			<?php
			$junotoys_css      = '';
			$junotoys_bg_image = junotoys_get_theme_option( 'front_page_woocommerce_bg_image' );
			if ( ! empty( $junotoys_bg_image ) ) {
				$junotoys_css .= 'background-image: url(' . esc_url( junotoys_get_attachment_url( $junotoys_bg_image ) ) . ');';
			}
			if ( ! empty( $junotoys_css ) ) {
				echo ' style="' . esc_attr( $junotoys_css ) . '"';
			}
			?>
	>
	<?php
		// Add anchor
		$junotoys_anchor_icon = junotoys_get_theme_option( 'front_page_woocommerce_anchor_icon' );
		$junotoys_anchor_text = junotoys_get_theme_option( 'front_page_woocommerce_anchor_text' );
		if ( ( ! empty( $junotoys_anchor_icon ) || ! empty( $junotoys_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
			echo do_shortcode(
				'[trx_sc_anchor id="front_page_section_woocommerce"'
											. ( ! empty( $junotoys_anchor_icon ) ? ' icon="' . esc_attr( $junotoys_anchor_icon ) . '"' : '' )
											. ( ! empty( $junotoys_anchor_text ) ? ' title="' . esc_attr( $junotoys_anchor_text ) . '"' : '' )
											. ']'
			);
		}
	?>
		<div class="front_page_section_inner front_page_section_woocommerce_inner
			<?php
			if ( junotoys_get_theme_option( 'front_page_woocommerce_fullheight' ) ) {
				echo ' junotoys-full-height sc_layouts_flex sc_layouts_columns_middle';
			}
			?>
				"
				<?php
				$junotoys_css      = '';
				$junotoys_bg_mask  = junotoys_get_theme_option( 'front_page_woocommerce_bg_mask' );
				$junotoys_bg_color_type = junotoys_get_theme_option( 'front_page_woocommerce_bg_color_type' );
				if ( 'custom' == $junotoys_bg_color_type ) {
					$junotoys_bg_color = junotoys_get_theme_option( 'front_page_woocommerce_bg_color' );
				} elseif ( 'scheme_bg_color' == $junotoys_bg_color_type ) {
					$junotoys_bg_color = junotoys_get_scheme_color( 'bg_color', $junotoys_scheme );
				} else {
					$junotoys_bg_color = '';
				}
				if ( ! empty( $junotoys_bg_color ) && $junotoys_bg_mask > 0 ) {
					$junotoys_css .= 'background-color: ' . esc_attr(
						1 == $junotoys_bg_mask ? $junotoys_bg_color : junotoys_hex2rgba( $junotoys_bg_color, $junotoys_bg_mask )
					) . ';';
				}
				if ( ! empty( $junotoys_css ) ) {
					echo ' style="' . esc_attr( $junotoys_css ) . '"';
				}
				?>
		>
			<div class="front_page_section_content_wrap front_page_section_woocommerce_content_wrap content_wrap woocommerce">
				<?php
				// Content wrap with title and description
				$junotoys_caption     = junotoys_get_theme_option( 'front_page_woocommerce_caption' );
				$junotoys_description = junotoys_get_theme_option( 'front_page_woocommerce_description' );
				if ( ! empty( $junotoys_caption ) || ! empty( $junotoys_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					// Caption
					if ( ! empty( $junotoys_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<h2 class="front_page_section_caption front_page_section_woocommerce_caption front_page_block_<?php echo ! empty( $junotoys_caption ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( $junotoys_caption, 'junotoys_kses_content' );
						?>
						</h2>
						<?php
					}

					// Description (text)
					if ( ! empty( $junotoys_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
						?>
						<div class="front_page_section_description front_page_section_woocommerce_description front_page_block_<?php echo ! empty( $junotoys_description ) ? 'filled' : 'empty'; ?>">
						<?php
							echo wp_kses( wpautop( $junotoys_description ), 'junotoys_kses_content' );
						?>
						</div>
						<?php
					}
				}

				// Content (widgets)
				?>
				<div class="front_page_section_output front_page_section_woocommerce_output list_products shop_mode_thumbs">
					<?php
					if ( 'products' == $junotoys_woocommerce_sc ) {
						$junotoys_woocommerce_sc_ids      = junotoys_get_theme_option( 'front_page_woocommerce_products_per_page' );
						$junotoys_woocommerce_sc_per_page = count( explode( ',', $junotoys_woocommerce_sc_ids ) );
					} else {
						$junotoys_woocommerce_sc_per_page = max( 1, (int) junotoys_get_theme_option( 'front_page_woocommerce_products_per_page' ) );
					}
					$junotoys_woocommerce_sc_columns = max( 1, min( $junotoys_woocommerce_sc_per_page, (int) junotoys_get_theme_option( 'front_page_woocommerce_products_columns' ) ) );
					echo do_shortcode(
						"[{$junotoys_woocommerce_sc}"
										. ( 'products' == $junotoys_woocommerce_sc
												? ' ids="' . esc_attr( $junotoys_woocommerce_sc_ids ) . '"'
												: '' )
										. ( 'product_category' == $junotoys_woocommerce_sc
												? ' category="' . esc_attr( junotoys_get_theme_option( 'front_page_woocommerce_products_categories' ) ) . '"'
												: '' )
										. ( 'best_selling_products' != $junotoys_woocommerce_sc
												? ' orderby="' . esc_attr( junotoys_get_theme_option( 'front_page_woocommerce_products_orderby' ) ) . '"'
													. ' order="' . esc_attr( junotoys_get_theme_option( 'front_page_woocommerce_products_order' ) ) . '"'
												: '' )
										. ' per_page="' . esc_attr( $junotoys_woocommerce_sc_per_page ) . '"'
										. ' columns="' . esc_attr( $junotoys_woocommerce_sc_columns ) . '"'
						. ']'
					);
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}
