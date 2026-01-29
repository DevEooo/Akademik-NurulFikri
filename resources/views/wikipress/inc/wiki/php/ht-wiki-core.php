<?php
/**
 *   HT Wiki Core Functionality
 */


// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; }

if ( ! class_exists( 'HT_Wiki_Core' ) ) {

	class HT_Wiki_Core {

		// Constructor
		function __construct() {
			// no core functionality yet
		}

	} //end class HT_Wiki_Core
}//end class exists test


// run the plugin
if ( class_exists( 'HT_Wiki_Core' ) ) {
	$ht_wiki_core_init = new HT_Wiki_Core();
}
