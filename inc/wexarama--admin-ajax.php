<?php

use ParagonIE\Sodium\Core\Curve25519\Ge\P2;

add_action('wp_ajax_get_update_counter_byID', 'get_update_counter_byID');
add_action('wp_ajax_get_update_counter', 'get_update_counter');
add_action('wp_ajax_get_update_thumbnail', 'get_update_thumbnail');
add_action('admin_footer', function () { ?>
    <script>
        jQuery(document).ready(function($) {
            jQuery('.get_update_count_byID').click(function() {
                var data = {
                    'action': 'get_update_counter_byID',
                    'post_id': jQuery(this).data('post'),
                };
                jQuery.post(ajaxurl, data, function(response) {
                    alert('Got this from the server: ' + response);
                    location.reload();
                });
            });

            function get_update_counter() {
                var data = {
                    'action': 'get_update_counter',
                    'nonce': true,
                };
                jQuery.post(ajaxurl, data, function(response) {
                    result = JSON.parse(response);
                    if (result.counter != 0) {
                        jQuery('#the-list').append("<tr><td>" + result.counter + "</td><td>" + result.post_id + "</td><td><a href='" + result.post_url + "'>" + result.post_name + "</a></td><td>" + result.post_count + "</td></tr>");
                        var pregcent = parseFloat(parseInt((jQuery(".table-update-counter tr").length - 1), 10) * 100) / parseInt(jQuery('#the-list td:first').text(), 10);
                        jQuery('.progress-bar-fill').css('width', pregcent + '%');
                        jQuery('.progress-bar-fill').text(parseInt(pregcent) + '%');
                        setTimeout(get_update_counter, 2000);
                    } else {
                        alert('Complated.');
                    }
                });
            }

            function get_update_thumbnail() {
                var data = {
                    'action': 'get_update_counter',
                    'nonce': true,
                };
                jQuery.post(ajaxurl, data, function(response) {
                    result = JSON.parse(response);
                    if (result.counter != 0) {
                        jQuery('#the-list').append("<tr><td>" + result.counter + "</td><td>" + result.post_id + "</td><td><a href='" + result.post_url + "'>" + result.post_name + "</a></td><td>" + result.post_count + "</td></tr>");
                        var pregcent = parseFloat(parseInt((jQuery(".table-update-counter tr").length - 1), 10) * 100) / parseInt(jQuery('#the-list td:first').text(), 10);
                        jQuery('.progress-bar-fill').css('width', pregcent + '%');
                        jQuery('.progress-bar-fill').text(parseInt(pregcent) + '%');
                        setTimeout(get_update_thumbnail, 2000);
                    } else {
                        alert('Complated.');
                    }
                });
            }

            jQuery('#get_update_counter').click(function() {
                get_update_counter();
            });

            jQuery('#get_update_thumbnail').click(function() {
                get_update_thumbnail();
            });

        });
    </script>
<?php
});

function get_update_counter()
{
    if (get_option('get_temp_db') == false) {
        $posts = get_posts([
            'post_type'     => 'post',
            'post_status'   => 'publish',
            'numberposts'   => -1,
            'order'         => 'DESC',
            'fields'        => 'ids'
        ]);
        update_option('get_temp_db', $posts);
    }
    $fetch      = get_option('get_temp_db');
    $post_id    = $fetch[0];
    $core       = new dv_live_core();
    $response   = $core->get_count_image_from_post($post_id);
    update_post_meta($post_id, 'get_count_img', $response);
    unset($fetch[0]);
    update_option('get_temp_db', array_values($fetch));
    $post_url   = get_permalink($post_id);
    $post_name  = get_the_title($post_id);
    echo json_encode([
        'post_id'   => $post_id,
        'post_url'  => $post_url,
        'post_name' => $post_name,
        'post_count' => $response,
        'counter'   => count($fetch),
    ]);
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


function get_update_thumbnail()
{
    $core = new dv_live_core();
    if (get_option('get_temp_db_thumbnail') == false) {
        $list_posts = query_posts(
            [
                'post_status'       =>  'publish',
                'post_type'         =>  'post',
                'fields'            => 'ids',
                'posts_per_page'    => -1,
                'order'             => 'DESC',
                'meta_query'        =>   [
                    'relation'      => 'OR',
                    [
                        'key' => '_thumbnail_id_update',
                        'compare' => 'NOT EXISTS'
                    ]
                ]
            ]
        );
        update_option('get_temp_db_thumbnail', $list_posts);
    }

    $fetch      = get_option('get_temp_db_thumbnail');
    $post_id    = $fetch[0];

    $string = get_post_field('post_content', $post_id);

    preg_match_all('~<img.*?src=["\']+(.*?)["\']+~', $string, $urls);
    $url = $urls[1][0];


    $image_id = $core->wp_insert_attachment_from_url($url,$post_id);
    update_post_meta($post_id,'_thumbnail_id',$image_id);
    update_post_meta($post_id,'_thumbnail_id_update',true);

    update_post_meta($image_id,'_wp_attached_file', str_replace('https://dl.listwallpaper.com/uploads/','',$url));

    unset($fetch[0]);
    update_option('get_temp_db_thumbnail', array_values($fetch));
    
    $post_url   = get_permalink($post_id);
    $post_name  = get_the_title($post_id);
    echo json_encode([
        'post_id'   => $post_id,
        'post_url'  => $post_url,
        'post_name' => $post_name,
        'post_count' => true,
        'counter'   => count($fetch),
    ]);
    die();
}
