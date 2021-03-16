<div class="wpc-admin-nav-menu">
    <div class="wpc-alt-admin-buttons">
        <a href="https://wpcoursesplugin.com/store/" class="button" style="border: 1px solid #12ad80!important;background-color: #23d19f; color: white;"><i class="fa fa-shopping-cart"></i>  <?php _e(
            'Shop Add-Ons', 'wp-courses'); ?></a>
        <a href="https://wpcoursesplugin.com/lesson/setting-up-wp-courses/" class="button"><i class="fa fa-question"></i>  <?php _e('Tutorials and Help', 'wp-courses');?></a>
        <a href="http://wpcoursesplugin.com/contact" class="button"><i class="fa fa-envelope"></i> <?php _e('Contact', 'wp-courses'); ?> WP Courses</a>
        <a href="https://wordpress.org/support/plugin/wp-courses/reviews/" class="button"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> <?php _e('Leave a Review', 'wp-courses'); ?></a>
    </div>
    <ul class="wpc-alt-admin-menu">
        <li class="wpc-admin-menu-item wpc-submenu-toggle">
            <span><?php _e('Courses', 'wp-courses'); ?><span class="dashicons dashicons-arrow-down"></span></span>
            <ul class="wpc-admin-submenu" style="display: none;">
                <li><a href="edit.php?post_type=course"><?php _e('Manage Courses', 'wp-courses'); ?></a></li>
                <li><a href="edit-tags.php?taxonomy=course-category&post_type=course"><?php _e('Course Categories', 'wp-courses'); ?></a></li>
                <li><a href="edit-tags.php?taxonomy=course-difficulty&post_type=course"><?php _e('Course Difficulties', 'wp-courses'); ?></a></li>
                <li><a href="admin.php?page=order_courses"><?php _e('Order Courses', 'wp-courses'); ?></a></li>
            </ul>
        </li>

        <li class="wpc-admin-menu-item wpc-submenu-toggle">
            <span><?php _e('Lessons and Modules', 'wp-courses'); ?><span class="dashicons dashicons-arrow-down"></span></span>
            <ul class="wpc-admin-submenu" style="display: none;">
                <li><a href="edit.php?post_type=lesson"><?php _e('Manage Lessons', 'wp-courses'); ?></a></li>
                <?php do_action('wpc_after_admin_nav_menu_manage_lessons'); ?>
                <li><a href="admin.php?page=order_lessons"><?php _e('Order Lessons and Manage Modules', 'wp-courses'); ?></a></li>
            </ul>
        </li>
    	
        <li class="wpc-admin-menu-item"><a href="edit.php?post_type=teacher"><?php _e('Teachers', 'wp-courses'); ?></a></li>
        <li class="wpc-admin-menu-item"><a href="admin.php?page=manage_students"><?php _e('Students and Progress', 'wp-courses'); ?></a></li> 
        <?php do_action('wpc_after_admin_nav_menu'); ?> 
        <li class="wpc-admin-menu-item"><a href="admin.php?page=wpc_options"><?php _e('Options', 'wp-courses'); ?></a></li>
    </ul>
</div>