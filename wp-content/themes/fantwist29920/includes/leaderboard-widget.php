<?php 
if (is_user_logged_in()) {
	$current_user_id = get_current_user_id();
}
else {
	$current_user_id = 0;
}
?>

<div class="leaderboard-wrap">
		
	<div class="section-header noselect"><?php if (!is_page('leaderboard')) { ?>Top 10 <?php } ?>Leaderboard</div>

	<div class="leaderboard-inner table fixed-layout full-width align-center">
	
		<div class="table-row">
			
			<div class="table-cell column-header">Rank</div>
			<div class="table-cell column-header">Player</div>
			<div class="table-cell column-header">Balance</div>
			
		</div>

	<?php 
	
	$users = get_users();
	$leaders = array();
	
	foreach ($users as $user) {
		
		if ($user->ID != '10') { //WP Engine
		
			$leader = array();
			$leader['user_login'] = $user->user_login;
			$leader['nickname'] = $user->nickname;
			$leader['balance'] = get_field('visible_balance', 'user_'.$user->ID);
			$leader['ID'] = $user->ID;
			
			$leaders[] = $leader;	
		
		}

	}		
	
	//Sort leaderboard
	$sort = array();
	foreach ($leaders as $key => $part) {
		$sort[$key] = $part['balance'];
	}
	array_multisort($sort, SORT_DESC, $leaders);	
	
	if (is_page('leaderboard')) {
		$limit = 9999999;
	}
	else {
		$limit = 11;
	}
	
	$count = 1;
	
	foreach ($leaders as $user) {
		
		if ($count < $limit) {
			?>
			
			<a href="/user/<?php echo $user['user_login']; ?>" class="leaderboard-user table-row <?php if ($current_user_id == $user['ID']) { echo 'strong'; }?>">
				
				<div class="table-cell leaderboard-count">
					<?php echo $count; ?>
				</div>
				<div class="table-cell leaderboard-username">
					<?php echo $user['nickname']; ?>
				</div>
				<div class="table-cell leaderboard-balance strong">
					$<?php echo number_format(get_field('visible_balance', 'user_'.$user['ID'])); ?>
				</div>
			
			</a>
			
			<?php
		}
		else {
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