<?php
/*
================================================================
===================== Cross Sale Start ======================
================================================================
*/

function cross_sale_action_woocommerce_after_cart_table()
{
    if (!is_cart()) {
        return;
    }

    // Styles
?>
    <style>
        .special-offer-title {
            margin-bottom: 15px;
        }

        .special-offer-title h2 {
            font-weight: 600;
            font-size: 28px;
        }

        p.special-product-name {
            display: inline-block;
            margin: 0;
            font-size: 20px;
            width: calc(100% - 50px);
        }

        .special-products-wrapper::before {
            content: "";
            position: absolute;
            width: 100%;
            height: 5px;
            border-radius: 10px;
            top: 0;
            left: 0;
        }

        .special-products-wrapper {
            max-width: 550px;
            margin-bottom: 50px;
            position: relative;
            padding-top: 35px;
        }

        .special-products-single {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .special-products-single a.remove {
            background-color: #eeeeee;
            border-color: #eeeeee;
            color: #333333;
            padding: 0.618em 1.416em;
            font-weight: 600;
            max-width: 120px;
            text-align: center;
            width: 100%;
            text-decoration: none !important;
        }

        .special-products-single a.remove:hover {
            background-color: #d5d5d5;
            border-color: #d5d5d5;
        }

        .special-products-single a.remove::before {
            display: none !important;
        }

        input.xs-add-to-cart-button[type="checkbox"] {
            position: relative;
            width: 50px;
            height: 28px;
            -webkit-appearance: none;
            background: #c6c6c6;
            outline: none;
            border-radius: 50px;
            transition: 0.7s;
        }

        input.xs-add-to-cart-button:checked[type="checkbox"] {
            background: #03a9f4;
        }

        input.xs-add-to-cart-button[type="checkbox"]:before {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            top: 50%;
            left: 5px;
            background: #ffffff;
            transform: translateY(-50%);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: .5s;
        }

        input.xs-add-to-cart-button:checked[type="checkbox"]:before {
            left: 25px;
        }

        @media (max-width: 767px) {
            p.special-product-name {
                font-size: 18px;
            }
        }
    </style>
    <?php

    // Check if 'special-offer' category exists, if not, create it
    $term = term_exists('special-offer', 'product_cat');
    if (!$term) {
        wp_insert_term('Special Offer', 'product_cat', [
            'description' => 'Special offer products to show after cart table',
            'slug' => 'special-offer'
        ]);
    }

    // Get the 'special-offer' category details
    $special_category = get_term_by('slug', 'special-offer', 'product_cat');

    // Display special offer products if available
    if ($special_category && $special_category->count > 0) {
    ?>
        <div class="special-offer-products-container">
            <div class="special-offer-title">
                <h2>Limited Time Special Offer</h2>
            </div>
            <div class="special-products-wrapper">
                <?php
                // Query special offer products
                $args = [
                    'category' => ['special-offer'],
                    'orderby' => 'date',
                    'order' => 'DESC',
                ];

                $products = wc_get_products($args);

                foreach ($products as $product) {
                    $product_id = $product->get_id();
                    $is_in_cart = my_custom_cart_contains($product_id);
                ?>
                    <div class="special-products-single <?php echo esc_attr($product_id); ?>">
                        <p class="special-product-name"><?php echo esc_html($product->get_name()); ?>
                            <strong>
                                <?php
                                if (class_exists('WC_Subscriptions_Product') && WC_Subscriptions_Product::is_subscription($product_id)) {
                                    echo WC_Subscriptions_Product::get_price_string($product_id);
                                } elseif ($product->is_on_sale()) {
                                    // WooCommerce will automatically add the currency symbol
                                    echo "<del>" . wc_price($product->get_regular_price()) . "</del> " . wc_price($product->get_sale_price());
                                } else {
                                    echo wc_price($product->get_regular_price());
                                }
                                ?>
                            </strong>
                        </p>
                        <input type="checkbox" class="xs-add-to-cart-button" data-product-id="<?php echo esc_attr($product_id); ?>" <?php checked($is_in_cart); ?>>
                    </div>
                <?php } ?>
                <p>Note: Not applicable for Regulated and Professional Courses.</p>
            </div>
        </div>
    <?php
    }
}

add_action('woocommerce_after_cart_table', 'cross_sale_action_woocommerce_after_cart_table', 10);

/*
 * jQuery to handle adding/removing special offer products to the cart
 */
add_action('wp_footer', 'my_custom_wc_button_script');
function my_custom_wc_button_script()
{
    ?>
    <script>
        jQuery(document).ready(function($) {
            var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')); ?>";

            $(document).on('click', '.xs-add-to-cart-button', function() {
                var $this = $(this);
                var productId = $this.data("product-id");

                if ($this.is(':disabled')) {
                    return;
                }

                if ($this.prop("checked")) {
                    $.post(ajaxurl, {
                        action: 'xs_add_to_cart',
                        product_id: productId
                    }, function(response) {
                        if (response.success) {
                            $this.prop("checked", true).addClass("on-cart");
                            $(document.body).trigger('added_to_cart');
                        }
                    }, 'json');
                } else {
                    $('a.remove[data-product_id="' + productId + '"]').trigger('click');
                    $this.prop("checked", false);
                    $(document.body).trigger('added_to_cart');
                }
            });
        });
    </script>
<?php
}

/*
 * AJAX handler to add product to cart
 */
add_action('wp_ajax_xs_add_to_cart', 'xs_add_to_cart');
add_action('wp_ajax_nopriv_xs_add_to_cart', 'xs_add_to_cart');

function xs_add_to_cart()
{
    $response = ['success' => false, 'message' => ''];

    if (!function_exists('WC')) {
        $response['message'] = "WooCommerce not installed.";
    } elseif (empty($_POST['product_id'])) {
        $response['message'] = "No product ID provided.";
    } else {
        $product_id = absint($_POST['product_id']);

        if (my_custom_cart_contains($product_id)) {
            $response['message'] = "Product already in cart.";
        } else {
            $cart = WC()->cart;
            $response['success'] = $cart->add_to_cart($product_id);

            $response['message'] = $response['success'] ? "Product added to cart." : "Product could not be added.";
        }
    }

    wp_send_json($response);
    wp_die();
}

/*
 * Check if a product is already in the cart
 */
function my_custom_cart_contains($product_id)
{
    $cart = WC()->cart;

    foreach ($cart->get_cart() as $cart_item) {
        if ($cart_item['product_id'] == $product_id) {
            return true;
        }
    }

    return false;
}

/*
================================================================
===================== Cross Sale End ======================
================================================================
*/
