<?php
	
	class WPC_Courses{
		public function get_connected_lessons_to_module($module_id){
			$args = array(
				'post_type'			=> array('lesson', 'wpc-quiz'),
				'meta_value'		=> $module_id,
				'meta_key'			=> 'connected_lesson_to_module',
				'posts_per_page'	=> -1,
				'nopaging' 			=> true,
				'order'				=> 'ASC',
				'orderby'			=> 'menu_order',
				'fields' 			=> 'ids'
			);
			$query = new WP_Query($args);

			return $query->posts;
		}
		public function get_module_percent_done($module_id, $user_id, $tracking){

			$wp_courses = new WPC_Courses();

			$connected_lessons = $wp_courses->get_connected_lessons_to_module( $module_id );

			$count = 0;

			foreach( $connected_lessons as $lesson ){
				if( wpc_has_done($lesson, $tracking) == true ){
					$count++;
				}
			}

			if($count == 0){
				return 0;
			} else {
				return ( $count / count( $connected_lessons ) ) * 100;
			}
			
		}
		public function get_previous_and_next_lesson_ids($lesson_id) {

			$course_id = get_post_meta($lesson_id, 'wpc-connected-lesson-to-course', true);

			if($course_id == 'none'){
				return array(
					'prev_id' => false,
					'next_id'	=> false
				);
			}

			$args = array(
				'post_type'			=> array('lesson', 'wpc-quiz'),
				'meta_value'		=> $course_id,
				'meta_key'			=> 'wpc-connected-lesson-to-course',
				'posts_per_page'	=> -1,
				'paged'				=> false,
				'nopaging' 			=> true,
				'order'				=> 'ASC',
				'orderby'			=> 'menu_order'
			);

			$query = new WP_Query($args);

			$count = 0;
			$break = false;

			while($query->have_posts()){
				$query->the_post();

				$current_lesson = get_the_ID();

				if( $current_lesson == $lesson_id ){
					$next_id = array_key_exists($count + 1, $query->posts) ? $query->posts[$count + 1]->ID : false;
					$prev_id = array_key_exists($count - 1, $query->posts) ? $query->posts[$count - 1]->ID : false;
					$break = true;
					break;
				}

				$count++;

			}

			wp_reset_postdata();

			if( $break === true ){
				return array(
					'prev_id' => $prev_id,
					'next_id'	=> $next_id
				);
			}

		}
		public function get_percent_viewed($course_id, $user_id = null){
			if($user_id == null){
				$user_id = get_current_user_id();
			}
			$wpc_lessons = new WPC_Lessons();
			$all_lessons = $wpc_lessons->get_connected_lessons($course_id, array('lesson', 'wpc-quiz'));
			$wpc_tracking = new WPC_Tracking;
			$viewed_lessons = $wpc_tracking->get_lesson_tracking($user_id);

			$viewed_count = 0;

			if(!empty($viewed_lessons)){
				foreach($viewed_lessons as $viewed){
					if(is_array($viewed)){
						if(in_array( $viewed['id'], $all_lessons )) {
							$viewed_count++;
						}
					} else {
						if(in_array( $viewed, $all_lessons )) {
							$viewed_count++;
						}
					}
				}		
			}


			$lesson_count = count($all_lessons);
			if( $lesson_count > 0 ) {
				$percent_viewed = $viewed_count / count($all_lessons); 
				$percent_viewed = $percent_viewed * 100;
			} else {
				$percent_viewed = 0;
			}
			return round($percent_viewed);
		}
		public function get_percent_completed($course_id, $user_id = null){

			if($user_id == null){
				$user_id = get_current_user_id();
			}

			$wpc_lessons = new WPC_Lessons();
			$all_lessons = $wpc_lessons->get_connected_lessons($course_id, array('lesson', 'wpc-quiz'));
			$all_lessons_new = array();
			$completed_tracking = get_user_meta($user_id, 'wpc-completed-lesson-tracking', true);

			$count = 0;

			/*foreach($all_lessons as $lesson_id) {
				// $alias_id = get_post_meta( $lesson_id, 'wpc-lesson-alias-id', true);

				if(!empty($alias_id) && $alias_id != 'none'){
					$lesson_id = $alias_id;
				} 

				$all_lessons_new[] = $lesson_id;

			}*/

			if(!empty($completed_tracking)){
				foreach( $completed_tracking as $complete ){
					if( is_array($complete) ){
						if( in_array( $complete['id'], $all_lessons ) ) {
							$count++;
						}
					} else {
						if( in_array( $complete, $all_lessons ) ) {
							$count++;
						}
					}
				}
			}

			$lesson_count = count($all_lessons);

			if( $lesson_count > 0 ) {
				$percent_viewed = $count / count($all_lessons); 
				$percent_viewed = $percent_viewed * 100;
			} else {
				$percent_viewed = 0;
			}
			return round($percent_viewed);
		}
		public function get_progress_bar($course_id, $user_id = null, $completed = true){
			if($user_id == null){
				$user_id = get_current_user_id();
			}
			if($completed == true){
				$completed_percent = $this->get_percent_completed($course_id, $user_id);
				$text = __('Completed', 'wp-courses');
				$class = "wpc-complete-progress";
				$icon = '<i class="fa fa-check"></i> ';
			} else {
				$completed_percent = $this->get_percent_viewed($course_id, $user_id);
				$text = __('Viewed', 'wp-courses');
				$class = "wpc-viewed-progress";
				$icon = '<i class="fa fa-eye"></i> ';
			}

			$data = '<div class="wpc-progress-bar"><div class="wpc-progress-bar-level ' . $class . '" style="width:' . $completed_percent . '%;"><div class="wpc-progress-bar-text">' . $icon . $completed_percent . '% ' . $text . '</div></div></div>';
			return $data;
		}
		public function get_course_category_list($class = ''){
			$data = '';
			$categories = get_terms('course-category');
			$cat = get_queried_object();
			$cat = isset($cat->slug) ? $cat->slug : '';
			$data .= '<div class="course-category-list">';
			$data .= '<h3 class="wpc-course-categories-header">' . __('Course Categories', 'wp-courses') . '</h3>';
			$data .= '<ul class="wpc-course-categories-list">';
			$active = is_post_type_archive('course') == TRUE ? 'active' : '';
			$data .= '<li><a id="wpc-all-categories-button" href="' . get_post_type_archive_link( 'course' ) . '" class="wpc-button ' . $active . '">' . __('All', 'wp-courses') . '</a></li>';
			if(!empty($categories)){
				foreach($categories as $category){

					$category->slug == $cat ? $active = 'active' : $active = '';

					$data .= '<li><a href="' . get_term_link($category) . '" class="wpc-button ' . $class . ' ' . $active . '">' . $category->name . '</a></li>';

				}
			} else {
				return 'There are no course categories.';
			}
			$data .= '</ul>';
			$data .= '</div>';

			if( has_filter( 'wpc_course_category_list' ) ){
				$data = apply_filters( 'wpc_course_category_list', $data );
			}

			return $data;
		}
		public function course_list($get = ''){
			$course_args = array(
				'post_type'			=> 'course',
				'nopaging' 			=> true,
				'order'				=> 'ASC',
				'orderby'			=> 'menu_order',
				'post_status'		=> 'publish',
			);
			$course_query = new WP_Query($course_args);
			$data = '';
			$data .= '<ul class="course-list">';
			while($course_query->have_posts()){
				$course_query->the_post();
				$data .= '<li class="lesson-button" data-id="' . get_the_ID() . '"><i class="fa fa-bars"></i> ' . get_the_title() . '</li>';
			}
			$data .= '</ul>';
			wp_reset_postdata();

			return $data;
		}
		public function get_course_difficulty($post_id){
			$data = '';
			$terms = wp_get_post_terms($post_id, 'course-difficulty');
			foreach($terms as $term){
				$data .= $term->name;
			}
			if(!empty($term)){
				return '<span class="difficulty-' . $term->slug . ' course-difficulty">' . $data . '</span>';
			} else {
				return '';
			}
		}
		public function get_start_course_button($course_id){
			$course_id = (empty($course_id)) ? get_the_ID() : $course_id;
			$args = array(
				'post_type'			=> 'lesson',
				'meta_value'		=> $course_id,
				'meta_key'			=> 'wpc-connected-lesson-to-course',
				'posts_per_page'	=> 1,
				'paged'				=> false,
				'nopaging' 			=> true,
				'order'				=> 'ASC',
				'orderby'			=> 'menu_order'
			);
			$query = new WP_Query($args);
			if($query->have_posts()){
				while($query->have_posts()){
					$query->the_post();

					$course_link = get_the_permalink();

					break;
				}
			}
			
			wp_reset_postdata();

			if(empty($course_link)){
				$button = '';
			} else {
				$button = '<a class="start-button wpc-button" href="' . $course_link . '">' . __('Start Course', 'wp-courses') . ' <i class="fa fa-arrow-right"></i></a>';
			}

			if(has_filter('wpc_start_course_button')){
				$button = apply_filters( 'wpc_start_course_button', $button );
			}

			return $button;

		}
	}
	class WPC_Lessons{
		public function __construct(){
			$this->post_id = get_the_ID();
		}
		public function get_lesson_attachments($lesson_id){

			$attachments = array();
			for($i = 1; $i<=3; $i++){
				$url = get_post_meta( $lesson_id, 'wpc-media-sections-' . $i, true );
				if(!empty($url)){
					array_push($attachments, $url);
				}

			}
			return $attachments;
		}
		public function get_lesson_video($lesson_id){
			
			$data = '';

			$lesson_video = get_post_meta($lesson_id, 'lesson-video', true);

			if(empty($lesson_video)){
				$lesson_video = '';
			} elseif( strpos( $lesson_video, '[' ) !== false ){
				$lesson_video = do_shortcode($lesson_video);
			} elseif( strpos( $lesson_video, 'iframe' ) !== false ){
				// it's an iframe, so return as is
				$lesson_video = $lesson_video;
			} elseif(strpos($lesson_video, 'youtu.be' )){
				// it's a YT video with shortened url
				$lesson_video = str_replace('youtube.be/', 'https://www.youtube.com/watch?v=', $lesson_video);
				$lesson_video = wp_oembed_get( $lesson_video );
			}elseif( strpos($lesson_video, 'youtube.com' ) || strpos($lesson_video, 'vimeo.com')) {
				// it's a youtube or vimeo video using a url 
				$lesson_video = wp_oembed_get( $lesson_video );
			} elseif( preg_match("/[a-z]/i", $lesson_video) || preg_match("/[A-Z]/i", $lesson_video )){
				// it's a YT video with code only (ie. CvL5Amq0e8w)
				$lesson_video = '<iframe class="wpc-video" id="video-iframe" width="560" height="315" src="https://www.youtube.com/embed/' . $lesson_video . '" frameborder="0" allowfullscreen></iframe>';
			} elseif( preg_match("/[a-z]/i", $lesson_video) == 0 || preg_match("/[A-Z]/i", $lesson_video) == 0 ){ 
				// it's not a YT video with code only (ie. CvL5Amq0e8w).  Assumed to be Vimeo.
				$lesson_video = '<iframe class="wpc-video" id="video-iframe" src="https://player.vimeo.com/video/' . $lesson_video . '" width="500" height="216" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
			}

			if(has_filter( 'wpc_lesson_video' )){
				$lesson_video = apply_filters( 'wpc_lesson_video', $lesson_video );
			}

			return $lesson_video;
			
		}
		public function get_connected_lessons($course_id, $post_types = array('lesson', 'wpc-module', 'wpc-quiz')){

			$args = array(
				'post_type'			=> $post_types,
				'meta_value'		=> $course_id,
				'meta_key'			=> 'wpc-connected-lesson-to-course',
				'posts_per_page'	=> -1,
				'nopaging' 			=> true,
				'order'				=> 'ASC',
				'orderby'			=> 'menu_order',
				'fields' 			=> 'ids'
			);
			$query = new WP_Query($args);

			return $query->posts;
		}
		public function get_lesson_list($course_id){
			$data = '';
			$lessons = $this->get_connected_lessons( $course_id );
			if(!empty($lessons)){
				$args = array(
					'post_type'			=> 'lesson',
					'post__in'			=> $lessons,
					'nopaging' 			=> true,
					'order'				=> 'ASC',
					'orderby'			=> 'menu_order'
				);
				$query = new WP_Query($args);
				$data .= '<ul class="lesson-list">';
				while($query->have_posts()){
					$query->the_post();
					$id = get_the_ID();
					$link = get_edit_post_link( $id );

					$data .= '<li data-id="' . $id . '"><a href="' . $link . '">' . get_the_title() . '</a></li>';
				
				}
				$data .= '</ul>';
				wp_reset_postdata();
			}
			return $data;
		}
		public function get_lesson_navigation($course_id){
			$show_lesson_numbers = get_option('wpc_show_lesson_numbers');
			$data = '';
			$count = 1;
			$lessons = $this->get_connected_lessons($course_id);
			$tracking = new WPC_Tracking();
			$tracking = $tracking->get_lesson_tracking();
			$completed_tracking = get_user_meta(get_current_user_id(), 'wpc-completed-lesson-tracking', true);
			if(is_array($completed_tracking) == false){
				$completed_tracking = array();
			}
			if(!empty($lessons)){
				$args = array(
					'post_type'			=> array('lesson', 'wpc-quiz', 'wpc-module'),
					'post__in'			=> $lessons,
					'nopaging' 			=> true,
					'order'				=> 'ASC',
					'orderby'			=> 'menu_order',
					'posts_per_page'	=> -1,
				);
				$query = new WP_Query($args);
				$data .= '<ul class="lesson-nav">';
				$this_post_id = get_the_ID();
				while($query->have_posts()){

					$query->the_post();
					$lesson_button_id = get_the_ID();
					$post_id = $lesson_button_id;
					$restriction = get_post_meta( $post_id, 'wpc-lesson-restriction', true );
					$show_lesson_nav_icons = get_option('wpc_show_lesson_nav_icons');

					if($show_lesson_nav_icons == 'true'){
						if( is_user_logged_in() ){

							$icon = '<i class="fa fa-play wpc-default-status"></i>';

							foreach($tracking as $viewed){
								if(is_array($viewed)){
									if($post_id == $viewed['id']){
										$icon = '<i class="fa fa-eye wpc-default-status"></i>';
									}
								} else {
									if($post_id == $viewed){
										$icon = '<i class="fa fa-eye wpc-default-status"></i>';
									}
								}
							}

							foreach($completed_tracking as $completed){
								if(is_array($completed)){
									if($post_id == $completed['id']){
										$icon = '<i class="fa fa-check wpc-default-status"></i>';
									}
								} else {
									if($post_id == $completed){
										$icon = '<i class="fa fa-check wpc-default-status"></i>';
									}
								}
							}
							
						} else {
							if($restriction == 'none'){
								$icon = '<i class="fa fa-play wpc-default-status"></i>';
							} else {
								$icon = '<i class="fa fa-lock wpc-default-status"></i>';
							}
						}
					} else {
						$icon = '';
					}
								
					if(has_filter( 'wpc_lesson_button_icon' )){
			            $icon = apply_filters( 'wpc_lesson_button_icon', $icon, $post_id );
			        }
					
					if($this->post_id == $lesson_button_id){
						$active = ' class="active-lesson-button"';
					} else{
						$active = '';
					}

					if($show_lesson_numbers == 'true'){
						$display_count = $count . ' - ';
					} else {
						$display_count = ' ';
					}

					if(get_post_type() == 'wpc-module'){
						$data .= '<h3 class="wpc-module-title">' . get_the_title() . '</h3>';
					} else {
						$data .= '<li' . $active . '><a data-lesson-button-id="' . $post_id . '" class="lesson-button" href="' . get_the_permalink() . '">' . $icon . $display_count . get_the_title() . '</a></li>';	
						$count++;
					}


				}
				$data .= '</ul>';

				$module_exists = false;

				wp_reset_postdata();
			}
			return $data;
		}
	}
	class WPC_Tracking{
		// call this function to initiate tracking.
		public function lesson_tracking($post_id){
			if(!is_user_logged_in()){
				return;
			}
			$user_id = get_current_user_id();
			$viewed_lessons = get_user_meta($user_id, 'wpc-lesson-tracking', true);
			if($viewed_lessons === ''){
				$viewed_lessons = array(
					array(
						'id' 		=> $post_id,
						'post_type'	=> get_post_type(),
						'time'		=> time(),
					),
				);
				update_user_meta($user_id, 'wpc-lesson-tracking', $viewed_lessons );
			} else{

				// check if is array to support legacy data model before saving a multi-dimensional array
				foreach($viewed_lessons as $key => $value){
					if(is_array($value)){
						if($post_id == $value['id']){
							unset($viewed_lessons[$key]);
						}
					} else {
						if($post_id == $value){
							unset($viewed_lessons[$key]);
						}
					}
				}

				array_unshift($viewed_lessons, array(
					'id' 		=> $post_id,
					'post_type'	=> get_post_type(),
					'time'		=> time(),
				));

				update_user_meta($user_id, 'wpc-lesson-tracking', $viewed_lessons);
			}
			return;
		}
		// return array of viewed lesson ids
		public function get_lesson_tracking($user_id = null){
			if($user_id === null){
				$user_id = get_current_user_id();
			}
			$lessons = get_user_meta($user_id, 'wpc-lesson-tracking', true);
			if(empty($lessons)){
				$lessons = array();
			}
			return $lessons;
		}
		// This function checks if course is completed and updates user meta accordingly
		public function course_tracking(){
			$user_id = get_current_user_id();
			$wpc_courses = new WPC_Courses();
			$lesson_id = get_the_ID();
			$course_id = get_post_meta($lesson_id, 'wpc-connected-lesson-to-course', true);
			$percent = $wpc_courses->get_percent_viewed($course_id);
			$completed_courses = get_user_meta($user_id, 'wpc-completed-courses', true );
			 
			if($percent == 100){
				if(empty($completed_courses)){
					$completed_courses = array($course_id);
					update_user_meta($user_id, 'wpc-completed-courses', $completed_courses );
				} else{
					array_push($completed_courses, $course_id);
					$completed_courses = array_unique($completed_courses);
					update_user_meta($user_id, 'wpc-completed-courses', $completed_courses);
				}
			}
		}
	}
	class WPC_Tools{
		public function get_toolbar(){
			$data = '';
			$user_id = get_current_user_id();
			$show_viewed_lessons_button = get_option('wpc_show_viewed_lessons', 'true');
			$tracking = new WPC_Tracking();
			$lesson_id = get_the_ID();

			$wpc_courses = new WPC_Courses();
			$links = $wpc_courses->get_previous_and_next_lesson_ids($lesson_id);
			$post_type = get_post_type();
			if( is_user_logged_in()){

					$data .= '<div class="tools-container">';

					// show previous lesson button if exists
					if($links['prev_id'] !== false){
						$data .= '<div id="wpc-prev-lesson" class="wpc-button"><a href="' . get_permalink($links['prev_id']) . '"><i class="fa fa-arrow-left"></i> ' . __('Previous', 'wp-courses') . '</a></div>';
					}

					// show next lesson button if exists
					if($links['next_id'] !== false){
						$data .= '<div id="wpc-next-lesson" class="wpc-button"><a href="' . get_permalink($links['next_id']) . '">' . __('Next' , 'wp-courses') . ' <i class="fa fa-arrow-right"></i></a></div>';
					}

					$show_completed_button = get_option('wpc_show_completed_lessons');

					if(is_user_logged_in() && $show_completed_button == 'true' && $post_type != 'wpc-quiz'){

						$completed_lessons = get_user_meta( $user_id, 'wpc-completed-lesson-tracking', true );

						$completed = false;

						if(!empty($completed_lessons)){
							foreach( $completed_lessons as $complete ){
								if( is_array($complete) ){
									if( $lesson_id == $complete['id'] ) {
										$completed = true;
									}
								} else {
									if( $lesson_id == $complete ) {
										$completed = true;
									}
								}
							}
						} else {
							$completed = false;
						}				

						if($completed_lessons) {
							if($completed == true){
								$completed = 'true';
								$btn_text = __('Completed', 'wp-courses');
								$completed_icon = '<i class="fa fa-check-square-o"></i>';
							} else {
								$completed = 'false';
								$btn_text = __('Mark Completed', 'wp-courses');
								$completed_icon = '<i class="fa fa-square-o"></i>';
							}
						} else {
							$completed = 'false';
							$btn_text = __('Not Completed', 'wp-courses');
							$completed_icon = '<i class="fa fa-square-o"></i>';
						}
						

						$data .= '<div id="wpc-completed-lesson-toggle" data-id="' . get_the_ID() . '" data-status="' . $completed . '" class="wpc-button">';
							$data .= $completed_icon . ' ';
							$data .= $btn_text;
						$data .= '</div>';

					}

					$orig_id = get_post_meta( $lesson_id, 'wpc-lesson-alias-id', true);

					// if is clone lesson, get the ID of the original lesson so we can output the content of the original kesson
					if(!empty($orig_id) && $orig_id != 'none'){
						$lesson_id = $orig_id;
					} 

					$attachments = new WPC_Lessons();
					$attachments = $attachments->get_lesson_attachments($lesson_id);
					
					if(!empty($attachments)) {
						$data .= '<div id="wpc-attachments-toggle" class="wpc-button">';
							$data .= '<i class="fa fa-file-image-o"></i>';
							$data .= ' ' . __('Lesson Attachments', 'wp-courses');
						$data .= '</div>';
					}

					$data .= '<div id="wpc-attachments-content" class="toolbar-content wpc-tab-content"><h3>' . __('Lesson Attachments', 'wp-content') . '</h3>';
						foreach($attachments as $att){
							$data .= '<a class="toolbar-button" href="' . $att . '">' . basename($att) . '</a>';
						}
					$data .= '</div>';

				$data .= '</div>';
			}
			wp_reset_postdata();
			return $data;
		}
	}
?>