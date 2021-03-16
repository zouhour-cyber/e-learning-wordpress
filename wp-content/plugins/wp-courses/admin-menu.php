<?php
// create custom plugin settings menu
add_action('admin_menu', 'wpc_create_menu');
function wpc_create_menu() {
	//create new top-level menu
	add_menu_page('WP Courses', 'WP Courses', 'administrator', 'wpc_settings', 'wpc_settings_page' );
	//call register settings function
	//add_action( 'admin_init', 'wpc_register_settings' );
}
function wpc_settings_page(){ ?>
	<?php include 'admin-nav-menu.php'; ?>
	<div class="wpc-main-admin-wrapper wrap">
		<?php include 'admin-extensions.php'; ?>
	</div>
<?php }
function wpc_register_submenu(){
	// add_submenu_page( 'wpc_settings', 'Add-Ons', 'Add-Ons', 'manage_options', 'admin.php?page=wpc_settings' );

	add_submenu_page( 'wpc_settings', 'Options', 'Options', 'manage_options', 'wpc_options', 'wpc_options_page' );

    add_submenu_page( 'wpc_settings', 'Teachers', 'Teachers', 'manage_options', 'edit.php?post_type=teacher' );
    add_submenu_page( 'wpc_settings', 'Course Categories', 'Course Categories', 'manage_options', 'edit-tags.php?taxonomy=course-category&post_type=course' );
    add_submenu_page( 'wpc_settings', 'Course Difficulties', 'Course Difficulties', 'manage_options', 'edit-tags.php?taxonomy=course-difficulty&post_type=course' );
    add_submenu_page( 'wpc_settings', 'Courses', 'Courses', 'manage_options', 'edit.php?post_type=course' );
    add_submenu_page( 'wpc_settings', 'Lessons', 'Lessons', 'manage_options', 'edit.php?post_type=lesson' );
    add_submenu_page( 'wpc_settings', 'Order Lessons and Manage Modules', 'Order Lessons and Manage Modules', 'manage_options', 'order_lessons', 'wpc_order_lessons_page' );
    add_submenu_page( 'wpc_settings', 'Order Courses', 'Order Courses', 'manage_options', 'order_courses', 'wpc_order_courses_page' );
    add_submenu_page( 'wpc_settings', 'Students and Progress', 'Students and Progress', 'manage_options', 'manage_students', 'wpc_manage_students_page' );
    do_action( 'wpc_after_register_submenu' );
}

add_action('admin_menu', 'wpc_register_submenu');

// Ajax for saving lesson ordering

function update_lesson_order_and_meta($posts){
	global $wpdb;

	$current_module = null;

    foreach($posts as $post){
    	$id = (int) $post['postID'];
    	$order = (int) $post['menuOrder'];
    	$postType = $post['postType'];
		$wpdb->query(
		    $wpdb->prepare(
		        "UPDATE $wpdb->posts SET menu_order = %d WHERE ID = %d",
		        $order,
		        $id
		    )
		);

		if($postType == 'wpc-module'){
			$current_module = $id; 
		} else {
			update_post_meta($id, 'connected_lesson_to_module', $current_module);
		}

    }
}

add_action( 'admin_footer', 'wpc_action_order_lessons_javascript' );
function wpc_action_order_lessons_javascript() { ?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
		jQuery( ".lesson-list" ).sortable({
			    axis: 'y',
			    update: function (event, ui) {

					var data = {
						'action': 'order_lessons',
						'posts': wpcLessonTableData(),
					};

			    	wpcShowAjaxIcon();

					jQuery.post(ajaxurl, data, function(response) {
						wpcHideAjaxIcon();
					});
			    }
		});
	});
	</script> <?php
}
add_action( 'wp_ajax_order_lessons', 'wpc_order_lessons_action_callback' );
function wpc_order_lessons_action_callback(){

	update_lesson_order_and_meta($_POST['posts']);

    wp_die(); // required
}

// Ajax for adding modules

add_action( 'admin_footer', 'wpc_action_add_module_javascript' );

function wpc_action_add_module_javascript() { 
			
		if( isset($_GET['course-selection']) ){
			$course_id = $_GET['course-selection'];
		} elseif(isset($_GET['post'])){
			$course_id = $_GET['post'];
		} else {
			$course_id = 'null';
		}
	?>
	<script type="text/javascript" >
	<?php $ajax_nonce = wp_create_nonce( "wpc-add-module-nonce" ); ?>
	jQuery(document).ready(function($) {
		jQuery('#wpc-add-module').click(function(){

			var data = {
				'security'	: "<?php echo $ajax_nonce; ?>",
				'action'	: 'add_module',
				'course_id'	: "<?php echo $course_id; ?>",
				'posts'		: wpcLessonTableData(),
			}

			wpcShowAjaxIcon();

			jQuery.post(ajaxurl, data, function(response) {
				// Add the module.
				jQuery('.lesson-list').prepend('<li data-id="' + response + '" data-post-type="wpc-module" class="lesson-button wpc-order-lesson-list-lesson ui-sortable-handle wpc-module-button"><i class="fa fa-bars wpc-grab"></i><input type="text" placeholder="Module Name" class="wpc-module-title-input"><button type="button" class="wpc-delete-module button"><i class="fa fa-trash"></i></button></li>');
				// Trigger save action in order to save new module and its meta.  This must be done because the module's ID is in the response and thus the module's order and meta cannot be saved in the first AJAX call.
				jQuery('#wpc-save-order').click();
				wpcHideAjaxIcon();
			});
			
		});
	});
	</script> <?php
}

add_action( 'wp_ajax_add_module', 'wpc_add_module_action_callback' );

function wpc_add_module_action_callback(){

	check_ajax_referer( 'wpc-add-module-nonce', 'security' );

    $post_ID = wp_insert_post(array(
    	'post_title'	=> '',
    	'post_type'		=> 'wpc-module',
    	'post_status'	=> 'publish',
    ));

    update_post_meta($post_ID, 'wpc-connected-lesson-to-course', $_POST['course_id']);

	update_lesson_order_and_meta($_POST['posts']);

    echo json_encode($post_ID);

    wp_die(); // required

}



// ajax for saving lesson and module order and meta

add_action( 'admin_footer', 'wpc_action_save_lesson_order_javascript' );

function wpc_action_save_lesson_order_javascript() { ?>
	<?php $ajax_nonce = wp_create_nonce( "wpc-save-lesson-order-nonce" ); ?>
	<script type="text/javascript" >
	jQuery(document).ready(function($) {
		jQuery('#wpc-save-order').click(function(){

			var data = {
				'security'	: "<?php echo $ajax_nonce; ?>",
				'action'	: 'save_lesson_order',
				'posts'		: wpcLessonTableData(),
			}

			wpcShowAjaxIcon();

			jQuery.post(ajaxurl, data, function(response) {
				wpcHideAjaxIcon();
			});
			
		});
	});
	</script> <?php
}

add_action( 'wp_ajax_save_lesson_order', 'wpc_save_lesson_order_action_callback' );

function wpc_save_lesson_order_action_callback(){

	check_ajax_referer( 'wpc-save-lesson-order-nonce', 'security' );

	update_lesson_order_and_meta($_POST['posts']);

    wp_die(); // required
}


// Ajax for deleting modules

add_action( 'admin_footer', 'wpc_action_delete_module_javascript' );

function wpc_action_delete_module_javascript() { ?>
	<script type="text/javascript" >
	<?php $ajax_nonce = wp_create_nonce( "wpc-delete-module-nonce" ); ?>
	jQuery(document).ready(function($) {
		jQuery(document).on('click', '.wpc-delete-module', function(){

			var clicked = jQuery(this);
			clicked.parent().remove();

			var data = {
				'security'	: "<?php echo $ajax_nonce; ?>",
				'action'	: 'delete_module',
				'module_id'	: clicked.parent().attr('data-id'),
				'posts'		: wpcLessonTableData(),
			}

			wpcShowAjaxIcon();

			jQuery.post(ajaxurl, data, function(response) {
				// remove module from list
				wpcHideAjaxIcon();
			});
			
		});
	});
	</script> <?php
}

add_action( 'wp_ajax_delete_module', 'wpc_delete_module_action_callback' );

function wpc_delete_module_action_callback(){

	check_ajax_referer( 'wpc-delete-module-nonce', 'security' );

	wp_delete_post((int)$_POST['module_id']);

	update_lesson_order_and_meta($_POST['posts']);

    wp_die(); // required
}

// Ajax for renaming modules

add_action( 'admin_footer', 'wpc_action_rename_module_javascript' );

function wpc_action_rename_module_javascript() { ?>
	<script type="text/javascript" >
	<?php $ajax_nonce = wp_create_nonce( "wpc-module-title-nonce" ); ?>
	jQuery(document).ready(function($) {
		jQuery(document).on('keyup', '.wpc-module-title-input', function(){

			var clicked = jQuery(this);

			var data = {
				'security'		: "<?php echo $ajax_nonce; ?>",
				'action'		: 'rename_module',
				'module_id'		: clicked.parent().attr('data-id'),
				'module_title'	: clicked.val(),
			}

			wpcShowAjaxIcon();

			jQuery.post(ajaxurl, data, function(response) {
				wpcHideAjaxIcon();
			});
			
		});
	});
	</script> <?php
}

add_action( 'wp_ajax_rename_module', 'wpc_rename_module_action_callback' );

function wpc_rename_module_action_callback(){

	check_ajax_referer( 'wpc-module-title-nonce', 'security' );

	$my_post = array(
	  'ID'           => (int)$_POST['module_id'],
	  'post_title'   => $_POST['module_title'],
	);

	wp_update_post( $my_post );

    wp_die(); // required
}

function wpc_manage_students_page(){
	include 'admin-nav-menu.php'; ?>
	<div class="wrap">

		<?php $wpc_courses = new WPC_Courses();

		// single course progress page

		if(isset($_GET['course_id'])){ 

			$user = get_user_by( 'ID', $_GET['student_id'] ); 

			?>

			<a href="?page=manage_students&student_id=<?php echo $_GET['student_id']; ?>" class="button"><< <?php _e('Back', 'wp-courses'); ?></a>

			<?php echo '<h1 style="margin-bottom: 15px;">' . $user->display_name . "'s " . __('Progress', 'wp-courses') . ' for: ' . get_the_title($_GET['course_id']) . '</h1>'; ?>

			<div class="wpc-light-box">
				<?php echo wpc_get_single_course_progress_list($_GET['course_id'], $_GET['student_id']); ?>
			</div>

		<?php } elseif(isset($_GET['quiz_id'])){ ?>

			<?php echo wpcq_single_quiz_result_table($_GET['quiz_id'], $_GET['student_id']); ?>

		<?php } elseif(isset($_GET['student_id'])){

			$user = get_user_by( 'ID', $_GET['student_id'] ); ?>

			<a href="?page=manage_students" class="button"><< <?php _e('Back', 'wp-courses'); ?></a>

			<h1><?php echo $user->display_name; ?></h1>

			<h2 class="nav-tab-wrapper">
				<a href="#" class="nav-tab wpc-nav-tab nav-tab-active"><i class="fa fa-bar-chart"></i> <?php _e('Course Progress', 'wp-courses'); ?></a>
				<a href="#" class="nav-tab wpc-nav-tab"><i class="fa fa-eye"></i> <?php _e('Viewed Lessons', 'wp-courses'); ?></a>
				<a href="#" class="nav-tab wpc-nav-tab"><i class="fa fa-check"></i> <?php _e('Completed Lessons', 'wp-courses'); ?></a>
				<?php do_action( 'wpc_admin_after_student_nav_tabs' ); ?>
			</h2>

			<div id="wpc-user-viewed-lessons" class="wpc-tab-content">
				<h2><?php _e('Course Progress', 'wp-courses'); ?></h2>
				<?php echo wpc_get_course_progress_table($user->ID); ?>
			</div>

			<div id="wpc-user-viewed-lessons" class="wpc-tab-content wpc-hide">
				<h2><?php _e('Viewed Lessons', 'wp-courses'); ?></h2>
				<?php echo wpc_get_lesson_tracking_table($user->ID, true); ?>
			</div>

			<div id="wpc-user-completed-lessons" class="wpc-hide wpc-tab-content">

			<h2><?php _e('Completed Lessons', 'wp-courses'); ?></h2>
				<?php echo wpc_get_lesson_tracking_table($user->ID, false); ?>
			</div>

			<?php do_action( 'wpc_admin_after_student_nav_tabs_content' ); ?>

		</div> <!-- wrap -->
			
		<?php } else { ?>

			<h1 class="wpc-admin-h1"><?php _e('All Students', 'wp-courses'); ?></h1>

			<?php include 'templates/admin/wpc_admin_student_table.php'; ?>

		<?php } ?>



<?php }


// Order lesson page
function wpc_order_lessons_page(){
	$wpc_admin = new WPC_Admin();
	$course_id = isset($_GET['course-selection']) ? $_GET['course-selection'] : 'null';
	include 'admin-nav-menu.php';
	echo '<div class="wrap">';
		echo '<div>';
		echo '<h1 class="wpc-admin-h1">' . __('Order Lessons and Manage Modules', 'wp-courses') . '</h1>';
		echo '<form action="" method="get">';
		echo '<div class="tablenav top">';
			echo '<div class="alignleft actions">';
				echo $wpc_admin->get_course_dropdown($course_id);
				echo '<input name="page" value="order_lessons" type="hidden"/>';
				echo '<button type="submit" class="button" id="lesson-order-course-select">' . __('Select', 'wp-courses') . '</button>';
			echo '</div>';

			if(isset($_GET['course-selection'])){
				echo '<button type="button" id="wpc-add-module" class="button">' . __('Add Module', 'wp-courses') . '</button>';
				echo '<button type="button" id="wpc-save-order" class="button button-primary button-large" style="margin-left: 10px;">' . __('Save', 'wp-courses') . '</button>';
			}

		echo '</div>';
		echo '</form>';
		echo '</div>';

	if(isset($_GET['course-selection'])){
		if($_GET['course-selection'] != 'none'){
			echo $wpc_admin->get_lesson_list($_GET['course-selection']);
		}
	}

	echo '</div>';
}
add_action( 'admin_footer', 'wpc_change_course_javascript' );
function wpc_change_course_javascript() { 
	$ajax_nonce = wp_create_nonce( "request-is-good-wpc" ); ?>
	<script type="text/javascript" >
	jQuery(document).ready(function() {
		jQuery('.course-select').change(function(){
			var parent = jQuery(this).parent().parent();
			var data = {
				'security': "<?php echo $ajax_nonce; ?>",
				'action': 'change_course',
				'course_id': jQuery(this).val(),
				'lesson_id': parent.attr('id').replace('post-', ''),
			}
			if (confirm('Are you sure you want to change the connected course?')) {
			    wpcShowAjaxIcon();
			} else {
			    return;
			}
			jQuery.post(ajaxurl, data, function(response) {
				wpcHideAjaxIcon();
			});
			
		});
	});
	</script> <?php
}
add_action( 'wp_ajax_change_course', 'wpc_change_course_action_callback' );
function wpc_change_course_action_callback(){
	check_ajax_referer( 'request-is-good-wpc', 'security' );
	$lesson_id = (int) $_POST['lesson_id'];
	$course_id = $_POST['course_id'];
	update_post_meta($lesson_id, 'wpc-connected-lesson-to-course', $course_id);
	wp_die(); // required
}
add_action( 'admin_footer', 'wpc_change_lesson_restriction_javascript' );
function wpc_change_lesson_restriction_javascript() { 
	$ajax_nonce = wp_create_nonce( "request-is-good-wpc" ); ?>
	<script type="text/javascript" >
	jQuery(document).ready(function() {
		jQuery('.lesson-restriction-radio').click(function(){
			var parent = jQuery(this).parent().parent();
			var data = {
				'security': "<?php echo $ajax_nonce; ?>",
				'action': 'wpc_change_restriction',
				'lesson_id': parent.attr('id').replace('post-', ''),
				'restriction': jQuery(this).val(),
			}
			wpcShowAjaxIcon();
			jQuery.post(ajaxurl, data, function(response) {
				wpcHideAjaxIcon();
			});
			
		});
	});
	</script> <?php
}
add_action( 'wp_ajax_wpc_change_restriction', 'wpc_change_restriction_action_callback' );
function wpc_change_restriction_action_callback(){
	check_ajax_referer( 'request-is-good-wpc', 'security' );
	$lesson_id = (int) $_POST['lesson_id'];
	$restriction = addslashes(strip_tags($_POST['restriction']));
	update_post_meta($lesson_id, 'wpc-lesson-restriction', $restriction);
	wp_die(); // required
}
add_action( 'admin_footer', 'wpc_order_course_javascript' );
function wpc_order_course_javascript() { ?>
	<script type="text/javascript" >
	jQuery(document).ready(function() {
		jQuery( ".course-list" ).sortable({
			    axis: 'y',
			    update: function (event, ui) {
			        var posts = [];
			        jQuery('.course-list li').each(function(key, value){
			        	posts.push({
			        		'postID': jQuery(this).attr('data-id'),
			        		'menuOrder': key,
			        	});
			        });
					var data = {
						'action': 'order_course',
						'posts': posts,
					};
					wpcShowAjaxIcon();
					jQuery.post(ajaxurl, data, function(response) {
						wpcHideAjaxIcon();
					});
			    }
		});
	});
	</script> <?php
}
add_action( 'wp_ajax_order_course', 'wpc_order_course_action_callback' );
function wpc_order_course_action_callback(){
	global $wpdb;
    foreach($_POST['posts'] as $post){
	    	$order = (int) $post['menuOrder'];
	    	$id = (int) $post['postID'];
			$wpdb->query(
			    $wpdb->prepare(
			        "UPDATE $wpdb->posts SET menu_order = %d WHERE ID = %d",
			        $order,
			        $id
			    )
			);
    }
    wp_die(); // required
}
function wpc_order_courses_page(){ ?>
		<?php include 'admin-nav-menu.php'; ?>
		<div class="wrap">
			<h1 class="wpc-admin-h1"><?php _e('Order Courses', 'wp-courses'); ?></h1>
			<div id="order-course-msg-wrapper"></div>
			<div>
			<?php 
				$wpc_courses = new WPC_Admin();
				echo $wpc_courses->get_course_list();
			?>
			</div>
		</div>
<?php }