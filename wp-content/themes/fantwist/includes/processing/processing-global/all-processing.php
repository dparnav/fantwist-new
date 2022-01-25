<?php

if ($league === "nhl") {
	$stats_key = '1f84d3da9fcb4f8fb276be1503989a33';
}
if ($league === "nba") {
	$stats_key = '1f84d3da9fcb4f8fb276be1503989a33';
}
if ($league === "mlb") {
	$stats_key = '1f84d3da9fcb4f8fb276be1503989a33';
}
if ($league == "nfl") {
	$stats_key = '1f84d3da9fcb4f8fb276be1503989a33';
}

function process_finish_the_game($stats_key)
{
	global $league, $game_date_time, $postData;
	$processing_game_id = $_REQUEST['game_id'];
	$processing_contest_id = $_REQUEST['contest_id'];

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
				'terms'    => $league,
			),
		),
	);

	$the_query = new WP_Query($args);

	if ($the_query->have_posts()) {
		while ($the_query->have_posts()) {
			$the_query->the_post();
			global $post;
			if (date('Y-M-d', strtotime($game_date_time)) == date('Y-M-d', get_field('contest_date'))) {
				$contest_id = $post->ID;
				break;
			}
		}
	}

	if ($league == "nfl") {
		global $post_id;
		$contest_id = $post_id;
	}

	if ($league != "nfl") {
		$update_this_contest_live_scores = "update_" . $league . "_live_scores";
		$update_this_contest_live_scores($stats_key);
	}

	$data = json_decode(get_field('contest_results', $contest_id), false, JSON_UNESCAPED_UNICODE);


	if (!empty($data)) {
		foreach ($data as $game) {
			if ($game->game_id == $processing_game_id) {
				$isComplete = ($game->is_game_over != true) ? false : true;
				break;
			}
		}

		if ($isComplete == true) {

			update_post_meta($postData->ID, 'bidding_status', 3);

			global $current_user;
			$user_id = $current_user->ID;

			//audit added
			wp_insert_post(array(
				'post_author' => $user_id,
				'post_title' => 'Contest - ' . $processing_contest_id . " - game - " . $processing_game_id,
				'post_type' => 'gameaudit',
				'post_status' => 'publish',
				'meta_input' => array(
					'contest_id' => $processing_contest_id,
					'game_id' => $processing_game_id,
					'updated_value_betting_status' => 3
				),
			));

			$continue = true;
		}
	}

	if ($continue == true) {

		// Retrieve Finished contests and calculate winners

		$wager_count = 0;

		$args = array(
			'post_type' => 'contest',
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'league',
					'field'    => 'slug',
					'terms'    => $league,
				),
			),
		);

		$the_query = new WP_Query($args);

		if ($the_query->have_posts()) {

			while ($the_query->have_posts()) {

				$the_query->the_post();
				global $post;

				if (date('Y-M-d', strtotime($game_date_time)) == date('Y-M-d', get_field('contest_date'))) {

					//if this is not main contest then find and calculate the main contest
					$nfl_main_contest = get_field('nfl_main_contest', $post->ID);
					if ($nfl_main_contest != '') {
						$post = get_post($nfl_main_contest);
					}

					$contest_id = $post->ID;

					if ($league == "nfl") {
						$contest_id = $post_id;
					}


					$contest_type = get_field('contest_type', $contest_id);
					$contest_results = json_decode(get_field('contest_results', $contest_id), false, JSON_UNESCAPED_UNICODE);
					$team_category = ['team1', 'team2'];

					if ($contest_type == 'Team vs Team') {

						// Retrieve wagers for each contest, and check for winners

						$args = array(
							'post_type' => 'wager',
							'posts_per_page' => -1,
							'meta_query' => array(
								'relation' => 'AND',
								array(
									'key'     => 'wager_contest',
									'value'   => $processing_contest_id,
								),
								array(
									'key' => 'wager_game_id',
									'value'   => $processing_game_id
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

								//current user details
								$current_user = wp_get_current_user();
								$user_id = $current_user->ID;
								$username = $current_user->user_login;
								//wager amount
								$wager_amount = get_field('wager_amount', $wager_id);



								$winnings = get_field('potential_winnings', $wager_id);
								//terms data
								$wager_type = get_the_terms($wager_id, 'wager_type');
								$wager_type = $wager_type[0]->name;
								$league = get_the_terms($wager_id, 'league');
								$league_id = $league[0]->term_id;

								// $wager_result = get_the_terms($wager_id, 'wager_result');
								// $wager_result_id = $wager_result[0]->term_id;
								// $schedule = get_the_terms($wager_id, 'schedule');
								// $schedule_id = $schedule[0]->term_id;


								if ($wager_type == 'Spread') {

									// For each wager, get winner and spread

									$winner = get_field('wager_winner_1', $wager_id);
									$spread = get_field('point_spread', $wager_id);


									// Loop through contest results to find contest

									foreach ($contest_results as $game) {

										foreach ($team_category as $team_cat1) {

											$team = $game->$team_cat1;

											if ($team->term_id == $winner && $game->game_id == $processing_game_id) {

												// Get total points for wagered winner

												$total_points_winner = $team->total_points;
												$team_name = $team->name;

												$opponent = $team->opponent_abbrev;

												// Get total points for wagered loser

												foreach ($team_category as $team_cat2) {

													$team = $game->$team_cat2;

													if ($team->team_abbrev == $opponent && $game->game_id == $processing_game_id) {

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

									$author_id = get_post_field('post_author', $wager_id);
									$buying_power = floatval(get_field('account_balance', 'user_' . $author_id));
									$total_equity = floatval(get_field('visible_balance', 'user_' . $author_id));

									$wager_amount = str_replace(',', '', get_field('wager_amount', $wager_id));
									$wager_winnings = str_replace(',', '', get_field('potential_winnings', $wager_id));

									if ($push == true) {

										$x_result = 'Push';
										$x_result_id = 64;

										$buying_power = $buying_power + $wager_amount;
									} else if ($win == true) {

										$x_result = 'Win';
										$x_result_id = 62;


										$total_equity = $total_equity + $wager_winnings;
										$buying_power = $buying_power + $wager_winnings + $wager_amount;
									} else if ($win == false) {

										$x_result = 'Loss';
										$x_result_id = 63;

										$total_equity = $total_equity - $wager_amount;
									}
									//insert data in wagers without wager amount

									$current_wager_data = array();
									$wager_type = get_field('wager_type', $wager_id);
									$current_wager_data['wager_post_id'] = get_the_id();
									$current_wager_data['wager_contest'] = get_field('wager_contest', $wager_id);
									$current_wager_data['contest_date'] = get_field('contest_date', $wager_id);
									$current_wager_data['wager_game'] = get_field('wager_game', $wager_id);
									$current_wager_data['wager_type'] = $wager_type;
									$current_wager_data['wager_amount'] = 0;
									$current_wager_data['potential_winnings'] = get_field('potential_winnings', $wager_id);
									$current_wager_data['wager_result'] = $x_result;
									$current_wager_data['wager_winner_1'] = get_field('wager_winner_1', $wager_id);
									$current_wager_data['wager_winner_1_name'] = get_field('wager_winner_1_name', $wager_id);
									$current_wager_data['wager_game_id'] = get_field('wager_game_id', $wager_id);
									$current_wager_data['wager_team_id'] = get_field('wager_team_id', $wager_id);
									$current_wager_data['update_count'] = get_field('update_count', $wager_id);
									$current_wager_data['current_balance'] = get_field('account_balance', 'user_' . $author_id);
									$current_wager_data['wager_rotation'] = strtoupper(get_field('wager_rotation', $wager_id));
									$current_wager_data['wager_winner_2'] = get_field('wager_winner_2', $wager_id);
									$current_wager_data['wager_winner_2_name'] = get_field('wager_winner_2_name', $wager_id);
									$current_wager_data['winner_1_odds'] = get_field('winner_1_odds', $wager_id);
									$current_wager_data['winner_2_odds'] = get_field('winner_2_odds', $wager_id);
									if ($wager_type == 'Spread') {
										$point_type = "point_spread";
									}
									if ($wager_type == 'Moneyline') {
										$point_type = "wager_moneyline";
									}
									if ($wager_type == 'Over/Under') {
										$point_type = "wager_overunder";
									}

									$current_wager_data['points_type'] = $wager_type;
									$current_wager_data['point_spread'] = get_field('point_spread', $wager_id);

									//args
									$args = array(
										'post_author' => $author_id,
										'post_title' => $contest_type . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2) . ' for - ' . $wager_id,
										'post_type' => 'wager',
										'post_status' => 'publish',
										'tax_input'		=> array(
											'wager_type' => array(
												$wager_type_id,
											),
											'wager_result' => array(
												$x_result_id, //wager result id
											),
											'league' => array(
												$league_id,
											),
										),
										'meta_input' => $current_wager_data,
									);
									require_once ABSPATH . '/wp-admin/includes/post.php';
									$post_exists = post_exists($contest_type . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2) . ' for - ' . $wager_id);
									if ($post_exists <= 1) {

										if ($x_result != "LOSS") {
											wp_insert_post($args);
											update_field('account_balance', $buying_power, 'user_' . $author_id);
											update_field('visible_balance', $total_equity, 'user_' . $author_id);
											update_field('open_wager_amount', get_open_wager_amount($author_id), 'user_' . $author_id);
										}
									}
									$wager_count++;
								} else if ($wager_type == 'Moneyline') {


									// For each wager, get winner and moneyline

									$winner = get_field('wager_winner_1', $wager_id);
									$moneyline = get_field('wager_moneyline', $wager_id);

									// Loop through contest results to find contest

									foreach ($contest_results as $game) {

										foreach ($team_category as $team_cat1) {

											$team = $game->$team_cat1;

											if ($team->term_id == $winner && $game->game_id == $processing_game_id) {

												// Get total points for wagered winner

												$total_points_winner = $team->total_points;
												$team_name = $team->name;
												$opponent = $team->opponent_abbrev;

												// Get total points for wagered loser

												foreach ($team_category as $team_cat2) {

													$team = $game->$team_cat2;

													if ($team->team_abbrev == $opponent && $game->game_id == $processing_game_id) {

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

									$author_id = get_post_field('post_author', $wager_id);
									$buying_power = floatval(get_field('account_balance', 'user_' . $author_id));
									$total_equity = floatval(get_field('visible_balance', 'user_' . $author_id));

									$wager_amount = str_replace(',', '', get_field('wager_amount', $wager_id));
									$wager_winnings = str_replace(',', '', get_field('potential_winnings', $wager_id));

									if ($push == true) {

										$x_result = 'Push';
										$x_result_id = 64;

										$buying_power = $buying_power + $wager_amount;
									} else if ($win == true) {

										$x_result = 'Win';
										$x_result_id = 62;

										$total_equity = $total_equity + $wager_winnings;
										$buying_power = $buying_power + $wager_winnings + $wager_amount;
									} else if ($win == false) {

										$x_result = 'Loss';
										$x_result_id = 63;

										$total_equity = $total_equity - $wager_amount;
									}
									//insert data in wagers without wager amount

									$current_wager_data = array();
									$wager_type = get_field('wager_type', $wager_id);
									$current_wager_data['wager_post_id'] = get_the_id();
									$current_wager_data['wager_contest'] = get_field('wager_contest', $wager_id);
									$current_wager_data['contest_date'] = get_field('contest_date', $wager_id);
									$current_wager_data['wager_game'] = get_field('wager_game', $wager_id);
									$current_wager_data['wager_type'] = $wager_type;
									$current_wager_data['wager_amount'] = 0;
									$current_wager_data['potential_winnings'] = get_field('potential_winnings', $wager_id);
									$current_wager_data['wager_result'] = $x_result;
									$current_wager_data['wager_winner_1'] = get_field('wager_winner_1', $wager_id);
									$current_wager_data['wager_winner_1_name'] = get_field('wager_winner_1_name', $wager_id);
									$current_wager_data['wager_game_id'] = get_field('wager_game_id', $wager_id);
									$current_wager_data['wager_team_id'] = get_field('wager_team_id', $wager_id);
									$current_wager_data['update_count'] = get_field('update_count', $wager_id);
									$current_wager_data['current_balance'] = get_field('account_balance', 'user_' . $author_id);
									$current_wager_data['wager_rotation'] = strtoupper(get_field('wager_rotation', $wager_id));
									$current_wager_data['wager_winner_2'] = get_field('wager_winner_2', $wager_id);
									$current_wager_data['wager_winner_2_name'] = get_field('wager_winner_2_name', $wager_id);
									$current_wager_data['winner_1_odds'] = get_field('winner_1_odds', $wager_id);
									$current_wager_data['winner_2_odds'] = get_field('winner_2_odds', $wager_id);
									if ($wager_type == 'Spread') {
										$point_type = "point_spread";
									}
									if ($wager_type == 'Moneyline') {
										$point_type = "wager_moneyline";
									}
									if ($wager_type == 'Over/Under') {
										$point_type = "wager_overunder";
									}

									$current_wager_data['points_type'] = $wager_type;
									$current_wager_data['wager_moneyline'] = get_field('wager_moneyline', $wager_id);

									//args
									$args = array(
										'post_author' => $author_id,
										'post_title' => $contest_type . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2) . ' for - ' . $wager_id,
										'post_type' => 'wager',
										'post_status' => 'publish',
										'tax_input'		=> array(
											'wager_type' => array(
												$wager_type_id,
											),
											'wager_result' => array(
												$x_result_id, //wager result id
											),
											'league' => array(
												$league_id,
											),
										),
										'meta_input' => $current_wager_data,
									);

									require_once ABSPATH . '/wp-admin/includes/post.php';
									$post_exists = post_exists($contest_type . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2) . ' for - ' . $wager_id);
									if ($post_exists <= 1) {

										if ($x_result != "LOSS") {
											wp_insert_post($args);
											update_field('account_balance', $buying_power, 'user_' . $author_id);
											update_field('visible_balance', $total_equity, 'user_' . $author_id);
											update_field('open_wager_amount', get_open_wager_amount($author_id), 'user_' . $author_id);
										}
									}
									$wager_count++;
								} else if ($wager_type == 'Over/Under') {

									// For each wager get winner, over/under and game ID

									$winner = get_field('wager_winner_1', $wager_id);
									$overunder = get_field('wager_overunder', $wager_id);
									$game_id = get_field('overunder_gameid', $wager_id);

									// Loop through contest results to find contest

									foreach ($contest_results as $game) {

										foreach ($team_category as $team_cat1) {

											$team = $game->$team_cat1;

											if ($game->game_id == $game_id) {

												// Get total points for team 1

												$total_points_team1 = $team->total_points;
												$team_name = $team->name;

												// Get total points for team 2

												foreach ($team_category as $team_cat2) {

													$team = $game->$team_cat2;

													if ($game->game_id == $game_id && $team_name != $team->name) {

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

									$author_id = get_post_field('post_author', $wager_id);
									$buying_power = floatval(get_field('account_balance', 'user_' . $author_id));
									$total_equity = floatval(get_field('visible_balance', 'user_' . $author_id));
									$wager_amount = str_replace(',', '', get_field('wager_amount', $wager_id));
									$wager_winnings = str_replace(',', '', get_field('potential_winnings', $wager_id));

									if ($push == true) {
										$x_result = 'Push';
										$x_result_id = 64;
										$buying_power = $buying_power + $wager_amount;
									} else if ($win == true) {
										$x_result = 'Win';
										$x_result_id = 62;
										$total_equity = $total_equity + $wager_winnings;
										$buying_power = $buying_power + $wager_winnings + $wager_amount;
									} else if ($win == false) {
										$x_result = 'Loss';
										$x_result_id = 63;
										$total_equity = $total_equity - $wager_amount;
									}
									//insert data in wagers without wager amount

									$current_wager_data = array();
									$wager_type = get_field('wager_type', $wager_id);
									$current_wager_data['wager_post_id'] = get_the_id();
									$current_wager_data['wager_contest'] = get_field('wager_contest', $wager_id);
									$current_wager_data['contest_date'] = get_field('contest_date', $wager_id);
									$current_wager_data['wager_game'] = get_field('wager_game', $wager_id);
									$current_wager_data['wager_type'] = $wager_type;
									$current_wager_data['wager_amount'] = 0;
									$current_wager_data['potential_winnings'] = get_field('potential_winnings', $wager_id);
									$current_wager_data['wager_result'] = $x_result;
									$current_wager_data['wager_winner_1'] = get_field('wager_winner_1', $wager_id);
									$current_wager_data['wager_winner_1_name'] = get_field('wager_winner_1_name', $wager_id);
									$current_wager_data['wager_game_id'] = get_field('wager_game_id', $wager_id);
									$current_wager_data['wager_team_id'] = get_field('wager_team_id', $wager_id);
									$current_wager_data['update_count'] = get_field('update_count', $wager_id);
									$current_wager_data['current_balance'] = get_field('account_balance', 'user_' . $author_id);
									$current_wager_data['wager_rotation'] = strtoupper(get_field('wager_rotation', $wager_id));
									$current_wager_data['wager_winner_2'] = get_field('wager_winner_2', $wager_id);
									$current_wager_data['wager_winner_2_name'] = get_field('wager_winner_2_name', $wager_id);
									$current_wager_data['winner_1_odds'] = get_field('winner_1_odds', $wager_id);
									$current_wager_data['winner_2_odds'] = get_field('winner_2_odds', $wager_id);
									if ($wager_type == 'Spread') {
										$point_type = "point_spread";
									}
									if ($wager_type == 'Moneyline') {
										$point_type = "wager_moneyline";
									}
									if ($wager_type == 'Over/Under') {
										$point_type = "wager_overunder";
									}

									$current_wager_data['points_type'] = $wager_type;
									$current_wager_data['point_' . $wager_type] = get_field($point_type, $wager_id);

									//args
									$args = array(
										'post_author' => $author_id,
										'post_title' => $contest_type . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2) . ' for - ' . $wager_id,
										'post_type' => 'wager',
										'post_status' => 'publish',
										'tax_input'		=> array(
											'wager_type' => array(
												$wager_type_id,
											),
											'wager_result' => array(
												$x_result_id, //wager result id
											),
											'league' => array(
												$league_id,
											),
										),
										'meta_input' => $current_wager_data,
									);
									require_once ABSPATH . '/wp-admin/includes/post.php';
									$post_exists = post_exists($contest_type . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2) . ' for - ' . $wager_id);
									if ($post_exists <= 1) {

										if ($x_result != "LOSS") {
											wp_insert_post($args);
											update_field('account_balance', $buying_power, 'user_' . $author_id);
											update_field('visible_balance', $total_equity, 'user_' . $author_id);
											update_field('open_wager_amount', get_open_wager_amount($author_id), 'user_' . $author_id);
										}
									}
									$wager_count++;
								}

								sleep(1);
							}
						}
					}
				}
			}
		}
	}
}

function finish_game_for_parlay_wagers()
{
	$wager_result_id = '';
	$parlay_wager_data = array();

	$parlay_wager_data_args = array(
		'post_type'  => 'parlaywager',
		'posts_per_page' => -1,
		'meta_query' => array(

			array(
				'key' => 'wager_result',
				'value'   => "Open"
			)
		),
		'relation' => 'AND',
		'tax_query'		=> array(
			array(
				'taxonomy' => 'wager_result',   // taxonomy name
				'field' => 'slug',           // term_id, slug or name
				'terms' => array('win', 'loss', 'push'),                  // term id, term slug or term name
				'operator' => 'NOT IN'
			),

		),
	);

	$parlay_wager_data_query = new WP_Query($parlay_wager_data_args);

	foreach ($parlay_wager_data_query->posts as $post) {

		$current_wager_data = array();
		$current_parlay_data = json_decode(get_field('parlay_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
		$current_wager_data['wager_post_id'] = $post->ID;
		$current_wager_data['post_id'] = $post->ID;
		$current_wager_data['contest_id'] = get_field('contest_id', $post->ID);
		$current_wager_data['league'] = (get_the_terms($current_wager_data['contest_id'], 'league'))[0]->name;
		$current_wager_data['contest_date'] = get_field('date', $post->ID);
		$current_wager_data['parlay_game_type'] = $current_parlay_data->parlay_game_type;
		$current_wager_data['wager_amount'] = $current_parlay_data->wager_amount;
		$current_wager_data['potential_winning'] = $current_parlay_data->potential_winning;
		$current_wager_data['current_balance'] = $current_parlay_data->current_balance;
		$current_wager_data['team'] = array();
		foreach ($current_parlay_data->team as $team_data) {
			array_push($current_wager_data['team'], array('team_id' => $team_data->team_id, 'game_id' => $team_data->game_id, 'team_name' => $team_data->team_name, 'rotation_number' => $team_data->rotation_number, 'points' => $team_data->points, 'points_type' => $team_data->points_type, 'push' => $team_data->push, 'result' => $team_data->result));
		}
		array_push($parlay_wager_data, $current_wager_data);
	}

	wp_reset_query();

	//match function						
	if (!empty($parlay_wager_data)) {
		foreach ($parlay_wager_data as $wager) {
			$wager_single_result = [];

			//status of the c11ontest
			$contest_status = get_field('contest_status', $wager['contest_id']);

			//only calculate the contest that are In Progress or done


			//the contest result data
			$results = get_field('contest_results', $wager['contest_id']);


			//when game is settled
			$bidding_status = [];
			$bidding_status_settled = '';
			foreach ($wager['team'] as $wager_team) {
				$the_query = new WP_Query(array(
					'post_type'  => 'gamedeatils',
					'meta_query' => array(
						array(
							'key'     => 'contest_id',
							'value'   => $wager['contest_id'],
						),
						array(
							'key' => 'game_id',
							'value'   => $wager_team['game_id']
						),
					),
				));

				$bidding_status =  get_post_meta($the_query->posts[0]->ID);
				$bidding_status_settled = $bidding_status['bidding_status'][0];
			}



			//run only if there is result
			if (!empty($results) && $bidding_status_settled == 3) {


				//variables to count the winners and losers
				$parlay_single_win = false;
				$continue = 0;
				$win = 0;
				$win_spread = 0;
				$win_over_under = 0;
				$push = 0;
				$push_ids = array();

				$contest_results = json_decode($results, false, JSON_UNESCAPED_UNICODE);
				$all_teams = ['team1', 'team2'];

				//contest result for the teams
				foreach ($contest_results as $data) {

					foreach ($all_teams as $current_team) {

						$game_status = $data->is_game_over;
						$team = $data->$current_team;

						//wager team points
						foreach ($wager['team'] as $wager_team) {

							$winning_status = false;
							$push_status = false;
							$the_query = new WP_Query(array(
								'post_type'  => 'gamedeatils',
								'meta_query' => array(
									array(
										'key'     => 'contest_id',
										'value'   => $wager['contest_id'],
									),
									array(
										'key' => 'game_id',
										'value'   => $wager_team['game_id']
									),
								),
							));

							$postMeta =  get_post_meta($the_query->posts[0]->ID);
							if ($postMeta['bidding_status'][0] != 3) {
								$continue = 1;
							}
							//if id matches and points are same then wager is won
							if ($wager_team['team_id'] == $team->term_id && $wager_team['game_id'] == $data->game_id) {
								if ($game_status == true) {

									$current_wager_points = $wager_team['points'];

									$winner_points =  $team->total_points;
									if ($current_team == "team1") {
										$loser_points = $data->team2->total_points;
									} else {
										$loser_points = $data->team1->total_points;
									}

									$total_points = $winner_points + $loser_points; //for calculating over/under
									$difference = $winner_points - $loser_points; //for calculating spread


									if ($wager_team['points_type'] == "Over/Under") {

										//if the wager is for over under
										if ($total_points == $current_wager_points || $total_points == 0) {
											$push++;

											$winning_status = "PUSH";
											array_push($push_ids, $wager_team['team_id']);
										} else {
											if ($current_team == "team1") {
												if ($total_points > $current_wager_points) {
													$win++;
													$winning_status = "WIN";
												} else {
													$winning_status = "LOSS";
												}
											} else if ($current_team == "team2") {
												if ($total_points < $current_wager_points) {
													$win++;
													$winning_status = "WIN";
												} else {
													$winning_status = "LOSS";
												}
											}
										}
									} else if ($wager_team['points_type'] == "spread") {
										//if the wager is for point spread
										if ($winner_points == 0 && $loser_points == 0) {
											$push++;
											$winning_status = "PUSH";
											array_push($push_ids, $wager_team['team_id']);
										} else {

											if ($current_wager_points > 0) {

												if ($winner_points > $loser_points) {
													$win++;
													$win_spread++;
													$winning_status = "WIN";
												} else {
													if (abs($difference) == $current_wager_points) {
														$push++;
														$winning_status = "PUSH";
														array_push($push_ids, $wager_team['team_id']);
													} else if (abs($difference) < $current_wager_points) {
														$win++;
														$win_spread++;
														$winning_status = "WIN";
													}
												}
											} else {
												if ($difference == abs($current_wager_points)) {
													$push++;
													$winning_status = "PUSH";
													array_push($push_ids, $wager_team['team_id']);
												} else if ($difference > abs($current_wager_points)) {
													$win++;
													$win_spread++;
													$winning_status = "WIN";
												}
											}
										}
									}
									if ($winning_status == "PUSH") {
										$wager_team['result'] = 'Push';
									} elseif ($winning_status == "WIN") {
										$wager_team['result'] = 'Win';
									} else {
										$wager_team['result'] = 'Loss';
									}
								} else {
									//do not finish the wagers yet
									$continue = 1;
								}

								array_push($wager_single_result, $wager_team);
							}
						}
					}
				}
				$wager['team'] = $wager_single_result;

				if ($continue == true) {
					continue;
				}

				$wager_post_id = $wager['post_id'];
				$author_id = get_post_field('post_author', $wager_post_id);
				$new_account_balance = get_field('account_balance', 'user_' . $author_id);


				$current_wager_contest_id = $wager['contest_id'];
				$current_wager_id = $wager['post_id'];
				$current_wager_contest_date = $wager['contest_date'];
				$wager['current_balance'] = $new_account_balance;


				unset($wager['post_id']);
				unset($wager['contest_id']);
				unset($wager['contest_date']);


				if ($push == $wager['parlay_game_type']) {
					$x_result = 'PUSH';
					$wager_result_id = 64;

					$wager['potential_winning'] = $wager['wager_amount'];
				} else if ($push + $win == $wager['parlay_game_type']) {

					switch ($win) {
						case 1:
							$winning_amount = $wager['wager_amount'] * 0.9090909090909092;
							$parlay_single_win = true;
							break;
						case 2:
							$winning_amount = $wager['wager_amount'] * 2.6;
							break;
						case 3:
							$winning_amount = $wager['wager_amount'] * 6;
							break;
						case 4:
							$winning_amount = $wager['wager_amount'] * 11;
							break;
						case 5:
							$winning_amount = $wager['wager_amount'] * 22;
							break;
						case 6:
							$winning_amount = $wager['wager_amount'] * 45;
							break;
						case 7:
							$winning_amount = $wager['wager_amount'] * 90;
							break;
						case 8:
							$winning_amount = $wager['wager_amount'] * 180;
							break;
					}


					for ($i = 0; $i < count($wager['team']); $i++) {
						if (in_array($wager['team'][$i]['team_id'], $push_ids)) {
							$wager['team'][$i]['push'] = "on";
						}
					}
					$wager['parlay_game_type'] = $win;

					$new_account_balance += $wager['wager_amount'] + number_format($winning_amount, 2);
					$wager['potential_winning'] = $wager['wager_amount'] + number_format($winning_amount, 2);
					$x_result = 'WIN';
					$wager_result_id = 62;
					// update_field('parlay_data', json_encode($wager, JSON_UNESCAPED_UNICODE), $wager_post_id);
				} else {
					$x_result = 'LOSS';
					$wager_result_id = 63;
					$wager['potential_winning'] = 0;
				}
				$wager['wager_amount'] = 0;
				$tag = get_term_by('name', $wager['league'], 'league');

				$tag_id =  $tag->term_id;
				if ($parlay_single_win) {

					//insert data in wagers without wager amount
					$current_parlay_data = json_decode(get_field('parlay_data', $wager_post_id), false, JSON_UNESCAPED_UNICODE);

					$current_wager_data = array();
					$wager_id =  $wager_post_id;
					$wager_type = $wager_team['points_type'];
					if ($wager_type == 'Over/Under' && substr($wager_team['rotation_number'], -2) % 2 == 0) {
						$current_wager_data['wager_winner_1'] = 'Under';
					} else {
						$current_wager_data['wager_winner_1'] = 'Over';
					}
					if ($wager_type == 'Spread') {
						$current_wager_data['wager_winner_1'] = $wager_type;
					}
					$current_wager_data['wager_post_id'] =  $wager_post_id;
					$current_wager_data['wager_contest'] = get_field('contest_id', $wager_post_id);
					$current_wager_data['contest_date'] = get_field('date', $wager_post_id);;
					$current_wager_data['wager_game'] = 'Team vs Team';
					$current_wager_data['wager_type'] = $wager_type;
					$current_wager_data['wager_amount'] = json_decode(get_field('parlay_data', $current_wager_data['wager_post_id']))->wager_amount;
					$current_wager_data['potential_winnings'] = $current_parlay_data->potential_winning;
					$current_wager_data['wager_result'] = $x_result;

					$current_wager_data['wager_winner_1_name'] = $wager_team['team_name'] . ' ' . $wager_team['points'];
					$current_wager_data['wager_game_id'] = $wager_team['game_id'];
					$current_wager_data['wager_team_id'] =  $wager_team['team_id'];
					$current_wager_data['update_count'] = '-1'; //update counter
					$current_wager_data['current_balance'] =  $current_parlay_data->current_balance;;
					$current_wager_data['wager_rotation'] = $wager_team['rotation_number'];
					$current_wager_data['wager_winner_2'] = get_field('wager_winner_2', $wager_id);
					$current_wager_data['wager_winner_2_name'] = get_field('wager_winner_2_name', $wager_id);
					$current_wager_data['winner_1_odds'] = get_field('winner_1_odds', $wager_id);
					$current_wager_data['winner_2_odds'] = get_field('winner_2_odds', $wager_id);
					if ($wager_type == 'Spread') {
						$point_type = "point_spread";
					}
					if ($wager_type == 'Moneyline') {
						$point_type = "wager_moneyline";
					}
					if ($wager_type == 'Over/Under') {
						$point_type = "wager_overunder";
					}

					$current_wager_data['points_type'] = $wager_type;
					$current_wager_data['point_' . $wager_type] = get_field($point_type, $wager_id);
					$contest_type = 'Team vs Team';
					$wager_amount = json_decode(get_field('parlay_data', $current_wager_data['wager_post_id']))->wager_amount;
					$winnings = $current_parlay_data->potential_winning;
					//args
					$parlay_wager_args = array(
						'post_author' => $author_id,
						'post_title' => $contest_type . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2) . ' for - ' . $wager_id,
						'post_type' => 'wager',
						'post_status' => 'publish',
						'meta_input' => $current_wager_data,
					);
					require_once ABSPATH . '/wp-admin/includes/post.php';
					$post_exists = post_exists($contest_type . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2) . ' for - ' . $wager_id);
				} else {

					$parlay_wager_args = array(
						'post_author' => $author_id,
						'post_title' => 'Parlay Wager for ' . $current_wager_contest_id . ' - ' . $current_wager_id,
						'post_type' => 'parlaywager',
						'post_status' => 'publish',
						'tax_input'		=> array(
							'wager_type' => array(
								4316, //Parlay
							),
							'wager_result' => array(
								$wager_result_id, //wager_result
							),
							'league' => array(
								$tag_id, //league
							),
						),
						'meta_input' => array(
							'contest_id' => $current_wager_contest_id,
							'date' => $current_wager_contest_date,
							'parlay_data' => json_encode($wager, JSON_UNESCAPED_UNICODE),
							'wager_result' => $x_result,
						),

					);
					require_once ABSPATH . '/wp-admin/includes/post.php';
					$post_exists = post_exists('Parlay Wager for ' . $current_wager_contest_id . ' - ' . $current_wager_id);
				}

				wp_set_post_terms($current_wager_id, $wager_result_id, 'wager_result');

				if ($post_exists <= 1) {

					$new_post_id = wp_insert_post($parlay_wager_args);
					wp_set_post_terms($new_post_id, $wager_result_id, 'wager_result');
					wp_set_post_terms($new_post_id, 4316, 'wager_type');
					wp_set_post_terms($new_post_id, $tag_id, 'league');
					if ($x_result != "LOSS") {
						update_field('account_balance', $new_account_balance, 'user_' . $author_id);
						update_field('open_wager_amount', get_open_wager_amount($author_id), 'user_' . $author_id);
					}
				}
			}
			sleep(1);
		}
	}
}

function finish_game_for_teaser_wagers()
{
	$wager_result_id = '';

	$teaser_wager_data = array();

	$teaser_wager_data_args = array(
		'post_type'  => 'teaserwager',
		'posts_per_page' => -1,
		'meta_query' => array(

			array(
				'key' => 'wager_result',
				'value'   => "Open"
			)
		),
		'relation' => 'AND',
		'tax_query'		=> array(
			array(
				'taxonomy' => 'wager_result',   // taxonomy name
				'field' => 'slug',           // term_id, slug or name
				'terms' => array('win', 'loss', 'push'),                  // term id, term slug or term name
				'operator' => 'NOT IN'
			),

		),
	);

	$teaser_wager_data_query = new WP_Query($teaser_wager_data_args);

	foreach ($teaser_wager_data_query->posts as $post) {

		$current_wager_data = array();
		$current_teaser_data = json_decode(get_field('teaser_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
		$current_wager_data['wager_post_id'] = $post->ID;
		$current_wager_data['post_id'] = $post->ID;
		$current_wager_data['contest_id'] = get_field('contest_id', $post->ID);
		$current_wager_data['contest_date'] = get_field('date', $post->ID);
		$current_wager_data['league'] = (get_the_terms($current_wager_data['contest_id'], 'league'))[0]->name;
		$current_wager_data['parlay_game_type'] = $current_teaser_data->parlay_game_type;
		$current_wager_data['teaser_points'] = $current_teaser_data->teaser_points;
		$current_wager_data['wager_amount'] = $current_teaser_data->wager_amount;
		$current_wager_data['potential_winning'] = $current_teaser_data->potential_winning;
		$current_wager_data['current_balance'] = $current_teaser_data->current_balance;
		$current_wager_data['team'] = array();
		foreach ($current_teaser_data->team as $team_data) {
			array_push($current_wager_data['team'], array('team_name' => $team_data->team_name, 'team_id' => $team_data->team_id, 'game_id' => $team_data->game_id, 'rotation_number' => $team_data->rotation_number, 'points' => $team_data->points, 'points_type' => $team_data->points_type, 'push' => $team_data->push, 'result' => $team_data->result));
		}
		array_push($teaser_wager_data, $current_wager_data);
	}
	wp_reset_query();


	//match function	
	if (!empty($teaser_wager_data)) {
		foreach ($teaser_wager_data as $wager) {
			$wager_single_result = [];
			//status of the contest
			$contest_status = get_field('contest_status', $wager['contest_id']);

			//only calculate the contest that are In Progress or done
			if ($contest_status != "Open") {

				//the contest result data
				$results = get_field('contest_results', $wager['contest_id']);

				//when game is settled
				$bidding_status = [];
				$bidding_status_settled = '';
				foreach ($wager['team'] as $wager_team) {
					$the_query = new WP_Query(array(
						'post_type'  => 'gamedeatils',
						'meta_query' => array(
							array(
								'key'     => 'contest_id',
								'value'   => $wager['contest_id'],
							),
							array(
								'key' => 'game_id',
								'value'   => $wager_team['game_id']
							),
						),
					));

					$bidding_status =  get_post_meta($the_query->posts[0]->ID);
					$bidding_status_settled = $bidding_status['bidding_status'][0];
				}



				//run only if there is result
				if (!empty($results) && $bidding_status_settled == 3) {

					//variables to count the winners and losers
					$continue = 0;
					$win = 0;
					$push = 0;
					$push_ids = array();

					$contest_results = json_decode($results, false, JSON_UNESCAPED_UNICODE);
					$all_teams = ['team1', 'team2'];

					//contest result for the teams
					foreach ($contest_results as $data) {
						foreach ($all_teams as $current_team) {
							$game_status = $data->is_game_over;
							$team = $data->$current_team;

							//wager team points
							foreach ($wager['team'] as $wager_team) {
								$winning_status = false;
								$the_query = new WP_Query(array(
									'post_type'  => 'gamedeatils',
									'meta_query' => array(
										array(
											'key'     => 'contest_id',
											'value'   => $wager['contest_id'],
										),
										array(
											'key' => 'game_id',
											'value'   => $wager_team['game_id']
										),
									),
								));

								$postMeta =  get_post_meta($the_query->posts[0]->ID);
								if ($postMeta['bidding_status'][0] != 3) {

									$continue = 1;
								}

								//if id matches and points are same then wager is won
								if ($wager_team['team_id'] == $team->term_id && $wager_team['game_id'] == $data->game_id) {

									if ($game_status == true) {

										$current_wager_points = $wager_team['points'];

										$winner_points =  $team->total_points;
										if ($current_team == "team1") {
											$loser_points = $data->team2->total_points;
										} else {
											$loser_points = $data->team1->total_points;
										}

										$total_points = $winner_points + $loser_points; //for calculating over/under
										$difference = $winner_points - $loser_points; //for calculating spread

										$winning_status = "LOSS";

										if ($wager_team['points_type'] == "Over/Under") {
											//if the wager is for over under
											if ($total_points == $current_wager_points || $total_points == 0) {
												$push++;
												$winning_status = "PUSH";
												array_push($push_ids, $wager_team['team_id']);
											} else {
												if ($current_team == "team1") {
													if ($total_points > $current_wager_points) {
														$win++;
														$winning_status = "WIN";
													}
												} else if ($current_team == "team2") {
													if ($total_points < $current_wager_points) {
														$win++;
														$winning_status = "WIN";
													}
												}
											}
										} else if ($wager_team['points_type'] == "spread") {
											//if the wager is for point spread
											if ($winner_points == 0 && $loser_points == 0) {
												$push++;
												$winning_status = "PUSH";
												array_push($push_ids, $wager_team['team_id']);
											} else {
												if ($current_wager_points > 0) {
													if ($winner_points > $loser_points) {
														$win++;
														$winning_status = "WIN";
													} else {
														if (abs($difference) == $current_wager_points) {
															$push++;
															$winning_status = "PUSH";
															array_push($push_ids, $wager_team['team_id']);
														} else if (abs($difference) < $current_wager_points) {
															$win++;
															$winning_status = "WIN";
														}
													}
												} else {
													if ($difference == abs($current_wager_points)) {
														$push++;
														$winning_status = "PUSH";
														array_push($push_ids, $wager_team['team_id']);
													} else if ($difference > abs($current_wager_points)) {
														$win++;
														$winning_status = "WIN";
													}
												}
											}
										}
										if ($winning_status == "PUSH") {
											$wager_team['result'] = 'Push';
										} elseif ($winning_status == "WIN") {
											$wager_team['result'] = 'Win';
										} else {
											$wager_team['result'] = 'Loss';
										}
									} else if ($game_status == false) {
										//do not finish the wagers yet
										$continue = 1;
									}
									array_push($wager_single_result, $wager_team);
								}
							}
						}
					}
					$wager['team'] = $wager_single_result;

					if ($continue == true) {

						continue;
					}


					$wager_post_id = $wager['post_id'];
					$author_id = get_post_field('post_author', $wager_post_id);
					$new_account_balance = get_field('account_balance', 'user_' . $author_id);


					$current_wager_contest_id = $wager['contest_id'];
					$current_wager_id = $wager['post_id'];
					$current_wager_contest_date = $wager['contest_date'];
					$wager['current_balance'] = $new_account_balance;


					unset($wager['post_id']);
					unset($wager['contest_id']);
					unset($wager['contest_date']);


					if ($push == $wager['parlay_game_type'] || ($push + 1) == $wager['parlay_game_type']) {
						// $new_account_balance += $wager['wager_amount'];
						$x_result = 'PUSH';
						$wager_result_id = 64;
						$wager['potential_winning'] = $wager['wager_amount'];
					} else if ($push + $win == $wager['parlay_game_type']) {
						$teaser_points = 1;
						$teaseByOptions = setting_teaser_points_for_different_league($wager['league']);
						foreach ($teaseByOptions as $option => $teaseBy) {
							if ($teaseBy == $wager['teaser_points']) {
								$teaser_points = $option + 1;
							}
						}
						switch ($teaser_points) {
							case 1:
								switch ($win) {
									case 2:
										$winning_amount = ($wager['wager_amount'] * 5) / 6;
										break;
									case 3:
										$winning_amount = ($wager['wager_amount'] * 8) / 5;
										break;
									case 4:
										$winning_amount = ($wager['wager_amount'] * 5) / 2;
										break;
									case 5:
										$winning_amount = ($wager['wager_amount'] * 9) / 2;
										break;
									case 6:
										$winning_amount = $wager['wager_amount'] * 7;
										break;
									case 7:
										$winning_amount = $wager['wager_amount'] * 9;
										break;
									case 8:
										$winning_amount = $wager['wager_amount'] * 12;
										break;
								}
								break;
							case 2:
								switch ($win) {
									case 2:
										$winning_amount = ($wager['wager_amount'] * 5) / 6.5;
										break;
									case 3:
										$winning_amount = ($wager['wager_amount'] * 7) / 5;
										break;
									case 4:
										$winning_amount = ($wager['wager_amount'] * 12) / 5;
										break;
									case 5:
										$winning_amount = $wager['wager_amount'] * 4;
										break;
									case 6:
										$winning_amount = $wager['wager_amount'] * 6;
										break;
									case 7:
										$winning_amount = $wager['wager_amount'] * 8;
										break;
									case 8:
										$winning_amount = $wager['wager_amount'] * 10;
										break;
								}
								break;
							case 3:
								switch ($win) {
									case 2:
										$winning_amount = ($wager['wager_amount'] * 5) / 7;
										break;
									case 3:
										$winning_amount = ($wager['wager_amount'] * 6) / 5;
										break;
									case 4:
										$winning_amount = ($wager['wager_amount'] * 9) / 5;
										break;
									case 5:
										$winning_amount = ($wager['wager_amount'] * 7) / 2;
										break;
									case 6:
										$winning_amount = $wager['wager_amount'] * 5;
										break;
									case 7:
										$winning_amount = ($wager['wager_amount'] * 13) / 2;
										break;
									case 8:
										$winning_amount = $wager['wager_amount'] * 9;
										break;
								}
								break;
						}

						for ($i = 0; $i < count($wager['team']); $i++) {
							if (in_array($wager['team'][$i]['team_id'], $push_ids)) {
								$wager['team'][$i]['push'] = "on";
							}
						}
						$wager['parlay_game_type'] = $win;

						$new_account_balance += $wager['wager_amount'] + number_format($winning_amount, 2);
						$wager['potential_winning'] = $wager['wager_amount'] + number_format($winning_amount, 2);
						$x_result = 'WIN';
						$wager_result_id = 62;

						update_field('teaser_data', json_encode($wager, JSON_UNESCAPED_UNICODE), $wager_post_id);
					} else {
						$x_result = 'LOSS';
						$wager_result_id = 63;
						$wager['potential_winning'] = 0;
					}
					$wager['wager_amount'] = 0;

					$tag = get_term_by('name', $wager['league'], 'league');

					$tag_id =  $tag->term_id;

					$teaser_wager_args = array(
						'post_author' => $author_id,
						'post_title' => 'Teaser Wager for ' . $current_wager_contest_id . ' - ' . $current_wager_id,
						'post_type' => 'teaserwager',
						'post_status' => 'publish',
						'tax_input'		=> array(
							'wager_type' => array(
								4317, //Teaser
							),
							'wager_result' => array(
								$wager_result_id, //wager_result
							),
							'league' => array(
								$tag_id, //league
							),
						),
						'meta_input' => array(
							'contest_id' => $current_wager_contest_id,
							'date' => $current_wager_contest_date,
							'teaser_data' => json_encode($wager, JSON_UNESCAPED_UNICODE),
							'wager_result' =>  $x_result,
						),
					);
					wp_set_post_terms($current_wager_id, $wager_result_id, 'wager_result');

					require_once ABSPATH . '/wp-admin/includes/post.php';
					$post_exists = post_exists('Teaser Wager for ' . $current_wager_contest_id . ' - ' . $current_wager_id);
					if ($post_exists <= 1) {
						$new_post_id = wp_insert_post($teaser_wager_args);
						wp_set_post_terms($new_post_id, $wager_result_id, 'wager_result');
						wp_set_post_terms($new_post_id, 4317, 'wager_type');
						wp_set_post_terms($new_post_id, $tag_id, 'league');
						if ($x_result != "LOSS") {
							update_field('account_balance', $new_account_balance, 'user_' . $author_id);
							update_field('open_wager_amount', get_open_wager_amount($author_id), 'user_' . $author_id);
						}
						sleep(1);
					}
				}
			}
		}
	}
}

//insert new contest in game details

function game_details_contest($contest_id)
{
	// $px_game_id = $_REQUEST['game_id'];

	$args = array(
		'post_type' => 'contest',
		'p' => $contest_id
	);
	$the_query = new WP_Query($args);


	if ($the_query->have_posts()) {
		foreach ($the_query as $post) {
			$results = get_field('contest_data', $contest_id);
			$contest_status = get_field('contest_status',  $contest_id);

			if (!empty($results)) {

				$contest_data = json_decode($results, false, JSON_UNESCAPED_UNICODE);

				if (get_field('games_status', $post->ID) == "Done" && empty($contest_data)) {
					update_field('contest_results', '', $post->ID);
					update_field('contest_live_stats_url', '', $post->ID);
					update_field('games_status', 'Not Done', $post->ID);
					header('Location: ' . $_SERVER['REQUEST_URI']);
				}
				$contest_data_projection = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
			} else {

				$contest_data = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
			}
			if (!empty($results)) :
				if (count($contest_data_projection) == count($contest_data)) :
					$all_contest_data = $contest_data;
				else :
					$all_contest_data = $contest_data_projection;
					$live_games_with_projection = 1;
				endif;
			else :
				$all_contest_data = $contest_data;
			endif;

			// $px_game_id = '';

			foreach ($all_contest_data as $game) {

				$px_game_id = $game->game_id;
				// print_r($game);


				$args = array(
					'post_type'  => 'gamedeatils',
					'meta_query' => array(
						array(
							'key'     => 'contest_id',
							'value'   => $contest_id
						),
						array(
							'key' => 'game_id',
							'value'   => $px_game_id
						),
					),
				);
				$the_query = new WP_Query($args);
				$team_id_1 = $game->team1->term_id;
				$team_id_2 = $game->team2->term_id;
				$team_name_1 = $game->team1->name;
				$team_name_2 = $game->team2->name;
				$team_1_spread = $game->team1->spread;
				$team_2_spread = $game->team2->spread;
				$team_1_moneyline = $game->team1->moneyline;
				$team_2_moneyline = $game->team2->moneyline;
				$overunder = $game->team1->overunder;
				$betting_status = 0;
				$reason_to_close = isset($_REQUEST['reason_to_close']) ? $_REQUEST['reason_to_close'] : "";

				$team_1_spread_previous = $game->team1->spread;
				$team_2_spread_previous = $game->team2->spread;
				$team_1_moneyline_previous = $game->team1->moneyline;
				$team_2_moneyline_previous = $game->team2->moneyline;
				$overunder_previous = $game->team2->overunder;
				$betting_status_previous = 0;

				global $current_user;
				$user_id = $current_user->ID;
				// $contest_id = '93080';
				$args = array(
					'post_author' => $user_id,
					'post_title' => $username . ' - contest - ' . $contest_id . " - game - " . $px_game_id,
					'post_type' => 'gamedeatils',
					'post_status' => 'publish',
					'meta_input' => array(
						'contest_id' => $contest_id,
						'game_id' => $px_game_id,
						'team_id_1' => $team_id_1,
						'team_name_1' => $team_name_1,
						'point_spread_team_1' => $team_1_spread,
						'money_line_team_1' => $team_1_moneyline,
						'team_id_2' => $team_id_2,
						'team_name_2' => $team_name_2,
						'point_spread_team_2' => $team_2_spread,
						'money_line_team_2' => $team_2_moneyline,
						'over_under' => $overunder,
						'bidding_status' => $betting_status,
						'update_count' => 0
					),
				);

				require_once ABSPATH . '/wp-admin/includes/post.php';
				$post_exists = post_exists($username . ' - contest - ' . $contest_id . " - game - " . $px_game_id);
				if ($post_exists <= 1) {
					wp_insert_post($args);
				}
				// audit added
				$audit_args = array(
					'post_author' => $user_id,
					'post_title' => 'Contest - ' . $contest_id . " - game - " . $px_game_id,
					'post_type' => 'gameaudit',
					'post_status' => 'publish',
					'meta_input' => array(
						'contest_id' => $contest_id,
						'game_id' => $px_game_id,
						'team_id_1' => $team_id_1,
						'team_id_2' => $team_id_2,
						'team_name_1' => $team_name_1,
						'team_name_2' => $team_name_2,
						'previous_value_spread_team_1' => $team_1_spread_previous,
						'previous_value_spread_team_2' => $team_2_spread_previous,
						'previous_value_overunder' => $overunder_previous,
						'previous_value_moneyline_team_1' => $team_1_moneyline_previous,
						'previous_value_moneyline_team_2' => $team_2_moneyline_previous,
						'updated_value_spread_team_1' => $team_1_spread,
						'updated_value_spread_team_2' => $team_2_spread,
						'updated_value_overunder' => $overunder,
						'updated_value_moneyline_team_1' => $team_1_moneyline,
						'updated_value_moneyline_team_2' => $team_2_moneyline,
						'previous_value_betting_status' => $betting_status_previous,
						'updated_value_betting_status' => $betting_status,
						'bet_close_reason' => 0
					),
				);
				require_once ABSPATH . '/wp-admin/includes/post.php';
				$post_exists = post_exists('Contest - ' . $contest_id . " - game - " . $px_game_id);
				if ($post_exists <= 1) {
					wp_insert_post($audit_args);
				}
			}
		}
	}
}
