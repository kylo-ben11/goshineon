<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Pricing_Method')) {
    include_once('rp-wcdpd-pricing-method.class.php');
}

/**
 * Pricing Method Group: Discount
 *
 * @class RP_WCDPD_Pricing_Method_Discount
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Pricing_Method_Discount')) {

abstract class RP_WCDPD_Pricing_Method_Discount extends RP_WCDPD_Pricing_Method
{
    protected $group_key        = 'discount';
    protected $group_position   = 10;

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
        return __('Discount', 'rp_wcdpd');
    }

    /**
     * Calculate adjustment value
     *
     * @access public
     * @param float $setting
     * @param float $reference_amount
     * @param array $adjustment
     * @return float
     */
    public function calculate($setting, $reference_amount = 0, $adjustment = null)
    {
        return -1 * RightPress_Helper::get_amount_in_currency_aelia($setting);
    }




}
}
