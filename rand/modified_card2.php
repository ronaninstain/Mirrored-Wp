<?php
// Register the shortcode
function fetch_courses_data_shortcode($atts)
{
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

            // Retrieve product price from the database using AJAX
            $product_price = '<div class="price text-right">Price: <span id="price-' . esc_attr($course_id) . '">Loading...</span></div>';

            $average_rating = isset($course_data['meta']['average_rating']) ? $course_data['meta']['average_rating'] : '';
            $rating_count = isset($course_data['meta']['rating_count']) ? $course_data['meta']['rating_count'] : '';
            if (empty($rating_count)) {
                $rating_count = "0";
            }
            if (empty($average_rating)) {
                $average_rating = "0";
            }

            $output .= '<div class="col-lg-4 col-md-6">';
            $output .= '<div class="single-course-inner">';
            $output .= '<div class="thumb">';
            $output .= '<img src="' . esc_url($course_data['thumbnail']) . '" alt="course">'; // Assuming thumbnail URL is in the 'thumbnail' field
            $output .= '</div>';
            $output .= '<div class="details">';
            $output .= '<div class="details-inner">';
            $output .= '<h6><a href="#" id="course-link-' . esc_attr($course_id) . '">' . esc_html($course_data['title']) . '</a></h6>';
            $output .= '</div>';
            $output .= '<div class="emt-course-meta"><div class="row"><div class="col-6">';
            $output .= '<div class="rating"><i class="fa fa-star"></i>  ' . esc_html($average_rating) . ' <span>(' . esc_html($rating_count) . ')</span>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= ' <div class="col-6">';
            $output .= $product_price;
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';

            // Add script to fetch price and update course title and product URL via AJAX
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
                                    $("#course-link-' . esc_attr($course_id) . '").attr("href", response.data.product_url);
                                } else {
                                    $("#price-' . esc_attr($course_id) . '").html("N/A");
                                    $("#course-link-' . esc_attr($course_id) . '").attr("href", "#");
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
