<?php
/**
 * The template to display custom header from the ThemeREX Addons Layouts
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0.06
 */

$junotoys_header_css   = '';
$junotoys_header_image = get_header_image();
$junotoys_header_video = junotoys_get_header_video();
if ( ! empty( $junotoys_header_image ) && junotoys_trx_addons_featured_image_override( is_singular() || junotoys_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$junotoys_header_image = junotoys_get_current_mode_image( $junotoys_header_image );
}

$junotoys_header_id = junotoys_get_custom_header_id();
$junotoys_header_meta = get_post_meta( $junotoys_header_id, 'trx_addons_options', true );
if ( ! empty( $junotoys_header_meta['margin'] ) ) {
	junotoys_add_inline_css( sprintf( '.page_content_wrap{padding-top:%s}', esc_attr( junotoys_prepare_css_value( $junotoys_header_meta['margin'] ) ) ) );
}

?><header class="top_panel top_panel_custom top_panel_custom_<?php echo esc_attr( $junotoys_header_id ); ?> top_panel_custom_<?php echo esc_attr( sanitize_title( get_the_title( $junotoys_header_id ) ) ); ?>
				<?php
				echo ! empty( $junotoys_header_image ) || ! empty( $junotoys_header_video )
					? ' with_bg_image'
					: ' without_bg_image';
				if ( '' != $junotoys_header_video ) {
					echo ' with_bg_video';
				}
				if ( '' != $junotoys_header_image ) {
					echo ' ' . esc_attr( junotoys_add_inline_css_class( 'background-image: url(' . esc_url( $junotoys_header_image ) . ');' ) );
				}
				if ( is_single() && has_post_thumbnail() ) {
					echo ' with_featured_image';
				}
				if ( junotoys_is_on( junotoys_get_theme_option( 'header_fullheight' ) ) ) {
					echo ' header_fullheight junotoys-full-height';
				}
				$junotoys_header_scheme = junotoys_get_theme_option( 'header_scheme' );
				if ( ! empty( $junotoys_header_scheme ) && ! junotoys_is_inherit( $junotoys_header_scheme  ) ) {
					echo ' scheme_' . esc_attr( $junotoys_header_scheme );
				}
				?>
">
	<?php

	// Background video
	if ( ! empty( $junotoys_header_video ) ) {
		get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/header-video' ) );
	}

	// Custom header's layout
	do_action( 'junotoys_action_show_layout', $junotoys_header_id );

	// Header widgets area
	get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/header-widgets' ) );

	?>
</header>
