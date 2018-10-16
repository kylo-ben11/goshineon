<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Methods related to rules
 *
 * @class RP_WCDPD_Rules
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Rules')) {

class RP_WCDPD_Rules
{

    /**
     * Get rules by context
     *
     * @access public
     * @param string $context
     * @param array $params
     * @param bool $include_disabled
     * @return array
     */
    public static function get($context, $params = array(), $include_disabled = false)
    {
        // Get all rules from settings by context
        $rules = RP_WCDPD_Settings::get($context);

        // Check if any rules are configured
        if (is_array($rules) && !empty($rules)) {

            // Filter rules
            foreach ($rules as $rule_key => $rule) {

                // Rule is disabled
                if (!$include_disabled && $rule['exclusivity'] === 'disabled') {
                    unset($rules[$rule_key]);
                    continue;
                }

                // Specific methods requested
                if (!empty($params['methods']) && !in_array($rule['method'], $params['methods'], true)) {
                    unset($rules[$rule_key]);
                    continue;
                }

                // Specific UIDs requested
                if (!empty($params['uids']) && !in_array($rule['uid'], $params['uids'], true)) {
                    unset($rules[$rule_key]);
                    continue;
                }

                // Allow developers to disable rules programmatically
                if (!$include_disabled && !apply_filters('rp_wcdpd_use_rule', true, $rule, $params)) {
                    unset($rules[$rule_key]);
                    continue;
                }
            }

            // Return rules
            return $rules;
        }

        // No rules configured
        return array();
    }

    /**
     * Filter adjustments by exclusivity settings
     *
     * @access public
     * @param string $context
     * @param array $adjustments
     * @return array
     */
    public static function filter_by_exclusivity($context, $adjustments)
    {
        // Nothing to do
        if (count($adjustments) < 2) {
            return $adjustments;
        }

        // Check for first exclusive rule
        foreach ($adjustments as $adjustment_key => $adjustment) {
            if ($adjustment['rule']['exclusivity'] === 'this') {
                return array($adjustment_key => $adjustment);
            }
        }

        // Count rules that don't go with other rules
        $exclusivity_other_count = 0;

        foreach ($adjustments as $adjustment) {
            if ($adjustment['rule']['exclusivity'] === 'other') {
                $exclusivity_other_count++;
            }
        }

        // All rules are set to not go with other rules - pick the first rule in a row
        if ($exclusivity_other_count === count($adjustments)) {
            foreach ($adjustments as $adjustment_key => $adjustment) {
                return array($adjustment_key => $adjustment);
            }
        }

        // At least one rule is set to not go with other rules - remove them
        if ($exclusivity_other_count > 0) {
            foreach ($adjustments as $adjustment_key => $adjustment) {
                if ($adjustment['rule']['exclusivity'] === 'other') {
                    unset($adjustments[$adjustment_key]);
                }
            }
        }

        // Filter adjustments by rule selection method
        $adjustments = RP_WCDPD_Rules::filter_by_selection_method($context, $adjustments);

        // Return adjustments
        return $adjustments;
    }

    /**
     * Filter adjustments by selection method
     *
     * @access public
     * @param string $context
     * @param array $adjustments
     * @return array
     */
    public static function filter_by_selection_method($context, $adjustments)
    {
        // Get rule selection method
        $selection_method = RP_WCDPD_Settings::get($context . '_rule_selection_method');

        // Sort by reference amount
        if (in_array($selection_method, array('smaller_price', 'bigger_discount', 'bigger_fee'), true)) {
            RP_WCDPD_Helper::stable_uasort($adjustments, array('RP_WCDPD_Rules', 'sort_by_reference_amount_desc'));
        }
        else if (in_array($selection_method, array('bigger_price', 'smaller_discount', 'smaller_fee'), true)) {
            RP_WCDPD_Helper::stable_uasort($adjustments, array('RP_WCDPD_Rules', 'sort_by_reference_amount_asc'));
        }

        // Pick first rule in a row
        if (in_array($selection_method, array('first', 'smaller_price', 'bigger_price', 'bigger_discount', 'smaller_discount', 'bigger_fee', 'smaller_fee'), true)) {
            foreach ($adjustments as $adjustment_key => $adjustment) {
                return array($adjustment_key => $adjustment);
            }
        }

        // Return all adjustments
        return $adjustments;
    }

    /**
     * Sort by reference amount ascending
     *
     * @access public
     * @param object $a
     * @param object $b
     * @return array
     */
    public static function sort_by_reference_amount_asc($a, $b)
    {
        if ($a['reference_amount'] > $b['reference_amount']) {
            return 1;
        }
        else if ($a['reference_amount'] < $b['reference_amount']) {
            return -1;
        }
        else {
            return 0;
        }
    }

    /**
     * Sort by reference amount descending
     *
     * @access public
     * @param object $a
     * @param object $b
     * @return array
     */
    public static function sort_by_reference_amount_desc($a, $b)
    {
        if ($a['reference_amount'] < $b['reference_amount']) {
            return 1;
        }
        else if ($a['reference_amount'] > $b['reference_amount']) {
            return -1;
        }
        else {
            return 0;
        }
    }





}
}
