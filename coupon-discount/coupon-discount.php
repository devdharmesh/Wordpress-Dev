<?php

/**
 * Plugin Name: Coupon Discount
 * Description: A WooCommerce add-on that applies a special coupon for discounts.
 * Version: 1.0
 * Author: Dharmesh Lakum
 */


if (!defined('ABSPATH')) {
    exit;
}

class CouponDiscountWooCommerceAddon
{
    protected $coupon_code = "COUDOUBLE";
    protected $coupon_amount = 0.5;

    public function __construct()
    {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        add_action('init', array($this, 'define_custom_coupon'));

        add_action('woocommerce_applied_coupon', array($this, 'applyCoupon'), 10, 1);
        add_action('woocommerce_before_calculate_totals', array($this, 'calculate_totals'), 10, 1);
        add_action('woocommerce_removed_coupon', array($this, 'removeCoupon'), 10, 1);
    }

    public function activate()
    {
        // Activation logic (if any)
    }

    public function deactivate()
    {
        // Deactivation logic (if any)
    }

    /**
     * Defines a custom coupon.
     *
     * This function creates a new WC_Coupon object with the provided coupon code,
     * sets its discount type to 'percent', sets the amount to 0, and saves it.
     *
     * @return void
     */
    public function define_custom_coupon()
    {
        // Create a new WC_Coupon object with the provided coupon code
        $coupon = new WC_Coupon($this->coupon_code);

        // Set the discount type to 'percent'
        $coupon->set_discount_type('percent');

        // Set the amount to 0
        $coupon->set_amount(0);

        // Save the coupon
        $coupon->save();
    }

    /**
     * Applies a coupon to the cart items.
     *
     * @param string $coupon The coupon code to apply.
     */
    public function applyCoupon($coupon)
    {
        // Convert the coupon code to lowercase for comparison.
        $coupon = strtoupper($coupon);

        // If the coupon matches the stored coupon code.
        if ($coupon == $this->coupon_code) {
            // Get the cart items.
            $cartItems = WC()->cart->get_cart();

            // If there are cart items.
            if ($cartItems) {
                // Loop through the cart items.
                foreach ($cartItems as $cartItem) {
                    // Get the product ID and data.
                    $productId = $cartItem['product_id'];
                    $product = $cartItem['data'];

                    // Get the product price.
                    $productPrice = $product->get_price();

                    // Add an extra product to the cart with custom price.
                    WC()->cart->add_to_cart(
                        $productId,
                        1,
                        0,
                        array(),
                        array('coupon_custom_price' => ($productPrice * $this->coupon_amount))
                    );
                }
            }
        }
    }

    /**
     * Function to remove a specific coupon from the cart items.
     *
     * @param string $coupon The coupon to be removed.
     */
    public function removeCoupon($coupon)
    {
        // Convert coupon to lowercase for comparison.
        $coupon = strtoupper($coupon);

        // Check if the coupon matches the class coupon code.
        if ($coupon == $this->coupon_code) {
            // Fetch cart items.
            $cartItems = WC()->cart->get_cart();

            // If cart items exist, loop through them.
            if ($cartItems) {
                foreach ($cartItems as $item) {
                    // Check if coupon custom price exists in the cart item.
                    if (array_key_exists('coupon_custom_price', $item)) {
                        // If yes, remove the cart item.
                        WC()->cart->remove_cart_item($item['key']);
                    }
                }
            }
        }
    }

    /**
     * This function calculates the totals for each item in the cart.
     *
     * @param object $cart The cart object.
     */
    public function calculate_totals($cart)
    {
        // Get the items in the cart
        $cart_items = $cart->get_cart();

        // Check if the cart has items
        if ($cart_items) {
            // Loop through each item in the cart
            foreach ($cart_items as $item) {

                // Check if the item has a custom price
                if (array_key_exists('coupon_custom_price', $item)) {
                    // Set the item's price to the custom price
                    $item['data']->set_price($item['coupon_custom_price']);
                }
            }
        }
    }
}

new CouponDiscountWooCommerceAddon();
