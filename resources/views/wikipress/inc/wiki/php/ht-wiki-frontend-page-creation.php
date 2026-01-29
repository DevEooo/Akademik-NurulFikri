<?php
/**
 *   HT Wiki Front End Page Creation
 */


// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

if ( ! class_exists( 'HT_Wiki_Page_Creation' ) ) {

	if ( ! defined( 'HTWIKI_UPDATE_SLUG_META_KEY' ) ) {
		define( 'HTWIKI_UPDATE_SLUG_META_KEY', '_htwiki_update_slug_required' );
	}

	class HT_Wiki_Page_Creation {

		// Constructor
		function __construct() {
			//page slug

			add_filter('htwiki_page_creation_url', array($this, 'htwiki_page_creation_url'));

			add_filter('htwiki_page_new_url', array($this, 'htwiki_page_new_url'));

			add_filter('template_redirect', array($this, 'htwiki_actions'));

			//maybe update slug
			add_filter( 'wp_insert_post_data', array($this, 'htwiki_update_slug_on_new_article'), 99, 2 );
		}

		/**
		* Page creation URL
		*/
		function htwiki_page_creation_url($url=''){
			return wp_nonce_url( '?wiki_action=create&slug=' . sanitize_title($this->htwiki_get_new_page_slug()), 'ht_wiki_create' );
		}

		/**
		* New page URL
		*/
		function htwiki_page_new_url(){
			return wp_nonce_url( '?wiki_action=new', 'ht_wiki_new' );
		}

		/**
		* Get the new page slug from the request uri (this must be santized by santize_title where used)
		*/
		function htwiki_get_new_page_slug(){
			$uri = (is_array($_SERVER) && array_key_exists('REQUEST_URI', $_SERVER) ) ? $_SERVER['REQUEST_URI']  : false;

			if(!$uri){
				return false;
			}

			preg_match('/([\w\-]+)/', $uri, $matches);

			if(!is_array($matches)){
				return false;
			}

			return $matches[0];
		}

		/**
		* Wiki actions
		*/
		function htwiki_actions(){
			$wiki_action = (is_array($_GET) && array_key_exists('wiki_action', $_GET) ) ? $_GET['wiki_action']  : false;

			switch ($wiki_action) {
				case 'create':
					$this->htwiki_action_create();
					break;
				case 'new':
					$this->htwiki_action_new();
					break;
				
				default:
					break;
			}
		}

		/**
		* Create an article from a 404 action
		*/
		function htwiki_action_create(){
			//security check
			check_admin_referer( 'ht_wiki_create' );

			$slug = (is_array($_GET) && array_key_exists('slug', $_GET) ) ? $_GET['slug']  : false;
			$slug = sanitize_title($slug);

			$nice_title = ucwords(str_replace('-',' ',$slug));

			$new_post = array(
				  'post_content'   => __('<!-- wp:paragraph -->
										<p>This is a newly created article and can now be edited</p>
										<!-- /wp:paragraph -->', 'wikipress'),
				  'post_name'      => $slug,
				  'post_title'     => $nice_title,
				  'post_status'    => 'publish',
				  'post_type'      => 'post',
				  'comment_status' => get_default_comment_status()
				);

			$new_post_id = wp_insert_post($new_post);

			$new_post_permalink = get_permalink($new_post_id);

			$new_post_edit_view = $new_post_permalink . '?view=edit';

			wp_safe_redirect($new_post_edit_view);

			exit;

		}

		/**
		* Create a brand new article
		*/
		function htwiki_action_new(){

			//security check
			check_admin_referer( 'ht_wiki_new' );

			if ( !apply_filters( 'htwiki_allow_anon_editing', false ) && !is_user_logged_in() ){
				wp_safe_redirect('login/');
				exit;
			}

			//$slug = (is_array($_GET) && array_key_exists('slug', $_GET) ) ? $_GET['slug']  : false;
			//$slug = sanitize_title($slug);
			$slug = '';

			$nice_title =  '';

			$new_post = array(
				  'post_content'   => __('<!-- wp:paragraph -->
										<p>This is a newly created article and can now be edited</p>
										<!-- /wp:paragraph -->', 'wikipress'),
				  'post_name'      => $slug,
				  'post_title'     => $nice_title,
				  'post_status'    => 'publish',
				  'post_type'      => 'post',
				  'comment_status' => get_default_comment_status()
				);

			$new_post_id = wp_insert_post($new_post);

			//flag the post that the slug needs updating when the title is updated
			update_post_meta( $new_post_id, HTWIKI_UPDATE_SLUG_META_KEY, true );

			$new_post_permalink = get_permalink($new_post_id);

			$new_post_edit_view = $new_post_permalink . '?view=edit';

			wp_safe_redirect($new_post_edit_view);

			exit;

		}

		/**
		* Update slug if flag is set
		*/
		function htwiki_update_slug_on_new_article( $data, $postarr ) {
			$post_id = $postarr['ID'];

			if($post_id && !empty( get_post_meta($post_id, HTWIKI_UPDATE_SLUG_META_KEY, true) ) ){
				$post = get_post($post_id);
				if($post->post_name==$data['post_name']){
					//user has not already set a new slug for the article, so generate it from the page title
					$data['post_name'] = sanitize_title( $data['post_title'] );
				}
				//remove the update slug flag
				delete_post_meta( $post_id, HTWIKI_UPDATE_SLUG_META_KEY );
			}
			
			return $data;
		}



	} //end class HT_Wiki_Page_Creation
}//end class exists test


// run the plugin
if ( class_exists( 'HT_Wiki_Page_Creation' ) ) {
	$ht_wiki_frontend_page_creation_init = new HT_Wiki_Page_Creation();
}
