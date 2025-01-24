<?php
/**
 * The template to display the widgets area in the footer
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0.10
 */

// Footer sidebar
$junotoys_footer_name    = junotoys_get_theme_option( 'footer_widgets' );
$junotoys_footer_present = ! junotoys_is_off( $junotoys_footer_name ) && is_active_sidebar( $junotoys_footer_name );
if ( $junotoys_footer_present ) {
	junotoys_storage_set( 'current_sidebar', 'footer' );
	$junotoys_footer_wide = junotoys_get_theme_option( 'footer_wide' );
	ob_start();
	if ( is_active_sidebar( $junotoys_footer_name ) ) {
		dynamic_sidebar( $junotoys_footer_name );
	}
	$junotoys_out = trim( ob_get_contents() );
	ob_end_clean();
	if ( ! empty( $junotoys_out ) ) {
		$junotoys_out          = preg_replace( "/<\\/aside>[\r\n\s]*<aside/", '</aside><aside', $junotoys_out );
		$junotoys_need_columns = true;   //or check: strpos($junotoys_out, 'columns_wrap')===false;
		if ( $junotoys_need_columns ) {
			$junotoys_columns = max( 0, (int) junotoys_get_theme_option( 'footer_columns' ) );			
			if ( 0 == $junotoys_columns ) {
				$junotoys_columns = min( 4, max( 1, junotoys_tags_count( $junotoys_out, 'aside' ) ) );
			}
			if ( $junotoys_columns > 1 ) {
				$junotoys_out = preg_replace( '/<aside([^>]*)class="widget/', '<aside$1class="column-1_' . esc_attr( $junotoys_columns ) . ' widget', $junotoys_out );
			} else {
				$junotoys_need_columns = false;
			}
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo ! empty( $junotoys_footer_wide ) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<?php do_action( 'junotoys_action_before_sidebar_wrap', 'footer' ); ?>
			<div class="footer_widgets_inner widget_area_inner">
				<?php
				if ( ! $junotoys_footer_wide ) {
					?>
					<div class="content_wrap">
					<?php
				}
				if ( $junotoys_need_columns ) {
					?>
					<div class="columns_wrap">
					<?php
				}
				do_action( 'junotoys_action_before_sidebar', 'footer' );
				junotoys_show_layout( $junotoys_out );
				do_action( 'junotoys_action_after_sidebar', 'footer' );
				if ( $junotoys_need_columns ) {
					?>
					</div><!-- /.columns_wrap -->
					<?php
				}
				if ( ! $junotoys_footer_wide ) {
					?>
					</div><!-- /.content_wrap -->
					<?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
			<?php do_action( 'junotoys_action_after_sidebar_wrap', 'footer' ); ?>
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
