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
 * Pricing Method Group: Fixed
 *
 * @class RP_WCDPD_Pricing_Method_Fixed
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Pricing_Method_Fixed')) {

abstract class RP_WCDPD_Pricing_Method_Fixed extends RP_WCDPD_Pricing_Method
{
    protected $group_key        = 'fixed';
    protected $group_position   = 30;

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
        return __('Price', 'rp_wcdpd');
    }




}
}
