<?php
/**
 * Service Widget
 *
 * @package uni_education
 */

if ( ! class_exists( 'Uni_Education_Service_Widget' ) ) :

     
    class Uni_Education_Service_Widget extends WP_Widget {
        /**
         * Sets up the widgets name etc
         */
        public function __construct() {
            $st_widget_service = array(
                'classname'   => 'service_widget',
                'description' => esc_html__( 'Compatible Area: Homepage', 'uni-education' ),
            );
            parent::__construct( 'uni_education_service_widget', esc_html__( 'ST: Service Widget', 'uni-education' ), $st_widget_service );
        }

        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
        public function widget( $args, $instance ) {
            // outputs the content of the widget
            if ( ! isset( $args['widget_id'] ) ) {
                $args['widget_id'] = $this->id;
            }

            $title   = ( ! empty( $instance['title'] ) ) ? ( $instance['title'] ) : '';
            $title   = apply_filters( 'widget_title', $title, $instance, $this->id_base );
            $content_type  = isset( $instance['content_type'] ) ? $instance['content_type'] : 'page';

            switch ($content_type) {
                case 'page':
                    $page_ids = array();
                    $icons = array();
                    for ( $i = 1; $i <= 6; $i++ ) :
                        if ( ! empty( $instance['page_id_' . $i] ) ) :
                            $page_ids[]  = $instance['page_id_' . $i];
                            $icons[]     = ! empty( $instance['service_page_icon_' . $i] ) ? $instance['service_page_icon_' . $i] : 'fa-cogs';
                        endif;
                    endfor;
                    $query_args = array(
                    'post_type'         => 'page',
                    'post__in'          => ( array ) $page_ids,
                    'posts_per_page'    => 6,
                    'orderby'           => 'post__in',
                    ); 
                break;

                case 'post':
                    $post_ids = array();
                    $icons = array();
                    for ( $i = 1; $i <= 6; $i++ ) :
                        if ( ! empty( $instance['post_id_' . $i] ) ) :
                            $post_ids[]  = $instance['post_id_' . $i];
                            $icons[]     = ! empty( $instance['service_post_icon_' . $i] ) ? $instance['service_post_icon_' . $i] : 'fa-cogs';
                        endif;
                    endfor;
                    $query_args = array(
                    'post_type'         => 'post',
                    'post__in'          => ( array ) $post_ids,
                    'posts_per_page'    => 6,
                    'orderby'           => 'post__in',
                    'ignore_sticky_posts' => true,
                    ); 
                break;
                
                default:
                break;
            }

            $query = new WP_Query( $query_args );
            $i = 0;

            echo $args['before_widget'];
            ?>
                <div class="our-services page-section relative center-align">
                    <div class="wrapper">
                        <?php if ( ! empty( $title ) ) : ?>
                            <div class="section-header align-center">
                                <?php echo $args['before_title'] . esc_html( $title ) . $args['after_title']; ?>
                            </div><!-- .section-header -->
                        <?php endif; ?>

                        <div class="section-content column-3">
                            <?php if ( $query -> have_posts() ) : while ( $query -> have_posts() ) : $query -> the_post(); ?>
                                <article class="hentry">
                                    <div class="post-wrapper">
                                        <?php $icon = ! empty( $icons[$i] ) ? $icons[$i] : 'fa-cogs'; ?>
                                        <div class="service">
                                            <a href="<?php the_permalink(); ?>">
                                                <i class="fa <?php echo esc_attr( $icon ); ?>" ></i>
                                            </a>
                                        </div><!-- .service -->

                                        <div class="entry-container">
                                            <header class="entry-header">
                                                <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                            </header>
                                            <div class="entry-content">
                                                <?php echo esc_html( uni_education_trim_content( 15 ) ); ?>
                                            </div><!-- .entry-content -->
                                        </div><!-- .entry-container -->

                                    </div><!-- .post-wrapper -->
                                </article>
                            <?php $i++;
                            endwhile; endif;
                            wp_reset_postdata(); ?>
                        </div><!-- .section-content -->
                    </div><!-- .wrapper -->
                </div><!-- #gallery -->

            <?php
            echo $args['after_widget'];
        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form( $instance ) {
            $title      = isset( $instance['title'] ) ? ( $instance['title'] ) : esc_html__( 'Service', 'uni-education' );
            $content_type   = isset( $instance['content_type'] ) ? $instance['content_type'] : 'page';
            $page_options = uni_education_page_choices();
            $post_options = uni_education_post_choices();
            $content_type_options = array(
                'page'      => esc_html__( 'Page', 'uni-education' ),
                'post'      => esc_html__( 'Post', 'uni-education' ),
            );
            ?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'uni-education' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>


            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'content_type' ) ); ?>"><?php esc_html_e( 'Content Type', 'uni-education' ); ?></label>
                <select class="content-type widfat" id="<?php echo esc_attr( $this->get_field_id( 'content_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'content_type' ) ); ?>" style="width:100%">
                    <?php foreach ( $content_type_options as $key => $value ) : ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $content_type, $key, $echo = true ) ?> ><?php echo esc_html( $value ); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>

            <hr style = "height: 2px;">

            <div class="page <?php echo ( 'page' == $content_type ) ? 'block' : 'none' ?>" >
                <?php for ( $i = 1; $i <= 6; $i++ ) : 
                    $service_page_icon  = isset( $instance['service_page_icon_' . $i] ) ? $instance['service_page_icon_' . $i] : 'fa-anchor';
                    $page_id = isset( $instance['page_id_' . $i] ) ? $instance['page_id_' . $i] : ''; ?>
                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( 'page_id_' . $i ) ); ?>"><?php printf( esc_html__( 'Select Page %d', 'uni-education' ), $i ); ?></label>
                        <select class="uni-education-widget-chosen-select widfat" id="<?php echo esc_attr( $this->get_field_id( 'page_id_' . $i ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'page_id_' . $i ) ); ?>">
                            <?php foreach ( $page_options as $page_option => $value ) : ?>
                                <option value="<?php echo absint( $page_option ); ?>" <?php selected( $page_id, $page_option, $echo = true ) ?> ><?php echo esc_html( $value ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>

                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( 'service_page_icon_' . $i ) ); ?>"><?php printf( esc_html__( 'Select Icon %d', 'uni-education' ), $i ); ?></label>
                        <input class="widefat uni-education-icon-picker" id="<?php echo esc_attr( $this->get_field_id('service_page_icon_' . $i) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'service_page_icon_' . $i ) ); ?>" type="text" value="<?php echo esc_attr( $service_page_icon ); ?>" />
                    </p>

                    <hr>
                <?php endfor; ?>
            </div>
            
            <div class="post <?php echo ( 'post' == $content_type ) ? 'block' : 'none' ?>" >
               <?php for ( $i = 1; $i <= 6; $i++ ) : 
                    $service_post_icon  = isset( $instance['service_post_icon_' . $i] ) ? $instance['service_post_icon_' . $i] : 'fa-anchor';
                    $post_id = isset( $instance['post_id_' . $i] ) ? $instance['post_id_' . $i] : ''; ?>
                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( 'post_id_' . $i ) ); ?>"><?php printf( esc_html__( 'Select Post %d', 'uni-education' ), $i ); ?></label>
                        <select class="uni-education-widget-chosen-select widfat" id="<?php echo esc_attr( $this->get_field_id( 'post_id_' . $i ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_id_' . $i ) ); ?>">
                            <?php foreach ( $post_options as $post_option => $value ) : ?>
                                <option value="<?php echo absint( $post_option ); ?>" <?php selected( $post_id, $post_option, $echo = true ) ?> ><?php echo esc_html( $value ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>

                    <p>
                        <label for="<?php echo esc_attr( $this->get_field_id( 'service_post_icon_' . $i ) ); ?>"><?php printf( esc_html__( 'Select Icon %d', 'uni-education' ), $i ); ?></label>
                        <input class="widefat uni-education-icon-picker" id="<?php echo esc_attr( $this->get_field_id('service_post_icon_' . $i) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'service_post_icon_' . $i ) ); ?>" type="text" value="<?php echo esc_attr( $service_post_icon ); ?>" />
                    </p>

                    <hr>
                <?php endfor; ?>
            </div>

        <?php }

        /**
        * Processing widget options on save
        *
        * @param array $new_instance The new options
        * @param array $old_instance The previous options
        */
        public function update( $new_instance, $old_instance ) {
            // processes widget options to be saved
            $instance                   = $old_instance;
            $instance['title']          = sanitize_text_field( $new_instance['title'] );
            $instance['content_type']   = sanitize_key( $new_instance['content_type'] );
            for ( $i = 1; $i <= 6; $i++ ) :
                $instance['service_page_icon_' . $i]  = sanitize_text_field( $new_instance['service_page_icon_' . $i] );
                $instance['service_post_icon_' . $i]  = sanitize_text_field( $new_instance['service_post_icon_' . $i] );
                $instance['page_id_' . $i]   = uni_education_sanitize_page_post( $new_instance['page_id_' . $i] );
                $instance['post_id_' . $i]   = uni_education_sanitize_page_post( $new_instance['post_id_' . $i] );
            endfor;
           
            return $instance;
        }
    }
endif;
