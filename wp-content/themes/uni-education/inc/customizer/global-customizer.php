<?php
/**
 * Global Customizer Options
 *
 * @package uni_education
 */

// Add Global section
$wp_customize->add_section( 'uni_education_global_section', array(
	'title'             => esc_html__( 'Global Setting','uni-education' ),
	'description'       => esc_html__( 'Global Setting Options', 'uni-education' ),
	'panel'             => 'uni_education_theme_options_panel',
) );

// header sticky setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[enable_sticky_header]', array(
	'default'           => uni_education_theme_option( 'enable_sticky_header' ),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[enable_sticky_header]', array(
	'label'             => esc_html__( 'Make Header Sticky', 'uni-education' ),
	'section'           => 'uni_education_global_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// breadcrumb setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[enable_breadcrumb]', array(
	'default'           => uni_education_theme_option( 'enable_breadcrumb' ),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[enable_breadcrumb]', array(
	'label'             => esc_html__( 'Enable Breadcrumb', 'uni-education' ),
	'section'           => 'uni_education_global_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// site layout setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[site_layout]', array(
	'sanitize_callback'   => 'uni_education_sanitize_select',
	'default'             => uni_education_theme_option('site_layout'),
) );

$wp_customize->add_control(  new Uni_Education_Radio_Image_Control ( $wp_customize, 'uni_education_theme_options[site_layout]', array(
	'label'               => esc_html__( 'Site Layout', 'uni-education' ),
	'section'             => 'uni_education_global_section',
	'choices'			  => uni_education_site_layout(),
) ) );

// loader setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[enable_loader]', array(
	'default'           => uni_education_theme_option( 'enable_loader' ),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[enable_loader]', array(
	'label'             => esc_html__( 'Enable Loader', 'uni-education' ),
	'section'           => 'uni_education_global_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// loader type control and setting
$wp_customize->add_setting( 'uni_education_theme_options[loader_type]', array(
	'default'          	=> uni_education_theme_option('loader_type'),
	'sanitize_callback' => 'uni_education_sanitize_select',
) );

$wp_customize->add_control( 'uni_education_theme_options[loader_type]', array(
	'label'             => esc_html__( 'Loader Type', 'uni-education' ),
	'section'           => 'uni_education_global_section',
	'type'				=> 'select',
	'choices'			=> uni_education_get_spinner_list(),
) );
