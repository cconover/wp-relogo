<?php
/**
 * Create entry in Settings menu
 * A submenu entry titled 'Relogo' is shown under Settings
 */
function cc_relogo_create_menu() {
	add_options_page(
		'Relogo Settings',					// Page title. This is displayed in the browser title bar.
		'Relogo',							// Menu title. This is displayed in the Settings submenu.
		'manage_options',					// Capability required to access the options page for this plugin
		'cc-relogo',						// Menu slug
		'cc_relogo_options_page'			// Function to render the options page
	);
}
add_action( 'admin_menu', 'cc_relogo_create_menu' );

/**
 * Relogo settings page configuration
 * Sets up options and settings fields for the options page
 */
add_action( 'admin_init', 'cc_relogo_admin_init' );

function cc_relogo_admin_init() {
	register_setting( 'cc-relogo-options-group', 'cc_relogo_options' ); // Register the settings group with the function

	add_settings_section(
		'options',							// Name of the section
		'Options',							// Title of the section, displayed on the options page
		'cc_relogo_options_callback',		// Callback function for displaying information
		'cc-relogo'							// Page ID for the options page
	);
	
	add_settings_field(
		'logourl',							// Field ID
		'Logo URL',							// Field title, displayed to the left of the field on the options page
		'cc_relogo_logourl_callback',		// Callback function to display the field
		'cc-relogo',						// Page ID for the options page
		'options'							// Settings section in which to display the field
	);
}

/* Display information about settings section */
function cc_relogo_options_callback() {
	echo '<p>Please provide a URL to the SVG file you would like to use.</p>';
}

/* 'logourl callback' */
function cc_relogo_logourl_callback() {
	$options = get_option( 'cc_relogo_options' ); // Retrieve options from database
	
	/* Make sure text input is wide enough to be useful */
	if ( strlen( $options['logourl'] ) < 60 ) {
		$logourl_length = 60; // Set the size to 60 if the length of the current value is less than that
	}
	elseif ( strlen( $options['logourl'] ) > 90 ) {
		$logourl_length = 90; // If the current value's length exceeds 90, set size to 90
	}
	else {
		$logourl_length = strlen( $options['logourl'] ); // Use the length of the current value if it's between 60 and 90
	}
	
	echo '<input id="logourl" name="cc_relogo_options[logourl]" type="text" size="'. $logourl_length . '" value="' . $options['logourl'] . '" />'; //Display text input field for 'logourl'
} // End 'logourl' callback

/**
 * Relogo Settings Page
 */
function cc_relogo_options_page() {
	/* Prevent users with insufficient permissions from accessing settings */
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( '<p>You do not have sufficient permissions to access this page.</p>' );
	}
	?>
	
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>Relogo Settings</h2>

		<form action="options.php" method="post">
			<?php
			settings_fields( 'cc-relogo-options-group' ); 	// Retrieve the fields created for the options page
			do_settings_sections( 'cc-relogo' ); 			// Display the section(s) for the options page
			submit_button();								// WordPress-generated 'Save Changes' button
			?>
		</form>
	</div>
	
	<?php	
}
?>