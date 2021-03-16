<?php

global $wpdb;

$paged = isset($_GET['paged']) ? $_GET['paged'] : 0;
$number = isset($_GET['student_count']) ? $_GET['student_count'] : 10;
$search = isset($_GET['s']) ? addslashes($_GET['s']) : '';
$table_name = $wpdb->prefix . 'users';

// search in $args is too exact, so searching with sql and passing ids to $args
$sql = "SELECT ID FROM {$table_name} WHERE user_login LIKE '%{$search}%' OR user_email LIKE '%{$search}%'";
$results = $wpdb->get_results($sql);

$student_ids = array();

// push ids to array so we can pass a simple array of user ids to get_users $args
if(!empty($results)){
	foreach($results as $result){
		array_push($student_ids, $result->ID);
	}
}

$args = array(
	'offset' 			=> $paged ? ($paged - 1) * $number : 0,
	'number' 			=> $number,
);

if(isset($_GET['s'])){
	// push argument 'include'
	$args['include'] = $student_ids;
	$total_users = count($student_ids);
} else {
	$total_users = count_users();
	$total_users = $total_users['total_users'];
}

$users = get_users( $args ); ?>

<div class="tablenav top">
	<form method="get" action="">
		<p class="search-box">
			<input type="search" value="" name="s" id="user-search-input"/>	
			<input type="submit" class="button" value="Search Users">
		</p>
		<input type="hidden" name="page" value="manage_students"/>
		<input type="hidden" name="student_count" value="<?php echo $number; ?>"/>
	</form>

	<form method="get" action="">
		<label for="wpc-student-count-select">Number of items per page: </label>
		<select id="wpc-student-count-select" name="student_count">
			<?php
				$values = array(10, 25, 50, 100);
				foreach($values as $value){
						$selected = $value == $number ? 'selected' : ''; 
						echo '<option value="' . $value . '" ' . $selected . '>' . $value . '</option>';
				}
			?>
		</select>
		<input type="hidden" name="page" value="manage_students"/>
		<input type="hidden" name="s" value="<?php echo $search; ?>"/>
		<input type="submit" class="button" value="Submit">
	</form>

	<div class="wpc-admin-pagination">
		<?php
			if($total_users > $number){
			  	$pl_args = array(
			    	'base'     		=> add_query_arg('paged','%#%'),
			   		'format'   		=> '',
			    	'total'    		=> ceil($total_users / $number),
			    	'current'  		=> max(1, $paged),
			    	'prev_text'     => '<< ' . __('Prev', 'wp-courses'),
					'next_text'     => __('Next', 'wp-courses') . ' >>',
			  	);
				echo paginate_links($pl_args);
			}
		?>
	</div>
</div>

<table class="widefat fixed" cellspacing="0">
	<thead>
		<tr>
			<th class="manage-column column-columnname" scope="col"><?php _e('Name', 'wp-courses'); ?></th>
			<th class="manage-column column-columnname" scope="col"><?php _e('Email', 'wp-courses'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
			$count = 0;

			foreach($users as $user) {
				$class = $count % 2 == 0 ? ' alternate' : '';

				echo '<tr class="' . $class . '">';
					echo '<td class="column-columnname"><a href="?page=manage_students&student_id=' . $user->ID . '"><strong>' . $user->display_name . '</strong></a></td>';
					echo '<td class="column-columnname">' . $user->user_email . '</td>';
				echo '</tr>';

				$count++;
			}
		?>

	<tbody>
	<tfoot>
		<tr>
			<th class="manage-column column-columnname" scope="col"><?php _e('Name', 'wp-courses'); ?></th>
			<th class="manage-column column-columnname" scope="col"><?php _e('Email', 'wp-courses'); ?></th>
		</tr>
	</tfoot>
</table>

<?php
	if($total_users > $number){ ?>
		<div class="tablenav top">
			<div class="wpc-admin-pagination">
				<?php echo paginate_links($pl_args); ?>
			</div>
		</div>
<?php } ?>