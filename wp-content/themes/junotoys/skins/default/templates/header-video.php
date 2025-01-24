<?php
/**
 * The template to display the background video in the header
 *
 * @package JUNOTOYS
 * @since JUNOTOYS 1.0.14
 */
$junotoys_header_video = junotoys_get_header_video();
$junotoys_embed_video  = '';
if ( ! empty( $junotoys_header_video ) && ! junotoys_is_from_uploads( $junotoys_header_video ) ) {
	if ( junotoys_is_youtube_url( $junotoys_header_video ) && preg_match( '/[=\/]([^=\/]*)$/', $junotoys_header_video, $matches ) && ! empty( $matches[1] ) ) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr( $matches[1] ); ?>"></div>
		<?php
	} else {
		?>
		<div id="background_video"><?php junotoys_show_layout( junotoys_get_embed_video( $junotoys_header_video ) ); ?></div>
		<?php
	}
}
