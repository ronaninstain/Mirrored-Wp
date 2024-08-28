<?php
/**
 * Twenty Twenty-Four functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @package Twenty Twenty-Four
 * @since Twenty Twenty-Four 1.0
 */

/* Register Block Styles */
if (!function_exists('twentytwentyfour_block_styles')):

	function twentytwentyfour_block_styles()
	{
		register_block_style(
			'core/details',
			array(
				'name' => 'arrow-icon-details',
				'label' => __('Arrow icon', 'twentytwentyfour'),
				'inline_style' => '
				.is-style-arrow-icon-details {
					padding-top: var(--wp--preset--spacing--10);
					padding-bottom: var(--wp--preset--spacing--10);
				}

				.is-style-arrow-icon-details summary {
					list-style-type: "\2193\00a0\00a0\00a0";
				}

				.is-style-arrow-icon-details[open]>summary {
					list-style-type: "\2192\00a0\00a0\00a0";
				}',
			)
		);
		// Add other block styles here...
	}

endif;

add_action('init', 'twentytwentyfour_block_styles');

/* Enqueue Block Stylesheets */
if (!function_exists('twentytwentyfour_block_stylesheets')):

	function twentytwentyfour_block_stylesheets()
	{
		wp_enqueue_block_style(
			'core/button',
			array(
				'handle' => 'twentytwentyfour-button-style-outline',
				'src' => get_parent_theme_file_uri('assets/css/button-outline.css'),
				'ver' => wp_get_theme(get_template())->get('Version'),
				'path' => get_parent_theme_file_path('assets/css/button-outline.css'),
			)
		);
	}

endif;

add_action('init', 'twentytwentyfour_block_stylesheets');

/* Register Pattern Categories */
if (!function_exists('twentytwentyfour_pattern_categories')):

	function twentytwentyfour_pattern_categories()
	{
		register_block_pattern_category(
			'twentytwentyfour_page',
			array(
				'label' => _x('Pages', 'Block pattern category', 'twentytwentyfour'),
				'description' => __('A collection of full page layouts.', 'twentytwentyfour'),
			)
		);
	}

endif;

add_action('init', 'twentytwentyfour_pattern_categories');

/* Include Kirki Framework */
if (!class_exists('Kirki')) {
	include_once dirname(__FILE__) . '/inc/kirki/kirki.php';
}

if (class_exists('Kirki')) {
	Kirki::add_config(
		'my_theme_config',
		array(
			'capability' => 'edit_theme_options',
			'option_type' => 'theme_mod',
		)
	);

	Kirki::add_section(
		'my_section',
		array(
			'title' => __('My Section', 'textdomain'),
			'description' => __('My section description.', 'textdomain'),
		)
	);

	Kirki::add_field(
		'my_theme_config',
		array(
			'type' => 'image',
			'settings' => 'custom_logo',
			'label' => __('Custom Logo', 'textdomain'),
			'section' => 'my_section',
			'default' => '',
		)
	);

	Kirki::add_field(
		'my_theme_config',
		array(
			'type' => 'text',
			'settings' => 'new_page_title',
			'label' => __('New Page Title', 'textdomain'),
			'section' => 'my_section',
			'default' => '',
		)
	);

	Kirki::add_field(
		'my_theme_config',
		array(
			'type' => 'textarea',
			'settings' => 'new_page_content',
			'label' => __('New Page Content', 'textdomain'),
			'section' => 'my_section',
			'default' => '',
		)
	);
}

// Register settings to store theme options
function my_custom_theme_settings()
{
	register_setting('my_theme_options_group', 'custom_logo');
	register_setting('my_theme_options_group', 'new_page_title');
	register_setting('my_theme_options_group', 'new_page_content');
}
add_action('admin_init', 'my_custom_theme_settings');

/* Bootstrap */

function wpbootstrap_enqueue_styles()
{
	// Enqueue Bootstrap CSS
	wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css');

	// Enqueue Bootstrap JS (including Popper.js)
	wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);

}
add_action('wp_enqueue_scripts', 'wpbootstrap_enqueue_styles');

/* to handle api query variable start */

function add_query_vars($vars)
{
	$vars[] = 'course_id';
	return $vars;
}
add_filter('query_vars', 'add_query_vars');


/* to handle api query variable end */