<?php /* Template Name: Admin Contest Manager */ ?>
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

    header("Location: " . home_url($path = '/admin'));
    exit;
}
?>
<?php get_header('custom'); ?>

<?php

$league_type = $_GET['league_type'];

if ($league_type == 'nfl') {

    // Get NFL Weeks
    $args = array(
        'post_type' => 'contest',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'contest_status',
                'value'   => 'Open',
            ),
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
            )
        ),
        'order'                => 'ASC',
        'orderby'            => 'meta_value',
        'meta_key'            => 'contest_date_sort',
        'meta_type'            => 'DATETIME'
    );

    $the_query = new WP_Query($args);
    $nfl_season_list = [];
    $nfl_week_list = [];
    if ($the_query->have_posts()) {
        $i = 0;
        while ($the_query->have_posts()) {
            $the_query->the_post();

            $nfl_main_contest = get_field('nfl_main_contest', $post->ID);
            if ($nfl_main_contest == '') {
                $league = get_the_terms($post->ID, 'league');
                $league_id = $league[0]->term_id;
                $league_name = $league[0]->name;
                $league_slug = $league[0]->slug;
                $league_logo = get_field('league_logo', 'league_' . $league_id);
                $contest_type = get_field('contest_type', $post->ID);
                $coming_soon = false;
                $contest_date_1 = date('g:i a', get_field('contest_date', $post->ID));
                $contest_date_2 = get_field('contest_date', $post->ID);
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
                $locked = '';
                if ($contest_status == 'Closed') {
                    $locked = 'locked';
                    $status_html = '<div class="contest-begins">Completed</div>';
                } else if ($contest_status == 'In Progress') {
                    $locked = 'in-progress';
                    $status_html = '<div class="contest-begins">In Progress <i class="fas fa-lock"></i></div>';
                } else {
                    if ($force_lock_unlock == 'Force Unlock') {
                        $status_html = '<div class="contest-begins">In Progress <i class="fas fa-lock"></i></div>';
                    } else {
                        $status_html = '<div class="contest-begins">Begins in <strong>' . $offset . '</strong></div>';
                    }
                }
                $permalink = get_permalink();
                $title = str_replace('Game Lines', 'Fantasy Picks Lines', str_replace('Regular Season', '', get_field('contest_title_without_type')));

                $title = str_replace("Lines", "", $title);

                if ($coming_soon) {
                    $permalink = 'javascript:void(0);';
                }
                $categories  = get_the_terms($post->ID, 'schedule');

                if ($categories && count($categories) > 0) :
                    foreach ($categories as $category) {

                        if (strpos($category->name, 'Pre') !== false) {
                            array_push($nfl_season_list, $category->name);
                        }

                        if (strpos($category->name, 'Season') !== false) {
                            array_push($nfl_season_list, $category->name);
                        }

                        if (strpos($category->name, 'Post') !== false) {
                            array_push($nfl_season_list, $category->name);
                        }

                        if (strpos($category->name, 'Week') !== false) {
                            array_push($nfl_week_list, $category->name);
                        }

                        // if (strpos($category->name, 'Week') !== false) {
                        //     if(!in_array($category->name,$contestWeeks)){
                        //         $contestWeeks[$i]['week_name'] = $category->name;
                        //     }
                        // }

                        if ($_GET['season'] == $category->name) {
                            $selectedSeason = $category->name;
                        }
                        if ($_GET['week'] == $category->name) {
                            $selectedWeek = $category->name;
                        }
                    }
                endif;
                $i++;
            }
        }
    }

    wp_reset_query();

    if (count($nfl_season_list) == 0 || count($nfl_week_list) == 0) {
        $contestWeeks['season_name'][0] = "No contests found!";
        $contestWeeks['week_name'][0] = "No contests found!";
    }

    // Find Games
    if (isset($_GET['week']) && !empty($_GET['week'])) {
        $nfl_season = $selectedSeason;
        $nfl_week = $selectedWeek;
    } else {
        $nfl_season = $contestWeeks['season_name'][0];
        $nfl_week = $contestWeeks['week_name'][0];
    }

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
    $main_contest_status;
    wp_reset_query();

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
        'order'                => 'ASC',
        'orderby'            => 'meta_value',
        'meta_key'            => 'contest_date_sort',
        'meta_type'            => 'DATETIME',
        'post__not_in'        => $exclude_post,
    );
    $the_query = new WP_Query($args);
} else {
    // Other than NFL
    $contest_date = $_GET['date'];

    $league_name = $_GET['league_name'];

    if (!empty($contest_date)) {
        $contest_date = date("Y-m-d", strtotime($contest_date));
    } else {
        $contest_date = date("Y-m-d");
    }
    $contest_date_format = date('m/d/Y', strtotime($contest_date));


    $args = array(
        'post_type' => 'contest',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key'     => 'contest_date_sort',
                'value'   => $contest_date,
                'compare' => 'LIKE'
            )
        ),
        'tax_query' => array(
            array(
                'taxonomy' => 'league',
                'field'    => 'slug',
                'terms'    => $league_type,
            )
        ),
        'order'                => 'ASC',
        'orderby'            => 'meta_value',
        'meta_key'            => 'contest_date_sort',
        'meta_type'            => 'DATETIME'
    );
    $the_query = new WP_Query($args);
}

$terms = get_terms(array(
    'taxonomy' => 'league',
    'hide_empty' => false,
));

?>
<div class="main-wrapper contest-manager">
    <div class="content-wrapper py-5">
        <div class="container">
            <h4 class="mb-4"><?php the_title(); ?></h4>
            <form>
                <div class="filters-wrap">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="league_type" class="form-control league_type_box">
                                    <option> Sport Type </option>
                                    <?php
                                    if (count($terms) > 0) {
                                        foreach ($terms as $term) {

                                            $selected = ($term->slug == $league_type) ? "selected='selected'" : "";
                                    ?>
                                            <option <?= $selected ?> value="<?= $term->slug; ?>"><?= $term->name; ?></option>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <?php

                        if ($league_type == 'nfl') {
                            $hideOtherFields = "d-none";
                            $hideNflFields = "";
                        } else {
                            $hideOtherFields = "";
                            $hideNflFields = "d-none";
                        }
                        ?>

                        <?php
                        if ($league_type == 'nfl') {
                            //change merge season and weeks
                            $nfl_weeks = [];
                            if (count($nfl_week_list) != 0) {
                                for ($i = 0; $i < count($nfl_week_list); $i++) {
                                    array_push($nfl_weeks, array('season' => $nfl_season_list[$i], 'week' => $nfl_week_list[$i]));
                                }
                            }
                            $nfl_week_list = $nfl_weeks;
                        }
                        ?>

                        <div class="col-md-4 <?= $hideNflFields ?> hideNflFields filter-fields">
                            <div class="form-group">
                                <select name="season" class="form-control">
                                    <option value="0">Season</option>

                                    <?php
                                    $nfl_season_list = array_unique($nfl_season_list);
                                    sort($nfl_season_list);
                                    foreach ($nfl_season_list as $season_name) : ?>
                                        <option><?= $season_name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 <?= $hideNflFields ?> hideNflFields filter-fields">
                            <div class="form-group">
                                <select name="week" class="form-control">
                                    <option value="0">Week</option>

                                    <?php
                                    //sort
                                    foreach ($nfl_week_list as $key => $part) {
                                        $sort[$key] = ($part['week']);
                                    }
                                    array_multisort($sort, SORT_ASC, $nfl_week_list);

                                    foreach ($nfl_week_list as $week) : ?>
                                        <option data-season="<?= $week['season'] ?>"><?= $week['week'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 <?= $hideOtherFields ?> hideOtherFields filter-fields">
                            <div class="form-group">
                                <input type="text" name="date" value="<?= $contest_date_format ?>" id="contest-date" class="form-control"><span class="calender-css"><i class="far fa-calendar-alt"></i></span>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary btn-lg btn-block"><span class="fa fa-search"></span></button>
                        </div>
                    </div>
                </div>
            </form>

            <?php
            if ($league_type == 'nfl') {

                // NFL games

                $args = array(
                    'post_type' => 'contest',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'schedule',
                            'field'    => 'name',
                            'terms'    => $nfl_season
                        ),
                        array(
                            'taxonomy' => 'schedule',
                            'field'    => 'name',
                            'terms'    => $nfl_week
                        ),
                    ),
                );

                $the_query = new WP_Query($args);

                $main_contest_id = get_field('nfl_main_contest', $the_query->posts[0]->ID);
                $main_contest_posts = get_post($main_contest_id);
                if ($the_query->have_posts()) {
                    while ($the_query->have_posts()) {
                        $the_query->the_post();

                        $main_contest_id = get_field('nfl_main_contest', $post->ID);
                        if (!empty($main_contest_id))
                            break;
                    }
                }

                if (!empty($main_contest_id)) :

                    echo '<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Picks Status</th>
                                <th class="text-left">Team Name</th>
                                <th class="text-center">Point Spread</th>
                                <th class="text-center">Over/Under</th>
                                <!-- <th class="text-center">MoneyLine</th> -->
                                <th class="text-center">Bets Placed</th>
                                <th class="text-center">Amount Bet</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>';


                    $post = get_post($main_contest_id);


                    $contest_post_id = $post->ID;

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

                        $contest_status_html = 'Open for picks';
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
                    }

                    $results = get_field('contest_results', $post->ID);

                    if (!empty($results)) {
                        $contest_data = json_decode($results, false, JSON_UNESCAPED_UNICODE);
                        $contest_data_projection = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
                    } else {
                        $contest_data = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
                    }

                    //Sort games by time

                    $sort = array();
                    if (isset($contest_data) && is_array($contest_data) && count($contest_data) > 0) {
                        foreach ($contest_data as $key => $part) {
                            $sort[$key] = strtotime($part->game_start);
                        }
                        array_multisort($sort, SORT_ASC, $contest_data);

                        $last_game_date = $sort[count($sort) - 1];

                        //Build output from team data

                        $game_count = 1;
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

                            $game_id = $game->game_id;

                            $team_1_name = $game->team1->name;
                            $team_1_points = $game->team1->total_points;
                            $team_1_projected_score = $game->team1->total_points;
                            $team_1_term_taxonomy = $game->team1->term_taxonomy_id;

                            $team_2_name = $game->team2->name;
                            $team_2_points = $game->team2->total_points;
                            $team_2_projected_score = $game->team2->total_points;
                            $team_2_term_taxonomy = $game->team2->term_taxonomy_id;

                            $home_abbrev = $game->team2->team_abbrev;
                            $current_game_id = $game->game_id;
                            $game_date = $game->game_start;

                            $team_count++;

                            //If the contest has statred but the game isn't
                            if (!empty($results)) {
                                foreach ($contest_data_projection as $projection_data) {
                                    if ($projection_data->game_id == $current_game_id) {
                                        $team_1_rotation = $projection_data->team1->rotation_number;
                                        $team_1_spread = $projection_data->team1->spread;
                                        $team_1_moneyline = $projection_data->team1->moneyline;

                                        $team_2_rotation = $projection_data->team2->rotation_number;
                                        $team_2_spread = $projection_data->team2->spread;
                                        $team_2_moneyline = $projection_data->team2->moneyline;
                                        $overunder = $projection_data->team1->overunder;
                                    }
                                }
                            } else {
                                $team_1_spread = $game->team1->spread;
                                $team_1_moneyline = $game->team1->moneyline;
                                $team_1_rotation = $game->team1->rotation_number;
                                $team_2_spread = $game->team2->spread;
                                $team_2_moneyline = $game->team2->moneyline;
                                $team_2_rotation = $game->team2->rotation_number;
                                $overunder = $game->team2->overunder;
                            }
                            //---------------------------------------------

                            $date_differenece = strtotime(date("d-m-Y", current_time('timestamp'))) - strtotime(date("d-m-Y", strtotime($game_date)));
                            $time_differenece = ((current_time('timestamp') - 60) - strtotime(date("d-m-Y H:i:s", (strtotime($game_date))))) / 60;

                            if ($date_differenece < 0 || $time_differenece < 0) {
                                $game_started = false;
                                $projected_or_live = 'Projected Fantasy Points: ';
                                $status = "Open for Picks";
                                $status_color = "info";
                            } else {
                                $game_started = true;
                                $projected_or_live = 'Live Fantasy Points: ';
                                $status = "In Progress";
                                $status_color = "danger";
                            }



                            $detailsLink = home_url() . '/game-details?contest_id=' . $contest_post_id . '&game_id=' . $game_id;

                            $args = array(
                                'post_type' => 'wager',
                                'meta_query' => array(
                                    'relation' => 'AND',
                                    array(
                                        'key'     => 'wager_contest',
                                        'value'   => $contest_post_id,
                                    ),
                                    array(
                                        'key'     => 'wager_game_id',
                                        'value'   => $game_id,
                                    ),
                                ),
                            );
                            $betsQuery = new WP_Query($args);

                            $betsCount =  $betsQuery->found_posts;
                            $open_counter = [];
                            $settled_counter = [];
                            $settled_count = 0;
                            $open_count = 0;
                            $final_result = [];
                            $wagerAmmount = 0;
                            $rotation_number = '';
                            if ($betsQuery->have_posts()) {
                                while ($betsQuery->have_posts()) {
                                    $betsQuery->the_post();
                                    $wager_result = get_field('wager_result', $post->ID);
                                    $final_result = $wager_result;
                                    $wager_post_id = get_field('wager_post_id', $post->ID);
                                    $rotation_number = get_field('wager_rotation', $post->ID);
                                    array_push($settled_counter, $wager_post_id);
                                    if (!in_array($wagerId, $settled_counter)) {
                                        $open_count++;
                                    } else {
                                        array_push($open_counter, $wagerId);
                                        $settled_count++;
                                    }
                                    $wagerAmmount += get_field('wager_amount', $post->ID);
                                }
                            }
                            wp_reset_query();


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

                                        if (count((array)$postData) > 0) {
                                            $postMeta =  get_post_meta($postData->ID);
                                            $betting_status = $postMeta['bidding_status'][0];
                                            // if(empty($results)){
                                            $team_1_spread = $postMeta['point_spread_team_1'][0];
                                            $team_2_spread = $postMeta['point_spread_team_2'][0];
                                            $overunder = $postMeta['over_under'][0];
                                            $team_1_moneyline = $postMeta['money_line_team_1'][0];
                                            $team_2_moneyline = $postMeta['money_line_team_2'][0];
                                            // }
                                            if ($date_differenece < 0 || $time_differenece < 1) {
                                                $game_started = false;
                                                $projected_or_live = 'Projected Fantasy Points: ';
                                                if ($betting_status == 0) {
                                                    $status = "Open for Picks";
                                                    $status_color = "info";
                                                }
                                                if ($betting_status == 2) {
                                                    $status = "Off the Board";
                                                    $status_color = "warning";
                                                }
                                            } else {
                                                $game_started = true;
                                                $projected_or_live = 'Live Fantasy Points: ';
                                                if ($betting_status == 1) {
                                                    $status = "In Progress";
                                                    $status_color = "danger";
                                                }
                                                if ($betting_status == 3) {
                                                    $status = "Settled";
                                                    $status_color = "success";
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            //If the contest has statred but the game isn't
                            if (!$game_started && !empty($results)) {
                                foreach ($contest_data_projection as $projection_data) {
                                    if ($projection_data->game_id == $current_game_id) {
                                        $team_1_rotation =  $projection_data->team1->rotation_number;
                                        $team_1_projected_score = $projection_data->team1->total_points;
                                        $team_2_rotation =  $projection_data->team2->rotation_number;
                                        $team_2_projected_score = $projection_data->team2->total_points;
                                    }
                                }
                            }
                            //---------------------------------------------


                            if ($game->is_game_over == 1 && $game_started) {
                                $projected_or_live = '<strong>Final: ';
                                $team_1_projected_score .= '</strong>';
                                $team_2_projected_score .= '</strong>';
                            }

                            $team_1_spread = ($team_1_spread == "") ? 0 : $team_1_spread;
                            $team_2_spread = ($team_2_spread == "") ? 0 : $team_2_spread;
                            $overunder = ($overunder == "") ? 0 : $overunder;
                            $team_1_moneyline = ($team_1_moneyline == "") ? 0 : $team_1_moneyline;
                            $team_2_moneyline = ($team_2_moneyline == "") ? 0 : $team_2_moneyline;

                            wp_reset_query();

                            $htmlContent = '<tr>
                                                        <td class="text-center" rowspan="2"><div class="game-time"><ul><li>
                                                        <i class="fa fa-calendar"></i> ' . date('m-d-Y', strtotime($game_date)) . '</li><li>
                                                        <i class="fa fa-clock"></i> ' . date('g:ia', strtotime($game_date)) . '</li>
                                                        </li></ul></div><div class="badge badge-' . $status_color . '">' . $status . '</div></td>
                                                        <td>' . $team_1_rotation . " - " . $team_1_name . '<div><small>' . $projected_or_live . number_format($team_1_projected_score, 2) . '</small></div></td>
                                                        <td class="text-center">' . number_format($team_1_spread, 2) . '</td>
                                                        <td class="text-center" rowspan="2">' . number_format($overunder, 2) . '</td>
                                                        <!--  <td class="text-center">' . number_format($team_1_moneyline) . '</td>-->
                                                        <td class="text-center" rowspan="2"><strong>';

                            if ($final_result == "Open") {
                                $htmlContent .= $open_count;
                            } elseif ($final_result != "Open") {
                                $htmlContent .= $settled_count;
                            }
                            $htmlContent .= '</strong></td>
                                                        <td class="text-center" rowspan="2"><strong>$' . number_format($wagerAmmount, 2) . '</strong></td>
                                                        <td class="text-center" rowspan="2"><a class="btn btn-info btn-sm bg-light" href="' . $detailsLink . '">View Details</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>' . $team_2_rotation . " - " . $team_2_name . '<div><small>' . $projected_or_live . number_format($team_2_projected_score, 2) . '</small></div></td>
                                                        <td class="text-center">' . number_format($team_2_spread, 2) . '</td> 
                                                      <!--  <td class="text-center">' . number_format($team_2_moneyline) . '</td> -->
                                                    </tr>';

                            if ($team_1_name != "" || $team_2_name != "") {
                                echo $htmlContent;
                                $game_count++;
                            }
                        }
                    }

                    echo '</tbody></table>';
                else :
                    echo '<div class="alert alert-info">No results found!</div>';
                endif;
            } else {
                // Other than NFL

                //echo $the_query->found_posts;
                //exit;
                if ($the_query->have_posts()) :

                    echo '<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Picks Status</th>
                                <th class="text-left">Team Name</th>
                                <th class="text-center">Point Spread</th>
                                <th class="text-center">Over/Under</th>
                               <!-- <th class="text-center">MoneyLine</th> -->
                                <th class="text-center">Bets Placed</th>
                                <th class="text-center">Amount Bet</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>';

                    while ($the_query->have_posts()) : $the_query->the_post();

                        $contest_post_id = $post->ID;

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

                            $contest_status_html = 'Open for picks';
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
                        if (isset($contest_data) && is_array($contest_data) && count($contest_data) > 0) {
                            foreach ($contest_data as $key => $part) {
                                $sort[$key] = strtotime($part->game_start);
                            }
                            array_multisort($sort, SORT_ASC, $contest_data);

                            $last_game_date = $sort[count($sort) - 1];

                            //Build output from team data

                            $game_count = 1;

                            foreach ($contest_data as $game) {

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

                                $term_id_1 = $game->team1->term_id;
                                $team_1_name = $game->team1->name;
                                $team_1_points = $game->team1->total_points;
                                $team_1_projected_score = $game->team1->total_points;
                                $team_1_term_taxonomy = $game->team1->term_taxonomy_id;

                                $term_id_2 = $game->team2->term_id;
                                $team_2_name = $game->team2->name;
                                $team_2_points = $game->team2->total_points;
                                $team_2_projected_score = $game->team2->total_points;
                                $team_2_term_taxonomy = $game->team2->term_taxonomy_id;

                                $home_abbrev = $game->team2->team_abbrev;
                                $current_game_id = $game->game_id;
                                $game_date = $game->game_start;

                                $team_count++;

                                //If the contest has statred but the game isn't
                                if (!empty($results)) {
                                    foreach ($contest_data_projection as $projection_data) {
                                        if ($projection_data->game_id == $current_game_id) {
                                            $team_1_rotation = $projection_data->team1->rotation_number;
                                            $team_1_spread = $projection_data->team1->spread;
                                            $team_1_moneyline = $projection_data->team1->moneyline;

                                            $team_2_rotation = $projection_data->team2->rotation_number;
                                            $team_2_spread = $projection_data->team2->spread;
                                            $team_2_moneyline = $projection_data->team2->moneyline;
                                            $overunder = $projection_data->team1->overunder;
                                        }
                                    }
                                } else {
                                    $team_1_spread = $game->team1->spread;
                                    $team_1_moneyline = $game->team1->moneyline;
                                    $team_1_rotation = $game->team1->rotation_number;
                                    $team_2_spread = $game->team2->spread;
                                    $team_2_moneyline = $game->team2->moneyline;
                                    $team_2_rotation = $game->team2->rotation_number;

                                    $overunder = $game->team2->overunder;
                                }
                                //---------------------------------------------

                                $date_differenece = strtotime(date("d-m-Y", current_time('timestamp'))) - strtotime(date("d-m-Y", strtotime($game_date)));
                                $time_differenece = ((current_time('timestamp') - 60) - strtotime(date("d-m-Y H:i:s", (strtotime($game_date))))) / 60;

                                if ($date_differenece < 0) {
                                    $status = "Not Yet Open";
                                    $status_color = "disable";
                                    $projected_or_live = 'Projected Fantasy Points: ';
                                    $game_started = false;
                                } else if ($date_differenece > 0) {
                                    $status = "In Progress";
                                    $status_color = "danger";
                                    $projected_or_live = 'Live Fantasy Points: ';
                                    $game_started = true;
                                } else {
                                    if ($time_differenece >= 1) {
                                        $status = "In Progress";
                                        $status_color = "danger";
                                        $projected_or_live = 'Live Fantasy Points: ';
                                        $game_started = true;
                                    } else {
                                        $status = "Open for picks";
                                        $status_color = "info";
                                        $projected_or_live = 'Projected Fantasy Points: ';
                                        $game_started = false;
                                    }
                                }



                                $detailsLink = home_url() . '/game-details?contest_id=' . $contest_post_id . '&game_id=' . $game_id;

                                $args = array(
                                    'post_type' => 'wager',
                                    'meta_query' => array(
                                        'relation' => 'AND',
                                        array(
                                            'key'     => 'wager_contest',
                                            'value'   => $contest_post_id,
                                        ),
                                        array(
                                            'key'     => 'wager_game_id',
                                            'value'   => $game_id,
                                        ),
                                    ),
                                );
                                $betsQuery = new WP_Query($args);

                                $betsCount =  $betsQuery->found_posts;
                                $wagerAmmount = 0;
                                $open_counter = [];
                                $settled_counter = [];
                                $settled_count = 0;
                                $open_count = 0;
                                $rotation_number = '';
                                $rotation_number_team1 = '';
                                $rotation_number_team2 = '';
                                $wager_team_id = '';
                                $final_result = [];
                                if ($betsQuery->have_posts()) {
                                    while ($betsQuery->have_posts()) {
                                        $betsQuery->the_post();
                                        $wager_result = get_field('wager_result', $post->ID);
                                        $final_result = $wager_result;
                                        $wager_post_id = get_field('wager_post_id', $post->ID);
                                        $wagerTotalAmount += get_field('wager_amount', $post->ID);
                                        array_push($settled_counter, $wager_post_id);
                                        $wager_team_id = get_field('wager_team_id', $post->ID);
                                        $rotation_number = get_field('wager_rotation', $post->ID);
                                        if ($wager_team_id == $term_id_1) {

                                            $rotation_number_team1 = $rotation_number;
                                        } elseif ($wager_team_id == $term_id_2) {
                                            $rotation_number_team2 = $rotation_number;
                                        }

                                        if (!in_array($wagerId, $settled_counter)) {
                                            $open_count++;
                                        } else {
                                            array_push($open_counter, $wagerId);
                                            $settled_count++;
                                        }
                                        $wagerAmmount += get_field('wager_amount', $post->ID);
                                    }
                                }
                                wp_reset_query();


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

                                            if (count((array)$postData) > 0) {
                                                $postMeta =  get_post_meta($postData->ID);
                                                $betting_status = $postMeta['bidding_status'][0];
                                                // if(empty($results)){
                                                $team_1_spread = $postMeta['point_spread_team_1'][0];
                                                $team_2_spread = $postMeta['point_spread_team_2'][0];
                                                $overunder = $postMeta['over_under'][0];
                                                $team_1_moneyline = $postMeta['money_line_team_1'][0];
                                                $team_2_moneyline = $postMeta['money_line_team_2'][0];
                                                // }
                                                if ($date_differenece < 0) {
                                                    $status = "Not Yet Open";
                                                    $status_color = "disable";
                                                    $projected_or_live = 'Projected Fantasy Points: ';
                                                    $game_started = false;
                                                } else {
                                                    if ($time_differenece < 1) {
                                                        $game_started = false;
                                                        if ($betting_status == 0) {
                                                            $status = "Open for Picks";
                                                            $status_color = "info";
                                                            $projected_or_live = 'Projected Fantasy Points: ';
                                                        }
                                                        if ($betting_status == 2) {
                                                            $status = "Off the Board";
                                                            $status_color = "warning";
                                                            $projected_or_live = 'Projected Fantasy Points: ';
                                                        }
                                                    } else {
                                                        $game_started = true;
                                                        if ($betting_status == 1) {
                                                            $status = "In Progress";
                                                            $status_color = "danger";
                                                            $projected_or_live = 'Live Fantasy Points: ';
                                                        }
                                                        if ($betting_status == 3) {
                                                            $status = "Settled";
                                                            $status_color = "success";
                                                            // $projected_or_live = 'Final: ';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                //If the contest has statred but the game isn't
                                if (!$game_started && !empty($results)) {
                                    foreach ($contest_data_projection as $projection_data) {
                                        if ($projection_data->game_id == $current_game_id) {
                                            $team_1_projected_score = $projection_data->team1->total_points;
                                            $team_2_projected_score = $projection_data->team2->total_points;
                                        }
                                    }
                                }
                                //---------------------------------------------


                                if ($game->is_game_over == 1) {
                                    $projected_or_live = '<strong>Final: ';
                                    $team_1_projected_score .= '</strong>';
                                    $team_2_projected_score .= '</strong>';
                                }

                                $team_1_spread = ($team_1_spread == "") ? 0 : $team_1_spread;
                                $team_2_spread = ($team_2_spread == "") ? 0 : $team_2_spread;
                                $overunder = ($overunder == "") ? 0 : $overunder;
                                $team_1_moneyline = ($team_1_moneyline == "") ? 0 : $team_1_moneyline;
                                $team_2_moneyline = ($team_2_moneyline == "") ? 0 : $team_2_moneyline;



                                wp_reset_query();

                                $htmlContent = '<tr>
                                                        <td class="text-center" rowspan="2"><div class="game-time"><ul><li>
                                                        <i class="fa fa-calendar"></i> ' . date('m-d-Y', strtotime($game_date)) . '</li><li>
                                                        <i class="fa fa-clock"></i> ' . date('g:ia', strtotime($game_date)) . '</li>
                                                        </li></ul></div><div class="badge badge-' . $status_color . '">' . $status . '</div></td>
                                                        <td>' . $team_1_rotation . " - " . $team_1_name . '<div><small>' . $projected_or_live . number_format($team_1_projected_score, 2) . '</small></div></td>
                                                        <td class="text-center">' . number_format($team_1_spread, 2) . '</td>
                                                        <td class="text-center" rowspan="2">' . number_format($overunder, 2) . '</td>
                                                       <!-- <td class="text-center">' . number_format($team_1_moneyline) . '</td> -->
                                                        <td class="text-center" rowspan="2"><strong>';

                                if ($final_result == "Open") {
                                    $htmlContent .= $open_count;
                                } elseif ($final_result != "Open") {
                                    $htmlContent .= $settled_count;
                                }
                                $htmlContent .= '</strong></td>
                                                        <td class="text-center" rowspan="2"><strong>$' . number_format($wagerAmmount, 2) . '</strong></td>
                                                        <td class="text-center" rowspan="2"><a class="btn btn-info btn-sm bg-light" href="' . $detailsLink . '">View Details</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td>' . $team_2_rotation . " - " . $team_2_name . '<div><small>' . $projected_or_live . number_format($team_2_projected_score, 2) . '</small></div></td>
                                                        <td class="text-center">' . number_format($team_2_spread, 2) . '</td>
                                                        <!--  <td class="text-center">' . number_format($team_2_moneyline) . '</td> -->
                                                    </tr>';

                                if ($team_1_name != "" || $team_2_name != "") {
                                    echo $htmlContent;
                                    $game_count++;
                                }
                            }
                        }
                    endwhile;
                    echo '</tbody></table>';
                else :
                    echo '<div class="alert alert-info">No results found!</div>';
                endif;
            }
            ?>

        </div>
    </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.js"></script>

<script>
    jQuery(document).ready(function() {
        jQuery('#contest-date').datepicker({

            "autoclose": true
        });

        var league_type_box_first_value = jQuery('.league_type_box').val();

        jQuery('.league_type_box').change(function() {
            var value = jQuery(this).val();
            if (league_type_box_first_value == 'nfl' && value != 'nfl') {
                window.location.href = "<?= home_url() ?>/contest-manager/?league_type=" + value;
            } else if (league_type_box_first_value != 'nfl' && value != 'nfl') {

            } else {
                window.location.href = "<?= home_url() ?>/contest-manager/?league_type=" + value;
            }
        });

        jQuery('select[name="week"] > option').each(function() {
            jQuery(this).hide();

            if (this.value == 0) {
                jQuery(this).show();
            }

        });

        function selectSeason() {
            let url = new URL(document.URL);
            let season = url.searchParams.get('season');
            let week = url.searchParams.get('week');
            jQuery('select[name="season"] > option').each(function() {
                if (season == this.value) {
                    jQuery(this).attr('selected', true)
                }
            });
            selectWeek(season, week)
        }
        selectSeason();

        jQuery('select[name="season"]').on('change', function() {
            selectWeek(this.value, 0)
        });

        function selectWeek(season, selectedWeek, ) {
            jQuery('select[name="week"] > option').each(function() {
                jQuery(this).hide();
                jQuery(this).attr('selected', false);
                if (season == jQuery(this).attr('data-season') || this.value == 0) {
                    jQuery(this).show();
                }
                if (this.value == selectedWeek) {
                    jQuery(this).attr('selected', true);
                }
            });
        }
    })
</script>
<?php get_footer('custom'); ?>