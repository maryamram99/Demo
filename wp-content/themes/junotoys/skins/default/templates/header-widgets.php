<?php
/**
 * The template to display the widgets area in the header
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0
 */

// Header sidebar
$junotoys_header_name    = junotoys_get_theme_option( 'header_widgets' );
$junotoys_header_present = ! junotoys_is_off( $junotoys_header_name ) && is_active_sidebar( $junotoys_header_name );
if ( $junotoys_header_present ) {
	junotoys_storage_set( 'current_sidebar', 'header' );
	$junotoys_header_wide = junotoys_get_theme_option( 'header_wide' );
	ob_start();
	if ( is_active_sidebar( $junotoys_header_name ) ) {
		dynamic_sidebar( $junotoys_header_name );
	}
	$junotoys_widgets_output = ob_get_contents();
	ob_end_clean();
	if ( ! empty( $junotoys_widgets_output ) ) {
		$junotoys_widgets_output = preg_replace( "/<\/aside>[\r\n\s]*<aside/", '</aside><aside', $junotoys_widgets_output );
		$junotoys_need_columns   = strpos( $junotoys_widgets_output, 'columns_wrap' ) === false;
		if ( $junotoys_need_columns ) {
			$junotoys_columns = max( 0, (int) junotoys_get_theme_option( 'header_columns' ) );
			if ( 0 == $junotoys_columns ) {
				$junotoys_columns = min( 6, max( 1, junotoys_tags_count( $junotoys_widgets_output, 'aside' ) ) );
			}
			if ( $junotoys_columns > 1 ) {
				$junotoys_widgets_output = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $junotoys_columns ) . ' widget', $junotoys_widgets_output );
			} else {
				$junotoys_need_columns = false;
			}
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo ! empty( $junotoys_header_wide ) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<?php do_action( 'junotoys_action_before_sidebar_wrap', 'header' ); ?>
			<div class="header_widgets_inner widget_area_inner">
				<?php
				if ( ! $junotoys_header_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $junotoys_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'junotoys_action_before_sidebar', 'header' );
				junotoys_show_layout( $junotoys_widgets_output );
				do_action( 'junotoys_action_after_sidebar', 'header' );
				if ( $junotoys_need_columns ) {
					?>
					</div>	<!-- /.columns_wrap -->
					<?php
				}
				if ( ! $junotoys_header_wide ) {
					?>
					</div>	<!-- /.content_wrap -->
					<?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
			<?php do_action( 'junotoys_action_after_sidebar_wrap', 'header' ); ?>
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
