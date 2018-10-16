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
 * Condition Group: Time
 *
 * @class RP_WCDPD_Condition_Time
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Condition_Time')) {

abstract class RP_WCDPD_Condition_Time extends RP_WCDPD_Condition
{
    protected $group_key        = 'time';
    protected $group_position   = 80;

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
        return __('Date & Time', 'rp_wcdpd');
    }




}
}
