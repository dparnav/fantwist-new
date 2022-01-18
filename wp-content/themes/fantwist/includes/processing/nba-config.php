<?php

// Update options and show confirmation message

$show_updated_points_message = false;

if (isset($_POST['nba-points-two'])) {
	if ($_POST['nba-points-two'] || $_POST['nba-points-two'] == '0') {
		update_option( 'nba-points-two', (float) $_POST['nba-points-two'] ); 
		$show_updated_points_message = true; 
	}
}
if (isset($_POST['nba-points-three'])) {
	if ($_POST['nba-points-three'] || $_POST['nba-points-three'] == '0') {
		update_option( 'nba-points-three', (float) $_POST['nba-points-three'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nba-points-free'])) {
	if ($_POST['nba-points-free'] || $_POST['nba-points-free'] == '0') {
		update_option( 'nba-points-free', (float) $_POST['nba-points-free'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nba-points-rebound'])) {
	if ($_POST['nba-points-rebound'] || $_POST['nba-points-rebound'] == '0') {
		update_option( 'nba-points-rebound', (float) $_POST['nba-points-rebound'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nba-points-assist'])) {
	if ($_POST['nba-points-assist'] || $_POST['nba-points-assist'] == '0') {
		update_option( 'nba-points-assist', (float) $_POST['nba-points-assist'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nba-points-steal'])) {
	if ($_POST['nba-points-steal'] || $_POST['nba-points-steal'] == '0') {
		update_option( 'nba-points-steal', (float) $_POST['nba-points-steal'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nba-points-block'])) {
	if ($_POST['nba-points-block'] || $_POST['nba-points-block'] == '0') {
		update_option( 'nba-points-block', (float) $_POST['nba-points-block'] ); 
		$show_updated_points_message = true; 
	}
}
if (isset($_POST['nba-points-turnover'])) {
	if ($_POST['nba-points-turnover'] || $_POST['nba-points-turnover'] == '0') {
		update_option( 'nba-points-turnover', (float) $_POST['nba-points-turnover'] );
		$show_updated_points_message = true;
	}
}

if ($show_updated_points_message == true) {
	echo '<div id="message" class="updated fade"><p>nba points algorithm updated.</p></div>';
}

if (isset($_POST['create_nba_projections_and_contests_button_clicked'])) {
	
	$projection_key = '055ebc1d62c84913bfbe4437aad5b267';
	
	$date = date_create($_POST['custom_date_entry']);
	$date = date_format($date,"Y-M-d");
	
	
	if ($date == '') {
		$date = strtoupper(date('Y-M-d'));
	}
	
	create_nba_projections_and_contests($date, $projection_key);

}
if (isset($_POST['update_live_nba_contests_button_clicked'])){
	
	$stats_key = '7cba61c8edbc4c4486b8b97308c3af08';
	
	update_live_nba_contests($stats_key, false);
	
	update_option( 'nba-last-updated-live', date('m-d-Y g:i a', time() - (60*60*7))); 

}
if (isset($_POST['process_finished_nba_contests_button'])) {
	
	$stats_key = '7cba61c8edbc4c4486b8b97308c3af08';
	
	process_finished_nba_contests($stats_key);
	
}


$args = array(
	'post_type' => 'cron_log',
	'post_status' => 'draft',
	'posts_per_page' => 1,
	'tax_query' => array(
		array(
			'taxonomy' => 'cron_type',
			'field'    => 'slug',
			'terms'    => 'nba',
		),
	),
);

$the_query = new WP_Query( $args );
			
if ( $the_query->have_posts() ) {	
	while ( $the_query->have_posts() ) {
		$the_query->the_post();
		
		$last_updated = human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago';
		
	}
}
wp_reset_query(); 


// nba Dashboard table

echo '<div class="table league-table league-table-nba">' .
'<div class="table-row strong align-center">' . 
'<div class="table-cell middle table-league-name align-center"><strong>NBA</strong></div>' . 
'<div class="table-cell middle table-tasks"><strong>Tasks</strong></div>' .
'<div class="table-cell middle table-points"><strong>Points Algorithm</strong></div>' .
'</div>' .
'<div class="table-row">' . '<div class="table-cell">&nbsp;</div>' .
'<div class="table-cell league-tasks"><ul style="list-style: disc; padding-left: 20px;"><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:0;">';	
echo '<span style="float: left;display: inline-block;line-height: 28px;padding-right: 8px;font-weight: bold;font-size: 12px;">Enter Date: </span><input type="date" name="custom_date_entry" placeholder="'.$btnDate.'" style="margin:0 0 0.5em;width:100%;" /><input type="hidden" value="" name="create_nba_projections_and_contests_button_clicked" />';
submit_button('Create NBA Projections and Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: This can only be performed one time per contest date.</div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0">';
echo '<input type="hidden" value="" name="update_live_nba_contests_button_clicked" />';
submit_button('Update Live NBA Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:green;font-weight:bold;">Auto (every 30 min)</span></div>';
echo '<div style="padding: 0;font-size: 10px;">Last updated: <span style="">'.$last_updated.'</span></div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0;">';
echo '<input type="hidden" value="" name="process_finished_nba_contests_button" />';
submit_button('Process Finished NBA Contests and Wagers');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '</form></li></ul></div><div class="table-cell league-points align-center"><form action="options-general.php?page=pariwager-processing" method="post">';
settings_fields( 'pariwager-nba-points' );
do_settings_sections( 'pariwager-nba-points' );
echo '<h2 style="font-size: 14px;margin:0 1em 1em">Players</h2>'.
'<label>Two Point: </label><input class="textbox" type="text" name="nba-points-two" value="'.esc_attr( get_option('nba-points-two') ).'" /><br>' .
'<label>Three Point: </label><input class="textbox" type="text" name="nba-points-three" value="'.esc_attr( get_option('nba-points-three') ).'" /><br>' .
'<label>Point: </label><input class="textbox" type="text" name="nba-points-free" value="'.esc_attr( get_option('nba-points-free') ).'" /><br>' .
'<label>Rebound: </label><input class="textbox" type="text" name="nba-points-rebound" value="'.esc_attr( get_option('nba-points-rebound') ).'" /><br>' .
'<label>Assist: </label><input class="textbox" type="text" name="nba-points-assist" value="'.esc_attr( get_option('nba-points-assist') ).'" /><br>' .
'<label>Steal: </label><input class="textbox" type="text" name="nba-points-steal" value="'.esc_attr( get_option('nba-points-steal') ).'" /><br>' .
'<label>Block: </label><input class="textbox" type="text" name="nba-points-block" value="'.esc_attr( get_option('nba-points-block') ).'" /><br>' .
'<label>Turnover: </label><input class="textbox" type="text" name="nba-points-turnover" value="'.esc_attr( get_option('nba-points-turnover') ).'" /><br>' .
'<div class="update-points-btn update-nba-points">';
submit_button('Update');
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: Algorithm adjustments will apply to <strong>LIVE</strong> and <strong>UPCOMING</strong> contests only.</div>';
echo '</div></form></div></div></div>';

?>