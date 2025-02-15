<?php

/**
 * Plugin Name:     WordPress Popup Plugin
 * Plugin URI:      https://github.com/Garun-dan/wordpress-popup-plugin
 * Description:     Plugin pop-up menggunakan WP REST API dan React
 * Author:          Rahmad
 * Author URI:      https://github.com/Garun-dan
 * Text Domain:     wordpress-popup-plugin
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Wordpress_Popup_Plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

// Load REST API Class
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
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));

        // Hook aktivasi & deaktivasi hanya didaftarkan satu kali
        register_activation_hook(__FILE__, array($this, 'on_activation'));
        register_deactivation_hook(__FILE__, array($this, 'on_deactivation'));
    }

    public function register_cpt_popup()
    {
        $args = array(
            'labels'             => array(
                'name'          => __('Pop Ups', 'wordpress-popup-plugin'),
                'singular_name' => __('Pop Up', 'wordpress-popup-plugin'),
            ),
            'public'             => true,
            'has_archive'        => false,
            'supports'           => array('title', 'editor', 'custom-fields'),
            'show_in_rest'       => true,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-format-aside',
        );

        register_post_type('wp_popup', $args);
    }

    public function enqueue_scripts()
    {
        if (!is_admin()) {
            wp_enqueue_script(
                'wp-popup-script',
                plugin_dir_url(__FILE__) . 'assets/js/popup.bundle.js',
                array('wp-element'),
                filemtime(plugin_dir_path(__FILE__) . 'assets/js/popup.bundle.js'),
                true
            );

            wp_enqueue_style(
                'wp-popup-style',
                plugin_dir_url(__FILE__) . 'assets/css/popup.css',
                array(),
                filemtime(plugin_dir_path(__FILE__) . 'assets/css/popup.css')
            );

            wp_localize_script('wp-popup-script', 'wpPopup', array(
                'nonce'  => wp_create_nonce('wp_rest'),
                'apiUrl' => rest_url('artistudio/v1/popup')
            ));
        }
    }


    // public function enqueue_scripts()
    // {
    //     if (!is_admin()) {
    //         // Load React-based popup script
    //         wp_enqueue_script(
    //             'wp-popup-script',
    //             plugin_dir_url(__FILE__) . 'assets/js/popup.bundle.js',
    //             array('wp-element'), // WP React dependency
    //             filemtime(plugin_dir_path(__FILE__) . 'assets/js/popup.bundle.js'),
    //             true
    //         );

    //         wp_enqueue_style(
    //             'wp-popup-style',
    //             plugin_dir_url(__FILE__) . 'assets/css/popup.css',
    //             array(),
    //             filemtime(plugin_dir_path(__FILE__) . 'assets/css/popup.css')
    //         );

    //         wp_localize_script('wp-popup-script', 'wpPopup', array(
    //             'nonce'  => wp_create_nonce('wp_rest'),
    //             'apiUrl' => rest_url('artistudio/v1/popup')
    //         ));
    //     }
    // }

    public function load_textdomain()
    {
        load_plugin_textdomain('wordpress-popup-plugin', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    public function on_activation()
    {
        $this->register_cpt_popup();
        flush_rewrite_rules();
    }

    public function on_deactivation()
    {
        flush_rewrite_rules();
    }
}

// Inisialisasi Plugin
WP_Popup_Plugin::get_instance();
