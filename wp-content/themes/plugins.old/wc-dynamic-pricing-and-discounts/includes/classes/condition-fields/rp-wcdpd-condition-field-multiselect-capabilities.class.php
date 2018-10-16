<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Condition_Field_Multiselect')) {
    include_once('rp-wcdpd-condition-field-multiselect.class.php');
}

/**
 * Condition Field: Multiselect - Capabilities
 *
 * @class RP_WCDPD_Condition_Field_Multiselect_Capabilities
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Condition_Field_Multiselect_Capabilities')) {

class RP_WCDPD_Condition_Field_Multiselect_Capabilities extends RP_WCDPD_Condition_Field_Multiselect
{
    protected $key = 'capabilities';

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
        $this->hook();
    }

    /**
     * Load multiselect options
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public function load_multiselect_options($ids = array(), $query = '')
    {
        return RightPress_Conditions_Helper::get_all_capabilities($ids, $query);
    }





}

RP_WCDPD_Condition_Field_Multiselect_Capabilities::get_instance();

}
