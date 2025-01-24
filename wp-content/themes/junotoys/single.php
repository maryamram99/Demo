<?php
/**
 * The template to display single post
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0
 */

// Full post loading
$full_post_loading          = junotoys_get_value_gp( 'action' ) == 'full_post_loading';

// Prev post loading
$prev_post_loading          = junotoys_get_value_gp( 'action' ) == 'prev_post_loading';
$prev_post_loading_type     = junotoys_get_theme_option( 'posts_navigation_scroll_which_block', 'article' );

// Position of the related posts
$junotoys_related_position   = junotoys_get_theme_option( 'related_position', 'below_content' );

// Type of the prev/next post navigation
$junotoys_posts_navigation   = junotoys_get_theme_option( 'posts_navigation' );
$junotoys_prev_post          = false;
$junotoys_prev_post_same_cat = (int)junotoys_get_theme_option( 'posts_navigation_scroll_same_cat', 1 );

// Rewrite style of the single post if current post loading via AJAX and featured image and title is not in the content
if ( ( $full_post_loading 
		|| 
		( $prev_post_loading && 'article' == $prev_post_loading_type )
	) 
	&& 
	! in_array( junotoys_get_theme_option( 'single_style' ), array( 'style-6' ) )
) {
	junotoys_storage_set_array( 'options_meta', 'single_style', 'style-6' );
}

do_action( 'junotoys_action_prev_post_loading', $prev_post_loading, $prev_post_loading_type );

get_header();

while ( have_posts() ) {

	the_post();

	// Type of the prev/next post navigation
	if ( 'scroll' == $junotoys_posts_navigation ) {
		$junotoys_prev_post = get_previous_post( $junotoys_prev_post_same_cat );  // Get post from same category
		if ( ! $junotoys_prev_post && $junotoys_prev_post_same_cat ) {
			$junotoys_prev_post = get_previous_post( false );                    // Get post from any category
		}
		if ( ! $junotoys_prev_post ) {
			$junotoys_posts_navigation = 'links';
		}
	}

	// Override some theme options to display featured image, title and post meta in the dynamic loaded posts
	if ( $full_post_loading || ( $prev_post_loading && $junotoys_prev_post ) ) {
		junotoys_sc_layouts_showed( 'featured', false );
		junotoys_sc_layouts_showed( 'title', false );
		junotoys_sc_layouts_showed( 'postmeta', false );
	}

	// If related posts should be inside the content
	if ( strpos( $junotoys_related_position, 'inside' ) === 0 ) {
		ob_start();
	}

	// Display post's content
	get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/content', 'single-' . junotoys_get_theme_option( 'single_style' ) ), 'single-' . junotoys_get_theme_option( 'single_style' ) );

	// If related posts should be inside the content
	if ( strpos( $junotoys_related_position, 'inside' ) === 0 ) {
		$junotoys_content = ob_get_contents();
		ob_end_clean();

		ob_start();
		do_action( 'junotoys_action_related_posts' );
		$junotoys_related_content = ob_get_contents();
		ob_end_clean();

		if ( ! empty( $junotoys_related_content ) ) {
			$junotoys_related_position_inside = max( 0, min( 9, junotoys_get_theme_option( 'related_position_inside' ) ) );
			if ( 0 == $junotoys_related_position_inside ) {
				$junotoys_related_position_inside = mt_rand( 1, 9 );
			}

			$junotoys_p_number         = 0;
			$junotoys_related_inserted = false;
			$junotoys_in_block         = false;
			$junotoys_content_start    = strpos( $junotoys_content, '<div class="post_content' );
			$junotoys_content_end      = strrpos( $junotoys_content, '</div>' );

			for ( $i = max( 0, $junotoys_content_start ); $i < min( strlen( $junotoys_content ) - 3, $junotoys_content_end ); $i++ ) {
				if ( $junotoys_content[ $i ] != '<' ) {
					continue;
				}
				if ( $junotoys_in_block ) {
					if ( strtolower( substr( $junotoys_content, $i + 1, 12 ) ) == '/blockquote>' ) {
						$junotoys_in_block = false;
						$i += 12;
					}
					continue;
				} else if ( strtolower( substr( $junotoys_content, $i + 1, 10 ) ) == 'blockquote' && in_array( $junotoys_content[ $i + 11 ], array( '>', ' ' ) ) ) {
					$junotoys_in_block = true;
					$i += 11;
					continue;
				} else if ( 'p' == $junotoys_content[ $i + 1 ] && in_array( $junotoys_content[ $i + 2 ], array( '>', ' ' ) ) ) {
					$junotoys_p_number++;
					if ( $junotoys_related_position_inside == $junotoys_p_number ) {
						$junotoys_related_inserted = true;
						$junotoys_content = ( $i > 0 ? substr( $junotoys_content, 0, $i ) : '' )
											. $junotoys_related_content
											. substr( $junotoys_content, $i );
					}
				}
			}
			if ( ! $junotoys_related_inserted ) {
				if ( $junotoys_content_end > 0 ) {
					$junotoys_content = substr( $junotoys_content, 0, $junotoys_content_end ) . $junotoys_related_content . substr( $junotoys_content, $junotoys_content_end );
				} else {
					$junotoys_content .= $junotoys_related_content;
				}
			}
		}

		junotoys_show_layout( $junotoys_content );
	}

	// Comments
	do_action( 'junotoys_action_before_comments' );
	comments_template();
	do_action( 'junotoys_action_after_comments' );

	// Related posts
	if ( 'below_content' == $junotoys_related_position
		&& ( 'scroll' != $junotoys_posts_navigation || (int)junotoys_get_theme_option( 'posts_navigation_scroll_hide_related', 0 ) == 0 )
		&& ( ! $full_post_loading || (int)junotoys_get_theme_option( 'open_full_post_hide_related', 1 ) == 0 )
	) {
		do_action( 'junotoys_action_related_posts' );
	}

	// Post navigation: type 'scroll'
	if ( 'scroll' == $junotoys_posts_navigation && ! $full_post_loading ) {
		?>
		<div class="nav-links-single-scroll"
			data-post-id="<?php echo esc_attr( get_the_ID( $junotoys_prev_post ) ); ?>"
			data-post-link="<?php echo esc_attr( get_permalink( $junotoys_prev_post ) ); ?>"
			data-post-title="<?php the_title_attribute( array( 'post' => $junotoys_prev_post ) ); ?>"
			data-cur-post-link="<?php echo esc_attr( get_permalink() ); ?>"
			data-cur-post-title="<?php the_title_attribute(); ?>"
			<?php do_action( 'junotoys_action_nav_links_single_scroll_data', $junotoys_prev_post ); ?>
		></div>
		<?php
	}
}

get_footer();
