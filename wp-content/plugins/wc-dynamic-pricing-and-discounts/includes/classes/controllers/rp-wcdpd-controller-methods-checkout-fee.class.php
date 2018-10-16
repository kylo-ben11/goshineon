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
 * Checkout Fee method controller
 *
 * @class RP_WCDPD_Controller_Methods_Checkout_Fee
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Controller_Methods_Checkout_Fee')) {

class RP_WCDPD_Controller_Methods_Checkout_Fee extends RP_WCDPD_Controller_Methods
{
    protected $context = 'checkout_fees';

    // Store applicable adjustments
    protected $applicable_adjustments = array();

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
        // Prepare checkout fees
        add_action('woocommerce_cart_calculate_fees', array($this, 'prepare_checkout_fees'), 10);

        // Apply checkout fees
        add_action('woocommerce_cart_calculate_fees', array($this, 'apply'), 11);
    }

    /**
     * Prepare checkout fees
     *
     * @access public
     * @param object $cart
     * @return void
     */
    public function prepare_checkout_fees($cart)
    {
        // Get applicable adjustments
        $adjustments = $this->get_applicable_adjustments();

        // Filter adjustments by rule selection method and exclusivity settings
        $adjustments = RP_WCDPD_Rules::filter_by_exclusivity($this->context, $adjustments);

        // Store adjustments so that other methods can access them later during this request
        $this->applicable_adjustments = $adjustments;
    }

    /**
     * Check if applicable simple checkout fees need to be combined
     *
     * @access public
     * @return bool
     */
    public function combine_simple()
    {
        return RP_WCDPD_Settings::get('checkout_fees_if_multiple_applicable') === 'combined' && count($this->applicable_adjustments) > 1;
    }

    /**
     * Get combined simple rule
     *
     * @access public
     * @return array
     */
    public function get_combined_simple_rule()
    {
        return array(
            'uid'               => 'rp_wcdpd_combined',
            'title'             => $this->get_combined_simple_checkout_fee_label(),
            'pricing_method'    => 'fee__amount',
            'pricing_value'     => $this->get_combined_simple_rule_amount(),
        );
    }

    /**
     * Get combined simple checkout fee label
     *
     * @access public
     * @return string
     */
    public function get_combined_simple_checkout_fee_label()
    {
        $label = RP_WCDPD_Settings::get('checkout_fees_combined_title');
        return !RightPress_Helper::is_empty($label) ? $label : __('Fee', 'rp_wcdpd');
    }





}

RP_WCDPD_Controller_Methods_Checkout_Fee::get_instance();

}
