<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class WP_Popup_API
{
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_api_endpoints'));
    }

    public function register_api_endpoints()
    {
        register_rest_route('artistudio/v1', '/popup/', array(
            'methods'  => 'GET',
            'callback' => array($this, 'get_popup_data'),
            'permission_callback' => function () {
                return is_user_logged_in();
            }
        ));
    }

    public function get_popup_data()
    {
        $args = array(
            'post_type'      => 'wp_popup',
            'posts_per_page' => -1
        );
        $query = new WP_Query($args);
        $popups = array();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $popups[] = array(
                    'title'   => get_the_title(),
                    'content' => get_the_content(),
                    'page'    => get_post_meta(get_the_ID(), 'page', true)
                );
            }
        }
        return $popups;
    }
}

new WP_Popup_API();
