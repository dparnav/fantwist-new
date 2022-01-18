<?php /* Template Name: Score Board */ ?>
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

<div class="page-hero lobby-hero score-board-hero" style="background-image:url(<?php echo get_the_post_thumbnail_url($post->ID,'full'); ?>)">
	<div class="hero-details centered-vertical noselect">
		<div class="inner-wrap">
			<h1 id="lobby-title score-board-title"><?php the_title(); ?></h1>
		</div>
	</div>
</div>

<?php
//select date for the contest
if(isset($_GET['contest-date'])){
	//selected date
	$contest_date = date("m/d/Y", strtotime($_GET['contest-date']));
}else{
	//current date
	$contest_date = date("m/d/Y", current_time('timestamp')); 
}
?>

<div class="lobby-box page-box score-board-box wrap">
	<div class="inner-wrap lobby-wrap score-board-title-wrap">
		<div class="date-selector-for-scoreboard">
			<form method="get">
				<input type="text" name="contest-date" value="<?=$contest_date?>" id="contest-date"><span class="calender-css"><i class="far fa-calendar-alt"></i></span>
				<button><span class="fas fa-search"></span></button>
			</form>
		</div>
		
		<div class="score-board">

				<?php 
				
					if(isset($_GET['contest-date'])){
						//selected date
						$contest_date = date("Y-m-d", strtotime($_GET['contest-date']));
					}else{
						//current date
						$contest_date = date("Y-m-d", current_time('timestamp'));
					}

					$nfl_count = 0;

					$post_query = new WP_Query( array(
						'post_type' => 'contest',
						'posts_per_page' => -1,
						'meta_query' => array(
							array(
								'key'     => 'contest_date_sort',
								'value'   => $contest_date,
								'compare' => 'LIKE'
							)
						),
					) );

					if(!empty($post_query->posts)){

						//sort the contests by the league
						$sort = array();
						foreach ($post_query->posts as $key => $part) {
							$sort[$key] = (get_the_terms( $part->ID, 'league' ))[0]->name;
						}
						array_multisort($sort, SORT_ASC, $post_query->posts);

						foreach($post_query->posts as $post){

							//setting data variables
							$league = (get_the_terms( $post->ID, 'league' ))[0]->name;
							$contest_id = $post->ID;
							
							if($league == "NFL"){
								$nfl_main_contest = get_field('nfl_main_contest', $post->ID);
								if($nfl_main_contest != ""){
									$post->ID = $nfl_main_contest;
								}else{
									continue;
								}
							}

							//see if games have started, in progress or finished
							$results = get_field('contest_results', $post->ID);
							if (!empty($results)) {
								$contest_data = json_decode($results, false, JSON_UNESCAPED_UNICODE);

								if(get_field('games_status',$post->ID) == "Done" && empty($contest_data)){
                                    update_field('contest_results', '', $post->ID);
                                    update_field('contest_live_stats_url', '', $post->ID);
                                    update_field('games_status', 'Not Done', $post->ID);
                                    header('Location: '.$_SERVER['REQUEST_URI']);
                                }
                                $contest_data_projection = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
							}
							else {
								$contest_data = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
							}

							//show the games only for today
							$stop_post = true;

							foreach($contest_data as $contest){
								if(date("Y-m-d", strtotime($contest->game_start)) == date("Y-m-d", strtotime($contest_date))){
									$stop_post = false;
								}
							}

							if($stop_post){
								continue;
							}

							//player types
							$player_types = array(); 
							switch($league){
								case "MLB" :
									$player_types = array('Hitters','Pitchers');
									break;
								case "NBA" :
									$player_types = array('C','F','G');
									break;
								case "NHL" :
									$player_types = array('Goalie','Players');
									break;
								case "NFL" :
									$player_types = array('QB','RB','WR','TE','D/SP Teams');
									break;
							}

							
							//display the name of the league
							if($league == "NFL" && $nfl_count != 0){}else{
								echo '<h2 class="how-to-play-wrap score-board-title-wrap">'.$league.'</h2>';
							}

							//the top bar for the table
							echo '<div class="my-contests my-score-board show">
									<div class="table fullwidth">';

							if($league == "NFL" && $nfl_count != 0){}else{
								echo '<div class="table-row table-heading">
									<div class="table-cell middle">Team Name</div>
									<div class="table-cell middle wager-status">Total</div>';
								foreach($player_types as $player_type){
									echo '<div class="table-cell middle wager-status">'.$player_type.'</div>';
								}
								echo '</div>';
							}

							//the data for table
							foreach($contest_data as $contest){

								if(date("Y-m-d", strtotime($contest->game_start)) == date("Y-m-d", strtotime($contest_date))){

									$date_differenece = strtotime(date("d-m-Y", current_time( 'timestamp' ))) - strtotime(date("d-m-Y", strtotime($contest->game_start)));
									$time_differenece = ((current_time( 'timestamp') - 60) - strtotime(date("d-m-Y H:i:s", (strtotime($contest->game_start)))))/60;
									$game_status = $contest->is_game_over;

									if (!empty($results)) {
										if($game_status === true){
											$game_from_contest = true;
											$final_status = '(F)';
										}else{
											if($date_differenece < 0){
													$final_status = '';
													$game_from_contest = false;
											}else{
												if($time_differenece >= 1){
													$final_status = '';
													$run_my_ajax = 1;
													$game_from_contest = true;
												}else{
													$final_status = '';
													$game_from_contest = false;
												}
											}
										}
									}else{
										$game_from_contest = true;
										$final_status = '';
									}

									if($game_from_contest){
										$team1 = $contest->team1;
										$team2 = $contest->team2;
									}else{
										foreach($contest_data_projection as $projection_data){
											if( $projection_data->game_id == $contest->game_id ){
												$team1 = $projection_data->team1;
												$team2 = $projection_data->team2;
											}
										}
									}

									$team1_total = 0;
									$team2_total = 0;
									$team_players = array('team1'=>'team1_players','team2'=>'team2_players');
									
									switch($league){
										case "MLB" :
											foreach($team_players as $team => $team_player){
												${$team_player} = array('Hitters'=>0,'Pitchers'=>0);
												if(!empty($results) && $game_from_contest){
													foreach(${$team}->player as $key => $player){
														switch($key){
															case "P"  : ${$team_player}['Pitchers'] += (float)$player; break;
															case "SP" : ${$team_player}['Pitchers'] += (float)$player; break;
															case "RP" : ${$team_player}['Pitchers'] += (float)$player; break;

															case "1B" : ${$team_player}['Hitters'] += (float)$player; break;
															case "2B" : ${$team_player}['Hitters'] += (float)$player; break;
															case "3B" : ${$team_player}['Hitters'] += (float)$player; break;
															
															case "C"  : ${$team_player}['Hitters'] += (float)$player; break;
															case "CF" : ${$team_player}['Hitters'] += (float)$player; break;

															case "DH" : ${$team_player}['Hitters'] += (float)$player; break;
															case "IF" : ${$team_player}['Hitters'] += (float)$player; break;

															case "LF" : ${$team_player}['Hitters'] += (float)$player; break;
															case "OF" : ${$team_player}['Hitters'] += (float)$player; break;

															case "PH" : ${$team_player}['Hitters'] += (float)$player; break;
															case "PR" : ${$team_player}['Hitters'] += (float)$player; break;

															case "RF" : ${$team_player}['Hitters'] += (float)$player; break;
															case "SS" : ${$team_player}['Hitters'] += (float)$player; break;
														}
													}
												}
											}
											break;
										case "NBA" :
											foreach($team_players as $team => $team_player){
												${$team_player} = array('C'=>0,'F'=>0,'G'=>0);
												if(!empty($results) && $game_from_contest){
													foreach(${$team}->player as $key => $player){
														switch($key){
															case "C" : ${$team_player}['C'] += (float)$player; break;
															case "PF" : ${$team_player}['F'] += (float)$player; break;
															case "SF" : ${$team_player}['F'] += (float)$player; break;
															case "PG" : ${$team_player}['G'] += (float)$player; break;
															case "SG" : ${$team_player}['G'] += (float)$player; break;
														}
													}
												}
											}
											break;
										case "NHL" :
											foreach($team_players as $team => $team_player){
												${$team_player} = array('Goalie'=>0,'Players'=>0);
												if(!empty($results) && $game_from_contest){
													foreach(${$team}->player as $key => $player){
														switch($key){
															case "G" : ${$team_player}['Goalie'] += (float)$player; break;
															default : ${$team_player}['Players'] += (float)$player; break;
														}
													}
												}
											}
											break;
										case "NFL" :
											foreach($team_players as $team => $team_player){
												${$team_player} = array('QB'=>0,'RB'=>0,'WR'=>0,'TE'=>0,'DST'=>0);
												if(!empty($results) && $game_from_contest){
													foreach(${$team}->player as $key => $player){
														switch($key){
															case "QB" : ${$team_player}['QB'] += (float)$player; break;
															case "RB" : ${$team_player}['RB'] += (float)$player; break;
															case "WR" : ${$team_player}['WR'] += (float)$player; break;
															case "TE" : ${$team_player}['TE'] += (float)$player; break;
															default : ${$team_player}['DST'] += (float)$player; break;
														}
													}
												}
											}
											break;
									}
									
									foreach($team1_players as $player){
										$team1_total += (float)$player;
									}
									foreach($team2_players as $player){
										$team2_total += (float)$player;
									}

									echo '<a class="table-row wager-row team-one">
											<div class="table-cell middle team-name">'.$team1->name.' <span>'.$final_status.'</span></div>
											<div class="table-cell middle total-points">'.(number_format($team1_total,2)).'</div>';
									foreach($team1_players as $player){
										echo '<div class="table-cell middle position-points">'.(number_format($player,2)).'</div>';
									}
									echo '</a>
										<a class="table-row wager-row team-two">
											<div class="table-cell middle team-name">'.$team2->name.' <span>' .$final_status. '</span></div>
											<div class="table-cell middle total-points">'.(number_format($team2_total,2)).'</div>';
									foreach($team2_players as $player){
										echo '<div class="table-cell middle position-points">'.(number_format($player,2)).'</div>';
									}
									echo '</a><div class="scoreboard-row"></div>';
								}
							}
							echo '</div></div>';
							

							if($league == "NFL"){
								$nfl_count++;
							}
						}
					}else{
						?>
							<div class="no-games-available">
								<h2 class="how-to-play-wrap score-board-title-wrap">The games are not available right now.</h2>
							</div>
						<?php
					}
				?>	

		</div>	
	</div>

</div>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script>
	jQuery(document).ready(function(){
		jQuery('#contest-date').datepicker();
    });
</script>

<?php get_footer(); ?> 