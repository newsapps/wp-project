<?php
/*****
 * Wordpress automated setup
 * setup.php
 *
 * Setup a WordPress instance and enable the network.
 *
 *****/

define('WP_INSTALLING', true);

require_once( 'cli-load.php' );

/** Load WordPress Administration Upgrade API */
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

/** Load wpdb */
// require_once( ABSPATH . 'wp-includes/wp-db.php');

global $settings;

$wp_install_result = wp_install(
    $settings['weblog_title'],
    $settings['user_name'],
    $settings['admin_email'],
    $settings['public'],
    '',
    $settings['admin_password']
);

if (is_wp_error( $wp_install_result )) {
    var_dump($wp_install_result);
    die("Wordpress install failed");
}

// Delete the first post
wp_delete_post( 1, true );

// Delete the default about page
wp_delete_post( 2, true );

print("Wordpress install finished\n");

// We need to create references to ms global tables to enable Network.
foreach ( $wpdb->tables( 'ms_global' ) as $table => $prefixed_table )
    $wpdb->$table = $prefixed_table;

install_network();

$ms_install_result = populate_network(
    $settings['network_id'],
    $settings['hostname'],
    $settings['admin_email'],
    $settings['network_title'],
    $settings['base'],
    $settings['subdomain_install']
);

if (is_wp_error( $ms_install_result ) && $ms_install_result->get_error_code() != 'no_wildcard_dns') {
    print($ms_install_result->get_error_message() . "\n");
    die("Network setup failed\n");
}

print("Network setup finished\n");
