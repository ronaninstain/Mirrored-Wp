<?php

/* courses, categories, pagination, search, single course, sort api by Shoive start */


add_action('rest_api_init', function () {
    // Set CORS headers
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        exit; // End the request for OPTIONS
    }
    header("Access-Control-Allow-Origin: *"); // Allow all origins
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Authorization, Content-Type");

    register_rest_route('custom/v1', '/posts/', [
        'methods' => 'GET',
        'callback' => 'get_all_courses',
        'permission_callback' => 'verify_secret_key',
        'args' => [
            'type' => [
                'default' => 'general', // 'general', 'latest', 'alphabetical'
                'validate_callback' => function ($param, $request, $key) {
                    return in_array($param, ['general', 'latest', 'alphabetical'], true);
                }
            ],
            'cpage' => [
                'default' => 1,
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric($param);
                }
            ],
            'per_page' => [
                'default' => 10,
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric($param);
                }
            ],
            'category' => [
                'default' => '',
                'validate_callback' => function ($param, $request, $key) {
                    return is_string($param);
                }
            ],
        ],
    ]);

    register_rest_route('custom/v1', '/connected-courses/', [
        'methods' => 'POST',
        'callback' => 'get_connected_courses',
        'permission_callback' => 'verify_secret_key',
        'args' => [
            'type' => [
                'default' => 'general', // Options: general, latest, alphabetical
                'validate_callback' => function ($param, $request, $key) {
                    return in_array($param, ['general', 'latest', 'alphabetical'], true);
                }
            ],
            'cpage' => [
                'default' => 1,
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric($param);
                }
            ],
            'per_page' => [
                'default' => 10,
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric($param);
                }
            ],
            'category' => [
                'default' => '',
                'validate_callback' => function ($param, $request, $key) {
                    return is_string($param);
                }
            ],
        ],
    ]);

    register_rest_route(
        'custom-api/v1',
        '/courses/(?P<id>\d+)',
        array(
            'methods' => 'GET',
            'callback' => 'get_course_by_id',
            'permission_callback' => 'verify_secret_key2',
        )
    );

    register_rest_route('custom/v1', '/course-categories/', array(
        'methods' => 'GET',
        'callback' => 'get_course_categories',
        'permission_callback' => 'verify_secret_key',
    ));

    register_rest_route('custom/v1', '/search-courses', [
        'methods' => 'GET',
        'callback' => 'search_courses',
        'permission_callback' => 'verify_secret_key',
        'args' => [
            'term' => [
                'required' => true,
                'validate_callback' => function ($param, $request, $key) {
                    return !empty($param);
                }
            ],
        ],
    ]);
});
function verify_secret_key(WP_REST_Request $request)
{
    $provided_key = $request->get_header('Authorization');
    // Check if the provided key starts with 'Bearer '
    if (strpos($provided_key, 'Bearer ') === 0) {

        //$provided_key = substr($provided_key, 7);


        $input = substr($provided_key, 7);

        // Remove 'Bearer ' from the string
        $input = str_replace('Bearer ', '', $input);

        // Split the string by ':'
        $parts = explode(':', $input);


        $part1 = intval($parts[0]); // Ensure client_id is an integer
        $part2 = sanitize_text_field($parts[1]);


        // Retrieve the expected secret key from the database based on client_id
        global $wpdb;
        $table_name = $wpdb->prefix . 'cmc_clients';

        $query = $wpdb->prepare("SELECT secret_key FROM $table_name WHERE id = %d", $part1);

        $row = $wpdb->get_row($query);

        if ($row && $row->secret_key === $part2) {
            return true;
        }
    }

    return new WP_Error('invalid_key', 'Invalid API key or client ID provided', ['status' => 403]);
}
function verify_secret_key2(WP_REST_Request $request)
{
    $provided_key = $request->get_header('Authorization');

    if (strpos($provided_key, 'Bearer ') === 0) {
        // Extract the Bearer token value
        $input = substr($provided_key, 7);
        $input = str_replace('Bearer ', '', $input);
        $parts = explode(':', $input);

        $client_id = intval($parts[0]);
        $secret_key = sanitize_text_field($parts[1]);

        // Retrieve the stored secret key from the database based on client_id
        global $wpdb;
        $table_name = $wpdb->prefix . 'cmc_clients';
        $query = $wpdb->prepare("SELECT secret_key FROM $table_name WHERE id = %d", $client_id);
        $row = $wpdb->get_row($query);

        // Validate the secret key
        if ($row && $row->secret_key === $secret_key) {
            return true;
        }
    }

    return new WP_Error('invalid_key', 'Invalid API key or client ID provided', ['status' => 403]);
}
function get_all_courses(WP_REST_Request $request)
{
    $type = $request['type'];
    $page = $request['cpage'];
    $per_page = $request['per_page'];
    $category = $request['category']; // Get the category parameter

    // Set up the base query arguments
    $args = [
        'post_type' => 'course',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'post_status' => 'publish',
    ];

    // Add category filter if category is provided
    if (!empty($category)) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'course-cat',
                'field' => 'slug',
                'terms' => $category,
            ],
        ];
    }

    // Adjust query arguments based on the type
    if ($type === 'latest') {
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    } elseif ($type === 'alphabetical') {
        $args['orderby'] = 'title';
        $args['order'] = 'ASC';
    }

    $query = new WP_Query($args);
    $courses = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Get categories
            $categories = get_the_terms(get_the_ID(), 'course-cat');
            $primary_category = '';
            if (!empty($categories) && !is_wp_error($categories)) {
                $primary_category = $categories[0]->name; // Get the first category as the primary
            }

            $courses[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'content' => get_the_content(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                'permalink' => get_the_permalink(get_the_ID()),
                'categories' => [$primary_category],  // Add category names here
                'meta' => array(
                    'average_rating' => get_post_meta(get_the_ID(), 'average_rating', true),
                    'rating_count' => get_post_meta(get_the_ID(), 'rating_count', true),
                    'vibe_students' => get_post_meta(get_the_ID(), 'vibe_students', true),
                    'vibe_product' => get_post_meta(get_the_ID(), 'vibe_product', true),
                    'regular_price' => get_post_meta(get_the_ID(), '_regular_price', true),
                    'sale_price' => get_post_meta(get_the_ID(), '_sale_price', true),
                    'units' => count(bp_course_get_curriculum_units(get_the_ID())),
                ),
            ];
        }
        wp_reset_postdata();
    }

    $total_posts = $query->found_posts;
    $total_pages = $query->max_num_pages;

    return [
        'total_posts' => $total_posts,
        'total_pages' => $total_pages,
        'courses' => $courses,
    ];
}
/* Main callback to get connected courses */
function get_connected_courses(WP_REST_Request $request)
{
    $params = $request->get_params();

    $type = isset($params['type']) ? sanitize_text_field($params['type']) : '';
    $page = isset($params['cpage']) ? intval($params['cpage']) : 1;
    $per_page = isset($params['per_page']) ? intval($params['per_page']) : 9;
    $category = isset($params['category']) ? sanitize_text_field($params['category']) : '';
    $course_ids = isset($params['course_ids']) ? $params['course_ids'] : [];

    // Sanitize course IDs
    $connected_course_ids = array_map('intval', (array) $course_ids);
    $connected_course_ids = array_filter($connected_course_ids, function ($id) {
        return $id > 0;
    });

    if (empty($connected_course_ids)) {
        return [
            'total_posts' => 0,
            'total_pages' => 0,
            'courses' => []
        ];
    }

    $args = [
        'post_type' => 'course',
        'posts_per_page' => $per_page,
        'paged' => $page,
        'post_status' => 'publish',
        'post__in' => $connected_course_ids,
    ];

    if (!empty($category)) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'course-cat',
                'field' => 'slug',
                'terms' => $category,
            ],
        ];
    }

    if ($type === 'latest') {
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    } elseif ($type === 'alphabetical') {
        $args['orderby'] = 'title';
        $args['order'] = 'ASC';
    }

    $query = new WP_Query($args);
    $courses = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Get the first course category (if available)
            $terms = get_the_terms(get_the_ID(), 'course-cat');
            $primary_category = '';
            if (!empty($terms) && !is_wp_error($terms)) {
                $primary_category = $terms[0]->name;
            }

            $courses[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'content' => get_the_content(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                'permalink' => get_the_permalink(get_the_ID()),
                'categories' => [$primary_category],
                'meta' => [
                    'average_rating' => get_post_meta(get_the_ID(), 'average_rating', true),
                    'rating_count' => get_post_meta(get_the_ID(), 'rating_count', true),
                    'vibe_students' => get_post_meta(get_the_ID(), 'vibe_students', true),
                    'vibe_product' => get_post_meta(get_the_ID(), 'vibe_product', true),
                    'regular_price' => get_post_meta(get_the_ID(), '_regular_price', true),
                    'sale_price' => get_post_meta(get_the_ID(), '_sale_price', true),
                    'units' => count(bp_course_get_curriculum_units(get_the_ID())),
                ],
            ];
        }
        wp_reset_postdata();
    }

    return [
        'total_posts' => $query->found_posts,
        'total_pages' => $query->max_num_pages,
        'courses' => $courses,
    ];
}
function get_course_by_id($data)
{
    $post_id = $data['id'];
    $course = get_post($post_id);

    if (empty($course) || $course->post_type != 'course') {
        return new WP_Error('no_course', 'Course not found', array('status' => 404));
    }

    // Get the course categories
    $categories = get_the_terms($course->ID, 'course-cat');
    $category_names = [];

    if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            $category_names[] = $category->name;
        }
    }

    // Get the course levels
    $courseLevels = get_the_terms($course->ID, 'level');
    $levels = [];

    if (!empty($courseLevels) && !is_wp_error($courseLevels)) {
        foreach ($courseLevels as $level) {
            $levels[] = $level->name;
        }
    }

    // get curriculumn list

    function createMultidimensionalArray($courseID)
    {
        $curriculums = bp_course_get_curriculum($courseID);
        $resultArray = array();
        $currentParent = null;

        foreach ($curriculums as $item) {
            if (get_post_type($item) != 'unit') {
                $currentParent = $item;
                $resultArray[$currentParent] = array();
            } elseif ($currentParent !== null) {
                $resultArray[$currentParent][] = get_the_title($item);
            }
        }
        return $resultArray;
    }

    $multidimensionalArray = createMultidimensionalArray($course->ID);

    $units = bp_course_get_curriculum_units($course->ID);
    $total_duration = 0;

    foreach ($units as $unit) {
        $duration = get_post_meta($unit, 'vibe_duration', true);
        $duration = empty($duration) ? 0 : $duration;

        $unit_duration_parameter = (get_post_type($unit) == 'unit')
            ? apply_filters('vibe_unit_duration_parameter', 60, $unit)
            : apply_filters('vibe_quiz_duration_parameter', 60, $unit);

        $total_duration += $duration * $unit_duration_parameter;
    }

    $courseDuration = tofriendlytime(($total_duration));

    function get_number_of_quizzes($courseID)
    {
        $units = bp_course_get_curriculum_units($courseID);
        $quizCount = 0;
        foreach ($units as $unit) {
            if (get_post_type($unit) == 'quiz') {
                $quizCount++;
            }
        }
        return $quizCount;
    }
    $quiz_count = get_number_of_quizzes($course->ID);

    $response = array(
        'id' => $course->ID,
        'title' => $course->post_title,
        'excerpt' => get_the_excerpt($course->ID),
        'content' => $course->post_content,
        'thumbnail' => get_the_post_thumbnail_url($course->ID),
        'categories' => $category_names,
        'curriculum' => $multidimensionalArray, // for course curriculum
        'course_levels' => $levels, // for levels
        'course_duration' => $courseDuration, // For duration
        'course_quiz' => $quiz_count, // For duration
        'meta' => array(
            'average_rating' => get_post_meta($course->ID, 'average_rating', true),
            'rating_count' => get_post_meta($course->ID, 'rating_count', true),
            'vibe_students' => get_post_meta($course->ID, 'vibe_students', true),
            'vibe_product' => get_post_meta($course->ID, 'vibe_product', true),
            'regular_price' => get_post_meta($course->ID, '_regular_price', true),
            'sale_price' => get_post_meta($course->ID, '_sale_price', true),
            'units' => count(bp_course_get_curriculum_units($course->ID)),
            'vibe_duration' => get_post_meta($course->ID, 'vibe_duration', true),
            'certificate_check' => get_post_meta($course->ID, 'vibe_certificate_template', true),
        ),
    );

    return new WP_REST_Response($response, 200);
}
function get_course_categories()
{
    $categories = get_terms(array(
        'taxonomy' => 'course-cat',
        'hide_empty' => false,
    ));

    if (!empty($categories) && !is_wp_error($categories)) {
        $category_list = array();

        foreach ($categories as $category) {
            $category_list[] = array(
                'id' => $category->term_id,
                'slug' => $category->slug,
                'name' => $category->name,
            );
        }

        wp_send_json_success($category_list);
    } else {
        wp_send_json_error('No categories found');
    }
}
function search_courses(WP_REST_Request $request)
{
    $term = $request->get_param('term');

    $args = [
        'post_type' => 'course',
        's' => $term,
        'posts_per_page' => 10, // Limit to 10 results
        'post_status' => 'publish',
    ];

    $query = new WP_Query($args);
    $courses = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $courses[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'small'), // Get the small thumbnail URL
            ];
        }
        wp_reset_postdata();
    }

    return new WP_REST_Response($courses, 200);
}

/* courses, categories, pagination, search, single course, sort api by Shoive end */