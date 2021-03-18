<?php
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// Load TPro file.
require_once 'testimonial-free.php';

$setting_options         = get_option( 'sp_testimonial_pro_options' );
$testimonial_data_remove = isset( $setting_options['testimonial_data_remove'] ) ? $setting_options['testimonial_data_remove'] : false;
if ( $testimonial_data_remove ) {

	// Delete testimonials and shortcodes.
	global $wpdb;
	$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'spt_testimonial'" );
	$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'spt_shortcodes'" );
	$wpdb->query( 'DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)' );
	$wpdb->query( 'DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)' );
	$wpdb->query( "DELETE FROM wp_term_taxonomy WHERE taxonomy = 'testimonial_cat' AND term_taxonomy_id NOT IN (SELECT term_taxonomy_id FROM wp_term_relationships)" );
	$wpdb->query( 'DELETE FROM wp_terms WHERE term_id NOT IN (SELECT term_id FROM wp_term_taxonomy)' );

	// Remove Option.
	delete_option( 'sp_testimonial_pro_options' );
	delete_option( 'testimonial_cat_children' );

	// Site options in Multisite.
	delete_site_option( 'sp_testimonial_pro_options' );
	delete_site_option( 'testimonial_cat_children' );

}
