<?php
	
//Register settings and options

function pariwagernbapoints() {
		
	register_setting( 'pariwager-nba-points', 'nba-points-two' );
	register_setting( 'pariwager-nba-points', 'nba-points-three' );
	register_setting( 'pariwager-nba-points', 'nba-points-free' );
	register_setting( 'pariwager-nba-points', 'nba-points-rebound' );
	register_setting( 'pariwager-nba-points', 'nba-points-assist' );
	register_setting( 'pariwager-nba-points', 'nba-points-steal' );
	register_setting( 'pariwager-nba-points', 'nba-points-block' );
	register_setting( 'pariwager-nba-points', 'nba-points-turnover' );

}
add_action( 'admin_init', 'pariwagernbapoints' );

// Local functions

function setNbaTeamValues($team,$home_away,$opponent_abbrev,$contest_player,$total_points,$contest_date_time,$type){

	$buffer_team = $team;
	$buffer_team->home_away = $home_away;
	$buffer_team->opponent = $buffer_team->TeamID;
	$buffer_team->opponent_abbrev = $opponent_abbrev;
	if($type == "C"){
		$buffer_team->players['Center'][] = $contest_player;
		$buffer_team->total_points_center = number_format($buffer_team->total_points_center + $total_points, 2);
	}
	if($type == "PG"){
		$buffer_team->players['Pointguard'][] = $contest_player;
		$buffer_team->total_points_pointguard = number_format($buffer_team->total_points_pointguard + $total_points, 2);
	}
	if($type == "PF"){
		$buffer_team->players['Powerforward'][] = $contest_player;
		$buffer_team->total_points_powerforward = number_format($buffer_team->total_points_powerforward + $total_points, 2);
	}
	if($type == "SG"){
		$buffer_team->players['Shootingguard'][] = $contest_player;
		$buffer_team->total_points_shootingguard = number_format($buffer_team->total_points_shootingguard + $total_points, 2);
	}
	if($type == "SF"){
		$buffer_team->players['Smallforward'][] = $contest_player;
		$buffer_team->total_points_smallforward = number_format($buffer_team->total_points_smallforward + $total_points, 2);
	}
	if($type == "Team"){
		$buffer_team->players['Team'][] = $contest_player;
		$buffer_team->total_points_team = number_format($buffer_team->total_points_team + $total_points, 2);
	}
	$buffer_team->total_points = number_format($buffer_team->total_points + $total_points, 2);
	$buffer_team->game_start = $contest_date_time;
	$buffer_team->game_id = $buffer_team->GlobalGameID;
								
	if ($total_points > 0) {
		$buffer_team->players['starters']['SP'] = $contest_player;
	}

	return $buffer_team;
}

function setNbaPlayerValuesto0($player){

	$buffer_player = $player;

	$buffer_player->twopoints = 0;
	$buffer_player->two_points = 0;
	$buffer_player->threepoints = 0;
	$buffer_player->three_points = 0;
	$buffer_player->freepoints = 0;
	$buffer_player->free_points = 0;
	$buffer_player->rebounds = 0;
	$buffer_player->rebounds_points = 0;
	$buffer_player->assists = 0;
	$buffer_player->assists_points = 0;
	$buffer_player->steals = 0;
	$buffer_player->steals_points = 0;
	$buffer_player->blocks = 0;
	$buffer_player->blocks_points = 0;
	$buffer_player->turnovers = 0;
	$buffer_player->turnovers_points = 0;
	$buffer_player->total_points = 0;
	$buffer_player->is_game_over = 0;

	return $buffer_player;
									
}


function giveNbaPlayerPointsValue($player,$stats_player){

	$buffer_player = $player;

	$twopoints_VAL = get_option('nba-points-two');
	$threepoints_VAL = get_option('nba-points-three');
	$freepoints_VAL = get_option('nba-points-free');
	$rebounds_VAL = get_option('nba-points-rebound');
	$assists_VAL = get_option('nba-points-assist');
	$steals_VAL = get_option('nba-points-steal');
	$blocks_VAL = get_option('nba-points-block');
	$turnovers_VAL = get_option('nba-points-turnover');
	

	$buffer_player->twopoints = $stats_player->TwoPointersMade;
	$player->two_points = number_format($buffer_player->twopoints*$twopoints_VAL, 2);
	$buffer_player->threepoints = $stats_player->ThreePointersMade;
	$player->three_points = number_format($buffer_player->threepoints*$threepoints_VAL, 2);
	$buffer_player->freepoints = $stats_player->FreeThrowsMade;
	$player->free_points = number_format($buffer_player->freepoints*$freepoints_VAL, 2);
	$buffer_player->rebounds = $stats_player->Rebounds;
	$player->rebounds_points = number_format($buffer_player->rebounds*$rebounds_VAL, 2);
	$buffer_player->assists = $stats_player->Assists;
	$player->assists_points = number_format($buffer_player->assists*$assists_VAL, 2);
	$buffer_player->steals = $stats_player->Steals;
	$player->steals_points = number_format($buffer_player->steals*$steals_VAL, 2);
	$buffer_player->blocks = $stats_player->Blocks;
	$player->blocks_points = number_format($buffer_player->blocks*$blocks_VAL, 2);
	$buffer_player->turnovers = $stats_player->Turnovers;
	$buffer_player->turnovers_points = number_format($buffer_player->turnovers*$turnovers_VAL, 2);
	$buffer_player->is_game_over = $stats_player->IsGameOver;
	   
	$buffer_player->total_points = $buffer_player->two_points + $buffer_player->three_points + $buffer_player->free_points + $buffer_player->rebounds_points + $buffer_player->assists_points + $buffer_player->steals_points + $buffer_player->blocks_points + $buffer_player->turnovers_points;

	return $buffer_player;
									
}

//Create nba Projections and Contests

function create_nba_projections_and_contests2($date, $projection_key) {
	
	//Define vars
	
	$mixed_center = array();
	$mixed_pointguard = array();
	$mixed_powerforward = array();
	$mixed_shootingguard = array();
	$mixed_smallforward = array();
	
	$parent_team = 41;
	$tax_league = 2;
	$league_title = 'NBA';
	
	$twopoints_VAL = get_option('nba-points-two');
	$threepoints_VAL = get_option('nba-points-three');
	$freepoints_VAL = get_option('nba-points-free');
	$rebounds_VAL = get_option('nba-points-rebound');
	$assists_VAL = get_option('nba-points-assist');
	$steals_VAL = get_option('nba-points-steal');
	$blocks_VAL = get_option('nba-points-block');
	$turnovers_VAL = get_option('nba-points-turnover');

	//Get player projections via SportsDataIO API
	$projections_url = "https://fly.sportsdata.io/v3/nba/projections/json/PlayerGameProjectionStatsByDate/$date";

	$response = wp_remote_get( $projections_url, array(
		'method'	=> 'GET',
		'timeout'	=> 60,
	    'headers'	=> array(
	        'Ocp-Apim-Subscription-Key' => $projection_key,
	    ),
	) );

	if ( is_array( $response ) && ! is_wp_error( $response ) ) {

		$data = json_decode($response['body']);
		
		//Prepare team arrays
	
		$args = array(
			'taxonomy' => 'team',
			'hide_empty' => false,
			'child_of' => $parent_team,
		);
		
		$all_teams = get_terms($args);

		// HANDLE DOUBLEHEADERS
		
		// 1. Remove teams that are not playing on this date
		// 2. Add game_1 and game_2 values to each team
		
		$team_key = 0;

		foreach ($all_teams as $team) {
			
			$team_id = get_term_meta( $team->term_id, 'TeamID', true );
			$team_abbrev = get_term_meta( $team->term_id, 'team_abbreviation', true );
			
			$team->TeamID = $team_id;
			$team->team_abbrev = $team_abbrev;
			$team->total_points = 0;
			$team->total_points_center = 0;
			$team->total_points_pointguard = 0;
			$team->total_points_powerforward = 0;
			$team->total_points_shootingguard = 0;
			$team->total_points_smallforward = 0;

			$team->players = array('center', 'pointguard', 'powerforward', 'shootingguard', 'smallforward');

			$playing_today = false;
			
			foreach ($data as $player) {
				   
				if ($player->Team == $team_abbrev) {
					
					if (isset($team->game_1)) {
						if ($player->GlobalGameID != $team->game_1) {
							$team->game_2 = $player->GlobalGameID;
						}
					}
					else {
						$team->game_1 = $player->GlobalGameID;
					}

					$playing_today = true;
					
				}
				
			}
			
			if ($playing_today == false) {
				
				unset($all_teams[$team_key]);
				
			}
			
			$team_key++;
			
		}
		
		$all_teams = array_values($all_teams);

		// 3. If a team has a doubleheader, append '(Game 1)' to team name and remove the game with the higher value
		
		foreach ($all_teams as $team) {
			
			if (isset($team->game_2)) {
				
				$team->name = $team->name . ' (Game 1)';
				
				if ($team->game_2 > $team->game_1) {
					$team->game_2 = '';
				}
				else {
					$team->game_1 = '';
				}
				
			}
			
		}

		foreach ($data as $player) {

			$count = 0;
		    
			$contest_date_time = $player->DateTime;
			$contest_date = date('Y-m-d', strtotime($player->DateTime)); 
			$opponent = $player->GlobalOpponentID;
			$opponent_abbrev = $player->Opponent;
			$home_away = strtolower($player->HomeOrAway);
			$position = $player->Position;
			$gameID = $player->GlobalGameID;
				
			$twopoints = $player->TwoPointersMade;
			$threepoints = $player->ThreePointersMade;
			$freepoints = $player->FreeThrowsMade;
			$rebounds = $player->Rebounds;
			$assists = $player->Assists;
			$steals = $player->Steals;
			$blocks = $player->BlockedShots;
			$turnovers = $player->Turnovers;
			
			$total_pts = $twopoints + $threepoints + $freepoints + $rebounds + $assists + $steals + $blocks + $turnovers;
		    
			if (isset($cutoff_datetime) == false) {
				$cutoff_datetime = $contest_date_time;
			}
			else if ($contest_date_time < $cutoff_datetime) {
				$cutoff_datetime = $contest_date_time; 
			}										

			$total_points = ($twopoints*$twopoints_VAL) + ($threepoints*$threepoints_VAL) + ($freepoints*$freepoints_VAL) + ($rebounds*$rebounds_VAL) + ($assists*$assists_VAL) + ($steals*$steals_VAL) + ($blocks*$blocks_VAL) + ($turnovers*$turnovers_VAL);
				
			$total_points = number_format($total_points, 2);

			$contest_player = array(
				'name' => $player->Name,
				'player_id' => $player->PlayerID,
				'team_id' => $player->GlobalTeamID,
				'opponent_id' => $opponent,
				'opponent_abbrev' => $opponent_abbrev,
				'home_away' => $home_away,
				'position' => $position,
				'game_start_et' => $contest_date_time,
				'twopoints' => $twopoints,
				'two_points' => $twopoints*$twopoints_VAL,
				'threepoints' => $threepoints,
				'three_points' => $threepoints*$threepoints_VAL,
				'freepoints' => $freepoints,
				'free_points' => $freepoints*$freepoints_VAL,
				'rebounds' => $rebounds,
				'rebounds_points' => $rebounds*$rebounds_VAL,
				'assists' => $assists,
				'assists_points' => $assists*$assists_VAL,
				'steals' => $steals,
				'steals_points' => $steals*$steals_VAL,
				'blocks' => $blocks,
				'blocks_points' => $blocks*$blocks_VAL,
				'turnovers' => $turnovers,
				'turnovers_points' => $turnovers*$turnovers,
				'game_id' => $gameID,
				'total_points' => $total_points,
				'is_game_over' => 0
				);
									
				//add to Teams array
				if ($position == 'C') {
					foreach ($all_teams as $team) {
						if ($team->TeamID == $player->GlobalTeamID) {
							if ($gameID == $team->game_1 || $gameID == $team->game_2) {
								$team = setNbaTeamValues($team,$home_away,$opponent_abbrev,$contest_player,$total_points,$contest_date_time,$position); 
								$mixed_center[] = $contest_player;
							}
						}
					}
				}
				
				if($position == 'PG'){
					foreach ($all_teams as $team) {
						if ($team->TeamID == $player->GlobalTeamID) {
							if ($gameID == $team->game_1 || $gameID == $team->game_2) {
								$team = setNbaTeamValues($team,$home_away,$opponent_abbrev,$contest_player,$total_points,$contest_date_time,$position);
								$mixed_pointguard[] = $contest_player;
							}
						}
					}
				}

				if($position == 'PF'){
					foreach ($all_teams as $team) {
						if ($team->TeamID == $player->GlobalTeamID) {
							if ($gameID == $team->game_1 || $gameID == $team->game_2) {
								$team = setNbaTeamValues($team,$home_away,$opponent_abbrev,$contest_player,$total_points,$contest_date_time,$position); 
								$mixed_powerforward[] = $contest_player; 
							}
						}
					}
				}

				if($position == 'SG'){
					foreach ($all_teams as $team) {
						if ($team->TeamID == $player->GlobalTeamID) {
							if ($gameID == $team->game_1 || $gameID == $team->game_2) {
								$team = setNbaTeamValues($team,$home_away,$opponent_abbrev,$contest_player,$total_points,$contest_date_time,$position); 
								$mixed_shootingguard[] = $contest_player; 
							}
						}
					}
				}

				if($position == 'SF'){
					foreach ($all_teams as $team) {
						if ($team->TeamID == $player->GlobalTeamID) {
							if ($gameID == $team->game_1 || $gameID == $team->game_2) {
								$team = setNbaTeamValues($team,$home_away,$opponent_abbrev,$contest_player,$total_points,$contest_date_time,$position);
								$mixed_smallforward[] = $contest_player;
							}
						}
					}
				}

		}

		// Sort Teams by total points
			
		$sort = array();
		foreach ($all_teams as $key => $part) {
			$sort[$key] = $part->total_points;
		}
		array_multisort($sort, SORT_DESC, $all_teams);

		//Add odds to each team
			
		$all_teams_no_odds = $all_teams;
			
		$i = 2;
		foreach ($all_teams as $team) {
			
			$team->odds_to_1 = $i; 
			$i++; 
			
		}
		//Build Mixed Teams
			
			
		//Calculate maximum mixed-team count
		
		$mixed_team_count = count($mixed_center);
		
		if (count($mixed_center) < $mixed_team_count) {
		    $mixed_team_count = count($mixed_center);
		}
		if (count($mixed_pointguard) < $mixed_team_count) {
		    $mixed_team_count = count($mixed_pointguard);
		}
		if (count($mixed_powerforward) < $mixed_team_count) {
		    $mixed_team_count = count($mixed_powerforward);
		}
		if (count($mixed_shootingguard) < $mixed_team_count) {
		    $mixed_team_count = count($mixed_shootingguard);
		}
		if (count($mixed_smallforward) < $mixed_team_count) {
		    $mixed_team_count = count($mixed_smallforward);
		}

		//Sort position groups by total points
		      
		$sort = array();
		foreach ($mixed_center as $key => $part) {
			$sort[$key] = $part['total_points'];
		}
		array_multisort($sort, SORT_DESC, $mixed_center);
		
		$sort = array();
		foreach ($mixed_pointguard as $key => $part) {
			$sort[$key] = $part['total_points']; 
		}
		array_multisort($sort, SORT_DESC, $mixed_pointguard);
		
		$sort = array();
		foreach ($mixed_powerforward as $key => $part) {
			$sort[$key] = $part['total_points'];
		}
		array_multisort($sort, SORT_DESC, $mixed_powerforward);
		 
		$sort = array();
		foreach ($mixed_shootingguard as $key => $part) {
			$sort[$key] = $part['total_points'];
		}
		array_multisort($sort, SORT_DESC, $mixed_shootingguard);

		$sort = array();
		foreach ($mixed_smallforward as $key => $part) { 
			$sort[$key] = $part['total_points'];
		}
		array_multisort($sort, SORT_DESC, $mixed_smallforward);

		$mixed_teams = array();
		    
		if ($mixed_team_count >= 25) {
		    $mixed_team_count = 25;
		}
				    	    
		for ($i = 0; $i < $mixed_team_count; $i++) {
			
			$mix_team = array(
				'team_name' => 'Team ' . ($i+1),
				'center' => $mixed_center[$i],
				'pointguard' => $mixed_pointguard[$i],
				'powerforward' => $mixed_powerforward[$i],
				'shootingguard' => $mixed_shootingguard[$i],
				'smallforward' => $mixed_smallforward[$i],
				'total_points' => 0,
				'odds_to_1' => (int) ($i+2),
			);
						
			$mixed_teams[] = $mix_team;
						
		} 
		    
		$i = 0;
		    
		foreach ($mixed_teams as $mix_team) {
		    
		    $total_points = $mix_team['center']['total_points'] + $mix_team['pointguard']['total_points'] + $mix_team['powerforward']['total_points'] + $mix_team['shootingguard']['total_points'] + $mix_team['smallforward']['total_points'] + $mix_team['team']['total_points'];
		    
		    $mixed_teams[$i]['total_points'] = number_format($total_points, 2);
		    
		    $i++;
		    
		}

		// BUILD CONTESTS
		
		if (isset($cutoff_datetime)) {
		
			$the_contest_datetime = strtoupper(date('m-d-Y g:i a', strtotime($cutoff_datetime) - 60 * 60 * 3));
			$the_contest_date_unix = strtoupper(date('U', strtotime($cutoff_datetime) - 60 * 60 * 3));
			$the_contest_date_notime = date('m-d-Y', strtotime($cutoff_datetime) - 60 * 60 * 3);
			$the_contest_date_notime_day = date('l F jS', strtotime($cutoff_datetime) - 60 * 60 * 3);
			$the_contest_date_sort = strtoupper(date('Y-m-d H:i:s', strtotime($cutoff_datetime) - 60 * 60 * 3));
			$contests_created = 0;
					
			//Build Team vs Field Contests

			$projections_url .= '?key='.$projection_key;
			
			$teams_contest = array(
				'post_status' => 'publish',
				'post_title' => $league_title . ': Teams ' . $the_contest_date_notime,
				'post_type' => 'contest',
				'meta_input' => array (
					'contest_type' => 'Teams',
					'contest_date' => $the_contest_date_unix,
					'contest_date_sort' => $the_contest_date_sort,
					'contest_status' => 'Open',
					'contest_data' => json_encode($all_teams, JSON_UNESCAPED_UNICODE),
					'contest_title_without_type' => $league_title . ' Teams: ' . $the_contest_date_notime_day,
					'contest_projections_url' => $projections_url
				),
				'tax_input' => array (
					'league' => $tax_league,
				),
			);
			
			$post_exists = post_exists($league_title . ': Teams ' . $the_contest_date_notime);
					
			//Build Mixed vs Field Contests
			
			$mixed_contest = array(
				'post_status' => 'publish',
				'post_title' => $league_title . ': Mixed ' . $the_contest_date_notime,
				'post_type' => 'contest',
				'meta_input' => array (
					'contest_type' => 'Mixed',
					'contest_date' => $the_contest_date_unix,
					'contest_date_sort' => $the_contest_date_sort,
					'contest_status' => 'Open',
					'contest_data' => json_encode($mixed_teams, JSON_UNESCAPED_UNICODE),
					'contest_title_without_type' => $league_title . ' Mixed: ' . $the_contest_date_notime_day,
					'contest_projections_url' => $projections_url
				),
				'tax_input' => array (
					'league' => $tax_league,
				),
			);
			
			$post_exists = post_exists($league_title . ': Mixed ' . $the_contest_date_notime); 
			
			//Build Team vs Team Contests
			
			$games = array();
			$teams = array();
			
			foreach ($all_teams_no_odds as $team) {
				
				unset($team->odds_to_1);
						
				if (!in_array($team->team_abbrev, $teams) && !in_array($team->opponent_abbrev, $teams)) {
				
					$game = array();
					
					$teams[] = $team->team_abbrev;
					$game[] = $team;
					
					$opponent = $team->opponent_abbrev;
					
					foreach ($all_teams_no_odds as $team1) {
						
						if ($team1->team_abbrev == $opponent) {
							
							if ($team1->total_points > $team->total_points) {
								
								// team1 is favorite; team is underdog
								
								$team1->internal_spread = number_format($team1->total_points - $team->total_points, 2);
								$team->internal_spread = number_format($team->total_points - $team1->total_points, 2);
								$team1->opening_spread = round(number_format($team1->total_points - $team->total_points, 2)* 2) / 2;
								$team->opening_spread = round(number_format($team->total_points - $team1->total_points, 2)* 2) / 2;
								$team1->current_spread = $team1->opening_spread;
								$team->current_spread = $team->opening_spread;
								
								$team1->opening_moneyline = round(number_format(($team1->total_points*3.65)*-1,2));
								$team->opening_moneyline = round(number_format($team->total_points*2.85,2));
								$team1->current_moneyline = round(number_format(($team1->total_points*3.65)*-1,2));
								$team->current_moneyline = round(number_format($team->total_points*2.85,2));
								
								$overunder = round(number_format(($team->total_points + $team1->total_points), 2)* 2) / 2;
								
								$team->opening_overunder = $overunder;
								$team1->opening_overunder = $overunder;
								$team->current_overunder = $overunder;
								$team1->current_overunder = $overunder;
								
								$team1->game_id = $team1->game_1;
								$team->game_id = $team->game_1;
								
							}
							else {
								
								// team1 is underdog; team is favorite
								
								$team1->internal_spread = number_format($team->total_points - $team1->total_points, 2);
								$team->internal_spread = number_format($team1->total_points - $team->total_points, 2);
								$team1->opening_spread = round(number_format($team->total_points - $team1->total_points, 2)* 2) / 2;
								$team->opening_spread = round(number_format($team1->total_points - $team->total_points, 2)* 2) / 2;
								$team1->current_spread = $team1->opening_spread;
								$team->current_spread = $team->opening_spread;
								
								$team1->opening_moneyline = round(number_format($team1->total_points*2.85,2));
								$team->opening_moneyline = round(number_format(($team->total_points*3.65)*-1,2));
								$team1->current_moneyline = round(number_format($team1->total_points*2.85,2));
								$team->current_moneyline = round(number_format(($team->total_points*3.65)*-1,2));
								
								$overunder = round(number_format(($team->total_points + $team1->total_points), 2)* 2) / 2;
								
								$team->opening_overunder = $overunder;
								$team1->opening_overunder = $overunder;
								$team->current_overunder = $overunder;
								$team1->current_overunder = $overunder;
								
								$team1->game_id = $team1->game_1;
								$team->game_id = $team->game_1;

							}
							
							$game[] = $team1; 
							$games[] = $game;
							
						}
						
					}
					
				}
			
			}
			
			$teams_contest = array(
				'post_status' => 'publish',
				'post_title' => $league_title . ': Game Lines ' . $the_contest_date_notime,
				'post_type' => 'contest',
				'meta_input' => array (
					'contest_type' => 'Team vs Team',
					'contest_date' => $the_contest_date_unix,
					'contest_date_sort' => $the_contest_date_sort,
					'contest_status' => 'Open',
					'contest_data' => json_encode($games, JSON_UNESCAPED_UNICODE),
					'contest_title_without_type' => $league_title . ' Game Lines: ' . $the_contest_date_notime_day,
					'contest_projections_url' => $projections_url
				),
				'tax_input' => array (
					'league' => $tax_league,
				),
			);
			 
			$post_exists = post_exists($league_title . ': Game Lines ' . $the_contest_date_notime); 
					
			if ($post_exists == 0) {
				wp_insert_post( $teams_contest );
				$contests_created++;
				
			}

		} else {
			$contests_created = 0;
		}
		
		// echo '<div id="message" class="updated fade"><p>'.$contests_created.' NBA contests created.</p></div>';

	} else {
	
		echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 1)</p></div>';
	
	}
} 

//New Create function
function create_nba_projections_and_contests($date, $projection_key) {
					
	//Define vars
	$parent_team = 41;
	$tax_league = 2;
	$league_title = 'NBA';
	$contests_created = 0;
	$game_data = array();
	
	//Prepare team arrays
		
	$args = array(
		'taxonomy' => 'team',
		'hide_empty' => false,
		'child_of' => $parent_team,
	);

	$all_teams = get_terms($args);  

	foreach ($all_teams as $team) {
		$team->TeamID = get_term_meta( $team->term_id, 'TeamID', true );
		$team->team_abbrev = get_term_meta( $team->term_id, 'team_abbreviation', true );
	}
	
	//Get player projections via SportsDataIO API
	$projections_url = "https://fly.sportsdata.io/v3/".strtolower($league_title)."/projections/json/PlayerGameProjectionStatsByDate/$date?key=".$projection_key;
	$projections_team_url = "https://fly.sportsdata.io/v3/".strtolower($league_title)."/projections/json/DfsSlatesByDate/$date?key=".$projection_key;

	$content_response = wp_remote_get( $projections_url, array(
		'method'	=> 'GET',
		'timeout'	=> 60,
		'headers'	=> array(
			'Ocp-Apim-Subscription-Key' => $projection_key,
		),
	) );
	$content_team_response = wp_remote_get( $projections_team_url, array(
		'method'	=> 'GET',
		'timeout'	=> 60,
		'headers'	=> array(
			'Ocp-Apim-Subscription-Key' => $projection_key,
		),
	) );

	if ( is_array( $content_response ) && ! is_wp_error( $content_response ) && is_array( $content_team_response ) && ! is_wp_error( $content_team_response ) ) {
		
		$content = json_decode($content_response['body']);	
		$content_team = json_decode($content_team_response['body']);

		$contest_all_teams = array();
		$team_information_data = array();
		$team_players = array();
		$contest_player_types = array();

		foreach($content as $player){
			if(!in_array($player->Team,$contest_all_teams)){
				array_push($contest_all_teams,$player->Team);
			}
		}

		foreach($contest_all_teams as $current_team){

			foreach($content as $player){

				if($current_team == $player->Team && !array_key_exists($current_team,$team_information_data)){

					foreach ($all_teams as $team_cat) {

						if ($team_cat->team_abbrev == $player->Team) {

							$team_information_data[$current_team] = array(
								'game_id' => $player->GlobalGameID,
								'game_start' => $player->DateTime,
								'is_game_over' => $player->IsGameOver,
								"term_id" =>  $team_cat->term_id,
								"TeamID" => $player->GlobalTeamID,
								"name" => $team_cat->name,
								"slug" => $team_cat->slug,
								"term_taxonomy_id" => $team_cat->term_taxonomy_id,
								"taxonomy" => "team",
								"parent" => $team_cat->parent,
								"team_abbrev" => $player->Team,
								"home_away" => strtolower($player->HomeOrAway),
								"opponent" => $player->GlobalOpponentID,
								"opponent_abbrev" => $player->Opponent
							);
							break;

						}
					}									
				}						

			}

		}

		foreach($content as $player){
			if(!in_array($player->Position,$contest_player_types)){
				array_push($contest_player_types,$player->Position);
			}
		}

		foreach($contest_all_teams as $current_team){

			$current_team_players = array();

			foreach ($contest_player_types as $player_type) {
				${'player_'.$player_type} = 0;
			}

			foreach($content as $player){
				if($current_team == $player->Team){
					foreach ($contest_player_types as $player_type) {
						if($player->Position == $player_type){
							${'player_'.$player_type} += $player->FantasyPointsDraftKings;
						}
					}
				}						
			}

			foreach ($contest_player_types as $player_type) {
				$current_team_players[$player_type] = ${'player_'.$player_type};					
			}

			$team_players[$current_team] = $current_team_players;

		}

		foreach($contest_all_teams as $current_team){

			$play_total_points = 0;
			
			foreach($team_players[$current_team] as $points){
				$play_total_points += $points;
			}

			$team_information_data[$current_team]['total_points'] = $play_total_points;
			$team_information_data[$current_team]['player'] = $team_players[$current_team];
			
		}

		foreach($team_information_data as $team_1){
				
			$current_game = array();
			$team1_spread = 0;
			$team2_spread = 0;
			$team_overunder = 0;
			$team1_moneyline = 0;
			$team2_moneyline = 0;

			if($team_1['home_away'] == "away"){

				foreach($team_information_data as $team_2){

					if($team_1['game_id'] == $team_2['game_id']){
						if($team_2['home_away'] == "home"){

							foreach($content_team as $ct_team){
								foreach($ct_team->DfsSlateGames as $dfsslategames){
									if($dfsslategames->Game->GlobalGameID == $team_1['game_id']){
										$team1_spread = $dfsslategames->Game->PointSpread;
										$team2_spread = $dfsslategames->Game->PointSpread;
										$team_overunder = $dfsslategames->Game->OverUnder;
										$team1_moneyline = $dfsslategames->Game->AwayTeamMoneyLine;
										$team2_moneyline = $dfsslategames->Game->HomeTeamMoneyLine;

										if($team2_moneyline > 0)
										{
											$team1_spread = -(abs($team1_spread));
											$team2_spread = (abs($team2_spread));
										}else{
											$team1_spread = (abs($team1_spread));
											$team2_spread = -(abs($team2_spread));
										}
										break;
									}
								}
							}


							$current_game = array(
								'game_id' => $team_1['game_id'],
								'game_start' => $team_1['game_start'],
								'is_game_over' => $team_1['is_game_over'],
								'player_types' => $contest_player_types,
								'team1' => array(
									"term_id" =>  $team_1['term_id'],
									"TeamID" => $team_1['TeamID'],
									"name" => $team_1['name'],
									"slug" => $team_1['slug'],
									"term_taxonomy_id" => $team_1['term_taxonomy_id'],
									"taxonomy" => "team",
									"parent" => $team_1['parent'],
									"team_abbrev" => $team_1['team_abbrev'],
									"home_away" => $team_1['home_away'],
									"opponent" => $team_1['opponent'],
									"opponent_abbrev" => $team_1['opponent_abbrev'],
									"total_points" => $team_1['total_points'],
									"spread" => $team1_spread,
									"overunder" => $team_overunder,
									"moneyline" => $team1_moneyline,
									"player" => $team_1['player'],
								),
								'team2' => array(
									"term_id" =>  $team_2['term_id'],
									"TeamID" => $team_2['TeamID'],
									"name" => $team_2['name'],
									"slug" => $team_2['slug'],
									"term_taxonomy_id" => $team_2['term_taxonomy_id'],
									"taxonomy" => "team",
									"parent" => $team_2['parent'],
									"team_abbrev" => $team_2['team_abbrev'],
									"home_away" => $team_2['home_away'],
									"opponent" => $team_2['opponent'],
									"opponent_abbrev" => $team_2['opponent_abbrev'],
									"total_points" => $team_2['total_points'],
									"spread" => $team2_spread,
									"overunder" => $team_overunder,
									"moneyline" => $team2_moneyline,
									"player" => $team_2['player'],
								),
							);

							array_push($game_data,$current_game);

						}
					}

				}

			}

		}

		$sort_games = array();
		foreach ($game_data as $key => $part) {
			$sort[$key] = strtotime($part['game_start']);
		}
		array_multisort($sort, SORT_ASC, $game_data);

		$the_contest_date_notime = date('m-d-Y', strtotime($game_data[0]['game_start']));
		$the_contest_date_notime_day = date('l F jS', strtotime($game_data[0]['game_start']));
		$the_contest_date_unix = strtoupper(date('U', strtotime($game_data[0]['game_start'])));
		$the_contest_date_sort = strtoupper(date('Y-m-d H:i:s', strtotime($game_data[0]['game_start'])));

		$teams_contest = array(
			'post_status' => 'publish',
			'post_title' => $league_title . ': Game Lines ' . $the_contest_date_notime,
			'post_type' => 'contest',
			'meta_input' => array (
				'contest_type' => 'Team vs Team',
				'contest_date' => $the_contest_date_unix,
				'contest_date_sort' => $the_contest_date_sort,
				'contest_status' => 'Open',
				'contest_data' => json_encode($game_data, JSON_UNESCAPED_UNICODE),
				'contest_title_without_type' => $league_title . ': Game Lines ' . $the_contest_date_notime_day,
				'contest_projections_url' => $projections_url,
				'games_status' => "Not Done"
			),
			'tax_input' => array (
				'league' => $tax_league
			),
		);
		
		$post_exists = post_exists($league_title . ': Game Lines ' . $the_contest_date_notime);
				
		if ($post_exists == 0) {
			wp_insert_post( $teams_contest );
			$contests_created++;
		}
	
		echo '<div id="message" class="updated fade"><p>'.$contests_created.' '.$league_title.' contests created.</p></div>';

	}else {
	
		echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 1)</p></div>';
	
	}	

}

//New Update Function
function update_nba_live_scores($stats_key) {
					
	//Define vars
	$parent_team = 41;
	$tax_league = 2;
	$league_title = 'NBA';
	$contests_created = 0;
	$game_data = array();

	//Prepare team arrays
		
	$args = array(
		'taxonomy' => 'team',
		'hide_empty' => false,
		'child_of' => $parent_team,
	);

	$all_teams = get_terms($args);  

	foreach ($all_teams as $team) {
		$team->TeamID = get_term_meta( $team->term_id, 'TeamID', true );
		$team->team_abbrev = get_term_meta( $team->term_id, 'team_abbreviation', true );
	}			
	
	$update_args = array(
		'post_type' => 'contest',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key'     => 'games_status',
				'value'   => "Not Done"
			)
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'league',
				'field'    => 'slug',
				'terms'    => strtolower($league_title)
			)
		),
	);
	
	$update_query = new WP_Query( $update_args );

	foreach($update_query->posts as $updatepost){
		$current_contest_date = get_field('contest_date',$updatepost->ID);

		if($current_contest_date <= current_time( 'timestamp')){
			$date = date('Y-M-d',$current_contest_date);

			//Get player stats via SportsDataIO API
			$stats_url = "https://fly.sportsdata.io/v3/".strtolower($league_title)."/stats/json/BoxScores/$date?key=".$stats_key;
		
			$content_response = wp_remote_get( $stats_url, array(
				'method'	=> 'GET',
				'timeout'	=> 60,
				'headers'	=> array(
					'Ocp-Apim-Subscription-Key' => $stats_key,
				),
			) );

			if ( is_array( $content_response ) && ! is_wp_error( $content_response ) ) {

				$contents = json_decode($content_response['body']);	
				
				foreach($contents as $content){
		
					$team1 = array();
					$team2 = array();
					$team_1_players = array();
					$team_2_players = array();
					$contest_player_types = array();
					$current_game = array();
		
					foreach ($content->TeamGames as $player) {
		
						if(empty($team1) || empty($team2)){
		
							foreach ($all_teams as $team_cat) {
		
								$team_information = array();
														
								if ($team_cat->team_abbrev == $player->Team) {
									$team_information = array(
										"term_id" =>  $team_cat->term_id,
										"TeamID" => $player->GlobalTeamID,
										"name" => $team_cat->name,
										"slug" => $team_cat->slug,
										"term_taxonomy_id" => $team_cat->term_taxonomy_id,
										"taxonomy" => "team",
										"parent" => $team_cat->parent,
										"team_abbrev" => $player->Team,
										"home_away" => strtolower($player->HomeOrAway),
										"opponent" => $player->GlobalOpponentID,
										"opponent_abbrev" => $player->Opponent,
										"total_points" => $player->FantasyPointsDraftKings,
									);
									break;
								}
							}
		
							if($player->HomeOrAway == "AWAY"){
								$team1 = $team_information;
							}else{
								$team2 = $team_information;
							}
						}
					}		

					foreach ($content->PlayerGames as $player) {
						
						if(!in_array($player->Position,$contest_player_types)){
							array_push($contest_player_types,$player->Position);
						}
						
					}
					
					foreach ($contest_player_types as $player_type) {
						
						${'players_'.$player_type.'1'} = 0;
						${'players_'.$player_type.'2'} = 0;
						
					}
		
					foreach ($content->PlayerGames as $player) {
						
						if($player->Team == $team1['team_abbrev']){
		
							foreach ($contest_player_types as $player_type) {
		
								if($player->Position == $player_type){
		
									${'players_'.$player_type.'1'} += $player->FantasyPointsFantasyDraft;
		
								}
		
		
							}
		
						}	
						
						if($player->Team == $team2['team_abbrev']){
		
							foreach ($contest_player_types as $player_type) {
		
								if($player->Position == $player_type){
		
									${'players_'.$player_type.'2'} += $player->FantasyPointsFantasyDraft;
		
								}
		
		
							}
		
						}	
					}
		
					

					foreach ($contest_player_types as $player_type) {
		
						$team_1_players[$player_type] = ${'players_'.$player_type.'1'};
						$team_2_players[$player_type] = ${'players_'.$player_type.'2'};
						
					}

					
					$contest_date_time = $content->Game->DateTime;
					$gameID = $content->Game->GlobalGameID;
					$contest_game_over = $content->Game->IsClosed;
					$team1['overunder'] = $team2['overunder'] = $content->Game->OverUnder;
					$team1['moneyline'] = $content->Game->AwayTeamMoneyLine;
					$team2['moneyline'] = $content->Game->HomeTeamMoneyLine;
					if($team2['moneyline'] > 0)
					{
						$team1['spread'] = -(abs($content->Game->PointSpread));
						$team2['spread'] = (abs($content->Game->PointSpread));
					}else{
						$team1['spread'] = (abs($content->Game->PointSpread));
						$team2['spread'] = -(abs($content->Game->PointSpread));
					}															
		
					$team1['player'] = $team_1_players;
					$team2['player'] = $team_2_players;
					
					
					$current_game['game_id'] = $gameID;
					$current_game['game_start'] = $contest_date_time;
					$current_game['is_game_over'] = $contest_game_over;
					$current_game['player_types'] = $contest_player_types;
					$current_game['team1'] = $team1;
					$current_game['team2'] = $team2;
					
					array_push($game_data,$current_game);
		
				}		
				
				$game_done = "Done";
				
				foreach($game_data as $game){
					if($game['is_game_over'] == "" || !$game['is_game_over'] || $game['is_game_over'] == 0){
						$game_done = "Not Done";
					}
				}
				update_field('contest_results', json_encode($game_data, JSON_UNESCAPED_UNICODE), $updatepost->ID);
				update_field('games_status', $game_done, $updatepost->ID);
				update_field('contest_status', 'In Progress', $updatepost->ID);
				update_field('contest_live_stats_url', $stats_url, $updatepost->ID);
			
			}

		}

	}

}


//Update Live Contests

function update_live_nba_contests($stats_key, $completed) {
	
	$twopoints_VAL = get_option('nba-points-two'); 
	$threepoints_VAL = get_option('nba-points-three'); 
	$freepoints_VAL = get_option('nba-points-free'); 
	$rebounds_VAL = get_option('nba-points-rebound'); 
	$assists_VAL = get_option('nba-points-assist'); 
	$steals_VAL = get_option('nba-points-steal'); 
	$blocks_VAL = get_option('nba-points-block'); 
	$turnovers_VAL = get_option('nba-points-turnover'); 
	
	//Update Live NBA Contests
	
	$contests_updated = 0;
	
	if ($completed == false) {
		$contest_status = 'In Progress';
	}
	else if ($completed == true) {
		$contest_status = 'Finished';
	}
	
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
				'terms'    => 'nba',
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
			$contest_results = json_decode(get_field('contest_data'));
			
			$this_contest['date'] = strtoupper(date('Y-M-d', $this_contest_date)); 
			$this_contest['date_time'] = $this_contest_date; 
			$this_contest['contest_id'] = $post->ID; 
			
			//Retrieve live player stats via SportsDataIO API
			
			$contest_date_url = strtoupper(date('d-M-Y', $this_contest_date));
			
			$response = wp_remote_get( "https://fly.sportsdata.io/v3/nba/stats/json/PlayerGameStatsByDate/$contest_date_url", array(
				'method'	=> 'GET',
				'timeout'	=> 60,
			    'headers'	=> array(
			        'Ocp-Apim-Subscription-Key' => $stats_key,
			    ),
			) );
			
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				
				$data = json_decode($response['body']); 
								
				if ($contest_type == 'Teams') {
										
					foreach ($contest_results as $result) {
							
						$result->total_points = 0;
						$result->total_points_center = 0;
						$result->total_points_pointguard = 0;
						$result->total_points_powerforward = 0;
						$result->total_points_shootingguard = 0;
						$result->total_points_smallforward = 0;
												
						foreach ($result->players as $players) {
							
							if (is_array($players)) {
								
								foreach ($players as $player) {

									$player = setNbaPlayerValuesto0($player);
						            
						            foreach ($data as $stats_player) {
							            
							            if ($player->game_id == $stats_player->GlobalGameID) {
							            
											if ($player->player_id == $stats_player->PlayerID) {
									           
									            $player = giveNbaPlayerPointsValue($player,$stats_player); 
												
												if ($player->position == 'C') {
													
													$result->total_points_center = $result->total_points_center + $player->total_points;
													
												}
												if ($player->position == 'PG') {
													
													$result->total_points_pointguard = $result->total_points_pointguard + $player->total_points;
													
												}
												if ($player->position == 'PF') {
													
													$result->total_points_powerforward = $result->total_points_powerforward + $player->total_points;
													
												}
												if ($player->position == 'SG') {
													
													$result->total_points_shootingguard = $result->total_points_shootingguard + $player->total_points;
													
												}
												if ($player->position == 'SF') {
													
													$result->total_points_smallforward = $result->total_points_smallforward + $player->total_points;
													
												}
												
												$result->total_points = $result->total_points_center + $result->total_points_pointguard + $result->total_points_powerforward + $result->total_points_shootingguard + $result->total_points_smallforward;
									           
								            }
								            
								        }
							           
						            }

								}
								
							}
							
						}
												
					}
					
					// Update live / results
					
					$sort = array();
					foreach ($contest_results as $key => $part) {
						$sort[$key] = $part->total_points;
					}
					array_multisort($sort, SORT_DESC, $contest_results);
									
					update_field('contest_results', json_encode($contest_results, JSON_UNESCAPED_UNICODE), $post->ID);
					update_field('contest_live_stats_url', 'https://fly.sportsdata.io/v3/nba/stats/json/PlayerGameStatsByDate/'.$contest_date_url.'?key='.$stats_key, $post->ID);
					
					$contests_updated++;
					
				}
				else if ($contest_type == 'Mixed') {
					
					foreach ($contest_results as $result) {
						
						$result->total_points = 0; 
						
						$k = 0;
						foreach ($result as $team) {
							
							if (is_array($team)) {
							
								foreach ($team as $player) {
									
									$player = setNbaPlayerValuesto0($player); 
									
									foreach ($data as $stats_player) {
							            
										if ($player->player_id == $stats_player->PlayerID) {
								           
								            $player = giveNbaPlayerPointsValue($player,$stats_player); 
										 	
											$result->total_points = $result->total_points+$player->total_points;  
								           
							            }
							           
						            }
									
								}
							
							}
							
							if (is_object($team)){
								
								$player = $team;
								
								$player = setNbaPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
							            
									if ($player->player_id == $stats_player->PlayerID) {
							           
							            $player = giveNbaPlayerPointsValue($player,$stats_player);
										
										$result->total_points = $result->total_points+$player->total_points;
							           
						            }
						           
					            }
							
							}

						}
						
					}
					
					// Update live / results
					
					$sort = array();
					foreach ($contest_results as $key => $part) {
						$sort[$key] = $part->total_points;
					}
					array_multisort($sort, SORT_DESC, $contest_results);
										 
					update_field('contest_results', json_encode($contest_results, JSON_UNESCAPED_UNICODE), $post->ID);
					update_field('contest_live_stats_url', 'https://fly.sportsdata.io/v3/nba/stats/json/PlayerGameStatsByDate/'.$contest_date_url.'?key='.$stats_key, $post->ID);
					
					$contests_updated++;
								
				}
				else if ($contest_type == 'Team vs Team') {
					
				
					foreach ($contest_results as $game) {
						
						foreach ($game as $team) {
							
							$players = $team->players;
							 
							$center = $players->Center;
							$pointguard = $players->Pointguard;
							$powerforward = $players->Powerforward;
							$shootingguard = $players->Shootingguard;
							$smallforward = $players->Smallforward;
							 
							$team->total_points = 0;
							$team->total_points_center = 0;
							$team->total_points_pointguard = 0;
							$team->total_points_powerforward = 0;
							$team->total_points_shootingguard = 0;
							$team->total_points_smallforward = 0;
							
							foreach ($center as $player) { 
								
								$player = setNbaPlayerValuesto0($player); 
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = giveNbaPlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points; 
										
									}
									
								}
								
							}
							
							foreach ($pointguard as $player) {
								
								$player = setNbaPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = giveNbaPlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
									}
									
								}
								
							}
							
							foreach ($powerforward as $player) {
								
								$player = setNbaPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = giveNbaPlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
									}
									
								}
								
							}
							
							foreach ($shootingguard as $player) {
								
								$player = setNbaPlayerValuesto0($player);
								
		 						foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = giveNbaPlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
									}
									
								}
								
							}
							
							foreach ($smallforward as $player) {
								
								$player = setNbaPlayerValuesto0($player);
								
							 	foreach ($data as $stats_player) { 
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = giveNbaPlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
							 		}
									
								}
								
							}
														
						}
						
					}

					
					update_field('contest_results', json_encode($contest_results, JSON_UNESCAPED_UNICODE), $post->ID);
					update_field('contest_live_stats_url', 'https://fly.sportsdata.io/v3/nba/stats/json/PlayerGameStatsByDate/'.$contest_date_url.'?key='.$stats_key, $post->ID);
					
					$contests_updated++;
					
				}

			}
			else {
	
				echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 2)</p></div>';
			
			}

		}
	
	}
	wp_reset_query();
	
	
	if ($completed == false) {
		echo '<div id="message" class="updated fade"><p>'.$contests_updated.' Live NBA contests updated.</p></div>';
	}
	
	$cron_log = array(
		'post_status' => 'draft',
		'post_title' => 'NBA Cron Log - Update Live',
		'post_type' => 'cron_log',
		'post_content' => date('m-d-Y g:i a', time() - (60*60*7)) . ' PT',
		'tax_input' => array (
			'cron_type' => 4594,
		),
	); 
	
	wp_set_current_user(1); 
	wp_insert_post( $cron_log ); 
	 
}

// Process completed contests and wagers

function update_projection_for_nba_contest($stats_key){

	$twopoints_VAL = get_option('nba-points-two'); 
	$threepoints_VAL = get_option('nba-points-three'); 
	$freepoints_VAL = get_option('nba-points-free'); 
	$rebounds_VAL = get_option('nba-points-rebound'); 
	$assists_VAL = get_option('nba-points-assist'); 
	$steals_VAL = get_option('nba-points-steal'); 
	$blocks_VAL = get_option('nba-points-block'); 
	$turnovers_VAL = get_option('nba-points-turnover'); 
	
	//Update Live NBA Contests
	
	$args = array(
		'post_type' => 'contest',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key'     => 'contest_date',
				'value'   => date('Y-m-d', current_time( 'timestamp')),
				'compare' => 'LIKE'
			)
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'league',
				'field'    => 'slug',
				'terms'    => 'nba',
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
			$contest_results = json_decode(get_field('contest_data'));
			
			$this_contest['date'] = strtoupper(date('Y-M-d', $this_contest_date)); 
			$this_contest['date_time'] = $this_contest_date; 
			$this_contest['contest_id'] = $post->ID; 
			
			//Retrieve live player stats via SportsDataIO API
			
			$contest_date_url = strtoupper(date('d-M-Y', $this_contest_date));
			
			$response = wp_remote_get( "https://fly.sportsdata.io/v3/nba/stats/json/PlayerGameStatsByDate/$contest_date_url", array(
				'method'	=> 'GET',
				'timeout'	=> 60,
			    'headers'	=> array(
			        'Ocp-Apim-Subscription-Key' => $stats_key,
			    ),
			) );
			
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				
				$data = json_decode($response['body']); 
								
				if ($contest_type == 'Teams') {
										
					foreach ($contest_results as $result) {
							
						$result->total_points = 0;
						$result->total_points_center = 0;
						$result->total_points_pointguard = 0;
						$result->total_points_powerforward = 0;
						$result->total_points_shootingguard = 0;
						$result->total_points_smallforward = 0;
												
						foreach ($result->players as $players) {
							
							if (is_array($players)) {
								
								foreach ($players as $player) {

									$player = setNbaPlayerValuesto0($player);
						            
						            foreach ($data as $stats_player) {
							            
							            if ($player->game_id == $stats_player->GlobalGameID) {
							            
											if ($player->player_id == $stats_player->PlayerID) {
									           
									            $player = giveNbaPlayerPointsValue($player,$stats_player); 
												
												if ($player->position == 'C') {
													
													$result->total_points_center = $result->total_points_center + $player->total_points;
													
												}
												if ($player->position == 'PG') {
													
													$result->total_points_pointguard = $result->total_points_pointguard + $player->total_points;
													
												}
												if ($player->position == 'PF') {
													
													$result->total_points_powerforward = $result->total_points_powerforward + $player->total_points;
													
												}
												if ($player->position == 'SG') {
													
													$result->total_points_shootingguard = $result->total_points_shootingguard + $player->total_points;
													
												}
												if ($player->position == 'SF') {
													
													$result->total_points_smallforward = $result->total_points_smallforward + $player->total_points;
													
												}
												
												$result->total_points = $result->total_points_center + $result->total_points_pointguard + $result->total_points_powerforward + $result->total_points_shootingguard + $result->total_points_smallforward;
									           
								            }
								            
								        }
							           
						            }

								}
								
							}
							
						}
												
					}
					
					// Update live / results
					
					$sort = array();
					foreach ($contest_results as $key => $part) {
						$sort[$key] = $part->total_points;
					}
					array_multisort($sort, SORT_DESC, $contest_results);
									
					update_field('contest_data', json_encode($contest_results, JSON_UNESCAPED_UNICODE), $post->ID);
					
					$contests_updated++;
					
				}
				else if ($contest_type == 'Mixed') {
					
					foreach ($contest_results as $result) {
						
						$result->total_points = 0; 
						
						$k = 0;
						foreach ($result as $team) {
							
							if (is_array($team)) {
							
								foreach ($team as $player) {
									
									$player = setNbaPlayerValuesto0($player); 
									
									foreach ($data as $stats_player) {
							            
										if ($player->player_id == $stats_player->PlayerID) {
								           
								            $player = giveNbaPlayerPointsValue($player,$stats_player); 
										 	
											$result->total_points = $result->total_points+$player->total_points;  
								           
							            }
							           
						            }
									
								}
							
							}
							
							if (is_object($team)){
								
								$player = $team;
								
								$player = setNbaPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
							            
									if ($player->player_id == $stats_player->PlayerID) {
							           
							            $player = giveNbaPlayerPointsValue($player,$stats_player);
										
										$result->total_points = $result->total_points+$player->total_points;
							           
						            }
						           
					            }
							
							}

						}
						
					}
					
					// Update live / results
					
					$sort = array();
					foreach ($contest_results as $key => $part) {
						$sort[$key] = $part->total_points;
					}
					array_multisort($sort, SORT_DESC, $contest_results);
										 
					update_field('contest_data', json_encode($contest_results, JSON_UNESCAPED_UNICODE), $post->ID);
					
					$contests_updated++;
								
				}
				else if ($contest_type == 'Team vs Team') {
					
				
					foreach ($contest_results as $game) {
						
						foreach ($game as $team) {
							
							$players = $team->players;
							 
							$center = $players->Center;
							$pointguard = $players->Pointguard;
							$powerforward = $players->Powerforward;
							$shootingguard = $players->Shootingguard;
							$smallforward = $players->Smallforward;
							 
							$team->total_points = 0;
							$team->total_points_center = 0;
							$team->total_points_pointguard = 0;
							$team->total_points_powerforward = 0;
							$team->total_points_shootingguard = 0;
							$team->total_points_smallforward = 0;
							
							foreach ($center as $player) {
								
								$player = setNbaPlayerValuesto0($player); 
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = giveNbaPlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points; 
										
									}
									
								}
								
							}
							
							foreach ($pointguard as $player) {
								
								$player = setNbaPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = giveNbaPlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
									}
									
								}
								
							}
							
							foreach ($powerforward as $player) {
								
								$player = setNbaPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = giveNbaPlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
									}
									
								}
								
							}
							
							foreach ($shootingguard as $player) {
								
								$player = setNbaPlayerValuesto0($player);
								
		 						foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = giveNbaPlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
									}
									
								}
								
							}
							
							foreach ($smallforward as $player) {
								
								$player = setNbaPlayerValuesto0($player);
								
							 	foreach ($data as $stats_player) { 
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = giveNbaPlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
							 		}
									
								}
								
							}
														
						}
						
					}

					
					update_field('contest_data', json_encode($contest_results, JSON_UNESCAPED_UNICODE), $post->ID);
					
					$contests_updated++;
					
				}

			}
			else {
	
				echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 2)</p></div>';
			
			}

		}
	
	}
	wp_reset_query();

}


function process_finished_nba_contests($stats_key) {
	
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
				'terms'    => 'nba',
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
			$this_contest['date'] = strtoupper(date('Y-M-d', $this_contest_date));
			$this_contest['date_time'] = $this_contest_date;
			$this_contest['id'] = $post->ID;
			
			if (!in_array($this_contest['date'], $contest_dates)) {
				
				$contest_dates[] = $this_contest['date'];
								
			}
			
			$contest_data[] = $this_contest;
			
		}
	}
	wp_reset_query();
		
	//If contest is finished, mark as "Finished"
	
	$continue = false;
	

	$currentDay = date('d-M-Y');
	$addOneDayToCurrentDay = date('d-M-Y', strtotime($date . ' +1 day'));

	if (!empty($contest_dates)) {
			
		$postponed_contests = array();
			
		foreach ($contest_dates as $contest_date) {
				
			$response = wp_remote_get( "https://fly.sportsdata.io/v3/nba/scores/json/GamesByDate/$contest_date", array(
				'method'	=> 'GET',
				'timeout'	=> 60,
			    'headers'	=> array(
			        'Ocp-Apim-Subscription-Key' => $stats_key,
			    ),
			) );
			
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
				
				$data = json_decode($response['body']);
				
				$isComplete = true;
				
				foreach ($data as $game) {
																								
					if ($game->Status == 'Canceled' || $game->Status == 'Suspended') {
						
						$postponed_contests[] = $game->GlobalGameID;
						$isComplete = false;
						
					}
					else if ($game->Status == 'Postponed' &&  strtotime($contest_data) < strtotime($addOneDayToCurrentDay) ) {
						$isComplete = true;
							
					}
					else if ($game->IsClosed != true) {
						
						$isComplete = false;
						break;
							
					}
							 		
				}
				
				if ($isComplete == true) {
					
					//Update status for all contests on this date to 'Finished' (ready for processing)
					
					foreach ($contest_data as $contest) {
						
						if ($contest['date'] == $contest_date) {
						
							update_field('contest_status', 'Finished', $contest['id']);
							
						}
						
					}
					
					$continue = true;
					
				}
				else { 
					
					echo '<div id="message" class="updated fade error"><p>There are still nba contests pending. Please try again when today\'s games are over. (Error Code 4)</p></div>';
					
				} 
	
			}	 
			else { 
				
				echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 3)</p></div>';
				
			}	
							
		}
	
	}
	else {
		
		echo '<div id="message" class="updated fade error"><p>There are no nba games in progress. Please try again later. (Error Code 5)</p></div>';
		
	}
	
	
	
	if ($continue == true) { 
	
	
		// Update stats for completed contests
	
		update_live_nba_contests($stats_key, true);
	
	
		// Retrieve Finished contests and calculate winners
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
			'tax_query' => array(
				array(
					'taxonomy' => 'league',
					'field'    => 'slug',
					'terms'    => 'nba',
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
				$contest_id = $post->ID;
				
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
							
							
							// nba POSTPONEMENTS: if postponed, push all involved wagers and return cash
							
							/*
							foreach ($teams_array as $team) {
								
								print_r($team);
								
							}
							*/						
								
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
					update_post_meta($contest_id, 'contest_status', 'Closed');
					//echo 'Team vs Field closed';
						
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
							
							
							// nba POSTPONEMENTS: if postponed, push all involved wagers and return cash
							
							/*
							foreach ($teams_array as $team) {
								
								print_r($team);
								
							}
							*/						
								
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
						
						$the_wager->reset_postdata();
						
					}
					
							
					//final step: mark contest as 'closed'
					update_post_meta($contest_id, 'contest_status', 'Closed');
					//echo 'Mixed closed';
					
				}
				else if ($contest_type == 'Team vs Team') {
					
					//echo $post->ID . ' ' . $post->post_title . '<br>';
					
					
					// Retrieve wagers for each contest, and check for winners
					
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
							
							$wager_type = get_field('wager_type', $wager_id);
							
							if ($wager_type == 'Spread') {
									
								// For each wager, get winner and spread
								
								$winner = get_field('wager_winner_1', $wager_id);
								$spread = get_field('point_spread', $wager_id);
								
								
								// Loop through contest results to find contest
								
								foreach ($contest_results as $game) {
									
									foreach ($game as $team) {
										
										if ($team->term_id == $winner) {
											
											// Get total points for wagered winner
											
											$total_points_winner = $team->total_points;
											$team_name = $team->name;
											$opponent = $team->opponent_abbrev;
											
											// Get total points for wagered loser
											
											foreach ($game as $team) {
												
												if ($team->team_abbrev == $opponent) {
												
													$total_points_loser = $team->total_points;
												
												}

											}
											
										}
										
									}
									
								}
								
								// Set vars
								
								$win = false;
								$push = false;
								$difference = 0;
								
								if ($total_points_loser == 0 && $total_points_winner == 0) {
									
									$push = true;
									
								}
								else {
								
									if ($spread > 0) {
										
										// Underdog
										
										$difference = $total_points_winner-$total_points_loser;
										
										if ($total_points_winner > $total_points_loser) {
										
											$win = true;
										
										}
										 else {
											
											if (abs($difference) == $spread) {
												
												$push = true;
											
											}
											else if (abs($difference) < $spread) {
											
												$win = true;
											
											}
											
										}
										
									}
									else {
										
										// Favored
										
										$difference = $total_points_winner-$total_points_loser;
										
										if ($difference == abs($spread)) {
											
											$push = true;
											
										}
										else {
											
											if ($difference > abs($spread)) {
												
												$win = true;
												
											}
											
										}
										
									}
								
								}
								
								//Update fields and user balance
								
								$user_id = get_post_field( 'post_author', $wager_id );
								$buying_power = floatval(get_field('account_balance', 'user_'.$user_id));
								$total_equity = floatval(get_field('visible_balance', 'user_'.$user_id));
								
								$wager_amount = str_replace(',','',get_field('wager_amount', $wager_id));
								$wager_winnings = str_replace(',','',get_field('potential_winnings', $wager_id));
								
								if ($push == true) {
								
									update_field('wager_result', 'Push', $wager_id);
									wp_set_post_terms( $wager_id, 64, 'wager_result', false );
									
									$buying_power = $buying_power + $wager_amount;
								
								}
								else if ($win == true) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									
									$total_equity = $total_equity + $wager_winnings;
									$buying_power = $buying_power + $wager_winnings + $wager_amount;
									
								}
								else if ($win == false) {
								
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
									$total_equity = $total_equity - $wager_amount;
								
								}
								
								update_field('account_balance', $buying_power, 'user_'.$user_id);
								update_field('visible_balance', $total_equity, 'user_'.$user_id);
								
								$wager_count++;
								
								
							}
							else if ($wager_type == 'Moneyline') {
								
								
								// For each wager, get winner and moneyline
								
								$winner = get_field('wager_winner_1', $wager_id); 
								$moneyline = get_field('wager_moneyline', $wager_id); 
																
								// Loop through contest results to find contest
								
								foreach ($contest_results as $game) {
									
									foreach ($game as $team) {
										
										if ($team->term_id == $winner) {
											
											// Get total points for wagered winner
											
											$total_points_winner = $team->total_points;
											$team_name = $team->name;
											$opponent = $team->opponent_abbrev;
											
											// Get total points for wagered loser
											
											foreach ($game as $team) {
												
												if ($team->team_abbrev == $opponent) {
												
													$total_points_loser = $team->total_points;
												
												}
												
											}
		
										}
	
									}
									
								}
								
								// Set vars
								
								$win = false;
								$push = false;
								
								// Determine win/loss/push
								
								if ($total_points_winner == $total_points_loser) {
									$push = true;
								}
								else if ($total_points_winner > $total_points_loser) {
									$win = true;
								}
								
								//Update fields and user balance
								
								$user_id = get_post_field( 'post_author', $wager_id );
								
								$buying_power = floatval(get_field('account_balance', 'user_'.$user_id));
								$total_equity = floatval(get_field('visible_balance', 'user_'.$user_id));
								
								$wager_amount = str_replace(',','',get_field('wager_amount', $wager_id));
								$wager_winnings = str_replace(',','',get_field('potential_winnings', $wager_id));
								
								if ($push == true) {
								
									update_field('wager_result', 'Push', $wager_id);
									wp_set_post_terms( $wager_id, 64, 'wager_result', false );
									
									$buying_power = $buying_power + $wager_amount;
								
								}
								else if ($win == true) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									
									$total_equity = $total_equity + $wager_winnings;
									$buying_power = $buying_power + $wager_winnings + $wager_amount;
									
								}
								else if ($win == false) {
								
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
									$total_equity = $total_equity - $wager_amount;
								
								}
								
								update_field('account_balance', $buying_power, 'user_'.$user_id);
								update_field('visible_balance', $total_equity, 'user_'.$user_id);
								
								$wager_count++;
	
							}
							else if ($wager_type == 'Over/Under') {
								
								
								// For each wager get winner, over/under and game ID
								
								$winner = get_field('wager_winner_1', $wager_id);
								$overunder = get_field('wager_overunder', $wager_id);
								$game_id = get_field('overunder_gameid', $wager_id);
								
								// Loop through contest results to find contest
								
								foreach ($contest_results as $game) {
									
									foreach ($game as $team) {
										
										if ($team->game_id == $game_id) {
											
											// Get total points for team 1
											
											$total_points_team1 = $team->total_points;
											$team_name = $team->name;
											
											// Get total points for team 2
											
											foreach ($game as $team) {
												
												if ($team->game_id == $game_id && $team_name != $team->name) {
												
													$total_points_team2 = $team->total_points;
												
												}
												
											}
		
										}
	
									}
									
								}
								
								// Get total
								
								$total_points_overunder = $total_points_team1 + $total_points_team2;
								
								// Set vars
								
								$win = false;
								$push = false;
								
								// Determine win/loss/push
								
								if ($total_points_overunder == $overunder || $total_points_overunder == 0) {
									
									$push = true;
								
								}
								else {
									
									if ($winner == 'Over') {
										
										if ($total_points_overunder > $overunder) {
											$win = true;
										}
										
									}
									else if ($winner == 'Under') {
										
										if ($total_points_overunder < $overunder) {
											$win = true;
										}
										
									}
									
								}
								
								//Update fields and user balance
								
								$user_id = get_post_field( 'post_author', $wager_id );
								$buying_power = floatval(get_field('account_balance', 'user_'.$user_id));
								$total_equity = floatval(get_field('visible_balance', 'user_'.$user_id));
								
								$wager_amount = str_replace(',','',get_field('wager_amount', $wager_id));
								$wager_winnings = str_replace(',','',get_field('potential_winnings', $wager_id));
								
								if ($push == true) {
								
									update_field('wager_result', 'Push', $wager_id);
									wp_set_post_terms( $wager_id, 64, 'wager_result', false );
									
									$buying_power = $buying_power + $wager_amount;
								
								}
								else if ($win == true) {
									
									update_field('wager_result', 'Win', $wager_id);
									wp_set_post_terms( $wager_id, 62, 'wager_result', false );
									
									$total_equity = $total_equity + $wager_winnings;
									$buying_power = $buying_power + $wager_winnings + $wager_amount;
									
								}
								else if ($win == false) {
								
									update_field('wager_result', 'Loss', $wager_id);
									wp_set_post_terms( $wager_id, 63, 'wager_result', false );
									
									$total_equity = $total_equity - $wager_amount;
								
								}
								
								update_field('account_balance', $buying_power, 'user_'.$user_id);
								update_field('visible_balance', $total_equity, 'user_'.$user_id);
								
								$wager_count++;
								
								
							}
																				
						}
					}
					
					$the_wager->reset_postdata();
					
					//final step: mark contest as 'closed'
					update_post_meta($contest_id, 'contest_status', 'Closed');
										
				}
						
				$contest_count++;
			
			}
		}
		wp_reset_query();
	
		
		echo '<div id="message" class="updated fade"><p>'.$wager_count.' wager(s) and '.$contest_count.' contest(s) processed.</p></div>';
			
	
	}

}

// Cron jobs

// add_action( 'nba_update_live_cron', 'nba_update_live_cron' );

function nba_update_live_cron() {
	
	// $stats_key = 'f75cd5d61287488292aa33b77a898aa6';
	// update_live_nba_contests($stats_key, false);
	
	/*
	$cron_log = array(
		'post_status' => 'draft',
		'post_title' => 'nba Cron Log - Update Live',
		'post_type' => 'cron_log',
		'post_content' => date('m-d-Y g:i a', time() - (60*60*7)) . ' PT',
		'tax_input' => array (
			'cron_type' => 4599,
		),
	);
	
	wp_set_current_user(1);
	wp_insert_post( $cron_log );
	*/
	
}
?>