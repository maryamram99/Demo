<?php
/**
 * The template to display default site footer
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0.10
 */

?>
<footer class="footer_wrap footer_default
<?php
$junotoys_footer_scheme = junotoys_get_theme_option( 'footer_scheme' );
if ( ! empty( $junotoys_footer_scheme ) && ! junotoys_is_inherit( $junotoys_footer_scheme  ) ) {
	echo ' scheme_' . esc_attr( $junotoys_footer_scheme );
}
?>
				">
	<?php

	// Footer widgets area
	get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/footer-widgets' ) );

	// Logo
	get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/footer-logo' ) );

	// Socials
	get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/footer-socials' ) );

	// Copyright area
	get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/footer-copyright' ) );

	?>
</footer><!-- /.footer_wrap -->
