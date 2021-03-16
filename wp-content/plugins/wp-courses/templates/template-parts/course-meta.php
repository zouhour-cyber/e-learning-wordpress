<?php 

$wpc_course = new WPC_Courses();
$course_id = get_the_ID();
$teachers = get_post_meta( $course_id, 'wpc-connected-teacher-to-course', true );
$difficulty = $wpc_course->get_course_difficulty($course_id);

?>

<div class="course-meta-wrapper">
	<?php if( $difficulty != '-1' && !empty( $difficulty ) ){ ?>
	<div class="cm-item">
		<span><?php echo __('Level', 'wp-courses') . ": " . $difficulty; ?>
		</span>
	</div>
	<?php } ?>
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
			<span>
				<?php echo __('Viewed', 'wp-courses') . ": " . $wpc_course->get_percent_viewed($course_id); ?>%
			</span>
		</div>
		<div class="cm-item">
			<span>
				<?php 
					$show_completed_button = get_option('wpc_show_completed_lessons');
					if($show_completed_button == 'true') {
						echo $wpc_course->get_progress_bar($course_id);
					}
				?>
			</span>
		</div>
	<?php } ?>
</div>