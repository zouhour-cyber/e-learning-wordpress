<?php 

	add_shortcode('lesson_count', 'wpc_lesson_count');

	function wpc_lesson_count(){
		$args = array(
			'post_type' => 'lesson',
			'posts_per_page' => -1
		);
		$query = new WP_Query($args);
		return $query->post_count;
	}

	add_shortcode('course_count', 'wpc_course_count');

	function wpc_course_count(){
		$args = array(
			'post_type' => 'course',
			'posts_per_page' => -1
		);
		$query = new WP_Query($args);
		return $query->post_count;
	}

	// list all courses

function wpc_courses(){

		ob_start();

		$wp_courses = new WPC_Courses();
		$courses_per_page = (int) get_option('wpc_courses_per_page'); ?>

		<div class="wpc-shortcode-container">
			<div class="wpc-shortcode-row">

				<div class="wpc-sidebar wpc-left-sidebar">
					<?php echo $wp_courses->get_course_category_list(); ?>
				</div>

				<?php

				$args = array(
					'post_type'			=> 'course',
					'order'				=> 'ASC',
					'orderby'			=> 'menu_order',
					'post_status'		=> 'publish',
					'paged'				=> ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1,
					'posts_per_page'	=> $courses_per_page,
				);

				$query = new WP_Query($args);

				if ( $query->have_posts() ) { ?>
					<div id="courses-wrapper" class="wpc-sidebar-content">

					<?php include 'templates/template-parts/course-filters.php'; ?>

					<?php while ( $query->have_posts() ) {
						$query->the_post();
						$course_id = get_the_ID();
						$excerpt = get_the_excerpt($course_id);
						$course_video = get_post_meta($course_id, 'course-video', true);
						$teachers = get_post_meta( $course_id, 'wpc-connected-teacher-to-course', true );
						$teacher_link = get_the_permalink( $teacher_id, false ); ?>
						<div class="course-container wpc-light-box">
								<?php if($course_video != ''){
										if(strpos($course_video, 'iframe') === false && !empty($course_video)){
											if(preg_match("/[a-z]/i", $lesson_video) || preg_match("/[A-Z]/i", $course_video)){
											    $course_video = '<iframe id="video-iframe" width="560" height="315" src="https://www.youtube.com/embed/' . $course_video . '" frameborder="0" allowfullscreen></iframe>';
											} else {
												$course_video = '<iframe id="video-iframe" src="https://player.vimeo.com/video/' . $course_video . '" width="500" height="216" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
											}
										}
										echo $course_video;
								} else{
									echo get_the_post_thumbnail($course_id, 'large');
								} ?>
								<div class="course-excerpt">
									<h2 class="course-title">
										<a href="<?php echo get_the_permalink($course_id); ?>">
											<?php echo get_the_title($course_id); ?>	
										</a>
									</h2>
									<?php echo $excerpt; ?>
								</div>
								<?php echo $wp_courses->get_start_course_button($course_id); ?>
								<div class="course-meta-wrapper">
									<div class="cm-item">
										<span><?php echo __('Level', 'wp-courses') . ": " . $wp_courses->get_course_difficulty($course_id); ?>
										</span>
								</div>

									<div class="cm-item teacher-meta-wrapper">
										<?php if( $teachers != '-1' ) { ?>
											<?php echo __('Teacher', 'wp-courses') . ": "; ?>
											<?php 

												if( is_array( $teachers ) ) { 
													$length = count($teachers);
													$count = 1;
													foreach( $teachers as $teacher ) { ?>

														<?php $teacher_link = get_the_permalink( $teacher, false ); ?>
														
															<a href="<?php echo $teacher_link; ?>"><?php echo get_the_title( $teacher ); ?></a><?php echo $count < $length ? ', ' : ''; ?>

														<?php $count++; ?>

													<?php } // end foreach
												} else { ?>

													<?php $teacher_link = get_the_permalink( $teachers, false ); ?>

														<a href="<?php echo $teacher_link; ?>">
															<?php echo get_the_title( $teachers ); ?>
														</a>

												<?php } ?>

											<?php } ?>
									</div>

									<?php if(is_user_logged_in()){ ?>
										<div class="cm-item">
											<span><?php echo __('Viewed', 'wp-courses') . ": " . $wp_courses->get_percent_viewed($course_id); ?>%
											</span>
										</div>

										<?php echo $wp_courses->get_progress_bar($course_id); ?>
									<?php } ?>
								</div>
							</div>

					<?php } // end while

					$page_count = (int) wpc_course_count() / $courses_per_page;

					if ( $page_count > 1) {
					    $the_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					    $pagination = array(
					        'base' => @add_query_arg('paged','%#%'),
					        'format' => '?paged=%#%',
					        'mid-size' => 1,
					        'current' => $the_paged,
					        'total' => ceil($page_count),
					        'prev_next' => True,
					        'prev_text' => __( '<< Previous' ),
					        'next_text' => __( 'Next >>' )
					    ); ?>

					   	<br>

					   	<div class="wpc-paginate-links">
					   		<?php echo paginate_links( $pagination ); ?>
					   	</div>
					    
					<?php }

					wp_reset_postdata(); ?>

					</div>
				</div>
			</div>

			<div style="clear:both;"></div>

		<?php } else {
			_e('There are no courses', 'wp-courses') . '.';
		}
	return ob_get_clean();
}
// legacy shortcode
add_shortcode( 'courses', 'wpc_courses' );
// new shortcode with prefix
add_shortcode( 'wpc_courses', 'wpc_courses' );

// user page

function wpc_profile_page($atts){

	if(!is_user_logged_in() && !isset($atts['user_id'])){
		return '<div class="wpc-msg">' . __('You must be logged in to view your profile', 'wp-courses') . '.</div>';
	}

	$data = '';

	if(isset($atts['user_id'])){
		$user_id = $atts['user_id'];
	} else {
		$user_id = get_current_user_id();
	}

	$user = get_user_by('ID', $user_id); 

	$viewed_lessons = get_user_meta($user_id, 'wpc-lesson-tracking', true);

	if(!empty($viewed_lessons)){
		$last_viewed = $viewed_lessons[0];

		if(is_array($last_viewed)){
			$lesson_id = $last_viewed['id'];
		} else {
			$lesson_id = $last_viewed;
		}
	}
	
	$title = get_the_title($lesson_id);
	$breadcrumb = wpc_get_breadcrumb($lesson_id);
	$permalink = get_the_permalink($lesson_id);
	
	$wpc_courses = new WPC_Courses();
	
	if(!empty($viewed_lessons)) {
		$prev_next_ids = $wpc_courses->get_previous_and_next_lesson_ids($lesson_id);
	} else {
		$prev_next_ids = '';
	}
	
	$is_clone_id = wpc_is_clone( $lesson_id );
	
	if( $is_clone_id != false ) {
		$lesson_id = $is_clone_id;
	} else {
		$title = get_the_title($lesson_id);
	}

	$avatar = get_avatar($user_id, 240);

	$data .= '<div class="wpc-shortcode-container">';
		$data .= '<div class="wpc-shortcode-row" id="wpc-last-viewed-wrapper">';

			$data .= '<div class="wpc-sidebar wpc-sidebar-left">';
				$data .= '<div class="wpc-user-img">' . $avatar . '</div>';
				$data .= '<h3 class="wpc-username">' . $user->display_name . '</h3>';
			$data .= '</div>';

			$data .= '<div class="wpc-sidebar-content">';
				$data .= '<div class="wpc-tab-content">';

					if(!empty($viewed_lessons)) {

						$data .= '<h4 class="wpc-tab-content-header">' . __('Last Viewed Lesson', 'wp-courses') . '</h4>';
						$data .= $breadcrumb;
						$data .= '<h3>' . $title . '</h3>';
						$data .= '<div class="wpc-lesson-excerpt">' . get_the_excerpt($lesson_id) . '</div>';

						$data .= '<a class="wpc-button" href="' . $permalink . '">' . __('View Lesson', 'wp-courses') . '</a>';
						$data .= !empty($prev_next_ids['prev_id']) ? '<a class="wpc-button" href="' . get_the_permalink($prev_next_ids['prev_id']) . '"><i class="fa fa-arrow-left"></i> ' . __('Previous Lesson', 'wp-courses') . '</a>' : '';
						$data .= !empty($prev_next_ids['next_id']) ? '<a class="wpc-button" href="' . get_the_permalink($prev_next_ids['next_id']) . '">' . __('Next Lesson', 'wp-courses') . ' <i class="fa fa-arrow-right"></i></a>' : '';	
					} else {
						$data .= $user->display_name . ' ' . __("hasn't viewed any lessons yet.", "wp-courses");
					}


				$data .= '</div>';
			$data .= '</div>';
		$data .= '</div>';
	$data .= '</div>';

	$data .= '<div class="wpc-shortcode-container">';
		$data .= '<div class="wpc-shortcode-row">';

				$viewed_class = $completed_class = $progress_class = '';

				if(isset($_GET['wpc_view'])){
					$viewed_class = $_GET['wpc_view'] == 'viewed' ? 'wpc-tab-active' : '';
					$completed_class = $_GET['wpc_view'] == 'completed' ? 'wpc-tab-active' : '';
					$progress_class = $_GET['wpc_view'] == 'course_progress' ? 'wpc-tab-active' : '';
				} else {
					$viewed_class = 'wpc-tab-active';
				}

				$tabs = '<a class="wpc-tab ' . $viewed_class . '" href="?wpc_view=viewed"><i class="fa fa-eye"></i> ' . __('Viewed', 'wp-courses') . '</a>';

				$show_completed_lessons = get_option('wpc_show_completed_lessons');

				$tabs .= $show_completed_lessons == 'true' ? '<a class="wpc-tab ' . $completed_class . '" href="?wpc_view=completed"><i class="fa fa-check"></i>' .  __('Completed', 'wp-courses') . '</a>' : ''
				;
				$tabs .= '<a class="wpc-tab ' . $progress_class . '" href="?wpc_view=course_progress"><i class="fa fa-bar-chart"></i> ' . __('Progress', 'wp-courses') . '</a>';

				$tabs = apply_filters('wpc_profile_tabs', $tabs);

				$data .= $tabs;

				$data .= '<div class="wpc-tab-content">';

					$tab_content = '';

					if(isset($_GET['wpc_view'])){

						if($_GET['wpc_view'] == 'viewed'){
							$tab_content .= wpc_get_lesson_tracking_table($user_id, true);
						} elseif($_GET['wpc_view'] == 'completed'){
							$tab_content .= wpc_get_lesson_tracking_table($user_id, false);
						} elseif($_GET['wpc_view'] == 'course_progress') {
							if(isset($_GET['course_id'])){
								$tab_content .= '<h3 class="wpc-tab-content-header" style="background: #fff;">' . get_the_title($_GET['course_id']) . '</h3>';
								$tab_content .= '<a class="wpc-button" href="?wpc_view=course_progress" style="margin-bottom: 20px;"><i class="fa fa-arrow-left"></i> Back</a>';
								$tab_content .= wpc_get_single_course_progress_list($_GET['course_id'], $user_id);
							} else {
								$tab_content .= wpc_get_course_progress_table($user_id);
							}
						}

					} else {
						$tab_content .= wpc_get_lesson_tracking_table($user_id, true);
					}

					$tab_content = apply_filters('wpc_profile_tab_content', $tab_content);

					$data .= $tab_content;

				$data .= '</div>';

				$content = '';

				$content = apply_filters('wpc_after_user_profile_content', $content);

				$data .= $content;

			$data .= '</div>';
		$data .= '</div>';
	$data .= '</div>';

	$data .= '<div style="clear:both;"></div>';

	return $data;
}

add_shortcode('wpc_profile', 'wpc_profile_page');

?>