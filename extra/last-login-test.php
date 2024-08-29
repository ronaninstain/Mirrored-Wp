<?php
/*
Template Name: Last Login Template
*/

get_header();


$user_id = 43236;

// Get user data
$user_info = get_userdata($user_id);
echo '<pre>';
var_dump($user_info);
echo '</pre>';


// meta for a specific user
$all_meta = get_user_meta($user_id);

// Display all meta keys and values
echo '<pre>';
print_r($all_meta);
echo '</pre>';


// the last login timestmp

$last_login_timestamp = get_user_meta($user_id, 'wc_last_active', true);

// the timestamp to a readable date format
$last_login = $last_login_timestamp ? date('F j, Y, g:i a', $last_login_timestamp) : false;

?>

<div class="hello-template">
    <h2>Hello!</h2>

    <?php if ($user_info): ?>
        <p>Welcome back, <?php echo esc_html($user_info->display_name); ?>!</p>
        <?php if ($last_login): ?>
            <p>Your last login was on: <?php echo esc_html($last_login); ?></p>
        <?php else: ?>
            <p>This is your first login. Welcome!</p>
        <?php endif; ?>
    <?php else: ?>
        <p>User not found.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>