<?php
/**
 * Template Name: Display Courses by Category
 */

get_header();

// Check if WooCommerce is active
if (class_exists('WooCommerce')) {
    $woocommerce_active = true;
    $current_currency = get_woocommerce_currency_symbol();
} else {
    $woocommerce_active = false;
    $current_currency = ''; // Set a default or leave it blank
}

// Get the current page number from query parameters
$current_page = get_query_var('paged') ? get_query_var('paged') : 1;
$per_page = 10;  // Number of courses to show per page
$category_slug = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';

// Fetch the courses grouped by category from the API with pagination
$api_url = add_query_arg(
    array(
        'page' => $current_page,
        'per_page' => $per_page,
        'category' => $category_slug
    ),
    'https://www.johnacademy.co.uk/wp-json/custom-api/v1/courses-grouped'
);

$response = wp_remote_get($api_url);

if (is_wp_error($response)) {
    echo '<p>Unable to retrieve courses at this time.</p>';
    get_footer();
    exit;
}

$body = wp_remote_retrieve_body($response);
$data = json_decode($body, true);

$courses_by_category = $data['courses_by_category'];
$total_pages = $data['total_pages'];

if (empty($courses_by_category)) {
    echo '<p>No courses found.</p>';
} else {
    echo '<div class="courses-grouped-by-category">';

    foreach ($courses_by_category as $category_name => $courses) {
        // Link the category name to its archive page
        echo '<h2>' . esc_html($category_name) . '</h2>';
        echo '<div class="courses-list">';

        foreach ($courses as $course) {
            // Fetch additional course data from the API response
            $courseID = $course['id'];
            $average_rating = $course['meta']['average_rating'];
            $countRating = $course['meta']['rating_count'];
            $courseStds = $course['meta']['vibe_students'];
            $product_id = $course['meta']['vibe_product'];
            $regular_price = $course['meta']['regular_price'];
            $sale_price = $course['meta']['sale_price'];
            $units = $course['meta']['units'];

            // Output the course data
            echo '<div class="course-item">';

            // Course Title
            echo '<h3>' . esc_html($course['title']) . '</h3>';

            // Course Excerpt
            if (!empty($course['excerpt'])) {
                echo '<p>' . esc_html($course['excerpt']) . '</p>';
            }

            // Course Ratings
            if ($average_rating) {
                echo '<p>Rating: ' . esc_html($average_rating) . '/5</p>';
            }

            // Course Students
            if ($courseStds) {
                echo '<p>Students: ' . esc_html($courseStds) . '</p>';
            }

            // Course Price
            if ($sale_price) {
                echo '<p>Price: ' . esc_html($current_currency) . esc_html($sale_price);
                if ($regular_price && $regular_price != $sale_price) {
                    echo ' (Regular Price: ' . esc_html($current_currency) . esc_html($regular_price) . ')';
                }
                echo '</p>';
            }

            // Course Links
            if (!empty($course['permalink'])) {
                echo '<p><a href="' . esc_url(home_url('/single-course?course_id=' . $courseID)) . '">View Course</a></p>';
            }

            // Add to Cart Button
            if ($woocommerce_active && $product_id) {
                echo '<p><a href="' . esc_url(wc_get_cart_url()) . '?add-to-cart=' . esc_attr($product_id) . '">Add to Cart</a></p>';
            } else {
                echo '<p>Not Available</p>';
            }

            echo '</div>'; // End course-item
        }

        echo '</div>'; // End courses-list
    }

    echo '</div>'; // End courses-grouped-by-category

    // Pagination links
    if ($total_pages > 1) {
        echo '<div class="pagination">';
        echo paginate_links(
            array(
                'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                'format' => '?paged=%#%',
                'current' => max(1, $current_page),
                'total' => $total_pages,
            )
        );
        echo '</div>';
    }
}

get_footer();
?>