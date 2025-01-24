<?php
/**
 * The Portfolio template to display the content
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

$junotoys_post_format = get_post_format();
$junotoys_post_format = empty( $junotoys_post_format ) ? 'standard' : str_replace( 'post-format-', '', $junotoys_post_format );

?><div class="
<?php
if ( ! empty( $junotoys_template_args['slider'] ) ) {
	echo ' slider-slide swiper-slide';
} else {
	echo ( junotoys_is_blog_style_use_masonry( $junotoys_blog_style[0] ) ? 'masonry_item masonry_item-1_' . esc_attr( $junotoys_columns ) : esc_attr( $junotoys_columns_class ));
}
?>
"><article id="post-<?php the_ID(); ?>" 
	<?php
	post_class(
		'post_item post_item_container post_format_' . esc_attr( $junotoys_post_format )
		. ' post_layout_portfolio'
		. ' post_layout_portfolio_' . esc_attr( $junotoys_columns )
		. ( 'portfolio' != $junotoys_blog_style[0] ? ' ' . esc_attr( $junotoys_blog_style[0] )  . '_' . esc_attr( $junotoys_columns ) : '' )
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

	$junotoys_hover   = ! empty( $junotoys_template_args['hover'] ) && ! junotoys_is_inherit( $junotoys_template_args['hover'] )
								? $junotoys_template_args['hover']
								: junotoys_get_theme_option( 'image_hover' );

	if ( 'dots' == $junotoys_hover ) {
		$junotoys_post_link = empty( $junotoys_template_args['no_links'] )
								? ( ! empty( $junotoys_template_args['link'] )
									? $junotoys_template_args['link']
									: get_permalink()
									)
								: '';
		$junotoys_target    = ! empty( $junotoys_post_link ) && false === strpos( $junotoys_post_link, home_url() )
								? ' target="_blank" rel="nofollow"'
								: '';
	}
	
	// Meta parts
	$junotoys_components = ! empty( $junotoys_template_args['meta_parts'] )
							? ( is_array( $junotoys_template_args['meta_parts'] )
								? $junotoys_template_args['meta_parts']
								: explode( ',', $junotoys_template_args['meta_parts'] )
								)
							: junotoys_array_get_keys_by_value( junotoys_get_theme_option( 'meta_parts' ) );

	// Featured image
	junotoys_show_post_featured( apply_filters( 'junotoys_filter_args_featured',
        array(
			'hover'         => $junotoys_hover,
			'no_links'      => ! empty( $junotoys_template_args['no_links'] ),
			'thumb_size'    => ! empty( $junotoys_template_args['thumb_size'] )
								? $junotoys_template_args['thumb_size']
								: junotoys_get_thumb_size(
									junotoys_is_blog_style_use_masonry( $junotoys_blog_style[0] )
										? (	strpos( junotoys_get_theme_option( 'body_style' ), 'full' ) !== false || $junotoys_columns < 3
											? 'masonry-big'
											: 'masonry'
											)
										: (	strpos( junotoys_get_theme_option( 'body_style' ), 'full' ) !== false || $junotoys_columns < 3
											? 'square'
											: 'square'
											)
								),
			'thumb_bg' => junotoys_is_blog_style_use_masonry( $junotoys_blog_style[0] ) ? false : true,
			'show_no_image' => true,
			'meta_parts'    => $junotoys_components,
			'class'         => 'dots' == $junotoys_hover ? 'hover_with_info' : '',
			'post_info'     => 'dots' == $junotoys_hover
										? '<div class="post_info"><h5 class="post_title">'
											. ( ! empty( $junotoys_post_link )
												? '<a href="' . esc_url( $junotoys_post_link ) . '"' . ( ! empty( $target ) ? $target : '' ) . '>'
												: ''
												)
												. esc_html( get_the_title() ) 
											. ( ! empty( $junotoys_post_link )
												? '</a>'
												: ''
												)
											. '</h5></div>'
										: '',
            'thumb_ratio'   => 'info' == $junotoys_hover ?  '100:102' : '',
        ),
        'content-portfolio',
        $junotoys_template_args
    ) );
	?>
</article></div><?php
// Need opening PHP-tag above, because <article> is a inline-block element (used as column)!