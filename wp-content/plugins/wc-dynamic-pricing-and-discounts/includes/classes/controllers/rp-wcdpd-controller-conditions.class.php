<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Controller')) {
    include_once('rp-wcdpd-controller.class.php');
}

/**
 * Conditions controller
 *
 * @class RP_WCDPD_Controller_Conditions
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Controller_Conditions')) {

class RP_WCDPD_Controller_Conditions extends RP_WCDPD_Controller
{
    protected $item_key             = 'condition';
    protected $items_are_grouped    = true;

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
        // Ajax handlers
        add_action('wp_ajax_rp_wcdpd_load_multiselect_options', array($this, 'ajax_load_multiselect_options'));
    }

    /**
     * Load multiselect options
     *
     * @access public
     * @return void
     */
    public function ajax_load_multiselect_options()
    {
        $options = array();

        // Check if required vars are set
        if (!empty($_POST['type']) && !empty($_POST['query'])) {

            // Load corresponding condition field
            if ($field = RP_WCDPD_Controller_Condition_Fields::get_item($_POST['type'])) {

                // Get options
                $options = call_user_func($field['get_multiselect_options_callback'], array(
                    'query'     => $_POST['query'],
                    'selected'  => (!empty($_POST['selected']) && is_array($_POST['selected'])) ? $_POST['selected'] : array(),
                ));
            }
        }

        // Return data and exit
        echo RightPress_Helper::json_encode_multiselect_options(array('results' => array_values($options)));
        exit;
    }




}

RP_WCDPD_Controller_Conditions::get_instance();

}
