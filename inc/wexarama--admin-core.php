<?php

use ParagonIE\Sodium\Core\Curve25519\Ge\P2;

if( ! class_exists('dv_live_core') ){

    class dv_live_core{

        public function __construct()
        {
            
        }

        public function get_count_image_from_post( $post_id ){
            if( empty($post_id) ){
                $post_id = 1;
            }
            $string = get_post_field('post_content', $post_id);

            preg_match_all('~<img.*?src=["\']+(.*?)["\']+~', $string, $urls);
            $urls = $urls[1];

            $count =  count($urls);

            if( !empty( $count ) ){
                return $count;
            }else{
                return 0;
            }

        }

    }

}