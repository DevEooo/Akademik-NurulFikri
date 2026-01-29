<?php

/**
 * Enqueues scripts for front-end.
 */

//exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


function wikipress_enqueue_scripts() {

	$wikipres_theme = wp_get_theme();
    $wikipress_theme_version = $wikipres_theme->get( 'Version' );

	/*
	* Load our main theme JavaScript file
	*/
	//$wikipress_js_src = (SCRIPT_DEBUG) ? 'js/htwiki-js.js' : 'js/htwiki-js.min.js';
	//$wikipress_js_js_url = get_template_directory_uri() . DIRECTORY_SEPARATOR . $wikipress_js_src;
	$wikipress_js_src = (SCRIPT_DEBUG) ? '/js/htwiki-js.js' : '/js/htwiki-js.min.js';
	$wikipress_js_js_url = get_template_directory_uri() . $wikipress_js_src;
	wp_register_script( 'wikipress-js', $wikipress_js_js_url, array( 'jquery' ), $wikipress_theme_version, true );
	wp_enqueue_script( 'wikipress-js' );
	wp_localize_script(
		'wikipress-js',
		'ajax_login_object',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'redirecturl' => home_url( '/' ),
			'loadingmessage' => __( 'Sending user info, please wait...', 'wikipress' ),
		)
	);

	/*
	* Load the nav magic
	*/
	//$wikipress_nav_magic_src = (SCRIPT_DEBUG) ? 'js/htwiki-nav-magic.js' : 'js/htwiki-nav-magic.min.js';
	//$wikipress_nav_magic_js_url = get_template_directory_uri() . DIRECTORY_SEPARATOR . $wikipress_nav_magic_src;
	$wikipress_nav_magic_src = (SCRIPT_DEBUG) ? '/js/htwiki-nav-magic.js' : '/js/htwiki-nav-magic.min.js';
	$wikipress_nav_magic_js_url = get_template_directory_uri() . $wikipress_nav_magic_src;
	wp_register_script( 'wikipress-nav-magic', $wikipress_nav_magic_js_url, array( 'jquery', 'wp-api', 'backbone' ), $wikipress_theme_version, true );
	wp_enqueue_script( 'wikipress-nav-magic' );
	wp_localize_script(
		'wikipress-nav-magic',
		'ajax_information',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		)
	);

	/*
	* Register JavaScript for Live Search
	*/
	wp_register_script('wikipress-live-search', get_template_directory_uri() . '/js/jquery.livesearch.js', array( 'jquery' ), $wikipress_theme_version, true);

}
add_action( 'wp_enqueue_scripts', 'wikipress_enqueue_scripts' );


/**
 * Add live search JavaScript to the footer
 */
function wikipress_add_live_search () {
?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
	jQuery('#wkp-search').liveSearch({url: '<?php echo home_url(); ?>/index.php?ajax=1&s='});
	});
	</script>
<?php
	wp_enqueue_script('wikipress-live-search');

}

add_action('wp_footer', 'wikipress_add_live_search');	