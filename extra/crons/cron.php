<?php
/* Cron Job By Shoive start */
function wplms_generate_course_json()
{
    // Fetch courses (WPLMS uses 'course' as the post type)
    $args = array(
        'post_type'      => 'course',       // Custom post type for courses
        'posts_per_page' => -1,             // Retrieve all courses
        'post_status'    => 'publish',       // Only published courses
        'meta_key' => 'vibe_students',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'meta_query'     => array(
            array(
                'key'     => 'vibe_product',
                'value'   => array(''),
                'compare' => 'NOT IN'
            )
        )
    );

    $courses = new WP_Query($args);

    // Initialize an empty array to store course data
    $course_data = array();

    if ($courses->have_posts()) {
        while ($courses->have_posts()) : $courses->the_post();
            $course_id = get_the_ID();
            $product_id = get_post_meta($course_id, 'vibe_product', true);
            $thumbnail_url = get_the_post_thumbnail_url($course_id, 'full') ?: null;

            // Fetch categories and levels
            $categories = wp_get_post_terms($course_id, 'course-cat', array("fields" => "names"));
            $levels = wp_get_post_terms($course_id, 'level', array("fields" => "names"));

            // Add course data to the array
            $course_data[] = array(
                'id'             => $course_id,
                'title'          => get_the_title(),
                'permalink'      => get_permalink(),
                'thumbnail'      => $thumbnail_url, // Get full size thumbnail or null if not exists
                'students'       => get_post_meta($course_id, 'vibe_students', true) ?: 0,
                'average_rating' => get_post_meta($course_id, 'average_rating', true) ?: 0,
                'product_id'     => $product_id,
                'regular_price'  => $product_id ? get_post_meta($product_id, '_regular_price', true) : null,
                'sale_price'     => $product_id ? get_post_meta($product_id, '_sale_price', true) : null,
                'categories'     => !empty($categories) ? $categories : [],  // Array of category names
                'levels'         => !empty($levels) ? $levels : []           // Array of level names
            );
        endwhile;
        wp_reset_postdata();
    }

    // Generate the JSON file
    $theme_dir = get_stylesheet_directory();  // Active theme directory
    $file_path = $theme_dir . '/data/course_data.json';  // Path to JSON file in the crons folder

    // Ensure the crons directory exists or create it
    if (!file_exists($theme_dir . '/data')) {
        mkdir($theme_dir . '/data', 0755, true);
    }

    // Save the JSON file
    file_put_contents($file_path, json_encode($course_data, JSON_PRETTY_PRINT));

    // Send confirmation email
    $to = 'shoivehossain@staffasia.org';  // Replace with your email
    $subject = 'Cron Job: Course Data JSON Generated';
    $message = "The cron job has successfully run. The course data has been saved to: " . $file_path;
    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($to, $subject, $message, $headers);
}

// Schedule cron job if it's not already scheduled
function wplms_schedule_cron_job()
{
    if (!wp_next_scheduled('wplms_generate_course_json_cron')) {
        wp_schedule_event(time(), 'every_five_minutes', 'wplms_generate_course_json_cron');
    }
}
add_action('wp', 'wplms_schedule_cron_job');

// Hook the function to the cron event
add_action('wplms_generate_course_json_cron', 'wplms_generate_course_json');

// Add a custom interval of 5 minutes to the cron schedules
function wplms_add_custom_cron_interval($schedules)
{
    $schedules['every_five_minutes'] = array(
        'interval' => 300,  // 300 seconds = 5 minutes
        'display'  => __('Every 5 Minutes')
    );
    return $schedules;
}
add_filter('cron_schedules', 'wplms_add_custom_cron_interval');

// Clear the scheduled cron job when the theme/plugin is deactivated
function wplms_clear_scheduled_cron()
{
    $timestamp = wp_next_scheduled('wplms_generate_course_json_cron');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'wplms_generate_course_json_cron');
    }
}
register_deactivation_hook(__FILE__, 'wplms_clear_scheduled_cron');

/* Cron Job By Shoive end */
