<?php
/**
 * Template Name: Home Page
 * The template for displaying home page.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package uni_education
 */

get_header(); 

if ( is_active_sidebar( 'home-page-area' ) ) :
	dynamic_sidebar( 'home-page-area' );
else : ?>
	<div class="wrapper default-intro">
		<?php the_widget( 'WP_Widget_Text', $instance = array( 'title' => esc_html__( 'Welcome to Uni Education Theme', 'uni-education' ), 'text' => esc_html__( 'Uni Education provides you the best platform for your business site. Customize Slider from Customizer Theme Options. Customize Home Page by adding widgets that are compatible for home page. Add Widgets that are prefixed by ST.', 'uni-education' ) ) ); ?>
	</div>
<?php endif;

get_footer();
