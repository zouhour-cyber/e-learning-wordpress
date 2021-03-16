<?php
/**
 * Event Widget
 *
 * @package uni_education
 */

if ( ! class_exists( 'Uni_Education_Event_Widget' ) ) :

     
    class Uni_Education_Event_Widget extends WP_Widget {
        /**
         * Sets up the widgets name etc
         */
        public function __construct() {
            $st_widget_event = array(
                'classname'   => 'event_widget',
                'description' => esc_html__( 'Compatible Area: Homepage', 'uni-education' ),
            );
            parent::__construct( 'uni_education_event_widget', esc_html__( 'ST: Event Widget', 'uni-education' ), $st_widget_event );
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
            $background_image  = isset( $instance['event_image_url'] ) ? $instance['event_image_url'] : '';
            $content_details = array();

            $cat_id = ! empty( $instance['cat_id'] ) ? $instance['cat_id'] : '';
            $query_args = array(
                'post_type'         => 'post',
                'posts_per_page'    => 3,
                'cat'               => absint( $cat_id ),
                'ignore_sticky_posts' => true,
                ); 

            $query = new WP_Query( $query_args );

            echo $args['before_widget'];
            ?>

                <div id="event-posts" class="page-section relative">
                    <div class="wrapper">
                        <?php if ( ! empty( $title ) ) : ?>
                            <div class="section-header align-center">
                                <?php echo $args['before_title'] . esc_html( $title ) . $args['after_title']; ?>
                            </div><!-- .section-header -->
                        <?php endif; ?>

                        <div class="section-content">
                            <?php if ( ! empty( $background_image ) ) : ?>
                                <div class="event-background">
                                    <img src="<?php echo esc_url( $background_image ); ?>">
                                </div>
                            <?php endif; ?>

                            <div class="event-container">
                                <?php if ( $query -> have_posts() ) : 
                                    while ( $query -> have_posts() ) : $query -> the_post(); ?>
                                        <article class="hentry">
                                            <div class="post-wrapper">
                                                <div class="entry-container">
                                                    <header class="entry-header">
                                                        <h2 class="entry-title">
                                                            <a href="<?php the_permalink(); ?>">
                                                                <i class="fa fa-check"></i>
                                                                <?php the_title(); ?>
                                                            </a>
                                                        </h2>
                                                    </header>
                                                    <div class="entry-content">
                                                        <?php echo esc_html( uni_education_trim_content( 20 ) ); ?>
                                                    </div><!-- .entry-content -->
                                                </div><!-- .entry-container -->
                                            </div><!-- .post-wrapper -->
                                        </article>
                                    <?php endwhile; 
                                endif;
                                wp_reset_postdata(); 
                                ?>
                            </div><!-- .event-container -->
                        </div><!-- .section-content -->
                    </div><!-- .wrapper -->
                </div><!-- #event-posts -->

            <?php
            echo $args['after_widget'];
        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form( $instance ) {
            $title      = isset( $instance['title'] ) ? ( $instance['title'] ) : esc_html__( 'Event', 'uni-education' );
            $cat_id     = isset( $instance['cat_id'] ) ? $instance['cat_id'] : '';
            $event_image_url  = isset( $instance['event_image_url'] ) ? $instance['event_image_url'] : '';

            $category_options = uni_education_category_choices();
            ?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'uni-education' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'cat_id' ) ); ?>"><?php echo esc_html__( 'Select Category', 'uni-education' ); ?></label>
                <select class="uni-education-widget-chosen-select widfat" id="<?php echo esc_attr( $this->get_field_id( 'cat_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cat_id' ) ); ?>">
                    <?php foreach ( $category_options as $category_option => $value ) : ?>
                        <option value="<?php echo absint( $category_option ); ?>" <?php selected( $cat_id, $category_option, $echo = true ) ?> ><?php echo esc_html( $value ); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>

            <div>
                <label for="<?php echo esc_attr( $this->get_field_id( 'event_image_url' ) ); ?>"><?php esc_html_e( 'Author Image', 'uni-education' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'event_image_url' ); ?>" name="<?php echo $this->get_field_name( 'event_image_url' ); ?>" type="text" value="<?php echo esc_url( $event_image_url ); ?>" />
                <button class="button upload_image_button" style="margin:15px 0 0;"><?php esc_html_e( 'Upload Image', 'uni-education' ); ?></button>
                <p><small><?php esc_html_e( 'Note: Recomended size 500x500 px. When you change the image, please make some changes in any other input field to save changes.', 'uni-education' ) ?></small><p>

                <?php
                $full_event_image_url = '';
                if ( ! empty( $event_image_url ) ) {
                    $full_event_image_url = $event_image_url;
                }

                $wrap_style = '';
                if ( empty( $full_event_image_url ) ) {
                    $wrap_style = ' style="display:none;" ';
                }
                ?>
                <div class="tpiw-preview-wrap" <?php echo esc_attr( $wrap_style ); ?>>
                    <img src="<?php echo esc_url( $full_event_image_url ); ?>" alt="<?php esc_attr_e('Preview', 'uni-education'); ?>" style="max-width: 100%;"  />
                </div><!-- .tpiw-preview-wrap -->

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
            $instance['event_image_url'] = esc_url_raw( $new_instance['event_image_url'] );
            $instance['cat_id']         = uni_education_sanitize_category( $new_instance['cat_id'] );
           
            return $instance;
        }
    }
endif;
