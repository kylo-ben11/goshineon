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
 * Condition: Product - Meta
 *
 * @class RP_WCDPD_Condition_Product_Meta
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Condition_Product_Meta')) {

class RP_WCDPD_Condition_Product_Meta extends RP_WCDPD_Condition_Product
{
    protected $key          = 'meta';
    protected $contexts     = array('product_pricing_product', 'product_pricing_bogo_product');
    protected $method       = 'meta';
    protected $fields       = array(
        'before'    => array('meta_key'),
        'after'     => array('text'),
    );
    protected $main_field   = 'text';
    protected $position     = 60;
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
        return __('Product meta field', 'rp_wcdpd');
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

            // Get product meta
            $product_meta = RightPress_WC_Meta::product_get_meta($params['item_id'], $params['condition']['meta_key'], false);
            $meta = RightPress_WC_Meta::normalize_meta_data($product_meta);

            // Get variation meta
            if (!empty($params['child_id'])) {
                $variation_meta = RightPress_WC_Meta::product_get_meta($params['child_id'], $params['condition']['meta_key'], false);
                $meta = array_merge($meta, RightPress_WC_Meta::normalize_meta_data($variation_meta));
            }

            // Return meta
            return RightPress_Helper::unwrap_post_meta($meta);
        }

        return null;
    }




}

RP_WCDPD_Condition_Product_Meta::get_instance();

}
