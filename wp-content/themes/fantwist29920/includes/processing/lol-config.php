<?php

// Update options and show confirmation message

$show_updated_points_message = false;

/*
if (isset($_POST['pga-points-eagles'])) {
	if ($_POST['pga-points-eagles'] || $_POST['pga-points-eagles'] == '0') {
		update_option( 'pga-points-eagles', (float) $_POST['pga-points-eagles'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-birdies'])) {
	if ($_POST['pga-points-birdies'] || $_POST['pga-points-birdies'] == '0') {
		update_option( 'pga-points-birdies', (float) $_POST['pga-points-birdies'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-pars'])) {
	if ($_POST['pga-points-pars'] || $_POST['pga-points-pars'] == '0') {
		update_option( 'pga-points-pars', (float) $_POST['pga-points-pars'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-bogeys'])) {
	if ($_POST['pga-points-bogeys'] || $_POST['pga-points-bogeys'] == '0') {
		update_option( 'pga-points-bogeys', (float) $_POST['pga-points-bogeys'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-doublebogeyorworse'])) {
	if ($_POST['pga-points-doublebogeyorworse'] || $_POST['pga-points-doublebogeyorworse'] == '0') {
		update_option( 'pga-points-doublebogeyorworse', (float) $_POST['pga-points-doublebogeyorworse'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-holesinone'])) {
	if ($_POST['pga-points-holesinone'] || $_POST['pga-points-holesinone'] == '0') {
		update_option( 'pga-points-holesinone', (float) $_POST['pga-points-holesinone'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-1'])) {
	if ($_POST['pga-points-tourneyfinish-1'] || $_POST['pga-points-tourneyfinish-1'] == '0') {
		update_option( 'pga-points-tourneyfinish-1', (float) $_POST['pga-points-tourneyfinish-1'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-2'])) {
	if ($_POST['pga-points-tourneyfinish-2'] || $_POST['pga-points-tourneyfinish-2'] == '0') {
		update_option( 'pga-points-tourneyfinish-2', (float) $_POST['pga-points-tourneyfinish-2'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-3'])) {
	if ($_POST['pga-points-tourneyfinish-3'] || $_POST['pga-points-tourneyfinish-3'] == '0') {
		update_option( 'pga-points-tourneyfinish-3', (float) $_POST['pga-points-tourneyfinish-3'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-4'])) {
	if ($_POST['pga-points-tourneyfinish-4'] || $_POST['pga-points-tourneyfinish-4'] == '0') {
		update_option( 'pga-points-tourneyfinish-4', (float) $_POST['pga-points-tourneyfinish-4'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-5'])) {
	if ($_POST['pga-points-tourneyfinish-5'] || $_POST['pga-points-tourneyfinish-5'] == '0') {
		update_option( 'pga-points-tourneyfinish-5', (float) $_POST['pga-points-tourneyfinish-5'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-6'])) {
	if ($_POST['pga-points-tourneyfinish-6'] || $_POST['pga-points-tourneyfinish-6'] == '0') {
		update_option( 'pga-points-tourneyfinish-6', (float) $_POST['pga-points-tourneyfinish-6'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-7'])) {
	if ($_POST['pga-points-tourneyfinish-7'] || $_POST['pga-points-tourneyfinish-7'] == '0') {
		update_option( 'pga-points-tourneyfinish-7', (float) $_POST['pga-points-tourneyfinish-7'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-8'])) {
	if ($_POST['pga-points-tourneyfinish-8'] || $_POST['pga-points-tourneyfinish-8'] == '0') {
		update_option( 'pga-points-tourneyfinish-8', (float) $_POST['pga-points-tourneyfinish-8'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-9'])) {
	if ($_POST['pga-points-tourneyfinish-9'] || $_POST['pga-points-tourneyfinish-9'] == '0') {
		update_option( 'pga-points-tourneyfinish-9', (float) $_POST['pga-points-tourneyfinish-9'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-10'])) {
	if ($_POST['pga-points-tourneyfinish-10'] || $_POST['pga-points-tourneyfinish-10'] == '0') {
		update_option( 'pga-points-tourneyfinish-10', (float) $_POST['pga-points-tourneyfinish-10'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-11-15'])) {
	if ($_POST['pga-points-tourneyfinish-11-15'] || $_POST['pga-points-tourneyfinish-11-15'] == '0') {
		update_option( 'pga-points-tourneyfinish-11-15', (float) $_POST['pga-points-tourneyfinish-11-15'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-16-20'])) {
	if ($_POST['pga-points-tourneyfinish-16-20'] || $_POST['pga-points-tourneyfinish-16-20'] == '0') {
		update_option( 'pga-points-tourneyfinish-16-20', (float) $_POST['pga-points-tourneyfinish-16-20'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-21-25'])) {
	if ($_POST['pga-points-tourneyfinish-21-25'] || $_POST['pga-points-tourneyfinish-21-25'] == '0') {
		update_option( 'pga-points-tourneyfinish-21-25', (float) $_POST['pga-points-tourneyfinish-21-25'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-26-30'])) {
	if ($_POST['pga-points-tourneyfinish-26-30'] || $_POST['pga-points-tourneyfinish-26-30'] == '0') {
		update_option( 'pga-points-tourneyfinish-26-30', (float) $_POST['pga-points-tourneyfinish-26-30'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-31-40'])) {
	if ($_POST['pga-points-tourneyfinish-31-40'] || $_POST['pga-points-tourneyfinish-31-40'] == '0') {
		update_option( 'pga-points-tourneyfinish-31-40', (float) $_POST['pga-points-tourneyfinish-31-40'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['pga-points-tourneyfinish-41-50'])) {
	if ($_POST['pga-points-tourneyfinish-41-50'] || $_POST['pga-points-tourneyfinish-41-50'] == '0') {
		update_option( 'pga-points-tourneyfinish-41-50', (float) $_POST['pga-points-tourneyfinish-41-50'] );
		$show_updated_points_message = true;
	}
}
*/

if ($show_updated_points_message == true) {
	echo '<div id="message" class="updated fade"><p>League of Legends points algorithm updated.</p></div>';
}

if (isset($_POST['create_lol_projections_and_contests_button_clicked'])) {
	
	$projection_key = '44691bd017be4bdabcd8af9da127ae38';
	$date = $_POST['custom_date_entry'];
	
	if ($date == '') {
		$date = 'this thursday';
	}
	
	create_lol_projections_and_contests($date, $projection_key);

}
if (isset($_POST['update_live_lol_contests_button_clicked'])){
	
	$stats_key = '44691bd017be4bdabcd8af9da127ae38';
	
	update_live_lol_contests($stats_key, false);
	
	update_option( 'lol-last-updated-live', date('m-d-Y g:i a', time() - (60*60*7))); 

}
if (isset($_POST['process_finished_lol_contests_button'])) {
	
	$stats_key = '44691bd017be4bdabcd8af9da127ae38';
	
	process_finished_lol_contests($stats_key);
	
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
			'terms'    => 'league-of-legends',
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



// LOL Dashboard table
echo '<div class="table league-table league-table-lol" style="display:none;">' .
'<div class="table-row strong align-center">' . 
'<div class="table-cell middle table-league-name align-center"><strong>League of Legends</strong></div>' . 
'<div class="table-cell middle table-tasks"><strong>Tasks</strong></div>' .
'<div class="table-cell middle table-points"><strong>Points Algorithm</strong></div>' .
'</div>' .
'<div class="table-row">' . '<div class="table-cell">&nbsp;</div>' .
'<div class="table-cell league-tasks"><ul style="list-style: disc; padding-left: 20px;"><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:0;">';	
echo '<span style="float: left;display: inline-block;line-height: 28px;padding-right: 8px;font-weight: bold;font-size: 12px;">Enter Tourney Start Day: </span><input type="text" name="custom_date_entry" placeholder="this thursday" style="margin:0 0 0.5em;" /><input type="hidden" value="" name="create_lol_projections_and_contests_button_clicked" />';
submit_button('Create LOL Projections and Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: This can only be performed one time per contest date -- mixed teams and projections cannot be reshuffled once they are created. If contests have already been created for the date entered, teams and projections will be unchanged.</div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0">';
echo '<input type="hidden" value="" name="update_live_lol_contests_button_clicked" />';
submit_button('Update Live LOL Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '<div style="padding: 0;font-size: 10px;">Last auto-run: <span style="">'.$last_updated.'</span></div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0;">';
echo '<input type="hidden" value="" name="process_finished_lol_contests_button" />';
submit_button('Process Finished LOL Contests and Wagers');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '</form></li></ul></div><div class="table-cell league-points align-center"><form action="options-general.php?page=pariwager-processing" method="post">';
settings_fields( 'pariwager-lol-points' );
do_settings_sections( 'pariwager-lol-points' );

/*
echo '<label>Eagle: </label><input class="textbox" type="text" name="pga-points-eagles" value="'.esc_attr( get_option('pga-points-eagles') ).'" /><br>' .
'<label>Birdie: </label><input class="textbox" type="text" name="pga-points-birdies" value="'.esc_attr( get_option('pga-points-birdies') ).'" /><br>' .
'<label>Par: </label><input class="textbox" type="text" name="pga-points-pars" value="'.esc_attr( get_option('pga-points-pars') ).'" /><br>' .
'<label>Bogey: </label><input class="textbox" type="text" name="pga-points-bogeys" value="'.esc_attr( get_option('pga-points-bogeys') ).'" /><br>' .
'<label>Double Bogey or Worse: </label><input class="textbox" type="text" name="pga-points-doublebogeyorworse" value="'.esc_attr( get_option('pga-points-doublebogeyorworse') ).'" /><br>' .
'<label>Hole in One: </label><input class="textbox" type="text" name="pga-points-holesinone" value="'.esc_attr( get_option('pga-points-holesinone') ).'" /><br><br>';
echo '<h2 style="font-size: 14px;margin:0 1em 1em">Tourney Finish</h2>' .
'<label>1st: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-1" value="'.esc_attr( get_option('pga-points-tourneyfinish-1') ).'" /><br>' .
'<label>2nd: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-2" value="'.esc_attr( get_option('pga-points-tourneyfinish-2') ).'" /><br>' .
'<label>3rd: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-3" value="'.esc_attr( get_option('pga-points-tourneyfinish-3') ).'" /><br>' .
'<label>4th: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-4" value="'.esc_attr( get_option('pga-points-tourneyfinish-4') ).'" /><br>' .
'<label>5th: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-5" value="'.esc_attr( get_option('pga-points-tourneyfinish-5') ).'" /><br>' .
'<label>6th: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-6" value="'.esc_attr( get_option('pga-points-tourneyfinish-6') ).'" /><br>' .
'<label>7th: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-7" value="'.esc_attr( get_option('pga-points-tourneyfinish-7') ).'" /><br>' .
'<label>8th: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-8" value="'.esc_attr( get_option('pga-points-tourneyfinish-8') ).'" /><br>' .
'<label>9th: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-9" value="'.esc_attr( get_option('pga-points-tourneyfinish-9') ).'" /><br>' .
'<label>10th: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-10" value="'.esc_attr( get_option('pga-points-tourneyfinish-10') ).'" /><br>' .
'<label>11th-15th: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-11-15" value="'.esc_attr( get_option('pga-points-tourneyfinish-11-15') ).'" /><br>' .
'<label>16th-20th: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-16-20" value="'.esc_attr( get_option('pga-points-tourneyfinish-16-20') ).'" /><br>' .
'<label>21st-25th: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-21-25" value="'.esc_attr( get_option('pga-points-tourneyfinish-21-25') ).'" /><br>' .
'<label>26th-30th: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-26-30" value="'.esc_attr( get_option('pga-points-tourneyfinish-26-30') ).'" /><br>' .
'<label>31st-40th: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-31-40" value="'.esc_attr( get_option('pga-points-tourneyfinish-31-40') ).'" /><br>' .
'<label>41st-50th: </label><input class="textbox" type="text" name="pga-points-tourneyfinish-41-50" value="'.esc_attr( get_option('pga-points-tourneyfinish-41-50') ).'" /><br>' .
*/

echo '<div class="update-points-btn update-lol-points">';
submit_button('Update');
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: Algorithm adjustments will apply to <strong>LIVE</strong> and <strong>UPCOMING</strong> contests only.</div>';
echo '</div></form></div></div>';

?>