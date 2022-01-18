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

<div class="lobby-box page-box score-board-box wrap">
	<div class="inner-wrap lobby-wrap score-board-title-wrap">
		<div class="date-selector-for-scoreboard">
			<form method="post">
				<input type="date" name="contest-date">
				<button>Submit</button>
			</form>
		</div>
		
		<div class="score-board">
			<?php 
				//select date for the contest
				if(isset($_POST['contest-date'])){
					$contest_date = date("Y-m-d", strtotime($_POST['contest-date']));
				}else{
					$contest_date = date("Y-m-d", current_time('timestamp')); 
				}

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
						$contest_data_projection = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
					}
					else {
						$contest_data = json_decode(get_field('contest_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
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
							$player_types = array('RB','QB','WR','TE','DST');
							break;
					}

					//display the name of the league
					echo '<h2 class="how-to-play-wrap score-board-title-wrap">'.$league.'</h2>';

					//the top bar for the table
					echo '<div class="my-contests my-score-board show">
							<div class="table fullwidth">
								<div class="table-row table-heading">
									<div class="table-cell middle">Team Name</div>
									<div class="table-cell middle wager-status">Total</div>';

					foreach($player_types as $player_type){
						echo '<div class="table-cell middle wager-status">'.$player_type.'</div>';
					}

					echo '</div>';

					//the data for table
					foreach($contest_data as $contest){

						$date_differenece = strtotime(date("d-m-Y", current_time( 'timestamp' ))) - strtotime(date("d-m-Y", strtotime($contest->game_start)));
						$time_differenece = ((current_time( 'timestamp') - 60) - strtotime(date("d-m-Y H:i:s", (strtotime($contest->game_start)))))/60;
						$game_status = $contest->is_game_over;

						if (!empty($results)) {
							if($game_status == true){
								$projected_or_live = 'Final Points: ';
								$game_from_contest = true;
							}else{
								if($date_differenece < 0){
										$projected_or_live = 'Projected Fantasy Points: ';
										$game_from_contest = false;
								}else{
									if($time_differenece >= 1){
										$projected_or_live = 'Live Points: ';
										$game_from_contest = true;
									}else{
										$projected_or_live = 'Projected Fantasy Points: ';
										$game_from_contest = false;
									}
								}
							}
						}else{
							$projected_or_live = 'Projected Fantasy Points: ';
							$game_from_contest = true;
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
									foreach(${$team}->player as $key => $player){
										switch($key){
											case "SP" : ${$team_player}['Pitchers'] += (int)$player; break;
											case "RP" : ${$team_player}['Pitchers'] += (int)$player; break;
											case "DH" : ${$team_player}['Hitters'] += (int)$player; break;
										}
									}
								}
								break;
							case "NBA" :
								foreach($team_players as $team => $team_player){
									${$team_player} = array('C'=>0,'F'=>0,'G'=>0);
									foreach(${$team}->player as $key => $player){
										switch($key){
											case "C" : ${$team_player}['C'] += (int)$player; break;
											case "PF" : ${$team_player}['F'] += (int)$player; break;
											case "SF" : ${$team_player}['F'] += (int)$player; break;
											case "PG" : ${$team_player}['G'] += (int)$player; break;
											case "SG" : ${$team_player}['G'] += (int)$player; break;
										}
									}
								}
								break;
							case "NHL" :
								foreach($team_players as $team => $team_player){
									${$team_player} = array('Goalie'=>0,'Players'=>0);
									foreach(${$team}->player as $key => $player){
										switch($key){
											case "G" : ${$team_player}['Goalie'] += (int)$player; break;
											default : ${$team_player}['Players'] += (int)$player; break;
										}
									}
								}
								break;
							case "NFL" :
								$team1_players = array('RB'=>0,'QB'=>0,'WR'=>0,'TE'=>0,'DST'=>0);
								break;
						}

						foreach($team1_players as $player){
							$team1_total += (int)$player;
						}
						foreach($team2_players as $player){
							$team2_total += (int)$player;
						}

						$team1->total_points = $projected_or_live.$team1->total_points;
						$team2->total_points = $projected_or_live.$team2->total_points;

						echo '<div class="scoreboard-row"><a class="table-row wager-row">
								<div class="table-cell middle team-name">'.$team1->name.' <span>('.$team1->total_points.')<span></div>
								<div class="table-cell middle total-points">'.$team1_total.'</div>';
						foreach($team1_players as $player){
							echo '<div class="table-cell middle position-points">'.$player.'</div>';
						}	
						echo '</a>
							<a class="table-row wager-row">
								<div class="table-cell middle team-name">'.$team2->name.' <span>('.$team2->total_points.')</span></div>
								<div class="table-cell middle total-points">'.$team2_total.'</div>';
						foreach($team2_players as $player){
							echo '<div class="table-cell middle position-points">'.$player.'</div>';
						}	
						echo '</a></div>';
					}
					echo '</div></div>';


					
				}
			?>			
		</div>	
	</div>

</div>
<script>
	jQuery('.lobby-tabs ul li').click(function () {
	var myText = jQuery(this).text();  
	if( myText == "Äll") {      
	jQuery("#lobby-title").text("CONTEST LOBBY");	
	} else {
	jQuery("#lobby-title").text(myText +" LOBBY");
	}
	});
</script>

<?php get_footer(); ?> 