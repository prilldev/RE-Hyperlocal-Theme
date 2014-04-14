<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove) */
define( 'CHILD_THEME_NAME', 'RE Hyperlocal' );
define( 'CHILD_THEME_URL', 'http://bgrweb.com/hyperlocal-theme/era' );

//* Enqueue Open Sans Google font
add_action( 'wp_enqueue_scripts', 'genesis_sample_google_fonts' );
function genesis_sample_google_fonts() {
	wp_enqueue_style( 'google-font-opensans', '//fonts.googleapis.com/css?family=Open+Sans:300,400,700', array(), CHILD_THEME_VERSION );
}

//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

/** Add support for custom header */
//* Custom header is in addition to right-aligned ERA branding (when selected)
add_theme_support( 'custom-header', array(
	'header_image'    => '',
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'height'          => 225,
	'width'           => 500,
) );

/** Create additional color style/branding option(s) (default is White with Crimson Red Accents) */
add_theme_support( 'genesis-style-selector', array(
	'local-life-erare'	=> __( 'ERA Branded', 'Local Life' )
) );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Reposition the primary navigation
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before_header', 'genesis_do_nav' );

$content_width = apply_filters( 'content_width', 590, 410, 910 );

/** Add support for structural wraps */
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'inner', 'footer-widgets', 'footer' ) );

/** Add new image sizes */
add_image_size( 'home-featured', 280, 100, TRUE );


/** Set Genesis Responsive Slider defaults */
add_filter( 'genesis_responsive_slider_settings_defaults', 'locallife_responsive_slider_defaults' );
function locallife_responsive_slider_defaults( $defaults ) {
	$defaults['slideshow_height'] = '400';
	$defaults['slideshow_width'] = '752';
	return $defaults;
}

/** Relocate breadcrumbs */
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_before_content_sidebar_wrap', 'genesis_do_breadcrumbs' );

/** Customize the post info function */
add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter($post_info) {
if (!is_page()) {
    $post_info = '[post_date] by [post_author_posts_link] &middot; [post_comments] [post_edit]';
    return $post_info;
}}

/** Customize the post meta function */
add_filter( 'genesis_post_meta', 'post_meta_filter' );
function post_meta_filter($post_meta) {
if (!is_page()) {
    $post_meta = '[post_categories before="Filed Under: "] &middot; [post_tags before="Tagged: "]';
    return $post_meta;
}}

//* Customize the return to top of page text
add_filter( 'genesis_footer_backtotop_text', 'sp_footer_backtotop_text' );
function sp_footer_backtotop_text($backtotop) {
	$backtotop = '[footer_backtotop text="Return to Top"]';
	return $backtotop;
}

/** Modify the size of the Gravatar in the author box */
add_filter( 'genesis_author_box_gravatar_size', 'locallife_author_box_gravatar_size' );
function locallife_author_box_gravatar_size($size) {
    return '78';
}

/**
 * Exclude Specific Categories from Blog Home Page (because they're already in widgets)
 * 
 * @author Bill Erickson
 * @link http://www.billerickson.net/customize-the-wordpress-query/
 * @param object $query data
 *
 */
function be_exclude_category_from_blog( $query ) {
	
	if( $query->is_main_query() && $query->is_home() ) {
		$query->set( 'cat', '-4' );
	}
 
}
add_action( 'pre_get_posts', 'be_exclude_category_from_blog' );

/**
 * When on Homepage, dont put specific categories in Genesis Grid
 * These will typically be the ones that have been added already in Home Widgets
 * as we don't want to see them on the Homepage twice
 *
 * IMPORTANT: Requires Genesis Grid Loop plugin as that's what we're hooking into
 * For standard Genesis Loop, simply change 1st parameter of 'add_filter' hook to 'genesis_loop'
 */
function be_exclude_category_from_home( $display, $query ) {
	
	global $wp_the_query;
	if ($query->is_main_query() && $query->is_category() && genesis_get_option( 'grid_on_category', 'genesis-grid' ) )
	//if( /*$query->is_home() &&*/ $query->is_category( array( 3, 'category-2', 'category-3' ) ) )
		$display = false;
	else
		$display = true;

 	return $display;

}
add_filter( 'genesis_grid_loop_section', 'be_exclude_category_from_home', 10, 2 );


/** 
 ** REGISTER the custom widget areas 
 */
genesis_register_sidebar( array(
	'id'			=> 'home-slider',
	'name'			=> __( 'Home Top Left', 'locallife' ),
	'description'	=> __( 'The top left slider/video/feature section of the homepage.', 'locallife' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-top-right',
	'name'			=> __( 'Home Top Right', 'locallife' ),
	'description'	=> __( 'The top right call-to-action section of the homepage (next to slider).', 'locallife' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-middle',
	'name'			=> __( 'Home Middle Top', 'locallife' ),
	'description'	=> __( 'The responsive 3-column middle section of featured items on the homepage.', 'locallife' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-bottom',
	'name'			=> __( 'Home Middle Bottom', 'locallife' ),
	'description'	=> __( 'The responsive 3-column bottom section of featured items on the homepage.', 'locallife' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-after-featured',
	'name'			=> __( 'Home After Featured', 'locallife' ),
	'description'	=> __( 'The full-width section that appears after the featured items on the homepage.', 'locallife' ),
) );
/* **
 * END of custom widget area registration
 */

/* **
 * ** Regular content and Primary sidebar follow...
  
 NOTE: Only Remove genesis_do_loop from page templates (home.php, archive.php, etc) 
 if you DO NOT want regular posts appearing after the above custom widget areas. 
 
 In this theme we will
 use Bill Erickson's "Better-Easier-Grid-Loop" to format Posts as columns 

 * ** START of Better-Easier-Grid-Loop CODE
 */

 /**
 * Archive Post Class
 * @since 1.0.0
 *
 * Breaks the posts into three columns
 * @link http://www.billerickson.net/code/grid-loop-using-post-class
 *
 * @param array $classes
 * @return array
 */
/* function be_archive_post_class( $classes ) {
 
	// Don't run on single posts or pages
	if( is_singular() )
		return $classes;
 
	$classes[] = 'one-third';
	global $wp_query;
	if( 0 == $wp_query->current_post || 0 == $wp_query->current_post % 3 )
		$classes[] = 'first';
	return $classes;
}
add_filter( 'post_class', 'be_archive_post_class' );
  */
 
/*
 * Custom Login Logo
 *
 */
function locallife_custom_login_logo() {
	echo '

	<style type="text/css"><!-- body.login #login h1 a { background-image:url('.get_bloginfo('template_directory').'/images/login.png) !important; background-size: 340px 90px; width: 320px; height: 90px; }--></style>';
}
add_action('login_head', 'locallife_custom_login_logo');


add_filter( 'genesis_author_box', 'locallife_author_box', 10, 6 );
/**
 * Custom Author Box
 *
 * @param string $output
 * @param string $context
 * @param string $pattern
 * @param string $gravatar
 * @param string $title
 * @param string $description
 * @return string $output
 */
function locallife_author_box( $output, $context, $pattern, $gravatar, $title, $description ) {

	$output = '';
	
	$blogname = get_bloginfo('name');
	$auth_nickname = get_the_author_meta( 'nickname' );
	$auth_website = get_the_author_meta('user_url');
	$google_profile = get_the_author_meta( 'googleplus' );
	$facebook_id = get_the_author_meta( 'facebook_id');
	$twitter_id = get_the_author_meta( 'twitter_id') ;
	$linkedin_id = get_the_author_meta( 'linkedin_id');

	/* Author Archive page, tack on Header above the box */
	if ( 'archive' == $context ) {
		$output .= '<h2>'.$auth_nickname.'&#39;s Articles on <em>'.$blogname.'</em></h2>';
	}
		
	$output .= '<div class="author-box">';
	$output .= '<div class="left">';
	$output .= get_avatar( get_the_author_meta( 'email' ), 200 );
	$output .= '</div><!-- .left -->';
	$output .= '<div class="right">';
	$name = get_the_author();
	$title = get_the_author_meta( 'title' );
	if( !empty( $title ) )
		$name .= ', ' . $title;
	$output .= '<h4 class="title">' . $name . '</h4>';
	$output .= '<p class="desc">' . get_the_author_meta( 'description' ) . '</p>';
	$output .= '</div><!-- .right -->';
	$output .= '<div class="cl"></div>';
	$output .= '<div class="left">';
	
	/* Add website and custom social lead-in depending on if website/social media links exist */
	$output .= '<br /><p class="im www"><strong>';
	if ( ($google_profile || $facebook_id || $twitter_id || $linkedin_id) && ($auth_website<>'') ) {
		$output .= 'Be sure to visit <a href="'.$auth_website.'">'.$auth_nickname.'&#39;s website</a> and say hello on:';
	}
	elseif ($google_profile || $facebook_id || $twitter_id || $linkedin_id) {
		$output .= 'Say hello to '.$auth_nickname.' on:';
	}
	elseif ($auth_website<>'') {
		$output .= 'Be sure to visit <a href="'.$auth_website.'">'.$auth_nickname.'&#39;s website</a>.';
	}
	/* Create Social Media Links */
	$output .= '<br />';
	if ( $google_profile ) {
		$output .= '<a class="link googleplus" href="' . $google_profile . '">Google+</a>';
	}
	if ( $facebook_id ) {
		$output .= '<a rel="nofollow" class="link facebook" href=http://www.facebook.com/' . $facebook_id . '>Facebook</a>';
	}
	if ( $twitter_id ) {
		$output .= '<a rel="nofollow" class="link twitter" href=http://twitter.com/intent/user?screen_name=' . $twitter_id . '>Twitter</a>';
	}
	if ( $linkedin_id ) {
		$output .= '<a rel="nofollow" class="link linkedin" href=http://www.linkedin.com/in/' . $linkedin_id . '>LinkedIn</a>';
	}
		
	// On single post -- so tack on "View All Posts" link to Author Archive page
	if( 'single' == $context ) {
	
		$output .= '<br /><br /><p>';
		$output .= '<a href="' . trailingslashit( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">View all of '.$auth_nickname.'&#39;s articles</a> on '.$blogname.'...';
		$output .= '</p>';
		
	}
	
	$output .= '</strong></p>';
	
	$output .= '</div><!-- .left -->';
	$output .= '</div><!-- .author-box -->';
		
	return $output;
}



/*
 * Add Custom Contact Profile Fields (used by Custom Author.php) *
 */ 
function locallife_add_custom_contact_profilefields( $contactmethods ) {
    /*$contactmethods['google_profile'] = 'Google Profile URL';*/
    $contactmethods['facebook_id'] = 'Facebook ID';
    $contactmethods['twitter_id'] = 'Twitter UserName';
    $contactmethods['linkedin_id'] = 'LinkedIn UserName';
    return $contactmethods;
}
add_filter('user_contactmethods','locallife_add_custom_contact_profilefields',10,1);



/* Customize the Display-Posts-Shortcode plugin output 
 * (plugin must also be installed)
 *
 * Add Meta (Author, etc) and Read More Link to Display Posts Shortcode plugin
 * @author Bill Erickson
 * @link http://wordpress.org/extend/plugins/display-posts-shortcode/
 *
 * @param $output string, the original markup for an individual post
 * @param $atts array, all the attributes passed to the shortcode
 * @param $image string, the image part of the output
 * @param $title string, the title part of the output
 * @param $date string, the date part of the output
 * @param $excerpt string, the excerpt part of the output
 * @param $inner_wrapper string, what html element to wrap each post in (default is li)
 * @return $output string, the modified markup for an individual post
 */
 
add_filter( 'display_posts_shortcode_output', 'locallife_display_posts_add_meta_readmore', 10, 7 );
function locallife_display_posts_add_meta_readmore( $output, $atts, $image, $title, $date, $excerpt, $inner_wrapper ) {
	
	// Here's the author
	$author = '<span class="post-info">Posted by <a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . get_the_author() . '</a></span>';
	
	$readmore = ' <strong><span class="post-info"><a class="more-link" href="' . get_permalink() . '">[Read More]</a></span></strong>';

	// Now let's rebuild the output and add the $author to it
	$output = '<' . $inner_wrapper . ' class="listing-item">' . '<h3>' . $title . '</h3>' . $image . $date . $author . $excerpt . $readmore . '</' . $inner_wrapper . '>';
	
	// Finally we'll return the modified output
	return $output;
}