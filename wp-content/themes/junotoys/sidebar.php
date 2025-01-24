<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0
 */

if ( junotoys_sidebar_present() ) {
	
	$junotoys_sidebar_type = junotoys_get_theme_option( 'sidebar_type' );
	if ( 'custom' == $junotoys_sidebar_type && ! junotoys_is_layouts_available() ) {
		$junotoys_sidebar_type = 'default';
	}
	
	// Catch output to the buffer
	ob_start();
	if ( 'default' == $junotoys_sidebar_type ) {
		// Default sidebar with widgets
		$junotoys_sidebar_name = junotoys_get_theme_option( 'sidebar_widgets' );
		junotoys_storage_set( 'current_sidebar', 'sidebar' );
		if ( is_active_sidebar( $junotoys_sidebar_name ) ) {
			dynamic_sidebar( $junotoys_sidebar_name );
		}
	} else {
		// Custom sidebar from Layouts Builder
		$junotoys_sidebar_id = junotoys_get_custom_sidebar_id();
		do_action( 'junotoys_action_show_layout', $junotoys_sidebar_id );
	}
	$junotoys_out = trim( ob_get_contents() );
	ob_end_clean();
	
	// If any html is present - display it
	if ( ! empty( $junotoys_out ) ) {
		$junotoys_sidebar_position    = junotoys_get_theme_option( 'sidebar_position' );
		$junotoys_sidebar_position_ss = junotoys_get_theme_option( 'sidebar_position_ss', 'below' );
		?>
		<div class="sidebar widget_area
			<?php
			echo ' ' . esc_attr( $junotoys_sidebar_position );
			echo ' sidebar_' . esc_attr( $junotoys_sidebar_position_ss );
			echo ' sidebar_' . esc_attr( $junotoys_sidebar_type );

			$junotoys_sidebar_scheme = apply_filters( 'junotoys_filter_sidebar_scheme', junotoys_get_theme_option( 'sidebar_scheme', 'inherit' ) );
			if ( ! empty( $junotoys_sidebar_scheme ) && ! junotoys_is_inherit( $junotoys_sidebar_scheme ) && 'custom' != $junotoys_sidebar_type ) {
				echo ' scheme_' . esc_attr( $junotoys_sidebar_scheme );
			}
			?>
		" role="complementary">
			<?php

			// Skip link anchor to fast access to the sidebar from keyboard
			?>
			<a id="sidebar_skip_link_anchor" class="junotoys_skip_link_anchor" href="#"></a>
			<?php

			do_action( 'junotoys_action_before_sidebar_wrap', 'sidebar' );

			// Button to show/hide sidebar on mobile
			if ( in_array( $junotoys_sidebar_position_ss, array( 'above', 'float' ) ) ) {
				$junotoys_title = apply_filters( 'junotoys_filter_sidebar_control_title', 'float' == $junotoys_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'junotoys' ) : '' );
				$junotoys_text  = apply_filters( 'junotoys_filter_sidebar_control_text', 'above' == $junotoys_sidebar_position_ss ? esc_html__( 'Show Sidebar', 'junotoys' ) : '' );
				?>
				<a href="#" class="sidebar_control" title="<?php echo esc_attr( $junotoys_title ); ?>"><?php echo esc_html( $junotoys_text ); ?></a>
				<?php
			}
			?>
			<div class="sidebar_inner">
				<?php
				do_action( 'junotoys_action_before_sidebar', 'sidebar' );
				junotoys_show_layout( preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $junotoys_out ) );
				do_action( 'junotoys_action_after_sidebar', 'sidebar' );
				?>
			</div>
			<?php

			do_action( 'junotoys_action_after_sidebar_wrap', 'sidebar' );

			?>
		</div>
		<div class="clearfix"></div>
		<?php
	}
}
