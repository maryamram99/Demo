<?php
/**
 * 'Band' template to display the content
 *
 * Used for index/archive/search.
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.71.0
 */

$junotoys_template_args = get_query_var( 'junotoys_template_args' );
if ( ! is_array( $junotoys_template_args ) ) {
	$junotoys_template_args = array(
								'type'    => 'band',
								'columns' => 1
								);
}

$junotoys_columns       = 1;

$junotoys_expanded      = ! junotoys_sidebar_present() && junotoys_get_theme_option( 'expand_content' ) == 'expand';

$junotoys_post_format   = get_post_format();
$junotoys_post_format   = empty( $junotoys_post_format ) ? 'standard' : str_replace( 'post-format-', '', $junotoys_post_format );

if ( is_array( $junotoys_template_args ) ) {
	$junotoys_columns    = empty( $junotoys_template_args['columns'] ) ? 1 : max( 1, $junotoys_template_args['columns'] );
	$junotoys_blog_style = array( $junotoys_template_args['type'], $junotoys_columns );
	if ( ! empty( $junotoys_template_args['slider'] ) ) {
		?><div class="slider-slide swiper-slide">
		<?php
	} elseif ( $junotoys_columns > 1 ) {
	    $junotoys_columns_class = junotoys_get_column_class( 1, $junotoys_columns, ! empty( $junotoys_template_args['columns_tablet']) ? $junotoys_template_args['columns_tablet'] : '', ! empty($junotoys_template_args['columns_mobile']) ? $junotoys_template_args['columns_mobile'] : '' );
				?><div class="<?php echo esc_attr( $junotoys_columns_class ); ?>"><?php
	}
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class( 'post_item post_item_container post_layout_band post_format_' . esc_attr( $junotoys_post_format ) );
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
			'thumb_bg'   => true,
			'thumb_ratio'   => '1:1',
			'thumb_size' => ! empty( $junotoys_template_args['thumb_size'] )
								? $junotoys_template_args['thumb_size']
								: junotoys_get_thumb_size( 
								in_array( $junotoys_post_format, array( 'gallery', 'audio', 'video' ) )
									? ( strpos( junotoys_get_theme_option( 'body_style' ), 'full' ) !== false
										? 'full'
										: ( $junotoys_expanded 
											? 'big' 
											: 'medium-square'
											)
										)
									: 'masonry-big'
								)
		),
		'content-band',
		$junotoys_template_args
	) );

	?><div class="post_content_wrap"><?php

		// Title and post meta
		$junotoys_show_title = get_the_title() != '';
		$junotoys_show_meta  = count( $junotoys_components ) > 0 && ! in_array( $junotoys_hover, array( 'border', 'pull', 'slide', 'fade', 'info' ) );
		if ( $junotoys_show_title ) {
			?>
			<div class="post_header entry-header">
				<?php
				// Categories
				if ( apply_filters( 'junotoys_filter_show_blog_categories', $junotoys_show_meta && in_array( 'categories', $junotoys_components ), array( 'categories' ), 'band' ) ) {
					do_action( 'junotoys_action_before_post_category' );
					?>
					<div class="post_category">
						<?php
						junotoys_show_post_meta( apply_filters(
															'junotoys_filter_post_meta_args',
															array(
																'components' => 'categories',
																'seo'        => false,
																'echo'       => true,
																'cat_sep'    => false,
																),
															'hover_' . $junotoys_hover, 1
															)
											);
						?>
					</div>
					<?php
					$junotoys_components = junotoys_array_delete_by_value( $junotoys_components, 'categories' );
					do_action( 'junotoys_action_after_post_category' );
				}
				// Post title
				if ( apply_filters( 'junotoys_filter_show_blog_title', true, 'band' ) ) {
					do_action( 'junotoys_action_before_post_title' );
					if ( empty( $junotoys_template_args['no_links'] ) ) {
						the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );
					} else {
						the_title( '<h4 class="post_title entry-title">', '</h4>' );
					}
					do_action( 'junotoys_action_after_post_title' );
				}
				?>
			</div><!-- .post_header -->
			<?php
		}

		// Post content
		if ( ! isset( $junotoys_template_args['excerpt_length'] ) && ! in_array( $junotoys_post_format, array( 'gallery', 'audio', 'video' ) ) ) {
			$junotoys_template_args['excerpt_length'] = 13;
		}
		if ( apply_filters( 'junotoys_filter_show_blog_excerpt', empty( $junotoys_template_args['hide_excerpt'] ) && junotoys_get_theme_option( 'excerpt_length' ) > 0, 'band' ) ) {
			?>
			<div class="post_content entry-content">
				<?php
				// Post content area
				junotoys_show_post_content( $junotoys_template_args, '<div class="post_content_inner">', '</div>' );
				?>
			</div><!-- .entry-content -->
			<?php
		}
		// Post meta
		if ( apply_filters( 'junotoys_filter_show_blog_meta', $junotoys_show_meta, $junotoys_components, 'band' ) ) {
			if ( count( $junotoys_components ) > 0 ) {
				do_action( 'junotoys_action_before_post_meta' );
				junotoys_show_post_meta(
					apply_filters(
						'junotoys_filter_post_meta_args', array(
							'components' => join( ',', $junotoys_components ),
							'seo'        => false,
							'echo'       => true,
						), 'band', 1
					)
				);
				do_action( 'junotoys_action_after_post_meta' );
			}
		}
		// More button
		if ( apply_filters( 'junotoys_filter_show_blog_readmore', ! $junotoys_show_title || ! empty( $junotoys_template_args['more_button'] ), 'band' ) ) {
			if ( empty( $junotoys_template_args['no_links'] ) ) {
				do_action( 'junotoys_action_before_post_readmore' );
				junotoys_show_post_more_link( $junotoys_template_args, '<div class="more-wrap">', '</div>' );
				do_action( 'junotoys_action_after_post_readmore' );
			}
		}
		?>
	</div>
</article>
<?php

if ( is_array( $junotoys_template_args ) ) {
	if ( ! empty( $junotoys_template_args['slider'] ) || $junotoys_columns > 1 ) {
		?>
		</div>
		<?php
	}
}
