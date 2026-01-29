<?php
/**
 *   HT Wiki Front End Revisions Functionality
 */

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; }

if ( ! class_exists( 'HT_Wiki_Frontend_Gutenberg' ) ) {

	class HT_Wiki_Frontend_Gutenberg {

		// Constructor
		function __construct() {
			add_action( 'init', array( $this, 'ht_wiki_init' ), 10 );
			add_action( 'get_header', array( $this, 'ht_wiki_remove_gutenberg' ), 10 );
			add_action( 'wp_enqueue_scripts', array( $this, 'ht_wiki_enqueue_gutenberg_scripts_and_styles' ), 15 );
			add_filter( 'user_has_cap', array( $this, 'ht_wiki_give_frontend_gutenberg_permissions' ), 10, 3 );
			add_action( 'wp_ajax_nopriv_query-attachments', array( $this, 'ht_wiki_wp_ajax_nopriv_query_attachments' ) );

			//dont allow logged out users to save content			
			add_filter( 'wp_insert_post_empty_content', array( $this, 'ht_wiki_insert_empty_content' ), PHP_INT_MAX -1, 2 );

			//block support
			add_filter( 'allowed_block_types_all', array( $this, 'ht_wiki_allowed_block_types' ), 10, 2 );

			//get edit post link
			add_filter( 'get_edit_post_link', array( $this, 'ht_wiki_get_edit_post_link' ), 10, 3 );

			//template redirect functionality
			//add_filter( 'template_redirect', array( $this, 'ht_wiki_template_redirect') );


		}

		/**
		 * Init
		 * scripts and styles
		 */
		function ht_wiki_init() {
			//don't run in admin
			if(is_admin()){
				return;
			}
			if ( function_exists('gutenberg_editor_scripts_and_styles') ) {
				add_action( 'wp_enqueue_scripts', 'gutenberg_editor_scripts_and_styles' );
			} else {
				do_action( 'enqueue_block_editor_assets' );
				add_action( 'template_redirect', array($this, 'ht_wiki_load_wp5_editor' ) );
			}
		}

		/**
		* Load the WP5 editor
		*/
		function ht_wiki_load_wp5_editor() {
			global $post;

			if(!$post){
				return;
			}
			//do not skip post
			//the_post();
			$block_editor_context = new WP_Block_Editor_Context( array( 'post' => $post ) );

			wp_add_inline_script( 'revisions', 'window.adminpage=false',  'after' );

			$align_wide    = get_theme_support( 'align-wide' );
			$color_palette = current( (array) get_theme_support( 'editor-color-palette' ) );
			$font_sizes    = current( (array) get_theme_support( 'editor-font-sizes' ) );
			$available_templates = wp_get_theme()->get_page_templates( get_post( $post->ID ) );
			$available_templates = ! empty( $available_templates ) ? array_replace(
				array(
					/** This filter is documented in wp-admin/includes/meta-boxes.php */
					'' => apply_filters( 'default_page_template_title', __( 'Default template', 'wikipress' ), 'rest-api' ),
				),
				$available_templates
			) : $available_templates;
			$allowed_block_types = apply_filters( 'allowed_block_types_all', true, $post );
			$body_placeholder = apply_filters( 'write_your_story', __( 'Start writing or type / to choose a block', 'wikipress' ), $post );

			$max_upload_size = wp_max_upload_size();
			if ( ! $max_upload_size ) {
				$max_upload_size = 0;
			}

			$styles = array(
				array(
					'css' => file_get_contents(
						ABSPATH . WPINC . '/css/dist/editor/style.css'
					),
				),
			);


			$image_size_names = apply_filters(
				'image_size_names_choose',
				array(
					'thumbnail' => __( 'Thumbnail', 'wikipress' ),
					'medium'    => __( 'Medium', 'wikipress' ),
					'large'     => __( 'Large', 'wikipress' ),
					'full'      => __( 'Full Size', 'wikipress' ),
				)
			);
			$available_image_sizes = array();
			foreach ( $image_size_names as $image_size_slug => $image_size_name ) {
				$available_image_sizes[] = array(
					'slug' => $image_size_slug,
					'name' => $image_size_name,
				);
			}

			$lock_details = array(
				'isLocked' => false,
				'user'     => 0,
			);

			$editor_settings = array(
				'alignWide'              => $align_wide,
				'availableTemplates'     => $available_templates,
				'allowedBlockTypes'      => $allowed_block_types,
				'disableCustomColors'    => get_theme_support( 'disable-custom-colors' ),
				'disableCustomFontSizes' => get_theme_support( 'disable-custom-font-sizes' ),
				'disablePostFormats'     => ! current_theme_supports( 'post-formats' ),
				/** This filter is documented in wp-admin/edit-form-advanced.php */
				'titlePlaceholder'       => apply_filters( 'enter_title_here', __( 'Add title', 'wikipress' ), $post ),
				'bodyPlaceholder'        => $body_placeholder,
				'isRTL'                  => is_rtl(),
				'autosaveInterval'       => 10,
				'maxUploadFileSize'      => $max_upload_size,
				'allowedMimeTypes'       => get_allowed_mime_types(),
				'styles'                 => $styles,
				'imageSizes'             => $available_image_sizes,
				'richEditingEnabled'     => user_can_richedit(),
				'postLock'               => $lock_details,
				'postLockUtils'          => array(
					'nonce'       => wp_create_nonce( 'lock-post_' . $post->ID ),
					'unlockNonce' => wp_create_nonce( 'update-post_' . $post->ID ),
					'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
				),

				'supportsLayout'                       => wp_theme_has_theme_json(),
	'__experimentalBlockPatterns'          => WP_Block_Patterns_Registry::get_instance()->get_all_registered(),
	'__experimentalBlockPatternCategories' => WP_Block_Pattern_Categories_Registry::get_instance()->get_all_registered(),
	'supportsTemplateMode'                 => current_theme_supports( 'block-templates' ),

				// Whether or not to load the 'postcustom' meta box is stored as a user meta
				// field so that we're not always loading its assets.
				'enableCustomFields'     => (bool) get_user_meta( get_current_user_id(), 'enable_custom_fields', true ),
			);

			// Gutenberg isn't active, fall back to WP 5+ internal block editor
			wp_add_inline_script(
				'wp-blocks',
				sprintf( 'wp.blocks.setCategories( %s );', wp_json_encode( $this->ht_wiki_get_block_categories( $post ) ) ),
				'after'
			);

			/*
			 * Assign initial edits, if applicable. These are not initially assigned to the persisted post,
			 * but should be included in its save payload.
			 */
			$initial_edits = null;
			$is_new_post   = false;
			// Preload server-registered block schemas.
			wp_add_inline_script(
				'wp-blocks',
				'wp.blocks.unstable__bootstrapServerSideBlockDefinitions(' . wp_json_encode( $this->ht_wiki_get_block_editor_server_block_settings() ) . ');'
			);
			// Get admin url for handling meta boxes.
			$meta_box_url = admin_url( 'post.php' );
			$meta_box_url = add_query_arg(
				array(
					'post'            => $post->ID,
					'action'          => 'edit',
					'meta-box-loader' => true,
					'_wpnonce'        => wp_create_nonce( 'meta-box-loader' ),
				),
				$meta_box_url
			);
			wp_localize_script( 'wp-editor', '_wpMetaBoxUrl', array( $meta_box_url ) );

			// Populate default code editor settings by short-circuiting wp_enqueue_code_editor.
			wp_add_inline_script(
				'wp-editor',
				sprintf(
					'window._wpGutenbergCodeEditorSettings = %s;',
					wp_json_encode( wp_get_code_editor_settings( array( 'type' => 'text/html' ) ) )
				)
			);
			$align_wide    = get_theme_support( 'align-wide' );
			$color_palette = current( (array) get_theme_support( 'editor-color-palette' ) );
			$font_sizes    = current( (array) get_theme_support( 'editor-font-sizes' ) );

			/**
			 * Filters the allowed block types for the editor, defaulting to true (all
			 * block types supported).
			 *
			 * @since 5.0.0
			 *
			 * @param bool|array $allowed_block_types Array of block type slugs, or
			 *                                        boolean to enable/disable all.
			 * @param object $post                    The post resource data.
			 */
			$allowed_block_types = apply_filters( 'allowed_block_types_all', true, $post );
			// Get all available templates for the post/page attributes meta-box.
			// The "Default template" array element should only be added if the array is
			// not empty so we do not trigger the template select element without any options
			// besides the default value.
			$available_templates = wp_get_theme()->get_page_templates( $post );
			$available_templates = ! empty( $available_templates ) ? array_merge(
				array(
					/** This filter is documented in wp-admin/includes/meta-boxes.php */
					'' => apply_filters( 'default_page_template_title', __( 'Default template', 'wikipress' ), 'rest-api' ),
				),
				$available_templates
			) : $available_templates;
			// Media settings.
			$max_upload_size = wp_max_upload_size();
			if ( ! $max_upload_size ) {
				$max_upload_size = 0;
			}
			// Editor Styles.
			$styles = array(
				array(
					'css' => file_get_contents(
						ABSPATH . WPINC . '/css/dist/editor/style.css'
					),
				),
			);

			/**
			 * Filters the allowed block types for the editor, defaulting to true (all
			 * block types supported).
			 *
			 * @since 5.0.0
			 *
			 * @param bool|array $allowed_block_types Array of block type slugs, or
			 *                                        boolean to enable/disable all.
			 * @param object $post                    The post resource data.
			 */
			$allowed_block_types = apply_filters( 'allowed_block_types_all', true, $post );
			// Get all available templates for the post/page attributes meta-box.
			// The "Default template" array element should only be added if the array is
			// not empty so we do not trigger the template select element without any options
			// besides the default value.
			$available_templates = wp_get_theme()->get_page_templates( $post );
			$available_templates = ! empty( $available_templates ) ? array_merge(
				array(
					/** This filter is documented in wp-admin/includes/meta-boxes.php */
					'' => apply_filters( 'default_page_template_title', __( 'Default template', 'wikipress' ), 'rest-api' ),
				),
				$available_templates
			) : $available_templates;
			// Media settings.
			$max_upload_size = wp_max_upload_size();
			if ( ! $max_upload_size ) {
				$max_upload_size = 0;
			}
			// Editor Styles.
			$styles = array(
				array(
					'css' => file_get_contents(
						ABSPATH . WPINC . '/css/dist/editor/style.css'
					),
				),
			);

			if ( false !== $color_palette ) {
				$editor_settings['colors'] = $color_palette;
			}
			if ( ! empty( $font_sizes ) ) {
				$editor_settings['fontSizes'] = $font_sizes;
			}
			if ( ! empty( $post_type_object->template ) ) {
				$editor_settings['template']     = $post_type_object->template;
				$editor_settings['templateLock'] = ! empty( $post_type_object->template_lock ) ? $post_type_object->template_lock : false;
			}
			// If there's no template set on a new post, use the post format, instead.
			if ( $is_new_post && ! isset( $editor_settings['template'] ) && 'post' === $post->post_type ) {
				$post_format = get_post_format( $post );
				if ( in_array( $post_format, array( 'audio', 'gallery', 'image', 'quote', 'video' ), true ) ) {
					$editor_settings['template'] = array( array( "core/$post_format" ) );
				}
			}
			$editor_settings = get_block_editor_settings( $editor_settings, $block_editor_context );
			/** the following JS must not be indented */
			$init_script = <<<JS
( function() {
	window._wpLoadBlockEditor = new Promise( function( resolve ) {
		wp.domReady( function() {
			resolve( wp.editPost.initializeEditor( 'editor', "%s", %d, %s, %s ) );
		} );
	} );
} )();
JS;

			$script = sprintf(
				$init_script,
				$post->post_type,
				$post->ID,
				wp_json_encode( $editor_settings ),
				wp_json_encode( $initial_edits )
			);


			wp_add_inline_script( 'wp-edit-post', $script );
		 

			/**
			 * Scripts
			 */
			wp_enqueue_media(
				array(
					'post' => $post->ID,
				)
			);

			//enqueue the tinymce backwards compatibility scripts
			wp_tinymce_inline_scripts();

			//enqueue the editor
			wp_enqueue_editor();
		}


		function ht_wiki_get_block_categories( $post ) {
			$default_categories = array(
				array(
					'slug'  => 'common',
					'title' => __( 'Common Blocks', 'wikipress' ),
					'icon'  => 'screenoptions',
				),
				array(
					'slug'  => 'formatting',
					'title' => __( 'Formatting', 'wikipress' ),
					'icon'  => null,
				),
				array(
					'slug'  => 'layout',
					'title' => __( 'Layout Elements', 'wikipress' ),
					'icon'  => null,
				),
				array(
					'slug'  => 'widgets',
					'title' => __( 'Widgets', 'wikipress' ),
					'icon'  => null,
				),
				array(
					'slug'  => 'embed',
					'title' => __( 'Embeds', 'wikipress' ),
					'icon'  => null,
				),
				array(
					'slug'  => 'reusable',
					'title' => __( 'Reusable Blocks', 'wikipress' ),
					'icon'  => null,
				),
			);
			/**
			 * Filter the default array of block categories.
			 *
			 * @since 5.0.0
			 *
			 * @param array   $default_categories Array of block categories.
			 * @param WP_Post $post               Post being loaded.
			 */
			return apply_filters( 'block_categories', $default_categories, $post );
		}


		function ht_wiki_get_block_editor_server_block_settings() {
		    $block_registry = WP_Block_Type_Registry::get_instance();
		    $blocks         = array();
		    $keys_to_pick   = array( 'title', 'description', 'icon', 'category', 'keywords', 'supports', 'attributes' );
		 
		    foreach ( $block_registry->get_all_registered() as $block_name => $block_type ) {
		        foreach ( $keys_to_pick as $key ) {
		            if ( ! isset( $block_type->{ $key } ) ) {
		                continue;
		            }
		 
		            if ( ! isset( $blocks[ $block_name ] ) ) {
		                $blocks[ $block_name ] = array();
		            }
		 
		            $blocks[ $block_name ][ $key ] = $block_type->{ $key };
		        }
		    }
		 
		    return $blocks;
		}

		/**
		 * Remove gutenberg if 404 (otherwise will result in warnings/error)
		 */
		function ht_wiki_remove_gutenberg(){
			if(is_404()){
				remove_action( 'wp_enqueue_scripts', 'gutenberg_editor_scripts_and_styles' );
			}			
		}

		/**
		 * Enqueue required scripts and styles
		 */
		function ht_wiki_enqueue_gutenberg_scripts_and_styles() {
			//don't run in admin
			if(is_admin()){
				return;
			}
			/*
			wp_enqueue_script( 'postbox', admin_url( 'js/postbox.min.js' ), array( 'jquery-ui-sortable' ), false, 1 );
			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style( 'common' );
			wp_enqueue_style( 'forms' );
			wp_enqueue_style( 'dashboard' );
			wp_enqueue_style( 'media' );
			wp_enqueue_style( 'admin-menu' );
			wp_enqueue_style( 'admin-bar' );
			wp_enqueue_style( 'nav-menus' );
			wp_enqueue_style( 'l10n' );
			wp_enqueue_style( 'buttons' );
			*/

			if ( htwiki_get_current_view() == 'edit' ) {

				wp_enqueue_script( 'postbox', admin_url('js/postbox.min.js'),array( 'jquery-ui-sortable' ),false, 1 );
				wp_enqueue_style( 'dashicons' );
				wp_enqueue_style( 'common' );
				wp_enqueue_style( 'forms' );
				wp_enqueue_style( 'dashboard' );
				wp_enqueue_style( 'media' );
				wp_enqueue_style( 'admin-menu' );
				wp_enqueue_style( 'admin-bar' );
				wp_enqueue_style( 'nav-menus' );
				wp_enqueue_style( 'l10n' );
				wp_enqueue_style( 'buttons' );
				wp_enqueue_style( 'ht_wiki', get_template_directory_uri() . '/style.css' );

				
				if ( ! function_exists('gutenberg_editor_scripts_and_styles') ) {
					wp_enqueue_script( 'heartbeat' );
					wp_enqueue_script( 'wp-edit-post' );
					wp_enqueue_script( 'wp-format-library' );
					wp_enqueue_style( 'wp-edit-post' );
					wp_enqueue_style( 'wp-format-library' );
				}

				//custom scripts
				$ht_frontend_editor_src = (SCRIPT_DEBUG) ? 'js/ht-wiki-frontend-editor.js' : 'js/ht-wiki-frontend-editor.min.js';
				$ht_frontend_editor_js_url = get_template_directory_uri() . '/inc/wiki/' . $ht_frontend_editor_src;
				wp_enqueue_script( 'ht-wiki-frontend-editor', $ht_frontend_editor_js_url, array('jquery', 'wp-editor'), false );

			}
		}

		/**
		 * Assign required permissions for frontend editing
		 */
		function ht_wiki_give_frontend_gutenberg_permissions( $allcaps, $cap, $args ) {
			if ( is_user_logged_in() ) {
				return $allcaps;
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

			//publish ht-wiki
			$allcaps['publish_pages'] = true;
			$allcaps['publish_posts'] = true;

			$allcaps['switch_themes'] = false;
			$allcaps['edit_themes'] = false;
			$allcaps['activate_plugins'] = false;
			$allcaps['edit_plugins'] = false;
			$allcaps['edit_users'] = false;
			$allcaps['import'] = false;
			$allcaps['unfiltered_html'] = false;
			$allcaps['edit_plugins'] = false;
			$allcaps['unfiltered_upload'] = false;

			//media upload
			if( apply_filters( 'htwiki_allow_anon_media_upload', false ) ){
				$allcaps['upload_files'] = true;
			}

			return $allcaps;
		}

		/**
		 * Assign required permissions for frontend editing
		 */
		function ht_wiki_wp_ajax_nopriv_query_attachments() {
			// @codingStandardsIgnoreStart
			// phpcs:ignore
			$query = isset( $_REQUEST['query'] ) ? (array) filter_input( INPUT_POST, 'query' ) : array();
			// @codingStandardsIgnoreEnd
			$keys = array(
				's',
				'order',
				'orderby',
				'posts_per_page',
				'paged',
				'post_mime_type',
				'post_parent',
				'post__in',
				'post__not_in',
				'year',
				'monthnum',
			);
			foreach ( get_taxonomies_for_attachments( 'objects' ) as $t ) {
				if ( $t->query_var && isset( $query[ $t->query_var ] ) ) {
					$keys[] = $t->query_var;
				}
			}

			$query = array_intersect_key( $query, array_flip( $keys ) );
			$query['post_type'] = 'attachment';
			if ( MEDIA_TRASH
				&& ! empty( $_REQUEST['query']['post_status'] )
				&& 'trash' === $_REQUEST['query']['post_status'] ) {
				$query['post_status'] = 'trash';
			} else {
				$query['post_status'] = 'inherit';
			}

			// filter query clauses to include filenames.
			if ( isset( $query['s'] ) ) {
				add_filter( 'posts_clauses', '_filter_query_attachment_filenames' );
			}

			// filters the arguments passed to WP_Query during an Ajax
			$query = apply_filters( 'ajax_query_attachments_args', $query );
			$query = new WP_Query( $query );

			$posts = array_map( 'wp_prepare_attachment_for_js', $query->posts );
			$posts = array_filter( $posts );

			wp_send_json_success( $posts );
		}


		/**
		* Insert post empty content filter
		*/
		function ht_wiki_insert_empty_content($maybe_empty, $postarr){
			//if the user is logged out and anon editing is disabled, stop post update from happening		

			if ( ! is_user_logged_in() && ! apply_filters( 'htwiki_allow_anon_editing', false )  ) {
    			return true;
			}

			return $maybe_empty;
		}

		/**
		* Allowed block types
		* currently all passed
		*/
		function ht_wiki_allowed_block_types( $allowed_block_types, $post ) {
			return $allowed_block_types;
		}

		/**
		* Filter get_edit_post_link
		*/
		function ht_wiki_get_edit_post_link($link, $post_ID, $context){
			if(!is_admin()){
				$link = get_permalink($post_ID) . '?view=edit';
			}
			return $link;
		}

		/**
		* Template redirect action hook
		* @deprecated
		*/
		function ht_wiki_template_redirect(){
			
		}


		/**
		* Stop post from reverting to draft?
		* @asof ht-wiki latest this appears to no longer be an issue
		*/

	} //end class HT_Wiki_Frontend_Gutenberg
}//end class exists test


// run the plugin
if ( class_exists( 'HT_Wiki_Frontend_Gutenberg' ) ) {
	$ht_wiki_frontend_gutenberg_init = new HT_Wiki_Frontend_Gutenberg();
}