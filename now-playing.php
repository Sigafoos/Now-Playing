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

add_meta_box("music","Now Playing","music_callback","page","side");

add_action("save_post","music_save_postdata");

function music_callback() {
}

function music_save_postdata() {
}
