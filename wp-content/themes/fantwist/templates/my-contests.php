<?php /* Template Name: My Contests */ ?>
<?php
if (is_user_logged_in()) {
	if (current_user_can('administrator')) {
		if (isset($_GET['viewas'])) {
			$current_user_id = $_GET['viewas'];
		} else {
			$current_user_id = get_current_user_id();
		}
	} else {
		$current_user_id = get_current_user_id();
	}
} else {
	header("Location: " . home_url());
	exit;
}
?>

<?php get_header(); ?>

<div class="page-hero my-contests-hero" style="background-image:url(<?php echo get_the_post_thumbnail_url($post->ID, 'full'); ?>)">
	<div class="hero-details centered-vertical noselect">
		<div class="inner-wrap">
			<h1><?= get_the_title(); ?></h1>
			<h2><?= get_field('my_contests_h2', $post->ID); ?></h2>
			<a class="leaderboard-btn" href="<?= get_home_url(); ?>/leaderboard/">View Leaderboard</a>
		</div>
	</div>
</div>

<?php
//date range 
if (isset($_GET['contest-date'])) {
	$contest_date = $_GET['contest-date'];
	$contest_date_range = explode(" - ", $_GET['contest-date']);
	$contest_date_range = array(
		'column' => 'post_date',
		'after' => $contest_date_range[0],
		'before' => date('Y-m-d', strtotime("+1 day", strtotime($contest_date_range[1])))
	);
} else {
	$contest_date = date("m/d/Y", current_time('timestamp')) . " - " . date("m/d/Y", current_time('timestamp'));
	$contest_date_range = [];
}

$all_wager_data = array();

//fantasy points wager
$fantasy_wager_data_query = new WP_Query(array(
	'post_type' => 'wager',
	'posts_per_page' => -1,
	'date_query' => $contest_date_range,
	'author' => $current_user_id,
	'meta_query' =>
	array(
		'key'     => 'wager_result',
		'value'   => 'Open',
	),
));

foreach ($fantasy_wager_data_query->posts as $post) {
	$current_wager_data = array();

	if (get_post_status(get_field('wager_contest', $post->ID))) {
		//get team name
		$current_team = get_term(get_field('wager_team_id', $post->ID));
		$wager_type = get_field('wager_type', $post->ID);

		$current_wager_data['wager_post_id'] = get_field('wager_post_id', $post->ID);
		$current_wager_data['contest_id'] = get_field('wager_contest', $post->ID);
		$current_wager_data['post_id'] = $post->ID;
		$current_wager_data['contest_title'] = get_the_title($current_wager_data['contest_id']);
		$current_wager_data['wager_type'] = "Fantasy Wager";
		$current_wager_data['wager_game_type'] = "Fantasy Wager";
		$current_wager_data['contest_url'] = get_home_url() . "/contest/" . $current_wager_data['contest_id'];
		$current_wager_data['published_date'] = strtotime(get_the_date('y-m-d g:i:sa', $post->ID));

		$current_wager_data['wager_amount'] = get_field('wager_amount', $post->ID);
		if($current_wager_data['wager_amount'] == 0){
			$current_wager_data['wager_amount'] = get_field('wager_amount', $current_wager_data['wager_post_id']);
		}

		$current_wager_data['wager_result'] = strtoupper(get_field('wager_result', $post->ID));
		$current_wager_data['date'] = $post->post_date;
		$current_wager_data['display_date'] = $post->post_date;
		$current_wager_data['current_balance'] = get_field('current_balance', $post->ID);


		if ($wager_type == 'Spread') {
			$point_type = "point_spread";
		}
		if ($wager_type == 'Moneyline') {
			$point_type = "wager_moneyline";
		}
		if ($wager_type == 'Over/Under') {
			$point_type = "wager_overunder";
		}

		$team_name_and_points = get_field('wager_winner_1_name', $post->ID);
		if ($wager_type != 'Over/Under') {
			$team_name_and_points = (get_field($point_type, $post->ID) > 0) ? $current_team->name . ' (+' . get_field($point_type, $post->ID) . ')' : $current_team->name . ' (' . get_field($point_type, $post->ID) . ')';
		}

		$current_wager_data['team_name'] = array(array('name' => get_field('wager_rotation', $post->ID) . ' - ' . $team_name_and_points, 'push' => 'off'));
		$current_wager_data['points_type'] = array($wager_type);
		$current_wager_data['win_amount'] = number_format(get_field('potential_winnings', $post->ID), 2);

		array_push($all_wager_data, $current_wager_data);
	}
}

//get parlay wager data in array
// $parlay_wager_data_args = array(
// 	'post_type' => 'parlaywager',
// 	'posts_per_page' => -1,
// 	'date_query' => $contest_date_range,
// 	'author' => $current_user_id,
// );
// $parlay_wager_data_query = new WP_Query($parlay_wager_data_args);

// foreach ($parlay_wager_data_query->posts as $post) {
// 	$current_wager_data = array();

// 	if (get_post_status(get_field('contest_id', $post->ID))) {

// 		$current_data = json_decode(get_field('parlay_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
// 		$current_wager_data['wager_post_id'] =  $current_data->wager_post_id;
// 		$current_wager_data['contest_id'] = get_field('contest_id', $post->ID);
// 		$current_wager_data['post_id'] = $post->ID;
// 		$current_wager_data['contest_title'] = get_the_title($current_wager_data['contest_id']);
// 		$current_wager_data['wager_type'] = $current_data->parlay_game_type . " Team Parlay";
// 		$current_wager_data['wager_game_type'] = "Parlay Wager";
// 		$current_wager_data['contest_url'] = "contest-parlay/?id=" . $current_wager_data['contest_id'];
// 		$current_wager_data['published_date'] = strtotime(get_the_date('y-m-d g:i:sa', $post->ID));
// 		$current_wager_data['wager_amount'] =str_replace(',', '',json_decode(get_field('parlay_data', $current_wager_data['wager_post_id']))->wager_amount);
// 		$current_wager_data['wager_result'] = strtoupper(get_field('wager_result', $post->ID));
// 		$current_wager_data['wager_rotation'] = get_field('wager_rotation', $post->ID);
// 		$current_wager_data['date'] = $post->post_date;
// 		$current_wager_data['display_date'] = $post->post_date;
// 		$current_wager_data['current_balance'] = number_format($current_data->current_balance, 2);

// 		$current_wager_data['team_name'] = array();
// 		$current_wager_data['points_type'] = array();

// 		foreach ($current_data->team as $team_data) {
// 			$plus = '';
// 			if ($team_data->points > 0 && $team_data->points_type == 'spread') {
// 				$plus = '+';
// 			}

// 			if (isset($team_data->push)) {
// 				$push = $team_data->push;
// 			} else {
// 				$push = "off";
// 			}

// 			if (strtolower($team_data->points_type) == "over/under") {
// 				array_push($current_wager_data['team_name'], array('name' => $team_data->rotation_number . ' - ' . $team_data->team_name . ' ' . $plus . $team_data->points, 'push' => $push, 'result' => $team_data->result));
// 			} else {
// 				array_push($current_wager_data['team_name'], array('name' => $team_data->rotation_number . ' - ' . ucwords($team_data->team_name) . ' (' . $plus . $team_data->points . ')', 'push' => $push, 'result' => $team_data->result));
// 			}

// 			array_push($current_wager_data['points_type'], ucwords($team_data->points_type));
// 		}

// 		switch ($current_data->parlay_game_type) {
// 			case '1':
// 				$winning_amount = str_replace(',', '',$current_wager_data['wager_amount']) * 0.9090909090909092;
// 				break;
// 			case '2':
// 				$winning_amount = (int)str_replace(',', '',$current_wager_data['wager_amount']) * 2.6;
// 				break;
// 			case '3':
// 				$winning_amount = str_replace(',', '',$current_wager_data['wager_amount']) * 6;
// 				break;
// 			case '4':
// 				$winning_amount = str_replace(',', '',$current_wager_data['wager_amount']) * 11;
// 				break;
// 			case '5':
// 				$winning_amount = str_replace(',', '',$current_wager_data['wager_amount']) * 22;
// 				break;
// 			case '6':
// 				$winning_amount = str_replace(',', '',$current_wager_data['wager_amount']) * 45;
// 				break;
// 			case '7':
// 				$winning_amount = str_replace(',', '',$current_wager_data['wager_amount']) * 90;
// 				break;
// 			case '8':
// 				$winning_amount = str_replace(',', '',$current_wager_data['wager_amount']) * 180;
// 				break;
// 		}

// 		$current_wager_data['win_amount'] = number_format($winning_amount);

// 		array_push($all_wager_data, $current_wager_data);
// 	}
// }

wp_reset_query();

//get teaser wager data in array
// $teaser_wager_data_args = array(
// 	'post_type' => 'teaserwager',
// 	'posts_per_page' => -1,
// 	'date_query' => $contest_date_range,
// 	'author' => $current_user_id,

// );
// $teaser_wager_data_query = new WP_Query($teaser_wager_data_args);

// foreach ($teaser_wager_data_query->posts as $post) {
// 	$current_wager_data = array();

// 	if (get_post_status(get_field('contest_id', $post->ID))) {


// 		$current_data = json_decode(get_field('teaser_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
// 		$current_wager_data['wager_post_id'] = $current_data->wager_post_id;
// 		$current_wager_data['contest_id'] = get_field('contest_id', $post->ID);
// 		$current_wager_data['post_id'] = $post->ID;
// 		$current_wager_data['contest_title'] = get_the_title($current_wager_data['contest_id']);
// 		$current_wager_data['wager_type'] = $current_data->parlay_game_type . " Team Teaser,  Tease by " . $current_data->teaser_points . " points";
// 		$current_wager_data['wager_game_type'] = "Teaser Wager";
// 		$current_wager_data['contest_url'] = "contest-teaser/?id=" . $current_wager_data['contest_id'];
// 		$current_wager_data['published_date'] = strtotime(get_the_date('y-m-d g:i:sa', $post->ID));
// 		$current_wager_data['wager_amount'] =  str_replace(',', '',json_decode(get_field('teaser_data', $current_wager_data['wager_post_id']))->wager_amount);
// 		$current_wager_data['wager_result'] = strtoupper(get_field('wager_result', $post->ID));
// 		$current_wager_data['date'] = $post->post_date;
// 		$current_wager_data['display_date'] = $post->post_date;
// 		$current_wager_data['current_balance'] = number_format($current_data->current_balance, 2);

// 		$current_wager_data['team_name'] = array();
// 		$current_wager_data['points_type'] = array();

// 		if (is_array($current_data->team) || is_object($current_data->team)) {


// 			foreach ($current_data->team as $team_data) {

// 				if (isset($team_data->push)) {
// 					$push = $team_data->push;
// 				} else {
// 					$push = "off";
// 				}

// 				if (strtolower($team_data->points_type) == "over/under") {
// 					array_push($current_wager_data['team_name'], array('name' => $team_data->rotation_number . ' - ' . $team_data->team_name . ' ' . $team_data->points, 'push' => $push, 'result' => $team_data->result));
// 				} else {
// 					array_push($current_wager_data['team_name'], array('name' => $team_data->rotation_number . ' - ' . ucwords($team_data->team_name) . ' (' . $team_data->points . ')', 'push' => $push, 'result' => $team_data->result));
// 				}
// 				array_push($current_wager_data['points_type'], ucwords($team_data->points_type));
// 			}
// 		}
// 		$teaser_points = 1;
// 		$league_name = (get_the_terms($current_wager_data['contest_id'], 'league'))[0]->name;
// 		$teaseByOptions = setting_teaser_points_for_different_league($league_name);

// 		foreach ($teaseByOptions as $option => $teaseBy) {
// 			if ($teaseBy == $current_data->teaser_points) {
// 				$teaser_points = $option + 1;
// 			}
// 		}

// 		switch ($teaser_points) {
// 			case 1:
// 				switch ($current_data->parlay_game_type) {
// 					case '2':
// 						$winning_amount = str_replace(',', '', $current_wager_data['wager_amount']) * 5 / 6;
// 						break;
// 					case '3':
// 						$winning_amount = (int)str_replace(',', '', $current_wager_data['wager_amount']) * 8 / 5;
// 						break;
// 					case '4':
// 						$winning_amount = str_replace(',', '', $current_wager_data['wager_amount']) * 5 / 2;
// 						break;
// 					case '5':
// 						$winning_amount =str_replace(',', '', $current_wager_data['wager_amount']) * 9 / 2;
// 						break;
// 					case '6':
// 						$winning_amount = str_replace(',', '', $current_wager_data['wager_amount']) * 7;
// 						break;
// 					case '7':
// 						$winning_amount = str_replace(',', '', $current_wager_data['wager_amount']) * 9;
// 						break;
// 					case '8':
// 						$winning_amount = str_replace(',', '', $current_wager_data['wager_amount']) * 12;
// 						break;
// 				}
// 				break;
// 			case 2:
// 				switch ($current_data->parlay_game_type) {
// 					case '2':
// 						$winning_amount = (int)str_replace(',', '', $current_wager_data['wager_amount']) * 5 / 6.5;
// 						break;
// 					case '3':
// 						$winning_amount = str_replace(',', '', $current_wager_data['wager_amount']) * 7 / 5;
// 						break;
// 					case '4':
// 						$winning_amount = str_replace(',', '', $current_wager_data['wager_amount']) * 12 / 5;
// 						break;
// 					case '5':
// 						$winning_amount =str_replace(',', '', $current_wager_data['wager_amount']) * 4;
// 						break;
// 					case '6':
// 						$winning_amount =str_replace(',', '', $current_wager_data['wager_amount']) * 6;
// 						break;
// 					case '7':
// 						$winning_amount =str_replace(',', '', $current_wager_data['wager_amount']) * 8;
// 						break;
// 					case '8':
// 						$winning_amount =str_replace(',', '', $current_wager_data['wager_amount']) * 10;
// 						break;
// 				}
// 				break;
// 			case 3:
// 				switch ($current_data->parlay_game_type) {
// 					case '2':
// 						$winning_amount = (int)str_replace(',', '', $current_wager_data['wager_amount']) * 5 / 7;
// 						break;
// 					case '3':
// 						$winning_amount = str_replace(',', '', $current_wager_data['wager_amount'] * 6) / 5;
// 						break;
// 					case '4':
// 						$winning_amount = str_replace(',', '', $current_wager_data['wager_amount'] * 9) / 5;
// 						break;
// 					case '5':
// 						$winning_amount = str_replace(',', '', $current_wager_data['wager_amount'] * 7) / 2;
// 						break;
// 					case '6':
// 						$winning_amount =str_replace(',', '', $current_wager_data['wager_amount']) * 5;
// 						break;
// 					case '7':
// 						$winning_amount = str_replace(',', '', $current_wager_data['wager_amount'] * 13) / 2;
// 						break;
// 					case '8':
// 						$winning_amount =str_replace(',', '', $current_wager_data['wager_amount']) * 9;
// 						break;
// 				}
// 				break;
// 		}

// 		$current_wager_data['win_amount'] = number_format($winning_amount,2);

// 		array_push($all_wager_data, $current_wager_data);
// 	}
// }

wp_reset_query();
//sort the data by id
$sort = array();
foreach ($all_wager_data as $key => $part) {
	$sort[$key] = $part['published_date'];
}
array_multisort($sort, SORT_ASC, $all_wager_data);
$all_wager_data = array_reverse($all_wager_data); //reverse the array

//making different arrays for different conditions

$updated_all_wager_data = [];

foreach ($all_wager_data as $post) {
	if (!empty($post['wager_post_id'])) {
		foreach ($all_wager_data as $npost) {
			if ($post['wager_post_id'] == $npost['post_id']) {
				$post['display_date'] = $npost['display_date'];
			}
		}
	}
	array_push($updated_all_wager_data, $post);
}
$all_wager_data = $updated_all_wager_data;

$all_wager_data_upcoming = array();
$all_wager_data_in_progress = array();
$all_wager_data_finished = array();
$all_wager_data_all = array();


foreach ($all_wager_data as $post) {
	$current_wager_data = array();
	$contest_status = get_field('contest_status', $post['contest_id']);

	//for finished
	if ($post['wager_result'] != "OPEN") {
		$current_wager_data['wager_post_id'] = $post['wager_post_id'];
		$current_wager_data['contest_id'] = $post['contest_id'];
		$current_wager_data['post_id'] = $post['post_id'];
		$current_wager_data['contest_title'] = $post['contest_title'];
		$current_wager_data['wager_type'] = $post['wager_type'];
		$current_wager_data['wager_game_type'] = $post['wager_game_type'];
		$current_wager_data['contest_url'] = $post['contest_url'];
		$current_wager_data['wager_amount'] = $post['wager_amount'];
		$current_wager_data['wager_result'] = $post['wager_result'];
		$current_wager_data['date'] = $post['date'];
		$current_wager_data['display_date'] = $post['display_date'];
		$current_wager_data['current_balance'] = $post['current_balance'];
		$current_wager_data['team_name'] = $post['team_name'];
		$current_wager_data['points_type'] = $post['points_type'];
		$current_wager_data['win_amount'] = $post['win_amount'];
		array_push($all_wager_data_finished, $current_wager_data);
	}
}

$wager_post_id = [];
foreach ($all_wager_data_finished as  $wager_post) {

	array_push($wager_post_id, $wager_post['wager_post_id']);
}
//new wager data

$new_wager_array = [];
foreach ($all_wager_data as $post) {
	$current_wager_data = array();

	if (!in_array($post['post_id'], $wager_post_id)) {
		$current_wager_data['wager_post_id'] = $post['wager_post_id'];
		$current_wager_data['contest_id'] = $post['contest_id'];
		$current_wager_data['post_id'] = $post['post_id'];
		$current_wager_data['contest_title'] = $post['contest_title'];
		$current_wager_data['wager_type'] = $post['wager_type'];
		$current_wager_data['wager_game_type'] = $post['wager_game_type'];
		$current_wager_data['contest_url'] = $post['contest_url'];
		$current_wager_data['wager_amount'] = $post['wager_amount'];
		$current_wager_data['wager_result'] = $post['wager_result'];
		$current_wager_data['date'] = $post['date'];
		$current_wager_data['display_date'] = $post['display_date'];
		$current_wager_data['current_balance'] = $post['current_balance'];
		$current_wager_data['team_name'] = $post['team_name'];
		$current_wager_data['points_type'] = $post['points_type'];
		$current_wager_data['win_amount'] = $post['win_amount'];
		array_push($new_wager_array, $current_wager_data);
	}
}
$all_wager_data_all = $new_wager_array;

foreach ($all_wager_data_all as $post) {
	$current_wager_data = array();
	$contest_status = get_field('contest_status', $post['contest_id']);
	//for up coming
	if ($post['wager_result'] == "OPEN" && $contest_status == "Open") {
		$current_wager_data['contest_id'] = $post['contest_id'];
		$current_wager_data['post_id'] = $post['post_id'];
		$current_wager_data['contest_title'] = $post['contest_title'];
		$current_wager_data['wager_type'] = $post['wager_type'];
		$current_wager_data['wager_game_type'] = $post['wager_game_type'];
		$current_wager_data['contest_url'] = $post['contest_url'];
		$current_wager_data['wager_amount'] = $post['wager_amount'];
		$current_wager_data['wager_result'] = $post['wager_result'];
		$current_wager_data['date'] = $post['date'];
		$current_wager_data['display_date'] = $post['display_date'];
		$current_wager_data['current_balance'] = $post['current_balance'];
		$current_wager_data['team_name'] = $post['team_name'];
		$current_wager_data['points_type'] = $post['points_type'];
		$current_wager_data['win_amount'] = $post['win_amount'];
		array_push($all_wager_data_upcoming, $current_wager_data);
	}

	//for in progress
	if ($post['wager_result'] == "OPEN" && $contest_status == "In Progress") {
		$current_wager_data['wager_post_id'] = $post['wager_post_id'];
		$current_wager_data['contest_id'] = $post['contest_id'];
		$current_wager_data['post_id'] = $post['post_id'];
		$current_wager_data['contest_title'] = $post['contest_title'];
		$current_wager_data['wager_type'] = $post['wager_type'];
		$current_wager_data['wager_game_type'] = $post['wager_game_type'];
		$current_wager_data['contest_url'] = $post['contest_url'];
		$current_wager_data['wager_amount'] = $post['wager_amount'];
		$current_wager_data['wager_result'] = $post['wager_result'];
		$current_wager_data['date'] = $post['date'];
		$current_wager_data['display_date'] = $post['display_date'];
		$current_wager_data['current_balance'] = $post['current_balance'];
		$current_wager_data['team_name'] = $post['team_name'];
		$current_wager_data['points_type'] = $post['points_type'];
		$current_wager_data['win_amount'] = $post['win_amount'];
		array_push($all_wager_data_in_progress, $current_wager_data);
	}
}
?>

<div class="my-contests-page page-box wrap">
	<div class="inner-wrap my-contests-wrap">
		<div class="score-board-box">
			<div class="inner-wrap lobby-wrap score-board-title-wrap">
				<div class="date-selector-for-scoreboard-title">
					<span><?= isset($_GET['contest-date']) ? $_GET['contest-date'] : ''; ?></span>
					<div><strong>Account <?= get_current_user_id() ?></strong></div>
				</div>
				<div class="date-selector-for-scoreboard">
					<form method="get">
						<input type="text" name="contest-date" id="contest-date" value="<?= $contest_date ?>" /><span class="calender-css"><i class="far fa-calendar-alt"></i></span>
						<button><span class="fas fa-search"></span></button>
					</form>
				</div>
			</div>
		</div>
		<div class="my-contests-main">
			<div class="my-contests-tabs noselect">
				<ul>
					<li><a class="transition active" href="javascript:void(0);" data-type="all" data-selector="my-contests-all">All</a></li>
					<!-- <li><a class="transition" href="javascript:void(0);" data-type="upcoming" data-selector="my-contests-upcoming">Upcoming</a></li>
					<li><a class="transition" href="javascript:void(0);" data-type="live" data-selector="my-contests-live">In Progress</a></li> -->
					<li><a class="transition" href="javascript:void(0);" data-type="closed" data-selector="my-contests-closed">Finished</a></li>
					<!-- <li><a class="transition" href="javascript:void(0);" data-type="my-account" data-selector="my-contests-my-account">My Account</a></li> -->
				</ul>
			</div>
			<div class="my-contests-grid">

				<div class="my-contests my-contests-all show">
					<?php if (count($all_wager_data_all) > 0) : ?>
						<div class="table fullwidth">
							<div class="table-row table-heading">
								<div class="table-cell middle">Bet Ticket</div>
								<div class="table-cell middle">Date / Time</div>
								<div class="table-cell middle">Contest</div>
								<!-- <div class="table-cell middle">Wager Type</div> -->
								<div class="table-cell middle">Team</div>
								<div class="table-cell middle">Wager Type</div>
								<!-- <div class="table-cell middle">Wager Amount</div> -->
								<div class="table-cell middle">Status / Result</div>
							</div>
							<?php foreach ($all_wager_data_all as $row) :
								if($row['points_type'][0] != "Moneyline"){

								?>
								
								<a id="wager-data" class="table-row wager-row wager-status-<?= strtolower($row['wager_result']) ?>">
									<div class="table-cell middle  contest-click-event" id="contestBetTicket">
										<?php
										if (!empty($row['wager_post_id'])) {

											echo $row['wager_post_id'];
										} else {
											echo $row['post_id'];
										}
										?>
									</div>
									<div class="table-cell middle" id="contestDate"><?= date('M d, Y - g:ia', strtotime($row['date'])) ?></div>
									<div class="table-cell middle" id="title"> <?= $row['contest_title'] ?> </div>
									<div class="table-cell middle" id="contestTitle"> <?= $row['contest_title'] ?> </div>
									<!-- <div class="table-cell middle"> <?= $row['wager_type'] ?> </div> -->
									<div class="table-cell middle" id="contestWagerDetails"> <?php foreach ($row['team_name'] as $team_name) {
																									echo '<span class="team-name-' . $team_name['push'] . '">' . $team_name['name'] . "</span>";
																								} ?> </div>
									<div class="table-cell middle" id="singleResult"> <?php foreach ($row['team_name'] as $team_name) {
																							echo '<span class="team-name-' . $team_name['push'] . '">' . $team_name['name'] . "</span>";
																						} ?> </div>
									<?php
									$pointTypeText = "";
									if (count($row['points_type']) > 1) :
										$pointTypeText = $row['wager_type'];
									else :
										foreach ($row['points_type'] as $points_type) {
											$pointTypeText .= $points_type . "<br>";
										}
									endif;
									?>
									<div class="table-cell middle"> <?php echo $pointTypeText; ?> </div>
									<?php
									$pointTypeText = "";
									if (count($row['points_type']) > 1 && $row['wager_game_type'] == "Teaser Wager") :
										$pointTypeText = "Teaser";
										elseif(count($row['points_type']) > 1 && $row['wager_game_type'] == "Parlay Wager") :
											$pointTypeText = "Parlay";
											elseif(count($row['points_type']) > 1) :
												$pointTypeText = $row['points_type'];
									else :
										foreach ($row['points_type'] as $points_type) {
											$pointTypeText .= $points_type . "<br>";
										}
									endif;
									?>
									<div class="table-cell middle" id=wager__types> <?php echo $pointTypeText; ?> </div>

									<div class="table-cell middle" id="wager_game_type"> <?= $row['wager_game_type'] ?> </div>
									<!-- <div class="table-cell middle"> Bet <strong>$<?= $row['wager_amount'] ?></strong> to win <strong>$<?= $row['win_amount'] ?></strong> </div> -->
									<div class="table-cell middle" id="wagerAmount"> Risk <strong>$<?= $row['wager_amount'] ?></strong> to win <strong>$<?= $row['win_amount'] ?></strong> </div>
									<?php
									if ($row['wager_result'] != "OPEN") {
									?>
										<div class="table-cell middle wager-status contest-click-event" id="wagerStatus">
											<?php
											echo $row['wager_result'] . '<span class="results-text">See Results <i class="fas fa-arrow-circle-right"></i></span>';
											?></div>
									<?php
									} else {
									?><div class="table-cell middle wager-status" id="wagerStatus">
											<?php echo $row['wager_result'];
											?></div>
									<?php
									}
									?>
									<div class="table-cell middle" id="wagerResult"><?= ucwords(strtolower($row['wager_result'])) ?></div>
									<div class="table-cell middle" id="wagerDisplayDate"><?= date('M d, Y - g:ia', strtotime($row['display_date'])) ?></div>
								</a>
							<?php
								}
						
						endforeach; ?>
						</div>
					<?php else : ?>
						<div class="mycontests-no-contests align-center">There are no contests to display.</div>
					<?php endif; ?>
				</div>

				<div class="my-contests my-contests-upcoming">
					<?php if (count($all_wager_data_upcoming) > 0) : ?>
						<div class="table fullwidth">
							<div class="table-row table-heading">
								<div class="table-cell middle">Bet Ticket</div>
								<div class="table-cell middle">Date / Time</div>
								<div class="table-cell middle">Contest</div>
								<!-- <div class="table-cell middle">Wager Type</div> -->
								<div class="table-cell middle">Team</div>
								<div class="table-cell middle">Wager Type</div>
								<div class="table-cell middle">Wager Amount</div>
								<div class="table-cell middle">Status / Result</div>
							</div>
							<?php foreach ($all_wager_data_upcoming as $row) : ?>

								<a id="wager-data" class="table-row wager-row wager-status-<?= strtolower($row['wager_result']) ?>">
									<div class="table-cell middle  contest-click-event" id="contestBetTicket"> <?= $row['post_id'] ?> </div>
									<div class="table-cell middle" id="contestDate"><?= date('M d, Y - g:ia', strtotime($row['date'])) ?></div>
									<div class="table-cell middle" id="title"> <?= $row['contest_title'] ?> </div>
									<div class="table-cell middle" id="contestTitle"> <?= $row['contest_title'] ?> </div>
									<!-- <div class="table-cell middle"> <?= $row['wager_type'] ?> </div> -->
									<div class="table-cell middle" id="contestWagerDetails"> <?php foreach ($row['team_name'] as $team_name) {
																									echo '<span class="team-name-' . $team_name['push'] . '">' . $team_name['name'] . "</span>";
																								} ?> </div>
									<div class="table-cell middle" id="singleResult"> <?php foreach ($row['team_name'] as $team_name) {
																							echo '<span class="team-name-' . $team_name['push'] . '">' . $team_name['name'] . "</span>";
																						} ?> </div>
									<?php
									$pointTypeText = "";
									if (count($row['points_type']) > 1) :
										$pointTypeText = $row['wager_type'];
									else :
										foreach ($row['points_type'] as $points_type) {
											$pointTypeText .= $points_type . "<br>";
										}
									endif;
									?>
									<div class="table-cell middle"> <?php echo $pointTypeText; ?> </div>
									<?php
									$pointTypeText = "";
									if (count($row['points_type']) > 1 && $row['wager_game_type'] == "Teaser Wager") :
										$pointTypeText = "Teaser";
										elseif(count($row['points_type']) > 1 && $row['wager_game_type'] == "Parlay Wager") :
											$pointTypeText = "Parlay";
											elseif(count($row['points_type']) > 1) :
												$pointTypeText = $row['points_type'];
									else :
										foreach ($row['points_type'] as $points_type) {
											$pointTypeText .= $points_type . "<br>";
										}
									endif;
									?>
									<div class="table-cell middle" id=wager__types> <?php echo $pointTypeText; ?> </div>
									<div class="table-cell middle" id="wager_game_type"> <?= $row['wager_game_type'] ?> </div>
									<div class="table-cell middle"> Bet <strong>$<?= $row['wager_amount'] ?></strong> to win <strong>$<?= $row['win_amount'] ?></strong> </div>
									<div class="table-cell middle" id="wagerAmount"> Bet <strong>$<?= $row['wager_amount'] ?></strong> to win <strong>$<?= $row['win_amount'] ?></strong> </div>
									<?php
									if ($row['wager_result'] != "OPEN") {
									?>
										<div class="table-cell middle wager-status contest-click-event" id="wagerStatus">
											<?php
											echo $row['wager_result'] . '<span class="results-text">See Results <i class="fas fa-arrow-circle-right"></i></span>';
											?></div>
									<?php
									} else {
									?><div class="table-cell middle wager-status" id="wagerStatus">
											<?php echo $row['wager_result'];
											?></div>
									<?php
									}
									?>
									<div class="table-cell middle" id="wagerResult"><?= ucwords(strtolower($row['wager_result'])) ?></div>
									<div class="table-cell middle" id="wagerDisplayDate"><?= date('M d, Y - g:ia', strtotime($row['display_date'])) ?></div>
								</a>
							<?php endforeach; ?>
						</div>
					<?php else : ?>
						<div class="mycontests-no-contests align-center">There are no contests to display.</div>
					<?php endif; ?>
				</div>

				<div class="my-contests my-contests-live">
					<?php if (count($all_wager_data_in_progress) > 0) : ?>
						<div class="table fullwidth">
							<div class="table-row table-heading">
								<div class="table-cell middle">Bet Ticket</div>
								<div class="table-cell middle">Date / Time</div>
								<div class="table-cell middle">Contest</div>
								<!-- <div class="table-cell middle">Wager Type</div> -->
								<div class="table-cell middle">Team</div>
								<div class="table-cell middle">Wager Type</div>
								<div class="table-cell middle">Wager Amount</div>
								<div class="table-cell middle">Status / Result</div>
							</div>
							<?php foreach ($all_wager_data_in_progress as $row) : ?>
								<a id="wager-data" class="table-row wager-row wager-status-<?= strtolower($row['wager_result']) ?>">
									<div class="table-cell middle  contest-click-event" id="contestBetTicket"> <?= $row['post_id'] ?> </div>
									<div class="table-cell middle" id="contestDate"><?= date('M d, Y - g:ia', strtotime($row['date'])) ?></div>
									<div class="table-cell middle" id="title"> <?= $row['contest_title'] ?> </div>
									<div class="table-cell middle" id="contestTitle"> <?= $row['contest_title'] ?> </div>
									<!-- <div class="table-cell middle"> <?= $row['wager_type'] ?> </div> -->
									<div class="table-cell middle" id="contestWagerDetails"> <?php foreach ($row['team_name'] as $team_name) {
																									echo '<span class="team-name-' . $team_name['push'] . '">' . $team_name['name'] . "</span>";
																								} ?> </div>
									<div class="table-cell middle" id="singleResult"> <?php foreach ($row['team_name'] as $team_name) {
																							echo '<span class="team-name-' . $team_name['push'] . '">' . $team_name['name'] . "</span>";
																						} ?> </div>
									<?php
									$pointTypeText = "";
									if (count($row['points_type']) > 1) :
										$pointTypeText = $row['wager_type'];
									else :
										foreach ($row['points_type'] as $points_type) {
											$pointTypeText .= $points_type . "<br>";
										}
									endif;
									?>
									<div class="table-cell middle"> <?php echo $pointTypeText; ?> </div>
									<?php
									$pointTypeText = "";
									if (count($row['points_type']) > 1 && $row['wager_game_type'] == "Teaser Wager") :
										$pointTypeText = "Teaser";
										elseif(count($row['points_type']) > 1 && $row['wager_game_type'] == "Parlay Wager") :
											$pointTypeText = "Parlay";
											elseif(count($row['points_type']) > 1) :
												$pointTypeText = $row['points_type'];
									else :
										foreach ($row['points_type'] as $points_type) {
											$pointTypeText .= $points_type . "<br>";
										}
									endif;
									?>
									<div class="table-cell middle" id=wager__types> <?php echo $pointTypeText; ?> </div>
									<div class="table-cell middle" id="wager_game_type"> <?= $row['wager_game_type'] ?> </div>
									<div class="table-cell middle"> Bet <strong>$<?= $row['wager_amount'] ?></strong> to win <strong>$<?= $row['win_amount'] ?></strong> </div>
									<div class="table-cell middle" id="wagerAmount"> Bet <strong>$<?= $row['wager_amount'] ?></strong> to win <strong>$<?= $row['win_amount'] ?></strong> </div>
									<?php
									if ($row['wager_result'] != "OPEN") {
									?>
										<div class="table-cell middle wager-status contest-click-event" id="wagerStatus">
											<?php
											echo $row['wager_result'] . '<span class="results-text">See Results <i class="fas fa-arrow-circle-right"></i></span>';
											?></div>
									<?php
									} else {
									?><div class="table-cell middle wager-status" id="wagerStatus">
											<?php echo $row['wager_result'];
											?></div>
									<?php
									}
									?>
									<div class="table-cell middle" id="wagerResult"><?= ucwords(strtolower($row['wager_result'])) ?></div>
									<div class="table-cell middle" id="wagerDisplayDate"><?= date('M d, Y - g:ia', strtotime($row['display_date'])) ?></div>
								</a>
							<?php endforeach; ?>
						</div>
					<?php else : ?>
						<div class="mycontests-no-contests align-center">There are no contests to display.</div>
					<?php endif; ?>
				</div>

				<div class="my-contests my-contests-closed">
					<?php if (count($all_wager_data_finished) > 0) : ?>
						<div class="table fullwidth">
							<div class="table-row table-heading">
								<div class="table-cell middle">Bet Ticket</div>
								<div class="table-cell middle">Date / Time</div>
								<div class="table-cell middle">Contest</div>
								<!-- <div class="table-cell middle">Wager Type</div> -->
								<div class="table-cell middle">Team</div>
								<div class="table-cell middle">Wager Type</div>
								<!-- <div class="table-cell middle">Wager Amount</div> -->
								<div class="table-cell middle">Status / Result</div>
							</div>
							<?php foreach ($all_wager_data_finished as $row) : ?>
								<a id="wager-data" class="table-row wager-row wager-status-<?= strtolower($row['wager_result']) ?>">
									<div class="table-cell middle  contest-click-event" id="contestBetTicket">
										<?php
										if (!empty($row['wager_post_id'])) {

											echo $row['wager_post_id'];
										} else {
											echo $row['post_id'];
										}
										?>
									</div>
									<div class="table-cell middle" id="contestDate"><?= date('M d, Y - g:ia', strtotime($row['date'])) ?></div>
									<div class="table-cell middle" id="title"> <?= $row['contest_title'] ?> </div>
									<div class="table-cell middle" id="contestTitle"> <?= $row['contest_title'] ?> </div>
									<!-- <div class="table-cell middle"> <?= $row['wager_type'] ?> </div> -->
									<div class="table-cell middle" id="contestWagerDetails"> <?php foreach ($row['team_name'] as $team_name) {
																									echo '<span class="team-name-' . $team_name['push'] . '">' . $team_name['name'] . "</span>";
																								} ?>
									</div>
									<div class="table-cell middle" id="singleResult"> <?php foreach ($row['team_name'] as $team_name) {
																							echo '<span class="team-name-' . $team_name['push'] . '">' . $team_name['name'] . "</span>";
																						} ?> </div>
									<?php
									$pointTypeText = "";
									if (count($row['points_type']) > 1) :
										$pointTypeText = $row['wager_type'];
									else :
										foreach ($row['points_type'] as $points_type) {
											$pointTypeText .= $points_type . "<br>";
										}
									endif;
									?>
									<div class="table-cell middle"> <?php echo $pointTypeText; ?> </div>
									<?php
									$pointTypeText = "";
									if (count($row['points_type']) > 1 && $row['wager_game_type'] == "Teaser Wager") :
										$pointTypeText = "Teaser";
										elseif(count($row['points_type']) > 1 && $row['wager_game_type'] == "Parlay Wager") :
											$pointTypeText = "Parlay";
											elseif(count($row['points_type']) > 1) :
												$pointTypeText = $row['points_type'];
									else :
										foreach ($row['points_type'] as $points_type) {
											$pointTypeText .= $points_type . "<br>";
										}
									endif;
									?>
									<div class="table-cell middle" id=wager__types> <?php echo $pointTypeText; ?> </div>
									<div class="table-cell middle" id="wager_game_type"> <?= $row['wager_game_type'] ?> </div>
									<!-- <div class="table-cell middle"> Bet <strong>$<?= $row['wager_amount'] ?></strong> to win <strong>$<?= $row['win_amount'] ?></strong> </div> -->
									<div class="table-cell middle" id="wagerAmount"> Bet <strong>$<?= $row['wager_amount'] ?></strong> to win <strong>$<?= $row['win_amount'] ?></strong> </div>
									<?php
									if ($row['wager_result'] != "OPEN") {
									?>
										<div class="table-cell middle wager-status contest-click-event" id="wagerStatus">
											<?php
											echo $row['wager_result'] . '<span class="results-text">See Results <i class="fas fa-arrow-circle-right"></i></span>';
											?></div>
									<?php
									} else {
									?><div class="table-cell middle wager-status" id="wagerStatus">
											<?php echo $row['wager_result'];
											?></div>
									<?php
									}
									?>
									<div class="table-cell middle" id="wagerResult"><?= ucwords(strtolower($row['wager_result'])) ?></div>
									<div class="table-cell middle" id="wagerDisplayDate"><?= date('M d, Y - g:ia', strtotime($row['display_date'])) ?></div>
								</a>
							<?php endforeach; ?>
						</div>
					<?php else : ?>
						<div class="mycontests-no-contests align-center">There are no contests to display.</div>
					<?php endif; ?>
				</div>

				<div class="my-contests my-contests-my-account">
					<?php if (count($all_wager_data) > 0) : ?>
						<div class="table fullwidth">
							<div class="table-row table-heading">
								<div class="table-cell middle">Date / Time</div>
								<div class="table-cell middle">Bet Ticket</div>
								<div class="table-cell middle">Wager Details</div>
								<div class="table-cell middle">Change in Balance</div>
								<div class="table-cell middle">Event Description</div>
								<div class="table-cell middle">Ending Balance</div>
							</div>
							<?php $i = 1 ?>
							<?php foreach ($all_wager_data as $row) : ?>
								<a id="wager-data" class="table-row wager-row wager-status-<?= strtolower($row['wager_result']) ?>">
									<div class="table-cell middle" id="contestDate"><?= date('M d, Y - g:ia', strtotime($row['date'])) ?></div>
									<div class="table-cell middle contest-click-event my-account-bet-ticket" id="contestBetTicket">
										<?php
										if (!empty($row['wager_post_id'])) {

											echo $row['wager_post_id'];
										} else {
											echo $row['post_id'];
										}

										?>
									</div>
									<div class="table-cell middle" id="contestTitle"> <?= $row['contest_title'] ?> </div>
									<div class="table-cell middle" id="contestWagerDetails">
										<?php foreach ($row['team_name'] as $team_name) {
											echo '<span class="team-name-' . $team_name['push'] . '">' . $team_name['name'] . "</span>";
										} ?> </div>
									<div class="table-cell middle" id="singleResult">
										<?php
										if ($row['wager_game_type'] != "Fantasy Wager") {

											foreach ($row['team_name'] as $team_name) {
												echo '<span class="team-name-' . $team_name['push'] . '">' . $team_name['name'] . "<br><span class='bet-result-span'>Result - " . $team_name['result'] . "</span></span>";
											}
										} else if ($row['wager_game_type'] == "Fantasy Wager" && $row['wager_result'] == "WIN") {
											echo '<span class="team-name-' . $team_name['push'] . '">' . $team_name['name'] . "<br><span class='bet-result-span'>Result - Win" . "</span></span>";
										} else if ($row['wager_game_type'] == "Fantasy Wager" && $row['wager_result'] == "LOSS") {
											echo '<span class="team-name-' . $team_name['push'] . '">' . $team_name['name'] . "<br><span class='bet-result-span'>Result - Loss" . "</span></span>";
										} else {
											echo '<span class="team-name-' . $team_name['push'] . '">' . $team_name['name'] . "</span>";
										}
										?>
									</div>

									<div class="table-cell middle" id="wager_game_type"> <?= $row['wager_game_type'] ?> </div>
									<?php
									$pointTypeText = "";
									if (count($row['points_type']) > 1) :
										$pointTypeText = $row['wager_type'];
									else :
										foreach ($row['points_type'] as $points_type) {
											$pointTypeText .= $points_type . "<br>";
										}
									endif;
									?>
									<div class="table-cell middle account-wager-type"> <?php echo $pointTypeText; ?> </div>
									<?php
									$pointTypeText = "";
									if (count($row['points_type']) > 1 && $row['wager_game_type'] == "Teaser Wager") :
										$pointTypeText = "Teaser";
										elseif(count($row['points_type']) > 1 && $row['wager_game_type'] == "Parlay Wager") :
											$pointTypeText = "Parlay";
											elseif(count($row['points_type']) > 1) :
												$pointTypeText = $row['points_type'];
									else :
										foreach ($row['points_type'] as $points_type) {
											$pointTypeText .= $points_type . "<br>";
										}
									endif;
									?>
									<div class="table-cell middle" id=wager__types> <?php echo $pointTypeText; ?> </div>
									<div class="table-cell middle" id="contestBalance">
										<?php
										if ($row['wager_result'] == "PUSH") {
											echo '$' . number_format($row['wager_amount'], 2);
										} else if ($row['wager_result'] == "WIN") {
											echo '$' . number_format($row['wager_amount'] + str_replace(',', '',  $row['win_amount']), 2);
										} else if ($row['wager_result'] == "OPEN") {
											echo ' - $' . number_format($row['wager_amount'], 2);
										} else {
											echo "$" . (0.00);
										}
										?>
									</div>
									<div class="table-cell middle" id="wagerStatus">
										<?php
										if ($row['wager_result'] == "OPEN") {
										?>
											Wager <strong>$<?= $row['wager_amount'] ?></strong> to Win <strong>$<?= str_replace(',', '',  $row['win_amount']) ?></strong>
										<?php
										} else if ($row['wager_result'] == "PUSH") {
											echo "Wager Settled - Push";
										} else {
											echo "Wager Settled";
										}
										?>
									</div>
									<div class="table-cell middle" id="wagerResult"><?= ucwords(strtolower($row['wager_result'])) ?></div>
									<div class="table-cell middle" id="wagerDisplayDate"><?= date('M d, Y - g:ia', strtotime($row['display_date'])) ?></div>
									<div class="table-cell middle" id="wagerAmount"> Risk <strong>$<?= $row['wager_amount'] ?></strong> to win <strong>$<?= $row['win_amount'] ?></strong> </div>
									<div class="table-cell middle">
										<?php
										$wager_amount = 0;
										if ($row['current_balance'] != 0) {
											if ($row['wager_result'] == "PUSH") {
												$wager_amount = number_format(number_format((str_replace(',', '', $row['current_balance'])), 2, '.', '') + $row['wager_amount'], 2);
											} else if ($row['wager_result'] == "WIN") {
												$wager_amount = number_format(number_format((str_replace(',', '', $row['current_balance'])), 2, '.', '') +  $row['wager_amount'] + str_replace(',', '',$row['win_amount']), 2);
											} else if ($row['wager_result'] == "LOSS") {
												$wager_amount = number_format(number_format((str_replace(',', '', $row['current_balance'])), 2, '.', ''), 2);
											} else if ($row['wager_result'] == "OPEN") {
												$wager_amount = number_format(number_format((str_replace(',', '', $row['current_balance'])), 2, '.', '') -   $row['wager_amount'], 2);
											}
										} else {
											$wager_amount = "0";
										}

										if ($i == 1) {
										?>

										<?php

										}
										echo "$" . number_format((str_replace(',', '', $wager_amount)), 2);
										?>
									</div>
								</a>
							<?php $i++;
							endforeach; ?>
						</div>
					<?php else : ?>
						<div class="mycontests-no-contests align-center">There are no contests to display.</div>
					<?php endif; ?>
				</div>

			</div>

			<div class="user-box">
				<a class="logout-link" href="<?php echo wp_logout_url(home_url()); ?> ">Log Out</a>
				<a class="profile" href="<?php echo get_author_posts_url($current_user_id); ?>">My Profile</a>
				<a class="edit-profile" href="/edit-profile/">Edit Profile</a>
			</div>
		</div>
	</div>
</div>
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
		<div id="popupWagerType"></div>
		<div id="popupcontestTitle"></div>
		<div id="popupBetAmout"></div>
		<div id="popupBetDetails"></div>
		<!-- <div id="popupWagerAmount"></div> -->
	</div>
	<div class="confirmation-popup-box-footer">
		<button type="button" id="btnOkConfirmOk" onclick="closeThePopupBox()">OK</button>
	</div>
</div>

<?php

get_footer();
?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>
	jQuery(function() {
		jQuery('input[name="contest-date"]').daterangepicker();
	});


	// my account popup

	jQuery('.contest-click-event').on('click', function() {
		$this = jQuery(this).parent('.wager-row');
		var contestDate = jQuery($this).find('div#wagerDisplayDate')[0].innerHTML.trim();
		var contestTitle = jQuery($this).find('div#contestTitle')[0].innerHTML.trim();
		var wagerAmount = jQuery($this).find('div#wagerAmount')[0].innerHTML.trim();
		var contestBetTicket = jQuery($this).find('div#contestBetTicket')[0].innerHTML.trim();
		var contestWagerDetails = jQuery($this).find("div#singleResult")[0].innerHTML.trim();
		var wagerResult = jQuery($this).find('div#wagerResult')[0].innerHTML.trim();
		var wagerType = jQuery($this).find('div#wager__types')[0].innerHTML.trim();
		let settle = 'Sold';
		let wagerStatus = "Undecided";
		let title = 'Sports Bet';
		let result = '';
		var txt = $('#singleResultMyAccount').text();

		if (wagerResult != "Open") {
			settle = "Settled";
			wagerStatus = wagerResult;
			result = "Result - " + wagerStatus;
		}


		jQuery('.confirmation-popup-box-title').html(title + " - " + settle);
		jQuery('#popupWagerDate').html('<div class="bet-settle-popup"><strong>' + contestDate + '</strong> </div>');
		// jQuery('#popupBetTicket').html('<div class="bet-title-popup">Bet Ticket: </div><div class="bet-value-popup">' + contestBetTicket + '</div>');
		jQuery('#popupcontestTitle').html('<div class="bet-title-popup">Title: </div><div class="bet-value-popup">' + contestTitle + '</div>');

		if (jQuery(this).hasClass('my-account-bet-ticket')) {
			jQuery('#popupBetDetails').html('<div class="bet-title-popup">Wager Details: </div><div class="bet-value-popup">' + contestWagerDetails + '</div>');
		} else {
			jQuery('#popupBetDetails').html('<div class="bet-title-popup">Wager Details: </div><div class="bet-value-popup">' + contestWagerDetails + result + '</div>');
		}


		if (jQuery(this).parents('.wager-status-open').length > 0) {
			jQuery('.bet-result-span').hide();
		} else {
			jQuery('.bet-result-span').show();
		}




		// jQuery('#popupWagerAmount').html('<div class="bet-title-popup">Wager Amount: </div><div class="bet-value-popup">' + wagerAmount + '</div>');
		jQuery('#popupWagerType').html('<div class="bet-title-popup">Wager Type: </div><div class="bet-value-popup">' + wagerType + '</div>');

		jQuery('#wagerPopupBox').fadeIn();
	});

	function closeThePopupBox() {
		jQuery('#wagerPopupBox').fadeOut();
	}

	//popup wager status
</script>