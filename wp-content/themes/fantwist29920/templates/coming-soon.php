<?php /* Template Name: Coming Soon */ ?>

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
    
    <style>
	@import url('https://fonts.googleapis.com/css?family=Lato:300,400,900');
	body { background-color: rgb(8,3,37); }
	.coming-soon {
		background-color: rgb(8,3,37);
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		color: white;
		text-align: center;
		font-family: 'Lato', sans-serif;
	}
	h1 { font-size: 24px; text-transform: uppercase }
	.centered-vertical { left:0;right:0;top: 50%; -webkit-transform: translateY(-50%); -ms-transform: translateY(-50%); transform: translateY(-50%); position: absolute }
    .noselect { -webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none }
  	.coming-soon a { color:white; text-decoration: none; font-size:14px; font-weight:bold; }
  	.coming-soon a:hover { text-decoration: underline }
  	</style>
    
	<?php wp_head(); ?>
				
</head>
<body>
	
	<div class="coming-soon noselect">
		
		<div class="centered-vertical">
			<img src="<?php echo get_template_directory_uri(); ?>/library/images/pariwager-logo.png" alt="PariWager" />
			<h1>Coming Soon</h1>
			<div><a href="mailTo:jeff@pariwager.com">Inquire <i class="fas fa-arrow-circle-right"></i></a></div>
		</div>
		
	</div>
	
</body>
</html>