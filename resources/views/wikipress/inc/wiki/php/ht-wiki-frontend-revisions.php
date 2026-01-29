<?php
/**
 *   HT Wiki Front End Revisions Functionality
 */


// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; }

if ( ! class_exists( 'HT_Wiki_Frontend_Revisions' ) ) {

	class HT_Wiki_Frontend_Revisions {

		// Constructor
		function __construct() {
			add_action( 'wp_head', array( $this, 'ht_wiki_wp_head' ) );
			add_action( 'the_content_history', array( $this, 'ht_wiki_the_content_history' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'ht_wiki_enqueue_revisions_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'ht_wiki_enqueue_revisions_styles' ) );

			// reroute revisions
			add_action( 'wp_ajax_nopriv_get-revision-diffs', array( $this, 'ht_wiki_ajax_get_revision_diffs' ) );
		}

		/**
		 * Ajax get revision diffs
		 */
		function ht_wiki_ajax_get_revision_diffs() {
			// add temporary permissions
			add_filter( 'user_has_cap', array( $this, 'ht_wiki_revision_permissions' ), 10, 3 );

			wp_ajax_get_revision_diffs();
			// remove temporary permissions
			remove_filter( 'user_has_cap', array( $this, 'ht_wiki_revision_permissions' ), 10, 3 );
		}

		/**
		 * Assign revision history permissions
		 */
		function ht_wiki_revision_permissions( $allcaps, $cap, $args ) {
			if ( is_user_logged_in() ) {
				//return $allcaps;
			}

			if( is_user_admin() ) {
   				return $allcaps;
			}
			// give permissions - @todo - work out controls or options
			$allcaps['read'] = true;
			$allcaps['manage_categories'] = false;
			$allcaps['edit_post'] = true;
			$allcaps['edit_posts'] = true;
			$allcaps['edit_others_posts'] = true;
			$allcaps['edit_published_posts'] = true;
			$allcaps['edit_others_pages'] = true;
			$allcaps['edit_published_pages'] = true;
			$allcaps['edit_page'] = true;
			$allcaps['edit_pages'] = true;

			$allcaps['switch_themes'] = false;
			$allcaps['edit_themes'] = false;
			$allcaps['activate_plugins'] = false;
			$allcaps['edit_plugins'] = false;
			$allcaps['edit_users'] = false;
			$allcaps['import'] = false;
			$allcaps['unfiltered_html'] = false;
			$allcaps['edit_plugins'] = false;
			$allcaps['unfiltered_upload'] = false;

			return $allcaps;
		}

		/**
		 * Header includes
		 */
		function ht_wiki_wp_head() {
			// don't run in admin
			if ( is_admin() ) {
				return;
			}

			// required admin includes, all appear necessary to avoid warnings
			require_once( ABSPATH . '/wp-admin/includes/template.php' );
			require_once( ABSPATH . 'wp-admin/includes/revision.php' );
			require_once( ABSPATH . 'wp-admin/includes/misc.php' );
			require_once( ABSPATH . 'wp-admin/includes/post.php' );
			?>

			<!-- define global rtl setting, otherwise revisions.js complains -->
			<script type="text/javascript">
				window.isRtl = <?php echo (int) is_rtl(); ?>;
				window.adminpage = 'revision-php';
			</script>

			<?php

		}

		/**
		 * Content history action hook
		 */
		function ht_wiki_the_content_history() {
			$this->ht_wiki_post_revisions();
		}

		/**
		 * Display post revisions container
		 */
		function ht_wiki_post_revisions( $args = null ) {
			// don't run in admin
			if ( is_admin() ) {
				return;
			}

			global $post, $revision, $action, $from, $to;

			// code below based on wp-admin/revision.php
			wp_reset_vars( array( 'revision', 'action', 'from', 'to' ) );

			// get the revisions of the current post
			$revisions = wp_get_post_revisions( $post->ID );

			//if no revisions, early exit
			if( count($revisions) < 1 ){
				_e('No history available for this article', 'wikipress');
				return;
			}

			// the current revision
			$revision = current( $revisions );

			// the revision id
			$revision_id = absint( $revision->ID );

			// from
			$from = is_numeric( $from ) ? absint( $from ) : null;
			if ( ! $revision_id ) {
				$revision_id = absint( $to );
			}

			// enqueue and localize scripts
			wp_enqueue_script( 'revisions' );
			wp_localize_script( 'revisions', '_wpRevisionsSettings', wp_prepare_revisions_for_js( $post, $revision_id, $from ) );

			$post_edit_link = get_edit_post_link();
			$post_title     = '<a href="' . $post_edit_link . '">' . _draft_or_post_title() . '</a>';
			$h1             = sprintf( __( 'Compare Revisions of &#8220;%s&#8221;', 'wikipress' ), $post_title );
			$return_to_post = '<a href="' . $post_edit_link . '">' . __( '&larr; Edit this post', 'wikipress' ) . '</a>';
			$title          = __( 'Revisions', 'wikipress' );

			?>
			<div id="wpbody-content">
				<div class="wrap">
					<h1 class="long-header"><?php echo wp_kses_post( $h1 ); ?></h1>
					<?php echo wp_kses_post( $return_to_post ); ?>
				</div>
			</div>


			<?php

			// print the wp revision templates
			wp_print_revision_templates();
		}

		/**
		 * Enqueue required scripts
		 */
		function ht_wiki_enqueue_revisions_scripts() {

			$ht_wiki_revisions_src = (SCRIPT_DEBUG) ? 'js/ht-wiki-revisions-js.js' : 'js/ht-wiki-revisions-js.min.js';
			$ht_wiki_revisions_js_url = get_template_directory_uri() . '/inc/wiki/' . $ht_wiki_revisions_src;
			wp_register_script( 'ht-wiki-revisions-js', $ht_wiki_revisions_js_url, array( 'revisions', 'jquery' ), false, true );
			wp_enqueue_script( 'ht-wiki-revisions-js' );
		}

		/**
		 * Enqueue required styles
		 */
		function ht_wiki_enqueue_revisions_styles() {

			if ( htwiki_get_current_view() == 'history' ) {

				wp_enqueue_style( 'dashicons' );
				wp_enqueue_style( 'common' );
				wp_enqueue_style( 'forms' );
				wp_enqueue_style( 'dashboard' );
				wp_enqueue_style( 'list-tables' );
				wp_enqueue_style( 'edit' );
				wp_enqueue_style( 'revisions' );
				wp_enqueue_style( 'media' );
				wp_enqueue_style( 'admin-menu' );
				wp_enqueue_style( 'admin-bar' );
				wp_enqueue_style( 'themes' );
				wp_enqueue_style( 'about' );
				wp_enqueue_style( 'nav-menus' );
				wp_enqueue_style( 'wp-pointer' );
				wp_enqueue_style( 'widgets' );
				wp_enqueue_style( 'l10n' );
				wp_enqueue_style( 'buttons' );

			}
		}

	} //end class HT_Wiki_Frontend_Revisions
}//end class exists test


// run the plugin
if ( class_exists( 'HT_Wiki_Frontend_Revisions' ) ) {
	$ht_wiki_frontend_revisions_init = new HT_Wiki_Frontend_Revisions();
}
