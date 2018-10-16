<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Condition')) {
    include_once('rp-wcdpd-condition.class.php');
}

/**
 * Condition Group: Customer Value
 *
 * @class RP_WCDPD_Condition_Customer_Value
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Condition_Customer_Value')) {

abstract class RP_WCDPD_Condition_Customer_Value extends RP_WCDPD_Condition
{
    protected $group_key        = 'customer_value';
    protected $group_position   = 60;

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
        return __('Customer Value (paid orders only)', 'rp_wcdpd');
    }




}
}
