<?php
if (!defined('ABSPATH')) {
    exit;
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
            'permission_callback' => '__return_true'
        ));
    }

    public function get_popup_data()
    {
        $args = array(
            'post_type'      => 'wp_popup',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        );

        $query = new WP_Query($args);
        $popups = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $popups[] = array(
                    'id'      => get_the_ID(),
                    'title'   => get_the_title(),
                    'content' => apply_filters('the_content', get_the_content()),
                    'page'    => get_post_meta(get_the_ID(), 'page', true),
                );
            }
        }

        wp_reset_postdata(); // Reset post data

        if (empty($popups)) {
            return new WP_Error('no_popup', 'Tidak ada pop-up yang tersedia.', array('status' => 404));
        }

        return rest_ensure_response($popups);
    }
}

new WP_Popup_API();
