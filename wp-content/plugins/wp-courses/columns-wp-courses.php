<?php

add_filter( 'manage_edit-lesson_columns', 'wpc_lesson_columns', 9, 1 ) ;

function wpc_lesson_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Lesson', 'wp-courses' ),
		'course' => __( 'Connected Course', 'wp-courses' ),
		'restriction'	=> __( 'Restriction', 'wp-courses'),
		'date' => __( 'Date', 'wp-courses' )
	);
	return $columns;
}

add_action( 'manage_lesson_posts_custom_column', 'wpc_manage_lesson_columns', 10, 2 );

function wpc_manage_lesson_columns( $column, $post_id ) {
	global $post;
	switch( $column ) {
		case 'course' :
			$wpc_admin = new WPC_Admin();
			$course_id = get_post_meta($post_id, 'wpc-connected-lesson-to-course', true);
			echo $wpc_admin->get_course_dropdown($course_id, 'course-select wpc-admin-select');
		break;
		case 'restriction' :
			$wpc_admin = new WPC_Admin();
			$radio_name = 'radio-' . $post_id;
			echo $wpc_admin->lesson_restriction_radio_buttons($post_id, $radio_name);
		break;
	}
}

?>