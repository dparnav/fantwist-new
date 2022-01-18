<?php

// Update options and show confirmation message

$show_updated_points_message = false;

if (isset($_POST['mls-points-goals'])) {
	if ($_POST['mls-points-goals'] || $_POST['mls-points-goals'] == '0') {
		update_option( 'mls-points-goals', (float) $_POST['mls-points-goals'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-assists'])) {
	if ($_POST['mls-points-assists'] || $_POST['mls-points-assists'] == '0') {
		update_option( 'mls-points-assists', (float) $_POST['mls-points-assists'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-shots'])) {
	if ($_POST['mls-points-shots'] || $_POST['mls-points-shots'] == '0') {
		update_option( 'mls-points-shots', (float) $_POST['mls-points-shots'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-shotsongoal'])) {
	if ($_POST['mls-points-shotsongoal'] || $_POST['mls-points-shotsongoal'] == '0') {
		update_option( 'mls-points-shotsongoal', (float) $_POST['mls-points-shotsongoal'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-crosses'])) {
	if ($_POST['mls-points-crosses'] || $_POST['mls-points-crosses'] == '0') {
		update_option( 'mls-points-crosses', (float) $_POST['mls-points-crosses'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-foulsdrawn'])) {
	if ($_POST['mls-points-foulsdrawn'] || $_POST['mls-points-foulsdrawn'] == '0') {
		update_option( 'mls-points-foulsdrawn', (float) $_POST['mls-points-foulsdrawn'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-foulsconceded'])) {
	if ($_POST['mls-points-foulsconceded'] || $_POST['mls-points-foulsconceded'] == '0') {
		update_option( 'mls-points-foulsconceded', (float) $_POST['mls-points-foulsconceded'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-tackleswon'])) {
	if ($_POST['mls-points-tackleswon'] || $_POST['mls-points-tackleswon'] == '0') {
		update_option( 'mls-points-tackleswon', (float) $_POST['mls-points-tackleswon'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-passintercepted'])) {
	if ($_POST['mls-points-passintercepted'] || $_POST['mls-points-passintercepted'] == '0') {
		update_option( 'mls-points-passintercepted', (float) $_POST['mls-points-passintercepted'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-yellowcard'])) {
	if ($_POST['mls-points-yellowcard'] || $_POST['mls-points-yellowcard'] == '0') {
		update_option( 'mls-points-yellowcard', (float) $_POST['mls-points-yellowcard'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-redcard'])) {
	if ($_POST['mls-points-redcard'] || $_POST['mls-points-redcard'] == '0') {
		update_option( 'mls-points-redcard', (float) $_POST['mls-points-redcard'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-penaltykickmiss'])) {
	if ($_POST['mls-points-penaltykickmiss'] || $_POST['mls-points-penaltykickmiss'] == '0') {
		update_option( 'mls-points-penaltykickmiss', (float) $_POST['mls-points-penaltykickmiss'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-cleansheet'])) {
	if ($_POST['mls-points-cleansheet'] || $_POST['mls-points-cleansheet'] == '0') {
		update_option( 'mls-points-cleansheet', (float) $_POST['mls-points-cleansheet'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-goaliesave'])) {
	if ($_POST['mls-points-goaliesave'] || $_POST['mls-points-goaliesave'] == '0') {
		update_option( 'mls-points-goaliesave', (float) $_POST['mls-points-goaliesave'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-goalconceded'])) {
	if ($_POST['mls-points-goalconceded'] || $_POST['mls-points-goalconceded'] == '0') {
		update_option( 'mls-points-goalconceded', (float) $_POST['mls-points-goalconceded'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-goaliecleansheet'])) {
	if ($_POST['mls-points-goaliecleansheet'] || $_POST['mls-points-goaliecleansheet'] == '0') {
		update_option( 'mls-points-goaliecleansheet', (float) $_POST['mls-points-goaliecleansheet'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-goaliewin'])) {
	if ($_POST['mls-points-goaliewin'] || $_POST['mls-points-goaliewin'] == '0') {
		update_option( 'mls-points-goaliewin', (float) $_POST['mls-points-goaliewin'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['mls-points-penaltykicksave'])) {
	if ($_POST['mls-points-penaltykicksave'] || $_POST['mls-points-penaltykicksave'] == '0') {
		update_option( 'mls-points-penaltykicksave', (float) $_POST['mls-points-penaltykicksave'] );
		$show_updated_points_message = true;
	}
}

if ($show_updated_points_message == true) {
	echo '<div id="message" class="updated fade"><p>MLS points algorithm updated.</p></div>';
}

if (isset($_POST['create_mls_projections_and_contests_button_clicked'])) {
	
	$projection_key = 'd94de8a990564486b5efa940ef06cde3';
	
	$date = $_POST['custom_date_entry'];
	
	if ($date == '') {
		$date = strtoupper(date('Y-M-d'));
	}
	
	create_mls_projections_and_contests($date, $projection_key);

}
if (isset($_POST['update_live_mls_contests_button_clicked'])){
	
	$stats_key = '4ee248e814c54f54a6a5c5d4f6f56772';
	
	update_live_mls_contests($stats_key, false);
	
	update_option( 'mls-last-updated-live', date('m-d-Y g:i a', time() - (60*60*7))); 

}
if (isset($_POST['process_finished_mls_contests_button'])) {
	
	$stats_key = '4ee248e814c54f54a6a5c5d4f6f56772';
	
	process_finished_mls_contests($stats_key);
	
}

$last_updated = 'Never';
$args = array(
	'post_type' => 'cron_log',
	'post_status' => 'draft',
	'posts_per_page' => 1,
	'tax_query' => array(
		array(
			'taxonomy' => 'cron_type',
			'field'    => 'slug',
			'terms'    => 'mls',
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



// MLS Dashboard table


echo '<div class="table league-table league-table-mls" style="display:none;">';
echo '<span style="color:red;font-weight:700">in development, do not touch</span>';
echo '<div class="table-row strong align-center">' . 
'<div class="table-cell middle table-league-name align-center"><strong>MLS</strong></div>' . 
'<div class="table-cell middle table-tasks"><strong>Tasks</strong></div>' .
'<div class="table-cell middle table-points"><strong>Points Algorithm</strong></div>' .
'</div>' .
'<div class="table-row">' . '<div class="table-cell">&nbsp;</div>' .
'<div class="table-cell league-tasks"><ul style="list-style: disc; padding-left: 20px;"><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:0;">';	
echo '<span style="float: left;display: inline-block;line-height: 28px;padding-right: 8px;font-weight: bold;font-size: 12px;">Enter Date: </span><input type="text" name="custom_date_entry" placeholder="'.$btnDate.'" style="margin:0 0 0.5em;" /><input type="hidden" value="" name="create_mls_projections_and_contests_button_clicked" />';
submit_button('Create MLS Projections and Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: This can only be performed one time per contest date -- mixed teams and projections cannot be reshuffled once they are created. If contests have already been created for the date entered, teams and projections will be unchanged.</div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0">';
echo '<input type="hidden" value="" name="update_live_mls_contests_button_clicked" />';
submit_button('Update Live MLS Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:green;font-weight:bold;">Auto (every 30 min)</span></div>';
echo '<div style="padding: 0;font-size: 10px;">Last updated: <span style="">'.$last_updated.'</span></div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0;">';
echo '<input type="hidden" value="" name="process_finished_mls_contests_button" />';
submit_button('Process Finished MLS Contests and Wagers');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '</form></li></ul></div><div class="table-cell league-points align-center"><form action="options-general.php?page=pariwager-processing" method="post">';
settings_fields( 'pariwager-mls-points' );
do_settings_sections( 'pariwager-mls-points' );
echo '<h2 style="font-size: 14px;margin:0 1em 1em">Non-Goalies</h2><label>Goal: </label><input class="textbox" type="text" name="mls-points-goals" value="'.esc_attr( get_option('mls-points-goals') ).'" /><br>' .
'<label>Assist: </label><input class="textbox" type="text" name="mls-points-assists" value="'.esc_attr( get_option('mls-points-assists') ).'" /><br>' .
'<label>Shot: </label><input class="textbox" type="text" name="mls-points-shots" value="'.esc_attr( get_option('mls-points-shots') ).'" /><br>' .
'<label>Shot on Goal: </label><input class="textbox" type="text" name="mls-points-shotsongoal" value="'.esc_attr( get_option('mls-points-shotsongoal') ).'" /><br>' .
'<label>Cross: </label><input class="textbox" type="text" name="mls-points-crosses" value="'.esc_attr( get_option('mls-points-crosses') ).'" /><br>' .
'<label>Foul Drawn: </label><input class="textbox" type="text" name="mls-points-foulsdrawn" value="'.esc_attr( get_option('mls-points-foulsdrawn') ).'" /><br>' .
'<label>Foul Conceded: </label><input class="textbox" type="text" name="mls-points-foulsconceded" value="'.esc_attr( get_option('mls-points-foulsconceded') ).'" /><br>' .
'<label>Tackle Won: </label><input class="textbox" type="text" name="mls-points-tackleswon" value="'.esc_attr( get_option('mls-points-tackleswon') ).'" /><br>' .
'<label>Pass Intercepted: </label><input class="textbox" type="text" name="mls-points-passintercepted" value="'.esc_attr( get_option('mls-points-passintercepted') ).'" /><br>' .
'<label>Yellow Card: </label><input class="textbox" type="text" name="mls-points-yellowcard" value="'.esc_attr( get_option('mls-points-yellowcard') ).'" /><br>' .
'<label>Red Card: </label><input class="textbox" type="text" name="mls-points-redcard" value="'.esc_attr( get_option('mls-points-redcard') ).'" /><br>' .
'<label>Penalty Kick Miss: </label><input class="textbox" type="text" name="mls-points-penaltykickmiss" value="'.esc_attr( get_option('mls-points-penaltykickmiss') ).'" /><br>' .
'<label>Clean Sheet: </label><input class="textbox" type="text" name="mls-points-cleansheet" value="'.esc_attr( get_option('mls-points-cleansheet') ).'" /><br>' .
'<hr><h2 style="margin:1em;font-size: 14px;">Goalies</h2>' .
'<label>Save (G): </label><input class="textbox" type="text" name="mls-points-goaliesave" value="'.esc_attr( get_option('mls-points-goaliesave') ).'" /><br>' .
'<label>Goal Conceded (G): </label><input class="textbox" type="text" name="mls-points-goalconceded" value="'.esc_attr( get_option('mls-points-goalconceded') ).'" /><br>' .
'<label>Clean Sheet (G): </label><input class="textbox" type="text" name="mls-points-goaliecleansheet" value="'.esc_attr( get_option('mls-points-goaliecleansheet') ).'" /><br>' .
'<label>Win (G): </label><input class="textbox" type="text" name="mls-points-goaliewin" value="'.esc_attr( get_option('mls-points-goaliewin') ).'" /><br>' .
'<label>PK Save (G): </label><input class="textbox" type="text" name="mls-points-penaltykicksave" value="'.esc_attr( get_option('mls-points-penaltykicksave') ).'" /><br>' .
'<div class="update-points-btn update-mls-points">';
submit_button('Update');
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: Algorithm adjustments will apply to <strong>LIVE</strong> and <strong>UPCOMING</strong> contests only.</div>';
echo '</div></form></div></div></div>';

?>