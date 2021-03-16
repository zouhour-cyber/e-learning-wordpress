<?php
/**
 * Plugin Name: WP Courses LMS
 * Description: Create unlimited courses on your WordPress website with WP Courses LMS.
 * Version: 2.0.33
 * Author: Myles English
 * Plugin URI: https://wpcoursesplugin.com
 * Author URI: https://stratospheredigital.ca
 * Text Domain: wp-courses
 * Domain Path: /lang
 * License: GPL2
 */

defined('ABSPATH') or die("No script kiddies please!");
include 'functions.php';
include 'wpc-options.php';
include 'class-wp-courses.php';
include 'class-wpc-admin.php';
include 'lesson-meta.php';
include 'course-meta.php';
include 'requirements-meta.php';
include 'wpc-ajax.php';
include 'admin-menu.php';
include 'columns-wp-courses.php';
include 'shortcodes.php';
include 'wpc-widgets.php';

// use ajax in the front-end
add_action('wp_head', 'wp_courses_ajaxurl'); 

function wp_courses_ajaxurl() {

   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

// add empty lightbox to footer

add_action('wp_footer', 'wpc_lightbox');

function wpc_lightbox(){
    echo '<div class="wpc-lightbox-wrapper" style="display: none;"><div class="wpc-lightbox"><div class="wpc-lightbox-close"><i class="fa fa-times"></i></div><div class="wpc-lightbox-content"></div></div></div>';
}

// install requirements table

function wpc_create_requirements_table() {
    global $wpdb;

    add_option( "wpc_db_version", "1.0" );

    // create rules table
    $table_name = $wpdb->prefix . 'wpc_rules';
    
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(10) NOT NULL AUTO_INCREMENT,
        post_id mediumint(10) NOT NULL,
        course_id mediumint(10),
        lesson_id mediumint(10),
        module_id mediumint(10),
        action varchar(255),
        type varchar(255),
        percent TINYINT,
        times mediumint(9),
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

}

register_activation_hook( __FILE__, 'wpc_create_requirements_table' );

function wpc_update_db_check() {
    $ver = get_site_option( "wpc_db_version" );
    if ( $ver != "1.0" ) {
        wpc_create_requirements_table();
    }
}
add_action( 'plugins_loaded', 'wpc_update_db_check' );

// admin lesson course filter
 
add_filter( 'parse_query', 'wpc_filter_lessons_by_course' );

function wpc_filter_lessons_by_course( $query ) {
  global $pagenow;
  // Get the post type
  $post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
  if ( is_admin() && $post_type == 'lesson' && isset( $_GET['wpc-course-filter'] ) && $_GET['wpc-course-filter'] !='all' ) {
    $query->query_vars['meta_key'] = 'wpc-connected-lesson-to-course';
    $query->query_vars['meta_value'] = $_GET['wpc-course-filter'];
    $query->query_vars['meta_compare'] = '=';
  }
}

add_action( 'restrict_manage_posts', 'wpc_course_filter_select' );
 
function wpc_course_filter_select() {   
    if(!empty($_GET['post_type'])){
        if($_GET['post_type'] == 'lesson'){
            global $wpdb;
            $sql = 'SELECT DISTINCT ID, post_title, post_status FROM '.$wpdb->posts.' WHERE post_type = "course" AND post_status = "publish" OR post_type = "course" AND post_status = "draft" ORDER By post_title';
            $results = $wpdb->get_results($sql);

            echo '<select name="wpc-course-filter" class="wpc-admin-select">';

                echo '<option value="all">' . __('All Courses', 'wp-courses') . '</option>';
                echo '<option value="none">' . __('None', 'wp-courses') . '</option>';

            foreach ($results as $result) {
                echo '<option value="' . $result->ID . '">' . $result->post_title . '</option>';
            }

            echo '</select>';
        }
    }
}

// courses and teachers per page

function wpc_num_posts($query) {

    $wpc_teachers_per_page = (int) get_option('wpc_teachers_per_page');

    if ( is_post_type_archive( 'teacher' ) && !is_admin() && !empty($wpc_teachers_per_page)) {
            $query->set('posts_per_page', $wpc_teachers_per_page);
    }

    if( is_post_type_archive( 'course' ) || is_tax('course-category') ){
        $wpc_courses_per_page = (int) get_option('wpc_courses_per_page');
        if (!is_admin() && !empty($wpc_courses_per_page)) {
                $query->set('posts_per_page', $wpc_courses_per_page);
        }
    }

    return $query;
}
add_filter('pre_get_posts', 'wpc_num_posts', 100);

// admin lightbox

add_action('admin_footer', 'wp_courses_admin_hidden_footer_elements');

function wp_courses_admin_hidden_footer_elements(){
    echo '<div class="wpc-lightbox-container" style="display: none;"><div class="wpc-lightbox-close">x</div><div class="wpc-lightbox"></div></div>';
    echo '<div id="wpc-ajax-save" class="fa-2x" style="display: none;"><i></i></div>';
}

// add localization
add_action('plugins_loaded', 'wpc_load_textdomain');
function wpc_load_textdomain() {
    load_plugin_textdomain( 'wp-courses', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
}

// add custom styling to header
function wpc_custom_styling(){
    $wpc_show_viewed_lessons = get_option('wpc_show_viewed_lessons');

    $wpc_primary_bg_color = get_option('wpc_primary_bg_color', '#f5f5f5');

    $wpc_primary_button_color = get_option('wpc_primary_button_color', '#23d19f');
    $wpc_primary_button_border_color = get_option('wpc_primary_button_border_color', '#12ad80');
    $wpc_primary_button_text_color = get_option('wpc_primary_button_text_color', '#fff');

    $wpc_primary_button_hover_color = get_option('wpc_primary_button_hover_color', '#23d19f');
    $wpc_primary_button_hover_border_color = get_option('wpc_primary_button_hover_border_color', '#12ad80');
    $wpc_primary_button_hover_text_color = get_option('wpc_primary_button_hover_text_color', '#fff');

    $wpc_primary_button_active_color = get_option('wpc_primary_button_active_color', '#009ee5');
    $wpc_primary_button_active_border_color = get_option('wpc_primary_button_active_border_color', '#027fb7');
    $wpc_primary_button_active_text_color = get_option('wpc_primary_button_active_text_color', '#fff');

    echo '<style>';

    if($wpc_show_viewed_lessons != 'true'){
        echo '#wpc-viewed-lessons-toggle { display: none; }';
    }

    echo '.wpc-container {
        background-color: ' . $wpc_primary_bg_color . ';
    }';

    echo '.wpc-button, .paginate_button, .paginate_button.current {
        background: ' . $wpc_primary_button_color . '; 
        border-color: ' . $wpc_primary_button_border_color . '!important; 
        color: ' . $wpc_primary_button_text_color . '!important; 
    }';

    echo '.paginate_button.current {
        background: ' . $wpc_primary_button_active_color . '!important; 
        border-color: ' . $wpc_primary_button_active_border_color . '!important; 
        color: ' . $wpc_primary_button_active_text_color . '; 
    }';

    echo '.paginate_button:hover {
        background: ' . $wpc_primary_button_hover_color . '!important; 
        border-color: ' . $wpc_primary_button_hover_border_color . '!important; 
        color: ' . $wpc_primary_button_hover_text_color . '!important; 
    }';

    echo '.wpc-button a { 
        color: ' . $wpc_primary_button_text_color . '; 
    }';

    echo 'a.wpc-button:hover, .wpc-button:hover { 
        background-color: ' . $wpc_primary_button_hover_color . '; 
        border-color: ' . $wpc_primary_button_hover_border_color . '!important; 
        color: ' . $wpc_primary_button_hover_text_color . '; 
    }';

    echo '.wpc-button:hover a { 
        color: ' . $wpc_primary_button_hover_text_color . '; 
    }';

    echo '.wpc-button.active { 
        background-color: ' . $wpc_primary_button_active_color . '; 
        border-color: ' . $wpc_primary_button_active_border_color . '!important; 
        color: ' . $wpc_primary_button_active_text_color . '; 
    }';

    echo '.wpc-button.active a{ 
        color: ' . $wpc_primary_button_active_text_color . '; 
    }';

    echo '.course-category-list ul a { color: ' . $wpc_primary_button_text_color . '; }';

    echo '.course-category-list ul a:hover { color: ' . $wpc_primary_button_hover_text_color . '; }';

    echo '.course-category-list ul a.active { color: ' . $wpc_primary_button_active_text_color . '; }';

    echo '</style>';
}
add_action('wp_head', 'wpc_custom_styling');

// enqueue scripts
function wpc_enqueue_scripts(){
    wp_enqueue_script('jquery');
    wp_enqueue_style( 'wpc-style', plugins_url('css/style.css',  __FILE__ ) );
    wp_enqueue_script('wpc-script', plugins_url('js/wpc-js.js',  __FILE__ ), 'jQuery', null, true);
    wp_enqueue_style( 'font-awesome-icons', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_enqueue_style( 'wpc-data-tables-style', plugins_url('css/datatables.min.css',  __FILE__ ) );
    wp_enqueue_script('wpc-data-tables-js', 'https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js', 'jQuery', null, true);

    // Localize the script with new data
    $translation_array = array(
        'completed'             => __( 'Completed', 'wp-courses' ),
        'notCompleted'          => __( 'Mark Completed', 'wp-courses' ),
        'emptyTable'            => __( 'No data available in table', 'wp-courses' ),
        'infoEmpty'             => __( 'There are 0 entries', 'wp-courses' ),
        'infoFiltered'          => __( 'Filtered from a total entry count of', 'wp-courses' ),
        'lengthMenu'            => __( 'Entries', 'wp-courses' ),
        'loadingRecords'        => __( 'Loading...', 'wp-courses' ),
        'processing'            => __( 'Processing...', 'wp-courses' ),
        'search'                => __( 'Search', 'wp-courses' ),
        'zeroRecords'           => __( 'No matching records found', 'wp-courses' ),
        'first'                 => __( 'First', 'wp-courses' ),
        'last'                  => __( 'Last', 'wp-courses' ),
        'next'                  => __( 'Next', 'wp-courses' ),
        'previous'              => __( 'Previous', 'wp-courses' ),
        'sortAscending'         => __( 'activate to sort column ascending', 'wp-courses' ),
        'sortDescending'        => __( 'activate to sort column descending', 'wp-courses' ),
    );

    wp_localize_script( 'wpc-script', 'WPCTranslations', $translation_array );

}
add_action( 'wp_enqueue_scripts', 'wpc_enqueue_scripts' );

function wpc_enqueue_admin_scripts(){
    wp_enqueue_style( 'wp-color-picker' ); 
    wp_enqueue_script( 'wp-color-picker' ); 
    wp_enqueue_style( 'wpc-data-tables-style', '//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css' );
    wp_enqueue_script('wpc-admin-js', plugins_url('js/wpc-admin.js', __FILE__), 'jQuery');
    wp_enqueue_style('wpc-admin-css', plugins_url('css/admin.css', __FILE__));
    wp_enqueue_script('wpc-data-tables-js', 'https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js', 'jQuery', null, true);
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_style( 'font-awesome-icons', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );

    // Localize the script with new data
    $translation_array = array(
        'whenSomeone'           => __( 'When Someone', 'wp-courses' ),
        'views'                 => __( 'Views', 'wp-courses' ),
        'completes'             => __( 'Completes', 'wp-courses' ),
        'scores'                => __( 'Scores', 'wp-courses' ),
        'anyCourse'             => __( 'Any Course', 'wp-courses' ),
        'aSpecificCourse'       => __( 'A Specific Course', 'wp-courses' ),
        'anyLesson'             => __( 'Any Lesson', 'wp-courses' ),
        'aSpecificLesson'       => __( 'A Specific Lesson', 'wp-courses' ),
        'anyModule'             => __( 'Any Module', 'wp-courses' ),
        'aSpecificModule'       => __( 'A Specific Module', 'wp-courses' ),
        'anyQuiz'               => __( 'Any Quiz', 'wp-courses' ),
        'aSpecificQuiz'         => __( 'A Specific Quiz', 'wp-courses' ),
        'none'                  => __( 'none', 'wp-courses' ),
        'percent'               => __( 'Percent', 'wp-courses' ),
        'times'                 => __( 'Times', 'wp-courses' ),
        'deleteRequirement'     => __( 'Delete Requirement', 'wp-courses' ),
    );

    wp_localize_script( 'wpc-admin-js', 'WPCAdminTranslations', $translation_array );

}
add_action( 'admin_enqueue_scripts', 'wpc_enqueue_admin_scripts' );

// Register Custom Post Type Course
function wpc_register_course_cp(){

    $labels = array(
        'name'               => _x( 'Courses', 'post type general name'),
        'singular_name'      => _x( 'Course', 'post type singular name'),
        'add_new'            => _x( 'Add New', 'Course'),
        'add_new_item'       => __( 'Add New Course'),
        'edit_item'          => __( 'Edit Course' ),
        'new_item'           => __( 'New Course' ),
        'all_items'          => __( 'All Courses' ),
        'view_item'          => __( 'View Course' ),
        'search_items'       => __( 'Search Courses' ),
        'not_found'          => __( 'No Courses Found' ),
        'not_found_in_trash' => __( 'No Courses Found in the Trash' ), 
        'parent_item_colon'  => '',
        'menu_name'          => __('Courses', 'wp-courses'),
    );
    $args = array(
        'labels'                => $labels,
        'show_in_admin_bar'     => true,
        'menu_icon'             => null,
        'show_in_nav_menus'     => false,
        'publicly_queryable'    => true,
        'query_var'             => true,
        'can_export'            => true,
        'rewrite'               => true,
        'show_in_menu'          => '',
        'description'           => __('Enter a Course Description Here', 'wp-courses'),
        'public'                => true,
        'show_ui'               => true,
        'hierarchical'          => false,
        'supports'              => array('title', 'editor', 'excerpt', 'page-attributes', 'thumbnail', 'custom-fields'),
        'has_archive'           => true,
        'show_in_rest'          => true,
    );

    register_post_type('course', $args);
    flush_rewrite_rules(false);
}
add_action('init', 'wpc_register_course_cp');

//Custom Messages for Custom Post Type Course
function wpc_course_messages( $messages ) {
    global $post;
    $post_ID = get_the_id();
    $messages['course'] = array(
        0 => '', 
        1 => sprintf( __('Course updated. <a href="%s">View Course</a>'), esc_url( get_permalink($post_ID) ) ),
        2 => __('Custom field updated.'),
        3 => __('Custom field deleted.'),
        4 => __('Course updated.'),
        5 => isset($_GET['revision']) ? sprintf( __('Course restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __('Course published. <a href="%s">View Course</a>'), esc_url( get_permalink($post_ID) ) ),
        7 => __('Course saved.'),
        8 => sprintf( __('Course submitted. <a target="_blank" href="%s">Preview Course</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        9 => sprintf( __('Course scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Course</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
        10 => sprintf( __('Course draft updated. <a target="_blank" href="%s">Preview Course</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    );
  return $messages;
}
add_filter( 'post_updated_messages', 'wpc_course_messages' );

// Register Custom Post Type Lesson
function wpc_register_lesson_cp(){
  $labels = array(
    'name'               => _x( 'Lesson', 'post type general name' ),
    'singular_name'      => _x( 'Lesson', 'post type singular name' ),
    'add_new'            => _x( 'Add New', 'Lesson' ),
    'add_new_item'       => __( 'Add New Lesson' ),
    'edit_item'          => __( 'Edit Lesson' ),
    'new_item'           => __( 'New Lesson' ),
    'all_items'          => __( 'All Lessons' ),
    'view_item'          => __( 'View Lesson' ),
    'search_items'       => __( 'Search Lessons' ),
    'not_found'          => __( 'No Lessons found' ),
    'not_found_in_trash' => __( 'No lessons found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => __('Lessons', 'wp-courses'),
  );
  $args = array(
    'show_in_admin_bar'   => true,
    'menu_icon'           => null,
    'show_in_nav_menus'   => false,
    'publicly_queryable'  => true,
    'exclude_from_search' => true,
    'has_archive'         => false,
    'query_var'           => true,
    'can_export'          => true,
    'rewrite'             => true,
    'show_in_menu'        => '',
    'has_archive'         => true,
    'hierarchical'        => true,
    'public'              => true,
    'show_ui'             => true,
    'show_in_rest'        => true,
    'labels'        => $labels,
    'description'   => __('Enter a lesson description here', 'wp-courses'),
    'supports'      => array( 'title', 'editor', 'excerpt', 'comments', 'revisions', 'author', 'custom-fields', 'page-attributes'),
  );

  register_post_type( 'lesson', $args ); 
  flush_rewrite_rules( false );
}
add_action( 'init', 'wpc_register_lesson_cp' );

function wpc_remove_lesson_rest_api_data( $data, $post, $context ) {

	$can_edit = current_user_can( 'edit_posts' ); 

	if ( $can_edit != true ) {

	    $wpc_enable_rest_lesson = get_option( 'wpc_enable_rest_lesson' );

	    if( $wpc_enable_rest_lesson != 'true' ){
	        unset ($data->data ['content']);
	        unset ($data->data ['excerpt']);
	    }
	}

	return $data;

}

add_filter( 'rest_prepare_lesson', 'wpc_remove_lesson_rest_api_data', 12, 3 );

//Custom Messages for Custom Post Type Lesson
function wpc_lesson_messages_cp( $messages ) {
    global $post;
    $post_ID = get_the_id();
    $messages['lesson'] = array(
        0 => '', 
        1 => sprintf( __('Lesson updated. <a href="%s">View Lesson</a>'), esc_url( get_permalink($post_ID) ) ),
        2 => __('Custom field updated.'),
        3 => __('Custom field deleted.'),
        4 => __('Lesson updated.'),
        5 => isset($_GET['revision']) ? sprintf( __('Lesson restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __('Lesson published. <a href="%s">View Lesson</a>'), esc_url( get_permalink($post_ID) ) ),
        7 => __('Lesson saved.'),
        8 => sprintf( __('Lesson submitted. <a target="_blank" href="%s">Preview Lesson</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        9 => sprintf( __('Lesson scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Lesson</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
        10 => sprintf( __('Lesson draft updated. <a target="_blank" href="%s">Preview Lesson</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    );
    return $messages;
}
add_filter( 'post_updated_messages', 'wpc_lesson_messages_cp' );

// Register Custom Post Type Module
function wpc_register_module_cp(){

    $labels = array(
        'name'               => _x( 'Module', 'post type general name' ),
        'singular_name'      => _x( 'Module', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'Module' ),
        'add_new_item'       => __( 'Add New Module' ),
        'edit_item'          => __( 'Edit Module' ),
        'new_item'           => __( 'New Module' ),
        'all_items'          => __( 'All Modules' ),
        'view_item'          => __( 'View Module' ),
        'search_items'       => __( 'Search Modules' ),
        'not_found'          => __( 'No Modules found' ),
        'not_found_in_trash' => __( 'No Modules found in the Trash' ), 
        'parent_item_colon'  => '',
        'menu_name'          => __('Modules', 'wp-courses'),
    );

    $args = array(
        'show_in_admin_bar'   => false,
        'menu_icon'           => null,
        'show_in_nav_menus'   => false,
        'publicly_queryable'  => true,
        'exclude_from_search' => true,
        'has_archive'         => false,
        'query_var'           => true,
        'can_export'          => true,
        'rewrite'             => true,
        'show_in_menu'        => '',
        'has_archive'         => true,
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_rest'        => true,
        'labels'              => $labels,
        'description'         => __('Enter a module description here', 'wp-courses'),
        'supports'            => array( 'title' ),
    );

    register_post_type( 'wpc-module', $args ); 
    flush_rewrite_rules( false );

}
add_action( 'init', 'wpc_register_module_cp' );
//Custom Messages for Custom Post Type Module
function wpc_module_messages_cp( $messages ) {
    global $post;
    $post_ID = get_the_id();
    $messages['wpc-module'] = array(
        0 => '', 
        1 => sprintf( __('Module updated. <a href="%s">View Module</a>'), esc_url( get_permalink($post_ID) ) ),
        2 => __('Custom field updated.'),
        3 => __('Custom field deleted.'),
        4 => __('Module updated.'),
        5 => isset($_GET['revision']) ? sprintf( __('Module restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __('Module published. <a href="%s">View Module</a>'), esc_url( get_permalink($post_ID) ) ),
        7 => __('Module saved.'),
        8 => sprintf( __('Module submitted. <a target="_blank" href="%s">Preview Module</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        9 => sprintf( __('Module scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Module</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
        10 => sprintf( __('Module draft updated. <a target="_blank" href="%s">Preview Module</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    );
    return $messages;
}
add_filter( 'post_updated_messages', 'wpc_module_messages_cp' );

// Register Custom Post Type Teacher
function wpc_register_teacher_cp(){
  $labels = array(
    'name'               => _x( 'Teachers', 'post type general name'),
    'singular_name'      => _x( 'Teacher', 'post type singular name'),
    'add_new'            => _x( 'Add New', 'Teacher'),
    'add_new_item'       => __( 'Add New Teacher'),
    'edit_item'          => __( 'Edit Teacher' ),
    'new_item'           => __( 'New Teacher' ),
    'all_items'          => __( 'All Teachers' ),
    'view_item'          => __( 'View Teacher' ),
    'search_items'       => __( 'Search Teachers' ),
    'not_found'          => __( 'No Teachers Found' ),
    'not_found_in_trash' => __( 'No Teachers Found in the Trash' ), 
    'parent_item_colon'  => '',
    'menu_name'          => __('Teachers', 'wp-courses'),
    );
  $args = array(
    'show_in_admin_bar'   => true,
    'menu_icon'           => null,
    'show_in_nav_menus'   => false,
    'publicly_queryable'  => true,
    'exclude_from_search' => true,
    'query_var'           => true,
    'can_export'          => true,
    'rewrite'             => true,
    'show_in_menu'        => '',
    'has_archive'         => true,
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_rest'        => true,
    'labels'              => $labels,
    'description'         => __('Enter a Teacher Description Here', 'wp-courses'),
    'supports'            => array('title', 'editor', 'excerpt', 'page-attributes', 'thumbnail'),
    ); 

    register_post_type('teacher', $args);
    flush_rewrite_rules(false);
}
add_action('init', 'wpc_register_teacher_cp');
//Custom Messages for Custom Post Type Teacher
function wpc_teacher_messages( $messages ) {
    $permalink = get_permalink(get_the_ID());
    $messages['course'] = array(
        0 => '', 
        1 => sprintf( __('Teacher updated. <a href="%s">View Teacher</a>'), esc_url( $permalink ) ),
        2 => __('Custom field updated.'),
        3 => __('Custom field deleted.'),
        4 => __('Teacher updated.'),
        5 => isset($_GET['revision']) ? sprintf( __('Teacher restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __('Teacher published. <a href="%s">View Teacher</a>'), esc_url( $permalink ) ),
        7 => __('Teacher saved.'),
        8 => sprintf( __('Teacher submitted. <a target="_blank" href="%s">Preview Teacher</a>'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
        9 => sprintf( __('Teacher scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Teacher</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( get_the_ID() ) ), esc_url( $permalink ) ),
        10 => sprintf( __('Teacher draft updated. <a target="_blank" href="%s">Preview Teacher</a>'), esc_url( add_query_arg( 'preview', 'true', $permalink ) )),  
    );
    return $messages;
}
add_filter( 'post_updated_messages', 'wpc_teacher_messages' );
// Register Custom Taxonomy "lesson-difficulty"
add_action( 'init', 'wpc_lesson_difficulty_args', 0 );
// Customize taxonomy
function wpc_lesson_difficulty_args() {
  $labels = array(
    'name'              => _x( 'Difficulty', 'taxonomy general name' ),
    'singular_name'     => _x( 'Difficulty', 'taxonomy name' ),
    'search_items'      => __( 'Search Difficulty' ),
    'all_items'         => __( 'All Difficulty' ),
    'parent_item'       => __( 'Parent Term Category' ),
    'parent_item_colon' => __( 'Parent Term Category:' ),
    'edit_item'         => __( 'Edit Difficulty' ), 
    'update_item'       => __( 'Update Difficulty' ),
    'add_new_item'      => __( 'Add New Difficulty' ),
    'new_item_name'     => __( 'New Difficulty' ),
    'menu_name'         => __( 'Difficulty' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => false,
    'show_admin_column' => true,
    'query_var' => true
  );
  register_taxonomy( 'course-difficulty', 'course', $args );
}
// Register Custom Taxonomy "category"
add_action( 'init', 'wpc_course_category_args', 0 );
// Customize taxonomy
function wpc_course_category_args() {
  $labels = array(
    'name'              => _x( 'Category', 'taxonomy general name' ),
    'singular_name'     => _x( 'Category', 'taxonomy name' ),
    'search_items'      => __( 'Search Category' ),
    'all_items'         => __( 'All Categories' ),
    'parent_item'       => __( 'Parent Term Category' ),
    'parent_item_colon' => __( 'Parent Term Category:' ),
    'edit_item'         => __( 'Edit Category' ), 
    'update_item'       => __( 'Update Category' ),
    'add_new_item'      => __( 'Add New Category' ),
    'new_item_name'     => __( 'New Category' ),
    'menu_name'         => __( 'Category' ),
  );
  $args = array(
    'labels' => $labels,
    'hierarchical' => false,
    'show_admin_column' => true,
    'query_var' => true
  );
  register_taxonomy( 'course-category', 'course', $args );
}
/*
** Use Custom Templates
*/
add_filter( 'template_include', 'wpc_single_lesson_page_template', 99 );
function wpc_single_lesson_page_template( $template )
{
    if ( get_post_type() == 'lesson' && is_single() ) {
        $new_template = dirname( __FILE__ ) . '/templates/wpc-single-lesson.php';
        if ( !empty( $new_template ) ) {
            return $new_template;
        } 
    } else {
        return $template;
    }
}
add_filter( 'template_include', 'wpc_archive_course_page_template', 99 );
function wpc_archive_course_page_template( $template )
{
    if ( is_post_type_archive( 'course' )) {
        $new_template = dirname( __FILE__ ) . '/templates/wpc-archive-course.php';
        if ( !empty( $new_template ) ) {
            return $new_template;
        } 
    } else {
        return $template;
    }
}
add_filter( 'template_include', 'wpc_archive_teacher_page_template', 99 );
function wpc_archive_teacher_page_template( $template )
{
    if ( is_post_type_archive( 'teacher' )) {
        $new_template = dirname( __FILE__ ) . '/templates/wpc-archive-teacher.php';
        //$new_template = locate_template( array( '/templates/wpc-single-lesson.php' ) );
        if ( !empty( $new_template ) ) {
            return $new_template;
        } 
    } else {
        return $template;
    }
}
add_filter( 'template_include', 'wpc_single_course_page_template', 99 );
function wpc_single_course_page_template( $template )
{
    if ( get_post_type() == 'course' && is_single() ) {
        $new_template = dirname( __FILE__ ) . '/templates/wpc-single-course.php';
        //$new_template = locate_template( array( '/templates/wpc-single-lesson.php' ) );
        if ( !empty( $new_template ) ) {
            return $new_template;
        } 
    } else {
        return $template;
    }
}
add_filter( 'template_include', 'wpc_single_teacher_page_template', 99 );
function wpc_single_teacher_page_template( $template )
{
    if ( get_post_type() == 'teacher' && is_single() ) {
        $new_template = dirname( __FILE__ ) . '/templates/wpc-single-teacher.php';
        //$new_template = locate_template( array( '/templates/wpc-single-lesson.php' ) );
        if ( !empty( $new_template ) ) {
            return $new_template;
        } 
    } else {
        return $template;
    }
}

add_filter( 'template_include', 'wpc_course_category_page_template', 9 );
function wpc_course_category_page_template( $template )
{
    if ( is_tax() == 'course-category' ) {
        $new_template = dirname( __FILE__ ) . '/templates/category-course-category.php';
        //$new_template = locate_template( array( '/templates/wpc-single-lesson.php' ) );
        if ( !empty( $new_template ) ) {
            return $new_template;
        } 
    } else {
        return $template;
    }
}

// order courses by menu order in course archive and coures category archive unless different order selected by user

add_action( 'pre_get_posts', 'wpc_change_courses_sort_order'); 

function wpc_change_courses_sort_order($query){

    // if( ! $query->is_post_type_archive() ) return;

    if( is_post_type_archive( 'course' )  && $query->is_main_query() || is_tax() == 'course-category'  && $query->is_main_query() ) {

        if( isset( $_GET['order'] ) ){

            $value =  $_GET['order'];

            if( $value == 'default' ){
                $query->set( 'order', 'ASC' );
                $query->set( 'orderby', 'menu_order' );
            } elseif( $value == 'newest') {
                $query->set('orderby', 'date');
                $query->set('order', 'desc');
            } elseif( $value == 'oldest') {
                $query->set('orderby', 'date');
                $query->set('order', 'asc');
            } elseif( $value == 'alphabetical' ) {
                $query->set('orderby', 'title');
                $query->set('order', 'asc');
            }
        } else {
            $query->set( 'order', 'ASC' );
            $query->set( 'orderby', 'menu_order' );
        }

        $search = $_GET['search'];
        $courses_per_page = get_option('wpc_courses_per_page');

        if( isset( $search ) ) {
            $query->set( 's', $search );            
        }
    }
};

// add video to content in single lessons.  This way PMPro can filter the lesson content including the video.
add_filter('the_content', 'wpc_add_video_to_lesson_content', 2);

function wpc_add_video_to_lesson_content($content){
    if(get_post_type() == 'lesson' && is_single()) {
        $lesson_id = get_the_ID();
        $orig_id = get_post_meta( $lesson_id, 'wpc-lesson-alias-id', true);

        // if is clone lesson, get the ID of the original lesson so we can output the content of the original kesson
        if(!empty($orig_id) && $orig_id != 'none'){
            $lesson_id = $orig_id;
        } 
        
        $wp_lessons = new WPC_Lessons();
        $video = $wp_lessons->get_lesson_video($lesson_id);
        $wpc_tools = new WPC_Tools();
        $toolbar = $wpc_tools->get_toolbar();
        return '<div id="video-wrapper" class="wpc-video-wrapper">' . $video . '</div>' . $toolbar . $content;
    } else {
        return $content;
    }

}

// completed lesson ajax

function wpc_push_completed($user_id, $post_id, $status, $post_type){

    $completed_lessons = get_user_meta($user_id, 'wpc-completed-lesson-tracking', true);

    $ids = wpc_get_alias_and_orig_ids($post_id);

    if($completed_lessons === '' || count($completed_lessons) == 0){

        $completed_lessons = array();

        foreach( $ids as $id ){
            $completed_lessons[] = array(
                'id'        => $id,
                'post_type' => $post_type,
                'time'      => time(),
            );
        }

        update_user_meta($user_id, 'wpc-completed-lesson-tracking', $completed_lessons );
    } else {
        // check if is array to support legacy data model before saving a multi-dimensional array
        // then loop through all completed lessons to first remove duplicate entries
        foreach( $completed_lessons as $key => $value ){
            if( is_array( $value ) ){

                foreach($ids as $id) {

                    if( $id == $value['id'] ){
                        unset($completed_lessons[$key]);
                    }

                }

            } else {

                foreach( $ids as $id ) {

                    if( $id == $value ){
                        unset( $completed_lessons[$key] );
                    } 

                }

            }
        }

        if( $status == 'false' ) {

            foreach( $ids as $id ) {

                // we don't want to display both the alias lesson and original lesson in tracking tables
                if( $post_id === $id ) {
                    $display = 'true';
                } else {
                    $display = 'false';
                }

                array_unshift($completed_lessons, array(
                    'id'        => $id,
                    'post_type' => $post_type,
                    'time'      => time(),
                    'display'   => $display,
                ));

            }

        }

        update_user_meta($user_id, 'wpc-completed-lesson-tracking', $completed_lessons);
    }
}

add_action( 'wp_footer', 'wpc_completed_action_javascript', 12 ); // Write our JS below here

function wpc_completed_action_javascript() { ?>
    <script type="text/javascript" >
    jQuery(document).ready(function($) {

        jQuery('#wpc-completed-lesson-toggle').click(function(){

            var lessonID = $(this).attr('data-id');

            var $lessonBtn = $('#wpc-completed-lesson-toggle');
            var $lessonBtnIcon = $('#wpc-completed-lesson-toggle i');

            var $lessonNavBtnIcon = $('[data-lesson-button-id="' + lessonID + '"] i');

            $lessonBtnIcon.attr('class', 'fa fa-spinner fa-spin fa-fw');
            $lessonNavBtnIcon.attr('class', 'fa fa-spinner fa-spin fa-fw');

            var data = {
                'type'      : 'POST',
                'action'    : 'get_badge_awards_lightbox',
                // 'dataType'   : 'text/html',
                'userID': <?php if(is_user_logged_in()){ echo get_current_user_ID(); } else { echo 'null'; } ?>,
                'completedStatus': $('#wpc-completed-lesson-toggle').attr('data-status'),
                'postID': lessonID,
            };
    
            jQuery.post(ajaxurl, data, function(response) {

                var res = JSON.parse(response);

                var $lessonNavBtnIcon = $('[data-lesson-button-id="' + data.postID + '"] i');

                // completed button display logic
                if($lessonBtn.attr('data-status') == 'false') {
                    $lessonBtn.attr('data-status', 'true');
                    $lessonBtn.html('<i class="fa fa-check-square-o"></i> ' + WPCTranslations.completed);
                    $lessonNavBtnIcon.attr('class', 'fa fa-check');
                } else {
                    $lessonBtn.attr('data-status', 'false');
                    $lessonBtn.html('<i class="fa fa-square-o"></i> ' + WPCTranslations.notCompleted);
                    $lessonNavBtnIcon.attr('class', 'fa fa-eye');
                }

                // append the badge awards lightbox
                $('body').append(res.html);

                // adjust progress bar
                jQuery('.wpc-progress-bar-level').css('width', res.percent + "%");
                jQuery('.wpc-progress-bar-text').html('<i class="fa fa-check"></i> ' + res.percent + '%' + ' Completed');
                    
            });

        });

    });

    </script>
<?php }

add_action( 'wp_ajax_get_badge_awards_lightbox', 'wpc_get_badge_awards_lightbox_action', 12 );

function wpc_get_badge_awards_lightbox_action() {

    $post_type = get_post_type($_POST['postID']);

    $lesson_id = $_POST['postID'];
    $course_id = get_post_meta($lesson_id, 'wpc-connected-lesson-to-course', true);

    // get original and lesson alias ids
    $all_lesson_ids = wpc_get_alias_and_orig_ids($lesson_id);

    wpc_push_completed( $_POST['userID'], $lesson_id, $_POST['completedStatus'], $post_type );

    $lightbox = wpc_rule_evaluation_engine($post_type, false);

    $wpc_courses = new WPC_Courses();

    $percent = $wpc_courses->get_percent_completed($course_id);

    echo json_encode(array(
        'html'      => $lightbox,
        'percent'   => $percent,
    ));
   
    wp_die(); // required
} 

// show top admin nav menu across differen cp types

function wpc_admin_nav_menu_display_logic(){
    $show = false;

    if(isset($_GET['taxonomy'])){
        if($_GET['taxonomy'] == 'course-difficulty' || $_GET['taxonomy'] == 'course-category'){
            $show = true;
        }
    }

    $post_type = get_post_type();

    if($post_type == 'lesson' || $post_type == 'course' || $post_type == 'wpc-quiz' || $post_type == 'teacher' || $post_type == 'wpc-badge' || $post_type == 'wpc-certificate'){
        if(is_archive()){
            $show = true;
        }
    }

    if(!is_admin()){
        $show = false;
    }

    return $show;
}

add_action('in_admin_header', 'wpc_admin_nav_menu');

function wpc_admin_nav_menu(){

    $show = wpc_admin_nav_menu_display_logic();

    if( $show == true ){
        include 'admin-nav-menu.php';
    }
}

add_action('admin_footer', 'wpc_admin_screen_options_styling');

// shows the admin nav menu after screen options

function wpc_admin_screen_options_styling(){

    $show = wpc_admin_nav_menu_display_logic();

    if($show == true){

    echo    '<script>

                jQuery(document).ready(function($){
                    var nav = $(".wpc-admin-nav-menu");
                    var navClone = nav.clone();
                    nav.remove();

                    $("#screen-meta-links").after(nav);
                });

            </script>';

    }

}

?>