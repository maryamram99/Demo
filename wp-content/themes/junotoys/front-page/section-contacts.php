<div class="front_page_section front_page_section_contacts<?php
	$junotoys_scheme = junotoys_get_theme_option( 'front_page_contacts_scheme' );
	if ( ! empty( $junotoys_scheme ) && ! junotoys_is_inherit( $junotoys_scheme ) ) {
		echo ' scheme_' . esc_attr( $junotoys_scheme );
	}
	echo ' front_page_section_paddings_' . esc_attr( junotoys_get_theme_option( 'front_page_contacts_paddings' ) );
	if ( junotoys_get_theme_option( 'front_page_contacts_stack' ) ) {
		echo ' sc_stack_section_on';
	}
?>"
		<?php
		$junotoys_css      = '';
		$junotoys_bg_image = junotoys_get_theme_option( 'front_page_contacts_bg_image' );
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
	$junotoys_anchor_icon = junotoys_get_theme_option( 'front_page_contacts_anchor_icon' );
	$junotoys_anchor_text = junotoys_get_theme_option( 'front_page_contacts_anchor_text' );
if ( ( ! empty( $junotoys_anchor_icon ) || ! empty( $junotoys_anchor_text ) ) && shortcode_exists( 'trx_sc_anchor' ) ) {
	echo do_shortcode(
		'[trx_sc_anchor id="front_page_section_contacts"'
									. ( ! empty( $junotoys_anchor_icon ) ? ' icon="' . esc_attr( $junotoys_anchor_icon ) . '"' : '' )
									. ( ! empty( $junotoys_anchor_text ) ? ' title="' . esc_attr( $junotoys_anchor_text ) . '"' : '' )
									. ']'
	);
}
?>
	<div class="front_page_section_inner front_page_section_contacts_inner
	<?php
	if ( junotoys_get_theme_option( 'front_page_contacts_fullheight' ) ) {
		echo ' junotoys-full-height sc_layouts_flex sc_layouts_columns_middle';
	}
	?>
			"
			<?php
			$junotoys_css      = '';
			$junotoys_bg_mask  = junotoys_get_theme_option( 'front_page_contacts_bg_mask' );
			$junotoys_bg_color_type = junotoys_get_theme_option( 'front_page_contacts_bg_color_type' );
			if ( 'custom' == $junotoys_bg_color_type ) {
				$junotoys_bg_color = junotoys_get_theme_option( 'front_page_contacts_bg_color' );
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
		<div class="front_page_section_content_wrap front_page_section_contacts_content_wrap content_wrap">
			<?php

			// Title and description
			$junotoys_caption     = junotoys_get_theme_option( 'front_page_contacts_caption' );
			$junotoys_description = junotoys_get_theme_option( 'front_page_contacts_description' );
			if ( ! empty( $junotoys_caption ) || ! empty( $junotoys_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				// Caption
				if ( ! empty( $junotoys_caption ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<h2 class="front_page_section_caption front_page_section_contacts_caption front_page_block_<?php echo ! empty( $junotoys_caption ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( $junotoys_caption, 'junotoys_kses_content' );
					?>
					</h2>
					<?php
				}

				// Description
				if ( ! empty( $junotoys_description ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
					?>
					<div class="front_page_section_description front_page_section_contacts_description front_page_block_<?php echo ! empty( $junotoys_description ) ? 'filled' : 'empty'; ?>">
					<?php
						echo wp_kses( wpautop( $junotoys_description ), 'junotoys_kses_content' );
					?>
					</div>
					<?php
				}
			}

			// Content (text)
			$junotoys_content = junotoys_get_theme_option( 'front_page_contacts_content' );
			$junotoys_layout  = junotoys_get_theme_option( 'front_page_contacts_layout' );
			if ( 'columns' == $junotoys_layout && ( ! empty( $junotoys_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				<div class="front_page_section_columns front_page_section_contacts_columns columns_wrap">
					<div class="column-1_3">
				<?php
			}

			if ( ( ! empty( $junotoys_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				<div class="front_page_section_content front_page_section_contacts_content front_page_block_<?php echo ! empty( $junotoys_content ) ? 'filled' : 'empty'; ?>">
					<?php
					echo wp_kses( $junotoys_content, 'junotoys_kses_content' );
					?>
				</div>
				<?php
			}

			if ( 'columns' == $junotoys_layout && ( ! empty( $junotoys_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div><div class="column-2_3">
				<?php
			}

			// Shortcode output
			$junotoys_sc = junotoys_get_theme_option( 'front_page_contacts_shortcode' );
			if ( ! empty( $junotoys_sc ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) {
				?>
				<div class="front_page_section_output front_page_section_contacts_output front_page_block_<?php echo ! empty( $junotoys_sc ) ? 'filled' : 'empty'; ?>">
					<?php
					junotoys_show_layout( do_shortcode( $junotoys_sc ) );
					?>
				</div>
				<?php
			}

			if ( 'columns' == $junotoys_layout && ( ! empty( $junotoys_content ) || ( current_user_can( 'edit_theme_options' ) && is_customize_preview() ) ) ) {
				?>
				</div></div>
				<?php
			}
			?>

		</div>
	</div>
</div>
