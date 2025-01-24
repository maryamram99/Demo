<?php
/**
 * The template to display default site footer
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0.10
 */

$junotoys_footer_id = junotoys_get_custom_footer_id();
$junotoys_footer_meta = get_post_meta( $junotoys_footer_id, 'trx_addons_options', true );
if ( ! empty( $junotoys_footer_meta['margin'] ) ) {
	junotoys_add_inline_css( sprintf( '.page_content_wrap{padding-bottom:%s}', esc_attr( junotoys_prepare_css_value( $junotoys_footer_meta['margin'] ) ) ) );
}
?>
<footer class="footer_wrap footer_custom footer_custom_<?php echo esc_attr( $junotoys_footer_id ); ?> footer_custom_<?php echo esc_attr( sanitize_title( get_the_title( $junotoys_footer_id ) ) ); ?>
						<?php
						$junotoys_footer_scheme = junotoys_get_theme_option( 'footer_scheme' );
						if ( ! empty( $junotoys_footer_scheme ) && ! junotoys_is_inherit( $junotoys_footer_scheme  ) ) {
							echo ' scheme_' . esc_attr( $junotoys_footer_scheme );
						}
						?>
						">
	<?php
	// Custom footer's layout
	do_action( 'junotoys_action_show_layout', $junotoys_footer_id );
	?>
</footer><!-- /.footer_wrap -->
