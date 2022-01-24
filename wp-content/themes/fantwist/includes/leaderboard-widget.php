<?php
if (is_user_logged_in()) {
	$current_user_id = get_current_user_id();
} else {
	$current_user_id = 0;
}
?>

<div class="leaderboard-wrap">

	<div class="section-header noselect"><?php if (!is_page('leaderboard')) { ?>Top 10 <?php } ?>Leaderboard</div>

	<div class="leaderboard-inner table fixed-layout full-width align-center">

		<div class="table-row">

			<div class="table-cell column-header">Rank</div>
			<div class="table-cell column-header">Player</div>
			<div class="table-cell column-header">Wins</div>

		</div>

		<?php

		//all users data
		$users = [];

		if(wp_get_current_user()->roles[0] == 'administrator'){
			//get all users
			$users = get_users();
		}else{
			//get current user
			array_push($users,get_user_by('id', get_current_user_id()));
		}

		$leaders = array();

		foreach ($users as $user) {
			$leader = array();
			$leader['user_login'] = $user->user_login;
			$leader['nickname'] = $user->nickname;
			$leader['balance'] = get_field('account_balance', 'user_' . $user->ID);
			$leader['ID'] = $user->ID;
	
			// win results args
			$win_wager_data_args = array(
				'post_type'  => 'wager',
				'author'     =>  $user->ID,
				'posts_per_page' => 10,
				'meta_query' => array(
	
					array(
						'key' => 'wager_result',
						'value'   => "Win"
					)
				),
	
			);
			//all results args
			$total_wager_data_args = array(
				'post_type'  => 'wager',
				'author'     =>  $user->ID,
				'posts_per_page' => 10,
				'meta_query' => array(
	
					array(
						'key' => 'wager_result',
						'value'   => array("Win", "Loss", "Push"),
	
					)
				),
	
			);
	
			$win_wager = new WP_Query($win_wager_data_args);
			$total_wager = new WP_Query($total_wager_data_args);
	
			$leader['WIN'] = 0;
			$leader['TOTAL'] = 0;
			$leader['RESULT'] = 0;
	
			$leader['WIN'] = $win_wager->post_count;
			$leader['TOTAL'] = $total_wager->post_count;
			if ($leader['RESULT'] != 0) {
	
				$leader['RESULT'] =  ($user['WIN'] / $user['TOTAL'])*100;
			}
	
			$leaders[] = $leader;
		}



		//Sort leaderboard
		$sort = array();
		foreach ($leaders as $key => $part) {
			$sort[$key] = $part['balance'];
		}
		array_multisort($sort, SORT_DESC, $leaders);

		if (is_page('leaderboard')) {
			$limit = 9999999;
		} else {
			$limit = 11;
		}

		$count = 1;

		foreach ($leaders as $user) {

			if ($count < $limit) {
		?>

				<a href="/user/<?php echo $user['user_login']; ?>" class="leaderboard-user table-row <?= ($current_user_id==$user['ID'])?'strong':''; ?> ">

					<div class="table-cell leaderboard-count">
						<?php echo $count; ?>
					</div>
					<div class="table-cell leaderboard-username">
						<?php echo $user['nickname']; ?>
					</div>
					<div class="table-cell leaderboard-balance strong">
						<?= $user['WIN'] . '/' . $user['TOTAL'] ?>
					</div>

				</a>

		<?php
			} else {
				break;
			}

			$count++;
		}

		?>

	</div>

	<?php if (!is_page('leaderboard')) { ?>

		<a class="view-all-wagers" href="/leaderboard">View Full Leaderboard <i class="fas fa-arrow-circle-right"></i></a>

	<?php } ?>

</div>