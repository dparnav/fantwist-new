<?php

// Update options and show confirmation message

$show_updated_points_message = false;

if (isset($_POST['nhl-points-goal'])) {
	if ($_POST['nhl-points-goal'] || $_POST['nhl-points-goal'] == '0') {
		update_option( 'nhl-points-goal', (float) $_POST['nhl-points-goal'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nhl-points-assist'])) {
	if ($_POST['nhl-points-assist'] || $_POST['nhl-points-assist'] == '0') {
		update_option( 'nhl-points-assist', (float) $_POST['nhl-points-assist'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nhl-points-shotongoal'])) {
	if ($_POST['nhl-points-shotongoal'] || $_POST['nhl-points-shotongoal'] == '0') {
		update_option( 'nhl-points-shotongoal', (float) $_POST['nhl-points-shotongoal'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nhl-points-blockedshot'])) {
	if ($_POST['nhl-points-blockedshot'] || $_POST['nhl-points-blockedshot'] == '0') {
		update_option( 'nhl-points-blockedshot', (float) $_POST['nhl-points-blockedshot'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nhl-points-shorthandedpointbonus'])) {
	if ($_POST['nhl-points-shorthandedpointbonus'] || $_POST['nhl-points-shorthandedpointbonus'] == '0') {
		update_option( 'nhl-points-shorthandedpointbonus', (float) $_POST['nhl-points-shorthandedpointbonus'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nhl-points-shootoutgoal'])) {
	if ($_POST['nhl-points-shootoutgoal'] || $_POST['nhl-points-shootoutgoal'] == '0') {
		update_option( 'nhl-points-shootoutgoal', (float) $_POST['nhl-points-shootoutgoal'] );
		$show_updated_points_message = true;
	}
} 
if (isset($_POST['nhl-points-hattrickbonus'])) {
	if ($_POST['nhl-points-hattrickbonus'] || $_POST['nhl-points-hattrickbonus'] == '0') {
		update_option( 'nhl-points-hattrickbonus', (float) $_POST['nhl-points-hattrickbonus'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nhl-points-goalwin'])) {
	if ($_POST['nhl-points-goalwin'] || $_POST['nhl-points-goalwin'] == '0') {
		update_option( 'nhl-points-goalwin', (float) $_POST['nhl-points-goalwin'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nhl-points-goalsave'])) {
	if ($_POST['nhl-points-goalsave'] || $_POST['nhl-points-goalsave'] == '0') {
		update_option( 'nhl-points-goalsave', (float) $_POST['nhl-points-goalsave'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nhl-points-goalagainst'])) {
	if ($_POST['nhl-points-goalagainst'] || $_POST['nhl-points-goalagainst'] == '0') {
		update_option( 'nhl-points-goalagainst', (float) $_POST['nhl-points-goalagainst'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nhl-points-shutoutbonus'])) {
	if ($_POST['nhl-points-shutoutbonus'] || $_POST['nhl-points-shutoutbonus'] == '0') {
		update_option( 'nhl-points-shutoutbonus', (float) $_POST['nhl-points-shutoutbonus'] );
		$show_updated_points_message = true;
	}
}

if ($show_updated_points_message == true) {
	echo '<div id="message" class="updated fade"><p>nhl points algorithm updated.</p></div>';
}

if (isset($_POST['create_nhl_projections_and_contests_button_clicked'])) {
	
	$projection_key = '67b23e7c2516424f97f6874947a62430';
	
	$date = date_create($_POST['custom_date_entry']);
	$date = date_format($date,"Y-M-d");
	
	
	if ($date == '') {
		$date = strtoupper(date('Y-M-d'));
	}
	
	create_nhl_projections_and_contests($date, $projection_key);

}
if (isset($_POST['update_live_nhl_contests_button_clicked'])){
	
	$stats_key = '815cb1bd3a7c4c8db30a5aee76dcf9c0';
	
	update_live_nhl_contests($stats_key, false);
	
	update_option( 'nhl-last-updated-live', date('m-d-Y g:i a', time() - (60*60*7))); 

}
if (isset($_POST['process_finished_nhl_contests_button'])) {
	
	$stats_key = '815cb1bd3a7c4c8db30a5aee76dcf9c0';
	
	process_finished_nhl_contests($stats_key);
	
}


$args = array(
	'post_type' => 'cron_log',
	'post_status' => 'draft',
	'posts_per_page' => 1,
	'tax_query' => array(
		array(
			'taxonomy' => 'cron_type',
			'field'    => 'slug',
			'terms'    => 'nhl',
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



// nhl Dashboard table

echo '<div class="table league-table league-table-nhl">' .
'<div class="table-row strong align-center">' . 
'<div class="table-cell middle table-league-name align-center"><strong>NHL</strong></div>' . 
'<div class="table-cell middle table-tasks"><strong>Tasks</strong></div>' .
'<div class="table-cell middle table-points"><strong>Points Algorithm</strong></div>' .
'</div>' .
'<div class="table-row">' . '<div class="table-cell">&nbsp;</div>' .
'<div class="table-cell league-tasks"><ul style="list-style: disc; padding-left: 20px;"><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:0;">';	
echo '<span style="float: left;display: inline-block;line-height: 28px;padding-right: 8px;font-weight: bold;font-size: 12px;">Enter Date: </span><input type="date" name="custom_date_entry" placeholder="'.$btnDate.'" style="margin:0 0 0.5em;width:100%;" /><input type="hidden" value="" name="create_nhl_projections_and_contests_button_clicked" />';
submit_button('Create NHL Projections and Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: This can only be performed one time per contest date.</div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0">';
echo '<input type="hidden" value="" name="update_live_nhl_contests_button_clicked" />';
submit_button('Update Live NHL Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:green;font-weight:bold;">Auto (every 30 min)</span></div>';
echo '<div style="padding: 0;font-size: 10px;">Last updated: <span style="">'.$last_updated.'</span></div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0;">';
echo '<input type="hidden" value="" name="process_finished_nhl_contests_button" />';
submit_button('Process Finished NHL Contests and Wagers');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '</form></li></ul></div><div class="table-cell league-points align-center"><form action="options-general.php?page=pariwager-processing" method="post">';
settings_fields( 'pariwager-nhl-points' );
do_settings_sections( 'pariwager-nhl-points' );
echo '<h2 style="font-size: 14px;margin:0 1em 1em">Players</h2><label>Goal: </label><input class="textbox" type="text" name="nhl-points-goal" value="'.esc_attr( get_option('nhl-points-goal') ).'" /><br>' .
'<label>Assist: </label><input class="textbox" type="text" name="nhl-points-assist" value="'.esc_attr( get_option('nhl-points-assist') ).'" /><br>' .
'<label>Shot on Goal: </label><input class="textbox" type="text" name="nhl-points-shotongoal" value="'.esc_attr( get_option('nhl-points-shotongoal') ).'" /><br>' .
'<label>Blocked Shot: </label><input class="textbox" type="text" name="nhl-points-blockedshot" value="'.esc_attr( get_option('nhl-points-blockedshot') ).'" /><br>' .
'<label>Short Handed Point Bonus: </label><input class="textbox" type="text" name="nhl-points-shorthandedpointbonus" value="'.esc_attr( get_option('nhl-points-shorthandedpointbonus') ).'" /><br>' .
'<label>Shootout Goal: </label><input class="textbox" type="text" name="nhl-points-shootoutgoal" value="'.esc_attr( get_option('nhl-points-shootoutgoal') ).'" /><br>' .
'<label>Hat Trick Bonus: </label><input class="textbox" type="text" name="nhl-points-hattrickbonus" value="'.esc_attr( get_option('nhl-points-hattrickbonus') ).'" /><br>' .
'<hr><h2 style="margin:1em;font-size: 14px;">Goalies</h2>' .
'<label>Win: </label><input class="textbox" type="text" name="nhl-points-goalwin" value="'.esc_attr( get_option('nhl-points-goalwin') ).'" /><br>' .
'<label>Save: </label><input class="textbox" type="text" name="nhl-points-goalsave" value="'.esc_attr( get_option('nhl-points-goalsave') ).'" /><br>' .
'<label>Goal Against: </label><input class="textbox" type="text" name="nhl-points-goalagainst" value="'.esc_attr( get_option('nhl-points-goalagainst') ).'" /><br>' .
'<label>Shutout Bonus: </label><input class="textbox" type="text" name="nhl-points-shutoutbonus" value="'.esc_attr( get_option('nhl-points-shutoutbonus') ).'" /><br>' .
'<div class="update-points-btn update-nhl-points">';
submit_button('Update');
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: Algorithm adjustments will apply to <strong>LIVE</strong> and <strong>UPCOMING</strong> contests only.</div>';
echo '</div></form></div></div></div>';

?>