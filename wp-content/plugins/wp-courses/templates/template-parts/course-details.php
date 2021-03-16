<div class="course-excerpt">
	<h2 class="course-title">
		<a href="<?php echo get_the_permalink(); ?>">
			<?php echo get_the_title(); ?>
		</a>
	</h2>
	<?php 
		do_action('wpc_after_course_title');
		if(is_archive()){
			the_excerpt();
		} else {
			the_content();
		}
	?>
</div>
<?php $wpc_course = new WPC_Courses(); ?>
<?php echo $wpc_course->get_start_course_button(get_the_ID()); ?>