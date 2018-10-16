<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Parent pricing method class
 *
 * @class RP_WCDPD_Pricing_Method
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Pricing_Method')) {

abstract class RP_WCDPD_Pricing_Method extends RP_WCDPD_Item
{
    protected $item_key = 'pricing_method';

    /**
     * Get custom item properties to register
     *
     * @access public
     * @return array
     */
    public function custom_item_properties()
    {
        return array(
            'label'                 => $this->get_label(),
            'contexts'              => $this->contexts,
            'calculate_callback'    => array($this, 'calculate'),
            'adjust_callback'       => array($this, 'adjust'),
        );
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
        return RightPress_Helper::get_amount_in_currency_aelia($setting);
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
        $amount += $this->calculate($setting, $amount);
        return (float) ($amount >= 0 ? $amount : 0);
    }





}
}
