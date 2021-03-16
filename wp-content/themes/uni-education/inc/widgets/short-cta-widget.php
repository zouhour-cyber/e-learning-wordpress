<?php
/**
 * Short Call to Action Widget
 *
 * @package uni_education
 */

if ( ! class_exists( 'Uni_Education_Short_Cta_Widget' ) ) :

     
    class Uni_Education_Short_Cta_Widget extends WP_Widget {
        /**
         * Sets up the widgets name etc
         */
        public function __construct() {
            $st_widget_cta = array(
                'classname'   => 'short_cta_widget',
                'description' => esc_html__( 'Compatible Area: Homepage, About Page, Service Page, Sidebar', 'uni-education' ),
            );
            parent::__construct( 'uni_education_short_cta_widget', esc_html__( 'ST: Short Call to Action Widget', 'uni-education' ), $st_widget_cta );
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
            $read_more  = isset( $instance['read_more'] ) ? $instance['read_more'] : esc_html__( 'Read More', 'uni-education' );
            $content_details = array();

            $page_id  = isset( $instance['page_id'] ) ? $instance['page_id'] : '';
            $query_args = array(
                'post_type' => 'page',
                'page_id' => absint( $page_id ),
                'posts_per_page' => 1,
            );
               
            $query = new WP_Query( $query_args );

            echo $args['before_widget'];
            ?>
                <?php if ( $query -> have_posts() ) : while ( $query -> have_posts() ) : $query -> the_post();  ?>
                    <div class="page-section cta-section short-cta-section relative">
                        <div class="wrapper">
                            <?php if ( ! empty( $title ) ) : ?>
                                <div class="section-header add-separator">
                                    <?php echo $args['before_title'] . esc_html( $title ) . $args['after_title']; ?>
                                </div><!-- .section-header -->
                            <?php endif; ?>

                            <div class="read-more">
                                <a href="<?php the_permalink(); ?>"><?php echo esc_html( $read_more ); ?></a>
                            </div>

                        </div><!-- .wrapper -->
                    </div><!-- #cta -->
                <?php endwhile; endif;
                wp_reset_postdata(); ?>

            <?php
            echo $args['after_widget'];
        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form( $instance ) {
            $title       = isset( $instance['title'] ) ? ( $instance['title'] ) : esc_html__( 'Short Call to Action', 'uni-education' );
            $page_id        = isset( $instance['page_id'] ) ? $instance['page_id'] : '';
            $read_more  = isset( $instance['read_more'] ) ? $instance['read_more'] : esc_html__( 'Read More', 'uni-education' );

            $page_options = uni_education_page_choices();
            ?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'uni-education' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'page_id' ) ); ?>"><?php esc_html_e( 'Select Page', 'uni-education' ); ?></label>
                <select class="uni-education-widget-chosen-select widfat" id="<?php echo esc_attr( $this->get_field_id( 'page_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'page_id' ) ); ?>">
                    <?php foreach ( $page_options as $page_option => $value ) : ?>
                        <option value="<?php echo absint( $page_option ); ?>" <?php selected( $page_id, $page_option, $echo = true ) ?> ><?php echo esc_html( $value ); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'read_more' ) ); ?>"><?php esc_html_e( 'Read More Text:', 'uni-education' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('read_more') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'read_more' ) ); ?>" type="text" value="<?php echo esc_attr( $read_more ); ?>" />
            </p>

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
            $instance['page_id']        = uni_education_sanitize_page_post( $new_instance['page_id'] );
            $instance['read_more']      = sanitize_text_field( $new_instance['read_more'] );
           
            return $instance;
        }
    }
endif;
