<?php

	// add requirement

	add_action( 'admin_footer', 'wpc_action_add_requirement_javascript' );

	function wpc_action_add_requirement_javascript() { ?>
		<?php $ajax_nonce = wp_create_nonce( "wpc-add-requirement-nonce" ); ?>
		<script type="text/javascript" >

		function renderRequirements(data){

			var requirementData = JSON.parse(data);

			var courseSelect = jQuery('#wpc-hidden-course-select').clone(true);

			var html = '';

			html += '<form class="wpc-requirement" data-requirement-id="' + requirementData[0].id + '">';

				html += '<label>' + WPCAdminTranslations.whenSomeone + ': </label>';
				html += '<select name="wpc-requirement-action" class="wpc-requirement-action">';
					html += ' <option value="views">' + WPCAdminTranslations.views + '</option>';
					html += ' <option value="completes">' + WPCAdminTranslations.completes + '</option>';
					html += ' <option value="scores">' + WPCAdminTranslations.scores + '</option>';
				html += ' </select>';

				html += ' <select name="wpc-requirement-type" class="wpc-requirement-type">';
					html += ' <option value="any-course">' + WPCAdminTranslations.anyCourse + '</option>';
					html += ' <option value="specific-course">' + WPCAdminTranslations.aSpecificCourse + '</option>';
					html += ' <option value="any-lesson">' + WPCAdminTranslations.anyLesson + '</option>';
					html += ' <option value="specific-lesson">' + WPCAdminTranslations.aSpecificLesson + '</option>';
					html += '<option value="any-module">' + WPCAdminTranslations.anyModule + '</option>';
					html += '<option value="specific-module">' + WPCAdminTranslations.aSpecificModule + '</option>';
					html += '<option value="any-quiz">' + WPCAdminTranslations.anyQuiz + '</option>';
					html += '<option value="specific-quiz">' + WPCAdminTranslations.aSpecificQuiz + '</option>';
				html += ' </select>';

				html += courseSelect.html();

				html += '<select class="wpc-requirement-lesson-select wpc-hide"><option value="none">' + WPCAdminTranslations.none + '</option></select>';

				html += '<label class="wpc-percent-label">' + WPCAdminTranslations.percent + ': </label><input name="wpc-percent" type="number" min="1" max="100" value="0" class="wpc-percent" novalidate/>';

				html += '<label class="wpc-times-label">' + WPCAdminTranslations.times + ': </label><input name="wpc-times" type="number" min="1" value="1" class="wpc-requirement-times" novalidate/><br>';

				html += '<button class="wpc-delete-requirement button" type="button" data-requirement-id="' + requirementData[0].id + '">' + WPCAdminTranslations.deleteRequirement + '</button>';

			html += '</form>';

			jQuery('#wpc-requirements').append(html);

		}

		// pass an array of lesson IDs to return lesson select

		function lessonOptions(data){
			var html = '';

			data = JSON.parse(data);

			html += '<option value="none">' + WPCAdminTranslations.none + '</option>'

			for(i=0; i<data.length; i++){
				html += '<option value="' + data[i].id + '">' + data[i].title + '</option>'
			}

			return html;
		}

		jQuery(document).ready(function($) {

			jQuery('#wpc-add-new-requirement').click(function(){
				var data = {
					'security'				: "<?php echo $ajax_nonce; ?>",
					'action'				: 'add_requirement',
					'post_id'				: "<?php the_ID(); ?>",
					'course_id'				: '',
					'lesson_id'				: '',
					'module_id'				: '',
					'required_action' 		: 'views', // views or completes
					'type' 					: 'any-course', // any-course, specific-course, any-lesson, specific-lesson
					'times' 				: '1',
					'percent' 				: '' // numeric value
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function(response) {
					wpcHideAjaxIcon();

					var notice = jQuery('.wpc-requirement-notice');

					if(notice.length > 0) {
						notice.hide();
					}

					renderRequirements(response);
				});
			});

		});
		</script> <?php
	}
	add_action( 'wp_ajax_add_requirement', 'wpc_add_requirement_action_callback' );
	function wpc_add_requirement_action_callback(){

		check_ajax_referer( 'wpc-add-requirement-nonce', 'security' );

		global $wpdb;
		$table = $wpdb->prefix . 'wpc_rules';

		// insert new rule to db

		$wpdb->insert($table,
			array(
				'post_id' 				=> $_POST['post_id'],
				'action' 				=> $_POST['required_action'],
				'type'					=> $_POST['type'],
				'times'					=> $_POST['times'],
				'percent'				=> $_POST['percent'],
				'course_id'				=> $_POST['course_id'],
				'lesson_id'				=> $_POST['lesson_id'],
				'module_id'				=> $_POST['module_id'],
			),
			array(
		    	'%d',
		    	'%s',
		    	'%s',
		    	'%d',
		    	'%d',
		    	'%d',
		    	'%d',
		    	'%d',
			) 
		);

		// return all requrirements so we can render them with JS

		$last_id = $wpdb->insert_id;

		$requirements = $wpdb->get_results( 
			"
			SELECT id, post_id, course_id, action, type, times, percent 
			FROM $table
			WHERE id = $last_id
			"
		);

		echo json_encode($requirements);

	    wp_die(); // required
	}

	// delete requirement ajax

	add_action( 'admin_footer', 'wpc_action_delete_requirement_javascript' );

	function wpc_action_delete_requirement_javascript() { ?>
		<?php $ajax_nonce = wp_create_nonce( "wpc-delete-requirement-nonce" ); ?>
		<script type="text/javascript" >

		jQuery(document).ready(function($) {

			jQuery(document).on('click', '.wpc-delete-requirement', function(){

				var clickedDelElem = $(this);

				var data = {
					'security'				: "<?php echo $ajax_nonce; ?>",
					'action'				: 'delete_requirement',
					'requirement_id' 		: clickedDelElem.attr('data-requirement-id')
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function(response) {
					wpcHideAjaxIcon();
					// console.log(response);
					clickedDelElem.parent().fadeOut();
				});
			});

		});
		</script> <?php
	}

	add_action( 'wp_ajax_delete_requirement', 'wpc_delete_requirement_action_callback' );
	function wpc_delete_requirement_action_callback(){

		check_ajax_referer( 'wpc-delete-requirement-nonce', 'security' );

		global $wpdb;

		$table = $wpdb->prefix . 'wpc_rules';
		$id = $_POST['requirement_id'];

		$return = $wpdb->delete( $table, array( 'id' => $id ) );

		if($return != false){
			echo $id;
		}

	    wp_die(); // required
	}


	// change course ajax

	add_action( 'admin_footer', 'wpc_action_change_requirement_course_javascript' );

	function wpc_action_change_requirement_course_javascript() { ?>
		<?php $ajax_nonce = wp_create_nonce( "wpc-change-requirement-course-nonce" ); ?>
		<script type="text/javascript" >

		jQuery(document).ready(function($) {

			jQuery(document).on('change', '.wpc-requirement-courses-select', function(){

				var changedElem = $(this);

				var requirementID = changedElem.parent().attr('data-requirement-id');

				var selectedCourseID = $(this).children('option:selected').val();

				var requirementType = $(this).siblings('.wpc-requirement-type').val();

				if( requirementType == 'specific-lesson' || requirementType == 'specific-module' || requirementType == 'specific-course' || requirementType == 'specific-quiz' ) {

					var data = {
						'security'				: "<?php echo $ajax_nonce; ?>",
						'action'				: 'change_requirement_course',
						'requirement_id'		: requirementID,
						'course_id' 			: selectedCourseID,
						'type'					: requirementType
					}

					wpcShowAjaxIcon();

					jQuery.post(ajaxurl, data, function(response) {
						wpcHideAjaxIcon();

						var $lessonSelect = changedElem.siblings('.wpc-requirement-lesson-select');

						$lessonSelect.html(lessonOptions(response));

						if( requirementType == 'specific-lesson' || requirementType == 'specific-module' || requirementType == 'specific-quiz' ) {
							$lessonSelect.show();
						}

					});
				} else {
					// else, reset lesson list to val of null and hide the list
					changedElem.siblings('.wpc-requirement-lesson-select').html('<option value="none">' + WPCAdminTranslations.none + '</option>').hide();
				}


			});

		});
		</script> <?php
	}
	
	add_action( 'wp_ajax_change_requirement_course', 'wpc_change_requirement_course_action_callback' );
	function wpc_change_requirement_course_action_callback(){

		check_ajax_referer( 'wpc-change-requirement-course-nonce', 'security' );

		$wpc_lessons = new WPC_Lessons();

		if($_POST['type'] == 'specific-module'){
			$post_types = array('wpc-module');
		} elseif ($_POST['type'] == 'specific-quiz'){
			$post_types = array('wpc-quiz');
		} else {
			$post_types = array('lesson');
		}

		$lessons = $wpc_lessons->get_connected_lessons($_POST['course_id'], $post_types);

		$newLessons = array();

		foreach($lessons as $id) {
			array_push($newLessons, array(
				'id'	=> $id,
				'title'	=> get_the_title($id),
			));
		}

		// save to db

		global $wpdb;

	    $table_name  = $wpdb->prefix . 'wpc_rules';

	    $wpdb->query( $wpdb->prepare("UPDATE $table_name 
	    	SET course_id = %d 
	    	WHERE id = %d", 
	    	(int) $_POST['course_id'], 
	    	(int) $_POST['requirement_id'])
	    );

		echo json_encode($newLessons);

	    wp_die(); // required
	}

	// change requirement action ajax

	add_action( 'admin_footer', 'wpc_action_change_requirement_act_javascript' );

	function wpc_action_change_requirement_act_javascript() { ?>
		<?php $ajax_nonce = wp_create_nonce( "wpc-change-requirement-action-nonce" ); ?>
		<script type="text/javascript" >

		jQuery(document).ready(function($) {

			jQuery(document).on('change', '.wpc-requirement-action', function(){

				var requirementAction = $(this).val();

				var requirementID = $(this).parent().attr('data-requirement-id');

				var data = {
					'security'				: "<?php echo $ajax_nonce; ?>",
					'action'				: 'change_requirement_act',
					'requirement_id'		: requirementID,
					'requirement_action'	: requirementAction
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function(response) {
					wpcHideAjaxIcon();		
				});
			});
		});
		</script> <?php
	}
	
	add_action( 'wp_ajax_change_requirement_act', 'wpc_change_requirement_act_action_callback' );
	function wpc_change_requirement_act_action_callback(){

		check_ajax_referer( 'wpc-change-requirement-action-nonce', 'security' );

		// save to db

		global $wpdb;

	    $table_name  = $wpdb->prefix . 'wpc_rules';

	    $wpdb->query( $wpdb->prepare("UPDATE $table_name 
	    	SET action = %s 
	    	WHERE id = %d", 
	    	$_POST['requirement_action'], 
	    	(int) $_POST['requirement_id'])
	    );

	    wp_die(); // required
	}

	// change requirement type ajax

	add_action( 'admin_footer', 'wpc_action_change_requirement_type_javascript' );

	function wpc_action_change_requirement_type_javascript() { ?>
		<?php $ajax_nonce = wp_create_nonce( "wpc-change-requirement-type-nonce" ); ?>
		<script type="text/javascript" >

		jQuery(document).ready(function($) {

			jQuery(document).on('change', '.wpc-requirement-type', function(){

				var requirementType = $(this).val();

				var requirementID = $(this).parent().attr('data-requirement-id');

				var data = {
					'security'				: "<?php echo $ajax_nonce; ?>",
					'action'				: 'change_requirement_type',
					'requirement_id'		: requirementID,
					'type'					: requirementType
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function(response) {
					wpcHideAjaxIcon();					
				});
			});
		});
		</script> <?php
	}
	
	add_action( 'wp_ajax_change_requirement_type', 'wpc_change_requirement_type_action_callback' );
	function wpc_change_requirement_type_action_callback(){

		check_ajax_referer( 'wpc-change-requirement-type-nonce', 'security' );

		// save to db

		global $wpdb;

	    $table_name  = $wpdb->prefix . 'wpc_rules';

	    $wpdb->query( $wpdb->prepare("UPDATE $table_name 
	    	SET type = %s 
	    	WHERE id = %d", 
	    	$_POST['type'], 
	    	(int) $_POST['requirement_id'])
	    );

	    wp_die(); // required
	}

	// change requirement times ajax

	add_action( 'admin_footer', 'wpc_action_change_requirement_times_javascript' );

	function wpc_action_change_requirement_times_javascript() { ?>
		<?php $ajax_nonce = wp_create_nonce( "wpc-change-requirement-times-nonce" ); ?>
		<script type="text/javascript" >

		jQuery(document).ready(function($) {

			jQuery(document).on('keyup', '.wpc-requirement-times', function(){

				var requirementTimes = $(this).val();

				var requirementID = $(this).parent().attr('data-requirement-id');

				var data = {
					'security'				: "<?php echo $ajax_nonce; ?>",
					'action'				: 'change_requirement_times',
					'requirement_id'		: requirementID,
					'times'					: requirementTimes
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function(response) {
					wpcHideAjaxIcon();					
				});
			});
		});
		</script> <?php
	}
	
	add_action( 'wp_ajax_change_requirement_times', 'wpc_change_requirement_times_action_callback' );
	function wpc_change_requirement_times_action_callback(){

		check_ajax_referer( 'wpc-change-requirement-times-nonce', 'security' );

		// save to db

		global $wpdb;

	    $table_name  = $wpdb->prefix . 'wpc_rules';

	    $wpdb->query( $wpdb->prepare("UPDATE $table_name 
	    	SET times = %d 
	    	WHERE id = %d", 
	    	(int) $_POST['times'], 
	    	(int) $_POST['requirement_id'])
	    );

	    wp_die(); // required
	}

	// change requirement percent ajax

	add_action( 'admin_footer', 'wpc_action_change_requirement_percent_javascript' );

	function wpc_action_change_requirement_percent_javascript() { ?>
		<?php $ajax_nonce = wp_create_nonce( "wpc-change-requirement-percent-nonce" ); ?>
		<script type="text/javascript" >

		jQuery(document).ready(function($) {

			jQuery(document).on('keyup', '.wpc-percent', function(){

				var requirementPercent = $(this).val();

				var requirementID = $(this).parent().attr('data-requirement-id');

				var data = {
					'security'				: "<?php echo $ajax_nonce; ?>",
					'action'				: 'change_requirement_percent',
					'requirement_id'		: requirementID,
					'percent'				: requirementPercent
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function(response) {
					wpcHideAjaxIcon();					
				});
			});
		});
		</script> <?php
	}
	
	add_action( 'wp_ajax_change_requirement_percent', 'wpc_change_requirement_percent_action_callback' );
	function wpc_change_requirement_percent_action_callback(){

		check_ajax_referer( 'wpc-change-requirement-percent-nonce', 'security' );

		// save to db

		global $wpdb;

	    $table_name  = $wpdb->prefix . 'wpc_rules';

	    $wpdb->query( $wpdb->prepare("UPDATE $table_name 
	    	SET percent = %d 
	    	WHERE id = %d", 
	    	(int) $_POST['percent'], 
	    	(int) $_POST['requirement_id'])
	    );

	    wp_die(); // required
	}


	// change requirement lesson ajax

	add_action( 'admin_footer', 'wpc_action_change_requirement_lesson_javascript' );

	function wpc_action_change_requirement_lesson_javascript() { ?>
		<?php $ajax_nonce = wp_create_nonce( "wpc-change-requirement-lesson-nonce" ); ?>
		<script type="text/javascript" >

		jQuery(document).ready(function($) {

			jQuery(document).on('change', '.wpc-requirement-lesson-select', function(){

				var requirementType = $(this).siblings('.wpc-requirement-type').val();

				var requirementLessonID = $(this).val();

				var requirementID = $(this).parent().attr('data-requirement-id');

				var data = {
					'security'				: "<?php echo $ajax_nonce; ?>",
					'action'				: 'change_requirement_lesson',
					'requirement_type'			: requirementType,
					'requirement_id'		: requirementID,
					'lesson_id'				: requirementLessonID
				}

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function(response) {
					wpcHideAjaxIcon();		
				});
			});
		});
		</script> <?php
	}
	
	add_action( 'wp_ajax_change_requirement_lesson', 'wpc_change_requirement_lesson_action_callback' );
	function wpc_change_requirement_lesson_action_callback(){

		check_ajax_referer( 'wpc-change-requirement-lesson-nonce', 'security' );

		if($_POST['requirement_type'] == 'any-module' || $_POST['requirement_type'] == 'specific-module') {
			$lesson_id = null;
			$module_id = $_POST['lesson_id'];
		} else {
			$lesson_id = $_POST['lesson_id'];
			$module_id = null;
		}

		// save to db

		global $wpdb;

	    $table_name  = $wpdb->prefix . 'wpc_rules';

	    $wpdb->query( $wpdb->prepare("UPDATE $table_name 
	    	SET lesson_id = %d, module_id = %d
	    	WHERE id = %d", 
	    	(int) $lesson_id, 
	    	(int) $module_id,
	    	(int) $_POST['requirement_id']
	    ));

	    wp_die(); // required
	}

?>