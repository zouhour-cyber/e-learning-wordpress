<?php get_header(); ?>

<?php while( have_posts() ){
	the_post(); ?>

<div class="wpc-container">
	<div class="wpc-row">
		<?php 

			$wpc_course = new WPC_Courses();
			$lesson_id = get_the_ID();
			$course_id = get_post_meta($lesson_id, 'wpc-connected-lesson-to-course', true);
			$orig_id = get_post_meta( $lesson_id, 'wpc-lesson-alias-id', true);

			// if is clone lesson, get the ID of the original lesson so we can output the content of the original lesson
			if(!empty($orig_id) && $orig_id != 'none'){
				$lesson_id = $orig_id;
			} 

			$show_progress_bar = get_option('wpc_show_completed_lessons');
			if(is_user_logged_in() && $show_progress_bar === 'true' && $course_id != 'none'){
				echo '<div class="single-lesson-course-progress">' . $wpc_course->get_progress_bar($course_id) . '</div>'; 
			} 

			$class = ($course_id != 'none') ? 'wpc-sidebar-content' : '';

		?>
		<div id="wpc-single-lesson-content" class="<?php echo $class; ?> wpc-light-box">

			<h1><?php the_title(); ?></h1>

			<?php echo wpc_get_breadcrumb(get_the_ID()); ?>

			<?php

		        $tracking = new WPC_Tracking();
		        $tracking->lesson_tracking( get_the_ID() );
		        $tracking->course_tracking();
		        $wp_lessons = new WPC_Lessons();
		        $lesson_nav = $wp_lessons->get_lesson_navigation($course_id);
		        $restriction = get_post_meta( get_the_ID(), 'wpc-lesson-restriction', true );

		        $custom_logged_out_message = get_option('wpc_logged_out_message');

		        if($restriction == 'free-account' && !is_user_logged_in()){ ?>

		        	<p class="wpc-content-restricted wpc-free-account-required">

		        	<?php if(!empty($custom_logged_out_message)){

		        		echo $custom_logged_out_message;

		        	} else { ?>

		            	<a href="<?php echo wp_login_url( get_permalink() );?>"><?php echo __('Log in', 'wp-courses'); ?></a> <?php echo __('or', 'wp-courses'); ?> <a href="<?php echo wp_registration_url(); ?>"><?php echo __('Register', 'wp-courses'); ?></a> <?php echo __('to view this lesson.', 'wp-courses');

		        	} ?>

		            </p>

		        <?php } else { ?>

		        	<?php 
						$my_postid = $lesson_id;//This is page id or post id
						$content_post = get_post($my_postid);
						$content = $content_post->post_content;
						$content = apply_filters('the_content', $content);
						$content = str_replace(']]>', ']]&gt;', $content);
						
		        	?>

		           	<div class="wpc-lesson-content"><?php echo $content; ?></div>

		        <?php } ?>
			
    	</div>

    	<?php if ($course_id != 'none') { ?>
	    	<div class="wpc-sidebar wpc-right-sidebar">
	    		<div id="lesson-nav-wrapper">
	    			<?php echo $lesson_nav; ?>
	    		</div>
	    	</div>
    	<?php } ?>


	</div>

	<?php if(comments_open() == true) { ?>
		<div class="wpc-row">
		   		<div class="wpc-comments-wrapper wpc-light-box">
		    		<?php comments_template(); ?>
		    	</div>
		</div>
	<?php } ?>

</div>



<?php } ?>

<?php get_footer(); ?>