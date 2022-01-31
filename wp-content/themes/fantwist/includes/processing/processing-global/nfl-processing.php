<?php

//Register settings and options

function pariwagerNFLpoints()
{

	register_setting('pariwager-nfl-points', 'nfl-points-passingTD');
	register_setting('pariwager-nfl-points', 'nfl-points-passingYds');
	register_setting('pariwager-nfl-points', 'nfl-points-passingInt');
	register_setting('pariwager-nfl-points', 'nfl-points-rushingTD');
	register_setting('pariwager-nfl-points', 'nfl-points-rushingYds');
	register_setting('pariwager-nfl-points', 'nfl-points-receivingTD');
	register_setting('pariwager-nfl-points', 'nfl-points-receivingYds');
	register_setting('pariwager-nfl-points', 'nfl-points-receptions');
	register_setting('pariwager-nfl-points', 'nfl-points-kickReturnTD');
	register_setting('pariwager-nfl-points', 'nfl-points-puntReturnTD');
	register_setting('pariwager-nfl-points', 'nfl-points-fumbleLost');
	register_setting('pariwager-nfl-points', 'nfl-points-ownFumbleRecTD');
	register_setting('pariwager-nfl-points', 'nfl-points-2ptConversionScored');
	register_setting('pariwager-nfl-points', 'nfl-points-2ptConversionPass');
	register_setting('pariwager-nfl-points', 'nfl-points-defensiveSack');
	register_setting('pariwager-nfl-points', 'nfl-points-defensiveFumbleRecovery');
	register_setting('pariwager-nfl-points', 'nfl-points-defkickoffReturnTD');
	register_setting('pariwager-nfl-points', 'nfl-points-defPuntReturnTD');
	register_setting('pariwager-nfl-points', 'nfl-points-defExtraPtReturn');
	register_setting('pariwager-nfl-points', 'nfl-points-defSafety');
	register_setting('pariwager-nfl-points', 'nfl-points-defInterception');
	register_setting('pariwager-nfl-points', 'nfl-points-defInterceptionReturnTD');
	register_setting('pariwager-nfl-points', 'nfl-points-def2PtConversionReturn');
	register_setting('pariwager-nfl-points', 'nfl-points-defInterception');
	register_setting('pariwager-nfl-points', 'nfl-points-defFumbleRecoveryTD');
	register_setting('pariwager-nfl-points', 'nfl-points-defBlockedKick');
	register_setting('pariwager-nfl-points', 'nfl-points-def0ptsAllowed');
	register_setting('pariwager-nfl-points', 'nfl-points-def1-6ptsAllowed');
	register_setting('pariwager-nfl-points', 'nfl-points-def7-13ptsAllowed');
	register_setting('pariwager-nfl-points', 'nfl-points-def14-20ptsAllowed');
	register_setting('pariwager-nfl-points', 'nfl-points-def21-27ptsAllowed');
	register_setting('pariwager-nfl-points', 'nfl-points-def28-34ptsAllowed');
	register_setting('pariwager-nfl-points', 'nfl-points-def35ptsAllowed');
}
add_action('admin_init', 'pariwagerNFLpoints');


//Create NFL Projections and Contests

function create_nfl_projections_and_contests_old($schedule_id, $projection_key)
{

	//Define vars

	$mixed_QB = array();
	$mixed_RB = array();
	$mixed_WR = array();
	$mixed_TE = array();
	$mixed_D = array();

	$parent_team = 2065;
	$tax_league = 6;
	$league_title = 'NFL';
	$count = 0;

	$passingTD_VAL = get_option('nfl-points-passingTD');
	$passingYds_VAL = get_option('nfl-points-passingYds');
	$passingInt_VAL = get_option('nfl-points-passingInt');
	$rushingTD_VAL = get_option('nfl-points-rushingTD');
	$rushingYds_VAL = get_option('nfl-points-rushingYds');
	$receivingTD_VAL = get_option('nfl-points-receivingTD');
	$receivingYds_VAL = get_option('nfl-points-receivingYds');
	$receptions_VAL = get_option('nfl-points-receptions');
	$kickReturnTD_VAL = get_option('nfl-points-kickReturnTD');
	$puntReturnTD_VAL = get_option('nfl-points-puntReturnTD');
	$fumbleLost_VAL = get_option('nfl-points-fumbleLost');
	$ownFumbleRecTD_VAL = get_option('nfl-points-ownFumbleRecTD');
	$twoPtConversionScored_VAL = get_option('nfl-points-2ptConversionScored');
	$twoPtConversionPass_VAL = get_option('nfl-points-2ptConversionPass');
	$defensiveSack_VAL = get_option('nfl-points-defensiveSack');
	$defensiveFumbleRecovery_VAL = get_option('nfl-points-defensiveFumbleRecovery');
	$defkickoffReturnTD_VAL = get_option('nfl-points-defkickoffReturnTD');
	$defPuntReturnTD_VAL = get_option('nfl-points-defPuntReturnTD');
	$defExtraPtReturn_VAL = get_option('nfl-points-defExtraPtReturn');
	$defSafety_VAL = get_option('nfl-points-defSafety');
	$defInterception_VAL = get_option('nfl-points-defInterception');
	$defInterceptionReturnTD_VAL = get_option('nfl-points-defInterceptionReturnTD');
	$def2PtConversionReturn_VAL = get_option('nfl-points-def2PtConversionReturn');
	$defFumbleRecoveryTD_VAL = get_option('nfl-points-defFumbleRecoveryTD');
	$defBlockedKick_VAL = get_option('nfl-points-defBlockedKick');
	$def0ptsAllowed_VAL = get_option('nfl-points-def0ptsAllowed');
	$def1_6ptsAllowed_VAL = get_option('nfl-points-def1-6ptsAllowed');
	$def7_13ptsAllowed_VAL = get_option('nfl-points-def7-13ptsAllowed');
	$def14_20ptsAllowed_VAL = get_option('nfl-points-def14-20ptsAllowed');
	$def21_27ptsAllowed_VAL = get_option('nfl-points-def21-27ptsAllowed');
	$def28_34ptsAllowed_VAL = get_option('nfl-points-def28-34ptsAllowed');
	$def35ptsAllowed_VAL = get_option('nfl-points-def35ptsAllowed');

	$schedule_type = get_field('schedule_type', 'schedule_' . $schedule_id);

	if ($schedule_type == 'Preseason') {
		$schedule = '2020PRE';
	} else if ($schedule_type == 'Regular Season') {
		$schedule = '2020REG';
	} else if ($schedule_type == 'Playoffs') {
		$schedule = '2020POST';
	}

	$nfl_week = get_field('nfl_week', 'schedule_' . $schedule_id);



	//Prepare team arrays

	$args = array(
		'taxonomy' => 'team',
		'hide_empty' => false,
		'child_of' => $parent_team,
	);

	$all_teams = get_terms($args);

	foreach ($all_teams as $team) {

		$team_id = get_term_meta($team->term_id, 'TeamID', true);
		$team_abbrev = get_term_meta($team->term_id, 'team_abbreviation', true);

		$team->TeamID = $team_id;
		$team->team_abbrev = $team_abbrev;
		$team->total_points = 0;
		$team->total_points_QB = 0;
		$team->total_points_RB = 0;
		$team->total_points_WR = 0;
		$team->total_points_TE = 0;
		$team->total_points_D = 0;
		$team->players = array('QB', 'RB', 'WR', 'TE', 'D');
	}

	$all_teams = array_values($all_teams);


	//Get offensive player projections via SportsDataIO API

	$projections_url = "https://fly.sportsdata.io/v3/nfl/projections/json/PlayerGameProjectionStatsByWeek/$schedule/$nfl_week" . '?key=' . $projection_key;

	$offense_response = wp_remote_get($projections_url, array(
		'method'	=> 'GET',
		'timeout'	=> 60,
		'headers'	=> array(
			'Ocp-Apim-Subscription-Key' => $projection_key,
		),
	));


	//Get defensive player projections via SportsDataIO API

	$defensive_projections_url = "https://fly.sportsdata.io/v3/nfl/projections/json/FantasyDefenseProjectionsByGame/$schedule/$nfl_week" . '?key=' . $projection_key;

	$defense_response = wp_remote_get($defensive_projections_url, array(
		'method'	=> 'GET',
		'timeout'	=> 60,
		'headers'	=> array(
			'Ocp-Apim-Subscription-Key' => $projection_key,
		),
	));



	if (is_array($offense_response) && !is_wp_error($offense_response)) {

		if (is_array($defense_response) && !is_wp_error($defense_response)) {


			$offense = json_decode($offense_response['body']);
			$defense = json_decode($defense_response['body']);


			//Offense

			foreach ($offense as $player) {

				$count = 0;

				$contest_date_time = $player->GameDate;
				$contest_date = date('Y-m-d', strtotime($player->GameDate));
				$opponent = $player->GlobalOpponentID;
				$opponent_abbrev = $player->Opponent;
				$home_away = strtolower($player->HomeOrAway);
				$position = $player->Position;
				$position_category = $player->PositionCategory;
				$gameID = $player->GlobalGameID;

				$passingTD = $player->PassingTouchdowns;
				$passingYds = $player->PassingYards;
				$passingInt = $player->PassingInterceptions;
				$rushingTD = $player->RushingTouchdowns;
				$rushingYds = $player->RushingYards;
				$receivingTD = $player->ReceivingTouchdowns;
				$receivingYds = $player->ReceivingYards;
				$receptions = $player->Receptions;
				$kickReturnTD = $player->KickReturnTouchdowns;
				$puntReturnTD = $player->PuntReturnTouchdowns;
				$fumbleLost = $player->FumblesLost;
				$ownFumbleRecTD = $player->FumbleReturnTouchdowns;
				$twoPtConversionScored = $player->TwoPointConversionRuns + $player->TwoPointConversionReceptions;
				$twoPtConversionPass = $player->TwoPointConversionPasses;

				$scoringDetails = $player->ScoringDetails;
				$scoreID = $player->ScoreID;

				if (isset($cutoff_datetime) == false) {
					$cutoff_datetime = $contest_date_time;
				} else if ($contest_date_time < $cutoff_datetime) {
					$cutoff_datetime = $contest_date_time;
				}

				if ($position_category == 'OFF') { //offense

					$total_points = ($passingTD * $passingTD_VAL) + ($passingYds * $passingYds_VAL) + ($passingInt * $passingInt_VAL) + ($rushingTD * $rushingTD_VAL) + ($rushingYds * $rushingYds_VAL) + ($receivingTD * $receivingTD_VAL) + ($receivingYds * $receivingYds_VAL) + ($receptions * $receptions_VAL) + ($kickReturnTD * $kickReturnTD_VAL) + ($puntReturnTD * $puntReturnTD_VAL) + ($fumbleLost * $fumbleLost_VAL) + ($ownFumbleRecTD * $ownFumbleRecTD_VAL) + ($twoPtConversionScored * $twoPtConversionScored_VAL) + ($twoPtConversionPass * $twoPtConversionPass_VAL);

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
						'passingTD' => $passingTD,
						'passingTD_points' => number_format($passingTD * $passingTD_VAL, 2),
						'passingYds' => $passingYds,
						'passingYds_points' => number_format($passingYds * $passingYds_VAL, 2),
						'passingInt' => $passingInt,
						'passingInt_points' => number_format($passingInt * $passingInt_VAL, 2),
						'rushingTD' => $rushingTD,
						'rushingTD_points' => number_format($rushingTD * $rushingTD_VAL, 2),
						'rushingYds' => $rushingYds,
						'rushingYds_points' => number_format($rushingYds * $rushingYds_VAL, 2),
						'receivingTD' => $receivingTD,
						'receivingTD_points' => number_format($receivingTD * $receivingTD_VAL, 2),
						'receivingYds' => $receivingYds,
						'receivingYds_points' => number_format($receivingYds * $receivingYds_VAL, 2),
						'receptions' => $receptions,
						'receptions_points' => number_format($receptions * $receptions_VAL, 2),
						'kickReturnTD' => $kickReturnTD,
						'kickReturnTD_points' => number_format($kickReturnTD * $kickReturnTD_VAL, 2),
						'puntReturnTD' => $puntReturnTD,
						'puntReturnTD_points' => number_format($puntReturnTD * $puntReturnTD_VAL, 2),
						'fumbleLost' => $fumbleLost,
						'fumbleLost_points' => number_format($fumbleLost * $fumbleLost_VAL, 2),
						'ownFumbleRecTD' => $ownFumbleRecTD,
						'ownFumbleRecTD_points' => number_format($ownFumbleRecTD * $ownFumbleRecTD_VAL, 2),
						'twoPtConversionScored' => $twoPtConversionScored,
						'twoPtConversionScored_points' => number_format($twoPtConversionScored * $twoPtConversionScored_VAL, 2),
						'twoPtConversionPass' => $twoPtConversionPass,
						'twoPtConversionPass_points' => number_format($twoPtConversionPass * $twoPtConversionPass_VAL, 2),
						'game_id' => $gameID,
						'total_points' => $total_points,
						'is_game_over' => 0,
						'statsUpdated' => 0
					);

					if ($position == 'QB') {

						//add to Teams array

						foreach ($all_teams as $team) {

							if ($team->TeamID == $player->GlobalTeamID) {

								$team->home_away = $home_away;
								$team->opponent = $team->TeamID;
								$team->opponent_abbrev = $opponent_abbrev;
								$team->players['QB'][] = $contest_player;
								$team->total_points_QB = number_format($team->total_points_QB + $total_points, 2);
								$team->total_points = number_format($team->total_points + $total_points, 2);
								$team->game_start = $contest_date_time;
								$team->game_id = $player->GlobalGameID;

								$mixed_QB[] = $contest_player;
							}
						}
					} else if ($position == 'RB') {

						//add to Teams array

						foreach ($all_teams as $team) {

							if ($team->TeamID == $player->GlobalTeamID) {

								$team->home_away = $home_away;
								$team->opponent = $team->TeamID;
								$team->opponent_abbrev = $opponent_abbrev;
								$team->players['RB'][] = $contest_player;
								$team->total_points_RB = number_format($team->total_points_RB + $total_points, 2);
								$team->total_points = number_format($team->total_points + $total_points, 2);
								$team->game_start = $contest_date_time;
								$team->game_id = $player->GlobalGameID;

								$mixed_RB[] = $contest_player;
							}
						}
					} else if ($position == 'WR') {

						//add to Teams array

						foreach ($all_teams as $team) {

							if ($team->TeamID == $player->GlobalTeamID) {

								$team->home_away = $home_away;
								$team->opponent = $team->TeamID;
								$team->opponent_abbrev = $opponent_abbrev;
								$team->players['WR'][] = $contest_player;
								$team->total_points_WR = number_format($team->total_points_WR + $total_points, 2);
								$team->total_points = number_format($team->total_points + $total_points, 2);
								$team->game_start = $contest_date_time;
								$team->game_id = $player->GlobalGameID;

								$mixed_WR[] = $contest_player;
							}
						}
					} else if ($position == 'TE') {

						//add to Teams array

						foreach ($all_teams as $team) {

							if ($team->TeamID == $player->GlobalTeamID) {

								$team->home_away = $home_away;
								$team->opponent = $team->TeamID;
								$team->opponent_abbrev = $opponent_abbrev;
								$team->players['TE'][] = $contest_player;
								$team->total_points_TE = number_format($team->total_points_TE + $total_points, 2);
								$team->total_points = number_format($team->total_points + $total_points, 2);
								$team->game_start = $contest_date_time;
								$team->game_id = $player->GlobalGameID;

								$mixed_TE[] = $contest_player;
							}
						}
					}
				}
			}


			// Defense

			foreach ($defense as $team_defense) {

				$count = 0;

				$contest_date_time = $team_defense->Date;
				$contest_date = date('Y-m-d', strtotime($team_defense->Date));
				$opponent = $team_defense->GlobalOpponentID;
				$opponent_abbrev = $team_defense->Opponent;
				$home_away = strtolower($team_defense->HomeOrAway);
				$position = 'D';
				$position_category = 'DEF';
				$gameID = $team_defense->GlobalGameID;

				$defensiveSack = $team_defense->Sacks;
				$defensiveFumbleRecovery = $team_defense->FumblesRecovered;
				$defkickoffReturnTD = $team_defense->KickReturnTouchdowns;
				$defPuntReturnTD = $team_defense->PuntReturnTouchdowns;
				$defExtraPtReturn = $team_defense->FieldGoalReturnTouchdowns + $team_defense->BlockedKickReturnTouchdowns;
				$defSafety = $team_defense->Safeties;
				$defInterception = $team_defense->Interceptions;
				$defInterceptionReturnTD = $team_defense->InterceptionReturnTouchdowns;
				$def2PtConversionReturn = $team_defense->TwoPointConversionReturns;
				$defFumbleRecoveryTD = $team_defense->FumbleReturnTouchdowns;
				$defBlockedKick = $team_defense->BlockedKicks;

				$scoringDetails = $team_defense->ScoringDetails;
				$scoreID = $team_defense->ScoreID;

				if (isset($cutoff_datetime) == false) {
					$cutoff_datetime = $contest_date_time;
				} else if ($contest_date_time < $cutoff_datetime) {
					$cutoff_datetime = $contest_date_time;
				}

				$total_points = ($defensiveSack * $defensiveSack_VAL) + ($defensiveFumbleRecovery * $defensiveFumbleRecovery_VAL) + ($defkickoffReturnTD * $defkickoffReturnTD_VAL) + ($defPuntReturnTD * $defPuntReturnTD_VAL) + ($defExtraPtReturn * $defExtraPtReturn_VAL) + ($defSafety * $defSafety_VAL) + ($defInterception * $defInterception_VAL) + ($defInterceptionReturnTD * $defInterceptionReturnTD_VAL) + ($def2PtConversionReturn * $def2PtConversionReturn_VAL) + ($defFumbleRecoveryTD * $defFumbleRecoveryTD_VAL) + ($defBlockedKick * $defBlockedKick_VAL);

				$total_points = number_format($total_points, 2);

				$contest_player = array(
					'name' => $team_defense->Team,
					'player_id' => $team_defense->PlayerID,
					'team_id' => $team_defense->GlobalTeamID,
					'opponent_id' => $opponent,
					'opponent_abbrev' => $opponent_abbrev,
					'home_away' => $home_away,
					'position' => 'D',
					'position_category' => 'DEF',
					'game_start_et' => $contest_date_time,
					'game_id' => $gameID,
					'total_points' => $total_points,
					'is_game_over' => 0,
					'sacks' => $defensiveSack,
					'sacks_VAL' => number_format($defensiveSack * $defensiveSack_VAL, 2),
					'fumbleRecovery' => $defensiveFumbleRecovery,
					'fumbleRecovery_VAL' => number_format($defensiveFumbleRecovery * $defensiveFumbleRecovery_VAL, 2),
					'kickoffReturnTD' => $kickReturnTD,
					'kickoffReturnTD_VAL' => number_format($kickReturnTD * $kickReturnTD_VAL, 2),
					'puntReturnTD' => $puntReturnTD,
					'puntReturnTD_VAL' => number_format($puntReturnTD * $puntReturnTD_VAL, 2),
					'extraPtReturn' => $defExtraPtReturn,
					'extraPtReturn_VAL' => number_format($defExtraPtReturn * $defExtraPtReturn_VAL, 2),
					'safety' => $defSafety,
					'safety_VAL' => number_format($defSafety * $defSafety_VAL, 2),
					'interceptions' => $defInterception,
					'interceptions_VAL' => number_format($defInterception * $defInterception_VAL, 2),
					'interceptionReturnTD' => $defInterceptionReturnTD,
					'interceptionReturnTD_VAL' => number_format($defInterceptionReturnTD * $defInterceptionReturnTD_VAL, 2),
					'twoPtConversionReturn' => $def2PtConversionReturn,
					'twoPtConversionReturn_VAL' => number_format($def2PtConversionReturn * $def2PtConversionReturn_VAL, 2),
					'fumbleRecoveryTD' => $defFumbleRecoveryTD,
					'fumbleRecoveryTD_VAL' => number_format($defFumbleRecoveryTD * $defFumbleRecoveryTD_VAL, 2),
					'blockedKick' => $defBlockedKick,
					'blockedKick_VAL' => number_format($defBlockedKick * $defBlockedKick_VAL, 2),
					'statsUpdated' => 0
				);

				//add to Teams array

				foreach ($all_teams as $team) {

					if ($team->team_abbrev == $team_defense->Team) {

						$team->home_away = $home_away;
						$team->opponent = $team->TeamID;
						$team->opponent_abbrev = $opponent_abbrev;
						$team->players['D'][] = $contest_player;
						$team->total_points_D = number_format($team->total_points_D + $total_points, 2);
						$team->total_points = number_format($team->total_points + $total_points, 2);
						$team->game_start = $contest_date_time;
						$team->game_id = $team_defense->GlobalGameID;

						$mixed_D[] = $contest_player;
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

			$mixed_team_count = count($mixed_QB);

			if (count($mixed_QB) < $mixed_team_count) {
				$mixed_team_count = count($mixed_QB);
			}
			if (count($mixed_RB) < $mixed_team_count) {
				$mixed_team_count = count($mixed_RB);
			}
			if (count($mixed_WR) < $mixed_team_count) {
				$mixed_team_count = count($mixed_WR);
			}
			if (count($mixed_TE) < $mixed_team_count) {
				$mixed_team_count = count($mixed_TE);
			}
			if (count($mixed_D) < $mixed_team_count) {
				$mixed_team_count = count($mixed_D);
			}

			//Sort position groups by total points

			$sort = array();
			foreach ($mixed_QB as $key => $part) {
				$sort[$key] = $part['total_points'];
			}
			array_multisort($sort, SORT_DESC, $mixed_QB);


			$sort = array();
			foreach ($mixed_RB as $key => $part) {
				$sort[$key] = $part['total_points'];
			}
			array_multisort($sort, SORT_DESC, $mixed_RB);

			$sort = array();
			foreach ($mixed_WR as $key => $part) {
				$sort[$key] = $part['total_points'];
			}
			array_multisort($sort, SORT_DESC, $mixed_WR);

			$sort = array();
			foreach ($mixed_TE as $key => $part) {
				$sort[$key] = $part['total_points'];
			}
			array_multisort($sort, SORT_DESC, $mixed_TE);

			$sort = array();
			foreach ($mixed_D as $key => $part) {
				$sort[$key] = $part['total_points'];
			}
			array_multisort($sort, SORT_DESC, $mixed_D);




			$mixed_teams = array();

			if ($mixed_team_count >= 25) {
				$mixed_team_count = 25;
			}

			for ($i = 0; $i < $mixed_team_count; $i++) {

				$mixed_team = array(
					'team_name' => 'Team ' . ($i + 1),
					'QB' => $mixed_QB[$i],
					'RB' => $mixed_RB[$i],
					'WR' => $mixed_WR[$i],
					'TE' => $mixed_TE[$i],
					'D' => $mixed_D[$i],
					'total_points' => 0,
					'odds_to_1' => (int) ($i + 2),
				);

				$mixed_teams[] = $mixed_team;
			}

			$i = 0;

			foreach ($mixed_teams as $mixed_team) {

				$total_points = $mixed_team['QB']['total_points'] + $mixed_team['RB']['total_points'] + $mixed_team['WR']['total_points'] + $mixed_team['TE']['total_points'] + $mixed_team['D']['total_points'];

				$mixed_teams[$i]['total_points'] = number_format($total_points, 2);

				$i++;
			}
		} else {

			echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 2)</p></div>';
		}
	} else {

		echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 1)</p></div>';
	}




	// BUILD CONTESTS

	if (isset($cutoff_datetime)) {

		$the_contest_datetime = strtoupper(date('m-d-Y g:i a', strtotime($cutoff_datetime) - 60 * 60 * 3));
		$the_contest_date_unix = strtoupper(date('U', strtotime($cutoff_datetime) - 60 * 60 * 3));
		$the_contest_date_notime = date('m-d-Y', strtotime($cutoff_datetime) - 60 * 60 * 3);
		$the_contest_date_notime_day = date('l F jS', strtotime($cutoff_datetime) - 60 * 60 * 3);
		$the_contest_date_sort = strtoupper(date('Y-m-d H:i:s', strtotime($cutoff_datetime) - 60 * 60 * 3));
		$contests_created = 0;

		$week_term = get_term_by('id', $schedule_id, 'schedule');

		if (!is_wp_error($week_term)) {

			//Build Team vs Field Contests

			$teams_contest = array(
				'post_status' => 'publish',
				'post_title' => $league_title . ': Teams ' . $schedule_type . ' Week ' . $nfl_week,
				'post_type' => 'contest',
				'meta_input' => array(
					'contest_type' => 'Teams',
					'contest_date' => $the_contest_date_unix,
					'contest_date_sort' => $the_contest_date_sort,
					'contest_status' => 'Open',
					'contest_data' => json_encode($all_teams, JSON_UNESCAPED_UNICODE),
					'contest_title_without_type' => $league_title . ': Teams ' . $schedule_type . ' Week ' . $nfl_week,
					'contest_projections_url' => $projections_url,
					'contest_nfl_defensive_projections_url' => $defensive_projections_url
				),
				'tax_input' => array(
					'league' => $tax_league,
					'schedule' => array($schedule_id, $week_term->parent),
				),
			);

			$post_exists = post_exists($league_title . ': Teams ' . $schedule_type . ' Week ' . $nfl_week);

			if ($post_exists == 0) {
				//wp_insert_post( $teams_contest );
				//$contests_created++;
			}


			//Build Mixed vs Field Contests

			$mixed_contest = array(
				'post_status' => 'publish',
				'post_title' => $league_title . ': Mixed ' . $schedule_type . ' Week ' . $nfl_week,
				'post_type' => 'contest',
				'meta_input' => array(
					'contest_type' => 'Mixed',
					'contest_date' => $the_contest_date_unix,
					'contest_date_sort' => $the_contest_date_sort,
					'contest_status' => 'Open',
					'contest_data' => json_encode($mixed_teams, JSON_UNESCAPED_UNICODE),
					'contest_title_without_type' => $league_title . ': Mixed ' . $schedule_type . ' Week ' . $nfl_week,
					'contest_projections_url' => $projections_url,
					'contest_nfl_defensive_projections_url' => $defensive_projections_url
				),
				'tax_input' => array(
					'league' => $tax_league,
					'schedule' => array($schedule_id, $week_term->parent),
				),
			);

			$post_exists = post_exists($league_title . ': Mixed ' . $schedule_type . ' Week ' . $nfl_week);

			if ($post_exists == 0) {
				//wp_insert_post( $mixed_contest );
				//$contests_created++;	
			}




			//Build Team vs Team Contests

			$games = array();
			$teams = array();
			$contests = array();

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
								$team1->opening_spread = round(number_format($team1->total_points - $team->total_points, 2) * 2) / 2;
								$team->opening_spread = round(number_format($team->total_points - $team1->total_points, 2) * 2) / 2;
								$team1->current_spread = $team1->opening_spread;
								$team->current_spread = $team->opening_spread;

								/*
								$team1->opening_moneyline = round(number_format(($team1->total_points*3.65)*-1,2));
								$team->opening_moneyline = round(number_format($team->total_points*2.85,2));
								$team1->current_moneyline = round(number_format(($team1->total_points*3.65)*-1,2));
								$team->current_moneyline = round(number_format($team->total_points*2.85,2));
								*/

								$team1->opening_moneyline = -110;
								$team->opening_moneyline = -110;
								$team1->current_moneyline = -110;
								$team->current_moneyline = -110;

								$overunder = round(number_format(($team->total_points + $team1->total_points), 2) * 2) / 2;

								$team->opening_overunder = $overunder;
								$team1->opening_overunder = $overunder;
								$team->current_overunder = $overunder;
								$team1->current_overunder = $overunder;
							} else {

								// team1 is underdog; team is favorite

								$team1->internal_spread = number_format($team->total_points - $team1->total_points, 2);
								$team->internal_spread = number_format($team1->total_points - $team->total_points, 2);
								$team1->opening_spread = round(number_format($team->total_points - $team1->total_points, 2) * 2) / 2;
								$team->opening_spread = round(number_format($team1->total_points - $team->total_points, 2) * 2) / 2;
								$team1->current_spread = $team1->opening_spread;
								$team->current_spread = $team->opening_spread;

								/*
								$team1->opening_moneyline = round(number_format($team1->total_points*2.85,2));
								$team->opening_moneyline = round(number_format(($team->total_points*3.65)*-1,2));
								$team1->current_moneyline = round(number_format($team1->total_points*2.85,2));
								$team->current_moneyline = round(number_format(($team->total_points*3.65)*-1,2));
								*/

								$team1->opening_moneyline = -110;
								$team->opening_moneyline = -110;
								$team1->current_moneyline = -110;
								$team->current_moneyline = -110;

								$overunder = round(number_format(($team->total_points + $team1->total_points), 2) * 2) / 2;

								$team->opening_overunder = $overunder;
								$team1->opening_overunder = $overunder;
								$team->current_overunder = $overunder;
								$team1->current_overunder = $overunder;
							}

							$game[] = $team1;
							$games[] = $game;
						}
					}
				}
			}


			$teams_contest = array(
				'post_status' => 'publish',
				'post_title' => $league_title . ': Game Lines ' . $schedule_type . ' Week ' . $nfl_week,
				'post_type' => 'contest',
				'meta_input' => array(
					'contest_type' => 'Team vs Team',
					'contest_date' => $the_contest_date_unix,
					'contest_date_sort' => $the_contest_date_sort,
					'contest_status' => 'Open',
					'contest_data' => json_encode($games, JSON_UNESCAPED_UNICODE),
					'contest_title_without_type' => $league_title . ': Game Lines ' . $schedule_type . ' Week ' . $nfl_week,
					'contest_projections_url' => $projections_url,
					'contest_nfl_defensive_projections_url' => $defensive_projections_url,
				),
				'tax_input' => array(
					'league' => $tax_league,
					'schedule' => array($schedule_id, $week_term->parent),
				),
			);

			$post_exists = post_exists($league_title . ': Game Lines ' . $schedule_type . ' Week ' . $nfl_week);

			if ($post_exists == 0) {
				$main_contest_id = wp_insert_post($teams_contest);
				$contests_created++;
			}



			//For NFL we must also build a separate contest for each day's slate of games (i.e. Thursday, Sunday, Monday) for wager processing

			$gamedays = array();

			foreach ($games as $game) {

				$i = 0;

				foreach ($game as $team) {

					if ($i == 0) {

						$date = date('m-d-Y', strtotime($team->game_start));

						if (!in_array($date, $gamedays)) {
							$gamedays[] = $date;
						}
					}

					$i++;
				}
			}

			$game_slate = array();
			$game_slate[] = $gamedays;

			foreach ($games as $game) {

				foreach ($game as $team) {

					$date = date('m-d-Y', strtotime($team->game_start));

					foreach ($gamedays as $gameday) {

						if ($date == $gameday) {

							//add game to gameday slate

							$game_slate[$gameday][] = $game;

							break;
						}
					}

					break;
				}
			}

			//remove unneeded index keys and sort by date
			array_shift($game_slate);
			ksort($game_slate);


			//create contest for each day there are games played

			foreach ($game_slate as $gameday) {

				$start = false;

				//get start time for each contest

				foreach ($gameday as $game) {

					foreach ($game as $team) {

						if ($start == false) {
							$cutoff_datetime = $team->game_start;
							$start = true;
						} else if ($team->game_start < $cutoff_datetime) {
							$cutoff_datetime = $team->game_start;
						}
					}
				}

				$the_contest_date_unix = strtoupper(date('U', strtotime($cutoff_datetime) - 60 * 60 * 3));
				$the_contest_date_sort = strtoupper(date('Y-m-d H:i:s', strtotime($cutoff_datetime) - 60 * 60 * 3));
				$the_contest_date_title = date('l F j', strtotime($cutoff_datetime));

				$teams_contest = array(
					'post_status' => 'publish',
					'post_title' => $league_title . ': Game Lines ' . $the_contest_date_title,
					'post_type' => 'contest',
					'meta_input' => array(
						'contest_type' => 'Team vs Team',
						'contest_date' => $the_contest_date_unix,
						'contest_date_sort' => $the_contest_date_sort,
						'contest_status' => 'Open',
						'contest_data' => json_encode($gameday, JSON_UNESCAPED_UNICODE),
						'contest_title_without_type' => $league_title . ' Game Lines: ' . $the_contest_date_title,
						'contest_projections_url' => $projections_url,
						'contest_nfl_defensive_projections_url' => $defensive_projections_url,
						'nfl_main_contest' => $main_contest_id,
					),
					'tax_input' => array(
						'league' => $tax_league,
						'schedule' => array($schedule_id, $week_term->parent),
					),
				);

				$post_exists = post_exists($league_title . ': Game Lines ' . $the_contest_date_title);

				if ($post_exists == 0) {
					wp_insert_post($teams_contest);
					$contests_created++;
				}
			}
		} else {

			echo '<div id="message" class="updated fade error"><p>There was a scheduling error. Check with Kyle.</p></div>';
		}
	} else {
		$contests_created = 0;
	}

	echo '<div id="message" class="updated fade"><p>' . $contests_created . ' NFL contests created.</p></div>';
}

//New Create NFL Projections and Contests
function create_nfl_projections_and_contests($schedule_id, $projection_key)
{

	$league_count = 400;
	$parent_team = 2065;
	$tax_league = 6;
	$league_title = 'NFL';
	$contests_created = 0;

	//Prepare team arrays

	$args = array(
		'taxonomy' => 'team',
		'hide_empty' => false,
		'child_of' => $parent_team,
	);

	$all_teams = get_terms($args);

	foreach ($all_teams as $team) {
		$team->TeamID = get_term_meta($team->term_id, 'TeamID', true);
		$team->team_abbrev = get_term_meta($team->term_id, 'team_abbreviation', true);
	}

	//get season type and week

	$schedule_type = get_field('schedule_type', 'schedule_' . $schedule_id);

	if ($schedule_type == 'Preseason') {
		$schedule_type_short = 'PRE';
	} else if ($schedule_type == 'Regular Season') {
		$schedule_type_short = 'REG';
	} else if ($schedule_type == 'Playoffs') {
		$schedule_type_short = 'POST';
	}

	$nfl_week = get_field('nfl_week', 'schedule_' . $schedule_id);

	//get year for the season

	$term = get_term($schedule_id, 'schedule');
	$termParent = ($term->parent == 0) ? $term : get_term($term->parent, 'schedule');
	$season = substr($termParent->name, 0, 4);

	//Get player projections via SportsDataIO API

	$projections_url = "https://fly.sportsdata.io/v3/nfl/projections/json/PlayerGameProjectionStatsByWeek/" . $season . $schedule_type_short . "/" . $nfl_week . "?key=" . $projection_key;
	$defense_projections_url = "https://fly.sportsdata.io/v3/nfl/projections/json/FantasyDefenseProjectionsByGame/" . $season . $schedule_type_short . "/" . $nfl_week . "?key=" . $projection_key;

	$contest_response = wp_remote_get($projections_url, array(
		'method'	=> 'GET',
		'timeout'	=> 60,
		'headers'	=> array(
			'Ocp-Apim-Subscription-Key' => $projection_key,
		),
	));

	$defense_contest_response = wp_remote_get($defense_projections_url, array(
		'method'	=> 'GET',
		'timeout'	=> 60,
		'headers'	=> array(
			'Ocp-Apim-Subscription-Key' => $projection_key,
		),
	));

	if (is_array($contest_response) && !is_wp_error($contest_response) && is_array($defense_contest_response) && !is_wp_error($defense_contest_response)) {

		$contents = json_decode($contest_response['body']);
		$defense_contents = json_decode($defense_contest_response['body']);
		$contest_data = array();
		$contest_teams = array();
		$contest_positions = array();
		$contest_dates = array();

		//giving defense player a position
		foreach ($defense_contents as $content) {
			$content->Position = "DST";
		}

		//merge offensive and defensive players
		$contents = array_merge($contents, $defense_contents);

		//get all team
		foreach ($contents as $content) {
			if (!in_array($content->Team, $contest_teams)) {
				if ($content->HomeOrAway == "AWAY") {
					$contest_teams[$content->Opponent] = $content->Team;
				}
			}
		}

		//get all positions
		foreach ($contents as $content) {
			if (!in_array($content->Position, $contest_positions)) {
				array_push($contest_positions, $content->Position);
			}
		}

		//get all positions
		foreach ($contest_teams as $opponent => $team) {
			foreach ($contest_positions as $position) {
				${$team . $position} = 0;
			}

			foreach ($contents as $content) {
				foreach ($contest_positions as $position) {
					if ($team == $content->Team) {
						if ($content->Position == $position) {
							${$team . $position} += $content->FantasyPointsDraftKings;
						}
					}
					if ($opponent == $content->Team) {
						if ($content->Position == $position) {
							${$opponent . $position} += $content->FantasyPointsDraftKings;
						}
					}
				}
			}
		}

		foreach ($contest_teams as $opponent => $team) {
			foreach ($contents as $content) {
				if ($team == $content->Team) {

					foreach ($all_teams as $current_term) {
						if ($team == $current_term->team_abbrev) {
							$team1_term_id = $current_term->term_id;
							$team1_team_id = $current_term->TeamID;
							$team1_name = $current_term->name;
							$team1_slug = $current_term->slug;
							$team1_term_taxonomy_id = $current_term->term_taxonomy_id;
							$team1_parent = $current_term->parent;
						}
						if ($opponent == $current_term->team_abbrev) {
							$team2_term_id = $current_term->term_id;
							$team2_team_id = $current_term->TeamID;
							$team2_name = $current_term->name;
							$team2_slug = $current_term->slug;
							$team2_term_taxonomy_id = $current_term->term_taxonomy_id;
							$team2_parent = $current_term->parent;
						}
					}


					$current_data = array();
					$current_data['game_id'] = $content->GameKey;
					$current_data['game_start'] = $content->DateTime;
					$current_data['season_type'] = $content->SeasonType;
					$current_data['season'] = $content->Season;
					$current_data['week'] = $nfl_week;
					$current_data['is_game_over'] = $content->IsGameOver;
					$current_data['player_types'] = $contest_positions;

					$current_data['team1']['term_id'] = $team1_term_id;
					$current_data['team1']['TeamID'] = $team1_team_id;
					$current_data['team1']['name'] = $team1_name;
					$current_data['team1']['slug'] = $team1_slug;
					$current_data['team1']['term_taxonomy_id'] = $team1_term_taxonomy_id;
					$current_data['team1']['taxonomy'] = "team";
					$current_data['team1']['parent'] = $team1_parent;
					$current_data['team1']['team_abbrev'] = $content->Team;
					$current_data['team1']['home_away'] = "away";
					$current_data['team1']['opponent'] = $team2_term_id;
					$current_data['team1']['opponent_abbrev'] = $content->Opponent;
					$current_data['team1']['total_points'] = 0;

					foreach ($contest_positions as $position) {
						$current_data['team1']['total_points'] += ${$team . $position};
						$current_data['team1']['player'][$position] = ${$team . $position};
					}

					$current_data['team2']['term_id'] = $team2_term_id;
					$current_data['team2']['TeamID'] = $team2_team_id;
					$current_data['team2']['name'] = $team2_name;
					$current_data['team2']['slug'] = $team2_slug;
					$current_data['team2']['term_taxonomy_id'] = $team2_term_taxonomy_id;
					$current_data['team2']['taxonomy'] = "team";
					$current_data['team2']['parent'] = $team2_parent;
					$current_data['team2']['team_abbrev'] = $content->Opponent;
					$current_data['team2']['home_away'] = "home";
					$current_data['team2']['opponent'] = $team1_term_id;
					$current_data['team2']['opponent_abbrev'] = $content->Team;
					$current_data['team2']['total_points'] = 0;

					foreach ($contest_positions as $position) {
						$current_data['team2']['total_points'] += ${$opponent . $position};
						$current_data['team2']['player'][$position] = ${$opponent . $position};
					}

					if ($current_data['team1']['total_points'] > $current_data['team2']['total_points']) {
						$current_data['team1']['spread'] = -abs($current_data['team1']['total_points'] - $current_data['team2']['total_points']);
						$current_data['team2']['spread'] = abs($current_data['team1']['total_points'] - $current_data['team2']['total_points']);
					} else {
						$current_data['team1']['spread'] = abs($current_data['team1']['total_points'] - $current_data['team2']['total_points']);
						$current_data['team2']['spread'] = -abs($current_data['team1']['total_points'] - $current_data['team2']['total_points']);
					}
					$current_data['team1']['overunder'] = $current_data['team2']['overunder'] = $current_data['team1']['total_points'] + $current_data['team2']['total_points'];

					$current_data['team1']['moneyline'] = number_format(($current_data['team1']['total_points'] * 3.65) * -1, 0);
					$current_data['team2']['moneyline'] = number_format($current_data['team2']['total_points'] * 2.85, 0);

					array_push($contest_data, $current_data);
					break;
				}
			}
		}
	} else {
		echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 1)</p></div>';
	}

	//sort the games

	foreach ($contest_data as $key => $part) {
		$sort[$key] = strtotime($part['game_start']);
	}
	array_multisort($sort, SORT_ASC, $contest_data);

	//adding rotation numbers
	for ($i = 0; $i < count($contest_data); $i++) {
		$contest_data[$i]['team1']['rotation_number'] =  "F" . (++$league_count);
		$contest_data[$i]['team2']['rotation_number'] =  "F" . (++$league_count);
	}
	//get all dates
	foreach ($contest_data as $date) {
		$current_date = date('Y-m-d', strtotime($date['game_start']));
		if (!in_array($current_date, $contest_dates)) {
			array_push($contest_dates, $current_date);
		}
	}

	//increament the week by 1
	if ($schedule_type == 'Preseason') {
		$nfl_week = (int)$nfl_week + 1;
	} else {
		$nfl_week = (int)$nfl_week;
	}

	$the_contest_date_unix = strtoupper(date('U', strtotime($contest_data[0]['game_start'])));
	$the_contest_date_sort = strtoupper(date('Y-m-d H:i:s', strtotime($contest_data[0]['game_start'])));

	$teams_contest = array(
		'post_status' => 'publish',
		'post_title' => $league_title . ' Game Lines: Season ' . $schedule_type . ' ' . $season . ' Week ' . $nfl_week,
		'post_type' => 'contest',
		'meta_input' => array(
			'contest_type' => 'Team vs Team',
			'contest_date' => $the_contest_date_unix,
			'contest_date_sort' => $the_contest_date_sort,
			'contest_status' => 'Open',
			'contest_data' => json_encode($contest_data, JSON_UNESCAPED_UNICODE),
			'contest_title_without_type' => $league_title . ' Game Lines: Season ' . $schedule_type . ' ' . $season . ' Week ' . $nfl_week,
			'contest_projections_url' => $projections_url,
			'contest_nfl_defensive_projections_url' => $defense_projections_url,
			'games_status' => 'Not Done'
		),
		'tax_input' => array(
			'league' => $tax_league,
			'schedule' => array($termParent->term_id, $schedule_id)
		),
	);

	$post_exists = post_exists($league_title . ' Game Lines: Season ' . $schedule_type . ' ' . $season . ' Week ' . $nfl_week);



	if ($post_exists == 0) {
		$nfl_main_contest = wp_insert_post($teams_contest);
		$contests_created++;
		$game_details = array();

		foreach ($contest_dates as $date) {

			$current_data = array();

			foreach ($contest_data as $contest) {
				if ($date == date('Y-m-d', strtotime($contest['game_start']))) {
					array_push($current_data, $contest);
				}
			}

			array_push($game_details, $current_data);
		}

		foreach ($game_details as $contest_data) {
			$the_contest_date_notime = date('m-d-Y', strtotime($contest_data[0]['game_start']));
			$the_contest_date_notime_day = date('l F jS', strtotime($contest_data[0]['game_start']));
			$the_contest_date_unix = strtoupper(date('U', strtotime($contest_data[0]['game_start'])));
			$the_contest_date_sort = strtoupper(date('Y-m-d H:i:s', strtotime($contest_data[0]['game_start'])));

			$teams_contest = array(
				'post_status' => 'publish',
				'post_title' => $league_title . ': Game Lines ' . $the_contest_date_notime,
				'post_type' => 'contest',
				'meta_input' => array(
					'contest_type' => 'Team vs Team',
					'contest_date' => $the_contest_date_unix,
					'contest_date_sort' => $the_contest_date_sort,
					'contest_status' => 'Open',
					'contest_data' => json_encode($contest_data, JSON_UNESCAPED_UNICODE),
					'contest_title_without_type' => $league_title . ': Game Lines ' . $the_contest_date_notime_day,
					'nfl_main_contest' => $nfl_main_contest,
					'contest_projections_url' => $projections_url,
					'games_status' => 'Not Done'
				),
				'tax_input' => array(
					'league' => $tax_league,
					'schedule' => array($termParent->term_id, $schedule_id)
				),
			);

			wp_insert_post($teams_contest);
			$contests_created++;
		}

		echo '<div id="message" class="updated fade"><p>' . $contests_created . ' NFL contests created.</p></div>';
	} else {
		echo '<div id="message" class="updated fade error"><p>The contest for Season ' . $schedule_type . ' ' . $season . ' for Week ' . $nfl_week . ' already exists. Please remove it to create another one.</p></div>';
	}
}

//new creating demo contest function
function create_nfl_demo_projections_and_contests($numofplays, $projection_key)
{

	//Define vars
	$parent_team = 2065;
	$tax_league = 6;
	$league_title = 'NFL';
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
		$team->TeamID = get_term_meta($team->term_id, 'TeamID', true);
		$team->team_abbrev = get_term_meta($team->term_id, 'team_abbreviation', true);
	}

	$number_of_plays = array(0);

	if ($numofplays > 0) {
		$number_of_plays[1] = $numofplays;
	}

	foreach ($number_of_plays as $number_of_play) {
		$current_contest = array();

		//Get player projections via SportsDataIO API

		$projections_url = "https://fly.sportsdata.io/v3/nfl/stats/json/SimulatedBoxScoresV3/" . $number_of_play . "?key=" . $projection_key;

		$content_response = wp_remote_get($projections_url, array(
			'method'	=> 'GET',
			'timeout'	=> 60,
			'headers'	=> array(
				'Ocp-Apim-Subscription-Key' => $projection_key,
			),
		));

		if (is_array($content_response) && !is_wp_error($content_response)) {

			$contents = json_decode($content_response['body']);

			foreach ($contents as $content) {

				$team1 = array();
				$team2 = array();
				$team_1_players = array();
				$team_2_players = array();
				$contest_player_types = array();
				$current_game = array();

				foreach ($content->TeamGames as $player) {

					if (empty($team1) || empty($team2)) {

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
									"spread" => $player->PointSpread,
									"overunder" => $player->OverUnder,
								);
								break;
							}
						}

						if ($player->HomeOrAway == "AWAY") {
							$team1 = $team_information;
							$team1['moneyline'] = $content->Score->AwayTeamMoneyLine;
						} else {
							$team2 = $team_information;
							$team2['moneyline'] = $content->Score->HomeTeamMoneyLine;
						}
					}
				}

				foreach ($content->PlayerGames as $player) {

					$contest_date_time = $player->GameDate;
					$gameID = $player->GameKey;
					$season_type = $player->SeasonType;
					$season = $player->Season;
					$week = $player->Week;
					$contest_game_over = $player->IsGameOver;

					if (!in_array($player->Position, $contest_player_types)) {
						array_push($contest_player_types, $player->Position);
					}
				}

				foreach ($contest_player_types as $player_type) {

					${'players_' . $player_type . '1'} = 0;
					${'players_' . $player_type . '2'} = 0;
				}

				foreach ($content->PlayerGames as $player) {

					if ($player->Team == $team1['team_abbrev']) {

						foreach ($contest_player_types as $player_type) {

							if ($player->Position == $player_type) {

								${'players_' . $player_type . '1'} += $player->FantasyPointsFantasyDraft;
							}
						}
					}

					if ($player->Team == $team2['team_abbrev']) {

						foreach ($contest_player_types as $player_type) {

							if ($player->Position == $player_type) {

								${'players_' . $player_type . '2'} += $player->FantasyPointsFantasyDraft;
							}
						}
					}
				}

				foreach ($contest_player_types as $player_type) {

					$team_1_players[$player_type] = ${'players_' . $player_type . '1'};
					$team_2_players[$player_type] = ${'players_' . $player_type . '2'};

					$total_points_1 += ${'players_' . $player_type . '1'};
					$total_points_2 += ${'players_' . $player_type . '2'};
				}

				$team1['total_points'] = $total_points_1;
				$team1['player'] = $team_1_players;
				$team2['total_points'] = $total_points_2;
				$team2['player'] = $team_2_players;


				$current_game['game_id'] = $gameID;
				// $current_game['game_start'] = $contest_date_time;

				if (!isset($demo_datetime)) {
					if ($numofplays > 0) {
						//Demo Date Time
						$demo_datetime = strtotime(date('Y-m-d g:ia', current_time('timestamp')));
					} else {
						//Demo Date Time
						$demo_datetime = strtotime(date('Y-m-d', current_time('timestamp'))) + 68400;
					}
				}

				$current_game['game_start'] = date('Y-m-d\TH:i:s', $demo_datetime);
				$demo_datetime += 86400;

				$current_game['season_type'] = $season_type;
				$current_game['season'] = $season;
				$current_game['week'] = $week;
				$current_game['is_game_over'] = $contest_game_over;
				$current_game['player_types'] = $contest_player_types;
				$current_game['team1'] = $team1;
				$current_game['team2'] = $team2;

				array_push($current_contest, $current_game);
			}

			unset($demo_datetime);

			foreach ($current_contest as $key => $part) {
				$sort[$key] = strtotime($part['game_start']);
			}
			array_multisort($sort, SORT_ASC, $current_contest);

			array_push($game_data, $current_contest);
		} else {
			echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 1)</p></div>';
		}
	}

	$schedule_type = $game_data[0][0]['season'];
	$nfl_week = $game_data[0][0]['week'];
	$the_contest_date_unix = strtoupper(date('U', strtotime($game_data[0][0]['game_start'])));
	$the_contest_date_sort = strtoupper(date('Y-m-d H:i:s', strtotime($game_data[0][0]['game_start'])));

	//getting schedule ids
	$parent_term = get_term_by('name', $schedule_type . ' NFL Season', 'schedule');
	$parent_id = $parent_term->term_id;

	$week_args = ['name' => 'Week ' . $nfl_week, 'parent' => $parent_id];
	$week_terms = get_terms('schedule', $week_args);
	$week_id = $week_terms[0]->term_id;

	$game_done_status = "Not Done";

	if (count($game_data) == 1) {
		$contest_result = "";
	} else {
		$contest_result = json_encode($game_data[1], JSON_UNESCAPED_UNICODE);
	}


	$teams_contest = array(
		'post_status' => 'publish',
		'post_title' => $league_title . ': Game Lines ' . $schedule_type . ' Week ' . $nfl_week,
		'post_type' => 'contest',
		'meta_input' => array(
			'contest_type' => 'Team vs Team',
			'contest_date' => $the_contest_date_unix,
			'contest_date_sort' => $the_contest_date_sort,
			'contest_status' => 'Open',
			'contest_data' => json_encode($game_data[0], JSON_UNESCAPED_UNICODE),
			'contest_results' => $contest_result,
			'contest_title_without_type' => $league_title . ': Game Lines ' . $schedule_type . ' Week ' . $nfl_week,
			'contest_projections_url' => $projections_url,
			'games_status' => $game_done_status
		),
		'tax_input' => array(
			'league' => $tax_league,
			'schedule' => array($parent_id, $week_id)
		),
	);

	$game_date = date('Y-m-d', current_time('timestamp'));

	$today_args = array(
		'post_type' => 'contest',
		'meta_query' => array(
			array(
				'key'     => 'contest_date_sort',
				'value'   => $game_date,
				'compare' => 'LIKE'
			)
		),
		'tax_query' => array(
			array(
				'taxonomy' => 'league',
				'field'    => 'slug',
				'terms'    => 'nfl'
			),
		),
	);
	$today_query = new WP_Query($today_args);
	$post_exists = count($today_query->posts);

	if ($post_exists == 0) {
		wp_insert_post($teams_contest);
		echo '<div id="message" class="updated fade"><p>NFL contest is created.</p></div>';
	} else {
		echo '<div id="message" class="updated fade error"><p>Contest for this date already exists. Please try again tomorrow.</p></div>';
	}
}

//New Update Projection NFL Contest
function update_nfl_projection_scores($projection_key)
{

	$league_count = 400;
	$parent_team = 2065;
	$league_title = 'NFL';

	//Prepare team arrays

	$args = array(
		'taxonomy' => 'team',
		'hide_empty' => false,
		'child_of' => $parent_team,
	);

	$all_teams = get_terms($args);

	foreach ($all_teams as $team) {
		$team->TeamID = get_term_meta($team->term_id, 'TeamID', true);
		$team->team_abbrev = get_term_meta($team->term_id, 'team_abbreviation', true);
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

	$update_query = new WP_Query($update_args);
	foreach ($update_query->posts as $updatepost) {
		
		$contest_id = $updatepost->ID;
		
		
		$nfl_main_contest = get_field('nfl_main_contest', $contest_id);
		
		if ($nfl_main_contest == "") {
			
			$new_league_count = 400;
			$all_terms = get_the_terms($contest_id, 'schedule');
			foreach ($all_terms as $current_term) {
				if ($current_term->description) {
					$schedule_id = $current_term->term_id;

					$start_date = get_field('contest_date', $contest_id);
					$current_time =  current_time('timestamp');

					if ($start_date == $current_time) {
						continue;
					}

					//get season type and week

					$schedule_type = get_field('schedule_type', 'schedule_' . $schedule_id);

					if ($schedule_type == 'Preseason') {
						$schedule_type_short = 'PRE';
					} else if ($schedule_type == 'Regular Season') {
						$schedule_type_short = 'REG';
					} else if ($schedule_type == 'Playoffs') {
						$schedule_type_short = 'POST';
					}

					$nfl_week = get_field('nfl_week', 'schedule_' . $schedule_id);

					//get year for the season

					$term = get_term($schedule_id, 'schedule');
					$termParent = ($term->parent == 0) ? $term : get_term($term->parent, 'schedule');
					$season = substr($termParent->name, 0, 4);

					//Get player projections via SportsDataIO API

					$projections_url = "https://fly.sportsdata.io/v3/nfl/projections/json/PlayerGameProjectionStatsByWeek/" . $season . $schedule_type_short . "/" . $nfl_week . "?key=" . $projection_key;
					$defense_projections_url = "https://fly.sportsdata.io/v3/nfl/projections/json/FantasyDefenseProjectionsByGame/" . $season . $schedule_type_short . "/" . $nfl_week . "?key=" . $projection_key;

					$contest_response = wp_remote_get($projections_url, array(
						'method'	=> 'GET',
						'timeout'	=> 60,
						'headers'	=> array(
							'Ocp-Apim-Subscription-Key' => $projection_key,
						),
					));
					$defense_contest_response = wp_remote_get($defense_projections_url, array(
						'method'	=> 'GET',
						'timeout'	=> 60,
						'headers'	=> array(
							'Ocp-Apim-Subscription-Key' => $projection_key,
						),
					));



					if (is_array($contest_response) && !is_wp_error($contest_response) && is_array($defense_contest_response) && !is_wp_error($defense_contest_response)) {

						$contents = json_decode($contest_response['body']);
						$defense_contents = json_decode($defense_contest_response['body']);
						$contest_data = array();
						$contest_teams = array();
						$contest_positions = array();

						//giving defense player a position
						foreach ($defense_contents as $content) {
							$content->Position = "DST";
						}

						//merge offensive and defensive players
						$contents = array_merge($contents, $defense_contents);

						//get all team
						foreach ($contents as $content) {
							if (!in_array($content->Team, $contest_teams)) {
								if ($content->HomeOrAway == "AWAY") {
									$contest_teams[$content->Opponent] = $content->Team;
								}
							}
						}

						//get all positions
						foreach ($contents as $content) {
							if (!in_array($content->Position, $contest_positions)) {
								array_push($contest_positions, $content->Position);
							}
						}

						//get all positions
						foreach ($contest_teams as $opponent => $team) {
							foreach ($contest_positions as $position) {
								${$team . $position} = 0;
							}

							foreach ($contents as $content) {
								foreach ($contest_positions as $position) {
									if ($team == $content->Team) {
										if ($content->Position == $position) {
											${$team . $position} += $content->FantasyPointsDraftKings;
										}
									}
									if ($opponent == $content->Team) {
										if ($content->Position == $position) {
											${$opponent . $position} += $content->FantasyPointsDraftKings;
										}
									}
								}
							}
						}

						foreach ($contest_teams as $opponent => $team) {
							foreach ($contents as $content) {
								if ($team == $content->Team) {

									foreach ($all_teams as $current_term) {
										if ($team == $current_term->team_abbrev) {
											$team1_term_id = $current_term->term_id;
											$team1_team_id = $current_term->TeamID;
											$team1_name = $current_term->name;
											$team1_slug = $current_term->slug;
											$team1_term_taxonomy_id = $current_term->term_taxonomy_id;
											$team1_parent = $current_term->parent;
										}
										if ($opponent == $current_term->team_abbrev) {
											$team2_term_id = $current_term->term_id;
											$team2_team_id = $current_term->TeamID;
											$team2_name = $current_term->name;
											$team2_slug = $current_term->slug;
											$team2_term_taxonomy_id = $current_term->term_taxonomy_id;
											$team2_parent = $current_term->parent;
										}
									}


									$current_data = array();
									$current_data['game_id'] = $content->GameKey;
									$current_data['game_start'] = $content->DateTime;
									$current_data['season_type'] = $content->SeasonType;
									$current_data['season'] = $content->Season;
									$current_data['week'] = $nfl_week;
									$current_data['is_game_over'] = $content->IsGameOver;
									$current_data['player_types'] = $contest_positions;

									$current_data['team1']['term_id'] = $team1_term_id;
									$current_data['team1']['TeamID'] = $team1_team_id;
									$current_data['team1']['name'] = $team1_name;
									$current_data['team1']['slug'] = $team1_slug;
									$current_data['team1']['term_taxonomy_id'] = $team1_term_taxonomy_id;
									$current_data['team1']['taxonomy'] = "team";
									$current_data['team1']['parent'] = $team1_parent;
									$current_data['team1']['team_abbrev'] = $content->Team;
									$current_data['team1']['home_away'] = "away";
									$current_data['team1']['opponent'] = $team2_term_id;
									$current_data['team1']['opponent_abbrev'] = $content->Opponent;
									$current_data['team1']['total_points'] = 0;

									foreach ($contest_positions as $position) {
										$current_data['team1']['total_points'] += ${$team . $position};
										$current_data['team1']['player'][$position] = ${$team . $position};
									}

									$current_data['team2']['term_id'] = $team2_term_id;
									$current_data['team2']['TeamID'] = $team2_team_id;
									$current_data['team2']['name'] = $team2_name;
									$current_data['team2']['slug'] = $team2_slug;
									$current_data['team2']['term_taxonomy_id'] = $team2_term_taxonomy_id;
									$current_data['team2']['taxonomy'] = "team";
									$current_data['team2']['parent'] = $team2_parent;
									$current_data['team2']['team_abbrev'] = $content->Opponent;
									$current_data['team2']['home_away'] = "home";
									$current_data['team2']['opponent'] = $team1_term_id;
									$current_data['team2']['opponent_abbrev'] = $content->Team;
									$current_data['team2']['total_points'] = 0;

									foreach ($contest_positions as $position) {
										$current_data['team2']['total_points'] += ${$opponent . $position};
										$current_data['team2']['player'][$position] = ${$opponent . $position};
									}

									if ($current_data['team1']['total_points'] > $current_data['team2']['total_points']) {
										$current_data['team1']['spread'] = -abs($current_data['team1']['total_points'] - $current_data['team2']['total_points']);
										$current_data['team2']['spread'] = abs($current_data['team1']['total_points'] - $current_data['team2']['total_points']);
									} else {
										$current_data['team1']['spread'] = abs($current_data['team1']['total_points'] - $current_data['team2']['total_points']);
										$current_data['team2']['spread'] = -abs($current_data['team1']['total_points'] - $current_data['team2']['total_points']);
									}
									$current_data['team1']['overunder'] = $current_data['team2']['overunder'] = $current_data['team1']['total_points'] + $current_data['team2']['total_points'];

									$current_data['team1']['moneyline'] = number_format(($current_data['team1']['total_points'] * 3.65) * -1, 0);
									$current_data['team2']['moneyline'] = number_format($current_data['team2']['total_points'] * 2.85, 0);

									array_push($contest_data, $current_data);
									break;
								}
							}
						}
					}

					//sort the games

					foreach ($contest_data as $key => $part) {
						$sort[$key] = strtotime($part['game_start']);
					}
					array_multisort($sort, SORT_ASC, $contest_data);
					//adding rotation numbers
					
					for ($i = 0; $i < count($contest_data); $i++) {
						$contest_data[$i]['team1']['rotation_number'] =  "F" . (++$new_league_count);
						$contest_data[$i]['team2']['rotation_number'] =  "F" . (++$new_league_count);
					}
					update_field('contest_data', json_encode($contest_data, JSON_UNESCAPED_UNICODE), $contest_id);
				}
			}
		}
	}
}

/*
function create_nfl_demo_projections_and_contests($numofplays,$projection_key) {
					
	//Define vars
	$parent_team = 2065;
	$tax_league = 6;
	$league_title = 'NFL';
	$contests_created = 0;
	$game_data = array();

	$demo_datetime = strtotime(date('Y-m-d', current_time( 'timestamp' ))) + 68400;
	
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
	
	$projections_url = "https://fly.sportsdata.io/v3/nfl/stats/json/SimulatedBoxScoresV3/".$numofplays."?key=".$projection_key;
	
	$content_response = wp_remote_get( $projections_url, array(
		'method'	=> 'GET',
		'timeout'	=> 60,
		'headers'	=> array(
			'Ocp-Apim-Subscription-Key' => $projection_key,
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
								"spread" => $player->PointSpread,
								"overunder" => $player->OverUnder,
							);
							break;
						}
					}

					if($player->HomeOrAway == "AWAY"){
						$team1 = $team_information;
						$team1['moneyline'] = $content->Score->AwayTeamMoneyLine;
					}else{
						$team2 = $team_information;
						$team2['moneyline'] = $content->Score->HomeTeamMoneyLine;
					}
				}
			}
			
			foreach ($content->PlayerGames as $player) {
				
				$contest_date_time = $player->GameDate;
				$gameID = $player->GameKey;
				$contest_game_over = $player->IsGameOver;

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

				$total_points_1 += ${'players_'.$player_type.'1'};
				$total_points_2 += ${'players_'.$player_type.'2'};
				
			}

			$team1['total_points'] = $total_points_1;
			$team1['player'] = $team_1_players;
			$team2['total_points'] = $total_points_2;
			$team2['player'] = $team_2_players;
			
			
			$current_game['game_id'] = $gameID;
			// $current_game['game_start'] = $contest_date_time;

			//Demo Date Time
			$current_game['game_start'] = date('Y-m-d\TH:i:s', $demo_datetime);
			$demo_datetime += 900; 

			$current_game['is_game_over'] = $contest_game_over;
			$current_game['player_types'] = $contest_player_types;
			$current_game['team1'] = $team1;
			$current_game['team2'] = $team2;
			
			array_push($game_data,$current_game);

		}

		
		
		if(!empty($game_data)){
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
		}
	
	}else {
	
		echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 1)</p></div>';
	
	}					
	
}
*/

//New Live NFL Contest
function update_nfl_live_scores($stats_key)
{

	$parent_team = 2065;
	$league_title = 'NFL';

	//Prepare team arrays

	$args = array(
		'taxonomy' => 'team',
		'hide_empty' => false,
		'child_of' => $parent_team,
	);

	$all_teams = get_terms($args);

	foreach ($all_teams as $team) {
		$team->TeamID = get_term_meta($team->term_id, 'TeamID', true);
		$team->team_abbrev = get_term_meta($team->term_id, 'team_abbreviation', true);
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

	$update_query = new WP_Query($update_args);

	foreach ($update_query->posts as $updatepost) {
		echo game_details_contest($updatepost->ID);
		$contest_id = $updatepost->ID;


		$nfl_main_contest = get_field('nfl_main_contest', $contest_id);

		if ($nfl_main_contest == "") {

			$all_terms = get_the_terms($contest_id, 'schedule');
			foreach ($all_terms as $current_term) {
				if ($current_term->description) {
					$schedule_id = $current_term->term_id;
					$schedule_end = json_decode($current_term->description)->end_date;

					$start_date = get_field('contest_date', $contest_id);
					$end_date = strtotime($schedule_end) + 28800;
					$current_time =  current_time('timestamp');

					if ($start_date >= $current_time) {
						continue;
					}

					//get season type and week

					$schedule_type = get_field('schedule_type', 'schedule_' . $schedule_id);

					if ($schedule_type == 'Preseason') {
						$schedule_type_short = 'PRE';
					} else if ($schedule_type == 'Regular Season') {
						$schedule_type_short = 'REG';
					} else if ($schedule_type == 'Playoffs') {
						$schedule_type_short = 'POST';
					}

					$nfl_week = get_field('nfl_week', 'schedule_' . $schedule_id);

					//get year for the season

					$term = get_term($schedule_id, 'schedule');
					$termParent = ($term->parent == 0) ? $term : get_term($term->parent, 'schedule');
					$season = substr($termParent->name, 0, 4);

					//Get player projections via SportsDataIO API

					$stats_url = "https://fly.sportsdata.io/v3/nfl/stats/json/PlayerGameStatsByWeek/" . $season . $schedule_type_short . "/" . $nfl_week . "?key=" . $stats_key;
					$defense_stats_url = "https://api.sportsdata.io/v3/nfl/stats/json/FantasyDefenseByGame/" . $season . $schedule_type_short . "/" . $nfl_week . "?key=" . $stats_key;

					$contest_response = wp_remote_get($stats_url, array(
						'method'	=> 'GET',
						'timeout'	=> 60,
						'headers'	=> array(
							'Ocp-Apim-Subscription-Key' => $stats_key,
						),
					));
					$defense_response = wp_remote_get($defense_stats_url, array(
						'method'	=> 'GET',
						'timeout'	=> 60,
						'headers'	=> array(
							'Ocp-Apim-Subscription-Key' => $stats_key,
						),
					));



					if (is_array($contest_response) && !is_wp_error($contest_response) && is_array($defense_response) && !is_wp_error($defense_response)) {

						$contents = json_decode($contest_response['body']);
						$defense_contents = json_decode($defense_response['body']);
						$contest_data = array();
						$contest_teams = array();
						$contest_positions = array();

						//giving defense player a position
						foreach ($defense_contents as $content) {
							$content->Position = "DST";
						}

						//merge offensive and defensive players
						$contents = array_merge($contents, $defense_contents);

						//get all team
						foreach ($contents as $content) {
							if (!in_array($content->Team, $contest_teams)) {
								if ($content->HomeOrAway == "AWAY") {
									$contest_teams[$content->Opponent] = $content->Team;
								}
							}
						}

						//get all positions
						foreach ($contents as $content) {
							if (!in_array($content->Position, $contest_positions)) {
								array_push($contest_positions, $content->Position);
							}
						}

						//get all positions
						foreach ($contest_teams as $opponent => $team) {
							foreach ($contest_positions as $position) {
								${$team . $position} = 0;
								${$opponent . $position} = 0;
							}

							foreach ($contents as $content) {
								foreach ($contest_positions as $position) {
									if ($team == $content->Team) {
										if ($content->Position == $position) {
											${$team . $position} += $content->FantasyPointsDraftKings;
										}
									}
									if ($opponent == $content->Team) {
										if ($content->Position == $position) {
											${$opponent . $position} += $content->FantasyPointsDraftKings;
										}
									}
								}
							}
						}


						foreach ($contest_teams as $opponent => $team) {
							foreach ($contents as $content) {
								if ($team == $content->Team) {

									foreach ($all_teams as $current_team) {
										if ($team == $current_team->team_abbrev) {
											$team1_term_id = $current_team->term_id;
											$team1_team_id = $current_team->TeamID;
											$team1_name = $current_team->name;
											$team1_slug = $current_team->slug;
											$team1_term_taxonomy_id = $current_team->term_taxonomy_id;
											$team1_parent = $current_team->parent;
										}
										if ($opponent == $current_team->team_abbrev) {
											$team2_term_id = $current_team->term_id;
											$team2_team_id = $current_team->TeamID;
											$team2_name = $current_team->name;
											$team2_slug = $current_team->slug;
											$team2_term_taxonomy_id = $current_team->term_taxonomy_id;
											$team2_parent = $current_team->parent;
										}
									}


									$current_data = array();
									$current_data['game_id'] = $content->GameKey;
									$current_data['game_start'] = $content->DateTime;
									$current_data['season_type'] = $content->SeasonType;
									$current_data['season'] = $content->Season;
									$current_data['week'] = $nfl_week;
									$current_data['is_game_over'] = $content->IsGameOver;
									$current_data['player_types'] = $contest_positions;

									$current_data['team1']['term_id'] = $team1_term_id;
									$current_data['team1']['TeamID'] = $team1_team_id;
									$current_data['team1']['name'] = $team1_name;
									$current_data['team1']['slug'] = $team1_slug;
									$current_data['team1']['term_taxonomy_id'] = $team1_term_taxonomy_id;
									$current_data['team1']['taxonomy'] = "team";
									$current_data['team1']['parent'] = $team1_parent;
									$current_data['team1']['team_abbrev'] = $content->Team;
									$current_data['team1']['home_away'] = "away";
									$current_data['team1']['opponent'] = $team2_term_id;
									$current_data['team1']['opponent_abbrev'] = $content->Opponent;
									$current_data['team1']['total_points'] = 0;

									foreach ($contest_positions as $position) {
										$current_data['team1']['total_points'] += ${$team . $position};
										$current_data['team1']['player'][$position] = ${$team . $position};
									}

									$current_data['team2']['term_id'] = $team2_term_id;
									$current_data['team2']['TeamID'] = $team2_team_id;
									$current_data['team2']['name'] = $team2_name;
									$current_data['team2']['slug'] = $team2_slug;
									$current_data['team2']['term_taxonomy_id'] = $team2_term_taxonomy_id;
									$current_data['team2']['taxonomy'] = "team";
									$current_data['team2']['parent'] = $team2_parent;
									$current_data['team2']['team_abbrev'] = $content->Opponent;
									$current_data['team2']['home_away'] = "home";
									$current_data['team2']['opponent'] = $team1_term_id;
									$current_data['team2']['opponent_abbrev'] = $content->Team;
									$current_data['team2']['total_points'] = 0;

									foreach ($contest_positions as $position) {
										$current_data['team2']['total_points'] += ${$opponent . $position};
										$current_data['team2']['player'][$position] = ${$opponent . $position};
									}

									if ($current_data['team1']['total_points'] > $current_data['team2']['total_points']) {
										$current_data['team1']['spread'] = -abs($current_data['team1']['total_points'] - $current_data['team2']['total_points']);
										$current_data['team2']['spread'] = abs($current_data['team1']['total_points'] - $current_data['team2']['total_points']);
									} else {
										$current_data['team1']['spread'] = abs($current_data['team1']['total_points'] - $current_data['team2']['total_points']);
										$current_data['team2']['spread'] = -abs($current_data['team1']['total_points'] - $current_data['team2']['total_points']);
									}
									$current_data['team1']['overunder'] = $current_data['team2']['overunder'] = $current_data['team1']['total_points'] + $current_data['team2']['total_points'];

									$current_data['team1']['moneyline'] = number_format(($current_data['team1']['total_points'] * 3.65) * -1, 0);
									$current_data['team2']['moneyline'] = number_format($current_data['team2']['total_points'] * 2.85, 0);

									array_push($contest_data, $current_data);
									break;
								}
							}
						}
					} else {
						echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 1)</p></div>';
					}

					//sort the games

					foreach ($contest_data as $key => $part) {
						$sort[$key] = strtotime($part['game_start']);
					}
					array_multisort($sort, SORT_ASC, $contest_data);

					$game_done = "Done";

					if ($end_date > $current_time) {
						$game_done = "Not Done";
					}
						// settled bidding status in gamedetails when game is over.
						foreach($contest_data as $game){
							if(!$game['is_game_over'] && $game['is_game_over'] != "canceled"){
								$game_done = "Not Done";
								
							}
						
							elseif($game['is_game_over']){
							$game_details_query = new WP_Query(array(
								'post_type'  => 'gamedeatils',
								'meta_query' => array(
									array(
										'key'     => 'contest_id',
										'value'   => $updatepost->ID,
									),
									array(
										'key' => 'game_id',
										'value'   => $game['game_id']
									),
								),
							));
							
							// print_r($game_details_query);
							$current_contest_id = $game_details_query->posts[0]->ID;
							// $bidding_status_settled = $bidding_status['bidding_status'][0];
							update_field('bidding_status', 3 , $current_contest_id);
						}
						}
			

					update_field('contest_results', json_encode($contest_data, JSON_UNESCAPED_UNICODE), $contest_id);
					update_field('games_status', $game_done, $contest_id);
					update_field('contest_status', 'In Progress', $contest_id);
					update_field('contest_live_stats_url', $stats_url, $contest_id);
				}
			}
		}
	}
}

//Update Live Contests

function update_live_nfl_contests($stats_key, $completed)
{

	$passingTD_VAL = get_option('nfl-points-passingTD');
	$passingYds_VAL = get_option('nfl-points-passingYds');
	$passingInt_VAL = get_option('nfl-points-passingInt');
	$rushingTD_VAL = get_option('nfl-points-rushingTD');
	$rushingYds_VAL = get_option('nfl-points-rushingYds');
	$receivingTD_VAL = get_option('nfl-points-receivingTD');
	$receivingYds_VAL = get_option('nfl-points-receivingYds');
	$receptions_VAL = get_option('nfl-points-receptions');
	$kickReturnTD_VAL = get_option('nfl-points-kickReturnTD');
	$puntReturnTD_VAL = get_option('nfl-points-puntReturnTD');
	$fumbleLost_VAL = get_option('nfl-points-fumbleLost');
	$ownFumbleRecTD_VAL = get_option('nfl-points-ownFumbleRecTD');
	$twoPtConversionScored_VAL = get_option('nfl-points-2ptConversionScored');
	$twoPtConversionPass_VAL = get_option('nfl-points-2ptConversionPass');
	$defensiveSack_VAL = get_option('nfl-points-defensiveSack');
	$defensiveFumbleRecovery_VAL = get_option('nfl-points-defensiveFumbleRecovery');
	$defkickoffReturnTD_VAL = get_option('nfl-points-defkickoffReturnTD');
	$defPuntReturnTD_VAL = get_option('nfl-points-defPuntReturnTD');
	$defExtraPtReturn_VAL = get_option('nfl-points-defExtraPtReturn');
	$defSafety_VAL = get_option('nfl-points-defSafety');
	$defInterception_VAL = get_option('nfl-points-defInterception');
	$defInterceptionReturnTD_VAL = get_option('nfl-points-defInterceptionReturnTD');
	$def2PtConversionReturn_VAL = get_option('nfl-points-def2PtConversionReturn');
	$defFumbleRecoveryTD_VAL = get_option('nfl-points-defFumbleRecoveryTD');
	$defBlockedKick_VAL = get_option('nfl-points-defBlockedKick');
	$def0ptsAllowed_VAL = get_option('nfl-points-def0ptsAllowed');
	$def1_6ptsAllowed_VAL = get_option('nfl-points-def1-6ptsAllowed');
	$def7_13ptsAllowed_VAL = get_option('nfl-points-def7-13ptsAllowed');
	$def14_20ptsAllowed_VAL = get_option('nfl-points-def14-20ptsAllowed');
	$def21_27ptsAllowed_VAL = get_option('nfl-points-def21-27ptsAllowed');
	$def28_34ptsAllowed_VAL = get_option('nfl-points-def28-34ptsAllowed');
	$def35ptsAllowed_VAL = get_option('nfl-points-def35ptsAllowed');


	//Update Live NFL Contests

	$contests_updated = 0;

	if ($completed == false) {
		$contest_status = 'In Progress';
	} else if ($completed == true) {
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
				'terms'    => 'nfl',
			),
		),
	);

	$the_query = new WP_Query($args);
	$load_api = false;

	if ($the_query->have_posts()) {

		$contest_data = array();
		$contest_dates = array();

		while ($the_query->have_posts()) {

			$the_query->the_post();
			global $post;

			$this_contest = array();

			$this_contest_date = get_field('contest_date');
			$contest_type = get_field('contest_type');

			$this_contest['date'] = strtoupper(date('Y-M-d', $this_contest_date));
			$this_contest['date_time'] = $this_contest_date;
			$this_contest['contest_id'] = $post->ID;
			$this_contest['contest_type'] = $contest_type;
			$this_contest['contest_title'] = $post->post_title;

			if (!in_array($this_contest['date'], $contest_dates)) {
				$contest_dates[] = $this_contest['date'];
			}

			$main_contest = get_field('nfl_main_contest', $post->ID);
			$terms = get_the_terms($post->ID, 'schedule');

			if ($contest_type == 'Team vs Team') {

				if ($main_contest != '') {

					$load_api = true;

					if ($terms) {

						foreach ($terms as $schedule) {

							$schedule_id = $schedule->term_id;

							if ($schedule->parent != '') {

								$schedule_type = get_field('schedule_type', 'schedule_' . $schedule_id);

								if ($schedule_type == 'Preseason') {
									$schedule_url = '2019PRE';
								} else if ($schedule_type == 'Regular Season') {
									$schedule_url = '2019REG';
								} else if ($schedule_type == 'Playoffs') {
									$schedule_url = '2019POST';
								}

								$nfl_week = get_field('nfl_week', 'schedule_' . $schedule_id);
							}
						}
					}

					$contest_data[] = $this_contest;
				}
			} else if ($contest_type == 'Mixed') {

				$load_api = true;

				$terms = get_the_terms($post->ID, 'schedule');

				if ($terms) {

					foreach ($terms as $schedule) {

						$schedule_id = $schedule->term_id;

						if ($schedule->parent != '') {

							$schedule_type = get_field('schedule_type', 'schedule_' . $schedule_id);

							if ($schedule_type == 'Preseason') {
								$schedule_url = '2019PRE';
							} else if ($schedule_type == 'Regular Season') {
								$schedule_url = '2019REG';
							} else if ($schedule_type == 'Playoffs') {
								$schedule_url = '2019POST';
							}

							$nfl_week = get_field('nfl_week', 'schedule_' . $schedule_id);
						}
					}
				}


				$contest_data[] = $this_contest;
			} else if ($contest_type == 'Teams') {

				$load_api = true;

				$terms = get_the_terms($post->ID, 'schedule');

				if ($terms) {

					foreach ($terms as $schedule) {

						$schedule_id = $schedule->term_id;

						if ($schedule->parent != '') {

							$schedule_type = get_field('schedule_type', 'schedule_' . $schedule_id);

							if ($schedule_type == 'Preseason') {
								$schedule_url = '2019PRE';
							} else if ($schedule_type == 'Regular Season') {
								$schedule_url = '2019REG';
							} else if ($schedule_type == 'Playoffs') {
								$schedule_url = '2019POST';
							}

							$nfl_week = get_field('nfl_week', 'schedule_' . $schedule_id);
						}
					}
				}

				$contest_data[] = $this_contest;
			}
		}
	}

	wp_reset_query();



	if ($load_api == true) {

		$response = wp_remote_get("https://fly.sportsdata.io/v3/nfl/stats/json/PlayerGameStatsByWeek/$schedule_url/$nfl_week?key=$stats_key", array(
			'method'	=> 'GET',
			'timeout'	=> 60,
			'headers'	=> array(
				'Ocp-Apim-Subscription-Key' => $stats_key,
			),
		));

		if (is_array($response) && !is_wp_error($response)) {

			$stats = json_decode($response['body']);

			foreach ($contest_data as $contest) {

				$contest_results = json_decode(get_field('contest_data', $contest['contest_id']));

				if ($contest['contest_type'] == 'Team vs Team') {

					foreach ($contest_results as $game) {

						foreach ($game as $team) {

							$team->total_points = 0;

							foreach ($team->players as $players) {

								if (is_array($players)) {

									foreach ($players as $player) {

										$player->total_points = 0;
										$player->passingTD = 0;
										$player->passingTD_points = 0;
										$player->passingYds = 0;
										$player->passingYds_points = 0;
										$player->passingInt = 0;
										$player->passingInt_points = 0;
										$player->rushingTD = 0;
										$player->rushingTD_points = 0;
										$player->rushingYds = 0;
										$player->rushingYds_points = 0;
										$player->receivingTD = 0;
										$player->receivingTD_points = 0;
										$player->receivingYds = 0;
										$player->receivingYds_points = 0;
										$player->receptions = 0;
										$player->receptions_points = 0;
										$player->kickReturnTD = 0;
										$player->kickReturnTD_points = 0;
										$player->puntReturnTD = 0;
										$player->puntReturnTD_points = 0;
										$player->fumbleLost = 0;
										$player->fumbleLost_points = 0;
										$player->ownFumbleRecTD = 0;
										$player->ownFumbleRecTD_points = 0;
										$player->twoPtConversionScored = 0;
										$player->twoPtConversionScored_points = 0;
										$player->twoPtConversionPass = 0;
										$player->twoPtConversionPass_points = 0;
										$player->statsUpdated = 0;

										foreach ($stats as $stats_player) {

											if ($player->player_id == $stats_player->PlayerID) {

												$player->total_points = 0;
												$player->passingTD = $stats_player->PassingTouchdowns;
												$player->passingTD_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2);
												$player->passingYds = $stats_player->PassingYards;
												$player->passingYds_points = number_format($stats_player->PassingYards * $passingYds_VAL, 2);
												$player->passingInt = $stats_player->PassingInterceptions;
												$player->passingInt_points = number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2);
												$player->rushingTD = $stats_player->RushingTouchdowns;
												$player->rushingTD_points = number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2);
												$player->rushingYds = $stats_player->RushingYards;
												$player->rushingYds_points = number_format($stats_player->RushingYards * $rushingYds_VAL, 2);
												$player->receivingTD = $stats_player->ReceivingTouchdowns;
												$player->receivingTD_points = number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2);
												$player->receivingYds = $stats_player->ReceivingYards;
												$player->receivingYds_points = number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2);
												$player->receptions = $stats_player->Receptions;
												$player->receptions_points = number_format($stats_player->Receptions * $receptions_VAL, 2);
												$player->kickReturnTD = $stats_player->KickReturnTouchdowns;
												$player->kickReturnTD_points = number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2);
												$player->puntReturnTD = $stats_player->PuntReturnTouchdowns;
												$player->puntReturnTD_points = number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2);
												$player->fumbleLost = $stats_player->FumblesLost;
												$player->fumbleLost_points = number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2);
												$player->ownFumbleRecTD = $stats_player->FumbleReturnTouchdowns;
												$player->ownFumbleRecTD_points = number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2);
												$player->twoPtConversionScored = $stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions;
												$player->twoPtConversionScored_points = number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2);
												$player->twoPtConversionPass = $stats_player->TwoPointConversionPasses;
												$player->twoPtConversionPass_points = number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);
												$player->statsUpdated = 1;

												$player->total_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2) + number_format($stats_player->PassingYards * $passingYds_VAL, 2) + number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2) + number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2) + number_format($stats_player->RushingYards * $rushingYds_VAL, 2) + number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2) + number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2) + number_format($stats_player->Receptions * $receptions_VAL, 2) + number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2) + number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2) + number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2) + number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2) + number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2) + number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);

												$team->total_points = $team->total_points + $player->total_points;
											}
										}
									}
								}
							}
						}
					}

					update_field('contest_results', json_encode($contest_results, JSON_UNESCAPED_UNICODE), $contest['contest_id']);
					update_field('contest_live_stats_url', "https://fly.sportsdata.io/v3/nfl/stats/json/PlayerGameStatsByWeek/$schedule_url/$nfl_week?key=$stats_key", $contest['contest_id']);

					$contests_updated++;
				} else if ($contest['contest_type'] == 'Mixed') {

					foreach ($contest_results as $result) {

						$result->total_points = 0;

						$mixed_QB = $result->QB;
						$mixed_RB = $result->RB;
						$mixed_WR = $result->WR;
						$mixed_TE = $result->TE;
						$mixed_D = $result->D;

						$mixed_QB->total_points = 0;
						$mixed_QB->passingTD = 0;
						$mixed_QB->passingTD_points = 0;
						$mixed_QB->passingYds = 0;
						$mixed_QB->passingYds_points = 0;
						$mixed_QB->passingInt = 0;
						$mixed_QB->passingInt_points = 0;
						$mixed_QB->rushingTD = 0;
						$mixed_QB->rushingTD_points = 0;
						$mixed_QB->rushingYds = 0;
						$mixed_QB->rushingYds_points = 0;
						$mixed_QB->receivingTD = 0;
						$mixed_QB->receivingTD_points = 0;
						$mixed_QB->receivingYds = 0;
						$mixed_QB->receivingYds_points = 0;
						$mixed_QB->receptions = 0;
						$mixed_QB->receptions_points = 0;
						$mixed_QB->kickReturnTD = 0;
						$mixed_QB->kickReturnTD_points = 0;
						$mixed_QB->puntReturnTD = 0;
						$mixed_QB->puntReturnTD_points = 0;
						$mixed_QB->fumbleLost = 0;
						$mixed_QB->fumbleLost_points = 0;
						$mixed_QB->ownFumbleRecTD = 0;
						$mixed_QB->ownFumbleRecTD_points = 0;
						$mixed_QB->twoPtConversionScored = 0;
						$mixed_QB->twoPtConversionScored_points = 0;
						$mixed_QB->twoPtConversionPass = 0;
						$mixed_QB->twoPtConversionPass_points = 0;
						$mixed_QB->statsUpdated = 0;

						$mixed_RB->total_points = 0;
						$mixed_RB->passingTD = 0;
						$mixed_RB->passingTD_points = 0;
						$mixed_RB->passingYds = 0;
						$mixed_RB->passingYds_points = 0;
						$mixed_RB->passingInt = 0;
						$mixed_RB->passingInt_points = 0;
						$mixed_RB->rushingTD = 0;
						$mixed_RB->rushingTD_points = 0;
						$mixed_RB->rushingYds = 0;
						$mixed_RB->rushingYds_points = 0;
						$mixed_RB->receivingTD = 0;
						$mixed_RB->receivingTD_points = 0;
						$mixed_RB->receivingYds = 0;
						$mixed_RB->receivingYds_points = 0;
						$mixed_RB->receptions = 0;
						$mixed_RB->receptions_points = 0;
						$mixed_RB->kickReturnTD = 0;
						$mixed_RB->kickReturnTD_points = 0;
						$mixed_RB->puntReturnTD = 0;
						$mixed_RB->puntReturnTD_points = 0;
						$mixed_RB->fumbleLost = 0;
						$mixed_RB->fumbleLost_points = 0;
						$mixed_RB->ownFumbleRecTD = 0;
						$mixed_RB->ownFumbleRecTD_points = 0;
						$mixed_RB->twoPtConversionScored = 0;
						$mixed_RB->twoPtConversionScored_points = 0;
						$mixed_RB->twoPtConversionPass = 0;
						$mixed_RB->twoPtConversionPass_points = 0;
						$mixed_RB->statsUpdated = 0;

						$mixed_WR->total_points = 0;
						$mixed_WR->passingTD = 0;
						$mixed_WR->passingTD_points = 0;
						$mixed_WR->passingYds = 0;
						$mixed_WR->passingYds_points = 0;
						$mixed_WR->passingInt = 0;
						$mixed_WR->passingInt_points = 0;
						$mixed_WR->rushingTD = 0;
						$mixed_WR->rushingTD_points = 0;
						$mixed_WR->rushingYds = 0;
						$mixed_WR->rushingYds_points = 0;
						$mixed_WR->receivingTD = 0;
						$mixed_WR->receivingTD_points = 0;
						$mixed_WR->receivingYds = 0;
						$mixed_WR->receivingYds_points = 0;
						$mixed_WR->receptions = 0;
						$mixed_WR->receptions_points = 0;
						$mixed_WR->kickReturnTD = 0;
						$mixed_WR->kickReturnTD_points = 0;
						$mixed_WR->puntReturnTD = 0;
						$mixed_WR->puntReturnTD_points = 0;
						$mixed_WR->fumbleLost = 0;
						$mixed_WR->fumbleLost_points = 0;
						$mixed_WR->ownFumbleRecTD = 0;
						$mixed_WR->ownFumbleRecTD_points = 0;
						$mixed_WR->twoPtConversionScored = 0;
						$mixed_WR->twoPtConversionScored_points = 0;
						$mixed_WR->twoPtConversionPass = 0;
						$mixed_WR->twoPtConversionPass_points = 0;
						$mixed_WR->statsUpdated = 0;

						$mixed_TE->total_points = 0;
						$mixed_TE->passingTD = 0;
						$mixed_TE->passingTD_points = 0;
						$mixed_TE->passingYds = 0;
						$mixed_TE->passingYds_points = 0;
						$mixed_TE->passingInt = 0;
						$mixed_TE->passingInt_points = 0;
						$mixed_TE->rushingTD = 0;
						$mixed_TE->rushingTD_points = 0;
						$mixed_TE->rushingYds = 0;
						$mixed_TE->rushingYds_points = 0;
						$mixed_TE->receivingTD = 0;
						$mixed_TE->receivingTD_points = 0;
						$mixed_TE->receivingYds = 0;
						$mixed_TE->receivingYds_points = 0;
						$mixed_TE->receptions = 0;
						$mixed_TE->receptions_points = 0;
						$mixed_TE->kickReturnTD = 0;
						$mixed_TE->kickReturnTD_points = 0;
						$mixed_TE->puntReturnTD = 0;
						$mixed_TE->puntReturnTD_points = 0;
						$mixed_TE->fumbleLost = 0;
						$mixed_TE->fumbleLost_points = 0;
						$mixed_TE->ownFumbleRecTD = 0;
						$mixed_TE->ownFumbleRecTD_points = 0;
						$mixed_TE->twoPtConversionScored = 0;
						$mixed_TE->twoPtConversionScored_points = 0;
						$mixed_TE->twoPtConversionPass = 0;
						$mixed_TE->twoPtConversionPass_points = 0;
						$mixed_TE->statsUpdated = 0;

						$mixed_D->total_points = 0;
						$mixed_D->sacks = 0;
						$mixed_D->sacks_VAL = 0;
						$mixed_D->fumbleRecovery = 0;
						$mixed_D->fumbleRecovery_VAL = 0;
						$mixed_D->kickoffReturnTD = 0;
						$mixed_D->kickoffReturnTD_VAL = 0;
						$mixed_D->puntReturnTD = 0;
						$mixed_D->puntReturnTD_VAL = 0;
						$mixed_D->extraPtReturn = 0;
						$mixed_D->extraPtReturn_VAL = 0;
						$mixed_D->safety = 0;
						$mixed_D->safety_VAL = 0;
						$mixed_D->interceptions = 0;
						$mixed_D->interceptions_VAL = 0;
						$mixed_D->interceptionReturnTD = 0;
						$mixed_D->interceptionReturnTD_VAL = 0;
						$mixed_D->twoPtConversionReturn = 0;
						$mixed_D->twoPtConversionReturn_VAL = 0;
						$mixed_D->fumbleRecoveryTD = 0;
						$mixed_D->fumbleRecoveryTD_VAL = 0;
						$mixed_D->blockedKick = 0;
						$mixed_D->blockedKick_VAL = 0;
						$mixed_D->statsUpdated = 0;


						foreach ($stats as $stats_player) {

							if ($mixed_QB->player_id == $stats_player->PlayerID) {

								$mixed_QB->total_points = 0;
								$mixed_QB->passingTD = $stats_player->PassingTouchdowns;
								$mixed_QB->passingTD_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2);
								$mixed_QB->passingYds = $stats_player->PassingYards;
								$mixed_QB->passingYds_points = number_format($stats_player->PassingYards * $passingYds_VAL, 2);
								$mixed_QB->passingInt = $stats_player->PassingInterceptions;
								$mixed_QB->passingInt_points = number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2);
								$mixed_QB->rushingTD = $stats_player->RushingTouchdowns;
								$mixed_QB->rushingTD_points = number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2);
								$mixed_QB->rushingYds = $stats_player->RushingYards;
								$mixed_QB->rushingYds_points = number_format($stats_player->RushingYards * $rushingYds_VAL, 2);
								$mixed_QB->receivingTD = $stats_player->ReceivingTouchdowns;
								$mixed_QB->receivingTD_points = number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2);
								$mixed_QB->receivingYds = $stats_player->ReceivingYards;
								$mixed_QB->receivingYds_points = number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2);
								$mixed_QB->receptions = $stats_player->Receptions;
								$mixed_QB->receptions_points = number_format($stats_player->Receptions * $receptions_VAL, 2);
								$mixed_QB->kickReturnTD = $stats_player->KickReturnTouchdowns;
								$mixed_QB->kickReturnTD_points = number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2);
								$mixed_QB->puntReturnTD = $stats_player->PuntReturnTouchdowns;
								$mixed_QB->puntReturnTD_points = number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2);
								$mixed_QB->fumbleLost = $stats_player->FumblesLost;
								$mixed_QB->fumbleLost_points = number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2);
								$mixed_QB->ownFumbleRecTD = $stats_player->FumbleReturnTouchdowns;
								$mixed_QB->ownFumbleRecTD_points = number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2);
								$mixed_QB->twoPtConversionScored = $stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions;
								$mixed_QB->twoPtConversionScored_points = number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2);
								$mixed_QB->twoPtConversionPass = $stats_player->TwoPointConversionPasses;
								$mixed_QB->twoPtConversionPass_points = number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);
								$mixed_QB->statsUpdated = 1;

								$mixed_QB->total_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2) + number_format($stats_player->PassingYards * $passingYds_VAL, 2) + number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2) + number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2) + number_format($stats_player->RushingYards * $rushingYds_VAL, 2) + number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2) + number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2) + number_format($stats_player->Receptions * $receptions_VAL, 2) + number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2) + number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2) + number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2) + number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2) + number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2) + number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);
							}
						}

						foreach ($stats as $stats_player) {

							if ($mixed_RB->player_id == $stats_player->PlayerID) {

								$mixed_RB->total_points = 0;
								$mixed_RB->passingTD = $stats_player->PassingTouchdowns;
								$mixed_RB->passingTD_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2);
								$mixed_RB->passingYds = $stats_player->PassingYards;
								$mixed_RB->passingYds_points = number_format($stats_player->PassingYards * $passingYds_VAL, 2);
								$mixed_RB->passingInt = $stats_player->PassingInterceptions;
								$mixed_RB->passingInt_points = number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2);
								$mixed_RB->rushingTD = $stats_player->RushingTouchdowns;
								$mixed_RB->rushingTD_points = number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2);
								$mixed_RB->rushingYds = $stats_player->RushingYards;
								$mixed_RB->rushingYds_points = number_format($stats_player->RushingYards * $rushingYds_VAL, 2);
								$mixed_RB->receivingTD = $stats_player->ReceivingTouchdowns;
								$mixed_RB->receivingTD_points = number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2);
								$mixed_RB->receivingYds = $stats_player->ReceivingYards;
								$mixed_RB->receivingYds_points = number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2);
								$mixed_RB->receptions = $stats_player->Receptions;
								$mixed_RB->receptions_points = number_format($stats_player->Receptions * $receptions_VAL, 2);
								$mixed_RB->kickReturnTD = $stats_player->KickReturnTouchdowns;
								$mixed_RB->kickReturnTD_points = number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2);
								$mixed_RB->puntReturnTD = $stats_player->PuntReturnTouchdowns;
								$mixed_RB->puntReturnTD_points = number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2);
								$mixed_RB->fumbleLost = $stats_player->FumblesLost;
								$mixed_RB->fumbleLost_points = number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2);
								$mixed_RB->ownFumbleRecTD = $stats_player->FumbleReturnTouchdowns;
								$mixed_RB->ownFumbleRecTD_points = number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2);
								$mixed_RB->twoPtConversionScored = $stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions;
								$mixed_RB->twoPtConversionScored_points = number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2);
								$mixed_RB->twoPtConversionPass = $stats_player->TwoPointConversionPasses;
								$mixed_RB->twoPtConversionPass_points = number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);
								$mixed_RB->statsUpdated = 1;

								$mixed_RB->total_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2) + number_format($stats_player->PassingYards * $passingYds_VAL, 2) + number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2) + number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2) + number_format($stats_player->RushingYards * $rushingYds_VAL, 2) + number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2) + number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2) + number_format($stats_player->Receptions * $receptions_VAL, 2) + number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2) + number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2) + number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2) + number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2) + number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2) + number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);
							}
						}

						foreach ($stats as $stats_player) {

							if ($mixed_WR->player_id == $stats_player->PlayerID) {

								$mixed_WR->total_points = 0;
								$mixed_WR->passingTD = $stats_player->PassingTouchdowns;
								$mixed_WR->passingTD_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2);
								$mixed_WR->passingYds = $stats_player->PassingYards;
								$mixed_WR->passingYds_points = number_format($stats_player->PassingYards * $passingYds_VAL, 2);
								$mixed_WR->passingInt = $stats_player->PassingInterceptions;
								$mixed_WR->passingInt_points = number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2);
								$mixed_WR->rushingTD = $stats_player->RushingTouchdowns;
								$mixed_WR->rushingTD_points = number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2);
								$mixed_WR->rushingYds = $stats_player->RushingYards;
								$mixed_WR->rushingYds_points = number_format($stats_player->RushingYards * $rushingYds_VAL, 2);
								$mixed_WR->receivingTD = $stats_player->ReceivingTouchdowns;
								$mixed_WR->receivingTD_points = number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2);
								$mixed_WR->receivingYds = $stats_player->ReceivingYards;
								$mixed_WR->receivingYds_points = number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2);
								$mixed_WR->receptions = $stats_player->Receptions;
								$mixed_WR->receptions_points = number_format($stats_player->Receptions * $receptions_VAL, 2);
								$mixed_WR->kickReturnTD = $stats_player->KickReturnTouchdowns;
								$mixed_WR->kickReturnTD_points = number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2);
								$mixed_WR->puntReturnTD = $stats_player->PuntReturnTouchdowns;
								$mixed_WR->puntReturnTD_points = number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2);
								$mixed_WR->fumbleLost = $stats_player->FumblesLost;
								$mixed_WR->fumbleLost_points = number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2);
								$mixed_WR->ownFumbleRecTD = $stats_player->FumbleReturnTouchdowns;
								$mixed_WR->ownFumbleRecTD_points = number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2);
								$mixed_WR->twoPtConversionScored = $stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions;
								$mixed_WR->twoPtConversionScored_points = number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2);
								$mixed_WR->twoPtConversionPass = $stats_player->TwoPointConversionPasses;
								$mixed_WR->twoPtConversionPass_points = number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);
								$mixed_WR->statsUpdated = 1;

								$mixed_WR->total_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2) + number_format($stats_player->PassingYards * $passingYds_VAL, 2) + number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2) + number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2) + number_format($stats_player->RushingYards * $rushingYds_VAL, 2) + number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2) + number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2) + number_format($stats_player->Receptions * $receptions_VAL, 2) + number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2) + number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2) + number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2) + number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2) + number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2) + number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);
							}
						}

						foreach ($stats as $stats_player) {

							if ($mixed_TE->player_id == $stats_player->PlayerID) {

								$mixed_TE->total_points = 0;
								$mixed_TE->passingTD = $stats_player->PassingTouchdowns;
								$mixed_TE->passingTD_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2);
								$mixed_TE->passingYds = $stats_player->PassingYards;
								$mixed_TE->passingYds_points = number_format($stats_player->PassingYards * $passingYds_VAL, 2);
								$mixed_TE->passingInt = $stats_player->PassingInterceptions;
								$mixed_TE->passingInt_points = number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2);
								$mixed_TE->rushingTD = $stats_player->RushingTouchdowns;
								$mixed_TE->rushingTD_points = number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2);
								$mixed_TE->rushingYds = $stats_player->RushingYards;
								$mixed_TE->rushingYds_points = number_format($stats_player->RushingYards * $rushingYds_VAL, 2);
								$mixed_TE->receivingTD = $stats_player->ReceivingTouchdowns;
								$mixed_TE->receivingTD_points = number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2);
								$mixed_TE->receivingYds = $stats_player->ReceivingYards;
								$mixed_TE->receivingYds_points = number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2);
								$mixed_TE->receptions = $stats_player->Receptions;
								$mixed_TE->receptions_points = number_format($stats_player->Receptions * $receptions_VAL, 2);
								$mixed_TE->kickReturnTD = $stats_player->KickReturnTouchdowns;
								$mixed_TE->kickReturnTD_points = number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2);
								$mixed_TE->puntReturnTD = $stats_player->PuntReturnTouchdowns;
								$mixed_TE->puntReturnTD_points = number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2);
								$mixed_TE->fumbleLost = $stats_player->FumblesLost;
								$mixed_TE->fumbleLost_points = number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2);
								$mixed_TE->ownFumbleRecTD = $stats_player->FumbleReturnTouchdowns;
								$mixed_TE->ownFumbleRecTD_points = number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2);
								$mixed_TE->twoPtConversionScored = $stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions;
								$mixed_TE->twoPtConversionScored_points = number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2);
								$mixed_TE->twoPtConversionPass = $stats_player->TwoPointConversionPasses;
								$mixed_TE->twoPtConversionPass_points = number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);
								$mixed_TE->statsUpdated = 1;

								$mixed_TE->total_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2) + number_format($stats_player->PassingYards * $passingYds_VAL, 2) + number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2) + number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2) + number_format($stats_player->RushingYards * $rushingYds_VAL, 2) + number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2) + number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2) + number_format($stats_player->Receptions * $receptions_VAL, 2) + number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2) + number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2) + number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2) + number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2) + number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2) + number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);
							}
						}

						foreach ($stats as $stats_player) {

							if ($mixed_D->name == $stats_player->Team && $stats_player->PositionCategory == 'DEF') {

								$mixed_D->sacks = $mixed_D->sacks + $stats_player->Sacks;
								$mixed_D->sacks_VAL = number_format($mixed_D->sacks * $defensiveSack_VAL, 2);
								$mixed_D->fumbleRecovery += $stats_player->FumblesRecovered;
								$mixed_D->fumbleRecovery_VAL = number_format($mixed_D->fumbleRecovery * $defensiveFumbleRecovery_VAL, 2);
								$mixed_D->kickoffReturnTD += $stats_player->KickReturnTouchdowns;
								$mixed_D->kickoffReturnTD_VAL = number_format($mixed_D->kickoffReturnTD * $defkickoffReturnTD_VAL, 2);
								$mixed_D->puntReturnTD += $stats_player->PuntReturnTouchdowns;
								$mixed_D->puntReturnTD_VAL = number_format($mixed_D->puntReturnTD * $defPuntReturnTD_VAL, 2);
								$mixed_D->extraPtReturn += ($stats_player->FieldGoalReturnTouchdowns + $stats_player->BlockedKickReturnTouchdowns);
								$mixed_D->extraPtReturn_VAL = number_format($mixed_D->extraPtReturn * $defExtraPtReturn_VAL, 2);
								$mixed_D->safety += $stats_player->Safeties;
								$mixed_D->safety_VAL = number_format($mixed_D->safety * $defSafety_VAL, 2);
								$mixed_D->interceptions += $stats_player->Interceptions;
								$mixed_D->interceptions_VAL = number_format($mixed_D->interceptions * $defInterception_VAL, 2);
								$mixed_D->interceptionReturnTD += $stats_player->InterceptionReturnTouchdowns;
								$mixed_D->interceptionReturnTD_VAL = number_format($mixed_D->interceptionReturnTD * $defInterceptionReturnTD_VAL, 2);
								$mixed_D->twoPtConversionReturn += $stats_player->TwoPointConversionReturns;
								$mixed_D->twoPtConversionReturn_VAL = number_format($mixed_D->twoPtConversionReturn * $def2PtConversionReturn_VAL, 2);
								$mixed_D->fumbleRecoveryTD += $stats_player->FumbleReturnTouchdowns;
								$mixed_D->fumbleRecoveryTD_VAL = number_format($mixed_D->fumbleRecoveryTD * $defFumbleRecoveryTD_VAL, 2);
								$mixed_D->blockedKick += $stats_player->BlockedKicks;
								$mixed_D->blockedKick_VAL = number_format($mixed_D->blockedKick * $defBlockedKick_VAL, 2);
								$mixed_D->statsUpdated = 1;

								$mixed_D->total_points = $mixed_D->sacks_VAL + $mixed_D->fumbleRecovery_VAL + $mixed_D->kickoffReturnTD_VAL + $mixed_D->puntReturnTD_VAL + $mixed_D->extraPtReturn_VAL + $mixed_D->safety_VAL + $mixed_D->interceptions_VAL + $mixed_D->interceptionReturnTD_VAL + $mixed_D->twoPtConversionReturn_VAL + $mixed_D->fumbleRecoveryTD_VAL + $mixed_D->blockedKick_VAL;
							}
						}

						$result->total_points = $mixed_QB->total_points + $mixed_RB->total_points + $mixed_WR->total_points + $mixed_TE->total_points + $mixed_D->total_points;
					}

					$sort = array();
					foreach ($contest_results as $key => $part) {
						$sort[$key] = $part->total_points;
					}
					array_multisort($sort, SORT_DESC, $contest_results);

					update_field('contest_results', json_encode($contest_results, JSON_UNESCAPED_UNICODE), $contest['contest_id']);
					update_field('contest_live_stats_url', "https://fly.sportsdata.io/v3/nfl/stats/json/PlayerGameStatsByWeek/$schedule_url/$nfl_week?key=$stats_key", $contest['contest_id']);
					$contests_updated++;
				} else if ($contest['contest_type'] == 'Teams') {

					foreach ($contest_results as $team) {

						$players_QB = $team->players->QB;
						$players_RB = $team->players->RB;
						$players_WR = $team->players->WR;
						$players_TE = $team->players->TE;
						$players_D = $team->players->D;

						$team->total_points = 0;
						$team->total_points_QB = 0;
						$team->total_points_RB = 0;
						$team->total_points_WR = 0;
						$team->total_points_TE = 0;
						$team->total_points_D = 0;

						$QB_points = 0;
						$RB_points = 0;
						$WR_points = 0;
						$TE_points = 0;
						$D_points = 0;

						foreach ($players_QB as $player) {

							$player->total_points = 0;
							$player->passingTD = 0;
							$player->passingTD_points = 0;
							$player->passingYds = 0;
							$player->passingYds_points = 0;
							$player->passingInt = 0;
							$player->passingInt_points = 0;
							$player->rushingTD = 0;
							$player->rushingTD_points = 0;
							$player->rushingYds = 0;
							$player->rushingYds_points = 0;
							$player->receivingTD = 0;
							$player->receivingTD_points = 0;
							$player->receivingYds = 0;
							$player->receivingYds_points = 0;
							$player->receptions = 0;
							$player->receptions_points = 0;
							$player->kickReturnTD = 0;
							$player->kickReturnTD_points = 0;
							$player->puntReturnTD = 0;
							$player->puntReturnTD_points = 0;
							$player->fumbleLost = 0;
							$player->fumbleLost_points = 0;
							$player->ownFumbleRecTD = 0;
							$player->ownFumbleRecTD_points = 0;
							$player->twoPtConversionScored = 0;
							$player->twoPtConversionScored_points = 0;
							$player->twoPtConversionPass = 0;
							$player->twoPtConversionPass_points = 0;
							$player->statsUpdated = 0;

							foreach ($stats as $stats_player) {

								if ($player->player_id == $stats_player->PlayerID) {

									$player->passingTD = $stats_player->PassingTouchdowns;
									$player->passingTD_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2);
									$player->passingYds = $stats_player->PassingYards;
									$player->passingYds_points = number_format($stats_player->PassingYards * $passingYds_VAL, 2);
									$player->passingInt = $stats_player->PassingInterceptions;
									$player->passingInt_points = number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2);
									$player->rushingTD = $stats_player->RushingTouchdowns;
									$player->rushingTD_points = number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2);
									$player->rushingYds = $stats_player->RushingYards;
									$player->rushingYds_points = number_format($stats_player->RushingYards * $rushingYds_VAL, 2);
									$player->receivingTD = $stats_player->ReceivingTouchdowns;
									$player->receivingTD_points = number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2);
									$player->receivingYds = $stats_player->ReceivingYards;
									$player->receivingYds_points = number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2);
									$player->receptions = $stats_player->Receptions;
									$player->receptions_points = number_format($stats_player->Receptions * $receptions_VAL, 2);
									$player->kickReturnTD = $stats_player->KickReturnTouchdowns;
									$player->kickReturnTD_points = number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2);
									$player->puntReturnTD = $stats_player->PuntReturnTouchdowns;
									$player->puntReturnTD_points = number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2);
									$player->fumbleLost = $stats_player->FumblesLost;
									$player->fumbleLost_points = number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2);
									$player->ownFumbleRecTD = $stats_player->FumbleReturnTouchdowns;
									$player->ownFumbleRecTD_points = number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2);
									$player->twoPtConversionScored = $stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions;
									$player->twoPtConversionScored_points = number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2);
									$player->twoPtConversionPass = $stats_player->TwoPointConversionPasses;
									$player->twoPtConversionPass_points = number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);
									$player->statsUpdated = 1;

									$player->total_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2) + number_format($stats_player->PassingYards * $passingYds_VAL, 2) + number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2) + number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2) + number_format($stats_player->RushingYards * $rushingYds_VAL, 2) + number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2) + number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2) + number_format($stats_player->Receptions * $receptions_VAL, 2) + number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2) + number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2) + number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2) + number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2) + number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2) + number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);

									$QB_points += $player->total_points;
								}
							}
						}

						foreach ($players_RB as $player) {

							$player->total_points = 0;
							$player->passingTD = 0;
							$player->passingTD_points = 0;
							$player->passingYds = 0;
							$player->passingYds_points = 0;
							$player->passingInt = 0;
							$player->passingInt_points = 0;
							$player->rushingTD = 0;
							$player->rushingTD_points = 0;
							$player->rushingYds = 0;
							$player->rushingYds_points = 0;
							$player->receivingTD = 0;
							$player->receivingTD_points = 0;
							$player->receivingYds = 0;
							$player->receivingYds_points = 0;
							$player->receptions = 0;
							$player->receptions_points = 0;
							$player->kickReturnTD = 0;
							$player->kickReturnTD_points = 0;
							$player->puntReturnTD = 0;
							$player->puntReturnTD_points = 0;
							$player->fumbleLost = 0;
							$player->fumbleLost_points = 0;
							$player->ownFumbleRecTD = 0;
							$player->ownFumbleRecTD_points = 0;
							$player->twoPtConversionScored = 0;
							$player->twoPtConversionScored_points = 0;
							$player->twoPtConversionPass = 0;
							$player->twoPtConversionPass_points = 0;
							$player->statsUpdated = 0;

							foreach ($stats as $stats_player) {

								if ($player->player_id == $stats_player->PlayerID) {

									$player->passingTD = $stats_player->PassingTouchdowns;
									$player->passingTD_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2);
									$player->passingYds = $stats_player->PassingYards;
									$player->passingYds_points = number_format($stats_player->PassingYards * $passingYds_VAL, 2);
									$player->passingInt = $stats_player->PassingInterceptions;
									$player->passingInt_points = number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2);
									$player->rushingTD = $stats_player->RushingTouchdowns;
									$player->rushingTD_points = number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2);
									$player->rushingYds = $stats_player->RushingYards;
									$player->rushingYds_points = number_format($stats_player->RushingYards * $rushingYds_VAL, 2);
									$player->receivingTD = $stats_player->ReceivingTouchdowns;
									$player->receivingTD_points = number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2);
									$player->receivingYds = $stats_player->ReceivingYards;
									$player->receivingYds_points = number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2);
									$player->receptions = $stats_player->Receptions;
									$player->receptions_points = number_format($stats_player->Receptions * $receptions_VAL, 2);
									$player->kickReturnTD = $stats_player->KickReturnTouchdowns;
									$player->kickReturnTD_points = number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2);
									$player->puntReturnTD = $stats_player->PuntReturnTouchdowns;
									$player->puntReturnTD_points = number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2);
									$player->fumbleLost = $stats_player->FumblesLost;
									$player->fumbleLost_points = number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2);
									$player->ownFumbleRecTD = $stats_player->FumbleReturnTouchdowns;
									$player->ownFumbleRecTD_points = number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2);
									$player->twoPtConversionScored = $stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions;
									$player->twoPtConversionScored_points = number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2);
									$player->twoPtConversionPass = $stats_player->TwoPointConversionPasses;
									$player->twoPtConversionPass_points = number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);
									$player->statsUpdated = 1;

									$player->total_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2) + number_format($stats_player->PassingYards * $passingYds_VAL, 2) + number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2) + number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2) + number_format($stats_player->RushingYards * $rushingYds_VAL, 2) + number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2) + number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2) + number_format($stats_player->Receptions * $receptions_VAL, 2) + number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2) + number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2) + number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2) + number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2) + number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2) + number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);

									$RB_points += $player->total_points;
								}
							}
						}

						foreach ($players_WR as $player) {

							$player->total_points = 0;
							$player->passingTD = 0;
							$player->passingTD_points = 0;
							$player->passingYds = 0;
							$player->passingYds_points = 0;
							$player->passingInt = 0;
							$player->passingInt_points = 0;
							$player->rushingTD = 0;
							$player->rushingTD_points = 0;
							$player->rushingYds = 0;
							$player->rushingYds_points = 0;
							$player->receivingTD = 0;
							$player->receivingTD_points = 0;
							$player->receivingYds = 0;
							$player->receivingYds_points = 0;
							$player->receptions = 0;
							$player->receptions_points = 0;
							$player->kickReturnTD = 0;
							$player->kickReturnTD_points = 0;
							$player->puntReturnTD = 0;
							$player->puntReturnTD_points = 0;
							$player->fumbleLost = 0;
							$player->fumbleLost_points = 0;
							$player->ownFumbleRecTD = 0;
							$player->ownFumbleRecTD_points = 0;
							$player->twoPtConversionScored = 0;
							$player->twoPtConversionScored_points = 0;
							$player->twoPtConversionPass = 0;
							$player->twoPtConversionPass_points = 0;
							$player->statsUpdated = 0;

							foreach ($stats as $stats_player) {

								if ($player->player_id == $stats_player->PlayerID) {

									$player->passingTD = $stats_player->PassingTouchdowns;
									$player->passingTD_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2);
									$player->passingYds = $stats_player->PassingYards;
									$player->passingYds_points = number_format($stats_player->PassingYards * $passingYds_VAL, 2);
									$player->passingInt = $stats_player->PassingInterceptions;
									$player->passingInt_points = number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2);
									$player->rushingTD = $stats_player->RushingTouchdowns;
									$player->rushingTD_points = number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2);
									$player->rushingYds = $stats_player->RushingYards;
									$player->rushingYds_points = number_format($stats_player->RushingYards * $rushingYds_VAL, 2);
									$player->receivingTD = $stats_player->ReceivingTouchdowns;
									$player->receivingTD_points = number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2);
									$player->receivingYds = $stats_player->ReceivingYards;
									$player->receivingYds_points = number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2);
									$player->receptions = $stats_player->Receptions;
									$player->receptions_points = number_format($stats_player->Receptions * $receptions_VAL, 2);
									$player->kickReturnTD = $stats_player->KickReturnTouchdowns;
									$player->kickReturnTD_points = number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2);
									$player->puntReturnTD = $stats_player->PuntReturnTouchdowns;
									$player->puntReturnTD_points = number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2);
									$player->fumbleLost = $stats_player->FumblesLost;
									$player->fumbleLost_points = number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2);
									$player->ownFumbleRecTD = $stats_player->FumbleReturnTouchdowns;
									$player->ownFumbleRecTD_points = number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2);
									$player->twoPtConversionScored = $stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions;
									$player->twoPtConversionScored_points = number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2);
									$player->twoPtConversionPass = $stats_player->TwoPointConversionPasses;
									$player->twoPtConversionPass_points = number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);
									$player->statsUpdated = 1;

									$player->total_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2) + number_format($stats_player->PassingYards * $passingYds_VAL, 2) + number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2) + number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2) + number_format($stats_player->RushingYards * $rushingYds_VAL, 2) + number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2) + number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2) + number_format($stats_player->Receptions * $receptions_VAL, 2) + number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2) + number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2) + number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2) + number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2) + number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2) + number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);

									$WR_points += $player->total_points;
								}
							}
						}

						foreach ($players_TE as $player) {

							$player->total_points = 0;
							$player->passingTD = 0;
							$player->passingTD_points = 0;
							$player->passingYds = 0;
							$player->passingYds_points = 0;
							$player->passingInt = 0;
							$player->passingInt_points = 0;
							$player->rushingTD = 0;
							$player->rushingTD_points = 0;
							$player->rushingYds = 0;
							$player->rushingYds_points = 0;
							$player->receivingTD = 0;
							$player->receivingTD_points = 0;
							$player->receivingYds = 0;
							$player->receivingYds_points = 0;
							$player->receptions = 0;
							$player->receptions_points = 0;
							$player->kickReturnTD = 0;
							$player->kickReturnTD_points = 0;
							$player->puntReturnTD = 0;
							$player->puntReturnTD_points = 0;
							$player->fumbleLost = 0;
							$player->fumbleLost_points = 0;
							$player->ownFumbleRecTD = 0;
							$player->ownFumbleRecTD_points = 0;
							$player->twoPtConversionScored = 0;
							$player->twoPtConversionScored_points = 0;
							$player->twoPtConversionPass = 0;
							$player->twoPtConversionPass_points = 0;
							$player->statsUpdated = 0;

							foreach ($stats as $stats_player) {

								if ($player->player_id == $stats_player->PlayerID) {

									$player->passingTD = $stats_player->PassingTouchdowns;
									$player->passingTD_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2);
									$player->passingYds = $stats_player->PassingYards;
									$player->passingYds_points = number_format($stats_player->PassingYards * $passingYds_VAL, 2);
									$player->passingInt = $stats_player->PassingInterceptions;
									$player->passingInt_points = number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2);
									$player->rushingTD = $stats_player->RushingTouchdowns;
									$player->rushingTD_points = number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2);
									$player->rushingYds = $stats_player->RushingYards;
									$player->rushingYds_points = number_format($stats_player->RushingYards * $rushingYds_VAL, 2);
									$player->receivingTD = $stats_player->ReceivingTouchdowns;
									$player->receivingTD_points = number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2);
									$player->receivingYds = $stats_player->ReceivingYards;
									$player->receivingYds_points = number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2);
									$player->receptions = $stats_player->Receptions;
									$player->receptions_points = number_format($stats_player->Receptions * $receptions_VAL, 2);
									$player->kickReturnTD = $stats_player->KickReturnTouchdowns;
									$player->kickReturnTD_points = number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2);
									$player->puntReturnTD = $stats_player->PuntReturnTouchdowns;
									$player->puntReturnTD_points = number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2);
									$player->fumbleLost = $stats_player->FumblesLost;
									$player->fumbleLost_points = number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2);
									$player->ownFumbleRecTD = $stats_player->FumbleReturnTouchdowns;
									$player->ownFumbleRecTD_points = number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2);
									$player->twoPtConversionScored = $stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions;
									$player->twoPtConversionScored_points = number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2);
									$player->twoPtConversionPass = $stats_player->TwoPointConversionPasses;
									$player->twoPtConversionPass_points = number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);
									$player->statsUpdated = 1;

									$player->total_points = number_format($stats_player->PassingTouchdowns * $passingTD_VAL, 2) + number_format($stats_player->PassingYards * $passingYds_VAL, 2) + number_format($stats_player->PassingInterceptions * $passingInt_VAL, 2) + number_format($stats_player->RushingTouchdowns * $rushingTD_VAL, 2) + number_format($stats_player->RushingYards * $rushingYds_VAL, 2) + number_format($stats_player->ReceivingTouchdowns * $receivingTD_VAL, 2) + number_format($stats_player->ReceivingYards * $receivingYds_VAL, 2) + number_format($stats_player->Receptions * $receptions_VAL, 2) + number_format($stats_player->KickReturnTouchdowns * $kickReturnTD_VAL, 2) + number_format($stats_player->PuntReturnTouchdowns * $puntReturnTD_VAL, 2) + number_format($stats_player->FumblesLost * $fumbleLost_VAL, 2) + number_format($stats_player->FumbleReturnTouchdowns * $ownFumbleRecTD_VAL, 2) + number_format(($stats_player->TwoPointConversionRuns + $stats_player->TwoPointConversionReceptions) * $twoPtConversionScored_VAL, 2) + number_format($stats_player->TwoPointConversionPasses * $twoPtConversionPass_VAL, 2);

									$TE_points += $player->total_points;
								}
							}
						}

						foreach ($players_D as $player) {

							$player->total_points = 0;
							$player->sacks = 0;
							$player->sacks_VAL = 0;
							$player->fumbleRecovery = 0;
							$player->fumbleRecovery_VAL = 0;
							$player->kickoffReturnTD = 0;
							$player->kickoffReturnTD_VAL = 0;
							$player->puntReturnTD = 0;
							$player->puntReturnTD_VAL = 0;
							$player->extraPtReturn = 0;
							$player->extraPtReturn_VAL = 0;
							$player->safety = 0;
							$player->safety_VAL = 0;
							$player->interceptions = 0;
							$player->interceptions_VAL = 0;
							$player->interceptionReturnTD = 0;
							$player->interceptionReturnTD_VAL = 0;
							$player->twoPtConversionReturn = 0;
							$player->twoPtConversionReturn_VAL = 0;
							$player->fumbleRecoveryTD = 0;
							$player->fumbleRecoveryTD_VAL = 0;
							$player->blockedKick = 0;
							$player->blockedKick_VAL = 0;
							$player->statsUpdated = 0;

							foreach ($stats as $stats_player) {

								if ($player->name == $stats_player->Team && $stats_player->PositionCategory == 'DEF') {

									$player->sacks += $stats_player->Sacks;
									$player->sacks_VAL = number_format($player->sacks * $defensiveSack_VAL, 2);
									$player->fumbleRecovery += $stats_player->FumblesRecovered;
									$player->fumbleRecovery_VAL = number_format($player->fumbleRecovery * $defensiveFumbleRecovery_VAL, 2);
									$player->kickoffReturnTD += $stats_player->KickReturnTouchdowns;
									$player->kickoffReturnTD_VAL = number_format($player->kickoffReturnTD * $defkickoffReturnTD_VAL, 2);
									$player->puntReturnTD += $stats_player->PuntReturnTouchdowns;
									$player->puntReturnTD_VAL = number_format($player->puntReturnTD * $defPuntReturnTD_VAL, 2);
									$player->extraPtReturn += $stats_player->FieldGoalReturnTouchdowns + $stats_player->BlockedKickReturnTouchdowns;
									$player->extraPtReturn_VAL = number_format($player->extraPtReturn * $defExtraPtReturn_VAL, 2);
									$player->safety += $stats_player->Safeties;
									$player->safety_VAL = number_format($player->safety * $defSafety_VAL, 2);
									$player->interceptions += $stats_player->Interceptions;
									$player->interceptions_VAL = number_format($player->interceptions * $defInterception_VAL, 2);
									$player->interceptionReturnTD += $stats_player->InterceptionReturnTouchdowns;
									$player->interceptionReturnTD_VAL = number_format($player->interceptionReturnTD * $defInterceptionReturnTD_VAL, 2);
									$player->twoPtConversionReturn += $stats_player->TwoPointConversionReturns;
									$player->twoPtConversionReturn_VAL = number_format($player->twoPtConversionReturn * $def2PtConversionReturn_VAL, 2);
									$player->fumbleRecoveryTD += $stats_player->FumbleReturnTouchdowns;
									$player->fumbleRecoveryTD_VAL = number_format($player->fumbleRecoveryTD * $defFumbleRecoveryTD_VAL, 2);
									$player->blockedKick += $stats_player->BlockedKicks;
									$player->blockedKick_VAL = number_format($player->blockedKick * $defBlockedKick_VAL, 2);
									$player->statsUpdated = 1;

									$player->total_points = number_format($player->sacks * $defensiveSack_VAL, 2) + number_format($player->fumbleRecovery * $defensiveFumbleRecovery_VAL, 2) + number_format($player->kickoffReturnTD * $defkickoffReturnTD_VAL, 2) + number_format($player->puntReturnTD * $defPuntReturnTD_VAL, 2) + number_format($player->extraPtReturn * $defExtraPtReturn_VAL, 2) + number_format($player->safety * $defSafety_VAL, 2) + number_format($player->interceptions * $defInterception_VAL, 2) + number_format($player->interceptionReturnTD * $defInterceptionReturnTD_VAL, 2) + number_format($player->twoPtConversionReturn * $def2PtConversionReturn_VAL, 2) + number_format($player->fumbleRecoveryTD * $defFumbleRecoveryTD_VAL, 2) + number_format($player->blockedKick * $defBlockedKick_VAL, 2);

									$D_points = $player->total_points;
								}
							}
						}

						$team->total_points_QB = $QB_points;
						$team->total_points_RB = $RB_points;
						$team->total_points_WR = $WR_points;
						$team->total_points_TE = $TE_points;
						$team->total_points_D = $D_points;

						$team->total_points = $QB_points + $RB_points + $WR_points + $TE_points + $D_points;
					}

					$sort = array();
					foreach ($contest_results as $key => $part) {
						$sort[$key] = $part->total_points;
					}
					array_multisort($sort, SORT_DESC, $contest_results);

					update_field('contest_results', json_encode($contest_results, JSON_UNESCAPED_UNICODE), $contest['contest_id']);
					update_field('contest_live_stats_url', "https://fly.sportsdata.io/v3/nfl/stats/json/PlayerGameStatsByWeek/$schedule_url/$nfl_week?key=$stats_key", $contest['contest_id']);
					$contests_updated++;
				}
			}
		} else {

			echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 2)</p></div>';
		}
	} else {

		echo '<div id="message" class="updated fade error"><p>There are no contests in progress. (Error Code 1)</p></div>';

		//echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 1)</p></div>';

	}



	if ($completed == false) {
		echo '<div id="message" class="updated fade"><p>' . $contests_updated . ' Live NFL contests updated.</p></div>';
	}

	$cron_log = array(
		'post_status' => 'draft',
		'post_title' => 'NFL Cron Log - Update Live',
		'post_type' => 'cron_log',
		'post_content' => date('m-d-Y g:i a', time() - (60 * 60 * 7)) . ' PT',
		'tax_input' => array(
			'cron_type' => 4308,
		),
	);

	wp_set_current_user(1);
	wp_insert_post($cron_log);
}


// Process completed contests and wagers

function process_finished_nfl_contests($stats_key)
{


	// Update stats

	update_live_nfl_contests($stats_key, false);



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
				'terms'    => 'nfl',
			),
		),
	);

	$the_query = new WP_Query($args);


	if ($the_query->have_posts()) {

		$contest_data = array();
		$contest_dates = array();

		while ($the_query->have_posts()) {

			$the_query->the_post();
			global $post;



			$this_contest = array();
			$this_contest_date = get_field('contest_date');
			$contest_type = get_field('contest_type');

			$this_contest['date'] = strtoupper(date('Y-M-d', $this_contest_date));
			$this_contest['date_time'] = $this_contest_date;
			$this_contest['contest_id'] = $post->ID;
			$this_contest['contest_type'] = $contest_type;
			$this_contest['contest_title'] = $post->post_title;

			$contest_schedule = false;
			$contest_schedule_week = false;

			if (!in_array($this_contest['date'], $contest_dates)) {
				$contest_dates[] = $this_contest['date'];
			}

			$main_contest = get_field('nfl_main_contest', $post->ID);
			$terms = get_the_terms($post->ID, 'schedule');

			if ($contest_type == 'Team vs Team') {

				if ($main_contest != '') {

					if ($terms) {

						foreach ($terms as $schedule) {

							$schedule_id = $schedule->term_id;

							if ($schedule->parent != '') {

								$schedule_type = get_field('schedule_type', 'schedule_' . $schedule_id);

								if ($schedule_type == 'Preseason') {
									$schedule_url = '2019PRE';
								} else if ($schedule_type == 'Regular Season') {
									$schedule_url = '2019REG';
								} else if ($schedule_type == 'Playoffs') {
									$schedule_url = '2019POST';
								}

								$nfl_week = get_field('nfl_week', 'schedule_' . $schedule_id);

								$this_contest['contest_schedule'] = $schedule_url;
								$this_contest['contest_schedule_week'] = $nfl_week;

								$contest_schedule = $schedule_url;
								$contest_schedule_week = $nfl_week;
							}
						}
					}

					$contest_data[] = $this_contest;
				} else {

					$main_contest_id = $post->ID;
				}
			} else if ($contest_type == 'Mixed') {

				if ($terms) {

					foreach ($terms as $schedule) {

						$schedule_id = $schedule->term_id;

						if ($schedule->parent != '') {

							$schedule_type = get_field('schedule_type', 'schedule_' . $schedule_id);

							if ($schedule_type == 'Preseason') {
								$schedule_url = '2019PRE';
							} else if ($schedule_type == 'Regular Season') {
								$schedule_url = '2019REG';
							} else if ($schedule_type == 'Playoffs') {
								$schedule_url = '2019POST';
							}

							$nfl_week = get_field('nfl_week', 'schedule_' . $schedule_id);

							$this_contest['contest_schedule'] = $schedule_url;
							$this_contest['contest_schedule_week'] = $nfl_week;

							$contest_schedule = $schedule_url;
							$contest_schedule_week = $nfl_week;
						}
					}
				}

				$contest_data[] = $this_contest;
			} else if ($contest_type == 'Teams') {

				if ($terms) {

					foreach ($terms as $schedule) {

						$schedule_id = $schedule->term_id;

						if ($schedule->parent != '') {

							$schedule_type = get_field('schedule_type', 'schedule_' . $schedule_id);

							if ($schedule_type == 'Preseason') {
								$schedule_url = '2019PRE';
							} else if ($schedule_type == 'Regular Season') {
								$schedule_url = '2019REG';
							} else if ($schedule_type == 'Playoffs') {
								$schedule_url = '2019POST';
							}

							$nfl_week = get_field('nfl_week', 'schedule_' . $schedule_id);

							$this_contest['contest_schedule'] = $schedule_url;
							$this_contest['contest_schedule_week'] = $nfl_week;

							$contest_schedule = $schedule_url;
							$contest_schedule_week = $nfl_week;
						}
					}
				}

				$contest_data[] = $this_contest;
			}
		}
	}
	wp_reset_query();




	// Check that games are over before processing wagers

	$areGamesOverUrl = "https://fly.sportsdata.io/v3/nfl/scores/json/ScoresByWeek/$contest_schedule/$contest_schedule_week?key=$stats_key";

	$areGamesOver_response = wp_remote_get($areGamesOverUrl, array(
		'method'	=> 'GET',
		'timeout'	=> 60,
	));

	$currentDay = date('d-M-Y');
	$addOneDayToCurrentDay = date('d-M-Y', strtotime($date . ' +1 day'));

	if (is_array($areGamesOver_response) && !is_wp_error($areGamesOver_response)) {


		$areGamesOver_games = json_decode($areGamesOver_response['body']);


		foreach ($contest_data as $contest) {


			$contest_results = json_decode(get_field('contest_data', $contest['contest_id']));
			$contest_complete = true;

			if ($contest['contest_type'] == 'Team vs Team') {

				foreach ($contest_results as $game) {

					if ($contest_complete == true) {

						$teams = array();

						foreach ($game as $team) {

							$teams[] = $team->team_abbrev;
						}

						foreach ($areGamesOver_games as $api_game) {

							if ($api_game->AwayTeam == $teams[0] || $api_game->AwayTeam == $teams[1]) {

								if (strtotime($contest_data) < strtotime($addOneDayToCurrentDay)) {
									$contest_complete = true;
									// If contests date is in past, mark as "Finished"

									update_field('contest_status', 'Finished', $contest['contest_id']);
									break;
								}
								break;
								if ($api_game->IsOver != 1) {

									$contest_complete = false;
								}
							}
						}
					}
				}

				if ($contest_complete == true) {

					// If contest is finished, mark as "Finished"

					update_field('contest_status', 'Finished', $contest['contest_id']);
				}
			} else if ($contest['contest_type'] == 'Mixed') {


				foreach ($areGamesOver_games as $api_game) {
					if (strtotime($contest_data) < strtotime($addOneDayToCurrentDay)) {
						$contest_complete = true;
						// If contests date is in past, mark as "Finished"

						update_field('contest_status', 'Finished', $contest['contest_id']);
						break;
					}
					if ($api_game->IsOver != 1) {

						$contest_complete = false;
						break;
					}
				}

				if ($contest_complete == true) {

					// If contest is finished, mark as "Finished"

					update_field('contest_status', 'Finished', $contest['contest_id']);
				}
			} else if ($contest['contest_type'] == 'Teams') {

				foreach ($areGamesOver_games as $api_game) {

					if (strtotime($contest_data) < strtotime($addOneDayToCurrentDay)) {
						$contest_complete = true;
						// If contests date is in past, mark as "Finished"

						update_field('contest_status', 'Finished', $contest['contest_id']);
						break;
					}
					if ($api_game->IsOver != 1) {

						$contest_complete = false;
						break;
					}
				}

				if ($contest_complete == true) {

					// If contest is finished, mark as "Finished"

					update_field('contest_status', 'Finished', $contest['contest_id']);
				}
			}
		}


		if ($contest_complete == false) {

			echo '<div id="message" class="updated fade error"><p>Not all games are complete for this date. Please try again when all games are over.</p></div>';
		} else {


			// Mark main Team Vs Team contest as "Closed"

			if (isset($main_contest_id)) {

				update_field('contest_status', 'Closed', $main_contest_id);
			}

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
						'terms'    => 'nfl',
					),
				),
			);

			$the_query = new WP_Query($args);

			if ($the_query->have_posts()) {

				while ($the_query->have_posts()) {

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
								update_post_meta($post->ID, 'team_winner_' . ($i + 1), $term_id);
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

						$the_wager = new WP_Query($args);

						if ($the_wager->have_posts()) {

							while ($the_wager->have_posts()) {

								$the_wager->the_post();

								$wager_id = get_the_id();

								$wager_type = strtolower(get_field('wager_type', $wager_id));
								$wager_amount = str_replace(',', '', get_field('wager_amount', $wager_id));
								$wager_winnings = str_replace(',', '', get_field('potential_winnings', $wager_id));

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
										wp_set_post_terms($wager_id, 62, 'wager_result', false);

										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'place') {

									if ($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);

										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'show') {

									if ($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2 || $wagered_winner_1 == $actual_winner_3) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'pick 2') {

									if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'pick 2 box') {

									if (($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2) && ($wagered_winner_2 == $actual_winner_1 || $wagered_winner_2 == $actual_winner_2)) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'pick 3') {

									if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2 && $wagered_winner_3 == $actual_winner_3) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'pick 3 box') {

									if (($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2 || $wagered_winner_1 == $actual_winner_3) && ($wagered_winner_2 == $actual_winner_1 || $wagered_winner_2 == $actual_winner_2 || $wagered_winner_2 == $actual_winner_3) && ($wagered_winner_3 == $actual_winner_1 || $wagered_winner_3 == $actual_winner_2 || $wagered_winner_3 == $actual_winner_3)) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'pick 4') {

									if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2 && $wagered_winner_3 == $actual_winner_3 && $wagered_winner_4 == $actual_winner_4) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'pick 4 box') {

									if (($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2 || $wagered_winner_1 == $actual_winner_3) && ($wagered_winner_2 == $actual_winner_1 || $wagered_winner_2 == $actual_winner_2 || $wagered_winner_2 == $actual_winner_3) && ($wagered_winner_3 == $actual_winner_1 || $wagered_winner_3 == $actual_winner_2 || $wagered_winner_3 == $actual_winner_3) && ($wagered_winner_4 == $actual_winner_1 || $wagered_winner_4 == $actual_winner_2 || $wagered_winner_4 == $actual_winner_3 || $wagered_winner_4 == $actual_winner_4)) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'pick 6') {

									if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2 && $wagered_winner_3 == $actual_winner_3 && $wagered_winner_4 == $actual_winner_4 && $wagered_winner_5 == $actual_winner_5 && $wagered_winner_6 == $actual_winner_6) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								}

								update_field('wager_contest_winner', $actual_winner_1, $wager_id);

								//Update user balance

								$user_id = get_post_field('post_author', $wager_id);
								$buying_power = floatval(get_field('account_balance', 'user_' . $user_id));
								$total_equity = floatval(get_field('visible_balance', 'user_' . $user_id));


								if ($winner == true) {

									$total_equity = $total_equity + $wager_winnings;
									$buying_power = $buying_power + $wager_winnings + $wager_amount;
								} else {

									$total_equity = $total_equity - $wager_amount;
								}

								update_field('account_balance', $buying_power, 'user_' . $user_id);
								update_field('visible_balance', $total_equity, 'user_' . $user_id);

								$wager_count++;
							}

							$the_query->reset_postdata();
						}

						//final step: mark contest as 'closed'
						update_post_meta($contest_id, 'contest_status', 'Closed');
					} else if ($contest_type == 'Mixed') {

						$mixed_winners = array();

						$i = 0;
						foreach ($contest_results as $winner) {

							if ($i < 6) {

								$winner_name = $winner->team_name;
								update_post_meta($post->ID, 'mixed_winner_' . ($i + 1), $winner_name);
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

						$the_wager = new WP_Query($args);

						if ($the_wager->have_posts()) {

							while ($the_wager->have_posts()) {

								$the_wager->the_post();

								$wager_id = get_the_id();

								$wager_type = strtolower(get_field('wager_type', $wager_id));
								$wager_amount = str_replace(',', '', get_field('wager_amount', $wager_id));
								$wager_winnings = str_replace(',', '', get_field('potential_winnings', $wager_id));

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
										wp_set_post_terms($wager_id, 62, 'wager_result', false);

										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'place') {

									if ($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);

										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'show') {

									if ($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2 || $wagered_winner_1 == $actual_winner_3) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'pick 2') {

									if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'pick 2 box') {

									if (($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2) && ($wagered_winner_2 == $actual_winner_1 || $wagered_winner_2 == $actual_winner_2)) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'pick 3') {

									if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2 && $wagered_winner_3 == $actual_winner_3) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'pick 3 box') {

									if (($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2 || $wagered_winner_1 == $actual_winner_3) && ($wagered_winner_2 == $actual_winner_1 || $wagered_winner_2 == $actual_winner_2 || $wagered_winner_2 == $actual_winner_3) && ($wagered_winner_3 == $actual_winner_1 || $wagered_winner_3 == $actual_winner_2 || $wagered_winner_3 == $actual_winner_3)) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'pick 4') {

									if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2 && $wagered_winner_3 == $actual_winner_3 && $wagered_winner_4 == $actual_winner_4) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'pick 4 box') {

									if (($wagered_winner_1 == $actual_winner_1 || $wagered_winner_1 == $actual_winner_2 || $wagered_winner_1 == $actual_winner_3) && ($wagered_winner_2 == $actual_winner_1 || $wagered_winner_2 == $actual_winner_2 || $wagered_winner_2 == $actual_winner_3) && ($wagered_winner_3 == $actual_winner_1 || $wagered_winner_3 == $actual_winner_2 || $wagered_winner_3 == $actual_winner_3) && ($wagered_winner_4 == $actual_winner_1 || $wagered_winner_4 == $actual_winner_2 || $wagered_winner_4 == $actual_winner_3 || $wagered_winner_4 == $actual_winner_4)) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								} else if ($wager_type == 'pick 6') {

									if ($wagered_winner_1 == $actual_winner_1 && $wagered_winner_2 == $actual_winner_2 && $wagered_winner_3 == $actual_winner_3 && $wagered_winner_4 == $actual_winner_4 && $wagered_winner_5 == $actual_winner_5 && $wagered_winner_6 == $actual_winner_6) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);
										$winner = true;
									} else {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);
									}
								}

								update_field('wager_contest_winner', $actual_winner_1, $wager_id);

								//Update user balance

								$user_id = get_post_field('post_author', $wager_id);
								$buying_power = floatval(get_field('account_balance', 'user_' . $user_id));
								$total_equity = floatval(get_field('visible_balance', 'user_' . $user_id));


								if ($winner == true) {

									$total_equity = $total_equity + $wager_winnings;
									$buying_power = $buying_power + $wager_winnings + $wager_amount;
								} else {

									$total_equity = $total_equity - $wager_amount;
								}

								update_field('account_balance', $buying_power, 'user_' . $user_id);
								update_field('visible_balance', $total_equity, 'user_' . $user_id);

								$wager_count++;
							}

							$the_wager->reset_postdata();
						}


						//final step: mark contest as 'closed'
						update_post_meta($contest_id, 'contest_status', 'Closed');
					} else if ($contest_type == 'Team vs Team') {


						// Retrieve Open wagers for each contest and check for winners

						$args = array(
							'post_type' => 'wager',
							'posts_per_page' => -1,
							'meta_query' => array(
								'relation' => 'AND',
								array(
									'key'     => 'wager_contest',
									'value'   => $contest_id,
								),
								array(
									'key'     => 'wager_result',
									'value'   => 'Open',
								),
							),
						);

						$the_wager = new WP_Query($args);

						if ($the_wager->have_posts()) {

							while ($the_wager->have_posts()) {

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
									} else {

										if ($spread > 0) {

											// Underdog

											$difference = $total_points_winner - $total_points_loser;

											if ($total_points_winner > $total_points_loser) {

												$win = true;
											} else {

												if (abs($difference) == $spread) {

													$push = true;
												} else if (abs($difference) < $spread) {

													$win = true;
												}
											}
										} else {

											// Favored

											$difference = $total_points_winner - $total_points_loser;

											if ($difference == abs($spread)) {

												$push = true;
											} else {

												if ($difference > abs($spread)) {

													$win = true;
												}
											}
										}
									}


									//Update fields and user balance

									$user_id = get_post_field('post_author', $wager_id);
									$buying_power = floatval(get_field('account_balance', 'user_' . $user_id));
									$total_equity = floatval(get_field('visible_balance', 'user_' . $user_id));

									$wager_amount = str_replace(',', '', get_field('wager_amount', $wager_id));
									$wager_winnings = str_replace(',', '', get_field('potential_winnings', $wager_id));

									if ($push == true) {

										update_field('wager_result', 'Push', $wager_id);
										wp_set_post_terms($wager_id, 64, 'wager_result', false);

										$buying_power = $buying_power + $wager_amount;
									} else if ($win == true) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);

										$total_equity = $total_equity + $wager_winnings;
										$buying_power = $buying_power + $wager_winnings + $wager_amount;
									} else if ($win == false) {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);

										$total_equity = $total_equity - $wager_amount;
									}

									update_field('account_balance', $buying_power, 'user_' . $user_id);
									update_field('visible_balance', $total_equity, 'user_' . $user_id);


									$wager_count++;
								} else if ($wager_type == 'Moneyline') {


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
									} else if ($total_points_winner > $total_points_loser) {
										$win = true;
									}

									//Update fields and user balance

									$user_id = get_post_field('post_author', $wager_id);
									$buying_power = floatval(get_field('account_balance', 'user_' . $user_id));
									$total_equity = floatval(get_field('visible_balance', 'user_' . $user_id));

									$wager_amount = str_replace(',', '', get_field('wager_amount', $wager_id));
									$wager_winnings = str_replace(',', '', get_field('potential_winnings', $wager_id));

									if ($push == true) {

										update_field('wager_result', 'Push', $wager_id);
										wp_set_post_terms($wager_id, 64, 'wager_result', false);

										$buying_power = $buying_power + $wager_amount;
									} else if ($win == true) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);

										$total_equity = $total_equity + $wager_winnings;
										$buying_power = $buying_power + $wager_winnings + $wager_amount;
									} else if ($win == false) {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);

										$total_equity = $total_equity - $wager_amount;
									}

									update_field('account_balance', $buying_power, 'user_' . $user_id);
									update_field('visible_balance', $total_equity, 'user_' . $user_id);

									$wager_count++;
								} else if ($wager_type == 'Over/Under') {

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
									} else {

										if ($winner == 'Over') {

											if ($total_points_overunder > $overunder) {
												$win = true;
											}
										} else if ($winner == 'Under') {

											if ($total_points_overunder < $overunder) {
												$win = true;
											}
										}
									}

									//Update fields and user balance

									$user_id = get_post_field('post_author', $wager_id);
									$buying_power = floatval(get_field('account_balance', 'user_' . $user_id));
									$total_equity = floatval(get_field('visible_balance', 'user_' . $user_id));

									$wager_amount = str_replace(',', '', get_field('wager_amount', $wager_id));
									$wager_winnings = str_replace(',', '', get_field('potential_winnings', $wager_id));

									if ($push == true) {

										update_field('wager_result', 'Push', $wager_id);
										wp_set_post_terms($wager_id, 64, 'wager_result', false);

										$buying_power = $buying_power + $wager_amount;
									} else if ($win == true) {

										update_field('wager_result', 'Win', $wager_id);
										wp_set_post_terms($wager_id, 62, 'wager_result', false);

										$total_equity = $total_equity + $wager_winnings;
										$buying_power = $buying_power + $wager_winnings + $wager_amount;
									} else if ($win == false) {

										update_field('wager_result', 'Loss', $wager_id);
										wp_set_post_terms($wager_id, 63, 'wager_result', false);

										$total_equity = $total_equity - $wager_amount;
									}

									update_field('account_balance', $buying_power, 'user_' . $user_id);
									update_field('visible_balance', $total_equity, 'user_' . $user_id);


									$wager_count++;
								}
							}
						}
						$the_wager->reset_postdata();


						// Final step: mark contest as 'Closed'
						update_post_meta($contest_id, 'contest_status', 'Closed');
					}

					$contest_count++;
				}
			}

			wp_reset_query();


			echo '<div id="message" class="updated fade"><p>' . $wager_count . ' wager(s) and ' . $contest_count . ' contest(s) processed.</p></div>';
		}
	} else {

		echo '<div id="message" class="updated fade error"><p>There was an error connecting to the SportsDataIO API. Please try again. (Error Code 3)</p></div>';
	}
}


// Cron jobs

add_action('nfl_update_live_cron', 'nfl_update_live_cron');

function nfl_update_live_cron()
{

	$stats_key = '1f84d3da9fcb4f8fb276be1503989a33';
	update_live_nfl_contests($stats_key, false);

	/*
	$cron_log = array(
		'post_status' => 'draft',
		'post_title' => 'NFL Cron Log - Update Live',
		'post_type' => 'cron_log',
		'post_content' => date('m-d-Y g:i a', time() - (60*60*7)) . ' PT',
		'tax_input' => array (
			'cron_type' => 4308,
		),
	);
	
	wp_set_current_user(1);
	wp_insert_post( $cron_log );
	*/
}

function nfl_schedule_create($schedule_key)
{

	//Get data of scheduled upcoming games 

	$schedule_url = "https://fly.sportsdata.io/v3/nfl/scores/json/Timeframes/recent";

	$contest_response = wp_remote_get($schedule_url, array(
		'method'	=> 'GET',
		'timeout'	=> 60,
		'headers'	=> array(
			'Ocp-Apim-Subscription-Key' => $schedule_key,
		),
	));

	if (is_array($contest_response) && !is_wp_error($contest_response)) {

		$contents = json_decode($contest_response['body']);

		$current_year = date("Y", current_time('timestamp'));
		$seasons = array();
		$weeks = array();

		foreach ($contents as $content) {

			if ($current_year == $content->Season) {

				if ($content->SeasonType == 1) {
					$content->SeasonType = "Regular Season";
					$season_name = $current_year . " NFL Season";
				} elseif ($content->SeasonType == 2) {
					$content->SeasonType = "Preseason";
					$season_name = $current_year . " NFL Preseason";
				} elseif ($content->SeasonType == 3) {
					$content->SeasonType = "Playoffs";
					$season_name = $current_year . " NFL Postseason";
				} elseif ($content->SeasonType == 4) {
					continue;
				}

				$current_schedule = array(
					'season_type' => $content->SeasonType,
					'season' => $content->Season,
					'week' => $content->Week,
					'week_start' => date('Y-m-d', strtotime($content->FirstGameStart)),
					'week_end' => date('Y-m-d', strtotime($content->LastGameEnd)),
				);
				switch (date('D', strtotime($current_schedule['week_start']))) {
					case 'Wed': $current_schedule['week_start'] = (date('Y-m-d', strtotime('-1 day', strtotime($current_schedule['week_start'])))); break;
					case 'Thu': $current_schedule['week_start'] = (date('Y-m-d', strtotime('-2 day', strtotime($current_schedule['week_start'])))); break;
					case 'Fri': $current_schedule['week_start'] = (date('Y-m-d', strtotime('-3 day', strtotime($current_schedule['week_start'])))); break;
					case 'Sat': $current_schedule['week_start'] = (date('Y-m-d', strtotime('-4 day', strtotime($current_schedule['week_start'])))); break;
					case 'Sun': $current_schedule['week_start'] = (date('Y-m-d', strtotime('-5 day', strtotime($current_schedule['week_start'])))); break;
				}


				if (!in_array($season_name, $seasons)) {
					array_push($seasons, $season_name);
				}

				array_push($weeks, $current_schedule);
			}
		}

		//sort the seasons
		foreach ($weeks as $key => $part) {
			$sort[$key] = strtotime($part['se']);
		}
		array_multisort($sort, SORT_ASC, $weeks);

		//loop to create season
		foreach ($seasons as $season) {

			if (strpos($season, 'Pre')) {
				$season_type = 'Preseason';
			} else if (strpos($season, 'Post')) {
				$season_type = 'Playoffs';
			} else {
				$season_type = 'Regular Season';
			}

			if (!term_exists($season, 'schedule')) {
				$tax = wp_insert_term($season, 'schedule');
				if (is_array($tax)) {
					update_term_meta($tax['term_id'], 'schedule_type', $season_type);
				}

				foreach ($weeks as $week) {
					if ($season_type == $week['season_type']) {
						if ($season_type == 'Preseason') {
							$week_name = 'Week ' . ($week['week'] + 1);
						} else {
							$week_name = 'Week ' . $week['week'];
						}

						$week_tax = wp_insert_term($week_name, 'schedule');

						$week_dates = json_encode(array('start_date' => $week['week_start'], 'end_date' => $week['week_end']));

						if (is_array($week_tax)) {
							wp_update_term($week_tax['term_id'], 'schedule', array(
								'description' => $week_dates,
								'parent' => $tax['term_id']
							));
							update_term_meta($week_tax['term_id'], 'nfl_week', $week['week']);
							update_term_meta($week_tax['term_id'], 'schedule_type', $week['season_type']);
						}
					}
				}
			}
		}
	}
}
