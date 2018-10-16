<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load dependencies
if (!class_exists('RP_WCDPD_Condition_Field')) {
    include_once('rp-wcdpd-condition-field.class.php');
}

/**
 * Condition Field Group: Multiselect
 *
 * @class RP_WCDPD_Condition_Field_Multiselect
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Condition_Field_Multiselect')) {

abstract class RP_WCDPD_Condition_Field_Multiselect extends RP_WCDPD_Condition_Field
{
    protected $accepts_multiple = true;

    /**
     * Get custom item properties to register
     *
     * @access public
     * @return array
     */
    public function custom_item_properties()
    {
        return array(
            'supports_hierarchy'                        => $this->supports_hierarchy,
            'display_callback'                          => array($this, 'display'),
            'validate_callback'                         => array($this, 'validate'),
            'sanitize_callback'                         => array($this, 'sanitize'),
            'get_children_callback'                     => array($this, 'get_children'),
            'get_multiselect_options_callback'          => array($this, 'get_multiselect_options'),
            'get_multiselect_option_labels_callback'    => array($this, 'get_multiselect_option_labels'),
        );
    }

    /**
     * Display field
     *
     * @access public
     * @param string $context
     * @param string $alias
     * @return void
     */
    public function display($context, $alias = 'condition')
    {
        RP_WCDPD_Form::multiselect($this->get_field_attributes($context, $alias));
    }

    /**
     * Get options
     *
     * @access public
     * @return array
     */
    public function get_options()
    {
        return array();
    }

    /**
     * Get multiselect options
     *
     * @access public
     * @param array $params
     * @return array
     */
    public function get_multiselect_options($params = array())
    {
        // Do not load options if params are not set
        if (empty($params)) {
            return array();
        }

        // Load multiselect field options
        $multiselect_options = $this->load_multiselect_options(array(), $params['query']);

        // Remove options that do not match criteria
        foreach ($multiselect_options as $option_key => $option) {
            if (!RightPress_Helper::string_contains_phrase($option['text'], $params['query']) || (!empty($params['selected']) && in_array($option['id'], $params['selected'], true))) {
                unset($multiselect_options[$option_key]);
            }
        }

        // Return options
        return $multiselect_options;
    }

    /**
     * Get multiselect option labels by ids
     *
     * @access public
     * @param array $value
     * @return array
     */
    public function get_multiselect_option_labels($value)
    {
        // Load multiselect options
        $multiselect_options = $this->load_multiselect_options($value);

        // Get existing option ids
        $existing_ids = wp_list_pluck($multiselect_options, 'id');

        // Add missing options
        foreach ($value as $id) {
            if (!in_array($id, $existing_ids, true)) {
                $multiselect_options[] = array(
                    'id'    => $id,
                    'text'  => (is_numeric($id) ? '#' : '') . $id . ' ' . __('(DELETED)', 'rp_wcdpd'),
                );
            }
        }

        return $multiselect_options;
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
        return array();
    }

    /**
     * Get class
     *
     * @access public
     * @param string $context
     * @param string $alias
     * @return string
     */
    public function get_class($context, $alias = 'condition')
    {
        return 'rp_wcdpd_' . $context . '_' . $alias . '_' . $this->key . ' rp_wcdpd_child_element_field rp_wcdpd_select2';
    }

    /**
     * Sanitize field value
     *
     * @access public
     * @param array $posted
     * @param array $condition
     * @param string $method_option_key
     * @return mixed
     */
    public function sanitize($posted, $condition, $method_option_key)
    {
        if (isset($posted[$this->key]) && !RightPress_Helper::is_empty($posted[$this->key])) {
            return (array) $posted[$this->key];
        }

        return array();
    }





}
}
