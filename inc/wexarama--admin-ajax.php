<?php

use ParagonIE\Sodium\Core\Curve25519\Ge\P2;

add_action('wp_ajax_get_update_counter_byID', 'get_update_counter_byID');
add_action('admin_footer', function () { ?>
    <script>
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

    </script>
<?php
});

function get_update_counter_byID()
{
    $post = $_POST;

    $core = new dv_live_core();
    $response = $core->get_count_image_from_post( $post['post_id'] );

    update_post_meta( $post['post_id'] ,'get_count_img' , $response );

    echo $response;

    die();
}
