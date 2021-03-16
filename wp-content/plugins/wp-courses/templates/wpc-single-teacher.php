<?php get_header(); ?>
<div class="wpc-container">
	<div class="wpc-row">
		<div class="wpc-sidebar wpc-left-sidebar">
			<div class="wpc-single-teacher-thumbnail">
    			<?php echo get_the_post_thumbnail(); ?>
    		</div>
    	</div>
		<div class="wpc-sidebar-content wpc-light-box">
			<h1><?php echo __('About', 'wp-courses'); ?> <?php the_title(); ?></h1>
			<?php 
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post(); 
						the_content();
					} // end while
				} // end if
			?>			
    	</div>
	</div>
</div>
<?php get_footer(); ?>