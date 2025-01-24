<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: //codex.wordpress.org/Template_Hierarchy
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0
 */

$junotoys_template = apply_filters( 'junotoys_filter_get_template_part', junotoys_blog_archive_get_template() );

if ( ! empty( $junotoys_template ) && 'index' != $junotoys_template ) {

	get_template_part( $junotoys_template );

} else {

	junotoys_storage_set( 'blog_archive', true );

	get_header();

	if ( have_posts() ) {

		// Query params
		$junotoys_stickies   = is_home()
								|| ( in_array( junotoys_get_theme_option( 'post_type' ), array( '', 'post' ) )
									&& (int) junotoys_get_theme_option( 'parent_cat' ) == 0
									)
										? get_option( 'sticky_posts' )
										: false;
		$junotoys_post_type  = junotoys_get_theme_option( 'post_type' );
		$junotoys_args       = array(
								'blog_style'     => junotoys_get_theme_option( 'blog_style' ),
								'post_type'      => $junotoys_post_type,
								'taxonomy'       => junotoys_get_post_type_taxonomy( $junotoys_post_type ),
								'parent_cat'     => junotoys_get_theme_option( 'parent_cat' ),
								'posts_per_page' => junotoys_get_theme_option( 'posts_per_page' ),
								'sticky'         => junotoys_get_theme_option( 'sticky_style', 'inherit' ) == 'columns'
															&& is_array( $junotoys_stickies )
															&& count( $junotoys_stickies ) > 0
															&& get_query_var( 'paged' ) < 1
								);

		junotoys_blog_archive_start();

		do_action( 'junotoys_action_blog_archive_start' );

		if ( is_author() ) {
			do_action( 'junotoys_action_before_page_author' );
			get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/author-page' ) );
			do_action( 'junotoys_action_after_page_author' );
		}

		if ( junotoys_get_theme_option( 'show_filters', 0 ) ) {
			do_action( 'junotoys_action_before_page_filters' );
			junotoys_show_filters( $junotoys_args );
			do_action( 'junotoys_action_after_page_filters' );
		} else {
			do_action( 'junotoys_action_before_page_posts' );
			junotoys_show_posts( array_merge( $junotoys_args, array( 'cat' => $junotoys_args['parent_cat'] ) ) );
			do_action( 'junotoys_action_after_page_posts' );
		}

		do_action( 'junotoys_action_blog_archive_end' );

		junotoys_blog_archive_end();

	} else {

		if ( is_search() ) {
			get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/content', 'none-search' ), 'none-search' );
		} else {
			get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/content', 'none-archive' ), 'none-archive' );
		}
	}

	get_footer();
}
