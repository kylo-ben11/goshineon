<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Condition_Cart_Items')) {
    include_once('rp-wcdpd-condition-cart-items.class.php');
}

/**
 * Condition: Cart Items - Product Tags
 *
 * @class RP_WCDPD_Condition_Cart_Items_Product_Tags
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Condition_Cart_Items_Product_Tags')) {

class RP_WCDPD_Condition_Cart_Items_Product_Tags extends RP_WCDPD_Condition_Cart_Items
{
    protected $key      = 'product_tags';
    protected $contexts = array('product_pricing', 'cart_discounts', 'checkout_fees');
    protected $method   = 'list_advanced';
    protected $fields   = array(
        'after' => array('product_tags'),
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
        return __('Product tags in cart', 'rp_wcdpd');
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
        return RightPress_Helper::get_wc_cart_product_tag_ids();
    }




}

RP_WCDPD_Condition_Cart_Items_Product_Tags::get_instance();

}
