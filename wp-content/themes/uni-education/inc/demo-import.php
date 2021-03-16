<?php
/**
 * demo import
 *
 * @package uni_education_pro
 */

/**
 * Imports predefine demos.
 * @return [type] [description]
 */
function uni_education_intro_text( $default_text ) {
    $default_text .= sprintf( '<p class="about-description">%1$s <a href="%2$s">%3$s</a></p>', esc_html__( 'Demo content files for Uni Education Theme.', 'uni-education' ),
    esc_url( 'https://drive.google.com/open?id=1m8WoE92B9Y2PhC_lzMXxF-8pyM08x_3S' ), esc_html__( 'Click here to download Demo Data', 'uni-education' ) );

    return $default_text;
}
add_filter( 'pt-ocdi/plugin_intro_text', 'uni_education_intro_text' );
