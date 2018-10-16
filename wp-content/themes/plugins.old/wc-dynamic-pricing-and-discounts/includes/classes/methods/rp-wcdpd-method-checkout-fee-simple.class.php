<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Method_Checkout_Fee')) {
    include_once('rp-wcdpd-method-checkout-fee.class.php');
}

/**
 * Checkout Fee Method: Simple
 *
 * @class RP_WCDPD_Method_Checkout_Fee_Simple
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Method_Checkout_Fee_Simple')) {

class RP_WCDPD_Method_Checkout_Fee_Simple extends RP_WCDPD_Method_Checkout_Fee
{
    protected $key              = 'simple';
    protected $group_key        = 'simple';
    protected $group_position   = 10;
    protected $position         = 10;

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
        $this->hook_group();
        $this->hook();
    }

    /**
     * Get group label
     *
     * @access public
     * @return string
     */
    public function get_group_label()
    {
        return __('Simple', 'rp_wcdpd');
    }

    /**
     * Get label
     *
     * @access public
     * @return string
     */
    public function get_label()
    {
        return __('Simple fee', 'rp_wcdpd');
    }

    /**
     * Get custom item properties to register
     *
     * @access public
     * @return array
     */
    public function custom_item_properties()
    {
        return array(
            'label'                             => $this->get_label(),
            'get_adjustments_callback'          => array($this, 'get_adjustments'),
            'apply_adjustment_callback'         => array($this, 'apply_adjustment'),
            'get_adjustment_amount_callback'    => array($this, 'get_adjustment_amount'),
        );
    }

    /**
     * Apply checkout fee
     *
     * @access public
     * @param array $adjustment
     * @param object $cart
     * @return void
     */
    public function apply_adjustment($adjustment, $cart)
    {
        // Get fee amount
        $fee_amount = $this->get_adjustment_amount($adjustment);

        // Do not actually add zero fees even if rule is applicable
        if ($fee_amount == 0) {
            return;
        }

        // Ensure fee label is unique - duplicate labels can't be used
        $fee_label = $this->get_unique_fee_label($adjustment['rule']['title'], $cart);

        // Check if fee is taxable and get tax class
        $tax_class = RP_WCDPD_Settings::get('checkout_fees_tax_class');
        $taxable = (wc_tax_enabled() && $tax_class !== null && $tax_class !== 'rp_wcdpd_not_taxable');
        $tax_class = ($taxable && $tax_class !== 'standard') ? $tax_class : '';

        // Add fee to cart
        $cart->add_fee($fee_label, $fee_amount, $taxable, $tax_class);
    }

    /**
     * Get adjustment amount
     *
     * @access public
     * @param array $adjustment
     * @return float
     */
    public function get_adjustment_amount($adjustment)
    {
        // Get cart subtotal
        $cart_subtotal = $this->get_cart_subtotal();

        // Get fee amount
        $fee_amount = RP_WCDPD_Pricing::get_adjustment_value($adjustment['rule']['pricing_method'], $adjustment['rule']['pricing_value'], $cart_subtotal, $adjustment);

        // Allow developers to override
        $fee_amount = apply_filters('rp_wcdpd_checkout_fee_amount', $fee_amount, $adjustment);

        // Return fee amount
        return (float) abs($fee_amount);
    }




}

RP_WCDPD_Method_Checkout_Fee_Simple::get_instance();

}
