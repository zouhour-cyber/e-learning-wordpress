<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package uni_education
 */

/**
 * uni_education_doctype_action hook
 *
 * @hooked uni_education_doctype -  10
 *
 */
do_action( 'uni_education_doctype_action' );

/**
 * uni_education_head_action hook
 *
 * @hooked uni_education_head -  10
 *
 */
do_action( 'uni_education_head_action' );

/**
 * uni_education_body_start_action hook
 *
 * @hooked uni_education_body_start -  10
 *
 */
do_action( 'uni_education_body_start_action' );
 
/**
 * uni_education_page_start_action hook
 *
 * @hooked uni_education_page_start -  10
 * @hooked uni_education_loader -  20
 *
 */
do_action( 'uni_education_page_start_action' );

/**
 * uni_education_header_start_action hook
 *
 * @hooked uni_education_header_start -  10
 *
 */
do_action( 'uni_education_header_start_action' );

/**
 * uni_education_site_branding_action hook
 *
 * @hooked uni_education_site_branding -  10
 *
 */
do_action( 'uni_education_site_branding_action' );

/**
 * uni_education_primary_nav_action hook
 *
 * @hooked uni_education_primary_nav -  10
 *
 */
do_action( 'uni_education_primary_nav_action' );

/**
 * uni_education_header_ends_action hook
 *
 * @hooked uni_education_header_ends -  10
 *
 */
do_action( 'uni_education_header_ends_action' );

/**
 * uni_education_site_content_start_action hook
 *
 * @hooked uni_education_site_content_start -  10
 *
 */
do_action( 'uni_education_site_content_start_action' );

/**
 * uni_education_primary_content_action hook
 *
 * @hooked uni_education_add_slider_section -  10
 *
 */
do_action( 'uni_education_primary_content_action' );