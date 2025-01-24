<?php
/**
 * The custom template to display the content
 *
 * Used for index/archive/search.
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0.50
 */

$junotoys_template_args = get_query_var( 'junotoys_template_args' );
if ( is_array( $junotoys_template_args ) ) {
	$junotoys_columns    = empty( $junotoys_template_args['columns'] ) ? 2 : max( 1, $junotoys_template_args['columns'] );
	$junotoys_blog_style = array( $junotoys_template_args['type'], $junotoys_columns );
} else {
	$junotoys_template_args = array();
	$junotoys_blog_style = explode( '_', junotoys_get_theme_option( 'blog_style' ) );
	$junotoys_columns    = empty( $junotoys_blog_style[1] ) ? 2 : max( 1, $junotoys_blog_style[1] );
}
$junotoys_blog_id       = junotoys_get_custom_blog_id( join( '_', $junotoys_blog_style ) );
$junotoys_blog_style[0] = str_replace( 'blog-custom-', '', $junotoys_blog_style[0] );
$junotoys_expanded      = ! junotoys_sidebar_present() && junotoys_get_theme_option( 'expand_content' ) == 'expand';
$junotoys_components    = ! empty( $junotoys_template_args['meta_parts'] )
							? ( is_array( $junotoys_template_args['meta_parts'] )
								? join( ',', $junotoys_template_args['meta_parts'] )
								: $junotoys_template_args['meta_parts']
								)
							: junotoys_array_get_keys_by_value( junotoys_get_theme_option( 'meta_parts' ) );
$junotoys_post_format   = get_post_format();
$junotoys_post_format   = empty( $junotoys_post_format ) ? 'standard' : str_replace( 'post-format-', '', $junotoys_post_format );

$junotoys_blog_meta     = junotoys_get_custom_layout_meta( $junotoys_blog_id );
$junotoys_custom_style  = ! empty( $junotoys_blog_meta['scripts_required'] ) ? $junotoys_blog_meta['scripts_required'] : 'none';

if ( ! empty( $junotoys_template_args['slider'] ) || $junotoys_columns > 1 || ! junotoys_is_off( $junotoys_custom_style ) ) {
	?><div class="
		<?php
		if ( ! empty( $junotoys_template_args['slider'] ) ) {
			echo 'slider-slide swiper-slide';
		} else {
			echo esc_attr( ( junotoys_is_off( $junotoys_custom_style ) ? 'column' : sprintf( '%1$s_item %1$s_item', $junotoys_custom_style ) ) . "-1_{$junotoys_columns}" );
		}
		?>
	">
	<?php
}
?>
<article id="post-<?php the_ID(); ?>" data-post-id="<?php the_ID(); ?>"
	<?php
	post_class(
			'post_item post_item_container post_format_' . esc_attr( $junotoys_post_format )
					. ' post_layout_custom post_layout_custom_' . esc_attr( $junotoys_columns )
					. ' post_layout_' . esc_attr( $junotoys_blog_style[0] )
					. ' post_layout_' . esc_attr( $junotoys_blog_style[0] ) . '_' . esc_attr( $junotoys_columns )
					. ( ! junotoys_is_off( $junotoys_custom_style )
						? ' post_layout_' . esc_attr( $junotoys_custom_style )
							. ' post_layout_' . esc_attr( $junotoys_custom_style ) . '_' . esc_attr( $junotoys_columns )
						: ''
						)
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
	// Custom layout
	do_action( 'junotoys_action_show_layout', $junotoys_blog_id, get_the_ID() );
	?>
</article><?php
if ( ! empty( $junotoys_template_args['slider'] ) || $junotoys_columns > 1 || ! junotoys_is_off( $junotoys_custom_style ) ) {
	?></div><?php
	// Need opening PHP-tag above just after </div>, because <div> is a inline-block element (used as column)!
}
