<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Controller')) {
    include_once('rp-wcdpd-controller.class.php');
}

/**
 * Method controller
 *
 * @class RP_WCDPD_Controller_Methods
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Controller_Methods')) {

abstract class RP_WCDPD_Controller_Methods extends RP_WCDPD_Controller
{
    protected $item_key             = 'method';
    protected $items_are_grouped    = true;

    /**
     * Get rule method by rule
     *
     * @access public
     * @param array $rule
     * @return mixed
     */
    public function get_method($rule = array())
    {
        // Get key from rule
        $key = isset($rule['method']) ? $rule['method'] : 'simple';

        // Attempt to find method by key
        foreach ($this->get_items() as $method_group) {
            foreach ($method_group['children'] as $method_key => $method) {
                if ($method_key === $key) {
                    return $method;
                }
            }
        }

        // No such method
        return false;
    }

    /**
     * Get rule method key by rule
     *
     * @access public
     * @param array $rule
     * @return mixed
     */
    public function get_method_key($rule)
    {
        if ($method = $this->get_method($rule)) {
            return $method['key'];
        }

        return false;
    }

    /**
     * Get applicable adjustments
     *
     * @access public
     * @param array $cart_items
     * @return array
     */
    public function get_applicable_adjustments($cart_items = null)
    {
        $adjustments = array();

        // Load all rules by context
        $rules = RP_WCDPD_Rules::get($this->context, array(
            'cart_items' => $cart_items,
        ));

        // Iterate over all rules
        foreach ($rules as $rule) {

            // Get method
            if ($method = $this->get_method($rule)) {

                // Get adjustments for current method/rule
                $current_adjustments = call_user_func($method['get_adjustments_callback'], $rule, $cart_items);

                // Add adjustments to the main array
                foreach ($current_adjustments as $cart_item_key => $adjustment) {

                    // Add extra data and split by cart item if needed
                    if ($cart_items !== null) {

                        $extra_data = array();

                        if (RP_WCDPD_Settings::get('decimal_quantities')) {

                            // Get product from cart item
                            $product = $cart_items[$cart_item_key]['data'];

                            // Get quantity step
                            $quantity_step = RightPress_Helper::get_wc_product_quantity_step($product);

                            // Add extra data
                            $extra_data['quantity_step'] = $quantity_step;
                            $extra_data['decimal_quantities'] = RightPress_Helper::wc_product_uses_decimal_quantities($product, $quantity_step);
                        }

                        // Add to main array
                        $adjustments[$cart_item_key][$rule['uid']] = array_merge($extra_data, $adjustment);
                    }
                    else {
                        $adjustments[$rule['uid']] = $adjustment;
                    }
                }
            }
        }

        return $adjustments;
    }

    /**
     * Apply rules
     *
     * Note: This method is for Cart Discounts and Checkout Fees - other methods must override it
     *
     * @access public
     * @param object $cart
     * @return void
     */
    public function apply($cart = null)
    {
        $simple_combined = false;

        // Iterate over applicable adjustments
        foreach ($this->applicable_adjustments as $rule_uid => $adjustment) {

            // Get method
            if ($method = $this->get_method($adjustment['rule'])) {

                // Handle combined simple cart discounts
                if ($method['key'] === 'simple') {

                    // Already combined
                    if ($simple_combined) {
                        continue;
                    }
                    // Not yet combined but need to be combined
                    else if ($this->combine_simple()) {

                        $adjustment = array(
                            'rule' => $this->get_combined_simple_rule(),
                        );

                        $simple_combined = true;
                    }
                }

                // Apply adjustment
                call_user_func($method['apply_adjustment_callback'], $adjustment, $cart);
            }
        }
    }

    /**
     * Get combined simple rule amount
     *
     * @access public
     * @return float
     */
    public function get_combined_simple_rule_amount()
    {
        $combined_amount = 0;

        // Iterate over applicable adjustments
        foreach ($this->applicable_adjustments as $rule_uid => $adjustment) {

            // Get method
            if ($method = $this->get_method($adjustment['rule'])) {
                if ($method['key'] === 'simple') {
                    $combined_amount += call_user_func($method['get_adjustment_amount_callback'], $adjustment);
                }
            }
        }

        // Return combined amount
        return (float) $combined_amount;
    }



}
}
