<?php /* Template Name: Leaderboard */ ?>

<?php get_header(); ?>

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
	
}
else {
	
	header("Location: ".home_url());
	exit;
	
}
?>



<div class="page-hero leaderboard-hero" style="background-image:url(<?php echo get_the_post_thumbnail_url($post->ID,'full'); ?>)">
	
	<div class="hero-details centered-vertical noselect">
		
		<div class="inner-wrap">
	
			<h1><?php the_title(); ?></h1>
			<h2><?php echo get_field('leaderboard_h2', $post->ID); ?></h2>
					
		</div>
		
	</div>
	
</div>

<div class="leaderboard page-box wrap">
	
	<div class="inner-wrap">
		
		<?php get_template_part( 'includes/leaderboard-widget' ); ?>

		<div class="user-box" style="margin-top:2em;">
				
			<a class="logout-link" href="<?php echo wp_logout_url(home_url()); ?> ">Log Out</a>
			<a class="edit-profile" href="/edit-profile/">Edit Profile</a>
			<a href="/my-wagers/">View My Wagers</a>

		</div>

	</div>

</div>

<?php get_footer(); ?>