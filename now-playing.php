<?php
/*
Plugin Name: Now Playing Widget
Plugin URI: 
Description: Displays an optional musical link (to Youtube) at the bottom of a post
Version: 0.5.2.3
Author: Dan Conley
Author URI: http://www.danconley.net
License: Kopyleft
*/

add_action('add_meta_boxes','dans_metaboxes');
add_action('save_post','music_save_postdata');

function dans_metaboxes() {
	add_meta_box("music","Now Playing","music_callback","post","side","high");
}

function music_callback($post, $args) {
	$artist = get_post_meta($post->ID,'_np_artist',TRUE);
	$song = get_post_meta($post->ID,'_np_song',TRUE);
	$url = get_post_meta($post->ID,'_np_url',TRUE);

	wp_nonce_field( plugin_basename( __FILE__ ), 'music_nonce' );
	echo "<label for=\"np_artist\">Artist</label>\n";
	echo "<input type=\"text\" id=\"np_artist\" name=\"np_artist\" value=\"" . $artist . "\" size=\"35\" />\n";
	echo "<label for=\"np_song\">Song</label>\n";
	echo "<input type=\"text\" id=\"np_song\" name=\"np_song\" value=\"" . $song . "\" size=\"35\" />\n";
	echo "<label for=\"np_url\">Youtube</label>\n";
	echo "<input type=\"text\" id=\"np_url\" name=\"np_url\" value=\"" . $url . "\" size=\"35\" />\n";
}

function music_save_postdata($id) {
	// the battery of "are you supposed to be here and can you do this" stuff
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!wp_verify_nonce($_POST['music_nonce'], plugin_basename( __FILE__ ))) return;

	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) return;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) ) return;
	}

	// okay? okay.
	if (!add_post_meta($id,'_np_artist',sanitize_text_field($_POST['np_artist']),TRUE)) update_post_meta($id,'_np_artist',sanitize_text_field($_POST['np_artist']));
	if (!add_post_meta($id,'_np_song',sanitize_text_field($_POST['np_song']),TRUE)) update_post_meta($id,'_np_song',sanitize_text_field($_POST['np_song']));
	if (!add_post_meta($id,'_np_url',sanitize_text_field($_POST['np_url']),TRUE)) update_post_meta($id,'_np_url',sanitize_text_field($_POST['np_url']));
}

?>
