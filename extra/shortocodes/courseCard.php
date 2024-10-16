<?php

function courseCards($atts)
{
    $course_id = $atts['courseid'];

    $currentID = get_queried_object_id();

    $current_course_terms = get_the_terms($currentID, 'course-cat');

    $current_course_term_ids = array();

    if ($current_course_terms) {
        foreach ($current_course_terms as $term) {
            $current_course_term_ids[] = $term->term_id;
        }
    }

    if (!empty($course_id)) {
        $course_ids = $course_id;
        $course_ids = (explode(",", $course_ids));

        $c_id = array();

        if ($course_ids) {
            foreach ($course_ids as $course_id) {
                $c_id[] = $course_id;
            }
        }

        $args = array(
            'post_type' => 'course',
            'post_status' => 'published',
            'posts_per_page' => 1,
            'post__in' => $c_id
        );
    } else {
        $args = array(
            'post_type' => 'course',
            'posts_per_page' => 5,
            'post__not_in' => array(get_the_ID()),
            'tax_query' => array(
                array(
                    'taxonomy' => 'course-cat',
                    'field' => 'id',
                    'terms' => $current_course_term_ids,
                ),
            ),
        );
    }

    $related_courses_query = new WP_Query($args);

    if ($related_courses_query->have_posts()) {
        while ($related_courses_query->have_posts()) {
            $related_courses_query->the_post();

            $courseID = get_the_ID();
            $courseImage = get_the_post_thumbnail_url($courseID, 'medium');
            $courseLink = get_the_permalink($courseID);
            $average_rating = get_post_meta($courseID, 'average_rating', true);
            $countRating = get_post_meta($courseID, 'rating_count', true);
            $taxonomy = 'course-cat';
            $terms = wp_get_post_terms($courseID, $taxonomy, array('fields' => 'all'));

            // Check for Yoast Primary Category
            $primary_cat_id = get_post_meta($courseID, '_yoast_wpseo_primary_course-cat', true);

            if ($primary_cat_id) {
                $primary_cat = get_term($primary_cat_id, 'course-cat');
            } else {
                // Fallback to first category
                $primary_cat = !empty($terms) ? $terms[0] : null;
            }

            $category_description = $primary_cat ? category_description($primary_cat) : '';

            // Your HTML starts here
?>
            <div class="r4h-ia-tab-content">
                <div class="tab-content-left-side">
                    <div class="left-side-text">
                        <?php if ($primary_cat) : ?>
                            <h1><?php echo esc_html($primary_cat->name); ?></h1>
                            <p><?php echo wp_kses_post($category_description); ?></p>
                        <?php endif; ?>
                    </div>
                    <a href="<?php echo site_url(); ?>/course-cat/<?php echo esc_html($primary_cat->slug); ?>">
                        <p>View All <?php echo esc_html($primary_cat->name); ?> Courses</p>
                        <img src="<?php echo get_theme_file_uri() . '/assets/img/material-symbols_double-arrow-rounded.svg' ?>" alt="svg" />
                    </a>
                </div>
                <div class="tab-content-right-side">
                    <h5>Recommended Professional Course</h5>
                    <div class="r4h-ia-template-custom-card">
                        <div class="img">
                            <img src="<?php echo $courseImage; ?>" alt="courseImg" />
                        </div>
                        <div class="details">
                            <div class="featured">
                                <div class="featured-img">
                                    <img src="<?php echo get_theme_file_uri() . '/assets/img/hotel_class.svg' ?>" alt="svg" />
                                </div>
                                <h6>FEATURED</h6>
                            </div>
                            <div class="duration">
                                <div class="duration-img">
                                    <img src="<?php echo get_theme_file_uri() . '/assets/img/schedule.svg' ?>" alt="avg" />
                                </div>
                                <h6>
                                    <?php
                                    // Rest of your time logic
                                    ?>
                                </h6>
                            </div>
                            <h1><?php bp_course_name(); ?></h1>
                            <div class="rating">
                                <div class="rating-img">
                                    <div class="rating_sh_content">
                                        <div class="sh_rating">
                                            <div class="sh_rating-upper" style="width:<?php echo $average_rating ? 20 * $average_rating : 0; ?>%">
                                                <span>★</span>
                                                <span>★</span>
                                                <span>★</span>
                                                <span>★</span>
                                                <span>★</span>
                                            </div>
                                            <div class="sh_rating-lower">
                                                <span>★</span>
                                                <span>★</span>
                                                <span>★</span>
                                                <span>★</span>
                                                <span>★</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p>(<?php echo $countRating; ?> reviews)</p>
                            </div>
                        </div>
                        <div class="price-button">
                            <a href="<?php echo $courseLink; ?>">View course</a>
                            <p><?php bp_course_credits(); ?></p>
                        </div>
                    </div>
                </div>
            </div>
<?php
        }
        wp_reset_query();
    } else {
        echo 'No course found';
    }
}
add_shortcode('courseCards', 'courseCards');
