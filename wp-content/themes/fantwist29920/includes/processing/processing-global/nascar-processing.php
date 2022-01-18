<?php
	
//Register settings and options

function pariwagerNASCARpoints() {
		
	register_setting( 'pariwager-nascar-points', 'nascar-points-position-differential' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-fastest-laps' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-laps-led' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-1' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-2' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-3' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-4' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-5' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-6' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-7' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-8' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-9' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-10' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-11' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-12' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-13' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-14' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-15' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-16' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-17' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-18' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-19' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-20' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-21' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-22' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-23' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-24' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-25' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-26' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-27' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-28' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-29' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-30' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-31' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-32' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-33' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-34' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-35' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-36' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-37' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-38' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-39' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-40' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-41' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-42' );
	register_setting( 'pariwager-nascar-points', 'nascar-points-racefinish-43' );
	register_setting( 'pariwager-nascar-points', 'nascar-last-updated-live' );
	   
}
add_action( 'admin_init', 'pariwagerNASCARpoints' );





//Update NASCAR Projections and Contests

function create_nascar_projections_and_contests($date, $projection_key) {
	
	//Define vars
	$tax_league = 8;
	$league_title = 'NASCAR';
	$already_exists = false;
		
	$position_differential_VAL = get_option('nascar-points-position-differential');
	$fastest_laps_VAL = get_option('nascar-points-fastest-laps');
	$laps_led_VAL = get_option('nascar-points-laps-led');

	$racefinish_1_VAL = get_option('nascar-points-racefinish-1');
	$racefinish_2_VAL = get_option('nascar-points-racefinish-2');
	$racefinish_3_VAL = get_option('nascar-points-racefinish-3');
	$racefinish_4_VAL = get_option('nascar-points-racefinish-4');
	$racefinish_5_VAL = get_option('nascar-points-racefinish-5');
	$racefinish_6_VAL = get_option('nascar-points-racefinish-6');
	$racefinish_7_VAL = get_option('nascar-points-racefinish-7');
	$racefinish_8_VAL = get_option('nascar-points-racefinish-8');
	$racefinish_9_VAL = get_option('nascar-points-racefinish-9');
	$racefinish_10_VAL = get_option('nascar-points-racefinish-10');
	$racefinish_11_VAL = get_option('nascar-points-racefinish-11');
	$racefinish_12_VAL = get_option('nascar-points-racefinish-12');
	$racefinish_13_VAL = get_option('nascar-points-racefinish-13');
	$racefinish_14_VAL = get_option('nascar-points-racefinish-14');
	$racefinish_15_VAL = get_option('nascar-points-racefinish-15');
	$racefinish_16_VAL = get_option('nascar-points-racefinish-16');
	$racefinish_17_VAL = get_option('nascar-points-racefinish-17');
	$racefinish_18_VAL = get_option('nascar-points-racefinish-18');
	$racefinish_19_VAL = get_option('nascar-points-racefinish-19');
	$racefinish_20_VAL = get_option('nascar-points-racefinish-20');
	$racefinish_21_VAL = get_option('nascar-points-racefinish-21');
	$racefinish_22_VAL = get_option('nascar-points-racefinish-22');
	$racefinish_23_VAL = get_option('nascar-points-racefinish-23');
	$racefinish_24_VAL = get_option('nascar-points-racefinish-24');
	$racefinish_25_VAL = get_option('nascar-points-racefinish-25');
	$racefinish_26_VAL = get_option('nascar-points-racefinish-26');
	$racefinish_27_VAL = get_option('nascar-points-racefinish-27');
	$racefinish_28_VAL = get_option('nascar-points-racefinish-28');
	$racefinish_29_VAL = get_option('nascar-points-racefinish-29');
	$racefinish_30_VAL = get_option('nascar-points-racefinish-30');
	$racefinish_31_VAL = get_option('nascar-points-racefinish-31');
	$racefinish_32_VAL = get_option('nascar-points-racefinish-32');
	$racefinish_33_VAL = get_option('nascar-points-racefinish-33');
	$racefinish_34_VAL = get_option('nascar-points-racefinish-34');
	$racefinish_35_VAL = get_option('nascar-points-racefinish-35');
	$racefinish_36_VAL = get_option('nascar-points-racefinish-36');
	$racefinish_37_VAL = get_option('nascar-points-racefinish-37');
	$racefinish_38_VAL = get_option('nascar-points-racefinish-38');
	$racefinish_39_VAL = get_option('nascar-points-racefinish-39');	
	$racefinish_40_VAL = get_option('nascar-points-racefinish-40');
	$racefinish_41_VAL = get_option('nascar-points-racefinish-41');
	$racefinish_42_VAL = get_option('nascar-points-racefinish-42');
	$racefinish_43_VAL = get_option('nascar-points-racefinish-43');
		
	
	// Get race data for requested date
	
	$races = wp_remote_get( "https://fly.sportsdata.io/nascar/v2/json/races/2019", array(
		'method'	=> 'GET',
		'timeout'	=> 60,
	    'headers'	=> array(
	        'Ocp-Apim-Subscription-Key' => $projection_key,
	    ),
	) );
		
	if ( is_array( $races ) && ! is_wp_error( $races ) ) {
		
		$race_data = json_decode($races['body']);
		
		$requested_date = date('Y-m-d', strtotime($date));
		$contest_data = array();
		$race_count = 0;
				
		foreach ($race_data as $race) {
		
			if ($race->SeriesName == 'Monster Energy NASCAR Cup') {
		
				$race_start = date('Y-m-d', strtotime($race->DateTime));
				
				if ($race_start == $requested_date) {
							
					$race_id = $race->RaceID;
					$race_datetime = $race->DateTime;
					$race_name = $race->Name;
					$race_track = $race->Track;
					$race_over = $race->IsOver;
										
					$projections = wp_remote_get( "https://fly.sportsdata.io/nascar/v2/json/DriverRaceProjections/$race_id", array(
						'method'	=> 'GET',
						'timeout'	=> 60,
					    'headers'	=> array(
					        'Ocp-Apim-Subscription-Key' => $projection_key,
					    ),
					) );
						
					if ( is_array( $projections ) && ! is_wp_error( $projections ) ) {
						
						$projection_data = json_decode($projections['body']);
						$driver_count = 0;
						
						foreach ($projection_data as $driver) {
							
							$player = array();
							
							$player['Name'] = $driver->Name;
							$player['DriverID'] = $driver->DriverID;
							$player['PositionDifferential'] = $driver->PositionDifferential;
							$player['position_differential_points'] = ($driver->PositionDifferential*$position_differential_VAL);
							$player['FastestLaps'] = $driver->FastestLaps;
							$player['fastest_laps_points'] = ($driver->FastestLaps*$fastest_laps_VAL);
							$player['LapsLed'] = $driver->LapsLed;
							$player['laps_led_points'] = ($driver->LapsLed*$laps_led_VAL);
							$player['FinalPosition'] = $driver->FinalPosition;
							
							if ($driver->FinalPosition == 1) {
								$final_position_VAL = $racefinish_1_VAL;
							}
							else if ($driver->FinalPosition == 2) {
								$final_position_VAL = $racefinish_2_VAL;
							}
							else if ($driver->FinalPosition == 3) {
								$final_position_VAL = $racefinish_3_VAL;
							}
							else if ($driver->FinalPosition == 4) {
								$final_position_VAL = $racefinish_4_VAL;
							}
							else if ($driver->FinalPosition == 5) {
								$final_position_VAL = $racefinish_5_VAL;
							}
							else if ($driver->FinalPosition == 6) {
								$final_position_VAL = $racefinish_6_VAL;
							}
							else if ($driver->FinalPosition == 7) {
								$final_position_VAL = $racefinish_7_VAL;
							}
							else if ($driver->FinalPosition == 8) {
								$final_position_VAL = $racefinish_8_VAL;
							}
							else if ($driver->FinalPosition == 9) {
								$final_position_VAL = $racefinish_9_VAL;
							}
							else if ($driver->FinalPosition == 10) {
								$final_position_VAL = $racefinish_10_VAL;
							}
							else if ($driver->FinalPosition == 11) {
								$final_position_VAL = $racefinish_11_VAL;
							}
							else if ($driver->FinalPosition == 12) {
								$final_position_VAL = $racefinish_12_VAL;
							}
							else if ($driver->FinalPosition == 13) {
								$final_position_VAL = $racefinish_13_VAL;
							}
							else if ($driver->FinalPosition == 14) {
								$final_position_VAL = $racefinish_14_VAL;
							}
							else if ($driver->FinalPosition == 15) {
								$final_position_VAL = $racefinish_15_VAL;
							}
							else if ($driver->FinalPosition == 16) {
								$final_position_VAL = $racefinish_16_VAL;
							}
							else if ($driver->FinalPosition == 17) {
								$final_position_VAL = $racefinish_17_VAL;
							}
							else if ($driver->FinalPosition == 18) {
								$final_position_VAL = $racefinish_18_VAL;
							}
							else if ($driver->FinalPosition == 19) {
								$final_position_VAL = $racefinish_19_VAL;
							}
							else if ($driver->FinalPosition == 20) {
								$final_position_VAL = $racefinish_20_VAL;
							}
							else if ($driver->FinalPosition == 21) {
								$final_position_VAL = $racefinish_21_VAL;
							}
							else if ($driver->FinalPosition == 22) {
								$final_position_VAL = $racefinish_22_VAL;
							}
							else if ($driver->FinalPosition == 23) {
								$final_position_VAL = $racefinish_23_VAL;
							}
							else if ($driver->FinalPosition == 24) {
								$final_position_VAL = $racefinish_24_VAL;
							}
							else if ($driver->FinalPosition == 25) {
								$final_position_VAL = $racefinish_25_VAL;
							}
							else if ($driver->FinalPosition == 26) {
								$final_position_VAL = $racefinish_26_VAL;
							}
							else if ($driver->FinalPosition == 27) {
								$final_position_VAL = $racefinish_27_VAL;
							}
							else if ($driver->FinalPosition == 28) {
								$final_position_VAL = $racefinish_28_VAL;
							}
							else if ($driver->FinalPosition == 29) {
								$final_position_VAL = $racefinish_29_VAL;
							}
							else if ($driver->FinalPosition == 30) {
								$final_position_VAL = $racefinish_30_VAL;
							}
							else if ($driver->FinalPosition == 31) {
								$final_position_VAL = $racefinish_31_VAL;
							}
							else if ($driver->FinalPosition == 32) {
								$final_position_VAL = $racefinish_32_VAL;
							}
							else if ($driver->FinalPosition == 33) {
								$final_position_VAL = $racefinish_33_VAL;
							}
							else if ($driver->FinalPosition == 34) {
								$final_position_VAL = $racefinish_34_VAL;
							}
							else if ($driver->FinalPosition == 35) {
								$final_position_VAL = $racefinish_35_VAL;
							}
							else if ($driver->FinalPosition == 36) {
								$final_position_VAL = $racefinish_36_VAL;
							}
							else if ($driver->FinalPosition == 37) {
								$final_position_VAL = $racefinish_37_VAL;
							}
							else if ($driver->FinalPosition == 38) {
								$final_position_VAL = $racefinish_38_VAL;
							}
							else if ($driver->FinalPosition == 39) {
								$final_position_VAL = $racefinish_39_VAL;
							}
							else if ($driver->FinalPosition == 40) {
								$final_position_VAL = $racefinish_40_VAL;
							}
							else if ($driver->FinalPosition == 41) {
								$final_position_VAL = $racefinish_41_VAL;
							}
							else if ($driver->FinalPosition == 42) {
								$final_position_VAL = $racefinish_42_VAL;
							}
							else if ($driver->FinalPosition == 43) {
								$final_position_VAL = $racefinish_43_VAL;
							}
							else {
								$final_position_VAL = 0;
							}
							
							$player['final_position_points'] = $final_position_VAL;
							
							$total_points = ($driver->PositionDifferential*$position_differential_VAL) + ($driver->FastestLaps*$fastest_laps_VAL) + ($driver->LapsLed*$laps_led_VAL) + $final_position_VAL;
							
							$player['total_points'] = number_format($total_points,2);
							
							$contest_data[] = $player;
							
							$driver_count++;
							
													
						}
						
						
						// Arrange drivers by total points
						
						$sort = array();
						foreach ($contest_data as $key => $part) {
							$sort[$key] = $part['total_points'];
						}
						array_multisort($sort, SORT_DESC, $contest_data);
						

						
						// Group drivers by teams of 2 and append total points, odds & team name
							
						$total = floor(count($contest_data));
												
						$mixed_projections = array();
						$j = 0; $k = 0;
						
						for ($i = 0; $i < $total; $i++) {
							
							if ($j > 1) {
								$j = 0;
								$mixed_projections[] = $team[$k];
								$mixed_projections[$k]['total_points'] = $mixed_projections[$k][0]['total_points']+$mixed_projections[$k][1]['total_points'];
								$mixed_projections[$k]['team_name'] = 'Team ' . ($k+1);
								$mixed_projections[$k]['team_number'] = ($k+1);
								$mixed_projections[$k]['odds_to_1'] = ($k+2);
								$k++;
							}
							
							$team[$k][] = $contest_data[$i];
							$j++;
						}
												
						// Create contest
							
						$date = strtoupper(date('m-d-Y g:i a', strtotime($race->DateTime) - 60 * 60 * 3));
						$date_unix = strtoupper(date('U', strtotime($race->DateTime) - 60 * 60 * 3));
						$date_notime = date('m-d-Y', strtotime($race->DateTime) - 60 * 60 * 3);
						$date_notime_day = date('l F jS', strtotime($race->DateTime) - 60 * 60 * 3);
						$date_sort = strtoupper(date('Y-m-d H:i:s', strtotime($race->DateTime) - 60 * 60 * 3));
						
						$mixed_contest = array(
							'post_status' => 'publish',
							'post_title' => 'NASCAR: Mixed ' . $date_notime,
							'post_type' => 'contest',
							'meta_input' => array (
								'contest_type' => 'Mixed',
								'contest_date' => $date_unix,
								'contest_date_sort' => $date_sort,
								'contest_status' => 'Open',
								'contest_data' => json_encode($mixed_projections, JSON_UNESCAPED_UNICODE),
								'contest_title_without_type' => 'NASCAR Mixed: ' . $date_notime_day,
								'contest_tournament_name' => $race_name,
								'contest_tournament_id' => $race_id,
								'contest_tournament_location' => $race_track,
							),
							'tax_input' => array (
								'league' => 8,
							),
						);
						
						$post_exists = post_exists('NASCAR: Mixed ' . $date_notime);
									
						if ($post_exists == 0) {
							
							wp_insert_post( $mixed_contest );
							$already_exists = false;
							$race_count++;
							
						}
						else {
							
							$mixed_contest['ID'] = $post_exists;
							$already_exists = true;
							
						}
						
						
						
					}
					else {
						
						echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 9)</p></div>';
						
					}
					
					
				}
				
			}
		
		}
		
		if ($race_count == 0) {
			
			if ($already_exists == true) {
				
				echo '<div id="message" class="updated fade error"><p>A NASCAR contest ('.$mixed_contest['ID'].') has already been created for this date. You must delete it to rebuild projections. (Error Code 7)</p></div>';
				
			}
			else {
				
				echo '<div id="message" class="updated fade error"><p>No NASCAR races were retrieved from the SportsDataIO API for the requested date ('.$date.'). Please try again. (Error Code 8)</p></div>';
				
			}
			
			
			
		}
		else {
			
			echo '<div id="message" class="updated fade"><p>'.$race_count.' NASCAR contest(s) created.</p></div>';
			
		}
		
		
	}
	else {
	
		echo '<div id="message" class="updated fade error"><p>There was an error retrieving NASCAR races from the SportsDataIO API. Please try again. (Error Code 6)</p></div>';
	
	}	
	
}



//Update Live contests with latest stats

function update_live_nascar_contests($stats_key, $completed) {
	
	$position_differential_VAL = get_option('nascar-points-position-differential');
	$fastest_laps_VAL = get_option('nascar-points-fastest-laps');
	$laps_led_VAL = get_option('nascar-points-laps-led');

	$racefinish_1_VAL = get_option('nascar-points-racefinish-1');
	$racefinish_2_VAL = get_option('nascar-points-racefinish-2');
	$racefinish_3_VAL = get_option('nascar-points-racefinish-3');
	$racefinish_4_VAL = get_option('nascar-points-racefinish-4');
	$racefinish_5_VAL = get_option('nascar-points-racefinish-5');
	$racefinish_6_VAL = get_option('nascar-points-racefinish-6');
	$racefinish_7_VAL = get_option('nascar-points-racefinish-7');
	$racefinish_8_VAL = get_option('nascar-points-racefinish-8');
	$racefinish_9_VAL = get_option('nascar-points-racefinish-9');
	$racefinish_10_VAL = get_option('nascar-points-racefinish-10');
	$racefinish_11_VAL = get_option('nascar-points-racefinish-11');
	$racefinish_12_VAL = get_option('nascar-points-racefinish-12');
	$racefinish_13_VAL = get_option('nascar-points-racefinish-13');
	$racefinish_14_VAL = get_option('nascar-points-racefinish-14');
	$racefinish_15_VAL = get_option('nascar-points-racefinish-15');
	$racefinish_16_VAL = get_option('nascar-points-racefinish-16');
	$racefinish_17_VAL = get_option('nascar-points-racefinish-17');
	$racefinish_18_VAL = get_option('nascar-points-racefinish-18');
	$racefinish_19_VAL = get_option('nascar-points-racefinish-19');
	$racefinish_20_VAL = get_option('nascar-points-racefinish-20');
	$racefinish_21_VAL = get_option('nascar-points-racefinish-21');
	$racefinish_22_VAL = get_option('nascar-points-racefinish-22');
	$racefinish_23_VAL = get_option('nascar-points-racefinish-23');
	$racefinish_24_VAL = get_option('nascar-points-racefinish-24');
	$racefinish_25_VAL = get_option('nascar-points-racefinish-25');
	$racefinish_26_VAL = get_option('nascar-points-racefinish-26');
	$racefinish_27_VAL = get_option('nascar-points-racefinish-27');
	$racefinish_28_VAL = get_option('nascar-points-racefinish-28');
	$racefinish_29_VAL = get_option('nascar-points-racefinish-29');
	$racefinish_30_VAL = get_option('nascar-points-racefinish-30');
	$racefinish_31_VAL = get_option('nascar-points-racefinish-31');
	$racefinish_32_VAL = get_option('nascar-points-racefinish-32');
	$racefinish_33_VAL = get_option('nascar-points-racefinish-33');
	$racefinish_34_VAL = get_option('nascar-points-racefinish-34');
	$racefinish_35_VAL = get_option('nascar-points-racefinish-35');
	$racefinish_36_VAL = get_option('nascar-points-racefinish-36');
	$racefinish_37_VAL = get_option('nascar-points-racefinish-37');
	$racefinish_38_VAL = get_option('nascar-points-racefinish-38');
	$racefinish_39_VAL = get_option('nascar-points-racefinish-39');	
	$racefinish_40_VAL = get_option('nascar-points-racefinish-40');
	$racefinish_41_VAL = get_option('nascar-points-racefinish-41');
	$racefinish_42_VAL = get_option('nascar-points-racefinish-42');
	$racefinish_43_VAL = get_option('nascar-points-racefinish-43');
	
	//Update Live NASCAR Contests
	
	$contests_updated = 0;
	if ($completed == false) {
		$contest_status = 'In Progress';
	}
	else if ($completed == true) {
		$contest_status = 'Finished';
	}
	
	$updated_contests_count = 0;
	
	$args = array(
		'post_type' => 'contest',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key'     => 'contest_status',
				'value'   => $contest_status,
			),
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'league',
				'field'    => 'slug',
				'terms'    => 'nascar',
			),
		),
	);
	
	$the_query = new WP_Query( $args );
				
	if ( $the_query->have_posts() ) {
		
		$contest_data = array();
		$contest_dates = array();
		
		while ( $the_query->have_posts() ) {
			
			$the_query->the_post();
			
			global $post;
	
			$this_contest = array();
			
			$this_contest_date = get_field('contest_date', $post->ID);
			$contest_type = get_field('contest_type', $post->ID);
			$contest_projections = json_decode(get_field('contest_data', $post->ID));
			$contest_results = $contest_projections;
			
			$this_contest['date'] = strtoupper(date('Y-M-d', $this_contest_date));
			$this_contest['date_time'] = $this_contest_date;
			$this_contest['contest_id'] = $post->ID;
			
			$race_id = get_field('contest_tournament_id', $post->ID);
			
			
			//Retrieve live stats via SportsDataIO API
			
			$contest_date_url = strtoupper(date('d-M-Y', $this_contest_date));
			
			$response = wp_remote_get( "https://fly.sportsdata.io/nascar/v2/json/raceresult/$race_id", array(
				'method'	=> 'GET',
				'timeout'	=> 60,
			    'headers'	=> array(
			        'Ocp-Apim-Subscription-Key' => $stats_key,
			    ),
			) );
			
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				
				$data = json_decode($response['body']);
				
				/*
				echo "https://fly.sportsdata.io/nascar/v2/json/raceresult/$race_id";
				
				echo '<pre>';
				print_r($data);
				echo '</pre>';
				
				exit;
				*/
				
				/* FOR SUNDAY  --  the below needs to be updated for nascar, currently showing PGA partial */
						
				$race = $data->Race;
				
				/*
				echo '<pre>';
				print_r($race);
				echo '</pre>';
				*/
				
				$drivers = $data->DriverRaces;
				$is_over = $race->IsOver;
				$in_progress = $race->IsInProgress;
				
				if ($in_progress == 1 || $is_over == 1) {
					
					foreach ($drivers as $driver) {
						
						/*
						echo '<pre>';
						print_r($driver);	
						echo '</pre>';
						*/
						
						$driver_id = $driver->DriverID;
						$teamIndex = 0;
						
						foreach ($contest_projections as $team) {
						
							$playerIndex = 0;
							
							foreach ($team as $team_player) {
													
								if ($playerIndex < 2) {
									
									if ($driver_id == $team_player->DriverID) {
																		
										$contest_results[$teamIndex]->$playerIndex->PositionDifferential = $driver->PositionDifferential;
										$contest_results[$teamIndex]->$playerIndex->position_differential_points = ($driver->PositionDifferential*$position_differential_VAL);
										$contest_results[$teamIndex]->$playerIndex->FastestLaps = $driver->FastestLaps;
										$contest_results[$teamIndex]->$playerIndex->fastest_laps_points = ($driver->FastestLaps*$fastest_laps_VAL);
										$contest_results[$teamIndex]->$playerIndex->LapsLed = $driver->LapsLed;
										$contest_results[$teamIndex]->$playerIndex->laps_led_points = ($driver->LapsLed*$laps_led_VAL);
										$contest_results[$teamIndex]->$playerIndex->FinalPosition = $driver->FinalPosition;
										
										if ($driver->FinalPosition == 1) {
											$final_position_VAL = $racefinish_1_VAL;
										}
										else if ($driver->FinalPosition == 2) {
											$final_position_VAL = $racefinish_2_VAL;
										}
										else if ($driver->FinalPosition == 3) {
											$final_position_VAL = $racefinish_3_VAL;
										}
										else if ($driver->FinalPosition == 4) {
											$final_position_VAL = $racefinish_4_VAL;
										}
										else if ($driver->FinalPosition == 5) {
											$final_position_VAL = $racefinish_5_VAL;
										}
										else if ($driver->FinalPosition == 6) {
											$final_position_VAL = $racefinish_6_VAL;
										}
										else if ($driver->FinalPosition == 7) {
											$final_position_VAL = $racefinish_7_VAL;
										}
										else if ($driver->FinalPosition == 8) {
											$final_position_VAL = $racefinish_8_VAL;
										}
										else if ($driver->FinalPosition == 9) {
											$final_position_VAL = $racefinish_9_VAL;
										}
										else if ($driver->FinalPosition == 10) {
											$final_position_VAL = $racefinish_10_VAL;
										}
										else if ($driver->FinalPosition == 11) {
											$final_position_VAL = $racefinish_11_VAL;
										}
										else if ($driver->FinalPosition == 12) {
											$final_position_VAL = $racefinish_12_VAL;
										}
										else if ($driver->FinalPosition == 13) {
											$final_position_VAL = $racefinish_13_VAL;
										}
										else if ($driver->FinalPosition == 14) {
											$final_position_VAL = $racefinish_14_VAL;
										}
										else if ($driver->FinalPosition == 15) {
											$final_position_VAL = $racefinish_15_VAL;
										}
										else if ($driver->FinalPosition == 16) {
											$final_position_VAL = $racefinish_16_VAL;
										}
										else if ($driver->FinalPosition == 17) {
											$final_position_VAL = $racefinish_17_VAL;
										}
										else if ($driver->FinalPosition == 18) {
											$final_position_VAL = $racefinish_18_VAL;
										}
										else if ($driver->FinalPosition == 19) {
											$final_position_VAL = $racefinish_19_VAL;
										}
										else if ($driver->FinalPosition == 20) {
											$final_position_VAL = $racefinish_20_VAL;
										}
										else if ($driver->FinalPosition == 21) {
											$final_position_VAL = $racefinish_21_VAL;
										}
										else if ($driver->FinalPosition == 22) {
											$final_position_VAL = $racefinish_22_VAL;
										}
										else if ($driver->FinalPosition == 23) {
											$final_position_VAL = $racefinish_23_VAL;
										}
										else if ($driver->FinalPosition == 24) {
											$final_position_VAL = $racefinish_24_VAL;
										}
										else if ($driver->FinalPosition == 25) {
											$final_position_VAL = $racefinish_25_VAL;
										}
										else if ($driver->FinalPosition == 26) {
											$final_position_VAL = $racefinish_26_VAL;
										}
										else if ($driver->FinalPosition == 27) {
											$final_position_VAL = $racefinish_27_VAL;
										}
										else if ($driver->FinalPosition == 28) {
											$final_position_VAL = $racefinish_28_VAL;
										}
										else if ($driver->FinalPosition == 29) {
											$final_position_VAL = $racefinish_29_VAL;
										}
										else if ($driver->FinalPosition == 30) {
											$final_position_VAL = $racefinish_30_VAL;
										}
										else if ($driver->FinalPosition == 31) {
											$final_position_VAL = $racefinish_31_VAL;
										}
										else if ($driver->FinalPosition == 32) {
											$final_position_VAL = $racefinish_32_VAL;
										}
										else if ($driver->FinalPosition == 33) {
											$final_position_VAL = $racefinish_33_VAL;
										}
										else if ($driver->FinalPosition == 34) {
											$final_position_VAL = $racefinish_34_VAL;
										}
										else if ($driver->FinalPosition == 35) {
											$final_position_VAL = $racefinish_35_VAL;
										}
										else if ($driver->FinalPosition == 36) {
											$final_position_VAL = $racefinish_36_VAL;
										}
										else if ($driver->FinalPosition == 37) {
											$final_position_VAL = $racefinish_37_VAL;
										}
										else if ($driver->FinalPosition == 38) {
											$final_position_VAL = $racefinish_38_VAL;
										}
										else if ($driver->FinalPosition == 39) {
											$final_position_VAL = $racefinish_39_VAL;
										}
										else if ($driver->FinalPosition == 40) {
											$final_position_VAL = $racefinish_40_VAL;
										}
										else if ($driver->FinalPosition == 41) {
											$final_position_VAL = $racefinish_41_VAL;
										}
										else if ($driver->FinalPosition == 42) {
											$final_position_VAL = $racefinish_42_VAL;
										}
										else if ($driver->FinalPosition == 43) {
											$final_position_VAL = $racefinish_43_VAL;
										}
										else {
											$final_position_VAL = 0;
										}
										
										$contest_results[$teamIndex]->$playerIndex->final_position_points = $final_position_VAL;
										
										$total_points = $final_position_VAL + ($driver->LapsLed*$laps_led_VAL) + ($driver->FastestLaps*$fastest_laps_VAL) + ($driver->PositionDifferential*$position_differential_VAL);
										
										$contest_results[$teamIndex]->$playerIndex->total_points = $total_points;
									
									}
									
									$playerIndex++;
								
								}
						
							}
							
							$teamIndex++;
							
						}
						
					}
					
					
					//recalculate total points
							
							
					$teamIndex = 0;
					
					foreach ($contest_results as $team) {
						
						$teamTotal = 0;
						$playerCount = 0;
						
						foreach ($team as $player) {
							
							if ($playerCount < 2) {
								$teamTotal += $player->total_points;
							}
							$playerCount++;
						}
						
						$contest_results[$teamIndex]->total_points = $teamTotal;
						$contest_results[$teamIndex]->team_name = 'Team ' . ($teamIndex+1);
						
						$teamIndex++;
						
					}
					
					$sort = array();
					foreach ($contest_results as $key => $part) {
						$sort[$key] = $part->total_points;
					}
					array_multisort($sort, SORT_DESC, $contest_results);
					
					
					// Update 6 winners
					for ($i = 0; $i < 6; $i++) {				
						
						update_post_meta($post->ID, 'mixed_winner_'.($i+1), $contest_results[$i]->team_name);
						$projected_teams[$i]['team_name'] = $contest_results[$i]->team_name;
						
					}	
					
						
					update_field('contest_results', json_encode($contest_results, JSON_UNESCAPED_UNICODE), $post->ID);
					update_field('contest_live_stats_url', 'https://fly.sportsdata.io/nascar/v2/json/raceresult/'.$race_id.'?key='.$stats_key, $post->ID);
					
					$updated_contests_count++;
					
				}
				else {
					
					echo '<div id="message" class="updated fade error"><p>This NASCAR race ('.$post->ID.') is not currently in progress. (Error Code 11)</p></div>';
					
				}
				

			}
			else {
				
				echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 10)</p></div>';
				
			}
		
		}
	
	}
	else {
		
		echo '<div id="message" class="updated fade error"><p>There are no NASCAR contests in progress.</p></div>';
		
	}
	
	wp_reset_query();
	
	echo '<div id="message" class="updated fade"><p>'.$updated_contests_count.' Live NASCAR contest(s) updated.</p></div>';
	
	$cron_log = array(
		'post_status' => 'draft',
		'post_title' => 'NASCAR Cron Log - Update Live',
		'post_type' => 'cron_log',
		'post_content' => date('m-d-Y g:i a', time() - (60*60*7)) . ' PT',
		'tax_input' => array (
			'cron_type' => 4303,
		),
	);
	
	wp_set_current_user(1);
	wp_insert_post( $cron_log );
	
}
	
	
// Process completed contests and wagers

function process_finished_nascar_contests($stats_key) {
	
	
	// Retrieve 'In Progress' contests
	
	$args = array(
		'post_type' => 'contest',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key'     => 'contest_status',
				'value'   => 'In Progress',
			),
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'league',
				'field'    => 'slug',
				'terms'    => 'nascar',
			),
		),
	);
	
	$the_query = new WP_Query( $args );
				
	if ( $the_query->have_posts() ) {
		
		$race_data = array();
		$contest_dates = array();
		
		while ( $the_query->have_posts() ) {
			
			$the_query->the_post();
			global $post;
			
			$this_contest = array();
			$this_contest_date = get_field('contest_date');
			$this_contest['date'] = strtoupper(date('Y-M-d', $this_contest_date));
			$this_contest['date_time'] = $this_contest_date;
			$this_contest['id'] = $post->ID;
			$this_contest['race_id'] = get_field('contest_tournament_id', $post->ID);
			
			if (!in_array($this_contest['date'], $contest_dates)) {
				
				$contest_dates[] = $this_contest['date'];
								
			}
			
			$contest_data[] = $this_contest;
			
			
		}
	}
	wp_reset_query();
	
	
		
		
	// If contest is finished, mark as "Finished"
	
	$continue = false;
	
	if (!empty($contest_dates)) {
			
		$postponed_contests = array();
			
		foreach ($contest_data as $race) {
				
			$race_id = $race['race_id'];
			$contest_id = $race['id'];
			
			$response = wp_remote_get( "https://fly.sportsdata.io/nascar/v2/json/raceresult/$race_id", array(
				'method'	=> 'GET',
				'timeout'	=> 60,
			    'headers'	=> array(
			        'Ocp-Apim-Subscription-Key' => $stats_key,
			    ),
			) );
			
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				
				$data = json_decode($response['body']);
				
				/*
				echo '<pre>';
				print_r($data);
				echo '</pre>';				
				*/
				
				$is_over = $data->Race->IsOver;	
				
				//echo $is_over;		
						
				if ($is_over == 1) {
										
					update_field('contest_status', 'Finished', $contest_id);
					
					$continue = true;
					
				}
				
			}
			else {
				
				echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 3)</p></div>';
				
			}
			
		}
		
	}
	else {
		
		echo '<div id="message" class="updated fade error"><p>There are no NASCAR races in progress. Please try again later. (Error Code 5)</p></div>';
		
	}
					
					
					
					
					
	if ($continue == true) { 
	
	
		// Update stats for completed contests
	
		update_live_nascar_contests($stats_key, true);
	
	
		// Retrieve completed contests and calculate winners
	
		$contest_count = 0;
		$wager_count = 0;
	
		$args = array(
			'post_type' => 'contest',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key'     => 'contest_status',
					'value'   => 'Finished',
				),
			),
		);
		
		$the_query = new WP_Query( $args );
				
		if ( $the_query->have_posts() ) {
			
			while ( $the_query->have_posts() ) {
				
				$the_query->the_post();
				global $post;
				
				$contest_type = get_field('contest_type', $post->ID);
				$contest_date = strtoupper(date('d-M-Y', get_field('contest_date', $post->ID)));
				$contest_results = json_decode(get_field('contest_results', $post->ID), false, JSON_UNESCAPED_UNICODE);
				
				if ($contest_type == 'Teams') {

					$team_winners = array();
					
					$i = 0;
					foreach ($contest_results as $winner) {
						
						if ($i < 6) {
							
							$term_id = $winner->term_id;
							update_post_meta($post->ID, 'team_winner_'.($i+1), $term_id);
							$team_winners[] = $term_id;
							
						}
					
						$i++;
					}
										
					
					//now, process all wagers for this contest
				
					$args = array(
						'post_type' => 'wager',
						'posts_per_page' => -1,
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key'     => 'wager_contest',
								'value'   => $post->ID,
							),
							array(
								'key'     => 'wager_result',
								'value'   => 'Open',
							),
						),
					);
					
					$the_wager = new WP_Query( $args );
							
					if ( $the_wager->have_posts() ) {
						
						while ( $the_wager->have_posts() ) {
							
							$the_wager->the_post();
							
							$wager_id = get_the_id();
							
							$wager_type = strtolower(get_field('wager_type', $wager_id));
							$wager_amount = str_replace(',','',get_field('wager_amount', $wager_id));
							$wager_winnings = str_replace(',','',get_field('potential_winnings', $wager_id));
									
							$wagered_winner_1 = get_field('wager_winner_1', $wager_id);
							$wagered_winner_2 = get_field('wager_winner_2', $wager_id);
							$wagered_winner_3 = get_field('wager_winner_3', $wager_id);
							$wagered_winner_4 = get_field('wager_winner_4', $wager_id);
							$wagered_winner_5 = get_field('wager_winner_5', $wager_id);
							$wagered_winner_6 = get_field('wager_winner_6', $wager_id);
								
							$actual_winner_1 = $team_winners[0];
							$actual_winner_2 = $team_winners[1];
							$actual_winner_3 = $team_winners[2];
							$actual_winner_4 = $team_winners[3];
							$actual_winner_5 = $team_winners[4];
							$actual_winner_6 = $team_winners[5];
							
													
							$winner = false;
												
							if ($wager_type == 'win') {
								
								if ($wagered_winner_1 == $actual_winner_1) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
															
							}
							else if ($wager_type == 'place') {
								
								if ($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'show') {
								
								if ($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2 || $wagered_winner_1 == $actual_winner_3) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'pick 2') {
								
								if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'pick 2 box') {
								
								if (($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2) && ($wagered_winner_2 == $actual_winner_1 || $wagered_winner_2 == $actual_winner_2)) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'pick 3') {
								
								if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2 && $wagered_winner_3 == $actual_winner_3) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'pick 3 box') {
								
								if (($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2 || $wagered_winner_1 == $actual_winner_3) && ($wagered_winner_2 == $actual_winner_1 || $wagered_winner_2 == $actual_winner_2 || $wagered_winner_2 == $actual_winner_3) && ($wagered_winner_3 == $actual_winner_1 || $wagered_winner_3 == $actual_winner_2 || $wagered_winner_3 == $actual_winner_3)) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'pick 4') {
								
								if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2 && $wagered_winner_3 == $actual_winner_3 && $wagered_winner_4 == $actual_winner_4) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'pick 4 box') {
								
								if (($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2 || $wagered_winner_1 == $actual_winner_3) && ($wagered_winner_2 == $actual_winner_1 || $wagered_winner_2 == $actual_winner_2 || $wagered_winner_2 == $actual_winner_3) && ($wagered_winner_3 == $actual_winner_1 || $wagered_winner_3 == $actual_winner_2 || $wagered_winner_3 == $actual_winner_3) && ($wagered_winner_4 == $actual_winner_1 || $wagered_winner_4 == $actual_winner_2 || $wagered_winner_4 == $actual_winner_3 || $wagered_winner_4 == $actual_winner_4)) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'pick 6') {
								
								if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2 && $wagered_winner_3 == $actual_winner_3 && $wagered_winner_4 == $actual_winner_4 && $wagered_winner_5 == $actual_winner_5 && $wagered_winner_6 == $actual_winner_6) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							
							update_field('wager_contest_winner', $actual_winner_1, $wager_id);
							
							//Update user balance
							
							$user_id = get_post_field( 'post_author', $wager_id );
							$buying_power = floatval(get_field('account_balance', 'user_'.$user_id));	
							$total_equity = floatval(get_field('visible_balance', 'user_'.$user_id));
							
							
							if ($winner == true) {
								
								$total_equity = $total_equity + $wager_winnings;
								$buying_power = $buying_power + $wager_winnings + $wager_amount;
														
							}
							else {
								
								$total_equity = $total_equity - $wager_amount;
								
							}
							
							update_field('account_balance', $buying_power, 'user_'.$user_id);
							update_field('visible_balance', $total_equity, 'user_'.$user_id);
							
							$wager_count++;
							
					
						}
						
						$the_query->reset_postdata();
						
					}
	
							
					//final step: mark contest as 'closed'
					update_post_meta($post->ID, 'contest_status', 'Closed');
						
				}
				else if ($contest_type == 'Mixed') {

					$mixed_winners = array();
					
					$i = 0;
					foreach ($contest_results as $winner) {
						
						if ($i < 6) {
							
							$winner_name = $winner->team_name;
							update_post_meta($post->ID, 'mixed_winner_'.($i+1), $winner_name);
							$mixed_winners[] = $winner_name;
							
						}
					
						$i++;
					}
										
					
					//now, process all wagers for this contest
				
					$args = array(
						'post_type' => 'wager',
						'posts_per_page' => -1,
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key'     => 'wager_contest',
								'value'   => $post->ID,
							),
							array(
								'key'     => 'wager_result',
								'value'   => 'Open',
							),
						),
					);
					
					$the_wager = new WP_Query( $args );
							
					if ( $the_wager->have_posts() ) {
						
						while ( $the_wager->have_posts() ) {
							
							$the_wager->the_post();
							
							$wager_id = get_the_id();
							
							$wager_type = strtolower(get_field('wager_type', $wager_id));
							$wager_amount = str_replace(',','',get_field('wager_amount', $wager_id));
							$wager_winnings = str_replace(',','',get_field('potential_winnings', $wager_id));
									
							$wagered_winner_1 = get_field('wager_winner_1', $wager_id);
							$wagered_winner_2 = get_field('wager_winner_2', $wager_id);
							$wagered_winner_3 = get_field('wager_winner_3', $wager_id);
							$wagered_winner_4 = get_field('wager_winner_4', $wager_id);
							$wagered_winner_5 = get_field('wager_winner_5', $wager_id);
							$wagered_winner_6 = get_field('wager_winner_6', $wager_id);
								
							$actual_winner_1 = $mixed_winners[0];
							$actual_winner_2 = $mixed_winners[1];
							$actual_winner_3 = $mixed_winners[2];
							$actual_winner_4 = $mixed_winners[3];
							$actual_winner_5 = $mixed_winners[4];
							$actual_winner_6 = $mixed_winners[5];
							
							
													
							$winner = false;
												
							if ($wager_type == 'win') {
								
								if ($wagered_winner_1 == $actual_winner_1) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
															
							}
							else if ($wager_type == 'place') {
								
								if ($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'show') {
								
								if ($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2 || $wagered_winner_1 == $actual_winner_3) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'pick 2') {
								
								if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'pick 2 box') {
								
								if (($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2) && ($wagered_winner_2 == $actual_winner_1 || $wagered_winner_2 == $actual_winner_2)) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'pick 3') {
								
								if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2 && $wagered_winner_3 == $actual_winner_3) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'pick 3 box') {
								
								if (($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2 || $wagered_winner_1 == $actual_winner_3) && ($wagered_winner_2 == $actual_winner_1 || $wagered_winner_2 == $actual_winner_2 || $wagered_winner_2 == $actual_winner_3) && ($wagered_winner_3 == $actual_winner_1 || $wagered_winner_3 == $actual_winner_2 || $wagered_winner_3 == $actual_winner_3)) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'pick 4') {
								
								if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2 && $wagered_winner_3 == $actual_winner_3 && $wagered_winner_4 == $actual_winner_4) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'pick 4 box') {
								
								if (($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2 || $wagered_winner_1 == $actual_winner_3) && ($wagered_winner_2 == $actual_winner_1 || $wagered_winner_2 == $actual_winner_2 || $wagered_winner_2 == $actual_winner_3) && ($wagered_winner_3 == $actual_winner_1 || $wagered_winner_3 == $actual_winner_2 || $wagered_winner_3 == $actual_winner_3) && ($wagered_winner_4 == $actual_winner_1 || $wagered_winner_4 == $actual_winner_2 || $wagered_winner_4 == $actual_winner_3 || $wagered_winner_4 == $actual_winner_4)) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							else if ($wager_type == 'pick 6') {
								
								if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2 && $wagered_winner_3 == $actual_winner_3 && $wagered_winner_4 == $actual_winner_4 && $wagered_winner_5 == $actual_winner_5 && $wagered_winner_6 == $actual_winner_6) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									$winner = true;
									
								}
								else {
									
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
								}
								
							}
							
							update_field('wager_contest_winner', $actual_winner_1, $wager_id);
							
							//Update user balance
							
							$user_id = get_post_field( 'post_author', $wager_id );
							$buying_power = floatval(get_field('account_balance', 'user_'.$user_id));	
							$total_equity = floatval(get_field('visible_balance', 'user_'.$user_id));
							
							
							if ($winner == true) {
								
								$total_equity = $total_equity + $wager_winnings;
								$buying_power = $buying_power + $wager_winnings + $wager_amount;
														
							}
							else {
								
								$total_equity = $total_equity - $wager_amount;
								
							}
							
							update_field('account_balance', $buying_power, 'user_'.$user_id);
							update_field('visible_balance', $total_equity, 'user_'.$user_id);
							
							$wager_count++;
							
					
						}
						
						$the_query->reset_postdata();
						
					}
	
							
					//final step: mark contest as 'closed'
					update_post_meta($post->ID, 'contest_status', 'Closed');
					
				}
				
				$contest_count++;
			
			}
		}
		wp_reset_query();
	
		
		echo '<div id="message" class="updated fade"><p>'.$wager_count.' wager(s) and '.$contest_count.' contest(s) processed.</p></div>';
			
	
	}
	else {
		
		echo '<div id="message" class="updated fade error"><p>There are no finished NASCAR contests ready for processing. Please try again later. (Error Code 9)</p></div>';
		
	}
	
}


// Cron jobs

add_action( 'nascar_update_live_cron', 'nascar_update_live_cron' );

function nascar_update_live_cron() {
	
	$stats_key = '63012620571942b08ed8028f75e32fd3';
	update_live_nascar_contests($stats_key, false);
	
	$cron_log = array(
		'post_status' => 'draft',
		'post_title' => 'NASCAR Cron Log - Update Live',
		'post_type' => 'cron_log',
		'post_content' => date('m-d-Y g:i a', time() - (60*60*7)) . ' PT',
		'tax_input' => array (
			'cron_type' => 4303,
		),
	);
	
	wp_set_current_user(1);
	wp_insert_post( $cron_log );
	
}