<?php get_header(); ?>
<div class="wpc-container">
	<div class="wpc-row">
		<div class="wpc-light-box">
    		<?php
                if(have_posts()){
                    while(have_posts()){
                        the_post();
                        echo '<div class="wpc-video-wrapper">';
                            include 'template-parts/course-video.php';
                        echo '</div>';
                        include 'template-parts/course-details.php';
                        include 'template-parts/course-meta.php';
                    }
                }
    		?>
    	</div>
        <?php do_action('wpc_before_single_course_lesson_nav'); ?>
        <div class="wpc-single-course-lesson-wrapper">
            <?php 

                $course_id = get_the_ID();
                $wpc_lessons = new WPC_Lessons();
                $lesson_nav = $wpc_lessons->get_lesson_navigation($course_id);

                echo $lesson_nav;

            ?>
        </div>
        <?php do_action('wpc_after_single_course_lesson_nav'); ?>
	</div>
</div>

<?php get_footer(); ?>