<?php

/**
 * Plugin Name: Enqueue Anything
 * Plugin URI: https://porretto.com/enqueue-any
 * Description: Quickly add scripts or styles to your site
 * Version: 1.0.1
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: Terrence Porretto
 * Author URI: https://porretto.com
 * License: GPL2
 * Text Domain: enqueue-any
 */

include('enqueue-any-settings.php');

function enqueue_any_add_scripts_anywhere() {

    $posts = get_posts([
        'post_type' => 'enqueue_any',
        'post_status' => 'publish',
        'numberposts' => -1
    ]);

    if($posts) {

        foreach ($posts as $key => $value) {

            $handle = get_post_meta( $value->ID, 'ea_name' );
            $src = get_post_meta( $value->ID, 'ea_url' );
            $deps = '';
            $ver = '';
            $in_footer = get_post_meta( $value->ID, 'ea_in_footer' );
            $in_footer = $in_footer[0] == 0 ? false :  true;
            $media = "all";
            $check_file_type = pathinfo($src[0]);

            if(array_key_exists("extension", $check_file_type)) {
                if ($check_file_type["extension"] == "js") {
                    wp_register_script( $handle[0], $src[0], $deps, $ver, $in_footer );
                    wp_enqueue_script( $handle[0] );
                }

                if ($check_file_type["extension"] == "css") {
                    wp_enqueue_style( $handle[0], $src[0], $deps, $ver, $media );
                }
            }else {
                if (strpos($check_file_type["filename"], 'icon') || strpos($check_file_type["filename"], 'family') || strpos($check_file_type["filename"], 'font')) {
                    wp_enqueue_style( $handle[0], $src[0], $deps, $ver, $media );
                }
            }
        }

    }
}
add_action( 'wp_enqueue_scripts', 'enqueue_any_add_scripts_anywhere' );
add_action( 'admin_enqueue_scripts', 'enqueue_any_add_scripts_anywhere' );

// Register Custom Post Type
function enqueue_any_post_type()
{

    $labels = array(
        'name'                  => _x('Enqueue Anything', 'Post Type General Name', 'enqueue_any'),
        'singular_name'         => _x('Enqueue Any', 'Post Type Singular Name', 'enqueue_any'),
        'menu_name'             => __('Enqueue Anything', 'enqueue_any'),
        'name_admin_bar'        => __('Enqueue Any', 'enqueue_any'),
        'archives'              => __('Enqueue Anything', 'enqueue_any'),
        'attributes'            => __('Enqueue Anything Attributes', 'enqueue_any'),
        'parent_item_colon'     => __('Parent Item:', 'enqueue_any'),
        'all_items'             => __('All Items', 'enqueue_any'),
        'add_new_item'          => __('Add New Asset', 'enqueue_any'),
        'add_new'               => __('Add New', 'enqueue_any'),
        'new_item'              => __('New Item', 'enqueue_any'),
        'edit_item'             => __('Edit Item', 'enqueue_any'),
        'update_item'           => __('Update Item', 'enqueue_any'),
        'view_item'             => __('View Item', 'enqueue_any'),
        'view_items'            => __('View Items', 'enqueue_any'),
        'search_items'          => __('Search Item', 'enqueue_any'),
        'not_found'             => __('Not found', 'enqueue_any'),
        'not_found_in_trash'    => __('Not found in Trash', 'enqueue_any'),
        'featured_image'        => __('Featured Image', 'enqueue_any'),
        'set_featured_image'    => __('Set featured image', 'enqueue_any'),
        'remove_featured_image' => __('Remove featured image', 'enqueue_any'),
        'use_featured_image'    => __('Use as featured image', 'enqueue_any'),
        'insert_into_item'      => __('Insert into item', 'enqueue_any'),
        'uploaded_to_this_item' => __('Uploaded to this item', 'enqueue_any'),
        'items_list'            => __('Items list', 'enqueue_any'),
        'items_list_navigation' => __('Items list navigation', 'enqueue_any'),
        'filter_items_list'     => __('Filter items list', 'enqueue_any'),
    );
    $args = array(
        'label'                 => __('Enqueue Any', 'enqueue_any'),
        'description'           => __('Post Type Description', 'enqueue_any'),
        'labels'                => $labels,
        'supports'              => array('title', 'custom-fields'),
        'taxonomies'            => array('category', 'post_tag'),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => false,
        'show_in_menu'          => false,
        'menu_position'         => 5,
        'show_in_admin_bar'     => false,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );
    register_post_type('enque-any', $args);
}
add_action('init', 'enqueue_any_post_type', 0);

function enqueue_any_save_asset() {
    $name = isset( $_POST['name'] ) ? sanitize_text_field($_POST['name']) : 'N/A';
    $url = isset( $_POST['url'] ) ? esc_url_raw($_POST['url']) : 'N/A';
    $footer = isset( $_POST['footer'] ) ? filter_var($_POST['footer'], FILTER_SANITIZE_NUMBER_INT) : 'N/A';
    // insert the post
    $post_id = wp_insert_post(array(
        'post_type' => 'enqueue_any',
        'post_title' => 'Enque Storage',
        'post_content' => 'Settings stored in post meta table',
        'post_status' => 'publish',
        'comment_status' => 'closed',   // if you prefer
        'ping_status' => 'closed',      // if you prefer
        // some simple key / value array
        'meta_input' => array(
            'ea_name' => $name,
            'ea_url' => $url,
            'ea_in_footer' => $footer
            // and so on ;)
        )
    ));
	wp_die(); // required. to end AJAX request.
}
add_action( 'wp_ajax_save_asset', 'enqueue_any_save_asset' );

// add a nonce for ajax calls
function enqueue_any_nonce() {
    wp_nonce_field( 'enqueue_any_nonce', 'enqueue_any_nonce' );
}

function enqueue_any_delete_post() {
    if(get_post_meta( $_POST['id'], 'ea_name' ) && get_post_meta( $_POST['id'], 'ea_url' )) {
        $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
        if ( ! wp_verify_nonce( $_POST['enqueue_any_nonce'], 'enqueue_any_nonce' ) ) {
            exit( 'Nonce verification failed' );
        } else {
            // remove the post
            wp_delete_post($id);
            wp_die(); // required. to end AJAX request.
        }
    }
}
add_action( 'wp_ajax_remove_asset', 'enqueue_any_delete_post' );

function enqueue_any_scripts_and_styles($hook_suffix) {
    if ($hook_suffix == 'toplevel_page_enqueue-anything') {
        wp_enqueue_style( 'flat-remix', plugins_url( 'css/flat-remix.min.css', __FILE__ ) );
        wp_enqueue_style( 'ea-styles', plugins_url( 'css/styles.css', __FILE__ ) );
        wp_enqueue_script( 'ea-save', plugins_url( 'js/save-asset-ajax.js', __FILE__ ) );
        wp_enqueue_script( 'ea-delete', plugins_url( 'js/delete-asset-ajax.js', __FILE__ ) );
    }
}
add_action( 'admin_enqueue_scripts', 'enqueue_any_scripts_and_styles' );
?>