<?php

// Update options and show confirmation message

$show_updated_points_message = false;

if (isset($_POST['ncaaf-points-passingTD'])) {
	if ($_POST['ncaaf-points-passingTD'] || $_POST['ncaaf-points-passingTD'] == '0') {
		update_option( 'ncaaf-points-passingTD', (float) $_POST['ncaaf-points-passingTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-passingYds'])) {
	if ($_POST['ncaaf-points-passingYds'] || $_POST['ncaaf-points-passingYds'] == '0') {
		update_option( 'ncaaf-points-passingYds', (float) $_POST['ncaaf-points-passingYds'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-passingInt'])) {
	if ($_POST['ncaaf-points-passingInt'] || $_POST['ncaaf-points-passingInt'] == '0') {
		update_option( 'ncaaf-points-passingInt', (float) $_POST['ncaaf-points-passingInt'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-rushingTD'])) {
	if ($_POST['ncaaf-points-rushingTD'] || $_POST['ncaaf-points-rushingTD'] == '0') {
		update_option( 'ncaaf-points-rushingTD', (float) $_POST['ncaaf-points-rushingTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-rushingYds'])) {
	if ($_POST['ncaaf-points-rushingYds'] || $_POST['ncaaf-points-rushingYds'] == '0') {
		update_option( 'ncaaf-points-rushingYds', (float) $_POST['ncaaf-points-rushingYds'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-receivingTD'])) {
	if ($_POST['ncaaf-points-receivingTD'] || $_POST['ncaaf-points-receivingTD'] == '0') {
		update_option( 'ncaaf-points-receivingTD', (float) $_POST['ncaaf-points-receivingTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-receivingYds'])) {
	if ($_POST['ncaaf-points-receivingYds'] || $_POST['ncaaf-points-receivingYds'] == '0') {
		update_option( 'ncaaf-points-receivingYds', (float) $_POST['ncaaf-points-receivingYds'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-receptions'])) {
	if ($_POST['ncaaf-points-receptions'] || $_POST['ncaaf-points-receptions'] == '0') {
		update_option( 'ncaaf-points-receptions', (float) $_POST['ncaaf-points-receptions'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-kickReturnTD'])) {
	if ($_POST['ncaaf-points-kickReturnTD'] || $_POST['ncaaf-points-kickReturnTD'] == '0') {
		update_option( 'ncaaf-points-kickReturnTD', (float) $_POST['ncaaf-points-kickReturnTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-puntReturnTD'])) {
	if ($_POST['ncaaf-points-puntReturnTD'] || $_POST['ncaaf-points-puntReturnTD'] == '0') {
		update_option( 'ncaaf-points-puntReturnTD', (float) $_POST['ncaaf-points-puntReturnTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-fumbleLost'])) {
	if ($_POST['ncaaf-points-fumbleLost'] || $_POST['ncaaf-points-fumbleLost'] == '0') {
		update_option( 'ncaaf-points-fumbleLost', (float) $_POST['ncaaf-points-fumbleLost'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-ownFumbleRecTD'])) {
	if ($_POST['ncaaf-points-ownFumbleRecTD'] || $_POST['ncaaf-points-ownFumbleRecTD'] == '0') {
		update_option( 'ncaaf-points-ownFumbleRecTD', (float) $_POST['ncaaf-points-ownFumbleRecTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-2ptConversionScored'])) {
	if ($_POST['ncaaf-points-2ptConversionScored'] || $_POST['ncaaf-points-2ptConversionScored'] == '0') {
		update_option( 'ncaaf-points-2ptConversionScored', (float) $_POST['ncaaf-points-2ptConversionScored'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-2ptConversionPass'])) {
	if ($_POST['ncaaf-points-2ptConversionPass'] || $_POST['ncaaf-points-2ptConversionPass'] == '0') {
		update_option( 'ncaaf-points-2ptConversionPass', (float) $_POST['ncaaf-points-2ptConversionPass'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-defensiveSack'])) {
	if ($_POST['ncaaf-points-defensiveSack'] || $_POST['ncaaf-points-defensiveSack'] == '0') {
		update_option( 'ncaaf-points-defensiveSack', (float) $_POST['ncaaf-points-defensiveSack'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-defensiveFumbleRecovery'])) {
	if ($_POST['ncaaf-points-defensiveFumbleRecovery'] || $_POST['ncaaf-points-defensiveFumbleRecovery'] == '0') {
		update_option( 'ncaaf-points-defensiveFumbleRecovery', (float) $_POST['ncaaf-points-defensiveFumbleRecovery'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-defkickoffReturnTD'])) {
	if ($_POST['ncaaf-points-defkickoffReturnTD'] || $_POST['ncaaf-points-defkickoffReturnTD'] == '0') {
		update_option( 'ncaaf-points-defkickoffReturnTD', (float) $_POST['ncaaf-points-defkickoffReturnTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-defPuntReturnTD'])) {
	if ($_POST['ncaaf-points-defPuntReturnTD'] || $_POST['ncaaf-points-defPuntReturnTD'] == '0') {
		update_option( 'ncaaf-points-defPuntReturnTD', (float) $_POST['ncaaf-points-defPuntReturnTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-defExtraPtReturn'])) {
	if ($_POST['ncaaf-points-defExtraPtReturn'] || $_POST['ncaaf-points-defExtraPtReturn'] == '0') {
		update_option( 'ncaaf-points-defExtraPtReturn', (float) $_POST['ncaaf-points-defExtraPtReturn'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-def2PtConversionReturn'])) {
	if ($_POST['ncaaf-points-def2PtConversionReturn'] || $_POST['ncaaf-points-def2PtConversionReturn'] == '0') {
		update_option( 'ncaaf-points-def2PtConversionReturn', (float) $_POST['ncaaf-points-def2PtConversionReturn'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-defSafety'])) {
	if ($_POST['ncaaf-points-defSafety'] || $_POST['ncaaf-points-defSafety'] == '0') {
		update_option( 'ncaaf-points-defSafety', (float) $_POST['ncaaf-points-defSafety'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-defInterception'])) {
	if ($_POST['ncaaf-points-defInterception'] || $_POST['ncaaf-points-defInterception'] == '0') {
		update_option( 'ncaaf-points-defInterception', (float) $_POST['ncaaf-points-defInterception'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-defInterceptionReturnTD'])) {
	if ($_POST['ncaaf-points-defInterceptionReturnTD'] || $_POST['ncaaf-points-defInterceptionReturnTD'] == '0') {
		update_option( 'ncaaf-points-defInterceptionReturnTD', (float) $_POST['ncaaf-points-defInterceptionReturnTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-defFumbleRecoveryTD'])) {
	if ($_POST['ncaaf-points-defFumbleRecoveryTD'] || $_POST['ncaaf-points-defFumbleRecoveryTD'] == '0') {
		update_option( 'ncaaf-points-defFumbleRecoveryTD', (float) $_POST['ncaaf-points-defFumbleRecoveryTD'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-defBlockedKick'])) {
	if ($_POST['ncaaf-points-defBlockedKick'] || $_POST['ncaaf-points-defBlockedKick'] == '0') {
		update_option( 'ncaaf-points-defBlockedKick', (float) $_POST['ncaaf-points-defBlockedKick'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-def0ptsAllowed'])) {
	if ($_POST['ncaaf-points-def0ptsAllowed'] || $_POST['ncaaf-points-def0ptsAllowed'] == '0') {
		update_option( 'ncaaf-points-def0ptsAllowed', (float) $_POST['ncaaf-points-def0ptsAllowed'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-def1-6ptsAllowed'])) {
	if ($_POST['ncaaf-points-def1-6ptsAllowed'] || $_POST['ncaaf-points-def1-6ptsAllowed'] == '0') {
		update_option( 'ncaaf-points-def1-6ptsAllowed', (float) $_POST['ncaaf-points-def1-6ptsAllowed'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-def7-13ptsAllowed'])) {
	if ($_POST['ncaaf-points-def7-13ptsAllowed'] || $_POST['ncaaf-points-def7-13ptsAllowed'] == '0') {
		update_option( 'ncaaf-points-def7-13ptsAllowed', (float) $_POST['ncaaf-points-def7-13ptsAllowed'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-def14-20ptsAllowed'])) {
	if ($_POST['ncaaf-points-def14-20ptsAllowed'] || $_POST['ncaaf-points-def14-20ptsAllowed'] == '0') {
		update_option( 'ncaaf-points-def14-20ptsAllowed', (float) $_POST['ncaaf-points-def14-20ptsAllowed'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-def21-27ptsAllowed'])) {
	if ($_POST['ncaaf-points-def21-27ptsAllowed'] || $_POST['ncaaf-points-def21-27ptsAllowed'] == '0') {
		update_option( 'ncaaf-points-def21-27ptsAllowed', (float) $_POST['ncaaf-points-def21-27ptsAllowed'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-def28-34ptsAllowed'])) {
	if ($_POST['ncaaf-points-def28-34ptsAllowed'] || $_POST['ncaaf-points-def28-34ptsAllowed'] == '0') {
		update_option( 'ncaaf-points-def28-34ptsAllowed', (float) $_POST['ncaaf-points-def28-34ptsAllowed'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['ncaaf-points-def35ptsAllowed'])) {
	if ($_POST['ncaaf-points-def35ptsAllowed'] || $_POST['ncaaf-points-def35ptsAllowed'] == '0') {
		update_option( 'ncaaf-points-def35ptsAllowed', (float) $_POST['ncaaf-points-def35ptsAllowed'] );
		$show_updated_points_message = true;
	}
}


if ($show_updated_points_message == true) {
	echo '<div id="message" class="updated fade"><p>NCAA-F points algorithm updated.</p></div>';
}

if (isset($_POST['create_ncaaf_projections_and_contests_button_clicked'])) {
	
	$projection_key = 'cc1a0c813b9b4267990116eb45c900b9';
	
	if (isset($_POST['schedule_id'])) {
		
		$schedule_id = $_POST['schedule_id'];
		create_ncaaf_projections_and_contests($schedule_id, $projection_key);
		
	}
	else {
		
		echo '<div id="message" class="updated fade"><p>Select a valid NCAA-F week.</p></div>';
		
	}
	
}
if (isset($_POST['update_live_ncaaf_contests_button_clicked'])){
	
	$stats_key = 'cc1a0c813b9b4267990116eb45c900b9';
	
	update_live_ncaaf_contests($stats_key, false);
	
	update_option( 'ncaaf-last-updated-live', date('m-d-Y g:i a', time() - (60*60*7))); 

}
if (isset($_POST['process_finished_ncaaf_contests_button'])) {
	
	$stats_key = 'cc1a0c813b9b4267990116eb45c900b9';
	
	process_finished_ncaaf_contests($stats_key);
	
}


$args = array(
	'post_type' => 'cron_log',
	'post_status' => 'draft',
	'posts_per_page' => 1,
	'tax_query' => array(
		array(
			'taxonomy' => 'cron_type',
			'field'    => 'slug',
			'terms'    => 'ncaaf',
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



// NCAA-F Dashboard table
echo '<span style="color:red;font-weight:bold;">IN DEVELOPMENT DO NOT TOUCH</span>';
echo '<div class="table league-table league-table-ncaaf">' .
'<div class="table-row strong align-center">' . 
'<div class="table-cell middle table-league-name align-center"><strong>NCAA-F</strong></div>' . 
'<div class="table-cell middle table-tasks"><strong>Tasks</strong></div>' .
'<div class="table-cell middle table-points"><strong>Points Algorithm</strong></div>' .
'</div>' .
'<div class="table-row">' . '<div class="table-cell">&nbsp;</div>' .
'<div class="table-cell league-tasks"><ul style="list-style: disc; padding-left: 20px;"><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:0;">';	

//echo '<span style="float: left;display: inline-block;line-height: 28px;padding-right: 8px;font-weight: bold;font-size: 12px;">Select Week: </span><input type="text" name="custom_date_entry" placeholder="1" style="margin:0 0 0.5em;width:100%;" />';

echo '<input type="hidden" value="" name="create_ncaaf_projections_and_contests_button_clicked" />';

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
submit_button('Create NCAA-F Projections and Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: This can only be performed one time per contest date.</div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0">';
echo '<input type="hidden" value="" name="update_live_ncaaf_contests_button_clicked" />';
submit_button('Update Live NCAA-F Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:green;font-weight:bold;">Auto (every 30 min)</span></div>';
echo '<div style="padding: 0;font-size: 10px;">Last updated: <span style="">'.$last_updated.'</span></div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0;">';
echo '<input type="hidden" value="" name="process_finished_ncaaf_contests_button" />';
submit_button('Process Finished NCAA-F Contests and Wagers');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '</form></li></ul></div><div class="table-cell league-points align-center"><form action="options-general.php?page=pariwager-processing" method="post">';
settings_fields( 'pariwager-ncaaf-points' );
do_settings_sections( 'pariwager-ncaaf-points' );
echo '<h2 style="font-size: 14px;margin:0 1em 1em">Offense</h2><label>Passing TD: </label><input class="textbox" type="text" name="ncaaf-points-passingTD" value="'.esc_attr( get_option('ncaaf-points-passingTD') ).'" /><br>' .
'<label>Passing Yds: </label><input class="textbox" type="text" name="ncaaf-points-passingYds" value="'.esc_attr( get_option('ncaaf-points-passingYds') ).'" /><br>' .
'<label>Passing Int: </label><input class="textbox" type="text" name="ncaaf-points-passingInt" value="'.esc_attr( get_option('ncaaf-points-passingInt') ).'" /><br>' .
'<label>Rushing TD: </label><input class="textbox" type="text" name="ncaaf-points-rushingTD" value="'.esc_attr( get_option('ncaaf-points-rushingTD') ).'" /><br>' .
'<label>Rushing Yds: </label><input class="textbox" type="text" name="ncaaf-points-rushingYds" value="'.esc_attr( get_option('ncaaf-points-rushingYds') ).'" /><br>' .
'<label>Receiving TD: </label><input class="textbox" type="text" name="ncaaf-points-receivingTD" value="'.esc_attr( get_option('ncaaf-points-receivingTD') ).'" /><br>' .
'<label>Receiving Yds: </label><input class="textbox" type="text" name="ncaaf-points-receivingYds" value="'.esc_attr( get_option('ncaaf-points-receivingYds') ).'" /><br>' .
'<label>Reception: </label><input class="textbox" type="text" name="ncaaf-points-receptions" value="'.esc_attr( get_option('ncaaf-points-receptions') ).'" /><br>' .
'<label>Kickoff Return TD: </label><input class="textbox" type="text" name="ncaaf-points-kickReturnTD" value="'.esc_attr( get_option('ncaaf-points-kickReturnTD') ).'" /><br>' .
'<label>Punt Return TD: </label><input class="textbox" type="text" name="ncaaf-points-puntReturnTD" value="'.esc_attr( get_option('ncaaf-points-puntReturnTD') ).'" /><br>' .
'<label>Fumble Lost: </label><input class="textbox" type="text" name="ncaaf-points-fumbleLost" value="'.esc_attr( get_option('ncaaf-points-fumbleLost') ).'" /><br>' .
'<label>Own Fumble Recovered for TD: </label><input class="textbox" type="text" name="ncaaf-points-ownFumbleRecTD" value="'.esc_attr( get_option('ncaaf-points-ownFumbleRecTD') ).'" /><br>' .
'<label>2pt Conversion Scored: </label><input class="textbox" type="text" name="ncaaf-points-2ptConversionScored" value="'.esc_attr( get_option('ncaaf-points-2ptConversionScored') ).'" /><br>' .
'<label>2pt Conversion Pass: </label><input class="textbox" type="text" name="ncaaf-points-2ptConversionPass" value="'.esc_attr( get_option('ncaaf-points-2ptConversionPass') ).'" /><br>' .
'<hr><h2 style="margin:1em;font-size: 14px;">Defense/Special Teams</h2>' .
'<label>Sack: </label><input class="textbox" type="text" name="ncaaf-points-defensiveSack" value="'.esc_attr( get_option('ncaaf-points-defensiveSack') ).'" /><br>' .
'<label>Fumble Recovery: </label><input class="textbox" type="text" name="ncaaf-points-defensiveFumbleRecovery" value="'.esc_attr( get_option('ncaaf-points-defensiveFumbleRecovery') ).'" /><br>' .
'<label>Kickoff Return TD: </label><input class="textbox" type="text" name="ncaaf-points-defkickoffReturnTD" value="'.esc_attr( get_option('ncaaf-points-defkickoffReturnTD') ).'" /><br>' .
'<label>Punt Return TD: </label><input class="textbox" type="text" name="ncaaf-points-defPuntReturnTD" value="'.esc_attr( get_option('ncaaf-points-defPuntReturnTD') ).'" /><br>' .
'<label>Extra Point Return: </label><input class="textbox" type="text" name="ncaaf-points-defExtraPtReturn" value="'.esc_attr( get_option('ncaaf-points-defExtraPtReturn') ).'" /><br>' .
'<label>2pt Conversion Return: </label><input class="textbox" type="text" name="ncaaf-points-def2PtConversionReturn" value="'.esc_attr( get_option('ncaaf-points-def2PtConversionReturn') ).'" /><br>' .
'<label>Safety: </label><input class="textbox" type="text" name="ncaaf-points-defSafety" value="'.esc_attr( get_option('ncaaf-points-defSafety') ).'" /><br>' .
'<label>Interception: </label><input class="textbox" type="text" name="ncaaf-points-defInterception" value="'.esc_attr( get_option('ncaaf-points-defInterception') ).'" /><br>' .
'<label>Interception Return TD: </label><input class="textbox" type="text" name="ncaaf-points-defInterceptionReturnTD" value="'.esc_attr( get_option('ncaaf-points-defInterceptionReturnTD') ).'" /><br>' .
'<label>Fumble Recovery TD: </label><input class="textbox" type="text" name="ncaaf-points-defFumbleRecoveryTD" value="'.esc_attr( get_option('ncaaf-points-defFumbleRecoveryTD') ).'" /><br>' .
'<label>Blocked Punt/Kick: </label><input class="textbox" type="text" name="ncaaf-points-defBlockedKick" value="'.esc_attr( get_option('ncaaf-points-defBlockedKick') ).'" /><br>' .
'<label>0 pts Allowed: </label><input class="textbox" type="text" name="ncaaf-points-def0ptsAllowed" value="'.esc_attr( get_option('ncaaf-points-def0ptsAllowed') ).'" /><br>' .
'<label>1-6 pts Allowed: </label><input class="textbox" type="text" name="ncaaf-points-def1-6ptsAllowed" value="'.esc_attr( get_option('ncaaf-points-def1-6ptsAllowed') ).'" /><br>' .
'<label>7-13 pts Allowed: </label><input class="textbox" type="text" name="ncaaf-points-def7-13ptsAllowed" value="'.esc_attr( get_option('ncaaf-points-def7-13ptsAllowed') ).'" /><br>' .
'<label>14-20 pts Allowed: </label><input class="textbox" type="text" name="ncaaf-points-def14-20ptsAllowed" value="'.esc_attr( get_option('ncaaf-points-def14-20ptsAllowed') ).'" /><br>' .
'<label>21-27 pts Allowed: </label><input class="textbox" type="text" name="ncaaf-points-def21-27ptsAllowed" value="'.esc_attr( get_option('ncaaf-points-def21-27ptsAllowed') ).'" /><br>' .
'<label>28-34 pts Allowed: </label><input class="textbox" type="text" name="ncaaf-points-def28-34ptsAllowed" value="'.esc_attr( get_option('ncaaf-points-def28-34ptsAllowed') ).'" /><br>' .
'<label>35+ pts Allowed: </label><input class="textbox" type="text" name="ncaaf-points-def35ptsAllowed" value="'.esc_attr( get_option('ncaaf-points-def35ptsAllowed') ).'" /><br>' .
'<div class="update-points-btn update-ncaaf-points">';
submit_button('Update');
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: Algorithm adjustments will apply to <strong>LIVE</strong> and <strong>UPCOMING</strong> contests only.</div>';
echo '</div></form></div></div></div>';

?>