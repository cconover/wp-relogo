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
	register_setting( 'cc-relogo-options-group', 'cc_relogo_options', 'cc_relogo_options_validate' ); // Register the settings group with the function

	add_settings_section(
		'options',							// Name of the section
		'Options',							// Title of the section, displayed on the options page
		'cc_relogo_options_callback',		// Callback function for displaying information
		'cc-relogo'							// Page ID for the options page
	);

	add_settings_field(						// Toggle the rel="logo" tag in <head>
		'active',							// Field ID
		'Active?',							// Field title, displayed to the left of the field on the options page
		'cc_relogo_active_callback',		// Callback function to display the field
		'cc-relogo',						// Page ID for the options page
		'options'							// Settings section in which to display the field
	);
	add_settings_field(						// Logo URL
		'logourl',
		'Logo URL',
		'cc_relogo_logourl_callback',
		'cc-relogo',
		'options'
	);
}

/* Display information about settings section */
function cc_relogo_options_callback() {
	echo '<p>Please provide the URL to the SVG file you would like to use.</p>';
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
	
	echo '<input id="logourl" name="cc_relogo_options[logourl]" type="text" size="'. $logourl_length . '" value="' . $options['logourl'] . '" />'; // Display text input field for 'logourl'
} // End cc_relogo_logourl_callback()

/* Callback for 'active' */
function cc_relogo_active_callback() {
	$options = get_option( 'cc_relogo_options' ); // Retrieve plugin options from the database
	
	/* Determine whether the box should be checked based on setting in database */
	if ( isset( $options['active'] ) ) {
		$checked = 'checked';
	}
	else {
		$checked = '';
	}
	
	echo '<input id="active" name="cc_relogo_options[active]" type="checkbox" value="Active" ' . $checked . '>';
} // End cc_relogo_active_callback()

/* Validate the options submitted by the user */
function cc_relogo_options_validate( $input ) {
	$options = get_option( 'cc_relogo_options '); // Retrieve options from database
	
	$logourl = $input['logourl']; // Set local variable for 'logourl' passed from form
	
	/* Make sure the input is a URL that starts with either http:// or https:// */
	if ( preg_match( '/^http\:\/\/|https\:\/\//i', $logourl ) ) {
		/* Check whether user provided .svg file, otherwise throw an error */
		if ( preg_match( '/\.svg$/i', $logourl ) ) {
			$options['logourl'] = $logourl; // File type validates, pass input to the database options
		}
		else {
			add_settings_error( 'cc_relogo_options', 'invalid-file-extension', 'You did not provide an SVG file.' );
		} // End SVG vaildation
	}
	else {
		add_settings_error( 'cc_relogo_options', 'invalid-url-protocol', 'You did not provide a valid URL. The URL must start with either "http://" or "https://".' );
	}
	
	/* Directly pass options not needing validation back to the database */
	$options['active'] 		= $input['active'];
	
	return $options;
} // End cc_relogo_options_validate()

/* Provide the user with the <img> tag that uses the relogo.org API */
function cc_relogo_api_imgtag() {
	$url = get_site_url(); // Retrieve the site URL
	$url = preg_replace( "/^http\:\/\/|https\:\/\//i", "", $url ); // Strip the protocol from the site URL
	
	$imgtag = '<strong>Use this tag to display your logo elsewhere:</strong> <pre><code>&lt;img src="http://relogo.org/api/' . $url . '" /&gt;</code></pre>'; // Create the <img> tag to display to the user
	
	echo $imgtag; // Display the tag
}

/**
 * Set admin notice if 'active' is not set
 */
function cc_relogo_inactive_admin_notice() {
	$options = get_option( 'cc_relogo_options' ); // Retrieve options from the database
	
	$optionsurl = admin_url( 'options-general.php?page=cc-relogo' ); // Get link to Relogo Options page
	
	/* Check whether 'active' is set, and if not show a notice */
	if ( !isset( $options['active'] ) ) {
		?>
		<div class="error">
			<p>The rel="logo" tag is not currrently active. Please update <a href="<?php echo $optionsurl; ?>">Relogo Settings</a> to make it active.</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'cc_relogo_inactive_admin_notice' ); // Add admin notice function to admin notices hook

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
		<?php
		cc_relogo_api_imgtag(); // Display API-based <img> tag
		?>
		<br />
		<!-- Button to donate Bitcoins -->
		<p>Like this plugin? Why not <a href="https://christiaanconover.com/code/wp-relogo#donate" target="_blank">buy me a cup of coffee</a>?</p>
	</div>
	
	<?php	
}
?>