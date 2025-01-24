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
<div class="junotoys_admin_notice junotoys_rate_notice notice notice-info is-dismissible" data-notice="rate">
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
	<h3 class="junotoys_notice_title"><a href="<?php echo esc_url( junotoys_storage_get( 'theme_rate_url' ) ); ?>" target="_blank">
		<?php
		echo esc_html(
			sprintf(
				// Translators: Add theme name and version to the 'Welcome' message
				__( 'Rate our theme "%s", please', 'junotoys' ),
				$junotoys_theme_obj->get( 'Name' ) . ( JUNOTOYS_THEME_FREE ? ' ' . __( 'Free', 'junotoys' ) : '' )
			)
		);
		?>
	</a></h3>
	<?php

	// Description
	?>
	<div class="junotoys_notice_text">
		<p><?php echo wp_kses_data( __( "We are glad you chose our WP theme for your website. You've done well customizing your website and we hope that you've enjoyed working with our theme.", 'junotoys' ) ); ?></p>
		<p><?php echo wp_kses_data( __( "It would be just awesome if you spend just a minute of your time to rate our theme or the customer service you've received from us.", 'junotoys' ) ); ?></p>
		<p class="junotoys_notice_text_info"><?php echo wp_kses_data( __( '* We love receiving your reviews! Every time you leave a review, our CEO Henry Rise gives $5 to homeless dog shelter! Save the planet with us!', 'junotoys' ) ); ?></p>
	</div>
	<?php

	// Buttons
	?>
	<div class="junotoys_notice_buttons">
		<?php
		// Link to the theme download page
		?>
		<a href="<?php echo esc_url( junotoys_storage_get( 'theme_rate_url' ) ); ?>" class="button button-primary" target="_blank"><i class="dashicons dashicons-star-filled"></i> 
			<?php
			// Translators: Add theme name
			echo esc_html( sprintf( __( 'Rate theme %s', 'junotoys' ), $junotoys_theme_obj->name ) );
			?>
		</a>
		<?php
		// Link to the theme support
		?>
		<a href="<?php echo esc_url( junotoys_storage_get( 'theme_support_url' ) ); ?>" class="button" target="_blank"><i class="dashicons dashicons-sos"></i> 
			<?php
			esc_html_e( 'Support', 'junotoys' );
			?>
		</a>
		<?php
		// Link to the theme documentation
		?>
		<a href="<?php echo esc_url( junotoys_storage_get( 'theme_doc_url' ) ); ?>" class="button" target="_blank"><i class="dashicons dashicons-book"></i> 
			<?php
			esc_html_e( 'Documentation', 'junotoys' );
			?>
		</a>
	</div>
</div>
