<?php

// Update options and show confirmation message

$show_updated_points_message = false;

if (isset($_POST['nascar-points-position-differential'])) {
	if ($_POST['nascar-points-position-differential'] || $_POST['nascar-points-position-differential'] == '0') {
		update_option( 'nascar-points-position-differential', (float) $_POST['nascar-points-position-differential'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-fastest-laps'])) {
	if ($_POST['nascar-points-fastest-laps'] || $_POST['nascar-points-fastest-laps'] == '0') {
		update_option( 'nascar-points-fastest-laps', (float) $_POST['nascar-points-fastest-laps'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-laps-led'])) {
	if ($_POST['nascar-points-laps-led'] || $_POST['nascar-points-laps-led'] == '0') {
		update_option( 'nascar-points-laps-led', (float) $_POST['nascar-points-laps-led'] );
		$show_updated_points_message = true;
	}
}

if (isset($_POST['nascar-points-racefinish-1'])) {
	if ($_POST['nascar-points-racefinish-1'] || $_POST['nascar-points-racefinish-1'] == '0') {
		update_option( 'nascar-points-racefinish-1', (float) $_POST['nascar-points-racefinish-1'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-2'])) {
	if ($_POST['nascar-points-racefinish-2'] || $_POST['nascar-points-racefinish-2'] == '0') {
		update_option( 'nascar-points-racefinish-2', (float) $_POST['nascar-points-racefinish-2'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-3'])) {
	if ($_POST['nascar-points-racefinish-3'] || $_POST['nascar-points-racefinish-3'] == '0') {
		update_option( 'nascar-points-racefinish-3', (float) $_POST['nascar-points-racefinish-3'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-4'])) {
	if ($_POST['nascar-points-racefinish-4'] || $_POST['nascar-points-racefinish-4'] == '0') {
		update_option( 'nascar-points-racefinish-4', (float) $_POST['nascar-points-racefinish-4'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-5'])) {
	if ($_POST['nascar-points-racefinish-5'] || $_POST['nascar-points-racefinish-5'] == '0') {
		update_option( 'nascar-points-racefinish-5', (float) $_POST['nascar-points-racefinish-5'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-6'])) {
	if ($_POST['nascar-points-racefinish-6'] || $_POST['nascar-points-racefinish-6'] == '0') {
		update_option( 'nascar-points-racefinish-6', (float) $_POST['nascar-points-racefinish-6'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-7'])) {
	if ($_POST['nascar-points-racefinish-7'] || $_POST['nascar-points-racefinish-7'] == '0') {
		update_option( 'nascar-points-racefinish-7', (float) $_POST['nascar-points-racefinish-7'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-8'])) {
	if ($_POST['nascar-points-racefinish-8'] || $_POST['nascar-points-racefinish-8'] == '0') {
		update_option( 'nascar-points-racefinish-8', (float) $_POST['nascar-points-racefinish-8'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-9'])) {
	if ($_POST['nascar-points-racefinish-9'] || $_POST['nascar-points-racefinish-9'] == '0') {
		update_option( 'nascar-points-racefinish-9', (float) $_POST['nascar-points-racefinish-9'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-10'])) {
	if ($_POST['nascar-points-racefinish-10'] || $_POST['nascar-points-racefinish-10'] == '0') {
		update_option( 'nascar-points-racefinish-10', (float) $_POST['nascar-points-racefinish-10'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-11'])) {
	if ($_POST['nascar-points-racefinish-11'] || $_POST['nascar-points-racefinish-11'] == '0') {
		update_option( 'nascar-points-racefinish-11', (float) $_POST['nascar-points-racefinish-11'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-12'])) {
	if ($_POST['nascar-points-racefinish-12'] || $_POST['nascar-points-racefinish-12'] == '0') {
		update_option( 'nascar-points-racefinish-12', (float) $_POST['nascar-points-racefinish-12'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-13'])) {
	if ($_POST['nascar-points-racefinish-13'] || $_POST['nascar-points-racefinish-13'] == '0') {
		update_option( 'nascar-points-racefinish-13', (float) $_POST['nascar-points-racefinish-13'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-14'])) {
	if ($_POST['nascar-points-racefinish-14'] || $_POST['nascar-points-racefinish-14'] == '0') {
		update_option( 'nascar-points-racefinish-14', (float) $_POST['nascar-points-racefinish-14'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-15'])) {
	if ($_POST['nascar-points-racefinish-15'] || $_POST['nascar-points-racefinish-15'] == '0') {
		update_option( 'nascar-points-racefinish-15', (float) $_POST['nascar-points-racefinish-15'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-16'])) {
	if ($_POST['nascar-points-racefinish-16'] || $_POST['nascar-points-racefinish-16'] == '0') {
		update_option( 'nascar-points-racefinish-16', (float) $_POST['nascar-points-racefinish-16'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-17'])) {
	if ($_POST['nascar-points-racefinish-17'] || $_POST['nascar-points-racefinish-17'] == '0') {
		update_option( 'nascar-points-racefinish-17', (float) $_POST['nascar-points-racefinish-17'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-18'])) {
	if ($_POST['nascar-points-racefinish-18'] || $_POST['nascar-points-racefinish-18'] == '0') {
		update_option( 'nascar-points-racefinish-18', (float) $_POST['nascar-points-racefinish-18'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-19'])) {
	if ($_POST['nascar-points-racefinish-19'] || $_POST['nascar-points-racefinish-19'] == '0') {
		update_option( 'nascar-points-racefinish-19', (float) $_POST['nascar-points-racefinish-19'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-20'])) {
	if ($_POST['nascar-points-racefinish-20'] || $_POST['nascar-points-racefinish-20'] == '0') {
		update_option( 'nascar-points-racefinish-20', (float) $_POST['nascar-points-racefinish-20'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-21'])) {
	if ($_POST['nascar-points-racefinish-21'] || $_POST['nascar-points-racefinish-21'] == '0') {
		update_option( 'nascar-points-racefinish-21', (float) $_POST['nascar-points-racefinish-21'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-22'])) {
	if ($_POST['nascar-points-racefinish-22'] || $_POST['nascar-points-racefinish-22'] == '0') {
		update_option( 'nascar-points-racefinish-22', (float) $_POST['nascar-points-racefinish-22'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-23'])) {
	if ($_POST['nascar-points-racefinish-23'] || $_POST['nascar-points-racefinish-23'] == '0') {
		update_option( 'nascar-points-racefinish-23', (float) $_POST['nascar-points-racefinish-23'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-24'])) {
	if ($_POST['nascar-points-racefinish-24'] || $_POST['nascar-points-racefinish-24'] == '0') {
		update_option( 'nascar-points-racefinish-24', (float) $_POST['nascar-points-racefinish-24'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-25'])) {
	if ($_POST['nascar-points-racefinish-25'] || $_POST['nascar-points-racefinish-25'] == '0') {
		update_option( 'nascar-points-racefinish-25', (float) $_POST['nascar-points-racefinish-25'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-26'])) {
	if ($_POST['nascar-points-racefinish-26'] || $_POST['nascar-points-racefinish-26'] == '0') {
		update_option( 'nascar-points-racefinish-26', (float) $_POST['nascar-points-racefinish-26'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-27'])) {
	if ($_POST['nascar-points-racefinish-27'] || $_POST['nascar-points-racefinish-27'] == '0') {
		update_option( 'nascar-points-racefinish-27', (float) $_POST['nascar-points-racefinish-27'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-28'])) {
	if ($_POST['nascar-points-racefinish-28'] || $_POST['nascar-points-racefinish-28'] == '0') {
		update_option( 'nascar-points-racefinish-28', (float) $_POST['nascar-points-racefinish-28'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-29'])) {
	if ($_POST['nascar-points-racefinish-29'] || $_POST['nascar-points-racefinish-29'] == '0') {
		update_option( 'nascar-points-racefinish-29', (float) $_POST['nascar-points-racefinish-29'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-30'])) {
	if ($_POST['nascar-points-racefinish-30'] || $_POST['nascar-points-racefinish-30'] == '0') {
		update_option( 'nascar-points-racefinish-30', (float) $_POST['nascar-points-racefinish-30'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-31'])) {
	if ($_POST['nascar-points-racefinish-31'] || $_POST['nascar-points-racefinish-31'] == '0') {
		update_option( 'nascar-points-racefinish-31', (float) $_POST['nascar-points-racefinish-31'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-32'])) {
	if ($_POST['nascar-points-racefinish-32'] || $_POST['nascar-points-racefinish-32'] == '0') {
		update_option( 'nascar-points-racefinish-32', (float) $_POST['nascar-points-racefinish-32'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-33'])) {
	if ($_POST['nascar-points-racefinish-33'] || $_POST['nascar-points-racefinish-33'] == '0') {
		update_option( 'nascar-points-racefinish-33', (float) $_POST['nascar-points-racefinish-33'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-34'])) {
	if ($_POST['nascar-points-racefinish-34'] || $_POST['nascar-points-racefinish-34'] == '0') {
		update_option( 'nascar-points-racefinish-34', (float) $_POST['nascar-points-racefinish-34'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-35'])) {
	if ($_POST['nascar-points-racefinish-35'] || $_POST['nascar-points-racefinish-35'] == '0') {
		update_option( 'nascar-points-racefinish-35', (float) $_POST['nascar-points-racefinish-35'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-36'])) {
	if ($_POST['nascar-points-racefinish-36'] || $_POST['nascar-points-racefinish-36'] == '0') {
		update_option( 'nascar-points-racefinish-36', (float) $_POST['nascar-points-racefinish-36'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-37'])) {
	if ($_POST['nascar-points-racefinish-37'] || $_POST['nascar-points-racefinish-37'] == '0') {
		update_option( 'nascar-points-racefinish-37', (float) $_POST['nascar-points-racefinish-37'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-38'])) {
	if ($_POST['nascar-points-racefinish-38'] || $_POST['nascar-points-racefinish-38'] == '0') {
		update_option( 'nascar-points-racefinish-38', (float) $_POST['nascar-points-racefinish-38'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-39'])) {
	if ($_POST['nascar-points-racefinish-39'] || $_POST['nascar-points-racefinish-39'] == '0') {
		update_option( 'nascar-points-racefinish-39', (float) $_POST['nascar-points-racefinish-39'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-40'])) {
	if ($_POST['nascar-points-racefinish-40'] || $_POST['nascar-points-racefinish-40'] == '0') {
		update_option( 'nascar-points-racefinish-40', (float) $_POST['nascar-points-racefinish-40'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-41'])) {
	if ($_POST['nascar-points-racefinish-41'] || $_POST['nascar-points-racefinish-41'] == '0') {
		update_option( 'nascar-points-racefinish-41', (float) $_POST['nascar-points-racefinish-41'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-42'])) {
	if ($_POST['nascar-points-racefinish-42'] || $_POST['nascar-points-racefinish-42'] == '0') {
		update_option( 'nascar-points-racefinish-42', (float) $_POST['nascar-points-racefinish-42'] );
		$show_updated_points_message = true;
	}
}
if (isset($_POST['nascar-points-racefinish-43'])) {
	if ($_POST['nascar-points-racefinish-43'] || $_POST['nascar-points-racefinish-43'] == '0') {
		update_option( 'nascar-points-racefinish-43', (float) $_POST['nascar-points-racefinish-43'] );
		$show_updated_points_message = true;
	}
}



if ($show_updated_points_message == true) {
	echo '<div id="message" class="updated fade"><p>NASCAR points algorithm updated.</p></div>';
}

if (isset($_POST['create_nascar_projections_and_contests_button_clicked'])) {
	
	$projection_key = '02af536ebdad4d51beb46013379f61e0';
	$date = $_POST['custom_date_entry'];
	
	if ($date == '') {
		$date = 'this saturday';
	}
	
	create_nascar_projections_and_contests($date, $projection_key);

}
if (isset($_POST['update_live_nascar_contests_button_clicked'])){
	
	$stats_key = '02af536ebdad4d51beb46013379f61e0';
	
	update_live_nascar_contests($stats_key, false);
	
	update_option( 'nascar-last-updated-live', date('m-d-Y g:i a', time() - (60*60*7))); 

}
if (isset($_POST['process_finished_nascar_contests_button'])) {
	
	$stats_key = '02af536ebdad4d51beb46013379f61e0';
	
	process_finished_nascar_contests($stats_key);
	
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
			'terms'    => 'nascar',
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



// NASCAR Dashboard table
echo '<div class="table league-table league-table-nascar">' .
'<div class="table-row strong align-center">' . 
'<div class="table-cell middle table-league-name align-center"><strong>NASCAR</strong></div>' . 
'<div class="table-cell middle table-tasks"><strong>Tasks</strong></div>' .
'<div class="table-cell middle table-points"><strong>Points Algorithm</strong></div>' .
'</div>' .
'<div class="table-row">' . '<div class="table-cell">&nbsp;</div>' .
'<div class="table-cell league-tasks"><ul style="list-style: disc; padding-left: 20px;"><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:0;">';	
echo '<span style="float: left;display: inline-block;line-height: 28px;padding-right: 8px;font-weight: bold;font-size: 12px;">Enter Race Day: </span><input type="text" name="custom_date_entry" placeholder="this saturday" style="margin:0 0 0.5em;" /><input type="hidden" value="" name="create_nascar_projections_and_contests_button_clicked" />';
submit_button('Create NASCAR Projections and Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: This can only be performed one time per contest date -- mixed teams and projections cannot be reshuffled once they are created. If contests have already been created for the date entered, teams and projections will be unchanged.</div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0">';
echo '<input type="hidden" value="" name="update_live_nascar_contests_button_clicked" />';
submit_button('Update Live NASCAR Contests');
echo '<div style="padding:5px 0;">Status: <span style="color:green;font-weight:bold;">Auto (every 30 min)</span></div>';
echo '<div style="padding: 0;font-size: 10px;">Last updated: <span style="">'.$last_updated.'</span></div>';
echo '</form><hr></li><li><form action="options-general.php?page=pariwager-processing" method="post" style="margin:1.5em 0;">';
echo '<input type="hidden" value="" name="process_finished_nascar_contests_button" />';
submit_button('Process Finished NASCAR Contests and Wagers');
echo '<div style="padding:5px 0;">Status: <span style="color:red;font-weight:bold;">Manual</span></div>';
echo '</form></li></ul></div><div class="table-cell league-points align-center"><form action="options-general.php?page=pariwager-processing" method="post">';
settings_fields( 'pariwager-nascar-points' );
do_settings_sections( 'pariwager-nascar-points' );
echo '<label>Position Differential: </label><input class="textbox" type="text" name="nascar-points-position-differential" value="'.esc_attr( get_option('nascar-points-position-differential') ).'" /><br>' .
'<label>Fastest Laps: </label><input class="textbox" type="text" name="nascar-points-fastest-laps" value="'.esc_attr( get_option('nascar-points-fastest-laps') ).'" /><br>' .
'<label>Laps Led: </label><input class="textbox" type="text" name="nascar-points-laps-led" value="'.esc_attr( get_option('nascar-points-laps-led') ).'" /><br><br>';
echo '<h2 style="font-size: 14px;margin:0 1em 1em">Race Finish</h2>' .
'<label>1st: </label><input class="textbox" type="text" name="nascar-points-racefinish-1" value="'.esc_attr( get_option('nascar-points-racefinish-1') ).'" /><br>' .
'<label>2nd: </label><input class="textbox" type="text" name="nascar-points-racefinish-2" value="'.esc_attr( get_option('nascar-points-racefinish-2') ).'" /><br>' .
'<label>3rd: </label><input class="textbox" type="text" name="nascar-points-racefinish-3" value="'.esc_attr( get_option('nascar-points-racefinish-3') ).'" /><br>' .
'<label>4th: </label><input class="textbox" type="text" name="nascar-points-racefinish-4" value="'.esc_attr( get_option('nascar-points-racefinish-4') ).'" /><br>' .
'<label>5th: </label><input class="textbox" type="text" name="nascar-points-racefinish-5" value="'.esc_attr( get_option('nascar-points-racefinish-5') ).'" /><br>' .
'<label>6th: </label><input class="textbox" type="text" name="nascar-points-racefinish-6" value="'.esc_attr( get_option('nascar-points-racefinish-6') ).'" /><br>' .
'<label>7th: </label><input class="textbox" type="text" name="nascar-points-racefinish-7" value="'.esc_attr( get_option('nascar-points-racefinish-7') ).'" /><br>' .
'<label>8th: </label><input class="textbox" type="text" name="nascar-points-racefinish-8" value="'.esc_attr( get_option('nascar-points-racefinish-8') ).'" /><br>' .
'<label>9th: </label><input class="textbox" type="text" name="nascar-points-racefinish-9" value="'.esc_attr( get_option('nascar-points-racefinish-9') ).'" /><br>' .
'<label>10th: </label><input class="textbox" type="text" name="nascar-points-racefinish-10" value="'.esc_attr( get_option('nascar-points-racefinish-10') ).'" /><br>' .
'<label>11th: </label><input class="textbox" type="text" name="nascar-points-racefinish-11" value="'.esc_attr( get_option('nascar-points-racefinish-11') ).'" /><br>' .
'<label>12th: </label><input class="textbox" type="text" name="nascar-points-racefinish-12" value="'.esc_attr( get_option('nascar-points-racefinish-12') ).'" /><br>' .
'<label>13th: </label><input class="textbox" type="text" name="nascar-points-racefinish-13" value="'.esc_attr( get_option('nascar-points-racefinish-13') ).'" /><br>' .
'<label>14th: </label><input class="textbox" type="text" name="nascar-points-racefinish-14" value="'.esc_attr( get_option('nascar-points-racefinish-14') ).'" /><br>' .
'<label>15th: </label><input class="textbox" type="text" name="nascar-points-racefinish-15" value="'.esc_attr( get_option('nascar-points-racefinish-15') ).'" /><br>' .
'<label>16th: </label><input class="textbox" type="text" name="nascar-points-racefinish-16" value="'.esc_attr( get_option('nascar-points-racefinish-16') ).'" /><br>' .
'<label>17th: </label><input class="textbox" type="text" name="nascar-points-racefinish-17" value="'.esc_attr( get_option('nascar-points-racefinish-17') ).'" /><br>' .
'<label>18th: </label><input class="textbox" type="text" name="nascar-points-racefinish-18" value="'.esc_attr( get_option('nascar-points-racefinish-18') ).'" /><br>' .
'<label>19th: </label><input class="textbox" type="text" name="nascar-points-racefinish-19" value="'.esc_attr( get_option('nascar-points-racefinish-19') ).'" /><br>' .
'<label>20th: </label><input class="textbox" type="text" name="nascar-points-racefinish-20" value="'.esc_attr( get_option('nascar-points-racefinish-20') ).'" /><br>' .
'<label>21st: </label><input class="textbox" type="text" name="nascar-points-racefinish-21" value="'.esc_attr( get_option('nascar-points-racefinish-21') ).'" /><br>' .
'<label>22nd: </label><input class="textbox" type="text" name="nascar-points-racefinish-22" value="'.esc_attr( get_option('nascar-points-racefinish-22') ).'" /><br>' .
'<label>23rd: </label><input class="textbox" type="text" name="nascar-points-racefinish-23" value="'.esc_attr( get_option('nascar-points-racefinish-23') ).'" /><br>' .
'<label>24th: </label><input class="textbox" type="text" name="nascar-points-racefinish-24" value="'.esc_attr( get_option('nascar-points-racefinish-24') ).'" /><br>' .
'<label>25th: </label><input class="textbox" type="text" name="nascar-points-racefinish-25" value="'.esc_attr( get_option('nascar-points-racefinish-25') ).'" /><br>' .
'<label>26th: </label><input class="textbox" type="text" name="nascar-points-racefinish-26" value="'.esc_attr( get_option('nascar-points-racefinish-26') ).'" /><br>' .
'<label>27th: </label><input class="textbox" type="text" name="nascar-points-racefinish-27" value="'.esc_attr( get_option('nascar-points-racefinish-27') ).'" /><br>' .
'<label>28th: </label><input class="textbox" type="text" name="nascar-points-racefinish-28" value="'.esc_attr( get_option('nascar-points-racefinish-28') ).'" /><br>' .
'<label>29th: </label><input class="textbox" type="text" name="nascar-points-racefinish-29" value="'.esc_attr( get_option('nascar-points-racefinish-29') ).'" /><br>' .
'<label>30th: </label><input class="textbox" type="text" name="nascar-points-racefinish-30" value="'.esc_attr( get_option('nascar-points-racefinish-30') ).'" /><br>' .
'<label>31st: </label><input class="textbox" type="text" name="nascar-points-racefinish-31" value="'.esc_attr( get_option('nascar-points-racefinish-31') ).'" /><br>' .
'<label>32nd: </label><input class="textbox" type="text" name="nascar-points-racefinish-32" value="'.esc_attr( get_option('nascar-points-racefinish-32') ).'" /><br>' .
'<label>33rd: </label><input class="textbox" type="text" name="nascar-points-racefinish-33" value="'.esc_attr( get_option('nascar-points-racefinish-33') ).'" /><br>' .
'<label>34th: </label><input class="textbox" type="text" name="nascar-points-racefinish-34" value="'.esc_attr( get_option('nascar-points-racefinish-34') ).'" /><br>' .
'<label>35th: </label><input class="textbox" type="text" name="nascar-points-racefinish-35" value="'.esc_attr( get_option('nascar-points-racefinish-35') ).'" /><br>' .
'<label>36th: </label><input class="textbox" type="text" name="nascar-points-racefinish-36" value="'.esc_attr( get_option('nascar-points-racefinish-36') ).'" /><br>' .
'<label>37th: </label><input class="textbox" type="text" name="nascar-points-racefinish-37" value="'.esc_attr( get_option('nascar-points-racefinish-37') ).'" /><br>' .
'<label>38th: </label><input class="textbox" type="text" name="nascar-points-racefinish-38" value="'.esc_attr( get_option('nascar-points-racefinish-38') ).'" /><br>' .
'<label>39th: </label><input class="textbox" type="text" name="nascar-points-racefinish-39" value="'.esc_attr( get_option('nascar-points-racefinish-39') ).'" /><br>' .
'<label>40th: </label><input class="textbox" type="text" name="nascar-points-racefinish-40" value="'.esc_attr( get_option('nascar-points-racefinish-40') ).'" /><br>' .
'<label>41st: </label><input class="textbox" type="text" name="nascar-points-racefinish-41" value="'.esc_attr( get_option('nascar-points-racefinish-41') ).'" /><br>' .
'<label>42nd: </label><input class="textbox" type="text" name="nascar-points-racefinish-42" value="'.esc_attr( get_option('nascar-points-racefinish-42') ).'" /><br>' .
'<label>43rd: </label><input class="textbox" type="text" name="nascar-points-racefinish-43" value="'.esc_attr( get_option('nascar-points-racefinish-43') ).'" /><br>' .
'<div class="update-points-btn update-nascar-points">';
submit_button('Update');
echo '<div style="font-style:italic; font-size:11px; margin:1em 0;">Note: Algorithm adjustments will apply to <strong>LIVE</strong> and <strong>UPCOMING</strong> contests only.</div>';
echo '</div></form></div></div></div>';

?>