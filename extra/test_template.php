<?php
/*
Template Name: Course Meta Fields Test
*/

get_header(); ?>

<div class="content-area">
    <main class="site-main" role="main">

        <h1>Course Meta Fields Test</h1>

        <?php
        // Replace with the ID of the course you want to test
        $course_id = 530118;

        // Fetch all meta fields for the course
        $all_meta = get_post_meta($course_id);

        // Check if any meta fields exist
        if (!empty($all_meta)) {
            echo '<h2>Meta Fields for Course ID: ' . $course_id . '</h2>';
            echo '<pre>';
            print_r($all_meta);
            echo '</pre>';
        } else {
            echo '<p>No meta fields found for this course.</p>';
        }
        ?>

    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>