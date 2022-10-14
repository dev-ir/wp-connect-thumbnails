<?php

use ParagonIE\Sodium\Core\Curve25519\Ge\P2;

add_action('wp_ajax_get_update_counter_byID', 'get_update_counter_byID');
add_action('wp_ajax_get_update_counter', 'get_update_counter');
add_action('admin_footer', function () { ?>
    <script>
        jQuery(document).ready(function($) {
            jQuery('#get_update_count_byID').click(function() {
                var data = {
                    'action': 'get_update_counter_byID',
                    'post_id': jQuery(this).data('post'),
                };
                jQuery.post(ajaxurl, data, function(response) {
                    alert('Got this from the server: ' + response);
                    location.reload();
                });
            });

            jQuery('#get_update_counter').click(function() {
                var data = {
                    'action': 'get_update_counter',
                    'nonce': true,
                };
                jQuery.post(ajaxurl, data, function(response) {
                    alert('Got this from the server: ' + response);
                    // location.reload();
                });
            });

        });
    </script>
<?php
});

function get_update_counter(){
    var_dump(get_option('get_temp_db'));
    if( get_option('get_temp_db') == false ){
        $posts = get_posts([
            'post_type'     => 'post',
            'post_status'   => 'publish',
            'numberposts'   => -1,
            'order'         => 'DESC',
            'fields'        => 'ids'
        ]);
        update_option( 'get_temp_db', $posts );
    }
    $fetch      = get_option('get_temp_db');
    $post_id    = $fetch[0];
    $core       = new dv_live_core();
    $response   = $core->get_count_image_from_post($post_id);
    update_post_meta($post_id, 'get_count_img', $response);
    unset($fetch[0]);
    update_option( 'get_temp_db', array_values($fetch) );
    echo json_encode(['post_id' => $post_id , 'count' => count($fetch) ]);
    die();

}

function get_update_counter_byID()
{
    $post = $_POST;

    $core = new dv_live_core();
    $response = $core->get_count_image_from_post($post['post_id']);

    update_post_meta($post['post_id'], 'get_count_img', $response);

    echo $response;

    die();
}
