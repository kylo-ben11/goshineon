<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Controller_Methods')) {
    include_once('rp-wcdpd-controller-methods.class.php');
}

/**
 * Cart Discount method controller
 *
 * @class RP_WCDPD_Controller_Methods_Cart_Discount
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Controller_Methods_Cart_Discount')) {

class RP_WCDPD_Controller_Methods_Cart_Discount extends RP_WCDPD_Controller_Methods
{
    protected $context = 'cart_discounts';

    protected $preparing_cart_discounts = false;

    // Store prepared adjustments
    protected $applicable_adjustments = array();
    protected $adjustments_prepared = false;

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
        // Prepare cart discounts
        add_action('woocommerce_before_calculate_totals', array($this, 'prepare_cart_discounts'), 1);

        // Apply cart discounts
        add_action('woocommerce_after_calculate_totals', array($this, 'apply'));

        // Remove no longer applicable cart discounts
        add_action('woocommerce_before_calculate_totals', array($this, 'remove_cart_discounts'), 2);
        add_action('woocommerce_check_cart_items', array($this, 'remove_cart_discounts'), 1);

        // Invalidate no longer applicable coupons
        add_filter('woocommerce_coupon_is_valid', array($this, 'coupon_is_valid'), 10, 2);

        // Provide discount data
        add_filter('woocommerce_get_shop_coupon_data', array($this, 'get_fake_coupon_data'), 10, 2);

        // Change fake coupon label
        add_filter('woocommerce_cart_totals_coupon_label', array($this, 'change_fake_coupon_label'), 99, 2);

        // Change fake coupon html
        add_filter('woocommerce_cart_totals_coupon_html', array($this, 'change_fake_coupon_html'), 99, 2);
    }

    /**
     * Prepare cart discounts
     *
     * @access public
     * @return void
     */
    public function prepare_cart_discounts()
    {
        // Add preparing flag
        $this->preparing_cart_discounts = true;

        // Check if cart discounts can be applied
        if (!$this->cart_discounts_can_be_applied()) {
            return;
        }

        // Get applicable adjustments
        $adjustments = $this->get_applicable_adjustments();

        // Filter adjustments by rule selection method and exclusivity settings
        $adjustments = RP_WCDPD_Rules::filter_by_exclusivity($this->context, $adjustments);

        // Store adjustments so that other methods can access them later during this request
        $this->applicable_adjustments = $adjustments;

        // Add flag
        $this->adjustments_prepared = true;

        // Remove preparing flag
        $this->preparing_cart_discounts = true;
    }

    /**
     * Check if cart discounts can be applied
     *
     * @access public
     * @return bool
     */
    public function cart_discounts_can_be_applied()
    {
        global $woocommerce;

        // Individual use coupon settings
        if (!RP_WCDPD_Settings::get('cart_discounts_apply_with_individual_use_coupons')) {

            // Iterate over coupons applied to cart
            if (is_array($woocommerce->cart->applied_coupons)) {
                foreach ($woocommerce->cart->applied_coupons as $coupon_code) {

                    // Skip cart discounts
                    if (RP_WCDPD_Controller_Methods_Cart_Discount::coupon_is_cart_discount($coupon_code)) {
                        continue;
                    }

                    // Load coupon
                    try {
                        $coupon = new WC_Coupon($coupon_code);
                    }
                    // Something went wrong
                    catch (Exception $e) {
                        return false;
                    }

                    // Check if coupon is individual use
                    if (!RightPress_Helper::wc_version_gte('3.0') && $coupon->individual_use === 'yes') {
                        return false;
                    }
                    else if (RightPress_Helper::wc_version_gte('3.0') && $coupon->get_individual_use()) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Get fake coupon data
     *
     * @access public
     * @param mixed $coupon_data
     * @param string $coupon_code
     * @return mixed
     */
    public function get_fake_coupon_data($coupon_data, $coupon_code)
    {
        // Prevent circular reference when using coupon conditions
        if ($this->preparing_cart_discounts && !RP_WCDPD_Controller_Methods_Cart_Discount::coupon_is_cart_discount($coupon_code)) {
            return $coupon_data;
        }

        // Prepare cart discounts now
        if (!$this->adjustments_prepared) {
            $this->prepare_cart_discounts();
        }

        // Combined simple cart discount handling
        if ($coupon_code === 'rp_wcdpd_combined') {
            return RP_WCDPD_Cart_Discounts::get_coupon_data_array(array(
                'amount' => $this->get_combined_simple_rule_amount(),
            ));
        }

        // Check if current coupon code refers to any of applicable cart discounts
        if (isset($this->applicable_adjustments[$coupon_code])) {

            // Get coupon data
            if ($method = $this->get_method($this->applicable_adjustments[$coupon_code]['rule'])) {
                $coupon_data = call_user_func($method['get_coupon_data_callback'], $this->applicable_adjustments[$coupon_code]);
            }
        }

        return $coupon_data;
    }

    /**
     * Remove cart discounts
     *
     * @access public
     * @return void
     */
    public function remove_cart_discounts()
    {
        global $woocommerce;

        $flag_totals_for_refresh = false;

        // Remove coupons that are no longer applicable
        foreach ($woocommerce->cart->applied_coupons as $applied_coupon) {

            // Check if this is our fake coupon that is no longer applicable
            if (RP_WCDPD_Controller_Methods_Cart_Discount::coupon_is_cart_discount($applied_coupon)) {

                $remove = false;

                // Cart discount no longer applicable
                if (!isset($this->applicable_adjustments[$applied_coupon])) {
                    $remove = true;
                }
                // Simple cart discount was combined with other simple cart discounts
                else if ($this->get_method_key($this->applicable_adjustments[$applied_coupon]['rule']) === 'simple' && $this->combine_simple() && $applied_coupon !== 'rp_wcdpd_combined') {
                    $remove = true;
                }

                // Check if cart discount needs to be removed
                if ($remove) {

                    // Add filter to temporarily enable coupons just in case they are disabled
                    add_filter('woocommerce_coupons_enabled', array($this, 'woocommerce_enable_coupons'));

                    // Remove coupon
                    $woocommerce->cart->remove_coupon($applied_coupon);
                    $flag_totals_for_refresh = true;

                    // Remove filter
                    remove_filter('woocommerce_coupons_enabled', array($this, 'woocommerce_enable_coupons'));
                }
            }
        }

        // Flag totals for refresh
        if ($flag_totals_for_refresh) {
            WC()->session->set('refresh_totals', true);
        }
    }

    /**
     * Invalidate no longer applicable coupons
     *
     * @access public
     * @param bool $is_valid
     * @param object $coupon
     * @return bool
     */
    public function coupon_is_valid($is_valid, $coupon)
    {
        // Get coupon code
        $code = RightPress_WC_Legacy::coupon_get_code($coupon);

        // Check if current coupon is cart discount
        if (RP_WCDPD_Controller_Methods_Cart_Discount::coupon_is_cart_discount($code)) {

            // Check if this cart discount is still applicable
            if (!isset($this->applicable_adjustments[$code])) {
                $is_valid = false;
            }
        }

        return $is_valid;
    }

    /**
     * Temporary enable WooCommerce coupons to be able to remove fake coupon
     *
     * @access public
     * @return string
     */
    public function woocommerce_enable_coupons()
    {
        return 'yes';
    }

    /**
     * Change fake coupon label
     *
     * @access public
     * @param string $label
     * @param object $coupon
     * @return string
     */
    public function change_fake_coupon_label($label, $coupon)
    {
        // Get coupon code
        $code = RightPress_WC_Legacy::coupon_get_code($coupon);

        // Combined simple cart discount handling
        if ($code === 'rp_wcdpd_combined') {
            return $this->get_combined_simple_cart_discount_label();
        }

        // Check if this is our fake coupon
        if (isset($this->applicable_adjustments[$code])) {

            // Get coupon label
            if ($method = $this->get_method($this->applicable_adjustments[$code]['rule'])) {
                $label = call_user_func($method['get_coupon_label_callback'], $this->applicable_adjustments[$code]);
            }
        }

        return $label;
    }

    /**
     * Change fake coupon value html
     *
     * @access public
     * @param string $html
     * @param object $coupon
     * @return string
     */
    public function change_fake_coupon_html($html, $coupon)
    {
        $code = RightPress_WC_Legacy::coupon_get_code($coupon);

        if (isset($this->applicable_adjustments[$code]) || $code === 'rp_wcdpd_combined') {
            return RP_WCDPD_Cart_Discounts::get_cart_discount_coupon_html($coupon);
        }

        return $html;
    }

    /**
     * Check if coupon is cart discount
     *
     * @access public
     * @param string $coupon_code
     * @return bool
     */
    public static function coupon_is_cart_discount($coupon_code)
    {
        return RightPress_Helper::string_begins_with_substring($coupon_code, 'rp_wcdpd_');
    }

    /**
     * Check if applicable simple cart discounts need to be combined
     *
     * @access public
     * @return bool
     */
    public function combine_simple()
    {
        return RP_WCDPD_Settings::get('cart_discounts_if_multiple_applicable') === 'combined' && count($this->applicable_adjustments) > 1;
    }

    /**
     * Get combined simple rule
     *
     * @access public
     * @return array
     */
    public function get_combined_simple_rule()
    {
        return array(
            'uid' => 'rp_wcdpd_combined'
        );
    }

    /**
     * Get combined simple cart discount label
     *
     * @access public
     * @return string
     */
    public function get_combined_simple_cart_discount_label()
    {
        $label = RP_WCDPD_Settings::get('cart_discounts_combined_title');
        return !RightPress_Helper::is_empty($label) ? $label : __('Discount', 'rp_wcdpd');
    }




}

RP_WCDPD_Controller_Methods_Cart_Discount::get_instance();

}
