<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Pricing_Method_Fee_Per_Cart_Item')) {
    include_once('rp-wcdpd-pricing-method-fee-per-cart-item.class.php');
}

/**
 * Pricing Method: Fee Per Cart Item - Amount
 *
 * @class RP_WCDPD_Pricing_Method_Fee_Per_Cart_Item_Amount
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Pricing_Method_Fee_Per_Cart_Item_Amount')) {

class RP_WCDPD_Pricing_Method_Fee_Per_Cart_Item_Amount extends RP_WCDPD_Pricing_Method_Fee_Per_Cart_Item
{
    protected $key      = 'amount';
    protected $contexts = array('checkout_fees_simple');
    protected $position = 10;

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
        return __('Fixed fee per item', 'rp_wcdpd');
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
        // Get conditions
        $conditions = (is_array($adjustment) && !empty($adjustment['rule']['conditions'])) ? $adjustment['rule']['conditions'] : array();

        // Get cart item quantity to multiply by
        $quantity = RP_WCDPD_Conditions::get_sum_of_cart_item_quantities_by_product_conditions($conditions);

        // Calculate adjustment
        return RightPress_Helper::get_amount_in_currency_aelia($setting) * $quantity;
    }





}

RP_WCDPD_Pricing_Method_Fee_Per_Cart_Item_Amount::get_instance();

}
