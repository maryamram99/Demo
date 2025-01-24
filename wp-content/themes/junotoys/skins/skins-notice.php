<?php
/**
 * The template to display Admin notices
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0.64
 */

$junotoys_skins_url  = get_admin_url( null, 'admin.php?page=trx_addons_theme_panel#trx_addons_theme_panel_section_skins' );
$junotoys_skins_args = get_query_var( 'junotoys_skins_notice_args' );
?>
<div class="junotoys_admin_notice junotoys_skins_notice notice notice-info is-dismissible" data-notice="skins">
	<?php
	// Theme image
	$junotoys_theme_img = junotoys_get_file_url( 'screenshot.jpg' );
	if ( '' != $junotoys_theme_img ) {
		?>
		<div class="junotoys_notice_image"><img src="<?php echo esc_url( $junotoys_theme_img ); ?>" alt="<?php esc_attr_e( 'Theme screenshot', 'junotoys' ); ?>"></div>
		<?php
	}

	// Title
	?>
	<h3 class="junotoys_notice_title">
		<?php esc_html_e( 'New skins are available', 'junotoys' ); ?>
	</h3>
	<?php

	// Description
	$junotoys_total      = $junotoys_skins_args['update'];	// Store value to the separate variable to avoid warnings from ThemeCheck plugin!
	$junotoys_skins_msg  = $junotoys_total > 0
							// Translators: Add new skins number
							? '<strong>' . sprintf( _n( '%d new version', '%d new versions', $junotoys_total, 'junotoys' ), $junotoys_total ) . '</strong>'
							: '';
	$junotoys_total      = $junotoys_skins_args['free'];
	$junotoys_skins_msg .= $junotoys_total > 0
							? ( ! empty( $junotoys_skins_msg ) ? ' ' . esc_html__( 'and', 'junotoys' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d free skin', '%d free skins', $junotoys_total, 'junotoys' ), $junotoys_total ) . '</strong>'
							: '';
	$junotoys_total      = $junotoys_skins_args['pay'];
	$junotoys_skins_msg .= $junotoys_skins_args['pay'] > 0
							? ( ! empty( $junotoys_skins_msg ) ? ' ' . esc_html__( 'and', 'junotoys' ) . ' ' : '' )
								// Translators: Add new skins number
								. '<strong>' . sprintf( _n( '%d paid skin', '%d paid skins', $junotoys_total, 'junotoys' ), $junotoys_total ) . '</strong>'
							: '';
	?>
	<div class="junotoys_notice_text">
		<p>
			<?php
			// Translators: Add new skins info
			echo wp_kses_data( sprintf( __( "We are pleased to announce that %s are available for your theme", 'junotoys' ), $junotoys_skins_msg ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="junotoys_notice_buttons">
		<?php
		// Link to the theme dashboard page
		?>
		<a href="<?php echo esc_url( $junotoys_skins_url ); ?>" class="button button-primary"><i class="dashicons dashicons-update"></i> 
			<?php
			// Translators: Add theme name
			esc_html_e( 'Go to Skins manager', 'junotoys' );
			?>
		</a>
	</div>
</div>
