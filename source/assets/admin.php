<?php

// Add admin menu
function add_admin_menu()
{
    add_menu_page(
        'AUO Music Player',
        'Music Player',
        'manage_options',
        'auo_admin_page',
        'auo_admin_page_content',
        'dashicons-admin-music',
        20
    );
}
add_action('admin_menu', 'add_admin_menu');

// Admin page content
function admin_page_content()
{
?>
    <div class="wrap">
        <h1>AUO Music Player</h1>
        <form method="post" enctype="multipart/form-data">
            <?php
            if (isset($_POST['upload_song'])) {
                handle_file_upload();
            }
            ?>
            <h2>Add New Song</h2>
            <input type="text" name="song_name" placeholder="Song Name" required>
            <input type="file" name="song_file" accept="audio/*" required>
            <input type="submit" name="upload_song" value="Upload Song" class="button-primary">
        </form>
        <hr>
        <h2>Most Played Songs</h2>
        <?php display_songs_table(); ?>
    </div>
<?php
}

// Handle song file upload
function handle_file_upload()
{
    if (!isset($_FILES['song_file']) || !isset($_POST['song_name'])) {
        return;
    }

    $uploaded_file = $_FILES['song_file'];
    $song_name = sanitize_text_field($_POST['song_name']);
    $upload_dir = wp_upload_dir();
    $target_file = $upload_dir['path'] . '/' . basename($uploaded_file['name']);

    if (move_uploaded_file($uploaded_file['tmp_name'], $target_file)) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'auo_songs_list';

        $wpdb->insert(
            $table_name,
            array(
                'song_name' => basename($uploaded_file['name']),
                'play_count' => 0,
                'song_url' => $target_file
            )
        );
        echo '<div class="updated"><p>Song uploaded successfully.</p></div>';
    } else {
        echo '<div class="error"><p>Failed to upload song.</p></div>';
    }
}

// Display songs table
function display_songs_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'auo_songs_list';
    $songs = $wpdb->get_results("SELECT * FROM $table_name ORDER BY play_count DESC");

    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>Song Name</th><th>Count</th><th>Play</th></tr></thead>';
    echo '<tbody>';
    foreach ($songs as $song) {
        echo '<tr>';
        echo '<td>' . esc_html($song->song_name) . '</td>';
        echo '<td>' . esc_html($song->play_count) . '</td>';
        echo '<td><a href="' . esc_url(wp_upload_dir()['url'] . '/' . $song->song_name) . '" target="_blank">Play Song</a></td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}
