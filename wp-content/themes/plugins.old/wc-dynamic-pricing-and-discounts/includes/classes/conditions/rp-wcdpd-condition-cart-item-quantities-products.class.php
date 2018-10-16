<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Condition_Cart_Item_Quantities')) {
    include_once('rp-wcdpd-condition-cart-item-quantities.class.php');
}

/**
 * Condition: Cart Item Quantities - Products
 *
 * @class RP_WCDPD_Condition_Cart_Item_Quantities_Products
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Condition_Cart_Item_Quantities_Products')) {

class RP_WCDPD_Condition_Cart_Item_Quantities_Products extends RP_WCDPD_Condition_Cart_Item_Quantities
{
    protected $key          = 'products';
    protected $contexts     = array('cart_discounts', 'checkout_fees');
    protected $method       = 'numeric';
    protected $fields       = array(
        'before'    => array('products'),
        'after'     => array('number'),
    );
    protected $main_field   = 'number';
    protected $position     = 10;
    protected $is_cart      = true;

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
        return __('Product quantity in cart', 'rp_wcdpd');
    }




}

RP_WCDPD_Condition_Cart_Item_Quantities_Products::get_instance();

}
