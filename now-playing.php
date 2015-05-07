<?php
/*
Plugin Name: Now Playing Widget
Plugin URI: 
Description: Displays an optional musical link (to Youtube) at the top of a post
Version: 0.5.2.3
Author: Dan Conley
Author URI: http://www.danconley.net
License: Kopyleft
*/

// 1. The edit post form
add_action('add_meta_boxes','dans_metaboxes');
add_action('save_post','music_save_postdata');
wp_enqueue_script('music-js',plugins_url('now-playing/now-playing.js',dirname(__FILE__)),array('jquery'));
wp_enqueue_style('font-awesome',plugins_url('now-playing/font-awesome.min.css',dirname(__FILE__)));
wp_enqueue_style('music',plugins_url('now-playing/now-playing.css',dirname(__FILE__)));

function dans_metaboxes() {
	add_meta_box("music","Now Playing","music_callback","post","side","core");
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
	if (!update_post_meta($id,'_np_artist',preg_replace("/&/","&amp;",sanitize_text_field($_POST['np_artist']),TRUE))) add_post_meta($id,'_np_artist',preg_replace("/&/","&amp;",sanitize_text_field($_POST['np_artist'])));
	if (!update_post_meta($id,'_np_song',preg_replace("/&/","&amp;",sanitize_text_field($_POST['np_song']),TRUE))) add_post_meta($id,'_np_song',preg_replace("/&/","&amp;",sanitize_text_field($_POST['np_song'])));
	if (!update_post_meta($id,'_np_url',preg_replace("/&/","&amp;",sanitize_text_field($_POST['np_url']),TRUE))) add_post_meta($id,'_np_url',preg_replace("/&/","&amp;",sanitize_text_field($_POST['np_url'])));
}

// 2. The display on posts
add_filter('the_content','music_display');

function music_display($content) {
	global $post;
	// if it's not a post, and the post doesn't at least have a youtube url, don't bother
	if ($post->post_type != "post") return $content;
	$url = get_post_meta($post->ID,"_np_url",TRUE);
	if (!$url) return $content;

	// yeah, I use Synacor syntax now. shit's mixed.
	if (strpos($url, 'youtube') !== FALSE)
	{
		preg_match("/v=([^&]+)/",$url,$match);
		$embed = "<iframe src=\"https://www.youtube.com/embed/" . $match[1] . "?rel=0\" frameborder=\"0\" allowfullscreen id=\"video\" style=\"display:none\"></iframe>";
	}
	else if (strpos($url, 'vimeo') !== FALSE)
	{
		preg_match('/([0-9]+)\/?$/', $url, $match);
		$embed = '<iframe src="//player.vimeo.com/video/' . $match[1] . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen id="video" style="display:none"></iframe>';
	}
	$artist = get_post_meta($post->ID,"_np_artist",TRUE);
	$song = get_post_meta($post->ID,"_np_song",TRUE);

	if ($song) $playing .= "\"" . $song . "\"";
	if ($song && $artist) $playing .= " - ";
	if ($artist) $playing .= $artist;

	$content = "<p id=\"nowplaying\"><a href=\"javascript:void(0)\"><i class=\"icon-music icon-large\"></i> " . $playing . " (<span id=\"arrow\">listen <i class=\"icon-angle-down icon-large\"></i></span>)</a></p>\r" . $embed . "\r" . $content;
	return $content;
}

?>
