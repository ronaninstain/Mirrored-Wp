<?php
/* Template Name: Course Cards */

// Custom query to get published courses
$args = array(
    'post_type'      => 'course',       // Custom post type for courses
    'posts_per_page' => 10,             // Retrieve all courses
    'post_status'    => 'publish',       // Only published courses
    'meta_query'     => array(
        array(
            'key'     => 'vibe_product',
            'value'   => array(''),
            'compare' => 'NOT IN'
        )
    )
);

$courses = new WP_Query($args);

// Check if there are courses
if (!$courses->have_posts()) {
    echo '<p>No courses available.</p>';
    return;
}

// Start building the HTML for course cards
?>
<div class="wplms-course-cards" style="display: flex; flex-wrap: wrap; gap: 20px;">
    <?php while ($courses->have_posts()) : $courses->the_post(); ?>
        <?php
        // Fetching course details
        $course_id = get_the_ID();
        $title = get_the_title();
        $permalink = get_permalink();
        $thumbnail = get_the_post_thumbnail_url($course_id, 'full');
        $students = get_post_meta($course_id, 'vibe_students', true);
        $average_rating = get_post_meta($course_id, 'average_rating', true);
        $product_id = get_post_meta($course_id, 'vibe_product', true);
        $regular_price = $product_id ? get_post_meta($product_id, '_regular_price', true) : 'Free';
        $sale_price = $product_id ? get_post_meta($product_id, '_sale_price', true) : null;
        ?>

        <div class="course-card" style="border: 1px solid #ddd; border-radius: 8px; width: 300px; padding: 16px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
            <a href="<?php echo esc_url($permalink); ?>" style="text-decoration: none; color: inherit;">
                <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($title); ?>" style="width: 100%; height: auto; border-radius: 8px 8px 0 0;">
                <h3 style="margin-top: 10px; font-size: 1.2em;"><?php echo esc_html($title); ?></h3>
            </a>
            <p><strong>Students:</strong> <?php echo esc_html($students ?: 'N/A'); ?></p>
            <p><strong>Average Rating:</strong> <?php echo esc_html($average_rating ?: 'Not rated yet'); ?></p>
            <p><strong>Price:</strong>
                <span style="text-decoration: <?php echo $sale_price ? 'line-through' : 'none'; ?>;">
                    <?php echo esc_html($regular_price); ?>
                </span>
                <?php if ($sale_price) : ?>
                    <span><?php echo esc_html($sale_price); ?></span>
                <?php endif; ?>
            </p>
            <a href="<?php echo esc_url($permalink); ?>" style="display: inline-block; margin-top: 10px; padding: 8px 16px; background-color: #0073aa; color: #fff; border-radius: 4px; text-align: center; text-decoration: none;">View Course</a>
        </div>
    <?php endwhile; ?>
</div>

<?php
// Reset post data after custom query
wp_reset_postdata();
