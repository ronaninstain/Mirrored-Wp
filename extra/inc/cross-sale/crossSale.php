<?php
/**
 * Keep track if the user manually removed the cross-sell.
 */
add_action('woocommerce_cart_item_removed', 'flag_cross_sell_removal', 10, 2);
function flag_cross_sell_removal($removed_cart_item_key, $cart)
{
    $cross_sell_id = 3778; // your cross-sell product ID

    // Was the removed item our cross-sell?
    if (
        isset($cart->removed_cart_contents[$removed_cart_item_key]['product_id'])
        && $cart->removed_cart_contents[$removed_cart_item_key]['product_id'] == $cross_sell_id
    ) {
        WC()->session->set('cross_sell_removed', true);
    }
}

/**
 * Clear the “removed” flag any time the customer adds something new 
 * (so next time they visit the cart we'll show cross-sell again).
 */
add_action('woocommerce_add_to_cart', 'reset_cross_sell_flag', 20, 6);
function reset_cross_sell_flag($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
{
    WC()->session->__unset('cross_sell_removed');
}

/**
 * Automatically add/update cross-sell on cart page—unless they’ve manually removed it.
 */
add_action('woocommerce_before_cart', 'conditionally_add_cross_sell');
function conditionally_add_cross_sell()
{
    if (is_admin() || ! is_cart()) {
        return;
    }

    // if they manually removed it last visit, don’t re-add
    if (WC()->session->get('cross_sell_removed')) {
        return;
    }

    $cross_sell_id      = 3778;
    $excluded_category  = 2178;
    $cart               = WC()->cart;
    $non_excluded_count = 0;
    $cross_sell_key     = false;

    foreach ($cart->get_cart() as $key => $item) {
        $pid = $item['product_id'];

        if ($pid == $cross_sell_id) {
            $cross_sell_key = $key;
            continue;
        }

        $cats = wc_get_product_term_ids($pid, 'product_cat');
        if (! in_array($excluded_category, $cats)) {
            $non_excluded_count += $item['quantity'];
        }
    }

    // nothing eligible? remove cross-sell if it snuck back in
    if ($non_excluded_count < 1) {
        if ($cross_sell_key) {
            $cart->remove_cart_item($cross_sell_key);
        }
        return;
    }

    // update existing cross-sell qty
    if ($cross_sell_key) {
        $cart->set_quantity($cross_sell_key, $non_excluded_count);
    }
    // add it fresh
    else {
        $cart->add_to_cart($cross_sell_id, $non_excluded_count);
    }
}
