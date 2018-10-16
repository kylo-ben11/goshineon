<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Methods related to WooCommerce Cart
 *
 * @class RP_WCDPD_WC_Cart
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_WC_Cart')) {

class RP_WCDPD_WC_Cart
{

    /**
     * Sort cart items by price
     *
     * @access public
     * @param array $cart_items
     * @param string $sort_order
     * @return array
     */
    public static function sort_cart_items_by_price($cart_items = null, $sort_order = 'ascending')
    {
        // Get cart items if not passed in
        if ($cart_items === null) {
            global $woocommerce;
            $cart_items = $woocommerce->cart->cart_contents;
        }

        // Sort cart items
        $sort_comparison_method = 'sort_cart_items_by_price_' . $sort_order . '_comparison';
        RP_WCDPD_Helper::stable_uasort($cart_items, array('RP_WCDPD_WC_Cart', $sort_comparison_method));

        // Return sorted cart items
        return $cart_items;
    }

    /**
     * Sort cart items by price ascending comparison method
     *
     * @access public
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function sort_cart_items_by_price_ascending_comparison($a, $b)
    {
        return RP_WCDPD_WC_Cart::sort_cart_items_by_price_comparison($a, $b, 'ascending');
    }

    /**
     * Sort cart items by price descending comparison method
     *
     * @access public
     * @param mixed $a
     * @param mixed $b
     * @return bool
     */
    public static function sort_cart_items_by_price_descending_comparison($a, $b)
    {
        return RP_WCDPD_WC_Cart::sort_cart_items_by_price_comparison($a, $b, 'descending');
    }

    /**
     * Sort cart items by price comparison method
     *
     * @access public
     * @param mixed $a
     * @param mixed $b
     * @param string $sort_order
     * @return bool
     */
    public static function sort_cart_items_by_price_comparison($a, $b, $sort_order)
    {
        // Get cart item prices
        $price_a = $a['data']->get_price();
        $price_b = $b['data']->get_price();

        // Compare prices
        if ($price_a < $price_b) {
            return ($sort_order === 'ascending' ? -1 : 1);
        }
        else if ($price_a > $price_b) {
            return ($sort_order === 'ascending' ? 1 : -1);
        }
        else {
            return 0;
        }
    }

    /**
     * Calculate our own cart subtotal since in some cases it may not be available yet
     *
     * Note: looks like line_subtotal and line_subtotal_tax are not always set
     * when this is called, however calculating by adding $product->get_price()
     * is not a good idea. Need to monitor if it affects anything from the users
     * perspective.
     *
     * @access public
     * @return float
     */
    public static function calculate_subtotal($include_tax = false)
    {
        global $woocommerce;

        $subtotal = 0;

        // Iterate over cart items
        foreach ($woocommerce->cart->get_cart() as $cart_item) {

            if (isset($cart_item['line_subtotal'])) {

                // Add line subtotal
                $subtotal += $cart_item['line_subtotal'];

                // Add line subtotal tax
                if (isset($cart_item['line_subtotal_tax']) && $include_tax) {
                    $subtotal += $cart_item['line_subtotal_tax'];
                }
            }
        }

        return $subtotal;
    }

    /**
     * Get cart item price for display including or exluding of tax depending on corresponding WooCommerce setting
     *
     * @access public
     * @param array $cart_item
     * @param float $price
     * @return float
     */
    public static function get_cart_item_price_for_display($cart_item, $price = null)
    {
        // Reference cart item product
        $product = $cart_item['data'];

        // Get product price
        if ($price === null) {
            $price = RightPress_WC_Legacy::product_get_price($product);
        }

        // Include or exclude tax
        if (get_option('woocommerce_tax_display_cart') === 'excl') {
            $price = RightPress_WC_Legacy::product_get_price_excluding_tax($product, 1, $price);
        }
        else {
            $price = RightPress_WC_Legacy::product_get_price_including_tax($product, 1, $price);
        }

        return (float) $price;
    }



}
}
