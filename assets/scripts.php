<?php

function enqueue_scripts()
{
    wp_enqueue_style('auo-style', plugin_dir_url(__FILE__) . 'css/styles.css');
    wp_enqueue_script('auo-script', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'), null, true);

    // Use script for AJAX
    wp_localize_script('auo-script', 'auo_ajax', array('url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_scripts');
