<div class="bettor-intelligence-wrap score-board-stats-wrap">
	
	<div class="section-header align-center">ScoreBoard</div>
	
	<div class="bettor-intelligence score-boad-stats table fixed-layout full-width">
	
		<div class="table-row">
		<div class="table-cell column-header team-league">Game Type</div>
			<div class="table-cell column-header team-name">Team</div>
			
			<div class="table-cell column-header team-score">Total</div>
		
		</div>

		<?php 
			$gameCount  = 0;
			//current date
			$contest_date = date("Y-m-d", current_time('timestamp'));

			$post_query = new WP_Query( array(
				'post_type' => 'contest',
				'posts_per_page' => -1,
				'meta_query' => array(array(
						'key'     => 'contest_date_sort',
						'value'   => $contest_date,
						'compare' => 'LIKE'
				)),
			) );

			foreach($post_query->posts as $post){

				//setting data variables
				$league = (get_the_terms( $post->ID, 'league' ))[0]->name;
				$contest_id = $post->ID;

				//see if games have started, in progress or finished
				$results = get_field('contest_results', $post->ID);
				if (!empty($results)) {
					$contest_data = json_decode($results, false, JSON_UNESCAPED_UNICODE);
				}
				else {
					$contest_data = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
				}
				
				//the data for table
				foreach($contest_data as $contest){

					$date_differenece = strtotime(date("d-m-Y", current_time( 'timestamp' ))) - strtotime(date("d-m-Y", strtotime($contest->game_start)));
					$time_differenece = ((current_time( 'timestamp') - 60) - strtotime(date("d-m-Y H:i:s", (strtotime($contest->game_start)))))/60;
					$game_status = $contest->is_game_over;

					$gameStatusText = '';
					if (!empty($results)) {
						if($game_status === true){
							$projected_or_live = 'Final Points: ';
							$gameStatusText = ' (F)';
							$game_from_contest = true;
							$final_class = "final";
						}else{
							$final_class = "";
							if($date_differenece < 0){
									$projected_or_live = 'Projected Fantasy Points: ';
									$game_from_contest = false;
									continue;
							}else{
								if($time_differenece >= 1){
									$projected_or_live = 'Live Points: ';
									$game_from_contest = true;
								}else{
									$projected_or_live = 'Projected Fantasy Points: ';
									$game_from_contest = false;
									continue;
								}
							}
						}
					}else{
						$final_class = "";
						$projected_or_live = 'Projected Fantasy Points: ';
						$game_from_contest = true;
						continue;
					}



					$team1 = $contest->team1;
					$team2 = $contest->team2;

					$team1_total = 0;
					$team2_total = 0;

					foreach($team1->player as $player){
						$team1_total += (float)$player;
					}
					foreach($team2->player as $player){
						$team2_total += (float)$player;
					}
					
					
					echo '<div class="table-row">
						<div class="table-cell column-valu team-league">
							<div class="team-value">'.$league.$gameStatusText.'</div>
						</div>
						<div class="table-cell column-value team-name">
							<div class="team-value">'.$team1->name.'</div>
							<div class="team-value">'.$team2->name.'</div>
						</div>
						
						<div class="table-cell column-value team-score">
							<div class="team-value">'.$team1_total.'</div>
							<div class="team-value">'.$team2_total.'</div>
						</div>
					</div>';

				}

				$gameCount++;
			}

			if($gameCount==0)
			{
				echo '<div class="table-row" style="bottom: 22px; position: relative; height: 30px;">
						<div class="table-cell column-valu team-league" style="width: 100%; position: absolute; padding: 30px;">
							No records found!
						</div>
					</div>';
			}
		?>
		
	</div>
		
	<a class="view-all-wagers view-scoreboard" href="<?=home_url()?>/scoreboard/">View Scoreboard <i class="fas fa-arrow-circle-right"></i></a>
			
</div>