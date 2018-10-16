<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Method_Product_Pricing_Quantity')) {
    include_once('rp-wcdpd-method-product-pricing-quantity.class.php');
}

/**
 * Product Pricing Method: BOGO
 *
 * @class RP_WCDPD_Method_Product_Pricing_Quantity_BOGO
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Method_Product_Pricing_Quantity_BOGO')) {

abstract class RP_WCDPD_Method_Product_Pricing_Quantity_BOGO extends RP_WCDPD_Method_Product_Pricing_Quantity
{
    protected $group_key        = 'bogo';
    protected $group_position   = 40;

    /**
     * Constructor class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->hook_group();
    }

    /**
     * Get group label
     *
     * @access public
     * @return string
     */
    public function get_group_label()
    {
        return __('Buy X Get Y', 'rp_wcdpd');
    }

    /**
     * Get cart items with quantities to adjust
     *
     * @access public
     * @param array $rule
     * @param array $cart_items
     * @return array
     */
    public function get_cart_items_to_adjust($rule, $cart_items = null)
    {
        $adjust = array();

        // Sort cart items by price descending so that we use more expensive items to trigger a rule and leave the cheaper ones to adjust
        $cart_items_desc = RP_WCDPD_WC_Cart::sort_cart_items_by_price($cart_items, 'descending');

        // Group cart item quantities
        $quantity_groups = $this->group_quantities($cart_items_desc, $rule);

        // Prepare target quantity group
        $receive_quantity_group = $this->get_target_quantity_group($rule, $cart_items);

        // Track cart item quantities that can no longer be considered (i.e. were either used to trigger rule or adjustment was applied to them)
        $used_quantities = array();

        // Iterate over quantity groups
        foreach ($quantity_groups as $quantity_group_key => $quantity_group) {

            // Start infinite loop to take care of repetition, will break out of it by ourselves
            while (true) {

                // Get quantities to purchase
                if ($quantities_to_purchase = $this->reserve_quantities($quantity_group, $used_quantities, $rule['bogo_purchase_quantity'], true)) {

                    // Mark quantities used temporary until we check if there are any items to be adjusted based this
                    $temporary_used_quantities = $this->merge_cart_item_quantities($used_quantities, $quantities_to_purchase);

                    // Get quantities to receive at adjusted price
                    if ($quantities_to_receive = $this->reserve_quantities($receive_quantity_group, $temporary_used_quantities, $rule['bogo_receive_quantity'])) {

                        // Mark quantities used
                        $used_quantities = $this->merge_cart_item_quantities($temporary_used_quantities, $quantities_to_receive);

                        // Add to main array
                        $adjust = $this->merge_cart_item_quantities($adjust, $quantities_to_receive);

                        // Maybe repeat this again
                        if ($this->repeat) {
                            continue;
                        }
                    }
                }

                // This loop can only be iterated explicitly, break out of it otherwise
                break;
            }
        }

        return $adjust;
    }

    /**
     * Get other cart items to adjust
     *
     * @access public
     * @param array $rule
     * @param array $cart_items
     * @return array
     */
    public function get_target_quantity_group($rule, $cart_items)
    {
        $matched = array();

        // Get conditions
        $conditions = !empty($rule['bogo_product_conditions']) ? $rule['bogo_product_conditions'] : array();

        // Check each cart item
        foreach ($cart_items as $cart_item_key => $cart_item) {

            // Check condition against current cart item
            if (RP_WCDPD_Conditions::conditions_are_matched($conditions, array('cart_item' => $cart_item))) {
                $matched[$cart_item_key] = $cart_item['quantity'];
            }
        }

        return $matched;
    }

    /**
     * Sort quantity group by cart item prices ascending
     *
     * @access public
     * @param array $quantity_group
     * @param array $cart_items
     * @return array
     */
    public function sort_quantity_group_by_price_ascending($quantity_group, $cart_items)
    {
        return array_merge(array_flip(array_intersect(array_keys($cart_items), array_keys($quantity_group))), $quantity_group);
    }

    /**
     * Apply adjustment to prices
     *
     * @access public
     * @param array $prices
     * @param array $adjustment
     * @return array
     */
    public function apply_adjustment_to_prices($prices, $adjustment)
    {
        // Get receive quantity
        $receive_quantity = !empty($adjustment['receive_quantity']) ? (int) $adjustment['receive_quantity'] : 1;

        // Do not process identical prices multiple times
        $processed = array();

        // Iterate over prices
        foreach ($prices as $index => $price) {

            // Get correct processed price key
            $processed_key = (string) $price['adjusted'];

            // Price not yet processed
            if (!isset($processed[$processed_key])) {

                // Get adjusted amount
                $adjusted = RP_WCDPD_Pricing::adjust_amount($price['adjusted'], $adjustment['rule']['bogo_pricing_method'], $adjustment['rule']['bogo_pricing_value']);

                // Round adjusted amount to get predictable results
                $adjusted = RP_WCDPD_Pricing::round($adjusted);

                // Allow developers to override
                $adjusted = apply_filters('rp_wcdpd_product_pricing_adjusted_unit_price', $adjusted, $price['adjusted'], $adjustment, array('receive_quantity' => $receive_quantity));

                // Add to processed prices array
                $processed[$processed_key] = (float) $adjusted;
            }

            // Add price to main array
            $prices[$index]['adjusted'] = $processed[$processed_key];

            // Set adjustment
            $prices[$index]['adjustments'][] = $adjustment;

            // Decrease quantity
            $receive_quantity--;

            // Nothing left to adjust
            if (!$receive_quantity) {
                break;
            }
        }

        return $prices;
    }


}
}
