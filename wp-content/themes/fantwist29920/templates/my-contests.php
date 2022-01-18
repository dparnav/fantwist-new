<?php /* Template Name: My Contests */ ?>

<?php 
if (is_user_logged_in()) {
	
	if (current_user_can('administrator')) {
		
		if (isset($_GET['viewas'])) {
			
			$current_user_id = $_GET['viewas'];
			
		} 
		else {
			
			$current_user_id = get_current_user_id();
			
		}
		
	}
	else {
		
		$current_user_id = get_current_user_id();
		
	}
	
} else {
	
	header("Location: ".home_url());
	exit;
	
}
?>

<?php get_header(); ?>

<div class="page-hero my-contests-hero" style="background-image:url(<?php echo get_the_post_thumbnail_url($post->ID,'full'); ?>)">
	
	<div class="hero-details centered-vertical noselect">
		
		<div class="inner-wrap">
	
			<h1><?php the_title(); ?></h1>
			<h2><?php echo get_field('my_contests_h2', $post->ID); ?></h2>
			
			<a class="leaderboard-btn" href="/leaderboard/">View Leaderboard</a>
		
		</div>
		
	</div>
	
</div>

<div class="my-contests-page page-box wrap">
	
	<div class="inner-wrap my-contests-wrap">

		<div class="my-contests-main">
			
			<div class="my-contests-tabs noselect">
				<ul>
					<li><a class="transition active" href="javascript:void(0);" data-type="all" data-selector="my-contests-all">All</a></li>
					<li><a class="transition" href="javascript:void(0);" data-type="upcoming" data-selector="my-contests-upcoming">Upcoming</a></li>
					<li><a class="transition" href="javascript:void(0);" data-type="live" data-selector="my-contests-live">In Progress</a></li>
					<li><a class="transition" href="javascript:void(0);" data-type="closed" data-selector="my-contests-closed">Finished</a></li>
				</ul>
			</div>
			
			<div class="my-contests-grid">
				
				<div class="my-contests my-contests-all show">
				
					<div class="table fullwidth">
						
						<div class="table-row table-heading">
						
							<div class="table-cell middle heading-id">ID</div>
							<div class="table-cell middle heading-contest">Contest</div>
							<div class="table-cell middle heading-type">Type</div>
							<div class="table-cell middle heading-wager">Wager</div>
							<div class="table-cell middle">Team(s)</div>
							<div class="table-cell middle heading-status">Status / Result</div>
						
						</div>
						
						<?php 
							
						$args = array(
							'post_type' => 'wager',
							'posts_per_page' => -1,
							'author' => $current_user_id,
						);
						
						$i = 0;
						
						$the_query = new WP_Query( $args );
						
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								
								$the_query->the_post();
								
								$contest = get_field('wager_contest',$post->ID);
								$contest_title = str_replace('Point Spread', '', get_field('contest_title_without_type', $contest));
								$wager_result = get_field('wager_result');
								
								$wager_type = strtolower(get_field('wager_type',$post->ID));
								$winners_html = '';
								
								$league = get_the_terms( $post->ID, 'league' );
								$league_id = $league[0]->term_id;
								$league_name = $league[0]->name;
							
								if ($wager_type == 'win' || $wager_type == 'place' || $wager_type == 'show' || $wager_type == 'spread' || $wager_type =='moneyline' || $wager_type == 'over/under') {
									
									if ($wager_type == 'spread') {
									
										$point_spread = get_field('point_spread', $post->ID);
									
										if ($point_spread > 0) {
										
											$point_spread = '+'. $point_spread;
										
										}
									
										$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . $point_spread . ')';
									
									}
									else if ($wager_type == 'moneyline') {
										
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
										
										$terms = get_terms( $args );
										
										foreach ($terms as $team) {
											
											$league_parent_id = $team->parent;
											$league_parent = get_term_by( 'term_id', $league_parent_id, 'team' );
											$league_parent_name = $league_parent->name;
											
											if ($league_parent_name == $league_name) {
												$moneyline = get_field('wager_moneyline', $post->ID);
												if ($moneyline > 0) {
													$moneyline_plus_minus = '+';
												}
												else {
													$moneyline_plus_minus = '';
												}
												$winners_html = $team->name . ' (' .$moneyline_plus_minus. $moneyline . ')';
											}
										
										}
										
									}
									else if ($wager_type == 'over/under') {
									
										$winners_html = get_field('wager_winner_1_name', $post->ID);
									
									}
									else {
									
										$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)';
									
									}
									
									$winnings = get_field('potential_winnings', $post->ID);
								
								}
								else if ($wager_type == "pick 2" || $wager_type == "pick 2 box") {
								
									$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
										get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)';
									
									$winnings = get_field('potential_winnings', $post->ID);
									
								}
								else if ($wager_type == "pick 3" || $wager_type == "pick 3 box") {
									
									$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
										get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)' . '<br>' . 
										get_field('wager_winner_3_name', $post->ID) . ' (' . get_field('winner_3_odds', $post->ID) . ':1)';
									
									$winnings = get_field('potential_winnings', $post->ID);
									
								}
								else if ($wager_type == "pick 4" || $wager_type == "pick 4 box") {
									
									$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
										get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)' . '<br>' . 
										get_field('wager_winner_3_name', $post->ID) . ' (' . get_field('winner_3_odds', $post->ID) . ':1)' . '<br>' .
										get_field('wager_winner_4_name', $post->ID) . ' (' . get_field('winner_4_odds', $post->ID) . ':1)';
									
									$winnings = get_field('potential_winnings', $post->ID);
									
								}	
								else if ($wager_type == "pick 6") {
									
									$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
										get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)' . '<br>' . 
										get_field('wager_winner_3_name', $post->ID) . ' (' . get_field('winner_3_odds', $post->ID) . ':1)' . '<br>' .
										get_field('wager_winner_4_name', $post->ID) . ' (' . get_field('winner_4_odds', $post->ID) . ':1)' . '<br>' .
										get_field('wager_winner_5_name', $post->ID) . ' (' . get_field('winner_5_odds', $post->ID) . ':1)' . '<br>' .
										get_field('wager_winner_6_name', $post->ID) . ' (' . get_field('winner_6_odds', $post->ID) . ':1)';
									
									$winnings = get_field('potential_winnings', $post->ID);
									
								}								
																
								echo '<a href="' . get_permalink($contest) . '" class="table-row wager-row wager-status-' . strtolower($wager_result) . '">' .
									'<div class="table-cell middle wager-id">'.$post->ID.'</div>' .
									'<div class="table-cell middle wager-contest">'.$contest_title.'</div>' .
									'<div class="table-cell middle wager-type">'.$wager_type.'</div>' .
									'<div class="table-cell middle wager-amount">Bet <strong>$'.number_format(get_field('wager_amount',$post->ID),2).'</strong> to win <strong>$'.$winnings.'</strong></div>' .
									'<div class="table-cell middle wager-winners">'.$winners_html.'</div>';
									
									if ($wager_result == 'Win' || $wager_result == 'Loss' || $wager_result == 'Push') {
									
										echo '<div class="table-cell middle wager-status">' . strtoupper($wager_result) . '<span class="results-text">See Results <i class="fas fa-arrow-circle-right"></i></span></div>';
									
									}
									else {
									
										echo '<div class="table-cell middle wager-status">' . $wager_result . '</div>';
									
									}
									
								echo '</a>';
								
								$i++;
								
							}
						}
						wp_reset_query();
						
						?>
												
					</div>
					
					<?php 
					
					if ($i == 0) {
					
						echo '<div class="mycontests-no-contests align-center">There are no contests to display.</div>';
					
					}
					
					?>
				
				</div>
				
				<div class="my-contests my-contests-upcoming">
				
					<div class="table fullwidth">
						
						<div class="table-row table-heading">
						
							<div class="table-cell middle heading-id">ID</div>
							<div class="table-cell middle heading-contest">Contest</div>
							<div class="table-cell middle heading-type">Type</div>
							<div class="table-cell middle heading-wager">Wager</div>
							<div class="table-cell middle">Team(s)</div>
							<div class="table-cell middle heading-status">Status / Result</div>
						
						</div>
						
						<?php 
						$args = array(
							'post_type' => 'wager',
							'posts_per_page' => -1,
							'author' => $current_user_id,
							'tax_query' => array(
								array(
									'taxonomy' => 'wager_result',
									'field'    => 'slug',
									'terms'    => 'open',
								),
							)
						);
						
						$i = 0;
						
						$the_query = new WP_Query( $args );
						
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								
								$the_query->the_post();
								
								$contest = get_field('wager_contest');
								$contest_status = get_field('contest_status', $contest);
																
								if ($contest_status == 'Open') {
								
									$contest_title = str_replace('Point Spread', '', get_field('contest_title_without_type', $contest));
									$wager_result = get_field('wager_result');
									
									$wager_type = strtolower(get_field('wager_type',$post->ID));
									$winners_html = '';
									
									$league = get_the_terms( $post->ID, 'league' );
									$league_id = $league[0]->term_id;
									$league_name = $league[0]->name;
									
									if ($wager_type == 'win' || $wager_type == 'place' || $wager_type == 'show' || $wager_type == 'spread' || $wager_type =='moneyline' || $wager_type == 'over/under') {
										if ($wager_type == 'spread') {
											$point_spread = get_field('point_spread', $post->ID);
											if ($point_spread > 0) {
												$point_spread = '+'.$point_spread;
											}
											$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . $point_spread . ')';
										}
										else if ($wager_type == 'moneyline') {
											
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
											
											$terms = get_terms( $args );
											
											foreach ($terms as $team) {
												
												$league_parent_id = $team->parent;
												$league_parent = get_term_by( 'term_id', $league_parent_id, 'team' );
												$league_parent_name = $league_parent->name;
												
												if ($league_parent_name == $league_name) {
													
													$moneyline = get_field('wager_moneyline', $post->ID);
													if ($moneyline > 0) {
														$moneyline_plus_minus = '+';
													}
													else {
														$moneyline_plus_minus = '';
													}
													$winners_html = $team->name . ' (' .$moneyline_plus_minus. $moneyline . ')';
													
												}
											
											}
											
										}
										else if ($wager_type == 'over/under') {
											$winners_html = get_field('wager_winner_1_name', $post->ID);
										}
										else {
											$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)';
										}
										$winnings = get_field('potential_winnings',$post->ID);
									}
									else if ($wager_type == "pick 2" || $wager_type == "pick 2 box") {
										$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
											get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)';
										$winnings = get_field('potential_winnings',$post->ID);
									}
									else if ($wager_type == "pick 3" || $wager_type == "pick 3 box") {
										$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
											get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)' . '<br>' . 
											get_field('wager_winner_3_name', $post->ID) . ' (' . get_field('winner_3_odds', $post->ID) . ':1)';
										$winnings = get_field('potential_winnings',$post->ID);
									}
									else if ($wager_type == "pick 4" || $wager_type == "pick 4 box") {
										$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
											get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)' . '<br>' . 
											get_field('wager_winner_3_name', $post->ID) . ' (' . get_field('winner_3_odds', $post->ID) . ':1)' . '<br>' .
											get_field('wager_winner_4_name', $post->ID) . ' (' . get_field('winner_4_odds', $post->ID) . ':1)';
										$winnings = get_field('potential_winnings',$post->ID);
									}	
									else if ($wager_type == "pick 6") {
										$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
											get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)' . '<br>' . 
											get_field('wager_winner_3_name', $post->ID) . ' (' . get_field('winner_3_odds', $post->ID) . ':1)' . '<br>' .
											get_field('wager_winner_4_name', $post->ID) . ' (' . get_field('winner_4_odds', $post->ID) . ':1)' . '<br>' .
											get_field('wager_winner_5_name', $post->ID) . ' (' . get_field('winner_5_odds', $post->ID) . ':1)' . '<br>' .
											get_field('wager_winner_6_name', $post->ID) . ' (' . get_field('winner_6_odds', $post->ID) . ':1)';
										$winnings = get_field('potential_winnings',$post->ID);
									}	
									
									echo '<a href="'.get_permalink($contest).'" class="table-row wager-row wager-status-'.strtolower($wager_result).'">' .
										'<div class="table-cell middle wager-id">'.$post->ID.'</div>' .
										'<div class="table-cell middle wager-contest">'.$contest_title.'</div>' .
										'<div class="table-cell middle wager-type">'.get_field('wager_type',$post->ID).'</div>' .
										'<div class="table-cell middle wager-amount">Bet <strong>$'.number_format(get_field('wager_amount',$post->ID),2).'</strong> to win <strong>$'.$winnings.'</strong></div>' .
										'<div class="table-cell middle wager-winners">'.$winners_html.'</div>' .
										'<div class="table-cell middle wager-status">'.$wager_result.'</div>' .
									'</a>';
									
									$i++;
								
								}
								
							}
						}
						wp_reset_query();
						?>
					
					</div>
					
					<?php 
					if ($i == 0) {
						echo '<div class="mycontests-no-contests align-center">There are no contests to display.</div>';
					}
					?>
				
				</div>
				
				<div class="my-contests my-contests-live">
				
					<div class="table fullwidth">
						
						<div class="table-row table-heading">
						
							<div class="table-cell middle heading-id">ID</div>
							<div class="table-cell middle heading-contest">Contest</div>
							<div class="table-cell middle heading-type">Type</div>
							<div class="table-cell middle heading-wager">Wager</div>
							<div class="table-cell middle">Team(s)</div>
							<div class="table-cell middle heading-status">Status / Result</div>
						
						</div>
						
						<?php 
						$args = array(
							'post_type' => 'wager',
							'posts_per_page' => -1,
							'author' => $current_user_id,
							'tax_query' => array(
								array(
									'taxonomy' => 'wager_result',
									'field'    => 'slug',
									'terms'    => 'open',
								)
							),
						);
						
						$i = 0;
						
						$the_query = new WP_Query( $args );
						
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								
								$the_query->the_post();
								
								$contest = get_field('wager_contest',$post->ID);
								$contest_status = get_field('contest_status', $contest);
								
								if ($contest_status == 'In Progress') {
								
									$contest_title = str_replace('Point Spread', '', get_field('contest_title_without_type', $contest));
									$wager_result = get_field('wager_result');
									
									$wager_type = strtolower(get_field('wager_type',$post->ID));
									$winners_html = '';
									
									$league = get_the_terms( $post->ID, 'league' );
									$league_id = $league[0]->term_id;
									$league_name = $league[0]->name;
									
									if ($wager_type == 'win' || $wager_type == 'place' || $wager_type == 'show' || $wager_type == 'spread' || $wager_type =='moneyline' || $wager_type == 'over/under') {
										if ($wager_type == 'spread') {
											$point_spread = get_field('point_spread', $post->ID);
											if ($point_spread > 0) {
												$point_spread = '+'.$point_spread;
											}
											$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . $point_spread . ')';
										}
										else if ($wager_type == 'moneyline') {
											
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
											
											$terms = get_terms( $args );
											
											foreach ($terms as $team) {
												
												$league_parent_id = $team->parent;
												$league_parent = get_term_by( 'term_id', $league_parent_id, 'team' );
												$league_parent_name = $league_parent->name;
												
												if ($league_parent_name == $league_name) {
													$moneyline = get_field('wager_moneyline', $post->ID);
													if ($moneyline > 0) {
														$moneyline_plus_minus = '+';
													}
													else {
														$moneyline_plus_minus = '';
													}
													$winners_html = $team->name . ' (' .$moneyline_plus_minus. $moneyline . ')';
												}
											
											}
											
										}
										else if ($wager_type == 'over/under') {
											$winners_html = get_field('wager_winner_1_name', $post->ID);
										}
										else {
											$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)';
										}
										$winnings = get_field('potential_winnings',$post->ID);
									}
									else if ($wager_type == "pick 2" || $wager_type == "pick 2 box") {
										$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
											get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)';
											$winnings = get_field('potential_winnings',$post->ID);
									}
									else if ($wager_type == "pick 3" || $wager_type == "pick 3 box") {
										$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
											get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)' . '<br>' . 
											get_field('wager_winner_3_name', $post->ID) . ' (' . get_field('winner_3_odds', $post->ID) . ':1)';
											$winnings = get_field('potential_winnings',$post->ID);
									}
									else if ($wager_type == "pick 4" || $wager_type == "pick 4 box") {
										$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
											get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)' . '<br>' . 
											get_field('wager_winner_3_name', $post->ID) . ' (' . get_field('winner_3_odds', $post->ID) . ':1)' . '<br>' .
											get_field('wager_winner_4_name', $post->ID) . ' (' . get_field('winner_4_odds', $post->ID) . ':1)';
											$winnings = get_field('potential_winnings',$post->ID);
									}	
									else if ($wager_type == "pick 6") {
										$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
											get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)' . '<br>' . 
											get_field('wager_winner_3_name', $post->ID) . ' (' . get_field('winner_3_odds', $post->ID) . ':1)' . '<br>' .
											get_field('wager_winner_4_name', $post->ID) . ' (' . get_field('winner_4_odds', $post->ID) . ':1)' . '<br>' .
											get_field('wager_winner_5_name', $post->ID) . ' (' . get_field('winner_5_odds', $post->ID) . ':1)' . '<br>' .
											get_field('wager_winner_6_name', $post->ID) . ' (' . get_field('winner_6_odds', $post->ID) . ':1)';
											$winnings = get_field('potential_winnings',$post->ID);
									}	
									
									echo '<a href="'.get_permalink($contest).'" class="table-row wager-row wager-status-'.strtolower($wager_result).'">' .
										'<div class="table-cell middle wager-id">'.$post->ID.'</div>' .
										'<div class="table-cell middle wager-contest">'.$contest_title.'</div>' .
										'<div class="table-cell middle wager-type">'.get_field('wager_type',$post->ID).'</div>' .
										'<div class="table-cell middle wager-amount">Bet <strong>$'.number_format(get_field('wager_amount',$post->ID),2).'</strong> to win <strong>$'.$winnings.'</strong></div>' .
										'<div class="table-cell middle wager-winners">'.$winners_html.'</div>' .
										'<div class="table-cell middle wager-status">'.$wager_result.'</div>' .
									'</a>';
									
									$i++;
								
								}
								
							}
						}
						
						wp_reset_query();
					
						?>
						
					</div>
					
					<?php 
					if ($i == 0) {
						echo '<div class="mycontests-no-contests align-center">There are no contests to display.</div>';
					}
					?>
					
				</div>
				
				<div class="my-contests my-contests-closed">
				
					<div class="table fullwidth">
						
						<div class="table-row table-heading">
						
							<div class="table-cell middle heading-id">ID</div>
							<div class="table-cell middle heading-contest">Contest</div>
							<div class="table-cell middle heading-type">Type</div>
							<div class="table-cell middle heading-wager">Wager</div>
							<div class="table-cell middle">Team(s)</div>
							<div class="table-cell middle heading-status">Status / Result</div>
						
						</div>
						
						<?php 
						$args = array(
							'post_type' => 'wager',
							'posts_per_page' => -1,
							'author' => $current_user_id,
							'tax_query' => array(
								'relation' => 'OR',
								array(
									'taxonomy' => 'wager_result',
									'field'    => 'slug',
									'terms'    => 'win',
								),
								array(
									'taxonomy' => 'wager_result',
									'field'    => 'slug',
									'terms'    => 'loss',
								),
								array(
									'taxonomy' => 'wager_result',
									'field'    => 'slug',
									'terms'    => 'push',
								),
							),
						);
						
						$i = 0;
						
						$the_query = new WP_Query( $args );
						
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								
								$the_query->the_post();
								
								$contest = get_field('wager_contest',$post->ID);
								$contest_title = str_replace('Point Spread', '', get_field('contest_title_without_type', $contest));
								$wager_result = get_field('wager_result');
								
								$wager_type = strtolower(get_field('wager_type',$post->ID));
								$winners_html = '';
								
								$league = get_the_terms( $post->ID, 'league' );
								$league_id = $league[0]->term_id;
								$league_name = $league[0]->name;
								
								if ($wager_type == 'win' || $wager_type == 'place' || $wager_type == 'show' || $wager_type == 'spread' || $wager_type =='moneyline' || $wager_type == 'over/under') {
									if ($wager_type == 'spread') {
										$point_spread = get_field('point_spread', $post->ID);
										if ($point_spread > 0) {
											$point_spread = '+'.$point_spread;
										}
										$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . $point_spread . ')';
									}
									else if ($wager_type == 'moneyline') {
										
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
										
										$terms = get_terms( $args );
										
										foreach ($terms as $team) {
											
											$league_parent_id = $team->parent;
											$league_parent = get_term_by( 'term_id', $league_parent_id, 'team' );
											$league_parent_name = $league_parent->name;
											
											if ($league_parent_name == $league_name) {
												$moneyline = get_field('wager_moneyline', $post->ID);
												if ($moneyline > 0) {
													$moneyline_plus_minus = '+';
												}
												else {
													$moneyline_plus_minus = '';
												}
												$winners_html = $team->name . ' (' .$moneyline_plus_minus. $moneyline . ')';
											}
										
										}
										
									}
									else if ($wager_type == 'over/under') {
										$winners_html = get_field('wager_winner_1_name', $post->ID);
									}
									else {
										$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)';
									}
									$winnings = get_field('potential_winnings',$post->ID);
								}
								else if ($wager_type == "pick 2" || $wager_type == "pick 2 box") {
									$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
										get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)';
									$winnings = get_field('potential_winnings',$post->ID);
								}
								else if ($wager_type == "pick 3" || $wager_type == "pick 3 box") {
									$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
										get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)' . '<br>' . 
										get_field('wager_winner_3_name', $post->ID) . ' (' . get_field('winner_3_odds', $post->ID) . ':1)';
										$winnings = get_field('potential_winnings',$post->ID);
								}
								else if ($wager_type == "pick 4" || $wager_type == "pick 4 box") {
									$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
										get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)' . '<br>' . 
										get_field('wager_winner_3_name', $post->ID) . ' (' . get_field('winner_3_odds', $post->ID) . ':1)' . '<br>' .
										get_field('wager_winner_4_name', $post->ID) . ' (' . get_field('winner_4_odds', $post->ID) . ':1)';
										$winnings = get_field('potential_winnings',$post->ID);
								}	
								else if ($wager_type == "pick 6") {
									$winners_html = get_field('wager_winner_1_name', $post->ID) . ' (' . get_field('winner_1_odds', $post->ID) . ':1)' . '<br>' . 
										get_field('wager_winner_2_name', $post->ID) . ' (' . get_field('winner_2_odds', $post->ID) . ':1)' . '<br>' . 
										get_field('wager_winner_3_name', $post->ID) . ' (' . get_field('winner_3_odds', $post->ID) . ':1)' . '<br>' .
										get_field('wager_winner_4_name', $post->ID) . ' (' . get_field('winner_4_odds', $post->ID) . ':1)' . '<br>' .
										get_field('wager_winner_5_name', $post->ID) . ' (' . get_field('winner_5_odds', $post->ID) . ':1)' . '<br>' .
										get_field('wager_winner_6_name', $post->ID) . ' (' . get_field('winner_6_odds', $post->ID) . ':1)';
										$winnings = get_field('potential_winnings',$post->ID);
								}
								
								echo '<a href="'.get_permalink($contest).'" class="table-row wager-row wager-status-'.strtolower($wager_result).'">' .
									'<div class="table-cell middle wager-id">'.$post->ID.'</div>' .
									'<div class="table-cell middle wager-contest">'.$contest_title.'</div>' .
									'<div class="table-cell middle wager-type">'.get_field('wager_type',$post->ID).'</div>' .
									'<div class="table-cell middle wager-amount">Bet <strong>$'.number_format(get_field('wager_amount',$post->ID),2).'</strong> to win <strong>$'.$winnings.'</strong></div>' .
									'<div class="table-cell middle wager-winners">'.$winners_html.'</div>' .
									'<div class="table-cell middle wager-status">'.strtoupper($wager_result).'<span class="results-text">See Results <i class="fas fa-arrow-circle-right"></i></span></div>' .
								'</a>';
								
								$i++;
								
							}
						}
						wp_reset_query();
						?>
						
					</div>
					
					<?php 
					if ($i == 0) {
						echo '<div class="mycontests-no-contests align-center">There are no contests to display.</div>';
					}
					?>
				
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

<?php get_footer(); ?>