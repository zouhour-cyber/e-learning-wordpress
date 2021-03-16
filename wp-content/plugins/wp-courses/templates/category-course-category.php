<?php get_header(); ?>
<?php $wpc_course = new WPC_Courses(); ?>
<div class="wpc-container">
	<div class="wpc-row">
		<div class="wpc-sidebar wpc-left-sidebar">
			<?php echo $wpc_course->get_course_category_list(); ?>
		</div>
		<div id="courses-wrapper" class="wpc-sidebar-content">

			<?php include 'template-parts/course-filters.php'; ?>

			<?php
				if(have_posts()){
					while(have_posts()){
						the_post();
						echo '<div class="course-container wpc-light-box">';
							echo '<div class="wpc-video-wrapper">';
								include 'template-parts/course-video.php';
							echo '</div>';
							include 'template-parts/course-details.php';
							include 'template-parts/course-meta.php';
						echo '</div>';
					}
					wp_reset_postdata();
					echo '<br><div class="wpc-paginate-links">' . paginate_links() . '</div>';
				}
			?>
		</div>
	</div>
</div>
<?php get_footer(); ?>