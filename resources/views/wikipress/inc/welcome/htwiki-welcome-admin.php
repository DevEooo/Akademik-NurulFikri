<?php
/**
 * Welcome setup
 */

//exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


if( !class_exists('HTWiki_Welcome_Setup') ){

	//options stage key
	if(!defined('HTWIKI_OPTION_STAGE_KEY')){
		define('HTWIKI_OPTION_STAGE_KEY', 'htwiki_option_stage');
	}

	if(!defined('HTWIKI_DEMO_CONTENT_VERSION_KEY')){
		define('HTWIKI_DEMO_CONTENT_VERSION_KEY', 'htwiki_demo_content_version');
	}


	class HTWiki_Welcome_Setup {

		private $theme_slug = 'wikipress';
		private $tgmpa_url = 'themes.php?page=tgmpa-install-plugins';
		private $tgmpa_menu_slug = 'tgmpa-install-plugins';

		//Constructor
		function __construct(){

			add_action( 'admin_menu', array( $this, 'htwiki_welcome_setup_menu' ) );
			add_action( 'admin_head', array( $this, 'htwiki_skip_activation' ) );
			add_action( 'admin_head', array( $this, 'htwiki_hide_gf_integration_warning_on_welcome_admin_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'htwiki_welcome_load_scripts_and_styles' ) );

			add_action( 'wp_ajax_htwiki_setup_plugins', array( $this, 'ajax_plugins' ) );
			add_action( 'wp_ajax_htwiki_activate_theme_key', array( $this, 'ajax_activate_theme_key' ) );

			//sample content helpers
			require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'htwiki-sample-content-helpers.php');

		}

		/**
		* Create menu option
		*/
		function htwiki_welcome_setup_menu(){
			add_theme_page(
				__('WikiPress Setup Assistant', 'wikipress'),
				__('WikiPress Setup Assistant', 'wikipress'),
				'manage_options',
				'htwiki-welcome',
				array( $this, 'htwiki_welcome_setup_page' )
			);
		}

		/**
		* Skip activation listener
		*/
		function htwiki_skip_activation(){
			$skip_activation = array_key_exists('skip-activation', $_POST) ? true : false;
			if(false!=$skip_activation){
				//skip activation prompt for 30 seconds
				set_transient('skip_htwiki_theme_activation', true, 30);
			}				
		}

		/**
		* Hide gravity forms integration warning
		*/
		function htwiki_hide_gf_integration_warning_on_welcome_admin_page(){
			$screen = function_exists('get_current_screen') ? get_current_screen() : false;

			//hide gravity forms integration warning on this page
			if( is_a( $screen, 'WP_Screen' ) && 'appearance_page_htwiki-welcome' ===  $screen->base ) {
				add_filter('kb_suggest_hide_gravity_forms_activation_warning', '__return_true');
			}
		}

		/**
		* Return true if demo content already installed
		*/
		function htwiki_demo_content_already_installed(){
			$htwiki_previously_installed_version = get_option(HTWIKI_DEMO_CONTENT_VERSION_KEY);
			if(empty($htwiki_previously_installed_version)){
				return false;
			} else {
				return true;
			}
			//$current_theme = wp_get_theme();
			//$current_theme_version = $current_theme->get( 'Version' );
			//return version_compare($htwiki_previously_installed_version, $current_theme_version, '=');
		}

		/**
		* Return true if demo content already installed
		*/
		function htwiki_set_demo_content_installed_flag(){
			$current_theme = wp_get_theme();
			$current_theme_version = $current_theme->get( 'Version' );
			update_option(HTWIKI_DEMO_CONTENT_VERSION_KEY, $current_theme_version);
		}

		/**
		* Welcome page callback
		*/
		function htwiki_welcome_setup_page(){

				$status = get_option( $this->theme_slug . '_license_key_status', false );

				$requested_stage = array_key_exists('stage', $_REQUEST) ? intval($_REQUEST['stage']) : 0;


	            $htwiki_previously_installed = $this->htwiki_demo_content_already_installed();

				$skip_activation = array_key_exists('skip-activation', $_REQUEST) ? true : false;
				if($skip_activation){
					//skip activation prompt for 30 seconds
					set_transient('skip_htwiki_theme_activation', true, 30);
				} else {
					$skip_activation = get_transient('skip_htwiki_theme_activation');
					$skip_activation = apply_filters('htwiki_skip_theme_activation', $skip_activation);
				}

				//use security to ensure requested stage is legit
				if( $requested_stage > 1 ){
					check_admin_referer( 'stage-check', 'stage-check' );
				}

				$stage = 1;
				if('valid'==$status || $skip_activation ){
					$stage = 2;
				}

				$skip_plugins = apply_filters('htwiki_skip_plugin_installation',false);
				if(3==$requested_stage || $skip_plugins ){
					$stage = 3;
				}

				if(4==$requested_stage){
					$stage = 4;
				}

				//save the stage
				update_option(HTWIKI_OPTION_STAGE_KEY, $stage);

				?>
				<div class="htwiki-setupwizard">

					<?php
						$this->htwiki_theme_intro_header($stage); ?>
						<?php if( $htwiki_previously_installed && $stage < 4 ) : ?>
							<div class="htwiki-setupwizard__upgradenotice">
								<h2 class="htwiki-setupwizard__title"><?php _e('Upgrading WikiPress?', 'wikipress'); ?></h2>
								<p><?php _e('It looks like you have previously installed WikiPress, if upgrading please complete these steps to ensure the theme license is active and the packaged plugins are up-to-date.', 'wikipress' ); ?></p>
							</div>
						<?php endif; ?>
						<div class="htwiki-setupwizard__content">
						<?php switch ($stage) {
							case 1:
								$this->htwiki_theme_license_tab();
								break;
							case 2:
								$this->htwiki_theme_install_reqd_plugins();
								break;
							case 3:
								$this->htwiki_theme_install_sample_content();
								break;
							case 4:
								$this->htwiki_theme_setup_complete();
								break;						
							default:
								//default action
								break;
						}
					?></div>

				</div><!-- /htwiki-setupwizard -->

				<?php
		}

		/**
		* Progress bar
		*/
		function htwiki_theme_intro_header($current_stage){
			//valid stages
			$stages = array(1,2,3,4);
			?>
			<div class="htwiki-progressbar">
				<ul>
				<?php foreach ($stages as $key => $stage ): ?>
					<?php $active_class = ($stage==$current_stage) ? ' active ' : ''; ?>
					<?php $complete_class = ($stage<$current_stage) ? ' complete ' : ''; ?>
					<?php $justcomplete_class = ($stage == ($current_stage -1 )) ? ' justcomplete ' : ''; ?>
					<li class="<?php echo $complete_class . $justcomplete_class . $active_class; ?>">
						<?php echo $stage; ?>						
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
			<?php
		}

		/**
		* Theme license tab
		*/
		function htwiki_theme_license_tab(){
			global $htwiki_theme_updater;
			$license = trim( get_option( $this->theme_slug . '_license_key' ) );
			$status = get_option( $this->theme_slug . '_license_key_status', false );

			// Checks license status to display under license key
			if ( ! $license ) {
				$message    = __('Please enter license key', 'wikipress');
			} else {
				$message    = __('There was a problem verifying your license, please re-check', 'wikipress');

				if ( ! get_transient( $this->theme_slug . '_license_message', false ) ) {
					set_transient( $this->theme_slug . '_license_message', $htwiki_theme_updater->check_license(), ( 60 * 60 * 24 ) );
				}
				$message = get_transient( $this->theme_slug . '_license_message' );
				
			}
			//recheck the status after validation
			$status = get_option( $this->theme_slug . '_license_key_status', false);

			?>

				<h2 class="htwiki-setupwizard__title"><?php _e('Activate WikiPress', 'wikipress'); ?></h2>
				<p><?php _e('Activate your new theme to unlock automatic updates and enable support for this site.', 'wikipress'); ?></p>

				
				<form method="post" action="options.php">

					<?php settings_fields( $this->theme_slug . '-license' ); ?>

					<div class="htwiki-licenseinput">

						<input id="<?php echo $this->theme_slug; ?>_license_key" name="<?php echo $this->theme_slug; ?>_license_key" type="text" class="htwiki-licenseinput__input" value="<?php echo esc_attr( $license ); ?>" placeholder="********************************" />
						
						<div class="htwiki-license-status">
							<span data-submit-message="<?php esc_html_e('Please wait, validating and activating key...', 'wikipress'); ?>"></span>
						</div>
						

					</div>
					
					
					<p class="htwiki-u-notice"><span><?php _e('Note:', 'wikipress'); ?></span><?php _e('You can find your license key in your Launch and Sell account area or your purchase receipt email.', 'wikipress'); ?></p>


					<?php $button_text = ('valid' == $status) ? __('Continue', 'wikipress') : __('Activate', 'wikipress'); ?>

					<?php $stage_check = wp_create_nonce( 'stage-check' ); ?>

					<p class="htwiki-setupwizard__actions step">
						<a href="?page=htwiki-welcome&stage=2&stage-check=<?php echo $stage_check; ?>"
						   class="button-primary button button-large button-next"
						   data-nonce="<?php echo wp_create_nonce($this->theme_slug . '_nonce'); ?>"
						   data-callback="activateLicense"><?php echo $button_text; ?></a>
						<a href="?page=htwiki-welcome&stage=2&skip-activation=1&stage-check=<?php echo $stage_check; ?>"
						   class="button button-large button-next"><?php esc_html_e( 'Skip this step', 'wikipress' ); ?></a>
						<?php wp_nonce_field( 'htwiki-theme-setup' ); ?>
					</p>
					
				</form>
			<?php
		}

		/**
		* Email alerts signup tab
		* not used in htwiki
		*/
		function htwiki_theme_email_alerts_tab(){
			$current_user = wp_get_current_user();
			?>
				<h2 class="htwiki-setupwizard__title"><?php _e('Sign up for email notifications', 'wikipress'); ?></h2>
                <p><?php printf(__('Visit <a href="%s" target="_blank">Launch and Sell</a>, or signup with the form below for more tips, guides and addons to harness the power of your new Knowledge Base', 'wikipress') , 'https://launchandsell.com'); ?></p>
                <!-- Sign up form -->
                <!-- Begin MailChimp Signup Form -->
                <link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css" >
                <style type="text/css">
                    #mc_embed_signup{clear:left; font:14px Helvetica,Arial,sans-serif; max-width: 600px; }
                    #mc_embed_signup .indicates-required, #mc_embed_signup .mc-field-group .asterisk {display: none;}
                    /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
                       We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
                </style>
                <div id="mc_embed_signup">
                <form action="//herothemes.us10.list-manage.com/subscribe/post?u=958c07d7ba2f4b21594564929&amp;id=db684b9928" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                    <div id="mc_embed_signup_scroll">
                <div class="indicates-required"><span class="asterisk">*</span> <?php _e('indicates required', 'wikipress'); ?></div>
                <div class="mc-field-group">
                    <label for="mce-EMAIL"><?php _e('Email Address', 'wikipress'); ?>  <span class="asterisk">*</span>
                </label>
                    <input type="email" value="<?php echo $current_user->user_email; ?>" name="EMAIL" class="required email" id="mce-EMAIL">
                    <input type="hidden" value="<?php echo $current_user->user_firstname; ?>" name="FNAME" class="" id="mce-FNAME">
                    <input type="hidden" value="<?php echo $current_user->user_lastname; ?>" name="LNAME" class="" id="mce-LNAME">
                    <input type="hidden" id="group_4096" name="group[925][4096]" value="1" /><!-- signup location = HKB Dashboard (group 4096) -->
                    <input type="hidden" name="SIGNUP" id="SIGNUP" value="ht-htwiki-dash" />
                </div>
                    <div id="mce-responses" class="clear">
                        <div class="response" id="mce-error-response" style="display:none"></div>
                        <div class="response" id="mce-success-response" style="display:none"></div>
                    </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                    <div style="position: absolute; left: -5000px;"><input type="text" name="b_958c07d7ba2f4b21594564929_db684b9928" tabindex="-1" value=""></div>
                    <input type="submit" value="<?php _e('Subscribe', 'wikipress'); ?>" name="subscribe" id="mc-embedded-subscribe" class="button">
                    <?php $stage_check = wp_create_nonce( 'stage-check' ); ?>
					<a href="?page=htwiki-welcome&stage=3&stage-check=<?php echo $stage_check; ?>"
					   class="button button-large button-next"><?php esc_html_e( 'Skip this step', 'wikipress' ); ?></a>
					<?php wp_nonce_field( 'htwiki-theme-setup' ); ?>					
                    </div>
                </form>
                </div>
                <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
                <!--End mc_embed_signup-->
                </script>

			<?php
		}

		/**
		* Install required plugins tab
		*/
		function htwiki_theme_install_reqd_plugins(){
			$all_plugins_ready = false;
			?>

				<h2 class="htwiki-setupwizard__title"><?php _e('Install Required Plugins', 'wikipress'); ?></h2>
				<form method="post">

					<?php
					$plugins = $this->get_tgm_config_plugins();
					/*
					Test plugin installation
					$plugins_todo = array(
										array('name'=>'Some magnificent plugin', 'slug'=>'test'), 
										array('name'=>'Probably an average plugin', 'slug'=>'test2'), 
										array('name'=>'Some great plugin', 'slug'=>'test3') 
									);
					$plugins = array('all'=>$plugins_todo, 'install'=>$plugins_todo);
					*/
					if ( count( $plugins['all'] ) ) {
						?>
						<p><?php esc_html_e( 'Your website needs a few essential plugins. The following plugins will be installed or updated:', 'wikipress' ); ?></p>
						<ul class="theme-install-setup-plugins">
							<?php foreach ( $plugins['all'] as $slug => $plugin ) { ?>
								<li data-slug="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $plugin['name'] ); ?>
									<span class="htwiki-plugin-item">
										<?php
										$keys = array();
										if ( isset( $plugins['install'][ $slug ] ) ) {
											$keys[] = esc_html__('Installation', 'wikipress');
										}
										if ( isset( $plugins['update'][ $slug ] ) ) {
											$keys[] = esc_html__('Update', 'wikipress');
										}
										if ( isset( $plugins['activate'][ $slug ] ) ) {
											$keys[] = esc_html__('Activation', 'wikipress');
										}
										echo implode( ' ' . esc_html__('and', 'wikipress') . ' ', $keys ) . ' ' . esc_html__('required', 'wikipress');
										?>
									</span>
									<div class="spinner"></div>
								</li>
							<?php } ?>
						</ul>
						<?php
					} else {
						$all_plugins_ready = true;
						echo '<p><strong>' . esc_html__( 'Good news! All plugins are already installed and up to date. Please continue.', 'wikipress' ) . '</strong></p>';
					} ?>

					<?php $stage_check = wp_create_nonce( 'stage-check' ); ?>



					<p class="htwiki-u-notice"><span><?php _e('Note:', 'wikipress'); ?></span><?php _e('You can add and remove plugins later on from within WordPress.', 'wikipress'); ?></p>

					<p class="htwiki-setupwizard__actions step">
						<a href="?page=htwiki-welcome&stage=3&stage-check=<?php echo $stage_check; ?>"
						   class="button-primary button button-large button-next"
						   data-callback="installPlugins"><?php esc_html_e( 'Continue', 'wikipress' ); ?></a>
						<?php wp_nonce_field( 'htwiki-theme-setup' ); ?>
					</p>
					
				</form>

			<?php
		}

		/**
		* Install sample content tab
		*/
		function htwiki_theme_install_sample_content(){
			?>

				<h2 class="htwiki-setupwizard__title"><?php _e('Install Sample Content', 'wikipress'); ?></h2>

				<?php	
					$content_installed = $this->htwiki_demo_content_already_installed();
		            $checkstate = ($content_installed) ? '' : 'checked';
	            ?>

	            <?php if(!$content_installed): ?>


					<p><?php _e('It\'s time to insert some default content for your new WordPress website. Choose what you would like inserted below and click Install Marked.', 'wikipress'); ?></p>

				<?php else: ?>

					<p><?php _e('It looks like you may have already run this step, you can check the items below to add additional demo data or re-install any missing items.', 'wikipress'); ?></p>

				<?php endif; ?>

				<form method="post" action="">
					<ul class="htwiki-content-items theme-install-setup-content">
						<li class="htwiki-content-item">
							<input type="checkbox" name="htwiki-sample-pages" data-content="pages" <?php echo $checkstate; ?> /><label for="htwiki-sample-pages"><?php _e('Pages', 'wikipress'); ?></label>
							<span class="htwiki-content-item-info-span"></span>
							<div class="spinner"></div>
						</li>
						<li class="htwiki-content-item">
							<input type="checkbox" name="htwiki-sample-articles" data-content="articles" <?php echo $checkstate; ?> /><label for="htwiki-sample-articles"><?php _e('Wiki', 'wikipress'); ?></label>
							<span class="htwiki-content-item-info-span"></span>
							<div class="spinner"></div>
						</li>
						<li class="htwiki-content-item">
							<input type="checkbox" name="htwiki-sample-menus" data-content="menus" <?php echo $checkstate; ?> /><label for="htwiki-sample-menus"><?php _e('Menus', 'wikipress'); ?></label>
							<span class="htwiki-content-item-info-span"></span>
							<div class="spinner"></div>
						</li>
						<li class="htwiki-content-item">
							<input type="checkbox" name="htwiki-sample-sidebar-widgets" data-content="widgets" <?php echo $checkstate; ?> /><label for="htwiki-sample-sidebar-widgets"><?php _e('Sidebars and Widgets', 'wikipress'); ?></label>
							<span class="htwiki-content-item-info-span"></span>
							<div class="spinner"></div>
						</li>
						<li class="htwiki-content-item">
							<input type="checkbox" name="htwiki-sample-settings" data-content="settings" <?php echo $checkstate; ?> /><label for="htwiki-sample-settings"><?php _e('Settings', 'wikipress'); ?></label>
							<span class="htwiki-content-item-info-span"></span>
							<div class="spinner"></div>
						</li>
					</ul><!-- /htwiki-content-items -->

					<?php if(!$content_installed): ?>

						<p class="htwiki-u-notice"><span><?php _e('Note:', 'wikipress'); ?></span><?php _e('It is recommended to leave everything selected. Once inserted, this content can be managed from the WordPress admin dashboard.', 'wikipress'); ?></p>

					<?php else: ?>

						<p class="htwiki-u-notice"><span><?php _e('Note:', 'wikipress'); ?></span><?php _e('The checked items will be installed. You can skip this step if you are upgrading WikiPress.', 'wikipress'); ?></p>

					<?php endif; ?>
				
	            	<input type="hidden" name="stage" value="5" />
	            	<?php $stage_check = wp_create_nonce( 'stage-check' ); ?>

	            	<p class="htwiki-setupwizard__actions step">
						<a href="?page=htwiki-welcome&stage=4&stage-check=<?php echo $stage_check; ?>"
						   class="button-primary button button-large button-next"
						   data-callback="installContent"><?php esc_html_e( 'Install Marked', 'wikipress' ); ?></a>
						<a href="?page=htwiki-welcome&stage=4&stage-check=<?php echo $stage_check; ?>"
						   class="button button-large button-next"><?php esc_html_e( 'Skip this step', 'wikipress' ); ?></a>
						<?php wp_nonce_field( 'htwiki-theme-setup' ); ?>
					</p>
	            </form>

			<?php
		}

		/**
		* Theme setup complete tab
		*/
		function htwiki_theme_setup_complete(){
			//setup complete
			$this->htwiki_set_demo_content_installed_flag();
			?>

				<h2 class="htwiki-setupwizard__title"><?php _e('You\'re All Set!', 'wikipress'); ?></h2>

				<p><?php _e('WikiPress has been successfully setup and your website is ready!', 'wikipress'); ?></p>

				<a class="htwiki-viewsite" href="<?php echo esc_url( home_url( '/' ) ); ?>" target"_blank">
					<?php _e('View Your Site', 'wikipress'); ?>
				</a>

				<div class="htwiki-setup-nextsteps">
					<h2><?php _e('What Next?', 'wikipress'); ?></h2>

					<ul>
						<li><a href="<?php echo admin_url( 'post-new.php?post_type=post' ); ?>" target="_blank"><?php _e('Add or Edit your first Wiki Page', 'wikipress'); ?></a></li>
						<li><a href="<?php echo admin_url( 'customize.php' ); ?>" target="_blank"><?php _e('Customize your site with your logo and colours', 'wikipress'); ?></a></li>
						<li><a href="<?php echo HT_WP_SUPPORT_URL; ?>" target="_blank"><?php _e('Check the WikiPress documentation to get the most from your theme', 'wikipress'); ?></a></li>
					</ul>

				</div>

			<?php
		}

		/**
		* Get TGM config plugins
		*/
		function get_tgm_config_plugins() {
			$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
			$plugins  = array(
				'all'      => array(), // Meaning: all plugins which still have open actions.
				'install'  => array(),
				'update'   => array(),
				'activate' => array(),
			);

			foreach ( $instance->plugins as $slug => $plugin ) {
				if ( $instance->is_plugin_active( $slug ) && false === $instance->does_plugin_have_update( $slug ) ) {
					// No need to display plugins if they are installed, up-to-date and active.
					continue;
				} else {
					$plugins['all'][ $slug ] = $plugin;

					if ( ! $instance->is_plugin_installed( $slug ) ) {
						$plugins['install'][ $slug ] = $plugin;
					} else {
						if ( false !== $instance->does_plugin_have_update( $slug ) ) {
							$plugins['update'][ $slug ] = $plugin;
						}

						if ( $instance->can_plugin_activate( $slug ) ) {
							$plugins['activate'][ $slug ] = $plugin;
						}
					}
				}
			}

			return $plugins;
		}

		/**
		* ajax handler for theme key activation
		*/
		function ajax_activate_theme_key() {
			if ( ! check_ajax_referer( 'htwiki_setup_setup_security', 'wpnonce' ) ) {
				wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'Failed Security', 'wikipress' ) ) );
			}

			$json = array();

			$key = filter_input( INPUT_POST, 'key', FILTER_SANITIZE_STRING );

			$attempt = filter_input( INPUT_POST, 'attempt', FILTER_SANITIZE_NUMBER_INT);

			update_option( $this->theme_slug . '_license_key', $key );

			$status = get_option( $this->theme_slug . '_license_key_status', false );

			$retry = false;

			//response license_valid
			$license_valid = false;

			if('valid'==$status){
				$message = esc_html__('Looks good, sit tight...', 'wikipress');
				$license_valid = true;
			} else {
				//workaround for when the license is valid, but has not activated correctly, retry upto three times
				if($attempt < 4){
					$message = esc_html__('Validating license with Launch and Sell', 'wikipress') . sprintf(' (%s)', $attempt);
					$retry = true;
				} else {
					$message = esc_html__('There is a problem with the license key', 'wikipress');			
				}				
			}

			//send response
			wp_send_json( array( 'done' => 1, 'valid' => $license_valid, 'message' => $message, 'retry' => $retry, 'attempt' => $attempt+1 ) );
			//needed on ajax calls to exit cleanly
			exit;
		}

		/**
		* ajax handler for plugin installation
		*/
		function ajax_plugins() {

			if ( ! check_ajax_referer( 'htwiki_setup_setup_security', 'wpnonce' )  ) {
				wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'Failed Security', 'wikipress' ) ) );
			}

			if ( empty( $_POST['slug'] ) ) {
				wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'Missing plugin slug', 'wikipress' ) ) );
			}

			$json = array();
			// send back some json we use to hit up TGM
			$plugins = $this->get_tgm_config_plugins();
			// what are we doing with this plugin?
			foreach ( $plugins['activate'] as $slug => $plugin ) {
				if ( $_POST['slug'] == $slug ) {
					$json = array(
						'url'           => admin_url( $this->tgmpa_url ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-activate',
						'action2'       => - 1,
						'message'       => esc_html__( 'Activating Plugin', 'wikipress' ),
					);
					break;
				}
			}
			foreach ( $plugins['update'] as $slug => $plugin ) {
				if ( $_POST['slug'] == $slug ) {
					$json = array(
						'url'           => admin_url( $this->tgmpa_url ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-update',
						'action2'       => - 1,
						'message'       => esc_html__( 'Updating Plugin', 'wikipress' ),
					);
					break;
				}
			}
			foreach ( $plugins['install'] as $slug => $plugin ) {
				if ( $_POST['slug'] == $slug ) {
					$json = array(
						'url'           => admin_url( $this->tgmpa_url ),
						'plugin'        => array( $slug ),
						'tgmpa-page'    => $this->tgmpa_menu_slug,
						'plugin_status' => 'all',
						'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
						'action'        => 'tgmpa-bulk-install',
						'action2'       => - 1,
						'message'       => esc_html__( 'Installing Plugin', 'wikipress' ),
					);
					break;
				}
			}

			if ( !empty($json) ) {
				$json['hash'] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin
				wp_send_json( $json );
			} else {
				wp_send_json( array( 'done' => 1, 'message' => esc_html__( 'Success', 'wikipress' ) ) );
			}
			exit;

		}

		/**
		* Load scripts and styles
		*/
		function htwiki_welcome_load_scripts_and_styles(){
			$screen = get_current_screen();
			if(is_a($screen, 'WP_Screen') && 'appearance_page_htwiki-welcome' == $screen->base ){
				//enqueue style
				wp_enqueue_style( 'theme-setup-css', get_template_directory_uri() . '/inc/welcome/css/theme-setup-admin-style.css' );

				//enqueue script
				wp_enqueue_script( 'jquery-blockui', get_template_directory_uri(). '/inc/welcome/js/jquery.blockUI.js', array( 'jquery' ), '2.70', true );
			
				$theme_welcome_js_file_src = (SCRIPT_DEBUG) ?  get_template_directory_uri() . '/inc/welcome/js/theme-setup-js.js' :  get_template_directory_uri() . '/inc/welcome/js/theme-setup-js.min.js';
				wp_enqueue_script( 'theme-setup-js', $theme_welcome_js_file_src, array(
					'jquery',
					'jquery-blockui',
				), '1.0' );
				wp_localize_script( 'theme-setup-js', 'htwiki_theme_setup', array(
					'tgm_plugin_nonce' => array(
						'update'  => wp_create_nonce( 'tgmpa-update' ),
						'install' => wp_create_nonce( 'tgmpa-install' ),
					),
					'theme_slug'	   => $this->theme_slug,
					'tgm_bulk_url'     => admin_url( $this->tgmpa_url ),
					'ajaxurl'          => admin_url( 'admin-ajax.php' ),
					'wpnonce'          => wp_create_nonce( 'htwiki_setup_setup_security' ),
					'verify_text'      => esc_html__( '...verifying', 'wikipress' ),
				) );

			}
			
		}

	}

}

if( class_exists('HTWiki_Welcome_Setup') ){
	$htwiki_welcome_setup_init = new HTWiki_Welcome_Setup();
}