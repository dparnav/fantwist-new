<?php
	
//Register settings and options

function pariwagerPGApoints() {
		
	register_setting( 'pariwager-pga-points', 'pga-points-eagles' );
	register_setting( 'pariwager-pga-points', 'pga-points-birdies' );
	register_setting( 'pariwager-pga-points', 'pga-points-pars' );
	register_setting( 'pariwager-pga-points', 'pga-points-bogeys' );
	register_setting( 'pariwager-pga-points', 'pga-points-doublebogeyorworse' );
	register_setting( 'pariwager-pga-points', 'pga-points-holesinone' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-1' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-2' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-3' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-4' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-5' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-6' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-7' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-8' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-9' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-10' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-11-15' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-16-20' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-21-25' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-26-30' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-31-40' );
	register_setting( 'pariwager-pga-points', 'pga-points-tourneyfinish-41-50' );
	register_setting( 'pariwager-pga-points', 'pga-last-updated-live' );
	   
}
add_action( 'admin_init', 'pariwagerPGApoints' );





//Update PGA Projections and Contests

function create_pga_projections_and_contests($date, $projection_key) {
	
	//Define vars
	
	$parent_team = 3835;
	$tax_league = 7;
	$league_title = 'PGA';
	
	$eagles_VAL = get_option('pga-points-eagles');
	$birdies_VAL = get_option('pga-points-birdies');
	$pars_VAL = get_option('pga-points-pars');
	$bogeys_VAL = get_option('pga-points-bogeys');
	$doublebogeyorworse_VAL = get_option('pga-points-doublebogeyorworse');
	$holesinone_VAL = get_option('pga-points-holesinone');
	$tourneyfinish_1_VAL = get_option('pga-points-tourneyfinish-1');
	$tourneyfinish_2_VAL = get_option('pga-points-tourneyfinish-2');
	$tourneyfinish_3_VAL = get_option('pga-points-tourneyfinish-3');
	$tourneyfinish_4_VAL = get_option('pga-points-tourneyfinish-4');
	$tourneyfinish_5_VAL = get_option('pga-points-tourneyfinish-5');
	$tourneyfinish_6_VAL = get_option('pga-points-tourneyfinish-6');
	$tourneyfinish_7_VAL = get_option('pga-points-tourneyfinish-7');
	$tourneyfinish_8_VAL = get_option('pga-points-tourneyfinish-8');
	$tourneyfinish_9_VAL = get_option('pga-points-tourneyfinish-9');
	$tourneyfinish_10_VAL = get_option('pga-points-tourneyfinish-10');
	$tourneyfinish_11_15_VAL = get_option('pga-points-tourneyfinish-11-15');
	$tourneyfinish_16_20_VAL = get_option('pga-points-tourneyfinish-16-20');
	$tourneyfinish_21_25_VAL = get_option('pga-points-tourneyfinish-21-25');
	$tourneyfinish_26_30_VAL = get_option('pga-points-tourneyfinish-26-30');
	$tourneyfinish_31_40_VAL = get_option('pga-points-tourneyfinish-31-40');
	$tourneyfinish_41_50_VAL = get_option('pga-points-tourneyfinish-41-50');
	
		
		
	// Get tournament projections for requested day
	
	$tourneys = wp_remote_get( "https://fly.sportsdata.io/golf/v2/json/Tournaments", array(
		'method'	=> 'GET',
		'timeout'	=> 60,
	    'headers'	=> array(
	        'Ocp-Apim-Subscription-Key' => $projection_key,
	    ),
	) );
		
	if ( is_array( $tourneys ) && ! is_wp_error( $tourneys ) ) {
		
		$tourney_data = json_decode($tourneys['body']);
		
		$requested_date = date('Y-m-d', strtotime($date));
		$contest_data = array();
		$tourney_count = 0;
		
		foreach ($tourney_data as $tournament) {
		
			$tourney_start = date('Y-m-d', strtotime($tournament->StartDate));
			
			if ($tourney_start == $requested_date && $tournament->TournamentID != 339) {
						
				$tourney_end = date('F j, Y', strtotime($tournament->EndDate));
				$tourney_name = $tournament->Name;
				$tourney_id = $tournament->TournamentID;
				$tourney_location = $tournament->Location;
				
				$projections = wp_remote_get( "https://fly.sportsdata.io/golf/v2/json/PlayerTournamentProjectionStats/$tourney_id", array(
					'method'	=> 'GET',
					'timeout'	=> 60,
				    'headers'	=> array(
				        'Ocp-Apim-Subscription-Key' => $projection_key,
				    ),
				) );
					
				if ( is_array( $projections ) && ! is_wp_error( $projections ) ) {
					
					$projection_data = json_decode($projections['body']);
					$golfer_count = 0;
					
					foreach ($projection_data as $player_projection) {
						
						$player = array();
							
						$playerTournamentID = $player_projection->PlayerTournamentID;
						$playerID = $player_projection->PlayerID;
						$name = $player_projection->Name;
						$double_bogeys_or_worse = $player_projection->DoubleBogeys + $player_projection->WorseThanDoubleBogey;
						$bogeys = $player_projection->Bogeys;
						$pars = $player_projection->Pars;
						$birdies = $player_projection->Birdies;
						$eagles = $player_projection->Eagles;
						$total_strokes = $player_projection->TotalStrokes;
						$holeinones = $player_projection->HoleInOnes;
						
						$total_points = ($eagles*$eagles_VAL) + ($birdies*$birdies_VAL) + ($pars*$pars_VAL) + ($bogeys*$bogeys_VAL) + ($doublebogeyorworse_VAL*$double_bogeys_or_worse) + ($holeinones*$holesinone_VAL);
						
						$player['PlayerTournamentID'] = $playerTournamentID;
						$player['PlayerID'] = $playerID;
						$player['name'] = $name;
						$player['total_strokes'] = $total_strokes;
						$player['eagles'] = $eagles;
						$player['eagles_points'] = $eagles*$eagles_VAL;
						$player['birdies'] = $birdies;
						$player['birdies_points'] = $birdies*$birdies_VAL;
						$player['pars'] = $pars;
						$player['pars_points'] = $pars*$pars_VAL;
						$player['bogeys'] = $bogeys;
						$player['bogeys_points'] = $bogeys*$bogeys_VAL;
						$player['double_bogeys_or_worse'] = $double_bogeys_or_worse;
						$player['double_bogeys_or_worse_points'] = number_format($double_bogeys_or_worse*$doublebogeyorworse_VAL,2);
						$player['holes_in_one'] = $holeinones;
						$player['holes_in_one_points'] = $holeinones*$holesinone_VAL;
						$player['total_points'] = number_format($total_points,2);
						
						$contest_data[] = $player;
						
						$golfer_count++;
												
					}
					
					
					// Arrange golfers by total points
					
					$sort = array();
					foreach ($contest_data as $key => $part) {
						$sort[$key] = $part['total_points'];
					}
					array_multisort($sort, SORT_DESC, $contest_data);
					
					
					
					// Add 'finish' and 'finish points' stats to each golfer, then re-compile and sort points
					
					$i = 0; $j = 1;
					
					foreach ($contest_data as $golfer) {
						
						$contest_data[$i]['rank'] = $j;
						
						if ($j == 1) {
							$finish_points = $tourneyfinish_1_VAL;
						}
						else if ($j == 2) {
							$finish_points = $tourneyfinish_2_VAL;
						}
						else if ($j == 3) {
							$finish_points = $tourneyfinish_3_VAL;
						}
						else if ($j == 4) {
							$finish_points = $tourneyfinish_4_VAL;
						}
						else if ($j == 5) {
							$finish_points = $tourneyfinish_5_VAL;
						}
						else if ($j == 6) {
							$finish_points = $tourneyfinish_6_VAL;
						}
						else if ($j == 7) {
							$finish_points = $tourneyfinish_7_VAL;
						}
						else if ($j == 8) {
							$finish_points = $tourneyfinish_8_VAL;
						}
						else if ($j == 9) {
							$finish_points = $tourneyfinish_9_VAL;
						}
						else if ($j == 10) {
							$finish_points = $tourneyfinish_10_VAL;
						}
						else if ($j > 10 && $j < 16) {
							$finish_points = $tourneyfinish_11_15_VAL;
						}
						else if ($j > 15 && $j < 21) {
							$finish_points = $tourneyfinish_16_20_VAL;
						}
						else if ($j > 20 && $j < 26) {
							$finish_points = $tourneyfinish_21_25_VAL;
						}
						else if ($j > 25 && $j < 31) {
							$finish_points = $tourneyfinish_26_30_VAL;
						}
						else if ($j > 30 && $j < 41) {
							$finish_points = $tourneyfinish_31_40_VAL;
						}
						else if ($j > 40 && $j < 51) {
							$finish_points = $tourneyfinish_41_50_VAL;
						}
						else {
							$finish_points = 0;
						}

						$contest_data[$i]['rank_points'] = $finish_points;
						$contest_data[$i]['total_points'] = $contest_data[$i]['total_points'] + $finish_points;
						
						$i++;
						$j++;
						
					}
					
					$sort = array();
					foreach ($contest_data as $key => $part) {
						$sort[$key] = $part['total_points'];
					}
					array_multisort($sort, SORT_DESC, $contest_data);
					
					
					
					// Group golfers by teams of 4 and append total points & team name
						
					$total = floor(count($contest_data));
											
					$mixed_projections = array();
					$j = 0; $k = 0;
					
					for ($i = 0; $i < $total; $i++) {
						
						if ($j > 3) {
							$j = 0;
							$mixed_projections[] = $team[$k];
							$mixed_projections[$k]['total_points'] = $mixed_projections[$k][0]['total_points']+$mixed_projections[$k][1]['total_points']+$mixed_projections[$k][2]['total_points']+$mixed_projections[$k][3]['total_points'];
							$mixed_projections[$k]['team_name'] = 'Team ' . ($k+1);
							$mixed_projections[$k]['team_number'] = ($k+1);
							$mixed_projections[$k]['odds_to_1'] = ($k+2);
							$k++;
						}
						
						$team[$k][] = $contest_data[$i];
						$j++;
					}
					
					
					// Create contest
						
					$date = strtoupper(date('m-d-Y g:i a', strtotime($tournament->StartDate)));
					$date_unix = strtoupper(date('U', strtotime($tournament->StartDate)));
					$date_notime = date('m-d-Y', strtotime($tournament->StartDate));
					$date_notime_day = date('l F jS', strtotime($tournament->StartDate));
					$date_sort = strtoupper(date('Y-m-d H:i:s', strtotime($tournament->StartDate)));
					
					$mixed_contest = array(
						'post_status' => 'publish',
						'post_title' => 'PGA: Mixed ' . $date_notime,
						'post_type' => 'contest',
						'meta_input' => array (
							'contest_type' => 'Mixed',
							'contest_date' => $date_unix,
							'contest_date_sort' => $date_sort,
							'contest_status' => 'Open',
							'contest_data' => json_encode($mixed_projections, JSON_UNESCAPED_UNICODE),
							'contest_title_without_type' => 'PGA Mixed: ' . $date_notime_day,
							'contest_tournament_name' => $tourney_name,
							'contest_tournament_end_date' => $tourney_end,
							'contest_tournament_id' => $tourney_id,
							'contest_tournament_location' => $tourney_location,
						),
						'tax_input' => array (
							'league' => 7,
						),
					);
					
					$post_exists = post_exists('PGA: Mixed ' . $date_notime);
								
					if ($post_exists == 0) {
						
						wp_insert_post( $mixed_contest );
						$already_exists = false;
						$tourney_count++;
						
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
		
		if ($tourney_count == 0) {
			
			if ($already_exists == true) {
				
				echo '<div id="message" class="updated fade error"><p>A PGA Contest ('.$mixed_contest['ID'].') has already been created for this date. You must delete it to rebuild projections. (Error Code 7)</p></div>';
				
			}
			else {
				
				echo '<div id="message" class="updated fade error"><p>No PGA Tournaments were retrieved from the SportsDataIO API for the requested date ('.$date.'. Please try again. (Error Code 8)</p></div>';
				
			}
			
			
			
		}
		else {
			
			echo '<div id="message" class="updated fade"><p>'.$tourney_count.' PGA contest(s) created.</p></div>';
			
		}
		
		
	}
	else {
	
		echo '<div id="message" class="updated fade error"><p>There was an error retrieving PGA tournaments from the SportsDataIO API. Please try again. (Error Code 6)</p></div>';
	
	}	
	
}



//Update In Progress contests with latest stats

function update_live_pga_contests($stats_key, $completed) {
	
	$eagles_VAL = get_option('pga-points-eagles');
	$birdies_VAL = get_option('pga-points-birdies');
	$pars_VAL = get_option('pga-points-pars');
	$bogeys_VAL = get_option('pga-points-bogeys');
	$doublebogeyorworse_VAL = get_option('pga-points-doublebogeyorworse');
	$holesinone_VAL = get_option('pga-points-holesinone');
	$tourneyfinish_1_VAL = get_option('pga-points-tourneyfinish-1');
	$tourneyfinish_2_VAL = get_option('pga-points-tourneyfinish-2');
	$tourneyfinish_3_VAL = get_option('pga-points-tourneyfinish-3');
	$tourneyfinish_4_VAL = get_option('pga-points-tourneyfinish-4');
	$tourneyfinish_5_VAL = get_option('pga-points-tourneyfinish-5');
	$tourneyfinish_6_VAL = get_option('pga-points-tourneyfinish-6');
	$tourneyfinish_7_VAL = get_option('pga-points-tourneyfinish-7');
	$tourneyfinish_8_VAL = get_option('pga-points-tourneyfinish-8');
	$tourneyfinish_9_VAL = get_option('pga-points-tourneyfinish-9');
	$tourneyfinish_10_VAL = get_option('pga-points-tourneyfinish-10');
	$tourneyfinish_11_15_VAL = get_option('pga-points-tourneyfinish-11-15');
	$tourneyfinish_16_20_VAL = get_option('pga-points-tourneyfinish-16-20');
	$tourneyfinish_21_25_VAL = get_option('pga-points-tourneyfinish-21-25');
	$tourneyfinish_26_30_VAL = get_option('pga-points-tourneyfinish-26-30');
	$tourneyfinish_31_40_VAL = get_option('pga-points-tourneyfinish-31-40');
	$tourneyfinish_41_50_VAL = get_option('pga-points-tourneyfinish-41-50');
	
	
	
	//Update Live PGA Contests
	
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
				'terms'    => 'pga',
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
			
			$this_contest_date = get_field('contest_date');
			$contest_type = get_field('contest_type');
			$contest_projections = json_decode(get_field('contest_data'));
			$contest_results = $contest_projections;
			
			$this_contest['date'] = strtoupper(date('Y-M-d', $this_contest_date));
			$this_contest['date_time'] = $this_contest_date;
			$this_contest['contest_id'] = $post->ID;
			
			$tourney_id = get_field('contest_tournament_id', $post->ID);
			
			//Retrieve live player stats via SportsDataIO API
			
			$contest_date_url = strtoupper(date('d-M-Y', $this_contest_date));
			
			$response = wp_remote_get( "https://fly.sportsdata.io/golf/v2/json/Leaderboard/$tourney_id", array(
				'method'	=> 'GET',
				'timeout'	=> 60,
			    'headers'	=> array(
			        'Ocp-Apim-Subscription-Key' => $stats_key,
			    ),
			) );
			
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				
				$data = json_decode($response['body']);
				
				$tournament = $data->Tournament;
				$players = $data->Players;
	
				$is_over = $tournament->IsOver;
				
				if ($is_over == 0) {
					
					$name = $tournament->Name;
					
					$mixed_projections = json_decode(get_field('contest_data', $post->ID));
					$mixed_results = $mixed_projections;
					
					foreach ($players as $player) {
							
						$player_id = $player->PlayerID;
						
						$teamIndex = 0;
						
						foreach ($mixed_projections as $team) {
														
							$playerIndex = 0;
														
							foreach ($team as $team_player) {
											
								if ($playerIndex < 4) {
									
									if ($player_id == $team_player->PlayerID) {
										
										$mixed_results[$teamIndex]->$playerIndex->total_strokes = $player->TotalStrokes;
										$mixed_results[$teamIndex]->$playerIndex->eagles = ($player->DoubleEagles + $player->Eagles - $player->HoleInOnes);
										$mixed_results[$teamIndex]->$playerIndex->eagles_points = (($player->DoubleEagles + $player->Eagles - $player->HoleInOnes)*$eagles_VAL);
										$mixed_results[$teamIndex]->$playerIndex->birdies = $player->Birdies;
										$mixed_results[$teamIndex]->$playerIndex->birdies_points = $player->Birdies*$birdies_VAL;
										$mixed_results[$teamIndex]->$playerIndex->pars = $player->Pars;
										$mixed_results[$teamIndex]->$playerIndex->pars_points = $player->Pars*$pars_VAL;
										$mixed_results[$teamIndex]->$playerIndex->bogeys = $player->Bogeys;
										$mixed_results[$teamIndex]->$playerIndex->bogeys_points = $player->Bogeys*$bogeys_VAL;
										$mixed_results[$teamIndex]->$playerIndex->double_bogeys_or_worse = ($player->DoubleBogeys+$player->WorseThanDoubleBogey);
										$mixed_results[$teamIndex]->$playerIndex->double_bogeys_or_worse_points = (($player->DoubleBogeys+$player->WorseThanDoubleBogey)*$doublebogeyorworse_VAL);
										$mixed_results[$teamIndex]->$playerIndex->holes_in_one = $player->HoleInOnes;
										$mixed_results[$teamIndex]->$playerIndex->rank = $player->Rank;
										
										
										if ($player->Rank == 1) {
											$finish_points = $tourneyfinish_1_VAL;
										}
										else if ($player->Rank == 2) {
											$finish_points = $tourneyfinish_2_VAL;
										}
										else if ($player->Rank == 3) {
											$finish_points = $tourneyfinish_3_VAL;
										}
										else if ($player->Rank == 4) {
											$finish_points = $tourneyfinish_4_VAL;
										}
										else if ($player->Rank == 5) {
											$finish_points = $tourneyfinish_5_VAL;
										}
										else if ($player->Rank == 6) {
											$finish_points = $tourneyfinish_6_VAL;
										}
										else if ($player->Rank == 7) {
											$finish_points = $tourneyfinish_7_VAL;
										}
										else if ($player->Rank == 8) {
											$finish_points = $tourneyfinish_8_VAL;
										}
										else if ($player->Rank == 9) {
											$finish_points = $tourneyfinish_9_VAL;
										}
										else if ($player->Rank == 10) {
											$finish_points = $tourneyfinish_10_VAL;
										}
										else if ($player->Rank > 10 && $player->Rank < 16) {
											$finish_points = $tourneyfinish_11_15_VAL;
										}
										else if ($player->Rank > 15 && $player->Rank < 21) {
											$finish_points = $tourneyfinish_16_20_VAL;
										}
										else if ($player->Rank > 20 && $player->Rank < 26) {
											$finish_points = $tourneyfinish_21_25_VAL;
										}
										else if ($player->Rank > 25 && $player->Rank < 31) {
											$finish_points = $tourneyfinish_26_30_VAL;
										}
										else if ($player->Rank > 30 && $player->Rank < 41) {
											$finish_points = $tourneyfinish_31_40_VAL;
										}
										else if ($player->Rank > 40 && $player->Rank < 51) {
											$finish_points = $tourneyfinish_41_50_VAL;
										}
										else {
											$finish_points = 0;
										}									
										
										$mixed_results[$teamIndex]->$playerIndex->rank_points = $finish_points;
																				
										$total_points = (($player->DoubleEagles+$player->Eagles-$player->HoleInOnes)*$eagles_VAL) + ($player->Birdies*$birdies_VAL) + ($player->Pars*$pars_VAL) + ($player->Bogeys*$bogeys_VAL) + ($doublebogeyorworse_VAL*($player->DoubleBogeys+$player->WorseThanDoubleBogey)) + ($player->HoleInOnes*$holesinone_VAL) + $finish_points;
										
										$mixed_results[$teamIndex]->$playerIndex->total_points = $total_points;
										
										//echo $player->Name . ' updated<br>';
										
									}
									$playerIndex++;
								
								}
								
							}
							
							$teamIndex++;
							
						}
																		
					}
					
					//recalculate total points
					
					
					$teamIndex = 0;
					
					foreach ($mixed_results as $team) {
						
						$teamTotal = 0;
						
						$playerCount = 0;
						foreach ($team as $player) {
							
							if ($playerCount < 4) {
								$teamTotal += $player->total_points;
							}
							$playerCount++;
						}
						
						$mixed_results[$teamIndex]->total_points = $teamTotal;
						$mixed_results[$teamIndex]->team_name = 'Team ' . ($teamIndex+1);
						
						$teamIndex++;
						
					}
					
					$sort = array();
					foreach ($mixed_results as $key => $part) {
						$sort[$key] = $part->total_points;
					}
					array_multisort($sort, SORT_DESC, $mixed_results);
					
					
					// Update 6 winners
					for ($i = 0; $i < 6; $i++) {				
						
						update_post_meta($post->ID, 'mixed_winner_'.($i+1), $mixed_results[$i]->team_name);
						$projected_teams[$i]['team_name'] = $mixed_results[$i]->team_name;
						
					}	
					
						
					update_field('contest_results', json_encode($mixed_results, JSON_UNESCAPED_UNICODE), $post->ID);
					update_field('contest_live_stats_url', 'https://fly.sportsdata.io/golf/v2/json/Leaderboard/'.$tourney_id.'?key='.$stats_key, $post->ID);
					
					$updated_contests_count++;
						
				}
			
			}
			else {
				
				echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 10)</p></div>';
				
			}
		
		}
	
	}
	else {
		
		echo '<div id="message" class="updated fade error"><p>There are no PGA contests in progress.</p></div>';
		
	}
	
	wp_reset_query();
	
	echo '<div id="message" class="updated fade"><p>'.$updated_contests_count.' Live PGA contest(s) updated.</p></div>';
	
	$cron_log = array(
		'post_status' => 'draft',
		'post_title' => 'PGA Cron Log - Update Live',
		'post_type' => 'cron_log',
		'post_content' => date('m-d-Y g:i a', time() - (60*60*7)) . ' PT',
		'tax_input' => array (
			'cron_type' => 4300,
		),
	);
	
	wp_set_current_user(1);
	wp_insert_post( $cron_log );
	
}
	
	
// Process completed contests and wagers

function process_finished_pga_contests($stats_key) {
	
	
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
				'terms'    => 'pga',
			),
		),
	);
	
	$the_query = new WP_Query( $args );
				
	if ( $the_query->have_posts() ) {
		
		$tournament_data = array();
		$contest_dates = array();
		
		while ( $the_query->have_posts() ) {
			
			$the_query->the_post();
			global $post;
			
			$this_contest = array();
			$this_contest_date = get_field('contest_date');
			$this_contest['date'] = strtoupper(date('Y-M-d', $this_contest_date));
			$this_contest['date_time'] = $this_contest_date;
			$this_contest['id'] = $post->ID;
			$this_contest['tourney_id'] = get_field('contest_tournament_id', $post->ID);
			
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
			
		foreach ($contest_data as $tournament) {
				
			$tourney_id = $tournament['tourney_id'];
			$contest_id = $tournament['id'];
			
			$response = wp_remote_get( "https://fly.sportsdata.io/golf/v2/json/Leaderboard/$tourney_id", array(
				'method'	=> 'GET',
				'timeout'	=> 60,
			    'headers'	=> array(
			        'Ocp-Apim-Subscription-Key' => $stats_key,
			    ),
			) );
			
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				
				$data = json_decode($response['body']);
								
				$is_over = $data->Tournament->IsOver;				
						
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
		
		echo '<div id="message" class="updated fade error"><p>There are no PGA tournaments in progress. Please try again later. (Error Code 5)</p></div>';
		
	}
					
					
					
					
					
	if ($continue == true) { 
	
	
		// Update stats for completed contests
	
		update_live_pga_contests($stats_key, true);
	
	
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
		
		echo '<div id="message" class="updated fade error"><p>There are no finished PGA contests ready for processing. Please try again later. (Error Code 9)</p></div>';
		
	}
	
}


// Cron jobs

add_action( 'pga_update_live_cron', 'pga_update_live_cron' );

function pga_update_live_cron() {
	
	$stats_key = '44691bd017be4bdabcd8af9da127ae38';
	update_live_pga_contests($stats_key, false);
	
	/*
	$cron_log = array(
		'post_status' => 'draft',
		'post_title' => 'PGA Cron Log - Update Live',
		'post_type' => 'cron_log',
		'post_content' => date('m-d-Y g:i a', time() - (60*60*7)) . ' PT',
		'tax_input' => array (
			'cron_type' => 4300,
		),
	);
	
	wp_set_current_user(1);
	wp_insert_post( $cron_log );
	*/
	
}
	