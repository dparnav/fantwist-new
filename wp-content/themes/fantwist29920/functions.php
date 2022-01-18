<?php
require_once( 'library/bones.php' );

function bones_ahoy() {

  // launching operation cleanup
  add_action( 'init', 'bones_head_cleanup' );
   
  // remove WP version from RSS
  add_filter( 'the_generator', 'bones_rss_version' );
 
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
  
  // clean up comment styles in the head
  add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );
  
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'bones_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  bones_theme_support();

  // cleaning up random code around images
  add_filter( 'the_content', 'bones_filter_ptags_on_images' );
  
  //remove emojis
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'wp_print_styles', 'print_emoji_styles' ); 
  
}

add_action( 'after_setup_theme', 'bones_ahoy' );

//google fonts
function bones_fonts() {
	wp_enqueue_style('googleFonts', 'https://fonts.googleapis.com/css?family=Sunflower:300,500,700');
}

//add_action('wp_enqueue_scripts', 'bones_fonts');

//thumb sizes
add_image_size( 'large-square', 360, 360, true );
add_image_size( 'standard-640-360', 640, 360, true );
add_image_size( 'medium-320-180', 320, 180, true );
add_image_size( 'small-280-158', 280, 158, true );

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'large-square' => __('360px by 360px'),
        'standard-640-360' => __('640px by 360px'),
        'medium-320-180' => __('320px by 180px'),
        'small-280-158' => __('280px by 158px'),
    ) );
}

// archive titles
add_filter( 'get_the_archive_title', function ($title) {

    if ( is_category() ) {

            $title = single_cat_title( '', false );

        } elseif ( is_tag() ) {

            $title = single_tag_title( '', false );

        } elseif ( is_author() ) {

            $title = '<span class="vcard">' . get_the_author() . '</span>' ;

        }

    return $title;

});


function update_new_user_balance( $user_id ) {
	
	update_user_meta( $user_id, 'account_balance', 1000);
	update_user_meta( $user_id, 'visible_balance', 1000);

}

add_action( 'user_register', 'update_new_user_balance', 10, 1);


function custom_post_types_taxonomies() {
        
    register_post_type( 'contest',
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
    
    register_post_type( 'wager',
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
			'supports' => array( 'author', 'revisions' ),
			'taxonomies' => array('league'),
        )  
    );
    	
	$labels = array(
		'name'              => _x( 'League', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'League', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Leagues', 'textdomain' ),
		'all_items'         => __( 'All Leagues', 'textdomain' ),
		'edit_item'         => __( 'Edit League', 'textdomain' ),
		'update_item'       => __( 'Update League', 'textdomain' ),
		'add_new_item'      => __( 'Add New League', 'textdomain' ),
		'new_item_name'     => __( 'New League', 'textdomain' ),
		'menu_name'         => __( 'Leagues', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'public'			=> false,
	);

	register_taxonomy( 'league', array( 'contest', 'wager' ), $args );
	
	$labels = array(
		'name'              => _x( 'Team', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Team', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Teams', 'textdomain' ),
		'all_items'         => __( 'All Teams', 'textdomain' ),
		'edit_item'         => __( 'Edit Team', 'textdomain' ),
		'update_item'       => __( 'Update Team', 'textdomain' ),
		'add_new_item'      => __( 'Add New Team', 'textdomain' ),
		'new_item_name'     => __( 'New Team', 'textdomain' ),
		'menu_name'         => __( 'Teams', 'textdomain' ),
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

	register_taxonomy( 'team', array( 'contest' ), $args );
	
	$labels = array(
		'name'              => _x( 'Wager Type', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Wager Type', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Wager Types', 'textdomain' ),
		'all_items'         => __( 'All Wager Types', 'textdomain' ),
		'edit_item'         => __( 'Edit Wager Type', 'textdomain' ),
		'update_item'       => __( 'Update Wager Type', 'textdomain' ),
		'add_new_item'      => __( 'Add New Wager Type', 'textdomain' ),
		'new_item_name'     => __( 'New Wager Type', 'textdomain' ),
		'menu_name'         => __( 'Wager Types', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'public'			=> false,
	);

	register_taxonomy( 'wager_type', array( 'wager' ), $args );
	
	$labels = array(
		'name'              => _x( 'Wager Results', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Wager Result', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Wager Results', 'textdomain' ),
		'all_items'         => __( 'All Wager Results', 'textdomain' ),
		'edit_item'         => __( 'Edit Wager Result', 'textdomain' ),
		'update_item'       => __( 'Update Wager Result', 'textdomain' ),
		'add_new_item'      => __( 'Add New Wager Result', 'textdomain' ),
		'new_item_name'     => __( 'New Wager Result', 'textdomain' ),
		'menu_name'         => __( 'Wager Results', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'public'			=> false,
	);

	register_taxonomy( 'wager_result', array( 'wager' ), $args );
	
	register_post_type( 'cron_log',
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
		'name'              => _x( 'Cron Types', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Cron Type', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Cron Types', 'textdomain' ),
		'all_items'         => __( 'All Cron Types', 'textdomain' ),
		'edit_item'         => __( 'Edit Cron Type', 'textdomain' ),
		'update_item'       => __( 'Update Cron Type', 'textdomain' ),
		'add_new_item'      => __( 'Add New Cron Type', 'textdomain' ),
		'new_item_name'     => __( 'New Cron Type', 'textdomain' ),
		'menu_name'         => __( 'Cron Types', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'public'			=> false,
	);

	register_taxonomy( 'cron_type', array( 'cron_log' ), $args );
	
	$labels = array(
		'name'              => _x( 'Schedule', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Schedule', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Schedule', 'textdomain' ),
		'all_items'         => __( 'All Schedules', 'textdomain' ),
		'edit_item'         => __( 'Edit Schedule', 'textdomain' ),
		'update_item'       => __( 'Update Schedule', 'textdomain' ),
		'add_new_item'      => __( 'Add New Schedule', 'textdomain' ),
		'new_item_name'     => __( 'New Schedule', 'textdomain' ),
		'menu_name'         => __( 'Schedule', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'public'			=> false,
	);

	register_taxonomy( 'schedule', array( 'contest', 'wager' ), $args );
    
}

add_action( 'init', 'custom_post_types_taxonomies' );

add_action('after_setup_theme', 'remove_admin_bar');
 
function remove_admin_bar() {
	
	if (!current_user_can('administrator') && !is_admin()) {
	
	  show_admin_bar(false);
	
	}
}

//Remove WPAUTOP from ACF TinyMCE Editor
function acf_wysiwyg_remove_wpautop() {
    //remove_filter('acf_the_content', 'wpautop' );
}
//add_action('acf/init', 'acf_wysiwyg_remove_wpautop');


// Block Access to /wp-admin for non admins.
function custom_blockusers_init() {
  if ( is_user_logged_in() && is_admin() && !current_user_can( 'administrator' ) && !current_user_can( 'editor' )) {
    wp_redirect( home_url() );
    exit;
  }
}
add_action( 'init', 'custom_blockusers_init' );

//remove visual editor for influencer posts
add_filter('user_can_richedit', function( $default ){
  if( get_post_type() === 'influencer_post')  return false;
  return $default;
});


// change base of author pages
function wpa_82004(){
    global $wp_rewrite;
    $wp_rewrite->author_base = 'user';
}
add_action('init','wpa_82004');



//LOGIN PAGE CUSTOMIZATIONS
function login_page_template() { 
?>
 
	<style type="text/css"> 
	@import url('https://fonts.googleapis.com/css?family=Lato:300,400,900');
	body.login div#login h1 a {
		background-image: url(<?php echo get_template_directory_uri(); ?>/library/images/fantwist-logo-new-1.png);
		background-size: contain;
		width: 100%;
		outline:none;
	}
	body.wp-core-ui {
		font-family: 'Lato', sans-serif;
		background:rgba(44, 145, 247, 0.9);
	}
	form#registerform, body.wp-core-ui.login form {
		background: #409bf7;
		color: white;
		box-shadow: none;
	}
	.login #login_error, .login .message, .login .success {
		background-color: #409bf7 !important;
		box-shadow: none !important;
		color: white;
	}
	p.message.register {
		display:none;
	}
	body.wp-core-ui.login label {
		color:white;
	}
	.login #nav {
		color:white;
	}
	body.wp-core-ui a, body.wp-core-ui.login #backtoblog a, body.wp-core-ui.login #nav a {
		color:white;
		outline:none;
	}
	body.login .message, body.login .success {
		border-left: none;
	}
	body.login.wp-core-ui .button-primary {
	    display: block;
	    background:linear-gradient(0deg, rgb(44, 181, 89), rgb(54, 196, 101));
	    color: #fff;
	    text-decoration: none;
	    box-shadow: none;
	    border:none;
	    letter-spacing: 0.5pt;
	    font-size: 14px;
	    text-shadow: none;
	    margin: 0 auto;
	    float: none !important;
	}
	.wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover {
		background:#61a55d;
		border-color:#61a55d;
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
		float:left;
		margin:0;
	}
	.wp-core-ui.login p.privacy-policy-checkbox {
	    margin: 1em 0;
	}
	.nsl-container-buttons {
		position:relative;
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
		clear:both;
	}
	body.login-action-login #login form p.submit {
		clear:both;
		margin-top:3em !important;
	}
	@media (max-width:767px) {
		
		.wp-core-ui.login .privacy-policy-checkbox label {
		    margin: 8px 0 25px 0px;
		    width: 90%;
		    clear: both;
		}
		
	}
	</style>

	<?php if ( $GLOBALS['pagenow'] === 'wp-login.php' && ! empty( $_REQUEST['action'] ) && $_REQUEST['action'] === 'register' ) { //registration page ?>

		<script>
		setTimeout(function(){
			
			jQuery(document).ready(function($){
			
				$('.nsl-container-buttons').addClass('disabled');
				$('#wp-submit').attr('disabled','disabled')
					
				$('.nsl-container-buttons a').each(function(){
					var $this = $(this),
						provider = $this.data('provider');
					
					if (provider == 'facebook' || provider == 'google') {
						$this.attr('data-redirect', '<?php echo site_url( '/lobby/ '); ?>');
					} 
					
				});
				
				$('#lp_privacy_policy').change(function() {
				    
				    if (this.checked) {
				        $('.nsl-container-buttons').removeClass('disabled');
				        $('#wp-submit').removeAttr('disabled')
				    } else {
				        $('.nsl-container-buttons').addClass('disabled');
				        $('#wp-submit').attr('disabled','disabled')
				    }
				});
				
				$('.submit').after('<div style="padding: 30px 0 0;text-align: center;font-weight: bold;font-size: 20px;">- OR -</div>')
				
			});
			
		}, 500)
		</script>
	
	<?php } ?>
	
	<?php if ( $GLOBALS['pagenow'] === 'wp-login.php' && ! empty( $_REQUEST['action'] ) && $_REQUEST['action'] === 'login' ) { //registration page ?>

		<script>
		setTimeout(function(){
			
			jQuery(document).ready(function($){
			
				$('.nsl-container-buttons').addClass('disabled');
				$('#wp-submit').attr('disabled','disabled')
					
				$('.nsl-container-buttons a').each(function(){
					var $this = $(this),
						provider = $this.data('provider');
					
					if (provider == 'facebook' || provider == 'google') {
						$this.attr('data-redirect', '<?php echo site_url( '/lobby/ '); ?>');
					} 
					
				});
				
				$('#lp_privacy_policy').change(function() {
				    
				    if (this.checked) {
				        $('.nsl-container-buttons').removeClass('disabled');
				        $('#wp-submit').removeAttr('disabled')
				    } else {
				        $('.nsl-container-buttons').addClass('disabled');
				        $('#wp-submit').attr('disabled','disabled')
				    }
				});
				
				$('.submit').after('<div style="padding: 30px 0 0;text-align: center;font-weight: bold;font-size: 20px;">- OR -</div>')
				
			});
			
		}, 500)
		</script>
	
	<?php } else if ( $GLOBALS['pagenow'] === 'wp-login.php' && $_REQUEST['action'] != 'lostpassword' && $_REQUEST['action'] != 'register') { ?>
		
		<script>
		setTimeout(function(){
			
			jQuery(document).ready(function($){
				
				$('.nsl-container-buttons a').each(function(){
					var $this = $(this),
						provider = $this.data('provider');
					
					if (provider == 'facebook' || provider == 'google') {
						$this.attr('data-redirect', '<?php echo site_url( '/lobby/ '); ?>');
					} 
					
				});
								
				$('.submit').after('<div style="padding: 30px 0 0;text-align: center;font-weight: bold;font-size: 20px;">- OR -</div>')
				
			});
			
		}, 500)
		</script>
		
	<?php } ?>
	
 <?php 
} 

add_action( 'login_enqueue_scripts', 'login_page_template' );

add_filter( 'login_headerurl', 'custom_loginlogo_url' );

function custom_loginlogo_url($url) {
     return '/';
}


//ADJUST CONTEST PERMALINKS
function mycustomname_links($post_link, $post = 0) {
    if($post->post_type === 'contest') {
        return home_url('contest/' . $post->ID . '/');
    }
    else{
        return $post_link;
    }
}
add_filter('post_type_link', 'mycustomname_links', 1, 3);

function mycustomname_rewrites_init(){
    add_rewrite_rule('contest/([0-9]+)?$', 'index.php?post_type=contest&p=$matches[1]', 'top');
}
add_action('init', 'mycustomname_rewrites_init');




//ADD PRIVACY/TERMS CHECKBOX TO SIGNUP FORM
add_action( 'register_form', 'loginpress_add_privacy_policy_field' );
function loginpress_add_privacy_policy_field() { ?>
  <p class="privacy-policy-checkbox">
	  <input type="checkbox" name="lp_privacy_policy" id="lp_privacy_policy" class="checkbox" />
    <label for="lp_privacy_policy"><?php _e( 'By clicking \'Register\' or Facebook/Google, I agree to the <a href="/terms-of-service/" target="_blank">Terms of Service</a> & <a href="/privacy-policy" target="_blank">Privacy Policy</a>.', 'loginpress' ) ?>
      
    </label>
  </p>
  <?php
}

add_filter( 'registration_errors', 'loginpresss_privacy_policy_auth', 10, 3 );
 
function loginpresss_privacy_policy_auth( $errors, $sanitized_user_login, $user_email ) {
 
  if ( ! isset( $_POST['lp_privacy_policy'] ) ) :
 
    $errors->add( 'policy_error', "<strong>ERROR</strong>: You must accept the terms of service and privacy policy to register." );
    return $errors;
  endif;
  return $errors;
}

add_action( 'user_register', 'loginpress_privacy_policy_save' );
 
function loginpress_privacy_policy_save( $user_id ) {
 
  if ( isset( $_POST['lp_privacy_policy'] ) )
     update_user_meta( $user_id, 'lp_privacy_policy', $_POST['lp_privacy_policy'] );
}

require_once(dirname(__FILE__) . '/includes/processing/pariwager-processing.php');


// Cron schedules

function my_cron_schedules($schedules){
    
    if(!isset($schedules["1min"])){
        $schedules["1min"] = array(
            'interval' => 1*60,
            'display' => __('Once every 1 minute'));
    }
    if(!isset($schedules["5min"])){
        $schedules["5min"] = array(
            'interval' => 5*60,
            'display' => __('Once every 5 minutes'));
    }
    if(!isset($schedules["30min"])){
        $schedules["30min"] = array(
            'interval' => 30*60,
            'display' => __('Once every 30 minutes'));
    }
    
    return $schedules;
    
}
add_filter('cron_schedules','my_cron_schedules');

//Disable registration verification email
add_filter( 'wpmu_signup_user_notification', '__return_false' );

if ( is_admin() ) {
	
	// ADMIN STYLES/SCRIPTS
	function load_admin_style() {
	
		wp_register_script( 'admin_js', get_stylesheet_directory_uri() . '/library/js/admin-scripts.js', array('jquery'), '1.0' );
		wp_enqueue_style( 'admin_css', get_template_directory_uri() . '/library/css/admin-styles.css', array(), '1.0');
		wp_enqueue_script( 'admin_js' );
	
	}
	add_action( 'admin_enqueue_scripts', 'load_admin_style' );
	
}
?>