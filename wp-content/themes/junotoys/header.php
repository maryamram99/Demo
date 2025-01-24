<?php
/**
 * The Header: Logo and main menu
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js<?php
	// Class scheme_xxx need in the <html> as context for the <body>!
	echo ' scheme_' . esc_attr( junotoys_get_theme_option( 'color_scheme' ) );
?>">

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
	do_action( 'junotoys_action_before_body' );
	?>

	<div class="<?php echo esc_attr( apply_filters( 'junotoys_filter_body_wrap_class', 'body_wrap' ) ); ?>" <?php do_action('junotoys_action_body_wrap_attributes'); ?>>

		<?php do_action( 'junotoys_action_before_page_wrap' ); ?>

		<div class="<?php echo esc_attr( apply_filters( 'junotoys_filter_page_wrap_class', 'page_wrap' ) ); ?>" <?php do_action('junotoys_action_page_wrap_attributes'); ?>>

			<?php do_action( 'junotoys_action_page_wrap_start' ); ?>

			<?php
			$junotoys_full_post_loading = ( junotoys_is_singular( 'post' ) || junotoys_is_singular( 'attachment' ) ) && junotoys_get_value_gp( 'action' ) == 'full_post_loading';
			$junotoys_prev_post_loading = ( junotoys_is_singular( 'post' ) || junotoys_is_singular( 'attachment' ) ) && junotoys_get_value_gp( 'action' ) == 'prev_post_loading';

			// Don't display the header elements while actions 'full_post_loading' and 'prev_post_loading'
			if ( ! $junotoys_full_post_loading && ! $junotoys_prev_post_loading ) {

				// Short links to fast access to the content, sidebar and footer from the keyboard
				?>
				<a class="junotoys_skip_link skip_to_content_link" href="#content_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'junotoys_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to content", 'junotoys' ); ?></a>
				<?php if ( junotoys_sidebar_present() ) { ?>
				<a class="junotoys_skip_link skip_to_sidebar_link" href="#sidebar_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'junotoys_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to sidebar", 'junotoys' ); ?></a>
				<?php } ?>
				<a class="junotoys_skip_link skip_to_footer_link" href="#footer_skip_link_anchor" tabindex="<?php echo esc_attr( apply_filters( 'junotoys_filter_skip_links_tabindex', 1 ) ); ?>"><?php esc_html_e( "Skip to footer", 'junotoys' ); ?></a>

				<?php
				do_action( 'junotoys_action_before_header' );

				// Header
				$junotoys_header_type = junotoys_get_theme_option( 'header_type' );
				if ( 'custom' == $junotoys_header_type && ! junotoys_is_layouts_available() ) {
					$junotoys_header_type = 'default';
				}
				get_template_part( apply_filters( 'junotoys_filter_get_template_part', "templates/header-" . sanitize_file_name( $junotoys_header_type ) ) );

				// Side menu
				if ( in_array( junotoys_get_theme_option( 'menu_side', 'none' ), array( 'left', 'right' ) ) ) {
					get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/header-navi-side' ) );
				}

				// Mobile menu
				if ( apply_filters( 'junotoys_filter_use_navi_mobile', true ) ) {
					get_template_part( apply_filters( 'junotoys_filter_get_template_part', 'templates/header-navi-mobile' ) );
				}

				do_action( 'junotoys_action_after_header' );

			}
			?>

			<?php do_action( 'junotoys_action_before_page_content_wrap' ); ?>

			<div class="page_content_wrap<?php
				if ( junotoys_is_off( junotoys_get_theme_option( 'remove_margins' ) ) ) {
					if ( empty( $junotoys_header_type ) ) {
						$junotoys_header_type = junotoys_get_theme_option( 'header_type' );
					}
					if ( 'custom' == $junotoys_header_type && junotoys_is_layouts_available() ) {
						$junotoys_header_id = junotoys_get_custom_header_id();
						if ( $junotoys_header_id > 0 ) {
							$junotoys_header_meta = junotoys_get_custom_layout_meta( $junotoys_header_id );
							if ( ! empty( $junotoys_header_meta['margin'] ) ) {
								?> page_content_wrap_custom_header_margin<?php
							}
						}
					}
					$junotoys_footer_type = junotoys_get_theme_option( 'footer_type' );
					if ( 'custom' == $junotoys_footer_type && junotoys_is_layouts_available() ) {
						$junotoys_footer_id = junotoys_get_custom_footer_id();
						if ( $junotoys_footer_id ) {
							$junotoys_footer_meta = junotoys_get_custom_layout_meta( $junotoys_footer_id );
							if ( ! empty( $junotoys_footer_meta['margin'] ) ) {
								?> page_content_wrap_custom_footer_margin<?php
							}
						}
					}
				}
				do_action( 'junotoys_action_page_content_wrap_class', $junotoys_prev_post_loading );
				?>"<?php
				if ( apply_filters( 'junotoys_filter_is_prev_post_loading', $junotoys_prev_post_loading ) ) {
					?> data-single-style="<?php echo esc_attr( junotoys_get_theme_option( 'single_style' ) ); ?>"<?php
				}
				do_action( 'junotoys_action_page_content_wrap_data', $junotoys_prev_post_loading );
			?>>
				<?php
				do_action( 'junotoys_action_page_content_wrap', $junotoys_full_post_loading || $junotoys_prev_post_loading );

				// Single posts banner
				if ( apply_filters( 'junotoys_filter_single_post_header', junotoys_is_singular( 'post' ) || junotoys_is_singular( 'attachment' ) ) ) {
					if ( $junotoys_prev_post_loading ) {
						if ( junotoys_get_theme_option( 'posts_navigation_scroll_which_block', 'article' ) != 'article' ) {
							do_action( 'junotoys_action_between_posts' );
						}
					}
					// Single post thumbnail and title
					$junotoys_path = apply_filters( 'junotoys_filter_get_template_part', 'templates/single-styles/' . junotoys_get_theme_option( 'single_style' ) );
					if ( junotoys_get_file_dir( $junotoys_path . '.php' ) != '' ) {
						get_template_part( $junotoys_path );
					}
				}

				// Widgets area above page
				$junotoys_body_style   = junotoys_get_theme_option( 'body_style' );
				$junotoys_widgets_name = junotoys_get_theme_option( 'widgets_above_page', 'hide' );
				$junotoys_show_widgets = ! junotoys_is_off( $junotoys_widgets_name ) && is_active_sidebar( $junotoys_widgets_name );
				if ( $junotoys_show_widgets ) {
					if ( 'fullscreen' != $junotoys_body_style ) {
						?>
						<div class="content_wrap">
							<?php
					}
					junotoys_create_widgets_area( 'widgets_above_page' );
					if ( 'fullscreen' != $junotoys_body_style ) {
						?>
						</div>
						<?php
					}
				}

				// Content area
				do_action( 'junotoys_action_before_content_wrap' );
				?>
				<div class="content_wrap<?php echo 'fullscreen' == $junotoys_body_style ? '_fullscreen' : ''; ?>">

					<?php do_action( 'junotoys_action_content_wrap_start' ); ?>

					<div class="content">
						<?php
						do_action( 'junotoys_action_page_content_start' );

						// Skip link anchor to fast access to the content from keyboard
						?>
						<a id="content_skip_link_anchor" class="junotoys_skip_link_anchor" href="#"></a>
						<?php
						// Single posts banner between prev/next posts
						if ( ( junotoys_is_singular( 'post' ) || junotoys_is_singular( 'attachment' ) )
							&& $junotoys_prev_post_loading 
							&& junotoys_get_theme_option( 'posts_navigation_scroll_which_block', 'article' ) == 'article'
						) {
							do_action( 'junotoys_action_between_posts' );
						}

						// Widgets area above content
						junotoys_create_widgets_area( 'widgets_above_content' );

						do_action( 'junotoys_action_page_content_start_text' );
