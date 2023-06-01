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

// GitHub API https://api.github.com/repos/tporret/wp-dev/contents/plugins
function private_repo_plugins_table_api() {
    $plugin_list = wp_remote_get( 'https://api.github.com/repos/tporret/wp-dev/contents/plugins' );
    return $plugin_list;
}

function private_repo_plugin_tabs($tabs) {
    $tabs['private_repo'] = __('Private Repo', 'private-repo');
    return $tabs;
}
add_filter('install_plugins_tabs', 'private_repo_plugin_tabs');

// Populate the custom tab with your own plugin list
function private_repo_tab_content() {

    $plugin_list = private_repo_plugins_table_api();
    $plugin_list = json_decode( $plugin_list['body'] );
    $plugin_list = array_reverse( $plugin_list );

    // Display the plugin list
    ?>
    <div class="plugin-install-popular wp-clearfix">
        <div class="plugin-install-popular-list">
            <?php foreach ( $plugin_list as $plugin ) : ?>
                <div class="plugin-card">
                    <div class="plugin-card-top">
                        <div class="name column-name">
                            <h3>
                                <a href="<?php echo esc_url( $plugin->_links->self  ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $plugin->name  ); ?></a>
                            </h3>
                        </div>
                        <div class="action-links">
                            <ul class="plugin-action-buttons">
                                <li>
                                    <a href="<?php echo esc_url( $plugin->_links->self  ); ?>" class="install-now button" data-slug="<?php echo esc_attr( $plugin->name  ); ?>" data-name="<?php echo esc_attr( $plugin->name  ); ?>" aria-label="<?php echo esc_attr( $plugin->name  ); ?> <?php esc_attr_e( 'Install Now' ); ?>" data-icon="">
                                        <?php esc_html_e( 'Install Now' ); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="desc column-description">
                            <p><?php echo esc_html( $plugin->name  ); ?></p>
                            <p class="authors"><?php printf( __( 'By %s' ), esc_html( $plugin->name  ) ); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}
add_action('install_plugins_private_repo', 'private_repo_tab_content');

// Enqueue js/partytowm/lib/partytown.js
function private_repo_enqueue_scripts() {
    wp_enqueue_script( 'partytown', plugins_url( 'js/partytown/lib/partytown.js', __FILE__ ), array(), '1.0.0', false );
    wp_enqueue_script( 'ga4', 'https://www.googletagmanager.com/gtag/js?id=G-C1N082Q0S4', array('partytown'), '1.0.0', true );
    wp_enqueue_script( 'google-analytics', plugins_url( 'js/analytics/analytics.js', __FILE__ ), array('partytown'), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'private_repo_enqueue_scripts' );

// Party Time !!!
function add_type_to_script( $tag, $handle, $src ) {

    if ( $handle === 'ga4' || $handle === 'google-analytics' ) {
        $tag = '<script type="text/partytown" src="' . esc_url( $src ) . '" class="' . $handle . '-pt"></script>';
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'add_type_to_script', 10, 3 );