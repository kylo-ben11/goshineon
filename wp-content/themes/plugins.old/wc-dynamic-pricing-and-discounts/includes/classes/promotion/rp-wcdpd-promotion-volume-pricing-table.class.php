<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Promotion: Volume Pricing Table
 *
 * @class RP_WCDPD_Promotion_Volume_Pricing_Table
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Promotion_Volume_Pricing_Table')) {

class RP_WCDPD_Promotion_Volume_Pricing_Table
{

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
        // Add pricing table hook if pricing table is enabled
        add_action(RP_WCDPD_Settings::get('promo_volume_pricing_table_position'), array($this, 'maybe_display_pricing_table'));

        // Prevent customers from purchasing quantities outside of quantity ranges
        if (RP_WCDPD_Settings::get('promo_volume_pricing_table') && RP_WCDPD_Settings::get('promo_volume_pricing_table_missing_ranges') === 'block') {
            add_filter('woocommerce_add_to_cart_validation', array($this, 'maybe_prevent_add_to_cart'), 10, 3);
            add_filter('woocommerce_update_cart_validation', array($this, 'maybe_prevent_quantity_update'), 10, 4);
        }
    }

    /**
     * Maybe display pricing table
     *
     * @access public
     * @return void
     */
    public function maybe_display_pricing_table()
    {
        global $product;

        if (!is_a($product, 'WC_Product')) {
            return;
        }

        // Hide if loaded via Ajax request, added this to solve #280
        // In case of problems, make the condition more specific to run only during
        // woocommerce_before_add_to_cart_form and woocommerce_after_add_to_cart_form
        // and to check for $_REQUEST['action'] === 'flatsome_quickview'
        if (is_ajax()) {
            return;
        }

        // Simple product handling
        if (!$product->is_type('variable') && !$product->is_type('variation')) {

            // Get applicable rule
            if ($rule = self::get_applicable_volume_rule($product)) {

                // Get table data
                if ($table_data = self::get_table_data($product, $rule)) {

                    // Display pricing table
                    self::display_pricing_table(array(array(
                        'product'       => $product,
                        'rule'          => $rule,
                        'table_data'    => $table_data,
                    )), false);
                }
            }
        }
        // Variable product handling
        else {

            // Switch to variable product if we got product variation
            if ($product->is_type('variation')) {
                $product = RightPress_WC_Legacy::product_variation_get_parent($product);
            }

            $variation_rules = array();
            $prices_match = false;
            $available_variations = 0;

            // Get rules for all variations
            foreach ($product->get_available_variations() as $variation_data) {

                // Load variation
                $variation = wc_get_product($variation_data['variation_id']);

                // Get rule for current variation
                if ($rule = self::get_applicable_volume_rule($variation)) {

                    // Get table data
                    if ($table_data = self::get_table_data($variation, $rule)) {
                        $available_variations++;
                    }

                    // Add to main array
                    $variation_rules[] = array(
                        'product'       => $variation,
                        'rule'          => $rule,
                        'table_data'    => $table_data,
                    );
                }
            }

            // Display table
            if (!empty($variation_rules) && $available_variations) {
                self::display_pricing_table($variation_rules, true);
            }
        }
    }

    /**
     * Check if product variation prices match
     *
     * @access public
     * @param array $data
     * @return bool
     */
    public static function variation_prices_match($data)
    {
        $last = null;

        // Check each variation
        foreach ($data as $single) {

            $current = 0.0;

            // Variation not available
            if ($single['table_data'] === false) {
                return false;
            }

            // Add prices of all ranges
            foreach ($single['table_data'] as $range) {
                $current += (float) $range['price_raw'];
            }

            // Prices do not match
            if ($last !== null && $last !== $current) {
                return false;
            }

            $last = $current;
        }

        return true;
    }

    /**
     * Display volume pricing table
     *
     * @access public
     * @param array $data
     * @param bool $is_variable
     * @return void
     */
    public static function display_pricing_table($data, $is_variable)
    {
        // Get table layout
        $layout = RP_WCDPD_Settings::get('promo_volume_pricing_table_layout');

        // Display one pricing table for simple product or variable product with equal prices
        if (!$is_variable || (self::variation_prices_match($data) && RP_WCDPD_Settings::get('promo_volume_pricing_table_variation_layout') === 'multiple')) {

            // Treat multiple variations as one since prices are the same
            $single = array_shift($data);
            $is_variable = false;

            // Display table
            RightPress_Helper::include_template(('volume-pricing-table/' . $layout), RP_WCDPD_PLUGIN_PATH, 'wc-dynamic-pricing-and-discounts', array('data' => array($single)));
        }
        // Display one table for all variations
        else if (RP_WCDPD_Settings::get('promo_volume_pricing_table_variation_layout') === 'single') {

            // Display table
            RightPress_Helper::include_template(('volume-pricing-table/' . $layout), RP_WCDPD_PLUGIN_PATH, 'wc-dynamic-pricing-and-discounts', array('data' => $data));
        }
        // Display multiple individual tables for multiple variations
        else {

            // Open variable product container
            echo '<div id="rp_wcdpd_pricing_table_variation_container" class="rp_wcdpd_pricing_table_variation_container">';

            // Iterate over products with rules
            foreach ($data as $single) {

                // Current variation is unavailable - do not display a table for it
                if ($single['table_data'] === false) {
                    continue;
                }

                // Open variation container
                echo '<div id="rp_wcdpd_pricing_table_variation_' . RightPress_WC_Legacy::product_get_id($single['product']) . '" class="rp_wcdpd_pricing_table_variation">';

                // Display table
                RightPress_Helper::include_template(('volume-pricing-table/' . $layout), RP_WCDPD_PLUGIN_PATH, 'wc-dynamic-pricing-and-discounts', array('data' => array($single)));

                // Close variation container
                echo '</div>';
            }

            // Close variable product container
            echo '</div>';
        }

        // Enqueue scripts and styles
        wp_enqueue_script('rp-wcdpd-volume-pricing-table-scripts', RP_WCDPD_PLUGIN_URL . '/assets/js/volume-pricing-table.js', array('jquery'), RP_WCDPD_VERSION);
        RP_WCDPD_Assets::enqueue_or_inject_stylesheet('rp-wcdpd-volume-pricing-table-styles', RP_WCDPD_PLUGIN_URL . '/assets/css/volume-pricing-table.css', RP_WCDPD_VERSION);
    }

    /**
     * Get applicable volume rule
     *
     * Note: This feature assumes that considering all conditions only one
     * volume rule will be applicable to one product. If there are more than
     * one volume rule, the first one in a row will be selected.
     *
     * Note: Rules that contain cart related conditions are not considered
     *
     * @access public
     * @param object $product
     * @return array|bool
     */
    public static function get_applicable_volume_rule($product)
    {
        if ($matched_rules = RP_WCDPD_Product_Pricing::get_applicable_rules_for_product($product, array('bulk', 'tiered'), true)) {
            return array_shift($matched_rules);
        }

        return false;
    }

    /**
     * Calculate reference amount for pricing table
     *
     * @access public
     * @param array $rule
     * @return float
     */
    public static function calculate_reference_amount_for_table($rule)
    {
        $amount = 0;

        // Check if at least one quantity range is defined
        if (!empty($rule['quantity_ranges'])) {

            $quantity = 0;

            // Iterate over quantity ranges
            foreach ($rule['quantity_ranges'] as $quantity_range) {

                // Note: Implement this functionality
            }
        }

        return (float) $amount;
    }

    /**
     * Get table data
     *
     * @access public
     * @param array $product
     * @param array $rule
     * @return array|false
     */
    public static function get_table_data($product, $rule)
    {
        $data = array();

        // Get product price
        $regular_price = $product->get_regular_price();
        $final_price = $product->get_price();

        // Get quantity ranges
        $quantity_ranges = $rule['quantity_ranges'];

        // Maybe add missing ranges
        if (RP_WCDPD_Settings::get('promo_volume_pricing_table_missing_ranges') === 'display') {
            $quantity_ranges = self::add_missing_ranges($quantity_ranges, $product);
        }

        // Get data for each quantity range
        foreach ($quantity_ranges as $quantity_range) {

            // Get from and to quantities
            $from = $quantity_range['from'];
            $to = $quantity_range['to'];

            // Format range label
            if ($from === $to) {
                $label = $from;
            }
            else if (empty($to)) {
                $label = $from . '+';
            }
            else {
                $label = $from . '-' . $to;
            }

            // Select correct price to adjust
            $price_to_adjust = (RP_WCDPD_Settings::get('product_pricing_sale_price_handling') === 'regular' && empty($quantity_range['is_missing_range'])) ? $regular_price : $final_price;

            // Price is not set in product settings
            if ($price_to_adjust === '') {
                return false;
            }

            // Calculate price
            $price = RP_WCDPD_Pricing::adjust_amount($price_to_adjust, $quantity_range['pricing_method'], $quantity_range['pricing_value']);

            // Add to array
            $data[] = array(
                'range_label'   => apply_filters('rp_wcdpd_volume_pricing_table_range_label', $label, $product, $rule, $quantity_range),
                'range_price'   => apply_filters('rp_wcdpd_volume_pricing_table_range_price', wc_price(RightPress_WC_Legacy::product_get_display_price($product, $price)), $product, $rule, $quantity_range, $price),
                'price_raw'     => $price,
            );
        }

        // Allow developers to make changes and return
        return apply_filters('rp_wcdpd_volume_pricing_table_data', $data, $product, $rule);
    }

    /**
     * Add missing quantity ranges (gaps in continuity)
     *
     * @access public
     * @param array $quantity_ranges
     * @param object $product
     * @return array
     */
    public static function add_missing_ranges($quantity_ranges, $product)
    {
        $fixed = array();

        // Check if product uses decimal quantities
        $decimal_quantities = RP_WCDPD_Settings::get('decimal_quantities') && RightPress_Helper::wc_product_uses_decimal_quantities($product);

        // Get quantity step
        $quantity_step = $decimal_quantities ? RightPress_Helper::get_wc_product_quantity_step($product) : 1;

        $last_from = null;
        $last_to = null;

        $count = count($quantity_ranges);
        $i = 1;

        foreach ($quantity_ranges as $quantity_range) {

            // Get from and to
            $from = $quantity_range['from'];
            $to = $quantity_range['to'];

            // Maybe add first range
            if ($last_from === null && $from > $quantity_step) {
                $fixed[] = self::get_missing_range($quantity_step, ($from - $quantity_step));
            }

            // Gap between last to and current from
            if ($last_to !== null && ($from - $last_to) > $quantity_step) {
                $fixed[] = self::get_missing_range(($last_to + $quantity_step), ($from - $quantity_step));
            }

            // Add current range
            $fixed[] = $quantity_range;

            // Set last from and to
            $last_from = $from;
            $last_to = $to;

            $i++;
        }

        // Add closing range
        if ($last_to !== null) {
            $fixed[] = self::get_missing_range(($last_to + $quantity_step), null);
        }

        return $fixed;
    }

    /**
     * Get missing quantity range
     *
     * @access public
     * @param int $from
     * @param int $to
     * @return array
     */
    public static function get_missing_range($from, $to)
    {
        return array(
            'from'              => $from,
            'to'                => $to,
            'pricing_method'    => 'discount__amount',
            'pricing_value'     => 0,
            'is_missing_range'  => true,
        );
    }

    /**
     * Maybe prevent add to cart
     *
     * @access public
     * @param bool $is_valid
     * @param int $product_id
     * @param int $quantity
     * @param int $variation_id
     * @param array $variation_data
     * @return bool
     */
    public function maybe_prevent_add_to_cart($is_valid, $product_id, $quantity, $variation_id = null, $variation_data = null)
    {
        // Note: This functionality is not ready for production and must not be enabled
        return $is_valid;

        // Get product
        $id = !empty($variation_id) ? $variation_id : $product_id;
        $product = wc_get_product($id);

        // Get applicable volume rule
        if ($rule = self::get_applicable_volume_rule($product)) {
            if (!self::select_quantity_range_by_quantity($rule['quantity_ranges'], $quantity)) {
                return false;
            }
        }

        return $is_valid;
    }

    /**
     * Maybe prevent quantity update
     *
     * @access public
     * @param bool $is_valid
     * @param string $cart_item_key
     * @param array $values
     * @param int $quantity
     * @return bool
     */
    public function maybe_prevent_quantity_update($is_valid, $cart_item_key, $values, $quantity)
    {
        // Note: This functionality is not ready for production and must not be enabled

        // Get variation properties
        $variation_id = !empty($values['variation_id']) ? $values['variation_id'] : null;
        $variation_data = !empty($values['variation']) ? $values['variation'] : null;

        // Check via other method
        if (!$this->maybe_prevent_add_to_cart(true, $values['product_id'], $quantity, $variation_id, $variation_data)) {
            return false;
        }

        return $is_valid;
    }

    /**
     * Select quantity range by quantity
     *
     * @access public
     * @param array $quantity_ranges
     * @param int $quantity
     * @return array|bool
     */
    public static function select_quantity_range_by_quantity($quantity_ranges, $quantity)
    {
        foreach ($quantity_ranges as $quantity_range) {
            if ($quantity_range['from'] <= $quantity && $quantity <= $quantity_range['to']) {
                return $quantity_range;
            }
        }

        return false;
    }






}

RP_WCDPD_Promotion_Volume_Pricing_Table::get_instance();

}
