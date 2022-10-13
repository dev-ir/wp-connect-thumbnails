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
            $this->admin();
            $this->add_count_image();
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
                switch ($column) {

                    case 'image_count':
                        echo get_post_meta($post_id,'dv_image_count');
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

        public function admin()
        {
            add_action('admin_menu', function () {
                add_menu_page(
                    __('DV Live Wallpaper', 'textdomain'),
                    __('DV Live Wallpaper', 'textdomain'),
                    'manage_options',
                    'admin',
                    9
                );
            });
        }
    }
}
