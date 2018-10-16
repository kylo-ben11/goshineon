<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Condition_Customer_Value')) {
    include_once('rp-wcdpd-condition-customer-value.class.php');
}

/**
 * Condition: Customer Value - Last Order Amount
 *
 * @class RP_WCDPD_Condition_Customer_Value_Last_Order_Amount
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Condition_Customer_Value_Last_Order_Amount')) {

class RP_WCDPD_Condition_Customer_Value_Last_Order_Amount extends RP_WCDPD_Condition_Customer_Value
{
    protected $key      = 'last_order_amount';
    protected $contexts = array('product_pricing', 'cart_discounts', 'checkout_fees');
    protected $method   = 'numeric';
    protected $fields   = array(
        'after' => array('decimal'),
    );
    protected $position = 40;
    protected $is_cart  = false;

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
        return __('Last order amount', 'rp_wcdpd');
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
        // Get customer id
        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : null;

        // Get billing email
        $billing_email = RightPress_Conditions_Helper::get_checkout_billing_email();

        // Get last order total
        if ($last_order_id = RightPress_Helper::get_wc_last_user_paid_order_id($customer_id, $billing_email)) {
            if ($order = wc_get_order($last_order_id)) {
                return (float) RightPress_WC_Legacy::order_get_total($order);
            }
        }

        return 0.0;
    }





}

RP_WCDPD_Condition_Customer_Value_Last_Order_Amount::get_instance();

}
