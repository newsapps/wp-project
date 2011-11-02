<?php

include( 'cli-load.php' );

# include plugin functions
require_once(ABSPATH . "wp-admin/includes/plugin.php");

global $settings;

//lets turn on plugins, network-wide
$network_plugins = array(
    "akismet/akismet.php"
);

foreach( $network_plugins as $plugin ) {
	
	echo "Activating network " . $plugin . " ...   ";
	$result = activate_plugin( $plugin, '', true );
	
	if ( is_wp_error( $result ) ) {
		foreach ( $result->get_error_messages() as $err )
			print("FAILED: {$err}\n");
	} else {
		print("Activated\n");
	}
	
}

do_action( 'plugins_loaded' );
