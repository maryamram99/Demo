<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0
 */

$junotoys_args = get_query_var( 'junotoys_logo_args' );

// Site logo
$junotoys_logo_type   = isset( $junotoys_args['type'] ) ? $junotoys_args['type'] : '';
$junotoys_logo_image  = junotoys_get_logo_image( $junotoys_logo_type );
$junotoys_logo_text   = junotoys_is_on( junotoys_get_theme_option( 'logo_text' ) ) ? get_bloginfo( 'name' ) : '';
$junotoys_logo_slogan = get_bloginfo( 'description', 'display' );
if ( ! empty( $junotoys_logo_image['logo'] ) || ! empty( $junotoys_logo_text ) ) {
	?><a class="sc_layouts_logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<?php
		if ( ! empty( $junotoys_logo_image['logo'] ) ) {
			if ( empty( $junotoys_logo_type ) && function_exists( 'the_custom_logo' ) && is_numeric($junotoys_logo_image['logo']) && (int) $junotoys_logo_image['logo'] > 0 ) {
				the_custom_logo();
			} else {
				$junotoys_attr = junotoys_getimagesize( $junotoys_logo_image['logo'] );
				echo '<img src="' . esc_url( $junotoys_logo_image['logo'] ) . '"'
						. ( ! empty( $junotoys_logo_image['logo_retina'] ) ? ' srcset="' . esc_url( $junotoys_logo_image['logo_retina'] ) . ' 2x"' : '' )
						. ' alt="' . esc_attr( $junotoys_logo_text ) . '"'
						. ( ! empty( $junotoys_attr[3] ) ? ' ' . wp_kses_data( $junotoys_attr[3] ) : '' )
						. '>';
			}
		} else {
			junotoys_show_layout( junotoys_prepare_macros( $junotoys_logo_text ), '<span class="logo_text">', '</span>' );
			junotoys_show_layout( junotoys_prepare_macros( $junotoys_logo_slogan ), '<span class="logo_slogan">', '</span>' );
		}
		?>
	</a>
	<?php
}
