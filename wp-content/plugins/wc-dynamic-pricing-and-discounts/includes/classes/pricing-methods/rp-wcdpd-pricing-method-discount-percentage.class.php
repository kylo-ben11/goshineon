<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Pricing_Method_Discount')) {
    include_once('rp-wcdpd-pricing-method-discount.class.php');
}

/**
 * Pricing Method: Discount - Percentage
 *
 * @class RP_WCDPD_Pricing_Method_Discount_Percentage
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Pricing_Method_Discount_Percentage')) {

class RP_WCDPD_Pricing_Method_Discount_Percentage extends RP_WCDPD_Pricing_Method_Discount
{
    protected $key      = 'percentage';
    protected $contexts = array('product_pricing_simple', 'product_pricing_volume', 'product_pricing_bogo', 'product_pricing_group', 'cart_discounts_simple');
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
        return __('Percentage discount', 'rp_wcdpd');
    }

    /**
     * Calculate adjustment value
     *
     * @access public
     * @param float $setting
     * @param float $reference_amount
     * @param array $adjustment
     * @return float
     */
    public function calculate($setting, $reference_amount = 0, $adjustment = null)
    {
        return -1 * (float) ($reference_amount * $setting / 100);
    }





}

RP_WCDPD_Pricing_Method_Discount_Percentage::get_instance();

}
