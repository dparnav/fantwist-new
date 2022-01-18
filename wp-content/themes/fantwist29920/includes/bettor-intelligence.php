<div class="bettor-intelligence-wrap">
	
	<div class="section-header align-center">Your Betting Stats</div>
	
	<div class="bettor-intelligence table fixed-layout full-width">
	
		<div class="table-row">
		
			<div class="table-cell column-header">Bet Type</div>
			<div class="table-cell column-header">Wagered</div>
			<div class="table-cell column-header">Hit Rate</div>
		
		</div>
		
		<?php 
		
		$args = array(
			'taxonomy' => 'wager_type',
			'hide_empty' => false,
		);
		
		$bet_types = get_terms( $args ); 
		
		$total_wagered = 0;
		$total_wagers = 0;
		$total_wins = 0;
		
		$exclude = array(52,53,54,55,56,57,58,59,60,61);
		
		foreach ($bet_types as $type) {
			
			if (!in_array($type->term_id, $exclude)):
					
				?>
			
				<div class="table-row">
			
					<div class="table-cell column-value bet-type"><?php echo $type->name; ?></div>
					<div class="table-cell column-value wagered">
						
						<?php 
						$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			
						if (strpos($url,'/user/') !== false) {
							$username = str_replace('/','',str_replace('/user/','',$_SERVER['REQUEST_URI']));
							$user = get_user_by('login', $username);
							$user_id = $user->ID;
						}
						else {
							$user_id = get_current_user_id();
						}
						
						$args = array(
							'post_type' => 'wager',
							'author' => $user_id,
							'tax_query' => array(
								array(
									'taxonomy' => 'wager_type',
									'field'    => 'slug',
									'terms'    => $type->slug,
								),
							),
							'posts_per_page' => -1,
						);
						
						$the_query = new WP_Query( $args );
						$units_wagered = 0;
						$wager_count = 0;
						$wager_wins = 0;
						
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								
								$wager_amount = str_replace(',','',get_field('wager_amount'));
								$units_wagered += $wager_amount;
								$total_wagered += $wager_amount;
								
								$wager_result = get_field('wager_result');
								if ($wager_result == 'Win') {
									$wager_wins++;
									$total_wins++;
								}
								if ($wager_result == 'Win' || $wager_result == 'Loss') {
									$wager_count++;
									$total_wagers++;
								}
															
							}
						}
						wp_reset_postdata();
						
						echo '$' . number_format($units_wagered, 2, '.', ',');
						?>
						
					</div>
					
					<div class="table-cell column-value"><?php if ($wager_count != 0) { echo number_format(($wager_wins/$wager_count)*100, 1) . '%'; } else { echo '0.0%'; } ?></div>
				
				</div>
			
				<?php
					
			endif;
			
		}
		
		?>
		
		<div class="table-row column-totals">
		
			<div class="table-cell column-total">TOTAL</div>
			<div class="table-cell column-total"><?php echo '$' . number_format($total_wagered, 2, '.', ','); ?></div>
			<div class="table-cell column-total"><?php if ($total_wagers != 0) { echo number_format(($total_wins/$total_wagers)*100, 1) . '%'; } else { echo '0.0%'; } ?></div>

		</div>
	
	</div>
		
	<a class="view-all-wagers" href="/my-wagers/">View All Wagers <i class="fas fa-arrow-circle-right"></i></a>
			
</div>