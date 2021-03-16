<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package uni_education
 */

/**
 * uni_education_site_content_ends_action hook
 *
 * @hooked uni_education_site_content_ends -  10
 *
 */
do_action( 'uni_education_site_content_ends_action' );

/**
 * uni_education_footer_start_action hook
 *
 * @hooked uni_education_footer_start -  10
 *
 */
do_action( 'uni_education_footer_start_action' );

/**
 * uni_education_site_info_action hook
 *
 * @hooked uni_education_site_info -  10
 *
 */
do_action( 'uni_education_site_info_action' );

/**
 * uni_education_footer_ends_action hook
 *
 * @hooked uni_education_footer_ends -  10
 * @hooked uni_education_slide_to_top -  20
 *
 */
do_action( 'uni_education_footer_ends_action' );

/**
 * uni_education_page_ends_action hook
 *
 * @hooked uni_education_page_ends -  10
 *
 */
do_action( 'uni_education_page_ends_action' );

wp_footer();

/**
 * uni_education_body_html_ends_action hook
 *
 * @hooked uni_education_body_html_ends -  10
 *
 */
do_action( 'uni_education_body_html_ends_action' );
