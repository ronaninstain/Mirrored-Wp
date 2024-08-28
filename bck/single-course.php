<?php
/**
 * Template Name: Single Course
 */

get_header();

// Get the course ID from the query string
$course_id = get_query_var('course_id');

// Define the source site's API endpoint
$api_url = 'https://www.johnacademy.co.uk/wp-json/custom-api/v1/courses/' . $course_id;

// Fetch data from the API
$response = wp_remote_get($api_url);

if (is_wp_error($response)) {
    echo '<p>Unable to retrieve course at this time.</p>';
    get_footer();
    exit;
}

$body = wp_remote_retrieve_body($response);
$data = json_decode($body, true);
$course = $data;

// Check if course data is empty
if (empty($course)) {
    echo '<p>Course not found.</p>';
} else {
    // Output the course data
    echo '<div class="course-details">';
    echo '<h1>' . esc_html($course['title']) . '</h1>';
    echo '<p>' . esc_html($course['content']) . '</p>';

    if (!empty($course['thumbnail'])) {
        echo '<img src="' . esc_url($course['thumbnail']) . '" alt="' . esc_attr($course['title']) . '">';
    }

    if (!empty($course['meta']['average_rating'])) {
        echo '<p>Rating: ' . esc_html($course['meta']['average_rating']) . '/5</p>';
    }

    if (!empty($course['meta']['vibe_students'])) {
        echo '<p>Students: ' . esc_html($course['meta']['vibe_students']) . '</p>';
    }

    if (!empty($course['meta']['regular_price'])) {
        echo '<p>Price: ' . esc_html($course['meta']['sale_price']);
        if ($course['meta']['regular_price'] != $course['meta']['sale_price']) {
            echo ' (Regular Price: ' . esc_html($course['meta']['regular_price']) . ')';
        }
        echo '</p>';
    }

    // Add a back link or navigation
    echo '<p><a href="' . esc_url(home_url('/apied-courses')) . '">Back to Courses</a></p>';
    echo '</div>';
}

get_footer();
