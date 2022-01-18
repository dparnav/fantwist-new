<?php
	
//Register settings and options

function pariwagerMLSpoints() {
		
	register_setting( 'pariwager-mls-points', 'mls-points-goals' );
	register_setting( 'pariwager-mls-points', 'mls-points-assists' );
	register_setting( 'pariwager-mls-points', 'mls-points-shots' );
	register_setting( 'pariwager-mls-points', 'mls-points-shotsongoal' );
	register_setting( 'pariwager-mls-points', 'mls-points-crosses' );
	register_setting( 'pariwager-mls-points', 'mls-points-foulsdrawn' );
	register_setting( 'pariwager-mls-points', 'mls-points-foulsconceded' );
	register_setting( 'pariwager-mls-points', 'mls-points-tackleswon' );
	register_setting( 'pariwager-mls-points', 'mls-points-passintercepted' );
	register_setting( 'pariwager-mls-points', 'mls-points-yellowcard' );
	register_setting( 'pariwager-mls-points', 'mls-points-redcard' );
	register_setting( 'pariwager-mls-points', 'mls-points-penaltykickmiss' );
	register_setting( 'pariwager-mls-points', 'mls-points-cleansheet' );
	register_setting( 'pariwager-mls-points', 'mls-points-goaliesave' );
	register_setting( 'pariwager-mls-points', 'mls-points-goalconceded' );
	register_setting( 'pariwager-mls-points', 'mls-points-goaliecleansheet' );
	register_setting( 'pariwager-mls-points', 'mls-points-goaliewin' );
	register_setting( 'pariwager-mls-points', 'mls-points-penaltykicksave' );
	register_setting( 'pariwager-mls-points', 'mls-last-updated-live' );
	   
}
add_action( 'admin_init', 'pariwagerMLSpoints' );





//Update MLS Projections and Contests

function create_mls_projections_and_contests($date, $projection_key) {
	
	//Define vars
	
	$mixed_attackers = array();
	$mixed_midfielders = array();
	$mixed_defenders = array();
	$mixed_goalies = array();
	
	$parent_team = 2988;
	$tax_league = 4;
	$league_title = 'MLS';
	
	$goals_VAL = get_option('mls-points-goals');
	$assists_VAL = get_option('mls-points-assists');
	$shots_VAL = get_option('mls-points-shots');
	$shotsongoal_VAL = get_option('mls-points-shotsongoal');
	$crosses_VAL = get_option('mls-points-crosses');
	$foulsdrawn_VAL = get_option('mls-points-foulsdrawn');
	$foulsconceded_VAL = get_option('mls-points-foulsconceded');
	$tackleswon_VAL = get_option('mls-points-tackleswon');
	$passintercepted_VAL = get_option('mls-points-passintercepted');
	$yellowcard_VAL = get_option('mls-points-yellowcard');
	$redcard_VAL = get_option('mls-points-redcard');
	$penaltykickmiss_VAL = get_option('mls-points-penaltykickmiss');
	$cleansheet_VAL = get_option('mls-points-cleansheet');
	$goaliesave_VAL = get_option('mls-points-goaliesave');
	$goalconceded_VAL = get_option('mls-points-goalconceded');
	$goaliecleansheet_VAL = get_option('mls-points-goaliecleansheet');
	$goaliewin_VAL = get_option('mls-points-goaliewin');
	$penaltykicksave_VAL = get_option('mls-points-penaltykicksave');
	
		
	//Get player projections via SportsDataIO API

	$response = wp_remote_get( "https://fly.sportsdata.io/v3/soccer/projections/json/PlayerGameProjectionStatsByCompetition/MLS/$date", array(
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
		
		/*		
		echo '<pre>';
		print_r($data);
		echo '</pre>';
		
		exit;
		*/
	   	 
	   	$ts = array(); 
	   	 
	    foreach ($data as $player) {
		    
		    $count = 0;
		    
		    $contest_date_time = $player->DateTime;
		    $contest_date = date('Y-m-d', strtotime($player->DateTime));
		    $contest_date_utc = date('U', strtotime($player->DateTime));
		    $goals = $player->Goals;
			$assists = $player->Assists;
			$shots = $player->Shots;
			$shotsongoal = $player->ShotsOnGoal;
			$crosses = $player->Crosses;
			$foulsconceded = $player->Fouls;
			$foulsdrawn = $player->Fouled;
			$tackleswon = $player->TacklesWon;
			$passintercepted = $player->Interceptions;
			$yellowcards = $player->YellowCards;
			$redcards = $player->RedCards;
			$penaltykickmisses = $player->PenaltyKickMisses;
			$cleansheets = $player->DefenderCleanSheets;
			$goaliesaves = $player->GoalkeeperSaves;
			$goalsconceded = $player->GoalkeeperGoalsAgainst;
			$goaliecleansheets = $player->GoalkeeperCleanSheets;
			$goaliewins = $player->GoalkeeperWins;
			$penaltykicksaves = $player->PenaltyKickSaves;
			$opponent = $player->GlobalOpponentId;
			$opponent_abbrev = $player->Opponent;
			$home_away = strtolower($player->HomeOrAway);
			$position = $player->Position;
			$position_category = $player->PositionCategory;
		    $gameID = $player->GlobalGameId;
		  	
		  	/*											
			if (isset($cutoff_datetime) == false) {
				$cutoff_datetime = $contest_date_utc;
			}
			else {
				
				if ($contest_date_utc < $cutoff_datetime) {
					$cutoff_datetime = $contest_date_utc;
				} 
				
			}
			*/
			
			if (!in_array($opponent, $ts)) {
				$ts[] = $opponent_abbrev;
			}
			
		}
		
		/*
		$the_contest_datetime = strtoupper(date('m-d-Y g:i a', $contest_date_utc));
		echo $the_contest_datetime;
		*/
		
		echo "https://fly.sportsdata.io/v3/soccer/projections/json/PlayerGameProjectionStatsByCompetition/MLS/$date";
		print_r($ts);
		
		
		
		
		
		
		
		
		
			
		/*
			$total_points = ($wins*$pitchingwins_VAL) + ($inningspitched*$inningspitched_VAL) + ($hitsagainst*$pitchinghits_VAL) + ($earnedruns*$earnedruns_VAL) + ($pitchingwalks*$pitchingwalks_VAL) + ($hitbypitch*$pitchinghitbypitch_VAL) + ($strikeouts*$pitchingstrikeouts_VAL) + ($nohitter*$pitchingnohitters_VAL) + ($completegameshutouts*$pitchingcompletegameshutouts_VAL);
				
			$position = 'P';
			$position_category = 'P';
			
			
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
			    'runs' => 0,
			    'runs_points' => 0,
			    'singles' => 0,
				'singles_points' => 0,
				'doubles' => 0,
				'doubles_points' => 0,
				'triples' => 0,
				'triples_points' => 0,
				'homeruns' => 0,
				'homeruns_points' => 0,
				'rbis' => 0,
				'rbis_points' => 0,
				'walks' => 0,
				'walks_points' => 0,
				'stolen_bases' => 0,
				'stolen_bases_points' => 0,
				'caught_stealing' => 0,
				'caught_stealing_points' => 0,
				'wins' => $wins,
				'wins_points' => number_format($wins*$pitchingwins_VAL, 2),
				'innings_pitched' => $inningspitched,
				'innings_pitched_points' => number_format($inningspitched*$inningspitched_VAL, 2),
				'hits_against' => $hitsagainst,
				'hits_against_points' => number_format($hitsagainst*$pitchinghits_VAL, 2),
				'earned_runs' => $earnedruns,
				'earned_runs_points' => number_format($earnedruns*$earnedruns_VAL, 2),
				'pitching_walks' => $pitchingwalks,
				'pitching_walks_points' => number_format($pitchingwalks*$pitchingwalks_VAL, 2),
				'hitbatsmen' => $hitbypitch,
				'hitbatsmen_points' => number_format($hitbypitch*$pitchinghitbypitch_VAL, 2),
				'strikeouts' => $strikeouts,
				'strikeouts_points' => number_format($strikeouts*$pitchingstrikeouts_VAL, 2),
				'nohitter' => $nohitter,
				'nohitter_points' => number_format($nohitter*$pitchingnohitters_VAL, 2),
				'complete_game_shutouts' => $completegameshutouts,
				'complete_game_shutouts_points' => number_format($completegameshutouts*$pitchingcompletegameshutouts_VAL, 2),
				'game_id' => $gameID,
				'total_points' => $total_points
		    );
			
			
			*/
			
			
			
			
			
			
			
			
			
			
			
			/*  
			if ($position == 'SP' || $position == 'P' || $position == 'RP' || $position_category == 'P' || $position_category == 'SP' || $position_category == 'RP') {
				
				$total_points = ($wins*$pitchingwins_VAL) + ($inningspitched*$inningspitched_VAL) + ($hitsagainst*$pitchinghits_VAL) + ($earnedruns*$earnedruns_VAL) + ($pitchingwalks*$pitchingwalks_VAL) + ($hitbypitch*$pitchinghitbypitch_VAL) + ($strikeouts*$pitchingstrikeouts_VAL) + ($nohitter*$pitchingnohitters_VAL) + ($completegameshutouts*$pitchingcompletegameshutouts_VAL);
				
				$position = 'P';
				$position_category = 'P';
				
				
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
				    'runs' => 0,
				    'runs_points' => 0,
				    'singles' => 0,
					'singles_points' => 0,
					'doubles' => 0,
					'doubles_points' => 0,
					'triples' => 0,
					'triples_points' => 0,
					'homeruns' => 0,
					'homeruns_points' => 0,
					'rbis' => 0,
					'rbis_points' => 0,
					'walks' => 0,
					'walks_points' => 0,
					'stolen_bases' => 0,
					'stolen_bases_points' => 0,
					'caught_stealing' => 0,
					'caught_stealing_points' => 0,
					'wins' => $wins,
					'wins_points' => number_format($wins*$pitchingwins_VAL, 2),
					'innings_pitched' => $inningspitched,
					'innings_pitched_points' => number_format($inningspitched*$inningspitched_VAL, 2),
					'hits_against' => $hitsagainst,
					'hits_against_points' => number_format($hitsagainst*$pitchinghits_VAL, 2),
					'earned_runs' => $earnedruns,
					'earned_runs_points' => number_format($earnedruns*$earnedruns_VAL, 2),
					'pitching_walks' => $pitchingwalks,
					'pitching_walks_points' => number_format($pitchingwalks*$pitchingwalks_VAL, 2),
					'hitbatsmen' => $hitbypitch,
					'hitbatsmen_points' => number_format($hitbypitch*$pitchinghitbypitch_VAL, 2),
					'strikeouts' => $strikeouts,
					'strikeouts_points' => number_format($strikeouts*$pitchingstrikeouts_VAL, 2),
					'nohitter' => $nohitter,
					'nohitter_points' => number_format($nohitter*$pitchingnohitters_VAL, 2),
					'complete_game_shutouts' => $completegameshutouts,
					'complete_game_shutouts_points' => number_format($completegameshutouts*$pitchingcompletegameshutouts_VAL, 2),
					'game_id' => $gameID,
					'total_points' => $total_points
			    );
			    				    
			    if ($position_category == 'P') {
				    
				    //add to Teams array
			
					foreach ($all_teams as $team) {
						
						if ($team->TeamID == $player->GlobalTeamID) {
							
							if ($gameID == $team->game_1 || $gameID == $team->game_2) {
							
								$team->home_away = $home_away;
								$team->opponent = $team->TeamID;
								$team->opponent_abbrev = $opponent_abbrev;
								$team->players['pitchers'][] = $contest_player;
								$team->total_points_pitchers = number_format($team->total_points_pitchers + $total_points, 2);
								$team->total_points = number_format($team->total_points + $total_points, 2);
								$team->game_start = $contest_date_time;
								
								$mixed_pitchers[] = $contest_player;
							
							}
							
						}
						
					}
				    
			    }
				
			}
			else {
				
				$total_points = ($runs*$goals_VAL) + ($singles*$singles_VAL) + ($doubles*$doubles_VAL) + ($triples*$triples_VAL) + ($homeruns*$homeruns_VAL) + ($rbis*$runsbattedin_VAL) + ($walks*$walks_VAL) + ($stolenbases*$stolenbases_VAL) + ($caughtstealing*$caughtstealing_VAL);
				
				
				$total_points = number_format($total_points, 2);
				
				if ($position == 'C') {
					
					$position_category = 'C';
					
				}
				
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
				    'goals_points' => number_format($goals*$goals_VAL, 2),
				    'singles' => $singles,
					'singles_points' => number_format($singles*$singles_VAL, 2),
					'doubles' => $doubles,
					'doubles_points' => number_format($doubles*$doubles_VAL, 2),
					'triples' => $triples,
					'triples_points' => number_format($triples*$triples_VAL, 2),
					'homeruns' => $homeruns,
					'homeruns_points' => number_format($homeruns*$homeruns_VAL, 2),
					'rbis' => $rbis,
					'rbis_points' => number_format($rbis*$runsbattedin_VAL, 2),
					'walks' => $walks,
					'walks_points' => number_format($walks*$walks_VAL, 2),
					'stolen_bases' => $stolenbases,
					'stolen_bases_points' => number_format($stolenbases*$stolenbases_VAL, 2),
					'caught_stealing' => $caughtstealing,
					'caught_stealing_points' => number_format($caughtstealing*$caughtstealing_VAL, 2),
					'wins' => 0,
					'wins_points' => 0,
					'innings_pitched' => 0,
					'innings_pitched_points' => 0,
					'hits_against' => 0,
					'hits_against_points' => 0,
					'earned_runs' => 0,
					'earned_runs_points' => 0,
					'pitching_walks' => 0,
					'pitching_walks_points' => 0,
					'hitbatsmen' => 0,
					'hitbatsmen_points' => 0,
					'strikeouts' => 0,
					'strikeouts_points' => 0,
					'nohitter' => 0,
					'nohitter_points' => 0,
					'complete_game_shutouts' => 0,
					'complete_game_shutouts_points' => 0,
					'game_id' => $gameID,
					'total_points' => $total_points,
			    );
			    
			    if ($position_category == 'IF') {
				    
				    
				    
				    //add to Teams array
					
					foreach ($all_teams as $team) {
						
						if ($team->TeamID == $player->GlobalTeamID) {
								
							if ($gameID == $team->game_1 || $gameID == $team->game_2) {
																
								$team->home_away = $home_away;
								$team->opponent = $team->TeamID;
								$team->opponent_abbrev = $opponent_abbrev;
								$team->players['infielders'][] = $contest_player;
								$team->total_points_infielders = number_format($team->total_points_infielders + $total_points, 2);
								$team->total_points = number_format($team->total_points + $total_points, 2);
								$team->game_start = $contest_date_time;
								
								$mixed_infielders[] = $contest_player;
								
							}
							
						}
						
					}
				    
			    }
			    
			    if ($position_category == 'OF') {
				    
				    
				    
				    //add to Teams array
						
					foreach ($all_teams as $team) {
						
						if ($team->TeamID == $player->GlobalTeamID) {
							
							if ($gameID == $team->game_1 || $gameID == $team->game_2) {
																	
								$team->home_away = $home_away;
								$team->opponent = $team->TeamID;
								$team->opponent_abbrev = $opponent_abbrev;
								$team->players['outfielders'][] = $contest_player;
								$team->total_points_outfielders = number_format($team->total_points_outfielders + $total_points, 2);
								$team->total_points = number_format($team->total_points + $total_points, 2);
								$team->game_start = $contest_date_time;
								
								$mixed_outfielders[] = $contest_player;
							
							}
								
						}
						
					}
				    
			    }
			    
			    if ($position_category == 'C') {
				    
				   
				    
				    //add to Teams array
					
					foreach ($all_teams as $team) {
						
						if ($team->TeamID == $player->GlobalTeamID) {
							
							if ($gameID == $team->game_1 || $gameID == $team->game_2) {
							
								$team->home_away = $home_away;
								$team->opponent = $team->TeamID;
								$team->opponent_abbrev = $opponent_abbrev;
								$team->players['catchers'][] = $contest_player;
								$team->total_points_catchers = number_format($team->total_points_catchers + $total_points, 2);
								$team->total_points = number_format($team->total_points + $total_points, 2);
								$team->game_start = $contest_date_time;
								
								$mixed_catchers[] = $contest_player;
							
							}
							
						}
						
					}
				    
			    }
			    
			}
								    
		   

		}
		
		// If a team has not assigned a SP yet, grant them 10 points
		
		foreach ($all_teams as $team) {
			
			if ($team->total_points_pitchers == 0) {
				
				$team->total_points_pitchers = 10;
				$team->total_points = $team->total_points+10;
				
			}
		
		}
			
		
		// Sort Teams by total points
		
		$sort = array();
		foreach ($all_teams as $key => $part) {
			$sort[$key] = $part->total_points;
		}
		array_multisort($sort, SORT_DESC, $all_teams);
		
		
		
		//Add odds to each team
		
		$i = 2;
		foreach ($all_teams as $team) {
			
			$team->odds_to_1 = $i;
			$i++;
			
		}
		
		
		//Build Mixed Teams
		
		
		//Calculate maximum mixed-team count
		
	    $mixed_team_count = count($mixed_catchers);
	    
	    if (count($mixed_pitchers) < $mixed_team_count) {
		    $mixed_team_count = count($mixed_pitchers);
	    }
	    if (count($mixed_infielders) < $mixed_team_count) {
		    $mixed_team_count = count($mixed_infielders);
	    }
	    if (count($mixed_outfielders) < $mixed_team_count) {
		    $mixed_team_count = count($mixed_outfielders);
	    }
	    
	    
	    //Sort position groups by total points
	    
	    $sort = array();
		foreach ($mixed_catchers as $key => $part) {
			$sort[$key] = $part['total_points'];
		}
		array_multisort($sort, SORT_DESC, $mixed_catchers);
	    
	    $sort = array();
		foreach ($mixed_pitchers as $key => $part) {
			$sort[$key] = $part['total_points'];
		}
		array_multisort($sort, SORT_DESC, $mixed_pitchers);
		
		$sort = array();
		foreach ($mixed_infielders as $key => $part) {
			$sort[$key] = $part['total_points'];
		}
		array_multisort($sort, SORT_DESC, $mixed_infielders);
		
		$sort = array();
		foreach ($mixed_outfielders as $key => $part) {
			$sort[$key] = $part['total_points'];
		}
		array_multisort($sort, SORT_DESC, $mixed_outfielders);
	    
	    
	    
	    
	    //Build Mixed Teams
	    
	    $mixed_teams = array();
	    if ($mixed_team_count >= 25) {
		    $mixed_team_count = 25;
	    }
			    
	    $s = 0;
	    $t = 1;
	    
	    for ($i = 0; $i < $mixed_team_count; $i++) {
			
			$mixed_team = array(
				'team_name' => 'Team ' . ($i+1),
				'pitcher' => $mixed_pitchers[$i],
				'catcher' => $mixed_catchers[$i],
				'infielders' => array (
					$mixed_infielders[$s], $mixed_infielders[$t],
				),
				'outfielders' => array(
					$mixed_outfielders[$s], $mixed_outfielders[$t],
				),
				'total_points' => 0,
				'odds_to_1' => (int) ($i+2),
			);
			
			$s = $t+1;
			$t = $t+2;
			
			$mixed_teams[] = $mixed_team;	
						
	    } 
	    
	    $i = 0;
	    foreach ($mixed_teams as $mixed_team) {
		    
		    $total_points = $mixed_team['pitcher']['total_points'] + $mixed_team['catcher']['total_points'] + $mixed_team['infielders'][0]['total_points'] + $mixed_team['infielders'][1]['total_points'] + $mixed_team['outfielders'][0]['total_points'] + $mixed_team['outfielders'][1]['total_points'];
		    
		    $mixed_teams[$i]['total_points'] = number_format($total_points, 2);
		    
		    $i++;
	    }
		
		//Build Contests

		$the_contest_datetime = strtoupper(date('m-d-Y g:i a', strtotime($cutoff_datetime) - 60 * 60 * 3));
		$the_contest_date_unix = strtoupper(date('U', strtotime($cutoff_datetime) - 60 * 60 * 3));
		$the_contest_date_notime = date('m-d-Y', strtotime($cutoff_datetime) - 60 * 60 * 3);
		$the_contest_date_notime_day = date('l F jS', strtotime($cutoff_datetime) - 60 * 60 * 3);
		$the_contest_date_sort = strtoupper(date('Y-m-d H:i:s', strtotime($cutoff_datetime) - 60 * 60 * 3));
		$contests_created = 0;
				
		
		//Build Team Contests
		
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
				'contest_title_without_type' => $league_title . ': ' . $the_contest_date_notime_day,
			),
			'tax_input' => array (
				'league' => $tax_league,
			),
		);
		
		$post_exists = post_exists($league_title . ': Teams ' . $the_contest_date_notime);
				
		if ($post_exists == 0) {
			wp_insert_post( $teams_contest );
			$contests_created++;
			
		}
		else {
			$teams_contest['ID'] = $post_exists;
			wp_update_post( $teams_contest );
			$contests_updated++;
		}
		
		//Build Mixed Contests
		
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
				'contest_title_without_type' => $league_title . ': ' . $the_contest_date_notime_day,
			),
			'tax_input' => array (
				'league' => $tax_league,
			),
		);
		
		$post_exists = post_exists($league_title . ': Mixed ' . $the_contest_date_notime);
				
		if ($post_exists == 0) {
			wp_insert_post( $mixed_contest );
			$contests_created++;
			
		}
		
		echo '<div id="message" class="updated fade"><p>'.$contests_created.' MLS contests created.</p></div>';
		
		*/
	
	}
	else {
	
		echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 1)</p></div>';
	
	}
	
}




//Update Live Contests

function update_live_mls_contests($stats_key, $completed) {
	
	$goals_VAL = get_option('mls-points-goals');
	$singles_VAL = get_option('mls-points-assists');
	$doubles_VAL = get_option('mls-points-shots');
	$triples_VAL = get_option('mls-points-shotsongoal');
	$homeruns_VAL = get_option('mls-points-crosses');
	$runsbattedin_VAL = get_option('mls-points-foulsdrawn');
	$walks_VAL = get_option('mls-points-foulsconceded');
	$stolenbases_VAL = get_option('mls-points-tackleswon');
	$caughtstealing_VAL = get_option('mls-points-passintercepted');
	$pitchingwins_VAL = get_option('mls-points-yellowcard');
	$inningspitched_VAL = get_option('mls-points-redcard');
	$pitchinghits_VAL = get_option('mls-points-penaltykickmiss');
	$earnedruns_VAL = get_option('mls-points-cleansheet');
	$pitchingwalks_VAL = get_option('mls-points-goaliesave');
	$pitchinghitbypitch_VAL = get_option('mls-points-goalconceded');
	$pitchingstrikeouts_VAL = get_option('mls-points-goaliecleansheet');
	$pitchingnohitters_VAL = get_option('mls-points-goaliewin');
	$pitchingcompletegameshutouts_VAL = get_option('mls-points-penaltykicksave');
	
	//Update Live MLS Contests
	
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
				'terms'    => 'mls',
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
			
			
			
			//Retrieve live player stats via SportsDataIO API
			
			$contest_date_url = strtoupper(date('d-M-Y', $this_contest_date));
			
			$response = wp_remote_get( "https://fly.sportsdata.io/v3/mlb/stats/JSON/PlayerGameStatsByDate/$contest_date_url", array(
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
						$result->total_points_pitchers = 0;
						$result->total_points_catchers = 0;
						$result->total_points_outfielders = 0;
						$result->total_points_infielders = 0;
												
						foreach ($result->players as $players) {
							
							if (is_array($players)) {
								
								foreach ($players as $player) {
									
									$player->goals = 0;
						            $player->goals_points = 0;
						            $player->singles = 0;
						            $player->singles_points = 0;
						            $player->doubles = 0;
						            $player->doubles_points = 0;
						            $player->triples = 0;
						            $player->triples_points = 0;
						            $player->homeruns = 0;
						            $player->homeruns_points = 0;
						            $player->rbis = 0;
						            $player->rbis_points = 0;
						            $player->walks = 0;
						            $player->walks_points = 0;
						            $player->stolen_bases = 0;
						            $player->stolen_bases_points = 0;
						            $player->caught_stealing = 0;
						            $player->caught_stealing_points = 0;
						            $player->wins = 0;
						            $player->wins_points = 0;
						            $player->innings_pitched = 0;
						            $player->innings_pitched_points = 0;
						            $player->hits_against = 0;
						            $player->hits_against_points = 0;
						            $player->earned_runs = 0;
						            $player->earned_runs_points = 0;
						            $player->pitching_walks = 0;
						            $player->pitching_walks_points = 0;
						            $player->hitbatsmen = 0;
						            $player->hitbatsmen_points = 0;
						            $player->strikeouts = 0;
						            $player->strikeouts_points = 0;
						            $player->nohitter = 0;
						            $player->nohitter_points = 0;
						            $player->complete_game_shutouts = 0;
						            $player->complete_game_shutouts_points = 0;
						            $player->total_points = 0;
						            
						            foreach ($data as $stats_player) {
							            
							            if ($player->game_id == $stats_player->GlobalGameID) {
							            
											if ($player->player_id == $stats_player->PlayerID) {
									           
									            if ($player->position_category == 'P') {
										            
										            //Pitchers are not credited with hitting points
										            
										            $player->goals = $stats_player->Runs;
													$player->runs_points = 0;
													$player->singles = $stats_player->Singles;
													$player->singles_points = 0;
													$player->doubles = $stats_player->Doubles;
													$player->doubles_points = 0;
													$player->triples = $stats_player->Triples;
													$player->triples_points = 0;
													$player->homeruns = $stats_player->HomeRuns;
													$player->homeruns_points = 0;
													$player->rbis = $stats_player->RunsBattedIn;
													$player->rbis_points = 0;
													$player->walks = $stats_player->Walks;
													$player->walks_points = 0;
													$player->stolen_bases = $stats_player->StolenBases;
													$player->stolen_bases_points = 0;
													$player->caught_stealing = $stats_player->CaughtStealing;
													$player->caught_stealing_points = 0;
													$player->wins = $stats_player->Wins;
													$player->wins_points = number_format($stats_player->Wins*$pitchingwins_VAL, 2);
													$player->innings_pitched = $stats_player->InningsPitchedDecimal;
													$player->innings_pitched_points = number_format($stats_player->InningsPitchedDecimal*$inningspitched_VAL, 2);
													$player->hits_against = $stats_player->PitchingHits;
													$player->hits_against_points = number_format($stats_player->PitchingHits*$pitchinghits_VAL, 2);
													$player->earned_runs = $stats_player->PitchingEarnedRuns;
													$player->earned_runs_points = number_format($stats_player->PitchingEarnedRuns*$earnedruns_VAL, 2);
													$player->pitching_walks = $stats_player->PitchingWalks;
													$player->pitching_walks_points = number_format($stats_player->PitchingWalks*$pitchingwalks_VAL, 2);
													$player->hitbatsmen = $stats_player->PitchingHitByPitch;
													$player->hitbatsmen_points = number_format($stats_player->PitchingHitByPitch*$pitchinghitbypitch_VAL, 2);
													$player->strikeouts = $stats_player->PitchingStrikeouts;
													$player->strikeouts_points = number_format($stats_player->PitchingStrikeouts*$pitchingstrikeouts_VAL, 2);
													$player->nohitter = $stats_player->PitchingNoHitters;
													$player->nohitter_points = number_format($stats_player->PitchingNoHitters*$pitchingnohitters_VAL, 2);
													
													$completegames = $stats_player->PitchingCompleteGames;
													if ($stats_player->PitchingEarnedRuns == 0 && $completegames != 0) {
														$completegameshutouts = $completegames;
													}
													else {
														$completegameshutouts = 0;
													}
													
													$player->complete_game_shutouts = $completegameshutouts;
													$player->complete_game_shutouts_points = number_format($completegameshutouts*$pitchingcompletegameshutouts_VAL, 2);
											            
									            }
									            else {
										            
										            //Hitters are not credited with pitching points
										            
										            $player->runs = $stats_player->Runs;
													$player->runs_points = number_format($player->runs*$goals_VAL, 2);
													$player->singles = $stats_player->Singles;
													$player->singles_points = number_format($stats_player->Singles*$singles_VAL, 2);
													$player->doubles = $stats_player->Doubles;
													$player->doubles_points = number_format($stats_player->Doubles*$doubles_VAL, 2);
													$player->triples = $stats_player->Triples;
													$player->triples_points = number_format($stats_player->Triples*$triples_VAL, 2);
													$player->homeruns = $stats_player->HomeRuns;
													$player->homeruns_points = number_format($stats_player->HomeRuns*$homeruns_VAL, 2);
													$player->rbis = $stats_player->RunsBattedIn;
													$player->rbis_points = number_format($stats_player->RunsBattedIn*$runsbattedin_VAL, 2);
													$player->walks = $stats_player->Walks;
													$player->walks_points = number_format($stats_player->Walks*$walks_VAL, 2);
													$player->stolen_bases = $stats_player->StolenBases;
													$player->stolen_bases_points = number_format($stats_player->StolenBases*$stolenbases_VAL, 2);
													$player->caught_stealing = $stats_player->CaughtStealing;
													$player->caught_stealing_points = number_format($stats_player->CaughtStealing*$caughtstealing_VAL, 2);
										         	$player->wins = $stats_player->Wins;
													$player->wins_points = 0;
													$player->innings_pitched = $stats_player->InningsPitchedDecimal;
													$player->innings_pitched_points = 0;
													$player->hits_against = $stats_player->PitchingHits;
													$player->hits_against_points = 0;
													$player->earned_runs = $stats_player->PitchingEarnedRuns;
													$player->earned_runs_points = 0;
													$player->pitching_walks = $stats_player->PitchingWalks;
													$player->pitching_walks_points = 0;
													$player->hitbatsmen = $stats_player->PitchingHitByPitch;
													$player->hitbatsmen_points = 0;
													$player->strikeouts = $stats_player->PitchingStrikeouts;
													$player->strikeouts_points = 0;
													$player->nohitter = $stats_player->PitchingNoHitters;
													$player->nohitter_points = 0;
													
													$completegames = $stats_player->PitchingCompleteGames;
													if ($stats_player->PitchingEarnedRuns == 0 && $completegames != 0) {
														$completegameshutouts = $completegames;
													}
													else {
														$completegameshutouts = 0;
													}
													
													$player->complete_game_shutouts = $completegameshutouts;
													$player->complete_game_shutouts_points = 0;
										         	   
									            }
										           
												$player->total_points = $player->runs_points + $player->singles_points + $player->doubles_points + $player->triples_points + $player->homeruns_points + $player->rbis_points + $player->walks_points + $player->stolen_bases_points + $player->caught_stealing_points + $player->wins_points + $player->innings_pitched_points + $player->hits_against_points + $player->earned_runs_points + $player->pitching_walks_points + $player->hitbatsmen_points + $player->strikeouts_points + $player->nohitter_points;
												
												if ($player->position_category == 'IF') {
													
													$result->total_points_infielders = $result->total_points_infielders + $player->total_points;
													
												}
												if ($player->position_category == 'OF') {
													
													$result->total_points_outfielders = $result->total_points_outfielders + $player->total_points;
													
												}
												if ($player->position_category == 'C') {
													
													$result->total_points_catchers = $result->total_points_catchers + $player->total_points;
													
												}
												if ($player->position_category == 'P') {
													
													$result->total_points_pitchers = $result->total_points_pitchers + $player->total_points;
													
												}
												
												$result->total_points = $result->total_points_pitchers + $result->total_points_outfielders + $result->total_points_catchers + $result->total_points_infielders;
									           
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
					
					$contests_updated++;
					
				}
				else if ($contest_type == 'Mixed') {
					
					
					foreach ($contest_results as $result) {
						
						$result->total_points = 0;
						
						$k = 0;
						foreach ($result as $team) {
							
							if (is_array($team)) {
							
								foreach ($team as $player) {
									
									$player->runs = 0;
									$player->runs_points = 0;
									$player->singles = 0;
									$player->singles_points = 0;
									$player->doubles = 0;
									$player->doubles_points = 0;
									$player->triples = 0;
									$player->triples_points = 0;
									$player->homeruns = 0;
									$player->homeruns_points = 0;
									$player->rbis = 0;
									$player->rbis_points = 0;
									$player-> walks = 0;
									$player-> walks_points = 0;
									$player->stolen_bases = 0;
									$player->stolen_bases_points = 0;
									$player->caught_stealing = 0;
									$player->caught_stealing_points = 0;
									$player->wins = 0;
									$player->wins_points = 0;
									$player->innings_pitched = 0;
									$player->innings_pitched_points = 0;
									$player->hits_against = 0;
									$player->hits_against_points = 0;
									$player->earned_runs = 0;
									$player->earned_runs_points = 0;
									$player->pitching_walks = 0;
									$player->pitching_walks_points = 0;
									$player->hitbatsmen = 0;
									$player->hitbatsmen_points = 0;
									$player->strikeouts = 0;
									$player->strikeouts_points = 0;
									$player->nohitter = 0;
									$player->nohitter_points = 0;
									$player->complete_game_shutouts = 0;
									$player->complete_game_shutouts_points = 0;
									$player->total_points = 0;
									
									foreach ($data as $stats_player) {
							            
										if ($player->player_id == $stats_player->PlayerID) {
								           
								            if ($player->position_category == 'P') {
									            
												
									            
									            //Pitchers are not credited with hitting points
									            
									            $player->runs = $stats_player->Runs;
												$player->runs_points = 0;
												$player->singles = $stats_player->Singles;
												$player->singles_points = 0;
												$player->doubles = $stats_player->Doubles;
												$player->doubles_points = 0;
												$player->triples = $stats_player->Triples;
												$player->triples_points = 0;
												$player->homeruns = $stats_player->HomeRuns;
												$player->homeruns_points = 0;
												$player->rbis = $stats_player->RunsBattedIn;
												$player->rbis_points = 0;
												$player->walks = $stats_player->Walks;
												$player->walks_points = 0;
												$player->stolen_bases = $stats_player->StolenBases;
												$player->stolen_bases_points = 0;
												$player->caught_stealing = $stats_player->CaughtStealing;
												$player->caught_stealing_points = 0;
												$player->wins = $stats_player->Wins;
												$player->wins_points = number_format($stats_player->Wins*$pitchingwins_VAL, 2);
												$player->innings_pitched = $stats_player->InningsPitchedDecimal;
												$player->innings_pitched_points = number_format($stats_player->InningsPitchedDecimal*$inningspitched_VAL, 2);
												$player->hits_against = $stats_player->PitchingHits;
												$player->hits_against_points = number_format($stats_player->PitchingHits*$pitchinghits_VAL, 2);
												$player->earned_runs = $stats_player->PitchingEarnedRuns;
												$player->earned_runs_points = number_format($stats_player->PitchingEarnedRuns*$earnedruns_VAL, 2);
												$player->pitching_walks = $stats_player->PitchingWalks;
												$player->pitching_walks_points = number_format($stats_player->PitchingWalks*$pitchingwalks_VAL, 2);
												$player->hitbatsmen = $stats_player->PitchingHitByPitch;
												$player->hitbatsmen_points = number_format($stats_player->PitchingHitByPitch*$pitchinghitbypitch_VAL, 2);
												$player->strikeouts = $stats_player->PitchingStrikeouts;
												$player->strikeouts_points = number_format($stats_player->PitchingStrikeouts*$pitchingstrikeouts_VAL, 2);
												$player->nohitter = $stats_player->PitchingNoHitters;
												$player->nohitter_points = number_format($stats_player->PitchingNoHitters*$pitchingnohitters_VAL, 2);
												
												$completegames = $stats_player->PitchingCompleteGames;
												if ($stats_player->PitchingEarnedRuns == 0 && $completegames != 0) {
													$completegameshutouts = $completegames;
												}
												else {
													$completegameshutouts = 0;
												}
												
												$player->complete_game_shutouts = $completegameshutouts;
												$player->complete_game_shutouts_points = number_format($completegameshutouts*$pitchingcompletegameshutouts_VAL, 2);
										            
								            }
								            else {
									            
									            //Hitters are not credited with pitching points
									            
									            $player->runs = $stats_player->Runs;
												$player->runs_points = number_format($player->runs*$goals_VAL, 2);
												$player->singles = $stats_player->Singles;
												$player->singles_points = number_format($stats_player->Singles*$singles_VAL, 2);
												$player->doubles = $stats_player->Doubles;
												$player->doubles_points = number_format($stats_player->Doubles*$doubles_VAL, 2);
												$player->triples = $stats_player->Triples;
												$player->triples_points = number_format($stats_player->Triples*$triples_VAL, 2);
												$player->homeruns = $stats_player->HomeRuns;
												$player->homeruns_points = number_format($stats_player->HomeRuns*$homeruns_VAL, 2);
												$player->rbis = $stats_player->RunsBattedIn;
												$player->rbis_points = number_format($stats_player->RunsBattedIn*$runsbattedin_VAL, 2);
												$player->walks = $stats_player->Walks;
												$player->walks_points = number_format($stats_player->Walks*$walks_VAL, 2);
												$player->stolen_bases = $stats_player->StolenBases;
												$player->stolen_bases_points = number_format($stats_player->StolenBases*$stolenbases_VAL, 2);
												$player->caught_stealing = $stats_player->CaughtStealing;
												$player->caught_stealing_points = number_format($stats_player->CaughtStealing*$caughtstealing_VAL, 2);
									         	$player->wins = $stats_player->Wins;
												$player->wins_points = 0;
												$player->innings_pitched = $stats_player->InningsPitchedDecimal;
												$player->innings_pitched_points = 0;
												$player->hits_against = $stats_player->PitchingHits;
												$player->hits_against_points = 0;
												$player->earned_runs = $stats_player->PitchingEarnedRuns;
												$player->earned_runs_points = 0;
												$player->pitching_walks = $stats_player->PitchingWalks;
												$player->pitching_walks_points = 0;
												$player->hitbatsmen = $stats_player->PitchingHitByPitch;
												$player->hitbatsmen_points = 0;
												$player->strikeouts = $stats_player->PitchingStrikeouts;
												$player->strikeouts_points = 0;
												$player->nohitter = $stats_player->PitchingNoHitters;
												$player->nohitter_points = 0;
												
												$completegames = $stats_player->PitchingCompleteGames;
												if ($stats_player->PitchingEarnedRuns == 0 && $completegames != 0) {
													$completegameshutouts = $completegames;
												}
												else {
													$completegameshutouts = 0;
												}
												
												$player->complete_game_shutouts = $completegameshutouts;
												$player->complete_game_shutouts_points = 0;
									         	   
								            }
									           
											$player->total_points = $player->runs_points + $player->singles_points + $player->doubles_points + $player->triples_points + $player->homeruns_points + $player->rbis_points + $player->walks_points + $player->stolen_bases_points + $player->caught_stealing_points + $player->wins_points + $player->innings_pitched_points + $player->hits_against_points + $player->earned_runs_points + $player->pitching_walks_points + $player->hitbatsmen_points + $player->strikeouts_points + $player->nohitter_points;
											
											$result->total_points = $result->total_points+$player->total_points;
								           
							            }
							           
						            }
									
								}
							
							}
							
							if (is_object($team)){
								
								$player = $team;
								
								$player->runs = 0;
								$player->runs_points = 0;
								$player->singles = 0;
								$player->singles_points = 0;
								$player->doubles = 0;
								$player->doubles_points = 0;
								$player->triples = 0;
								$player->triples_points = 0;
								$player->homeruns = 0;
								$player->homeruns_points = 0;
								$player->rbis = 0;
								$player->rbis_points = 0;
								$player-> walks = 0;
								$player-> walks_points = 0;
								$player->stolen_bases = 0;
								$player->stolen_bases_points = 0;
								$player->caught_stealing = 0;
								$player->caught_stealing_points = 0;
								$player->wins = 0;
								$player->wins_points = 0;
								$player->innings_pitched = 0;
								$player->innings_pitched_points = 0;
								$player->hits_against = 0;
								$player->hits_against_points = 0;
								$player->earned_runs = 0;
								$player->earned_runs_points = 0;
								$player->pitching_walks = 0;
								$player->pitching_walks_points = 0;
								$player->hitbatsmen = 0;
								$player->hitbatsmen_points = 0;
								$player->strikeouts = 0;
								$player->strikeouts_points = 0;
								$player->nohitter = 0;
								$player->nohitter_points = 0;
								$player->complete_game_shutouts = 0;
								$player->complete_game_shutouts_points = 0;
								$player->total_points = 0;
								
								foreach ($data as $stats_player) {
							            
									if ($player->player_id == $stats_player->PlayerID) {
							           
							            if ($player->position_category == 'P') {
								            
											
								            
								            //Pitchers are not credited with hitting points
								            
								            $player->runs = $stats_player->Runs;
											$player->runs_points = 0;
											$player->singles = $stats_player->Singles;
											$player->singles_points = 0;
											$player->doubles = $stats_player->Doubles;
											$player->doubles_points = 0;
											$player->triples = $stats_player->Triples;
											$player->triples_points = 0;
											$player->homeruns = $stats_player->HomeRuns;
											$player->homeruns_points = 0;
											$player->rbis = $stats_player->RunsBattedIn;
											$player->rbis_points = 0;
											$player->walks = $stats_player->Walks;
											$player->walks_points = 0;
											$player->stolen_bases = $stats_player->StolenBases;
											$player->stolen_bases_points = 0;
											$player->caught_stealing = $stats_player->CaughtStealing;
											$player->caught_stealing_points = 0;
											$player->wins = $stats_player->Wins;
											$player->wins_points = number_format($stats_player->Wins*$pitchingwins_VAL, 2);
											$player->innings_pitched = $stats_player->InningsPitchedDecimal;
											$player->innings_pitched_points = number_format($stats_player->InningsPitchedDecimal*$inningspitched_VAL, 2);
											$player->hits_against = $stats_player->PitchingHits;
											$player->hits_against_points = number_format($stats_player->PitchingHits*$pitchinghits_VAL, 2);
											$player->earned_runs = $stats_player->PitchingEarnedRuns;
											$player->earned_runs_points = number_format($stats_player->PitchingEarnedRuns*$earnedruns_VAL, 2);
											$player->pitching_walks = $stats_player->PitchingWalks;
											$player->pitching_walks_points = number_format($stats_player->PitchingWalks*$pitchingwalks_VAL, 2);
											$player->hitbatsmen = $stats_player->PitchingHitByPitch;
											$player->hitbatsmen_points = number_format($stats_player->PitchingHitByPitch*$pitchinghitbypitch_VAL, 2);
											$player->strikeouts = $stats_player->PitchingStrikeouts;
											$player->strikeouts_points = number_format($stats_player->PitchingStrikeouts*$pitchingstrikeouts_VAL, 2);
											$player->nohitter = $stats_player->PitchingNoHitters;
											$player->nohitter_points = number_format($stats_player->PitchingNoHitters*$pitchingnohitters_VAL, 2);
											
											$completegames = $stats_player->PitchingCompleteGames;
											if ($stats_player->PitchingEarnedRuns == 0 && $completegames != 0) {
												$completegameshutouts = $completegames;
											}
											else {
												$completegameshutouts = 0;
											}
											
											$player->complete_game_shutouts = $completegameshutouts;
											$player->complete_game_shutouts_points = number_format($completegameshutouts*$pitchingcompletegameshutouts_VAL, 2);
									            
							            }
							            else {
								            
								            //Hitters are not credited with pitching points
								            
								            $player->runs = $stats_player->Runs;
											$player->runs_points = number_format($player->runs*$goals_VAL, 2);
											$player->singles = $stats_player->Singles;
											$player->singles_points = number_format($stats_player->Singles*$singles_VAL, 2);
											$player->doubles = $stats_player->Doubles;
											$player->doubles_points = number_format($stats_player->Doubles*$doubles_VAL, 2);
											$player->triples = $stats_player->Triples;
											$player->triples_points = number_format($stats_player->Triples*$triples_VAL, 2);
											$player->homeruns = $stats_player->HomeRuns;
											$player->homeruns_points = number_format($stats_player->HomeRuns*$homeruns_VAL, 2);
											$player->rbis = $stats_player->RunsBattedIn;
											$player->rbis_points = number_format($stats_player->RunsBattedIn*$runsbattedin_VAL, 2);
											$player->walks = $stats_player->Walks;
											$player->walks_points = number_format($stats_player->Walks*$walks_VAL, 2);
											$player->stolen_bases = $stats_player->StolenBases;
											$player->stolen_bases_points = number_format($stats_player->StolenBases*$stolenbases_VAL, 2);
											$player->caught_stealing = $stats_player->CaughtStealing;
											$player->caught_stealing_points = number_format($stats_player->CaughtStealing*$caughtstealing_VAL, 2);
								         	$player->wins = $stats_player->Wins;
											$player->wins_points = 0;
											$player->innings_pitched = $stats_player->InningsPitchedDecimal;
											$player->innings_pitched_points = 0;
											$player->hits_against = $stats_player->PitchingHits;
											$player->hits_against_points = 0;
											$player->earned_runs = $stats_player->PitchingEarnedRuns;
											$player->earned_runs_points = 0;
											$player->pitching_walks = $stats_player->PitchingWalks;
											$player->pitching_walks_points = 0;
											$player->hitbatsmen = $stats_player->PitchingHitByPitch;
											$player->hitbatsmen_points = 0;
											$player->strikeouts = $stats_player->PitchingStrikeouts;
											$player->strikeouts_points = 0;
											$player->nohitter = $stats_player->PitchingNoHitters;
											$player->nohitter_points = 0;
											
											$completegames = $stats_player->PitchingCompleteGames;
											if ($stats_player->PitchingEarnedRuns == 0 && $completegames != 0) {
												$completegameshutouts = $completegames;
											}
											else {
												$completegameshutouts = 0;
											}
											
											$player->complete_game_shutouts = $completegameshutouts;
											$player->complete_game_shutouts_points = 0;
								         	   
							            }
								           
										$player->total_points = $player->runs_points + $player->singles_points + $player->doubles_points + $player->triples_points + $player->homeruns_points + $player->rbis_points + $player->walks_points + $player->stolen_bases_points + $player->caught_stealing_points + $player->wins_points + $player->innings_pitched_points + $player->hits_against_points + $player->earned_runs_points + $player->pitching_walks_points + $player->hitbatsmen_points + $player->strikeouts_points + $player->nohitter_points;
										
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
		echo '<div id="message" class="updated fade"><p>'.$contests_updated.' Live MLS contests updated.</p></div>';
	}
	
}




// Process completed contests and wagers

function process_finished_mls_contests($stats_key) {
	
	
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
				'terms'    => 'mls',
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
	
	
	
	//echo '<pre>';
	//print_r($contest_data);
	//echo '</pre>';
	
		
	//If contest is finished, mark as "Finished"
	
	$continue = false;
	
	if (!empty($contest_dates)) {
			
		$postponed_contests = array();
			
		foreach ($contest_dates as $contest_date) {
				
			$response = wp_remote_get( "https://fly.sportsdata.io/v3/mlb/stats/JSON/GamesByDate/$contest_date", array(
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
																								
					if ($game->Status == 'Postponed' || $game->Status == 'Canceled' || $game->Status == 'Suspended') {
						
						$postponed_contests[] = $game->GlobalGameID;
						
					}
					else if ($game->IsClosed != 1) {
						
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
					
					echo '<div id="message" class="updated fade error"><p>There are still MLS contests pending. Please try again when today\'s games are over. (Error Code 4)</p></div>';
					
					
				}
	
			}	
			else {
				
				echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 3)</p></div>';
				
			}	
							
		}
	
	}
	else {
		
		echo '<div id="message" class="updated fade error"><p>There are no MLS games in progress. Please try again later. (Error Code 5)</p></div>';
				
	}
	
	
	
	if ($continue == true) { 
	
	
		// Update stats for completed contests
	
		update_live_mls_contests($stats_key, true);
	
	
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

}


// Cron jobs

add_action( 'mls_update_live_cron', 'mls_update_live_cron' );

function mls_update_live_cron() {
	
	$stats_key = '4ee248e814c54f54a6a5c5d4f6f56772';
	update_live_mls_contests($stats_key, false);
	
	$cron_log = array(
		'post_status' => 'draft',
		'post_title' => 'MLS Cron Log - Update Live',
		'post_type' => 'cron_log',
		'post_content' => date('m-d-Y g:i a', time() - (60*60*7)) . ' PT',
		'tax_input' => array (
			'cron_type' => 4304,
		),
	);
	
	wp_set_current_user(1);
	wp_insert_post( $cron_log );
	
}

?>