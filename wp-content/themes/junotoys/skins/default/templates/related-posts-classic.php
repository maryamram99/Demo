<?php
/**
 * The template 'Style 2' to displaying related posts
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0
 */

$junotoys_link        = get_permalink();
$junotoys_post_format = get_post_format();
$junotoys_post_format = empty( $junotoys_post_format ) ? 'standard' : str_replace( 'post-format-', '', $junotoys_post_format );
?><div id="post-<?php the_ID(); ?>" <?php post_class( 'related_item post_format_' . esc_attr( $junotoys_post_format ) ); ?> data-post-id="<?php the_ID(); ?>">
	<?php
	junotoys_show_post_featured(
		array(
			'thumb_ratio'   => '300:223',
			'thumb_size'    => apply_filters( 'junotoys_filter_related_thumb_size', junotoys_get_thumb_size( (int) junotoys_get_theme_option( 'related_posts' ) == 1 ? 'huge' : 'square' ) ),
		)
	);
	?>
	<div class="post_header entry-header">
		<?php
		if ( in_array( get_post_type(), array( 'post', 'attachment' ) ) ) {

			junotoys_show_post_meta(
				array(
					'components' => 'categories',
					'class'      => 'post_meta_categories',
				)
			);

		}
		?>
		<h6 class="post_title entry-title"><a href="<?php echo esc_url( $junotoys_link ); ?>"><?php
			if ( '' == get_the_title() ) {
				esc_html_e( '- No title -', 'junotoys' );
			} else {
				the_title();
			}
		?></a></h6>
	</div>
</div>
