<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Condition_Time')) {
    include_once('rp-wcdpd-condition-time.class.php');
}

/**
 * Condition: Time - Date
 *
 * @class RP_WCDPD_Condition_Time_Date
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Condition_Time_Date')) {

class RP_WCDPD_Condition_Time_Date extends RP_WCDPD_Condition_Time
{
    protected $key      = 'date';
    protected $contexts = array('product_pricing', 'cart_discounts', 'checkout_fees');
    protected $method   = 'date';
    protected $fields   = array(
        'after' => array('date'),
    );
    protected $position = 10;
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
        return __('Date', 'rp_wcdpd');
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
        $date = RightPress_Helper::get_datetime_object();
        $date->setTime(0, 0, 0);
        return $date;
    }




}

RP_WCDPD_Condition_Time_Date::get_instance();

}
