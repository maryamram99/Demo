<?php
/**
 * The template to display Admin notices
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0.1
 */

$junotoys_theme_slug = get_option( 'template' );
$junotoys_theme_obj  = wp_get_theme( $junotoys_theme_slug );
?>
<div class="junotoys_admin_notice junotoys_welcome_notice notice notice-info is-dismissible" data-notice="admin">
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
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name and version to the 'Welcome' message
				__( 'Welcome to %1$s v.%2$s', 'junotoys' ),
				$junotoys_theme_obj->get( 'Name' ) . ( JUNOTOYS_THEME_FREE ? ' ' . __( 'Free', 'junotoys' ) : '' ),
				$junotoys_theme_obj->get( 'Version' )
			)
		);
		?>
	</h3>
	<?php

	// Description
	?>
	<div class="junotoys_notice_text">
		<p class="junotoys_notice_text_description">
			<?php
			echo str_replace( '. ', '.<br>', wp_kses_data( $junotoys_theme_obj->description ) );
			?>
		</p>
		<p class="junotoys_notice_text_info">
			<?php
			echo wp_kses_data( __( 'Attention! Plugin "ThemeREX Addons" is required! Please, install and activate it!', 'junotoys' ) );
			?>
		</p>
	</div>
	<?php

	// Buttons
	?>
	<div class="junotoys_notice_buttons">
		<?php
		// Link to the page 'About Theme'
		?>
		<a href="<?php echo esc_url( admin_url() . 'themes.php?page=junotoys_about' ); ?>" class="button button-primary"><i class="dashicons dashicons-nametag"></i> 
			<?php
			echo esc_html__( 'Install plugin "ThemeREX Addons"', 'junotoys' );
			?>
		</a>
	</div>
</div>
