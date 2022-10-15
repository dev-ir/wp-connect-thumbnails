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

        public static function get_image_id( $url ){
            $attachment_id = 0;
            if ( false !== strpos( $url, wp_upload_dir()['baseurl'] . '/' ) ) {
                $file = basename( $url );
                $query_args = array(
                    'post_type'   => 'attachment',
                    'post_status' => 'inherit',
                    'fields'      => 'ids',
                    'meta_query'  => array(
                        array(
                            'value'   => $file,
                            'compare' => 'LIKE',
                            'key'     => '_wp_attachment_metadata',
                        ),
                    )
                );
                $query = new WP_Query( $query_args );
                if ( $query->have_posts() ) {
                    foreach ( $query->posts as $post_id ) {
                        $meta = wp_get_attachment_metadata( $post_id );
                        $original_file       = basename( $meta['file'] );
                        $cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
                        if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
                            $attachment_id = $post_id;
                            break;
                        }
                    }
                }
            }
            return $attachment_id;
        }

        public function wp_insert_attachment_from_url( $url , $post_id ) {
        

            $image_metadata = pathinfo($url);

            $file_name = sanitize_text_field($image_metadata)['filename'];
            $mime_type = wp_check_filetype($url)['type'];
            return wp_insert_attachment(
                array(
                    'guid'              => $url,
                    'post_title'        => $file_name,
                    'post_mime_type'    => $mime_type,
                 ),
                $file_name,
                $post_id
            );
        
        }

    }

}