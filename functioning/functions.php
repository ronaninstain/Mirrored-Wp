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

/* B2b admin role start by shoive */

function create_b2b_admin_role()
{
	// Check if the role already exists
	if (get_role('b2b_administrators')) {
		// If the role exists, remove it to rewrite its capabilities
		remove_role('b2b_administrators');
	}

	// Create a new role based on the administrator role
	$admin_capabilities = get_role('administrator')->capabilities;

	// Add new role 'B2B Administrators' with admin capabilities
	add_role('b2b_administrators', 'B2B Administrators', $admin_capabilities);

	// Get the B2B Admin role
	$role = get_role('b2b_administrators');

	// Remove capabilities to edit, delete, or add users
	$role->remove_cap('delete_users');
	$role->remove_cap('create_users');
	$role->remove_cap('edit_users');

	// Remove theme and plugin management capabilities
	$role->remove_cap('edit_theme_options');
	$role->remove_cap('install_themes');
	$role->remove_cap('delete_themes');
	$role->remove_cap('update_themes');
	$role->remove_cap('install_plugins');
	$role->remove_cap('delete_plugins');
	$role->remove_cap('update_plugins');
	$role->remove_cap('edit_plugins');

	// WooCommerce order restrictions (add, edit, delete)
	$role->remove_cap('delete_shop_orders'); // Prevent deleting orders
	$role->remove_cap('publish_shop_orders'); // Prevent publishing shop orders
	$role->add_cap('edit_shop_orders'); // Prevent editing orders

	// Add capabilities to view WooCommerce orders and access the orders tab
	$role->add_cap('read_private_shop_orders'); // Ability to read private shop orders
	$role->add_cap('read_shop_orders'); // Ability to read public shop orders
	$role->add_cap('view_woocommerce_reports'); // View WooCommerce reports

	// Add WooCommerce menu management (necessary to access orders)
	$role->add_cap('manage_woocommerce'); // Required to access the WooCommerce menu

	// Allow basic viewing capabilities (non-editing)
	$role->add_cap('read'); // Basic read capability
	$role->add_cap('view_shop_order'); // View individual orders without editing/deleting them

	// Additional capabilities needed to avoid blank page
	$role->add_cap('view_woocommerce_orders'); // Allows access to order overview
	$role->add_cap('view_admin_dashboard'); // Allows access to the WooCommerce dashboard
}
add_action('init', 'create_b2b_admin_role');

// Remove ACF menu for B2B Admin role
function restrict_acf_menu_for_b2b_admin()
{
	if (current_user_can('b2b_administrators')) {
		remove_menu_page('edit.php?post_type=acf-field-group');
	}
}
add_action('admin_menu', 'restrict_acf_menu_for_b2b_admin', 999);

/* B2b admin role end by shoive */
