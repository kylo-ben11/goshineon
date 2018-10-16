<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Parent condition class
 *
 * @class RP_WCDPD_Condition
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Condition')) {

abstract class RP_WCDPD_Condition extends RP_WCDPD_Item
{
    protected $item_key     = 'condition';
    protected $fields       = null;
    protected $main_field   = null;

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
            'method'                => $this->method,
            'fields'                => $this->fields,
            'contexts'              => $this->contexts,
            'is_cart'               => $this->is_cart,
            'check_callback'        => array($this, 'check'),
            'get_value_callback'    => array($this, 'get_value'),
        );
    }

    /**
     * Get main field key to compare value against
     *
     * @access public
     * @return string
     */
    public function get_main_field()
    {
        // Main field defined
        if (isset($this->main_field)) {
            return $this->main_field;
        }

        // At least one field defined
        if (is_array($this->fields)) {
            foreach (array('after', 'before') as $position) {
                if (!empty($this->fields[$position])) {
                    return $this->fields[$position][0];
                }
            }
        }

        return null;
    }

    /**
     * Check against condition
     *
     * @access public
     * @param array $params
     * @return bool
     */
    public function check($params)
    {
        // Load condition method
        if ($method = RP_WCDPD_Controller_Condition_Methods::get_item($this->method)) {

            // Get values to compare
            $value = $this->get_value($params);
            $condition_value = $this->get_condition_value($params);

            // Compare values if value is set
            if ($value !== null) {
                return call_user_func($method['check_callback'], $params['condition']['method_option'], $value, $condition_value);
            }
        }

        return false;
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
        return null;
    }

    /**
     * Get condition value
     *
     * @access public
     * @param array $params
     * @return mixed
     */
    public function get_condition_value($params)
    {
        // Load field
        if ($field_key = $this->get_main_field()) {
            if ($field = RP_WCDPD_Controller_Condition_Fields::get_item($field_key)) {
                if (isset($params['condition'][$field_key])) {

                    // Reference value
                    $value = $params['condition'][$field_key];

                    // Field supports hierarchy
                    if ($field['supports_hierarchy']) {
                        return call_user_func($field['get_children_callback'], $value);
                    }
                    // Field does not support hierarchy
                    else {
                        return $value;
                    }
                }
            }
        }

        return null;
    }



}
}
