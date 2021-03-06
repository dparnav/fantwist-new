		<?php 
		$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		
		if (strpos($url,'/user/') !== false) :
		
			if ( isset($_GET['profiletab']) == false ):
		
				$username = str_replace('/','',str_replace('/user/','',$_SERVER['REQUEST_URI']));
				$user = get_user_by('login', $username);
				$user_id = $user->ID;
			
				?>
	
				<div class="profile-box-wrap page-box wrap">
					
					<div class="inner-wrap">
				
						<div class="profile-grid">
							
							<?php //get_template_part( 'includes/leaderboard-widget' ); ?>
							
							<div class="recent-activity">
								
								<div class="section-header noselect">Recent Activity</div>
								
								<div class="table fullwidth fixed-layout">
									
									<div class="table-row">
									
										<div class="table-cell table-header">
											League
										</div>
										<div class="table-cell table-header">
											Type
										</div>
										<div class="table-cell table-header">
											Date
										</div>
									
									</div>
									
									<?php 
									$args = array(
										'post_type' => 'wager',
										'posts_per_page' => -1,
										'author' => $user_id,
										'posts_per_page' => 10,
									);
									
									$the_query = new WP_Query( $args );
									
									if ( $the_query->have_posts() ) {
										while ( $the_query->have_posts() ) {
											
											$the_query->the_post();
											
											$league = get_the_terms($post->ID, 'league');
											
											foreach ($league as $the_league) {
												$league_name = $the_league->name;
												$league_id = $the_league->term_id;
											}
											
											$wager_type = get_field('wager_type',$post->ID);
											$wager_date = get_the_date('M j, Y');
											$wager_contest = get_field('wager_contest',$post->ID);
											
											?>
											
											<a href="<?php echo get_permalink($wager_contest); ?>" class="table-row wager-row">
												
												<div class="table-cell league"><div class="league-logo" style="background-image:url(<?php echo get_field('league_logo','league_'.$league_id); ?>);"></div><span class="league-name"><?php echo $league_name; ?></span></div>
												<div class="table-cell type"><?php echo ucwords($wager_type); ?></div>
												<div class="table-cell date"><?php echo $wager_date; ?></div>
												
											</a>	
											
											<?php
											
										}
									}
									wp_reset_query();
									?>
				
								</div>
								
							</div>
							
							<?php get_template_part( 'includes/bettor-intelligence' ); ?>
							
							<div class="biggest-wins">
								
								<div class="section-header noselect">Biggest Wins</div>
								
								<div class="table fullwidth fixed-layout">
									
									<div class="table-row">
									
										<div class="table-cell table-header">
											League
										</div>
										<div class="table-cell table-header">
											Type
										</div>
										<div class="table-cell table-header">
											Date
										</div>
									
									</div>
									
									<?php 
									$args = array(
										'post_type' => 'wager',
										'posts_per_page' => -1,
										'author' => $user_id,
										'posts_per_page' => 10,
										'tax_query' => array(
											array(
												'taxonomy' => 'wager_result',
												'field'    => 'slug',
												'terms'    => 'Win',
											),
										),
										'meta_key'			=> 'potential_winnings',
										'orderby'			=> 'meta_value',
										'order'				=> 'DESC'
									);
									
									$the_query = new WP_Query( $args );
									
									if ( $the_query->have_posts() ) {
										while ( $the_query->have_posts() ) {
											
											$the_query->the_post();
											
											$league = get_the_terms($post->ID, 'league');
											
											foreach ($league as $the_league) {
												$league_name = $the_league->name;
												$league_id = $the_league->term_id;
											}
											
											$wager_type = get_field('wager_type',$post->ID);
											$wager_date = get_the_date('M j, Y');
											$wager_contest = get_field('wager_contest',$post->ID);
											
											?>
											
											<a href="<?php echo get_permalink($wager_contest); ?>" class="table-row wager-row"> 
												
												<div class="table-cell league"><div class="league-logo" style="background-image:url(<?php echo get_field('league_logo','league_'.$league_id); ?>);"></div><span class="league-name"><?php echo $league_name; ?></span></div> 
												<div class="table-cell type"><?php echo ucwords($wager_type); ?></div> 
												<div class="table-cell date"><?php echo $wager_date; ?></div> 
												
											</a>	
											
											<?php
											
										}
									}
									wp_reset_query();
									?>
				
								</div>
								
							</div>
								
						</div>
						
					</div>
					
				</div>
				
			<?php endif; ?>
		
		<?php endif; ?>
	
		
		<footer class="footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
			
			<?php if (!is_home() && !is_front_page()) { ?>
			
				<div class="home-section-text align-center">
				
					<div class="section-grid table fixed-layout align-center section-social">
						
						<a class="section-grid-tile section-grid-social table-cell top" href="https://www.facebook.com/pariwager" target="_blank">
							
							<div class="home-grid-icon"><i class="fab fa-facebook-f"></i></div>
							<div class="home-grid-description">Facebook</div>
							
						</a>
						
						<a class="section-grid-tile section-grid-social table-cell top" href="https://twitter.com/pariwager" target="_blank">
							
							<div class="home-grid-icon"><i class="fab fa-twitter"></i></div>
							<div class="home-grid-description">Twitter</div>
							
						</a>
						
					</div>
					
					<div class="home-disclaimer align-center">
						
						<p>FanTwist is a gambling site ??? FanTwist is an online sports wagering game and operates within the House Rules where they are legal.</p>
						<div class="footer-links align-center">
							<a class="white strong" href="mailto:jeff@fantwist.com">Contact</a> | <a class="white strong" href="<?= home_url()?>/privacy-policy/">Privacy</a> | <a href="<?= home_url()?>/terms-of-service/" class="white strong">Terms</a> | <a href="<?= home_url()?>/house-rules/" class="white strong">More Info - House Rules</a>
						</div>
						
						<p class="align-center copyright">?? <?=date('Y')?> <strong>FanFair Digital</strong> All rights reserved.</p>
						
						
					</div>
					
				</div>
			
			<?php } ?>
			
		</footer>
		
	</div> <!-- end #main-wrapper -->

	<?php wp_footer(); ?>
	
	<?php include 'scripts-footer.php'; ?>

</body>

</html>