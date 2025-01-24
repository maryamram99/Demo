<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0
 */

$junotoys_template_args = get_query_var( 'junotoys_template_args' );

if ( is_array( $junotoys_template_args ) ) {
	$junotoys_columns    = empty( $junotoys_template_args['columns'] ) ? 2 : max( 1, $junotoys_template_args['columns'] );
	$junotoys_blog_style = array( $junotoys_template_args['type'], $junotoys_columns );
    $junotoys_columns_class = junotoys_get_column_class( 1, $junotoys_columns, ! empty( $junotoys_template_args['columns_tablet']) ? $junotoys_template_args['columns_tablet'] : '', ! empty($junotoys_template_args['columns_mobile']) ? $junotoys_template_args['columns_mobile'] : '' );
} else {
	$junotoys_template_args = array();
	$junotoys_blog_style = explode( '_', junotoys_get_theme_option( 'blog_style' ) );
	$junotoys_columns    = empty( $junotoys_blog_style[1] ) ? 2 : max( 1, $junotoys_blog_style[1] );
    $junotoys_columns_class = junotoys_get_column_class( 1, $junotoys_columns );
}
$junotoys_expanded   = ! junotoys_sidebar_present() && junotoys_get_theme_option( 'expand_content' ) == 'expand';

$junotoys_post_format = get_post_format();
$junotoys_post_format = empty( $junotoys_post_format ) ? 'standard' : str_replace( 'post-format-', '', $junotoys_post_format );

?><div class="<?php
	if ( ! empty( $junotoys_template_args['slider'] ) ) {
		echo ' slider-slide swiper-slide';
	} else {
		echo ( junotoys_is_blog_style_use_masonry( $junotoys_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $junotoys_columns ) : esc_attr( $junotoys_columns_class ) );
	}
?>"><article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $junotoys_post_format )
				. ' post_layout_classic post_layout_classic_' . esc_attr( $junotoys_columns )
				. ' post_layout_' . esc_attr( $junotoys_blog_style[0] )
				. ' post_layout_' . esc_attr( $junotoys_blog_style[0] ) . '_' . esc_attr( $junotoys_columns )
	);
	junotoys_add_blog_animation( $junotoys_template_args );
	?>
>
	<?php

	// Sticky label
	if ( is_sticky() && ! is_paged() ) {
		?>
		<span class="post_label label_sticky"></span>
		<?php
	}

	// Featured image
	$junotoys_hover      = ! empty( $junotoys_template_args['hover'] ) && ! junotoys_is_inherit( $junotoys_template_args['hover'] )
							? $junotoys_template_args['hover']
							: junotoys_get_theme_option( 'image_hover' );

	$junotoys_components = ! empty( $junotoys_template_args['meta_parts'] )
							? ( is_array( $junotoys_template_args['meta_parts'] )
								? $junotoys_template_args['meta_parts']
								: explode( ',', $junotoys_template_args['meta_parts'] )
								)
							: junotoys_array_get_keys_by_value( junotoys_get_theme_option( 'meta_parts' ) );

	junotoys_show_post_featured( apply_filters( 'junotoys_filter_args_featured',
		array(
			'thumb_size' => ! empty( $junotoys_template_args['thumb_size'] )
				? $junotoys_template_args['thumb_size']
				: junotoys_get_thumb_size(
				'classic' == $junotoys_blog_style[0]
						? ( strpos( junotoys_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $junotoys_columns > 2 ? 'big' : 'huge' )
								: ( $junotoys_columns > 2
									? ( $junotoys_expanded ? 'square' : 'square' )
									: ($junotoys_columns > 1 ? 'square' : ( $junotoys_expanded ? 'huge' : 'big' ))
									)
							)
						: ( strpos( junotoys_get_theme_option( 'body_style' ), 'full' ) !== false
								? ( $junotoys_columns > 2 ? 'masonry-big' : 'full' )
								: ($junotoys_columns === 1 ? ( $junotoys_expanded ? 'huge' : 'big' ) : ( $junotoys_columns <= 2 && $junotoys_expanded ? 'masonry-big' : 'masonry' ))
							)
			),
			'hover'      => $junotoys_hover,
			'meta_parts' => $junotoys_components,
			'no_links'   => ! empty( $junotoys_template_args['no_links'] ),
        ),
        'content-classic',
        $junotoys_template_args
    ) );

	// Title and post meta
	$junotoys_show_title = get_the_title() != '';
	$junotoys_show_meta  = count( $junotoys_components ) > 0 && ! in_array( $junotoys_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $junotoys_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php

			// Post meta
			if ( apply_filters( 'junotoys_filter_show_blog_meta', $junotoys_show_meta, $junotoys_components, 'classic' ) ) {
				if ( count( $junotoys_components ) > 0 ) {
					do_action( 'junotoys_action_before_post_meta' );
					junotoys_show_post_meta(
						apply_filters(
							'junotoys_filter_post_meta_args', array(
							'components' => join( ',', $junotoys_components ),
							'seo'        => false,
							'echo'       => true,
						), $junotoys_blog_style[0], $junotoys_columns
						)
					);
					do_action( 'junotoys_action_after_post_meta' );
				}
			}

			// Post title
			if ( apply_filters( 'junotoys_filter_show_blog_title', true, 'classic' ) ) {
				do_action( 'junotoys_action_before_post_title' );
				if ( empty( $junotoys_template_args['no_links'] ) ) {
					the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
				} else {
					the_title( '<h4 class="post_title entry-title">', '</h4>' );
				}
				do_action( 'junotoys_action_after_post_title' );
			}

			if( !in_array( $junotoys_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
				// More button
				if ( apply_filters( 'junotoys_filter_show_blog_readmore', ! $junotoys_show_title || ! empty( $junotoys_template_args['more_button'] ), 'classic' ) ) {
					if ( empty( $junotoys_template_args['no_links'] ) ) {
						do_action( 'junotoys_action_before_post_readmore' );
						junotoys_show_post_more_link( $junotoys_template_args, '<div class="more-wrap">', '</div>' );
						do_action( 'junotoys_action_after_post_readmore' );
					}
				}
			}
			?>
		</div><!-- .entry-header -->
		<?php
	}

	// Post content
	if( in_array( $junotoys_post_format, array( 'quote', 'aside', 'link', 'status' ) ) ) {
		ob_start();
		if (apply_filters('junotoys_filter_show_blog_excerpt', empty($junotoys_template_args['hide_excerpt']) && junotoys_get_theme_option('excerpt_length') > 0, 'classic')) {
			junotoys_show_post_content($junotoys_template_args, '<div class="post_content_inner">', '</div>');
		}
		// More button
		if(! empty( $junotoys_template_args['more_button'] )) {
			if ( empty( $junotoys_template_args['no_links'] ) ) {
				do_action( 'junotoys_action_before_post_readmore' );
				junotoys_show_post_more_link( $junotoys_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'junotoys_action_after_post_readmore' );
			}
		}
		$junotoys_content = ob_get_contents();
		ob_end_clean();
		junotoys_show_layout($junotoys_content, '<div class="post_content entry-content">', '</div><!-- .entry-content -->');
	}
	?>

</article></div><?php
// Need opening PHP-tag above, because <div> is a inline-block element (used as column)!
