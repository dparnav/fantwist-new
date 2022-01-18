<?php /* Template Name: Admin Large Bet Report */




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

$searchParams = [];

$sportType = $_GET['sport-type'];
$betType = $_GET['bet-type'];

$searchParams['sport-type'] = $sportType;
$searchParams['bet-type'] = $betType;

$terms = get_terms(array(
    'taxonomy' => 'league',
    'hide_empty' => false,
));


$wager_taxonomies = get_terms(array(
    'taxonomy' => 'wager_type',
    'hide_empty' => false,
));

$wager_taxonomies = [
    (object)['slug' => 'spread', 'name' => 'Spread'],
    (object)['slug' => 'over-under', 'name' => 'Over/Under'],
    // (object)['slug' => 'moneyline', 'name' => 'Moneyline'],
    //stop parlay teaser
    // (object)['slug' => 'parlay', 'name' => 'Parlay'],
    // (object)['slug' => 'teaser', 'name' => 'Teaser'],
];


$args = array(
    'post_type' => array('wager'),
    // , 'parlaywager', 'teaserwager'),
    'paged' => get_query_var('paged'),


);

$minimumBetAmount = $_GET['minimum-bet-amount'];

$searchParams['minimum-bet-amount'] = $minimumBetAmount;

$sportType = $_GET['sport-type'];



if (isset($sportType) && !empty($sportType) && isset($betType) && !empty($betType)) {
    $args['tax_query'] = array(
        'relation' => "AND",
        array(
            'taxonomy' => 'league',
            'field'    => 'slug',
            'terms'    => $sportType,
        ),
        array(
            'taxonomy' => 'wager_type',
            'field'    => 'slug',
            'terms'    => $betType,
        )
    );
} else if (isset($sportType) && !empty($sportType)) {
    $args['tax_query'] = array(
        'relation' => "AND",
        array(
            'taxonomy' => 'league',
            'field'    => 'slug',
            'terms'    => $sportType,
        ),
        array(
            'taxonomy' => 'wager_type',
            'field'    => 'slug',
            'terms'    => ['spread', 'over-under']
        )
    );
} else if (isset($betType) && !empty($betType)) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'wager_type',
            'field'    => 'slug',
            'terms'    => $betType,
        ),
    );
}

if (isset($minimumBetAmount) && !empty($minimumBetAmount)) {
    $args['meta_query'] = array(
        array(
            'key' => 'wager_amount',
            'value' => $minimumBetAmount,
            'compare' => '>='
        ),
    );
}



// Export functionality
if (isset($_REQUEST['export'])) {

    unset($args['paged']);
    $args['posts_per_page'] = -1;
    $the_query = new WP_Query($args);

    $list = array(
        ['ID', 'Sport', 'Name', 'Type', 'Wager', 'Team', 'Contest Date', 'Result']
    );

    if ($the_query->have_posts()) :
        $i = 0;
        while ($the_query->have_posts()) :
            $the_query->the_post();
            if ($post->post_type == 'parlaywager' || $post->post_type == 'teaserwager') {
                $meta = get_post_meta($post->ID);

                if ($post->post_type == 'parlaywager') {
                    $meta = get_post_meta($post->ID);
                    $current_data = json_decode($meta['parlay_data'][0]);
                    $wager_type = $current_data->parlay_game_type . " Team Parlay";
                    $wager_post_id = $current_data->wager_post_id;
                    $wager_amount = number_format(json_decode(get_field('parlay_data', $wager_post_id))->wager_amount, 2);
                    $winnings =  number_format(json_decode(get_field('parlay_data', $wager_post_id))->potential_winning, 2);

                    if ($current_data->team[0]->points > 0) {
                        $current_data->team[0]->points = '+' . $current_data->team[0]->points;
                    }
                    $wager_winner_1_name = '';
                    foreach ($current_data->team as $team_data) {


                        if (strtolower($team_data->points_type) == "over/under") {
                            $wager_winner_1_name .= $team_data->rotation_number . ' - ' . $team_data->team_name . ' ' . $team_data->points . "<br>";
                        } else {
                            $wager_winner_1_name .= $team_data->rotation_number . ' - ' . ucwords($team_data->team_name) . ' (' . $team_data->points . ')' . "<br>";
                        }
                    }
                } else {
                    $meta = get_post_meta($post->ID);
                    $current_data = json_decode($meta['teaser_data'][0]);
                    $wager_type = $current_data->parlay_game_type . " Team Teaser,  Tease by " . $current_data->teaser_points . " points";
                    $wager_post_id = $current_data->wager_post_id;
                    $wager_amount = number_format(json_decode(get_field('teaser_data', $wager_post_id))->wager_amount, 2);
                    $winnings =  number_format(json_decode(get_field('teaser_data', $wager_post_id))->potential_winning, 2);


                    $wager_winner_1_name = '';
                    foreach ($current_data->team as $team_data) {


                        if (strtolower($team_data->points_type) == "over/under") {
                            $wager_winner_1_name .= $team_data->rotation_number . ' - ' . $team_data->team_name . ' ' . $team_data->points . "<br>";
                        } else {
                            $wager_winner_1_name .= $team_data->rotation_number . ' - ' . ucwords($team_data->team_name) . ' (' . $team_data->points . ')' . "<br>";
                        }
                    }
                }
                $wager_contest = get_field('contest_id', $post->ID);
                $sportName = get_the_title($wager_contest);
                $authorName = get_the_author_meta('display_name', $post->post_author);
                $contest_date = date('m/d/Y', ((int)$meta['date'][0]));
                $wager_result = $meta['wager_result'][0];
            } else {
                $meta = get_post_meta($post->ID);
                $authorName = get_the_author_meta('display_name', $post->post_author);

                $sportName = wp_get_object_terms($post->ID, 'league', array('fields' => 'names'));
                $sportName = $sportName[0];
                $wager_contest = $meta['wager_contest'][0];
                $sport_type = "";
                $wager_game = $meta['wager_game'][0];
                $wager_post_id  = get_field('wager_post_id', $post->ID);
                $wager_amount = number_format(get_field('wager_amount', $wager_post_id), 2);
                $wager_type = $meta['wager_type'][0];
                $potential_winnings = $meta['potential_winnings'][0];
                $contest_date = date('m/d/Y', ((int)$meta['contest_date'][0]));
                $wager_winner_1 = $meta['wager_winner_1'][0];
                $wager_winner_1_name = $meta['wager_winner_1_name'][0];
                $wager_result = $meta['wager_result'][0];
                $winnings = $meta['point_spread'][0];
            }

            $wagerData = [
                $wager_contest,
                $sportName,
                ucfirst($authorName),
                $wager_type,
                "Bet " . $wager_amount . " to " . $winnings,
                $wager_winner_1_name,
                $contest_date,
                $wager_result
            ];



            array_push($list, $wagerData);

        endwhile;
    else :
    // Insert any content or load a template for no posts found.
    endif;

    wp_reset_query();


    // Open a file in write mode ('w')
    $f = fopen('php://memory', 'w');
    // Loop through file pointer and a line
    foreach ($list as $fields) {
        fputcsv($f, $fields);
    }
    // reset the file pointer to the start of the file
    fseek($f, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="test.csv";');
    // make php send the generated csv lines to the browser is working
    fpassthru($f);
    exit;
}


$the_query = new WP_Query($args);
global $wp;

$searchQueryParams = http_build_query($searchParams);

$searchUrl = home_url('large-bet-report') . "?" . $searchQueryParams;

get_header('custom'); ?>

<div class="content-wrapper pt-5 pb-2">
    <div class="container">
        <h4 class="mb-4"><?php the_title(); ?></h4>

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
                        <div class="ml-3 form-group">
                            <label class="mr-2">Minimum Bet Amount</label>
                            <input value="<?= $minimumBetAmount ?>" type="number" name="minimum-bet-amount" class="form-control">
                        </div>

                        <div class="ml-3 form-group">
                            <select name="bet-type" class="form-control">
                                <option value="">-- Select Bet Type --</option>
                                <?php
                                if (count($wager_taxonomies) > 0) {
                                    foreach ($wager_taxonomies as $wager_taxonomy) {

                                        $selected = ($wager_taxonomy->slug == $betType) ? "selected='" . $betType . "'" : "";
                                ?>
                                        <option <?= $selected ?> value="<?= $wager_taxonomy->slug; ?>"><?= $wager_taxonomy->name; ?></option>
                                    <?php
                                    }
                                } else {
                                    ?>

                                <?php } ?>
                            </select>
                        </div>

                        <div class="ml-3 form-group">
                            <select name="sport-type" class="form-control">
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
                        <div class="ml-3 form-group pr-3" style="flex:1; justify-content:space-between;">
                            <input type="submit" name="search" value="Search" class="btn btn-primary form-control">
                            <input type="submit" name="export" value="Export" class="btn btn-outline-primary ml-3">

                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="overflow: hidden;">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Contest</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Wager</th>
                                <th>Team(s)</th>
                                <th>Contest Date</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php


                            if ($the_query->have_posts()) {

                                while ($the_query->have_posts()) {
                                    $the_query->the_post();
                                    $type =  strtolower(get_field('wager_type', $post->ID));
                                    if ($type != 'moneyline') {
                                        if ($post->post_type == 'wager') {

                                            $contest = get_field('wager_contest', $post->ID);

                                            $contest_title = str_replace('Point Spread', '', get_field('contest_title_without_type', $contest));

                                            $meta = get_post_meta($post->ID);
                                            $authorName = get_the_author_meta('display_name', $post->post_author);

                                            $sportName = wp_get_object_terms($post->ID, 'league', array('fields' => 'names'));
                                            $sportName = $sportName[0];
                                            $wager_contest = $meta['wager_contest'][0];

                                            $contest_status = $meta['contest_status'][0];

                                            $sport_type = "";
                                            $wager_game = $meta['wager_game'][0];
                                            $wager_post_id  = get_field('wager_post_id', $post->ID);
                                            $wager_amount = number_format(get_field('wager_amount', $wager_post_id), 2);
                                            $wager_type = strtolower($meta['wager_type'][0]);
                                            $potential_winnings = $meta['potential_winnings'][0];
                                            $contest_date = date('m/d/Y', $meta['contest_date'][0]);
                                            $wager_winner_1 = $meta['wager_winner_1'][0];
                                            $wager_winner_1_name = $meta['wager_winner_1_name'][0];
                                            $winners_html = "";
                                            $wager_result = $meta['wager_result'][0];
                                            $point_spread = $meta['point_spread'][0];

                                            $contest = get_field('wager_contest', $post->ID);
                                            $contest_title = str_replace('Point Spread', '', get_field('contest_title_without_type', $contest));
                                            $wager_result = get_field('wager_result');

                                            $wager_type = strtolower(get_field('wager_type', $post->ID));
                                            $winners_html = '';

                                            $league = get_the_terms($post->ID, 'league');
                                            $league_id = $league[0]->term_id;
                                            $league_name = $league[0]->name;


                                            if ($wager_type == 'win' || $wager_type == 'place' || $wager_type == 'show' || $wager_type == 'spread' || $wager_type == 'over/under') {

                                                if ($wager_type == 'spread') {
                                                    $wager_rotation = get_field('wager_rotation', $post->ID);
                                                    $point_spread = get_field('point_spread', $post->ID);

                                                    if ($point_spread > 0) {

                                                        $point_spread = '+' . $point_spread;
                                                    }

                                                    $winners_html = $wager_rotation . ' - ' . get_field('wager_winner_1_name', $post->ID) . ' (' . $point_spread . ')';
                                                } else if ($wager_type == 'over/under') {
                                                    $wager_rotation = get_field('wager_rotation', $post->ID);
                                                    $winners_html = $wager_rotation . ' - ' . get_field('wager_winner_1_name', $post->ID);
                                                } else {
                                                    $wager_rotation = get_field('wager_rotation', $post->ID);

                                                    $winners_html =  $wager_rotation . ' - ' . get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)';
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
                                        }

                            ?>
                                        <tr>
                                            <td><?= $wager_contest ?></td>
                                            <td><?= $contest_title ?></td>
                                            <td><?= ucfirst($authorName) ?></td>
                                            <td><?= ucfirst($wager_type) ?></td>
                                            <td>Bet <strong style="font-size:12px;">$<?= $wager_amount ?></strong> to win <strong style="font-size:12px;">$<?= $winnings ?></strong></td>
                                            <td><?= $winners_html ?></td>
                                            <td><?= $contest_date ?></td>
                                            <td><?= $wager_result ?></td>
                                        </tr>
                                <?php    }
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="8">
                                        <p class="alert alert-info">No results found!</p>
                                    </td>

                                </tr>
                            <?php

                            }
                            ?>
                        </tbody>
                    </table>

                    <?php
                    wp_pagenavi(array('query' => $the_query));
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>


<?php get_footer('custom'); ?>