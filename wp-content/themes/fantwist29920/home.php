<?php /* Template Name: Home */ ?>

<?php get_header(); $page = get_page_by_path('home'); $pageId = $page->ID; ?>

	<div class="home-wrapper">
	
		<section class="home-section home-section-1 section-hero">
	
			<div class="home-background-image home-background-1 parallax-window" data-parallax="scroll" data-image-src="<?php echo get_field('home_background_image_1', $pageId); ?>" data-speed="0.5"></div>
			
			<div class="inner-wrap">
				
				<div class="home-section-text centered-vertical">
				
					<h1><?php echo get_field('home_h1', $pageId); ?></h1>
					<h2><?php echo get_field('home_h2', $pageId); ?></h2>
					
					<div class="home-buttons">
						
						<?php if (is_user_logged_in()) { ?>
						
							<a href="javascript:void(0);" class="home-btn btn-signup btn-share"><i class="fas fa-share"></i> &nbsp; Share With a Friend</a>
						
						<?php } else { ?>
						
							<a href="/wp-login.php?action=register" class="home-btn btn-signup"><i class="fas fa-user-plus"></i> &nbsp; Sign Up For Free</a>
						
						<?php } ?>
						
						<a href="/how-to-play/" class="home-btn btn-learn-more"><i class="fas fa-chalkboard-teacher"></i> &nbsp; How To Play</a>
					
					</div>
				
				</div>
				
			</div>
		
		</section>
		
		<section class="home-section home-section-2 no-image noselect">
		
			<div class="section-header">Get Started Now</div>
			
			<div class="inner-wrap">
				
				<div class="section-grid table fullwidth fixed-layout align-center">
					
					<div class="section-grid-tile table-cell top">
						
						<div class="home-grid-icon"><i class="fas fa-sign-in-alt"></i></div>
						<?php if (!is_user_logged_in()) { ?>
							<div class="home-grid-title"><a href="/wp-login.php?action=register">Sign Up</a></div>
							<div class="home-grid-description">Simply enter an email and choose a username to get started.</div>
						<?php } else { ?>
							<div class="home-grid-title"><span>Sign Up <i class="fas fa-check-circle" style="color:white;"></i></span></div>
							<div class="home-grid-description">Thanks for signing up!</div>
						<?php } ?>
						
						
					</div>
					<div class="section-grid-tile table-cell top">
						
						<div class="home-grid-icon"><i class="fas fa-list-ol"></i></div>
						<div class="home-grid-title"><a href="/how-to-play/">Play</a></div>
						<div class="home-grid-description">
							<ul>
								<li>Choose a contest.</li>
								<li>Pick your team.</li>
								<li>Place your bet.</li>
							</ul>
						</div>
						
					</div>
					<div class="section-grid-tile table-cell top">
						
						<div class="home-grid-icon"><i class="fas fa-trophy"></i></div>
						<div class="home-grid-title">Win</div>
						<div class="home-grid-description">Climb the leaderboard to win real prizes.</div>
						
					</div>
					
				</div>
		
			</div>
		
		</section>
		
		<!--
		<section class="home-section home-section-3 section-image noselect">
	
			<div class="home-background-image home-background-3 parallax-window" data-parallax="scroll" data-image-src="<?php echo get_field('home_background_image_3', $pageId); ?>" data-speed="0.5"></div>
			
			<div class="home-section-text centered-vertical">
			
				<div class="section-header">Always Fair and Simple For Everyone</div>
				
				<div class="section-grid table fixed-layout align-center">
					
					<div class="section-grid-tile table-cell top">
						
						<div class="home-grid-icon"><i class="fas fa-balance-scale"></i></div>
						<div class="home-grid-title">Level Playing Field</div>
						<div class="home-grid-description">No drafting or advanced sports knowledge required. Choose your favorite team or any one of our pre-drafted squads. No one has an unfair advantage playing on FanTwist.</div>
						
					</div>
					<div class="section-grid-tile table-cell top">
						
						<div class="home-grid-icon"><i class="far fa-thumbs-up"></i></div>
						<div class="home-grid-title">Free To Play</div>
						<div class="home-grid-description">FanTwist is not a gambling site - we host 100% legal fantasy sports contests operating within state laws where applicable. No buy-ins, deposits, or risks - as long as users are eligible in their territory, they can play for and claim prizes.</div>
						
					</div>
					<div class="section-grid-tile table-cell top">
						
						<div class="home-grid-icon"><i class="fas fa-funnel-dollar"></i></div>
						<div class="home-grid-title">Fun Betting Format</div>
						<div class="home-grid-description">Our unique games present multiple betting options, allowing you to choose multiple teams within a single contest. Win, Place, Show, or go big and Pick 6!</div>
						
					</div>
					
				</div>
				
			</div>
		
		</section>-->

		<?php if (!is_user_logged_in()) { ?>
				
			<section class="home-section home-section-4 no-image">
	
				<div class="inner-wrap">	
								
					<div class="section-header no-upper noselect">You made it this far. What are you waiting for?</div>
					<div class="home-section-text align-center">
						<a href="/wp-login.php?action=register" class="home-btn btn-signup"><i class="fas fa-user-plus"></i> &nbsp; Sign Up For Free</a>
					</div>
			
				</div>
	
			</section>
		
		<?php } ?>
				
		<section class="home-section home-section-5 section-image">
	
			<div class="home-background-image home-background-5 parallax-window" data-parallax="scroll" data-image-src="<?php echo get_field('home_background_image_5', $pageId); ?>" data-speed="0.5"></div>
			
			<div class="home-section-text centered-vertical" style="min-height:250px;">
			
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
				
				<div class="home-disclaimer">
					
					<p>*NO PURCHASE NECESSARY. A PURCHASE WILL NOT IMPROVE YOUR CHANCES OF WINNING. Must be 18 or older to play online games. All prize claims are subject to verification. Restrictions apply. See Terms and Conditions for additional eligibility restrictions. Any prizes pictured are for illustrative purposes only. VOID WHERE PROHIBITED BY LAW.</p>

					<p>FanTwist is not a gambling site – FanTwist is 100% legal online sports gaming and operates within the contest rules where they are legal. There are no buy-ins, deposits, or risk – and as long as users are 18 years old and located in an eligible territory, they can play for and claim prizes. Eligibility to participate for cash and prizes is based on the State or Territory in which you reside. Local Laws determine the guidelines for the contest eligibility. If a territory does not allow for contest games, then it is deemed ineligible and users from that territory cannot participate in any contests.</p>
				
					<div class="footer-links align-center">
						<a class="white strong" href="/contact/">Contact</a> | <a class="white strong" href="https://pariwager.com" target="_blank">Corporate</a> | <a class="white strong" href="/privacy-policy/">Privacy</a> | <a href="/terms-of-service/" class="white strong">Terms</a>
					</div>
					
					<p class="align-center">© 2019 <strong>PariWager LLC.</strong> All rights reserved.</p>
					
					
				</div>
				
			</div>
		
		</section>
	
	</div>

<?php get_footer(); ?>