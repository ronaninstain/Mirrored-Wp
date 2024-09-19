<?php
function get_course_price()
{
    global $wpdb;

    $course_id = intval($_POST['course_id']);
    $table_name = $wpdb->prefix . 'ptc_items';

    $product_id = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT product_id FROM $table_name WHERE course_id = %d",
            $course_id
        )
    );

    if ($product_id) {
        // Get the product object
        $product = wc_get_product($product_id);
        $product_url = get_permalink($product_id);
        if ($product) {
            $price = $product->get_sale_price() ?: $product->get_regular_price();
            wp_send_json_success([
                'price' => '<sup>' . get_woocommerce_currency_symbol() . '</sup>' . esc_html($price),
                'product_url' => $product_url
            ]);
        } else {
            wp_send_json_error(['message' => 'Product not found.']);
        }
    } else {
        wp_send_json_error(['message' => 'No product associated with this course.']);
    }

    wp_die();
}

add_action('wp_ajax_get_course_price', 'get_course_price');
add_action('wp_ajax_nopriv_get_course_price', 'get_course_price');

