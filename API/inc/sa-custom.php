<?php
function send_custom_email($to, $subject, $message, $from_email, $from_name) {
    // Dynamically set the "from" email
    add_filter('wp_mail_from', function() use ($from_email) {
        return $from_email;
    });

    // Dynamically set the "from" name
    add_filter('wp_mail_from_name', function() use ($from_name) {
        return $from_name;
    });

    $headers = array('Content-Type: text/html; charset=UTF-8');

    $email_sent = wp_mail($to, $subject, $message, $headers);

    // Remove the filters to avoid affecting other emails
    remove_filter('wp_mail_from', function() use ($from_email) {});
    remove_filter('wp_mail_from_name', function() use ($from_name) {});

    // Check if the email was sent successfully
    if ($email_sent) {
        echo 'Email sent successfully!';
    } else {
        echo 'Failed to send the email.';
    }
}

// Example usage
//send_custom_email('sakib.ahmed.staffasia@gmail.com', 'Sample Email from WordPress', 'This is a sample email sent from WordPress.', 'info@oneeducation.org.uk', 'OE');
//send_custom_email('sakib.ahmed.staffasia@gmail.com', 'Sample Email from WordPress', 'This is a sample email sent from WordPress.', 'info@libm.co.uk', 'LIBM');
