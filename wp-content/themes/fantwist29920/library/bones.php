<?php

function bones_head_cleanup() {
	// category feeds
	// remove_action( 'wp_head', 'feed_links_extra', 3 );
	// post and comment feeds
	// remove_action( 'wp_head', 'feed_links', 2 );
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
	// remove WP version from css
	add_filter( 'style_loader_src', 'bones_remove_wp_ver_css_js', 9999 );
	// remove Wp version from scripts
	add_filter( 'script_loader_src', 'bones_remove_wp_ver_css_js', 9999 );
	// remove comments
	remove_post_type_support( 'post', 'comments' );
}

// remove WP version from RSS
function bones_rss_version() { return ''; }

// remove WP version from scripts
function bones_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}

// remove injected CSS for recent comments widget
function bones_remove_wp_widget_recent_comments_style() {
	if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
		remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
	}
}

// remove injected CSS from recent comments widget
function bones_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
	}
}

// remove injected CSS from gallery
function bones_gallery_style($css) {
	return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}

function bones_scripts_and_styles() {

  global $wp_styles;

  if (!is_admin()) {

		// modernizr (without media query polyfill)
		wp_register_script( 'bones-modernizr', get_stylesheet_directory_uri() . '/library/js/libs/modernizr.custom.min.js', array(), '2.5.3', false );

		// register main stylesheet
		//wp_register_style( 'vimeo_css', get_stylesheet_directory_uri() . '/library/js/libs/vimeo/jquery.mb.vimeo_player.min.css', array(), '', 'all' );
		
		// ie-only style sheet
		wp_register_style( 'bones-ie-only', get_stylesheet_directory_uri() . '/library/css/ie.css', array(), '' );
	
		//adding scripts file in the footer
		wp_register_script( 'hcsticky-js', get_stylesheet_directory_uri() . '/library/js/libs/hcsticky.js', array( 'jquery' ), '', true );
		/*
		wp_register_script( 
	        'slickjs', 
	        '//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js', 
	        array( 'jquery' )
	    );
	    */
	    /*
	    wp_register_script( 
	        'vimeo_bg', 
	        get_stylesheet_directory_uri() . '/library/js/libs/vimeo/jquery.mb.vimeo_player.min.js', 
	        array( 'jquery' )
	    );
	    */
		// enqueue styles and scripts
		wp_enqueue_script( 'bones-modernizr' );
		wp_enqueue_script( 'hcsticky-js' );
		//wp_enqueue_style( 'vimeo_css' );
		wp_enqueue_style( 'bones-ie-only' );
		

		$wp_styles->add_data( 'bones-ie-only', 'conditional', 'lt IE 9' );

		wp_enqueue_script( 'jquery' );
		//wp_enqueue_script( 'hcsticky-js' );
		//wp_enqueue_script( 'vimeo_bg' );		

	}
	
}

function bones_theme_support() {

	// wp thumbnails (sizes handled in functions.php)
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );

	// default thumb size
	set_post_thumbnail_size(150, 150, true);

	// rss thingy
	add_theme_support('automatic-feed-links');

	// adding post format support
	/*add_theme_support( 'post-formats',
		array(
			//'aside',             // title less blurb
			'gallery',           // gallery of images
			//'link',              // quick link to other site
			//'image',             // an image
			//'quote',             // a quick quote
			//'status',            // a Facebook like status update
			'video',             // video
			//'audio',             // audio
			//'chat'               // chat transcript
		)
	);*/

	// wp menus
	add_theme_support( 'menus' );

	// registering wp3+ menus
	register_nav_menus(
		array(
			'main-nav' => __( 'The Main Menu', 'bonestheme' ),   // main nav in header
			'footer-links' => __( 'Footer Links', 'bonestheme' ) // secondary nav in footer
		)
	);

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form'
	) );

}

// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function bones_filter_ptags_on_images($content){
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
?>