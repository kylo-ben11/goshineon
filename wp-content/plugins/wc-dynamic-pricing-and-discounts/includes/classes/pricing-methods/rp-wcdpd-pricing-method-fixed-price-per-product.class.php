<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Pricing_Method_Fixed')) {
    include_once('rp-wcdpd-pricing-method-fixed.class.php');
}

/**
 * Pricing Method: Fixed - Price Per Product
 *
 * @class RP_WCDPD_Pricing_Method_Fixed_Price_Per_Product
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Pricing_Method_Fixed_Price_Per_Product')) {

class RP_WCDPD_Pricing_Method_Fixed_Price_Per_Product extends RP_WCDPD_Pricing_Method_Fixed
{
    protected $key      = 'price_per_product';
    protected $contexts = array('product_pricing_group');
    protected $position = 20;

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
        return __('Fixed price per product', 'rp_wcdpd');
    }

    /**
     * Adjust amount
     *
     * @access public
     * @param float $amount
     * @param float $setting
     * @return float
     */
    public function adjust($amount, $setting)
    {
        $amount = $this->calculate($setting, $amount);
        return (float) ($amount >= 0 ? $amount : 0);
    }





}

RP_WCDPD_Pricing_Method_Fixed_Price_Per_Product::get_instance();

}