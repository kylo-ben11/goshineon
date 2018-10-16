<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Condition_Product')) {
    include_once('rp-wcdpd-condition-product.class.php');
}

/**
 * Condition: Product - Stock Quantity
 *
 * @class RP_WCDPD_Condition_Product_Stock_Quantity
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Condition_Product_Stock_Quantity')) {

class RP_WCDPD_Condition_Product_Stock_Quantity extends RP_WCDPD_Condition_Product
{
    protected $key          = 'stock_quantity';
    protected $contexts     = array('product_pricing_product', 'product_pricing_bogo_product');
    protected $method       = 'numeric';
    protected $fields       = array(
        'after'     => array('number'),
    );
    protected $position     = 70;
    protected $is_cart      = false;

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
        return __('Product stock quantity', 'rp_wcdpd');
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
        if (!empty($params['item_id'])) {

            // Attempt to load product
            if ($product = wc_get_product($params['item_id'])) {

                // Get product stock quantity
                return $product->get_stock_quantity();
            }
        }

        return null;
    }




}

RP_WCDPD_Condition_Product_Stock_Quantity::get_instance();

}
