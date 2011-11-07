<?php
/*****
 * Wordpress automated setup
 * setup_root.php
 *
 * Configures the root blog
 *****/

require_once( 'cli-load.php' );

global $settings;

// Set default blog options
foreach ($settings['root_site'] as $key=>$val)
    update_option($key, $val);

// Set upload path and url
$upload_settings = array(
    'upload_path' => WP_CONTENT_DIR . $settings['upload_path'],
    'upload_url_path' => WP_CONTENT_URL . $settings['upload_url_path']
);

foreach ($upload_settings as $key=>$val)
    update_option($key, $val);

print("Default blog options set\n");

// Set network options
foreach ($settings['network'] as $key => $val)
    update_site_option($key, $val);

$allowed_themes = array();
foreach ( $settings['network']['allowed_themes'] as $theme )
    $allowed_themes[$theme] = true;

update_site_option('allowedthemes', $allowed_themes);

print("Default network options set\n");
