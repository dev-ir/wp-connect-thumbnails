<?php

/**
 * Plugin Name: DV Live Wallpaper 
 * Plugin URI:  https://developermen.ir/product-category/wordpress/plugins/dv-live-wallpaper
 * Description: Adding special features from Friday: calling the first image indicator, the image dialer
 * Version:     2.0
 * Author:      Mahdi Ebrahimi
 * Author URI:  https://dvgroups.ca
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: dv-feature-image
 * Domain Path: /languages
 * Requires at least: 4.9
 * Tested up to: 5.8
 * Requires PHP: 5.2.4
 */
if (!ABSPATH) exit;
if (!class_exists('dv_live_wallpaper')) {

    add_action('plugins_loaded', array('dv_live_wallpaper', 'instance'));


    class dv_live_wallpaper
    {

        public function __construct()
        {
            $this->defined();
            $this->include_files();
            $this->admin();
            $this->add_count_image();
        }

        public function defined(){
            

            if( !defined('wexaram__dir_url') ){
                define( 'wexaram__dir_url' , plugin_dir_url( __FILE__ ) );
              }
              if( !defined('wexaram__dir_path') ){
                define( 'wexaram__dir_path' , plugin_dir_path( __FILE__ ) );
              }
        }
        private function add_count_image()
        {

            // Add the custom columns to the book post type:
            add_filter('manage_post_posts_columns', function ($columns) {
                $columns['image_count'] = __('Count image', 'your_text_domain');
                return $columns;
            });


            // Add the data to the custom columns for the book post type:
            add_action('manage_post_posts_custom_column', function ($column, $post_id) {

                $counter = !empty( get_post_meta($post_id,'get_count_img',true) ) ? get_post_meta($post_id,'get_count_img',true) : '0';

                switch ($column) {

                    case 'image_count':
                        echo '<div>';
                        echo '<span class="button" style="margin-right:10px"> '. $counter .' </span>';
                        echo '<span class="button button-primary" data-post="'.$post_id.'" id="get_update_count_byID" >' . __( 'Re Count' ) . '</span>'; 
                        echo '</div>';
                    break;
                }
            }, 10, 2);
        }

        public static function instance()
        {
            static $instance = null;
            if (is_null($instance)) {
                $instance = new dv_live_wallpaper;
            }
            return $instance;
        }

        private function include_files(){

            $list_file = [
                'wexarama--admin-core'      => 'inc',
                'wexarama--admin-ajax'      => 'inc',
                'wexarama--admin-table'     => 'inc',
                'wexarama--enqueue'         => 'inc',
            ];
            if( isset( $list_file ) ){
                foreach( $list_file as $file => $folder ){
                    require __DIR__ . '\\' . $folder . '\\' . $file . '.php';
                }
            }
            
        }

        public function admin()
        {
            add_action('admin_menu', function () {
                add_menu_page(
                    __('Setting LW', 'textdomain'),
                    __('Setting LW', 'textdomain'),
                    'manage_options',
                    'admin',
                    'dv_live_settings'
                );
            });
        }
    }
}
