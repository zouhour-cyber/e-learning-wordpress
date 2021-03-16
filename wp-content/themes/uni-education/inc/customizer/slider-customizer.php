<?php
/**
 * Slider Customizer Options
 *
 * @package uni_education
 */

// Add slider section
$wp_customize->add_section( 'uni_education_slider_section', array(
	'title'             => esc_html__( 'Slider Section','uni-education' ),
	'description'       => esc_html__( 'Slider Setting Options', 'uni-education' ),
	'panel'             => 'uni_education_theme_options_panel',
) );

// slider menu enable setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[enable_slider]', array(
	'default'           => uni_education_theme_option('enable_slider'),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[enable_slider]', array(
	'label'             => esc_html__( 'Enable Slider', 'uni-education' ),
	'section'           => 'uni_education_slider_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// slider social menu enable setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[slider_entire_site]', array(
	'default'           => uni_education_theme_option('slider_entire_site'),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[slider_entire_site]', array(
	'label'             => esc_html__( 'Show Entire Site', 'uni-education' ),
	'section'           => 'uni_education_slider_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// slider arrow control enable setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[slider_auto_slide]', array(
	'default'           => uni_education_theme_option('slider_auto_slide'),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[slider_auto_slide]', array(
	'label'             => esc_html__( 'Enable Auto Slide', 'uni-education' ),
	'section'           => 'uni_education_slider_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// slider arrow control enable setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[slider_arrow]', array(
	'default'           => uni_education_theme_option('slider_arrow'),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[slider_arrow]', array(
	'label'             => esc_html__( 'Show Arrow Controller', 'uni-education' ),
	'section'           => 'uni_education_slider_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// slider btn label chooser control and setting
$wp_customize->add_setting( 'uni_education_theme_options[slider_btn_label]', array(
	'default'          	=> uni_education_theme_option('slider_btn_label'),
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'uni_education_theme_options[slider_btn_label]', array(
	'label'             => esc_html__( 'Button Label', 'uni-education' ),
	'section'           => 'uni_education_slider_section',
	'type'				=> 'text',
) );

for ( $i = 1; $i <= 5; $i++ ) :

	// slider pages drop down chooser control and setting
	$wp_customize->add_setting( 'uni_education_theme_options[slider_content_page_' . $i . ']', array(
		'sanitize_callback' => 'uni_education_sanitize_page_post',
	) );

	$wp_customize->add_control( new Uni_Education_Dropdown_Chosen_Control( $wp_customize, 'uni_education_theme_options[slider_content_page_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Select Page %d', 'uni-education' ), $i ),
		'section'           => 'uni_education_slider_section',
		'choices'			=> uni_education_page_choices(),
	) ) );

endfor;