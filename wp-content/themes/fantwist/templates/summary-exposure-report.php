<?php /* Template Name: Admin Summary Exposure Report */




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
        }
    }
    $main_contest_status;
    wp_reset_query();

    $args = array(
        'post_type' => 'contest',
        'posts_per_page' => -1,

        'order'                => 'ASC',
        'orderby'            => 'meta_value',
        'meta_key'            => 'contest_date_sort',
        'meta_type'            => 'DATETIME'
    );
    $the_query = new WP_Query($args);

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

    $the_query = new WP_Query($args);

    $contestList = [];
    $openGamesDone = 0;
    if ($the_query->have_posts()) {
        while ($the_query->have_posts()) {
            $the_query->the_post();
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
    'tax_query' => array(
        array(
            'taxonomy' => 'wager_type',
            'field'    => 'slug',
            'terms'    => ['spread', 'over-under']
        )
    )
);

if ($league_type) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'wager_type',
            'field'    => 'slug',
            'terms'    => ['spread', 'over-under']
        ),
        array(
            'taxonomy' => 'league',
            'field'    => 'slug',
            'terms'    => $league_type
        )
    );
}

$args['meta_query'] = array(
    array(
        'key' => 'wager_contest',
        'value' => $contestList,
        'compare' => 'IN'
    ),
);


$the_query = new WP_Query($args);


/* Code for summery wagers */

 $contest_date = $_GET['contest_date'];

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
// if (date("Y-m-d", strtotime($contest_date)) != date('Y-m-d')) {
//     $openGamesCount = 0;
// }

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
    // 'meta_query' => array(
    //     array(
    //         'key' => 'wager_contest',
    //         'value' => $contestList,
    //         'compare' => 'IN'
    //     )
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
        // array(
        //     'taxonomy' => 'wager_type',
        //     'field'    => 'slug',
        //     'terms'    => ['spread', 'over-under', 'moneyline']
        // ),
        array(
            'taxonomy' => 'league',
            'field'    => 'slug',
            'terms'    => $league_type
        )
    );
}

$fantasyWagers = new WP_Query($args);


// Parlay Wagers
$parlayArgs = array(
    'post_type' => ['parlaywager'],
    // 'date_query' => array(
    //     array(
    //         'year'  => $year,
    //         'month' => $month,
    //         'day'   => $day,
    //     ),
    // ),
    'meta_query' => array(
        array(
            'key' => 'contest_id',
            'value' => $contestList,
            'compare' => 'IN'
        )
    )
);


$parlayWagers = new WP_Query($parlayArgs);

//echo "<pre>";


$parlayAmountWagered = 0.00;
$parlayAmountReturned = 0.00;
$parlayAmountPaid = 0.00;
$parlayExposure = 0.00;
$parlayCounter = 0;
if ($parlayWagers->have_posts()) {
    while ($parlayWagers->have_posts()) {
        $parlayWagers->the_post();

        $parlayBetData = json_decode(get_field('parlay_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
        $wagerType = $parlayBetData->parlay_game_type;
        $wagerAmount = $parlayBetData->wager_amount;
        $wagerPotentialWinning = $parlayBetData->potential_winning;
        $wagerResult = strtoupper(get_field('wager_result', $post->ID));

        $wager_type = get_field('wager_type', $post->ID);
        $wager_result = strtoupper(get_field('wager_result', $post->ID));;
        $parlayCounter++;

        // Wagered Amount
        $wagerAmount = str_replace(',', '', $wagerAmount);
        $parlayAmountWagered += $wagerAmount;
        // Wagered Amount

        // Amount Returned
        if ($wager_result == "PUSH") {
            $parlayAmountReturned += $wagerAmount;
        }
        // Amount Returned

        // Amount Paid
        if ($wager_result == "WIN") {
            $parlayAmountPaid += $wagerAmount + $wagerPotentialWinning;
        }
        //Amount Paid


        // Amount Exposure
        $wagerPotentialWinning = str_replace(',', '', $wagerPotentialWinning);

        if ($wager_result == "OPEN") {
            $parlayExposure +=  $wagerPotentialWinning;
        }
        //Amount Exposure
    }
}

$parlayAmountWagered = number_format($parlayAmountWagered, 2, '.', '');
$parlayAmountReturned = number_format($parlayAmountReturned, 2, '.', '');
$parlayAmountPaid = number_format($parlayAmountPaid, 2, '.', '');
$parlayExposure = number_format($parlayExposure, 2, '.', '');





// Teaser Wagers
$teaserArgs = array(
    'post_type' => ['teaserwager'],
    // 'date_query' => array(
    //     array(
    //         'year'  => $year,
    //         'month' => $month,
    //         'day'   => $day,
    //     ),
    // ),
    'meta_query' => array(
        array(
            'key' => 'contest_id',
            'value' => $contestList,
            'compare' => 'IN'
        )
    )


);

$teaserWagers = new WP_Query($teaserArgs);

//echo "<pre>";


$teaserAmountWagered = 0.00;
$teaserAmountReturned = 0.00;
$teaserAmountPaid = 0.00;
$teaserExposure = 0.00;
$teaserCounter = 0;
if ($teaserWagers->have_posts()) {
    while ($teaserWagers->have_posts()) {
        $teaserWagers->the_post();

        $teaserBetData = json_decode(get_field('teaser_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
        $wagerType = $teaserBetData->teaser_game_type;
        $wagerAmount = $teaserBetData->wager_amount;
        $wagerPotentialWinning = $teaserBetData->potential_winning;
        $wagerResult = strtoupper(get_field('wager_result', $post->ID));

        $wager_type = get_field('wager_type', $post->ID);
        $wager_result = strtoupper(get_field('wager_result', $post->ID));;
        $teaserCounter++;
        // Wagered Amount
        $wagerAmount = str_replace(',', '', $wagerAmount);
        $teaserAmountWagered += $wagerAmount;
        // Wagered Amount

        // Amount Returned
        if ($wager_result == "PUSH") {
            $teaserAmountReturned += $wagerAmount;
        }
        // Amount Returned

        // Amount Paid
        if ($wager_result == "WIN") {
            $teaserAmountPaid += $wagerAmount + $wagerPotentialWinning;
        }
        //Amount Paid


        // Amount Exposure
        $wagerPotentialWinning = str_replace(',', '', $wagerPotentialWinning);

        if ($wager_result == "OPEN") {
            $teaserExposure += $wagerPotentialWinning;
        }
        //Amount Exposure
    }
}

$teaserAmountWagered = number_format($teaserAmountWagered, 2, '.', '');
$teaserAmountReturned = number_format($teaserAmountReturned, 2, '.', '');
$teaserAmountPaid = number_format($teaserAmountPaid, 2, '.', '');
$teaserExposure = number_format($teaserExposure, 2, '.', '');


//exit;

// echo "<pre>";
// print_r($fantasyWagers);
// exit;
// Get All games

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
        <h4 class="mb-4">Summary Exposure Report</h4>

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
                    <form class="form-inline w-100" action="<?= $searchUrl ?>">

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


            } else  if (empty($_GET['league_type']) && empty($_GET['season']) && empty($_GET['week']) && empty($_GET['date'])) {

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
    
    
                } else {

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
            ?>
            <div class="card-body">
                <div class="table-responsive" style="overflow: hidden;">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Bet Type</th>
                                <th>Amount Wagered</th>
                                <th>Number of Bets</th>
                                <!-- <th>Amount Returned</th> -->
                                <!-- <th>Amount Paid</th> -->
                                <th>Exposure</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php

                            $totalAmountWagered = 0.00;
                            $totalAmountReturned = 0.00;
                            $totalAmountPaid = 0.00;
                            $totalExposure = 0.00;

                            $spreadAmountWagered = 0.00;
                            $moneylineAmountWagered = 0.00;
                            $overUnderAmountWagered = 0.00;

                            $spreadAmountReturned = 0.00;
                            $moneylineAmountReturned = 0.00;
                            $overUnderAmountReturned = 0.00;

                            $spreadAmountPaid = 0.00;
                            $moneylineAmountPaid = 0.00;
                            $overUnderAmountPaid = 0.00;

                            $spreadExposure = 0.00;
                            $moneylineExposure = 0.00;
                            $overUnderExposure = 0.00;
                            $spread_counter = 0;
                            $over_under_counter = 0;
                            $moneyline_counter = 0;
                            if ($fantasyWagers->have_posts()) {
                                while ($fantasyWagers->have_posts()) {
                                    $fantasyWagers->the_post();




                                    $wager_type = get_field('wager_type', $post->ID);
                                    $wager_result = strtoupper(get_field('wager_result', $post->ID));
                                    //var_dump($wager_result);

                                    // Wagered Amount
                                    if ($wager_type == 'Spread' && $wager_result == "OPEN") {

                                        $point_type = "point_spread";
                                        $spreadAmountWagered += floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Moneyline' && $wager_result == "OPEN") {
                                        $point_type = "wager_moneyline";
                                        $moneylineAmountWagered += floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Over/Under' && $wager_result == "OPEN") {
                                        $point_type = "wager_overunder";
                                        $overUnderAmountWagered +=  floatval(get_field('wager_amount', $post->ID));
                                    }
                                    // Wagered Amount

                                    // Amount Returned
                                    if ($wager_type == 'Spread' && $wager_result == "PUSH") {
                                        $point_type = "point_spread";
                                        $spreadAmountReturned += floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Moneyline' && $wager_result == "PUSH") {
                                        $point_type = "wager_moneyline";
                                        $moneylineAmountReturned += floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Over/Under' && $wager_result == "PUSH") {
                                        $point_type = "wager_overunder";
                                        $overUnderAmountReturned +=  floatval(get_field('wager_amount', $post->ID));
                                    }
                                    // Amount Returned

                                    // Amount Paid
                                    if ($wager_type == 'Spread' && $wager_result == "WIN") {


                                        $point_type = "point_spread";
                                        $spreadAmountPaid += floatval(get_field('potential_winnings', $post->ID))
                                            + floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Moneyline' && $wager_result == "WIN") {
                                        $point_type = "wager_moneyline";
                                        $moneylineAmountPaid += floatval(get_field('potential_winnings', $post->ID))
                                            + floatval(get_field('wager_amount', $post->ID));
                                    } elseif ($wager_type == 'Over/Under' && $wager_result == "WIN") {
                                        $point_type = "wager_overunder";
                                        $overUnderAmountPaid +=  floatval(get_field('potential_winnings', $post->ID))
                                            + floatval(get_field('wager_amount', $post->ID));
                                    }
                                    //Amount Paid
                                    //no. of bets

                                    if ($wager_type == 'Spread') {
                                        $spread_counter++;
                                    }
                                    if ($wager_type == 'Moneyline') {
                                        $moneyline_counter++;
                                    }
                                    if ($wager_type == 'Over/Under') {
                                        $over_under_counter++;
                                    }


                                    //no. of bets
                                    // Amount Exposure
                                    if ($wager_type == 'Spread' && $wager_result == "OPEN") {
                                        $point_type = "point_spread";
                                        $spreadExposure += floatval(get_field('potential_winnings', $post->ID));
                                    } elseif ($wager_type == 'Moneyline' && $wager_result == "OPEN") {
                                        $point_type = "wager_moneyline";
                                        $moneylineExposure += floatval(get_field('potential_winnings', $post->ID));
                                    } elseif ($wager_type == 'Over/Under' && $wager_result == "OPEN") {
                                        $point_type = "wager_overunder";
                                        $overUnderExposure +=  floatval(get_field('potential_winnings', $post->ID));
                                    }
                                    //Amount Exposure

                            ?>

                                <?php } ?>
                            <?php } ?>

                            <?php
                            $spreadAmountWagered = number_format($spreadAmountWagered, 2, '.', '');
                            $moneylineAmountWagered = number_format($moneylineAmountWagered, 2, '.', '');
                            $overUnderAmountWagered = number_format($overUnderAmountWagered, 2, '.', '');

                            $spreadAmountReturned = number_format($spreadAmountReturned, 2, '.', '');
                            $moneylineAmountReturned = number_format($moneylineAmountReturned, 2, '.', '');
                            $overUnderAmountReturned = number_format($overUnderAmountReturned, 2, '.', '');

                            $spreadAmountPaid = number_format($spreadAmountPaid, 2, '.', '');
                            $moneylineAmountPaid = number_format($moneylineAmountPaid, 2, '.', '');
                            $overUnderAmountPaid = number_format($overUnderAmountPaid, 2, '.', '');

                            $spreadExposure = number_format($spreadExposure, 2, '.', '');
                            $moneylineExposure = number_format($moneylineExposure, 2, '.', '');
                            $overUnderExposure = number_format($overUnderExposure, 2, '.', '');

                            // Totals
                            $totalAmountWagered = $spreadAmountWagered + $overUnderAmountWagered ;
                            $totalAmountWagered = number_format($totalAmountWagered, 2, '.', '');

                            $totalAmountReturned = $spreadAmountReturned + $overUnderAmountReturned ;
                            $totalAmountReturned = number_format($totalAmountReturned, 2, '.', '');

                            $totalAmountPaid = $spreadAmountPaid  + $overUnderAmountPaid ;
                            $totalAmountPaid = number_format($totalAmountPaid, 2, '.', '');


                            $totalExposure = $spreadExposure + $overUnderExposure ;
                            $totalBets = $spread_counter + $over_under_counter ;
                            $totalExposure = number_format($totalExposure, 2, '.', '');
                            ?>


                            <tr>
                                <td><strong>Spread</strong></td>
                                <td>$<?= $spreadAmountWagered ?></td>
                                <td><?= $spread_counter ?></td>
                                <!-- <td>$<?= $spreadAmountReturned ?></td> -->
                                <!-- <td>$<?= $spreadAmountPaid ?></td> -->
                                <td>$<?= $spreadExposure ?></td>
                            </tr>
                            <tr>
                                <td><strong>Over/Under</strong></td>
                                <td>$<?= $overUnderAmountWagered ?></td>
                                <td><?= $over_under_counter ?></td>
                                <!-- <td>$<?= $overUnderAmountReturned ?></td> -->
                                <!-- <td>$<?= $overUnderAmountPaid ?></td> -->
                                <td>$<?= $overUnderExposure ?></td>
                            </tr>
                            <!-- <tr>
                                <td><strong>Moneyline</strong></td>
                                <td>$<?= $moneylineAmountWagered ?></td>
                                <td><?= $moneyline_counter ?></td>
                                <td>$<?= $moneylineAmountReturned ?></td> 
                                 <td>$<?= $moneylineAmountPaid ?></td>
                                <td>$<?= $moneylineExposure ?></td>
                            </tr> -->
                            <!-- <tr>
                                <td><strong>Parlay</strong></td>
                                <td>$<?= $parlayAmountWagered ?></td>
                                <td><?= $parlayCounter ?></td>
                                 <td>$<?= $parlayAmountReturned ?>
                                 <td>$<?= $parlayAmountPaid ?></td> 
                                <td>$<?= $parlayExposure ?></td>

                            </tr>
                            <tr>
                                <td><strong>Teaser</strong></td>
                                <td>$<?= $teaserAmountWagered ?></td>
                                <td><?= $teaserCounter ?></td>
                                 <td>$<?= $teaserAmountReturned ?></td> 
                                 <td>$<?= $teaserAmountPaid ?></td> 
                                <td>$<?= $teaserExposure ?></td>

                            </tr> -->
                            <tr>
                                <td><strong>Total</strong></td>
                                <td><strong>$<?= $totalAmountWagered ?></strong></td>
                                <td><strong><?= $totalBets ?></strong></td>
                                <!-- <td>$<?= $totalAmountReturned ?></td> -->
                                <!-- <td><strong>$<?= $totalAmountPaid ?></strong></td> -->
                                <td><strong>$<?= $totalExposure ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
                window.location.href = "<?= home_url() ?>/summary-exposure-report/?league_type=" + value;
            } else if (league_type_box_first_value != 'nfl' && value != 'nfl') {

            } else {
                window.location.href = "<?= home_url() ?>/summary-exposure-report/?league_type=" + value;
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