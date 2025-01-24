<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0
 */

$junotoys_template_args = get_query_var( 'junotoys_template_args' );
$junotoys_columns = 1;
if ( is_array( $junotoys_template_args ) ) {
	$junotoys_columns    = empty( $junotoys_template_args['columns'] ) ? 1 : max( 1, $junotoys_template_args['columns'] );
	$junotoys_blog_style = array( $junotoys_template_args['type'], $junotoys_columns );
	if ( ! empty( $junotoys_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $junotoys_columns > 1 ) {
	    $junotoys_columns_class = junotoys_get_column_class( 1, $junotoys_columns, ! empty( $junotoys_template_args['columns_tablet']) ? $junotoys_template_args['columns_tablet'] : '', ! empty($junotoys_template_args['columns_mobile']) ? $junotoys_template_args['columns_mobile'] : '' );
		?>
		<div class="<?php echo esc_attr( $junotoys_columns_class ); ?>">
		<?php
	}
} else {
	$junotoys_template_args = array();
}
$junotoys_expanded    = ! junotoys_sidebar_present() && junotoys_get_theme_option( 'expand_content' ) == 'expand';
$junotoys_post_format = get_post_format();
$junotoys_post_format = empty( $junotoys_post_format ) ? 'standard' : str_replace( 'post-format-', '', $junotoys_post_format );
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_excerpt post_format_' . esc_attr( $junotoys_post_format ) );
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
								: array_map( 'trim', explode( ',', $junotoys_template_args['meta_parts'] ) )
								)
							: junotoys_array_get_keys_by_value( junotoys_get_theme_option( 'meta_parts' ) );
	junotoys_show_post_featured( apply_filters( 'junotoys_filter_args_featured',
		array(
			'no_links'   => ! empty( $junotoys_template_args['no_links'] ),
			'hover'      => $junotoys_hover,
			'meta_parts' => $junotoys_components,
			'thumb_size' => ! empty( $junotoys_template_args['thumb_size'] )
							? $junotoys_template_args['thumb_size']
							: junotoys_get_thumb_size( strpos( junotoys_get_theme_option( 'body_style' ), 'full' ) !== false
								? 'full'
								: ( $junotoys_expanded 
									? 'huge' 
									: 'big' 
									)
								),
		),
		'content-excerpt',
		$junotoys_template_args
	) );

	// Title and post meta
	$junotoys_show_title = get_the_title() != '';
	$junotoys_show_meta  = count( $junotoys_components ) > 0 && ! in_array( $junotoys_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );

	if ( $junotoys_show_title ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			if ( apply_filters( 'junotoys_filter_show_blog_title', true, 'excerpt' ) ) {
				do_action( 'junotoys_action_before_post_title' );
				if ( empty( $junotoys_template_args['no_links'] ) ) {
					the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
				} else {
					the_title( '<h3 class="post_title entry-title">', '</h3>' );
				}
				do_action( 'junotoys_action_after_post_title' );
			}
			?>
		</div><!-- .post_header -->
		<?php
	}

	// Post content
	if ( apply_filters( 'junotoys_filter_show_blog_excerpt', empty( $junotoys_template_args['hide_excerpt'] ) && junotoys_get_theme_option( 'excerpt_length' ) > 0, 'excerpt' ) ) {
		?>
		<div class="post_content entry-content">
			<?php

			// Post meta
			if ( apply_filters( 'junotoys_filter_show_blog_meta', $junotoys_show_meta, $junotoys_components, 'excerpt' ) ) {
				if ( count( $junotoys_components ) > 0 ) {
					do_action( 'junotoys_action_before_post_meta' );
					junotoys_show_post_meta(
						apply_filters(
							'junotoys_filter_post_meta_args', array(
								'components' => join( ',', $junotoys_components ),
								'seo'        => false,
								'echo'       => true,
							), 'excerpt', 1
						)
					);
					do_action( 'junotoys_action_after_post_meta' );
				}
			}

			if ( junotoys_get_theme_option( 'blog_content' ) == 'fullpost' ) {
				// Post content area
				?>
				<div class="post_content_inner">
					<?php
					do_action( 'junotoys_action_before_full_post_content' );
					the_content( '' );
					do_action( 'junotoys_action_after_full_post_content' );
					?>
				</div>
				<?php
				// Inner pages
				wp_link_pages(
					array(
						'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'junotoys' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'junotoys' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					)
				);
			} else {
				// Post content area
				junotoys_show_post_content( $junotoys_template_args, '<div class="post_content_inner">', '</div>' );
			}

			// More button
			if ( apply_filters( 'junotoys_filter_show_blog_readmore',  ! isset( $junotoys_template_args['more_button'] ) || ! empty( $junotoys_template_args['more_button'] ), 'excerpt' ) ) {
				if ( empty( $junotoys_template_args['no_links'] ) ) {
					do_action( 'junotoys_action_before_post_readmore' );
					if ( junotoys_get_theme_option( 'blog_content' ) != 'fullpost' ) {
						junotoys_show_post_more_link( $junotoys_template_args, '<p>', '</p>' );
					} else {
						junotoys_show_post_comments_link( $junotoys_template_args, '<p>', '</p>' );
					}
					do_action( 'junotoys_action_after_post_readmore' );
				}
			}

			?>
		</div><!-- .entry-content -->
		<?php
	}
	?>
</article>
<?php

if ( is_array( $junotoys_template_args ) ) {
	if ( ! empty( $junotoys_template_args['slider'] ) || $junotoys_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
