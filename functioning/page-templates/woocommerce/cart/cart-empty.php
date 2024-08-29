<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
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

	.woocommerce .button.wc-backward {
		background: transparent;
		color: #f16126;
		border-radius: 3px;
		padding: 10px 30px;
		border: 1px solid #f16126;
		width: auto;
		cursor: pointer;
		transition: all 0.3s ease;
	}

	.woocommerce .button.wc-backward:hover {
		color: #fff;
		background: #f16126;
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
			/*
			 * @hooked wc_empty_cart_message - 10
			 */
			do_action('woocommerce_cart_is_empty');

			if (wc_get_page_id('shop') > 0): ?>
				<p class="return-to-shop">
					<a class="button wc-backward<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"
						href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>">
						<?php
						/**
						 * Filter "Return To Shop" text.
						 *
						 * @since 4.6.0
						 * @param string $default_text Default text.
						 */
						echo esc_html(apply_filters('woocommerce_return_to_shop_text', __('Return to shop', 'woocommerce')));
						?>
					</a>
				</p>
			<?php endif; ?>
		</div>
	</div>
</div>