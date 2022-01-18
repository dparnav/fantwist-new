<?php /* Template Name: Lobby */ ?>

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

<div class="page-hero lobby-hero" style="background-image:url(<?php echo get_the_post_thumbnail_url($post->ID,'full'); ?>)">
	
	<div class="hero-details centered-vertical noselect">
		
		<div class="inner-wrap">
	
			<h1><?php the_title(); ?></h1>
			<h2><?php echo get_field('lobby_h2', $post->ID); ?></h2>
			
			<a class="leaderboard-btn" href="/leaderboard/">View Leaderboard</a>
		
		</div>
		
	</div>
	
</div>

<div class="lobby-box page-box wrap">
	
	<div class="inner-wrap lobby-wrap">

		<div class="lobby-main">
			
			<div class="section-header noselect" style="display:none">Play Now! <div class="section-header-menu"><i class="fas fa-bars"></i><div class="section-header-submenu"><a href="javascript:void(0);">Live</a><a href="javascript:void(0);">Completed</a></div></div></div>
			
			<div class="lobby-tabs noselect upcoming-tab">
				<ul>
					<?php 
					
					echo '<li><a class="transition active" href="javascript:void(0);" data-league="all">All</a></li>';	
					
					$terms = get_terms( array(
					    'taxonomy' => 'league',
					    'hide_empty' => false,
					    'meta_key' => 'league_active',
					    'meta_value' => 'Yes',
					) );
										
					foreach ($terms as $term) {
						echo '<li><a class="transition" href="javascript:void(0);" data-league="'.$term->slug.'">' . $term->name . '</a></li>';
					}
					
					?>
				</ul>
			</div>
			
			<div class="lobby-games">
				
				<?php 
				$args = array(
					'post_type' => 'contest',
					'posts_per_page' => -1,
					'meta_query' => array(
						'relation' => 'OR',
						array(
							'key'     => 'contest_status',
							'value'   => 'Open',
						),
						array(
							'key'     => 'contest_status',
							'value'   => 'In Progress',
						),
					),
					'order'				=> 'ASC',
					'orderby'			=> 'meta_value',
					'meta_key'			=> 'contest_date_sort',
					'meta_type'			=> 'DATETIME'
				);
				
				$the_query = new WP_Query( $args );
				
				if ( $the_query->have_posts() ) {
										
					while ( $the_query->have_posts() ) {
						
						$the_query->the_post();
						
						$nfl_main_contest = get_field('nfl_main_contest', $post->ID);
						
						if ($nfl_main_contest == '') {
																					
							$league = get_the_terms( $post->ID, 'league' );
							$league_id = $league[0]->term_id;
							$league_name = $league[0]->name;
							$league_slug = $league[0]->slug;
							$league_logo = get_field('league_logo','league_'.$league_id);
						
							$contest_type = get_field('contest_type', $post->ID);
							
							$coming_soon = false;
							
							if ($contest_type == 'Mixed') {
								$contest_type_text = 'Mixed<span>vs</span>Field';
							}
							else if ($contest_type == 'Teams') {
								$contest_type_text = 'Teams<span>vs</span>Field';
							}
							else if ($contest_type == 'Team vs Team') {
								$contest_type_text = 'Team<span>vs</span>Team';
							}
							else if ($contest_type == 'Starters') {
								$contest_type_text = 'Starters';
								$coming_soon = true;
							}
						
						
						
							$contest_date_1 = date('g:i a', get_field('contest_date', $post->ID));
							$contest_date_2 = get_field('contest_date', $post->ID);
							$offset = human_time_diff( $contest_date_2, current_time( 'timestamp' ) );
							
							$contest_status = get_field('contest_status', $post->ID);
							$force_lock_unlock = get_field('force_lockunlock', $post->ID);
	
							if ($force_lock_unlock == 'Force Lock') {
								$contest_status = 'In Progress';
								update_field('contest_status', 'In Progress', $post->ID);
							}
							else if ($force_lock_unlock == 'Force Unlock') {
								$contest_status = 'Open';
								update_field('contest_status', 'Open', $post->ID);
								
							}
							else {
								if ($contest_date_2 < current_time( 'timestamp' ) && $contest_status == 'Open') {
									$contest_status = 'In Progress';
									update_field('contest_status', 'In Progress', $post->ID);
								}
							}
							
							$locked = '';
	
							if ($contest_status == 'Closed') {
								$locked = 'locked';
								$status_html = '<div class="contest-begins">Completed</div>';
							}
							else if ($contest_status == 'In Progress') {
								$locked = 'in-progress';
								$status_html = '<div class="contest-begins">In Progress <i class="fas fa-lock"></i></div>';
							}
							else {
								if ($force_lock_unlock == 'Force Unlock') {
									$status_html = '<div class="contest-begins">In Progress <i class="fas fa-lock"></i></div>';
								}
								else {
									$status_html = '<div class="contest-begins">Begins in <strong>' . $offset . '</strong></div>';
								}
								
							}
							
							$permalink = get_permalink();
							$title = str_replace('Game Lines', 'Fantasy Betting Lines', str_replace('Regular Season','',get_field('contest_title_without_type')));
							
							if ($coming_soon) {
								$permalink = 'javascript:void(0);';
							}
							
							echo '<div class="contest-game contest-status-'.str_replace(' ', '', $contest_type).' contest-'.$league_slug.' '.$locked.'">' .
								'<a class="table fullwidth" href="'.$permalink.'">' .
									'<div class="contest-left contest-logo table-cell middle">' .
										'<img src="' . $league_logo . '" alt="'. $league_name . '" />' .
										'<div class="contest-type">' . $contest_type_text . '</div>' .
									'</div>' .
									'<div class="contest-right contest-details table-cell middle">' .
										'<div class="contest-title transition">' . $title . '</div>';
										
										if ($league_name != 'PGA') {
											echo '<div class="contest-date">' . $contest_date_1 . ' PT</div>';
										}
										
										echo $status_html .
									'</div>' .
								'</a>' .
							'</div>';
						
						}
						
					}
				}
				wp_reset_query();
				?>
				
			</div>
			
		</div>
		
		<div class="lobby-sidebar">
			
			<div class="sidebar-section lobby-leaderboard">
				
				<?php get_template_part( 'includes/leaderboard-widget' ); ?>
				
			</div>
			
			<?php get_template_part( 'includes/bettor-intelligence' ); ?>
			
			<div class="sidebar-section lobby-recent">
				
				<div class="section-header noselect">Recent Contests</div>
				
				<?php 
				$args = array(
					'post_type' => 'contest',
					'posts_per_page' => 6,
					'order' => 'DESC',
					'orderby' => 'meta_value',
					'meta_key' => 'contest_date_sort',
					'meta_type' => 'DATETIME',
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key'     => 'contest_status',
							'value'   => array('Closed', 'Finished'),
							'compare' => 'IN',
						),
						array(
					        'relation' => 'OR',
						        array(
								'key'     => 'nfl_main_contest',
								'value'   => '',
								'compare' => 'NOT EXISTS'
							),
							array(
								'key'     => 'nfl_main_contest',
								'value'   => '',
								'compare' => '='
							),
					    ),
					),	
				);
				
				$the_query = new WP_Query( $args );
				
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						
						$the_query->the_post();
						
						//$nfl_main_contest = get_field('nfl_main_contest', $post->ID);
						
						//if ($nfl_main_contest == '') {
						
							$recent_league = get_the_terms( $post->ID, 'league' );
							$recent_league_id = $recent_league[0]->term_id;
							$recent_league_name = $recent_league[0]->name;
							$recent_league_slug = $recent_league[0]->slug;
							$recent_league_logo = get_field('league_logo','league_'.$recent_league_id);
						
							$recent_contest_type = get_field('contest_type', $post->ID);
							
							if ($recent_contest_type == 'Mixed') {
								$recent_contest_type_text = 'Mixed<span>vs</span>Field';
							}
							else if ($recent_contest_type == 'Teams') {
								$recent_contest_type_text = 'Teams<span>vs</span>Field';
							}
							else if ($recent_contest_type == 'Team vs Team') {
								$recent_contest_type_text = 'Team<span>vs</span>Team';
							}
						
						
						
							$recent_contest_date_1 = date('g:i a', get_field('contest_date', $post->ID));
							$recent_contest_date_2 = get_field('contest_date', $post->ID);
							$recent_offset = human_time_diff( $recent_contest_date_2, current_time( 'timestamp' ) );
							
							$recent_contest_status = get_field('contest_status', $post->ID);
							$recent_force_lock_unlock = get_field('force_lockunlock', $post->ID);
							
							$contest_type = get_field('contest_type', $post->ID);
	
							
							if ($recent_force_lock_unlock == 'Force Lock') {
								$recent_contest_status = 'In Progress';
								update_field('contest_status', 'In Progress', $post->ID);
							}
							else if ($recent_force_lock_unlock == 'Force Unlock') {
								$recent_contest_status = 'Open';
								update_field('contest_status', 'Open', $post->ID);
								
							}
							else {
								if ($recent_contest_date_2 < current_time( 'timestamp' ) && $recent_contest_status == 'Open') {
									$recent_contest_status = 'In Progress';
									update_field('contest_status', 'In Progress', $post->ID);
								}
							}
							
							$recent_locked = '';
							//if ($contest_date_2 < current_time( 'timestamp' )) {
							if ($recent_contest_status == 'Closed') {
								$recent_locked = 'finished';
								$recent_status_html = '<div class="contest-begins">Completed</div>';
							}
							else if ($recent_contest_status == 'In Progress') {
								$recent_locked = 'in-progress';
								$recent_status_html = '<div class="contest-begins">In Progress <i class="fas fa-lock"></i></div>';
							}
							else if ($recent_contest_status == 'Finished') {
								$recent_locked = 'finished';
								$recent_status_html = '<div class="contest-begins">Completed</div>';
							}
							else {
								if ($recent_force_lock_unlock == 'Force Unlock') {
									$recent_status_html = '<div class="contest-begins">In Progress <i class="fas fa-lock"></i></div>';
								}
								else {
									$recent_status_html = '<div class="contest-begins">Begins in <strong>' . $recent_offset . '</strong></div>';
								}
								
							}
							
							
							$recent_permalink = get_permalink();
							$title = str_replace('Game Lines', 'Fantasy Betting Lines', str_replace('Regular Season','',get_field('contest_title_without_type')));
							//$permalink = 'javascript:void(0);';
							
							echo '<div class="contest-game contest-status-'.str_replace(' ', '', $contest_type).' contest-'.$recent_league_slug.' '.$recent_locked.'">' .
								'<a class="table fullwidth" href="'.$recent_permalink.'">' .
									'<div class="contest-left contest-logo table-cell middle">' .
										'<img src="' . $recent_league_logo . '" alt="'. $recent_league_name . '" />' .
										'<div class="contest-type">' . $recent_contest_type_text . '</div>' .
									'</div>' .
									'<div class="contest-right contest-details table-cell middle">' .
										'<div class="contest-title transition">' . $title . '</div>';
										
										if ($recent_league_name != 'PGA') {
										
											echo '<div class="contest-date">' . $recent_contest_date_1 . ' PT</div>';
										
										}
										
										echo $recent_status_html .
									'</div>' .
								'</a>' .
							'</div>';
							
						//}
						
					}
				}
				wp_reset_query();
				?>
				
			</div>
			
			<div class="user-box">
					
				<a class="logout-link" href="<?php echo wp_logout_url(home_url()); ?> ">Log Out</a>
				<a class="profile" href="<?php echo get_author_posts_url($current_user_id); ?>">My Profile</a>
				<a class="edit-profile" href="/edit-profile/">Edit Profile</a>
				<a href="/my-wagers/">View My Wagers</a>

			</div>
			
		</div>

	</div>

</div>

<?php get_footer(); ?>