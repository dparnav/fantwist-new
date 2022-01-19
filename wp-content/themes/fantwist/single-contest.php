<?php
if (is_user_logged_in()) {

	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;
	$username = $current_user->user_login;
	$current_user_id = $current_user->ID;


	if (isset($_POST['wager-type']) && isset($_POST['wager-game'])) {

		$wager_type = $_POST['wager-type'];
		$game_type = $_POST['wager-game'];
		$league_tax = $_POST['wager-league'];
		$wager_game_id = $_POST['wager-game-id'];
		$wager_term_id = $_POST['wager-team-id'];
		$wager_amount = 0;
		$wager_contest = $_POST['wager-contest'];
		$update_count = 0;


		//count flag
		$gamedeatils_query = new WP_Query(array(
			'post_type'  => 'gamedeatils',
			'meta_query' => array(
				array(
					'key'     => 'contest_id',
					'value'   => $wager_contest,
				),
				array(
					'key' => 'game_id',
					'value'   => $wager_game_id
				),
			),
		));
		foreach ($gamedeatils_query->posts as $game) {
			if (get_field('update_count', $game->ID)) {
				$update_count = get_field('update_count', $game->ID);
			}
			if (get_field('bidding_status', $game->ID) != 0) {
				echo '
				<script>
					alert("Sorry, the game has been closed for picks! Place a bet on a different contest.");
					window.location = "' . get_home_url() . '/contest/' . $_POST['wager-contest'] . '";
				</script>
				';
				exit;
			}
		}

		$existing_wager_amount = 0;
		//fantasy points wager
		$wager_data_query = new WP_Query(array(
			'post_type' => 'wager',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'wager_result',
					'value' => 'Open',
					'compare' => 'LIKE'
				)
			),
			'author' => $current_user_id,
		));
		foreach ($wager_data_query->posts as $post) {
			$existing_wager_type = get_field('wager_type', $post->ID) == "Over/Under" ? "overunder" : get_field('wager_type', $post->ID);
			$existing_wager_team_id = get_field('wager_team_id', $post->ID);
			$existing_wager_contest = get_field('wager_contest', $post->ID);
			$wager_term = wp_get_post_terms($post->ID, array('league'));

			if ($wager_term_id == $existing_wager_team_id && strtolower($_POST['wager-line-type']) == strtolower($existing_wager_type) && $wager_contest == $existing_wager_contest && $wager_term[0]->term_id == $league_tax) {
				$existing_wager_amount += get_field('wager_amount', $post->ID);
			}
		}

		if ($existing_wager_amount + $wager_amount > 500) {
			echo '
			<script>
				alert("Sorry, the maximum allowed is $500 for each type of bet on a single game. Please try picks a smaller amount or place a different bet.");
				window.location = "' . get_home_url() . '/contest/' . $_POST['wager-contest'] . '";
			</script>
			';
			exit;
		}

		if ($game_type == 'Teams') {

			if ($wager_type == 'win' || $wager_type == 'place' || $wager_type == 'show') {

				if (isset($_POST['wager-team-1'])) {

					if (isset($_POST['wager-amount'])) {

						if ($wager_type == 'win') {
							$denom = 1;
							$tax = 52;
						} else if ($wager_type == 'place') {
							$denom = 2;
							$tax = 53;
						} else if ($wager_type == 'show') {
							$denom = 3;
							$tax = 54;
						}

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team = $_POST['wager-team-1'];
						$arr = explode('_', $wager_team);
						$wager_team_1 = $arr[0];
						$wager_odds_1 = $arr[1];

						$wager_team_1_term = get_term_by('id', $wager_team_1, 'team');
						$wager_team_1_name = $wager_team_1_term->name;
						$wager_amount = 0;

						$winnings = ($wager_amount * $wager_odds_1) / $denom;

						//echo number_format($winnings,2);

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . $wager_type . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => $wager_type,
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_winner_1' => $wager_team_1,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1_name' => $wager_team_1_name,
								'winner_1_odds' => $wager_odds_1,
								'wager_result' => 'Open',
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);

						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {
						echo 'error2';
					}
				} else {
					echo 'error1';
				}
			} else if ($wager_type == 'pick-2') {

				if (isset($_POST['wager-team-1']) && isset($_POST['wager-team-2'])) {

					if (isset($_POST['wager-amount'])) {

						$tax = 55;

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team_1 = $_POST['wager-team-1'];
						$arr = explode('_', $wager_team_1);
						$wager_team_1 = $arr[0];
						$wager_odds_1 = $arr[1];

						$wager_team_1_term = get_term_by('id', $wager_team_1, 'team');
						$wager_team_1_name = $wager_team_1_term->name;

						$wager_team_2 = $_POST['wager-team-2'];
						$arr = explode('_', $wager_team_2);
						$wager_team_2 = $arr[0];
						$wager_odds_2 = $arr[1];

						$wager_team_2_term = get_term_by('id', $wager_team_2, 'team');
						$wager_team_2_name = $wager_team_2_term->name;

						$wager_amount = 0;

						$winnings = $wager_amount * $wager_odds_1 * $wager_odds_2 * 2;

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => ucwords(str_replace('-', ' ', $wager_type)),
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_winner_1' => $wager_team_1,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1_name' => $wager_team_1_name,
								'wager_winner_2' => $wager_team_2,
								'wager_winner_2_name' => $wager_team_2_name,
								'winner_1_odds' => $wager_odds_1,
								'winner_2_odds' => $wager_odds_2,
								'wager_result' => 'Open',
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);


						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {

						echo 'error4';
					}
				} else {

					echo 'error3';
				}
			} else if ($wager_type == 'pick-2-box') {

				if (isset($_POST['wager-team-1']) && isset($_POST['wager-team-2'])) {

					if (isset($_POST['wager-amount'])) {

						$tax = 56;

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team_1 = $_POST['wager-team-1'];
						$arr = explode('_', $wager_team_1);
						$wager_team_1 = $arr[0];
						$wager_odds_1 = $arr[1];

						$wager_team_1_term = get_term_by('id', $wager_team_1, 'team');
						$wager_team_1_name = $wager_team_1_term->name;

						$wager_team_2 = $_POST['wager-team-2'];
						$arr = explode('_', $wager_team_2);
						$wager_team_2 = $arr[0];
						$wager_odds_2 = $arr[1];

						$wager_team_2_term = get_term_by('id', $wager_team_2, 'team');
						$wager_team_2_name = $wager_team_2_term->name;

						$wager_amount = 0;

						$winnings = $wager_amount * $wager_odds_1 * $wager_odds_2;

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => ucwords(str_replace('-', ' ', $wager_type)),
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_winner_1' => $wager_team_1,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1_name' => $wager_team_1_name,
								'wager_winner_2' => $wager_team_2,
								'wager_winner_2_name' => $wager_team_2_name,
								'winner_1_odds' => $wager_odds_1,
								'winner_2_odds' => $wager_odds_2,
								'wager_result' => 'Open',
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);


						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {

						echo 'error4';
					}
				} else {

					echo 'error3';
				}
			} else if ($wager_type == 'pick-3') {

				if (isset($_POST['wager-team-1']) && isset($_POST['wager-team-2']) && isset($_POST['wager-team-3'])) {

					if (isset($_POST['wager-amount'])) {

						$tax = 57;

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team_1 = $_POST['wager-team-1'];
						$arr = explode('_', $wager_team_1);
						$wager_team_1 = $arr[0];
						$wager_odds_1 = $arr[1];

						$wager_team_1_term = get_term_by('id', $wager_team_1, 'team');
						$wager_team_1_name = $wager_team_1_term->name;

						$wager_team_2 = $_POST['wager-team-2'];
						$arr = explode('_', $wager_team_2);
						$wager_team_2 = $arr[0];
						$wager_odds_2 = $arr[1];

						$wager_team_2_term = get_term_by('id', $wager_team_2, 'team');
						$wager_team_2_name = $wager_team_2_term->name;

						$wager_team_3 = $_POST['wager-team-3'];
						$arr = explode('_', $wager_team_3);
						$wager_team_3 = $arr[0];
						$wager_odds_3 = $arr[1];

						$wager_team_3_term = get_term_by('id', $wager_team_3, 'team');
						$wager_team_3_name = $wager_team_3_term->name;

						$wager_amount = 0;

						$winnings = $wager_amount * $wager_odds_1 * $wager_odds_2 * $wager_odds_3 * 3;

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => ucwords(str_replace('-', ' ', $wager_type)),
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_winner_1' => $wager_team_1,
								'wager_rotation' => $overunder_rotation,

								'wager_winner_1_name' => $wager_team_1_name,
								'wager_winner_2' => $wager_team_2,
								'wager_winner_2_name' => $wager_team_2_name,
								'wager_winner_3' => $wager_team_3,
								'wager_winner_3_name' => $wager_team_3_name,
								'winner_1_odds' => $wager_odds_1,
								'winner_2_odds' => $wager_odds_2,
								'winner_3_odds' => $wager_odds_3,
								'wager_result' => 'Open',
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);


						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {

						echo 'error4';
					}
				} else {

					echo 'error3';
				}
			} else if ($wager_type == 'pick-3-box') {

				if (isset($_POST['wager-team-1']) && isset($_POST['wager-team-2']) && isset($_POST['wager-team-3'])) {

					if (isset($_POST['wager-amount'])) {

						$tax = 58;

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team_1 = $_POST['wager-team-1'];
						$arr = explode('_', $wager_team_1);
						$wager_team_1 = $arr[0];
						$wager_odds_1 = $arr[1];

						$wager_team_1_term = get_term_by('id', $wager_team_1, 'team');
						$wager_team_1_name = $wager_team_1_term->name;

						$wager_team_2 = $_POST['wager-team-2'];
						$arr = explode('_', $wager_team_2);
						$wager_team_2 = $arr[0];
						$wager_odds_2 = $arr[1];

						$wager_team_2_term = get_term_by('id', $wager_team_2, 'team');
						$wager_team_2_name = $wager_team_2_term->name;

						$wager_team_3 = $_POST['wager-team-3'];
						$arr = explode('_', $wager_team_3);
						$wager_team_3 = $arr[0];
						$wager_odds_3 = $arr[1];

						$wager_team_3_term = get_term_by('id', $wager_team_3, 'team');
						$wager_team_3_name = $wager_team_3_term->name;

						$wager_amount = 0;

						$winnings = $wager_amount * $wager_odds_1 * $wager_odds_2 * $wager_odds_3;

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => ucwords(str_replace('-', ' ', $wager_type)),
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1' => $wager_team_1,
								'wager_winner_1_name' => $wager_team_1_name,
								'wager_winner_2' => $wager_team_2,
								'wager_winner_2_name' => $wager_team_2_name,
								'wager_winner_3' => $wager_team_3,
								'wager_winner_3_name' => $wager_team_3_name,
								'winner_1_odds' => $wager_odds_1,
								'winner_2_odds' => $wager_odds_2,
								'winner_3_odds' => $wager_odds_3,
								'wager_result' => 'Open',
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);


						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {

						echo 'error4';
					}
				} else {

					echo 'error3';
				}
			} else if ($wager_type == 'pick-4') {

				if (isset($_POST['wager-team-1']) && isset($_POST['wager-team-2']) && isset($_POST['wager-team-3']) && isset($_POST['wager-team-4'])) {

					if (isset($_POST['wager-amount'])) {

						$tax = 59;

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team_1 = $_POST['wager-team-1'];
						$arr = explode('_', $wager_team_1);
						$wager_team_1 = $arr[0];
						$wager_odds_1 = $arr[1];

						$wager_team_1_term = get_term_by('id', $wager_team_1, 'team');
						$wager_team_1_name = $wager_team_1_term->name;

						$wager_team_2 = $_POST['wager-team-2'];
						$arr = explode('_', $wager_team_2);
						$wager_team_2 = $arr[0];
						$wager_odds_2 = $arr[1];

						$wager_team_2_term = get_term_by('id', $wager_team_2, 'team');
						$wager_team_2_name = $wager_team_2_term->name;

						$wager_team_3 = $_POST['wager-team-3'];
						$arr = explode('_', $wager_team_3);
						$wager_team_3 = $arr[0];
						$wager_odds_3 = $arr[1];

						$wager_team_3_term = get_term_by('id', $wager_team_3, 'team');
						$wager_team_3_name = $wager_team_3_term->name;

						$wager_team_4 = $_POST['wager-team-4'];
						$arr = explode('_', $wager_team_4);
						$wager_team_4 = $arr[0];
						$wager_odds_4 = $arr[1];

						$wager_team_4_term = get_term_by('id', $wager_team_4, 'team');
						$wager_team_4_name = $wager_team_4_term->name;

						$wager_amount = 0;

						$winnings = $wager_amount * $wager_odds_1 * $wager_odds_2 * $wager_odds_3 * $wager_odds_4 * 4;

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => ucwords(str_replace('-', ' ', $wager_type)),
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1' => $wager_team_1,
								'wager_winner_1_name' => $wager_team_1_name,
								'wager_winner_2' => $wager_team_2,
								'wager_winner_2_name' => $wager_team_2_name,
								'wager_winner_3' => $wager_team_3,
								'wager_winner_3_name' => $wager_team_3_name,
								'wager_winner_4' => $wager_team_4,
								'wager_winner_4_name' => $wager_team_4_name,
								'winner_1_odds' => $wager_odds_1,
								'winner_2_odds' => $wager_odds_2,
								'winner_3_odds' => $wager_odds_3,
								'winner_4_odds' => $wager_odds_4,
								'wager_result' => 'Open',
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);


						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {

						echo 'error4';
					}
				} else {

					echo 'error3';
				}
			} else if ($wager_type == 'pick-4-box') {

				if (isset($_POST['wager-team-1']) && isset($_POST['wager-team-2']) && isset($_POST['wager-team-3']) && isset($_POST['wager-team-4'])) {

					if (isset($_POST['wager-amount'])) {

						$tax = 60;

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team_1 = $_POST['wager-team-1'];
						$arr = explode('_', $wager_team_1);
						$wager_team_1 = $arr[0];
						$wager_odds_1 = $arr[1];

						$wager_team_1_term = get_term_by('id', $wager_team_1, 'team');
						$wager_team_1_name = $wager_team_1_term->name;

						$wager_team_2 = $_POST['wager-team-2'];
						$arr = explode('_', $wager_team_2);
						$wager_team_2 = $arr[0];
						$wager_odds_2 = $arr[1];

						$wager_team_2_term = get_term_by('id', $wager_team_2, 'team');
						$wager_team_2_name = $wager_team_2_term->name;

						$wager_team_3 = $_POST['wager-team-3'];
						$arr = explode('_', $wager_team_3);
						$wager_team_3 = $arr[0];
						$wager_odds_3 = $arr[1];

						$wager_team_3_term = get_term_by('id', $wager_team_3, 'team');
						$wager_team_3_name = $wager_team_3_term->name;

						$wager_team_4 = $_POST['wager-team-4'];
						$arr = explode('_', $wager_team_4);
						$wager_team_4 = $arr[0];
						$wager_odds_4 = $arr[1];

						$wager_team_4_term = get_term_by('id', $wager_team_4, 'team');
						$wager_team_4_name = $wager_team_4_term->name;

						$wager_amount = 0;

						$winnings = $wager_amount * $wager_odds_1 * $wager_odds_2 * $wager_odds_3 * $wager_odds_4;

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => ucwords(str_replace('-', ' ', $wager_type)),
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1' => $wager_team_1,
								'wager_winner_1_name' => $wager_team_1_name,
								'wager_winner_2' => $wager_team_2,
								'wager_winner_2_name' => $wager_team_2_name,
								'wager_winner_3' => $wager_team_3,
								'wager_winner_3_name' => $wager_team_3_name,
								'wager_winner_4' => $wager_team_4,
								'wager_winner_4_name' => $wager_team_4_name,
								'winner_1_odds' => $wager_odds_1,
								'winner_2_odds' => $wager_odds_2,
								'winner_3_odds' => $wager_odds_3,
								'winner_4_odds' => $wager_odds_4,
								'wager_result' => 'Open',
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);


						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {

						echo 'error4';
					}
				} else {

					echo 'error3';
				}
			} else if ($wager_type == 'pick-6') {

				if (isset($_POST['wager-team-1']) && isset($_POST['wager-team-2']) && isset($_POST['wager-team-3']) && isset($_POST['wager-team-4']) && isset($_POST['wager-team-5']) && isset($_POST['wager-team-6'])) {

					if (isset($_POST['wager-amount'])) {

						$tax = 61;

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team_1 = $_POST['wager-team-1'];
						$arr = explode('_', $wager_team_1);
						$wager_team_1 = $arr[0];
						$wager_odds_1 = $arr[1];

						$wager_team_1_term = get_term_by('id', $wager_team_1, 'team');
						$wager_team_1_name = $wager_team_1_term->name;

						$wager_team_2 = $_POST['wager-team-2'];
						$arr = explode('_', $wager_team_2);
						$wager_team_2 = $arr[0];
						$wager_odds_2 = $arr[1];

						$wager_team_2_term = get_term_by('id', $wager_team_2, 'team');
						$wager_team_2_name = $wager_team_2_term->name;

						$wager_team_3 = $_POST['wager-team-3'];
						$arr = explode('_', $wager_team_3);
						$wager_team_3 = $arr[0];
						$wager_odds_3 = $arr[1];

						$wager_team_3_term = get_term_by('id', $wager_team_3, 'team');
						$wager_team_3_name = $wager_team_3_term->name;

						$wager_team_4 = $_POST['wager-team-4'];
						$arr = explode('_', $wager_team_4);
						$wager_team_4 = $arr[0];
						$wager_odds_4 = $arr[1];

						$wager_team_4_term = get_term_by('id', $wager_team_4, 'team');
						$wager_team_4_name = $wager_team_4_term->name;

						$wager_team_5 = $_POST['wager-team-5'];
						$arr = explode('_', $wager_team_5);
						$wager_team_5 = $arr[0];
						$wager_odds_5 = $arr[1];

						$wager_team_5_term = get_term_by('id', $wager_team_5, 'team');
						$wager_team_5_name = $wager_team_5_term->name;

						$wager_team_6 = $_POST['wager-team-6'];
						$arr = explode('_', $wager_team_6);
						$wager_team_6 = $arr[0];
						$wager_odds_6 = $arr[1];

						$wager_team_6_term = get_term_by('id', $wager_team_6, 'team');
						$wager_team_6_name = $wager_team_6_term->name;

						$wager_amount = 0;

						$winnings = $wager_amount * $wager_odds_1 * $wager_odds_2 * $wager_odds_3 * $wager_odds_4 * $wager_odds_5 * $wager_odds_6;

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => ucwords(str_replace('-', ' ', $wager_type)),
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1' => $wager_team_1,
								'wager_winner_1_name' => $wager_team_1_name,
								'wager_winner_2' => $wager_team_2,
								'wager_winner_2_name' => $wager_team_2_name,
								'wager_winner_3' => $wager_team_3,
								'wager_winner_3_name' => $wager_team_3_name,
								'wager_winner_4' => $wager_team_4,
								'wager_winner_4_name' => $wager_team_4_name,
								'wager_winner_5' => $wager_team_5,
								'wager_winner_5_name' => $wager_team_5_name,
								'wager_winner_6' => $wager_team_6,
								'wager_winner_6_name' => $wager_team_6_name,
								'winner_1_odds' => $wager_odds_1,
								'winner_2_odds' => $wager_odds_2,
								'winner_3_odds' => $wager_odds_3,
								'winner_4_odds' => $wager_odds_4,
								'winner_5_odds' => $wager_odds_5,
								'winner_6_odds' => $wager_odds_6,
								'wager_result' => 'Open',
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);


						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {

						echo 'error4';
					}
				} else {

					echo 'error3';
				}
			}
		} else if ($game_type == 'Mixed') {

			if ($wager_type == 'win' || $wager_type == 'place' || $wager_type == 'show') {

				if (isset($_POST['wager-team-1'])) {

					if (isset($_POST['wager-amount'])) {

						if ($wager_type == 'win') {
							$denom = 1;
							$tax = 52;
						} else if ($wager_type == 'place') {
							$denom = 2;
							$tax = 53;
						} else if ($wager_type == 'show') {
							$denom = 3;
							$tax = 54;
						}

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team_1 = json_decode(stripslashes($_POST['wager-team-1']));

						$wager_team_1_players = $wager_team_1->players;
						$wager_team_1_name = 'Team ' . $wager_team_1->team_number;
						$wager_odds_1 = $wager_team_1->odds_to_1;

						$wager_amount = 0;

						$winnings = ($wager_amount * $wager_odds_1) / $denom;

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . $wager_type . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => $wager_type,
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1' => $wager_team_1_name,
								'wager_winner_1_name' => $wager_team_1_name,
								'winner_1_odds' => $wager_odds_1,
								'wager_result' => 'Open',
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);

						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {
						echo 'error2';
					}
				} else {
					echo 'error1';
				}
			} else if ($wager_type == 'pick-2') {

				if (isset($_POST['wager-team-1']) && isset($_POST['wager-team-2'])) {

					if (isset($_POST['wager-amount'])) {

						$tax = 55;

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team_1 = json_decode(stripslashes($_POST['wager-team-1']));
						$wager_team_2 = json_decode(stripslashes($_POST['wager-team-2']));

						$wager_team_1_players = $wager_team_1->players;
						$wager_team_1_name = 'Team ' . $wager_team_1->team_number;
						$wager_odds_1 = $wager_team_1->odds_to_1;

						$wager_team_2_players = $wager_team_2->players;
						$wager_team_2_name = 'Team ' . $wager_team_2->team_number;
						$wager_odds_2 = $wager_team_2->odds_to_1;

						$wager_amount = 0;

						$winnings = $wager_amount * $wager_odds_1 * $wager_odds_2 * 2;

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => ucwords(str_replace('-', ' ', $wager_type)),
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1' => $wager_team_1_name,
								'wager_winner_1_name' => $wager_team_1_name,
								'winner_1_odds' => $wager_odds_1,
								'wager_winner_2' => $wager_team_2_name,
								'wager_winner_2_name' => $wager_team_2_name,
								'winner_2_odds' => $wager_odds_2,
								'wager_result' => 'Open',
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);

						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {
						echo 'error2';
					}
				} else {
					echo 'error1';
				}
			} else if ($wager_type == 'pick-2-box') {

				if (isset($_POST['wager-team-1']) && isset($_POST['wager-team-2'])) {

					if (isset($_POST['wager-amount'])) {

						$tax = 56;

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team_1 = json_decode(stripslashes($_POST['wager-team-1']));
						$wager_team_2 = json_decode(stripslashes($_POST['wager-team-2']));

						$wager_team_1_players = $wager_team_1->players;
						$wager_team_1_name = 'Team ' . $wager_team_1->team_number;
						$wager_odds_1 = $wager_team_1->odds_to_1;

						$wager_team_2_players = $wager_team_2->players;
						$wager_team_2_name = 'Team ' . $wager_team_2->team_number;
						$wager_odds_2 = $wager_team_2->odds_to_1;

						$wager_amount = 0;

						$winnings = $wager_amount * $wager_odds_1 * $wager_odds_2;

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => ucwords(str_replace('-', ' ', $wager_type)),
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1' => $wager_team_1_name,
								'wager_winner_1_name' => $wager_team_1_name,
								'winner_1_odds' => $wager_odds_1,
								'wager_winner_2' => $wager_team_2_name,
								'wager_winner_2_name' => $wager_team_2_name,
								'winner_2_odds' => $wager_odds_2,
								'wager_result' => 'Open',
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);

						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {
						echo 'error2';
					}
				} else {
					echo 'error1';
				}
			} else if ($wager_type == 'pick-3') {

				if (isset($_POST['wager-team-1']) && isset($_POST['wager-team-2']) && isset($_POST['wager-team-3'])) {

					if (isset($_POST['wager-amount'])) {

						$tax = 57;

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team_1 = json_decode(stripslashes($_POST['wager-team-1']));
						$wager_team_2 = json_decode(stripslashes($_POST['wager-team-2']));
						$wager_team_3 = json_decode(stripslashes($_POST['wager-team-3']));

						$wager_team_1_players = $wager_team_1->players;
						$wager_team_1_name = 'Team ' . $wager_team_1->team_number;
						$wager_odds_1 = $wager_team_1->odds_to_1;

						$wager_team_2_players = $wager_team_2->players;
						$wager_team_2_name = 'Team ' . $wager_team_2->team_number;
						$wager_odds_2 = $wager_team_2->odds_to_1;

						$wager_team_3_players = $wager_team_3->players;
						$wager_team_3_name = 'Team ' . $wager_team_3->team_number;
						$wager_odds_3 = $wager_team_3->odds_to_1;


						$wager_amount = 0;

						$winnings = $wager_amount * $wager_odds_1 * $wager_odds_2 * $wager_odds_3 * 3;

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => ucwords(str_replace('-', ' ', $wager_type)),
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1' => $wager_team_1_name,
								'wager_winner_1_name' => $wager_team_1_name,
								'winner_1_odds' => $wager_odds_1,
								'wager_winner_2' => $wager_team_2_name,
								'wager_winner_2_name' => $wager_team_2_name,
								'winner_2_odds' => $wager_odds_2,
								'wager_winner_3' => $wager_team_3_name,
								'wager_winner_3_name' => $wager_team_3_name,
								'winner_3_odds' => $wager_odds_3,
								'wager_result' => 'Open',
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);

						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {
						echo 'error2';
					}
				} else {
					echo 'error1';
				}
			} else if ($wager_type == 'pick-3-box') {

				if (isset($_POST['wager-team-1']) && isset($_POST['wager-team-2']) && isset($_POST['wager-team-3'])) {

					if (isset($_POST['wager-amount'])) {

						$tax = 58;

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team_1 = json_decode(stripslashes($_POST['wager-team-1']));
						$wager_team_2 = json_decode(stripslashes($_POST['wager-team-2']));
						$wager_team_3 = json_decode(stripslashes($_POST['wager-team-3']));

						$wager_team_1_players = $wager_team_1->players;
						$wager_team_1_name = 'Team ' . $wager_team_1->team_number;
						$wager_odds_1 = $wager_team_1->odds_to_1;

						$wager_team_2_players = $wager_team_2->players;
						$wager_team_2_name = 'Team ' . $wager_team_2->team_number;
						$wager_odds_2 = $wager_team_2->odds_to_1;

						$wager_team_3_players = $wager_team_3->players;
						$wager_team_3_name = 'Team ' . $wager_team_3->team_number;
						$wager_odds_3 = $wager_team_3->odds_to_1;


						$wager_amount = 0;

						$winnings = $wager_amount * $wager_odds_1 * $wager_odds_2 * $wager_odds_3;

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => ucwords(str_replace('-', ' ', $wager_type)),
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1' => $wager_team_1_name,
								'wager_winner_1_name' => $wager_team_1_name,
								'winner_1_odds' => $wager_odds_1,
								'wager_winner_2' => $wager_team_2_name,
								'wager_winner_2_name' => $wager_team_2_name,
								'winner_2_odds' => $wager_odds_2,
								'wager_winner_3' => $wager_team_3_name,
								'wager_winner_3_name' => $wager_team_3_name,
								'winner_3_odds' => $wager_odds_3,
								'wager_result' => 'Open',
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);

						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {
						echo 'error2';
					}
				} else {
					echo 'error1';
				}
			} else if ($wager_type == 'pick-4') {

				if (isset($_POST['wager-team-1']) && isset($_POST['wager-team-2']) && isset($_POST['wager-team-3']) && isset($_POST['wager-team-4'])) {

					if (isset($_POST['wager-amount'])) {

						$tax = 59;

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team_1 = json_decode(stripslashes($_POST['wager-team-1']));
						$wager_team_2 = json_decode(stripslashes($_POST['wager-team-2']));
						$wager_team_3 = json_decode(stripslashes($_POST['wager-team-3']));
						$wager_team_4 = json_decode(stripslashes($_POST['wager-team-4']));

						$wager_team_1_players = $wager_team_1->players;
						$wager_team_1_name = 'Team ' . $wager_team_1->team_number;
						$wager_odds_1 = $wager_team_1->odds_to_1;

						$wager_team_2_players = $wager_team_2->players;
						$wager_team_2_name = 'Team ' . $wager_team_2->team_number;
						$wager_odds_2 = $wager_team_2->odds_to_1;

						$wager_team_3_players = $wager_team_3->players;
						$wager_team_3_name = 'Team ' . $wager_team_3->team_number;
						$wager_odds_3 = $wager_team_3->odds_to_1;

						$wager_team_4_players = $wager_team_4->players;
						$wager_team_4_name = 'Team ' . $wager_team_4->team_number;
						$wager_odds_4 = $wager_team_4->odds_to_1;


						$wager_amount = 0;

						$winnings = $wager_amount * $wager_odds_1 * $wager_odds_2 * $wager_odds_3 * $wager_odds_4 * 4;

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => ucwords(str_replace('-', ' ', $wager_type)),
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1' => $wager_team_1_name,
								'wager_winner_1_name' => $wager_team_1_name,
								'winner_1_odds' => $wager_odds_1,
								'wager_winner_2' => $wager_team_2_name,
								'wager_winner_2_name' => $wager_team_2_name,
								'winner_2_odds' => $wager_odds_2,
								'wager_winner_3' => $wager_team_3_name,
								'wager_winner_3_name' => $wager_team_3_name,
								'winner_3_odds' => $wager_odds_3,
								'wager_winner_4' => $wager_team_4_name,
								'wager_winner_4_name' => $wager_team_4_name,
								'winner_4_odds' => $wager_odds_4,
								'wager_result' => 'Open',
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);

						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {
						echo 'error2';
					}
				} else {
					echo 'error1';
				}
			} else if ($wager_type == 'pick-4-box') {

				if (isset($_POST['wager-team-1']) && isset($_POST['wager-team-2']) && isset($_POST['wager-team-3']) && isset($_POST['wager-team-4'])) {

					if (isset($_POST['wager-amount'])) {

						$tax = 60;

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team_1 = json_decode(stripslashes($_POST['wager-team-1']));
						$wager_team_2 = json_decode(stripslashes($_POST['wager-team-2']));
						$wager_team_3 = json_decode(stripslashes($_POST['wager-team-3']));
						$wager_team_4 = json_decode(stripslashes($_POST['wager-team-4']));

						$wager_team_1_players = $wager_team_1->players;
						$wager_team_1_name = 'Team ' . $wager_team_1->team_number;
						$wager_odds_1 = $wager_team_1->odds_to_1;

						$wager_team_2_players = $wager_team_2->players;
						$wager_team_2_name = 'Team ' . $wager_team_2->team_number;
						$wager_odds_2 = $wager_team_2->odds_to_1;

						$wager_team_3_players = $wager_team_3->players;
						$wager_team_3_name = 'Team ' . $wager_team_3->team_number;
						$wager_odds_3 = $wager_team_3->odds_to_1;

						$wager_team_4_players = $wager_team_4->players;
						$wager_team_4_name = 'Team ' . $wager_team_4->team_number;
						$wager_odds_4 = $wager_team_4->odds_to_1;


						$wager_amount = 0;

						$winnings = $wager_amount * $wager_odds_1 * $wager_odds_2 * $wager_odds_3 * $wager_odds_4;

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => ucwords(str_replace('-', ' ', $wager_type)),
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1' => $wager_team_1_name,
								'wager_winner_1_name' => $wager_team_1_name,
								'winner_1_odds' => $wager_odds_1,
								'wager_winner_2' => $wager_team_2_name,
								'wager_winner_2_name' => $wager_team_2_name,
								'winner_2_odds' => $wager_odds_2,
								'wager_winner_3' => $wager_team_3_name,
								'wager_winner_3_name' => $wager_team_3_name,
								'winner_3_odds' => $wager_odds_3,
								'wager_winner_4' => $wager_team_4_name,
								'wager_winner_4_name' => $wager_team_4_name,
								'winner_4_odds' => $wager_odds_4,
								'wager_result' => 'Open',
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);

						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {
						echo 'error2';
					}
				} else {
					echo 'error1';
				}
			} else if ($wager_type == 'pick-6') {

				if (isset($_POST['wager-team-1']) && isset($_POST['wager-team-2']) && isset($_POST['wager-team-3']) && isset($_POST['wager-team-4']) && isset($_POST['wager-team-5']) && isset($_POST['wager-team-6'])) {

					if (isset($_POST['wager-amount'])) {

						$tax = 61;

						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];

						$wager_team_1 = json_decode(stripslashes($_POST['wager-team-1']));
						$wager_team_2 = json_decode(stripslashes($_POST['wager-team-2']));
						$wager_team_3 = json_decode(stripslashes($_POST['wager-team-3']));
						$wager_team_4 = json_decode(stripslashes($_POST['wager-team-4']));
						$wager_team_5 = json_decode(stripslashes($_POST['wager-team-5']));
						$wager_team_6 = json_decode(stripslashes($_POST['wager-team-6']));

						$wager_team_1_players = $wager_team_1->players;
						$wager_team_1_name = 'Team ' . $wager_team_1->team_number;
						$wager_odds_1 = $wager_team_1->odds_to_1;

						$wager_team_2_players = $wager_team_2->players;
						$wager_team_2_name = 'Team ' . $wager_team_2->team_number;
						$wager_odds_2 = $wager_team_2->odds_to_1;

						$wager_team_3_players = $wager_team_3->players;
						$wager_team_3_name = 'Team ' . $wager_team_3->team_number;
						$wager_odds_3 = $wager_team_3->odds_to_1;

						$wager_team_4_players = $wager_team_4->players;
						$wager_team_4_name = 'Team ' . $wager_team_4->team_number;
						$wager_odds_4 = $wager_team_4->odds_to_1;

						$wager_team_5_players = $wager_team_5->players;
						$wager_team_5_name = 'Team ' . $wager_team_5->team_number;
						$wager_odds_5 = $wager_team_5->odds_to_1;

						$wager_team_6_players = $wager_team_6->players;
						$wager_team_6_name = 'Team ' . $wager_team_6->team_number;
						$wager_odds_6 = $wager_team_6->odds_to_1;


						$wager_amount = 0;

						$winnings = $wager_amount * $wager_odds_1 * $wager_odds_2 * $wager_odds_3 * $wager_odds_4 * $wager_odds_5 * $wager_odds_6;

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - ' . str_replace('-', ' ', $wager_type) . ' - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									$tax,
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_type' => ucwords(str_replace('-', ' ', $wager_type)),
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1' => $wager_team_1_name,
								'wager_winner_1_name' => $wager_team_1_name,
								'winner_1_odds' => $wager_odds_1,
								'wager_winner_2' => $wager_team_2_name,
								'wager_winner_2_name' => $wager_team_2_name,
								'winner_2_odds' => $wager_odds_2,
								'wager_winner_3' => $wager_team_3_name,
								'wager_winner_3_name' => $wager_team_3_name,
								'winner_3_odds' => $wager_odds_3,
								'wager_winner_4' => $wager_team_4_name,
								'wager_winner_4_name' => $wager_team_4_name,
								'winner_4_odds' => $wager_odds_4,
								'wager_winner_5' => $wager_team_5_name,
								'wager_winner_5_name' => $wager_team_5_name,
								'winner_5_odds' => $wager_odds_5,
								'wager_winner_6' => $wager_team_6_name,
								'wager_winner_6_name' => $wager_team_6_name,
								'winner_6_odds' => $wager_odds_6,
								'wager_result' => 'Open',
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);

						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {
						echo 'error2';
					}
				} else {
					echo 'error1';
				}
			}
		} else if ($game_type == 'Team vs Team') {

			if (isset($_POST['wager-line-type'])) {

				if ($_POST['wager-line-type'] == 'spread') {

					if (isset($_POST['wager-line-type'])) {


						$wager_contest = $_POST['wager-contest'];
						$wager_contest_date = $_POST['wager-contest-date'];
						$wager_team = $_POST['wager-team-vs-spread'];
						$overunder_rotation = $_POST['rotation'];
						$wager_game_id = $_POST['wager-game-id'];

						$arr = explode('_', $wager_team);
						$wager_team_id = $arr[0];
						$spread = $arr[1];

						if ($spread > 0) {
							$spread_plus_minus = '+';
						} else {
							$spread_plus_minus = '';
						}

						$wager_team_1_term = get_term_by('id', $wager_team_id, 'team');
						$wager_team_1_name = $wager_team_1_term->name;
						$wager_amount = 0;

						$winnings = ($wager_amount * 0.9090909090909092) * 2 / 2;
						$winnings = number_format($winnings, 2);

						$args = array(
							'post_author' => $user_id,
							'post_title' => $username . ' - ' . strtolower($game_type) . ' - point spread - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
							'post_type' => 'wager',
							'post_status' => 'publish',
							'tax_input'		=> array(
								'wager_type' => array(
									4305, //spread
								),
								'wager_result' => array(
									65, //open
								),
								'league' => array(
									$league_tax,
								),
							),
							'meta_input' => array(
								'wager_contest' => $wager_contest,
								'wager_game' => $game_type,
								'wager_amount' => $wager_amount,
								'wager_type' => 'Spread',
								'potential_winnings' => number_format($winnings, 2),
								'contest_date' => $wager_contest_date,
								'wager_rotation' => $overunder_rotation,
								'wager_winner_1' => $wager_team_id,
								'wager_winner_1_name' => $wager_team_1_name,
								'wager_result' => 'Open',
								'point_spread' => $spread,
								'wager_game_id' => $wager_game_id,
								'wager_team_id' => $wager_term_id,
								'update_count' => $update_count,
								'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
							),
						);

						// single selection wager type
						$args_2 = array(
							'post_type' => 'wager',
							'posts_per_page' => 10,
							'post_status' => 'publish',
							// 'tax_input'		=> array(
							// 	'wager_type' => array(
							// 		4305, //spread
							// 	),
							// 	'wager_result' => array(
							// 		65, //open
							// 	),
							// 	'league' => array(
							// 		$league_tax,
							// 	),
							// ),
							'meta_query' => array(
								'relation' => 'AND',
								array(
									'key' => 'wager_type',
									'value' => $_POST['wager-line-type'],
									'compare' => 'LIKE'
								),
								array(
									'key' => 'wager_game_id',
									'value' => $wager_game_id,
									'compare' => 'LIKE'
								),
							)
						);
						$the_query2 = new WP_Query($args_2);
						$wager_post_count = $the_query2->post_count;

						//check balance
						$balance = floatval(get_field('account_balance', 'user_' . $user_id));
						$balance = $balance - $wager_amount;

						if ($balance < 0) {

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=0");

							exit;
						} else {

							$new_post_id = wp_insert_post($args);

							if ($new_post_id != 0) {

								update_field('account_balance', $balance, 'user_' . $user_id);
								update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

								$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

								header("Location: $url?bet=1");

								exit;
							}
						}
					} else {
						echo 'error2';
					}
				} else if ($_POST['wager-line-type'] == 'moneyline') {

					$wager_contest = $_POST['wager-contest'];
					$wager_contest_date = $_POST['wager-contest-date'];
					$wager_team = $_POST['wager-team-vs-moneyline'];
					$overunder_rotation = $_POST['rotation'];

					$arr = explode('_', $wager_team);
					$wager_team_id = $arr[0];
					$moneyline = $arr[1];

					$wager_team_1_term = get_term_by('id', $wager_team_id, 'team');
					$wager_amount = 0;



					if ($moneyline > 0) {
						$moneyline_plus_minus = '+';
						$winnings = abs(($wager_amount / 100) * ($moneyline));
					} else {
						$winnings = abs(($wager_amount / $moneyline) * (100));
						$moneyline_plus_minus = '';
					}
					$winnings = number_format($winnings, 2);

					$wager_team_1_term = get_term_by('id', $wager_team_id, 'team');
					$wager_team_1_name = $wager_team_1_term->name;

					$args = array(
						'post_author' => $user_id,
						'post_title' => $username . ' - ' . strtolower($game_type) . ' - moneyline - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
						'post_type' => 'wager',
						'post_status' => 'publish',
						'tax_input'		=> array(
							'wager_type' => array(
								4306, //spread
							),
							'wager_result' => array(
								65, //open
							),
							'league' => array(
								$league_tax,
							),
						),
						'meta_input' => array(
							'wager_contest' => $wager_contest,
							'wager_game' => $game_type,
							'wager_amount' => $wager_amount,
							'wager_type' => 'Moneyline',
							'potential_winnings' => number_format($winnings, 2),
							'contest_date' => $wager_contest_date,
							'wager_rotation' => $overunder_rotation,
							'wager_winner_1' => $wager_team_id,
							'wager_winner_1_name' => $wager_team_1_name,
							'wager_result' => 'Open',
							'wager_moneyline' => $moneyline,
							'wager_game_id' => $wager_game_id,
							'wager_team_id' => $wager_term_id,
							'update_count' => $update_count,
							'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
						),
					);

					//check balance
					$balance = floatval(get_field('account_balance', 'user_' . $user_id));
					$balance = $balance - $wager_amount;

					if ($balance < 0) {

						$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

						header("Location: $url?bet=0");

						exit;
					} else {

						$new_post_id = wp_insert_post($args);

						if ($new_post_id != 0) {

							update_field('account_balance', $balance, 'user_' . $user_id);
							update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=1");

							exit;
						}
					}
				} else if ($_POST['wager-line-type'] == 'overunder') {

					$wager_contest = $_POST['wager-contest'];
					$wager_contest_date = $_POST['wager-contest-date'];
					$overunder_rotation = $_POST['rotation'];
					$overunder_choice = $_POST['overunderval1'];
					$overunder_value = $_POST['overunderval2'];
					$overunder_gameid = $_POST['overunderval3'];
					$overunder_text = $_POST['wager-team-vs-overunder'];

					$wager_amount = 0;

					$winnings = (($wager_amount * 0.9090909090909092)) * 2 / 2;
					$winnings = number_format($winnings, 2);

					$args = array(
						'post_author' => $user_id,
						'post_title' => $username . ' - ' . strtolower($game_type) . ' - over/under - bet ' . $wager_amount . ' to win ' . number_format($winnings, 2),
						'post_type' => 'wager',
						'post_status' => 'publish',
						'tax_input'		=> array(
							'wager_type' => array(
								4307, //over/under
							),
							'wager_result' => array(
								65, //open
							),
							'league' => array(
								$league_tax,
							),
						),
						'meta_input' => array(
							'wager_contest' => $wager_contest,
							'wager_game' => $game_type,
							'wager_amount' => $wager_amount,
							'wager_type' => 'Over/Under',
							'potential_winnings' => number_format($winnings, 2),
							'contest_date' => $wager_contest_date,
							'wager_winner_1' => $overunder_choice,
							'wager_rotation' => $overunder_rotation,
							'wager_winner_1_name' => $overunder_text,
							'wager_result' => 'Open',
							'wager_overunder' => $overunder_value,
							'overunder_gameid' => $overunder_gameid,
							'wager_game_id' => $wager_game_id,
							'wager_team_id' => $wager_term_id,
							'update_count' => $update_count,
							'current_balance' => floatval(get_field('account_balance', 'user_' . $user_id))
						),
					);

					$args_2 = array(
						'post_type' => 'wager',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key' => 'wager_type',
								'value' => 'over/under',
								'compare' => 'LIKE'
							),
							array(
								'key' => 'wager_game_id',
								'value' => $wager_game_id,
								'compare' => 'LIKE'
							),
						)
					);
					$the_query2 = new WP_Query($args_2);
					$wager_post_count = $the_query2->post_count;

					//check balance
					$balance = floatval(get_field('account_balance', 'user_' . $user_id));
					$balance = $balance - $wager_amount;

					if ($balance < 0) {

						$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

						header("Location: $url?bet=0");

						exit;
					} else {

						$new_post_id = wp_insert_post($args);

						if ($new_post_id != 0) {

							update_field('account_balance', $balance, 'user_' . $user_id);
							update_field('open_wager_amount', get_open_wager_amount($user_id), 'user_' . $user_id);

							$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

							header("Location: $url?bet=1");

							exit;
						}
					}
				}
			}
		}
	}
} else {

	header("Location: " . home_url());
	exit;
}

//ajax function
function get_current_scores()
{

	//set variables
	$contest_id = $_POST['contest_id'];
	$game_id = $_POST['game_id'];
	$type = $_POST['type'];
	$team = $_POST['team'];
	$score = $_POST['score'];
	$score_changed = false;

	//get current scores from gamedetails
	$game_details = new WP_Query(array(
		'post_type'  => 'gamedeatils',
		'meta_query' => array(
			array(
				'key'     => 'contest_id',
				'value'   => $contest_id,
			),
			array(
				'key' => 'game_id',
				'value'   => $game_id
			),
		),
	));

	$postData = [];
	if ($game_details->found_posts > 0) {
		if ($game_details->have_posts()) {
			foreach ($game_details->posts as $post) {
				$postData = $post;
			}
		}
	}

	if (count((array)$postData) > 0) {
		$postMeta =  get_post_meta($postData->ID);
		if (empty($results)) {
			$team_1 = $postMeta['team_name_1'][0];
			$team_2 = $postMeta['team_name_2'][0];
			$team_1_spread = $postMeta['point_spread_team_1'][0];
			$team_2_spread = $postMeta['point_spread_team_2'][0];
			$overunder = $postMeta['over_under'][0];
			$team_1_moneyline = $postMeta['money_line_team_1'][0];
			$team_2_moneyline = $postMeta['money_line_team_2'][0];
		}
	}

	$all_teams = ['team_1', 'team_2'];
	foreach ($all_teams as $current_team) {
		if ($team == ${$current_team}) {
			if ($type == 'spread') {
				if ($score != ${$current_team . '_spread'}) {
					$score_changed = true;
					$score = ${$current_team . '_spread'};
				}
			}
			if ($type == 'overunder') {
				if ($score != $overunder) {
					$score_changed = true;
					$score = $overunder;
				}
			}
			if ($type == 'moneyline') {
				if ($score != ${$current_team . '_moneyline'}) {
					$score_changed = true;
					$score = ${$current_team . '_moneyline'};
				}
			}
		}
	}

	$data = [];

	if ($score_changed) {

		$data = [
			'score' => $score,
		];
	}

	echo json_encode($data);

	exit;
}

//ajax function call
if (isset($_POST['type']) && !empty($_POST['type'])) {
	//set variables
	$game_id = $_POST['game_id'];
	$type = $_POST['type'];
	$wager_type = json_encode($type);
	$wager_post_count = '';
	if ($type == 'overunder') {


		//get details of gameid
		$args_2 = array(
			'post_type' => 'wager',
			'posts_per_page' => -1,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'wager_type',
					'value' => 'over/under',

				),
				array(
					'key' => 'wager_game_id',
					'value' => $game_id,

				),
			)
		);
		$the_query2 = new WP_Query($args_2);
		$wager_post_count = $the_query2->post_count;
	}
	if ($type == 'spread') {


		//get details of gameid
		$args_2 = array(
			'post_type' => 'wager',
			'posts_per_page' => -1,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'wager_type',
					'value' => 'spread',

				),
				array(
					'key' => 'wager_game_id',
					'value' => $game_id,

				),
			)
		);
		$the_query2 = new WP_Query($args_2);
		$wager_post_count = $the_query2->post_count;
	}

	echo ($wager_post_count);
	exit;
}


//ajax for single wager contest



//ajax function call
if (isset($_POST['game_id']) && !empty($_POST['game_id'])) {
	$game_id = $_POST['game_id'];
	$game_id();
}









require_once('includes/processing/parser.php');

function trimName($name)
{

	$name = trim($name);

	$parser = new FullNameParser();
	$split_name = $parser->parse_name($name);
	$name = $split_name['fname'][0] . '. ' . $split_name['lname'];

	return $name;
}

// Redirect to main NFL contest
global $post;

$nfl_main_contest = get_field('nfl_main_contest', $post->ID);
if ($nfl_main_contest != '') {
	$main_contest_url = get_permalink($nfl_main_contest);
	header("Location: " . $main_contest_url);
	exit;
}

?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<?php
		$league = get_the_terms($post->ID, 'league');
		$league_id = $league[0]->term_id;
		$league_name = $league[0]->name;

		if ($league_name == 'MLB') {
			$img = get_template_directory_uri() . '/library/images/mlb-header-2.jpg';
		} else if ($league_name == 'PGA') {
			$img = get_template_directory_uri() . '/library/images/pga-header.jpg';
		} else if ($league_name == 'EPL') {
			$img = get_template_directory_uri() . '/library/images/soccer-header.jpg';
		} else if ($league_name == 'MLS') {
			$img = get_template_directory_uri() . '/library/images/soccer-header.jpg';
		} else if ($league_name == 'NASCAR') {
			$img = get_template_directory_uri() . '/library/images/nascar-header-1.jpg';
		} else if ($league_name == 'NFL') {
			$img = get_template_directory_uri() . '/library/images/football-header.jpg';
		} else {
			$img = get_template_directory_uri() . '/library/images/contest-image.jpg';
		}
		?>

		<?php





		?>
		<div class="page-hero contest-hero league-<?php echo $league_name; ?>" style="background-image:url(<?php echo $img; ?>)">

			<div class="hero-details centered-vertical noselect">

				<div class="inner-wrap">

					<div class="hero-logo"><img src="<?php echo get_field('league_logo', 'league_' . $league_id); ?>" /></div>

					<?php

					$contest_type = get_field('contest_type', $post->ID);

					if ($league_name == 'NFL!') {

						// NFL is a little different...

						$contest_id = $post->ID;
						$exclude_post = array($post->ID);
						$contest_title = 'NFL';

						$terms = get_the_terms($post->ID, 'schedule');

						foreach ($terms as $schedule) {

							if ($schedule->parent != '') {

								$schedule_type = get_field('schedule_type', 'schedule_' . $schedule->term_id);

								if ($schedule_type == 'Preseason') {
									$contest_title .= ' Preseason ' . $schedule->name;
								} else if ($schedule_type == 'Regular Season') {
									$contest_title .= ' ' . $schedule->name;
								} else if ($schedule_type == 'Playoffs') {
									$contest_title .= ' Playoffs ' . $schedule->name;
								}
							}
						}

						$contest_date_1 = date('l F j', get_field('contest_date', $post->ID));
						$contest_date_2 = get_field('contest_date', $post->ID);
						$contest_date_3 = date('g:ia', get_field('contest_date', $post->ID));
						$offset = human_time_diff($contest_date_2, current_time('timestamp'));

						$contest_status = get_field('contest_status', $post->ID);

						$force_lock_unlock = get_field('force_lockunlock', $post->ID);

						if ($force_lock_unlock == 'Force Lock') {

							$contest_status = 'In Progress';
						} else if ($force_lock_unlock == 'Force Unlock') {

							$contest_status = 'Open';
						} else {

							if ($contest_date_2 < current_time('timestamp') && $contest_status == 'Open') {

								$contest_status = 'In Progress';
							}
						}

						$contest_begins_time_html = $contest_date_3 . ' ET';

						if ($contest_status == 'Open') {
							if ($force_lock_unlock == 'Force Unlock') {
								$contest_begins_html = 'In Progress';
							} else {
								$contest_begins_html = 'Begins in ' . $offset;
							}

							$contest_status_html = 'Open for betting';
							$odds_title = '';
						} else if ($contest_status == 'In Progress') {
							$contest_begins_html = '';
							$contest_status_html = 'In Progress';
							$odds_title = '';
						} else {
							$contest_begins_html = '';
							$contest_status_html = 'Locked';
							$odds_title = '';
						}

						if ($contest_status == 'Closed' || $contest_status == 'In Progress' || $contest_status == 'Finished') {

							if ($contest_status == 'Closed' || $contest_status == 'Finished') {

								$contest_status_html = 'Completed';
							} else {

								$contest_status_html = 'In Progress';
							}

							$results = get_field('contest_results', $post->ID);

							if (!empty($results)) {

								$contest_data = json_decode($results, false, JSON_UNESCAPED_UNICODE);
							} else {

								$contest_data = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
							}
						} else {

							$contest_data = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
						}

					?>

						<h1><?php echo $contest_title; ?></h1>

						<?php if ($contest_begins_html) { ?><div class="contest-begins"><?php echo $contest_begins_html; ?></div><?php } ?>

						<div class="contest-status contest-status-<?php echo strtolower(str_replace(' ', '-', $contest_status)); ?>">
							<span><?php echo $contest_status_html;
									if ($contest_status == 'In Progress') {
										echo ' <i class="fas fa-lock"></i>';
									} ?></span>
						</div>

					<?php

					} else {

						$contest_id = $post->ID;
						$exclude_post = array($post->ID);

						$contest_date_1 = date('l F j', get_field('contest_date', $post->ID));
						$contest_date_2 = get_field('contest_date', $post->ID);
						$contest_date_3 = date('g:ia', get_field('contest_date', $post->ID));
						$offset = human_time_diff($contest_date_2, current_time('timestamp'));

						$contest_status = get_field('contest_status', $post->ID);

						$force_lock_unlock = get_field('force_lockunlock', $post->ID);

						if ($force_lock_unlock == 'Force Lock') {

							$contest_status = 'In Progress';
							update_field('contest_status', 'In Progress', $post->ID);
						} else if ($force_lock_unlock == 'Force Unlock') {

							$contest_status = 'Open';
							update_field('contest_status', 'Open', $post->ID);
						} else {

							if ($contest_date_2 < current_time('timestamp') && $contest_status == 'Open') {
								$contest_status = 'In Progress';
								update_field('contest_status', 'In Progress', $post->ID);
							}
						}

						$contest_begins_time_html = $contest_date_3 . ' ET';

						if ($contest_status == 'Open') {
							if ($force_lock_unlock == 'Force Unlock') {
								$contest_begins_html = 'In Progress';
							} else {
								$contest_begins_html = 'Begins in ' . $offset;
							}

							$contest_status_html = 'Open for betting';
							$odds_title = '';
						} else if ($contest_status == 'In Progress') {
							$contest_begins_html = '';
							$contest_status_html = 'In Progress';
							$odds_title = '';
						} else {
							$contest_begins_html = '';
							$contest_status_html = 'Locked';
							$odds_title = '';
						}

						if ($contest_status == 'Closed' || $contest_status == 'In Progress' || $contest_status == 'Finished') {

							if ($contest_status == 'Closed' || $contest_status == 'Finished') {

								$contest_status_html = 'Completed';
							} else {

								$contest_status_html = 'In Progress';
							}

							/*$results = get_field('contest_results', $post->ID);
					
					if (!empty($results)) {
					
						$contest_data = json_decode($results, false, JSON_UNESCAPED_UNICODE);
					
					}
					else {
					
						$contest_data = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
					
					}*/
						} else {

							// $contest_data = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);

						}

						if (!isset($_GET['scores'])) {
							$results = get_field('contest_results', $post->ID);
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
						} else {
							if ($_GET['scores'] == 'projection') {
								$contest_data = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
							}
							if ($_GET['scores'] == 'live') {
								$scores_result = get_field('contest_results', $post->ID);
								if (!empty($scores_result)) {
									$contest_data = json_decode($scores_result, false, JSON_UNESCAPED_UNICODE);
								} else {
									$contest_data = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
								}
							}
						}

					?>

						<h1><?php echo $league_name; ?>: <?php echo $contest_date_1; ?></h1>

						<?php if ($league_name == 'PGA' || $league_name == 'NASCAR') { ?>
							<div class="contest-tourney-name"><?php echo get_field('contest_tournament_name', $post->ID); ?> - <?php echo get_field('contest_tournament_location', $post->ID); ?></div>
						<?php } ?>

						<div class="contest-type"><?php echo $contest_type; ?></div>

						<?php if ($league_name != 'PGA') { ?>
							<div class="contest-begins-time"><?php echo $contest_begins_time_html; ?></div>
						<?php } ?>

						<?php if ($contest_begins_html) { ?><div class="contest-begins"><?php echo $contest_begins_html; ?></div><?php } ?>

						<div class="contest-status contest-status-<?php echo strtolower(str_replace(' ', '-', $contest_status)); ?>">
							<span><?php echo $contest_status_html;
									if ($contest_status == 'In Progress') {
										echo ' <i class="fas fa-lock"></i>';
									} ?></span>
						</div>

					<?php

					}

					?>

				</div>

			</div>

		</div>

		<div class="contest-box page-box wrap contest-box-<?php echo str_replace(' ', '-', strtolower($contest_type)); ?>">
			<!-- Error for selection more than one bet -->
			<div class="wager-submitted-message ajax-error" style="display: none;">
				<div class="submit-message-icon"><i class="fas fa-exclamation-circle"></i></div> Sorry, you have already placed this bet.
			</div>
			<!--  -->
			<div class="inner-wrap contest-wrap <?php if ($league_name != 'NFL!') { ?>contest-status-<?php echo str_replace(' ', '-', strtolower($contest_status_html));
																									} ?>">

				<?php if (isset($_GET['bet'])) { ?>

					<?php if ($_GET['bet'] == '1') { ?>

						<div class="wager-submitted-message">
							<div class="submit-message-icon"><i class="far fa-check-circle"></i></div> Your <?php if ($contest_type != 'Team vs Team') {
																												echo '<strong>' . $contest_type . '</strong>';
																											} ?> wager has been submitted. You can view your wagers <a href="<?php echo get_site_url(); ?>/my-wagers/">here<i style="padding-left:3px" class="fas fa-arrow-circle-right"></i></a>
						</div>

					<?php } ?>

					<?php if ($_GET['bet'] == '0') { ?>

						<div class="wager-submitted-message error">
							<div class="submit-message-icon"><i class="fas fa-exclamation-circle"></i></div> Sorry, you have already placed this bet.
						</div>

					<?php } ?>

				<?php } ?>

				<?php if ($contest_status == 'Open' && ($contest_type == 'Mixed' || $contest_type == 'Teams' || $contest_type == 'Starters')) { ?>

					<div class="popupbet contest-tabs-wrap noselect contest-tabs-<?php echo str_replace(' ', '-', strtolower($contest_type)); ?>">

						<div class="make-a-bet-header">Make a Bet</div>

						<form action="" class="submit-bet-form" method="post">

							<?php if ($contest_type == 'Mixed' || $contest_type == 'Teams' || $contest_type == 'Starters') { ?>

								<div class="wager-tab">

									<div class="wager-label"><span class="wager-step">1.</span> Select Wager Type: </div>

									<div class="selectdiv">
										<select class="select-wager" name="wager-type">
											<!--<option value="select" selected="selected">Type</option>-->
											<option value="win" selected="selected">Win</option>
											<option value="place">Place</option>
											<option value="show">Show</option>
											<option value="pick-2">Pick 2</option>
											<option value="pick-2-box">Pick 2 Box</option>
											<option value="pick-3">Pick 3</option>
											<option value="pick-3-box">Pick 3 Box</option>
											<option value="pick-4">Pick 4</option>
											<option value="pick-4-box">Pick 4 Box</option>
											<option value="pick-6">Pick 6</option>
										</select>
									</div>

									<div class="wager-descriptions">
										<div class="wager-description wager-description-win show">Team finishes 1st</div>
										<div class="wager-description wager-description-place">Team finishes 1st or 2nd</div>
										<div class="wager-description wager-description-show">Team finishes 1st, 2nd, or 3rd</div>
										<div class="wager-description wager-description-pick-2">Teams finish 1st and 2nd in EXACT order</div>
										<div class="wager-description wager-description-pick-2-box">Teams finish 1st and 2nd in ANY order</div>
										<div class="wager-description wager-description-pick-3">Teams finish 1st, 2nd, and 3rd in EXACT order</div>
										<div class="wager-description wager-description-pick-3-box">Teams finish 1st, 2nd, and 3rd in ANY order</div>
										<div class="wager-description wager-description-pick-4">Teams finish 1st, 2nd, 3rd, and 4th in EXACT order</div>
										<div class="wager-description wager-description-pick-4-box">Teams finish 1st, 2nd, 3rd, and 4th in ANY order</div>
										<div class="wager-description wager-description-pick-6">Teams finish 1st through 6th in EXACT order</div>
									</div>

								</div>

								<div class="contest-tabs table fixed-layout">

									<div class="contest-tab label-tab table-cell top show">
										<div class="tab-header">&nbsp;</div>
										<div class="make-a-bet-label"><span class="wager-step">2.</span>Choose Team(s): </div>
									</div>

									<?php if ($contest_type == 'Teams') : ?>

										<?php for ($tab = 1; $tab < 7; $tab++) { ?>

											<?php
											if ($tab == 1 || $tab == 4) {
												echo '<div class="contest-tab-row-' . $tab . '">';
											}
											?>

											<div class="contest-tab contest-tab-teams contest-tab-<?php echo $tab; ?> table-cell middle <?php if ($tab == 1) {
																																			echo 'show';
																																		} ?>">

												<div class="tab-header hide"><?php echo $tab;
																				if ($tab == 1) {
																					echo 'st';
																				} else if ($tab == 2) {
																					echo 'nd';
																				} else if ($tab == 3) {
																					echo 'rd';
																				} else if ($tab == 4 || $tab == 5 || $tab == 6) {
																					echo 'th';
																				} ?></div>

												<div class="selectdiv">

													<select class="select-team" name="wager-team-<?php echo $tab; ?>" data-index="<?php echo $tab; ?>">

														<option selected="selected">Team</option>

														<?php
														$odds_count = 2;
														foreach ($contest_data as $team) {
														?>

															<option class="option-select-team option-select-team-<?php echo $team->term_taxonomy_id; ?>" value="<?php echo $team->term_taxonomy_id; ?>_<?php echo $odds_count; ?>" name="<?php echo $team->name; ?> (<?php echo $odds_count; ?>:1)"><?php echo $team->name; ?> (<?php echo $odds_count; ?>:1)</option>

														<?php
															$odds_count++;
														}
														?>

													</select>

												</div>

											</div>

											<?php
											if ($tab == 6 || $tab == 3) {
												echo '</div>';
											}
											?>

										<?php } ?>

										<input id="wager-game-input" name="wager-game" value="Teams" type="hidden">

									<?php elseif ($contest_type == 'Mixed') : ?>

										<?php for ($tab = 1; $tab < 7; $tab++) { ?>

											<?php
											if ($tab == 1 || $tab == 4) {
												echo '<div class="contest-tab-row-' . $tab . '">';
											}
											?>

											<div class="contest-tab contest-tab-mixed contest-tab-<?php echo $tab; ?> table-cell middle <?php if ($tab == 1) {
																																			echo 'show';
																																		} ?>">

												<div class="tab-header hide"><?php echo $tab;
																				if ($tab == 1) {
																					echo 'st';
																				} else if ($tab == 2) {
																					echo 'nd';
																				} else if ($tab == 3) {
																					echo 'rd';
																				} else if ($tab == 4 || $tab == 5 || $tab == 6) {
																					echo 'th';
																				} ?></div>

												<div class="selectdiv">

													<select class="select-team" name="wager-team-<?php echo $tab; ?>" data-index="<?php echo $tab; ?>">

														<option selected="selected">Team</option>

														<?php

														$odds_count = 2;
														$team_count = 1;

														foreach ($contest_data as $team) {

															$team_data = array();

															$odds_count = $team->odds_to_1;
															$team_data['odds_to_1'] = $odds_count;
															$team_data['team_number'] = $team_count;

														?>

															<option class="team-<?php echo $odds_count; ?> option-select-team option-select-team-<?php echo $team_count; ?>" value="<?php echo htmlspecialchars(json_encode($team_data), ENT_QUOTES, 'UTF-8'); ?>" name="Team <?php echo $team_count; ?> (<?php echo $odds_count; ?>:1)">Team <?php echo $team_count; ?> (<?php echo $odds_count; ?>:1)</option>

														<?php
															$odds_count++;
															$team_count++;
														}
														?>

													</select>

												</div>

											</div>

											<?php
											if ($tab == 6 || $tab == 3) {
												echo '</div>';
											}
											?>

										<?php } ?>

										<input id="wager-game-input" name="wager-game" value="Mixed" type="hidden">

									<?php elseif ($contest_type == 'Starters') : ?>

										<?php for ($tab = 1; $tab < 7; $tab++) { ?>

											<?php
											if ($tab == 1 || $tab == 4) {
												echo '<div class="contest-tab-row-' . $tab . '">';
											}
											?>

											<div class="contest-tab contest-tab-teams contest-tab-<?php echo $tab; ?> table-cell middle <?php if ($tab == 1) {
																																			echo 'show';
																																		} ?>">

												<div class="tab-header hide"><?php echo $tab;
																				if ($tab == 1) {
																					echo 'st';
																				} else if ($tab == 2) {
																					echo 'nd';
																				} else if ($tab == 3) {
																					echo 'rd';
																				} else if ($tab == 4 || $tab == 5 || $tab == 6) {
																					echo 'th';
																				} ?></div>

												<div class="selectdiv">

													<select class="select-team" name="wager-team-<?php echo $tab; ?>" data-index="<?php echo $tab; ?>">

														<option selected="selected">Team</option>

														<?php
														$odds_count = 2;
														foreach ($contest_data as $team) {
														?>

															<option class="option-select-team option-select-team-<?php echo $team->term_taxonomy_id; ?>" value="<?php echo $team->term_taxonomy_id; ?>_<?php echo $odds_count; ?>" name="<?php echo $team->name; ?> (<?php echo $odds_count; ?>:1)"><?php echo $team->name; ?> (<?php echo $odds_count; ?>:1)</option>

														<?php
															$odds_count++;
														}
														?>

													</select>

												</div>

											</div>

											<?php
											if ($tab == 6 || $tab == 3) {
												echo '</div>';
											}
											?>

										<?php } ?>

										<input id="wager-game-input" name="wager-game" value="Starters" type="hidden">


									<?php endif; ?>

								</div>

								<div class="wager-amount-tab">

									<!-- <div class="wager-amount-label"><span class="wager-step">3.</span>Enter Amount ($): </div> -->

									<input class="wager-amount" type="text" value="0" name="wager-amount">

									<input class="submit-bet" type="submit" value="Submit Bet" data-format="<?php echo strtolower($contest_type); ?>">

									<div class="total-winnings-proj">TO WIN: <strong>$<span class="total-winnings-val">0</span></strong></div>
									<div class="total-winnings-msg hide"></div>

								</div>

							<?php } ?>

							<input name="wager-contest" value="<?php echo $post->ID; ?>" type="hidden">
							<input name="wager-contest-date" value="<?php echo $contest_date_2; ?>" type="hidden">
							<input name="wager-league" value="<?php echo $league_id; ?>" id="wager-league" type="hidden">

						</form>

					</div>

				<?php } ?>

				<div class="contest-wrapper contest-status-<?php echo str_replace(' ', '-', $contest_status); ?> contest-wrapper-<?php echo strtolower(str_replace(' ', '', $contest_type)); ?> contest-wrapper-<?php echo strtolower($league_name); ?>">

					<div class="contest-sidebar-left">

						<?php

						if ($league_name == 'NFL!' && $contest_type == 'Team vs Team') {

							$contestIDs = array();
							$main_contest_status = false;

							$args = array(
								'post_type' => 'contest',
								'posts_per_page' => -1,
								'meta_key' => 'nfl_main_contest',
								'meta_value' => $contest_id,
							);

							$the_query3 = new WP_Query($args);
							if ($the_query3->have_posts()) {
								while ($the_query3->have_posts()) {
									$the_query3->the_post();

									$contest_status = get_field('contest_status', $post->ID);

									if ($contest_status == 'Open' && $main_contest_status == false) {

										$main_contest_status = 'Open';
									}
								}
							}
							wp_reset_query();
						} else {

							$main_contest_status = $contest_status;
						}

						$main_contest_status;



						if (($main_contest_status == 'Open' || $main_contest_status == 'In Progress') && $contest_type == 'Team vs Team') {

						?>

							<div class="popupbet section-left bet-ticket-sidebar transition">

								<div class="bet-ticket-close">
									<i class="fas fa-times"></i>
								</div>

								<div class="contest-tabs-wrap noselect contest-tabs-<?php echo str_replace(' ', '-', strtolower($contest_type)); ?>">

									<div class="make-a-bet-header">Bet Ticket <span><?php echo  date('m-d-Y', get_field('contest_date', $post->ID)) ?></span></div>
									<form action="" class="submit-bet-form" method="post">

										<div class="contest-tabs">
											<input type="hidden" name="wager-game-id" id="wager-game-id">
											<input type="hidden" name="wager-team-id" id="wager-team-id">
											<div class="contest-tab label-tab">
												<div class="make-a-bet-label"><span class="wager-step"></span>&nbsp;</div>
											</div>

											<div class="contest-tab contest-tab-teams show">

												<div class="contest-lines-select selectdiv">

													<select class="select-team select-team-spread" name="wager-team-vs-spread">

														<option selected="selected" disabled>Team</option>

														<?php
														$team_category = ['team1', 'team2'];
														$live_games_with_projection = 0;

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


														foreach ($all_contest_data as $game) {

															if ($live_games_with_projection == 1) :
																foreach ($contest_data as $c_data) :
																	if ($game->game_id == $c_data->game_id) :
																		$game = $c_data;
																		break;
																	endif;
																endforeach;
															endif;

															foreach ($team_category as $team_cat) {

																$team = $game->$team_cat;
																/* Show scores from DB updated by admin */

																$game_id = $game->game_id;
																global $post;
																$args = array(
																	'post_type'  => 'gamedeatils',
																	'meta_query' => array(
																		array(
																			'key'     => 'contest_id',
																			'value'   => $post->ID,
																		),
																		array(
																			'key' => 'game_id',
																			'value'   => $game_id
																		),
																	),
																);
																$the_query = new WP_Query($args);
																$postData = [];
																if ($the_query->found_posts > 0) {

																	if ($the_query->have_posts()) {
																		while ($the_query->have_posts()) {
																			$the_query->the_post();
																			$postData = $post;

																			if (count((array)$postData) > 0) {

																				$postMeta =  get_post_meta($postData->ID);
																				$point_spread_team_1 = $postMeta['point_spread_team_1'][0];
																				$point_spread_team_2 = $postMeta['point_spread_team_2'][0];
																				$at_or_vs = $team->home_away;

																				if ($at_or_vs == 'home') {

																					$team->spread = number_format($point_spread_team_2, 2);
																				} else {

																					$team->spread = number_format($point_spread_team_1, 2);
																				}
																			}
																		}
																	}
																}

																wp_reset_postdata();

																/* Show scores from DB updated by admin */

														?>

																<option class="option-select-team option-select-team-<?php echo $team->term_taxonomy_id; ?>" value="<?php echo $team->term_taxonomy_id; ?>_<?php echo $team->spread; ?>" data-gameid="<?php echo $game_id; ?>" name="<?php echo $team->name; ?>" data-spread="<?php echo $team->spread; ?>"><?php echo $team->rotation_number . " - " . $team->name; ?> <?php if ($team->spread > 0) {
																																																																																																							echo '+';
																																																																																																						}
																																																																																																						echo $team->spread; ?></option>

														<?php
															}
														}
														?>

													</select>

													<select class="select-team select-team-moneyline" name="wager-team-vs-moneyline">

														<option selected="selected" disabled>Team</option>

														<?php
														$team_category = ['team1', 'team2'];
														$live_games_with_projection = 0;

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


														foreach ($all_contest_data as $game) {

															if ($live_games_with_projection == 1) :
																foreach ($contest_data as $c_data) :
																	if ($game->game_id == $c_data->game_id) :
																		$game = $c_data;
																		break;
																	endif;
																endforeach;
															endif;

															foreach ($team_category as $team_cat) {

																$team = $game->$team_cat;
																/* Show scores from DB updated by admin */

																$game_id = $game->game_id;

																$args = array(
																	'post_type'  => 'gamedeatils',
																	'meta_query' => array(
																		array(
																			'key'     => 'contest_id',
																			'value'   => $post->ID,
																		),
																		array(
																			'key' => 'game_id',
																			'value'   => $game_id
																		),
																	),
																);
																$the_query = new WP_Query($args);
																$postData = [];
																if ($the_query->found_posts > 0) {

																	if ($the_query->have_posts()) {
																		while ($the_query->have_posts()) {
																			$the_query->the_post();
																			$postData = $post;

																			if (count((array)$postData) > 0) {
																				$postMeta = get_post_meta($postData->ID);
																				$at_or_vs = $team->home_away;
																				if ($at_or_vs == 'home') {
																					$team->moneyline = $postMeta['money_line_team_2'][0];
																				} else {
																					$team->moneyline = $postMeta['money_line_team_1'][0];
																				}
																			}
																		}
																	}
																}

																wp_reset_postdata();

																/* Show scores from DB updated by admin */

														?>

																<option class="option-select-team option-select-team-<?php echo $team->term_taxonomy_id; ?>" value="<?php echo $team->term_taxonomy_id; ?>_<?php echo number_format($team->moneyline); ?>" data-gameid="<?php echo $game_id; ?>" name="<?php echo $team->name; ?>" data-spread="<?php echo number_format($team->moneyline); ?>"><?php echo  $team->rotation_number . " - " . $team->name; ?> <?php if ($team->moneyline > 0) {
																																																																																																																	echo '+';
																																																																																																																}
																																																																																																																echo number_format($team->moneyline); ?></option>

														<?php
															}
														}
														?>

													</select>

													<select class="select-team select-team-overunder" name="wager-team-vs-overunder">

														<!--
												<option class="option-select-team option-select-team-<?php echo $team->term_taxonomy_id; ?>" value="<?php echo $team->term_taxonomy_id; ?>_<?php echo $team->current_overunder; ?>" name="TOR vs NYY - Under 160.5" data-spread="<?php echo $team->current_overunder; ?>"><?php echo $team->name; ?> <?php if ($team->current_overunder > 0) {
																																																																																							echo '+';
																																																																																						}
																																																																																						echo $team->current_overunder; ?></option>
												-->

													</select>

												</div>

											</div>

											<input id="wager-game-input" name="wager-game" value="Team vs Team" type="hidden">

										</div>

										<div class="wager-amount-tab">

											<!-- <div class="wager-amount-label"><span class="wager-step"></span>Enter Wager ($): </div> -->

											<input type="hidden" class="wager-amount" type="text" value="0" name="wager-amount">

											<input class="submit-bet" type="submit" value="Submit Bet" data-format="<?php echo strtolower($contest_type); ?>">

											<div class="total-winnings-proj">TO WIN <strong>$<span class="total-winnings-val">0</span></strong></div>
											<div class="total-winnings-msg hide"></div>

										</div>

										<input name="wager-type" value="Team vs Team" type="hidden">

										<?php if ($league_name != 'NFL!') { ?>

											<input name="wager-contest" value="<?php echo $post->ID; ?>" type="hidden">
											<input name="wager-contest-date" value="<?php echo $contest_date_2; ?>" type="hidden">

										<?php } ?>

										<input name="wager-league" value="<?php echo $league_id; ?>" id="wager-league" type="hidden">



									</form>

								</div>

							</div>
							<div id="confirmationPopupBox" class="confirmation-popup-box" style="display: none;">
								<div class="confirmation-popup-box-header">
									<h4 class="confirmation-popup-box-title">Confirmation</h4>
									<button type="button" class="confirmation-popup-box-close">
										<span aria-hidden="true">&#x2715;</span>
									</button>
								</div>
								<div class="confirmation-popup-box-body">
									<div id="popupBetAmout"></div>
									<div id="popupBetType"></div>
									<div id="popupBetDetails"></div>
								</div>
								<div class="confirmation-popup-box-footer">
									<div>
										Do you accept this bet?
									</div>
									<button id="btnYesConfirmYesNo" type="button">Yes</button>
									<button id="btnNoConfirmYesNo" type="button">No</button>
								</div>
							</div>
						<?php } ?>

						<!--div class="section-left leaderboard">
				
					<?php // get_template_part( 'includes/leaderboard-widget' ); 
					?>
				
				</div-->

						<?php get_template_part('includes/bettor-intelligence'); ?>
						<?php get_template_part('includes/score-board-stats'); ?>

						<!-- <div class="section-left recent-contests">
					
					<div class="section-header noselect">Live/Upcoming Contests</div>
					
					<?php
					$args = array(
						'post_type' => 'contest',
						'posts_per_page' => 10,
						'meta_query' => array(
							array(
								'key'     => 'contest_status',
								'value'   => array('Open', 'In Progress'),
								'compare' => 'IN',
							),
						),
						'order'				=> 'ASC',
						'orderby'			=> 'meta_value',
						'meta_key'			=> 'contest_date_sort',
						'meta_type'			=> 'DATETIME',
						'post__not_in'		=> $exclude_post,
					);

					$the_query = new WP_Query($args);

					if ($the_query->have_posts()) {
						while ($the_query->have_posts()) {

							$the_query->the_post();

							$nfl_main_contest = get_field('nfl_main_contest', $post->ID);

							if ($nfl_main_contest == '') {

								$recent_league = get_the_terms($post->ID, 'league');
								$recent_league_id = $recent_league[0]->term_id;
								$recent_league_name = $recent_league[0]->name;
								$recent_league_slug = $recent_league[0]->slug;
								$recent_league_logo = get_field('league_logo', 'league_' . $recent_league_id);

								$recent_contest_type = get_field('contest_type', $post->ID);

								$coming_soon = false;

								if ($recent_contest_type == 'Mixed') {
									$recent_contest_type_text = 'Mixed<span>vs</span>Field';
								} else if ($recent_contest_type == 'Teams') {
									$recent_contest_type_text = 'Teams<span>vs</span>Field';
								} else if ($recent_contest_type == 'Team vs Team') {
									$recent_contest_type_text = 'Team<span>vs</span>Team';
								} else if ($recent_contest_type == 'Starters') {
									$recent_contest_type_text = 'Starters';
									$coming_soon = true;
								}

								$recent_contest_date_1 = date('g:i a', get_field('contest_date', $post->ID));
								$recent_contest_date_2 = get_field('contest_date', $post->ID);
								$recent_offset = human_time_diff($recent_contest_date_2, current_time('timestamp'));

								$recent_contest_status = get_field('contest_status', $post->ID);
								$recent_force_lock_unlock = get_field('force_lockunlock', $post->ID);

								if ($recent_force_lock_unlock == 'Force Lock') {
									$recent_contest_status = 'In Progress';
									update_field('contest_status', 'In Progress', $post->ID);
								} else if ($recent_force_lock_unlock == 'Force Unlock') {
									$recent_contest_status = 'Open';
									update_field('contest_status', 'Open', $post->ID);
								} else {
									if ($recent_contest_date_2 < current_time('timestamp') && $recent_contest_status == 'Open') {
										$recent_contest_status = 'In Progress';
										update_field('contest_status', 'In Progress', $post->ID);
									}
								}

								$recent_locked = '';
								//if ($contest_date_2 < current_time( 'timestamp' )) {
								if ($recent_contest_status == 'Closed') {
									$recent_locked = 'locked';
									$recent_status_html = '<div class="contest-begins">Locked</div>';
								} else if ($recent_contest_status == 'In Progress') {
									$recent_locked = 'in-progress';
									$recent_status_html = '<div class="contest-begins">In Progress <i class="fas fa-lock"></i></div>';
								} else if ($recent_contest_status == 'Finished') {
									$recent_locked = 'finished';
									$recent_status_html = '<div class="contest-begins">Locked</div>';
								} else {
									if ($recent_force_lock_unlock == 'Force Unlock') {
										$recent_status_html = '<div class="contest-begins">In Progress <i class="fas fa-lock"></i></div>';
									} else {
										$recent_status_html = '<div class="contest-begins">Begins in <strong>' . $recent_offset . '</strong></div>';
									}
								}

								$recent_permalink = get_permalink();
								$title = str_replace('Game Lines', 'Fantasy Picks Lines', str_replace('Regular Season', '', get_field('contest_title_without_type')));

								if ($coming_soon) {
									$recent_permalink = 'javascript:void(0);';
								}

								echo '<div class="contest-game-1 contest-' . $recent_league_slug . ' ' . $recent_locked . '">' .
									'<a class="table fullwidth" href="' . $recent_permalink . '">' .
									'<div class="contest-left contest-logo table-cell middle">' .
									'<img src="' . $recent_league_logo . '" alt="' . $recent_league_name . '" />' .
									'<div class="contest-type">' . $recent_contest_type_text . '</div>' .
									'</div>' .
									'<div class="contest-right contest-details table-cell middle">' .
									'<div class="contest-title transition">' . $title . '</div>';

								if ($recent_league_name != 'PGA') {
									echo '<div class="contest-date">' . $recent_contest_date_1 . ' ET</div>';
								}

								echo $recent_status_html .
									'</div>' .
									'</a>' .
									'</div>';
							}
						}
					}
					wp_reset_query();
					?>
				</div> -->

						<div class="user-box">

							<a class="logout-link" href="<?php echo wp_logout_url(home_url()); ?> ">Log Out</a>
							<a class="profile" href="<?php echo get_author_posts_url($current_user_id); ?>">My Profile</a>
							<a class="edit-profile" href="/edit-profile/">Edit Profile</a>
							<a href="/my-wagers/">View My Wagers</a>

						</div>

					</div>

					<div class="contest-main contest-type-<?= $league_name ?>">

						<?php
						$league = get_the_terms($post->ID, 'league');
						$league_name = $league[0]->name;

						if ($contest_status == 'Closed') {
							$proj_or_total = 'Points';
						} else if ($contest_status == 'In Progress') {
							$proj_or_total = 'Points';
						} else {
							$proj_or_total = 'Proj.';
						}

						if ($league_name == 'NFL!' && $contest_type == 'Team vs Team') {

							//get child contest IDs

							$contestIDs = array();

							$args = array(
								'post_type' => 'contest',
								'posts_per_page' => -1,
								'meta_key' => 'nfl_main_contest',
								'meta_value' => $contest_id,
							);

							$the_query3 = new WP_Query($args);
							if ($the_query3->have_posts()) {
								while ($the_query3->have_posts()) {
									$the_query3->the_post();

									$contestIDs[] = $post->ID;
								}
							}
							wp_reset_query();

							//get all wagers from child contests

							$args = array(
								'post_type' => 'wager',
								'posts_per_page' => -1,
								'author' => get_current_user_id(),
								'meta_key' => 'wager_contest',
								'meta_value' => $contestIDs,
							);
						} else {

							$args = array(
								'post_type' => 'wager',
								'posts_per_page' => -1,
								'author' => get_current_user_id(),
								'meta_key' => 'wager_contest',
								'meta_value' => $contest_id,
							);
						}

						$the_query2 = new WP_Query($args);

						$wager_count = 0;
						$wager_list_html = '';
						$s = 's';

						if ($the_query2->have_posts()) {

							while ($the_query2->have_posts()) {

								$the_query2->the_post();

								$wager_type = get_field('wager_type');
								$winners = array();

								if ($wager_type == 'win' || $wager_type == 'place' || $wager_type == 'show') {

									$wager_list_team_1 = get_field('wager_winner_1_name');
									$wager_list_team_1_odds = get_field('winner_1_odds') . ':1';

									$wager_team_names = $wager_list_team_1 . ' (' . $wager_list_team_1_odds . ')';
									$winners[] = $wager_list_team_1;
								} else if ($wager_type == 'Pick 2 Box' || $wager_type == 'Pick 2') {

									$wager_list_team_1 = get_field('wager_winner_1_name');
									$wager_list_team_1_odds = get_field('winner_1_odds') . ':1';
									$wager_list_team_2 = get_field('wager_winner_2_name');
									$wager_list_team_2_odds = get_field('winner_2_odds') . ':1';

									$wager_team_names = $wager_list_team_1 . ' (' . $wager_list_team_1_odds . '), ' . $wager_list_team_2 . ' (' . $wager_list_team_2_odds . ')';
									$winners[] = $wager_list_team_1;
									$winners[] = $wager_list_team_2;
								} else if ($wager_type == 'Pick 3 Box' || $wager_type == 'Pick 3') {

									$wager_list_team_1 = get_field('wager_winner_1_name');
									$wager_list_team_1_odds = get_field('winner_1_odds') . ':1';
									$wager_list_team_2 = get_field('wager_winner_2_name');
									$wager_list_team_2_odds = get_field('winner_2_odds') . ':1';
									$wager_list_team_3 = get_field('wager_winner_3_name');
									$wager_list_team_3_odds = get_field('winner_3_odds') . ':1';

									$wager_team_names = $wager_list_team_1 . ' (' . $wager_list_team_1_odds . '), ' . $wager_list_team_2 . ' (' . $wager_list_team_2_odds . '), ' . $wager_list_team_3 . ' (' . $wager_list_team_3_odds . ')';
									$winners[] = $wager_list_team_1;
									$winners[] = $wager_list_team_2;
									$winners[] = $wager_list_team_3;
								} else if ($wager_type == 'Pick 4 Box' || $wager_type == 'Pick 4') {

									$wager_list_team_1 = get_field('wager_winner_1_name');
									$wager_list_team_1_odds = get_field('winner_1_odds') . ':1';
									$wager_list_team_2 = get_field('wager_winner_2_name');
									$wager_list_team_2_odds = get_field('winner_2_odds') . ':1';
									$wager_list_team_3 = get_field('wager_winner_3_name');
									$wager_list_team_3_odds = get_field('winner_3_odds') . ':1';
									$wager_list_team_4 = get_field('wager_winner_4_name');
									$wager_list_team_4_odds = get_field('winner_4_odds') . ':1';

									$wager_team_names = $wager_list_team_1 . ' (' . $wager_list_team_1_odds . '), ' . $wager_list_team_2 . ' (' . $wager_list_team_2_odds . '), ' . $wager_list_team_3 . ' (' . $wager_list_team_3_odds . '), ' . $wager_list_team_4 . ' (' . $wager_list_team_4_odds . ')';
									$winners[] = $wager_list_team_1;
									$winners[] = $wager_list_team_2;
									$winners[] = $wager_list_team_3;
									$winners[] = $wager_list_team_4;
								} else if ($wager_type == 'Pick 6') {

									$wager_list_team_1 = get_field('wager_winner_1_name');
									$wager_list_team_1_odds = get_field('winner_1_odds') . ':1';
									$wager_list_team_2 = get_field('wager_winner_2_name');
									$wager_list_team_2_odds = get_field('winner_2_odds') . ':1';
									$wager_list_team_3 = get_field('wager_winner_3_name');
									$wager_list_team_3_odds = get_field('winner_3_odds') . ':1';
									$wager_list_team_4 = get_field('wager_winner_4_name');
									$wager_list_team_4_odds = get_field('winner_4_odds') . ':1';
									$wager_list_team_5 = get_field('wager_winner_5_name');
									$wager_list_team_5_odds = get_field('winner_5_odds') . ':1';
									$wager_list_team_6 = get_field('wager_winner_6_name');
									$wager_list_team_6_odds = get_field('winner_6_odds') . ':1';

									$wager_team_names = $wager_list_team_1 . ' (' . $wager_list_team_1_odds . '), ' . $wager_list_team_2 . ' (' . $wager_list_team_2_odds . '), ' . $wager_list_team_3 . ' (' . $wager_list_team_3_odds . '), ' . $wager_list_team_4 . ' (' . $wager_list_team_4_odds . '), ' . $wager_list_team_5 . ' (' . $wager_list_team_5_odds . '), ' . $wager_list_team_6 . ' (' . $wager_list_team_6_odds . ')';

									$winners[] = $wager_list_team_1;
									$winners[] = $wager_list_team_2;
									$winners[] = $wager_list_team_3;
									$winners[] = $wager_list_team_4;
									$winners[] = $wager_list_team_5;
									$winners[] = $wager_list_team_6;
								} else if ($wager_type == 'Spread') {

									$wager_list_team_1 = get_field('wager_winner_1_name');
									$wager_list_team_1_odds = get_field('point_spread');

									if ($wager_list_team_1_odds > 0) {
										$wager_list_team_1_odds = '+' . $wager_list_team_1_odds;
									}

									$wager_team_names = $wager_list_team_1 . ' (' . $wager_list_team_1_odds . ') - Spread';
								} else if ($wager_type == 'Over/Under') {

									$wager_list_team_1 = get_field('wager_winner_1_name');
									$wager_list_team_1_odds = get_field('point_spread');

									if ($wager_list_team_1_odds > 0) {
										$wager_list_team_1_odds = '+' . $wager_list_team_1_odds;
									}

									$wager_team_names = $wager_list_team_1;
								} else if ($wager_type == 'Moneyline') {
									$wager_list_team_1 = get_field('wager_winner_1_name');
									$wager_list_team_1_odds = get_field('wager_moneyline');
									$wager_team_names = $wager_list_team_1 . ' (' . $moneyline_plus_minus . $wager_list_team_1_odds . ') - Moneyline';
									$league = get_the_terms($post->ID, 'league');
									$league_id = $league[0]->term_id;
									$league_name = $league[0]->name;

									$money_team_abbrev = get_field('wager_winner_1_name', $post->ID);

									$args = array(
										'hide_empty' => false,
										'meta_query' => array(
											array(
												'key'       => 'team_abbreviation',
												'value'     => $money_team_abbrev,
												'compare'   => '='
											),
										),
										'taxonomy'  => 'team',
									);

									$terms = get_terms($args);

									foreach ($terms as $team) {

										$league_parent_id = $team->parent;
										$league_parent = get_term_by('term_id', $league_parent_id, 'team');
										$league_parent_name = $league_parent->name;

										if ($league_parent_name == $league_name) {
											$moneyline = get_field('wager_moneyline', $post->ID);
											$money_team_abbrev = get_field('wager_winner_1_name', $post->ID);
											if ($moneyline > 0) {
												$moneyline_plus_minus = '+';
											} else {
												$moneyline_plus_minus = '-';
											}

											$wager_team_names = $money_team_abbrev . ' (' . $moneyline_plus_minus . $moneyline . ') - Moneyline';
										}
									}
								}

								$wager_amount = get_field('wager_amount');
								$wager_list_winnings = get_field('potential_winnings');
								$wager_result = get_field('wager_result');
								//get data rotation of created bets
								$data_rotation = get_field('wager_rotation');
								if ($wager_result == 'Win') {
									$wager_list_result = '&nbsp;&nbsp;<i class="fas fa-check-circle"></i>';
									$wager_list_class = 'win';
								} else if ($wager_result == 'Loss') {
									$wager_list_result = '&nbsp;&nbsp;<i class="fas fa-times-circle"></i>';
									$wager_list_class = 'loss';
								} else if ($wager_result == 'Push') {
									$wager_list_result = '&nbsp;&nbsp;<span>PUSH</span>';
									$wager_list_class = 'push';
								} else {
									$wager_list_result = '';
									$wager_list_class = '';
								}

								if ($wager_type == 'Spread') {
									$wager_list_html = $wager_list_html . '<div class="wager-list-item ' . $wager_list_class . '" data-team="' . htmlspecialchars(json_encode($winners), ENT_QUOTES, 'UTF-8') . '" data-type="' . $wager_type . '">' . $data_rotation . ' - ' . $wager_team_names . '</div>';
								} else if ($wager_type == 'Over/Under') {
									$wager_list_html = $wager_list_html . '<div class="wager-list-item ' . $wager_list_class . '" data-team="' . htmlspecialchars(json_encode($winners), ENT_QUOTES, 'UTF-8') . '" data-type="' . $wager_type . '">' . $data_rotation . ' - ' . $wager_team_names . '</div>';
								} else if ($wager_type == 'Moneyline') {
									$wager_list_html = $wager_list_html . '<div class="wager-list-item ' . $wager_list_class . '" data-team="' . htmlspecialchars(json_encode($winners), ENT_QUOTES, 'UTF-8') . '" data-type="' . $wager_type . '">' . $data_rotation . ' - ' . $wager_team_names . '</div>';
								} else {
									$wager_list_html = $wager_list_html . '<div class="wager-list-item ' . $wager_list_class . '" data-team="' . htmlspecialchars(json_encode($winners), ENT_QUOTES, 'UTF-8') . '" data-type="' . $wager_type . '">' . strtoupper(str_replace('box', 'Box', $wager_type)) . ' - ' . $wager_team_names . ' - Bet $' . number_format($wager_amount, 2) . ' to Win $' . $wager_list_winnings . ' ' . $wager_list_result . '</div>';
								}


								$wager_count++;
							}

							if ($wager_count == 1) {
								$s = '';
							} else {
								$s = 's';
							}

							if ($contest_status == 'Closed') {
								$have = '';
							} else {
								$have = 'have ';
							}

							$showAll = '<a class="show-all-wagers" href="javascript:void(0);">Show <i class="fas fa-caret-down"></i></a>';
						} else {

							if ($contest_status == 'Closed' || $contest_status == 'In Progress') {
								$have = '';
							} else {
								$have = 'have ';
							}

							$showAll = '';
						}
						wp_reset_postdata();

						if ($wager_count > 0) {

							echo '<div class="your-wagers noselect"><i class="fas fa-angle-right"></i> &nbsp;&nbsp;You ' . $have . 'placed ' . $wager_count . ' wager' . $s . ' on this contest. ' . $showAll . ' <div class="wager-list">' . $wager_list_html . '</div></div>';
						} else {

							echo '<div class="your-wagers noselect"><i class="fas fa-angle-right"></i> &nbsp;&nbsp;You ' . $have . 'placed ' . $wager_count . ' wager' . $s . ' on this contest. ' . $showAll . '</div>';
						}



						if ($contest_status == 'Open') {

							if ($contest_type == 'Team vs Team') {

								if ($league_name != 'NFL!') {
									echo '<h2 class="section-header projections-header noselect">Fantasy Picks Lines</h2>';
								}
							} else {

								echo '<h2 class="section-header projections-header noselect">Fantasy Point Projections</h2>';
							}
						} else if ($contest_status == 'In Progress') {

							if ($league_name == 'NFL!') {
							} else {

								echo '<h2 class="section-header projections-header noselect">Live Scores <div class="refresh-live" data-league="' . $league_name . '"><span class="refresh no-animate"><i class="fas fa-sync"></i></span><span class="refresh animate" style="display:none"><i class="fas fa-sync fa-spin"></i></span></div></h2>';
							}
						} else if ($contest_status == 'Closed' && $league_name != 'NFL!') {
							echo '<h2 class="section-header projections-header noselect">Fantasy Results</h2>';
						}

						if ($contest_type == 'Teams') {

							/*
					$sort = array();
					foreach ($contest_data as $key => $part) {
						$sort[$key] = strtotime($part->game_start);
					}
					array_multisort($sort, SORT_ASC, $contest_data);
					*/

							//Build output from team data

							$odds_count = 2;
							$game_count = 0;

							foreach ($contest_data as $team) {

								if ($league_name == 'mlb') {


									$pitchers = $team->players->pitchers;
									$catchers = $team->players->catchers;
									$outfielders = $team->players->outfielders;
									$infielders = $team->players->infielders;

									$pitchers_html = '<div class="position-html pitchers-html pitchers-html-' . $game_count . '">';
									$pitcher_count = 0;
									foreach ($pitchers as $pitcher) {
										if ($pitcher->total_points != 0) {
											if ($contest_status == 'Open') {
												$pitchers_html .= '<div class="player-position">' . trimName($pitcher->name) . ' (<strong>' . $pitcher->total_points . ': </strong>' . $pitcher->earned_runs . ' ER, ' . $pitcher->strikeouts . ' K, ' . $pitcher->pitching_walks . ' BB)</div>';
											} else {
												$pitchers_html .= '<div class="player-position">' . trimName($pitcher->name) . ' (<strong>' . $pitcher->total_points . ': </strong>' . number_format($pitcher->innings_pitched, 2) . ' IP, ' . $pitcher->earned_runs . ' ER, ' . $pitcher->strikeouts . ' K, ' . $pitcher->pitching_walks . ' BB)</div>';
											}

											$pitcher_count++;
										}
									}
									if ($pitcher_count == 0) {
										$pitchers_html .= '<div class="player-position empty"><strong style="inline-block;">(SP not yet announced)</strong></div>';
									}
									$pitchers_html .= '</div>';

									$catchers_html = '<div class="position-html catchers-html catchers-html-' . $game_count . '">';
									foreach ($catchers as $catcher) {
										if ($catcher->total_points != 0) {
											$catchers_html .= '<div class="player-position">' . trimName($catcher->name) . ' (<strong>' . $catcher->total_points . ': </strong>' . ($catcher->singles + $catcher->doubles + $catcher->triples + $catcher->homeruns) . ' H, ' . $catcher->homeruns . ' HR, ' . $catcher->runs . ' R, ' . $catcher->rbis . ' RBI)</div>';
										}
									}
									$catchers_html .= '</div>';

									$outfielders_html = '<div class="position-html outfielders-html outfielders-html-' . $game_count . '">';
									foreach ($outfielders as $outfielder) {
										if ($outfielder->total_points != 0) {
											$outfielders_html .= '<div class="player-position">' . trimName($outfielder->name) . ' (<strong>' . $outfielder->total_points . ': </strong>' . ($outfielder->singles + $outfielder->doubles + $outfielder->triples + $outfielder->homeruns) . ' H, ' . $outfielder->homeruns . ' HR, ' . $outfielder->runs . ' R, ' . $outfielder->rbis . ' RBI)</div>';
										}
									}
									$outfielders_html .= '</div>';

									$infielders_html = '<div class="position-html infielders-html infielders-html-' . $game_count . '">';
									foreach ($infielders as $infielder) {
										if ($infielder->total_points != 0) {
											$infielders_html .= '<div class="player-position">' . trimName($infielder->name) . ' (<strong>' . $infielder->total_points . ': </strong>' . ($infielder->singles + $infielder->doubles + $infielder->triples + $infielder->homeruns) . ' H, ' . $infielder->homeruns . ' HR, ' . $infielder->runs . ' R, ' . $infielder->rbis . ' RBI)</div>';
										}
									}
									$infielders_html .= '</div>';


									$positions_html = '<div class="team-position position-mlb-pitchers points-' . number_format($team->total_points_pitchers, 2) . '" data-teamexpand="contest-team-wrap-' . $game_count . '" data-expand="pitchers-html-' . $game_count . '"><span>All Pitchers: ' . number_format($team->total_points_pitchers, 2) . ' ' . $proj_or_total . '</span> ' . $pitchers_html . '</div><div class="team-position position-mlb-catchers points-' . number_format($team->total_points_catchers, 2) . '" data-teamexpand="contest-team-wrap-' . $game_count . '" data-expand="catchers-html-' . $game_count . '"><span>All Catchers: ' . number_format($team->total_points_catchers, 2) . ' ' . $proj_or_total . '</span> ' . $catchers_html . '</div><div class="team-position position-mlb-infielders points-' . number_format($team->total_points_infielders, 2) . '" data-teamexpand="contest-team-wrap-' . $game_count . '" data-expand="infielders-html-' . $game_count . '"><span>All Infielders: ' . number_format($team->total_points_infielders, 2) . ' ' . $proj_or_total . '</span> ' . $infielders_html . '</div><div class="team-position position-mlb-outfielders points-' . number_format($team->total_points_outfielders, 2) . '" data-teamexpand="contest-team-wrap-' . $game_count . '" data-expand="outfielders-html-' . $game_count . '"><span>All Outfielders: ' . number_format($team->total_points_outfielders, 2) . ' ' . $proj_or_total . '</span> ' . $outfielders_html . '</div>';

									$at_or_vs = $team->home_away;

									if ($at_or_vs == 'home') {
										$at_vs = 'v.';
									} else if ($at_or_vs == 'away') {
										$at_vs = '@';
									}

									$total_team_points = $team->total_points_pitchers + $team->total_points_catchers + $team->total_points_infielders + $team->total_points_outfielders;

									$total_points = '<div class="total-pts">Total: ' . $total_team_points . '</div>';

									if ($contest_status == 'Closed' || $contest_status == 'In Progress') {
										$odds_count = $team->odds_to_1;
									} else {
										$odds_count = $odds_count;
									}

									echo '<div class="contest-team" data-name="' . $team->name . ' (' . $odds_count . ':1' . ')">' .
										'<div class="contest-team-wrap contest-team-wrap-' . $game_count . '">';

									if (($game_count < 6 && $contest_status == 'Closed') || ($game_count < 6 && $contest_status == 'In Progress')) {
										echo '<div class="team-header noselect" data-team="' . $team->name . '">' . $team->name . ' <span>(' . $odds_count . ':1' . ')</span><div class="at-vs">' . $at_vs . ' ' . $team->opponent_abbrev . '<span>(' . date('g:ia', strtotime($team->game_start)) . ' ET)</span></div></div>';
									} else {
										echo '<div class="team-header noselect" data-team="' . $team->name . '"">' . $team->name . ' <span>(' . $odds_count . ':1' . ')</span><div class="at-vs">' . $at_vs . ' ' . $team->opponent_abbrev . '<span>(' . date('g:ia', strtotime($team->game_start)) . ' ET)</span></div></div>';
									}

									echo '<div class="contest-positions">' . $positions_html . $total_points . '</div>' .
										'</div>' .
										'</div>';
								} else if ($league_name == 'NFL!') {

									$QBs = $team->players->QB;
									$RBs = $team->players->RB;
									$WRs = $team->players->WR;
									$TEs = $team->players->TE;
									$DEF = $team->players->D;

									$QB_html = '<div class="position-html qb-html qb-html-' . $game_count . '">';

									foreach ($QBs as $QB) {
										if ($QB->total_points != 0) {
											$QB_html .= '<div class="player-position">' . trimName($QB->name) . ' (<strong>' . $QB->total_points . '</strong>)</div>';
										}
									}
									$QB_html .= '</div>';

									$RB_html = '<div class="position-html rb-html rb-html-' . $game_count . '">';
									foreach ($RBs as $RB) {
										if ($RB->total_points != 0) {
											$RB_html .= '<div class="player-position">' . trimName($RB->name) . ' (<strong>' . $RB->total_points . '</strong>)</div>';
										}
									}
									$RB_html .= '</div>';

									$WR_html = '<div class="position-html wr-html wr-html-' . $game_count . '">';
									foreach ($WRs as $WR) {
										if ($WR->total_points != 0) {
											$WR_html .= '<div class="player-position">' . trimName($WR->name) . ' (<strong>' . $WR->total_points . '</strong>)</div>';
										}
									}
									$WR_html .= '</div>';

									$TE_html = '<div class="position-html te-html te-html-' . $game_count . '">';
									foreach ($TEs as $TE) {
										if ($TE->total_points != 0) {
											$TE_html .= '<div class="player-position">' . trimName($TE->name) . ' (<strong>' . $TE->total_points . '</strong>)</div>';
										}
									}
									$TE_html .= '</div>';

									$DEF_html = '<div class="position-html d-html d-html-' . $game_count . '">';
									foreach ($DEF as $D) {
										if ($D->total_points != 0) {
											$DEF_html .= '<div class="player-position">' . $D->name . ' (<strong>' . $D->total_points . '</strong>)</div>';
										}
									}
									$DEF_html .= '</div>';


									$positions_html = '<div class="team-position position-nfl-qb points-' . number_format($team->total_points_QB, 2) . '" data-teamexpand="contest-team-wrap-' . $game_count . '" data-expand="qb-html-' . $game_count . '"><span>All QB: ' . number_format($team->total_points_QB, 2) . ' ' . $proj_or_total . '</span> ' . $QB_html . '</div><div class="team-position position-nfl-rb points-' . number_format($team->total_points_RB, 2) . '" data-teamexpand="contest-team-wrap-' . $game_count . '" data-expand="rb-html-' . $game_count . '"><span>All RB: ' . number_format($team->total_points_RB, 2) . ' ' . $proj_or_total . '</span> ' . $RB_html . '</div><div class="team-position position-nfl-wr points-' . number_format($team->total_points_WR, 2) . '" data-teamexpand="contest-team-wrap-' . $game_count . '" data-expand="wr-html-' . $game_count . '"><span>All WR: ' . number_format($team->total_points_WR, 2) . ' ' . $proj_or_total . '</span> ' . $WR_html . '</div><div class="team-position position-nfl-te points-' . number_format($team->total_points_TE, 2) . '" data-teamexpand="contest-team-wrap-' . $game_count . '" data-expand="te-html-' . $game_count . '"><span>All TE: ' . number_format($team->total_points_TE, 2) . ' ' . $proj_or_total . '</span> ' . $TE_html . '</div><div class="team-position position-nfl-def points-' . number_format($team->total_points_D, 2) . '"><span>D/ST: ' . number_format($team->total_points_D, 2) . ' ' . $proj_or_total . '</span> ' . $DEF_html . '</div>';

									$at_or_vs = $team->home_away;

									if ($at_or_vs == 'home') {
										$at_vs = 'v.';
									} else if ($at_or_vs == 'away') {
										$at_vs = '@';
									}

									$total_team_points = $team->total_points_QB + $team->total_points_RB + $team->total_points_WR + $team->total_points_TE + $team->total_points_D;

									$total_points = '<div class="total-pts">Total: ' . $total_team_points . '</div>';

									if ($contest_status == 'Closed' || $contest_status == 'In Progress') {
										$odds_count = $team->odds_to_1;
									} else {
										$odds_count = $odds_count;
									}

									echo '<div class="contest-team" data-name="' . $team->name . ' (' . $odds_count . ':1' . ')">' .
										'<div class="contest-team-wrap contest-team-wrap-' . $game_count . '">' .
										'<div class="team-header noselect" data-team="' . $team->name . '">' . $team->name . ' <span>(' . $odds_count . ':1' . ')</span><div class="at-vs">' . $at_vs . ' ' . $team->opponent_abbrev . '<span>(' . date('g:ia', strtotime($team->game_start)) . ' ET)</span></div></div>' .
										'<div class="contest-positions">' . $positions_html . $total_points . '</div>' .
										'</div>' .
										'</div>';
								}

								$odds_count++;
								$game_count++;
							}
						} else if ($contest_type == 'Mixed') {

							//Build output from mixed team data

							$odds_count = 2;
							$team_count = 1;

							foreach ($contest_data as $team) {

								if ($league_name == 'mlb') {

									if ($team_count < 26) {

										$count = 0;
										$players = array();
										$points = array();

										$team_players = array();

										$team_name = $team->team_name;
										$total_points = $team->total_points;
										$pitcher = $team->pitcher;
										$catcher = $team->catcher;
										$outfielder_1 = $team->outfielders[0];
										$outfielder_2 = $team->outfielders[1];
										$infielder_1 = $team->infielders[0];
										$infielder_2 = $team->infielders[1];

										if ($infielder_1->home_away == 'home') {
											$player_0_at_or_vs = ' v. ';
										} else {
											$player_0_at_or_vs = ' @ ';
										}

										if ($outfielder_1->home_away == 'home') {
											$player_1_at_or_vs = ' v. ';
										} else {
											$player_1_at_or_vs = ' @ ';
										}

										if ($infielder_2->home_away == 'home') {
											$player_2_at_or_vs = ' v. ';
										} else {
											$player_2_at_or_vs = ' @ ';
										}

										if ($outfielder_2->home_away == 'home') {
											$player_3_at_or_vs = ' v. ';
										} else {
											$player_3_at_or_vs = ' @ ';
										}

										if ($pitcher->home_away == 'home') {
											$player_4_at_or_vs = ' v. ';
										} else {
											$player_4_at_or_vs = ' @ ';
										}

										if ($catcher->home_away == 'home') {
											$player_5_at_or_vs = ' v. ';
										} else {
											$player_5_at_or_vs = ' @ ';
										}

										$player_0_name = trimName($infielder_1->name) . $player_0_at_or_vs . $infielder_1->opponent_abbrev;
										$player_0_points = $infielder_1->total_points;

										$player_1_name = trimName($outfielder_1->name) . $player_1_at_or_vs . $outfielder_1->opponent_abbrev;
										$player_1_points = $outfielder_1->total_points;

										$player_2_name = trimName($infielder_2->name) . $player_2_at_or_vs . $infielder_2->opponent_abbrev;
										$player_2_points = $infielder_2->total_points;

										$player_3_name = trimName($outfielder_2->name) . $player_3_at_or_vs . $outfielder_2->opponent_abbrev;
										$player_3_points = $outfielder_2->total_points;

										$player_4_name = trimName($pitcher->name) . $player_4_at_or_vs . $pitcher->opponent_abbrev;
										$player_4_points = $pitcher->total_points;

										$player_5_name = trimName($catcher->name) . $player_5_at_or_vs . $catcher->opponent_abbrev;
										$player_5_points = $catcher->total_points;


										$positions_html = '<div class="team-position position-mlb-infielder">IF: ' . $player_0_name . ' ' . ' - ' . $player_0_points . ' ' . $proj_or_total . '</div><div class="team-position position-mlb-infielder">IF: ' . $player_2_name . ' ' . ' - ' . $player_2_points . ' ' . $proj_or_total . '</div><div class="team-position position-mlb-outfielder">OF: ' . $player_1_name . ' ' . ' - ' . $player_1_points . ' ' . $proj_or_total . '</div><div class="team-position position-mlb-outfielder">OF: ' . $player_3_name . ' ' . ' - ' . $player_3_points . ' ' . $proj_or_total . '</div><div class="team-position position-mlb-pitcher">P: ' . $player_4_name . ' ' . ' - ' . $player_4_points . ' ' . $proj_or_total . '</div><div class="team-position position-mlb-catcher">C: ' . $player_5_name . ' - ' . $player_5_points . ' ' . $proj_or_total . '</div>';

										$team_text = $team_name . ' <span>(' . $team->odds_to_1 . ':1' . ')</span>';

										$total_points_html = '<div class="total-pts">Total: ' . $total_points . '</div>';

										echo '<div class="contest-team" data-name="' . $team_name . ' (' . $team->odds_to_1 . ':1' . ')">' .
											'<div class="contest-team-wrap">';
										if (($odds_count < 8 && $contest_status == 'Closed') || ($odds_count < 8 && $contest_status == 'In Progress')) {
											echo '<div class="team-header noselect" data-team="' . $team_name . '">' . $team_text . '</div>';
										} else {
											echo '<div class="team-header noselect" data-team="' . $team_name . '">' . $team_text . '</div>';
										}
										echo '<div class="contest-positions">' . $positions_html . $total_points_html . '</div>' .
											'</div>' .
											'</div>';
									}
								} else if ($league_name == 'PGA') {

									//if ($team_count < 26) {


									$count = 0;
									$players = array();
									$points = array();

									$team_players = array();

									$team_name = $team->team_name;
									$total_points = $team->total_points;

									$positions_html = '';

									foreach ($team as $player) {

										if ($count < 4) {

											$player_name[$count] = $player->name;
											$player_points[$count] = $player->total_points;

											$positions_html .= '<div class="team-position golfer">' . $player_name[$count] . ': ' . $player_points[$count] . ' ' . $proj_or_total . '</div>';
										}

										$count++;
									}

									$team_text = $team_name . ' <span>(' . $team->odds_to_1 . ':1' . ')</span>';

									$total_points_html = '<div class="total-pts">Total: ' . $total_points . '</div>';

									echo '<div class="contest-team" data-name="' . $team_name . ' (' . $team->odds_to_1 . ':1' . ')">' .
										'<div class="contest-team-wrap">';
									if (($odds_count < 8 && $contest_status == 'Closed') || ($odds_count < 8 && $contest_status == 'In Progress')) {
										echo '<div class="team-header noselect" data-team="' . $team_name . '">' . $team_text . '</div>';
									} else {
										echo '<div class="team-header noselect" data-team="' . $team_name . '">' . $team_text . '</div>';
									}
									echo '<div class="contest-positions">' . $positions_html . $total_points_html . '</div>' .
										'</div>' .
										'</div>';

									//}

								} else if ($league_name == 'NASCAR') {

									//if ($team_count < 26) {

									$count = 0;
									$players = array();
									$points = array();

									$team_players = array();

									$team_name = $team->team_name;
									$total_points = $team->total_points;

									$positions_html = '';

									foreach ($team as $player) {

										if ($count < 2) {

											$player_name[$count] = $player->Name;
											$player_points[$count] = $player->total_points;

											$positions_html .= '<div class="team-position driver">' . $player_name[$count] . ': ' . $player_points[$count] . ' ' . $proj_or_total . '</div>';
										}

										$count++;
									}

									$team_text = $team_name . ' <span>(' . $team->odds_to_1 . ':1' . ')</span>';

									$total_points_html = '<div class="total-pts">Total: ' . $total_points . '</div>';

									echo '<div class="contest-team" data-name="' . $team_name . ' (' . $team->odds_to_1 . ':1' . ')">' .
										'<div class="contest-team-wrap">';
									if (($odds_count < 8 && $contest_status == 'Closed') || ($odds_count < 8 && $contest_status == 'In Progress')) {
										echo '<div class="team-header noselect" data-team="' . $team_name . '">' . $team_text . '</div>';
									} else {
										echo '<div class="team-header noselect" data-team="' . $team_name . '">' . $team_text . '</div>';
									}
									echo '<div class="contest-positions">' . $positions_html . $total_points_html . '</div>' .
										'</div>' .
										'</div>';

									//}

								} else if ($league_name == 'NFL!') {

									if ($team_count < 26) {

										$count = 0;
										$players = array();
										$points = array();

										$team_players = array();

										$team_name = $team->team_name;
										$total_points = $team->total_points;

										$QB = $team->QB;
										$RB = $team->RB;
										$WR = $team->WR;
										$TE = $team->TE;
										$DEF = $team->D;

										if ($QB->home_away == 'home') {
											$player_0_at_or_vs = ' v. ';
										} else {
											$player_0_at_or_vs = ' @ ';
										}

										if ($RB->home_away == 'home') {
											$player_1_at_or_vs = ' v. ';
										} else {
											$player_1_at_or_vs = ' @ ';
										}

										if ($WR->home_away == 'home') {
											$player_2_at_or_vs = ' v. ';
										} else {
											$player_2_at_or_vs = ' @ ';
										}

										if ($TE->home_away == 'home') {
											$player_3_at_or_vs = ' v. ';
										} else {
											$player_3_at_or_vs = ' @ ';
										}

										if ($DEF->home_away == 'home') {
											$player_4_at_or_vs = ' v. ';
										} else {
											$player_4_at_or_vs = ' @ ';
										}

										$player_0_name = trimName($QB->name) . $player_0_at_or_vs . $QB->opponent_abbrev;
										$player_0_points = $QB->total_points;

										$player_1_name = trimName($RB->name) . $player_1_at_or_vs . $RB->opponent_abbrev;
										$player_1_points = $RB->total_points;

										$player_2_name = trimName($WR->name) . $player_2_at_or_vs . $WR->opponent_abbrev;
										$player_2_points = $WR->total_points;

										$player_3_name = trimName($TE->name) . $player_3_at_or_vs . $TE->opponent_abbrev;
										$player_3_points = $TE->total_points;

										$player_4_name = $DEF->name . ' (' . $player_4_at_or_vs . $DEF->opponent_abbrev . ')';
										$player_4_points = $DEF->total_points;


										$positions_html = '<div class="team-position position-nfl-qb">QB: ' . $player_0_name . ' ' . ' - ' . $player_0_points . ' ' . $proj_or_total . '</div><div class="team-position position-nfl-rb">RB: ' . $player_2_name . ' ' . ' - ' . $player_2_points . ' ' . $proj_or_total . '</div><div class="team-position position-nfl-wr">WR: ' . $player_1_name . ' ' . ' - ' . $player_1_points . ' ' . $proj_or_total . '</div><div class="team-position position-nfl-te">TE: ' . $player_3_name . ' ' . ' - ' . $player_3_points . ' ' . $proj_or_total . '</div><div class="team-position position-nfl-def">D/ST: ' . $player_4_name . ' ' . ' - ' . $player_4_points . ' ' . $proj_or_total . '</div>';

										$team_text = $team_name . ' <span>(' . $team->odds_to_1 . ':1' . ')</span>';

										$total_points_html = '<div class="total-pts">Total: ' . $total_points . '</div>';

										echo '<div class="contest-team" data-name="' . $team_name . ' (' . $team->odds_to_1 . ':1' . ')">' .
											'<div class="contest-team-wrap">';
										if (($odds_count < 8 && $contest_status == 'Closed') || ($odds_count < 8 && $contest_status == 'In Progress')) {
											echo '<div class="team-header noselect" data-team="' . $team_name . '">' . $team_text . '</div>';
										} else {
											echo '<div class="team-header noselect" data-team="' . $team_name . '">' . $team_text . '</div>';
										}
										echo '<div class="contest-positions">' . $positions_html . $total_points_html . '</div>' .
											'</div>' .
											'</div>';
									}
								}

								$odds_count++;
								$team_count++;
							}
						} else if ($contest_type == 'Team vs Team') {

							$continue = true;




							foreach ($contest_data as $game) {

								if (!isset($game->game_id) && $continue == true) {

									$continue = false;
									echo '<div style="font-size:13px;">This contest is no longer available.</div>';
									break;
								}
							}

							if ($continue != false) {

								if ($league_name == 'NFL') {
									//Sort games by time

									$sort = array();

									foreach ($contest_data as $key => $part) {
										$sort[$key] = strtotime($part->game_start);
									}
									array_multisort($sort, SORT_ASC, $contest_data);

									$first_game_date = $sort[0];
									$last_game_date = $sort[count($sort) - 1];

									if ($contest_status === "In Progress") {
										if ((strtotime(date("d-m-Y g:ia", current_time('timestamp'))) - strtotime(date("Y-m-d g:ia", $last_game_date + 10800))) > 0) {
											$the_contest_startover = "Games are finished";
										} else {
											$the_contest_startover = "Games are in progress";
										}
									} else if ($contest_status === "Finished") {
										$the_contest_startover = "Games are settled";
									} else {
										$the_contest_startover = "";
									}
									$the_contest_startover = "";
									//Build output from team data

									$game_count = 1;
									if ($the_contest_startover !== "") {
										echo "<h3 class='section-header'>$the_contest_startover</h3>";
									}

									//game dates checker
									$previous_game_date = 0;
									$current_game_date = 0;
									$live_games_with_projection = 0;

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


									foreach ($all_contest_data as $game) {

										if ($live_games_with_projection == 1) :
											foreach ($contest_data as $c_data) :
												if ($game->game_id == $c_data->game_id) :
													$game = $c_data;
													break;
												endif;
											endforeach;
										endif;

										$team_count = 0;

										if ($contest_status == 'In Progress') {
											$projected_or_live = 'Live Fantasy Points: ';
										} else if ($contest_status == 'Closed' || $contest_status == 'Finished') {
											$projected_or_live = 'Final: ';
										} else {
											$projected_or_live = 'Projected Fantasy Points: ';
										}


										// Show scores from DB updated by admin 

										$contest_post_id = $post->ID;
										$game_id = $game->game_id;

										$args = array(
											'post_type'  => 'gamedeatils',
											'meta_query' => array(
												array(
													'key'     => 'contest_id',
													'value'   => $contest_post_id
												),
												array(
													'key' => 'game_id',
													'value'   => $game_id
												),
											),
										);

										$the_query = new WP_Query($args);
										$postData = [];
										if ($the_query->found_posts > 0) {
											if ($the_query->have_posts()) {
												while ($the_query->have_posts()) {
													$the_query->the_post();
													$postData = $post;
												}
											}
										}

										wp_reset_query();

										if (!empty($results)) {
											foreach ($contest_data_projection as $projection_data) {
												$current_game_id = $game->game_id;

												if ($projection_data->game_id == $current_game_id) {
													$data_rotation_1 = $projection_data->team1->rotation_number;
													$data_rotation_2 = $projection_data->team2->rotation_number;
												}
											}
										} else {

											$data_rotation_1 = 	$game->team1->rotation_number;
											$data_rotation_2 = 	$game->team2->rotation_number;
										}
										$team_1_html = '<div class="team-vs-team-wrap"><span class="team-vs-team-name">' . $data_rotation_1 . ' - ' . $game->team1->name . '<i class="fas fa-angle-down"></i></span><span class="team-vs-team-pts">(';
										$team_1_html_end_point = $game->team1->total_points;
										$team_1_team = $game->team1->name;
										$team_1_team_abbrev = $game->team1->team_abbrev;
										$team_1_term_taxonomy = $game->team1->term_taxonomy_id;
										$team_1_points = $game->team1->total_points;

										$team_2_html = '<div class="team-vs-team-wrap"><span class="team-vs-team-name">' . $data_rotation_2 . ' - ' . $game->team2->name . '<i class="fas fa-angle-down"></i></span><span class="team-vs-team-pts">(';
										$team_2_html_end_point = $game->team2->total_points;
										$team_2_team = $game->team2->name;
										$team_2_team_abbrev = $game->team2->team_abbrev;
										$team_2_term_taxonomy = $game->team2->term_taxonomy_id;
										$team_2_points = $game->team2->total_points;

										$home_abbrev = $game->team2->team_abbrev;
										$current_game_id = $game->game_id;

										//show the projection points for spread, over/under and moneyline if the games are done
										if (!empty($results)) {
											foreach ($contest_data_projection as $projection_data) {
												if ($projection_data->game_id == $current_game_id) {
													$team_1_spread = $projection_data->team1->spread;
													$team_1_moneyline = $projection_data->team1->moneyline;
													$team_2_spread = $projection_data->team2->spread;
													$team_2_moneyline = $projection_data->team2->moneyline;
													$overunder = $projection_data->team1->overunder;
												}
											}
										} else {
											$team_1_spread = $game->team1->spread;
											$team_1_moneyline = $game->team1->moneyline;
											$team_2_spread = $game->team2->spread;
											$team_2_moneyline = $game->team2->moneyline;
											$overunder = $game->team2->overunder;
										}
										//---------------------------------------------

										if (count((array)$postData) > 0) {

											$postMeta =  get_post_meta($postData->ID);

											$team_1_spread = $postMeta['point_spread_team_1'][0];
											$team_2_spread = $postMeta['point_spread_team_2'][0];

											$overunder = $postMeta['over_under'][0];

											$team_1_moneyline = $postMeta['money_line_team_1'][0];
											$moneyline_1_plus = ($team_1_moneyline > 0) ? '+' : '';
											$team_2_moneyline = $postMeta['money_line_team_2'][0];
											$moneyline_2_plus = ($team_2_moneyline > 0) ? '+' : '';

											$betting_status = $postMeta['bidding_status'][0];
										}

										$team_count++;

										$game_date = $game->game_start;

										$date_differenece = strtotime(date("d-m-Y", current_time('timestamp'))) - strtotime(date("d-m-Y", strtotime($game_date)));
										$time_differenece = ((current_time('timestamp') - 60) - strtotime(date("d-m-Y H:i:s", (strtotime($game_date))))) / 60;
										$hide_game = " ";
										if ($date_differenece < 0 || $time_differenece < 0) {
											$game_started = false;
											$projected_or_live = 'Projected Fantasy Points: ';
											if (isset($betting_status) && $betting_status == 2) {
												$contest_status_html = "in progress";
												$not_active = "not-active";
											} else {
												$contest_status_html = "open for betting";
												$not_active = "";
											}
										} else {
											$game_started = true;
											$projected_or_live = 'Live Fantasy Points: ';
											$contest_status_html = "in progress";
											$not_active = "not-active";
											$hide_game = "hide-game";
										}

										//If the contest has statred but the game isn't
										if (!$game_started && !empty($results)) {
											foreach ($contest_data_projection as $projection_data) {
												if ($projection_data->game_id == $current_game_id) {
													$team_1_html_end_point = $projection_data->team1->total_points;
													$team_2_html_end_point = $projection_data->team2->total_points;
												}
											}
										}
										//---------------------------------------------

										$team_1_spread = ($team_1_spread == "") ? 0 : $team_1_spread;
										$team_2_spread = ($team_2_spread == "") ? 0 : $team_2_spread;
										$overunder = ($overunder == "") ? 0 : $overunder;
										$team_1_moneyline = ($team_1_moneyline == "") ? 0 : $team_1_moneyline;
										$team_2_moneyline = ($team_2_moneyline == "") ? 0 : $team_2_moneyline;

										$team_1_plus = ($team_1_spread > 0) ? '+' : '';
										$team_2_plus = ($team_2_spread > 0) ? '+' : '';
										$moneyline_1_plus = ($team_1_moneyline > 0) ? '+' : '';
										$moneyline_2_plus = ($team_2_moneyline > 0) ? '+' : '';

										if ($game->is_game_over == 1 && $game_started) {
											$projected_or_live = '<strong>Final: ';
											$team_1_html_end_point .= '</strong>';
											$team_2_html_end_point .= '</strong>';
										}

										$team_1_html .= $projected_or_live . $team_1_html_end_point . ')</span></div>';
										$team_2_html .= $projected_or_live . $team_2_html_end_point . ')</span></div>';
										$current_game_date = date("m/d/Y", strtotime($game_date));
										$current_time = (current_time('timestamp') - 60);
										$current_game_time =  strtotime(date("d-m-Y H:i:s", (strtotime($game_date)))) / 60;

										if ($team_1_team != "" && $team_2_team != "") {
											if ($current_game_date != $previous_game_date) {

												echo '<h2 class="section-header ' . $hide_game . '">' . $current_game_date . ' </h2>';
												$first_games = "first-games";
											} else if ($current_time < $current_game_time) {
												$hide_game = "hide-game";
												echo '<h2 class="section-header ' . $hide_game . '">' . $current_game_date . ' </h2>';

												$first_games = "";
											}
											echo '<div class="contest-team contest-vs contest-vs-' . $game_count . ' ' . $hide_game . '" data-gameid="' . $game_id . '">' .
												'<div class="team-header team-vs-header mobile"><span class="team-vs-game-count">Game ' . str_pad($game_count, 2, '0', STR_PAD_LEFT) . ' - </span><span class="team-vs-game-time">' . date('g:ia', strtotime($game->game_start)) . '</span></div>' .
												'<div class="table fullwidth">' .
												'<div class="contest-team-vs-team-details table-cell middle">' .
												'<div class="team-header team-vs-header noselect"><span class="team-vs-game-count">Game<br><span class="game-number">' . str_pad($game_count, 2, '0', STR_PAD_LEFT) . '</span></span><span class="team-vs-game-time">' . date('g:ia', strtotime($game->game_start)) . '</span></div>' .
												'</div>' .
												'<div class="contest-team-vs-team-teams table-cell middle">' .
												'<div class="contest-team-wrap contest-team-vs-wrap noselect ' . $not_active . '">' .
												$team_1_html .
												$team_2_html .
												'</div>' .
												'</div>' .
												'<div class="contest-team-vs-team-pointspread ' . $first_games . ' contest-team-vs-team-cell table-cell middle">' .
												'<div class="contest-teamvsteam-cell team-vs-team-bet transition contest-pointspread bord contest-status-' . str_replace(' ', '-', strtolower($contest_status_html)) . '" data-name="' . $team_1_team . '" data-spread="' . $team_1_plus . $team_1_spread . '"data-rotation="' . $data_rotation_1 . '" data-select="spread" data-term-texmoney-id="' . $team_1_term_taxonomy . '" data-gameid="' . $game_id . '"><strong>' . $team_1_plus . number_format($team_1_spread, 2) . '</strong></div>' .
												'<div class="contest-teamvsteam-cell team-vs-team-bet transition contest-pointspread contest-status-' . str_replace(' ', '-', strtolower($contest_status_html)) . '" data-name="' . $team_2_team . '" data-spread="' . $team_2_plus . $team_2_spread . '"data-rotation="' . $data_rotation_2 . '" data-select="spread" data-term-texmoney-id="' . $team_2_term_taxonomy . '" data-gameid="' . $game_id . '"><strong>' . $team_2_plus . number_format($team_2_spread, 2) . '</strong></div>' .
												'</div>' .
												'<div class="contest-team-vs-team-overunder ' . $first_games . ' contest-team-vs-team-cell table-cell middle">' .
												'<div class="contest-teamvsteam-cell team-vs-team-bet transition contest-overunder bord contest-status-' . str_replace(' ', '-', strtolower($contest_status_html)) . '" data-name="' . $team_1_team . '" data-spread="' . $overunder . '"data-rotation="' . $data_rotation_1 . '" data-select="overunder" data-term-texmoney-id="' . $team_1_term_taxonomy . '" data-gameid="' . $game_id . '" data-overundertext="' . $team_1_team_abbrev . ' vs ' . $team_2_team_abbrev . ' - Over ' . $overunder . '" data-overunderval1="Over" data-overunderval2="' . $overunder . '" data-overunderval3="' . $game_id . '">O <strong>' . $overunder . '</strong></div>' .
												'<div class="contest-teamvsteam-cell team-vs-team-bet transition contest-overunder contest-status-' . str_replace(' ', '-', strtolower($contest_status_html)) . '" data-name="' . $team_1_team . '" data-spread="' . $overunder . '"data-rotation="' . $data_rotation_2 . '" data-select="overunder" data-term-texmoney-id="' . $team_2_term_taxonomy . '" data-gameid="' . $game_id . '" data-overundertext="' . $team_1_team_abbrev . ' vs ' . $team_2_team_abbrev . ' - Under ' . $overunder . '" data-overunderval1="Under" data-overunderval2="' . $overunder . '" data-overunderval3="' . $game_id . '">U <strong>' . $overunder . '</strong></div>' .
												'</div>' .
												// '<div class="contest-team-vs-team-moneyline ' . $first_games . ' contest-team-vs-team-cell table-cell middle">' .
												// '<div class="contest-teamvsteam-cell team-vs-team-bet transition contest-moneyline bord contest-status-' . str_replace(' ', '-', strtolower($contest_status_html)) . '" data-name="' . $team_1_team . '" data-spread="' . $moneyline_1_plus . number_format($team_1_moneyline) . '"data-rotation="' . $data_rotation_1 . '" data-select="moneyline" data-term-texmoney-id="' . $team_1_term_taxonomy . '" data-gameid="' . $game_id . '"><strong>' . $moneyline_1_plus . number_format($team_1_moneyline) . '</strong></div>' .
												// '<div class="contest-teamvsteam-cell team-vs-team-bet transition contest-moneyline contest-status-' . str_replace(' ', '-', strtolower($contest_status_html)) . '" data-name="' . $team_2_team . '" data-spread="' . $moneyline_2_plus . number_format($team_2_moneyline) . '"data-rotation="' . $data_rotation_2 . '" data-select="moneyline" data-term-texmoney-id="' . $team_2_term_taxonomy . '" data-gameid="' . $game_id . '"><strong>' . $moneyline_2_plus . number_format($team_2_moneyline) . '</strong></div>' .
												// '</div>' .
												'</div>' .
												'</div>';

											$game_count++;
											$previous_game_date = $current_game_date;
										}
										unset($betting_status);
									}
								} else {

									$sort = array();

									foreach ($contest_data as $key => $part) {
										$sort[$key] = strtotime($part->game_start);
									}
									array_multisort($sort, SORT_ASC, $contest_data);

									$first_game_date = $sort[0];
									$last_game_date = $sort[count($sort) - 1];

									if ($contest_status === "In Progress") {
										if ((strtotime(date("d-m-Y g:ia", current_time('timestamp'))) - strtotime(date("Y-m-d g:ia", $last_game_date + 10800))) > 0) {
											$the_contest_startover = "Games are in finished";
										} else {
											$the_contest_startover = "Games are in progress";
										}
									} else if ($contest_status === "Finished") {
										$the_contest_startover = "Games are settled";
									} else {
										$the_contest_startover = "";
									}
									$the_contest_startover = "";
									//Build output from team data

									$game_count = 1;
									if ($the_contest_startover !== "") {
										echo "<h3 class='section-header'>$the_contest_startover</h3>";
									}

									foreach ($contest_data as $game) {
										if ($contest_status == 'In Progress') {
											$projected_or_live = 'Live Fantasy Points: ';
										} else if ($contest_status == 'Closed' || $contest_status == 'Finished') {
											$projected_or_live = 'Final: ';
										} else {
											$projected_or_live = 'Projected Fantasy Points: ';
										}


										// Show scores from DB updated by admin 

										$contest_post_id = $post->ID;
										$game_id = $game->game_id;

										$args = array(
											'post_type'  => 'gamedeatils',
											'meta_query' => array(
												array(
													'key'     => 'contest_id',
													'value'   => $contest_post_id
												),
												array(
													'key' => 'game_id',
													'value'   => $game_id
												),
											),
										);

										$the_query = new WP_Query($args);
										$postData = [];
										if ($the_query->found_posts > 0) {

											if ($the_query->have_posts()) {
												while ($the_query->have_posts()) {
													$the_query->the_post();
													$postData = $post;
												}
											}
										}

										wp_reset_query();

										//adding player points to the total
										$game->team1->total_points = 0;
										$game->team2->total_points = 0;
										foreach ($game->team1->player as $score) {
											$game->team1->total_points += $score;
										}
										foreach ($game->team2->player as $score) {
											$game->team2->total_points += $score;
										}
										//display rotation number
										if (!empty($results)) {
											foreach ($contest_data_projection as $projection_data) {
												$current_game_id = $game->game_id;

												if ($projection_data->game_id == $current_game_id) {
													$data_rotation_1 = $projection_data->team1->rotation_number;
													$data_rotation_2 = $projection_data->team2->rotation_number;
												}
											}
										} else {

											$data_rotation_1 = 	$game->team1->rotation_number;
											$data_rotation_2 = 	$game->team2->rotation_number;
										}
										$team_1_html = '<div class="team-vs-team-wrap"><span class="team-vs-team-name">' . $data_rotation_1 . ' - ' . $game->team1->name . '<i class="fas fa-angle-down"></i></span><span class="team-vs-team-pts">(';
										$team_1_html_end_point = $game->team1->total_points;
										$team_1_team = $game->team1->name;
										$team_1_team_abbrev = $game->team1->team_abbrev;
										$team_1_term_taxonomy = $game->team1->term_taxonomy_id;
										$team_1_points = $game->team1->total_points;

										$team_2_html = '<div class="team-vs-team-wrap"><span class="team-vs-team-name">' . $data_rotation_2 . ' - ' . $game->team2->name . '<i class="fas fa-angle-down"></i></span><span class="team-vs-team-pts">(';
										$team_2_html_end_point = $game->team2->total_points;
										$team_2_team = $game->team2->name;
										$team_2_team_abbrev = $game->team2->team_abbrev;
										$team_2_term_taxonomy = $game->team2->term_taxonomy_id;
										$team_2_points = $game->team2->total_points;

										$home_abbrev = $game->team2->team_abbrev;
										$current_game_id = $game->game_id;

										//show the projection points for spread, over/under and moneyline if the games are done
										if (!empty($results)) {
											foreach ($contest_data_projection as $projection_data) {
												if ($projection_data->game_id == $current_game_id) {
													$team_1_spread = $projection_data->team1->spread;
													$team_1_moneyline = $projection_data->team1->moneyline;
													$team_2_spread = $projection_data->team2->spread;
													$team_2_moneyline = $projection_data->team2->moneyline;
													$overunder = $projection_data->team1->overunder;
												}
											}
										} else {
											$team_1_spread = $game->team1->spread;
											$team_1_moneyline = $game->team1->moneyline;
											$team_2_spread = $game->team2->spread;
											$team_2_moneyline = $game->team2->moneyline;
											$overunder = $game->team2->overunder;
										}
										//---------------------------------------------

										if (count((array)$postData) > 0) {

											$postMeta =  get_post_meta($postData->ID);

											$team_1_spread = $postMeta['point_spread_team_1'][0];
											$team_2_spread = $postMeta['point_spread_team_2'][0];

											$overunder = $postMeta['over_under'][0];

											$team_1_moneyline = $postMeta['money_line_team_1'][0];
											$moneyline_1_plus = ($team_1_moneyline > 0) ? '+' : '';
											$team_2_moneyline = $postMeta['money_line_team_2'][0];
											$moneyline_2_plus = ($team_2_moneyline > 0) ? '+' : '';

											$betting_status = $postMeta['bidding_status'][0];
										}

										$team_count++;

										$game_date = $game->game_start;

										$date_differenece = strtotime(date("d-m-Y", current_time('timestamp'))) - strtotime(date("d-m-Y", strtotime($game_date)));
										$time_differenece = ((current_time('timestamp') - 60) - strtotime(date("d-m-Y H:i:s", (strtotime($game_date))))) / 60;
										$hide_game = " ";
										if ($date_differenece < 0) {
											$contest_status_html = "in progress";
											$projected_or_live = 'Projected Fantasy Points: ';
											$not_active = "not-active";
											$game_started = false;
										} else {
											if ($time_differenece >= 1) {
												$contest_status_html = "in progress";
												$projected_or_live = 'Live Fantasy Points: ';
												$not_active = "not-active";
												$game_started = true;
												$hide_game = "hide-game";
											} else {
												$projected_or_live = 'Projected Fantasy Points: ';
												$game_started = false;
												if (isset($betting_status) && $betting_status == 2) {
													$contest_status_html = "in progress";
													$not_active = "not-active";
												} else {
													$contest_status_html = "open for betting";
													$not_active = "";
												}
											}
										}

										//If the contest has statred but the game isn't
										if (!$game_started && !empty($results)) {
											foreach ($contest_data_projection as $projection_data) {
												if ($projection_data->game_id == $current_game_id) {
													$team_1_html_end_point = $projection_data->team1->total_points;
													$team_2_html_end_point = $projection_data->team2->total_points;
												}
											}
										}
										//---------------------------------------------

										$team_1_spread = ($team_1_spread == "") ? 0 : $team_1_spread;
										$team_2_spread = ($team_2_spread == "") ? 0 : $team_2_spread;
										$overunder = ($overunder == "") ? 0 : $overunder;
										$team_1_moneyline = ($team_1_moneyline == "") ? 0 : $team_1_moneyline;
										$team_2_moneyline = ($team_2_moneyline == "") ? 0 : $team_2_moneyline;

										$team_1_plus = ($team_1_spread > 0) ? '+' : '';
										$team_2_plus = ($team_2_spread > 0) ? '+' : '';
										$moneyline_1_plus = ($team_1_moneyline > 0) ? '+' : '';
										$moneyline_2_plus = ($team_2_moneyline > 0) ? '+' : '';

										if ($game->is_game_over == 1 && $game_started) {
											$projected_or_live = '<strong>Final: ';
											$team_1_html_end_point .= '</strong>';
											$team_2_html_end_point .= '</strong>';
										}

										$team_1_html .= $projected_or_live . $team_1_html_end_point . ')</span></div>';
										$team_2_html .= $projected_or_live . $team_2_html_end_point . ')</span></div>';

										if ($team_1_team != "" && $team_2_team != "") {
											echo '<div class="contest-team contest-vs contest-vs-' . $game_count . ' ' . $hide_game . '" data-gameid="' . $game_id . '">' .
												'<div class="team-header team-vs-header mobile"><span class="team-vs-game-count">Game ' . str_pad($game_count, 2, '0', STR_PAD_LEFT) . ' - </span><span class="team-vs-game-time">' . date('g:ia', strtotime($game->game_start)) . '</span></div>' .
												'<div class="table fullwidth">' .
												'<div class="contest-team-vs-team-details table-cell middle">' .
												'<div class="team-header team-vs-header noselect"><span class="team-vs-game-count">Game<br><span class="game-number">' . str_pad($game_count, 2, '0', STR_PAD_LEFT) . '</span></span><span class="team-vs-game-time">' . date('g:ia', strtotime($game->game_start)) . '</span></div>' .
												'</div>' .
												'<div class="contest-team-vs-team-teams table-cell middle">' .
												'<div class="contest-team-wrap contest-team-vs-wrap noselect ' . $not_active . '">' .
												$team_1_html .
												$team_2_html .
												'</div>' .
												'</div>' .
												'<div class="contest-team-vs-team-pointspread contest-team-vs-team-cell table-cell middle">' .
												'<div class="contest-teamvsteam-cell team-vs-team-bet transition contest-pointspread bord contest-status-' . str_replace(' ', '-', strtolower($contest_status_html)) . '" data-name="' . $team_1_team . '" data-spread="' . $team_1_plus . $team_1_spread . '"data-rotation="' . $data_rotation_1 . ' " data-select="spread" data-term-texmoney-id="' . $team_1_term_taxonomy . '" data-gameid="' . $game_id . '"><strong>' . $team_1_plus . number_format($team_1_spread, 2) . '</strong></div>' .
												'<div class="contest-teamvsteam-cell team-vs-team-bet transition contest-pointspread contest-status-' . str_replace(' ', '-', strtolower($contest_status_html)) . '" data-name="' . $team_2_team . '" data-spread="' . $team_2_plus . $team_2_spread . '"data-rotation="' . $data_rotation_2 . '" data-select="spread" data-term-texmoney-id="' . $team_2_term_taxonomy . '" data-gameid="' . $game_id . '"><strong>' . $team_2_plus . number_format($team_2_spread, 2) . '</strong></div>' .
												'</div>' .
												'<div class="contest-team-vs-team-overunder contest-team-vs-team-cell table-cell middle">' .
												'<div class="contest-teamvsteam-cell team-vs-team-bet transition contest-overunder bord contest-status-' . str_replace(' ', '-', strtolower($contest_status_html)) . '" data-name="' . $team_1_team . '" data-select="overunder" data-term-texmoney-id="' . $team_1_term_taxonomy . '" data-gameid="' . $game_id . '" data-spread="' . $overunder . '"data-rotation="' . $data_rotation_1 . '" data-overundertext="' . $team_1_team_abbrev . ' vs ' . $team_2_team_abbrev . ' - Over ' . $overunder . '" data-overunderval1="Over" data-overunderval2="' . $overunder . '" data-overunderval3="' . $game_id . '">O <strong>' . $overunder . '</strong></div>' .
												'<div class="contest-teamvsteam-cell team-vs-team-bet transition contest-overunder contest-status-' . str_replace(' ', '-', strtolower($contest_status_html)) . '" data-name="' . $team_1_team . '" data-select="overunder" data-term-texmoney-id="' . $team_2_term_taxonomy . '" data-gameid="' . $game_id . '" data-spread="' . $overunder . '"data-rotation="' . $data_rotation_2 . '" data-overundertext="' . $team_1_team_abbrev . ' vs ' . $team_2_team_abbrev . ' - Under ' . $overunder . '" data-overunderval1="Under" data-overunderval2="' . $overunder . '" data-overunderval3="' . $game_id . '">U <strong>' . $overunder . '</strong></div>' .
												'</div>' .
												// '<div class="contest-team-vs-team-moneyline contest-team-vs-team-cell table-cell middle">' .
												// '<div class="contest-teamvsteam-cell team-vs-team-bet transition contest-moneyline bord contest-status-' . str_replace(' ', '-', strtolower($contest_status_html)) . '" data-name="' . $team_1_team . '" data-select="moneyline" data-term-texmoney-id="' . $team_1_term_taxonomy . '" data-gameid="' . $game_id . '" data-spread="' . number_format($team_1_moneyline) . '"data-rotation="' . $data_rotation_1 . '"><strong>' . $moneyline_1_plus . number_format($team_1_moneyline) . '</strong></div>' .
												// '<div class="contest-teamvsteam-cell team-vs-team-bet transition contest-moneyline contest-status-' . str_replace(' ', '-', strtolower($contest_status_html)) . '" data-name="' . $team_2_team . '" data-select="moneyline" data-term-texmoney-id="' . $team_2_term_taxonomy . '" data-gameid="' . $game_id . '" data-spread="' . number_format($team_2_moneyline) . '"data-rotation="' . $data_rotation_2 . '"><strong>' . $moneyline_2_plus . number_format($team_2_moneyline) . '</strong></div>' .
												// '</div>' .
												'</div>' .
												'</div>';

											$game_count++;
										}
										unset($betting_status);
									}
								}
							}
						} else if ($contest_type == 'Starters') {

							$odds_count = 2;
							$game_count = 0;

							foreach ($contest_data as $team) {

								if ($league_name == 'mlb') {

									$pitchers = $team->players->pitchers;
									$catchers = $team->players->catchers;
									$outfielders = $team->players->outfielders;
									$infielders = $team->players->infielders;


									if (isset($team->players->starters->{"SP"})) {
										$starter_sp = $team->players->starters->{"SP"};
									} else {
										$starter_sp = false;
									}

									if (isset($team->players->starters->{"C"})) {
										$starter_c = $starter_c = $team->players->starters->{"C"};
									} else {
										$starter_c = false;
									}

									if (isset($team->players->starters->{"1B"})) {
										$starter_1b = $team->players->starters->{"1B"};
									} else {
										$starter_1b = false;
									}

									if (isset($team->players->starters->{"2B"})) {
										$starter_2b = $team->players->starters->{"2B"};
									} else {
										$starter_2b = false;
									}

									if (isset($team->players->starters->{"SS"})) {
										$starter_ss = $team->players->starters->{"SS"};
									} else {
										$starter_ss = false;
									}

									if (isset($team->players->starters->{"3B"})) {
										$starter_3b = $team->players->starters->{"3B"};
									} else {
										$starter_3b = false;
									}

									if (isset($team->players->starters->{"LF"})) {
										$starter_lf = $team->players->starters->{"LF"};
									} else {
										$starter_lf = false;
									}

									if (isset($team->players->starters->{"CF"})) {
										$starter_cf = $team->players->starters->{"CF"};
									} else {
										$starter_cf = false;
									}

									if (isset($team->players->starters->{"RF"})) {
										$starter_rf = $team->players->starters->{"RF"};
									} else {
										$starter_rf = false;
									}



									$pitcher = $starter_sp;

									$pitchers_html = '<div class="starters-sp-html starters-sp-html-' . $game_count . '"><strong>SP</strong>: ';
									$pitcher_count = 0;

									if ($pitcher != false) {

										if ($pitcher->total_points != 0) {

											$pitchers_html .= trimName($pitcher->name) . ' (' . $pitcher->total_points . ')';
											$pitcher_count++;
										}

										if ($pitcher_count == 0) {
											$pitchers_html .= '(No projection available)';
										}
									} else {
										$pitchers_html .= '(No projection available)';
									}

									$pitchers_html .= '</div>';




									$catcher = $starter_c;
									$catchers_html = '<div class="starters-c-html starters-c-html-' . $game_count . '"><strong>C</strong>: ';

									if ($catcher != false) {

										if ($catcher->total_points != 0) {
											$catchers_html .= trimName($catcher->name) . ' (' . $catcher->total_points . ')';
										} else {
											$catchers_html .= '(No projection available)';
										}
									} else {
										$catchers_html .= '(No projection available)';
									}

									$catchers_html .= '</div>';




									$starter_1b_html = '<div class="starters-1b-html starters-1b-html-' . $game_count . '"><strong>1B</strong>: ';

									if ($starter_1b != false) {

										if ($starter_1b->total_points != 0) {
											$starter_1b_html .= trimName($starter_1b->name) . ' (' . $starter_1b->total_points . ')';
										} else {
											$starter_1b_html .= '(No projection available)';
										}
									} else {
										$starter_1b_html .= '(No projection available)';
									}

									$starter_1b_html .= '</div>';




									$starter_2b_html = '<div class="starters-2b-html starters-2b-html-' . $game_count . '"><strong>2B</strong>: ';

									if ($starter_2b != false) {

										if ($starter_2b->total_points != 0) {
											$starter_2b_html .= trimName($starter_2b->name) . ' (' . $starter_2b->total_points . ')';
										} else {
											$starter_2b_html .= '(No projection available)';
										}
									} else {
										$starter_2b_html .= '(No projection available)';
									}

									$starter_2b_html .= '</div>';



									$positions_html = '<div class="team-position position-mlb-pitchers points-' . number_format($team->total_points_pitchers, 2) . '">' . $pitchers_html . '</div>' .
										'<div class="team-position position-mlb-catchers points-' . number_format($team->total_points_catchers, 2) . '">' . $catchers_html . '</div>' .
										'<div class="team-position position-mlb-1b points-' . number_format($team->total_points_catchers, 2) . '">' . $starter_1b_html . '</div>';


									$at_or_vs = $team->home_away;

									if ($at_or_vs == 'home') {
										$at_vs = 'v.';
									} else if ($at_or_vs == 'away') {
										$at_vs = '@';
									}

									$total_team_points = $team->total_points_pitchers + $team->total_points_catchers + $team->total_points_infielders + $team->total_points_outfielders;

									$total_points = '<div class="total-pts">Total: ' . $total_team_points . '</div>';

									if ($contest_status == 'Closed' || $contest_status == 'In Progress') {
										$odds_count = $team->odds_to_1;
									} else {
										$odds_count = $odds_count;
									}

									echo '<div class="contest-team" data-name="' . $team->name . ' (' . $odds_count . ':1' . ')">' .
										'<div class="contest-team-wrap contest-team-wrap-' . $game_count . '">';

									if (($game_count < 6 && $contest_status == 'Closed') || ($game_count < 6 && $contest_status == 'In Progress')) {
										echo '<div class="team-header noselect" data-team="' . $team->name . '">' . $team->name . ' <span>(' . $odds_count . ':1' . ')</span><div class="at-vs">' . $at_vs . ' ' . $team->opponent_abbrev . '<span>(' . date('g:ia', strtotime($team->game_start)) . ' ET)</span></div></div>';
									} else {
										echo '<div class="team-header noselect" data-team="' . $team->name . '"">' . $team->name . ' <span>(' . $odds_count . ':1' . ')</span><div class="at-vs">' . $at_vs . ' ' . $team->opponent_abbrev . '<span>(' . date('g:ia', strtotime($team->game_start)) . ' ET)</span></div></div>';
									}

									echo '<div class="contest-positions">' . $positions_html . $total_points . '</div>' .
										'</div>' .
										'</div>';
								}

								$odds_count++;
								$game_count++;
							}
						}

						?>

					</div>

				</div>

			</div>

		</div>

<?php endwhile;
endif; ?>

<?php

//get values after page reload

if ($_GET['bet'] == '1') {

?>
	<!--Contest details popup-->
	<div id="wagerPopupBox" class="confirmation-popup-box" style="display: none;">
		<div class="confirmation-popup-box-header">
			<h4 class="confirmation-popup-box-title">Confirmation</h4>
			<button type="button" class="confirmation-popup-box-close">
				<span aria-hidden="true" onclick="closeThePopupBox()">&#x2715;</span>
			</button>
		</div>
		<div class="confirmation-popup-box-body">
			<div id="popupWagerDate"></div>
			<!-- <div id="popupBetTicket"></div> -->
			<div id="popupBetDate"></div>
			<div id="popupBetContest"></div>
			<div id="popupBetTeam"></div>
			<div id="popupWagerType"></div>
			<!-- <div id="popupWagerAmount"></div> -->
		</div>
		<div class="confirmation-popup-box-footer">
			<button type="button" id="btnOkConfirmOk" onclick="closeThePopupBox()">OK</button>
		</div>
	</div>

	<?php

	$args = array(
		'post_type' => 'wager',
		'posts_per_page' => 1,
		'author' => get_current_user_id(),
		'post_status' => 'publish',
		'meta_query' => array(
			array(
				'key' => 'wager_result',
				'value' => 'Open',
				'compare' => 'LIKE'
			)
		),
	);


	$the_query = new WP_Query($args);



	if ($the_query->have_posts()) {

		foreach ($the_query->posts as $wager_post) {
			$wager_post_id = $wager_post->ID;
			$existing_wager_type = get_field('wager_type', $wager_post_id) == "Over/Under" ? "Over/Under" : get_field('wager_type', $wager_post_id);
			$wager_amount = get_field('wager_amount', $wager_post_id);
			$potential_winning = get_field('potential_winnings', $wager_post_id);
			$contest_id = get_field('wager_contest', $wager_post_id);
			$current_date =  strtotime($wager_post->post_date);
			$date = date('M d, Y - g:ia', $current_date);
			$wager_result = get_field('wager_result', $wager_post_id);
			$wager_term = wp_get_post_terms($wager_post_id, array('league'));
			$wager_winner_1 = get_field('wager_winner_1_name', $wager_post_id);
			$wager_rotation = get_field('wager_rotation', $wager_post_id);
			$moneyline_points = get_field('wager_moneyline', $wager_post_id);
			if ($moneyline_points > 0) {
				$moneyline_points = "+" . $moneyline_points;
			}
			$point_spread_team_1 = get_field('point_spread', $wager_post_id);
			if ($point_spread_team_1 > 0) {
				$point_spread_team_1 = "+" . $point_spread_team_1;
			}

			$title = get_the_title($contest_id);
	?>
			<input id="postId" value="<?php echo $wager_post_id; ?>" type="hidden">
			<input id="date" value="<?php echo $date; ?>" type="hidden">
			<input id="contestTitle" value="<?php echo $title; ?>" type="hidden">
			<?php
			if ($existing_wager_type == "Over/Under") {
			?>
				<input id="contestTeam" value="<?php echo $wager_rotation . " - " . $wager_winner_1; ?>" type="hidden">
			<?php
			} else if ($existing_wager_type == "Spread") {
			?>
				<input id="contestTeam" value="<?php echo $wager_rotation . " - " . $wager_winner_1 . " (" . $point_spread_team_1 . ")"; ?>" type="hidden">
			<?php
			} else {
			?>
				<input id="contestTeam" value="<?php echo $wager_rotation . " - " . $wager_winner_1 . " (" . $moneyline_points . ")"; ?>" type="hidden">
			<?php
			}
			?>
			<input id="wagerType" value="<?php echo $existing_wager_type; ?>" type="hidden">
			<input id="wagerAmount" value="<?php echo "Bet <strong>$" . $wager_amount . "</strong> To win <strong>$" . $potential_winning . "</strong>"; ?>" type="hidden">
			<input id="wagerStatus" value="<?php echo $wager_result; ?>" type="hidden">
	<?php
		}
	}


	?>




	<script>
		jQuery(document).ready(function() {
			var contestId = jQuery('#postId').val();
			var contestDate = jQuery('#date').val();
			var contestTitle = jQuery('#contestTitle').val();
			var contestTeam = jQuery('#contestTeam').val();
			var wagerType = jQuery('#wagerType').val();
			var wagerAmount = jQuery('#wagerAmount').val();
			var wagerStatus = jQuery('#wagerStatus').val();

			jQuery('.confirmation-popup-box-title').html("Sports Bet - Sold");
			jQuery('#popupWagerDate').html('<div class="bet-settle-popup"><strong>' + contestDate + '</strong> </div>');
			// jQuery('#popupBetTicket').html('<div class="bet-title-popup">Bet Ticket: </div><div class="bet-value-popup">' + contestId + '</div>');
			jQuery('#popupBetContest').html('<div class="bet-title-popup">Title: </div><div class="bet-value-popup">' + contestTitle + '</div>');
			jQuery('#popupBetTeam').html('<div class="bet-title-popup">Wager Details: </div><div class="bet-value-popup">' + contestTeam + '</div>');
			jQuery('#popupWagerType').html('<div class="bet-title-popup">Wager type: </div><div class="bet-value-popup">' + wagerType + '</div>');
			// jQuery('#popupWagerAmount').html('<div class="bet-title-popup">Wager Amount: </div><div class="bet-value-popup">' + wagerAmount + '</div>');
			// jQuery('#wagerPopupBox').fadeIn();
			window.location = "<?= home_url() . '/contest/' . $post->ID ?>";

		});

		function closeThePopupBox() {
			jQuery('#wagerPopupBox').fadeOut();
			window.location = "<?= home_url() . '/contest/' . $post->ID ?>";
		}
	</script>

<?php
}
?>







<script>
	//hide date bar from nfl lobby games if game is in progress
	if (jQuery('.section-header').hasClass('hide-game')) {
		jQuery(".hide-game").css("display", "none");
	}



	//
	jQuery(document).ready(function() {

		//add active on the wager button when clicked
		jQuery('.team-vs-team-bet').on('click', function() {
			// if(!jQuery('.team-vs-team-bet').hasClass('active')){

			jQuery('.team-vs-team-bet').removeClass('active');
			jQuery(this).addClass('active');
			// }
		});
		jQuery(document).on('click', '.bet-ticket-close', function() {
			jQuery('.team-vs-team-bet').removeClass('active');
		})
	});
</script>

<?php get_footer(); ?>

<script>
	//ajax call to check the current point
	jQuery(document).on('click', '.team-vs-team-bet', function() {
		$this = jQuery(this);
		let details = {
			contestID: <?= $post->ID ?>,
			gameId: $this.data('gameid'),
			type: $this.data('select'),
			team: $this.data('name'),
			termId: $this.data('term-texmoney-id'),
			dataRotation: $this.data('rotation')
		};
		let scores = $this.data('spread');

		scoreCheckAndUpdate(details, scores);
	});

	jQuery(document).on('click', '.contest-teamvsteam-cell', function() {
		jQuery('.popupbet').hide();
	})






	jQuery(document).on('click', '.submit-bet', function() {

		$this = jQuery('.team-vs-team-bet.active');
		let details = {
			contestID: <?= $post->ID ?>,
			gameId: $this.data('gameid'),
			type: $this.data('select'),
			team: $this.data('name'),
			termId: $this.data('term-texmoney-id'),
			dataRotation: $this.data('rotation')
		};
		let scores = 0;
		if (details.type == "overunder") {
			scores = jQuery('.submit-bet-form .overunderval2').val();
		} else {
			scores = jQuery("select[name='wager-team-vs-" + details.type + "']").val();
			scores = scores.replace(details.termId + '_', '');
			if (scores > 0) {
				scores = '+' + scores;
			}
		}

		scoreCheckAndUpdate(details, scores);
		jQuery('.popupbet').fadeOut();
	});

	function scoreCheckAndUpdate(details, scores) {
		//set vars
		let contestID = details.contestID;
		let gameId = details.gameId;
		let type = details.type;
		let team = details.team;
		let termId = details.termId;
		let dataRotation = details.dataRotation;
		let score = scores;

		jQuery.ajax({
			type: "POST",
			url: "<?= home_url() . '/contest/' ?>" + contestID,
			data: {
				get_current_scores: 'get_current_scores',
				contest_id: contestID,
				game_id: gameId,
				type: type,
				team: team,
				term_id: termId,
				data_rotation: dataRotation,
				score: score
			},
			success: function(output) {

				let newData = JSON.parse(output);

				// Jquery for error message,when selecting more than one bet
				if (output > 0) {
					Toastify({
						text: "Sorry, you have already placed this bet",
						duration: 2000,
						close: true,
						gravity: "top", // `top` or `bottom`
						position: "right", // `left`, `center` or `right`
						stopOnFocus: true, // Prevents dismissing of toast on hover
						style: {
							background: "linear-gradient(to right, #31BC5F, #31BC5F)",
						},
						onClick: function() {} // Callback after click
					}).showToast();
				} else {
					jQuery('.ajax-error').hide();
					jQuery('.popupbet').fadeIn();

				}
				// if (Object.keys(newData).length !== 0) {

				// 	jQuery('#confirmationPopupBox').fadeOut();

				// 	if (confirm("Note that the details of this contest have been updated. Would you like to continue?")) {
				// 		let updatedScore = newData.score;

				// 		let plus = '';
				// 		if (Number(updatedScore) > 0) {
				// 			plus = '+';
				// 		}

				// 		if (type == 'overunder') {

				// 			let currentVal = jQuery(".contest-lines-select.selectdiv .select-team.select-team-" + type + " option").val();
				// 			currentVal = currentVal.replace(score, updatedScore);
				// 			jQuery(".contest-lines-select.selectdiv .select-team.select-team-" + type).empty().append('<option class="option-select-team" value="' + currentVal + '" name="' + currentVal + '">' + dataRotation + " - " + currentVal + '</option>');
				// 			jQuery('.submit-bet-form .overunderval2').val(updatedScore);

				// 		} else {
				// 			if (type == 'moneyline') {
				// 				jQuery(".contest-lines-select.selectdiv .select-team.select-team-" + type + " option").attr("selected", false);
				// 				jQuery(".contest-lines-select.selectdiv .select-team.select-team-" + type).append('<option class="option-select-team option-select-team-' + termId + '" value="' + termId + '_' + parseInt(updatedScore) + '" data-gameid="' + gameId + '" name="' + team + '" data-spread="' + parseInt(updatedScore) + '" selected="selected">' + dataRotation + " - " + team + '' + plus + parseInt(updatedScore) + '</option>');
				// 				window.moneylineVal = parseInt(updatedScore);
				// 			} else {

				// 				jQuery(".contest-lines-select.selectdiv .select-team.select-team-" + type + " option").attr("selected", false);
				// 				jQuery(".contest-lines-select.selectdiv .select-team.select-team-" + type).append('<option class="option-select-team option-select-team-' + termId + '" value="' + termId + '_' + updatedScore + '" data-gameid="' + gameId + '" name="' + team + '" data-spread="' + updatedScore + '" selected="selected">' + dataRotation + " - " + team + '' + plus + updatedScore + '</option>');
				// 			}



				// 		}
				// 	} else {

				// 		window.location.reload();

				// 	}
				// }
			}
		});
	}
</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>