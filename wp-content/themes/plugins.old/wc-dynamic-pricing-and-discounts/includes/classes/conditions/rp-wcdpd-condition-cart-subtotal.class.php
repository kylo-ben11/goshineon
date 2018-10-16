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
 * Condition: Cart - Subtotal
 *
 * @class RP_WCDPD_Condition_Cart_Subtotal
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Condition_Cart_Subtotal')) {

class RP_WCDPD_Condition_Cart_Subtotal extends RP_WCDPD_Condition_Cart
{
    protected $key      = 'subtotal';
    protected $contexts = array('cart_discounts', 'checkout_fees');
    protected $method   = 'numeric';
    protected $fields   = array(
        'after' => array('decimal'),
    );
    protected $position = 10;
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
        return wc_tax_enabled() ? __('Subtotal (including tax)', 'rp_wcdpd') : __('Subtotal', 'rp_wcdpd');
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
        return RightPress_Helper::get_wc_cart_subtotal();
    }

    /**
     * Get condition value
     *
     * @access public
     * @param array $params
     * @return mixed
     */
    public function get_condition_value($params)
    {
        // Load field
        if ($field_key = $this->get_main_field()) {
            if ($field = RP_WCDPD_Controller_Condition_Fields::get_item($field_key)) {
                if (isset($params['condition'][$field_key])) {
                    return RightPress_Helper::get_amount_in_currency($params['condition'][$field_key]);
                }
            }
        }

        return null;
    }




}

RP_WCDPD_Condition_Cart_Subtotal::get_instance();

}
