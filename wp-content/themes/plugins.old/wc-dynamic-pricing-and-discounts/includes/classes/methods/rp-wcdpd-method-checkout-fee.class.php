<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Method')) {
    include_once('rp-wcdpd-method.class.php');
}

/**
 * Checkout Fee Method
 *
 * @class RP_WCDPD_Method_Checkout_Fee
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Method_Checkout_Fee')) {

abstract class RP_WCDPD_Method_Checkout_Fee extends RP_WCDPD_Method
{
    protected $context = 'checkout_fees';

    /**
     * Get cart subtotal
     *
     * @access public
     * @return float
     */
    public function get_cart_subtotal()
    {
        return RightPress_Helper::get_wc_cart_subtotal(false);
    }

    /**
     * Get unique fee label
     *
     * @access public
     * @param string $label
     * @param object $cart
     * @return string
     */
    public function get_unique_fee_label($label, $cart)
    {
        $original_label = $label;
        $i = 2;

        do {

            // Assume fee label is unique until we check this
            $is_unique = true;

            // Get fee id from fee label
            $fee_id = sanitize_title($label);

            // Check if fee id is unique
            foreach ($cart->get_fees() as $cart_fee) {

                // Label is not unique, try to append an integer to it
                if ($cart_fee->id === $fee_id) {
                    $label = $original_label . apply_filters('rp_wcdpd_duplicate_fee_label_suffix', (' ' . $i));
                    $is_unique = false;
                    $i++;
                    break;
                }
            }
        }
        while (!$is_unique);

        // Label must be unique by now
        return $label;
    }


}
}
