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

    if (in_array($_SERVER['REQUEST_URI'], $redirect_pages)) {
        wp_redirect(site_url('/blog/'), 301);
        exit;
    }
}
add_action('template_redirect', 'redirect_blog_layouts');
