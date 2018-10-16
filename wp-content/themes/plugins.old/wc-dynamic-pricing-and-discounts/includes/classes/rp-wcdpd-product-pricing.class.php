<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Methods related to product pricing rules
 *
 * @class RP_WCDPD_Product_Pricing
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Product_Pricing')) {

class RP_WCDPD_Product_Pricing
{

    /**
     * Remove cart items that are not affected by specific pricing rules
     *
     * @access public
     * @param array $cart_items
     * @param array $rules
     * @return array
     */
    public static function filter_items_by_rules($cart_items, $rules)
    {
        $filtered = array();
        $keys_added = array();

        // Iterate over cart items
        foreach ($cart_items as $cart_item_key => $cart_item) {

            // Cart item already in list
            if (in_array($cart_item_key, $keys_added, true)) {
                continue;
            }

            // Filter rules by conditions to leave those that apply to current cart item
            $current_rules = RP_WCDPD_Conditions::filter_rules($rules, array(
                'cart_item' => $cart_item,
            ));

            // Add to results array if at least one rule applies to this cart item
            if (!empty($current_rules)) {
                $filtered[$cart_item_key] = $cart_item;
                $keys_added[] = $cart_item_key;
            }
        }

        return $filtered;
    }

    /**
     * Get product pricing rules applicable to product
     *
     * Note: this must only be used in promotion tools when product is not yet in cart
     *
     * @access public
     * @param object $product
     * @param array $methods
     * @param bool $skip_cart_conditions
     * @return array
     */
    public static function get_applicable_rules_for_product($product, $methods = null, $skip_cart_conditions = false)
    {
        // Maybe exclude products already on sale
        if (RP_WCDPD_Settings::get('product_pricing_sale_price_handling') === 'exclude' && RP_WCDPD_Product_Pricing::product_is_on_sale($product)) {
            return false;
        }

        // Get product pricing rules
        if ($rules = RP_WCDPD_Rules::get('product_pricing', array('methods' => $methods))) {

            // Get product id
            $product_id = RightPress_WC_Legacy::product_get_id($product);

            // Product details for condition checks
            $filter_config = array(
                'item_id'               => $product->is_type('variation') ? RightPress_WC_Legacy::product_variation_get_parent_id($product) : $product_id,
                'child_id'              => $product->is_type('variation') ? $product_id : null,
                'variation_attributes'  => $product->is_type('variation') ? $product->get_variation_attributes() : null,
                'skip_cart_conditions'  => $skip_cart_conditions,
            );

            // Get exclude rules
            $exclude_rules = RP_WCDPD_Rules::get('product_pricing', array('methods' => array('exclude')));

            // Check product against exclude rules
            if (empty($exclude_rules) || !RP_WCDPD_Conditions::exclude_item_by_rules($exclude_rules, $filter_config)) {

                // Filter rules by conditions
                if ($rules = RP_WCDPD_Conditions::filter_rules($rules, $filter_config)) {

                    // Filter rules by exclusivity settings
                    if ($mockup_adjustments = RP_WCDPD_Rules::filter_by_exclusivity('product_pricing', RP_WCDPD_Product_Pricing::get_mockup_adjustments($rules))) {

                        // Exctract rules and return
                        return wp_list_pluck($mockup_adjustments, 'rule');
                    }
                }
            }
        }

        // No rules found
        return array();
    }

    /**
     * Get mockup adjustments for use in rule exclusivity checks
     *
     * Wraps rules into arrays and calculates reference amount if needed
     *
     * @access public
     * @param array $rules
     * @return array
     */
    public static function get_mockup_adjustments($rules)
    {
        $adjustments = array();

        // Check if reference amount is needed
        $selection_method = RP_WCDPD_Settings::get('product_pricing_rule_selection_method');
        $calculate_reference_amount = in_array($selection_method, array('smaller_price', 'bigger_price'), true);

        // Iterate over rules
        foreach ($rules as $rule) {

            // Wrap rule
            $adjustment = array(
                'rule' => $rule,
            );

            // Maybe calculate reference amount
            if ($calculate_reference_amount) {
                $adjustment['reference_amount'] = 0;
            }

            // Add to main array
            $adjustments[] = $adjustment;
        }

        return $adjustments;
    }

    /**
     * Safe check for is on sale
     *
     * @access public
     * @param object $product
     * @return bool
     */
    public static function product_is_on_sale($product)
    {
        // Special case
        if (RP_WCDPD_Settings::get('promo_display_price_override')) {

            if (RightPress_Helper::wc_version_gte('3.0')) {
                return $product->is_on_sale('edit');
            }
            else {

                // Note: do we need to handle grouped and variable products?
                if ($product->is_type('grouped') || $product->is_type('variable')) {
                    return false;
                }

                // Note: review this later
                $price = RightPress_WC_Legacy::product_get_price($product, 'edit');
                $sale_price = RightPress_WC_Legacy::product_get_sale_price($product, 'edit');
                $regular_price = RightPress_WC_Legacy::product_get_regular_price($product, 'edit');
		return ($sale_price !== $regular_price && $sale_price === $price);
            }
        }
        // Regular handling
        else {
            return $product->is_on_sale();
        }
    }



}
}
