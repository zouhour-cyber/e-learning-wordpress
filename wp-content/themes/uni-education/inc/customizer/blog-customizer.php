<?php
/**
 * Blog / Archive / Search Customizer Options
 *
 * @package uni_education
 */

// Add blog section
$wp_customize->add_section( 'uni_education_blog_section', array(
	'title'             => esc_html__( 'Blog/Archive Page Setting','uni-education' ),
	'description'       => esc_html__( 'Blog/Archive/Search Page Setting Options', 'uni-education' ),
	'panel'             => 'uni_education_theme_options_panel',
) );

// latest blog title drop down chooser control and setting
$wp_customize->add_setting( 'uni_education_theme_options[latest_blog_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'          	=> uni_education_theme_option( 'latest_blog_title' ),
) );

$wp_customize->add_control( new Uni_Education_Dropdown_Chosen_Control( $wp_customize, 'uni_education_theme_options[latest_blog_title]', array(
	'label'             => esc_html__( 'Latest Blog Title', 'uni-education' ),
	'description'       => esc_html__( 'Note: This title is displayed when your homepage displays option is set to latest posts.', 'uni-education' ),
	'section'           => 'uni_education_blog_section',
	'type'				=> 'text',
) ) );

// sidebar layout setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[sidebar_layout]', array(
	'sanitize_callback'   => 'uni_education_sanitize_select',
	'default'             => uni_education_theme_option( 'sidebar_layout' ),
) );

$wp_customize->add_control(  new Uni_Education_Radio_Image_Control ( $wp_customize, 'uni_education_theme_options[sidebar_layout]', array(
	'label'               => esc_html__( 'Sidebar Layout', 'uni-education' ),
	'section'             => 'uni_education_blog_section',
	'choices'			  => uni_education_sidebar_position(),
) ) );

// column control and setting
$wp_customize->add_setting( 'uni_education_theme_options[column_type]', array(
	'default'          	=> uni_education_theme_option( 'column_type' ),
	'sanitize_callback' => 'uni_education_sanitize_select',
) );

$wp_customize->add_control( 'uni_education_theme_options[column_type]', array(
	'label'             => esc_html__( 'Column Layout', 'uni-education' ),
	'section'           => 'uni_education_blog_section',
	'type'				=> 'select',
	'choices'			=> array( 
		'column-1' 		=> esc_html__( 'One Column', 'uni-education' ),
		'column-2' 		=> esc_html__( 'Two Column', 'uni-education' ),
	),
) );

// excerpt count control and setting
$wp_customize->add_setting( 'uni_education_theme_options[excerpt_count]', array(
	'default'          	=> uni_education_theme_option( 'excerpt_count' ),
	'sanitize_callback' => 'uni_education_sanitize_number_range',
	'validate_callback' => 'uni_education_validate_excerpt_count',
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'uni_education_theme_options[excerpt_count]', array(
	'label'             => esc_html__( 'Excerpt Length', 'uni-education' ),
	'description'       => esc_html__( 'Note: Min 1 & Max 50.', 'uni-education' ),
	'section'           => 'uni_education_blog_section',
	'type'				=> 'number',
	'input_attrs'		=> array(
		'min'	=> 1,
		'max'	=> 50,
		),
) );

// pagination control and setting
$wp_customize->add_setting( 'uni_education_theme_options[pagination_type]', array(
	'default'          	=> uni_education_theme_option( 'pagination_type' ),
	'sanitize_callback' => 'uni_education_sanitize_select',
) );

$wp_customize->add_control( 'uni_education_theme_options[pagination_type]', array(
	'label'             => esc_html__( 'Pagination Type', 'uni-education' ),
	'section'           => 'uni_education_blog_section',
	'type'				=> 'select',
	'choices'			=> array( 
		'default' 		=> esc_html__( 'Default', 'uni-education' ),
		'numeric' 		=> esc_html__( 'Numeric', 'uni-education' ),
	),
) );

// Archive date meta setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[show_date]', array(
	'default'           => uni_education_theme_option( 'show_date' ),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[show_date]', array(
	'label'             => esc_html__( 'Show Date', 'uni-education' ),
	'section'           => 'uni_education_blog_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// Archive category meta setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[show_category]', array(
	'default'           => uni_education_theme_option( 'show_category' ),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[show_category]', array(
	'label'             => esc_html__( 'Show Category', 'uni-education' ),
	'section'           => 'uni_education_blog_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// Archive author meta setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[show_author]', array(
	'default'           => uni_education_theme_option( 'show_author' ),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[show_author]', array(
	'label'             => esc_html__( 'Show Author', 'uni-education' ),
	'section'           => 'uni_education_blog_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );

// Archive comment meta setting and control.
$wp_customize->add_setting( 'uni_education_theme_options[show_comment]', array(
	'default'           => uni_education_theme_option( 'show_comment' ),
	'sanitize_callback' => 'uni_education_sanitize_switch',
) );

$wp_customize->add_control( new Uni_Education_Switch_Control( $wp_customize, 'uni_education_theme_options[show_comment]', array(
	'label'             => esc_html__( 'Show Comment', 'uni-education' ),
	'section'           => 'uni_education_blog_section',
	'on_off_label' 		=> uni_education_show_options(),
) ) );