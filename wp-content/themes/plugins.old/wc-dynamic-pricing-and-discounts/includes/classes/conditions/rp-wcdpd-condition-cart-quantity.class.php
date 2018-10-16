<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Condition_Cart')) {
    include_once('rp-wcdpd-condition-cart.class.php');
}

/**
 * Condition: Cart - Quantity
 *
 * @class RP_WCDPD_Condition_Cart_Quantity
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Condition_Cart_Quantity')) {

class RP_WCDPD_Condition_Cart_Quantity extends RP_WCDPD_Condition_Cart
{
    protected $key      = 'quantity';
    protected $contexts = array('cart_discounts', 'checkout_fees');
    protected $method   = 'numeric';
    protected $fields   = array(
        'after' => array('decimal'),
    );
    protected $position = 50;
    protected $is_cart  = true;

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
        parent::__construct();

        $this->hook();
    }

    /**
     * Get label
     *
     * @access public
     * @return string
     */
    public function get_label()
    {
        return __('Sum of item quantities', 'rp_wcdpd');
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
        return RightPress_Helper::get_wc_cart_sum_of_item_quantities();
    }




}

RP_WCDPD_Condition_Cart_Quantity::get_instance();

}
