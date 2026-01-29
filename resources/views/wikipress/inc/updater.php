<?php
/**
* Theme updater
*/

//debug feature for updater
//set_site_transient( 'update_plugins', null );

//HeroThemes site url and product name
define( 'HT_WIKIPRESS_HEROTHEMES_STORE_URL', 'https://launchandsell.com/?nocache' );
define( 'HT_WIKIPRESS_PRODUCT_NAME', 'WikiPress Theme' ); 

if( !class_exists( 'HT_Wiki_Updater' ) ) {
	class HT_Wiki_Updater {

		function __construct(){
			add_action('after_setup_theme', array( $this, 'ht_wiki_load_updater' ) );
		}

		function ht_wiki_load_updater(){
			if(!apply_filters('htwiki_disable_theme_updater', false)){
				include('updater/theme-updater.php');	
			}
			
		}

	}
}


if( class_exists( 'HT_Wiki_Updater' ) ) {

	$ht_wiki_updater_init = new HT_Wiki_Updater();

}