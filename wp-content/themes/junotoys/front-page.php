<?php
/**
 * The Front Page template file.
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0.31
 */

get_header();

// If front-page is a static page
if ( get_option( 'show_on_front' ) == 'page' ) {

	// If Front Page Builder is enabled - display sections
	if ( junotoys_is_on( junotoys_get_theme_option( 'front_page_enabled', false ) ) ) {

		if ( have_posts() ) {
			the_post();
		}

		$junotoys_sections = junotoys_array_get_keys_by_value( junotoys_get_theme_option( 'front_page_sections' ) );
		if ( is_array( $junotoys_sections ) ) {
			foreach ( $junotoys_sections as $junotoys_section ) {
				get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'front-page/section', $junotoys_section ), $junotoys_section );
			}
		}

		// Else if this page is a blog archive
	} elseif ( is_page_template( 'blog.php' ) ) {
		get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'blog' ) );

		// Else - display a native page content
	} else {
		get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'page' ) );
	}

	// Else get the template 'index.php' to show posts
} else {
	get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'index' ) );
}

get_footer();
