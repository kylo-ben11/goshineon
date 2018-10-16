<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Controller_Methods')) {
    include_once('rp-wcdpd-controller-methods.class.php');
}

/**
 * Product Pricing method controller
 *
 * @class RP_WCDPD_Controller_Methods_Product_Pricing
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Controller_Methods_Product_Pricing')) {

class RP_WCDPD_Controller_Methods_Product_Pricing extends RP_WCDPD_Controller_Methods
{
    protected $context = 'product_pricing';

    // Track which rules were processed for cart items
    protected $rules_processed = array();

    // Singleton instance
    protected static $instance = false;

    /**
     * Singleton control
     */
    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        // Apply pricing rules to cart
        add_action('woocommerce_cart_loaded_from_session', array($this, 'cart_loaded_from_session'), 100);
        add_action('woocommerce_before_calculate_totals', array($this, 'apply'), 100);

        // Maybe change cart item display price
        add_filter('woocommerce_cart_item_price', array($this, 'cart_item_price'), 10, 3);
    }

    /**
     * Cart loaded from session
     *
     * @access public
     * @param object $cart
     * @return void
     */
    public function cart_loaded_from_session($cart)
    {
        if (!defined('RP_WCDPD_CART_LOADED_FROM_SESSION')) {
            define('RP_WCDPD_CART_LOADED_FROM_SESSION', true);
        }

        // Iterate over cart items
        foreach ($cart->cart_contents as $cart_item_key => $cart_item) {

            // Add flag that indicates that cart item's product is in cart
            $cart->cart_contents[$cart_item_key]['data']->rp_wcdpd_in_cart = true;

            // Unset any previously set adjustment meta data
            if (isset($cart->cart_contents[$cart_item_key]['rp_wcdpd_data'])) {
                unset($cart->cart_contents[$cart_item_key]['rp_wcdpd_data']);
            }
        }

        // Apply pricing rules to cart
        $this->apply($cart);

        // Force calculation of totals so that they are updated in mini-cart
        if (defined('WC_DOING_AJAX') && WC_DOING_AJAX && !empty($_REQUEST['wc-ajax']) && $_REQUEST['wc-ajax'] === 'get_refreshed_fragments') {
            $cart->subtotal = false;
        }
    }

    /**
     * Apply pricing rules to cart
     *
     * @access public
     * @param object $cart
     * @return void
     */
    public function apply($cart = null)
    {
        // Do not run before woocommerce_cart_loaded_from_session is run
        if (!defined('RP_WCDPD_CART_LOADED_FROM_SESSION')) {
            return;
        }

        // Load cart if not passed in
        if (!is_a($cart, 'WC_Cart')) {
            global $woocommerce;
            $cart = $woocommerce->cart;
        }

        // Sort cart items by price from cheapest
        $cart_items = RP_WCDPD_WC_Cart::sort_cart_items_by_price($cart->cart_contents, 'ascending');

        // Apply exclude rules and allow developers to exclude items
        $cart_items = apply_filters('rp_wcdpd_product_pricing_cart_items', $cart_items);

        // Maybe exclude items that are already on sale
        if (RP_WCDPD_Settings::get('product_pricing_sale_price_handling') === 'exclude') {
            $cart_items = $this->exclude_cart_items_already_on_sale($cart_items);
        }

        // Get applicable product pricing adjustments
        $adjustments = $this->get_applicable_adjustments($cart_items);

        // Iterate over adjustments for cart items
        foreach ($adjustments as $cart_item_key => $cart_item_adjustments) {

            // Get cart item quantity
            $cart_item_quantity = $cart->cart_contents[$cart_item_key]['quantity'];

            // Get base price
            $base_price = RP_WCDPD_Pricing::get_product_base_price($cart->cart_contents[$cart_item_key]['data']);

            // Get initial cart item price and set to cart item
            $initial_price = apply_filters('rp_wcdpd_product_pricing_initial_price', $base_price, $cart->cart_contents[$cart_item_key]);
            $cart->cart_contents[$cart_item_key]['rp_wcdpd_data']['initial_price'] = $initial_price;

            // Store individual price for each quantity unit
            $prices = RP_WCDPD_Controller_Methods_Product_Pricing::generate_prices_array($base_price, $cart_item_quantity);

            // Filter cart item adjustments by rule selection method and exclusivity settings
            $cart_item_adjustments = RP_WCDPD_Rules::filter_by_exclusivity($this->context, $cart_item_adjustments);

            // Apply all remaining cart item adjustments
            foreach ($cart_item_adjustments as $rule_uid => $adjustment) {

                // Apply adjustment to prices of current cart item
                if ($method = $this->get_method($adjustment['rule'])) {
                    $prices = call_user_func($method['apply_adjustment_to_prices_callback'], $prices, $adjustment);
                }

                // Sort prices so that units with least discounts come up first for the next rule
                RP_WCDPD_Helper::stable_uasort($prices, array('RP_WCDPD_Controller_Methods_Product_Pricing', 'sort_prices_by_adjustment_count_asc'));
            }

            // Get final WooCommerce product price without any adjustments to use for items that were not adjusted
            // Note: We can't use base price as it depends on setting product_pricing_sale_price_handling
            $wc_price = RightPress_WC_Legacy::product_get_price($cart->cart_contents[$cart_item_key]['data'], 'edit');

            // Calculate average price from prices array
            $average_price = RP_WCDPD_Controller_Methods_Product_Pricing::get_price_from_prices_array($prices, $wc_price, $cart_item_quantity, $cart->cart_contents[$cart_item_key]['data'], $cart->cart_contents[$cart_item_key]);

            // Set average price to cart item
            RightPress_WC_Legacy::product_set_price($cart->cart_contents[$cart_item_key]['data'], $average_price);

            // Set extra data
            foreach ($cart_item_adjustments as $rule_uid => $adjustment) {
                $cart->cart_contents[$cart_item_key]['rp_wcdpd_data']['adjustments'][$rule_uid] = array();
            }
        }
    }

    /**
     * Maybe change cart item display price
     *
     * @access public
     * @param string $price_html
     * @param array $cart_item
     * @param string $cart_item_key
     * @return string
     */
    public function cart_item_price($price_html, $cart_item, $cart_item_key)
    {
        // Check if pricing was adjusted for this cart item
        if (isset($cart_item['rp_wcdpd_data']['initial_price'])) {

            // Get adjusted price
            $adjusted_price = RP_WCDPD_WC_Cart::get_cart_item_price_for_display($cart_item);

            // Get initial price including potential tax
            $initial_price = RP_WCDPD_WC_Cart::get_cart_item_price_for_display($cart_item, $cart_item['rp_wcdpd_data']['initial_price']);

            // Adjusted price is lower than initial price
            if ($adjusted_price < $initial_price && RP_WCDPD_Settings::get('product_pricing_display_regular_price')) {
                $price_html = '<del>' . wc_price($initial_price) . '</del> <ins>' . wc_price($adjusted_price) . '</ins>';
            }
        }

        return $price_html;
    }

    /**
     * Exclude cart items that are already on sale
     *
     * @access public
     * @param array $cart_items
     * @return array
     */
    public function exclude_cart_items_already_on_sale($cart_items)
    {
        foreach ($cart_items as $cart_item_key => $cart_item) {
            if ($cart_item['data']->is_on_sale()) {
                unset($cart_items[$cart_item_key]);
            }
        }

        return $cart_items;
    }

    /**
     * Check if rule is already processed for cart item
     * Mark processed if it is not processed yet
     *
     * @access public
     * @param string $rule_key
     * @param string $cart_item_key
     * @return bool
     */
    public static function is_already_processed($rule_key, $cart_item_key)
    {
        // Get instance
        $instance = RP_WCDPD_Controller_Methods_Product_Pricing::get_instance();

        // Rule already processed
        if (isset($instance->rules_processed[$rule_key]) && in_array($cart_item_key, $instance->rules_processed[$rule_key], true)) {
            return true;
        }
        // Rule not processed yet - mark as processed
        else {
            $instance->rules_processed[$rule_key][] = $cart_item_key;
            return false;
        }
    }

    /**
     * Sort prices by adjustment count ascending
     *
     * @access public
     * @param object $a
     * @param object $b
     * @return array
     */
    public static function sort_prices_by_adjustment_count_asc($a, $b)
    {
        $count_a = count($a['adjustments']);
        $count_b = count($b['adjustments']);

        // Compare
        if ($count_a > $count_b) {
            return 1;
        }
        else if ($count_a < $count_b) {
            return -1;
        }
        else {
            return 0;
        }
    }

    /**
     * Generate prices array for a cart item
     *
     * @access public
     * @param float $base_price
     * @param int $cart_item_quantity
     * @return array
     */
    public static function generate_prices_array($base_price, $cart_item_quantity)
    {
        $prices = array();

        // Fix base price
        $base_price = (float) $base_price;

        // Iterate over quantity units
        for ($i = 1; $i <= $cart_item_quantity; $i++) {
            $prices[$i] = array(
                'original'      => $base_price,
                'adjusted'      => $base_price,
                'adjustments'   => array(),
            );
        }

        return $prices;
    }

    /**
     * Calculate average price from prices array
     *
     * @access public
     * @param array $prices
     * @param float $wc_price
     * @param int $cart_item_quantity
     * @param object $product
     * @param array $cart_item
     * @return float
     */
    public static function get_price_from_prices_array($prices, $wc_price, $cart_item_quantity, $product, $cart_item = null)
    {
        // Calculate subtotal
        $subtotal = 0.0;

        // Iterate over prices
        foreach ($prices as $price) {

            // Price was adjusted
            if (!empty($price['adjustments'])) {
                $subtotal += $price['adjusted'];
            }
            // Price was not adjusted
            else {
                $subtotal += $wc_price;
            }
        }

        // Calculate average price
        $average_price = $subtotal / $cart_item_quantity;

        // Round cart item price so that we end up with correct cart line subtotal
        $average_price = RP_WCDPD_Pricing::round($average_price);

        // Allow developers to override
        $average_price = apply_filters('rp_wcdpd_product_pricing_adjusted_price', $average_price, $prices, $product, $cart_item);

        // Return price
        return $average_price;
    }





}

RP_WCDPD_Controller_Methods_Product_Pricing::get_instance();

}
