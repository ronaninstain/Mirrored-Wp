<?php
/**
 * Template Name: Edit Favorite Quote
 */

// Ensure ACF form processing is initialized
acf_form_head();
get_header(); ?>

<div class="container">
    <?php if (is_user_logged_in()): ?>
        <?php
        $post_id = get_the_ID(); // Get the current post ID
        acf_form(
            array(
                'post_id' => $post_id,
                'fields' => array('favorite_quote'),
                'submit_value' => 'Update Quote',
                'return' => '%post_url%', // Redirect to the post after updating
            )
        );
        ?>
    <?php else: ?>
        <p>You need to be logged in to edit this field.</p>
    <?php endif; ?>


</div>


<?php if (function_exists('get_field')): ?>
    <?php $post_id = get_the_ID(); ?>
    <?php $favorite_quote = get_field('favorite_quote', $post_id); ?>
    <?php if ($favorite_quote): ?>
        <div class="favorite-quote">
            <h3>Favorite Quote:</h3>
            <p><?php echo esc_html($favorite_quote); ?></p>
        </div>
    <?php else: ?>
        <p>No favorite quote found for this post.</p>
    <?php endif; ?>
<?php else: ?>
    <p>ACF is not available.</p>
<?php endif; ?>

<?php get_footer(); ?>