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
 * Pricing Method: Fixed - Price Per Range
 *
 * @class RP_WCDPD_Pricing_Method_Fixed_Price_Per_Range
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Pricing_Method_Fixed_Price_Per_Range')) {

class RP_WCDPD_Pricing_Method_Fixed_Price_Per_Range extends RP_WCDPD_Pricing_Method_Fixed
{
    protected $key      = 'price_per_range';
    protected $contexts = array('product_pricing_volume');
    protected $position = 40;

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
        return __('Fixed price per range', 'rp_wcdpd');
    }





}

RP_WCDPD_Pricing_Method_Fixed_Price_Per_Range::get_instance();

}
