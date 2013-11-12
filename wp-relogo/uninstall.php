<?php
/**
 * uninstall.php for wp-relogo
 * Remove all plugin data from WordPress if the plugin is uninstalled
 */

// Do not execute if uninstall is not requested by WordPress
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// Remove plugin options from options table in database
delete_option( 'cc_relogo_options' );
?>