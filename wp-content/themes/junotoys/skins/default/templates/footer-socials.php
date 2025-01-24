<?php
/**
 * The template to display the socials in the footer
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0.10
 */


// Socials
if ( junotoys_is_on( junotoys_get_theme_option( 'socials_in_footer' ) ) ) {
	$junotoys_output = junotoys_get_socials_links();
	if ( '' != $junotoys_output ) {
		?>
		<div class="footer_socials_wrap socials_wrap">
			<div class="footer_socials_inner">
				<?php junotoys_show_layout( $junotoys_output ); ?>
			</div>
		</div>
		<?php
	}
}
