<?php
/**
 * Sample content static helpers
 */

//exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


if( ! class_exists('HTWiki_Sample_Content_Helpers')){

	
	if(!defined('HTWIKI_WIKI_POST_TYPE')){
		define('HTWIKI_WIKI_POST_TYPE', 'post');
	}

	if(!defined('HTWIKI_WIKI_CATEGORY_TAXONOMY')){
		define('HTWIKI_WIKI_CATEGORY_TAXONOMY', 'category');
	}

	if(!defined('HTWIKI_WIKI_LOGIN_PAGE_POST_ID_KEY')){
		define('HTWIKI_WIKI_LOGIN_PAGE_POST_ID_KEY', 'htwiki_wiki_login_post_id');
	}

	class HTWiki_Sample_Content_Helpers {

		//Constructor
		function __construct(){

			add_action( 'wp_ajax_htwiki_setup_content', array( $this, 'ajax_content' ) );

			add_action( 'admin_head', array( $this, 'admin_head' ) );

			add_action( 'after_switch_theme', array( $this, 'after_switch_theme' ) );

		}

		/**
		* Perform sample install on transient state
		*/
		function after_switch_theme(){
			$install_demo_content = get_transient( 'htwiki_install_demo_content' );
			$install_demo_content = apply_filters('htwiki_sample_content_install_demo_content', $install_demo_content);
			//required structure due to get_transient returning literal false if not set
			if( $install_demo_content === false ){
				//do nothing
				return;
			} else {
				//install all demo content
				if(apply_filters('htwiki_sample_content_install_demo_pages', true)){
					$this->htwiki_install_demo_pages();	
				}
				if(apply_filters('htwiki_sample_content_install_demo_wiki', true)){
					$this->htwiki_install_demo_wiki();	
				}
				if(apply_filters('htwiki_sample_content_install_demo_menus', true)){
					$this->htwiki_install_demo_menus();	
				}
				if(apply_filters('htwiki_sample_content_install_demo_widgets', true)){
					$this->htwiki_install_demo_widgets();	
				}
				if(apply_filters('htwiki_sample_content_install_demo_settings', true)){
					$this->htwiki_install_demo_settings();	
				}

				//remove the install demo content transient
				delete_transient( 'htwiki_install_demo_content' );
			}
		}

		/**
		* Admin header action calls
		* @canberemoved
		*/
		function admin_head(){
			$test_action = filter_input( INPUT_GET, 'test-install', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
			if($test_action){
				//perform any debug actions here
				//$this->htwiki_install_demo_settings();
			}
		}

		/**
		* ajax handler for htwiki_setup_content action
		*/
		function ajax_content(){
			if ( ! check_ajax_referer( 'htwiki_setup_setup_security', 'wpnonce' ) ) {
				wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'Failed Security', 'wikipress' ) ) );
			}

			$json = array();

			$add_action = filter_input( INPUT_POST, 'add', FILTER_SANITIZE_STRING );

			if($add_action){

				switch ($add_action) {
					case 'pages':
						//pages
						$this->htwiki_install_demo_pages();
						$json = array( 'message' => esc_html__('Pages Installed', 'wikipress'), 'done' => 1 );
						break;
					case 'articles':
						//articles
						$this->htwiki_install_demo_wiki();
						$json = array( 'message' => esc_html__('Wiki Installed', 'wikipress'), 'done' => 1 );
						break;
					case 'menus':
						//menus
						$this->htwiki_install_demo_menus();
						$json = array( 'message' => esc_html__('Menus Installed', 'wikipress'), 'done' => 1 );
						break;
					case 'widgets':
						//widgets
						$this->htwiki_install_demo_widgets();
						$json = array( 'message' => esc_html__('Widgets Installed', 'wikipress'), 'done' => 1 );
						break;
					case 'settings':
						//settings
						$this->htwiki_install_demo_settings();
						$json = array( 'message' => esc_html__('Settings Activated', 'wikipress'), 'done' => 1 );
						break;
					default:
						//break
						break;
				}   

			}

			if ( !empty($json) ) {
				$json['hash'] = md5( serialize( $json ) ); //check if duplicates happen, move to next item
				wp_send_json( $json );
			} else {
				wp_send_json( array( 'done' => 1, 'message' => esc_html__( 'Success', 'wikipress' ) ) );
			}
			exit;  
		}

		/**
		* Install demo pages
		*/
		function htwiki_install_demo_pages(){

			//Welcome
			$page = array( 'title'=> esc_html__( 'Welcome to WikiPress', 'wikipress'), 'category'=> '' );
			$content = $this->get_sample_file_content('sample-welcome-to-wikipress');
			$page_id = $this->add_sample_post('page', $page['title'], $content);
			
			//What is a Wiki?
			$page = array( 'title'=> esc_html__( 'What is a Wiki?', 'wikipress'), 'category'=> '' );
			$content = $this->get_sample_file_content('sample-what-is-a-wiki');
			$page_id = $this->add_sample_post('page', $page['title'], $content);

			//How can WikiPress help you?
			$page = array( 'title'=> esc_html__( 'How can WikiPress help you?', 'wikipress'), 'category'=> '' );
			$content = $this->get_sample_file_content('sample-how-wikipress-can-help');
			$page_id = $this->add_sample_post('page', $page['title'], $content);

			//Login Page
			$page = array( 'title'=> esc_html__( 'Login', 'wikipress'), 'category'=> '' );
			$content = __( 'This is your login page, controlled by the login template. Do not edit this page directly', 'wikipress' );
			$page_id = $this->add_sample_post('page', $page['title'], $content);
			update_post_meta( $page_id, '_wp_page_template', 'template-login.php' );
			update_option(HTWIKI_WIKI_LOGIN_PAGE_POST_ID_KEY, $page_id);

		}

		/**
		* Install demo wiki
		*/
		function htwiki_install_demo_wiki($template = 'default'){

			$this->add_sample_wiki_categories($template);
			
			$sample_wiki_pages = array();
			switch ($template) {
				case 'company':
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Your First Day at the Company', 'wikipress'), 'category'=> 'Welcome Aboard' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Getting to Know the Team', 'wikipress'), 'category'=> 'Welcome Aboard' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Where to Find Your Work', 'wikipress'), 'category'=> 'Welcome Aboard' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Employee Benefits & Perks', 'wikipress'), 'category'=> 'Welcome Aboard' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'How the Buddy System Works', 'wikipress'), 'category'=> 'Welcome Aboard' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'How to Get Help', 'wikipress'), 'category'=> 'Welcome Aboard' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Work-Life Balance', 'wikipress'), 'category'=> 'Welcome Aboard' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Our Company&quot;s Mission', 'wikipress'), 'category'=> 'Welcome Aboard' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Tips for Your First Week', 'wikipress'), 'category'=> 'Welcome Aboard' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Training and Development', 'wikipress'), 'category'=> 'Welcome Aboard' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'A Brief Company History', 'wikipress'), 'category'=> 'Welcome Aboard' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Emergency Evacuation Plan', 'wikipress'), 'category'=> 'Health & Safety' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Safety is No Accident', 'wikipress'), 'category'=> 'Health & Safety' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Tips for Safe Working', 'wikipress'), 'category'=> 'Health & Safety' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Emergency Contacts', 'wikipress'), 'category'=> 'Health & Safety' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'What Do in the Event of a Fire', 'wikipress'), 'category'=> 'Health & Safety' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Company Disaster Recovery Plan', 'wikipress'), 'category'=> 'Health & Safety' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Earthquake Awareness', 'wikipress'), 'category'=> 'Health & Safety' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Safe Lifting and Moving', 'wikipress'), 'category'=> 'Health & Safety' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Employee Hygiene Guidelines', 'wikipress'), 'category'=> 'Health & Safety' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Night Time Working', 'wikipress'), 'category'=> 'Health & Safety' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Anti-Slavery Policy', 'wikipress'), 'category'=> 'Policies & Procedures' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Legal Compliance Framework', 'wikipress'), 'category'=> 'Policies & Procedures' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Code of Conduct', 'wikipress'), 'category'=> 'Policies & Procedures' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Fair Usage Policy', 'wikipress'), 'category'=> 'Policies & Procedures' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Dealing with Press Enquiries', 'wikipress'), 'category'=> 'Policies & Procedures' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Privacy Policy', 'wikipress'), 'category'=> 'Policies & Procedures' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Employee Grievance Procedure', 'wikipress'), 'category'=> 'Policies & Procedures' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Data Security and Confidentiality', 'wikipress'), 'category'=> 'Policies & Procedures' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Data Exchange Protocols', 'wikipress'), 'category'=> 'Policies & Procedures' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Company Dress Code', 'wikipress'), 'category'=> 'Policies & Procedures' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Logging into the Network', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Remote Access & VPN', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Using the VoIP telephones', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Redirecting Calls', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Backup & Recovery', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Data Encryption & Security', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'How To Reset Your Password', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Laptop Security', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Mobile App Guide', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Phone Directory', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Expense Claims', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Completing the Timecard', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'VAT User Guide', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Purchase Orders', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Invoicing', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Reporting and Retention Requirements', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Travel and Entertainment', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Sign Off and Accountability', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Corporate Credit Cards', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Petty Cash', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Overtime Payments', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Setting up Payroll', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Career Advancement', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'CPE Requirements', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Corporate Social Responsibility', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Employee Appraisals', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Company Structure', 'wikipress'), 'category'=> 'Appendix' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Patent Submissions', 'wikipress'), 'category'=> 'Appendix' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Glossary of Terms', 'wikipress'), 'category'=> 'Appendix' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Website Disclaimers', 'wikipress'), 'category'=> 'Appendix' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Legal Notices', 'wikipress'), 'category'=> 'Appendix' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Office Locations & Hours', 'wikipress'), 'category'=> 'Appendix' );
					break;	
				case 'general':
					//populate pages
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'About WikiPress', 'wikipress'), 'category'=> 'about' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Our Company Structure', 'wikipress'), 'category'=> 'about' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Corperate Responsibility', 'wikipress'), 'category'=> 'about' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Contact Details', 'wikipress'), 'category'=> 'about' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Getting Started with Contributing', 'wikipress'), 'category'=> 'contributing' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Contribution Guidelines', 'wikipress'), 'category'=> 'contributing' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Submitting and Review', 'wikipress'), 'category'=> 'contributing' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Rejections and Appeals', 'wikipress'), 'category'=> 'contributing' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Installing WordPress', 'wikipress'), 'category'=> 'how-to' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Testing WordPress Locally', 'wikipress'), 'category'=> 'how-to' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Getting Started with Databases', 'wikipress'), 'category'=> 'how-to' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Email Support in WordPress', 'wikipress'), 'category'=> 'how-to' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Using Video in Articles', 'wikipress'), 'category'=> 'how-to' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Using 3rd Party Plugins', 'wikipress'), 'category'=> 'how-to' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Working with Gutenberg', 'wikipress'), 'category'=> 'how-to' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Perks and Benefits', 'wikipress'), 'category'=> 'company' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Code of Conduct', 'wikipress'), 'category'=> 'company' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Privacy and Security', 'wikipress'), 'category'=> 'company' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Leadership', 'wikipress'), 'category'=> 'company' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Choosing the Right Pet', 'wikipress'), 'category'=> 'pets' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'How to Care for Your Pet', 'wikipress'), 'category'=> 'pets' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Caring for a Sick Pet', 'wikipress'), 'category'=> 'pets' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Excercising Your Pet', 'wikipress'), 'category'=> 'pets' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'The Relative Size of the Planets', 'wikipress'), 'category'=> 'space' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Calculating Distance in Light Years', 'wikipress'), 'category'=> 'space' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Planets in the Solar System', 'wikipress'), 'category'=> 'space' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'The Future of Space Travel', 'wikipress'), 'category'=> 'space' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Living in Space', 'wikipress'), 'category'=> 'space' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Manned Missions to Mars', 'wikipress'), 'category'=> 'space' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Tips for Cost Effective Travel', 'wikipress'), 'category'=> 'budgeting' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'How to Budget for a Trip', 'wikipress'), 'category'=> 'budgeting' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Where to Find the Best Deals on Used Cars', 'wikipress'), 'category'=> 'budgeting' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Saving Money on Dining', 'wikipress'), 'category'=> 'budgeting' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Free Activities for Days Out', 'wikipress'), 'category'=> 'budgeting' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Making the Perfect Pizza Base', 'wikipress'), 'category'=> 'food' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Baking Apple Pies for Guests', 'wikipress'), 'category'=> 'food' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Cupcakes from Scratch', 'wikipress'), 'category'=> 'food' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'How to Boil the Perfect Egg', 'wikipress'), 'category'=> 'food' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Jams, Jellies and Preserves', 'wikipress'), 'category'=> 'food' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Sunday Lunch Ideas', 'wikipress'), 'category'=> 'food' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Healthy Snacks and Drinks', 'wikipress'), 'category'=> 'food' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Cooking Fat Free Meals', 'wikipress'), 'category'=> 'food' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Choosing a New Car', 'wikipress'), 'category'=> 'cars' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'The Best Supercars of All Time', 'wikipress'), 'category'=> 'cars' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'How to Drive a Manual Transmission', 'wikipress'), 'category'=> 'cars' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Parallel Parking Tips and Tricks', 'wikipress'), 'category'=> 'cars' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Finding the Right Garage', 'wikipress'), 'category'=> 'cars' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Small Cars for the City', 'wikipress'), 'category'=> 'cars' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Saving Fuel by Driving Smart', 'wikipress'), 'category'=> 'cars' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Electric Car Buying Guide', 'wikipress'), 'category'=> 'cars' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Choosing the Best Seat on a Flight', 'wikipress'), 'category'=> 'travel' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Best Cities for Culture', 'wikipress'), 'category'=> 'travel' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'How to Get to the Airport in Style', 'wikipress'), 'category'=> 'travel' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Lounge Access on a Budget', 'wikipress'), 'category'=> 'travel' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Airport Parking Guide', 'wikipress'), 'category'=> 'travel' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Worlds Best Beaches', 'wikipress'), 'category'=> 'travel' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Clubbing and Partying in Mexico', 'wikipress'), 'category'=> 'travel' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Amazing Scenary in Canada', 'wikipress'), 'category'=> 'travel' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Top 10 River Cruise Destinations', 'wikipress'), 'category'=> 'travel' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Wildlife Sightseeing Trips', 'wikipress'), 'category'=> 'travel' );
					break;			
				default:
					//populate pages
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'VAT User Guide', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Travel and Entertainment', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Sign Off and Accountability', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Purchase Orders', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Overtime Payments', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Invoicing', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Expense Claims', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Employee Appraisals', 'wikipress'), 'category'=> 'Finance & HR' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'What Do in the Event of a Fire', 'wikipress'), 'category'=> 'Health & Safety' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Safe Lifting and Moving', 'wikipress'), 'category'=> 'Health & Safety' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Emergency Contacts', 'wikipress'), 'category'=> 'Health & Safety' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Company Disaster Recovery Plan', 'wikipress'), 'category'=> 'Health & Safety' ); 
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Using the VoIP telephones', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Remote Access & VPN', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Redirecting Calls', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Phone Directory', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Laptop Security', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'How To Reset Your Password', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Data Encryption & Security', 'wikipress'), 'category'=> 'IT & Phones' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Backup & Recovery', 'wikipress'), 'category'=> 'IT & Phones' );			
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Company Structure', 'wikipress'), 'category'=> 'Misc' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Office Locations & Hours', 'wikipress'), 'category'=> 'Misc' ); 
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Privacy Policy', 'wikipress'), 'category'=> 'Policies & Procedures' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Legal Compliance Framework', 'wikipress'), 'category'=> 'Policies & Procedures' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Employee Grievance Procedure', 'wikipress'), 'category'=> 'Policies & Procedures' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Data Security and Confidentiality', 'wikipress'), 'category'=> 'Policies & Procedures' );
					$sample_wiki_pages[] = array( 'title'=> esc_html__( 'Code of Conduct', 'wikipress'), 'category'=> 'Policies & Procedures' );
					break;
			}
			

			

			$sample_wiki_content = $this->get_sample_file_content('sample-wiki-default');

			foreach ($sample_wiki_pages as $key => $sample_wiki_page) {
				$sample_wiki_page_title = $sample_wiki_page['title'];
				$sample_wiki_page_category = $sample_wiki_page['category'];
				$page = get_page_by_title($sample_wiki_page_title);
				if( ! $page ){
					$page_id = $this->add_sample_post(HTWIKI_WIKI_POST_TYPE, $sample_wiki_page_title, $sample_wiki_content, $sample_wiki_page_category);
					$page = get_post($faq_page_id);
				}
			}

		}


		/**
		* Add sample categories
		*/
		function add_sample_wiki_categories($template = 'default'){

			$wiki_categories = array();

			switch ($template) {
				case 'company':
					//populate categories
					$wiki_categories[] = __('Welcome Aboard', 'wikipress');
					$wiki_categories[] = __('Health & Safety', 'wikipress');				
					$wiki_categories[] = __('Policies & Procedures', 'wikipress');
					$wiki_categories[] = __('IT & Phones', 'wikipress');
					$wiki_categories[] = __('Finance & HR', 'wikipress');
					$wiki_categories[] = __('Appendix', 'wikipress');
					break;	
				case 'general':
					//populate categories
					$wiki_categories[] = __('About', 'wikipress');
					$wiki_categories[] = __('Contributing', 'wikipress');				
					$wiki_categories[] = __('How To', 'wikipress');
					$wiki_categories[] = __('Company', 'wikipress');
					$wiki_categories[] = __('Pets', 'wikipress');
					$wiki_categories[] = __('Space', 'wikipress');
					$wiki_categories[] = __('Budgeting', 'wikipress');
					$wiki_categories[] = __('Food', 'wikipress');
					$wiki_categories[] = __('Travel', 'wikipress');
					break;			
				default:
					//populate categories
					$wiki_categories[] = __('Finance & HR', 'wikipress');
					$wiki_categories[] = __('Health & Safety', 'wikipress');	
					$wiki_categories[] = __('IT & Phones', 'wikipress');
					$wiki_categories[] = __('Misc', 'wikipress');		
					$wiki_categories[] = __('Policies & Procedures', 'wikipress');
					break;
			}

			//add categories
			foreach ($wiki_categories as $key => $wiki_category_name) {
				$wiki_category_id = $this->add_wiki_category($wiki_category_name);
			}

		}

		/**
		* Insert a wiki category
		* @param $name The name of the category
		* @return (ID) The term_id
		*/
		function add_wiki_category($name){
			$name = sanitize_text_field($name);
			$term = term_exists($name, HTWIKI_WIKI_CATEGORY_TAXONOMY);

			if(is_array($term)){
				//already a term
			} else {
				//else insert term
				$term = wp_insert_term($name, HTWIKI_WIKI_CATEGORY_TAXONOMY);
			}

			//return the terms id
			if( array_key_exists('term_id', $term) ){
				return $term['term_id'];
			} else {
				return new WP_Error( 'error', __('Term ID does not exist', 'wikipress') );
			}
			
		}

		/**
		* Install demo menus
		*/
		function htwiki_install_demo_menus(){
			// Check if the menu exists
			$menu_name = __('WikiPress Navigation Menu', 'wikipress');
			$menu_name = apply_filters('htwiki_sample_content_menu_name', $menu_name);
			$htwiki_nav_menu = wp_get_nav_menu_object( $menu_name );
			$menu_id = 0;

			//create if doesn't exist
			if( !$htwiki_nav_menu){
				$menu_id = wp_create_nav_menu($menu_name);
			} else {
				$menu_id = $htwiki_nav_menu->term_id;
			}


			$what_is_a_wiki_page_title = esc_html__( 'What is a Wiki?', 'wikipress');
			$what_is_a_wiki_page = get_page_by_title($what_is_a_wiki_page_title);
			if($what_is_a_wiki_page){
				wp_update_nav_menu_item($menu_id, 0, array(
						'menu-item-title' => $what_is_a_wiki_page->post_title,
						'menu-item-object' => 'page',
						'menu-item-object-id' => $what_is_a_wiki_page->ID,
						'menu-item-type' => 'post_type',
						'menu-item-status' => 'publish',
						//'menu-item-parent-id' => $navParentID,
						'menu-item-position' => $what_is_a_wiki_page->menu_order
					)
				);
			}

			$how_can_wikipress_help_page_title = esc_html__( 'How can WikiPress help you?', 'wikipress');
			$how_can_wikipress_help_page = get_page_by_title($how_can_wikipress_help_page_title);
			if($how_can_wikipress_help_page){
				wp_update_nav_menu_item($menu_id, 0, array(
						'menu-item-title' => $how_can_wikipress_help_page->post_title,
						'menu-item-object' => 'page',
						'menu-item-object-id' => $how_can_wikipress_help_page->ID,
						'menu-item-type' => 'post_type',
						'menu-item-status' => 'publish',
						//'menu-item-parent-id' => $navParentID,
						'menu-item-position' => $how_can_wikipress_help_page->menu_order
					)
				);
			}                 


			//assign the menu
			if( !has_nav_menu( 'main-menu' ) ){
				//menu location not assigned
				$locations = get_theme_mod('nav_menu_locations');
				$locations['main-menu'] = $menu_id;
				set_theme_mod( 'nav_menu_locations', $locations );
			} else {
				//menu location assigned, overwrite
				$locations = get_theme_mod('nav_menu_locations');
				$locations['main-menu'] = $menu_id;
				set_theme_mod( 'nav_menu_locations', $locations );
			}


		}

		/**
		* Install demo widgets
		*/
		function htwiki_install_demo_widgets(){

			$sidebar_widgets = get_option('sidebar_widgets');
			//init if required
			$sidebar_widgets = ( empty( $sidebar_widgets ) ) ? array() : $sidebar_widgets;
			
			/*

			//Sidebar -- HOME  (id: sidebar-home)

			//1. Knowledge Base Articles:Popular Articles

			//widget_ht-kb-articles-widget
			$articles_widgets = get_option('widget_ht-kb-articles-widget', array());
			//compute an index
			//dev note - this isn't perfect as WP does not seem to index sequentially, it may override an old widget
			//but remains workable to overcome other issues with auto adding widgets
			$articles_widgets_index = count($articles_widgets) + 1;

			$new_articles_widget = array (
				'title' => __('Popular Articles', 'htwiki'),
				'num' => 5,
				'sort_by' => 'popular',
				'category' => 'all',
				'asc_sort_order' => 0,
				'comment_num' => 0,
				'rating' => 0
			);
			//add to array
			$articles_widgets[$articles_widgets_index] = $new_articles_widget;
			update_option( 'widget_ht-kb-articles-widget', $articles_widgets );

			$sidebar_widgets['sidebar-home'][] = 'ht-kb-articles-widget-' . $articles_widgets_index;
			

			//2. Knowledge Base Exit:Need Support

			//submit ticket page url
			$submit_ticket_page_url = '#';
			$submit_ticket_page = get_page_by_title( esc_html__('Submit A Ticket', 'htwiki') );
			if( ! empty( $submit_ticket_page ) ){
				$submit_ticket_page_permalink = get_permalink($submit_ticket_page);
				$submit_ticket_page_url = $submit_ticket_page_permalink;
			}

			//widget_ht-kb-exit-widget
			$exit_widgets = get_option('widget_ht-kb-exit-widget', array());
			//compute an index
			$exit_widgets_index = count($exit_widgets) + 1;

			$new_exit_widget = array (
				'title' => __('Need Support?', 'htwiki'),
				'text' => __("Can't find the answer you're looking for? Don't worry we're here to help!", 'htwiki'),
				'btn' => __('CONTACT SUPPORT', 'htwiki'),
				'url' => $submit_ticket_page_url 
			);
			//add to array
			$exit_widgets[$exit_widgets_index] = $new_exit_widget;
			update_option( 'widget_ht-kb-exit-widget', $exit_widgets );

			$sidebar_widgets['sidebar-home'][] = 'ht-kb-exit-widget-' . $exit_widgets_index;
			

			//Sidebar -- Category (id: sidebar-category)

			//1. Knowledge Base Exit:Need Support

			//widget_ht-kb-exit-widget
			$exit_widgets = get_option('widget_ht-kb-exit-widget', array());
			//compute an index
			$exit_widgets_index = count($exit_widgets) + 1;

			$new_exit_widget = array (
				'title' => __('Need Support?', 'htwiki'),
				'text' => __("Can't find the answer you're looking for? Don't worry we're here to help!", 'htwiki'),
				'btn' => __('CONTACT SUPPORT', 'htwiki'),
				'url' => $submit_ticket_page_url
			);

			//add to array
			$exit_widgets[$exit_widgets_index] = $new_exit_widget;
			update_option( 'widget_ht-kb-exit-widget', $exit_widgets );

			$sidebar_widgets['sidebar-category'][] = 'ht-kb-exit-widget-' . $exit_widgets_index;

			//Sidebar -- Article (id: sidebar-article)

			//1. Knowledge Base Table of Contents:Contents

			//widget_ht-kb-toc-widget
			$toc_widgets = get_option('widget_ht-kb-toc-widget', array());
			//compute an index
			$toc_widgets_index = count($toc_widgets) + 1;

			$new_toc_widget = array (
				'title' => __('Contents', 'htwiki'),
			);
			//add_to_array
			$toc_widgets[$toc_widgets_index] = $new_toc_widget;
			update_option( 'widget_ht-kb-toc-widget', $toc_widgets );

			//get and assign the key
			end($toc_widgets);
			$toc_widget_index = key($toc_widgets);
			$sidebar_widgets['sidebar-article'][] = 'ht-kb-toc-widget-' . $toc_widgets_index;

			//not in this version
			//(id: sidebar-page)

			//update active widgets
			update_option( 'sidebars_widgets', $sidebar_widgets );
			*/
		}

		/**
		* Set default theme mods
		*/
		function htwiki_install_demo_settings(){

			//page on front
			$welcome_to_wikipress_page_title = esc_html__( 'Welcome to WikiPress', 'wikipress');
			$welcome_to_wikipress_page = get_page_by_title( $welcome_to_wikipress_page_title );

			if ( $welcome_to_wikipress_page ){
				update_option( 'page_on_front', $welcome_to_wikipress_page->ID );
   				update_option( 'show_on_front', 'page' );
			}

			//set the theme mods here

			//login page
			$login_page_id = get_option(HTWIKI_WIKI_LOGIN_PAGE_POST_ID_KEY);
			set_theme_mod( 'wkp_setting__loginpage', $login_page_id );   

			
		}

		/**
		* Adds a sample post
		* @param $title The title of the post
		* @param $content The content of the post
		* @param $category The category of the post
		* @return (Int) New post id
		*/
		function add_sample_post($post_type='post', $title = '', $content = '', $wiki_category = '' ){

			//if already a post, use that 
			$post = get_page_by_title( $title );
			if($post && is_a($post, 'WP_Post')){
				//@todo - are additional comparative tests required here?
				return $post->ID;
			}

			$new_post = array(
				  'post_content'   => $content,
				  'post_name'      => $title,
				  'post_title'     => $title,
				  'post_status'    => 'publish',
				  'post_type'      => $post_type
				);

			$new_post_id = wp_insert_post($new_post);


			//check post if valid, and post type not page, then add category and tag taxonomies
			if( $new_post_id > 0){
				//wiki_categories
				if( '' != $wiki_category ){
					$taxonomy = HTWIKI_WIKI_CATEGORY_TAXONOMY;
					//set new category, override old ones
					$wiki_category_slug = sanitize_title($wiki_category);
					wp_set_object_terms( $new_post_id, $wiki_category_slug, $taxonomy, false );  
				}
				
			}

			return $new_post_id;
			
		}


		/**
		* Get the sample content from a file
		* @param (String) $filename (Note this will we sanitized with sanitize title and must be in the sample-content directory, will also be appended default with .php)
		* @param (String) $ext File extension
		* @return (String) Sample content
		*/
		function get_sample_file_content($filename='', $ext='php'){
			if( ! empty( $filename )){
				$filename = sanitize_title($filename) . '.' . $ext;
				ob_start();
				@include( dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sample-content' . DIRECTORY_SEPARATOR . $filename );
				$sample = ob_get_contents();
				ob_end_clean();
				return $sample;
			} else {
				return __('No sample content available for this file', 'wikipress');
			}            

		}

		/**
		* Get the sample content from a file
		* @param (String) $filename (Note this will we sanitized with sanitize title and must be in the sample-content/svg directory, will also be appended default with .svg)
		* @param (String) $ext File extension
		* @return (String) Sample content
		*/
		function get_sample_svg_content($filename='', $ext='svg'){
			if( ! empty( $filename )){
				$filename = sanitize_title($filename) . '.' . $ext;
				ob_start();
				@include( dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sample-content' . DIRECTORY_SEPARATOR . 'svg' . DIRECTORY_SEPARATOR . $filename );
				$sample = ob_get_contents();
				ob_end_clean();
				return $sample;
			} else {
				return __('No sample content available for this file', 'wikipress');
			}            

		}

		/** 
		* Assign a category image to a category term
		* @param (Int) $attachment_id Attachment ID
		* @param (Int) $term_id Category Term ID to assign image 
		*/
		function assign_category_image_to_category($attachment_id=0, $term_id=0){
			if( $attachment_id>0 && $term_id > 0){
				$term_meta = array();
				$term_meta['meta_image'] = $attachment_id;
				update_option("taxonomy_$term_id", $term_meta);
			}
		}

		/** 
		* Assign a svg content to a category term
		* @param (String) $svg_content The svg content
		* @param (Int) $term_id Category Term ID to assign image 
		*/
		function assign_category_svg_to_category($svg_content='', $term_id=0){
			if( !empty($svg_content) && $term_id > 0){
				$term_meta['meta_svg'] = $svg_content;
				update_option("taxonomy_$term_id", $term_meta); 
			}
		}


		/** 
		* imports a image from the sample-content/images from the welcome/sample-content/images/ directory
		* @param (String) $filename the filename
		* @param (String) $ext filename extension
		* @return (Int) ID attachment ID
		*/
		function import_image_to_wp_library($filename='undefined', $ext='png'){
			$file_to_import = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sample-content' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $filename . '.' . $ext ;
			//
			$file_to_import = apply_filters('htwiki_import_image_to_wp_library', $file_to_import, $filename, $ext);
			if(file_exists($file_to_import)){
			   $id = $this->handle_import_image($file_to_import);
				return $id; 
			} else {
				return new WP_Error( 'error', __('File does not exist', 'wikipress') );
			}            
		}


		/**
		* Handle image import
		* @param (File) Image file to import 
		* @return (Int) attachment_id
		*/
		function handle_import_image( $file ) {
			set_time_limit( 0 );

			//current time
			$time = current_time( 'mysql', 1 );

			//test writeable upload dir
			if ( !(($uploads = wp_upload_dir( $time )) && false === $uploads['error']) ) {
				return new WP_Error( 'import_error', $uploads['error'] );
			}

			$wp_filetype = wp_check_filetype( $file, null );

			//extract
			extract( $wp_filetype );

			//generate unique filename
			$filename = wp_unique_filename( $uploads['path'], basename( $file ) );

			//copy the file to the uploads dir
			$new_file = $uploads['path'] . '/' . $filename;
			if ( false === @copy( $file, $new_file ) )
				return new WP_Error( 'import_error', sprintf( __( 'Unable to import %s.', 'wikipress' ), $file ) );

			//assign file permissions
			try {
			   $stat = stat( dirname( $new_file ) );
				$perms = $stat['mode'] & 0000666;
				chmod( $new_file, $perms );   
			} catch (Exception $e) {
				//handle permissions errors
			}                             

			//prepare the url
			$url = $uploads['url'] . DIRECTORY_SEPARATOR . $filename;

			//apply upload filters
			$return = apply_filters( 'wp_handle_upload', array( 'file' => $new_file, 'url' => $url, 'type' => $type ) );
			$new_file = $return['file'];
			$url = $return['url'];
			$type = $return['type'];

			$attachment_title = preg_replace( '!\.[^.]+$!', '', basename( $file ) );
			$attachment_content = '';
			$attachment_excerpt = '';

			//use image exif for title and captions
			@require_once( ABSPATH . '/wp-admin/includes/image.php' );
			if ( 0 === strpos( $type, 'image/' ) && $image_meta = @wp_read_image_metadata( $new_file ) ) {
				if ( trim( $image_meta['title'] ) && ! is_numeric( sanitize_title( $image_meta['title'] ) ) ) {
					$attachment_title = $image_meta['title'];
				}
		
				if ( trim( $image_meta['caption'] ) ) {
					$attachment_excerpt = $image_meta['caption'];
				}
			}
			
			$attachment_post_date = current_time( 'mysql' );
			$attachment_post_date_gmt = current_time( 'mysql', 1 );

			//attachment array
			$attachment_data = array(
				'post_mime_type' => $type,
				'guid' => $url,
				'post_parent' => 0,
				'post_title' => $attachment_title,
				'post_name' => $attachment_title,
				'post_content' => $attachment_content,
				'post_excerpt' => $attachment_excerpt,
				'post_date' => $attachment_post_date,
				'post_date_gmt' => $attachment_post_date_gmt
			);

			//wc 4.4 compat
			$new_file = str_replace( ucfirst( wp_normalize_path( $uploads['basedir'] ) ), $uploads['basedir'], $new_file );

			//save
			$id = wp_insert_attachment( $attachment_data, $new_file );
			if ( !is_wp_error( $id ) ) {
				$data = wp_generate_attachment_metadata( $id, $new_file );
				wp_update_attachment_metadata( $id, $data );
			}

			return $id;
		}

		/**
		* Helper function checks if slug is available
		* interface function reserved for future use
		* @param (String) $slug Slug to check
		* @return true
		*/ 
		function is_slug_available($slug){
			return true;
		}


	}

}


//run the module
if(class_exists('HTWiki_Sample_Content_Helpers')){

	$htwiki_sample_content_helpers_init = new HTWiki_Sample_Content_Helpers();

}