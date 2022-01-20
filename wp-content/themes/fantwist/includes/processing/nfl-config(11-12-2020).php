<?php

// Update options and show confirmation message

$show_updated_points_message = false;

if (isset($_POST['nfl-points-passingTD'])) {
	if ($_POST['nfl-points-passingTD'] || $_POST['nfl-points-passingTD'] == '0') {
		update_option( 'nfl-points-passingTD', (float) $_POST['nfl-points-passingTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-passingYds'])) {
	if ($_POST['nfl-points-passingYds'] || $_POST['nfl-points-passingYds'] == '0') {
		update_option( 'nfl-points-passingYds', (float) $_POST['nfl-points-passingYds'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-passingInt'])) {
	if ($_POST['nfl-points-passingInt'] || $_POST['nfl-points-passingInt'] == '0') {
		update_option( 'nfl-points-passingInt', (float) $_POST['nfl-points-passingInt'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-rushingTD'])) {
	if ($_POST['nfl-points-rushingTD'] || $_POST['nfl-points-rushingTD'] == '0') {
		update_option( 'nfl-points-rushingTD', (float) $_POST['nfl-points-rushingTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-rushingYds'])) {
	if ($_POST['nfl-points-rushingYds'] || $_POST['nfl-points-rushingYds'] == '0') {
		update_option( 'nfl-points-rushingYds', (float) $_POST['nfl-points-rushingYds'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-receivingTD'])) {
	if ($_POST['nfl-points-receivingTD'] || $_POST['nfl-points-receivingTD'] == '0') {
		update_option( 'nfl-points-receivingTD', (float) $_POST['nfl-points-receivingTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-receivingYds'])) {
	if ($_POST['nfl-points-receivingYds'] || $_POST['nfl-points-receivingYds'] == '0') {
		update_option( 'nfl-points-receivingYds', (float) $_POST['nfl-points-receivingYds'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-receptions'])) {
	if ($_POST['nfl-points-receptions'] || $_POST['nfl-points-receptions'] == '0') {
		update_option( 'nfl-points-receptions', (float) $_POST['nfl-points-receptions'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-kickReturnTD'])) {
	if ($_POST['nfl-points-kickReturnTD'] || $_POST['nfl-points-kickReturnTD'] == '0') {
		update_option( 'nfl-points-kickReturnTD', (float) $_POST['nfl-points-kickReturnTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-puntReturnTD'])) {
	if ($_POST['nfl-points-puntReturnTD'] || $_POST['nfl-points-puntReturnTD'] == '0') {
		update_option( 'nfl-points-puntReturnTD', (float) $_POST['nfl-points-puntReturnTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-fumbleLost'])) {
	if ($_POST['nfl-points-fumbleLost'] || $_POST['nfl-points-fumbleLost'] == '0') {
		update_option( 'nfl-points-fumbleLost', (float) $_POST['nfl-points-fumbleLost'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-ownFumbleRecTD'])) {
	if ($_POST['nfl-points-ownFumbleRecTD'] || $_POST['nfl-points-ownFumbleRecTD'] == '0') {
		update_option( 'nfl-points-ownFumbleRecTD', (float) $_POST['nfl-points-ownFumbleRecTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-2ptConversionScored'])) {
	if ($_POST['nfl-points-2ptConversionScored'] || $_POST['nfl-points-2ptConversionScored'] == '0') {
		update_option( 'nfl-points-2ptConversionScored', (float) $_POST['nfl-points-2ptConversionScored'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-2ptConversionPass'])) {
	if ($_POST['nfl-points-2ptConversionPass'] || $_POST['nfl-points-2ptConversionPass'] == '0') {
		update_option( 'nfl-points-2ptConversionPass', (float) $_POST['nfl-points-2ptConversionPass'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-defensiveSack'])) {
	if ($_POST['nfl-points-defensiveSack'] || $_POST['nfl-points-defensiveSack'] == '0') {
		update_option( 'nfl-points-defensiveSack', (float) $_POST['nfl-points-defensiveSack'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-defensiveFumbleRecovery'])) {
	if ($_POST['nfl-points-defensiveFumbleRecovery'] || $_POST['nfl-points-defensiveFumbleRecovery'] == '0') {
		update_option( 'nfl-points-defensiveFumbleRecovery', (float) $_POST['nfl-points-defensiveFumbleRecovery'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-defkickoffReturnTD'])) {
	if ($_POST['nfl-points-defkickoffReturnTD'] || $_POST['nfl-points-defkickoffReturnTD'] == '0') {
		update_option( 'nfl-points-defkickoffReturnTD', (float) $_POST['nfl-points-defkickoffReturnTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-defPuntReturnTD'])) {
	if ($_POST['nfl-points-defPuntReturnTD'] || $_POST['nfl-points-defPuntReturnTD'] == '0') {
		update_option( 'nfl-points-defPuntReturnTD', (float) $_POST['nfl-points-defPuntReturnTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-defExtraPtReturn'])) {
	if ($_POST['nfl-points-defExtraPtReturn'] || $_POST['nfl-points-defExtraPtReturn'] == '0') {
		update_option( 'nfl-points-defExtraPtReturn', (float) $_POST['nfl-points-defExtraPtReturn'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-def2PtConversionReturn'])) {
	if ($_POST['nfl-points-def2PtConversionReturn'] || $_POST['nfl-points-def2PtConversionReturn'] == '0') {
		update_option( 'nfl-points-def2PtConversionReturn', (float) $_POST['nfl-points-def2PtConversionReturn'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-defSafety'])) {
	if ($_POST['nfl-points-defSafety'] || $_POST['nfl-points-defSafety'] == '0') {
		update_option( 'nfl-points-defSafety', (float) $_POST['nfl-points-defSafety'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-defInterception'])) {
	if ($_POST['nfl-points-defInterception'] || $_POST['nfl-points-defInterception'] == '0') {
		update_option( 'nfl-points-defInterception', (float) $_POST['nfl-points-defInterception'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-defInterceptionReturnTD'])) {
	if ($_POST['nfl-points-defInterceptionReturnTD'] || $_POST['nfl-points-defInterceptionReturnTD'] == '0') {
		update_option( 'nfl-points-defInterceptionReturnTD', (float) $_POST['nfl-points-defInterceptionReturnTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-defFumbleRecoveryTD'])) {
	if ($_POST['nfl-points-defFumbleRecoveryTD'] || $_POST['nfl-points-defFumbleRecoveryTD'] == '0') {
		update_option( 'nfl-points-defFumbleRecoveryTD', (float) $_POST['nfl-points-defFumbleRecoveryTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-defBlockedKick'])) {
	if ($_POST['nfl-points-defBlockedKick'] || $_POST['nfl-points-defBlockedKick'] == '0') {
		update_option( 'nfl-points-defBlockedKick', (float) $_POST['nfl-points-defBlockedKick'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-def0ptsAllowed'])) {
	if ($_POST['nfl-points-def0ptsAllowed'] || $_POST['nfl-points-def0ptsAllowed'] == '0') {
		update_option( 'nfl-points-def0ptsAllowed', (float) $_POST['nfl-points-def0ptsAllowed'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-def1-6ptsAllowed'])) {
	if ($_POST['nfl-points-def1-6ptsAllowed'] || $_POST['nfl-points-def1-6ptsAllowed'] == '0') {
		update_option( 'nfl-points-def1-6ptsAllowed', (float) $_POST['nfl-points-def1-6ptsAllowed'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-def7-13ptsAllowed'])) {
	if ($_POST['nfl-points-def7-13ptsAllowed'] || $_POST['nfl-points-def7-13ptsAllowed'] == '0') {
		update_option( 'nfl-points-def7-13ptsAllowed', (float) $_POST['nfl-points-def7-13ptsAllowed'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-def14-20ptsAllowed'])) {
	if ($_POST['nfl-points-def14-20ptsAllowed'] || $_POST['nfl-points-def14-20ptsAllowed'] == '0') {
		update_option( 'nfl-points-def14-20ptsAllowed', (float) $_POST['nfl-points-def14-20ptsAllowed'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-def21-27ptsAllowed'])) {
	if ($_POST['nfl-points-def21-27ptsAllowed'] || $_POST['nfl-points-def21-27ptsAllowed'] == '0') {
		update_option( 'nfl-points-def21-27ptsAllowed', (float) $_POST['nfl-points-def21-27ptsAllowed'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-def28-34ptsAllowed'])) {
	if ($_POST['nfl-points-def28-34ptsAllowed'] || $_POST['nfl-points-def28-34ptsAllowed'] == '0') {
		update_option( 'nfl-points-def28-34ptsAllowed', (float) $_POST['nfl-points-def28-34ptsAllowed'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nfl-points-def35ptsAllowed'])) {
	if ($_POST['nfl-points-def35ptsAllowed'] || $_POST['nfl-points-def35ptsAllowed'] == '0') {
		update_option( 'nfl-points-def35ptsAllowed', (float) $_POST['nfl-points-def35ptsAllowed'] );
		$show_updated_points_message = true;
	}
}


if ($show_updated_points_message == true) {
	echo '<div id="message" class="updated fade"><p>NFL points algorithm updated.</p></div>';
}

if (isset($_POST['create_nfl_projections_and_contests_button_clicked'])) {
	
	$projection_key = '1f84d3da9fcb4f8fb276be1503989a33';
	
	if (isset($_POST['schedule_id'])) {
		
		$schedule_id = $_POST['schedule_id'];
		create_nfl_projections_and_contests($schedule_id, $projection_key);
		
	}
	else {
		
		echo '<div id="message" class="updated fade"><p>Select a valid NFL week.</p></div>';
		
	}
	
}
if (isset($_POST['update_live_nfl_contests_button_clicked'])){
	
	$stats_key = 'cc1a0c813b9b4267990116eb45c900b9';//'cc1a0c813b9b4267990116eb45c900b9';
	
	update_live_nfl_contests($stats_key, false);
	
	update_option( 'nfl-last-updated-live', date('m-d-Y g:i a', time() - (60*60*7))); 

}
if (isset($_POST['process_finished_nfl_contests_button'])) {
	
	$stats_key = 'cc1a0c813b9b4267990116eb45c900b9'; //'cc1a0c813b9b4267990116eb45c900b9';
	
	process_finished_nfl_contests($stats_key);
	
}


$args = array(
	'post_type' => 'cron_log',
	'post_status' => 'draft',
	'posts_per_page' => 1,
	'tax_query' => array(
		array(
			'taxonomy' => 'cron_type',
			'field'    => 'slug',
			'terms'    => 'nfl',
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



// NFL Dashboard table

echo '<div class="table league-table league-table-nfl">' .
'<div class="table-row strong align-center">' . 
'<div class="table-cell middle table-league-name align-center"><strong>NFL</strong></div>' . 
'<div class="table-cell middle table-tasks"><strong>Tasks</strong></div>' .
'<div class="table-cell middle table-points"><strong>Points Algorithm</strong></div>' .
'</div>' .
'<div class="table-row">' . '<div class="table-cell">&nbsp;</div>' .
'<div class="table-cell league-tasks"><ul style="list-style: disc; padding-left: 20px;"><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:0;">';	

//echo '<span style="float: left;display: inline-block;line-height: 28px;padding-right: 8px;font-weight: bold;font-size: 12px;">Select Week: </span><input type="text" name="custom_date_entry" placeholder="1" style="margin:0 0 0.5em;width:100%;" />';

echo '<input type="hidden" value="" name="create_nfl_projections_and_contests_button_clicked" />';

$tax_args = array(
  'taxonomy'		=> 'schedule',
  'orderby'			=> 'name',
  'show_count'		=> 0,
  'hierarchical'	=> 1,
  'hide_empty'		=> 0,
  'name'			=> 'schedule_id',
);
wp_dropdown_categories($tax_args);

echo '<div style="height:5px;"></div>';
submit_button('Create NFL Projections and Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: This can only be performed one time per contest date.</div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0">';
echo '<input type="hidden" value="" name="update_live_nfl_contests_button_clicked" />';
submit_button('Update Live NFL Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:green;font-weight:bold;">Auto (every 30 min)</span></div>';
echo '<div style="padding: 0;font-size: 10px;">Last updated: <span style="">'.$last_updated.'</span></div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0;">';
echo '<input type="hidden" value="" name="process_finished_nfl_contests_button" />';
submit_button('Process Finished NFL Contests and Wagers');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '</form></li></ul></div><div class="table-cell league-points align-center"><form action="options-general.php?page=pariwager-processing" method="post">';
settings_fields( 'pariwager-nfl-points' );
do_settings_sections( 'pariwager-nfl-points' );
echo '<h2 style="font-size: 14px;margin:0 1em 1em">Offense</h2><label>Passing TD: </label><input class="textbox" type="text" name="nfl-points-passingTD" value="'.esc_attr( get_option('nfl-points-passingTD') ).'" /><br>' .
'<label>Passing Yds: </label><input class="textbox" type="text" name="nfl-points-passingYds" value="'.esc_attr( get_option('nfl-points-passingYds') ).'" /><br>' .
'<label>Passing Int: </label><input class="textbox" type="text" name="nfl-points-passingInt" value="'.esc_attr( get_option('nfl-points-passingInt') ).'" /><br>' .
'<label>Rushing TD: </label><input class="textbox" type="text" name="nfl-points-rushingTD" value="'.esc_attr( get_option('nfl-points-rushingTD') ).'" /><br>' .
'<label>Rushing Yds: </label><input class="textbox" type="text" name="nfl-points-rushingYds" value="'.esc_attr( get_option('nfl-points-rushingYds') ).'" /><br>' .
'<label>Receiving TD: </label><input class="textbox" type="text" name="nfl-points-receivingTD" value="'.esc_attr( get_option('nfl-points-receivingTD') ).'" /><br>' .
'<label>Receiving Yds: </label><input class="textbox" type="text" name="nfl-points-receivingYds" value="'.esc_attr( get_option('nfl-points-receivingYds') ).'" /><br>' .
'<label>Reception: </label><input class="textbox" type="text" name="nfl-points-receptions" value="'.esc_attr( get_option('nfl-points-receptions') ).'" /><br>' .
'<label>Kickoff Return TD: </label><input class="textbox" type="text" name="nfl-points-kickReturnTD" value="'.esc_attr( get_option('nfl-points-kickReturnTD') ).'" /><br>' .
'<label>Punt Return TD: </label><input class="textbox" type="text" name="nfl-points-puntReturnTD" value="'.esc_attr( get_option('nfl-points-puntReturnTD') ).'" /><br>' .
'<label>Fumble Lost: </label><input class="textbox" type="text" name="nfl-points-fumbleLost" value="'.esc_attr( get_option('nfl-points-fumbleLost') ).'" /><br>' .
'<label>Own Fumble Recovered for TD: </label><input class="textbox" type="text" name="nfl-points-ownFumbleRecTD" value="'.esc_attr( get_option('nfl-points-ownFumbleRecTD') ).'" /><br>' .
'<label>2pt Conversion Scored: </label><input class="textbox" type="text" name="nfl-points-2ptConversionScored" value="'.esc_attr( get_option('nfl-points-2ptConversionScored') ).'" /><br>' .
'<label>2pt Conversion Pass: </label><input class="textbox" type="text" name="nfl-points-2ptConversionPass" value="'.esc_attr( get_option('nfl-points-2ptConversionPass') ).'" /><br>' .
'<hr><h2 style="margin:1em;font-size: 14px;">Defense/Special Teams</h2>' .
'<label>Sack: </label><input class="textbox" type="text" name="nfl-points-defensiveSack" value="'.esc_attr( get_option('nfl-points-defensiveSack') ).'" /><br>' .
'<label>Fumble Recovery: </label><input class="textbox" type="text" name="nfl-points-defensiveFumbleRecovery" value="'.esc_attr( get_option('nfl-points-defensiveFumbleRecovery') ).'" /><br>' .
'<label>Kickoff Return TD: </label><input class="textbox" type="text" name="nfl-points-defkickoffReturnTD" value="'.esc_attr( get_option('nfl-points-defkickoffReturnTD') ).'" /><br>' .
'<label>Punt Return TD: </label><input class="textbox" type="text" name="nfl-points-defPuntReturnTD" value="'.esc_attr( get_option('nfl-points-defPuntReturnTD') ).'" /><br>' .
'<label>Extra Point Return: </label><input class="textbox" type="text" name="nfl-points-defExtraPtReturn" value="'.esc_attr( get_option('nfl-points-defExtraPtReturn') ).'" /><br>' .
'<label>2pt Conversion Return: </label><input class="textbox" type="text" name="nfl-points-def2PtConversionReturn" value="'.esc_attr( get_option('nfl-points-def2PtConversionReturn') ).'" /><br>' .
'<label>Safety: </label><input class="textbox" type="text" name="nfl-points-defSafety" value="'.esc_attr( get_option('nfl-points-defSafety') ).'" /><br>' .
'<label>Interception: </label><input class="textbox" type="text" name="nfl-points-defInterception" value="'.esc_attr( get_option('nfl-points-defInterception') ).'" /><br>' .
'<label>Interception Return TD: </label><input class="textbox" type="text" name="nfl-points-defInterceptionReturnTD" value="'.esc_attr( get_option('nfl-points-defInterceptionReturnTD') ).'" /><br>' .
'<label>Fumble Recovery TD: </label><input class="textbox" type="text" name="nfl-points-defFumbleRecoveryTD" value="'.esc_attr( get_option('nfl-points-defFumbleRecoveryTD') ).'" /><br>' .
'<label>Blocked Punt/Kick: </label><input class="textbox" type="text" name="nfl-points-defBlockedKick" value="'.esc_attr( get_option('nfl-points-defBlockedKick') ).'" /><br>' .
'<label>0 pts Allowed: </label><input class="textbox" type="text" name="nfl-points-def0ptsAllowed" value="'.esc_attr( get_option('nfl-points-def0ptsAllowed') ).'" /><br>' .
'<label>1-6 pts Allowed: </label><input class="textbox" type="text" name="nfl-points-def1-6ptsAllowed" value="'.esc_attr( get_option('nfl-points-def1-6ptsAllowed') ).'" /><br>' .
'<label>7-13 pts Allowed: </label><input class="textbox" type="text" name="nfl-points-def7-13ptsAllowed" value="'.esc_attr( get_option('nfl-points-def7-13ptsAllowed') ).'" /><br>' .
'<label>14-20 pts Allowed: </label><input class="textbox" type="text" name="nfl-points-def14-20ptsAllowed" value="'.esc_attr( get_option('nfl-points-def14-20ptsAllowed') ).'" /><br>' .
'<label>21-27 pts Allowed: </label><input class="textbox" type="text" name="nfl-points-def21-27ptsAllowed" value="'.esc_attr( get_option('nfl-points-def21-27ptsAllowed') ).'" /><br>' .
'<label>28-34 pts Allowed: </label><input class="textbox" type="text" name="nfl-points-def28-34ptsAllowed" value="'.esc_attr( get_option('nfl-points-def28-34ptsAllowed') ).'" /><br>' .
'<label>35+ pts Allowed: </label><input class="textbox" type="text" name="nfl-points-def35ptsAllowed" value="'.esc_attr( get_option('nfl-points-def35ptsAllowed') ).'" /><br>' .
'<div class="update-points-btn update-nfl-points">';
submit_button('Update');
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: Algorithm adjustments will apply to <strong>LIVE</strong> and <strong>UPCOMING</strong> contests only.</div>';
echo '</div></form></div></div></div>';

?>