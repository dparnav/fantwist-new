<!DOCTYPE html>
<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html <?php language_attributes(); ?> class="no-js">
<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<title><?php bloginfo('name'); ?> &raquo; <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>

	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
	<meta name="google-site-verification" content="lXitF7WF8fmPbMmXPZshR3mQAFjZRGsqqLwLU03JZkE" />

	<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/screenshot.png">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png" />
	<!--[if IE]>Reports
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
	<![endif]-->
	<meta name="msapplication-TileColor" content="#042f6e">
	<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/screenshot.png">
	<meta name="theme-color" content="#042f6e">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<link href="<?php echo get_template_directory_uri(); ?>/library/custom-admin-portal/all.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
<!-- Custom styles for this template-->
<link href="<?php echo get_template_directory_uri(); ?>/library/custom-admin-portal/sb-admin-2.min.css" rel="stylesheet">

	<!-- FONTAWESOME -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

	<?php wp_head(); ?>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/library/custom-admin-portal/style.css">

</head>
<?php $classes = array(); ?>
<body <?php body_class($classes); ?> itemscope itemtype="http://schema.org/WebPage">

<?php
$redirectUrl = home_url().'/admin';
?>
<!-- Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
		
      <div class="modal-body text-center p-4 pt-5">
	  		Are you sure you want to log out of the FanTwist admin portal?
      </div>
      <div class="modal-footer justify-content-center border-0">
	  <a type="button" class="btn btn-primary" href="<?=wp_logout_url($redirectUrl)?>">Logout</a>
        <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

	<div class="main-wrapper">
		<header class="bg-light">
			<nav class="navbar navbar-expand-lg navbar-light ">
				<!-- <a class="navbar-brand" href="#">FanTwist</a> -->
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav">
						<li class="nav-item">
						<a class="nav-logo mr-5" href="<?=home_url() ?>/contest-manager">
							<img class="transition px-logo" src="<?php echo get_template_directory_uri(); ?>/library/images/fantwist-logo-new-1.png" alt="FanTwist" />
						</a>
							<!-- <a class="nav-link" href="<?=home_url() ?>/contest-manager">Admin Home</a> -->
						</li>						
					</ul>
					<?php
					
					?>
					<ul class="navbar-nav pull-right">
						<li class="nav-item active">
							<a class="nav-link py-2 px-3" href="<?=home_url() ?>/contest-manager">Contest Manager</a>
						</li>
						<li class="nav-item">
							<a class="nav-link py-2 px-3" href="<?=home_url() ?>/bettors">Bettors</a>
						</li>
						<li class="nav-item">
							<a class="nav-link py-2 px-3" href="<?=home_url() ?>/reports">Reports</a>
						</li>
						<li class="nav-item">
							<a class="nav-link py-2 px-3 logout-link" data-toggle="modal" data-target="#logoutModal" href="javascript:;">Logout</a>
						</li>
					</ul>
				</div>
			</nav>
		</header>