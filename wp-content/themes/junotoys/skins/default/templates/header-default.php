<?php
/**
 * The template to display default site header
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0
 */

$junotoys_header_css   = '';
$junotoys_header_image = get_header_image();
$junotoys_header_video = junotoys_get_header_video();
if ( ! empty( $junotoys_header_image ) && junotoys_trx_addons_featured_image_override( is_singular() || junotoys_storage_isset( 'blog_archive' ) || is_category() ) ) {
	$junotoys_header_image = junotoys_get_current_mode_image( $junotoys_header_image );
}

?><header class="top_panel top_panel_default
	<?php
	echo ! empty( $junotoys_header_image ) || ! empty( $junotoys_header_video ) ? ' with_bg_image' : ' without_bg_image';
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

	// Main menu
	get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/header-navi' ) );

	// Mobile header
	if ( junotoys_is_on( junotoys_get_theme_option( 'header_mobile_enabled' ) ) ) {
		get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/header-mobile' ) );
	}

	// Page title and breadcrumbs area
	if ( ! is_single() ) {
		get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/header-title' ) );
	}

	// Header widgets area
	get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/header-widgets' ) );
	?>
</header>
