<?php
require_once('library/bones.php');

function bones_ahoy()
{

	// launching operation cleanup
	add_action('init', 'bones_head_cleanup');

	// remove WP version from RSS
	add_filter('the_generator', 'bones_rss_version');

	// remove pesky injected css for recent comments widget
	add_filter('wp_head', 'bones_remove_wp_widget_recent_comments_style', 1);

	// clean up comment styles in the head
	add_action('wp_head', 'bones_remove_recent_comments_style', 1);

	// clean up gallery output in wp
	add_filter('gallery_style', 'bones_gallery_style');

	// enqueue base scripts and styles
	add_action('wp_enqueue_scripts', 'bones_scripts_and_styles', 999);
	// ie conditional wrapper

	// launching this stuff after theme setup
	bones_theme_support();

	// cleaning up random code around images
	add_filter('the_content', 'bones_filter_ptags_on_images');

	//remove emojis
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles');
}

add_action('after_setup_theme', 'bones_ahoy');

//google fonts
function bones_fonts()
{
	wp_enqueue_style('googleFonts', 'https://fonts.googleapis.com/css?family=Sunflower:300,500,700');
}

//add_action('wp_enqueue_scripts', 'bones_fonts');

//thumb sizes
add_image_size('large-square', 360, 360, true);
add_image_size('standard-640-360', 640, 360, true);
add_image_size('medium-320-180', 320, 180, true);
add_image_size('small-280-158', 280, 158, true);

add_filter('image_size_names_choose', 'bones_custom_image_sizes');

function bones_custom_image_sizes($sizes)
{
	return array_merge($sizes, array(
		'large-square' => __('360px by 360px'),
		'standard-640-360' => __('640px by 360px'),
		'medium-320-180' => __('320px by 180px'),
		'small-280-158' => __('280px by 158px'),
	));
}

// archive titles
add_filter('get_the_archive_title', function ($title) {

	if (is_category()) {

		$title = single_cat_title('', false);
	} elseif (is_tag()) {

		$title = single_tag_title('', false);
	} elseif (is_author()) {

		$title = '<span class="vcard">' . get_the_author() . '</span>';
	}

	return $title;
});


function update_new_user_balance($user_id)
{

	update_user_meta($user_id, 'account_balance', 5000);
	update_user_meta($user_id, 'visible_balance', 5000);
}

add_action('user_register', 'update_new_user_balance', 10, 1);


function custom_post_types_taxonomies()
{

	register_post_type(
		'contest',
		array(
			'labels' => array(
				'name' 				=> __('Contests'),
				'singular_name'		=> __('Contest'),
				'add_new'			=> __('Add New'),
				'add_new_item'		=> __('Add New Contest'),
				'edit_item'			=> __('Edit Contest'),
				'new_item'			=> __('New Contest'),
				'view_item'			=> __('View Contest'),
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'exclude_from_search' => true,
			'show_in_nav_menus' => true,
			'has_archive' => false,
			'rewrite' => array(
				'slug' => '/contest',
				'feeds' => false
			),
			'menu_position' => 5,
			'supports' => array('title', 'revisions'),
		)
	);

	register_post_type(
		'wager',
		array(
			'labels' => array(
				'name' 				=> __('Wagers'),
				'singular_name'		=> __('Wager'),
				'add_new'			=> __('Add New'),
				'add_new_item'		=> __('Add New Wager'),
				'edit_item'			=> __('Edit Wager'),
				'new_item'			=> __('New Wager'),
				'view_item'			=> __('View Wager'),
			),
			'publicly_queryable' => false,
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'show_in_admin_bar' => true,
			'has_archive' => false,
			'menu_position' => 5,
			'supports' => array('author', 'revisions'),
			'taxonomies' => array('league'),
		)
	);



	// //parley wagers
	// register_post_type(
	// 	'parlaywager',
	// 	array(
	// 		'labels' => array(
	// 			'name' 				=> __('Parlay Wagers'),
	// 			'singular_name'		=> __('Parlay Wager'),
	// 			'add_new'			=> __('Add New'),
	// 			'add_new_item'		=> __('Add New Parlay Wager'),
	// 			'edit_item'			=> __('Edit Parlay Wager'),
	// 			'new_item'			=> __('New Parlay Wager'),
	// 			'view_item'			=> __('View Parlay Wager'),
	// 		),
	// 		'publicly_queryable' => false,
	// 		'public' => false,
	// 		'show_ui' => true,
	// 		'show_in_menu' => true,
	// 		'show_in_nav_menus' => false,
	// 		'exclude_from_search' => true,
	// 		'show_in_admin_bar' => true,
	// 		'has_archive' => false,
	// 		'menu_position' => 5,
	// 		'supports' => array('author', 'revisions'),
	// 		'taxonomies' => array('league'),
	// 	)
	// );

	// //teaser wagers
	// register_post_type(
	// 	'teaserwager',
	// 	array(
	// 		'labels' => array(
	// 			'name' 				=> __('Teaser Wagers'),
	// 			'singular_name'		=> __('Teaser Wager'),
	// 			'add_new'			=> __('Add New'),
	// 			'add_new_item'		=> __('Add New Teaser Wager'),
	// 			'edit_item'			=> __('Edit Teaser Wager'),
	// 			'new_item'			=> __('New Teaser Wager'),
	// 			'view_item'			=> __('View Teaser Wager'),
	// 		),
	// 		'publicly_queryable' => false,
	// 		'public' => false,
	// 		'show_ui' => true,
	// 		'show_in_menu' => true,
	// 		'show_in_nav_menus' => false,
	// 		'exclude_from_search' => true,
	// 		'show_in_admin_bar' => true,
	// 		'has_archive' => false,
	// 		'menu_position' => 5,
	// 		'supports' => array('author', 'revisions'),
	// 		'taxonomies' => array('league'),
	// 	)
	// );

	//teaser wagers
	register_post_type(
		'gameaudit',
		array(
			'labels' => array(
				'name' 				=> __('Game Audits'),
				'singular_name'		=> __('Game Audit'),
				'add_new'			=> __('Add New'),
				'add_new_item'		=> __('Add New Game Audit'),
				'edit_item'			=> __('Edit Game Audit'),
				'new_item'			=> __('New Game Audit'),
				'view_item'			=> __('View Game Audit'),
			),
			'publicly_queryable' => false,
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'show_in_admin_bar' => true,
			'has_archive' => false,
			'menu_position' => 5,
			'supports' => array('author', 'revisions'),
		)
	);



	$labels = array(
		'name'              => _x('League', 'taxonomy general name', 'textdomain'),
		'singular_name'     => _x('League', 'taxonomy singular name', 'textdomain'),
		'search_items'      => __('Search Leagues', 'textdomain'),
		'all_items'         => __('All Leagues', 'textdomain'),
		'edit_item'         => __('Edit League', 'textdomain'),
		'update_item'       => __('Update League', 'textdomain'),
		'add_new_item'      => __('Add New League', 'textdomain'),
		'new_item_name'     => __('New League', 'textdomain'),
		'menu_name'         => __('Leagues', 'textdomain'),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'public'			=> false,
	);

	register_taxonomy('league', array('contest', 'wager', 'parlaywager', 'teaserwager'), $args);

	$labels = array(
		'name'              => _x('Team', 'taxonomy general name', 'textdomain'),
		'singular_name'     => _x('Team', 'taxonomy singular name', 'textdomain'),
		'search_items'      => __('Search Teams', 'textdomain'),
		'all_items'         => __('All Teams', 'textdomain'),
		'edit_item'         => __('Edit Team', 'textdomain'),
		'update_item'       => __('Update Team', 'textdomain'),
		'add_new_item'      => __('Add New Team', 'textdomain'),
		'new_item_name'     => __('New Team', 'textdomain'),
		'menu_name'         => __('Teams', 'textdomain'),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'public'			=> false,
		'meta_box_cb'       => false,
	);

	register_taxonomy('team', array('contest'), $args);

	$labels = array(
		'name'              => _x('Wager Type', 'taxonomy general name', 'textdomain'),
		'singular_name'     => _x('Wager Type', 'taxonomy singular name', 'textdomain'),
		'search_items'      => __('Search Wager Types', 'textdomain'),
		'all_items'         => __('All Wager Types', 'textdomain'),
		'edit_item'         => __('Edit Wager Type', 'textdomain'),
		'update_item'       => __('Update Wager Type', 'textdomain'),
		'add_new_item'      => __('Add New Wager Type', 'textdomain'),
		'new_item_name'     => __('New Wager Type', 'textdomain'),
		'menu_name'         => __('Wager Types', 'textdomain'),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'public'			=> false,
	);

	register_taxonomy('wager_type', array('wager', 'parlaywager', 'teaserwager'), $args);

	$labels = array(
		'name'              => _x('Wager Results', 'taxonomy general name', 'textdomain'),
		'singular_name'     => _x('Wager Result', 'taxonomy singular name', 'textdomain'),
		'search_items'      => __('Search Wager Results', 'textdomain'),
		'all_items'         => __('All Wager Results', 'textdomain'),
		'edit_item'         => __('Edit Wager Result', 'textdomain'),
		'update_item'       => __('Update Wager Result', 'textdomain'),
		'add_new_item'      => __('Add New Wager Result', 'textdomain'),
		'new_item_name'     => __('New Wager Result', 'textdomain'),
		'menu_name'         => __('Wager Results', 'textdomain'),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'public'			=> false,
	);

	register_taxonomy('wager_result', array('wager', 'parlaywager', 'teaserwager'), $args);

	register_post_type(
		'cron_log',
		array(
			'labels' => array(
				'name' 				=> __('Cron Logs'),
				'singular_name'		=> __('Cron Log'),
				'add_new'			=> __('Add New'),
				'add_new_item'		=> __('Add New'),
				'edit_item'			=> __('Edit Cron Log'),
				'new_item'			=> __('New Cron Log'),
				'view_item'			=> __('View Cron Log'),
			),
			'publicly_queryable' => false,
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'show_in_admin_bar' => true,
			'has_archive' => false,
			'menu_position' => 6,
			'supports' => array('title', 'revisions', 'editor'),
		)
	);

	$labels = array(
		'name'              => _x('Cron Types', 'taxonomy general name', 'textdomain'),
		'singular_name'     => _x('Cron Type', 'taxonomy singular name', 'textdomain'),
		'search_items'      => __('Search Cron Types', 'textdomain'),
		'all_items'         => __('All Cron Types', 'textdomain'),
		'edit_item'         => __('Edit Cron Type', 'textdomain'),
		'update_item'       => __('Update Cron Type', 'textdomain'),
		'add_new_item'      => __('Add New Cron Type', 'textdomain'),
		'new_item_name'     => __('New Cron Type', 'textdomain'),
		'menu_name'         => __('Cron Types', 'textdomain'),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'public'			=> false,
	);

	register_taxonomy('cron_type', array('cron_log'), $args);

	$labels = array(
		'name'              => _x('Schedule', 'taxonomy general name', 'textdomain'),
		'singular_name'     => _x('Schedule', 'taxonomy singular name', 'textdomain'),
		'search_items'      => __('Search Schedule', 'textdomain'),
		'all_items'         => __('All Schedules', 'textdomain'),
		'edit_item'         => __('Edit Schedule', 'textdomain'),
		'update_item'       => __('Update Schedule', 'textdomain'),
		'add_new_item'      => __('Add New Schedule', 'textdomain'),
		'new_item_name'     => __('New Schedule', 'textdomain'),
		'menu_name'         => __('Schedule', 'textdomain'),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'public'			=> false,
	);

	register_taxonomy('schedule', array('contest', 'wager'), $args);


	register_post_type(
		'gamedeatils',
		array(
			'labels' => array(
				'name' 				=> __('Game Custom Details'),
				'singular_name'		=> __('Game Custom Detail'),
				'add_new'			=> __('Add New'),
				'add_new_item'		=> __('Add New Game Custom Detail'),
				'edit_item'			=> __('Edit Game Custom Detail'),
				'new_item'			=> __('New Game Custom Detail'),
				'view_item'			=> __('View Game Custom Detail'),
			),
			'publicly_queryable' => false,
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'show_in_admin_bar' => true,
			'has_archive' => false,
			'menu_position' => 5,
			'supports' => array('author', 'revisions')
		)
	);
}

add_action('init', 'custom_post_types_taxonomies');

add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar()
{

	if (!current_user_can('administrator') && !is_admin()) {

		show_admin_bar(false);
	}
}

//Remove WPAUTOP from ACF TinyMCE Editor
function acf_wysiwyg_remove_wpautop()
{
	//remove_filter('acf_the_content', 'wpautop' );
}
//add_action('acf/init', 'acf_wysiwyg_remove_wpautop');


// Block Access to /wp-admin for non admins.
function custom_blockusers_init()
{
	if (is_user_logged_in() && is_admin() && !current_user_can('administrator') && !current_user_can('editor')) {
		wp_redirect(home_url());
		exit;
	}
}
add_action('init', 'custom_blockusers_init');

//remove visual editor for influencer posts
add_filter('user_can_richedit', function ($default) {
	if (get_post_type() === 'influencer_post')  return false;
	return $default;
});


// change base of author pages
function wpa_82004()
{
	global $wp_rewrite;
	$wp_rewrite->author_base = 'user';
}
add_action('init', 'wpa_82004');



//LOGIN PAGE CUSTOMIZATIONS
function login_page_template()
{
?><style type="text/css">
		@import url('https://fonts.googleapis.com/css?family=Lato:300,400,900');

		body.login div#login h1 a {
			background-image: url(<?php echo get_template_directory_uri(); ?>/library/images/fantwist-logo-new-1.png);
			background-size: contain;
			width: 100%;
			outline: none;
		}

		body.wp-core-ui {
			font-family: 'Lato', sans-serif;
			background: rgba(44, 145, 247, 0.9);
		}

		form#registerform,
		body.wp-core-ui.login form {
			background: #409bf7;
			color: white;
			box-shadow: none;
		}

		.login #login_error,
		.login .message,
		.login .success {
			background-color: #409bf7 !important;
			box-shadow: none !important;
			color: white;
		}

		p.message.register {
			display: none;
		}

		body.wp-core-ui.login label {
			color: white;
		}

		.login #nav {
			color: white;
		}

		body.wp-core-ui a,
		body.wp-core-ui.login #backtoblog a,
		body.wp-core-ui.login #nav a {
			color: white;
			outline: none;
		}

		body.login .message,
		body.login .success {
			border-left: none;
		}

		body.login.wp-core-ui .button-primary {
			display: block;
			background: linear-gradient(0deg, rgb(44, 181, 89), rgb(54, 196, 101));
			color: #fff;
			text-decoration: none;
			box-shadow: none;
			border: none;
			letter-spacing: 0.5pt;
			font-size: 14px;
			text-shadow: none;
			margin: 0 auto;
			float: none !important;
		}

		.wp-core-ui .button-primary.focus,
		.wp-core-ui .button-primary.hover,
		.wp-core-ui .button-primary:focus,
		.wp-core-ui .button-primary:hover {
			background: #61a55d;
			border-color: #61a55d;
		}

		.wp-core-ui.login .privacy-policy-checkbox label {
			font-size: 12px;
			float: left;
			margin: -7px 0 25px 10px;
			width: 90%;
			line-height: 17px;
			letter-spacing: 0.25pt;
		}

		p#reg_passmail {
			margin: 0;
			width: 100%;
			line-height: 17px;
			font-size: 12px;
			letter-spacing: 0.25pt;
		}

		.wp-core-ui.login .privacy-policy-checkbox input {
			float: left;
			margin: 0;
		}

		.wp-core-ui.login p.privacy-policy-checkbox {
			margin: 1em 0;
		}

		.nsl-container-buttons {
			position: relative;
		}

		.nsl-container-buttons.disabled:after {
			content: ' ';
			position: absolute;
			top: 0;
			left: 0;
			height: 100%;
			width: 100%;
			background-color: rgba(8, 4, 36, 0.6);
		}

		#login form p {
			clear: both;
		}

		body.login-action-login #login form p.submit {
			clear: both;
			margin-top: 3em !important;
		}

		@media (max-width:767px) {

			.wp-core-ui.login .privacy-policy-checkbox label {
				margin: 8px 0 25px 0px;
				width: 90%;
				clear: both;
			}

		}
	</style>

	<?php if ($GLOBALS['pagenow'] === 'wp-login.php' && !empty($_REQUEST['action']) && $_REQUEST['action'] === 'register') { //registration page 
	?>

		<script>
			setTimeout(function() {

				jQuery(document).ready(function($) {

					$('.nsl-container-buttons').addClass('disabled');
					$('#wp-submit').attr('disabled', 'disabled')

					$('.nsl-container-buttons a').each(function() {
						var $this = $(this),
							provider = $this.data('provider');

						if (provider == 'facebook' || provider == 'google') {
							$this.attr('data-redirect', '<?php echo site_url('/lobby/ '); ?>');
						}

					});

					$('#lp_privacy_policy').change(function() {

						if (this.checked) {
							$('.nsl-container-buttons').removeClass('disabled');
							$('#wp-submit').removeAttr('disabled')
						} else {
							$('.nsl-container-buttons').addClass('disabled');
							$('#wp-submit').attr('disabled', 'disabled')
						}
					});

					$('.submit').after('<div style="padding: 30px 0 0;text-align: center;font-weight: bold;font-size: 20px;">- OR -</div>')

				});

			}, 500)
		</script>

	<?php } ?>

	<?php if ($GLOBALS['pagenow'] === 'wp-login.php' && !empty($_REQUEST['action']) && $_REQUEST['action'] === 'login') { //registration page 
	?>

		<script>
			setTimeout(function() {

				jQuery(document).ready(function($) {

					$('.nsl-container-buttons').addClass('disabled');
					$('#wp-submit').attr('disabled', 'disabled')

					$('.nsl-container-buttons a').each(function() {
						var $this = $(this),
							provider = $this.data('provider');

						if (provider == 'facebook' || provider == 'google') {
							$this.attr('data-redirect', '<?php echo site_url('/lobby/ '); ?>');
						}

					});

					$('#lp_privacy_policy').change(function() {

						if (this.checked) {
							$('.nsl-container-buttons').removeClass('disabled');
							$('#wp-submit').removeAttr('disabled')
						} else {
							$('.nsl-container-buttons').addClass('disabled');
							$('#wp-submit').attr('disabled', 'disabled')
						}
					});

					$('.submit').after('<div style="padding: 30px 0 0;text-align: center;font-weight: bold;font-size: 20px;">- OR -</div>')

				});

			}, 500)
		</script>

	<?php } else if ($GLOBALS['pagenow'] === 'wp-login.php' && $_REQUEST['action'] != 'lostpassword' && $_REQUEST['action'] != 'register') { ?>

		<script>
			setTimeout(function() {

				jQuery(document).ready(function($) {

					$('.nsl-container-buttons a').each(function() {
						var $this = $(this),
							provider = $this.data('provider');

						if (provider == 'facebook' || provider == 'google') {
							$this.attr('data-redirect', '<?php echo site_url('/lobby/ '); ?>');
						}

					});

					$('.submit').after('<div style="padding: 30px 0 0;text-align: center;font-weight: bold;font-size: 20px;">- OR -</div>')

				});

			}, 500)
		</script>

	<?php } ?>

<?php
}

add_action('login_enqueue_scripts', 'login_page_template');

add_filter('login_headerurl', 'custom_loginlogo_url');

function custom_loginlogo_url($url)
{
	return '/';
}


//ADJUST CONTEST PERMALINKS
function mycustomname_links($post_link, $post = 0)
{
	if ($post->post_type === 'contest') {
		return home_url('contest/' . $post->ID . '/');
	} else {
		return $post_link;
	}
}
add_filter('post_type_link', 'mycustomname_links', 1, 3);

function mycustomname_rewrites_init()
{
	add_rewrite_rule('contest/([0-9]+)?$', 'index.php?post_type=contest&p=$matches[1]', 'top');
}
add_action('init', 'mycustomname_rewrites_init');




//ADD PRIVACY/TERMS CHECKBOX TO SIGNUP FORM
add_action('register_form', 'loginpress_add_privacy_policy_field');
function loginpress_add_privacy_policy_field()
{ ?>
	<p class="privacy-policy-checkbox">
		<input type="checkbox" name="lp_privacy_policy" id="lp_privacy_policy" class="checkbox" />
		<label for="lp_privacy_policy"><?php _e('By clicking \'Register\' or Facebook/Google, I agree to the <a href="/terms-of-service/" target="_blank">Terms of Service</a> & <a href="/privacy-policy" target="_blank">Privacy Policy</a>.', 'loginpress') ?>

		</label>
	</p>
<?php
}

add_filter('registration_errors', 'loginpresss_privacy_policy_auth', 10, 3);

function loginpresss_privacy_policy_auth($errors, $sanitized_user_login, $user_email)
{

	if (!isset($_POST['lp_privacy_policy'])) :

		$errors->add('policy_error', "<strong>ERROR</strong>: You must accept the terms of service and privacy policy to register.");
		return $errors;
	endif;
	return $errors;
}

add_action('user_register', 'loginpress_privacy_policy_save');

function loginpress_privacy_policy_save($user_id)
{

	if (isset($_POST['lp_privacy_policy']))
		update_user_meta($user_id, 'lp_privacy_policy', $_POST['lp_privacy_policy']);
}

require_once(dirname(__FILE__) . '/includes/processing/pariwager-processing.php');


// Cron schedules

function my_cron_schedules($schedules)
{

	if (!isset($schedules["1min"])) {
		$schedules["1min"] = array(
			'interval' => 1 * 60,
			'display' => __('Once every 1 minute')
		);
	}
	if (!isset($schedules["5min"])) {
		$schedules["5min"] = array(
			'interval' => 5 * 60,
			'display' => __('Once every 5 minutes')
		);
	}
	if (!isset($schedules["30min"])) {
		$schedules["30min"] = array(
			'interval' => 30 * 60,
			'display' => __('Once every 30 minutes')
		);
	}

	return $schedules;
}
add_filter('cron_schedules', 'my_cron_schedules');

//Disable registration verification email
add_filter('wpmu_signup_user_notification', '__return_false');

if (is_admin()) {

	// ADMIN STYLES/SCRIPTS
	function load_admin_style()
	{

		wp_register_script('admin_js', get_stylesheet_directory_uri() . '/library/js/admin-scripts.js', array('jquery'), '1.0');
		wp_enqueue_style('admin_css', get_template_directory_uri() . '/library/css/admin-styles.css', array(), '1.0');
		wp_enqueue_script('admin_js');
	}
	add_action('admin_enqueue_scripts', 'load_admin_style');
}
function ajax_login_init()
{

	wp_register_script('ajax-login-script', get_template_directory_uri() . '/library/js/ajax-login-script.js', array('jquery'));

	$requestUri = explode("/", $_SERVER["REQUEST_URI"]);

	if (in_array('admin', $requestUri)) {

		wp_enqueue_script('ajax-login-script');
		wp_localize_script('ajax-login-script', 'ajax_login_object', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'redirecturl' => home_url($path = '/contest-manager'),
			'loadingmessage' => __('Sending user info, please wait...')
		));
	}
	// Enable the user with no privileges to run ajax_login() in AJAX
	add_action('wp_ajax_nopriv_ajaxlogin', 'ajax_login');
}

// Execute the action only if the user isn't logged in
if (!is_user_logged_in()) {
	add_action('init', 'ajax_login_init');
}


function ajax_login()
{

	// First check the nonce, if it fails the function will break
	check_ajax_referer('ajax-login-nonce', 'security');

	// Nonce is checked, get the POST data and sign user on
	$info = array();
	$info['user_login'] = $_POST['username'];
	$info['user_password'] = $_POST['password'];
	$info['remember'] = true;

	$user_signon = wp_signon($info, false);
	if (!is_wp_error($user_signon)) {
		wp_set_current_user($user_signon->ID);
		wp_set_auth_cookie($user_signon->ID);
		echo json_encode(array('loggedin' => true, 'message' => __('Login successful, redirecting...')));
	} else {
		echo json_encode(array('loggedin' => false, 'message' => __('Failed to Login. Please re-check')));
	}

	die();
}

function wiaw_pagenavi_to_bootstrap($html)
{
	$out = '';
	$out = str_replace('<div', '', $html);
	$out = str_replace('class=\'wp-pagenavi\' role=\'navigation\'>', '', $out);
	$out = str_replace('<a', '<li class="page-item"><a class="page-link"', $out);
	$out = str_replace('</a>', '</a></li>', $out);
	$out = str_replace('<span aria-current=\'page\' class=\'current\'', '<li aria-current="page" class="page-item active"><span class="page-link current"', $out);
	$out = str_replace('<span class=\'pages\'', '<li class="page-item"><span class="page-link pages"', $out);
	$out = str_replace('<span class=\'extend\'', '<li class="page-item"><span class="page-link extend"', $out);
	$out = str_replace('</span>', '</span></li>', $out);
	$out = str_replace('</div>', '', $out);
	return '<div class="d-flex justify-content-center"><ul class="pagination" role="navigation">' . $out . '</ul></div>';
}
add_filter('wp_pagenavi', 'wiaw_pagenavi_to_bootstrap', 10, 2);


//Stoping auto update because we are not using updated data right now
/*
function do_this_in_a_minute() {
	require_once (get_theme_root().'/fantwist/includes/processing/processing-global/nhl-processing.php');
	require_once (get_theme_root().'/fantwist/includes/processing/processing-global/nba-processing.php');
	require_once (get_theme_root().'/fantwist/includes/processing/processing-global/mlb-processing.php');
	
	update_live_nhl_contests('815cb1bd3a7c4c8db30a5aee76dcf9c0', false);
	update_option( 'nhl-last-updated-live', date('m-d-Y g:i a', time() - (60*60*7))); 

	update_live_nba_contests('7cba61c8edbc4c4486b8b97308c3af08', false);
	update_option( 'nba-last-updated-live', date('m-d-Y g:i a', time() - (60*60*7)));
	
	update_live_mlb_contests('8a0f611eb4fd46b384f9e390a43b5b92', false);
	update_option( 'mlb-last-updated-live', date('m-d-Y g:i a', time() - (60*60*7)));
}
add_action( 'my_new_event','do_this_in_a_minute' );
 */
// put this line inside a function, 
// presumably in response to something the user does
// otherwise it will schedule a new event on every page visit

// wp_schedule_single_event( time() + 1, 'my_new_event' );

// time() + 3600 = one hour from now.


/* Commneted out for demo */
/*
function create_contest_automatically() {
	$today_current_date = date('Y-m-d', current_time( 'timestamp'));
	$today_league_types = ['nhl','nba','mlb'];

    foreach($today_league_types as $today_league_type){
		if($today_league_type == "nhl"){
			$projection_key = '67b23e7c2516424f97f6874947a62430';
		}

		if($today_league_type == "nba"){
			$projection_key = '055ebc1d62c84913bfbe4437aad5b267';
		}

		if($today_league_type == "mlb"){
			$projection_key = 'd52da89973df40989625a6aa8ac3874c';
		}

		$today_args = array(
			'post_type' => 'contest',
			'meta_query' => array(
				array(
					'key'     => 'contest_date',
					'value'   => $today_current_date,
					'compare' => 'LIKE'
				)
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'league',
					'field'    => 'slug',
					'terms'    => $today_league_type,
				)
			),
			'order'                => 'ASC',
			'orderby'            => 'meta_value',
			'meta_key'            => 'contest_date_sort',
			'meta_type'            => 'DATETIME'
		);
		$today_the_query = new WP_Query($today_args);
		$today_contest_count = $today_the_query->post_count;
		if($today_contest_count == 0){
			require_once (get_theme_root().'/fantwist/includes/processing/processing-global/'.$today_league_type.'-processing.php');
			
			$create_new_contest = "create_".$today_league_type."_projections_and_contests";
			$create_new_contest($today_current_date, $projection_key);
		}
	}
}

add_action('my_event_to_create_contest', 'create_contest_automatically');

if(1617667200 == strtotime(date('g:ia', current_time( 'timestamp')))){
	wp_schedule_single_event( strtotime(date('g:ia', current_time( 'timestamp'))), 'my_event_to_create_contest' );
}

function update_projections(){

	$today_league_types = ['nhl','nba','mlb'];

	foreach($today_league_types as $today_league_type){
		if($today_league_type == "nhl"){
			$projection_key = '67b23e7c2516424f97f6874947a62430';
		}

		if($today_league_type == "nba"){
			$projection_key = '055ebc1d62c84913bfbe4437aad5b267';
		}

		if($today_league_type == "mlb"){
			$projection_key = 'd52da89973df40989625a6aa8ac3874c';
		}

		require_once (get_theme_root().'/fantwist/includes/processing/processing-global/'.$today_league_type.'-processing.php');

		$update_projection = "update_projection_for_".$today_league_type."_contest";
		$update_projection($projection_key);
	}
	
}
add_action('my_event_to_update_projection','update_projections');
wp_schedule_single_event( strtotime(date('g:ia', current_time( 'timestamp'))), 'my_event_to_update_projection' );



*/
function create_contest_automatically()
{
	// if (date('H:ia', current_time('timestamp')) >= date('H:ia', strtotime('8:00am')) && date('H:ia', current_time('timestamp')) <= date('H:ia', strtotime('9:00am'))) {

	$today_current_date = date('Y-m-d', current_time('timestamp'));
	$today_league_types = ['nhl', 'nba', 'mlb'];
	$new_contest_id = '';
	foreach ($today_league_types as $today_league_type) {
		if ($today_league_type == "nhl") {
			$projection_key = '1f84d3da9fcb4f8fb276be1503989a33';
		}

		if ($today_league_type == "nba") {
			$projection_key = '1f84d3da9fcb4f8fb276be1503989a33';
		}

		if ($today_league_type == "mlb") {
			$projection_key = '1f84d3da9fcb4f8fb276be1503989a33';
		}

		$today_args = array(
			'post_type' => 'contest',
			'meta_query' => array(
				array(
					'key'     => 'contest_date_sort',
					'value'   => $today_current_date,
					'compare' => 'LIKE'
				)
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'league',
					'field'    => 'slug',
					'terms'    => $today_league_type,
				)
			),
			'order'                => 'ASC',
			'orderby'            => 'meta_value',
			'meta_key'            => 'contest_date_sort',
			'meta_type'            => 'DATETIME'
		);
		$today_the_query = new WP_Query($today_args);
		$today_contest_count = $today_the_query->post_count;
		if ($today_contest_count == 0) {
			require_once(get_theme_root() . '/fantwist/includes/processing/processing-global/' . $today_league_type . '-processing.php');
			$create_new_contest = "create_" . $today_league_type . "_projections_and_contests";
			$new_contest_id = $create_new_contest($today_current_date, $projection_key);
		
			if (($today_league_type) === "nba") {
				$term_id = "2";
			}
			if (($today_league_type) === "nhl") {
				$term_id = "3";
			}
			if (($today_league_type) === "mlb") {
				$term_id = "4";
			}
			if (($today_league_type) === "nfl") {
				$term_id = "6";
			}
			wp_set_post_terms($new_contest_id, $term_id, 'league');
			game_details_contest($new_contest_id);
		}
	}
	
}


add_action('my_event_to_update_projection', 'create_contest_automatically');
wp_schedule_single_event(time() + 30,  'my_event_to_update_projection');

/* NFL contest create and projection update */

function create_contenst_nfl_automatically()
{
	//for nfl
	$today_current_date = current_time('timestamp');
	$projection_key = "1f84d3da9fcb4f8fb276be1503989a33";
	$all_terms = get_terms('schedule', array('hide_empty' => false,));
	foreach ($all_terms as $term) {
		if ($term->description != "") {
			$term_start_date = strtotime(json_decode($term->description)->start_date);
			$term_end_date = strtotime(json_decode($term->description)->end_date);

			switch (date('D', $term_start_date)) {
				case 'Wed':
					$term_start_date = strtotime(date('Y-m-d', strtotime('-1 day', $term_start_date)));
					break;
				case 'Thu':
					$term_start_date = strtotime(date('Y-m-d', strtotime('-2 day', $term_start_date)));
					break;
				case 'Fri':
					$term_start_date = strtotime(date('Y-m-d', strtotime('-3 day', $term_start_date)));
					break;
				case 'Sat':
					$term_start_date = strtotime(date('Y-m-d', strtotime('-4 day', $term_start_date)));
					break;
				case 'Sun':
					$term_start_date = strtotime(date('Y-m-d', strtotime('-5 day', $term_start_date)));
					break;
			}
			if ($term_start_date <= $today_current_date && $today_current_date <= $term_end_date) {

				if (!function_exists('post_exists')) {
					require_once(ABSPATH . 'wp-admin/includes/post.php');
				}
				require_once(get_theme_root() . '/fantwist/includes/processing/processing-global/nfl-processing.php');
				create_nfl_projections_and_contests($term->term_id, $projection_key);

				$today_args = array(
					'post_type' => 'contest',
					'meta_query' => array(
						array(
							'key' => 'contest_date_sort',
							'value' => date('Y-m-d H:i:s', $term_start_date),
							'compare' => '>',
							'type' => 'DATE'
						),
						array(
							'key' => 'contest_date_sort',
							'value' => date('Y-m-d H:i:s', $term_end_date),
							'compare' => '<',
							'type' => 'DATE'
						)
					)
				);

				// echo date('Y-m-d',$term_start_date);
				$today_the_query = new WP_Query($today_args);
				// print_r($today_the_query->posts);

				foreach ($today_the_query->posts as $contest_posts) {
					if (strtolower(substr($contest_posts->post_title, 0, 3)) == "nfl") {
						wp_set_post_terms($contest_posts->ID, "6", 'league');
						wp_set_post_terms($contest_posts->ID, array($term->parent, $term->term_id), 'schedule');
						game_details_contest($contest_posts->ID);
					}
				}
			}
		}
	}
}

add_action('my_event_to_update_projection', 'create_contenst_nfl_automatically');
wp_schedule_single_event(time() + 60,  'my_event_to_update_projection');

//Update projection scores after every 5 minutes
function update_contest_projection_scores()
{
	$today_league_types = ['nba', 'nhl', 'mlb', 'nfl'];

	foreach ($today_league_types as $today_league_type) {
		if ($today_league_type == "nhl") {
			$projection_key = '1f84d3da9fcb4f8fb276be1503989a33';
		}

		if ($today_league_type == "nba") {
			$projection_key = '1f84d3da9fcb4f8fb276be1503989a33';
		}

		if ($today_league_type == "mlb") {
			$projection_key = '1f84d3da9fcb4f8fb276be1503989a33';
		}

		if ($today_league_type == "nfl") {
			$projection_key = '1f84d3da9fcb4f8fb276be1503989a33';
		}

		$update_projection_scores = "update_" . $today_league_type . "_projection_scores";
		$update_projection_scores($projection_key);
	}
}

add_action('event_to_update_projection_scores', 'update_contest_projection_scores');
wp_schedule_single_event(time() + 300,  'event_to_update_projection_scores');

//Update live scores after every 30 seconds
function update_contest_live_scores()
{
	// send_mail('update_contest_live_scores');
	$today_league_types = ['nba', 'nhl', 'mlb', 'nfl'];

	foreach ($today_league_types as $today_league_type) {
		if ($today_league_type == "nhl") {
			$stats_key = '1f84d3da9fcb4f8fb276be1503989a33';
		}
		if ($today_league_type == "nba") {
			$stats_key = '1f84d3da9fcb4f8fb276be1503989a33';
		}
		if ($today_league_type == "mlb") {
			$stats_key = '1f84d3da9fcb4f8fb276be1503989a33';
		}
		if ($today_league_type == "nfl") {
			$stats_key = '1f84d3da9fcb4f8fb276be1503989a33';
		}

		$update_live_scores = "update_" . $today_league_type . "_live_scores";
		$update_live_scores($stats_key);
	}
}

add_action('event_to_update_live_scores', 'update_contest_live_scores');
wp_schedule_single_event(time() + 10,  'event_to_update_live_scores');

function send_mail($type, $tickets = [])
{
	$to = "parnav@pixlerlab.com";
	$subject = $type;
	$txt = "Hello world!";



	$txt = json_encode($tickets);
	$headers = array('Content-Type: text/html; charset=UTF-8');

	// wp_mail( $to, $subject, $txt, $headers );


}
// parlay teaser wagers calculations
function cron_job_to_finish_parlay_teaser_wager()
{
	// send_mail('cron_job_to_finish_parlay_teaser_wager');
	require_once(get_theme_root() . '/fantwist/includes/processing/processing-global/all-processing.php');
	finish_game_for_parlay_wagers();
	finish_game_for_teaser_wagers();
}

add_action('event_to_finish_parlay_teaser_wager', 'cron_job_to_finish_parlay_teaser_wager');
wp_schedule_single_event(time() + 10,  'event_to_finish_parlay_teaser_wager');


function setting_teaser_points_for_different_league($league_type)
{

	if ($league_type == 'MLB') {
		$teaseByOptions = [1, 2, 3];
	} else if ($league_type == 'PGA') {
		$teaseByOptions = [1, 2, 3];
	} else if ($league_type == 'EPL') {
		$teaseByOptions = [1, 2, 3];
	} else if ($league_type == 'MLS') {
		$teaseByOptions = [1, 2, 3];
	} else if ($league_type == 'NASCAR') {
		$teaseByOptions = [1, 2, 3];
	} else if ($league_type == 'NFL') {
		$teaseByOptions = [18, 19.5, 21];
	} else if ($league_type == 'NBA') {
		$teaseByOptions = [8, 10.5, 12];
	} else if ($league_type == 'NHL') {
		$teaseByOptions = [1, 2, 3];
	} else {
		$teaseByOptions = [1, 2, 3];
	}

	return $teaseByOptions;
}

function create_nfl_schedule_automatically()
{
	nfl_schedule_create('1f84d3da9fcb4f8fb276be1503989a33');
}

add_action('my_event_to_create_nfl_schedule_automatically', 'create_nfl_schedule_automatically');
wp_schedule_single_event(time() + 864000,  'my_event_to_create_nfl_schedule_automatically');

//total wager amount
function get_open_wager_amount($current_user_id)
{
	//set the wager amount
	$open_wager_amount = 0;

	//fantasy points wager
	$fantasy_wager_data_args = array(
		'post_type' => 'wager',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key' => 'wager_result',
				'value' => 'Open',
				'compare' => 'LIKE'
			)
		),
		'author' => $current_user_id,
	);
	$wager_data_query = new WP_Query($fantasy_wager_data_args);
	foreach ($wager_data_query->posts as $post) {
		if (get_post_status(get_field('wager_contest', $post->ID))) {
			$open_wager_amount += number_format(get_field('wager_amount', $post->ID), 2);
		}
	}
	//parlay points wager
	$parlay_wager_data_args = array(
		'post_type' => 'parlaywager',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key' => 'wager_result',
				'value' => 'Open',
				'compare' => 'LIKE'
			)
		),
		'author' => $current_user_id,
	);
	$wager_data_query = new WP_Query($parlay_wager_data_args);
	foreach ($wager_data_query->posts as $post) {
		if (get_post_status(get_field('contest_id', $post->ID))) {
			$current_data = json_decode(get_field('parlay_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
			$open_wager_amount += number_format($current_data->wager_amount, 2);
		}
	}
	//teaser points wager
	$teaser_wager_data_args = array(
		'post_type' => 'teaserwager',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key' => 'wager_result',
				'value' => 'Open',
				'compare' => 'LIKE'
			)
		),
		'author' => $current_user_id,
	);
	$wager_data_query = new WP_Query($teaser_wager_data_args);
	foreach ($wager_data_query->posts as $post) {
		if (get_post_status(get_field('contest_id', $post->ID))) {
			$current_data = json_decode(get_field('teaser_data', $post->ID), false, JSON_UNESCAPED_UNICODE);
			$open_wager_amount += number_format($current_data->wager_amount, 2);
		}
	}

	return number_format($open_wager_amount, 2);
}

//adding logs page
function admin_menu_change_logs()
{
	add_menu_page(
		'Game Audit Logs',
		'Game Audit Logs',
		'manage_options',
		'change-logs',
		'display_change_logs',
		'dashicons-media-document',
		10
	);
	add_submenu_page(
		'change-logs',
		'Change Logs',
		'Change Logs',
		'manage_options',
		'change-logs',
		"display_change_logs"
	);
	add_submenu_page(
		'change-logs',
		'Status Logs',
		'Status Logs',
		'manage_options',
		'status-logs',
		"display_status_logs"
	);
}

add_action('admin_menu', 'admin_menu_change_logs');

//change logs function
function display_change_logs()
{
	$game_audits = new WP_Query(array(
		'post_type'  => 'gameaudit',
		'posts_per_page' => -1,
	));

?>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<h2 class="mt-2">Change Logs</h2>
	<div class="container-fluid mt-5">
		<table class="table table-stripped">
			<thead>
				<th>Contest Id</th>
				<th>Teams</th>
				<th>Contest Date</th>
				<th>Contest Start Time</th>
				<th>Spread Points (Previous / New)</th>
				<th>Overunder Points (Previous / New)</th>
				<th>Moneyline Points (Previous / New)</th>
				<th>Updated At</th>
				<th>Operator</th>
			</thead>
			<tbody>
				<?php
				$records = 0;
				foreach ($game_audits->posts as $post) :
					//check if there is any change
					$anyChange = 0;

					//set variables
					$contest_id = get_field('contest_id', $post->ID);
					$team_name_1 = get_field('team_name_1', $post->ID);
					$team_name_2 = get_field('team_name_2', $post->ID);
					$contest_date = Date("m-d-Y", get_field('contest_date', $contest_id));
					$contest_start_time = Date("g:ia", get_field('contest_date', $contest_id));
					$updated_at = Date("m-d-Y g:ia", strtotime($post->post_date));
					$operator = get_the_author_meta('display_name', $post->post_author);

					$all_points = [
						'previous_value_spread_team_1' => 'updated_value_spread_team_1',
						'previous_value_spread_team_2' => 'updated_value_spread_team_2',
						'previous_value_overunder' => 'updated_value_overunder',
						'previous_value_moneyline_team_1' => 'updated_value_moneyline_team_1',
						'previous_value_moneyline_team_2' => 'updated_value_moneyline_team_2',
					];

					foreach ($all_points as $key => $value) {
						${$key} = get_field($key, $post->ID);
						${$value} = get_field($value, $post->ID);

						if (${$key} != ${$value}) {
							$anyChange = 1;
						}
					}

					if ($anyChange) :
						$records++;
				?>
						<tr>
							<td><?= $contest_id ?></td>
							<td>
								<?= $team_name_1 ?>
								<br>
								<?= $team_name_2 ?>
							</td>
							<td><?= $contest_date ?></td>
							<td><?= $contest_start_time ?></td>
							<td>
								<?= $previous_value_spread_team_1 . " / " . $updated_value_spread_team_1 ?>
								<br>
								<?= $previous_value_spread_team_2 . " / " . $updated_value_spread_team_2 ?>
							</td>
							<td>
								<?= $previous_value_overunder . " / " . $updated_value_overunder ?>
							</td>
							<td>
								<?= $previous_value_moneyline_team_1 . " / " . $updated_value_moneyline_team_1 ?>
								<br>
								<?= $previous_value_moneyline_team_2 . " / " . $updated_value_moneyline_team_2 ?>
							</td>
							<td><?= $updated_at ?></td>
							<td><?= $operator ?></td>
						</tr>
				<?php
					endif;
				endforeach;
				if ($records == 0) {
					echo '<tr><td colspan="9"> No Logs Found!</td></tr>';
				}
				?>
			</tbody>
		</table>
	</div>
<?php

}

//status logs function
function display_status_logs()
{
	$game_audits = new WP_Query(array(
		'post_type'  => 'gameaudit',
		'posts_per_page' => -1,
	));

?>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<h2 class="mt-2">Change Logs</h2>
	<div class="container-fluid mt-5">
		<table class="table table-stripped">
			<thead>
				<th>Contest Id</th>
				<th>Game Id</th>
				<th>Status</th>
				<th>Reason (if any)</th>
				<th>Updated At</th>
				<th>Operator</th>
			</thead>
			<tbody>
				<?php
				$records = 0;
				foreach ($game_audits->posts as $post) :
					//check if there is any change
					$anyChange = 0;

					//set variables
					$contest_id = get_field('contest_id', $post->ID);
					$game_id = get_field('game_id', $post->ID);
					$previous_value_betting_status = get_field('previous_value_betting_status', $post->ID);
					$updated_value_betting_status = get_field('updated_value_betting_status', $post->ID);
					$bet_close_reason = get_field('bet_close_reason', $post->ID);
					$updated_at = Date("m-d-Y g:ia", strtotime($post->post_date));
					$operator = get_the_author_meta('display_name', $post->post_author);



					if ($previous_value_betting_status != $updated_value_betting_status) {
						$anyChange = 1;
					}

					switch ($updated_value_betting_status) {
						case "0":
							$status = "Open for bets";
							break;
						case "2":
							$status = "Off the board";
							break;
						case "3":
							$status = "Settled";
							break;
					}

					if ($anyChange) :
						$records++;
				?>
						<tr>
							<td><?= $contest_id ?></td>
							<td><?= $game_id ?></td>
							<td><?= $status ?></td>
							<td><?= $bet_close_reason ?></td>
							<td><?= $updated_at ?></td>
							<td><?= $operator ?></td>
						</tr>
				<?php
					endif;
				endforeach;
				if ($records == 0) {
					echo '<tr><td colspan="9"> No Logs Found!</td></tr>';
				}
				?>
			</tbody>
		</table>
	</div>
	<?php
}

function add_account_number_below_name()
{
	if (is_page('User')) {
	?>
		<script>
			jQuery(document).ready(function() {
				jQuery("<div class='account-page-account-number'>Account <?= get_current_user_id() ?></div>").insertBefore(".um-profile-connect.um-member-connect");
			});
		</script>
<?php
	}
}
add_action('wp_head', 'add_account_number_below_name');


// check whether number is interger

function isInteger($number)
{
	$int_number = (int) $number;
	$number_checker = $number - $int_number;
	return ($number_checker > 0) ? 0 : 1;
}
