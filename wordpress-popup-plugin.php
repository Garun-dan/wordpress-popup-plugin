<?php

/**
 * Plugin Name:     WordPress Popup Plugin
 * Plugin URI:      https://github.com/Garun-dan/wordpress-popup-plugin
 * Description:     Plugin pop-up menggunakan WP REST API
 * Author:          Rahmad
 * Author URI:      https://github.com/Garun-dan
 * Text Domain:     wordpress-popup-plugin
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wordpress_Popup_Plugin
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once plugin_dir_path(__FILE__) . 'includes/class-wp-popup-api.php';

final class WP_Popup_Plugin
{
    private static $instance = null;

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->define_hooks();
    }

    private function define_hooks()
    {
        add_action('init', array($this, 'register_cpt_popup'));
    }

    public function register_cpt_popup()
    {
        register_post_type('wp_popup', array(
            'labels'      => array(
                'name'          => __('Pop Ups'),
                'singular_name' => __('Pop Up')
            ),
            'public'      => true,
            'has_archive' => false,
            'supports'    => array('title', 'editor', 'custom-fields'),
            'show_in_rest' => true
        ));
    }
}

WP_Popup_Plugin::get_instance();
