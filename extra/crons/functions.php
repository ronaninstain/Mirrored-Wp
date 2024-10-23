<?php
// Shoive

//For Course Tags

add_action('init', 'create_course_tag_taxonomy', 0);
function create_course_tag_taxonomy()
{

    $labels = array(
        'name' => _x('Course Tags', 'Tag general name'),
        'singular_name' => _x('course_tag', 'Tag singular name'),
        'search_items' =>  __('Search Tag'),
        'all_items' => __('All Tag'),
        'edit_item' => __('Edit Tag'),
        'update_item' => __('Update Tag'),
        'add_new_item' => __('Add New Tag'),
        'new_item_name' => __('New Tag Name'),
        'menu_name' => __('Course Tag'),
    );

    register_taxonomy('course-tag', array('course'), array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_in_menu' => true,
        'show_admin_column' => true,
        'rewrite'           => array('slug' => 'course-tag')
    ));
}

// For Changing Course button text

function custom_wplms_course_button_label($label)
{
    return 'Start Course Now';
}
add_filter('wplms_take_this_course_button_label', 'custom_wplms_course_button_label');


// this added by arif for blog 
// related post for single post page 
function showRelatedPosts($postType = 'post', $postID = null, $totalPosts = null, $relatedBy = null)
{
    global $post, $related_posts_custom_query_args;
    if (null === $postID) $postID = $post->ID;
    if (null === $totalPosts) $totalPosts = 3;
    if (null === $relatedBy) $relatedBy = 'category';
    if (null === $postType) $postType = 'post';

    if ($relatedBy === 'category') {
        $categories = get_the_category($post->ID);
        $catidlist = '';
        foreach ($categories as $category) {
            $catidlist .= $category->cat_ID . ",";
        }
        $related_posts_custom_query_args = array(
            'post_type' => $postType,
            'posts_per_page' => $totalPosts,
            'post__not_in' => array($postID),
            'orderby' => 'rand',
            'cat' => $catidlist,
        );
    }

    if ($relatedBy === 'tags') {
        $tags = wp_get_post_tags($postID);
        if ($tags) {
            $tag_ids = array();
            foreach ($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
            $related_posts_custom_query_args = array(
                'post_type' => $postType,
                'tag__in' => $tag_ids,
                'posts_per_page' => $totalPosts,
                'post__not_in' => array($postID),
                'orderby' => 'rand',
            );
        } else {
            $related_posts_custom_query_args = array(
                'post_type' => $postType,
                'posts_per_page' => $totalPosts,
                'post__not_in' => array($postID),
                'orderby' => 'rand',
            );
        }
    }

    // Initiate the custom query
    $custom_query = new WP_Query($related_posts_custom_query_args);
    if ($custom_query->have_posts()) : ?>
        <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>

            <div class="col-sm-4 col-md-4">
                <div class="cb-related-post-thumbnail">
                    <a href="<?php the_permalink(); ?>">
                        <img src="<?php the_post_thumbnail_url('card-thumb') ?>" alt="<?php the_title() ?>" name="<?php the_title(); ?>">
                    </a>
                </div>
                <div class="cb-related-entry-desc">
                    <p><?php echo get_the_date('M j, Y'); ?></p>
                    <span class="h3">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </span>
                </div>
            </div>

        <?php endwhile; ?>
    <?php else : ?>
        <p>Sorry, no related articles to display.</p>
<?php endif;
    wp_reset_postdata();
}

/**
 * Move up to down comment fields 
 */
function workhouse_move_comment_field_to_bottom($fields)
{
    $comment_field = $fields['comment'];
    unset($fields['comment']);
    $fields['comment'] = $comment_field;
    unset($fields['url']);
    unset($fields['cookies']);
    return $fields;
}
add_filter('comment_form_fields', 'workhouse_move_comment_field_to_bottom');




/* Cron Job By Shoive start */
function wplms_generate_course_json()
{
    // Fetch courses (WPLMS uses 'course' as the post type)
    $args = array(
        'post_type'      => 'course',  // Custom post type for course in WPLMS
        'posts_per_page' => -1,        // Retrieve all courses
        'post_status'    => 'publish',
        'meta_query' => array(
            array(
                'key' => 'vibe_product',
                'value' => array(''),
                'compare' => 'NOT IN'
            )
        ), // Only published courses
    );

    $courses = new WP_Query($args);

    // Initialize an empty array to store course data
    $course_data = array();

    if ($courses->have_posts()) {
        while ($courses->have_posts()) : $courses->the_post();
            $course_data[] = array(
                'id'       => get_the_ID(),
                'title'       => get_the_title(),
                'permalink'   => get_permalink(),
                'thumbnail'   => get_the_post_thumbnail_url(get_the_ID(), 'full') // Get full size thumbnail
            );
        endwhile;
        wp_reset_postdata();
    }

    // Generate the JSON file
    $upload_dir = wp_upload_dir();  // Get WordPress upload directory
    $file_path = $upload_dir['basedir'] . '/course_data.json';  // File path for the JSON file

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