<?php

/*
** lesson video meta box
*/

function wpc_add_lesson_video_meta_box() {
	add_meta_box(
		'wpc_lesson_video', // id
		__( 'Lesson Video', 'wp-courses' ),
		'wpc_lesson_video_meta_box_callback',
		'lesson',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'wpc_add_lesson_video_meta_box' );

function wpc_lesson_video_meta_box_callback( $post ) {

		wp_nonce_field('wpc_save_lesson_video_meta_box_data', 'wpc_lesson_video_meta_box_nonce');

		$value = get_post_meta( $post->ID, 'lesson-video', true );
  		do_action('wpc-before-lesson-meta');

		?>

		<label>
			<button type="button" class="wpc-question-btn button" data-content='If your lesson has a video, copy and paste the iframe from YouTube, Vimeo or another host here.'>?</button>
			<?php echo __('Video Embed Code', 'wp-courses'); ?> (iframe)
		</label>

		<br>

		<textarea style="width:100%;" id="wpc-lesson-video" name="wpc-lesson-video"><?php echo $value; ?></textarea>

<?php }

function wpc_save_lesson_video_meta_box_data($post_id) {

	// check if nonce is set.
	if ( ! isset( $_POST['wpc_lesson_video_meta_box_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['wpc_lesson_video_meta_box_nonce'], 'wpc_save_lesson_video_meta_box_data' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'lesson' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	// save the data to post meta
	if ( isset( $_POST['wpc-lesson-video'] ) ) {
		$my_data = strip_tags($_POST['wpc-lesson-video'], '<iframe>');
		update_post_meta( $post_id, 'lesson-video', $my_data );
	}

}

add_action( 'save_post', 'wpc_save_lesson_video_meta_box_data' );

/*
** connected course to lesson meta box
*/

function wpc_add_connected_course_to_lesson_meta_box() {
	$screens = array( 'lesson', 'wpc-quiz' );
		foreach ( $screens as $screen ) {
		add_meta_box(
			'wpc_connected_course_to_lesson', // id
			__( 'Connected Course', 'wp-courses' ),
			'wpc_connected_course_to_lesson_meta_box_callback',
			$screen,
			'side',
			'high'
		);
	}
}

add_action( 'add_meta_boxes', 'wpc_add_connected_course_to_lesson_meta_box' );

function wpc_connected_course_to_lesson_meta_box_callback( $post ) { 

	wp_nonce_field('wpc_save_connected_course_to_lesson_meta_box_data', 'wpc_connected_course_to_lesson_meta_box_nonce'); ?>

	<button type="button" class="wpc-question-btn button" data-content="If you would like your lessons to appear in a course you have created, you will need to connect them to that course.">?</button>

	<?php

	global $post;
	$post_old = $post;

	$wpc_admin = new WPC_Admin();

	$course_id = get_post_meta($post->ID, 'wpc-connected-lesson-to-course', true);

	echo $wpc_admin->get_course_dropdown($course_id, 'wpc-lesson-meta-course-select', true, 'course-selection');
	echo '<br><a style="margin: 5px 0 0;" href="' . admin_url() . 'post-new.php?post_type=course" class="button">' . __('Add New Course', 'wp-courses') . '</a>';

	// fixes issue with wrong slug being used
	$post = $post_old;
  	setup_postdata( $post );

}


// save connected course to lesson
function wpc_save_connected_course_to_lesson_meta_box_data( $post_id ) {

	// Check if our nonce is set.
	if ( ! isset( $_POST['wpc_connected_course_to_lesson_meta_box_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['wpc_connected_course_to_lesson_meta_box_nonce'], 'wpc_save_connected_course_to_lesson_meta_box_data' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'lesson' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}
	
	if(isset($_POST['course-selection'])){
		$course_id = sanitize_text_field( $_POST['course-selection'] );
		$wpc_courses = new WPC_Courses();
		update_post_meta( $post_id, 'wpc-connected-lesson-to-course', $course_id);
	}
}

add_action( 'save_post', 'wpc_save_connected_course_to_lesson_meta_box_data' );

/*
** lesson restriction meta box
*/

function wpc_add_lesson_restriction_meta_box() {
	$screens = array( 'lesson', 'wpc-quiz' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'wpc_lesson_restriction',
			__( 'Lesson Restriction', 'wp-courses' ),
			'wpc_lesson_restriction_meta_box_callback',
			$screen,
			'side',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'wpc_add_lesson_restriction_meta_box' );

function wpc_lesson_restriction_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field('wpc_save_lesson_restriction_meta_box_data', "wpc_lesson_meta_box_nonce"); 

	$restriction = get_post_meta($post->ID, 'wpc-lesson-restriction', true);

	$display = $restriction != 'membership' ? 'display:none;' : '';

	?>

	<div id="wpc-lesson-restriction-container" style="position:relative;">
		<div id="wpc-lesson-restriction-overlay" style="<?php echo $display; ?>"><?php echo __('Lesson Restriction Options Disabled if Membership Level(s) Checked', 'wp-courses'); ?>
		</div>

		<?php
			$wpc_admin = new WPC_Admin();
			echo $wpc_admin->lesson_restriction_radio_buttons($post->ID, 'wpc-lesson-restriction', '');
		?>

	</div>

<?php }

// save lesson restriction.

function wpc_save_lesson_restriction_meta_box_data( $post_id ) {

	// Check if our nonce is set.
	if ( ! isset( $_POST['wpc_lesson_meta_box_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['wpc_lesson_meta_box_nonce'], 'wpc_save_lesson_restriction_meta_box_data' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'lesson' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	if(isset($_POST['wpc-lesson-restriction'])){
		$restriction = sanitize_text_field( $_POST['wpc-lesson-restriction'] );
		update_post_meta( $post_id, 'wpc-lesson-restriction', $restriction );
	}
}
add_action( 'save_post', 'wpc_save_lesson_restriction_meta_box_data' );