<?php
function after_submission($entry, $form)
{
    // Ensure the form ID is correct
    if ($form['id'] != 242) {
        return;
    }

    // Get the email from the form submission
    $email = rgar($entry, '5'); // '5' is the field ID for the email field

    // Check if the email is valid
    if (!is_email($email)) {
        return;
    }

    // Check if the user already exists
    if (email_exists($email)) {
        return; // User already exists, do nothing
    }

    // Generate a username from the email
    $username = sanitize_user(current(explode('@', $email)), true);

    // Ensure the username is unique
    $username = wp_slash($username);
    $i = 1;
    while (username_exists($username)) {
        $username = $username . $i;
        $i++;
    }

    // Generate a random password
    $password = wp_generate_password(12, false);

    // Create the user
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        // Handle error if user creation fails
        return;
    }

    // Set the user role to 'student'
    $user = new WP_User($user_id);
    $user->set_role('student');

    // Get the selected course ID from the form submission
    $course_id = rgar($entry, '10'); // '10' is the field ID for the course dropdown

    // Check if the course ID is valid
    if (!empty($course_id) && is_numeric($course_id)) {
        // Add the user to the selected course
        $course_assign = bp_course_add_user_to_course($user_id, $course_id);

        if (is_wp_error($course_assign)) {
            // Handle error if course assignment fails
            error_log('Failed to assign user to course: ' . $course_assign->get_error_message());
        }
    }

    // Optionally, send an email to the user with their login details
    wp_new_user_notification($user_id, null, 'both');
}

// Hook the function to the Gravity Forms submission
add_action('gform_after_submission_242', 'after_submission', 10, 2);
