<?php

/*
Plugin Name: WPU Thumbnails
Description: Centralized way to add Thumbnails sizes to WordPress.
Version: 0.1.1
Author: Darklg
Author URI: http://darklg.me/
License: MIT License
License URI: http://opensource.org/licenses/MIT
*/

if (!function_exists('add_image_size')) {
    return;
}

class WPUThumbnails
{

    function __construct() {
        add_action('init', array(&$this,
            'add_image_sizes'
        ));
        add_filter('intermediate_image_sizes_advanced', array(&$this,
            'remove_default_img_sizes'
        ));
        add_filter('image_size_names_choose', array(&$this,
            'custom_image_sizes_choose'
        ));
    }

    // Get user image sizes & ensure good values
    function get_images_sizes() {
        $user_sizes = apply_filters('wpu_thumbnails_sizes', array());
        $sizes = array();
        foreach ($user_sizes as $id => $user_size) {
            $size = $user_size;
            if (!is_array($user_size)) {
                $size = array();
            }

            if (!isset($size['w']) || !is_numeric($size['w'])) {
                $size['w'] = 0;
            }

            if (!isset($size['h']) || !is_numeric($size['h'])) {
                $size['h'] = 0;
            }

            if (!isset($size['crop']) || !is_bool($size['crop'])) {
                $size['crop'] = false;
            }

            if (!isset($size['name']) || empty($size['name'])) {
                $size['name'] = $id;
            }

            if (!isset($size['display_gallery_insert']) || !is_bool($size['display_gallery_insert'])) {
                $size['display_gallery_insert'] = true;
            }

            if (!isset($size['post_type']) || !is_array($size['post_type'])) {
                $size['post_type'] = array(
                    'any'
                );
            }

            $sizes[$id] = $size;
        }
        return $sizes;
    }

    // Add our new image sizes
    function add_image_sizes() {
        if (!isset($_REQUEST['post_id'])) {
            return;
        }
        $post_type = get_post_type($_REQUEST['post_id']);
        $sizes = $this->get_images_sizes();
        foreach ($sizes as $id => $size) {
            if (in_array('any', $size['post_type']) || in_array($post_type, $size['post_type'])) {
                add_image_size($id, $size['w'], $size['h'], $size['crop']);
            }
        }
    }

    // Delete unused default image sizes
    function remove_default_img_sizes($sizes) {
        unset($sizes['medium']);
        unset($sizes['large']);
        return $sizes;
    }

    // Add image to admin selector
    function custom_image_sizes_choose($sizes) {
        $user_sizes = $this->get_images_sizes();
        foreach ($user_sizes as $id => $size) {
            if ($size['display_gallery_insert']) {
                $sizes[$id] = $size['name'];
            }
        }
        return $sizes;
    }
}

$WPUThumbnails = new WPUThumbnails();
