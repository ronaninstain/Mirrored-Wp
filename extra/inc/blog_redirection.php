<?php
function redirect_blog_layouts()
{
    $redirect_pages = [
        '/blogs-articles/',
        '/blog-layout-2/',
        '/blog-layout-2/page/2/',
        '/blog-layout-1/',
        '/blog-layout-3/',
    ];

    $redirects = [
        '/how-old-must-you-be-to-buy-paracetamol/' => '/what-are-the-categories-of-medication/'
    ];

    // Redirect blog layout pages to the primary blog page
    if (in_array(trailingslashit($_SERVER['REQUEST_URI']), $redirect_pages)) {
        wp_redirect(site_url('/blogs'), 301);
        exit;
    }

    // Redirect specific course pages to new URLs
    $request_uri = trailingslashit($_SERVER['REQUEST_URI']);
    foreach ($redirects as $old_url => $new_url) {
        if ($request_uri === trailingslashit($old_url)) {
            wp_redirect(site_url($new_url), 301);
            exit;
        }
    }
}
add_action('template_redirect', 'redirect_blog_layouts');

function custom_course_redirect_parse_request($query)
{
    // Full URLs mapping from old to new course URLs
    $redirects = [
        '/course/diploma-in-pharmacy-skills/' => '/course/diploma-in-pharmacy-skills-2/',
        '/course/heating-ventilation-air-conditioning-hvac-technician/' => '/course/heating-ventilation-air-conditioning-hvac-technician-2/',
        '/course/speech-and-language-therapy-assistant/' => '/course/speech-and-language-therapy-assistant-3/',
        '/course/online-electrician-course/' => '/course/basic-electricity-course-3/',
        '/course/diploma-in-hr-and-payroll-administrator2/' => '/course/hr-and-payroll-management-with-recruitment-consultant-diploma/',
        '/course/window-cleaner-course/' => '/course/housekeeper/',
        '/course/medical-coding-and-billing2/' => '/course/clinical-coding-billing/',
    ];

    $request_uri = trailingslashit($_SERVER['REQUEST_URI']);

    // Check if the current request URI matches any in the redirects array
    if (array_key_exists($request_uri, $redirects)) {
        $new_url = site_url($redirects[$request_uri]);
        wp_redirect($new_url, 301);
        exit;
    }
}
add_action('parse_request', 'custom_course_redirect_parse_request');



