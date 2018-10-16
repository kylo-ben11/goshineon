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
 * Condition Group: Cart Item Quantities
 *
 * @class RP_WCDPD_Condition_Cart_Item_Quantities
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Condition_Cart_Item_Quantities')) {

abstract class RP_WCDPD_Condition_Cart_Item_Quantities extends RP_WCDPD_Condition
{
    protected $group_key        = 'cart_item_quantities';
    protected $group_position   = 35;

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
        return __('Cart Item Quantities', 'rp_wcdpd');
    }

    /**
     * Get value to compare against condition
     *
     * @access public
     * @param array $params
     * @return mixed
     */
    public function get_value($params)
    {
        if (!empty($params['condition'][$this->key])) {
            return RightPress_Helper::get_wc_cart_sum_of_item_quantities(array(
                $this->key => $params['condition'][$this->key],
            ));
        }

        return null;
    }




}
}
