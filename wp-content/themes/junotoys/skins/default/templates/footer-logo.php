<?php
/**
 * The template to display the site logo in the footer
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0.10
 */

// Logo
if ( junotoys_is_on( junotoys_get_theme_option( 'logo_in_footer' ) ) ) {
	$junotoys_logo_image = junotoys_get_logo_image( 'footer' );
	$junotoys_logo_text  = get_bloginfo( 'name' );
	if ( ! empty( $junotoys_logo_image['logo'] ) || ! empty( $junotoys_logo_text ) ) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if ( ! empty( $junotoys_logo_image['logo'] ) ) {
					$junotoys_attr = junotoys_getimagesize( $junotoys_logo_image['logo'] );
					echo '<a href="' . esc_url( home_url( '/' ) ) . '">'
							. '<img src="' . esc_url( $junotoys_logo_image['logo'] ) . '"'
								. ( ! empty( $junotoys_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $junotoys_logo_image['logo_retina'] ) . ' 2x"' : '' )
								. ' class="logo_footer_image"'
								. ' alt="' . esc_attr__( 'Site logo', 'junotoys' ) . '"'
								. ( ! empty( $junotoys_attr[3] ) ? ' ' . wp_kses_data( $junotoys_attr[3] ) : '' )
							. '>'
						. '</a>';
				} elseif ( ! empty( $junotoys_logo_text ) ) {
					echo '<h1 class="logo_footer_text">'
							. '<a href="' . esc_url( home_url( '/' ) ) . '">'
								. esc_html( $junotoys_logo_text )
							. '</a>'
						. '</h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
