<?php /* Template Name: Admin Detailed Exposure Report */



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

get_header('custom'); ?>

<?php

$searchParams = [];

$league_type = $sportType = $_GET['league_type'];
$searchParams['league_type'] = $sportType;


$terms = get_terms(array(
    'taxonomy' => 'league',
    'hide_empty' => false,
));



if ($league_type == 'nfl') {

    // Get NFL Weeks
    $args = array(
        'post_type' => 'contest',
        'posts_per_page' => -1,
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


                        if (strpos(strtolower($category->name), strtolower('Week')) === false) {
                            array_push($nfl_season_list, $category->name);
                        }

                        if (strpos($category->name, 'Week') !== false) {
                            array_push($nfl_week_list, $category->name);
                        }

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
        // 'meta_key' => 'nfl_main_contest',
        // 'meta_value' => $contest_id,
    );

    $the_query3 = new WP_Query($args);
    if ($the_query3->have_posts()) {
        while ($the_query3->have_posts()) {
            $the_query3->the_post();

            $contest_status = get_field('contest_status', $post->ID);

            // if ($contest_status == 'Open' && $main_contest_status == false) {

            //     $main_contest_status = 'Open';
            // }
        }
    }
    $main_contest_status;
    wp_reset_query();


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

    $contestList = [];

    if (!empty($main_contest_id)) :
        $post = get_post($main_contest_id);
        array_push($contestList, $post->ID);
    endif;

    //page reload on nfl selection
    if(isset($_GET['season']) && isset($_GET['week'])){

        $args = array(
    
            'post_type' => 'contest',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key'     => 'nfl_main_contest',
                    'value'   => $contestList,
                    'compare' => 'IN'
                )
            )
        );
    }
    else{
        $args = [];
    }

    $contest_query = new WP_Query($args);
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
        'order'                => 'ASC',
        'orderby'            => 'meta_value',
        'meta_key'            => 'contest_date_sort',
        'meta_type'            => 'DATETIME'
    );

    if ($league_type) {
        $args['tax_query'] = array(

            array(
                'taxonomy' => 'league',
                'field'    => 'slug',
                'terms'    => $league_type
            )
        );
    }

    $contest_query = new WP_Query($args);

    $contestList = [];
    if ($contest_query->have_posts()) {
        while ($contest_query->have_posts()) {
            $contest_query->the_post();
            $openGamesStatus = get_field('games_status',$post->ID);
            array_push($contestList, $post->ID);
        }
    }
}


$args = array(
    'post_type' => 'wager',
    'paged' => get_query_var('paged'),
    'orderby' => 'meta_value_num',
    'meta_key' => 'contest_date',
    // 'tax_query' => array(
    //     array(
    //         'taxonomy' => 'wager_type',
    //         'field'    => 'slug',
    //         'terms'    => ['spread', 'over-under', 'moneyline']
    //     )
    // )
);

$args['meta_query'] = array(
    array(
        'key' => 'wager_contest',
        'value' => $contestList,
        'compare' => 'IN'
    ),
);

if ($league_type) {
    $args['tax_query'] = array(

        array(
            'taxonomy' => 'league',
            'field'    => 'slug',
            'terms'    => $league_type
        ),
        // array(
        //     'taxonomy' => 'wager_type',
        //     'field'    => 'slug',
        //     'terms'    => ['spread', 'over-under', 'moneyline']
        // )
    );
}

$the_query = new WP_Query($args);


/* Code for summery wagers */

$contest_date = $_GET['date'];

if (!empty($contest_date)) {
    $year = date("Y", strtotime($contest_date));
    $month = date("m", strtotime($contest_date));
    $day = date("d", strtotime($contest_date));
}

//set status variables
$openGamesCount = 0;
$inProgressGamesCount = 0;
$isSettledGamesCount = 0;
// Get games open 
$openGamesArgs = array(
    'post_type' => 'gamedeatils',
    'posts_per_page' => -1,
    // 'date_query' => array(
    //     array(
    //         'year'  => $year,
    //         'month' => $month,
    //         'day'   => $day,
    //     ),
    // ),
    'meta_query' => array(
        array(
            'key' => 'bidding_status',
            'value'    => 0,
            'compare'    => '='
        ),
        array(
            'key' => 'contest_id',
            'value' => $contestList,
            'compare' => 'IN'
        )
    )
);




$openGames = new WP_Query($openGamesArgs);
// $openGamesCountExactValue =  $openGamesCount =  $openGames->post_count;
$openGamesCount +=  $openGames->post_count;
if($openGamesStatus == "Done"){
    $inProgressGamesCount += $openGamesCount;
    $openGamesCount = 0;

}


// Get off the board games 
$offGamesArgs = array(
    'post_type' => 'gamedeatils',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => 'bidding_status',
            'value'    => 2,
            'compare'    => '='
        ),
        array(
            'key' => 'contest_id',
            'value' => $contestList,
            'compare' => 'IN'
        )
    )
);
$offGames = new WP_Query($offGamesArgs);
// $openGamesCountExactValue =  $openGamesCount =  $openGames->post_count;
if($openGamesStatus){
    $inProgressGamesCount += $offGames->post_count;
}

// Get Inprogress games
$inProgressArgs = array(
    'post_type' => 'gamedeatils',
    'posts_per_page' => -1,
    // 'date_query' => array(
    //     array(
    //         'year'  => $year,
    //         'month' => $month,
    //         'day'   => $day,
    //     ),
    // ),
    'meta_query' => array(
        array(
            'key' => 'bidding_status',
            'value'    => 1,
            'compare'    => '='
        ),
        array(
            'key' => 'contest_id',
            'value' => $contestList,
            'compare' => 'IN'
        )
    )
);

$openGamesStatus = get_field('games_status',$post->ID);

$inProgressGames = new WP_Query($inProgressArgs);
$inProgressGamesCount +=  $inProgressGames->post_count;

// Get Inprogress games


// Get Settled games
$settledGamesArgs = array(
    'post_type' => 'gamedeatils',
    'posts_per_page' => -1,
    // 'date_query' => array(
    //     array(
    //         'year'  => $year,
    //         'month' => $month,
    //         'day'   => $day,
    //     ),
    // ),
    'meta_query' => array(
        array(
            'key' => 'bidding_status',
            'value'    => 3,
            'compare'    => '='
        ),
        array(
            'key' => 'contest_id',
            'value' => $contestList,
            'compare' => 'IN'
        )
    )
);
$settledGames = new WP_Query($settledGamesArgs);
$isSettledGamesCount =  $settledGames->post_count;
// Get Settled games

if (strtotime($contest_date) < strtotime(date('Y-m-d'))) {
    $inProgressGamesCount += $openGamesCountExactValue;
}

// Get All games
$args = array(
    'post_type' => ['wager'],
    'meta_query' => array(
        array(
            'key' => 'wager_contest',
            'value' => $contestList,
            'compare' => 'IN'
        )
    ),
    // 'date_query' => array(
    //     array(
    //         'year'  => $year,
    //         'month' => $month,
    //         'day'   => $day,
    //     ),
    // ),
    // 'tax_query' => array(
    //     array(
    //         'taxonomy' => 'wager_type',
    //         'field'    => 'slug',
    //         'terms'    => ['spread', 'over-under', 'moneyline']
    //     )
    // )
);

if ($league_type) {
    $args['tax_query'] = array(

        array(
            'taxonomy' => 'league',
            'field'    => 'slug',
            'terms'    => $league_type
        ),
        // array(
        //     'taxonomy' => 'wager_type',
        //     'field'    => 'slug',
        //     'terms'    => ['spread', 'over-under', 'moneyline']
        // )
    );
}

$fantasyWagers = new WP_Query($args);


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
<?php

if ($league_type == 'nfl') {
    $hideOtherFields = "d-none";
    $hideNflFields = "";
} else {
    $hideOtherFields = "";
    $hideNflFields = "d-none";
}
?>

<div class="content-wrapper pt-5 pb-2">
    <div class="container">
        <a href="<?= home_url() ?>/reports" class="btn  btn btn-outline-primary float-right" style="border: none;">Back to Reports</a>
        <h4 class="mb-4">Detailed Exposure Report</h4>

    </div>
</div>


<!-- Begin Page Content -->
<div class="container">
    <div class="large-reports-wrapper pb-5">
        <!-- Page Heading -->
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row">
                    <form class="form-inline w-100">

                        <div class="ml-3 col-md-3 ">
                            <div class="form-group">
                                <select name="league_type" class="form-control league_type_box w-100">
                                    <option value="">-- Select Sport Type --</option>
                                    <?php
                                    if (count($terms) > 0) {
                                        foreach ($terms as $term) {

                                            $selected = ($term->slug == $sportType) ? "selected='" . $sportType . "'" : "";
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

                        <div class="ml-3 col-md-3 <?= $hideNflFields ?> hideNflFields filter-fields ">
                            <div class="form-group">
                                <select name="season" class="form-control w-100">
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
                        <div class="ml-3 col-md-3  <?= $hideNflFields ?> hideNflFields filter-fields">
                            <div class="form-group">
                                <select name="week" class="form-control w-100">
                                    <option value="0">Week</option>

                                    <?php
                                    //sort
                                    foreach ($nfl_week_list as $key => $part) {
                                        $sort[$key] = ($part);
                                    }

                                    array_multisort($sort, SORT_ASC, $nfl_week_list);

                                    foreach ($nfl_week_list as $week) : ?>
                                        <option data-season="<?= $week['season'] ?>"><?= $week['week'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="ml-3 col-md-3  <?= $hideOtherFields ?> hideOtherFields filter-fields">
                            <div class="form-group">
                                <input type="text" name="date" value="<?= $contest_date_format ?>" id="contest-date" class="form-control"><span class="calender-css"><i class="far fa-calendar-alt"></i></span>
                            </div>
                        </div>

                        <div class="ml-3 col-md-3 form-group pr-3" style="flex:1; justify-content:space-between;">
                            <input type="submit" name="search" value="Search" class="btn btn-primary form-control">
                            <input type="submit" name="export" value="Export" class="btn btn-outline-primary ml-3">

                        </div>
                    </form>
                </div>
            </div>
            <?php
              if (!empty($_GET['league_type']) && empty($_GET['season']) && empty($_GET['week']) && empty($_GET['date'])) {

            ?>
                <div class="main-wager-box">
                    <div class="inner-wager-box bg-secondary text-white">
                        <h5 class="card-title">Games Open for Picks</h5>
                        <h2 class="card-text"><?= 0 ?></h2>
                    </div>
                    <div class="inner-wager-box bg-secondary text-white">
                        <h5 class="card-title">Games in Progress</h5>
                        <h2 class="card-text"><?= 0 ?></h2>
                    </div>
                    <div class="inner-wager-box bg-secondary text-white">
                        <h5 class="card-title">Games Settled</h5>
                        <h2 class="card-text"><?= 0 ?></h2>
                    </div>
                </div>
            <?php


            }
            else  if (empty($_GET['league_type']) && empty($_GET['season']) && empty($_GET['week']) && empty($_GET['date'])) {

                ?>
                    <div class="main-wager-box">
                        <div class="inner-wager-box bg-secondary text-white">
                            <h5 class="card-title">Games Open for Picks</h5>
                            <h2 class="card-text"><?= 0 ?></h2>
                        </div>
                        <div class="inner-wager-box bg-secondary text-white">
                            <h5 class="card-title">Games in Progress</h5>
                            <h2 class="card-text"><?= 0 ?></h2>
                        </div>
                        <div class="inner-wager-box bg-secondary text-white">
                            <h5 class="card-title">Games Settled</h5>
                            <h2 class="card-text"><?= 0 ?></h2>
                        </div>
                    </div>
                <?php
    
    
                }
            else {

            ?>
                <div class="main-wager-box">
                    <div class="inner-wager-box bg-secondary text-white">
                        <h5 class="card-title">Games Open for Picks</h5>
                        <h2 class="card-text"><?= $openGamesCount ?></h2>
                    </div>
                    <div class="inner-wager-box bg-secondary text-white">
                        <h5 class="card-title">Games in Progress</h5>
                        <h2 class="card-text"><?= $inProgressGamesCount ?></h2>
                    </div>
                    <div class="inner-wager-box bg-secondary text-white">
                        <h5 class="card-title">Games Settled</h5>
                        <h2 class="card-text"><?= $isSettledGamesCount ?></h2>
                    </div>
                </div>

                <?php
            }
            if ($contest_query->have_posts()) {
                while ($contest_query->have_posts()) {
                    $contest_query->the_post();

                    $results = get_field('contest_results', $post->ID);

                    // If game other than NFL
                    $contest_id = $post->ID;

                    // If game is NFL
                    if ($league_type == 'nfl') {
                        $contest_id = get_field('nfl_main_contest', $post->ID);
                    }
                    if (!empty($results)) {
                        $contest_data = json_decode($results, false, JSON_UNESCAPED_UNICODE);
                        $contest_data_projection = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
                    } else {
                        $contest_data = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
                    }
                    $contest_data = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
                    foreach ($contest_data as $cData) {

                        $contest_status = get_field('contest_status', $post->ID);
                        $force_lock_unlock = get_field('force_lockunlock', $post->ID);

                        if ($contest_status == 'Open') {
                            if ($force_lock_unlock == 'Force Unlock') {
                                $contest_begins_html = 'In Progress';
                            } else {
                                $contest_begins_html = 'Begins in ' . $offset;
                            }

                            $contest_status_html = 'Open for Picks';
                            $odds_title = '';
                        } else if ($contest_status == 'In Progress') {
                            $contest_begins_html = '';
                            $contest_status_html = 'In Progress';
                            $odds_title = '';
                        } else {
                            $contest_begins_html = '';
                            $contest_status_html = 'Closed';
                            $odds_title = '';
                        }

                        if ($contest_status == 'Closed' || $contest_status == 'In Progress' || $contest_status == 'Finished') {

                            if ($contest_status == 'Closed' || $contest_status == 'Finished') {

                                $contest_status_html = 'Completed';
                            } else {

                                $contest_status_html = 'In Progress';
                            }
                        }


                        $game_id = $cData->game_id;

                        $game_date = $game->game_start;
                        $date_differenece = strtotime(date("d-m-Y", current_time('timestamp'))) - strtotime(date("d-m-Y", strtotime($game_date)));
                        $time_differenece = ((current_time('timestamp') - 60) - strtotime(date("d-m-Y H:i:s", (strtotime($game_date))))) / 60;



                        // Teams Info
                        $team_1_name = $cData->team1->name;
                        //var_dump([$cData]);
                        if (isset($cData->team1->rotation_number)) {
                            $team_1_name = $cData->team1->rotation_number . ' - ' . $team_1_name;
                        }

                        $team_2_name = $cData->team2->name;
                        if (isset($cData->team1->rotation_number)) {
                            $team_2_name = $cData->team2->rotation_number . ' - ' . $team_2_name;
                        }

                        $team_1_id = $cData->team1->term_id;
                        $team_2_id = $cData->team2->term_id;


                        // Get All games
                        $args = array(
                            'post_type' => ['gamedeatils'],
                            'meta_query' => array(
                                array(
                                    'key' => 'contest_id',
                                    'value' => $contest_id,
                                    'compare' => '='
                                ),
                                array(
                                    'key' => 'game_id',
                                    'value' => $game_id,
                                    'compare' => '='
                                )
                            )
                        );


                        $fantasyWagers = new WP_Query($args);

                        if ($fantasyWagers->have_posts()) {
                            while ($fantasyWagers->have_posts()) {
                                $fantasyWagers->the_post();


                                $betting_status = get_field('bidding_status');
                                // if ($bidding_status == 0) {
                                //     $contest_status_html = "Open for betting";
                                // } elseif ($bidding_status == 1) {
                                //     $contest_status_html = "In Progress";
                                // } elseif ($bidding_status == 2) {
                                //     $contest_status_html = "Closed for betting";
                                // } elseif ($bidding_status == 3) {
                                //     $contest_status_html = "Settled";
                                // }
                            }
                        }

                        if (strtotime($contest_date) < strtotime(date('Y-m-d'))) {
                            $contest_status_html = "In Progress";
                        }



                        if ($date_differenece < 0 || $time_differenece < 1) {
                            $game_started = false;
                            $projected_or_live = 'Projected Fantasy Points: ';
                            if ($betting_status == 0) {
                                $contest_status_html = "Open for Picks";
                                $status_color = "info";
                            }
                            if ($betting_status == 2) {
                                $contest_status_html = "Off the Board";
                                $status_color = "warning";
                            }
                        } else {
                            $game_started = true;
                            $projected_or_live = 'Live Fantasy Points: ';
                            if ($betting_status == 1) {
                                $contest_status_html = "In Progress";
                                $status_color = "danger";
                            }
                            if ($betting_status == 3) {
                                $contest_status_html = "Settled";
                                $status_color = "success";
                            }
                        }


                        // Get Wagers
                        $args = array(
                            'post_type' => ['wager'],
                            'date_query' => array(
                                array(
                                    'year'  => $year,
                                    'month' => $month,
                                    'day'   => $day,
                                ),
                            ),
                            'meta_query' => array(
                                array(
                                    'key' => 'wager_contest',
                                    'value' => $contest_id,
                                    'compare' => '='
                                ),
                                array(
                                    'key' => 'wager_game_id',
                                    'value' => $game_id,
                                    'compare' => '='
                                )
                            ),
                            // 'tax_query' => array(
                            //     array(
                            //         'taxonomy' => 'wager_type',
                            //         'field'    => 'slug',
                            //         'terms'    => ['spread', 'over-under', 'moneyline']
                            //     )
                            // )
                        );

                        if ($league_type) {
                            $args['tax_query'] = array(

                                array(
                                    'taxonomy' => 'league',
                                    'field'    => 'slug',
                                    'terms'    => $league_type
                                ),
                                // array(
                                //     'taxonomy' => 'wager_type',
                                //     'field'    => 'slug',
                                //     'terms'    => ['spread', 'over-under', 'moneyline']
                                // )
                            );
                        }


                        $wagers_query = new WP_Query($args);


                        $team_1_wagers = 0;
                        $team_2_wagers = 0;



                        $totalAmountWagered = 0.00;
                        $totalAmountReturned = 0.00;
                        $totalAmountPaid = 0.00;
                        $totalExposure = 0.00;

                        $team_1_SpreadAmountWagered = 0.00;
                        $team_1_MoneylineAmountWagered = 0.00;
                        $team_1_OverUnderAmountWagered = 0.00;

                        $team_1_SpreadAmountPaid = 0.00;
                        $team_1_MoneylineAmountPaid = 0.00;
                        $team_1_OverUnderAmountPaid = 0.00;

                        $team_1_SpreadExposure = 0.00;
                        $team_1_MoneylineExposure = 0.00;
                        $team_1_OverUnderExposure = 0.00;

                        $team_2_SpreadAmountWagered = 0.00;
                        $team_2_MoneylineAmountWagered = 0.00;
                        $team_2_OverUnderAmountWagered = 0.00;

                        $team_2_SpreadAmountPaid = 0.00;
                        $team_2_MoneylineAmountPaid = 0.00;
                        $team_2_OverUnderAmountPaid = 0.00;

                        $team_2_SpreadExposure = 0.00;
                        $team_2_MoneylineExposure = 0.00;
                        $team_2_OverUnderExposure = 0.00;

                        $spread_counter_team1 = 0;
                        $spread_counter_team2 = 0;
                        $over_under_counter_team1 = 0;
                        $over_under_counter_team2 = 0;
                        $moneyline_counter_team1 = 0;
                        $moneyline_counter_team2 = 0;

                        if ($wagers_query->have_posts()) {
                            while ($wagers_query->have_posts()) {
                                $wagers_query->the_post();
                                $wager_team_id = get_field('wager_team_id', $post->ID);

                                $wager_type = get_field('wager_type', $post->ID);
                                $wager_result = strtoupper(get_field('wager_result', $post->ID));

                                if ($team_1_id == $wager_team_id) {


                                    // Wagered Amount

                                    if ($wager_type == 'Spread') {
                                        $point_type = "point_spread";
                                        $team_1_SpreadAmountWagered += floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Moneyline') {
                                        $point_type = "wager_moneyline";
                                        $team_1_MoneylineAmountWagered += floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Over/Under') {
                                        $point_type = "wager_overunder";
                                        $team_1_OverUnderAmountWagered +=  floatval(get_field('wager_amount', $post->ID));
                                    }
                                    // Wagered Amount

                                    // Amount Returned
                                    if ($wager_type == 'Spread' && $wager_result == "PUSH") {
                                        $point_type = "point_spread";
                                        $team_1_SpreadAmountReturned += floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Moneyline' && $wager_result == "PUSH") {
                                        $point_type = "wager_moneyline";
                                        $team_1_MoneylineAmountReturned += floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Over/Under' && $wager_result == "PUSH") {
                                        $point_type = "wager_overunder";
                                        $team_1_OverUnderAmountReturned +=  floatval(get_field('wager_amount', $post->ID));
                                    }
                                    // Amount Returned

                                    // Amount Paid
                                    if ($wager_type == 'Spread' && $wager_result == "WIN") {
                                        $point_type = "point_spread";
                                        $team_1_SpreadAmountPaid += floatval(get_field('potential_winnings', $post->ID))
                                            + floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Moneyline' && $wager_result == "WIN") {
                                        $point_type = "wager_moneyline";
                                        $team_1_MoneylineAmountPaid += floatval(get_field('potential_winnings', $post->ID))
                                            + floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Over/Under' && $wager_result == "WIN") {
                                        $point_type = "wager_overunder";
                                        $team_1_OverUnderAmountPaid +=  floatval(get_field('potential_winnings', $post->ID))
                                            + floatval(get_field('wager_amount', $post->ID));
                                    }
                                    //Amount Paid


                                    // Amount Exposure
                                    if ($wager_type == 'Spread' && $wager_result == "OPEN") {
                                        $point_type = "point_spread";
                                        $team_1_SpreadExposure += floatval(get_field('potential_winnings', $post->ID));
                                    } elseif ($wager_type == 'Moneyline' && $wager_result == "OPEN") {
                                        $point_type = "wager_moneyline";
                                        $team_1_MoneylineExposure += floatval(get_field('potential_winnings', $post->ID));
                                    } elseif ($wager_type == 'Over/Under' && $wager_result == "OPEN") {
                                        $point_type = "wager_overunder";
                                        $team_1_OverUnderExposure +=  floatval(get_field('potential_winnings', $post->ID));
                                    }
                                    if ($wager_type == 'Spread' && $team_1_id == $wager_team_id) {
                                        $spread_counter_team1++;
                                    }
                                    if ($wager_type == 'Moneyline' && $team_1_id == $wager_team_id) {
                                        $moneyline_counter_team1++;
                                    }
                                    if ($wager_type == 'Over/Under' && $team_1_id == $wager_team_id) {
                                        $over_under_counter_team1++;
                                    }
                                    //Amount Exposure

                                } elseif ($team_2_id == $wager_team_id) {

                                    $wager_type = get_field('wager_type', $post->ID);
                                    $wager_result = strtoupper(get_field('wager_result', $post->ID));

                                    // Wagered Amount
                                    if ($wager_type == 'Spread') {
                                        $point_type = "point_spread";
                                        $team_2_SpreadAmountWagered += floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Moneyline') {
                                        $point_type = "wager_moneyline";
                                        $team_2_MoneylineAmountWagered += floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Over/Under') {
                                        $point_type = "wager_overunder";
                                        $team_2_OverUnderAmountWagered +=  floatval(get_field('wager_amount', $post->ID));
                                    }
                                    // Wagered Amount

                                    // Amount Returned
                                    if ($wager_type == 'Spread' && $wager_result == "PUSH") {
                                        $point_type = "point_spread";
                                        $team_2_SpreadAmountReturned += floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Moneyline' && $wager_result == "PUSH") {
                                        $point_type = "wager_moneyline";
                                        $team_2_MoneylineAmountReturned += floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Over/Under' && $wager_result == "PUSH") {
                                        $point_type = "wager_overunder";
                                        $team_2_OverUnderAmountReturned +=  floatval(get_field('wager_amount', $post->ID));
                                    }
                                    // Amount Returned

                                    // Amount Paid
                                    if ($wager_type == 'Spread' && $wager_result == "WIN") {
                                        $point_type = "point_spread";
                                        $team_2_SpreadAmountPaid += floatval(get_field('potential_winnings', $post->ID))
                                            + floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Moneyline' && $wager_result == "WIN") {
                                        $point_type = "wager_moneyline";
                                        $team_2_MoneylineAmountPaid += floatval(get_field('potential_winnings', $post->ID))
                                            + floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Over/Under' && $wager_result == "WIN") {
                                        $point_type = "wager_overunder";
                                        $team_2_OverUnderAmountPaid +=  floatval(get_field('potential_winnings', $post->ID))
                                            + floatval(get_field('wager_amount', $post->ID));
                                    }
                                    //Amount Paid


                                    // Amount Exposure
                                    if ($wager_type == 'Spread' && $wager_result == "OPEN") {
                                        $point_type = "point_spread";
                                        $team_2_SpreadExposure += floatval(get_field('potential_winnings', $post->ID));
                                    } elseif ($wager_type == 'Moneyline' && $wager_result == "OPEN") {
                                        $point_type = "wager_moneyline";
                                        $team_2_MoneylineExposure += floatval(get_field('potential_winnings', $post->ID));
                                    } elseif ($wager_type == 'Over/Under' && $wager_result == "OPEN") {
                                        $point_type = "wager_overunder";
                                        $team_2_OverUnderExposure +=  floatval(get_field('potential_winnings', $post->ID));
                                    }
                                    //Amount Exposure

                                    if ($wager_type == 'Spread' && $team_2_id == $wager_team_id) {
                                        $spread_counter_team2++;
                                    }

                                    if ($wager_type == 'Moneyline' && $team_2_id == $wager_team_id) {
                                        $moneyline_counter_team2++;
                                    }

                                    if ($wager_type == 'Over/Under' && $team_2_id == $wager_team_id) {
                                        $over_under_counter_team2++;
                                    }
                                }
                            }
                        }

                        $team_1_SpreadAmountWagered = number_format($team_1_SpreadAmountWagered, 2, '.', '');
                        $team_2_SpreadAmountWagered = number_format($team_2_SpreadAmountWagered, 2, '.', '');
                        $team_1_MoneylineAmountWagered = number_format($team_1_MoneylineAmountWagered, 2, '.', '');
                        $team_2_MoneylineAmountWagered = number_format($team_2_MoneylineAmountWagered, 2, '.', '');
                        $team_1_OverUnderAmountWagered = number_format($team_1_OverUnderAmountWagered, 2, '.', '');
                        $team_2_OverUnderAmountWagered = number_format($team_2_OverUnderAmountWagered, 2, '.', '');

                        //calculate percentage
                        $team_1_SpreadAmountWagered_percentage = number_format(($team_1_SpreadAmountWagered / ($team_1_SpreadAmountWagered + $team_2_SpreadAmountWagered)) * 100, 2);
                        $team_2_SpreadAmountWagered_percentage = number_format(($team_2_SpreadAmountWagered / ($team_1_SpreadAmountWagered + $team_2_SpreadAmountWagered)) * 100, 2);
                        $team_1_MoneylineAmountWagered_percentage = number_format(($team_1_MoneylineAmountWagered / ($team_1_MoneylineAmountWagered + $team_2_MoneylineAmountWagered)) * 100, 2);
                        $team_2_MoneylineAmountWagered_percentage = number_format(($team_2_MoneylineAmountWagered / ($team_1_MoneylineAmountWagered + $team_2_MoneylineAmountWagered)) * 100, 2);
                        $team_1_OverUnderAmountWagered_percentage = number_format(($team_1_OverUnderAmountWagered / ($team_1_OverUnderAmountWagered + $team_2_OverUnderAmountWagered)) * 100, 2);
                        $team_2_OverUnderAmountWagered_percentage = number_format(($team_2_OverUnderAmountWagered / ($team_1_OverUnderAmountWagered + $team_2_OverUnderAmountWagered)) * 100, 2);

                        $team_1_SpreadAmountWagered_percentage = isInteger($team_1_SpreadAmountWagered_percentage) ? (int) $team_1_SpreadAmountWagered_percentage : $team_1_SpreadAmountWagered_percentage;
                        $team_2_SpreadAmountWagered_percentage = isInteger($team_2_SpreadAmountWagered_percentage) ? (int) $team_2_SpreadAmountWagered_percentage : $team_2_SpreadAmountWagered_percentage;
                        $team_1_MoneylineAmountWagered_percentage = isInteger($team_1_MoneylineAmountWagered_percentage) ? (int) $team_1_MoneylineAmountWagered_percentage : $team_1_MoneylineAmountWagered_percentage;
                        $team_2_MoneylineAmountWagered_percentage = isInteger($team_2_MoneylineAmountWagered_percentage) ? (int) $team_2_MoneylineAmountWagered_percentage : $team_2_MoneylineAmountWagered_percentage;
                        $team_1_OverUnderAmountWagered_percentage = isInteger($team_1_OverUnderAmountWagered_percentage) ? (int) $team_1_OverUnderAmountWagered_percentage : $team_1_OverUnderAmountWagered_percentage;
                        $team_2_OverUnderAmountWagered_percentage = isInteger($team_2_OverUnderAmountWagered_percentage) ? (int) $team_2_OverUnderAmountWagered_percentage : $team_2_OverUnderAmountWagered_percentage;

                        $team_1_SpreadAmountWagered_percentage = is_numeric($team_1_SpreadAmountWagered_percentage) ? $team_1_SpreadAmountWagered_percentage : 0;
                        $team_2_SpreadAmountWagered_percentage = is_numeric($team_2_SpreadAmountWagered_percentage) ? $team_2_SpreadAmountWagered_percentage : 0;
                        $team_1_MoneylineAmountWagered_percentage = is_numeric($team_1_MoneylineAmountWagered_percentage) ? $team_1_MoneylineAmountWagered_percentage : 0;
                        $team_2_MoneylineAmountWagered_percentage = is_numeric($team_2_MoneylineAmountWagered_percentage) ? $team_2_MoneylineAmountWagered_percentage : 0;
                        $team_1_OverUnderAmountWagered_percentage = is_numeric($team_1_OverUnderAmountWagered_percentage) ? $team_1_OverUnderAmountWagered_percentage : 0;
                        $team_2_OverUnderAmountWagered_percentage = is_numeric($team_2_OverUnderAmountWagered_percentage) ? $team_2_OverUnderAmountWagered_percentage : 0;

                        $totalAmountWagered = $team_1_SpreadAmountWagered + $team_2_SpreadAmountWagered
                            + $team_1_OverUnderAmountWagered + +$team_2_OverUnderAmountWagered;

                        $totalAmountWagered = number_format($totalAmountWagered, 2, '.', '');

                        $team_1_SpreadAmountPaid = number_format($team_1_SpreadAmountPaid, 2, '.', '');
                        $team_2_SpreadAmountPaid = number_format($team_2_SpreadAmountPaid, 2, '.', '');
                        $team_1_MoneylineAmountPaid = number_format($team_1_MoneylineAmountPaid, 2, '.', '');
                        $team_2_MoneylineAmountPaid = number_format($team_2_MoneylineAmountPaid, 2, '.', '');
                        $team_1_OverUnderAmountPaid = number_format($team_1_OverUnderAmountPaid, 2, '.', '');
                        $team_2_OverUnderAmountPaid = number_format($team_2_OverUnderAmountPaid, 2, '.', '');

                        //calculate percentage
                        $team_1_SpreadAmountPaid_percentage = number_format(($team_1_SpreadAmountPaid / ($team_1_SpreadAmountPaid + $team_2_SpreadAmountPaid)) * 100, 2);
                        $team_2_SpreadAmountPaid_percentage = number_format(($team_2_SpreadAmountPaid / ($team_1_SpreadAmountPaid + $team_2_SpreadAmountPaid)) * 100, 2);
                        $team_1_MoneylineAmountPaid_percentage = number_format(($team_1_MoneylineAmountPaid / ($team_1_MoneylineAmountPaid + $team_2_MoneylineAmountPaid)) * 100, 2);
                        $team_2_MoneylineAmountPaid_percentage = number_format(($team_2_MoneylineAmountPaid / ($team_1_MoneylineAmountPaid + $team_2_MoneylineAmountPaid)) * 100, 2);
                        $team_1_OverUnderAmountPaid_percentage = number_format(($team_1_OverUnderAmountPaid / ($team_1_OverUnderAmountPaid + $team_2_OverUnderAmountPaid)) * 100, 2);
                        $team_2_OverUnderAmountPaid_percentage = number_format(($team_2_OverUnderAmountPaid / ($team_1_OverUnderAmountPaid + $team_2_OverUnderAmountPaid)) * 100, 2);

                        $team_1_SpreadAmountPaid_percentage = isInteger($team_1_SpreadAmountPaid_percentage) ? (int) $team_1_SpreadAmountPaid_percentage : $team_1_SpreadAmountPaid_percentage;
                        $team_2_SpreadAmountPaid_percentage = isInteger($team_2_SpreadAmountPaid_percentage) ? (int) $team_2_SpreadAmountPaid_percentage : $team_2_SpreadAmountPaid_percentage;
                        $team_1_MoneylineAmountPaid_percentage = isInteger($team_1_MoneylineAmountPaid_percentage) ? (int) $team_1_MoneylineAmountPaid_percentage : $team_1_MoneylineAmountPaid_percentage;
                        $team_2_MoneylineAmountPaid_percentage = isInteger($team_2_MoneylineAmountPaid_percentage) ? (int) $team_2_MoneylineAmountPaid_percentage : $team_2_MoneylineAmountPaid_percentage;
                        $team_1_OverUnderAmountPaid_percentage = isInteger($team_1_OverUnderAmountPaid_percentage) ? (int) $team_1_OverUnderAmountPaid_percentage : $team_1_OverUnderAmountPaid_percentage;
                        $team_2_OverUnderAmountPaid_percentage = isInteger($team_2_OverUnderAmountPaid_percentage) ? (int) $team_2_OverUnderAmountPaid_percentage : $team_2_OverUnderAmountPaid_percentage;

                        $team_1_SpreadAmountPaid_percentage = is_numeric($team_1_SpreadAmountPaid_percentage) ? $team_1_SpreadAmountPaid_percentage : 0;
                        $team_2_SpreadAmountPaid_percentage = is_numeric($team_2_SpreadAmountPaid_percentage) ? $team_2_SpreadAmountPaid_percentage : 0;
                        $team_1_MoneylineAmountPaid_percentage = is_numeric($team_1_MoneylineAmountPaid_percentage) ? $team_1_MoneylineAmountPaid_percentage : 0;
                        $team_2_MoneylineAmountPaid_percentage = is_numeric($team_2_MoneylineAmountPaid_percentage) ? $team_2_MoneylineAmountPaid_percentage : 0;
                        $team_1_OverUnderAmountPaid_percentage = is_numeric($team_1_OverUnderAmountPaid_percentage) ? $team_1_OverUnderAmountPaid_percentage : 0;
                        $team_2_OverUnderAmountPaid_percentage = is_numeric($team_2_OverUnderAmountPaid_percentage) ? $team_2_OverUnderAmountPaid_percentage : 0;

                        $totalAmountPaid = $team_1_SpreadAmountPaid + $team_2_SpreadAmountPaid
                            + $team_1_MoneylineAmountPaid + $team_2_MoneylineAmountPaid
                            + $team_1_OverUnderAmountPaid + $team_2_OverUnderAmountPaid;

                        $totalAmountPaid = number_format($totalAmountPaid, 2, '.', '');

                        $team_1_SpreadExposure = number_format($team_1_SpreadExposure, 2, '.', '');
                        $team_2_SpreadExposure = number_format($team_2_SpreadExposure, 2, '.', '');
                        $team_1_MoneylineExposure = number_format($team_1_MoneylineExposure, 2, '.', '');
                        $team_2_MoneylineExposure = number_format($team_2_MoneylineExposure, 2, '.', '');
                        $team_1_OverUnderExposure = number_format($team_1_OverUnderExposure, 2, '.', '');
                        $team_2_OverUnderExposure = number_format($team_2_OverUnderExposure, 2, '.', '');

                        //calculate percentage
                        $team_1_SpreadExposure_percentage = number_format(($team_1_SpreadExposure / ($team_1_SpreadExposure + $team_2_SpreadExposure)) * 100, 2);
                        $team_2_SpreadExposure_percentage = number_format(($team_2_SpreadExposure / ($team_1_SpreadExposure + $team_2_SpreadExposure)) * 100, 2);
                        $team_1_MoneylineExposure_percentage = number_format(($team_1_MoneylineExposure / ($team_1_MoneylineExposure + $team_2_MoneylineExposure)) * 100, 2);
                        $team_2_MoneylineExposure_percentage = number_format(($team_2_MoneylineExposure / ($team_1_MoneylineExposure + $team_2_MoneylineExposure)) * 100, 2);
                        $team_1_OverUnderExposure_percentage = number_format(($team_1_OverUnderExposure / ($team_1_OverUnderExposure + $team_2_OverUnderExposure)) * 100, 2);
                        $team_2_OverUnderExposure_percentage = number_format(($team_2_OverUnderExposure / ($team_1_OverUnderExposure + $team_2_OverUnderExposure)) * 100, 2);

                        $team_1_SpreadExposure_percentage = isInteger($team_1_SpreadExposure_percentage) ? (int) $team_1_SpreadExposure_percentage : $team_1_SpreadExposure_percentage;
                        $team_2_SpreadExposure_percentage = isInteger($team_2_SpreadExposure_percentage) ? (int) $team_2_SpreadExposure_percentage : $team_2_SpreadExposure_percentage;
                        $team_1_MoneylineExposure_percentage = isInteger($team_1_MoneylineExposure_percentage) ? (int) $team_1_MoneylineExposure_percentage : $team_1_MoneylineExposure_percentage;
                        $team_2_MoneylineExposure_percentage = isInteger($team_2_MoneylineExposure_percentage) ? (int) $team_2_MoneylineExposure_percentage : $team_2_MoneylineExposure_percentage;
                        $team_1_OverUnderExposure_percentage = isInteger($team_1_OverUnderExposure_percentage) ? (int) $team_1_OverUnderExposure_percentage : $team_1_OverUnderExposure_percentage;
                        $team_2_OverUnderExposure_percentage = isInteger($team_2_OverUnderExposure_percentage) ? (int) $team_2_OverUnderExposure_percentage : $team_2_OverUnderExposure_percentage;

                        $team_1_SpreadExposure_percentage = is_numeric($team_1_SpreadExposure_percentage) ? $team_1_SpreadExposure_percentage : 0;
                        $team_2_SpreadExposure_percentage = is_numeric($team_2_SpreadExposure_percentage) ? $team_2_SpreadExposure_percentage : 0;
                        $team_1_MoneylineExposure_percentage = is_numeric($team_1_MoneylineExposure_percentage) ? $team_1_MoneylineExposure_percentage : 0;
                        $team_2_MoneylineExposure_percentage = is_numeric($team_2_MoneylineExposure_percentage) ? $team_2_MoneylineExposure_percentage : 0;
                        $team_1_OverUnderExposure_percentage = is_numeric($team_1_OverUnderExposure_percentage) ? $team_1_OverUnderExposure_percentage : 0;
                        $team_2_OverUnderExposure_percentage = is_numeric($team_2_OverUnderExposure_percentage) ? $team_2_OverUnderExposure_percentage : 0;
                        $totalBets = $spread_counter_team1 + $spread_counter_team2 + $over_under_counter_team1 + $over_under_counter_team2;
                        $totalExposure = $team_1_SpreadExposure + $team_2_SpreadExposure
                            + $team_1_OverUnderExposure + $team_2_OverUnderExposure;

                        $totalExposure = number_format($totalExposure, 2, '.', '');
                        // Get Wagers

                        $gameTitle = $cData->team1->name . " vs " . $cData->team2->name . " - " . $contest_status_html;

                ?>

                        <div class="card-body">
                            <div class="table-responsive" style="overflow: hidden;">
                                <h5><?= $gameTitle; ?></h5>
                                <br>
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Bet Type</th>
                                            <th>Bet Details</th>
                                            <th>Amount Wagered</th>
                                            <th>Number of Bets</th>
                                            <!-- <th>Amount Returned</th> -->
                                            <!-- <th>Amount Paid</th> -->
                                            <th>Exposure</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td rowspan="2"><strong>Spread</strong></td>
                                            <td><?= $team_1_name ?></td>
                                            <td>$<?= $team_1_SpreadAmountWagered ?> (<?= $team_1_SpreadAmountWagered_percentage ?>%)</td>
                                            <td><?= $spread_counter_team1 ?></td>
                                            <!-- <td>$0.00</td> -->
                                            <!-- <td>$<?= $team_1_SpreadAmountPaid ?> (<?= $team_1_SpreadAmountPaid_percentage ?>%)</td> -->
                                            <td>$<?= $team_1_SpreadExposure ?> (<?= $team_1_SpreadExposure_percentage ?>%)</td>

                                        </tr>
                                        <tr>
                                            <!-- <td><strong>Spread</strong></td> -->
                                            <td><?= $team_2_name ?></td>
                                            <td>$<?= $team_2_SpreadAmountWagered ?> (<?= $team_2_SpreadAmountWagered_percentage ?>%)</td>
                                            <td><?= $spread_counter_team2 ?></td>
                                            <!-- <td>$0.00</td> -->
                                            <!-- <td>$<?= $team_2_SpreadAmountPaid ?> (<?= $team_2_SpreadAmountPaid_percentage ?>%)</td> -->
                                            <td>$<?= $team_2_SpreadExposure ?> (<?= $team_2_SpreadExposure_percentage ?>%)</td>
                                        </tr>

                                        <tr>
                                            <td rowspan="2"><strong>Over/Under</strong></td>
                                            <td><?= $team_1_name ?></td>
                                            <td>$<?= $team_1_OverUnderAmountWagered ?> (<?= $team_1_OverUnderAmountWagered_percentage ?>%)</td>
                                            <td><?= $over_under_counter_team1 ?></td>
                                            <!-- <td>$0.00</td> -->
                                            <!-- <td>$<?= $team_1_OverUnderAmountPaid ?> (<?= $team_1_OverUnderAmountPaid_percentage ?>%)</td> -->
                                            <td>$<?= $team_1_OverUnderExposure ?> (<?= $team_1_OverUnderExposure_percentage ?>%)</td>

                                        </tr>

                                        <tr>
                                            <!-- <td><strong>Over/Under</strong></td> -->
                                            <td><?= $team_2_name ?></td>
                                            <td>$<?= $team_2_OverUnderAmountWagered ?> (<?= $team_2_OverUnderAmountWagered_percentage ?>%)</td>
                                            <td><?= $over_under_counter_team2 ?></td>
                                            <!-- <td>$0.00</td> -->
                                            <!-- <td>$<?= $team_2_OverUnderAmountPaid ?> (<?= $team_2_OverUnderAmountPaid_percentage ?>%)</td> -->
                                            <td>$<?= $team_2_OverUnderExposure ?> (<?= $team_2_OverUnderExposure_percentage ?>%)</td>
                                        </tr>


                                        <!-- <tr>
                                            <td rowspan="2"><strong>Moneyline</strong></td>
                                            <td><?= $team_1_name ?></td>
                                            <td>$<?= $team_1_MoneylineAmountWagered ?> (<?= $team_1_MoneylineAmountWagered_percentage ?>%)</td>
                                            <td><?= $moneyline_counter_team1 ?></td>
                                            <td>$0.00</td>
                                            <td>$<?= $team_1_MoneylineAmountPaid ?> (<?= $team_1_MoneylineAmountPaid_percentage ?>%)</td>
                                            <td>$<?= $team_1_MoneylineExposure ?> (<?= $team_1_MoneylineExposure_percentage ?>%)</td>

                                        </tr>

                                        <tr>
                                            <td><strong>Moneyline</strong></td>
                                            <td><?= $team_2_name ?></td>
                                            <td>$<?= $team_2_MoneylineAmountWagered ?> (<?= $team_2_MoneylineAmountWagered_percentage ?>%)</td>
                                            <td><?= $moneyline_counter_team2 ?></td>
                                            <td>$0.00</td>
                                            <td>$<?= $team_2_MoneylineAmountPaid ?> (<?= $team_2_MoneylineAmountPaid_percentage ?>%)</td>
                                            <td>$<?= $team_2_MoneylineExposure ?> (<?= $team_2_MoneylineExposure_percentage ?>%)</td>
                                        </tr> -->
                                        <tr>
                                            <td colspan="2"><strong>Totals</strong></td>
                                            <!-- <td></td> -->
                                            <td><strong>$<?= $totalAmountWagered ?></strong></td>
                                            <td><strong><?= $totalBets ?></strong></td>
                                            <!-- <td>$0.00</td> -->
                                            <!-- <td><strong>$<?= $totalAmountPaid ?></strong></td> -->
                                            <td><strong>$<?= $totalExposure ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
            <?php
                }
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
            //alert(league_type_box_first_value);

            var value = jQuery(this).val();
            //alert(value)
            if (league_type_box_first_value == 'nfl' && value != 'nfl') {
                window.location.href = "<?= home_url() ?>/detailed-exposure-report/?league_type=" + value;
            } else if (league_type_box_first_value != 'nfl' && value != 'nfl') {

            } else {
                window.location.href = "<?= home_url() ?>/detailed-exposure-report/?league_type=" + value;
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
<style>
    .calender-css {
        position: absolute;
        right: 60px;
        top: 9px;
    }

    .main-wager-box {
        display: flex;
        width: 100%;
        flex-wrap: wrap;
        justify-content: center;
        padding: 20px 0;
    }

    .main-wager-box .inner-wager-box {
        width: 29%;
        padding: 20px 20px;
        border: 1px solid #ddd;
        display: flex;
        flex-direction: column;
        margin: 15px;
        text-align: center;
    }

    .table-responsive>.table-bordered {
        border: 1px solid #dee2e6;
    }
</style>
<?php get_footer('custom'); ?>