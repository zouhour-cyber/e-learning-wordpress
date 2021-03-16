<?php
/**
 * Single Post Customizer Options
 *
 * @package uni_education
 */

// Add excerpt section
$wp_customize->add_section( 'uni_education_single_section', array(
	'title'             => esc_html__( 'Single Post Setting','uni-education' ),
	'description'       => esc_html__( 'Single Post Setting Options', 'uni-education' ),
	'panel'             => 'uni_education_theme_options_panel',
) );

// sidebar layout setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[sidebar_single_layout]', array(
	'sanitize_callback'   => 'uni_education_sanitize_select',
	'default'             => uni_education_theme_option('sidebar_single_layout'),
) );

$wp_customize->add_control(  new Uni_Education_Radio_Image_Control ( $wp_customize, 'uni_education_theme_options[sidebar_single_layout]', array(
	'label'               => esc_html__( 'Sidebar Layout', 'uni-education' ),
	'section'             => 'uni_education_single_section',
	'choices'			  => uni_education_sidebar_position(),
) ) );

// Archive date meta setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[show_single_date]', array(
	'default'           => uni_education_theme_option( 'show_single_date' ),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[show_single_date]', array(
	'label'             => esc_html__( 'Show Date', 'uni-education' ),
	'section'           => 'uni_education_single_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// Archive category meta setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[show_single_category]', array(
	'default'           => uni_education_theme_option( 'show_single_category' ),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[show_single_category]', array(
	'label'             => esc_html__( 'Show Category', 'uni-education' ),
	'section'           => 'uni_education_single_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// Archive category meta setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[show_single_tags]', array(
	'default'           => uni_education_theme_option( 'show_single_tags' ),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[show_single_tags]', array(
	'label'             => esc_html__( 'Show Tags', 'uni-education' ),
	'section'           => 'uni_education_single_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// Archive author meta setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[show_single_author]', array(
	'default'           => uni_education_theme_option( 'show_single_author' ),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[show_single_author]', array(
	'label'             => esc_html__( 'Show Author', 'uni-education' ),
	'section'           => 'uni_education_single_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );
