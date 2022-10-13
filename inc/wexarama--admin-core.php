<?php

use ParagonIE\Sodium\Core\Curve25519\Ge\P2;

if( ! class_exists('dv_live_core') ){

    class dv_live_core{

        public function __construct()
        {
            
        }

        public function get_count_image_from_post( $post_id ){
            if( !empty($post_id) ){

                $string = get_the_content($post_id);

            }
        }

    }

}