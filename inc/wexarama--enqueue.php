<?php 
add_action( 'admin_enqueue_scripts', function () {
    wp_enqueue_style( 'wp-toranjapp-progress-bar', wexaram__dir_url.'assets/plugins/progress-bar/css/wexarama--progress-bar.css', array(), 1.1, 'all' );
} );