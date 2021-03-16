<?php

// requirements

add_action( 'add_meta_boxes', 'wpc_requirements_meta_box' );

function wpc_requirements_meta_box() {
	$views = array('wpc-badge', 'wpc-certificate', 'wpc-email');
	foreach($views as $view){
		add_meta_box(
			'wpc_requirements_wrapper', // id
			__( 'Requirements', 'wp-courses' ),
			'wpc_requirements_meta_box_callback',
			$view,
			'normal',
			'low'
		);
	}
}

function wpc_requirements_meta_box_callback( $post ) {

		wp_nonce_field('wpc_save_requirements_meta_box_data', 'wpc_requirements_meta_box_nonce');

		$wpc_admin = new WPC_Admin();

		?>

		<div class="wpc-admin-toolbar wpc-metabox-toolbar">
			<button id="wpc-add-new-requirement" class="button" type="button"><?php _e('Add Requirement', 'wp-courses'); ?></button>
		</div>

		<div id="wpc-requirements"> 
			
			<?php

				$results = wpc_get_rules(get_the_ID());

				if(!empty($results)){
					foreach($results as $result){ ?>
						<div class="wpc-requirement" data-requirement-id="<?php echo $result->id ?>">

							<label><?php _e('When Someone', 'wp-courses'); ?>: </label>
							<select name="wpc-requirement-action" class="wpc-requirement-action">
								 <option value="views" <?php echo $result->action == 'views' ? ' selected' : '' ?>><?php _e('Views', 'wp-courses'); ?></option>
								 <option value="completes" <?php echo $result->action == 'completes' ? ' selected' : '' ?>><?php _e('Completes', 'wp-courses'); ?></option>
								 <option value="scores" <?php echo $result->action == 'scores' ? ' selected' : '' ?>><?php _e('Scores', 'wp-courses'); ?></option>
							 </select>

							 <select name="wpc-requirement-type" class="wpc-requirement-type">
								 <option value="any-course" <?php echo $result->type == 'any-course' ? ' selected' : '' ?> class="<?php echo $result->action == 'scores' ? 'wpc-hide' : ''; ?>"><?php _e('Any Course', 'wp-courses'); ?></option>
								 <option value="specific-course" <?php echo $result->type == 'specific-course' ? ' selected' : '' ?> class="<?php echo $result->action == 'scores' ? 'wpc-hide' : ''; ?>"><?php _e('A Specific Course', 'wp-courses'); ?></option>
								 <option value="any-lesson" <?php echo $result->type == 'any-lesson' ? ' selected' : '' ?> class="<?php echo $result->action == 'scores' ? 'wpc-hide' : ''; ?>"><?php _e('Any Lesson', 'wp-courses'); ?></option>
								 <option value="specific-lesson" <?php echo $result->type == 'specific-lesson' ? ' selected' : '' ?> class="<?php echo $result->action == 'scores' ? 'wpc-hide' : ''; ?>"><?php _e('A Specific Lesson', 'wp-courses'); ?></option>
								 <option value="any-module" <?php echo $result->type == 'any-module' ? ' selected' : '' ?> class="<?php echo $result->action == 'scores' ? 'wpc-hide' : ''; ?>"><?php _e('Any Module', 'wp-courses'); ?></option>
								 <option value="specific-module" <?php echo $result->type == 'specific-module' ? ' selected' : '' ?> class="<?php echo $result->action == 'scores' ? 'wpc-hide' : ''; ?>"><?php _e('A Specific Module', 'wp-courses'); ?></option>
								 <option value="any-quiz" <?php echo $result->type == 'any-quiz' ? ' selected' : '' ?>><?php _e('Any Quiz', 'wp-courses'); ?></option>
								 <option value="specific-quiz" <?php echo $result->type == 'specific-quiz' ? ' selected' : '' ?>><?php _e('A Specific Quiz', 'wp-courses'); ?></option>
							 </select>

							 <?php 

							 	$class = $result->type == 'any-module' || $result->type == 'any-lesson' || $result->type == 'any-course' || $result->type == 'any-quiz' ? ' wpc-hide' : '';
							 	echo $wpc_admin->get_course_dropdown($result->course_id, 'wpc-requirement-courses-select' . $class ); 
								$wpc_lessons = new WPC_Lessons(); 

							?>

						 	<?php if(!empty($result->course_id && $result->course_id != null)){
						 		$modules = $wpc_lessons->get_connected_lessons($result->course_id, array('wpc-module')); 
						 		$lessons = $wpc_lessons->get_connected_lessons($result->course_id); 
						 		$quizzes = $wpc_lessons->get_connected_lessons($result->course_id, array('wpc-quiz')); 
						 		

						 		$class = $result->type != 'specific-lesson' && $result->type != 'specific-module' && $result->type != 'specific-quiz' ? 'wpc-hide' : '' ; ?>

					 			<select class="wpc-requirement-lesson-select <?php echo $class; ?>">
					 				<?php if($result->type == 'specific-lesson'){ ?>

							 			<?php foreach($lessons as $id){ ?>

											<option value="<?php echo $id; ?>" <?php echo (int) $id == (int) $result->lesson_id ? ' selected' : ''; ?>>
							 				<?php echo get_the_title($id); ?>
							 				</option>

										<?php } // end foreach ?>

									<?php } elseif($result->type == 'specific-module') { ?>

										<?php foreach($modules as $id){ ?>

											<option value="<?php echo $id; ?>" <?php echo (int) $id == (int) $result->module_id ? ' selected' : ''; ?>>
							 				<?php echo get_the_title($id); ?>
							 				</option>

										<?php } // end foreach ?>

									<?php } elseif($result->type == 'specific-quiz') { ?>

										<?php foreach($quizzes as $id){ ?>

											<option value="<?php echo $id; ?>" <?php echo (int) $id == (int) $result->lesson_id ? ' selected' : ''; ?>>
							 				<?php echo get_the_title($id); ?>
							 				</option>

										<?php } // end foreach ?>

									<?php } else { ?>
										<option value="none"><?php _e('none', 'wp-courses'); ?></option>
									<?php } ?>

								</select>		

							<?php } // end if ?>			

							<?php if( $result->type == 'specific-course' || $result->type == 'specific-module' || $result->type == 'any-course' || $result->type == 'any-module' ){
								$class = '';
							} elseif($result->action == 'views' && $result->type == 'any-quiz'){
								$class = 'wpc-hide';
							} elseif($result->action == 'completes' && $result->type == 'any-quiz'){
								$class = 'wpc-hide';
							} elseif($result->action == 'views' && $result->type == 'specific-quiz'){
								$class = 'wpc-hide';
							} elseif($result->action == 'completes' && $result->type == 'specific-quiz'){
								$class = 'wpc-hide';
							} elseif($result->action == 'scores' && $result->type == 'specific-quiz'){
								$class = '';
							} elseif($result->action == 'scores' && $result->type == 'any-quiz'){
								$class = '';
							} else {
								$class = 'wpc-hide';
							} ?> 		

							<label class="wpc-percent-label <?php echo $class; ?>"><?php _e('Percent', 'wp-courses'); ?>: </label>
							<input type="number" min="0" max="100" value="<?php echo $result->percent; ?>" class="wpc-percent <?php echo $class; ?>"/>

							<?php $class = ($result->type == 'specific-course' || $result->type == 'specific-lesson' || $result->type == 'specific-module' || $result->type == 'specific-quiz') ? ' wpc-hide' : ''; ?>

							<label class="wpc-times-label <?php echo $class; ?>"><?php _e('Times', 'wp-courses'); ?>: </label><input name="wpc-times" type="number" min="1" value="<?php echo $result->times; ?>" class="wpc-requirement-times <?php echo $class; ?>"/>

							<br>
							
							<button class="wpc-delete-requirement button" type="button" data-requirement-id="<?php echo $result->id ?>"><?php _e('Delete Requirement', 'wp-courses'); ?></button>
							

						</div>

					<?php }
					} else {
						echo '<div class="wpc-requirement-notice" id="wpc-no-requirement-notice">' . __('Click "add requirement" to add your first requirement', 'wp-courses') . '</div>';
					} ?>

		</div> <!-- badge requirements render here -->

		<?php echo '<div id="wpc-hidden-course-select">' . $wpc_admin->get_course_dropdown(null, 'wpc-requirement-courses-select wpc-hide') . '</div>';

	}

function wpc_save_requirements_meta_box_data($post_id) {

	// check if nonce is set.
	if ( ! isset( $_POST['wpc_requirements_meta_box_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['wpc_requirements_meta_box_nonce'], 'wpc_save_requirements_meta_box_data' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	$post_types = array('wpc-badge', 'wpc-certificate', 'wpc-email');

	foreach($post_types as $type) {
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && $type == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
	}

	// save the data to post meta

}

add_action( 'save_post', 'wpc_save_requirements_meta_box_data' );

?>