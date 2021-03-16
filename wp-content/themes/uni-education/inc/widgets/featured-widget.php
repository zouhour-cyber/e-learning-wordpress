<?php
/**
 * Featured Widget
 *
 * @package uni_education
 */

if ( ! class_exists( 'Uni_Education_Featured_Widget' ) ) :

     
    class Uni_Education_Featured_Widget extends WP_Widget {
        /**
         * Sets up the widgets name etc
         */
        public function __construct() {
            $st_widget_featured = array(
                'classname'   => 'featured_widget',
                'description' => esc_html__( 'Compatible Area: Homepage, Sidebar, Footer', 'uni-education' ),
            );
            parent::__construct( 'uni_education_featured_widget', esc_html__( 'ST: Featured Widget', 'uni-education' ), $st_widget_featured );
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

            $page_ids = array();
            for ( $i = 1; $i <= 3; $i++ ) :
                if ( ! empty( $instance['page_id_' . $i] ) ) :
                    $page_ids[]  = $instance['page_id_' . $i];
                endif;
            endfor;

            $query_args = array(
            'post_type'         => 'page',
            'post__in'          => ( array ) $page_ids,
            'posts_per_page'    => 3,
            'orderby'           => 'post__in',
            ); 

            $query = new WP_Query( $query_args );

            echo $args['before_widget'];
            ?>

                <div id="featured-posts" class="page-section relative">
                    <div class="wrapper">
                        <?php if ( ! empty( $title ) ) : ?>
                            <div class="section-header align-center">
                                <?php if ( ! empty( $title ) ) :
                                    echo $args['before_title'] . esc_html( $title ) . $args['after_title']; ?>
                                <?php endif; ?>
                            </div><!-- .section-header -->
                        <?php endif; ?>

                        <div class="section-content column-3">
                            <?php if ( $query -> have_posts() ) : 
                                while ( $query -> have_posts() ) : $query -> the_post(); ?>
                                    <article class="hentry">
                                        <div class="post-wrapper">
                                            <?php if ( has_post_thumbnail() ) : ?>
                                                <div class="featured-image">
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
                                                    </a>
                                                </div><!-- .featured-image -->
                                            <?php endif; ?>

                                            <div class="entry-container">
                                                <header class="entry-header">
                                                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                                </header>
                                                <div class="entry-content">
                                                    <?php echo esc_html( uni_education_trim_content( 20 ) ); ?>
                                                </div><!-- .entry-content -->
                                                <a class="more-btn" href="<?php the_permalink(); ?>">
                                                    <?php echo esc_html( $read_more ); ?>
                                                </a>
                                            </div><!-- .entry-container -->
                                        </div><!-- .post-wrapper -->
                                    </article>
                                <?php endwhile; 
                            endif;
                            wp_reset_postdata(); ?>
                        </div><!-- .section-content -->
                    </div><!-- .wrapper -->
                </div><!-- #featured-posts -->

            <?php
            echo $args['after_widget'];
        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form( $instance ) {
            $title      = isset( $instance['title'] ) ? ( $instance['title'] ) : esc_html__( 'Featured', 'uni-education' );
            $read_more  = isset( $instance['read_more'] ) ? $instance['read_more'] : esc_html__( 'Read More', 'uni-education' );

            $page_options = uni_education_page_choices();
            ?>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'uni-education' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>

            <?php for ( $i = 1; $i <= 3; $i++ ) : 
                $page_id = isset( $instance['page_id_' . $i] ) ? $instance['page_id_' . $i] : ''; ?>
                <p>
                    <label for="<?php echo esc_attr( $this->get_field_id( 'page_id_' . $i ) ); ?>"><?php printf( esc_html__( 'Select Page %d', 'uni-education' ), $i ); ?></label>
                    <select class="uni-education-widget-chosen-select widfat" id="<?php echo esc_attr( $this->get_field_id( 'page_id_' . $i ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'page_id_' . $i ) ); ?>">
                        <?php foreach ( $page_options as $page_option => $value ) : ?>
                            <option value="<?php echo absint( $page_option ); ?>" <?php selected( $page_id, $page_option, $echo = true ) ?> ><?php echo esc_html( $value ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
            <?php endfor; ?>
            
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
            $instance['read_more']      = wp_kses_post( $new_instance['read_more'] );
            for ( $i = 1; $i <= 3; $i++ ) :
                $instance['page_id_' . $i]   = uni_education_sanitize_page_post( $new_instance['page_id_' . $i] );
            endfor;
           
            return $instance;
        }
    }
endif;
