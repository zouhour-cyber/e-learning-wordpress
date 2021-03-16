<?php
/**
 * Footer Customizer Options
 *
 * @package uni_education
 */

// Add footer section
$wp_customize->add_section( 'uni_education_footer_section', array(
	'title'             => esc_html__( 'Footer Section','uni-education' ),
	'description'       => esc_html__( 'Footer Setting Options', 'uni-education' ),
	'panel'             => 'uni_education_theme_options_panel',
) );

// slide to top enable setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[slide_to_top]', array(
	'default'           => uni_education_theme_option('slide_to_top'),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[slide_to_top]', array(
	'label'             => esc_html__( 'Show Slide to Top', 'uni-education' ),
	'section'           => 'uni_education_footer_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// copyright text
$wp_customize->add_setting( 'uni_education_theme_options[copyright_text]',
	array(
		'default'       		=> uni_education_theme_option('copyright_text'),
		'sanitize_callback'		=> 'uni_education_santize_allow_tags',
	)
);
$wp_customize->add_control( 'uni_education_theme_options[copyright_text]',
    array(
		'label'      			=> esc_html__( 'Copyright Text', 'uni-education' ),
		'section'    			=> 'uni_education_footer_section',
		'type'		 			=> 'textarea',
    )
);
