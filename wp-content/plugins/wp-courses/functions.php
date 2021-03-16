<?php

function wpc_is_clone( $lesson_id ) {

	$orig_id = get_post_meta( $lesson_id, 'wpc-lesson-alias-id', true );

	if( !empty( $orig_id ) && $orig_id != 'none' ){
		return $orig_id;
	} else {
		return false;
	}

}

function wpc_get_alias_and_orig_ids($lesson_id){
	global $wpdb;

	$actual_id = $lesson_id;

	$orig_id = get_post_meta( $lesson_id, 'wpc-lesson-alias-id', true);

	if(!empty($orig_id) && $orig_id != 'none'){
		$lesson_id = $orig_id;
	} 

	// 
	$sql = "SELECT post_id, meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'wpc-lesson-alias-id' AND meta_value = {$lesson_id} AND meta_value != 'none' OR meta_key = 'wpc-lesson-alias-id' AND post_id = {$lesson_id} AND meta_value != 'none'";

	$results = $wpdb->get_results($sql);

	if( empty( $results ) ){

		return array($lesson_id);

	} else {

		$ids = array();

		$ids[] = $actual_id;

		foreach( $results as $result ) {
			$ids[] = $result->meta_value;
			$ids[] = $result->post_id;
		}

		$ids = array_unique($ids);

		return array_reverse($ids);
	}
}

function wpc_get_connected_course($lesson_id) {

	$orig_id = get_post_meta( $lesson_id, 'wpc-lesson-alias-id', true);

	if(!empty($orig_id) && $orig_id != 'none'){
		$course_id = get_post_meta($lesson_id, 'wpc-connected-lesson-to-course', true);
	} 

	if(empty($orig_id) || $orig_id == 'none') {
		$course_id = get_post_meta($lesson_id, 'wpc-connected-lesson-to-course', true);
	}	

}

function wpc_get_breadcrumb($lesson_id){

	$show_breadcrumb = get_option('wpc_show_breadcrumb_trail');

	$course_id = get_post_meta($lesson_id, 'wpc-connected-lesson-to-course', true);

	$terms = get_the_terms($course_id, 'course-category');

	if($show_breadcrumb != true){
		return;
	} elseif(empty($terms) && $course_id == 'none'){
		return;
	}

	if(empty($terms)){
		$term_link = '';
	} else {
		$term_link = '<a href="' . get_term_link($terms[0]->term_id) . '">' . $terms[0]->name . '</a> > ';
	}

	if($course_id != 'none') {
		$course_link = '<a href="' . get_the_permalink($course_id) . '">' . get_the_title($course_id) . '</a> > ';
	} else {
		$course_link = '';
	}

	return '<div class="wpc-breadcrumb">' . $term_link . $course_link . '<a href="' . get_the_permalink($lesson_id) . '">' . get_the_title($lesson_id) . '</a></div>';

}

function wpc_has_done($post_id, $tracking){

	$done = false;

	if(!empty($tracking)){
		foreach($tracking as $completed){
			if(is_array($completed)){
				if($post_id == $completed['id']){
					$done = true;
				}
			} else {
				if($post_id == $completed){
					$done = true;
				}
			}
		}

		return $done;
	} else {
		return false;
	}

}

function wpc_get_single_course_progress_list($course_id, $user_id){

	$data = '';

	$wpc_lessons = new WPC_Lessons();
	$lessons = $wpc_lessons->get_connected_lessons($course_id); 

	$args = array(
		'post_type'			=> array('lesson', 'wpc-quiz', 'wpc-module'),
		'post__in'			=> $lessons,
		'nopaging' 			=> true,
		'order'				=> 'ASC',
		'orderby'			=> 'menu_order',
		'posts_per_page'	=> -1,
	);

	$query = new WP_Query($args); 

	$data .= '<ul class="lesson-list">';

		while($query->have_posts()) {
			$query->the_post();

			$id = get_the_id();

			$icon = '<i class="fa fa-fw" style="width: 1em;"></i>';
			$class = '';

			$viewed_lessons = get_user_meta($user_id, 'wpc-lesson-tracking', true);
			$completed_lessons = get_user_meta($user_id, 'wpc-completed-lesson-tracking', true);

			if(!empty($viewed_lessons)){
				foreach($viewed_lessons as $viewed){
					if(is_array($viewed)){
						if($viewed['id'] == $id){
							$icon = '<i class="fa fa-eye"></i>';
							$class = 'wpc-viewed';
						}
					} else {
						if($viewed == $id){
							$icon = '<i class="fa fa-eye"></i>';
							$class = 'wpc-viewed';
						}
					}
				}
			}
			
			if(!empty($completed_lessons)){
				foreach($completed_lessons as $completed){
					if(is_array($completed)){
						if($completed['id'] == $id){
							$icon = '<i class="fa fa-check"></i>';
							$class = 'wpc-completed';
						}
					} else {
						if($completed == $id){
							$icon = '<i class="fa fa-check"></i>';
							$class = 'wpc-completed';
						}
					}
				}
			}

			if(get_post_type() == 'wpc-module') {
				$data .= '<h3 class="wpc-module-title">' . get_the_title($id) . '</h3>';
			} else {
				$data .= '<li><a href="' . get_the_permalink($id) . '" class="lesson-button ' . $class . '">' . $icon . ' ' . get_the_title($id) . '</li></a>';
			}
		}

	$data .= '</ul>';

	wp_reset_postdata(); 

	return $data;
}

function wpc_get_course_progress_table($user_id){

	$data = '';

	$wpc_courses = new WPC_Courses();

	$args = array(
		'post_type'			=> 'course',
		'posts_per_page'	=> -1,
		'paged'				=> false,
	);

	$query = new WP_Query($args);

	$data .= '<table class="widefat fixed wpc-sortable-table">';
	$data .= 	'<thead>
				<tr><th>' . __('Title', 'wp-courses') . '</th><th>' . __('Viewed Percent', 'wp-courses') . '</th><th>' . __('Completed Percent', 'wp-courses') . '</th></tr>
			</thead>';

		while($query->have_posts()){
			$query->the_post();

			$course_id = get_the_id();

			$link = is_admin() ? '?page=manage_students&student_id=' . $user_id . '&course_id=' . $course_id : '?course_id=' . $course_id . '&wpc_view=course_progress';

			$data .= '<tr>';
				$data .= '<td class="column-columnname">';
				$data .= '<strong>' . get_the_title() . '</strong><br><a href="' . $link . '">(' . __('view details') . ')</a>';
				$data .= '</td>';
				$data .= '<td class="column-columnname">' . $wpc_courses->get_progress_bar($course_id, $user_id, false) . '</td>';
				$data .= '<td class="column-columnname">' . $wpc_courses->get_progress_bar($course_id, $user_id, true) . '</td>';
			$data .= '</tr>';
		}

		$data .= 	'<tfoot>
				<tr><th>' . __('Title', 'wp-courses') . '</th><th>' . __('Viewed Percent', 'wp-courses') . '</th><th>' . __('Completed Percent', 'wp-courses') . '</th></tr>
		</tfoot>';


	$data .= '</table>';

	wp_reset_postdata(); 

	return $data;
}

function wpc_get_lesson_tracking_table($user_id, $viewed = true){

	$data = '';

	$user = get_userdata($user_id);

	$tracking = new WPC_Tracking();
	if($viewed == true){
		$lessons = get_user_meta($user_id, 'wpc-lesson-tracking', true);
		$text = __('viewed', 'wp-courses');
	} else {
		$lessons = get_user_meta($user_id, 'wpc-completed-lesson-tracking', true);
		$text = __('completed', 'wp-courses');
	}

	if(!empty($lessons)) {

			$data .= '<table class="widefat fixed wpc-sortable-table" cellspacing="0">';
		 		$data .= '<thead>
		 				<tr><th class="manage-column column-columnname" scope="col">' . __('Lesson Name', 'wp-courses') . '</th>
		 				<th class="manage-column column-columnname" scope="col">' . __('Course Name', 'wp-courses') . '</th>
		 				<th class="manage-column column-columnname" scope="col">' . __('Time', 'wp-courses') . '</th></tr></thead>';
		 		$data .= '<tbody>';

		 			$count = 0;

					foreach( $lessons as $view ){

						$lesson_id = is_array($view) ? $view['id'] : $view;
						$post_status = get_post_status( $lesson_id );

						$class = $count % 2 == 1 ? ' alternate' : '';

						if( is_array( $view ) ){
							if( array_key_exists( 'display', $view ) ){
								$display = $view['display'];
							} else {
								$display = 'true';
							}
						} else {
							$display = 'true';
						}

						if( get_post_type( $lesson_id ) != 'wpc-quiz' && $post_status == 'publish' ){
							$data .= '<tr class="' . $class . '" data-id="' . $lesson_id . '">';
								$data .= '<td class="column-columnname">';

									$data .= '<a href="' . get_the_permalink($lesson_id) . '">' . get_the_title($lesson_id) . '</a>'; 

								$data .= '</td>';

								$data .= '<td class="column-columnname">';

								$data .= get_the_title( get_post_meta( $lesson_id, 'wpc-connected-lesson-to-course', true ) );

								$data .= '</td>';

								$data .= '<td class="column-columnname">';
									$data .= is_array($view) ? date('l jS F Y H:i:s', $view['time']) : '';
								$data .= '</td>';

							$data .= '</tr>';
						}

						$count++;
					}

				$data .= '<tbody>';
				$data .= '<tfoot><tr><th class="manage-column column-columnname" scope="col">' . __('Lesson Name', 'wp-courses') . '</th>
						<th class="manage-column column-columnname" scope="col">' . __('Course Name', 'wp-courses') . '</th>
		 				<th class="manage-column column-columnname" scope="col">' . __('Time', 'wp-courses') . '</th></tr></tfoot>';
			$data .= '</table>';

	} else {
		$data .= $user->display_name . ' ' . __("hasn't", 'wp-courses') . ' ' . $text . ' ' . __("any lessons yet", 'wp-courses') . '.';
	}

	return $data;

}

/*
** REQUIREMENTS FUNCTIONS
*/

// returns a list of requirements
function wpc_get_ordering_list($post_type = 'wpc-requirement'){

	$data = '';

	$args = array(
        'post_type'         => $post_type,
        'orderby'           => 'menu_order',
        'nopaging'          => true,
        'order'             => 'ASC',
        'posts_per_page'    => -1,
    );

    $query = new WP_Query($args);

    if($query->have_posts()){
    	$data .= '<ul class="lesson-list">';

        while($query->have_posts()){
            $query->the_post();

            $id = get_the_ID();

            $data .= '<li class="lesson-button wpc-order-lesson-list-lesson" data-post-type="' . $post_type . '" data-id="' . get_the_ID() . '"><i class="fa fa-bars wpc-grab"></i> ' . get_the_title() . '<a href="' . get_edit_post_link($id) . '" style="float:right;"> (Edit)</a></li>';
        }

        $data .= '</ul>';
    } else {
    	$data = __('There is nothing to order', 'wp-courses') . '.';
    }

   	wp_reset_postdata();

    return $data;
}

// get rule by ID
function wpc_get_rule_by_ID($rule_id){

		global $wpdb;

		$table_name = $wpdb->prefix . 'wpc_rules';

		$sql = "SELECT id, post_id, course_id, module_id, lesson_id, type, action, percent, times FROM {$table_name} WHERE id = {$rule_id}";
		$results = $wpdb->get_results($sql);

		return $results;

}

// return rules rows
function wpc_get_rules($post_id){

	global $wpdb;

	$table = $wpdb->prefix . 'wpc_rules';

	$sql = "SELECT id, post_id, course_id, module_id, lesson_id, action, percent, type, times FROM {$table} WHERE post_id = {$post_id}";

	$results = $wpdb->get_results($sql);

	return $results;
}

// checks which rules have been completed and returns rule tracking
function wpc_check_rules($post_id, $user_id){

	if(!is_user_logged_in()){
		return;
	}

	$wpc_courses = new WPC_Courses();

	global $wpdb;

	$user_id = get_current_user_id();

	// get published courses
	$table_name = $wpdb->prefix . 'posts';
	$sql = "SELECT ID FROM {$table_name} WHERE post_status = 'publish' AND post_type = 'course'";
	$courses = $wpdb->get_results($sql);

	// get published modules
	$mod_table_name = $wpdb->prefix . 'posts';
	$mod_sql = "SELECT ID FROM $mod_table_name WHERE post_type = 'wpc-module' AND post_status = 'publish'";
	$modules = $wpdb->get_results($mod_sql);

	$rules = wpc_get_rules($post_id);

	$completed_lessons = get_user_meta($user_id, 'wpc-completed-lesson-tracking', true);
	$viewed_lessons = get_user_meta($user_id, 'wpc-lesson-tracking', true);

	$rule_tracking = array();

	$completed_rule = false;

	foreach($rules as $rule) {
		if($rule->action == 'views'){

			if($rule->type == 'specific-lesson'){
				$completed_rule = wpc_has_done($rule->lesson_id, $viewed_lessons) == true ? true : false;
			} elseif($rule->type == 'any-lesson'){
				$completed_rule = count($viewed_lessons) >= $rule->times ? true : false;
			} elseif($rule->type == 'specific-course'){
				$completed_rule = $wpc_courses->get_percent_viewed($rule->course_id) >= $rule->percent ? true : false;
			} elseif($rule->type == 'any-course'){
				$times = 0;
				foreach($courses as $course){
					if( $wpc_courses->get_percent_viewed($course->ID) >= $rule->percent ){
						$times++;
					}
				}
				if($times >= $rule->times){
					$completed_rule = true;
				}

			} elseif($rule->type == 'specific-module'){
				$completed_rule = $wpc_courses->get_module_percent_done($rule->module_id, $user_id, $viewed_lessons) >= $rule->percent ? true : false;
			} elseif($rule->type == 'any-module'){

				$times = 0;

				if(!empty($modules)) {
					foreach($modules as $module) {
						if( $wpc_courses->get_module_percent_done($module->ID, $user_id, $viewed_lessons) >= $rule->percent ){
							$times++;
						}
					}
				}

				if($times >= $rule->times){
					$completed_rule = true;
				}

			} elseif($rule->type == 'any-quiz'){

				$times = 0;

				if(!empty($viewed_lessons)){
					foreach($viewed_lessons as $viewed) {
						if( array_key_exists('post_type', $viewed) ) {
							if($viewed['post_type'] == 'wpc-quiz'){
								$times++;
							}
						}
					}
				}

				if($times >= $rule->times){
					$completed_rule = true;
				} else {
					$completed_rule = false;
				}

			} elseif($rule->type == 'specific-quiz'){
				$completed_rule = wpc_has_done($rule->lesson_id, $viewed_lessons) == true ? true : false;
			}

		} elseif($rule->action == 'completes'){

			if($rule->type == 'specific-lesson'){
				$completed_rule = wpc_has_done($rule->lesson_id, $completed_lessons) == true ? true : false;
			} elseif($rule->type == 'any-lesson'){

				$count = 0;

				// if this is clone lesson, get the ID of the original lesson so we're only counting the original lesson toward the completed count.

				if( !empty( $completed_lessons && is_array( $completed_lessons ) ) ) {
					foreach( $completed_lessons as $lesson ) {

						if( is_array( $lesson ) ) {

							$is_clone = wpc_is_clone( $lesson['id'] );

							if( $is_clone === false ){
								$count++;
							}

						} else {

							$is_clone = wpc_is_clone( $lesson );

							if( $is_clone === false ){
								$count++;
							}
						}

					}
				}

				$completed_rule = $count >= $rule->times ? true : false;

			} elseif($rule->type == 'specific-course'){
				$completed_rule = $wpc_courses->get_percent_completed($rule->course_id) >= $rule->percent ? true : false;
			} elseif($rule->type == 'any-course'){
				$times = 0;
				foreach($courses as $course){
					if( $wpc_courses->get_percent_completed($course->ID) >= $rule->percent ){
						$times++;
					}
				}
				if($times >= $rule->times){
					$completed_rule = true;
				} else {
					$completed_rule = false;
				}
			} elseif($rule->type == 'specific-module'){
				$completed_rule = $wpc_courses->get_module_percent_done($rule->module_id, $user_id, $completed_lessons) >= $rule->percent ? true : false;
			} elseif($rule->type == 'any-module'){

				$times = 0;

				if(!empty($modules)) {
					foreach($modules as $module) {
						if( $wpc_courses->get_module_percent_done($module->ID, $user_id, $completed_lessons) >= $rule->percent ){
							$times++;
						}
					}
				}

				if($times >= $rule->times){
					$completed_rule = true;
				} else {
					$completed_rule = false;
				}

			}  elseif($rule->type == 'any-quiz'){

				$times = 0;

				if(!empty($completed_lessons)){
					foreach($completed_lessons as $completed) {
						if( array_key_exists('post_type', $completed) ) {
							if($completed['post_type'] == 'wpc-quiz'){
								$times++;
							}
						}
					}
				}

				if($times >= $rule->times) {
					$completed_rule = true;
				} else {
					$completed_rule = false;
				}

			} elseif($rule->type == 'specific-quiz'){
				$completed_rule = wpc_has_done($rule->lesson_id, $completed_lessons) == true ? true : false;
			}

		} elseif($rule->action == 'scores') {

			$quiz_table_name = $wpdb->prefix . 'wpc_quiz_results';

			if($rule->type == 'specific-quiz'){
				// check if user has met or exceeded score requirements for specific quiz
				$quiz_sql = "SELECT user_ID, quiz_ID, score_percent FROM {$quiz_table_name} WHERE user_ID = {$user_id} AND score_percent >= {$rule->percent} AND quiz_ID = {$rule->lesson_id}";
				$quiz_results = $wpdb->get_results($quiz_sql);
				$count = $wpdb->num_rows;
				$completed_rule = $count >= 1 ? true : false;
			} elseif($rule->type == 'any-quiz'){
				// check if user has met or exceeded score and times requirements for any quiz
				$quiz_sql = "SELECT user_ID, score_percent FROM {$quiz_table_name} WHERE user_ID = {$user_id} AND score_percent >= {$rule->percent}";
				$quiz_results = $wpdb->get_results($quiz_sql);
				$count = $wpdb->num_rows;
				$completed_rule = $count >= $rule->times ? true : false;
			}
		}

		// push rule results to tracking array
		array_unshift($rule_tracking, array(
			'rule_id'		=> $rule->id,
			'rule_status'	=> $completed_rule,
		));

	}

	return $rule_tracking;

}



add_action('wp_footer', 'wpc_rule_evaluation_engine', 20);

function wpc_rule_evaluation_engine($post_type = null, $echo = true){

	if(!is_user_logged_in()){
		return;
	}

	// needed so works with ajax and footer hook
	if($post_type == null){
		$post_type = get_post_type();
	}

	$data = '';

	$award_badge = false;
	$award_certificate = false;
	$award_email = false;
	$new_status = null;

	// don't evaluate rules unless we're on a page that can effect rules
	if( $post_type == 'lesson' || $post_type == 'wpc-quiz'){

		// get all badges, certificates and emails
		$args = array(
			'post_type'			=> array('wpc-badge', 'wpc-certificate', 'wpc-email'),
			'posts_per_page'	=> -1,
			'paged'				=> false,
		);

		$query = new WP_Query($args);

		$user =  wp_get_current_user();

		$user_id = get_current_user_id();

		$requirement_tracking = get_user_meta($user_id, 'wpc-requirement-tracking', true);

		$tracking = array();

		$to_award = array();

		$home_url = home_url();

		if($query->have_posts()){

			while($query->have_posts()){

				$query->the_post();

				$post_id = get_the_ID();

				$rule_tracking = wpc_check_rules($post_id, $user_id);

				$old_status = false;

				$award_post_type = get_post_type();

				if(!empty($requirement_tracking)){
					foreach($requirement_tracking as $req) {
						if( $req['id'] == $post_id ){
							$old_status = $req['status'];
							break;
						}
					}
				}
				
				// check if all rules have been met
				foreach($rule_tracking as $rule) {
					if($rule['rule_status'] == true){
						$new_status = true;
					} else {
						$new_status = false;
						break;
					}
				}

				if($old_status == false && $new_status == true){
					// Requirements have been met.  Give award.

					$to_award[] = array(
						'id' 	=> $post_id,
						'type'	=> get_post_type(),
					);

					if($award_post_type == 'wpc-badge'){
						$award_badge = true;
					} elseif($award_post_type == 'wpc-certificate'){
						$award_certificate = true;
					} elseif($award_post_type == 'wpc-email'){
						$award_email = true;
					}
				}

				// push results to array so we can store in user meta
				array_unshift($tracking, array(
					'id'		=> $post_id,
					'type'		=> $post_type,
					'status'	=> $new_status,
					'rules'		=> $rule_tracking,
				));

			} // end while

			wp_reset_postdata();

			update_user_meta( $user_id, 'wpc-requirement-tracking', $tracking );

			$send_email = get_user_meta($user_id, 'wpc-email-status', true);

			// check if we should send the email(s)

			if($award_email == true  && $send_email != 'false') {

				$headers = array('Content-Type: text/html; charset=UTF-8');

				$headers = apply_filters('wpc_email_headers', $headers);

				$name = get_option('wpc_business_name');
				$unit = get_option('wpc_unit_number');
				$address = get_option('wpc_physical_address');
				$city = get_option('wpc_city');
				$state = get_option('wpc_state');
				$zip = get_option('wpc_zip_code');
				$country = get_option('wpc_country');

				$address = '<p><br><b>' . $name . '</b></br>' . $unit . ' ' . $address . '<br>';
				$address .= $city . ' ' . $state . ' ' . $zip . '<br>';
				$address .= $country . '</p>';

				$address = apply_filters('wpc_email_signature', $address);

				foreach($to_award as $email){
					if($email['type'] == 'wpc-email'){
						if(!empty($user->user_email)){
							// send the email

							$unsub = '<p class="unsub"><a href="' . $home_url . '?wpc-user-id=' . $user_id . '&wpc-unsub=true">' . __('Click here', 'wpc-emails') . '</a> ' . 'to unsubscribe from fututre emails from ' . $home_url . '</p>';

							$unsub = apply_filters('wpc_email_unsub', $unsub);

							$content = wpautop(get_post_field('post_content', $email['id'])) . '<br>' . $address . '<br>' . $unsub;
							$subject = html_entity_decode(get_the_title($email['id']));

							if(!empty($address) && !empty($city) && !empty($state) && !empty($zip) && !empty($country)){
								wp_mail($user->user_email, $subject , $content, $headers);
							}

						}
					}
				}
			}

			if($award_certificate == true) {
				// display awarded certificates
			}

			// display awarded badges
			if($award_badge == true){

				$text = __("You've Received an Award", 'wp-courses');

				$data .= '<div class="wpc-award-lightbox-wrapper"><div class="wpc-award-lightbox"><h4 class="wpc-lightbox-header">' . $text . '</h4><div class="wpc-lightbox-close"><i class="fa fa-times"></i></div><div class="wpc-award-lightbox-content">';

				foreach($to_award as $award){
					if($award['type'] == 'wpc-badge'){
						$data .= wpc_render_badge($award['id']);
					}
				}

				$data .= '</div></div></div>';

			} // end if

		} // end if

		if($echo === true){
			echo $data;
		} elseif($echo === false) {
			return $data;
		}

	} // end if
	
}


function wpc_has_requirement($post_id, $user_id = null){

	if($user_id === null) {
		$user_id = get_current_user_id();
	}

	$requirements = get_user_meta($user_id, 'wpc-requirement-tracking', true);

	if(empty($requirements)){
		return false;
	}

	$has = false;

	foreach($requirements as $requirement){
		if($post_id == $requirement['id']){
			if($requirement['status'] == true){
				$has = true;
				break;
			}
		} else {
			$has = false;
		}
	}

	return $has;

}

?>