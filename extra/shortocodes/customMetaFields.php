<?php
function add_course_meta_boxes()
{
    add_meta_box(
        'course_meta_box',          // Unique ID
        'Course Custom Fields',     // Box title
        'course_meta_box_html',     // Content callback
        'course',                   // Post type
        'normal',                   // Context
        'high'                      // Priority
    );
}
add_action('add_meta_boxes', 'add_course_meta_boxes');

function course_meta_box_html($post)
{
    // Retrieve existing values from the database.
    // Ensure you are retrieving single values (not arrays).
    $custom_reviews = get_post_meta($post->ID, 'vibe_course_review', true);
    $custom_rating_count = get_post_meta($post->ID, 'rating_count', true);
    $custom_average_rating = get_post_meta($post->ID, 'average_rating', true);

    // Output security nonces.
    wp_nonce_field('save_course_meta_box', 'custom_reviews_nonce');
    wp_nonce_field('save_course_meta_box', 'custom_rating_count_nonce');
    wp_nonce_field('save_course_meta_box', 'custom_average_rating_nonce');
?>

    <div style="margin-bottom: 10px;">
        <label for="custom_reviews">Custom Reviews</label>
        <input type="text" name="custom_reviews" id="custom_reviews" value="<?php echo esc_attr($custom_reviews); ?>" />
    </div>

    <div style="margin-bottom: 10px;">
        <label for="custom_rating_count">Custom Rating Count</label>
        <input type="text" name="custom_rating_count" id="custom_rating_count" value="<?php echo esc_attr($custom_rating_count); ?>" />
    </div>

    <div style="margin-bottom: 10px;">
        <label for="custom_average_rating">Custom Average Rating</label>
        <input type="text" name="custom_average_rating" id="custom_average_rating" value="<?php echo esc_attr($custom_average_rating); ?>" />
    </div>

<?php
}

function save_course_meta_box($post_id)
{
    // Check if the nonce is set and verify it.
    if (
        !isset($_POST['custom_reviews_nonce']) ||
        !wp_verify_nonce($_POST['custom_reviews_nonce'], 'save_course_meta_box') ||
        !isset($_POST['custom_rating_count_nonce']) ||
        !wp_verify_nonce($_POST['custom_rating_count_nonce'], 'save_course_meta_box') ||
        !isset($_POST['custom_average_rating_nonce']) ||
        !wp_verify_nonce($_POST['custom_average_rating_nonce'], 'save_course_meta_box')
    ) {
        return;
    }

    // If this is an autosave, return.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions.
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Debugging: Check if custom_reviews is set and log the value.
    if (isset($_POST['custom_reviews'])) {
        $custom_reviews = sanitize_text_field($_POST['custom_reviews']);

        // Debugging: Log the new value for custom_reviews.
        error_log('Saving custom_reviews: ' . $custom_reviews);

        // Get the existing vibe_course_review value for debugging.
        $existing_reviews = get_post_meta($post_id, 'vibe_course_review', true);
        error_log('Existing vibe_course_review: ' . print_r($existing_reviews, true));

        // Delete the old meta if it exists (forcefully clear old value).
        delete_post_meta($post_id, 'vibe_course_review');

        // Add the new value.
        $update_result = update_post_meta($post_id, 'vibe_course_review', $custom_reviews);

        // Debugging: Log if the update succeeded or failed.
        if ($update_result) {
            error_log('vibe_course_review updated successfully.');
        } else {
            error_log('vibe_course_review update failed.');
        }

        // Retrieve again to check if the update was successful.
        $updated_reviews = get_post_meta($post_id, 'vibe_course_review', true);
        error_log('Updated vibe_course_review: ' . print_r($updated_reviews, true));
    }

    if (isset($_POST['custom_rating_count'])) {
        $custom_rating_count = sanitize_text_field($_POST['custom_rating_count']);
        update_post_meta($post_id, 'rating_count', $custom_rating_count);
    }

    if (isset($_POST['custom_average_rating'])) {
        $custom_average_rating = sanitize_text_field($_POST['custom_average_rating']);
        update_post_meta($post_id, 'average_rating', $custom_average_rating);
    }
}
add_action('save_post', 'save_course_meta_box');
