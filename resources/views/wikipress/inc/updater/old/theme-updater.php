<?php
/**
 * Theme Updater Config
 */

// Includes the files needed for the theme updater
if ( !class_exists( 'EDD_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/theme-updater-admin.php' );
}

global $htwiki_theme_updater;
// Loads the updater classes
$htwiki_theme_updater = new EDD_Theme_Updater_Admin(

	// Config settings
	$config = array(
		'remote_api_url' => 'https://launchandsell.com/?nocachee', // Site where EDD is hosted
		'item_name' => 'WikiPress Theme', // Name of theme
		'theme_slug' => 'wikipress', // Theme slug
		'version' => get_ht_theme_version(), // The current version of this theme
		'author' => 'Launch and Sell', // The author of this theme
		'download_id' => '', // Optional, used for generating a license renewal link
		'renew_url' => '' // Optional, allows for a custom license renewal link
	),

	// Strings
	$strings = array(
		'theme-license' => __( 'WikiPress Theme License', 'wikipress' ),
		'enter-key' => __( 'Enter your WikiPress theme license key.', 'wikipress' ),
		'license-key' => __( 'License Key', 'wikipress' ),
		'license-action' => __( 'License Action', 'wikipress' ),
		'deactivate-license' => __( 'Deactivate License', 'wikipress' ),
		'activate-license' => __( 'Activate License', 'wikipress' ),
		'status-unknown' => __( 'License status is unknown.', 'wikipress' ),
		'renew' => __( 'Renew?', 'wikipress' ),
		'unlimited' => __( 'unlimited', 'wikipress' ),
		'license-key-is-active' => __( 'License key is active.', 'wikipress' ),
		'expires%s' => __( 'Expires %s.', 'wikipress' ),
		'%1$s/%2$-sites' => __( 'You have %1$s / %2$s sites activated.', 'wikipress' ),
		'license-key-expired-%s' => __( 'License key expired %s.', 'wikipress' ),
		'license-key-expired' => __( 'License key has expired. You may need to check the status your Launch and Sell account.', 'wikipress' ),
		'license-keys-do-not-match' => __( 'License keys do not match.', 'wikipress' ),
		'license-is-inactive' => __( 'License is inactive. You may need to check the status your Launch and Sell account.', 'wikipress' ),
		'license-key-is-disabled' => __( 'License key is disabled. You may need to check the status your Launch and Sell account.', 'wikipress' ),
		'site-is-inactive' => __( 'Site is inactive. You may need to transfer your domain from your Launch and Sell account.', 'wikipress' ),
		'license-status-unknown' => __( 'License status is unknown. You may need to check the status your Launch and Sell account.', 'wikipress' ),
		'update-notice' => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'wikipress' ),
		'update-available' => __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'wikipress' )
	)

);