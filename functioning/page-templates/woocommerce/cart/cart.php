<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined('ABSPATH') || exit;
?>
<style>
	.entry-header {
		display: none;
	}

	.entry-content {
		margin: 0;
	}

	.woocommerce-message {
		border-top-color: #f16126;
	}

	.woocommerce-message::before,
	.woocommerce-message a {
		color: #f16126;
	}

	.woocommerce-info {
		border-top-color: #f16126;
	}

	.woocommerce-info::before,
	.woocommerce-info a {
		color: #f16126;
	}

	.woocommerce-cart table.cart img {
		width: auto;
	}

	.woocommerce a.checkout-button.button {
		background: #fff;
		border-radius: 3px;
		padding: 10px 30px;
		color: #fff;
		background: #f16126;
		border: 1px solid #f16126;
		width: auto;
		cursor: pointer;
		text-align: center;
		transition: all 0.3s ease;
	}

	.woocommerce a.checkout-button.button:hover {
		color: #f16126;
		background: transparent;
	}

	.woocommerce .section-wrapper button.button {
		color: #fff !important;
		background: #f16126;
		border: 1px solid #f16126;
	}

	.woocommerce .section-wrapper button.button:hover {
		color: #f16126 !important;
		background: transparent;
	}

	.woocommerce .section-wrapper button.button[name="apply_coupon"] {
		color: #f16126 !important;
		background: transparent;
		border: 1px solid #f16126;
		white-space: nowrap;
	}

	.woocommerce .section-wrapper button.button[name="apply_coupon"]:hover {
		color: #fff !important;
		background: #f16126;
	}

	.shop-cart .section-wrapper .cart-top table thead tr th:nth-child(3) {
		text-align: left;
	}

	@media (max-width: 575px) {
		.woocommerce .section-wrapper .cart-checkout > * {
			width: 100% !important;
		}
		.woocommerce .section-wrapper .cart-checkout {
			gap: 10px;
		}

		.woocommerce .section-wrapper button.button[name="apply_coupon"] {
			font-size: 14px;
		}
	}
</style>
<!-- Page Header section start here -->
<div class="pageheader-section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="pageheader-content text-center">
					<h2>Shop Cart</h2>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="/">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page">Cart Page</li>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Page Header section ending here -->
<div class="shop-cart padding-tb">
	<div class="container">
		<div class="section-wrapper">
			<?php
			do_action('woocommerce_before_cart'); ?>

			<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
				<div class="cart-top">
					<?php do_action('woocommerce_before_cart_table'); ?>
					<table>
						<thead>
							<tr>
								<th class="product-name cat-product"><?php esc_html_e('Product', 'woocommerce'); ?></th>
								<th class="product-price cat-price"><?php esc_html_e('Price', 'woocommerce'); ?></th>
								<th class="product-quantity cat-quantity">
									<?php esc_html_e('Quantity', 'woocommerce'); ?>
								</th>
								<th class="product-subtotal cat-toprice"><?php esc_html_e('Total', 'woocommerce'); ?>
								</th>
								<th class="product-remove cat-edit"><?php esc_html_e('Edit', 'woocommerce'); ?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php do_action('woocommerce_before_cart_contents'); ?>

							<?php
							foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
								$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
								$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
								/**
								 * Filter the product name.
								 *
								 * @since 2.1.0
								 * @param string $product_name Name of the product in the cart.
								 * @param array $cart_item The product in the cart.
								 * @param string $cart_item_key Key for the product in the cart.
								 */
								$product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);

								if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
									$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
									?>
									<tr
										class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

										<td class="product-name product-item cat-product"
											data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
											<div class="p-thumb">
												<?php
												$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

												if (!$product_permalink) {
													echo $thumbnail; // PHPCS: XSS ok.
												} else {
													printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
												}
												?>
											</div>
											<div class="p-content">
												<?php
												if (!$product_permalink) {
													echo wp_kses_post($product_name . '&nbsp;');
												} else {
													/**
													 * This filter is documented above.
													 *
													 * @since 2.1.0
													 */
													echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
												}

												do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

												// Meta data.
												echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.
										
												// Backorder notification.
												if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
													echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
												}
												?>
											</div>

										</td>

										<td class="product-price cat-price"
											data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
											<?php
											echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
											?>
										</td>

										<td class="product-quantity cat-quantity"
											data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">

											<?php
											if ($_product->is_sold_individually()) {
												$min_quantity = 1;
												$max_quantity = 1;
											} else {
												$min_quantity = 0;
												$max_quantity = $_product->get_max_purchase_quantity();
											}

											$product_quantity = woocommerce_quantity_input(
												array(
													'input_name' => "cart[{$cart_item_key}][qty]",
													'input_value' => $cart_item['quantity'],
													'max_value' => $max_quantity,
													'min_value' => $min_quantity,
													'product_name' => $product_name,
												),
												$_product,
												false
											);
											echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
											?>

										</td>

										<td class="product-subtotal cat-toprice"
											data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
											<?php
											echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
											?>
										</td>
										<td class="product-remove cat-edit">
											<?php
											echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												'woocommerce_cart_item_remove_link',
												sprintf(
													'<a href="%s" aria-label="%s" data-product_id="%s" data-product_sku="%s"><img src="%s/assets/images/shop/del.png" alt="product"></a>',
													esc_url(wc_get_cart_remove_url($cart_item_key)),
													/* translators: %s is the product name */
													esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
													esc_attr($product_id),
													esc_attr($_product->get_sku()),
													esc_url(get_template_directory_uri())
												),
												$cart_item_key
											);
											?>
										</td>
									</tr>
									<?php
								}
							}
							?>

							<?php do_action('woocommerce_cart_contents'); ?>

						</tbody>
					</table>
				</div>
				<div class="cart-bottom">
					<div class="actions cart-checkout-box">
						<?php if (wc_coupons_enabled()) { ?>
							<div class="coupon">
								<label for="coupon_code"
									class="screen-reader-text"><?php esc_html_e('Coupon:', 'woocommerce'); ?></label>
								<input type="text" name="coupon_code" class="input-text cart-page-input-text"
									id="coupon_code" value=""
									placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>" />
								<button type="submit"
									class="button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"
									name="apply_coupon"
									value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"><?php esc_html_e('Apply coupon', 'woocommerce'); ?></button>
								<?php do_action('woocommerce_cart_coupon'); ?>
							</div>
						<?php } ?>

						<div class="cart-checkout">
							<button type="submit"
								class="button a2n_btn<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"
								name="update_cart"
								value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>
							<?php do_action('woocommerce_proceed_to_checkout'); ?>
						</div>

						<?php do_action('woocommerce_cart_actions'); ?>

						<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
					</div>
					<?php do_action('woocommerce_after_cart_contents'); ?>

					<?php do_action('woocommerce_after_cart_table'); ?>

					<?php do_action('woocommerce_before_cart_collaterals'); ?>
					<div class="cart-collaterals shiping-box">
						<?php
						/**
						 * Cart collaterals hook.
						 *
						 * @hooked woocommerce_cross_sell_display
						 * @hooked woocommerce_cart_totals - 10
						 */
						do_action('woocommerce_cart_collaterals');
						?>
					</div>


					<?php do_action('woocommerce_after_cart'); ?>
				</div>
			</form>


		</div>
	</div>
</div>