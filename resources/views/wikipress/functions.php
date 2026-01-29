<?php
/**
* Theme functions and definitions
* by Launch and Sell (https://launchandsell.com)
*/


//enable to use unminified scripts
//@debug - deprecated use  WP SCRIPT_DEBUG instead
/*
if ( !defined( 'HTWIKI_DEBUG_SCRIPTS' ) ) {
	define( 'HTWIKI_DEBUG_SCRIPTS', false );
}
*/

// Documentation/Support URL
if ( ! defined( 'HT_WP_SUPPORT_URL' ) ) {
	define( 'HT_WP_SUPPORT_URL', 'https://help.launchandsell.com/categories/wikipress/' );
}

if ( ! function_exists( 'wikipress_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function wikipress_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on _s, use a find and replace
		 * to change '_s' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'wikipress', get_template_directory() . '/languages' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// TODO: test which of these we need

		// Add support for Block Styles.
		//add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'disable-custom-colors' );
		add_theme_support( 'disable-custom-font-sizes' );

	}
endif;
add_action( 'after_setup_theme', 'wikipress_setup' );

/**
* Login Functions
*/
require get_template_directory() . '/inc/login-functions.php';

/**
* Load Scripts
*/
require get_template_directory() . '/inc/scripts.php';

/**
* TGM Config
*/
require get_template_directory() . '/inc/tgm-config.php';

/**
* Theme Welcome
*/
require get_template_directory() . '/inc/welcome/htwiki-welcome-admin.php';

/**
* Wiki
*/
require get_template_directory() . '/inc/wiki/ht-wiki.php';

/**
* Customizer
*/
require get_template_directory() . '/inc/customizer.php';

/**
* Updater
*/
require get_template_directory() . '/inc/updater.php';;


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wikipress_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wikipress_content_width', 800 );
}
add_action( 'after_setup_theme', 'wikipress_content_width', 0 );


/**
 * Enqueues styles for front-end.
 */
function wikipress_theme_styles() {

	/*
	 * Loads our main stylesheet.
	 */
	wp_enqueue_style( 'wikipress-style', get_template_directory_uri() . '/css/style.css', false, 1.0 );

	//@todo get frontend styles to match editor styles, either in main style or variation of block editor styles for frontend
	//wp_enqueue_style( 'htwiki-block-editor-styles', get_template_directory_uri() . '/css/block-editor.css' );

}
add_action( 'wp_enqueue_scripts', 'wikipress_theme_styles', 9999 );

/**
 * Load block editor styles
 */
function wikipress_block_editor_styles() {

	wp_enqueue_style( 'wikipress-block-editor-styles', get_template_directory_uri() . '/css/block-editor.css' );

	// Overwrite Core block styles with empty styles.
	//wp_deregister_style( 'wp-block-library' );
	//wp_register_style( 'wp-block-library', '' );

	// Overwrite Core theme styles with empty styles.
	wp_deregister_style( 'wp-block-library-theme' );
	wp_register_style( 'wp-block-library-theme', '' );

}

if ( htwiki_get_current_view() == 'edit' || is_admin() ) {
	add_action( 'enqueue_block_editor_assets', 'wikipress_block_editor_styles' );
}


/**
 * Add custom query variable
 * @note - This functionality appears to break the is_front_page conditional, so is replaced by a standard $_GET
 */
function htwiki_add_custom_query_vars_filter( $vars ) {
	$vars[] = 'view';
	return $vars;
}
add_filter( 'query_vars', 'htwiki_add_custom_query_vars_filter' );


/**
 * Get the current page view
 */
function htwiki_get_current_view( $context = 'none' ) {
	//does not work with front page test
	//$view_var = get_query_var( 'view', 'read' );

	$view_var = isset($_GET['view']) ? $_GET['view'] : 'read';
	$allowed_views = array( 'read', 'edit', 'history', 'talk' );
	if ( in_array( $view_var, $allowed_views ) ) {
		// is allowed
		$view = $view_var;
	} else {
		// not allowed view, set to default
		$view = 'read';
	}

	return $view;
}



/**
* Setup custom theme body classes
*/

add_filter( 'body_class', 'wkp_body_classes' );
function wkp_body_classes( $classes ) {

	$view_var = get_query_var( 'view', 'read' );

	if ( $view_var == 'read' ) {

		$classes[] = 'article-read-view';

	} elseif ( $view_var == 'edit' ) {

		$classes[] = 'article-edit-view';

	} elseif ( $view_var == 'history' ) {

		$classes[] = 'article-history-view';

	} elseif ( $view_var == 'talk' ) {

		$classes[] = 'article-talk-view';

	}

	return $classes;

}


//allow anon editing
add_filter( 'htwiki_allow_anon_media_upload', '__return_false' );
//add_filter('htwiki_allow_anon_editing', 'htwiki_allow_anon_editing_user_option');
function htwiki_allow_anon_editing_user_option( $allow ) {
	$allow = get_theme_mod( 'wkp_setting__wikianonedit', $allow );
	return $allow;
}

//allow anon media upload
add_filter( 'htwiki_allow_anon_media_upload', '__return_false' );
//add_filter( 'htwiki_allow_anon_media_upload', 'htwiki_allow_anon_media_upload_user_option' );
function htwiki_allow_anon_media_upload_user_option( $allow ) {
	$allow = get_theme_mod( 'wkp_setting__wikianonmedia', $allow );
	return $allow;
}


//redirect to setup assistant on theme activation
add_action( 'after_switch_theme', 'htwiki_onthemeswitch_welcome_redirect' );
function htwiki_onthemeswitch_welcome_redirect() {
	if( current_user_can( 'edit_theme_options' ) ){
		//transfer to welcome installer
		wp_safe_redirect( admin_url( 'themes.php?page=htwiki-welcome' ) );	
	}
}

//gutenberg not required for WP>5.1
add_filter( 'ht_wiki_gutenberg_plugin_required', 'htwiki_ht_wiki_gutenberg_plugin_required' );
function htwiki_ht_wiki_gutenberg_plugin_required( $required ){
	global $wp_version;
	if ( version_compare( $wp_version, '5.1', '>=' ) ) {
		return false;
	} else {
		return $required;
	}
}

//rename posts menu wiki, move to wiki functionality?
add_action( 'init', 'htwiki_change_post_object' );
// Change dashboard Posts to Wiki
function htwiki_change_post_object() {
	if(!is_admin()){
		return;
	}
    $get_post_type = get_post_type_object('post');
    $labels = $get_post_type->labels;
    $labels->name = __( 'Wiki', 'wikipress' );
    $labels->singular_name = __( 'Wiki', 'wikipress' );
    $labels->add_new = __( 'Add Wiki Page', 'wikipress' );
    $labels->add_new_item = __( 'Add Wiki Page', 'wikipress' );
    $labels->edit_item = __( 'Edit Wiki Page', 'wikipress' );
    $labels->new_item = __( 'Wiki Page', 'wikipress' );
    $labels->view_item = __( 'View Wiki Page', 'wikipress' );
    $labels->search_items = __( 'Search Wiki Pages', 'wikipress' );
    $labels->not_found = __( 'No Wiki Pages found', 'wikipress' );
    $labels->not_found_in_trash = __( 'No Wiki Pages found in Trash', 'wikipress' );
    $labels->all_items = __( 'All Wiki Pages', 'wikipress' );
    $labels->menu_name = __( 'Wiki', 'wikipress' );
    $labels->name_admin_bar = __( 'Wiki', 'wikipress' );
}

//todo: Change New->Post to New-> Wiki Page in admin menu

// Get categories/subcategories
function htwiki_get_categories($parent=0){
	$wkp_hp_cat_args = array(
		'orderby' => 'name',
		'order' => 'ASC',
		'hierarchical' => true,
		'hide_empty' => 0,
		'exclude' => 0,
		'pad_counts'  => 1
	);

	$wkp_categories = get_categories( $wkp_hp_cat_args ); 
	$wkp_categories = wp_list_filter( $wkp_categories, array( 'parent' => $parent ) );
	return apply_filters('htwiki_get_categories', $wkp_categories);
}

add_action( 'wp_ajax_update_menu_handle', 'htwiki_update_menu_handler' );
add_action( 'wp_ajax_nopriv_update_menu_handle', 'htwiki_update_menu_handler' );
function htwiki_update_menu_handler(){
	$result = array();
	try {
		ob_start();
	    get_template_part('header', 'nav');
	    $nav = ob_get_contents();
	    ob_end_clean();
	    $result['state'] = 'success';
	    $result['nav'] = $nav;
	} catch (Exception $e) {
		$result['state'] = 'error';
	    $result['error'] = $e->getMessage();
	}
	echo json_encode( $result );
    die(); //required to return complete result
}

/** this does not appear to work as a trash fix? */
add_action( 'template_redirect', 'htwiki_trash_fix' );
function htwiki_trash_fix(){
	if (is_admin() || !isset($_GET['trashed']) ) {
	  return;
	} 

	wp_redirect( home_url() );
    die;	
}

/**
* Modify default 'post' post type to be more suitable for wiki presentation
*/
add_action( 'init', 'wkp_modify_posts_posttype' );
function wkp_modify_posts_posttype() {
	remove_post_type_support( 'post', 'excerpt' );
	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'post', 'trackbacks' );
	remove_post_type_support( 'post', 'page-attributes' );
	
}

/**
* Adds theme name + version to the <head> tag
*/
function ht_themeversion_in_header() {
	echo __( '<meta name="generator" content="' . get_ht_theme_name() . ' v' . get_ht_theme_version() . '" />' . "\n", 'wikipress' );
}
add_action( 'wp_head', 'ht_themeversion_in_header' );

/**
* Function for returning the theme name
*/
function get_ht_theme_name() {
	$ht_theme = wp_get_theme();
	return $ht_theme->get( 'Name' );
}

/**
* Function for returning parent theme version (auto-updater)
*/
function get_ht_theme_version() {
	$theme_data    = wp_get_theme();
	$theme_version = '';
	$is_child      = ht_is_child_theme( $theme_data );
	if ( $is_child ) {
		$theme_version = $theme_data->parent()->get( 'Version' );
	} else {
		$theme_version = $theme_data->get( 'Version' );
	}
	return $theme_version;
}

/**
* Is active theme child theme?
*/
function ht_is_child_theme( $theme_data ) {
	$parent = $theme_data->parent();
	if ( ! empty( $parent ) ) {
		return true;
	}
	return false;
}

/**
* Check we have WordPress >= 5.4
*/
function ht_wiki_check_wp_version_notice(){
	global $wp_version;
	if ( version_compare( $wp_version, '5.4', '>=' ) ) {
		//no action
	} else {
		?>
			<div class="notice notice-error">
                <p><?php printf( __('WikiPress requires WordPress v5.4 or greater, <a href="%s">Please update WordPress now</a>.', 'wikipress' ), admin_url( 'update-core.php' ) ); ?></p>
            </div>
		<?php
	}
}
add_action( 'admin_notices', 'ht_wiki_check_wp_version_notice' );