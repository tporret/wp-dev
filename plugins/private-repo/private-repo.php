<?php

/**
 * Plugin Name: Private Repo
 * Plugin URI: https://porretto.com/private-repo
 * Description: Add a custom repo for your private plugins and themes
 * Version: 1.0.0
 * Requires at least: 5.2
 * Requires PHP: 8.0
 * Author: Terrence Porretto
 * Author URI: https://porretto.com
 * License: GPL2
 * Text Domain: private-repo
 */

function private_repo_plugin_tabs($tabs) {
    $tabs['private_repo'] = __('Private Repo', 'private-repo');
    return $tabs;
}
add_filter('install_plugins_tabs', 'private_repo_plugin_tabs');

// GitHub API https://api.github.com/tporret/wp-dev
function private_repo_install_plugins_table_api_args() {
    $plugin_list = wp_remote_get( 'https://api.github.com/repos/tporret/wp-dev/contents/plugins' );
}
private_repo_install_plugins_table_api_args();

// Enqueue js/partytowm/lib/partytown.js
function private_repo_enqueue_scripts() {
    wp_enqueue_script( 'partytime', plugins_url( 'js/partytown/lib/partytown.js', __FILE__ ), array(), '1.0.0', false );
    wp_enqueue_script( 'ga4', 'https://www.googletagmanager.com/gtag/js?id=G-C1N082Q0S4', array(), '1.0.0', true );
    wp_enqueue_script( 'google-analytics', plugins_url( 'js/analytics/analytics.js', __FILE__ ), array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'private_repo_enqueue_scripts' );

// Party Time !!!
function add_type_to_script( $tag, $handle, $src ) {
    if ( 'ga4' === $handle || 'google-analytics' === $handle ) {
        $tag = '<script type="text/partytown" src="' . esc_url( $src ) . '"></script>';
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'add_type_to_script', 10, 3 );