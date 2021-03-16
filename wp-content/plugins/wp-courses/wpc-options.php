<?php 

function wpc_register_settings() {

   	add_option( 'wpc_enable_rest_lesson', 'false');

   	add_option( 'wpc_show_course_search', 'true');

   	add_option( 'wpc_show_breadcrumb_trail', 'true');
   	add_option( 'wpc_show_lesson_numbers', 'true');
   	add_option( 'wpc_show_completed_lessons', 'true');
   	add_option( 'wpc_show_lesson_nav_icons', 'true');
   	add_option( 'wpc_courses_per_page', 10);
   	add_option( 'wpc_teachers_per_page', 10);
   	add_option( 'wpc_logged_out_message' );

   	add_option( 'wpc_primary_bg_color', 'transparent');

   	add_option( 'wpc_primary_button_color', '#23d19f');
   	add_option( 'wpc_primary_button_border_color', '#12ad80');
   	add_option( 'wpc_primary_button_text_color', '#fff');

   	add_option( 'wpc_primary_button_hover_color', '#12ad80');
   	add_option( 'wpc_primary_button_hover_border_color', '#12ad80');
   	add_option( 'wpc_primary_button_hover_text_color', '#fff');

   	add_option( 'wpc_primary_button_active_color', '#009ee5');
   	add_option( 'wpc_primary_button_active_border_color', '#027fb7');
   	add_option( 'wpc_primary_button_active_text_color', '#fff');

	register_setting( 'wpc_options', 'wpc_enable_rest_lesson', 'wpc_callback' );

	register_setting( 'wpc_options', 'wpc_show_course_search', 'wpc_callback' );

	register_setting( 'wpc_options', 'wpc_show_breadcrumb_trail', 'wpc_callback' );
   	register_setting( 'wpc_options', 'wpc_show_lesson_numbers', 'wpc_callback' );
   	register_setting( 'wpc_options', 'wpc_show_completed_lessons', 'wpc_callback' );
   	register_setting( 'wpc_options', 'wpc_show_lesson_nav_icons', 'wpc_callback' );
   	register_setting( 'wpc_options', 'wpc_courses_per_page', 'wpc_callback' );
   	register_setting( 'wpc_options', 'wpc_teachers_per_page', 'wpc_callback' );
   	register_setting( 'wpc_options', 'wpc_logged_out_message', 'wpc_callback' );

   	register_setting('wpc_options', 'wpc_primary_bg_color', 'wpc_callback');

   	register_setting( 'wpc_options', 'wpc_primary_button_color', 'wpc_callback' );
   	register_setting( 'wpc_options', 'wpc_primary_button_border_color', 'wpc_callback' );
    register_setting( 'wpc_options', 'wpc_primary_button_text_color', 'wpc_callback' );

    register_setting( 'wpc_options', 'wpc_primary_button_hover_color', 'wpc_callback' );
   	register_setting( 'wpc_options', 'wpc_primary_button_hover_border_color', 'wpc_callback' );
    register_setting( 'wpc_options', 'wpc_primary_button_hover_text_color', 'wpc_callback' );

    register_setting( 'wpc_options', 'wpc_primary_button_active_color', 'wpc_callback' );
   	register_setting( 'wpc_options', 'wpc_primary_button_active_border_color', 'wpc_callback' );
    register_setting( 'wpc_options', 'wpc_primary_button_active_text_color', 'wpc_callback' );
}
add_action( 'admin_init', 'wpc_register_settings' );

function wpc_options_page(){ ?>

	<?php include 'admin-nav-menu.php'; ?>

	<div class="wrap">
		<?php // screen_icon(); ?>

		<form method="post" action="options.php">
			<?php 

			settings_fields( 'wpc_options' );

			$wpc_enable_rest_lesson = get_option('wpc_enable_rest_lesson');

			$wpc_show_course_search = get_option('wpc_show_course_search');

			$wpc_show_breadcrumb_trail = get_option('wpc_show_breadcrumb_trail');
			$wpc_show_lesson_numbers = get_option('wpc_show_lesson_numbers');
			$wpc_show_completed_lessons = get_option('wpc_show_completed_lessons');
			$wpc_show_lesson_nav_icons = get_option('wpc_show_lesson_nav_icons');
			$wpc_courses_per_page = get_option('wpc_courses_per_page');
			$wpc_teachers_per_page = get_option('wpc_teachers_per_page');
			$wpc_logged_out_message = get_option('wpc_logged_out_message');

			$wpc_primary_bg_color = get_option('wpc_primary_bg_color', 'transparent');

			$wpc_primary_button_color = get_option('wpc_primary_button_color', '#23d19f');
			$wpc_primary_button_border_color = get_option('wpc_primary_button_border_color', '#12ad80');
			$wpc_primary_button_text_color = get_option('wpc_primary_button_text_color', '#fff');

			$wpc_primary_button_hover_color = get_option('wpc_primary_button_hover_color', '#12ad80');
			$wpc_primary_button_hover_border_color = get_option('wpc_primary_button_hover_border_color', '#12ad80');
			$wpc_primary_button_hover_text_color = get_option('wpc_primary_button_hover_text_color', '#fff');

			$wpc_primary_button_active_color = get_option('wpc_primary_button_active_color', '#009ee5');
			$wpc_primary_button_active_border_color = get_option('wpc_primary_button_active_border_color', '#027fb7');
			$wpc_primary_button_active_text_color = get_option('wpc_primary_button_active_text_color', '#fff');

			$id = get_the_ID();

			if( $wpc_enable_rest_lesson == 'true' ){
				$rest_lesson_checked = 'checked';
			} else {
				$rest_lesson_checked = '';
			}

			if( $wpc_show_course_search == 'true' ){
				$course_search_checked = 'checked';
			} else {
				$course_search_checked = '';
			}

			if($wpc_show_breadcrumb_trail == 'true'){
				$breadcrumb_checked = 'checked';
			} else {
				$breadcrumb_checked = '';
			}

			if($wpc_show_lesson_numbers == 'true'){
				$lesson_numbers_checked = 'checked';
			} else {
				$lesson_numbers_checked = '';
			}

			if($wpc_show_completed_lessons == 'true'){
				$completed_checked = 'checked';
			} else {
				$completed_checked = '';
			}

			if($wpc_show_lesson_nav_icons == 'true'){
				$icons_checked = 'checked';
			} else {
				$icons_checked = '';
			}

			?>

			<div class="wpc-admin-box" style="margin-top: 25px;">

				<h2 class="wpc-admin-box-header">General Options</h2>

				<h2>REST API</h2>

				<div class="wpc-option">
					<input type="checkbox" id="wpc-enable-lesson-rest" name="wpc_enable_rest_lesson" value="true" <?php echo $rest_lesson_checked; ?>/><label for="wpc-enable-lesson-rest">Show lesson content in the REST API.  <b>Warning!</b>  If checked, restricted lesson content is accessable <b>TO ANYONE</b> via the REST API.</label>
				</div>

			</div>

			<div class="wpc-admin-box">

				<h2 class="wpc-admin-box-header">Display Options</h2>

				<h2>Course Page Display Options</h2>

				<div class="wpc-option">
					<input type="checkbox" id="wpc-course-search" name="wpc_show_course_search" value="true" <?php echo $course_search_checked; ?>/><label for="wpc-course-search">Show course search</label>
				</div>

				<h2>Lesson Page Display Options</h2>

				<div class="wpc-option">
					<input type="checkbox" id="wpc-show-breadcrumb-trail" name="wpc_show_breadcrumb_trail" value="true" <?php echo $breadcrumb_checked; ?>/><label for="wpc-show-lesson-numbers">Show breadcrumb trail</label>
				</div>

				<div class="wpc-option">
					<input type="checkbox" id="wpc-show-lesson-numbers" name="wpc_show_lesson_numbers" value="true" <?php echo $lesson_numbers_checked; ?>/><label for="wpc-show-lesson-numbers">Show lesson numbers</label>
				</div>

				<div class="wpc-option">
					<input type="checkbox" id="wpc-show-completed-lessons" name="wpc_show_completed_lessons" value="true" <?php echo $completed_checked; ?>/><label for="wpc-show-completed-lessons">Show completed lesson button and completed lessons progress bar on lesson page</label>
				</div>

				<div class="wpc-option">
					<input type="checkbox" id="wpc-show-lesson_nav_icons" name="wpc_show_lesson_nav_icons" value="true" <?php echo $icons_checked; ?>/><label for="wpc-show-lesson_nav_icons">Show lesson navigation button icons (eye, play, check, lock)</label>
				</div>

				<h2>Messages</h2>

				<div class="wpc-option">
					<label>Custom restricted lesson message for logged out users on lesson page</label>
				</div>

				<?php $settings = array(
				    'teeny' => true,
				    'textarea_rows' => 6,
				    'tabindex' => 2,
				    'textarea_name'	=> 'wpc_logged_out_message',
				);
				wp_editor(esc_html( __($wpc_logged_out_message)), 'wpc_logged_out_message', $settings); ?>

				<h2>Other Display Options</h2>

				<div class="wpc-option">
					<label for="wpc-courses-per-page">Courses Per Page</label><br>
					<input id="wpc-courses-per-page" type="number" value="<?php echo $wpc_courses_per_page; ?>" name="wpc_courses_per_page"/>
				</div>

				<div class="wpc-option">
					<label for="wpc-teachers-per-page">Teachers Per Page</label><br>
					<input id="wpc-teachers-per-page" type="number" value="<?php echo $wpc_teachers_per_page; ?>" name="wpc_teachers_per_page"/>
				</div>
				
				<?php do_action( 'wpc_after_display_options' ); ?>

			</div>

			<div class="wpc-admin-box">
				<h2 class="wpc-admin-box-header">Design Options</h2>

				<h2>Page Design Options</h2>

				<input class="color-field" name="wpc_primary_bg_color" value="<?php echo $wpc_primary_bg_color; ?>"/>
				<label>Primary Background Color</label>
				<br>

				<h2>Primary Button Colors</h2>

				<input class="color-field" name="wpc_primary_button_color" value="<?php echo $wpc_primary_button_color; ?>"/>
				<label>Primary Button Color</label>
				<br>

				<input class="color-field" name="wpc_primary_button_border_color" value="<?php echo $wpc_primary_button_border_color; ?>"/>
				<label>Primary Button Border Color</label>
				<br>

				<input class="color-field" name="wpc_primary_button_text_color" value="<?php echo $wpc_primary_button_text_color; ?>"/>
				<label>Primary Button Text Color</label>
				<br>

				<h2>Primary Button Hover Colors</h2>

				<input class="color-field" name="wpc_primary_button_hover_color" value="<?php echo $wpc_primary_button_hover_color; ?>"/>
				<label>Primary Button Hover Color</label>
				<br>

				<input class="color-field" name="wpc_primary_button_hover_border_color" value="<?php echo $wpc_primary_button_hover_border_color; ?>"/>
				<label>Primary Button Hover Border Color</label>
				<br>

				<input class="color-field" name="wpc_primary_button_hover_text_color" value="<?php echo $wpc_primary_button_hover_text_color; ?>"/>
				<label>Primary Button Hover Text Color</label>


				<h2>Primary Button Active Colors</h2>

				<input class="color-field" name="wpc_primary_button_active_color" value="<?php echo $wpc_primary_button_active_color; ?>"/>
				<label>Primary Button Active Color</label>
				<br>

				<input class="color-field" name="wpc_primary_button_active_border_color" value="<?php echo $wpc_primary_button_active_border_color; ?>"/>
				<label>Primary Button Active Border Color</label>
				<br>

				<input class="color-field" name="wpc_primary_button_active_text_color" value="<?php echo $wpc_primary_button_active_text_color; ?>"/>
				<label>Primary Button Active Text Color</label>


				<?php do_action( 'wpc_after_design_options' ); ?>
			</div>

			<?php do_action( 'wpc_after_options' ); ?>

			<?php submit_button(); ?>

		</form>
	</div>

<?php } ?>