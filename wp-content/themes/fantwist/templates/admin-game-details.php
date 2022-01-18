<?php /* Template Name: Admin Game Details */

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

    header("Location: " . home_url($path = '/admin'));
    exit;
}
?>
<?php get_header('custom'); ?>


<?php
$contest_id = $_REQUEST['contest_id'];
$px_game_id = $_REQUEST['game_id'];

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
$postData = [];
if ($the_query->found_posts > 0) {

    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $postData = $post;
        }
    }
}

wp_reset_postdata();

$args = array(
    'post_type' => 'wager',
    'relation' => 'AND',
    'meta_query' => array(
        array(
            'key'     => 'wager_contest',
            'value'   => $contest_id
        ),
        array(
            'key' => 'wager_game_id',
            'value'   => $px_game_id
        ),
    ),
);
$betsQuery = new WP_Query($args);
$betsCount =  $betsQuery->found_posts;
$wagerTotalAmount = 0;
$open_counter = [];
$settled_counter = [];
$settled_count = 0;
$open_count = 0;
$final_result = [];
$wagersHtml = "";
if ($betsQuery->have_posts()) {
    while ($betsQuery->have_posts()) {
        $betsQuery->the_post();

        $contest = get_field('wager_contest', $post->ID);
        $contest_title = str_replace('Point Spread', '', get_field('contest_title_without_type', $contest));

        $wagerId = $post->ID;
        $wagerName = get_the_author();
        $wagerType =  get_field('wager_type', $post->ID);
        $wager_type =  strtolower(get_field('wager_type', $post->ID));
        $wagerAmount =  get_field('wager_amount', $post->ID);
        $potentialWin =  get_field('potential_winnings', $post->ID);
        $winnerName = get_field('wager_winner_1_name', $post->ID);
        $toBetToWin  =  "Bet <strong style='font-size:12px;'>$" . $wagerAmount . "</strong> to Win <strong style='font-size:12px;'>$" . $potentialWin . "</strong>";
        $result = get_field('wager_result', $post->ID);
        $final_result = $result;
        $wager_post_id = get_field('wager_post_id', $post->ID);
        $wagerTotalAmount += get_field('wager_amount', $post->ID);
        array_push($settled_counter, $wager_post_id);
        array_push($open_counter, $wagerId);
        if (!in_array($wagerId, $settled_counter)) {
            $open_count++;
        } else {

            $settled_count++;
        }
        $author = get_field('overunder_gameid', $post->ID);


        $contest = get_field('wager_contest', $post->ID);
        $contest_title = str_replace('Point Spread', '', get_field('contest_title_without_type', $contest));
        $wager_result = get_field('wager_result');

        $wager_type = strtolower(get_field('wager_type', $post->ID));
        $winners_html = '';

        $league = get_the_terms($post->ID, 'league');
        $league_id = $league[0]->term_id;
        $league_name = $league[0]->name;

        if ($wager_type == 'win' || $wager_type == 'place' || $wager_type == 'show' || $wager_type == 'spread' || $wager_type == 'moneyline' || $wager_type == 'over/under') {

            if ($wager_type == 'spread') {

                $point_spread = get_field('point_spread', $post->ID);

                if ($point_spread > 0) {

                    $point_spread = '+' . $point_spread;
                }

                $winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . $point_spread . ')';
            } else if ($wager_type == 'moneyline') {

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
                        if ($moneyline > 0) {
                            $moneyline_plus_minus = '+';
                        } else {
                            $moneyline_plus_minus = '';
                        }
                        $winners_html = $team->name . ' (' . $moneyline_plus_minus . $moneyline . ')';
                    }
                }
            } else if ($wager_type == 'over/under') {

                $winners_html = get_field('wager_winner_1_name', $post->ID);
            } else {

                $winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)';
            }

            $winnings = get_field('potential_winnings', $post->ID);
        } else if ($wager_type == "pick 2" || $wager_type == "pick 2 box") {

            $winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' .
                get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)';

            $winnings = get_field('potential_winnings', $post->ID);
        } else if ($wager_type == "pick 3" || $wager_type == "pick 3 box") {

            $winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' .
                get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)' . '<br>' .
                get_field('wager_winner_3_name', $post->ID) . ' (' . get_field('winner_3_odds', $post->ID) . ':1)';

            $winnings = get_field('potential_winnings', $post->ID);
        } else if ($wager_type == "pick 4" || $wager_type == "pick 4 box") {

            $winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' .
                get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)' . '<br>' .
                get_field('wager_winner_3_name', $post->ID) . ' (' . get_field('winner_3_odds', $post->ID) . ':1)' . '<br>' .
                get_field('wager_winner_4_name', $post->ID) . ' (' . get_field('winner_4_odds', $post->ID) . ':1)';

            $winnings = get_field('potential_winnings', $post->ID);
        } else if ($wager_type == "pick 6") {

            $winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' .
                get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)' . '<br>' .
                get_field('wager_winner_3_name', $post->ID) . ' (' . get_field('winner_3_odds', $post->ID) . ':1)' . '<br>' .
                get_field('wager_winner_4_name', $post->ID) . ' (' . get_field('winner_4_odds', $post->ID) . ':1)' . '<br>' .
                get_field('wager_winner_5_name', $post->ID) . ' (' . get_field('winner_5_odds', $post->ID) . ':1)' . '<br>' .
                get_field('wager_winner_6_name', $post->ID) . ' (' . get_field('winner_6_odds', $post->ID) . ':1)';

            $winnings = get_field('potential_winnings', $post->ID);
        }


        $wagersHtml .= "<tr>
                            <td>" . $wagerId . "</td>
                            <td>" . $contest_title . "</td>
                            <td>" . ucfirst($wagerName) . "</td>
                            <td>" . $wagerType . "</td>
                            <td>" . $toBetToWin . "</td>
                            <td>" . $winners_html . "</td>
                            <td>" . $result . "</td>
                        </tr>";
    }
    wp_reset_query();
}




$args = array(
    'post_type' => 'contest',
    'p' => $contest_id
);
$the_query = new WP_Query($args);

if ($the_query->have_posts()) {
    while ($the_query->have_posts()) {

        $the_query->the_post();

        $league = wp_get_object_terms($post->ID, 'league')[0]->slug;

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

        //Sort games by time
        $sort = array();
        foreach ($contest_data as $key => $part) {
            $sort[$key] = strtotime($part->game_start);
        }
        array_multisort($sort, SORT_ASC, $contest_data);

        //Build output from team data
        $slate_game_count = 1;
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

            //adding player points to the total
            $game->team1->total_points = 0;
            $game->team2->total_points = 0;
            foreach ($game->team1->player as $score) {
                $game->team1->total_points += $score;
            }
            foreach ($game->team2->player as $score) {
                $game->team2->total_points += $score;
            }

            $team_count = 0;

            $game_id = $game->game_id;

            $team_id_1 = $game->team1->TeamID;
            $term_id_1 = $game->team1->term_id;
            $team_1_name = $game->team1->name;
            $team_1_team = $game->team1->name;
            $team_1_points = $game->team1->total_points;
            $team_1_projected_score = $game->team1->total_points;
            $team_1_term_taxonomy = $game->team1->term_taxonomy_id;

            $team_id_2 = $game->team2->TeamID;
            $term_id_2 = $game->team2->term_id;
            $team_2_name = $game->team2->name;
            $team_2_team = $game->team2->name;
            $team_2_points = $game->team2->total_points;
            $team_2_projected_score = $game->team2->total_points;
            $team_2_term_taxonomy = $game->team2->term_taxonomy_id;

            $home_abbrev = $game->team2->team_abbrev;
            $game_date = $game->game_start;
            $game_date_time = $game->game_start;

            $team_count++;

            //If the contest has statred but the game isn't
            if (!$game_started && !empty($results)) {
                foreach ($contest_data_projection as $projection_data) {
                    if ($projection_data->game_id == $game_id) {
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

            if ($_GET['game_id'] == $game_id) {
                break;
            }

            $game_count++;
            $slate_game_count++;
        }

        $game_date = date('m/d/Y', strtotime($game_date_time));

        if ($league == 'nfl') {
            $categories = get_the_terms($_GET['contest_id'], 'schedule');

            if ($categories && count($categories) > 0) :
                foreach ($categories as $category) {
                    if (strpos($category->name, 'Pre') !== false) {
                        $season = $category->name;
                    }
                    if (strpos($category->name, 'Season') !== false) {
                        $season = $category->name;
                    }
                    if (strpos($category->name, 'Post') !== false) {
                        $season = $category->name;
                    }
                    if (strpos($category->name, 'Week') !== false) {
                        $week = $category->name;
                    }
                }
            endif;

            $post_id = $post->ID;

            $back_url = '?league_type=' . $league . '&season=' . $season . '&week=' . $week;
        } else {
            $back_url = '?league_type=' . $league . '&date=' . $game_date;
        }

        $date_differenece = strtotime(date("d-m-Y", current_time('timestamp'))) - strtotime(date("d-m-Y", strtotime($game_date_time)));
        $time_differenece = ((current_time('timestamp') - 60) - strtotime(date("d-m-Y H:i:s", (strtotime($game_date_time))))) / 60;

        if ($date_differenece > 0) {
            $contest_status = "in progress";
            $points_status = "disabled";
            $projected_or_live = 'Live Fantasy Points: ';
            $game_started = true;
        } else {
            if ($time_differenece >= 1) {
                $contest_status = "in progress";
                $points_status = "disabled";
                $projected_or_live = 'Live Fantasy Points: ';
                $game_started = true;
            } else {
                $contest_status = "not done";
                $points_status = "";
                $projected_or_live = 'Projected Fantasy Points: ';
                $game_started = false;
            }
        }
        //If the contest has statred but the game isn't
        if (!$game_started && !empty($results)) {
            foreach ($contest_data_projection as $projection_data) {
                if ($projection_data->game_id == $game_id) {
                    $team_1_projected_score = $projection_data->team1->total_points;
                    $team_2_projected_score = $projection_data->team2->total_points;
                }
            }
        }
        //---------------------------------------------

        if ($game->is_game_over == 1 && $game_started) {
            $contest_status = "done";
            $projected_or_live = '<strong>Final: ';
            $team_1_html_end_point .= '</strong>';
            $team_2_html_end_point .= '</strong>';
        }
        if ($game->is_game_over == "canceled" && $game_started) {
            $contest_status = "done";
            $projected_or_live = '<strong>Final: ';
        }


        if (isset($_REQUEST['save'])) {

            if (isset($_POST['finish-' . $league])) {

                require_once(get_theme_root() . '/fantwist/includes/processing/processing-global/all-processing.php');
                require_once(get_theme_root() . '/fantwist/includes/processing/processing-global/' . $league . '-processing.php');

                process_finish_the_game($stats_key);
            }


            if ($contest_status == "not done") {

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

                $team_id_1 = $_REQUEST['team_id_1'];
                $team_id_2 = $_REQUEST['team_id_2'];
                $team_name_1 = $_REQUEST['team_name_1'];
                $team_name_2 = $_REQUEST['team_name_2'];
                $team_1_spread = $_REQUEST['point-spread-team-1'];
                $team_2_spread = $_REQUEST['point-spread-team-2'];
                $team_1_moneyline = $_REQUEST['money-line-team-1'];
                $team_2_moneyline = $_REQUEST['money-line-team-2'];
                $overunder = $_REQUEST['over-under'];
                $betting_status = ($_REQUEST['betting_status'] !== "") ? $_REQUEST['betting_status'] : 0;
                $reason_to_close = isset($_REQUEST['reason_to_close']) ? $_REQUEST['reason_to_close'] : "";

                global $current_user;
                $user_id = $current_user->ID;

                if ($the_query->found_posts > 0) {

                    if ($the_query->have_posts()) {
                        while ($the_query->have_posts()) {
                            $the_query->the_post();
                            $post_id  = $post->ID;

                            $team_1_spread_previous = get_field('point_spread_team_1', $post_id);
                            $team_2_spread_previous = get_field('point_spread_team_2', $post_id);
                            $team_1_moneyline_previous = get_field('money_line_team_1', $post_id);
                            $team_2_moneyline_previous = get_field('money_line_team_2', $post_id);
                            $overunder_previous = get_field('over_under', $post_id);
                            $betting_status_previous = get_field('bidding_status', $post_id);
                            $update_count_previous = (int)get_field('update_count', $post_id);
                            $update_count = $update_count_previous + 1;

                            update_post_meta($post_id, 'point_spread_team_1', $team_1_spread);
                            update_post_meta($post_id, 'money_line_team_1', $team_1_moneyline);
                            update_post_meta($post_id, 'point_spread_team_2', $team_2_spread);
                            update_post_meta($post_id, 'money_line_team_2', $team_2_moneyline);
                            update_post_meta($post_id, 'over_under', $overunder);
                            update_post_meta($post_id, 'bidding_status', $betting_status);
                            update_post_meta($post_id, 'update_count', $update_count);


                            //audit added
                            wp_insert_post(array(
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
                                    'bet_close_reason' => $reason_to_close,
                                    'update_count' => $update_count
                                ),
                            ));
                        }
                    }
                    wp_reset_postdata();
                } else {

                    $team_1_spread_previous = $game->team1->spread;
                    $team_2_spread_previous = $game->team2->spread;
                    $team_1_moneyline_previous = $game->team1->moneyline;
                    $team_2_moneyline_previous = $game->team2->moneyline;
                    $overunder_previous = $game->team2->overunder;
                    $betting_status_previous = 0;

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

                    $new_post_id = wp_insert_post($args);

                    //audit added
                    wp_insert_post(array(
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
                    ));
                }

                header('location:' . home_url() . '/game-details/?contest_id=' . $contest_id . '&game_id=' . $px_game_id);
                exit;
            }
        }

?>

        <div class="content-wrapper py-5">
            <div class="container">
                <form method="post">
                    <div class="form-header mb-4">
                        <h4 class="m-0">Game Details - <?= date('m-d-Y', strtotime($game->game_start)); ?></h4>
                        <div>
                            <a href="<?= home_url() . '/contest-manager/' . $back_url  ?>" class="btn btn-sm btn-outline-secondary ml-2">Back</a>
                            <button type="submit" name="save" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </div>

                    <div class="result-wrap mt-4 contest-manager">
                        <input type="hidden" name="team_id_1" value="<?= $team_id_1 ?>">
                        <input type="hidden" name="team_id_2" value="<?= $team_id_2 ?>">

                        <input type="hidden" name="team_name_1" value="<?= $team_1_name ?>">
                        <input type="hidden" name="team_name_2" value="<?= $team_2_name ?>">


                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center w-15">Picks Status</th>
                                    <th>Team Name</th>
                                    <th class="text-center">Point Spread</th>
                                    <th class="text-center">Over/Under</th>
                                    <th class="text-center">MoneyLine</th>
                                    <th class="text-center">Bets Placed</th>
                                    <th class="text-center">Amount Bet</th>
                                </tr>
                            </thead>

                            <?php

                            if (count((array)$postData) > 0) {
                                $postMeta =  get_post_meta($postData->ID);
                                // if (empty($results)) {
                                $team_1_spread = $postMeta['point_spread_team_1'][0];
                                $team_2_spread = $postMeta['point_spread_team_2'][0];
                                $overunder = $postMeta['over_under'][0];
                                $team_1_moneyline = $postMeta['money_line_team_1'][0];
                                $team_2_moneyline = $postMeta['money_line_team_2'][0];
                                // }
                                $betting_status = $postMeta['bidding_status'][0];
                            }
                            ?>

                            <?php
                            if (!isset($betting_status)) {
                                $team_name_1 = $team_1_name;
                                $team_name_2 = $team_2_name;
                                $betting_status = '0';

                                global $current_user;
                                $user_id = $current_user->ID;
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

                                $new_post_id = wp_insert_post($args);
                            }

                            $team_1_spread = ($team_1_spread == "") ? 0 : $team_1_spread;
                            $team_2_spread = ($team_2_spread == "") ? 0 : $team_2_spread;
                            $overunder = ($overunder == "") ? 0 : $overunder;
                            $team_1_moneyline = ($team_1_moneyline == "") ? 0 : $team_1_moneyline;
                            $team_2_moneyline = ($team_2_moneyline == "") ? 0 : $team_2_moneyline;

                            ?>

                            <tbody>
                                <tr>
                                    <td class="text-center" rowspan="2">
                                        <div class="game-time">
                                            <ul>
                                                <li>
                                                    <i class="fa fa-clock"></i> <?= date('g:ia', strtotime($game->game_start)); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <?php if ($contest_status == "not done") : ?>
                                            <label class="custom-switch">
                                                <input type="checkbox" name="betting_status" id="betting_status" value="0" <?= ($betting_status == 0) ? "checked" : "" ?>>
                                                <span></span>
                                            </label>
                                            <div class="mt-3" id="betting_status_label"><?= ($betting_status == 0) ? "Open for picks" : "Off the board" ?></div>
                                            <input type="hidden" name="reason_to_close" id="reasonToClose">
                                        <?php else : ?>
                                            <div class="btn-wrap">
                                                <?php if ($contest_status == "in progress") : ?>
                                                    <div class="badge badge-danger">In Progress</div>
                                                <?php else : ?>
                                                    <?php if ($betting_status != 3) : ?>
                                                        <div class="badge badge-danger">In Progress</div>
                                                        <div class="mt-2">
                                                            <input type="checkbox" value="1" name="finish-<?= $league ?>"> <label>Settle</label>
                                                        </div>
                                                    <?php else : ?>
                                                        <div class="badge badge-success">Settled</div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= $team_1_name ?>
                                        <div>
                                            <small><?= $projected_or_live . number_format($team_1_projected_score, 2) ?></small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control input-small" name="point-spread-team-1" value="<?= number_format($team_1_spread, 2) ?>" <?= $points_status ?>>
                                    </td>
                                    <td class="text-center" rowspan="2">
                                        <input type="text" class="form-control input-small" name="over-under" value="<?= number_format($overunder, 2) ?>" <?= $points_status ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control input-small" name="money-line-team-1" value="<?= number_format($team_1_moneyline) ?>" <?= $points_status ?>>
                                    </td>
                                    <td class="text-center" rowspan="2">
                                        <strong><?php

                                                if ($final_result == "Open") {
                                                    echo $open_count;
                                                } elseif ($final_result != "Open") {
                                                    echo $settled_count;
                                                }; ?></strong>
                                    </td>
                                    <td class="text-center" rowspan="2">
                                        <strong>$<?= number_format($wagerTotalAmount, 2) ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?= $team_2_name ?>
                                        <div>
                                            <small><?= $projected_or_live . number_format($team_2_projected_score, 2) ?></small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control input-small" name="point-spread-team-2" value="<?= number_format($team_2_spread, 2) ?>" <?= $points_status ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control input-small" name="money-line-team-2" value="<?= number_format($team_2_moneyline) ?>" <?= $points_status ?>>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>

                <?php

                $changeStatus = new WP_Query(array(
                    'post_type'  => 'gameaudit',
                    'posts_per_page' => -1,
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
                ));

                $wagerQuery = new WP_Query(array(
                    'post_type' => 'wager',
                    'posts_per_page' => '-1',
                    'relation' => 'AND',
                    'meta_query' => array(
                        array(
                            'key'     => 'wager_contest',
                            'value'   => $contest_id
                        ),
                        array(
                            'key' => 'wager_game_id',
                            'value'   => $px_game_id
                        ),
                    ),
                ));
              
                if ($changeStatus->have_posts()) {

                    $bets = [];

                    foreach ($changeStatus->posts as $change) {
                        $current_bet = [];

                        $current_bet['change_date'] = $change->post_date;
                        $current_bet['team_1_spread'] = get_field('updated_value_spread_team_1', $change->ID);
                        $current_bet['team_2_spread'] = get_field('updated_value_spread_team_2', $change->ID);
                        $current_bet['team_overunder'] = get_field('updated_value_overunder', $change->ID);
                        $current_bet['team_1_moneyline'] = get_field('updated_value_moneyline_team_1', $change->ID);
                        $current_bet['team_2_moneyline'] = get_field('updated_value_moneyline_team_2', $change->ID);
                        $current_bet['update_count'] = (get_field('update_count', $change->ID) == "") ? 0 : get_field('update_count', $change->ID);

                        $current_bet['spread_change'] = 0;
                        $current_bet['overunder_change'] = 0;
                        $current_bet['moneyline_change'] = 0;

                        $current_bet['team_1_spread_amount'] = 0;
                        $current_bet['team_1_spread_potential_winnings'] = 0;
                        $current_bet['team_2_spread_amount'] = 0;
                        $current_bet['team_2_spread_potential_winnings'] = 0;
                        $current_bet['team_1_overunder_amount'] = 0;
                        $current_bet['team_1_overunder_potential_winnings'] = 0;
                        $current_bet['team_2_overunder_amount'] = 0;
                        $current_bet['team_2_overunder_potential_winnings'] = 0;
                        $current_bet['team_1_moneyline_amount'] = 0;
                        $current_bet['team_1_moneyline_potential_winnings'] = 0;
                        $current_bet['team_2_moneyline_amount'] = 0;
                        $current_bet['team_2_moneyline_potential_winnings'] = 0;

                        $current_bet['team_1_spread_count'] = 0;
                        $current_bet['team_2_spread_count'] = 0;
                        $current_bet['team_1_overunder_count'] = 0;
                        $current_bet['team_2_overunder_count'] = 0;
                        $current_bet['team_1_moneyline_count'] = 0;
                        $current_bet['team_2_moneyline_count'] = 0;
                       



                        foreach ($wagerQuery->posts as $wager) {
                            $wager_result = get_field('wager_result', $wager->ID);
                            if ($wager_result == "Open") {
                              
                                $wager_type = get_field('wager_type', $wager->ID);
                                $wager_team_id = get_field('wager_team_id', $wager->ID);
                                $wager_amount = get_field('wager_amount', $wager->ID);
                                $wager_potential_winnings = get_field('potential_winnings', $wager->ID);
                                $wager_update_count = (get_field('update_count', $wager->ID) == "") ? 0 : get_field('update_count', $wager->ID);

                                if ($current_bet['update_count'] == $wager_update_count) {
                                    if ($wager_type == "Spread") {
                                        if ($term_id_1 == $wager_team_id) {

                                            if ($current_bet['team_1_spread'] == get_field('point_spread', $wager->ID)) {
                                                $current_bet['team_1_spread_count'] += 1 ;
                                                $current_bet['team_1_spread_amount'] += (float)$wager_amount;
                                                $current_bet['team_1_spread_potential_winnings'] += (float)$wager_potential_winnings;
                                                $current_bet['spread_change'] = 1;
                                            }
                                        }
                                        if ($term_id_2 == $wager_team_id) {
                                            if ($current_bet['team_2_spread'] == get_field('point_spread', $wager->ID)) {
                                                $current_bet['team_2_spread_count'] += 1;
                                                $current_bet['team_2_spread_amount'] += (float)$wager_amount;
                                                $current_bet['team_2_spread_potential_winnings'] += (float)$wager_potential_winnings;
                                                $current_bet['spread_change'] = 1;
                                            }
                                        }
                                    }

                                    if ($wager_type == "Over/Under") {
                                        if ($term_id_1 == $wager_team_id) {
                                            if ($current_bet['team_overunder'] == get_field('wager_overunder', $wager->ID)) {
                                                $current_bet['team_1_overunder_count'] += 1 ;
                                                $current_bet['team_1_overunder_amount'] += (float)$wager_amount;
                                                $current_bet['team_1_overunder_potential_winnings'] += (float)$wager_potential_winnings;
                                                $current_bet['overunder_change'] = 1;
                                            }
                                        }
                                        if ($term_id_2 == $wager_team_id) {
                                            if ($current_bet['team_overunder'] == get_field('wager_overunder', $wager->ID)) {
                                                $current_bet['team_2_overunder_count'] += 1 ;
                                                $current_bet['team_2_overunder_amount'] += (float)$wager_amount + 6;
                                                $current_bet['team_2_overunder_potential_winnings'] += (float)$wager_potential_winnings;
                                                $current_bet['overunder_change'] = 1;
                                            }
                                        }
                                    }

                                    if ($wager_type == "Moneyline") {
                                        if ($term_id_1 == $wager_team_id) {
                                            if ($current_bet['team_1_moneyline'] == get_field('wager_moneyline', $wager->ID)) {
                                                $current_bet['team_1_moneyline_count'] += 1 ;
                                                $current_bet['team_1_moneyline_amount'] += (float)$wager_amount;
                                                $current_bet['team_1_moneyline_potential_winnings'] += (float)$wager_potential_winnings;
                                                $current_bet['moneyline_change'] = 1;
                                            }
                                        }
                                        if ($term_id_2 == $wager_team_id) {
                                            if ($current_bet['team_2_moneyline'] == get_field('wager_moneyline', $wager->ID)) {
                                                $current_bet['team_2_moneyline_count'] += 1 ;
                                                $current_bet['team_2_moneyline_amount'] += (float)$wager_amount;
                                                $current_bet['team_2_moneyline_potential_winnings'] += (float)$wager_potential_winnings;
                                                $current_bet['moneyline_change'] = 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $current_bet['team_1_spread_percentage'] = $current_bet['team_1_spread_amount'] / (($current_bet['team_1_spread_amount'] + $current_bet['team_2_spread_amount']) != 0 ? $current_bet['team_1_spread_amount'] + $current_bet['team_2_spread_amount'] : 1);
                        $current_bet['team_2_spread_percentage'] = $current_bet['team_2_spread_amount'] / (($current_bet['team_1_spread_amount'] + $current_bet['team_2_spread_amount']) != 0 ? $current_bet['team_1_spread_amount'] + $current_bet['team_2_spread_amount'] : 1);
                        $current_bet['team_1_overunder_percentage'] = $current_bet['team_1_overunder_amount'] / (($current_bet['team_1_overunder_amount'] + $current_bet['team_2_overunder_amount']) != 0 ? $current_bet['team_1_overunder_amount'] + $current_bet['team_2_overunder_amount'] : 1);
                        $current_bet['team_2_overunder_percentage'] = $current_bet['team_2_overunder_amount'] / (($current_bet['team_1_overunder_amount'] + $current_bet['team_2_overunder_amount']) != 0 ? $current_bet['team_1_overunder_amount'] + $current_bet['team_2_overunder_amount'] : 1);
                        $current_bet['team_1_moneyline_percentage'] = $current_bet['team_1_moneyline_amount'] / (($current_bet['team_1_moneyline_amount'] + $current_bet['team_2_moneyline_amount']) != 0 ? $current_bet['team_1_moneyline_amount'] + $current_bet['team_2_moneyline_amount'] : 1);
                        $current_bet['team_2_moneyline_percentage'] = $current_bet['team_2_moneyline_amount'] / (($current_bet['team_1_moneyline_amount'] + $current_bet['team_2_moneyline_amount']) != 0 ? $current_bet['team_1_moneyline_amount'] + $current_bet['team_2_moneyline_amount'] : 1);

                        if ($current_bet['team_1_spread_amount'] != 0 || $current_bet['team_2_spread_amount'] != 0 || $current_bet['team_1_overunder_amount'] != 0 || $current_bet['team_2_overunder_amount'] != 0 || $current_bet['team_1_moneyline_amount'] != 0 || $current_bet['team_2_moneyline_amount'] != 0) {
                            array_push($bets, $current_bet);
                        }
                    }

                    //Sort games by time
                    $sort = array();
                    foreach ($bets as $key => $part) {
                        $sort[$key] = strtotime($part['change_date']);
                    }
                    array_multisort($sort, SORT_ASC, $bets);

                    // $bets = array_reverse($bets);
                }

                if (empty($bets)) {
                    $bets = array(array('count' => 1, 'change_date' => date('Y-m-d g:i:s a', current_time('timestamp')), 'team_1_spread' => $team_1_spread, 'team_2_spread' => $team_2_spread, 'team_overunder' => $overunder, 'team_1_moneyline' => $team_1_moneyline, 'team_2_moneyline' => $team_2_moneyline, 'team_1_spread_amount' => 0, 'team_1_spread_potential_winnings' => 0, 'team_2_spread_amount' => 0, 'team_2_spread_potential_winnings' => 0, 'team_1_overunder_amount' => 0, 'team_1_overunder_potential_winnings' => 0, 'team_2_overunder_amount' => 0, 'team_2_overunder_potential_winnings' => 0, 'team_1_moneyline_amount' => 0, 'team_1_moneyline_potential_winnings' => 0, 'team_2_moneyline_amount' => 0, 'team_2_moneyline_potential_winnings' => 0, 'team_1_spread_percentage' => 0, 'team_2_spread_percentage' => 0, 'team_1_overunder_percentage' => 0, 'team_2_overunder_percentage' => 0, 'team_1_moneyline_percentage' => 0, 'team_2_moneyline_percentage' => 0));
                }

                ?>


                <?php
                $spread_count_team1_total_bet = 0;
                $spread_count_team2_total_bet = 0;
                $moneyline_count_team1_total_bet = 0;
                $moneyline_count_team2_total_bet = 0;
                $overunder_count_team1_total_bet = 0;
                $overunder_count_team2_total_bet = 0;
                //for total amount
                if ($wagerQuery->have_posts()) {

                    $total_bet = [];
                    $total_result_spread = 0;
                    $total_result_over_under = 0;
                    $total_result_moneyline = 0;

                    foreach ($wagerQuery->posts as $wager) {
                        $wager_result = get_field('wager_result', $wager->ID);
                        $wager_post_id = get_field('wager_post_id', $wager->ID);
                        $wager_type = get_field('wager_type', $wager->ID);
                        $wager_amount = get_field('wager_amount', $wager->ID);
                        $wager_potential_winning = get_field('potential_winnings', $wager->ID);
                        $total_bet['result'] = $wager_result;
                        $total_bet['post_id'] = $wager_post_id;
                        $total_bet['wager_type'] = $wager_type;
                        $total_bet['wager_amount'] = get_field('wager_amount', $total_bet['post_id']);
                        $total_bet['potential_winnings'] = $wager_potential_winning;



                        if ($total_bet['post_id'] && $total_bet['wager_type'] == "Over/Under" && $total_bet['result'] == "Win") {



                            $total_result_over_under -= $total_bet['potential_winnings'];
                        } elseif ($total_bet['post_id'] && $total_bet['wager_type'] == "Over/Under" && $total_bet['result'] == "Loss") {
                            $total_result_over_under += $total_bet['wager_amount'];
                        }

                        if ($total_bet['post_id'] && $total_bet['wager_type'] == "Spread" && $total_bet['result'] == "Win") {



                            $total_result_spread -= $total_bet['potential_winnings'];
                        } elseif ($total_bet['post_id'] && $total_bet['wager_type'] == "Spread" && $total_bet['result'] == "Loss") {
                            $total_result_spread += $total_bet['wager_amount'];
                        }

                        //moneyline win/loss
                        if ($total_bet['post_id'] && $total_bet['wager_type'] == "Moneyline" && $total_bet['result'] == "Win") {



                            $total_result_moneyline -= $total_bet['potential_winnings'];
                        } elseif ($total_bet['post_id'] && $total_bet['wager_type'] == "Moneyline" && $total_bet['result'] == "Loss") {
                            $total_result_moneyline += $total_bet['wager_amount'];
                        }

                        if ($wager_result == "Open") {
                            $wager_type = get_field('wager_type', $wager->ID);
                            $wager_team_id = get_field('wager_team_id', $wager->ID);
                            $wager_amount = get_field('wager_amount', $wager->ID);
                            $wager_potential_winnings = get_field('potential_winnings', $wager->ID);

                            if ($wager_type == "Spread") {
                                if ($term_id_1 == $wager_team_id) {
                                    $spread_count_team1_total_bet++;
                                    $total_bet['team_1_spread_amount'] += (float)$wager_amount;
                                    $total_bet['team_1_spread_potential_winnings'] += (float)$wager_potential_winnings;
                                }
                                if ($term_id_2 == $wager_team_id) {
                                    $spread_count_team2_total_bet++;
                                    $total_bet['team_2_spread_amount'] += (float)$wager_amount;
                                    $total_bet['team_2_spread_potential_winnings'] += (float)$wager_potential_winnings;
                                }
                                // print_r($wager_result);
                                // exit;
                            }

                            if ($wager_type == "Over/Under") {
                                if ($term_id_1 == $wager_team_id) {
                                    $total_bet['over_wager_result_team1'] = $wager_result;
                                    $overunder_count_team1_total_bet++;
                                    $total_bet['team_1_overunder_amount'] += (float)$wager_amount;
                                    $total_bet['team_1_overunder_potential_winnings'] += (float)$wager_potential_winnings;
                                }
                                if ($term_id_2 == $wager_team_id) {
                                    $total_bet['under_wager_result_team2'] = $wager_result;
                                    $overunder_count_team2_total_bet++;
                                    $total_bet['team_2_overunder_amount'] += (float)$wager_amount;
                                    $total_bet['team_2_overunder_potential_winnings'] += (float)$wager_potential_winnings;
                                }
                            }

                            if ($wager_type == "Moneyline") {
                                if ($term_id_1 == $wager_team_id) {
                                    $total_bet['moneyline_wager_result_team1'] = $wager_result;
                                    $moneyline_count_team1_total_bet++;
                                    $total_bet['team_1_moneyline_amount'] += (float)$wager_amount;
                                    $total_bet['team_1_moneyline_potential_winnings'] += (float)$wager_potential_winnings;
                                }
                                if ($term_id_2 == $wager_team_id) {
                                    $total_bet['moneyline_wager_result_team2'] = $wager_result;
                                    $moneyline_count_team2_total_bet++;
                                    $total_bet['team_2_moneyline_amount'] += (float)$wager_amount;
                                    $total_bet['team_2_moneyline_potential_winnings'] += (float)$wager_potential_winnings;
                                }
                            }
                        }
                    }

                    $total_bet['team_1_spread_percentage'] = $total_bet['team_1_spread_amount'] / (($total_bet['team_1_spread_amount'] + $total_bet['team_2_spread_amount']) != 0 ? $total_bet['team_1_spread_amount'] + $total_bet['team_2_spread_amount'] : 1);
                    $total_bet['team_2_spread_percentage'] = $total_bet['team_2_spread_amount'] / (($total_bet['team_1_spread_amount'] + $total_bet['team_2_spread_amount']) != 0 ? $total_bet['team_1_spread_amount'] + $total_bet['team_2_spread_amount'] : 1);
                    $total_bet['team_1_overunder_percentage'] = $total_bet['team_1_overunder_amount'] / (($total_bet['team_1_overunder_amount'] + $total_bet['team_2_overunder_amount']) != 0 ? $total_bet['team_1_overunder_amount'] + $total_bet['team_2_overunder_amount'] : 1);
                    $total_bet['team_2_overunder_percentage'] = $total_bet['team_2_overunder_amount'] / (($total_bet['team_1_overunder_amount'] + $total_bet['team_2_overunder_amount']) != 0 ? $total_bet['team_1_overunder_amount'] + $total_bet['team_2_overunder_amount'] : 1);
                    $total_bet['team_1_moneyline_percentage'] = $total_bet['team_1_moneyline_amount'] / (($total_bet['team_1_moneyline_amount'] + $total_bet['team_2_moneyline_amount']) != 0 ? $total_bet['team_1_moneyline_amount'] + $total_bet['team_2_moneyline_amount'] : 1);
                    $total_bet['team_2_moneyline_percentage'] = $total_bet['team_2_moneyline_amount'] / (($total_bet['team_1_moneyline_amount'] + $total_bet['team_2_moneyline_amount']) != 0 ? $total_bet['team_1_moneyline_amount'] + $total_bet['team_2_moneyline_amount'] : 1);

                    $total_spread_bets_total_bets = $spread_count_team1_total_bet + $spread_count_team2_total_bet;
                    $total_overunder_bets_total_bets = $overunder_count_team1_total_bet + $overunder_count_team2_total_bet;
                    $total_moneyline_bets_total_bets = $moneyline_count_team1_total_bet + $moneyline_count_team2_total_bet;
                    $team_1_total_bet = $spread_count_team1_total_bet + $overunder_count_team1_total_bet + $moneyline_count_team1_total_bet;
                    $team_2_total_bet = $spread_count_team2_total_bet + $overunder_count_team2_total_bet + $moneyline_count_team2_total_bet;
                    $total =  $team_1_total_bet + $team_2_total_bet;
                } else {
                    $total_bet = array('team_1_spread_amount' => 0, 'team_2_spread_amount' => 0, 'team_1_spread_potential_winnings' => 0, 'team_2_spread_potential_winnings' => 0, 'team_1_overunder_amount' => 0, 'team_2_overunder_amount' => 0, 'team_1_overunder_potential_winnings' => 0, 'team_2_overunder_potential_winnings' => 0, 'team_1_moneyline_amount' => 0, 'team_2_moneyline_amount' => 0, 'team_1_moneyline_potential_winnings' => 0, 'team_2_moneyline_potential_winnings' => 0);
                }

                ?>
                <div class="result-wrap mt-4">

                    <h4 class="mb-4 mt-5">Wager Totals</h4>



                    <?php
                    //counter variable
                    $counter = 1;
                    // foreach ($bets as $bet) : 
                    ?>

                    <table class="table table-total table-bordered mb-3 mt-5">
                        <thead>
                            <tr>
                                <th><?= $team_1_name ?></th>
                                <th>Point Spread</th>
                                <th>Over Total</th>
                                <th>Moneyline</th>
                                <th>All Bets</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Amount Bet (Exposure)</strong></td>
                                <td>$<?= number_format($total_bet['team_1_spread_amount'], 2) . " ($" . number_format($total_bet['team_1_spread_potential_winnings'], 2) . ")" ?></td>
                                <td>$<?= number_format($total_bet['team_1_overunder_amount'], 2) . " ($" . number_format($total_bet['team_1_overunder_potential_winnings'], 2) . ")" ?></td>
                                <td>$<?= number_format($total_bet['team_1_moneyline_amount'], 2) . " ($" . number_format($total_bet['team_1_moneyline_potential_winnings'], 2) . ")" ?></td>
                                <td>$<?= number_format($total_bet['team_1_spread_amount'] + $total_bet['team_1_overunder_amount'] + $total_bet['team_1_moneyline_amount'], 2) . " ($" . number_format($total_bet['team_1_spread_potential_winnings'] + $total_bet['team_1_overunder_potential_winnings'] + $total_bet['team_1_moneyline_potential_winnings'], 2) . ")" ?></td>
                            </tr>
                            <tr>
                                <td><strong>% of Amount Bet</strong></td>
                                <td><?= number_format($total_bet['team_1_spread_percentage'] * 100, 0) ?>%</td>
                                <td><?= number_format($total_bet['team_1_overunder_percentage'] * 100, 0) ?>%</td>
                                <td><?= number_format($total_bet['team_1_moneyline_percentage'] * 100, 0) ?>%</td>
                                <td><?= number_format(number_format((($total_bet['team_1_spread_amount'] + $total_bet['team_1_overunder_amount'] + $total_bet['team_1_moneyline_amount'])   /   (($total_bet['team_1_spread_amount'] + $total_bet['team_1_overunder_amount'] + $total_bet['team_1_moneyline_amount'])   + ($total_bet['team_2_spread_amount'] + $total_bet['team_2_overunder_amount'] + $total_bet['team_2_moneyline_amount'])) * 100))) ?>%</td>
                            </tr>
                            <tr>
                                <td><strong>Number of Bets</strong></td>
                                <td><?= $spread_count_team1_total_bet ?></td>
                                <td><?= $overunder_count_team1_total_bet ?></td>
                                <td><?= $moneyline_count_team1_total_bet ?></td>
                                <td><?= $team_1_total_bet ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-total table-bordered mb-3 mt-5">
                        <thead>
                            <tr>
                                <th><?= $team_2_name ?></th>
                                <th>Point Spread</th>
                                <th>Under Total</th>
                                <th>Moneyline</th>
                                <th>All Bets</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Amount Bet (Exposure)</strong></td>
                                <td>$<?= number_format($total_bet['team_2_spread_amount'], 2) . " ($" . number_format($total_bet['team_2_spread_potential_winnings'], 2) . ")" ?></td>
                                <td>$<?= number_format($total_bet['team_2_overunder_amount'], 2) . " ($" . number_format($total_bet['team_2_overunder_potential_winnings'], 2) . ")" ?></td>
                                <td>$<?= number_format($total_bet['team_2_moneyline_amount'], 2) . " ($" . number_format($total_bet['team_2_moneyline_potential_winnings'], 2) . ")" ?></td>
                                <td>$<?= number_format($total_bet['team_2_spread_amount'] + $total_bet['team_2_overunder_amount'] + $total_bet['team_2_moneyline_amount'], 2) . " ($" . number_format($total_bet['team_2_spread_potential_winnings'] + $total_bet['team_2_overunder_potential_winnings'] + $total_bet['team_2_moneyline_potential_winnings'], 2) . ")" ?></td>
                            </tr>
                            <tr>
                                <td><strong>% of Amount Bet</strong></td>
                                <td><?= number_format($total_bet['team_2_spread_percentage'] * 100, 0) ?>%</td>
                                <td><?= number_format($total_bet['team_2_overunder_percentage'] * 100, 0) ?>%</td>
                                <td><?= number_format($total_bet['team_2_moneyline_percentage'] * 100, 0) ?>%</td>
                                <td><?= number_format(number_format((($total_bet['team_2_spread_amount'] + $total_bet['team_2_overunder_amount'] + $total_bet['team_2_moneyline_amount'])   /   (($total_bet['team_1_spread_amount'] + $total_bet['team_1_overunder_amount'] + $total_bet['team_1_moneyline_amount'])   + ($total_bet['team_2_spread_amount'] + $total_bet['team_2_overunder_amount'] + $total_bet['team_2_moneyline_amount'])) * 100))) ?>%</td>
                            </tr>
                            <tr>
                                <td><strong>Number of Bets</strong></td>
                                <td><?= $spread_count_team2_total_bet ?></td>
                                <td><?= $overunder_count_team2_total_bet ?></td>
                                <td><?= $moneyline_count_team2_total_bet ?></td>
                                <td><?= $team_2_total_bet ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-total table-bordered mb-3 mt-5">
                        <thead>
                            <tr>
                                <th>Result</th>
                                <th>Point Spread</th>
                                <th>Over/Under</th>
                                <th>Moneyline</th>
                                <th>All Bets</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php $total_result = $total_result_spread + $total_result_over_under + $total_result_moneyline;

                                

                                ?>
                                <td><strong>Win/Loss</strong></td>
                                <?php if ($total_result_spread < 0) {
                                   

                                ?>
                                    <td class="loss-result-text">- $<?= abs($total_result_spread) ?></td>

                                <?php
                                } elseif ($total_result_spread > 0) {
                                ?>
                                    <td class="win-result-text">$<?= $total_result_spread ?></td>

                                <?php
                                } else {
                                ?>
                                    <td>$<?= 0 ?></td>

                                <?php
                                }
                                if ($total_result_over_under < 0) {
                                ?>
                                    <td class="loss-result-text" ?>- $<?= abs($total_result_over_under)  ?></td>
                                <?php
                                } elseif ($total_result_over_under > 0) {
                                ?>
                                    <td class="win-result-text" ?>$<?= $total_result_over_under  ?></td>
                                <?php
                                } else {
                                ?>
                                    <td>$<?= 0 ?></td>

                                <?php
                                }
                                if ($total_result_moneyline < 0) {
                                ?>
                                    <td class="loss-result-text" ?>- $<?= abs($total_result_moneyline)  ?></td>
                                <?php } elseif ($total_result_moneyline > 0) {
                                ?>
                                    <td class="win-result-text" ?>$<?= $total_result_moneyline  ?></td>
                                <?php } else {
                                ?>
                                    <td>$<?= 0 ?></td>

                                <?php
                                }

                                if ($total_result < 0) {
                                ?>
                                    <td class="all-bets-result">- $<?= abs($total_result) ?></td>
                                <?php
                                } else {
                                ?>
                                    <td class="all-bets-result">$<?= $total_result ?></td>
                                <?php
                                }
                                ?>
                            </tr>
                            <!-- <tr>
                                <td><strong>% of Dollar Amounts</strong></td>
                                <td>100%</td>
                                <td>100%</td>
                                <td>100%</td>
                                <td>100%</td>
                            </tr>
                            <tr>
                                <td><strong>Number of Bets</strong></td>
                                <td><?= $total_spread_bets_total_bets ?></td>
                                <td><?= $total_overunder_bets_total_bets ?></td>
                                <td><?= $total_moneyline_bets_total_bets ?></td>
                                <td><?= $total ?></td>
                            </tr> -->
                        </tbody>
                    </table>

                    <h4 class="mb-3 mt-4">Wager Details</h4>
                    <?php

                    foreach ($bets as $bet) :
                    ?>
                        <div class="row wager-block-header">
                            <div class="col-md-12 text-center">
                                <h5><?= ($counter == 1) ? 'Opening Lines' : 'Updated Lines' ?> <?= date('Y-m-d g:i:s a', strtotime($bet['change_date'])) ?></h5>
                            </div>
                        </div>
                        <div class="betting-score-box">
                            <?php if ($bet['change_date'] != '') { ?>
                            <?php } ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Point Spread <?= $bet['spread_change'] ? '<span class="wager-change">*</span>' : ''; ?></strong>
                                    <div class="mt-2"> <?= $team_1_name . ": " . ($bet['team_1_spread'] > 0 ? "+" : '') . number_format($bet['team_1_spread'], 2) ?> </div>
                                    <div class="mt-2"> <?= $team_2_name . ": " . ($bet['team_2_spread'] > 0 ? "+" : '') . number_format($bet['team_2_spread'], 2) ?> </div>
                                </div>
                                <div class="col-md-4">
                                    <strong>Over / Under <?= $bet['overunder_change'] ? '<span class="wager-change">*</span>' : ''; ?></strong>
                                    <div class="mt-2"> <?= number_format($bet['team_overunder'], 2) ?></div>
                                </div>
                                <div class="col-md-4">
                                    <strong>Moneyline <?= $bet['moneyline_change'] ? '<span class="wager-change">*</span>' : ''; ?></strong>
                                    <div class="mt-2"> <?= $team_1_name . ": " . ($bet['team_1_moneyline'] > 0 ? "+" : '') . number_format($bet['team_1_moneyline']) ?> </div>
                                    <div class="mt-2"> <?= $team_2_name . ": " . ($bet['team_2_moneyline'] > 0 ? "+" : '') . number_format($bet['team_2_moneyline']) ?> </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered mb-3">
                            <thead>
                                <tr>
                                    <th><?= $team_1_name ?></th>
                                    <th>Point Spread</th>
                                    <th>Over Total</th>
                                    <th>Moneyline</th>
                                    <th>All Bets</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Dollar Amount Bets</strong></td>
                                    <td>$<?= number_format($bet['team_1_spread_amount'], 2) . " ($" . number_format($bet['team_1_spread_potential_winnings'], 2) . ")" ?></td>
                                    <td>$<?= number_format($bet['team_1_overunder_amount'], 2) . " ($" . number_format($bet['team_1_overunder_potential_winnings'], 2) . ")" ?></td>
                                    <td>$<?= number_format($bet['team_1_moneyline_amount'], 2) . " ($" . number_format($bet['team_1_moneyline_potential_winnings'], 2) . ")" ?></td>
                                    <td>$<?= number_format($bet['team_1_spread_amount'] + $bet['team_1_overunder_amount'] + $bet['team_1_moneyline_amount'], 2) . " ($" . number_format($bet['team_1_spread_potential_winnings'] + $bet['team_1_overunder_potential_winnings'] + $bet['team_1_moneyline_potential_winnings'], 2) . ")" ?></td>
                                </tr>
                                <tr>
                                    <td><strong>% of Dollar Amounts</strong></td>
                                    <td><?= number_format($bet['team_1_spread_percentage'] * 100, 0) ?>%</td>
                                    <td><?= number_format($bet['team_1_overunder_percentage'] * 100, 0) ?>%</td>
                                    <td><?= number_format($bet['team_1_moneyline_percentage'] * 100, 0) ?>%</td>
                                    <td><?= number_format(number_format((($bet['team_1_spread_amount'] + $bet['team_1_overunder_amount'] + $bet['team_1_moneyline_amount'])   /   (($bet['team_1_spread_amount'] + $bet['team_1_overunder_amount'] + $bet['team_1_moneyline_amount'])   + ($bet['team_2_spread_amount'] + $bet['team_2_overunder_amount'] + $bet['team_2_moneyline_amount'])) * 100))) ?>%</td>
                                </tr>
                                <tr>
                                <td><strong>Number of Bets</strong></td>
                                <td><?= isset($bet['team_1_spread_count']) ? $bet['team_1_spread_count'] : '0' ?></td>
                                <td><?= isset($bet['team_1_overunder_count']) ? $bet['team_1_overunder_count'] : '0' ?></td>
                                <td><?= isset($bet['team_1_moneyline_count']) ? $bet['team_1_moneyline_count'] : '0' ?></td>
                                <td><?= $bet['team_1_spread_count'] + $bet['team_1_overunder_count'] + $bet['team_1_moneyline_count'] ?></td>
                            </tr>
                        
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-3 mb-5">
                            <thead>
                                <tr>
                                    <th><?= $team_2_name ?></th>
                                    <th>Point Spread</th>
                                    <th>Under Total</th>
                                    <th>Moneyline</th>
                                    <th>All Bets</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Dollar Amount Bets</strong></td>
                                    <td>$<?= number_format($bet['team_2_spread_amount'], 2) . " ($" . number_format($bet['team_2_spread_potential_winnings'], 2) . ")" ?></td>
                                    <td>$<?= number_format($bet['team_2_overunder_amount'], 2) . " ($" . number_format($bet['team_2_overunder_potential_winnings'], 2) . ")" ?></td>
                                    <td>$<?= number_format($bet['team_2_moneyline_amount'], 2) . " ($" . number_format($bet['team_2_moneyline_potential_winnings'], 2) . ")" ?></td>
                                    <td>$<?= number_format($bet['team_2_spread_amount'] + $bet['team_2_overunder_amount'] + $bet['team_2_moneyline_amount'], 2) . " ($" . number_format($bet['team_2_spread_potential_winnings'] + $bet['team_2_overunder_potential_winnings'] + $bet['team_2_moneyline_potential_winnings'], 2) . ")" ?></td>
                                </tr>
                                <tr>
                                    <td><strong>% of Dollar Amounts</strong></td>
                                    <td><?= number_format($bet['team_2_spread_percentage'] * 100, 0) ?>%</td>
                                    <td><?= number_format($bet['team_2_overunder_percentage'] * 100, 0) ?>%</td>
                                    <td><?= number_format($bet['team_2_moneyline_percentage'] * 100, 0) ?>%</td>
                                    <td><?= number_format(number_format((($bet['team_2_spread_amount'] + $bet['team_2_overunder_amount'] + $bet['team_2_moneyline_amount'])   /   (($bet['team_1_spread_amount'] + $bet['team_1_overunder_amount'] + $bet['team_1_moneyline_amount'])   + ($bet['team_2_spread_amount'] + $bet['team_2_overunder_amount'] + $bet['team_2_moneyline_amount'])) * 100))) ?>%</td>
                                </tr>
                                <tr>
                                <td><strong>Number of Bets</strong></td>
                                <td><?= isset($bet['team_2_spread_count']) ? $bet['team_2_spread_count'] : '0' ?></td>
                                <td><?= isset($bet['team_2_overunder_count']) ? $bet['team_2_overunder_count'] : '0' ?></td>
                                <td><?= isset($bet['team_2_moneyline_count']) ? $bet['team_2_moneyline_count'] : '0' ?></td>
                                <td><?= $bet['team_2_spread_count'] + $bet['team_2_overunder_count'] + $bet['team_2_moneyline_count'] ?></td>
                            </tr>
                            </tbody>
                        </table>


                    <?php $counter++;
                    endforeach; ?>


                </div>

            </div>
        </div>

<?php     }
    wp_reset_query();
} ?>

<script>
    jQuery(document).ready(function() {
        jQuery('input[name ="point-spread-team-1"]').bind('input', function() {
            let for_team1 = jQuery('input[name ="point-spread-team-1"]').val();
            if (for_team1.match(/-*/) !== "-") {
                jQuery('input[name ="point-spread-team-2"]').val(-(for_team1));
            } else {
                jQuery('input[name ="point-spread-team-2"]').val((for_team1));
            }
        });
        jQuery('input[name ="point-spread-team-2"]').bind('input', function() {
            let for_team2 = jQuery('input[name ="point-spread-team-2"]').val();
            if (for_team2.match(/-*/) !== "-") {
                jQuery('input[name ="point-spread-team-1"]').val(-(for_team2));
            } else {
                jQuery('input[name ="point-spread-team-1"]').val((for_team2));
            }

        });
        jQuery('#betting_status').on('click', function(e) {

            if (!jQuery('#betting_status').prop('checked')) {
                var reasonToClose = prompt("Reason to close the bet ?");
                if (!reasonToClose)
                    return false;
            }

            jQuery('#betting_status').on('change', function() {
                if (jQuery('#betting_status').prop('checked')) {
                    jQuery('#betting_status_label').text("Open for betting");
                    jQuery('#reasonToClose').removeAttr("value");
                } else {
                    jQuery('#betting_status_label').text("Off the board");
                    jQuery('#reasonToClose').val(reasonToClose);
                }
            });

            return true;
        });
    });
</script>
<?php get_footer('custom'); ?>