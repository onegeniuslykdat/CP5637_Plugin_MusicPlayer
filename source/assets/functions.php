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
        <h3>Current most played song (played: <?php echo $songs[0]->play_count; ?> times)</h3>
        <h4>
            <?php echo $songs[0]->song_name; ?>
        </h4>

        <br />

        <audio id="auo-audio" controls>
            <source id="auo-source" src="" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>

        <h3>Songs in the library</h3>
        <ul id="auo-song-list">
            <?php foreach ($songs as $song) : ?>
                <li data-id="<?php echo $song->id; ?>" data-text="<?php echo $song->song_name; ?>" data-src="<?php echo esc_url(wp_upload_dir()['url'] . '/' . $song->song_name); ?>">
                    <?php echo $song->song_name; ?>
                    <p><em>Played: <?php echo esc_html($song->play_count); ?> times</em></p>
                </li>
                <br />
            <?php endforeach; ?>
        </ul>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('auo_music_player', 'auo_music_player');

function auo_update_play_count()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'auo_songs_list';

    $song_name = sanitize_text_field($_POST['song_name']);
    $song_id = sanitize_text_field($_POST['song_id']);
    $song = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = ", $song_id));

    if ($song) {
        $wpdb->update(
            $table_name,
            array('play_count' => $song->play_count + 1),
            array('id' => $song->id)
        );
    }

    wp_die();
}
add_action('wp_ajax_auo_update_play_count', 'auo_update_play_count');
add_action('wp_ajax_nopriv_auo_update_play_count', 'auo_update_play_count');
