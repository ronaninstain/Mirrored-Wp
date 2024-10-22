<?php
$courseID = get_the_ID();
$is_live_class_enabled = get_post_meta($courseID, '_live_classes_meta_key', true);

/* Start */

if ($is_live_class_enabled === '1') {
    // Retrieve the associated product ID
    $product_ID = get_post_meta($courseID, 'vibe_product', true);

    // Check if product ID is valid
    if ($product_ID) {
        // Get the WooCommerce product
        $product = wc_get_product($product_ID);

        // Check if the product is a variable product
        if ($product && $product->is_type('variable')) {
            // Get the available variations
            $available_variations = $product->get_available_variations();

            // Get the attributes of the product
            $attributes = $product->get_variation_attributes();

            // Output the form for selecting variations
            if (!empty($available_variations)) {
?>
                <form class="variations_form cart" method="post" enctype="multipart/form-data">
                    <div class="variation-selection">
                        <?php foreach ($attributes as $attribute_name => $options) { ?>
                            <div class="attribute-group">
                                <!-- <label><?php echo wc_attribute_label($attribute_name); ?>:</label> -->
                                <?php foreach ($options as $option) { ?>
                                    <?php
                                    // Find the matching variation for this option
                                    foreach ($available_variations as $variation) {
                                        $variation_attributes = $variation['attributes'];
                                        $price_html = $variation['price_html']; // Price of the variation

                                        if (isset($variation_attributes['attribute_' . sanitize_title($attribute_name)]) && $variation_attributes['attribute_' . sanitize_title($attribute_name)] == $option) {
                                    ?>
                                            <label>
                                                <input type="radio" name="attribute_<?php echo esc_attr(sanitize_title($attribute_name)); ?>" value="<?php echo esc_attr($option); ?>" required>
                                                <?php echo esc_html($option); ?> - <?php echo wp_kses_post($price_html); ?>
                                            </label>
                                            <br />
                                    <?php
                                        }
                                    }
                                    ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Hidden fields for variation and product details -->
                    <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product_ID); ?>">
                    <input type="hidden" name="variation_id" id="variation_id" value="">

                    <!-- "Take This Course" Button -->
                    <a href="#" id="take-this-course" class="button alt">
                        <?php esc_html_e('Take This Course', 'woocommerce'); ?>
                    </a>
                </form>

                <script type="text/javascript">
                    jQuery(document).ready(function($) {
                        // Variable to hold selected variation ID
                        var variationID = '';

                        // Handle radio button changes and update the variation ID
                        $('input[type=radio]').on('change', function() {
                            var selectedAttributes = {};
                            $('input[type=radio]:checked').each(function() {
                                selectedAttributes[$(this).attr('name')] = $(this).val();
                            });

                            // Find the matching variation ID
                            var availableVariations = <?php echo wp_json_encode($available_variations); ?>;
                            $.each(availableVariations, function(index, variation) {
                                var match = true;
                                $.each(variation.attributes, function(attr_name, attr_value) {
                                    if (selectedAttributes[attr_name] !== attr_value) {
                                        match = false;
                                        return false;
                                    }
                                });
                                if (match) {
                                    variationID = variation.variation_id;
                                    return false; // Break loop
                                }
                            });
                        });

                        // Handle the "Take This Course" button click
                        $('#take-this-course').on('click', function(e) {
                            e.preventDefault();

                            if (variationID) {
                                // Redirect to the cart page with the selected variation
                                window.location.href = '<?php echo site_url(); ?>/cart/?add-to-cart=<?php echo $product_ID; ?>&variation_id=' + variationID;
                            } else {
                                alert('<?php echo esc_js(__('Please select a variation before proceeding.', 'woocommerce')); ?>');
                            }
                        });
                    });
                </script>

    <?php
            } else {
                echo '<p>' . esc_html__('This course does not have variable product options.', 'woocommerce') . '</p>';
            }
        }
    }
} else {
    ?>
    <div class="course-price">
        <?php echo bp_course_credits(); ?>
    </div>
<?php
}

/* End */


if (!$is_live_class_enabled === '1') {
    if (function_exists('membeship_button')) {
        //oiopub_banner_zone(1, 'center');
        $course_id = get_the_ID();
        membeship_button($course_id);
    } else {
        the_course_button();
    }
}
