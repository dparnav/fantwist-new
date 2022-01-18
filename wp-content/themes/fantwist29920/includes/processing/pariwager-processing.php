<?php 
	
//Remove "Posts" post type
add_action( 'admin_menu', 'remove_default_post_type' );

function remove_default_post_type() {
    remove_menu_page( 'edit.php' );
}
	

//SORTABLE CUSTOM COLUMNS FOR CONTESTS
function contest_cpt_columns($columns) {

	unset(
		$columns['date']
	);
	
	unset(
		$columns['taxonomy-player']
	);
	
	unset(
		$columns['taxonomy-team']
	);
		
	$new_columns = array(
		'type' => 'Type',
		'contest_date' => 'Contest Date (PT)',
		'contest_status' => 'Status',
	);
    return array_merge($columns, $new_columns);
}
add_filter('manage_contest_posts_columns' , 'contest_cpt_columns');

function custom_columns( $column, $post_id ) {
	switch ( $column ) {
		case 'type':
			echo get_field('contest_type', $post_id);
			break;

		case 'contest_date':
			//echo get_field('contest_date', $post_id);
			//echo date('g:i a', get_field('contest_date', $post_id));
			echo date('m-d-Y g:i a', get_field('contest_date', $post_id));
			break;
			
		case 'contest_status':
			echo get_field('contest_status', $post_id);
			break;
	}
}

add_action( 'manage_posts_custom_column' , 'custom_columns', 10, 2 );

function contest_register_sortable( $columns ) {
	$columns['contest_date'] = 'Contest Date (PT)';
	return $columns;
}
add_filter("manage_edit-contest_sortable_columns", "contest_register_sortable" );





//SORTABLE CUSTOM COLUMNS FOR WAGERS
function wager_cpt_columns($columns) {
	
	unset(
		$columns['taxonomy-wager_result']
	);
		
	$new_columns = array(
		'wager_results' => 'Wager Results',
	);
	
    return array_merge($columns, $new_columns);
    
}
add_filter('manage_wager_posts_columns' , 'wager_cpt_columns');

function custom_wager_columns( $column, $post_id ) {
	switch ( $column ) {
		case 'wager_results':
			echo get_field('wager_result', $post_id);
			break;
	}
}

add_action( 'manage_posts_custom_column' , 'custom_wager_columns', 10, 2 );

function wager_register_sortable( $columns ) {
	$columns['wager_results'] = 'Wager Results';
	return $columns;
}
add_filter("manage_edit-wager_sortable_columns", "wager_register_sortable" );




//MLB global functions

require_once(dirname(__FILE__) . '/processing-global/mlb-processing.php');

//NFL global functions

require_once(dirname(__FILE__) . '/processing-global/nfl-processing.php');

//PGA global functions

require_once(dirname(__FILE__) . '/processing-global/pga-processing.php');

//NASCAR global functions

require_once(dirname(__FILE__) . '/processing-global/nascar-processing.php');

//MLS global functions

require_once(dirname(__FILE__) . '/processing-global/mls-processing.php');

//League of Legends global functions

require_once(dirname(__FILE__) . '/processing-global/lol-processing.php');

//NCAA-F global functions

require_once(dirname(__FILE__) . '/processing-global/ncaaf-processing.php');

// NHL global functions

require_once(dirname(__FILE__) . '/processing-global/nhl-processing.php');



//menu page
add_action('admin_menu', 'pariwager_processing_menu');

function pariwager_processing_menu() {
	add_menu_page('PariWager Processing', 'Processing', 'manage_options', 'pariwager-processing', 'pariwager_processing_admin_page');
}

function pariwager_processing_admin_page() {

	if (!current_user_can('manage_options')) { //check permissions
		wp_die( __('You do not have permission to view this page.'));
	}
	
	echo '<style>.table { border-top:1px solid white; border-bottom:1px solid white; border-right:1px solid white; display:table; width:100%; } .league-table .table-row:nth-of-type(odd) { background-color: #e0e0e0; } .league-table .table-row:nth-of-type(even) { background-color: #ececec } .table-row { display:table-row; } .align-center { text-align:center; } .table-cell.middle { vertical-align:middle; } .table-cell { border-left:1px solid #fff; display:table-cell; position:relative; padding: 10px 1em } .league-points.table-cell input.textbox { width:60px;display:inline-block;margin:0 auto 10px; } .league-points.table-cell input.button { display:block; margin:0 auto;} .league-points.table-cell label { font-weight:bold; padding-right:5px; }.league-tasks.table-cell input { float: none; display: block; margin: 0;} .table-league-name { font-size:20px; width: 115px; padding: 10px 1em;line-height:24px; } .league-table { margin: 1.5em 0; width: 800px; max-width: 100%; } .table-points { width:40% } .toplevel_page_pariwager-processing p.submit { padding:0; } .update-points-btn { margin:0.5em 0 0 }</style>';
	
	echo '<div class="wrap">';
	
		echo '<h2>PariWager Processing</h2>';
		
		echo 'The current server date/time is ' . date('m-d-y H:i') . ' UTC<br><br>';
		
		
		if (isset($_POST['custom_date_entry'])) {
			if ($_POST['custom_date_entry'] != '') {
				$date = $_POST['custom_date_entry'];
			}
			else {
				$date = strtoupper(date('Y-M-d'));
			}
		}
		else {
			$date = strtoupper(date('Y-M-d'));
		}
		
		$btnDate = strtoupper(date('Y-M-d'));
		
		
		// MLB 
		
		require_once(dirname(__FILE__) . '/mlb-config.php');
		
		// NFL 
		
		require_once(dirname(__FILE__) . '/nfl-config.php');
		
		// NCAA-F
		
		require_once(dirname(__FILE__) . '/ncaaf-config.php');
		
		// PGA
		
		//require_once(dirname(__FILE__) . '/pga-config.php');
		
		// NASCAR
		
		//require_once(dirname(__FILE__) . '/nascar-config.php');
		
		// MLS
		
		//require_once(dirname(__FILE__) . '/mls-config.php');
		
		// League of Legends
		
		//require_once(dirname(__FILE__) . '/lol-config.php');
		
		?>
		
		<script>
		jQuery(document).ready(function($){
	
			$('.toplevel_page_pariwager-processing input.disabled').attr('disabled','disabled');
						
			$('input.button-primary').on('click',function() {
				$('input.button-primary').addClass('disabled');
			});
			
		});
		</script>
	
		<?php
		
	echo '</div>'; //close wrap

}

?>