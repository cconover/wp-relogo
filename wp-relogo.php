<?php
/*
Plugin Name: Relogo
Plugin URI: https://christiaanconover.com/code/wp-relogo
Description: Add support for the rel="logo" tag to your site, in accordance with the spec published at <a href="http://relogo.org">relogo.org</a>. <strong>This requires your logo in SVG format.</strong>
Version: 0.1.1
Version: 0.2.0
Author: Christiaan Conover
Author URI: https://christiaanconover.com
License: GPLv2
*/


/**
 * Initiate created the rel="logo" tag during plugins loading
 * Now we set up the plugin for WordPress to execute it
 */
function cc_relogo_setup() {
	/* Add the tag to wp_head() */
	add_action( 'wp_head', 'cc_relogo_addtag' );
}
add_action( 'plugins_loaded', 'cc_relogo_setup' ); // Add setup to plugins_loaded()


/**
 * Add rel="logo" tag to <head>
 */
function cc_relogo_addtag() {
	$options = get_option( 'cc_relogo_options' ); // Get plugin options
	
	/* If there's a value for the logo URL, create the tag */
	if ( isset( $options['logourl'] ) ) {
		echo '<link rel="logo" type="image/svg" href="' . $options['logourl'] . '" />';
	}
} // End create tag


/**
 * If in wp-admin, load plugin's admin functions
 */
if ( is_admin() ) {
	require_once( dirname(__FILE__) . '/admin/wp-relogo-admin.php' );
}



/**
 * Activate plugin
 */
function cc_relogo_activate() {
	/* Check for WordPress version compatibility, and if it fails deactivate the plugin.
	   Current WordPress version compatibility: 3.5.2 and greater */
	if ( version_compare( get_bloginfo( 'version' ), '3.5.2', '<' ) ) {
		deactivate_plugins( basename(__FILE__) ); // Deactivate the plugin
	}
	
	$defaultlogo = plugin_dir_url( __FILE__ ) . 'assets/relogo.svg'; // Relogo logo SVG, included with plugin
	
	/* Set default options for plugin */
	$options = array (
		'logourl'	=> $defaultlogo,	// 'logourl' is set to the Relogo logo
		'active'	=> ''				// Adding tag to head is off
	);
	add_option( 'cc_relogo_options', $options );
} // End of activation
register_activation_hook( __FILE__, 'cc_relogo_activate' ); // Register activation function with WordPress' activation hook

/**
 * Deactivate plugin
 */
function cc_relogo_deactivate() {
	remove_action( 'wp_head', 'cc_relogo_addtag' );
} // End of deactivation
register_deactivation_hook( __FILE__, 'cc_relogo_deactivate' ); // Register deactivation function with WordPress' deactivation hook
?>