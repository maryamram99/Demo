<?php
/**
 * The template to display the copyright info in the footer
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap
<?php
$junotoys_copyright_scheme = junotoys_get_theme_option( 'copyright_scheme' );
if ( ! empty( $junotoys_copyright_scheme ) && ! junotoys_is_inherit( $junotoys_copyright_scheme  ) ) {
	echo ' scheme_' . esc_attr( $junotoys_copyright_scheme );
}
?>
				">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text">
			<?php
				$junotoys_copyright = junotoys_get_theme_option( 'copyright' );
			if ( ! empty( $junotoys_copyright ) ) {
				// Replace {{Y}} or {Y} with the current year
				$junotoys_copyright = str_replace( array( '{{Y}}', '{Y}' ), date( 'Y' ), $junotoys_copyright );
				// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
				$junotoys_copyright = junotoys_prepare_macros( $junotoys_copyright );
				// Display copyright
				echo wp_kses( nl2br( $junotoys_copyright ), 'junotoys_kses_content' );
			}
			?>
			</div>
		</div>
	</div>
</div>
