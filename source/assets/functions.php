<?php

function auo_music_player($atts)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'auo_songs_list';

    // Fetch songs from the database
    $songs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY play_count DESC");

    // Output music player and most played songs
    ob_start();
?>
    <div id="auo-player">
        <!-- <audio id="mpmp-audio" controls>
            <source id="mpmp-source" src="" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio> -->
        <ul id="auo-song-list">
            <?php foreach ($songs as $song) : ?>
                <li data-src="<?php echo esc_url(wp_upload_dir()['url'] . '/' . $song->song_name); ?>">
                    <a href="<?php echo esc_url(wp_upload_dir()['url'] . '/' . $song->song_name); ?>">
                        <?php echo esc_html($song->song_name); ?>
                    </a>
                    <p>Plays: <?php echo esc_html($song->play_count); ?></p>
                </li>
                <br />
            <?php endforeach; ?>
        </ul>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('auo_music_player', 'auo_music_player');

function update_play_count()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'auo_songs_list';

    $song_name = sanitize_text_field($_POST['song_name']);
    $song = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE song_name = %s", $song_name));

    if ($song) {
        $wpdb->update(
            $table_name,
            array('play_count' => $song->play_count + 1),
            array('id' => $song->id)
        );
    }

    wp_die();
}
add_action('wp_ajax_update_play_count', 'update_play_count');
add_action('wp_ajax_nopriv_update_play_count', 'update_play_count');
