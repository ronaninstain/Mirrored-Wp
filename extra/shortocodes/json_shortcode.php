<?php
function course_by_json_shortcode($atts)
{
    // Start time measurement
    $start_time = microtime(true);

    // Set default values for the attributes
    $atts = shortcode_atts(array(
        'cat_id' => '',
        'course_ids' => '',
        'limit' => 10,
    ), $atts);

    // Define the path to the JSON file
    $json_file_path = get_stylesheet_directory() . '/data/course_data.json'; // Update file name

    // Check if the JSON file exists
    if (!file_exists($json_file_path)) {
        return '<h3>Course data not found at path: ' . esc_html($json_file_path) . '</h3>'; // Debugging output
    }

    // Load and decode the JSON data
    $json_data = json_decode(file_get_contents($json_file_path), true);

    // Apply filter based on category ID, course IDs, and limit
    $filtered_courses = array_filter($json_data, function ($course) use ($atts) {
        $is_in_category = !$atts['cat_id'] || in_array($atts['cat_id'], $course['categories']);
        $is_in_ids = !$atts['course_ids'] || in_array($course['id'], array_map('intval', explode(',', $atts['course_ids'])));
        return $is_in_category && $is_in_ids;
    });

    $filtered_courses = array_slice($filtered_courses, 0, $atts['limit']);

    // Generate the output HTML
    ob_start();

    if (!empty($filtered_courses)) {
        echo '<div class="row mha_tabs_row">';
        foreach ($filtered_courses as $course) {
            $img_url = isset($course['thumbnail']) ? esc_url($course['thumbnail']) : '';

?>
            <div class="col-md-4">
                <div class="srs_trending_card_pr">
                    <div class="srs_img_wrapper">
                        <a href="<?php echo esc_url($course['permalink']); ?>"> <!-- Fixed key from 'url' to 'permalink' -->
                            <img src="<?php echo $img_url; ?>" alt="Course Image" />
                        </a>
                    </div>
                    <div class="srs_content_wrapper">
                        <h3 class="srs_title">
                            <a href="<?php echo esc_url($course['permalink']); ?>"><?php echo esc_html($course['title']); ?></a>
                        </h3>
                        <div class="srs_meta_area">
                            <div class="srs-ratings-container bp_blank_stars">
                                <div style="width: <?php echo 20 * $course['average_rating']; ?>%" class="bp_filled_stars"></div>
                            </div>
                            <div class="student_count">
                                <p><?php echo esc_html($course['students']); ?> Students</p>
                            </div>
                        </div>
                        <div class="srs_price_area">
                            <a class="srs_btn_courses" href="<?php echo esc_url($course['permalink']); ?>">View Course</a>
                            <div class="srs_price">
                                <strong><?php echo esc_html($course['regular_price']); ?></strong> <!-- Fixed key here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php
        }
        echo '</div>';
    } else {
        echo "<h3>No course found</h3>";
    }

    // Calculate execution time
    $execution_time = microtime(true) - $start_time;
    echo '<p>Execution Time: ' . round($execution_time, 4) . ' seconds</p>'; // Output execution time

    return ob_get_clean();
}

// Register the shortcode
add_shortcode('course_by_json', 'course_by_json_shortcode');
