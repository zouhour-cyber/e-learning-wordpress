<?php
/**
 * Slider hook
 *
 * @package uni_education
 */

if ( ! function_exists( 'uni_education_add_slider_section' ) ) :
    /**
    * Add slider section
    *
    *@since Uni Education 1.0.0
    */
    function uni_education_add_slider_section() {

        // Check if slider is enabled on frontpage
        $slider_enable = apply_filters( 'uni_education_section_status', 'enable_slider', 'slider_entire_site' );

        if ( ! $slider_enable )
            return false;

        // Get slider section details
        $section_details = array();
        $section_details = apply_filters( 'uni_education_filter_slider_section_details', $section_details );

        if ( empty( $section_details ) ) 
            return;

        // Render slider section now.
        uni_education_render_slider_section( $section_details );
    }
endif;
add_action( 'uni_education_primary_content_action', 'uni_education_add_slider_section', 10 );


if ( ! function_exists( 'uni_education_get_slider_section_details' ) ) :
    /**
    * slider section details.
    *
    * @since Uni Education 1.0.0
    * @param array $input slider section details.
    */
    function uni_education_get_slider_section_details( $input ) {

        $content = array();
        $page_ids = array();

        for ( $i = 1; $i <= 5; $i++ )  :
            $page_ids[] = uni_education_theme_option( 'slider_content_page_' . $i );;
        endfor;
        
        $args = array(
            'post_type'         => 'page',
            'post__in'          => ( array ) $page_ids,
            'posts_per_page'    => 5,
            'orderby'           => 'post__in',
            );                    

        // Run The Loop.
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) : 
            while ( $query->have_posts() ) : $query->the_post();
                $page_post['title']     = get_the_title();
                $page_post['url']       = get_the_permalink();
                $page_post['excerpt']   = uni_education_trim_content( 20 );
                $page_post['image']     = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_id(), 'full' ) : '';

                // Push to the main array.
                array_push( $content, $page_post );
            endwhile;
        endif;
        wp_reset_postdata();
            
        if ( ! empty( $content ) )
            $input = $content;
       
        return $input;
    }
endif;
// slider section content details.
add_filter( 'uni_education_filter_slider_section_details', 'uni_education_get_slider_section_details' );


if ( ! function_exists( 'uni_education_render_slider_section' ) ) :
  /**
   * Start slider section
   *
   * @return string slider content
   * @since Uni Education 1.0.0
   *
   */
   function uni_education_render_slider_section( $content_details = array() ) {
        if ( empty( $content_details ) )
            return;

        $slider_control = uni_education_theme_option( 'slider_arrow' );
        $slider_auto_slide = uni_education_theme_option( 'slider_auto_slide' );
        $slider_btn_label = uni_education_theme_option( 'slider_btn_label', esc_html__( 'Learn More', 'uni-education' ) );
        ?>
    	<div id="custom-header">
            <div class="section-content banner-slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "infinite": true, "speed": 1200, "dots": false, "arrows":<?php echo $slider_control ? 'true' : 'false'; ?>, "autoplay": <?php echo $slider_auto_slide ? 'true' : 'false'; ?>, "fade": true, "draggable": true }'>
                <?php foreach ( $content_details as $content ) : ?>
                    <div class="custom-header-content-wrapper slide-item">
                        <?php if ( ! empty( $content['image'] ) ) : ?>
                            <img src="<?php echo esc_url( $content['image'] ); ?>" alt="<?php echo esc_attr( $content['title'] ); ?>">
                        <?php endif; ?>
                        <div class="overlay"></div>
                        <div class="wrapper">
                            <div class="custom-header-content">
                                <?php if ( ! empty( $content['title'] ) ) : ?>
                                    <h2><a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $content['title'] ); ?></a></h2>
                                <?php endif; 

                                if ( ! empty( $content['excerpt'] ) ) : ?>
                                    <p><?php echo wp_kses_post( $content['excerpt'] ); ?></p>
                                <?php endif; 

                                if ( ! empty( $slider_btn_label ) ) : ?>
                                    <div class="read-more">
                                        <a href="<?php echo esc_url( $content['url'] ); ?>"><?php echo esc_html( $slider_btn_label ); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div><!-- .custom-header-content -->
                        </div>
                    </div><!-- .custom-header-content-wrapper -->
                <?php endforeach; ?>
            </div><!-- .wrapper -->
        </div><!-- #custom-header -->
    <?php 
    }
endif;