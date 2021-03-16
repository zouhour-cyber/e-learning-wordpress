<?php
/**
 * Uni Education Theme Customizer
 *
 * @package uni_education
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function uni_education_customize_register( $wp_customize ) {
	// Load custom control functions.
	require get_template_directory() . '/inc/customizer/controls.php';

	// Load validation functions.
	require get_template_directory() . '/inc/customizer/validate.php';

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'uni_education_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'uni_education_customize_partial_blogdescription',
		) );
	}

	// Register custom section types.
	$wp_customize->register_section_type( 'Uni_Education_Customize_Section_Upsell' );

	// Register sections.
	$wp_customize->add_section(
		new Uni_Education_Customize_Section_Upsell(
			$wp_customize,
			'theme_upsell',
			array(
				'title'    => esc_html__( 'Uni Education Pro', 'uni-education' ),
				'pro_text' => esc_html__( 'Buy Pro', 'uni-education' ),
				'pro_url'  => 'http://www.sharkthemes.com/downloads/uni-education-pro/',
				'priority'  => 10,
			)
		)
	);

	// Add panel for common Home Page Settings
	$wp_customize->add_panel( 'uni_education_theme_options_panel' , array(
	    'title'      => esc_html__( 'Theme Options','uni-education' ),
	    'description'=> esc_html__( 'Uni Education Theme Options.', 'uni-education' ),
	    'priority'   => 100,
	) );

	// topbar settings
	require get_template_directory() . '/inc/customizer/topbar-customizer.php';

	// slider settings
	require get_template_directory() . '/inc/customizer/slider-customizer.php';

	// footer settings
	require get_template_directory() . '/inc/customizer/footer-customizer.php';
	
	// blog/archive settings
	require get_template_directory() . '/inc/customizer/blog-customizer.php';

	// single settings
	require get_template_directory() . '/inc/customizer/single-customizer.php';

	// page settings
	require get_template_directory() . '/inc/customizer/page-customizer.php';

	// global settings
	require get_template_directory() . '/inc/customizer/global-customizer.php';

}
add_action( 'customize_register', 'uni_education_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function uni_education_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function uni_education_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function uni_education_customize_preview_js() {
	wp_enqueue_script( 'uni-education-customizer', get_template_directory_uri() . '/assets/js/customizer' . uni_education_min() . '.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'uni_education_customize_preview_js' );

/**
 * Load dynamic logic for the customizer controls area.
 */
function uni_education_customize_control_js() {
	// Choose from select jquery.
	wp_enqueue_style( 'jquery-chosen', get_template_directory_uri() . '/assets/css/chosen' . uni_education_min() . '.css' );
	wp_enqueue_script( 'jquery-chosen', get_template_directory_uri() . '/assets/js/chosen' . uni_education_min() . '.js', array( 'jquery' ), '1.4.2', true );

	// admin script
	wp_enqueue_style( 'uni-education-admin-style', get_template_directory_uri() . '/assets/css/admin' . uni_education_min() . '.css' );
	wp_enqueue_script( 'uni-education-admin-script', get_template_directory_uri() . '/assets/js/admin' . uni_education_min() . '.js', array( 'jquery', 'jquery-chosen' ), '1.0.0', true );

	wp_enqueue_style( 'uni-education-customizer-style', get_template_directory_uri() . '/assets/css/customizer' . uni_education_min() . '.css' );
	wp_enqueue_script( 'uni-education-customizer-controls', get_template_directory_uri() . '/assets/js/customizer-controls' . uni_education_min() . '.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'customize_controls_enqueue_scripts', 'uni_education_customize_control_js' );
