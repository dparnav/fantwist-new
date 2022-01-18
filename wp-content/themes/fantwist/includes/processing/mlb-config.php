<?php

// Update options and show confirmation message

$show_updated_points_message = false;

if (isset($_POST['mlb-points-runs'])) {
	if ($_POST['mlb-points-runs'] || $_POST['mlb-points-runs'] == '0') {
		update_option( 'mlb-points-runs', (float) $_POST['mlb-points-runs'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-singles'])) {
	if ($_POST['mlb-points-singles'] || $_POST['mlb-points-singles'] == '0') {
		update_option( 'mlb-points-singles', (float) $_POST['mlb-points-singles'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-doubles'])) {
	if ($_POST['mlb-points-doubles'] || $_POST['mlb-points-doubles'] == '0') {
		update_option( 'mlb-points-doubles', (float) $_POST['mlb-points-doubles'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-triples'])) {
	if ($_POST['mlb-points-triples'] || $_POST['mlb-points-triples'] == '0') {
		update_option( 'mlb-points-triples', (float) $_POST['mlb-points-triples'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-homeruns'])) {
	if ($_POST['mlb-points-homeruns'] || $_POST['mlb-points-homeruns'] == '0') {
		update_option( 'mlb-points-homeruns', (float) $_POST['mlb-points-homeruns'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-runsbattedin'])) {
	if ($_POST['mlb-points-runsbattedin'] || $_POST['mlb-points-runsbattedin'] == '0') {
		update_option( 'mlb-points-runsbattedin', (float) $_POST['mlb-points-runsbattedin'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-walks'])) {
	if ($_POST['mlb-points-walks'] || $_POST['mlb-points-walks'] == '0') {
		update_option( 'mlb-points-walks', (float) $_POST['mlb-points-walks'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-stolenbases'])) {
	if ($_POST['mlb-points-stolenbases'] || $_POST['mlb-points-stolenbases'] == '0') {
		update_option( 'mlb-points-stolenbases', (float) $_POST['mlb-points-stolenbases'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-caughtstealing'])) {
	if ($_POST['mlb-points-caughtstealing'] || $_POST['mlb-points-caughtstealing'] == '0') {
		update_option( 'mlb-points-caughtstealing', (float) $_POST['mlb-points-caughtstealing'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-pitchingwins'])) {
	if ($_POST['mlb-points-pitchingwins'] || $_POST['mlb-points-pitchingwins'] == '0') {
		update_option( 'mlb-points-pitchingwins', (float) $_POST['mlb-points-pitchingwins'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-inningspitcheddecimal'])) {
	if ($_POST['mlb-points-inningspitcheddecimal'] || $_POST['mlb-points-inningspitcheddecimal'] == '0') {
		update_option( 'mlb-points-inningspitcheddecimal', (float) $_POST['mlb-points-inningspitcheddecimal'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-pitchinghits'])) {
	if ($_POST['mlb-points-pitchinghits'] || $_POST['mlb-points-pitchinghits'] == '0') {
		update_option( 'mlb-points-pitchinghits', (float) $_POST['mlb-points-pitchinghits'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-pitchingearnedruns'])) {
	if ($_POST['mlb-points-pitchingearnedruns'] || $_POST['mlb-points-pitchingearnedruns'] == '0') {
		update_option( 'mlb-points-pitchingearnedruns', (float) $_POST['mlb-points-pitchingearnedruns'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-pitchingwalks'])) {
	if ($_POST['mlb-points-pitchingwalks'] || $_POST['mlb-points-pitchingwalks'] == '0') {
		update_option( 'mlb-points-pitchingwalks', (float) $_POST['mlb-points-pitchingwalks'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-pitchinghitbypitch'])) {
	if ($_POST['mlb-points-pitchinghitbypitch'] || $_POST['mlb-points-pitchinghitbypitch'] == '0') {
		update_option( 'mlb-points-pitchinghitbypitch', (float) $_POST['mlb-points-pitchinghitbypitch'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-pitchingstrikeouts'])) {
	if ($_POST['mlb-points-pitchingstrikeouts'] || $_POST['mlb-points-pitchingstrikeouts'] == '0') {
		update_option( 'mlb-points-pitchingstrikeouts', (float) $_POST['mlb-points-pitchingstrikeouts'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-pitchingnohitters'])) {
	if ($_POST['mlb-points-pitchingnohitters'] || $_POST['mlb-points-pitchingnohitters'] == '0') {
		update_option( 'mlb-points-pitchingnohitters', (float) $_POST['mlb-points-pitchingnohitters'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mlb-points-pitchingcompletegameshutouts'])) {
	if ($_POST['mlb-points-pitchingcompletegameshutouts'] || $_POST['mlb-points-pitchingcompletegameshutouts'] == '0') {
		update_option( 'mlb-points-pitchingcompletegameshutouts', (float) $_POST['mlb-points-pitchingcompletegameshutouts'] );
		$show_updated_points_message = true;
	}
}

if ($show_updated_points_message == true) {
	echo '<div id="message" class="updated fade"><p>MLB points algorithm updated.</p></div>';
}

if (isset($_POST['create_mlb_projections_and_contests_button_clicked'])) {
	
	$projection_key = 'd52da89973df40989625a6aa8ac3874c'; // '2100a540c8344b4d94399e64e604ccb3';

	$date = date_create($_POST['custom_date_entry']);
	$date = date_format($date,"Y-M-d");

	
	if ($date == '') {
		$date = strtoupper(date('Y-M-d'));
	}
	
	create_mlb_projections_and_contests($date, $projection_key);

}
if (isset($_POST['update_live_mlb_contests_button_clicked'])){
	
	$stats_key = '8a0f611eb4fd46b384f9e390a43b5b92'; //'562f123e387a4c2bbb37395741d0a539';
	
	update_live_mlb_contests($stats_key, false);
	
	update_option( 'mlb-last-updated-live', date('m-d-Y g:i a', time() - (60*60*7))); 

}
if (isset($_POST['process_finished_mlb_contests_button'])) {
	
	$stats_key = '8a0f611eb4fd46b384f9e390a43b5b92'; //'562f123e387a4c2bbb37395741d0a539';
	
	process_finished_mlb_contests($stats_key);
	
}


$args = array(
	'post_type' => 'cron_log',
	'post_status' => 'draft',
	'posts_per_page' => 1,
	'tax_query' => array(
		array(
			'taxonomy' => 'cron_type',
			'field'    => 'slug',
			'terms'    => 'mlb',
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



// MLB Dashboard table

echo '<div class="table league-table league-table-mlb">' .
'<div class="table-row strong align-center">' . 
'<div class="table-cell middle table-league-name align-center"><strong>MLB</strong></div>' . 
'<div class="table-cell middle table-tasks"><strong>Tasks</strong></div>' .
'<div class="table-cell middle table-points"><strong>Points Algorithm</strong></div>' .
'</div>' .
'<div class="table-row">' . '<div class="table-cell">&nbsp;</div>' .
'<div class="table-cell league-tasks"><ul style="list-style: disc; padding-left: 20px;"><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:0;">';	
echo '<span style="float: left;display: inline-block;line-height: 28px;padding-right: 8px;font-weight: bold;font-size: 12px;">Enter Date: </span><input type="date" name="custom_date_entry" placeholder="'.$btnDate.'" style="margin:0 0 0.5em;width:100%;" /><input type="hidden" value="" name="create_mlb_projections_and_contests_button_clicked" />';
submit_button('Create MLB Projections and Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: This can only be performed one time per contest date.</div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0">';
echo '<input type="hidden" value="" name="update_live_mlb_contests_button_clicked" />';
submit_button('Update Live MLB Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:green;font-weight:bold;">Auto (every 30 min)</span></div>';
echo '<div style="padding: 0;font-size: 10px;">Last updated: <span style="">'.$last_updated.'</span></div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0;">';
echo '<input type="hidden" value="" name="process_finished_mlb_contests_button" />';
submit_button('Process Finished MLB Contests and Wagers');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '</form></li></ul></div><div class="table-cell league-points align-center"><form action="options-general.php?page=pariwager-processing" method="post">';
settings_fields( 'pariwager-mlb-points' );
do_settings_sections( 'pariwager-mlb-points' );
echo '<h2 style="font-size: 14px;margin:0 1em 1em">Non-Pitchers</h2><label>Run: </label><input class="textbox" type="text" name="mlb-points-runs" value="'.esc_attr( get_option('mlb-points-runs') ).'" /><br>' .
'<label>Single: </label><input class="textbox" type="text" name="mlb-points-singles" value="'.esc_attr( get_option('mlb-points-singles') ).'" /><br>' .
'<label>Double: </label><input class="textbox" type="text" name="mlb-points-doubles" value="'.esc_attr( get_option('mlb-points-doubles') ).'" /><br>' .
'<label>Triple: </label><input class="textbox" type="text" name="mlb-points-triples" value="'.esc_attr( get_option('mlb-points-triples') ).'" /><br>' .
'<label>Home Run: </label><input class="textbox" type="text" name="mlb-points-homeruns" value="'.esc_attr( get_option('mlb-points-homeruns') ).'" /><br>' .
'<label>RBI: </label><input class="textbox" type="text" name="mlb-points-runsbattedin" value="'.esc_attr( get_option('mlb-points-runsbattedin') ).'" /><br>' .
'<label>Walk: </label><input class="textbox" type="text" name="mlb-points-walks" value="'.esc_attr( get_option('mlb-points-walks') ).'" /><br>' .
'<label>Stolen Base: </label><input class="textbox" type="text" name="mlb-points-stolenbases" value="'.esc_attr( get_option('mlb-points-stolenbases') ).'" /><br>' .
'<label>Caught Stealing: </label><input class="textbox" type="text" name="mlb-points-caughtstealing" value="'.esc_attr( get_option('mlb-points-caughtstealing') ).'" /><br>' .
'<hr><h2 style="margin:1em;font-size: 14px;">Pitchers</h2>' .
'<label>Win (P): </label><input class="textbox" type="text" name="mlb-points-pitchingwins" value="'.esc_attr( get_option('mlb-points-pitchingwins') ).'" /><br>' .
'<label>Inning Pitched: </label><input class="textbox" type="text" name="mlb-points-inningspitcheddecimal" value="'.esc_attr( get_option('mlb-points-inningspitcheddecimal') ).'" /><br>' .
'<label>Hit Against: </label><input class="textbox" type="text" name="mlb-points-pitchinghits" value="'.esc_attr( get_option('mlb-points-pitchinghits') ).'" /><br>' .
'<label>Earned Run Allowed: </label><input class="textbox" type="text" name="mlb-points-pitchingearnedruns" value="'.esc_attr( get_option('mlb-points-pitchingearnedruns') ).'" /><br>' .
'<label>Walk Against: </label><input class="textbox" type="text" name="mlb-points-pitchingwalks" value="'.esc_attr( get_option('mlb-points-pitchingwalks') ).'" /><br>' .
'<label>Hit Batsman: </label><input class="textbox" type="text" name="mlb-points-pitchinghitbypitch" value="'.esc_attr( get_option('mlb-points-pitchinghitbypitch') ).'" /><br>' .
'<label>Strikeout (P): </label><input class="textbox" type="text" name="mlb-points-pitchingstrikeouts" value="'.esc_attr( get_option('mlb-points-pitchingstrikeouts') ).'" /><br>' .
'<label>No-Hitter: </label><input class="textbox" type="text" name="mlb-points-pitchingnohitters" value="'.esc_attr( get_option('mlb-points-pitchingnohitters') ).'" /><br>' .
'<label>CG Shutout: </label><input class="textbox" type="text" name="mlb-points-pitchingcompletegameshutouts" value="'.esc_attr( get_option('mlb-points-pitchingcompletegameshutouts') ).'" /><br>' .
'<div class="update-points-btn update-mlb-points">';
submit_button('Update');
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: Algorithm adjustments will apply to <strong>LIVE</strong> and <strong>UPCOMING</strong> contests only.</div>';
echo '</div></form></div></div></div>';

?>