<?php
/**
 * Customizer functionality
 */

// Load library
require_once ( get_template_directory() . '/inc/kirki/kirki.php' );

// Disable Kirki telemetry module
add_filter( 'kirki_telemetry', '__return_false' );

// Update paths
if ( ! function_exists( 'wikipress_kirki_update_url' ) ) {
    function wikipress_kirki_update_url( $config ) {
        $config['url_path'] = get_template_directory_uri() . '/inc/kirki/';
        return $config;
    }
}
add_filter( 'kirki/config', 'wikipress_kirki_update_url', 999 );

// Style library
function wikipress_kirki_styling( $config ) {
	return wp_parse_args( array(
		'disable_loader'   => true,
	), $config );
}
add_filter( 'kirki/config', 'wikipress_kirki_styling' );


if ( class_exists( 'Kirki' ) ) {

	/**
	* inheritable configuration
	*/
	Kirki::add_config( 'wikipress', array(
		'capability'    => 'edit_theme_options',
		'option_type'   => 'theme_mod',
	) );

	/** 
	* Panel: Theme
	*/
	Kirki::add_panel( 'wkp_panel__main', array(
	    'priority'    => 10,
	    'title'       => esc_attr__( 'Theme', 'wikipress' ),
	    'description' => esc_attr__( 'Customize your theme here', 'wikipress' ),
	) );


	/** 
	* Section: Header
	*/
	Kirki::add_section( 'wkp_sec__header', array(
		'title'         => esc_attr__( 'Header', 'wikipress' ),
		'panel'     	=> 'wkp_panel__main',
		'priority'      => 1,
		'capability'    => 'edit_theme_options',
	) );

	// Setting: Theme Logo
	Kirki::add_field( 'htwiki', array(
		'type'     		=> 'image',
	    'settings' 		=> 'wkp_setting__themelogo',
	    'label'			=> esc_attr__( 'Site Logo', 'wikipress' ),
	    'description' 	=> esc_attr__( 'Upload a site logo image.', 'wikipress' ),
	    'section'  		=> 'wkp_sec__header',
	    'priority' 		=> 10,
	    'default'  		=> get_template_directory_uri().'/img/wikipress-logo.png',
	) );


	/** 
	* Section: Styling
	*/
	Kirki::add_section( 'wkp_sec__styling', array(
		'title'          => esc_attr__( 'Styling', 'wikipress' ),
		'panel'     	=> 'wkp_panel__main',
		'priority'       => 1,
		'capability'     => 'edit_theme_options',
	) );

	// Setting: Theme Color
	Kirki::add_field( 'wikipress', array(
		'type'        => 'color',
		'settings'    => 'wkp_setting__themecolor',
		'label'       => esc_attr__( 'Theme Color', 'wikipress' ),
		'description' => esc_attr__( 'Set the theme color.', 'wikipress' ),
		'section'     => 'wkp_sec__styling',
		'default'     => '#1B499A',
		'priority'    => 10,
		'transport'   => 'postMessage',
		'output'      => array(
			array(
				'element'  => '.wkp-header, .wkp-userinfo .wkp-userinfo__contentwrap, body.page-template-template-login, form input[type="submit"], form .wkp-formsubmit',
				'property' => 'background',
			),
			array(
				'element'  => '.wkp-article__editbtn a:hover, .wkp-article__addbtn a:hover',
				'property' => 'color',
			),
			array(
				'element'  => '.wkp-userinfo .wkp-userinfo__contentwrap::before',
				'property' => 'background',
				'value_pattern' => 'linear-gradient(to bottom, rgba(255, 255, 255, 0) 0%, $ 100%)',
			),
			array(
				'element'  => '.wkp-postlist svg path, .wkp-catlist svg path',
				'property' => 'fill',
			),
			array(
				'element'  => '.wp-block-quote',
				'property' => 'border-color',
			),
		),
		'js_vars'     => array(
			array(
				'element'  => '.wkp-header, .wkp-userinfo .wkp-userinfo__contentwrap, body.page-template-template-login, form input[type="submit"], form .wkp-formsubmit',
				'function' => 'style',
				'property' => 'background',
			),
			array(
				'element'  => '.wkp-article__editbtn a:hover, .wkp-article__addbtn a:hover',
				'function' => 'style',
				'property' => 'color',
			),
			array(
				'element'  => '.wkp-postlist svg path, .wkp-catlist svg path',
				'function' => 'style',
				'property' => 'fill',
			),
			array(
				'element' => '.wkp-userinfo .wkp-userinfo__contentwrap::before',
				'function' => 'style',
				'property' => 'background',
				'value_pattern' => 'linear-gradient(to bottom, rgba(255, 255, 255, 0) 0%, $ 100%)',
			),
			array(
				'element'  => '.wp-block-quote',
				'function' => 'style',
				'property' => 'border-color',
			),
		),
	) );

	// Setting: Link Color
	Kirki::add_field( 'wikipress', array(
		'type'        => 'color',
		'settings'    => 'wkp_setting__linkcolor',
		'label'       => esc_attr__( 'Link Color', 'wikipress' ),
		'description' => esc_attr__( 'Set the link color.', 'wikipress' ),
		'section'     => 'wkp_sec__styling',
		'default'     => '#1B499A',
		'priority'    => 10,
		'transport'   => 'postMessage',
		'output'      => array(
			array(
				'element'  => 'a',
				'property' => 'color',
			),
		),
		'js_vars'     => array(
			array(
				'element'  => 'a',
				'function' => 'style',
				'property' => 'color',
			),
		),
	) );

	// Setting: Link Color:Hover
	Kirki::add_field( 'wikipress', array(
		'type'        => 'color',
		'settings'    => 'wkp_setting__linkcolorhover',
		'label'       => esc_attr__( 'Link Color:hover', 'wikipress' ),
		'description' => esc_attr__( 'Set the link on hover color', 'wikipress' ),
		'section'     => 'wkp_sec__styling',
		'default'     => '#1B499A',
		'priority'    => 10,
		'transport'   => 'postMessage',
		'output'      => array(
			array(
				'element'  => 'a:hover',
				'property' => 'color',
			),
		),
		
		'js_vars'     => array(
			array(
				'element'  => 'a:hover',
				'function' => 'style',
				'property' => 'color',
			),
		),
	) );

	// Setting: Menu Text Color
	Kirki::add_field( 'wikipress', array(
		'type'        => 'color',
		'settings'    => 'wkp_setting__menutextcolor',
		'label'       => esc_attr__( 'Menu Text', 'wikipress' ),
		'description' => esc_attr__( 'Set the menu text color.', 'wikipress' ),
		'section'     => 'wkp_sec__styling',
		'default'     => '#ffffff',
		'priority'    => 10,
		'transport'   => 'postMessage',
		'output'      => array(
			array(
				'element'  => '.wkp-mainnav>ul a, .wkp-mainnav>ul ul a, .wkp-userinfo__content a .wkp-userinfo__name',
				'property' => 'color',
			),
		),
		'js_vars'     => array(
			array(
				'element'  => '.wkp-mainnav>ul a, .wkp-mainnav>ul ul a, .wkp-userinfo__content a .wkp-userinfo__name',
				'function' => 'style',
				'property' => 'color',
			),
		),
	) );


	/**
	* Theme Typography
	*/
	Kirki::add_section( 'typography_sec', array(
		'title'          => esc_attr__( 'Typography', 'wikipress' ),
		'panel'     	=> 'wkp_panel__main',
		'priority'       => 1,
		'capability'     => 'edit_theme_options',
	) );



	Kirki::add_field( 'wikipress', array(
		'type'        => 'typography',
		'settings'    => 'wkp_typography_headings',
		'label'       => esc_attr__( 'Headings Font', 'wikipress' ),
		'description' => esc_attr__( 'Set the heading typography here', 'wikipress' ),
		'section'     => 'typography_sec',
		'default'     => array(
			'font-family'    => 'Open Sans',
			'variant' 		   => '700',
			'letter-spacing' => '0',
			'color'          => '#32325d',
			'text-transform' => 'none',
		),
		'priority'    => 10,
		'choices'     => array(
			'fonts' => array(
				'google',
				'standard' => array(
					'-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol"',
					),
				),
		),
		'output' => array(
			array(
				'element' => 'h1, h2, h3, h4, h5, h6',
				'property' => 'font-family',
			),
			array(
			'element'  => '.editor-post-title__block .editor-post-title__input',
		),
		),
	) );

	Kirki::add_field( 'wikipress', array(
	'type'        => 'typography',
	'settings'    => 'wkp_typography_body',
	'label'       => esc_attr__( 'Body Font', 'wikipress' ),
	'description' => esc_attr__( 'Set the body typography here', 'wikipress' ),
	'section'     => 'typography_sec',
	'default'     => array(
		'font-family'    => 'Open Sans',
		'variant'        => 'regular',
		'font-size'      => '17px',
		'line-height'    => '1.75',
		'letter-spacing' => '0',
		'color'          => '#525f7f',
	),
	'priority'    => 10,
	'choices' => array(
		'fonts' => array(
			'google',
			'standard' => array(
				'-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol"',
				),
			),
	),
	'output'      => array(
		array(
			'element' => 'html',
		),
		array(
			'element'  => '.edit-post-visual-editor.editor-styles-wrapper',
		),

	),
	) );

	/**
	* Section: Theme Settings
	*/
	Kirki::add_section( 'wkp_sec__settings', array(
		'title'          => esc_attr__( 'Wiki Settings', 'wikipress' ),
		'panel'     	=> 'wkp_panel__main',
		'priority'       => 1,
		'capability'     => 'edit_theme_options',
	) );

	Kirki::add_field( 'wikipress', array(
		'settings'    => 'wkp_setting__loginpage',
  	'section'     => 'wkp_sec__settings',
  	'type'        => 'dropdown-pages',
  	'label'       => esc_attr__( 'Login Page', 'wikipress' ),   
  	'description' => esc_attr__( 'Set your login page', 'wikipress' ),
  	'priority'    => 10,
	) );

	/*
	Kirki::add_field( 'wikipress', array(
		'settings'    => 'wkp_setting__wikianonedit',
  	'section'     => 'wkp_sec__settings',
  	'type'        => 'toggle',
  	'label'       => esc_attr__( 'Anonymous Edit', 'wikipress' ),   
  	'description' => esc_attr__( 'Enable anonymous users to edit articles', 'wikipress' ),
  	'default'     => '0',
  	'priority'    => 10,
	) );

	Kirki::add_field( 'wikipress', array(
		'settings'    => 'wkp_setting__wikianonmedia',
  	'section'     => 'wkp_sec__settings',
  	'type'        => 'toggle',
  	'label'       => esc_attr__( 'Anonymous Media Upload', 'wikipress' ),   
  	'description' => esc_attr__( 'Enable anonymous users to upload files', 'wikipress' ),
  	'default'     => '0',
  	'priority'    => 10,
	) );
	*/

}

