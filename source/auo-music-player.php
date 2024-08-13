<?php
/*
Plugin Name: AUO Music Player
Description: AUO Music Player is a plugin that enhances your WordPress website with great audio from your library. Upload your favourite songs and see how well your users enjoy each song by how many times a song is played.
Version: 1.0.0
Author: Anthony Udochukwu Onyekwere
URL: https://github.com/onegeniuslykdat/CP5637_Plugin_MusicPlayer
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include necessary files
include_once(plugin_dir_path(__FILE__) . 'assets/functions.php');
include_once(plugin_dir_path(__FILE__) . 'assets/admin.php');
include_once(plugin_dir_path(__FILE__) . 'assets/scripts.php');

// Activation hook
function activate()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'auo_songs_list';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        song_name varchar(255) NOT NULL,
        play_count bigint(20) DEFAULT 0 NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'activate');
