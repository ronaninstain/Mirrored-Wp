<?php
/*
Template Name: Last Login CSV Report
*/

// Define the filename for the CSV
$filename = 'students_last_login_report.csv';

// Set headers to download the file as a CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Open a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Add CSV column headers
fputcsv($output, ['First Name', 'Last Name', 'Email', 'Last Login']);

// Get all users with the role 'student'
$args = [
    'role' => 'student',
    'fields' => ['ID', 'user_email', 'display_name']
];
$students = get_users($args);

// Loop through each student and write their data to the CSV
foreach ($students as $student) {
    $user_id = $student->ID;

    // Get user's first and last name
    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);

    // Get the last login timestamp and convert to a readable date format
    $last_login_timestamp = get_user_meta($user_id, 'wc_last_active', true);
    $last_login = $last_login_timestamp ? date('F j, Y, g:i a', $last_login_timestamp) : 'Never logged in';

    // Add the row to the CSV
    fputcsv($output, [
        $first_name,
        $last_name,
        $student->user_email,
        $last_login,
    ]);
}

// Close the output stream
fclose($output);

// Exit to ensure no additional output is sent
exit;
?>