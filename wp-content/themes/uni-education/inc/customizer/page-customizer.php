<?php
/**
 * Page Customizer Options
 *
 * @package uni_education
 */

// Add excerpt section
$wp_customize->add_section( 'uni_education_page_section', array(
	'title'             => esc_html__( 'Page Setting','uni-education' ),
	'description'       => esc_html__( 'Page Setting Options', 'uni-education' ),
	'panel'             => 'uni_education_theme_options_panel',
) );

// sidebar layout setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[sidebar_page_layout]', array(
	'sanitize_callback'   => 'uni_education_sanitize_select',
	'default'             => uni_education_theme_option('sidebar_page_layout'),
) );

$wp_customize->add_control(  new Uni_Education_Radio_Image_Control ( $wp_customize, 'uni_education_theme_options[sidebar_page_layout]', array(
	'label'               => esc_html__( 'Sidebar Layout', 'uni-education' ),
	'section'             => 'uni_education_page_section',
	'choices'			  => uni_education_sidebar_position(),
) ) );
