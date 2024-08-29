<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
	exit;
}
?>
<style>
	.entry-header {
		display: none;
	}

	.entry-content {
		margin: 0;
	}

	.woocommerce-info {
		border-top-color: #f16126;
	}

	.woocommerce-info::before,
	.woocommerce-info a {
		color: #f16126;
	}

	.woocommerce-message {
		border-top-color: #f16126;
	}

	.woocommerce-message::before,
	.woocommerce-message a {
		color: #f16126;
	}

	.woocommerce-checkout #payment {
		background: transparent;
	}

	.woocommerce table.shop_table {
		border: 0;
	}

	h3#order_review_heading {
		padding: 12px 0 0 12px;
	}

	.woocommerce #payment #place_order,
	.woocommerce .section-wrapper button.button {
		color: #fff;
		background: #f16126;
		border: 1px solid #f16126;
	}
	.woocommerce #payment #place_order:hover,
	.woocommerce .section-wrapper button.button:hover {
		color: #f16126;
		background: transparent;
	}
</style>
<!-- Page Header section start here -->
<div class="pageheader-section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="pageheader-content text-center">
					<h2>Checkout</h2>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb justify-content-center">
							<li class="breadcrumb-item"><a href="/">Home</a></li>
							<li class="breadcrumb-item active" aria-current="page">Checkout Page</li>
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
			do_action('woocommerce_before_checkout_form', $checkout);

			// If checkout registration is disabled and not logged in, the user cannot checkout.
			if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
				echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
				return;
			}

			?>

			<form name="checkout" method="post" class="checkout woocommerce-checkout"
				action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

				<?php if ($checkout->get_checkout_fields()): ?>

					<?php do_action('woocommerce_checkout_before_customer_details'); ?>

					<div class="cart-top p-3">
						<div class="col2-set" id="customer_details">
							<div class="col-1">
								<?php do_action('woocommerce_checkout_billing'); ?>
							</div>

							<div class="col-2">
								<?php do_action('woocommerce_checkout_shipping'); ?>
							</div>
						</div>
					</div>

					<?php do_action('woocommerce_checkout_after_customer_details'); ?>

				<?php endif; ?>

				<?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

				<?php do_action('woocommerce_checkout_before_order_review'); ?>

				<div id="order_review" class="woocommerce-checkout-review-order cart-bottom">
					<h3 id="order_review_heading"><?php esc_html_e('Your order', 'woocommerce'); ?></h3>
					<?php do_action('woocommerce_checkout_order_review'); ?>
				</div>

				<?php do_action('woocommerce_checkout_after_order_review'); ?>

			</form>

			<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
		</div>
	</div>
</div>