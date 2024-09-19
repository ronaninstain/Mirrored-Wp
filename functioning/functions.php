<?php

/**
 * DISPLAY site functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package display
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

//by Shoive
include_once 'inc/all-course-ajax-price.php';
include_once 'inc/course-cards/course_cards_home.php';
require_once(get_template_directory() . '/inc/tgmpa/tgmpa-configuration.php');

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */




function display_setup()
{



	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Display site, use a find and replace
	 * to change 'display_site' to the name of your theme in all the template files.
	 */
	load_theme_textdomain('display_site', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support('title-tag');

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Primary', 'display_site'),
			// added by Nabil 
			'footer_left_menu' => __('Footer Left Menu', 'display_site'),
			'footer_right_menu' => __('Footer Right Menu', 'display_site'),
			'footer_bottom_menu' => __('Footer Bottom Menu', 'display_site'),
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'display_site_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);


	add_theme_support('woocommerce');


	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height' => 250,
			'width' => 250,
			'flex-width' => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'display_site_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function display_site_content_width()
{
	$GLOBALS['content_width'] = apply_filters('display_site_content_width', 640);
}
add_action('after_setup_theme', 'display_site_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function display_site_widgets_init()
{
	register_sidebar(
		array(
			'name' => esc_html__('Sidebar', 'display_site'),
			'id' => 'sidebar-1',
			'description' => esc_html__('Add widgets here.', 'display_site'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget' => '</section>',
			'before_title' => '<h2 class="widget-title">',
			'after_title' => '</h2>',
		)
	);
}
add_action('widgets_init', 'display_site_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function display_site_scripts()
{

	//by Shoive
	// wp_enqueue_script('lms-custom-js', get_template_directory_uri() . '/assets/js/lms-custom.js', array(), null, true);
	wp_enqueue_script('lms-search-js', get_template_directory_uri() . '/assets/js/lms-search2.js', array(), time(), true);

}
add_action('wp_enqueue_scripts', 'display_site_scripts');



function get_limited_content($limit = 30)
{
	$content = get_the_content();
	$content = wp_strip_all_tags($content); // Remove HTML tags
	$words = explode(' ', $content, $limit + 1);

	if (count($words) > $limit) {
		array_pop($words);
		$content = implode(' ', $words) . '...'; // Add ellipsis
	} else {
		$content = implode(' ', $words);
	}

	return $content;
}


// api purpose by shoive

function add_query_vars_filter($vars)
{
	$vars[] = "page";
	return $vars;
}
add_filter('query_vars', 'add_query_vars_filter');

/* to handle api query variable start */

function add_query_vars($vars)
{
	$vars[] = 'course_id';
	return $vars;
}
add_filter('query_vars', 'add_query_vars');


/* to handle api query variable end */


function create_student_role()
{
	if (!get_role('student')) {
		add_role(
			'student',
			__('Student'),
			array(
				'read' => true, // Allow this role to read content
				// Add other capabilities here if needed
			)
		);
	}
}
add_action('init', 'create_student_role');

// added by Nabil for cart page 

function display_quantity_minus()
{
	echo '<div class="dec qtybutton">-</div>';
}
add_action('woocommerce_before_quantity_input_field', 'display_quantity_minus');

function display_quantity_plus()
{
	echo '<div class="inc qtybutton">+</div>';
}
add_action('woocommerce_after_quantity_input_field', 'display_quantity_plus');
