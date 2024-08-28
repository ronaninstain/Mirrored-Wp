<?php
/* Template Name: ACf output */
acf_form_head();
get_header();
?>

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

<?php
get_footer();
?>