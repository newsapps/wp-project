<?php
/*****
 * Wordpress automated setup
 * setup_blog.php
 *
 * Creates and configures one network blog. Pass it the index of the blog
 * in the blogs data file.
 *
 * As of 3.1.2 we can only make one blog per init.
 * http://core.trac.wordpress.org/ticket/12028
 *****/

require_once( 'cli-load.php' );

global $settings;

$options = getopt("n:");

// Check for data/blogs.json
if ( file_exists( dirname( __DIR__ ) . '/data/blogs.json' ) ) {
    $tmp_fn = dirname( __DIR__ ) . '/data/blogs.json';
    $tmp_fc = file_get_contents($tmp_fn);

    $sites = json_decode($tmp_fc, $assoc = true);
}

global $wpdb;

if ( $options['n'] >= count($sites) ) die("No more blogs.\n");

$site = $sites[$options['n']];

$wpdb->hide_errors();

if ($settings['subdomain_install']) {
    $id = wpmu_create_blog($site['slug'].".".$settings['hostname'], "", $site['name'], 1, $settings['site'], 1);
} else {
    $id = wpmu_create_blog($settings['hostname'], "/".$site['slug'], $site['name'], 1, $settings['site'], 1);
}

$wpdb->show_errors();

if (!is_wp_error( $id )) {
    //doing a normal flush rules will not work, just delete the rewrites
    switch_to_blog( $id );

    // we delete the rewrites because flushing them does not work if the originally
    // loaded blog is the main one, deleteing them will force a propper flush on that site's first page.
    delete_option( 'rewrite_rules' );

    // Delete the first post
    wp_delete_post( 1, true );

    // Delete the about page
    wp_delete_post( 2, true );

    // flush rewrite rules
    delete_option( 'rewrite_rules' );

    // set all the defaults for the blog
    foreach ($settings['blog'] as $key=>$val)
        update_option($key, $val);

    update_option('blogdescription', $site['description']);

    // Set upload path and url
    $upload_settings = array(
        'upload_path' => WP_CONTENT_DIR . $settings['upload_path'],
        'upload_url_path' => WP_CONTENT_URL . $settings['upload_url_path']
    );

    foreach ($upload_settings as $key=>$val)
        update_option($key, $val);

    restore_current_blog();
    unset( $id );

    print("Success - ".$site['name']." setup\n");
} else {
    die($id->get_error_message());
}
