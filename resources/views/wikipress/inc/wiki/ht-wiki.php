<?php

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; }

if ( ! class_exists( 'HT_Wiki' ) ) {

	// required for updater
	if ( ! defined( 'HT_WIKI_MAIN_PLUGIN_FILE' ) ) {
		define( 'HT_WIKI_MAIN_PLUGIN_FILE', __FILE__ );
	}

	if ( ! defined( 'HT_WIKI_VERSION_NUMBER' ) ) {
		define( 'HT_WIKI_VERSION_NUMBER', '0.1.3' );
	}

	/*
	@deprecated - use WP SCRIPT_DEBUG instead
	//enable to use unminfied scripts
	if(!defined('HT_WIKI_DEBUG_SCRIPTS')){
		define('HT_WIKI_DEBUG_SCRIPTS', true);
	}
	*/

	class HT_Wiki {

		// Constructor
		function __construct() {

			/* LOAD MODULES */
			include_once( 'php/ht-wiki-core.php' );
			include_once( 'php/ht-wiki-frontend-revisions.php' );
			include_once( 'php/ht-wiki-frontend-gutenberg.php' );
			include_once( 'php/ht-wiki-frontend-page-creation.php' );
		}

	} //end class HT_Wiki
}//end class exists test


// run the plugin
if ( class_exists( 'HT_Wiki' ) ) {
	$ht_wiki_init = new HT_Wiki();
}
