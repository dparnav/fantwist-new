<?php /* Template Name: Admin Reports */ ?>
<?php get_header('custom'); ?>
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

<div class="content-wrapper pt-5 pb-2">
	<div class="container">
		<h4 class="mb-4"><?php the_title(); ?></h4>

	</div>
</div>

<!-- Begin Page Content -->
<div class="container">
	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		
		<div class="card-body">
		<h4><a href="<?=home_url();?>/large-bet-report">Large Bet Report</a> </h4>
		<p>In essence we would like to "flag" any single wager made that exceeds a predetermined dollar amount we establish.   At the outset we will establish a maximum wager amount for each wager type say $500.00, it would be nice to know when a player makes a wager of that amount.</p>
		
		<h4><a href="<?=home_url();?>/summary-exposure-report">Summary Exposure Report</a></h4>
		<p></p>

		<h4><a href="<?=home_url();?>/detailed-exposure-report">Detailed Exposure Report</a></h4>
		<p></p>

		<h4><a href="#">Player Tracking Report</a></h4>
		<p>
			This tracks each player’s bet and win/loss activity so we can determine what players are winning at an unusually high rate.<br>
		For all bet types.
		</p>
		
		<h4><a href="#">Game Liability Report – overall win/loss</a></h4>
		<p>
			How much has been won or lost on each side of each wager type. For all bet types.
		</p>

		<h4><a href="#">Game Liability Report – by all bet types</a></h4>
		<p>
			The dollar amounts wagered on each wager type thus identifying out liability on outcomes
		</p>

		<h4><a href="#">Number of bets – by bet type</a></h4>
		<p>
			Number of bets, not dollar dollar amounts, by bet type. Sometimes one big bet throws off the general picks public’s pattern. You want to be able to see that.
		</p>

		<h4><a href="#">What If Report</a></h4>
		<p>Projected outcome of game win loss. i.e., what if Jets 10 Raiders 21?
		</p>

		<h4><a href="#">Projection Audit Report</a></h4>
		<p>Break down of any line movement by type of bet. Where did the number open, how was the line moved?  Was it moved correctly or did someone move the line the wrong way.</p>

		<h4><a href="#">Lock Out Report</a></h4>
		<p>Were games locked out when game started other than in game wagering?</p>
		
		<h4><a href="#">Past Post Report</a></h4>
		<p>Were any bets made after game started other than in game wagering?</p>
		</div>
	</div>
</div>
<?php get_footer('custom'); ?>