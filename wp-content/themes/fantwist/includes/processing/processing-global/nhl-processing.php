<?php
//Register settings and options

function pariwagernhlpoints() {
		
	register_setting( 'pariwager-nhl-points', 'nhl-points-goal' );
	register_setting( 'pariwager-nhl-points', 'nhl-points-assist' );
	register_setting( 'pariwager-nhl-points', 'nhl-points-shotongoal' );
	register_setting( 'pariwager-nhl-points', 'nhl-points-blockedshot' );
	register_setting( 'pariwager-nhl-points', 'nhl-points-shorthandedpointbonus' );
	register_setting( 'pariwager-nhl-points', 'nhl-points-shootoutgoal' );
	register_setting( 'pariwager-nhl-points', 'nhl-points-hattrickbonus' );
	register_setting( 'pariwager-nhl-points', 'nhl-points-win' );
	register_setting( 'pariwager-nhl-points', 'nhl-points-save' );
	register_setting( 'pariwager-nhl-points', 'nhl-points-goalagainst' );
	register_setting( 'pariwager-nhl-points', 'nhl-points-shutoutbonus' );

}
add_action( 'admin_init', 'pariwagernhlpoints' );

// Local functions

function setTeamValues($team,$home_away,$opponent_abbrev,$contest_player,$total_points,$contest_date_time,$type){

	$buffer_team = $team;
	$buffer_team->home_away = $home_away;
	$buffer_team->opponent = $buffer_team->TeamID;
	$buffer_team->opponent_abbrev = $opponent_abbrev;
	if($type == "C"){
		$buffer_team->players['Center'][] = $contest_player;
		$buffer_team->total_points_center = number_format($buffer_team->total_points_center + $total_points, 2);
	}
	if($type == "D"){
		$buffer_team->players['Defenseplayer'][] = $contest_player;
		$buffer_team->total_points_defenseplayer = number_format($buffer_team->total_points_defenseplayer + $total_points, 2);
	}
	if($type == "G"){
		$buffer_team->players['Goalie'][] = $contest_player;
		$buffer_team->total_points_goalie = number_format($buffer_team->total_points_goalie + $total_points, 2);
	}
	if($type == "LW"){
		$buffer_team->players['Leftwing'][] = $contest_player;
		$buffer_team->total_points_leftwing = number_format($buffer_team->total_points_leftwing + $total_points, 2);
	}
	if($type == "RW"){
		$buffer_team->players['Rightwing'][] = $contest_player;
		$buffer_team->total_points_rightwing = number_format($buffer_team->total_points_rightwing + $total_points, 2);
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

function setPlayerValuesto0($player){

	$buffer_player = $player;

	$buffer_player->goals = 0;
	$buffer_player->goals_points = 0;
	$buffer_player->assists = 0;
	$buffer_player->assists_points = 0;
	$buffer_player->shotsongoal = 0;
	$buffer_player->shotsongoal_points = 0;
	$buffer_player->blockedshots = 0;
	$buffer_player->blockedshots_points = 0;
	$buffer_player->shorthandedgoals = 0;
	$buffer_player->shorthandedgoals_points = 0;
	$buffer_player->shootoutgoals = 0;
	$buffer_player->shootoutgoals_points = 0;
	$buffer_player->hattricks = 0;
	$buffer_player->hattricks_points = 0;
	$buffer_player->goalwins = 0;
	$buffer_player->goalwins_points = 0;
	$buffer_player->goalsaves = 0;
	$buffer_player->goalsaves_points = 0;
	$buffer_player->goalsagainst = 0;
	$buffer_player->goalsagainst_points = 0;
	$buffer_player->shutoutsbonus = 0;
	$buffer_player->shutoutsbonus_points = 0;
	$buffer_player->total_points = 0;
	$buffer_player->is_game_over = 0;

	return $buffer_player;
									
}

function givePlayerPointsValue($player,$stats_player){

	$buffer_player = $player;

	$goals_VAL = get_option('nhl-points-goal');
	$assists_VAL = get_option('nhl-points-assist');
	$shotsongoal_VAL = get_option('nhl-points-shotongoal');
	$blockedshots_VAL = get_option('nhl-points-blockedshot');
	$shorthandedpointsbonus_VAL = get_option('nhl-points-shorthandedpointbonus');
	$shootoutgoals_VAL = get_option('nhl-points-shootoutgoal');
	$hattricksbonus_VAL = get_option('nhl-points-hattrickbonus');
	$goalwins_VAL = get_option('nhl-points-goalwin');
	$goalsaves_VAL = get_option('nhl-points-goalsave');
	$goalsagainst_VAL = get_option('nhl-points-goalagainst');
	$shutoutsbonus_VAL = get_option('nhl-points-shutoutbonus');


	if ($buffer_player->position_category == 'SKATERS') {

		$buffer_player->goals = $stats_player->Goals;
		$player->goals_points = number_format($buffer_player->goals*$goals_VAL, 2);
		$buffer_player->assists = $stats_player->Assists;
		$player->assists_points = number_format($buffer_player->assists*$assists_VAL, 2);
		$buffer_player->shotsongoal = $stats_player->ShotsOnGoal;
		$player->shotsongoal_points = number_format($buffer_player->shotsongoal*$shotsongoal_VAL, 2);
		$buffer_player->blockedshots = $stats_player->Blocks;
		$player->blockedshots_points = number_format($buffer_player->blockedshots*$blockedshots_VAL, 2);
		$buffer_player->shorthandedgoals = $stats_player->ShortHandedGoals + $stats_player->ShortHandedAssists;
		$buffer_player->shorthandedgoals_points = number_format($buffer_player->shorthandedgoals*$shorthandedpointsbonus_VAL, 2);
		$buffer_player->shootoutgoals = $stats_player->ShootoutGoals;
		$buffer_player->shootoutgoals_points = number_format($buffer_player->shootoutgoals*$shootoutgoals_VAL, 2);
		$buffer_player->hattricks = $stats_player->HatTricks;
		$buffer_player->hattricks_points = number_format($buffer_player->hattricks*$hattricksbonus_VAL, 2);
		$buffer_player->goalwins = $stats_player->GoaltendingWins;
		$buffer_player->goalwins_points = 0;
		$buffer_player->goalsaves = $stats_player->GoaltendingSaves;
		$buffer_player->goalsaves_points = 0;
		$buffer_player->goalsagainst = $stats_player->GoaltendingGoalsAgainst;
		$buffer_player->goalsagainst_points = 0;
		$buffer_player->shutoutsbonus = $stats_player->GoaltendingShutouts;
		$buffer_player->shutoutsbonus_points = 0;
		
	}
	else {
		
		$buffer_player->goals = $stats_player->Goals;
		$player->goals_points = 0;
		$buffer_player->assists = $stats_player->Assists;
		$player->assists_points = 0;
		$buffer_player->shotsongoal = $stats_player->ShotsOnGoal;
		$player->shotsongoal_points = 0;
		$buffer_player->blockedshots = $stats_player->Blocks;
		$player->blockedshots_points = 0;
		$buffer_player->shorthandedgoals = $stats_player->ShortHandedGoals + $stats_player->ShortHandedAssists;
		$buffer_player->shorthandedgoals_points = 0;
		$buffer_player->shootoutgoals = $stats_player->ShootoutGoals;
		$buffer_player->shootoutgoals_points = 0;
		$buffer_player->hattricks = $stats_player->HatTricks;
		$buffer_player->hattricks_points = 0;
		$buffer_player->goalwins = $stats_player->GoaltendingWins;
		$buffer_player->goalwins_points = number_format($buffer_player->goalwins*$goalwins_VAL, 2);
		$buffer_player->goalsaves = $stats_player->GoaltendingSaves;
		$buffer_player->goalsaves_points = number_format($buffer_player->goalsaves*$goalsaves_VAL, 2);
		$buffer_player->goalsagainst = $stats_player->GoaltendingGoalsAgainst;
		$buffer_player->goalsagainst_points = number_format($buffer_player->goalsagainst*$goalsagainst_VAL, 2);
		$buffer_player->shutoutsbonus = $stats_player->GoaltendingShutouts;
		$buffer_player->shutoutsbonus_points = number_format($buffer_player->shutoutsbonus*$shutoutsbonus_VAL, 2);
		
	}

	$buffer_player->is_game_over = $stats_player->IsGameOver;
	   
	$buffer_player->total_points = $buffer_player->goals_points + $buffer_player->assists_points + $buffer_player->shotsongoal_points + $buffer_player->blockedshots_points + $buffer_player->shorthandedpointsbonus_points + $buffer_player->shootoutgoals_points + $buffer_player->hattricksbonus_points + $buffer_player->goaltendingwins_points + $buffer_player->goaltendingsaves_points + $buffer_player->goalsagainst_points + $buffer_player->shutoutsbonus_points;

	return $buffer_player;
									
}

//Create nhl Projections and Contests

function create_nhl_projections_and_contests2($date, $projection_key) {
	
	//Define vars
	
	$mixed_center = array();
	$mixed_defenseplayer = array();
	$mixed_goalie = array();
	$mixed_leftwing = array();
	$mixed_rightwing = array();
	
	$parent_team = 42;
	$tax_league = 3;
	$league_title = 'NHL';
	
	$goals_VAL = get_option('nhl-points-goal');
	$assists_VAL = get_option('nhl-points-assist');
	$shotsongoal_VAL = get_option('nhl-points-shotongoal');
	$blockedshots_VAL = get_option('nhl-points-blockedshot');
	$shorthandedpointsbonus_VAL = get_option('nhl-points-shorthandedpointbonus');
	$shootoutgoals_VAL = get_option('nhl-points-shootoutgoal');
	$hattricksbonus_VAL = get_option('nhl-points-hattrickbonus');
	$goalwins_VAL = get_option('nhl-points-goalwin');
	$goalsaves_VAL = get_option('nhl-points-goalsave');
	$goalsagainst_VAL = get_option('nhl-points-goalagainst');
	$shutoutsbonus_VAL = get_option('nhl-points-shutoutbonus');

	//Get player projections via SportsDataIO API
	$projections_url = "https://fly.sportsdata.io/v3/nhl/projections/json/PlayerGameProjectionStatsByDate/$date";

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
			$team->total_points_defenseplayer = 0;
			$team->total_points_goalie = 0;
			$team->total_points_leftwing = 0;
			$team->total_points_rightwing = 0;

			$team->players = array('center', 'defenseplayer', 'goalie', 'leftwing', 'rightwing');

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
				
			$goals = $player->Goals;
			$assists = $player->Assists;
			$shotsongoal = $player->ShotsOnGoal;
			$blockedshots = $player->Blocks;
			$shorthandedgoals = $player->ShortHandedGoals + $player->ShortHandedAssists;
			$shootoutgoals = $player->ShootoutGoals;
			$hattricks = $player->HatTricks;
			$goalwins = $player->GoaltendingWins;
			$goalsaves = $player->GoaltendingSaves;
			$goalsagainst = $player->GoaltendingGoalsAgainst;
			$shutoutsbonus = $player->GoaltendingShutouts;
			
			$total_pts = $goals + $assists + $shotsongoal + $blockedshots + $shorthandedgoals + $shootoutgoals + $hattricks + $goalwins + $goalsaves + $goalsagainst + $shutoutsbonus;
		    
			if (isset($cutoff_datetime) == false) {
				$cutoff_datetime = $contest_date_time;
			}
			else if ($contest_date_time < $cutoff_datetime) {
				$cutoff_datetime = $contest_date_time; 
			}										

		    if ($position == 'C' || $position == 'LW' || $position == 'RW') {

				$position_category = "SKATERS";

				$total_points = ($goals*$goals_VAL) + ($assists*$assists_VAL) + ($shotsongoal*$shotsongoal_VAL) + ($blockedshots*$blockedshots_VAL) + ($shorthandedgoals*$shorthandedpointsbonus_VAL) + ($shootoutgoals*$shootoutgoals_VAL) + ($hattricks*$hattricksbonus_VAL);
				
				$total_points = number_format($total_points, 2);

				$contest_player = array(
					'name' => $player->Name,
					'player_id' => $player->PlayerID,
					'team_id' => $player->GlobalTeamID,
					'opponent_id' => $opponent,
					'opponent_abbrev' => $opponent_abbrev,
					'home_away' => $home_away,
					'position' => $position,
					'position_category' => $position_category,
					'game_start_et' => $contest_date_time,
					'goals' => $goals,
					'goals_points' => $goals*$goals_VAL,
					'assists' => $assists,
					'assists_points' => $assists*$assists_VAL,
					'shotsongoal' => $shotsongoal,
					'shotsongoal_points' => $shotsongoal*$shotsongoal_VAL,
					'blockedshots' => $blockedshots,
					'blockedshots_points' => $blockedshots*$blockedshots_VAL,
					'shorthandedgoals' => $shorthandedgoals,
					'shorthandedgoals_points' => $shorthandedgoals*$shorthandedpointsbonus_VAL,
					'shootoutgoals' => $shootoutgoals,
					'shootoutgoals_points' => $shootoutgoals*$shootoutgoals_VAL,
					'hattricks' => $hattricks,
					'hattricks_points' => $hattricks*$hattricksbonus_VAL,
					'goalwins' => 0,
					'goalwins_points' => 0,
					'goalsaves' => 0,
					'goalsaves_points' => 0,
					'goalsagainst' => 0,
					'goalsagainst_points' => 0,
					'shutoutsbonus' => 0,
					'shutoutsbonus_points' => 0,
					'game_id' => $gameID,
					'total_points' => $total_points,
					'is_game_over' => 0
				);				
									
				//add to Teams array
				if ($position == 'C') {
					foreach ($all_teams as $team) {
						if ($team->TeamID == $player->GlobalTeamID) {
							if ($gameID == $team->game_1 || $gameID == $team->game_2) {
								$team = setTeamValues($team,$home_away,$opponent_abbrev,$contest_player,$total_points,$contest_date_time,$position);
								$mixed_center[] = $contest_player;
							}
						}
					}
				}
				
				if($position == 'LW'){
					foreach ($all_teams as $team) {
						if ($team->TeamID == $player->GlobalTeamID) {
							if ($gameID == $team->game_1 || $gameID == $team->game_2) {
								$team = setTeamValues($team,$home_away,$opponent_abbrev,$contest_player,$total_points,$contest_date_time,$position);
								$mixed_leftwing[] = $contest_player;
							}
						}
					}
				}

				if($position == 'RW'){
					foreach ($all_teams as $team) {
						if ($team->TeamID == $player->GlobalTeamID) {
							if ($gameID == $team->game_1 || $gameID == $team->game_2) {
								$team = setTeamValues($team,$home_away,$opponent_abbrev,$contest_player,$total_points,$contest_date_time,$position);
								$mixed_rightwing[] = $contest_player;
							}
						}
					}
				}
				
			} else {

				$position_category = "GOALTENDERS";

				$total_points = ($goalwins*$goalwins_VAL) + ($goalsaves*$goalsaves_VAL) + ($goalsagainst*$goalsagainst_VAL) + ($shutoutsbonus*$shutoutsbonus_VAL);
				
				$total_points = number_format($total_points, 2);
					
				$contest_player = array(
					'name' => $player->Name,
					'player_id' => $player->PlayerID,
					'team_id' => $player->GlobalTeamID,
					'opponent_id' => $opponent,
					'opponent_abbrev' => $opponent_abbrev,
					'home_away' => $home_away,
					'position' => $position,
					'position_category' => $position_category,
					'game_start_et' => $contest_date_time,
					'goals' => 0,
					'goals_points' => 0,
					'assists' => 0,
					'assists_points' => 0,
					'shotsongoal' => 0,
					'shotsongoal_points' => 0,
					'blockedshots' => 0,
					'blockedshots_points' => 0,
					'shorthandedgoals' => 0,
					'shorthandedgoals_points' => 0,
					'shootoutgoals' => 0,
					'shootoutgoals_points' => 0,
					'hattricks' => 0,
					'hattricks_points' => 0,
					'goalwins' => $goalwins,
					'goalwins_points' => $goalwins*$goalwins_VAL,
					'goalsaves' => $goalsaves,
					'goalsaves_points' => $goalsaves*$goalsaves_VAL,
					'goalsagainst' => $goalsagainst,
					'goalsagainst_points' => $goalsagainst*$goalsagainst_VAL,
					'shutoutsbonus' => $shutoutsbonus,
					'shutoutsbonus_points' => $shutoutsbonus*$shutoutsbonus_VAL,
					'game_id' => $gameID,
					'total_points' => $total_points,
					'is_game_over' => 0
				);

				if ($position == 'G') {
					foreach ($all_teams as $team) {
						if ($team->TeamID == $player->GlobalTeamID) {
							if ($gameID == $team->game_1 || $gameID == $team->game_2) {
								$team = setTeamValues($team,$home_away,$opponent_abbrev,$contest_player,$total_points,$contest_date_time,$position);
								$mixed_goalie[] = $contest_player;
							}
						}
					}
				}

				if($position == 'D'){
					foreach ($all_teams as $team) {
						if ($team->TeamID == $player->GlobalTeamID) {
							if ($gameID == $team->game_1 || $gameID == $team->game_2) {
								$team = setTeamValues($team,$home_away,$opponent_abbrev,$contest_player,$total_points,$contest_date_time,$position);
								$mixed_defenseplayer[] = $contest_player;
							}
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
		if (count($mixed_defenseplayer) < $mixed_team_count) {
		    $mixed_team_count = count($mixed_defenseplayer);
		}
		if (count($mixed_goalie) < $mixed_team_count) {
		    $mixed_team_count = count($mixed_goalie);
		}
		if (count($mixed_leftwing) < $mixed_team_count) {
		    $mixed_team_count = count($mixed_leftwing);
		}
		if (count($mixed_rightwing) < $mixed_team_count) {
		    $mixed_team_count = count($mixed_rightwing);
		}

		//Sort position groups by total points
		      
		$sort = array();
		foreach ($mixed_center as $key => $part) {
			$sort[$key] = $part['total_points'];
		}
		array_multisort($sort, SORT_DESC, $mixed_center);
		
		
		$sort = array();
		foreach ($mixed_defenseplayer as $key => $part) {
			$sort[$key] = $part['total_points'];
		}
		array_multisort($sort, SORT_DESC, $mixed_defenseplayer);
		
		$sort = array();
		foreach ($mixed_goalie as $key => $part) {
			$sort[$key] = $part['total_points'];
		}
		array_multisort($sort, SORT_DESC, $mixed_goalie);
		
		$sort = array();
		foreach ($mixed_leftwing as $key => $part) {
			$sort[$key] = $part['total_points'];
		}
		array_multisort($sort, SORT_DESC, $mixed_leftwing);

		$sort = array();
		foreach ($mixed_rightwing as $key => $part) {
			$sort[$key] = $part['total_points'];
		}
		array_multisort($sort, SORT_DESC, $mixed_rightwing);

		$mixed_teams = array();
		    
		if ($mixed_team_count >= 25) {
		    $mixed_team_count = 25;
		}
				    	    
		for ($i = 0; $i < $mixed_team_count; $i++) {
			
			$mix_team = array(
				'team_name' => 'Team ' . ($i+1),
				'center' => $mixed_center[$i],
				'defenseplayer' => $mixed_defenseplayer[$i],
				'goalie' => $mixed_goalie[$i],
				'leftwing' => $mixed_leftwing[$i],
				'rightwing' => $mixed_rightwing[$i],
				'total_points' => 0,
				'odds_to_1' => (int) ($i+2),
			);
						
			$mixed_teams[] = $mix_team;	
						
		} 
		    
		$i = 0;
		    
		foreach ($mixed_teams as $mix_team) {
		    
		    $total_points = $mix_team['center']['total_points'] + $mix_team['defenseplayer']['total_points'] + $mix_team['goalie']['total_points'] + $mix_team['leftwing']['total_points'] + $mix_team['rightwing']['total_points'] + $mix_team['team']['total_points'];
		    
		    $mixed_teams[$i]['total_points'] = number_format($total_points, 2);
		    
		    $i++;
		    
		}

		// BUILD CONTESTS
		
		if (isset($cutoff_datetime)) {
		
			$the_contest_datetime = strtoupper(date('m-d-Y g:i a', strtotime($cutoff_datetime) - 60 * 60 * 3));
			// $the_contest_date_unix = strtoupper(date('U', strtotime($cutoff_datetime) - 60 * 60 * 3));
			$the_contest_date_unix = strtoupper(date('U', strtotime($cutoff_datetime)));
			$the_contest_date_notime = date('m-d-Y', strtotime($cutoff_datetime) - 60 * 60 * 3);
			$the_contest_date_notime_day = date('l F jS', strtotime($cutoff_datetime) - 60 * 60 * 3);
			// $the_contest_date_sort = strtoupper(date('Y-m-d H:i:s', strtotime($cutoff_datetime) - 60 * 60 * 3));
			$the_contest_date_sort = strtoupper(date('Y-m-d H:i:s', strtotime($cutoff_datetime)));
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
		
		echo '<div id="message" class="updated fade"><p>'.$contests_created.' NHL contests created.</p></div>';

	} else {
	
		echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 1)</p></div>';
	
	}
}

//New Create function
function create_nhl_projections_and_contests($date, $projection_key) {
					
	//Define vars
	$league_count = 600;
	$parent_team = 42;
	$tax_league = 3;
	$league_title = 'NHL';
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

		$contest_all_games = array();
		$contest_all_teams = array();
		$team_information_data = array();
		$team_players = array();
		$contest_player_types = array();

		foreach($content as $player){
			if(!in_array($player->GlobalGameID,$contest_all_games)){
				array_push($contest_all_games,$player->GlobalGameID);
			}
		}

		foreach($content as $player){
			if(!in_array($player->Team,$contest_all_teams)){
				array_push($contest_all_teams,$player->Team);
			}
		}

		foreach($contest_all_games as $current_game){

			foreach($contest_all_teams as $current_team){

				foreach($content as $player){

					if($current_game == $player->GlobalGameID && $current_team == $player->Team ){

						foreach ($all_teams as $team_cat) {

							if ($team_cat->team_abbrev == $player->Team) {

								$team_information_data[$current_team.$current_game] = array(
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

		}

		foreach($content as $player){
			if(!in_array($player->Position,$contest_player_types)){
				array_push($contest_player_types,$player->Position);
			}
		}

		foreach($contest_all_games as $current_game){

			foreach($contest_all_teams as $current_team){

				$current_team_players = array();

				foreach ($contest_player_types as $player_type) {
					${'player_'.$player_type} = 0;
				}

				foreach($content as $player){
					if($current_game == $player->GlobalGameID && $current_team == $player->Team){
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
				
				$team_players[$current_team.$current_game] = $current_team_players;
			}

		}

		foreach($contest_all_games as $current_game){

			foreach($contest_all_teams as $current_team){

				$play_total_points = 0;
				
				foreach($team_players[$current_team.$current_game] as $points){
					$play_total_points += $points;
				}

				$team_information_data[$current_team.$current_game]['total_points'] = $play_total_points;
				$team_information_data[$current_team.$current_game]['player'] = $team_players[$current_team.$current_game];
			}
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
										if($team1_moneyline == 0){
											$team1_moneyline = $dfsslategames->Game->AwayTeamMoneyLine;
										}
										if($team2_moneyline == 0){
											$team2_moneyline = $dfsslategames->Game->HomeTeamMoneyLine;
										}
									}
								}
							}

							$team1_spread = (number_format(($team_2['total_points'] -  $team_1['total_points']),2));
							$team2_spread = (number_format(($team_1['total_points'] -  $team_2['total_points']),2));
							$team_overunder = round(number_format(($team_1['total_points'] +  $team_2['total_points']),2));

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

							if(isset($current_game['team1']['name'])){
								array_push($game_data,$current_game);
							}

						}
					}

				}

			}

		}

		if(!empty($game_data)){
			$sort_games = array();
			foreach ($game_data as $key => $part) {
				$sort[$key] = strtotime($part['game_start']);
			}
			array_multisort($sort, SORT_ASC, $game_data);

				//adding rotation numbers
				for ($i=0;$i<count($game_data);$i++){
					$game_data[$i]['team1']['rotation_number'] =  "F".(++$league_count);
					$game_data[$i]['team2']['rotation_number'] =  "F".(++$league_count);
					
				}
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
		}

	}else {
	
		echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 1)</p></div>';
	
	}	

}

//New Update Projection Function
function update_nhl_projection_scores($projection_key) {
					
	//Define vars
	$league_count = 600;
	$parent_team = 42;
	$league_title = 'NHL';
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

		if($current_contest_date >= current_time( 'timestamp')){
			$date = date('Y-M-d',$current_contest_date);

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

				$contest_all_games = array();
				$contest_all_teams = array();
				$team_information_data = array();
				$team_players = array();
				$contest_player_types = array();

				foreach($content as $player){
					if(!in_array($player->GlobalGameID,$contest_all_games)){
						array_push($contest_all_games,$player->GlobalGameID);
					}
				}

				foreach($content as $player){
					if(!in_array($player->Team,$contest_all_teams)){
						array_push($contest_all_teams,$player->Team);
					}
				}

				foreach($contest_all_games as $current_game){

					foreach($contest_all_teams as $current_team){

						foreach($content as $player){

							if($current_game == $player->GlobalGameID && $current_team == $player->Team ){

								foreach ($all_teams as $team_cat) {

									if ($team_cat->team_abbrev == $player->Team) {

										$team_information_data[$current_team.$current_game] = array(
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

				}

				foreach($content as $player){
					if(!in_array($player->Position,$contest_player_types)){
						array_push($contest_player_types,$player->Position);
					}
				}

				foreach($contest_all_games as $current_game){

					foreach($contest_all_teams as $current_team){

						$current_team_players = array();

						foreach ($contest_player_types as $player_type) {
							${'player_'.$player_type} = 0;
						}

						foreach($content as $player){
							if($current_game == $player->GlobalGameID && $current_team == $player->Team){
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
						
						$team_players[$current_team.$current_game] = $current_team_players;
					}

				}

				foreach($contest_all_games as $current_game){

					foreach($contest_all_teams as $current_team){

						$play_total_points = 0;
						
						foreach($team_players[$current_team.$current_game] as $points){
							$play_total_points += $points;
						}

						$team_information_data[$current_team.$current_game]['total_points'] = $play_total_points;
						$team_information_data[$current_team.$current_game]['player'] = $team_players[$current_team.$current_game];
					}
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
												if($team1_moneyline == 0){
													$team1_moneyline = $dfsslategames->Game->AwayTeamMoneyLine;
												}
												if($team2_moneyline == 0){
													$team2_moneyline = $dfsslategames->Game->HomeTeamMoneyLine;
												}
											}
										}
									}

									$team1_spread = (number_format(($team_2['total_points'] -  $team_1['total_points']),2));
									$team2_spread = (number_format(($team_1['total_points'] -  $team_2['total_points']),2));
									$team_overunder = round(number_format(($team_1['total_points'] +  $team_2['total_points']),2));

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

									if(isset($current_game['team1']['name'])){
										array_push($game_data,$current_game);
									}

								}
							}

						}

					}

				}

				
				if(!empty($game_data)){
					$sort = array();
					foreach ($game_data as $key => $part) {
						$sort[$key] = strtotime($part['game_start']);
					}
					array_multisort($sort, SORT_ASC, $game_data);
				//adding rotation numbers
				for ($i=0;$i<count($game_data);$i++){
					$game_data[$i]['team1']['rotation_number'] =  "F".(++$league_count);
					$game_data[$i]['team2']['rotation_number'] =  "F".(++$league_count);
					
				}
					update_field('contest_data', json_encode($game_data, JSON_UNESCAPED_UNICODE), $updatepost->ID);

				}

			}

		}

	}

}

//New Update Function
function update_nhl_live_scores($stats_key) {
					
	//Define vars
	$parent_team = 42;
	$tax_league = 3;
	$league_title = 'NHL';
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
		
									${'players_'.$player_type.'1'} += $player->FantasyPointsDraftKings;
		
								}
		
		
							}
		
						}	
						
						if($player->Team == $team2['team_abbrev']){
		
							foreach ($contest_player_types as $player_type) {
		
								if($player->Position == $player_type){
		
									${'players_'.$player_type.'2'} += $player->FantasyPointsDraftKings;
		
								}
		
		
							}
		
						}	
					}
		
					

					$team1['total_points'] = 0;
					$team2['total_points'] = 0;
					
					foreach ($contest_player_types as $player_type) {
						
						$team_1_players[$player_type] = ${'players_'.$player_type.'1'};
						$team1['total_points'] += ${'players_'.$player_type.'1'};
						$team_2_players[$player_type] = ${'players_'.$player_type.'2'};
						$team2['total_points'] += ${'players_'.$player_type.'2'};
						
					}

					
					$contest_date_time = $content->Game->DateTime;
					$gameID = $content->Game->GlobalGameID;
					$contest_end_date_time = $content->Game->GameEndDateTime;

					//check if the game is suspended
					$all_status = ['Suspended', 'Postponed', 'Delayed', 'Canceled'];
					if( in_array($content->Game->Status,$all_status) ){
						$contest_game_over = "canceled";
					}else{
						$contest_game_over = $content->Game->IsClosed;
					}

					$team1['overunder'] = $team2['overunder'] = $content->Game->OverUnder;
					$team1['moneyline'] = $content->Game->AwayTeamMoneyLine;
					$team2['moneyline'] = $content->Game->HomeTeamMoneyLine;
					if($team1['total_points'] > $team2['total_points'])
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
					$current_game['game_end'] = $contest_end_date_time;
					$current_game['is_game_over'] = $contest_game_over;
					$current_game['player_types'] = $contest_player_types;
					$current_game['team1'] = $team1;
					$current_game['team2'] = $team2;
					
					if(isset($current_game['team1']['name'])){
						array_push($game_data,$current_game);
					}
		
				}		
				
				$game_done = "Done";
				
				$end_dates = array();
				foreach($game_data as $game){
					array_push($end_dates,$game['game_end']);
				}
				foreach($game_data as $game){
					if(!$game['is_game_over'] && $game['is_game_over'] != "canceled"){
						$game_done = "Not Done";
					}
				}

				if($game_done == "Done"){					
					$end_date = strtotime(max(array_filter($end_dates)))+28800;
					$current_time =  current_time( 'timestamp');

					if($end_date > $current_time){
						$game_done == "Not Done";
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
function update_live_nhl_contests($stats_key, $completed) {
	
	$goals_VAL = get_option('nhl-points-goal');
	$assists_VAL = get_option('nhl-points-assist');
	$shotsongoal_VAL = get_option('nhl-points-shotongoal');
	$blockedshots_VAL = get_option('nhl-points-blockedshot');
	$shorthandedpointsbonus_VAL = get_option('nhl-points-shorthandedpointbonus');
	$shootoutgoals_VAL = get_option('nhl-points-shootoutgoal');
	$hattricksbonus_VAL = get_option('nhl-points-hattrickbonus');
	$goalwins_VAL = get_option('nhl-points-goalwin');
	$goalsaves_VAL = get_option('nhl-points-goalsave');
	$goalsagainst_VAL = get_option('nhl-points-goalagainst');
	$shutoutsbonus_VAL = get_option('nhl-points-shutoutbonus');
	
	//Update Live NHL Contests
	
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
				'terms'    => 'nhl',
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
			
			$response = wp_remote_get( "https://fly.sportsdata.io/v3/nhl/stats/json/PlayerGameStatsByDate/$contest_date_url", array(
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
						$result->total_points_defenseplayer = 0;
						$result->total_points_goalie = 0;
						$result->total_points_leftwing = 0;
						$result->total_points_rightwing = 0;
												
						foreach ($result->players as $players) {
							
							if (is_array($players)) {
								
								foreach ($players as $player) {

									$player = setPlayerValuesto0($player);
						            
						            foreach ($data as $stats_player) {
							            
							            if ($player->game_id == $stats_player->GlobalGameID) {
							            
											if ($player->player_id == $stats_player->PlayerID) {
									           
									            $player = givePlayerPointsValue($player,$stats_player);
												
												if ($player->position == 'C') {
													
													$result->total_points_center = $result->total_points_center + $player->total_points;
													
												}
												if ($player->position == 'LW') {
													
													$result->total_points_leftwing = $result->total_points_leftwing + $player->total_points;
													
												}
												if ($player->position == 'RW') {
													
													$result->total_points_rightwing = $result->total_points_rightwing + $player->total_points;
													
												}
												if ($player->position == 'D') {
													
													$result->total_points_defenseplayer = $result->total_points_defenseplayer + $player->total_points;
													
												}
												if ($player->position == 'G') {
													
													$result->total_points_goalie = $result->total_points_goalie + $player->total_points;
													
												}
												
												$result->total_points = $result->total_points_center + $result->total_points_leftwing + $result->total_points_rightwing + $result->total_points_defenseplayer + $result->total_points_goalie;
									           
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
					update_field('contest_live_stats_url', 'https://fly.sportsdata.io/v3/nhl/stats/json/PlayerGameStatsByDate/'.$contest_date_url.'?key='.$stats_key, $post->ID);
					
					$contests_updated++;
					
				}
				else if ($contest_type == 'Mixed') {
					
					
					foreach ($contest_results as $result) {
						
						$result->total_points = 0;
						
						$k = 0;
						foreach ($result as $team) {
							
							if (is_array($team)) {
							
								foreach ($team as $player) {
									
									$player = setPlayerValuesto0($player);
									
									foreach ($data as $stats_player) {
							            
										if ($player->player_id == $stats_player->PlayerID) {
								           
								            $player = givePlayerPointsValue($player,$stats_player);
											
											$result->total_points = $result->total_points+$player->total_points;
								           
							            }
							           
						            }
									
								}
							
							}
							
							if (is_object($team)){
								
								$player = $team;
								
								$player = setPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
							            
									if ($player->player_id == $stats_player->PlayerID) {
							           
							            $player = givePlayerPointsValue($player,$stats_player);
										
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
					update_field('contest_live_stats_url', 'https://fly.sportsdata.io/v3/nhl/stats/json/PlayerGameStatsByDate/'.$contest_date_url.'?key='.$stats_key, $post->ID);
					
					$contests_updated++;
								
				}
				else if ($contest_type == 'Team vs Team') {
					
				
					foreach ($contest_results as $game) {
						
						foreach ($game as $team) {
							
							$players = $team->players;
							
							$center = $players->Center;
							$leftwing = $players->Leftwing;
							$rightwing = $players->Rightwing;
							$defenseplayer = $players->Defenseplayer;
							$goalie = $players->Goalie;
							
							$team->total_points = 0;
							$team->total_points_center = 0;
							$team->total_points_leftwing = 0;
							$team->total_points_rightwing = 0;
							$team->total_points_defenseplayer = 0;
							$team->total_points_goalie = 0;
							
							foreach ($center as $player) {
								
								$player = setPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = givePlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
									}
									
								}
								
							}
							
							foreach ($leftwing as $player) {
								
								$player = setPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = givePlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
									}
									
								}
								
							}
							
							foreach ($rightwing as $player) {
								
								$player = setPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = givePlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
									}
									
								}
								
							}
							
							foreach ($defenseplayer as $player) {
								
								$player = setPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = givePlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
									}
									
								}
								
							}
							
							foreach ($goalie as $player) {
								
								$player = setPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = givePlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
									}
									
								}
								
							}
														
						}
						
					}

					
					
					update_field('contest_results', json_encode($contest_results, JSON_UNESCAPED_UNICODE), $post->ID);
					update_field('contest_live_stats_url', 'https://fly.sportsdata.io/v3/nhl/stats/json/PlayerGameStatsByDate/'.$contest_date_url.'?key='.$stats_key, $post->ID);
					
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
		echo '<div id="message" class="updated fade"><p>'.$contests_updated.' Live NHL contests updated.</p></div>';
	}
	
	$cron_log = array(
		'post_status' => 'draft',
		'post_title' => 'NHL Cron Log - Update Live',
		'post_type' => 'cron_log',
		'post_content' => date('m-d-Y g:i a', time()) . ' ET',
		'tax_input' => array (
			'cron_type' => 4593,
		),
	);
	
	wp_set_current_user(1);
	wp_insert_post( $cron_log );
	
}

function update_projection_for_nhl_contest($stats_key){

	$goals_VAL = get_option('nhl-points-goal');
	$assists_VAL = get_option('nhl-points-assist');
	$shotsongoal_VAL = get_option('nhl-points-shotongoal');
	$blockedshots_VAL = get_option('nhl-points-blockedshot');
	$shorthandedpointsbonus_VAL = get_option('nhl-points-shorthandedpointbonus');
	$shootoutgoals_VAL = get_option('nhl-points-shootoutgoal');
	$hattricksbonus_VAL = get_option('nhl-points-hattrickbonus');
	$goalwins_VAL = get_option('nhl-points-goalwin');
	$goalsaves_VAL = get_option('nhl-points-goalsave');
	$goalsagainst_VAL = get_option('nhl-points-goalagainst');
	$shutoutsbonus_VAL = get_option('nhl-points-shutoutbonus');

	$args = array(
		'post_type' => 'contest',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key'     => 'contest_date_sort',
				'value'   => date('Y-m-d', current_time( 'timestamp')),
				'compare' => 'LIKE'
			)
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'league',
				'field'    => 'slug',
				'terms'    => 'nhl',
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
			
			$response = wp_remote_get( "https://fly.sportsdata.io/v3/nhl/projections/json/PlayerGameProjectionStatsByDate/$contest_date_url", array(
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
						$result->total_points_defenseplayer = 0;
						$result->total_points_goalie = 0;
						$result->total_points_leftwing = 0;
						$result->total_points_rightwing = 0;
												
						foreach ($result->players as $players) {
							
							if (is_array($players)) {
								
								foreach ($players as $player) {

									$player = setPlayerValuesto0($player);
						            
						            foreach ($data as $stats_player) {
							            
							            if ($player->game_id == $stats_player->GlobalGameID) {
							            
											if ($player->player_id == $stats_player->PlayerID) {
									           
									            $player = givePlayerPointsValue($player,$stats_player);
												
												if ($player->position == 'C') {
													
													$result->total_points_center = $result->total_points_center + $player->total_points;
													
												}
												if ($player->position == 'LW') {
													
													$result->total_points_leftwing = $result->total_points_leftwing + $player->total_points;
													
												}
												if ($player->position == 'RW') {
													
													$result->total_points_rightwing = $result->total_points_rightwing + $player->total_points;
													
												}
												if ($player->position == 'D') {
													
													$result->total_points_defenseplayer = $result->total_points_defenseplayer + $player->total_points;
													
												}
												if ($player->position == 'G') {
													
													$result->total_points_goalie = $result->total_points_goalie + $player->total_points;
													
												}
												
												$result->total_points = $result->total_points_center + $result->total_points_leftwing + $result->total_points_rightwing + $result->total_points_defenseplayer + $result->total_points_goalie;
									           
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
									
									$player = setPlayerValuesto0($player);
									
									foreach ($data as $stats_player) {
							            
										if ($player->player_id == $stats_player->PlayerID) {
								           
								            $player = givePlayerPointsValue($player,$stats_player);
											
											$result->total_points = $result->total_points+$player->total_points;
								           
							            }
							           
						            }
									
								}
							
							}
							
							if (is_object($team)){
								
								$player = $team;
								
								$player = setPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
							            
									if ($player->player_id == $stats_player->PlayerID) {
							           
							            $player = givePlayerPointsValue($player,$stats_player);
										
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
							$leftwing = $players->Leftwing;
							$rightwing = $players->Rightwing;
							$defenseplayer = $players->Defenseplayer;
							$goalie = $players->Goalie;
							
							$team->total_points = 0;
							$team->total_points_center = 0;
							$team->total_points_leftwing = 0;
							$team->total_points_rightwing = 0;
							$team->total_points_defenseplayer = 0;
							$team->total_points_goalie = 0;
							
							foreach ($center as $player) {
								
								$player = setPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = givePlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
									}
									
								}
								
							}
							
							foreach ($leftwing as $player) {
								
								$player = setPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = givePlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
									}
									
								}
								
							}
							
							foreach ($rightwing as $player) {
								
								$player = setPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = givePlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
									}
									
								}
								
							}
							
							foreach ($defenseplayer as $player) {
								
								$player = setPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = givePlayerPointsValue($player,$stats_player);
										
										$team->total_points = $team->total_points+$player->total_points;
										
									}
									
								}
								
							}
							
							foreach ($goalie as $player) {
								
								$player = setPlayerValuesto0($player);
								
								foreach ($data as $stats_player) {
								
									if ($player->player_id == $stats_player->PlayerID) {
										
										$player = givePlayerPointsValue($player,$stats_player);
										
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


// Process completed contests and wagers

function process_finished_nhl_contests($stats_key) {
	
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
				'terms'    => 'nhl',
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
	
	if (!empty($contest_dates)) {
			
		$postponed_contests = array();
		
		$currentDay = date('d-M-Y');
		$addOneDayToCurrentDay = date('d-M-Y', strtotime($date . ' +1 day'));

		foreach ($contest_dates as $contest_date) {
			
			
			$response = wp_remote_get( "https://fly.sportsdata.io/v3/nhl/scores/json/GamesByDate/$contest_date", array(
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
						break;
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
					
					echo '<div id="message" class="updated fade error"><p>There are still nhl contests pending. Please try again when today\'s games are over. (Error Code 4)</p></div>';
					
				}
	
			}	
			else {
				
				echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 3)</p></div>';
				
			}	
							
		}
	
	}
	else {
		
		echo '<div id="message" class="updated fade error"><p>There are no nhl games in progress. Please try again later. (Error Code 5)</p></div>';
		
	}
	
	
	
	if ($continue == true) { 
	
	
		// Update stats for completed contests
	
		update_live_nhl_contests($stats_key, true);
	
	
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
					'terms'    => 'nhl',
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
							
							
							// nhl POSTPONEMENTS: if postponed, push all involved wagers and return cash
							
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
							
							
							// nhl POSTPONEMENTS: if postponed, push all involved wagers and return cash
							
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

// add_action( 'nhl_update_live_cron', 'nhl_update_live_cron' );

function nhl_update_live_cron() {
	
	// $stats_key = '562f123e387a4c2bbb37395741d0a539';
	// update_live_nhl_contests($stats_key, false);
	
	/*
	$cron_log = array(
		'post_status' => 'draft',
		'post_title' => 'nhl Cron Log - Update Live',
		'post_type' => 'cron_log',
		'post_content' => date('m-d-Y g:i a', time()) . ' ET',
		'tax_input' => array (
			'cron_type' => 4598,
		),
	);
	
	wp_set_current_user(1);
	wp_insert_post( $cron_log );
	*/
	
}

?>