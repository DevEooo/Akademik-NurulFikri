<?php

/**
 * Ajax login functions
 */

// Execute the action only if the user isn't logged in
if ( ! is_user_logged_in() ) {
	add_action( 'init', 'wikipress_ajax_login_init' );
}

function wikipress_ajax_login_init() {

	// Enable the user with no privileges to run ajax_login() in AJAX
	add_action( 'wp_ajax_nopriv_ajaxlogin', 'wikipress_ajax_login' );

}

function wikipress_ajax_login() {

	// First check the nonce, if it fails the function will break
	check_ajax_referer( 'ajax-login-nonce', 'security' );

	// Nonce is checked, get the POST data and sign user on
	$info = array();
	$info['user_login'] = $_POST['email'];
	$info['user_password'] = $_POST['password'];
	$info['remember'] = true;

	$user_signon = wp_signon( $info );
	if ( is_wp_error( $user_signon ) ) {
		echo json_encode(
			array(
				'loggedin' => false,
				'message' => __(
					'Wrong username or password.',
					'wikipress' ),
			)
		);
	} else {
		echo json_encode(
			array(
				'loggedin' => true,
				'message' => __(
					'Login successful, redirecting...',
					'wikipress' ),
			)
		);
	}

	die();
}


/**
 * Redirect users to homepage on logout
 */
add_action( 'wp_logout', 'wikipress_redirect_logout' );
function wikipress_redirect_logout() {
	wp_redirect( home_url( $path = '/' ) );
	exit();
}
