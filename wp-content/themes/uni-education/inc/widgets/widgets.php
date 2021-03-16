<?php
/**
 * Register Widgets
 *
 * @package uni_education
 */

/**
 * Load dynamic logic for the widgets.
 */
function uni_education_widget_js( $hook ) {
	if ( 'widgets.php' === $hook ) :
		wp_enqueue_script( 'media-upload' );
	   	wp_enqueue_media();
	   	
		// Choose from select jquery.
		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome' . uni_education_min() . '.css' );
		wp_enqueue_style( 'simple-iconpicker', get_template_directory_uri() . '/assets/css/simple-iconpicker' . uni_education_min() . '.css' );
		wp_enqueue_style( 'uni-education-admin-style', get_template_directory_uri() . '/assets/css/admin' . uni_education_min() . '.css' );
		wp_enqueue_style( 'jquery-chosen', get_template_directory_uri() . '/assets/css/chosen' . uni_education_min() . '.css' );
		wp_enqueue_script( 'jquery-simple-iconpicker', get_template_directory_uri() . '/assets/js/simple-iconpicker' . uni_education_min() . '.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'jquery-chosen', get_template_directory_uri() . '/assets/js/chosen' . uni_education_min() . '.js', array( 'jquery' ), '1.4.2', true );
		wp_enqueue_script( 'uni-education-admin-script', get_template_directory_uri() . '/assets/js/admin' . uni_education_min() . '.js', array( 'jquery', 'jquery-chosen', 'jquery-simple-iconpicker' ), '1.0.0', true );
	endif;

}
add_action( 'admin_enqueue_scripts', 'uni_education_widget_js' );

/*
 * Add introduction widget
 */
require get_template_directory() . '/inc/widgets/introduction-widget.php';

/*
 * Add featured widget
 */
require get_template_directory() . '/inc/widgets/featured-widget.php';

/*
 * Add portfolio widget
 */
require get_template_directory() . '/inc/widgets/portfolio-widget.php';

/*
 * Add recent widget
 */
require get_template_directory() . '/inc/widgets/recent-widget.php';

/*
 * Add service widget
 */
require get_template_directory() . '/inc/widgets/service-widget.php';

/*
 * Add short call to action widget
 */
require get_template_directory() . '/inc/widgets/short-cta-widget.php';

/*
 * Add client widget
 */
require get_template_directory() . '/inc/widgets/client-widget.php';

/*
 * Add event widget
 */
require get_template_directory() . '/inc/widgets/event-widget.php';

/*
 * Add social widget
 */
require get_template_directory() . '/inc/widgets/social-widget.php';

/**
 * Register widgets
 */
function uni_education_register_widgets() {
	
	register_widget( 'Uni_Education_Introduction_Widget' );
	
	register_widget( 'Uni_Education_Featured_Widget' );

	register_widget( 'Uni_Education_Portfolio_Widget' );

	register_widget( 'Uni_Education_Recent_Widget' );

	register_widget( 'Uni_Education_Service_Widget' );

	register_widget( 'Uni_Education_Short_Cta_Widget' );

	register_widget( 'Uni_Education_Client_Widget' );

	register_widget( 'Uni_Education_Event_Widget' );
	
	register_widget( 'Uni_Education_Social_Links_Widget' );
}
add_action( 'widgets_init', 'uni_education_register_widgets' );