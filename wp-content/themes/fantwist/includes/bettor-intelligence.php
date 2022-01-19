<div class="bettor-intelligence-wrap">

	<div class="section-header align-center">Your Picks Stats</div>

	<div class="bettor-intelligence table fixed-layout full-width">

		<div class="table-row">

			<div class="table-cell column-header">Bet Type</div>
			<div class="table-cell column-header">Win/Loss</div>
			<div class="table-cell column-header">Hit Rate</div>

		</div>

		<?php

		$args = array(
			'taxonomy' => 'wager_type',
			'hide_empty' => false,
		);

		$bet_types = get_terms($args);

		$total_wagered = 0;
		$total_wagers = 0;
		$total_wins = 0;
		// Remove parlay and teaser of your Picks stats column
		$exclude = array(52, 53, 54, 55, 56, 57, 58, 59, 60, 61,4306,4316,4317);

		foreach ($bet_types as $type) {
			if ($type->name == 'Prop') {
				continue;
			}

			if (!in_array($type->term_id, $exclude)) :
		?>
					<div class="table-row">

						<div class="table-cell column-value bet-type"><?= $type->name ?></div>
						<div class="table-cell column-value wagered">

							<?php

							$units_wagered = 0;
							$wager_count = 0;
							$wager_wins = 0;

							$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

							if (strpos($url, '/user/') !== false) {
								$username = str_replace('/', '', str_replace('/user/', '', $_SERVER['REQUEST_URI']));
								$user = get_user_by('login', $username);
								$user_id = $user->ID;
							} else {
								$user_id = get_current_user_id();
							}

							if ($type->name == "Parlay") {
								$args = array(
									'post_type' => 'parlaywager',
									'author' => $user_id,
									'posts_per_page' => -1
								);

								$the_query = new WP_Query($args);

								if ($the_query->have_posts()) {
									while ($the_query->have_posts()) {

										$the_query->the_post();

										$wager_amount = str_replace(',', '', json_decode(get_field('parlay_data'), false, JSON_UNESCAPED_UNICODE)->wager_amount);
										$units_wagered += $wager_amount;
										$total_wagered += $wager_amount;

										$wager_result = get_field('wager_result');
										if ($wager_result == 'WIN') {
											$wager_wins++;
											$total_wins++;
										}
										if ($wager_result == 'WIN' || $wager_result == 'LOSS') {
											$wager_count++;
											$total_wagers++;
										}
									}
								}
								wp_reset_postdata();
							} else if ($type->name == "Teaser") {
								$args = array(
									'post_type' => 'teaserwager',
									'author' => $user_id,
									'posts_per_page' => -1
								);

								$the_query = new WP_Query($args);

								if ($the_query->have_posts()) {
									while ($the_query->have_posts()) {

										$the_query->the_post();

										$wager_amount = str_replace(',', '', json_decode(get_field('teaser_data'), false, JSON_UNESCAPED_UNICODE)->wager_amount);
										$units_wagered += $wager_amount;
										$total_wagered += $wager_amount;

										$wager_result = get_field('wager_result');
										if ($wager_result == 'WIN') {
											$wager_wins++;
											$total_wins++;
										}
										if ($wager_result == 'WIN' || $wager_result == 'LOSS') {
											$wager_count++;
											$total_wagers++;
										}
									}
								}
								wp_reset_postdata();
							} else {
								$args = array(
									'post_type' => 'wager',
									'author' => $user_id,
									// 'tax_query' => array(
									// 	array(
									// 		'taxonomy' => 'wager_type',
									// 		'field'    => 'slug',
									// 		'terms'    => $type->slug,
									// 	),
									// ),
									'posts_per_page' => -1,
								);

								$the_query = new WP_Query($args);

								if ($the_query->have_posts()) {
									while ($the_query->have_posts()) {
										$the_query->the_post();

										$wager_type = get_field('wager_type');
										$wager_amount = str_replace(',', '', get_field('wager_amount'));
										if ($wager_type == 'Spread' && $type->name == $wager_type) {
											$units_wagered += $wager_amount;
											$total_wagered += $wager_amount;
										}
										if ($wager_type == 'Moneyline' && $type->name == $wager_type) {
											$units_wagered += $wager_amount;
											$total_wagered += $wager_amount;
										}
										if ($wager_type == 'Over/Under' && $type->name == $wager_type) {
											$units_wagered += $wager_amount;
											$total_wagered += $wager_amount;
										}
										$wager_result = get_field('wager_result');

										if ($wager_result == 'Win' && $wager_type == 'Spread' && $type->name == $wager_type) {
											$wager_wins++;
											$total_wins++;
										}
										if (($wager_result == 'Win' || $wager_result == 'Loss') && $wager_type == 'Spread' && $type->name == $wager_type) {
											$wager_count++;
											$total_wagers++;
										}
										//moneyline
										if ($wager_result == 'Win' && $wager_type == 'Moneyline' && $type->name == $wager_type) {
											$wager_wins++;
											$total_wins++;
										}
										if (($wager_result == 'Win' || $wager_result == 'Loss') && $wager_type == 'Moneyline' && $type->name == $wager_type) {
											$wager_count++;
											$total_wagers++;
										}
										//Over/Under
										if ($wager_result == 'Win' && $wager_type == 'Over/Under' && $type->name == $wager_type) {
											$wager_wins++;
											$total_wins++;
										}
										if (($wager_result == 'Win' || $wager_result == 'Loss') && $wager_type == 'Over/Under' && $type->name == $wager_type) {
											$wager_count++;
											$total_wagers++;
										}
									}
								}
								wp_reset_postdata();
							}
							echo $wager_wins.'/'.$wager_count;
							?>

						</div>

						<div class="table-cell column-value"><?php if ($wager_count != 0) {
																	echo number_format(($wager_wins/$wager_count)*100,2 )."%";
																} else {
																	echo '0%';
																} ?></div>

					</div>

		<?php
				
			endif;
		}

		?>

		<div class="table-row column-totals">

			<div class="table-cell column-total">TOTAL</div>
			<div class="table-cell column-total"><?php echo $total_wins .'/'. $total_wagers ?></div>
			<div class="table-cell column-total"><?php if ($total_wagers != 0 || $total_wins !=0) {
														echo number_format(($total_wins/ $total_wagers)*100,2)."%";
													} else {
														echo '0%';
													} ?></div>

		</div>

	</div>

	<a class="view-all-wagers" href="<?= home_url() ?>/my-wagers/">View All Wagers <i class="fas fa-arrow-circle-right"></i></a>

</div>