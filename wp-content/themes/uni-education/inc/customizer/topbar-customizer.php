<?php
/**
 * Topbar Customizer Options
 *
 * @package uni_education
 */

// Add topbar section
$wp_customize->add_section( 'uni_education_topbar_section', array(
	'title'             => esc_html__( 'Top Bar Section','uni-education' ),
	'description'       => sprintf( '%1$s <a class="menu_locations" href="#"> %2$s </a> %3$s', esc_html__( 'Note: To show social menu.', 'uni-education' ), esc_html__( 'Click Here', 'uni-education' ), esc_html__( 'to create menu.', 'uni-education' ) ),
	'panel'             => 'uni_education_theme_options_panel',
) );

// topbar enable setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[enable_topbar]', array(
	'default'           => uni_education_theme_option('enable_topbar'),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[enable_topbar]', array(
	'label'             => esc_html__( 'Enable Topbar', 'uni-education' ),
	'section'           => 'uni_education_topbar_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// topbar address control and setting
$wp_customize->add_setting( 'uni_education_theme_options[topbar_open_hrs]', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Uni_Education_Dropdown_Chosen_Control( $wp_customize, 'uni_education_theme_options[topbar_open_hrs]', array(
	'label'             => esc_html__( 'Opening Days - Hrs', 'uni-education' ),
	'section'           => 'uni_education_topbar_section',
	'type'				=> 'text',
) ) );

// topbar phone control and setting
$wp_customize->add_setting( 'uni_education_theme_options[topbar_phone]', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Uni_Education_Dropdown_Chosen_Control( $wp_customize, 'uni_education_theme_options[topbar_phone]', array(
	'label'             => esc_html__( 'Phone No', 'uni-education' ),
	'section'           => 'uni_education_topbar_section',
	'type'				=> 'text',
) ) );

// topbar email control and setting
$wp_customize->add_setting( 'uni_education_theme_options[topbar_email]', array(
	'sanitize_callback' => 'sanitize_email',
) );

$wp_customize->add_control( new Uni_Education_Dropdown_Chosen_Control( $wp_customize, 'uni_education_theme_options[topbar_email]', array(
	'label'             => esc_html__( 'Email ID', 'uni-education' ),
	'section'           => 'uni_education_topbar_section',
	'type'				=> 'email',
) ) );

// topbar social menu enable setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[show_social_menu]', array(
	'default'           => uni_education_theme_option('show_social_menu'),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[show_social_menu]', array(
	'label'             => esc_html__( 'Show Social Menu', 'uni-education' ),
	'section'           => 'uni_education_topbar_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// topbar search enable setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[show_top_search]', array(
	'default'           => uni_education_theme_option('show_top_search'),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[show_top_search]', array(
	'label'             => esc_html__( 'Show Search', 'uni-education' ),
	'section'           => 'uni_education_topbar_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );