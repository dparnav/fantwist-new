<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<title><?php bloginfo('name'); ?> &raquo; <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>
		
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0"/>
	<meta name="google-site-verification" content="lXitF7WF8fmPbMmXPZshR3mQAFjZRGsqqLwLU03JZkE" />
	
	<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/screenshot.png">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png" />
	
	<!--[if IE]>
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
	<![endif]-->
	<meta name="msapplication-TileColor" content="#042f6e">
	<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/screenshot.png">
    <meta name="theme-color" content="#042f6e">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    	
	<!-- FONTAWESOME -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	
	<?php wp_head(); ?>
	
	<?php include 'style.php'; ?>
		
	<?php include 'scripts-header.php'; ?>
				
</head>

<?php $classes = array(); ?>

<body <?php body_class($classes); ?> itemscope itemtype="http://schema.org/WebPage">
	
	<script>
	  window.fbAsyncInit = function() {
	    FB.init({
	      appId      : '982019338814434',
	      cookie     : true,
	      xfbml      : true,
	      version    : 'v3.3'
	    });
	      
	    FB.AppEvents.logPageView();   
	      
	  };
	
	  (function(d, s, id){
	     var js, fjs = d.getElementsByTagName(s)[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement(s); js.id = id;
	     js.src = "https://connect.facebook.net/en_US/sdk.js";
	     fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));
	</script>
	
	<header class="site-header transition noselect">
		
		<nav class="nav-top inner-wrap">
						
			<a class="nav-logo" href="/">
				<img class="transition" src="<?php echo get_template_directory_uri(); ?>/library/images/fantwist-logo-new-1.png" alt="FanTwist" />
			</a>
						
			<?php if (is_user_logged_in()) { 
				
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
				
				?>
				
				<div class="nav-menu-left">
			
					<a class="nav-lobby <?php if (is_page('lobby')) { echo 'active'; } ?>" href="/lobby/">
						<span><i class="fas fa-home"></i> Lobby</span>
					</a>
					
					<a class="nav-contests <?php if (is_page('my-wagers')) { echo 'active'; } ?>" href="/my-wagers/">
						<span><i class="fas fa-list"></i> My Wagers</span>
					</a>
					
					<a class="nav-leaderboard <?php if (is_page('leaderboard')) { echo 'active'; } ?>" href="/leaderboard/">
						<span><i class="far fa-star"></i> Leaderboard</span>
					</a>
					
					<a class="nav-howtoplay <?php if (is_page('how-to-play')) { echo 'active'; } ?>" href="/how-to-play/">
						<span><i class="fas fa-question"></i> How to Play</span>
					</a>
				
				</div>
				
				<div class="nav-menu-right">
				
					<a class="nav-bets" href="/lobby/">
						<span><i class="fas fa-list"></i> Bet Now</span>
					</a>
					
					<?php 
					$account_balance = get_field('account_balance', 'user_'.$current_user_id); 
					$visible_balance = get_field('visible_balance', 'user_'.$current_user_id);
					?>
					
					<a href="<?php echo get_author_posts_url($current_user_id); ?>" class="nav-balance noselect">
						<i class="fas fa-user"></i> Balance <span class="balance-amount">$<?php echo number_format($account_balance, 2); ?></span>
					</a>
					
					<div class="nav-menu-right-dots">
						<i class="fas fa-ellipsis-h"></i>
					</div>
					
					<div class="nav-menu-right-submenu transition">
						<div class="nav-balance-wrap">
							
							<a class="nav-submenu-item" href="<?php echo get_author_posts_url($current_user_id); ?>">My Profile</a>
							<a class="nav-submenu-item" href="/my-wagers/">Open Wagers <span>$<?php echo number_format((abs($account_balance-$visible_balance)), 2); ?></span> <i class="fas fa-arrow-circle-right"></i></a>
							<!--<div class="nav-submenu-item">Available <span>$<?php echo number_format($account_balance, 2); ?></span></div>-->
							<?php if ($visible_balance < 100) { ?>
							<a href="javascript:void(0);" class="nav-request-more">Request More $</a>
							<?php } ?>
							<a class="logout-link" href="<?php echo wp_logout_url(home_url()); ?> ">Log Out</a>
						</div>
					</div>
				
			<?php } else { ?>
			
				<div class="nav-menu-left">
			
					<a class="nav-howtoplay <?php if (is_page('how-to-play')) { echo 'active'; } ?>" href="/how-to-play/">
						<span><i class="fas fa-chalkboard-teacher"></i> How to Play</span>
					</a>
				
				</div>
			
				<div class="nav-menu-right">
				
					<a class="nav-login" href="javascript:void(0);">
						<span>Login <i class="fas fa-sign-in-alt"></i></span>
					</a>
					
					<a class="nav-signup" href="/wp-login.php?action=register">
						<span>Sign Up <i class="fas fa-user-plus"></i></span>
					</a>
				
			<?php } ?>
			
				</div>
			
		</nav>
		
		<div class="login-box">
			
			<?php wp_login_form(array('redirect'=>site_url( '/lobby/ '))); ?>
			<a href="<?php echo wp_lostpassword_url(); ?>">Forgot Password</a> | 
			<a href="/wp-login.php?action=register">Sign Up</a>
			
		</div>
		
	</header>
	
	<?php if (is_user_logged_in()) { ?>
	
		<div class="mobile-menu">
			
			<a class="mobile-menu-item" href="/lobby/"><i class="fas fa-home"></i><span>Lobby</span></a>
			<a class="mobile-menu-item" href="/my-wagers/"><i class="fas fa-list"></i><span>My Wagers</span></a>
			<a class="mobile-menu-item" href="/leaderboard/"><i class="far fa-star"></i><span>Leaders</span></a>
			<a class="mobile-menu-item" href="/how-to-play/"><i class="fas fa-question"></i><span>How To Play</span></a>
			<a class="mobile-menu-item" href="<?php echo get_author_posts_url($current_user_id); ?>"><i class="fas fa-user"></i><span>Profile</span></a>
			
		</div>
	
	<?php } ?>
	
	<div class="main-wrapper" style="height:1000px">
				
		<?php //if (!is_user_logged_in()) { include (get_template_directory() . '/includes/user-login.php'); } ?>
		
		<?php //if (!is_user_logged_in()) { include (get_template_directory() . '/includes/user-signup.php'); } ?>
