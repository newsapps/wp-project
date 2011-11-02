<?php

include( 'cli-load.php' );

$query = "select blog_id from wp_blogs";
$results = $wpdb->get_results($query, 'ARRAY_A');

foreach ( $results as $blog ) {
    switch_to_blog($blog['blog_id']);
    
    update_option('permalink_structure', '/%year%/%monthnum%/%postname%/');
    print "Set new permalink structure for " . get_bloginfo('name') . "\n";
    
    restore_current_blog();
}
