<?php
// Register the shortcode
function fetch_courses_data_shortcode($atts)
{
    global $wpdb; // Include the global $wpdb for DB queries

    // Extract shortcode attributes
    $atts = shortcode_atts(
        array(
            'course_id' => '', // Default course_id is empty
        ),
        $atts,
        'course_data'
    );

    // Check if course_id is provided
    if (empty($atts['course_id'])) {
        return 'Please provide one or more course IDs.';
    }

    // Split the course IDs into an array
    $course_ids = explode(',', $atts['course_id']);

    $output = '';

    // Loop through each course ID and fetch data
    foreach ($course_ids as $course_id) {
        $course_id = intval(trim($course_id)); // Clean up the course ID
        if ($course_id) {
            // API endpoint on the source site to fetch course by ID
            $api_url = 'https://course-dashboard.com/wp-json/custom-api/v1/courses/' . $course_id;

            $client_id = get_option('client_id'); // Update as necessary
            $secret_key = get_option('secret_key'); // Update as necessary

            // Add the Authorization header
            $args = array(
                'headers' => array(
                    'Authorization' => 'Bearer ' . $client_id . ':' . $secret_key,
                ),
            );

            // Fetch the course data
            $response = wp_remote_get($api_url, $args);

            // Check if the API call was successful
            if (is_wp_error($response)) {
                $output .= 'Failed to retrieve course data for ID ' . $course_id . '.<br>';
                continue;
            }

            $course_data = json_decode(wp_remote_retrieve_body($response), true);

            // Check if course data is empty
            if (empty($course_data)) {
                $output .= 'No course found with the ID ' . $course_id . '.<br>';
                continue;
            }

            // Retrieve product ID from the custom database table
            $table_name = $wpdb->prefix . 'ptc_items';
            $product_id = $wpdb->get_var(
                $wpdb->prepare("SELECT product_id FROM $table_name WHERE course_id = %d", $course_id)
            );

            // Get product URL if the product exists
            if ($product_id) {
                $product_url = get_permalink($product_id);
            } else {
                $product_url = '#'; // Fallback if no product is associated
            }

            // Retrieve product price from the database using AJAX
            $product_price = '<div class="course-price" id="price-' . esc_attr($course_id) . '">Loading price...</div>';

            // Generate course categories
            $categories = isset($course_data['categories']) ? $course_data['categories'] : [];

            $category_output = '<div class="course-category">';
            foreach ($categories as $category) {
                $category_output .= '<div class="course-cate"><a href="#">' . esc_html($category) . '</a></div>';
            }

            $average_rating = isset($course_data['meta']['average_rating']) ? $course_data['meta']['average_rating'] : '';
            $rating_count = isset($course_data['meta']['rating_count']) ? $course_data['meta']['rating_count'] : '';

            $category_output .= '<div class="course-reiew">';
            if ($average_rating) {
                $category_output .= '<span class="ratting">';
                for ($i = 1; $i <= 5; $i++) {
                    if ($average_rating >= $i) {
                        $category_output .= '<i class="icofont-ui-rating"></i>';
                    } elseif ($average_rating > $i - 1 && $average_rating < $i) {
                        $category_output .= '<i class="icofont-ui-rate-blank" style="position: relative;">
                            <i class="icofont-ui-rating" style="position: absolute; top: 0; left: 0; width: 50%; overflow: hidden;"></i>
                           </i>';
                    } else {
                        $category_output .= '<i class="icofont-ui-rate-blank"></i>';
                    }
                }
                $category_output .= '</span>';
            }

            if ($rating_count) {
                $category_output .= '<span class="rating-count">' . esc_html($rating_count) . ' reviews</span>';
            }
            $category_output .= '</div>';
            $category_output .= '</div>';

            // Generate course levels
            $levels = isset($course_data['course_levels']) ? $course_data['course_levels'] : [];
            $levels_output = '<div class="course-topic">';
            foreach ($levels as $level) {
                $levels_output .= '<i class="icofont-signal"></i> ' . esc_html($level);
            }
            $levels_output .= '</div>';

            $output .= '<div class="col">';
            $output .= '<div class="course-item">';
            $output .= '<div class="course-inner">';
            $output .= '<div class="course-thumb">';
            $output .= '<img src="' . esc_url($course_data['thumbnail']) . '" alt="course">'; // Assuming thumbnail URL is in the 'thumbnail' field
            $output .= '</div>';
            $output .= '<div class="course-content">';
            $output .= $product_price; // Placeholder for price fetched via AJAX
            $output .= $category_output; // Add course categories
            $output .= '<a href="' . esc_url($product_url) . '">';
            $output .= '<h5>' . esc_html($course_data['title']) . '</h5>'; // Assuming title is in the 'title' field
            $output .= '</a>';
            $output .= '<div class="course-details">';
            $output .= '<div class="course-count"><i class="icofont-video-alt"></i> ' . esc_html($course_data['meta']['units']) . 'x Lesson</div>'; // Assuming lesson count is in the 'units' field
            $output .= $levels_output; // Assuming format is in the 'format' field
            $output .= '</div>';
            $output .= '<div class="course-footer">';
            $output .= '<div class="course-btn">';
            $output .= '<a href="' . esc_url($product_url) . '" class="lab-btn-text">Read More <i class="icofont-external-link"></i></a>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';

            // a script to fetch the price via AJAX
            $output .= '<script>
                (function($) {
                    $(document).ready(function() {
                        $.ajax({
                            url: "' . admin_url('admin-ajax.php') . '",
                            type: "POST",
                            data: {
                                action: "get_course_price",
                                course_id: ' . $course_id . '
                            },
                            success: function(response) {
                                if (response.success) {
                                    $("#price-' . esc_attr($course_id) . '").html(response.data.price);
                                } else {
                                    $("#price-' . esc_attr($course_id) . '").html("N/A");
                                }
                            },
                            error: function() {
                                $("#price-' . esc_attr($course_id) . '").html("Error fetching price");
                            }
                        });
                    });
                })(jQuery);
            </script>';
        }
    }

    return $output;
}
add_shortcode('course_data', 'fetch_courses_data_shortcode');
