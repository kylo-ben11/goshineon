<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Methods related to Settings
 *
 * @class RP_WCDPD_Settings
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Settings')) {

class RP_WCDPD_Settings
{
    // Track settings structure versions
    protected static $version = '1';

    // Define settings structure
    protected static $structure = null;
    protected static $options = array();

    // Define contexts
    private static $contexts = array(
        'product_pricing',
        'cart_discounts',
        'checkout_fees',
    );

    // Keep settings in memory
    protected $settings  = array();

    // Cache settings revision
    protected $settings_revision = null;

    // Define options for some product pricing rule select fields
    protected static $quantities_based_on_methods = null;
    protected static $group_quantities_based_on_methods = null;
    protected static $exclusivity_methods = null;
    protected static $receive_products_methods = null;

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
        // Load settings now
        $this->load_settings();

        // Register settings
        add_action('admin_init', array($this, 'register_settings'));

        // Add link to menu
        add_action('admin_menu', array($this, 'add_to_menu'), 12);

        // Pass configuration to Javascript
        add_action('admin_enqueue_scripts', array($this, 'configuration_to_javascript'), 999);

        // Enqueue templates to be rendered in footer
        add_action('admin_footer', array($this, 'render_templates_in_footer'));

        // Custom capability for settings
        add_filter('option_page_capability_rp_wcdpd_settings_group_product_pricing', array($this, 'custom_settings_capability'));
        add_filter('option_page_capability_rp_wcdpd_settings_group_cart_discounts', array($this, 'custom_settings_capability'));
        add_filter('option_page_capability_rp_wcdpd_settings_group_checkout_fees', array($this, 'custom_settings_capability'));
        add_filter('option_page_capability_rp_wcdpd_settings_group_settings', array($this, 'custom_settings_capability'));

        // Migration notices
        add_action('admin_notices', array($this, 'maybe_display_migration_notice'), 1);

        // Delete migration notice
        $this->hide_migration_notice();
    }

    /**
     * Get settings structure
     *
     * @access public
     * @return array
     */
    public static function get_structure()
    {
        if (self::$structure === null) {
            self::$structure = array(
                'product_pricing' => array(
                    'title' => __('Product Pricing', 'rp_wcdpd'),
                    'children' => array(
                        'product_pricing_rules' => array(
                            'title' => __('Product Pricing Settings', 'rp_wcdpd'),
                            'children' => array(
                                'product_pricing_rule_selection_method' => array(
                                    'title'     => __('Rule selection method', 'rp_wcdpd'),
                                    'type'      => 'grouped_select',
                                    'default'   => 'first',
                                    'required'  => true,
                                    'options'   => array(
                                        'all'   => array(
                                            'label'     => __('Apply All', 'rp_wcdpd'),
                                            'options'  => array(
                                                'all' => __('Apply all applicable rules', 'rp_wcdpd'),
                                            ),
                                        ),
                                        'one'   => array(
                                            'label'     => __('Apply One', 'rp_wcdpd'),
                                            'options'  => array(
                                                'first'     => __('Apply first applicable rule', 'rp_wcdpd'),
                                                'smaller_price' => __('Apply rule for smaller price', 'rp_wcdpd'),
                                                'bigger_price'  => __('Apply rule for bigger price', 'rp_wcdpd'),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'cart_discounts' => array(
                    'title' => __('Cart Discounts', 'rp_wcdpd'),
                    'children' => array(
                        'cart_discounts_rules' => array(
                            'title' => __('Cart Discounts Settings', 'rp_wcdpd'),
                            'children' => array(
                                'cart_discounts_rule_selection_method' => array(
                                    'title'     => __('Rule selection method', 'rp_wcdpd'),
                                    'type'      => 'grouped_select',
                                    'default'   => 'first',
                                    'required'  => true,
                                    'options'   => array(
                                        'all'   => array(
                                            'label'     => __('Apply All', 'rp_wcdpd'),
                                            'options'  => array(
                                                'all'   => __('Apply all applicable rules', 'rp_wcdpd'),
                                            ),
                                        ),
                                        'one'   => array(
                                            'label'     => __('Apply One', 'rp_wcdpd'),
                                            'options'  => array(
                                                'first'             => __('Apply first applicable rule', 'rp_wcdpd'),
                                                'bigger_discount'   => __('Apply bigger discount', 'rp_wcdpd'),
                                                'smaller_discount'  => __('Apply smaller discount', 'rp_wcdpd'),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'checkout_fees' => array(
                    'title' => __('Checkout Fees', 'rp_wcdpd'),
                    'children' => array(
                        'checkout_fees_rules' => array(
                            'title' => __('Checkout Fees Settings', 'rp_wcdpd'),
                            'children' => array(
                                'checkout_fees_rule_selection_method' => array(
                                    'title'     => __('Rule selection method', 'rp_wcdpd'),
                                    'type'      => 'grouped_select',
                                    'default'   => 'first',
                                    'required'  => true,
                                    'options'   => array(
                                        'all'   => array(
                                            'label'     => __('Apply All', 'rp_wcdpd'),
                                            'options'  => array(
                                                'all'   => __('Apply all applicable rules', 'rp_wcdpd'),
                                            ),
                                        ),
                                        'one'   => array(
                                            'label'     => __('Apply One', 'rp_wcdpd'),
                                            'options'  => array(
                                                'first'         => __('Apply first applicable rule', 'rp_wcdpd'),
                                                'bigger_fee'    => __('Apply bigger fee', 'rp_wcdpd'),
                                                'smaller_fee'   => __('Apply smaller fee', 'rp_wcdpd'),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'promo' => array(
                    'title' => __('Promotion', 'rp_wcdpd'),
                    'children' => array(
                        'display_price_override' => array(
                            'title' => __('Change Display Prices', 'rp_wcdpd'),
                            'children' => array(
                                'promo_display_price_override' => array(
                                    'title'     => __('Apply simple rules to display prices', 'rp_wcdpd'),
                                    'type'      => 'checkbox',
                                    'default'   => '0',
                                ),
                                'promo_display_price_override_cart_conditions' => array(
                                    'title'     => __('Include rules with cart conditions', 'rp_wcdpd'),
                                    'type'      => 'checkbox',
                                    'default'   => '0',
                                    'hint'      => __('Warning! If you leave this unchecked, pricing rules with conditions like <strong>products in cart</strong> or <strong>coupons applied</strong> will not be included. However, including rules with complex cart conditions may confuse customers when prices change unexpectedly.', 'rp_wcdpd'),
                                ),
                            ),
                        ),
                        'volume_pricing_table' => array(
                            'title' => __('Volume Pricing Table', 'rp_wcdpd'),
                            'children' => array(
                                'promo_volume_pricing_table' => array(
                                    'title'     => __('Display pricing table', 'rp_wcdpd'),
                                    'type'      => 'checkbox',
                                    'default'   => '0',
                                ),
                                'promo_volume_pricing_table_title' => array(
                                    'title'     => __('Title', 'rp_wcdpd'),
                                    'type'      => 'text',
                                    'default'   => __('Quantity discounts', 'rp_wcdpd'),
                                    'required'  => false,
                                ),
                                'promo_volume_pricing_table_position' => array(
                                    'title'     => __('Position', 'rp_wcdpd'),
                                    'type'      => 'select',
                                    'default'   => 'woocommerce_before_add_to_cart_form',
                                    'required'  => true,
                                    'options'   => array(
                                        'woocommerce_before_add_to_cart_form'       => __('Above add to cart', 'rp_wcdpd'),
                                        'woocommerce_after_add_to_cart_form'        => __('Below add to cart', 'rp_wcdpd'),
                                        'woocommerce_product_meta_end'              => __('Below product meta', 'rp_wcdpd'),
                                        'woocommerce_single_product_summary'        => __('Above product summary', 'rp_wcdpd'),
                                        'woocommerce_after_single_product_summary'  => __('Below product summary', 'rp_wcdpd'),
                                        'woocommerce_after_main_content'            => __('Below page content', 'rp_wcdpd'),
                                    ),
                                ),
                                'promo_volume_pricing_table_layout' => array(
                                    'title'     => __('Layout', 'rp_wcdpd'),
                                    'type'      => 'grouped_select',
                                    'default'   => 'horizontal',
                                    'required'  => true,
                                    'options'   => array(
                                        'inline'    => array(
                                            'label'     => __('Inline', 'rp_wcdpd'),
                                            'options'   => array(
                                                'inline-horizontal'    => __('Inline - horizontal', 'rp_wcdpd'),
                                                'inline-vertical'      => __('Inline - vertical', 'rp_wcdpd'),
                                            ),
                                        ),
                                        'modal'    => array(
                                            'label'     => __('Modal', 'rp_wcdpd'),
                                            'options'   => array(
                                                'modal-horizontal'    => __('Modal - horizontal', 'rp_wcdpd'),
                                                'modal-vertical'      => __('Modal - vertical', 'rp_wcdpd'),
                                            ),
                                        ),
                                    ),
                                ),
                                'promo_volume_pricing_table_variation_layout' => array(
                                    'title'     => __('Variable product handling', 'rp_wcdpd'),
                                    'type'      => 'select',
                                    'default'   => 'multiple',
                                    'required'  => true,
                                    'options'   => array(
                                        'multiple'  => __('Display individual pricing tables', 'rp_wcdpd'),
                                        'single'    => __('Display one pricing table with all variations', 'rp_wcdpd'),
                                    ),
                                ),
                                'promo_volume_pricing_table_missing_ranges' => array(
                                    'title'     => __('If quantity range is not defined', 'rp_wcdpd'),
                                    'type'      => 'select',
                                    'default'   => 'display',
                                    'required'  => true,
                                    'options'   => array(
                                        'display'   => __('Display missing range with regular price', 'rp_wcdpd'),
                                        'hide'      => __('Do not display missing range', 'rp_wcdpd'),
                                        // Not ready for production (issues with other items in cart and "Quantities Based On" setting
                                        // 'block'     => __('Do not allow to purchase', 'rp_wcdpd'),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                'settings' => array(
                    'title' => __('Settings', 'rp_wcdpd'),
                    'children' => array(
                        /*'general_settings' => array(
                            'title' => __('General', 'rp_wcdpd'),
                            'children' => array(
                                'decimal_quantities' => array(
                                    'title'     => __('Decimal quantity support', 'rp_wcdpd'),
                                    'type'      => 'checkbox',
                                    'default'   => '0',
                                ),
                            ),
                         ),*/
                        'product_pricing_settings' => array(
                            'title' => __('Product Pricing', 'rp_wcdpd'),
                            'children' => array(
                                'product_pricing_sale_price_handling' => array(
                                    'title'     => __('If product is already on sale', 'rp_wcdpd'),
                                    'type'      => 'select',
                                    'default'   => 'sale',
                                    'required'  => true,
                                    'options'   => array(
                                        'sale'      => __('Use sale price', 'rp_wcdpd'),
                                        'regular'   => __('Use regular price', 'rp_wcdpd'),
                                        'exclude'   => __('Exclude products that are already on sale', 'rp_wcdpd'),
                                    ),
                                    'hint'      => __('Affects products that have a sale price set in product settings if at least one pricing rule is applicable.', 'rp_wcdpd'),
                                ),
                                'product_pricing_irrational_price_handling' => array(
                                    'title'     => __('If discounted price is irrational', 'rp_wcdpd'),
                                    'type'      => 'select',
                                    'default'   => 'do_not_round',
                                    'required'  => true,
                                    'options'   => array(
                                        'round'         => __('Prefer correct calculation', 'rp_wcdpd'),
                                        'do_not_round'  => __('Prefer desired subtotal', 'rp_wcdpd'),
                                    ),
                                    'hint'      => __('For example, a 3 for $10 rule results in a per-item price of $3.33(3).<br>The first option gives a correct calculation: $3.33 * 3 = $9.99.<br>The second option gives a desired subtotal: $3.33 * 3 = $10.00.', 'rp_wcdpd'),
                                ),
                                'product_pricing_display_regular_price' => array(
                                    'title'     => __('Display regular price when discounting', 'rp_wcdpd'),
                                    'type'      => 'checkbox',
                                    'default'   => '1',
                                ),
                            ),
                        ),
                        'cart_discounts_settings' => array(
                            'title' => __('Cart Discounts', 'rp_wcdpd'),
                            'children' => array(
                                'cart_discounts_if_multiple_applicable' => array(
                                    'title'     => __('If multiple discounts are applicable', 'rp_wcdpd'),
                                    'type'      => 'select',
                                    'default'   => 'individual',
                                    'required'  => true,
                                    'options'   => array(
                                        'individual'    => __('Display all individual discounts', 'rp_wcdpd'),
                                        'combined'      => __('Combine to one total discount', 'rp_wcdpd'),
                                    ),
                                ),
                                'cart_discounts_combined_title' => array(
                                    'title'     => __('Combined discount title', 'rp_wcdpd'),
                                    'type'      => 'text',
                                    'default'   => __('Discount', 'rp_wcdpd'),
                                    'required'  => true,
                                ),
                                'cart_discounts_apply_with_individual_use_coupons' => array(
                                    'title'     => __('Apply with individual use coupons', 'rp_wcdpd'),
                                    'type'      => 'checkbox',
                                    'default'   => '1',
                                ),
                            ),
                        ),
                        'checkout_fees_settings' => array(
                            'title' => __('Checkout Fees', 'rp_wcdpd'),
                            'children' => array(
                                'checkout_fees_if_multiple_applicable' => array(
                                    'title'     => __('If multiple fees are applicable', 'rp_wcdpd'),
                                    'type'      => 'select',
                                    'default'   => 'individual',
                                    'required'  => true,
                                    'options'   => array(
                                        'individual'    => __('Display all individual fees', 'rp_wcdpd'),
                                        'combined'      => __('Combine to one total fee', 'rp_wcdpd'),
                                    ),
                                    // Use this hint if we switch to per-rule tax classes
                                    // 'hint'      => __('If you choose to combine multiple fees to one fee and fees have different tax classes, they will be grouped by tax class and multiple combined fees will be displayed (one per tax class).', 'rp_wcdpd'),
                                ),
                                'checkout_fees_combined_title' => array(
                                    'title'     => __('Combined fee title', 'rp_wcdpd'),
                                    'type'      => 'text',
                                    'default'   => __('Fee', 'rp_wcdpd'),
                                    'required'  => true,
                                ),
                                'checkout_fees_tax_class' => array(
                                    'title'     => __('Tax class', 'rp_wcdpd'),
                                    'type'      => 'select',
                                    'default'   => 'standard',
                                    'required'  => true,
                                    'options'   => RightPress_Helper::get_wc_tax_class_list(array('rp_wcdpd_not_taxable' => __('Not Taxable', 'rp_wcdpd'))),
                                ),
                            ),
                        ),
                    ),
                ),
            );
        }

        return self::$structure;
    }

    /**
     * Load settings
     *
     * @access public
     * @return void
     */
    public function load_settings()
    {
        // Load any stored settings
        $stored = get_option('rp_wcdpd_settings', array());

        // Attempt to migrate settings from older version if none were found
        if (empty($stored) || empty($stored[self::$version])) {
            require_once 'lazy/rp-wcdpd-settings-migration.class.php';
            $stored = RP_WCDPD_Settings_Migration::migrate($stored);
        }

        // Get settings of current version
        $stored = (is_array($stored) && isset($stored[self::$version])) ? $stored[self::$version] : array();

        // Iterate over field structure and either assign stored value or revert to default value
        foreach (self::get_structure() as $tab_key => $tab) {
            foreach ($tab['children'] as $section_key => $section) {
                foreach ($section['children'] as $field_key => $field) {

                    // Set value
                    if (isset($stored[$field_key])) {
                        $this->settings[$field_key] = $stored[$field_key];
                    }
                    else {
                        $this->settings[$field_key] = isset($field['default']) ? $field['default'] : null;
                    }

                    // Set options
                    if (!empty($field['options'])) {
                        self::$options[$field_key] = $field['options'];
                    }
                }
            }
        }

        // Load rules
        foreach (self::$contexts as $rule_type) {
            $this->settings[$rule_type] = (!empty($stored[$rule_type]) && is_array($stored[$rule_type])) ? $stored[$rule_type] : array();
        }

        // Pre-2.1 compatibility for BOGO rule products to get
        $this->fix_bogo_get_products_pre_2_1();
    }

    /**
     * Get options for select fields
     *
     * @access public
     * @param string $key
     * @return array
     */
    public static function get_options($key)
    {
        return isset(self::$options[$key]) ? self::$options[$key] : array();
    }

    /**
     * Register settings with WordPress
     *
     * @access public
     * @return void
     */
    public function register_settings()
    {
        // Check if current user can manage plugin settings
        if (!RP_WCDPD::is_admin()) {
            return;
        }

        // Iterate over tabs
        foreach (self::get_structure() as $tab_key => $tab) {

            // Register tab
            register_setting(
                'rp_wcdpd_settings_group_' . $tab_key,
                'rp_wcdpd_settings',
                array($this, 'validate_settings')
            );

            // Iterate over sections
            foreach ($tab['children'] as $section_key => $section) {

                $settings_page_id = 'rp-wcdpd-admin-' . str_replace('_', '-', $tab_key);

                // Register section
                add_settings_section(
                    $section_key,
                    $section['title'],
                    array($this, 'print_section_info'),
                    $settings_page_id
                );

                // Iterate over fields
                foreach ($section['children'] as $field_key => $field) {

                    // Do not display checkout fees tax class setting if tax calculation is disabled
                    if ($field_key === 'checkout_fees_tax_class' && !wc_tax_enabled()) {
                        continue;
                    }

                    // Register field
                    add_settings_field(
                        'rp_wcdpd_' . $field_key,
                        $field['title'],
                        array($this, 'print_field_' . $field['type']),
                        $settings_page_id,
                        $section_key,
                        array(
                            'field_key'             => $field_key,
                            'field'                 => $field,
                            'data-rp-wcdpd-hint'    => !empty($field['hint']) ? $field['hint'] : null,
                        )
                    );
                }
            }
        }
    }

    /**
     * Get all settings in array
     *
     * @access public
     * @return array
     */
    public static function get_all()
    {
        $instance = self::get_instance();
        return $instance->settings;
    }

    /**
     * Get value of a single setting
     *
     * @access public
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $instance = self::get_instance();

        // Get settings value
        $value = isset($instance->settings[$key]) ? $instance->settings[$key] : $default;

        // Allow developers to override value and return it
        return apply_filters('rp_wcdpd_settings_value', $value, $key);
    }

    /*
     * Update value of a single setting
     *
     * @access public
     * @return bool
     */
    public static function update($key, $value)
    {
        // User not allowed to update settings
        if (!RP_WCDPD::is_admin()) {
            return false;
        }

        $instance = self::get_instance();

        // Setting must be defined in self::$structure
        if (!isset($instance->settings[$key])) {
            return;
        }

        // Assign new value
        $instance->settings[$key] = $value;

        // Store settings
        return update_option('rp_wcdpd_settings', array(self::$version => $instance->settings));
    }

    /**
     * Add Settings link to menu
     *
     * @access public
     * @return void
     */
    public function add_to_menu()
    {
        add_submenu_page(
            'woocommerce',
            __('Pricing & Discounts', 'rp_wcdpd'),
            __('Pricing & Discounts', 'rp_wcdpd'),
            RP_WCDPD::get_admin_capability(),
            'rp_wcdpd_settings',
            array('RP_WCDPD_Settings', 'print_settings_page')
        );
    }

    /**
     * Print settings page
     *
     * @access public
     * @return void
     */
    public static function print_settings_page()
    {
        // Get current tab
        $current_tab = RP_WCDPD_Settings::get_tab();

        // Open form container
        echo '<div class="wrap woocommerce"><form method="post" action="options.php" enctype="multipart/form-data">';

        // Print notices
        settings_errors('rp_wcdpd');

        // Print header
        include RP_WCDPD_PLUGIN_PATH . 'includes/views/settings/header.php';

        // Print settings page content
        include RP_WCDPD_PLUGIN_PATH . 'includes/views/settings/fields.php';

        // Print footer
        include RP_WCDPD_PLUGIN_PATH . 'includes/views/settings/footer.php';

        // JS UI preloader
        include RP_WCDPD_PLUGIN_PATH . 'includes/views/settings/preloader.php';

        // Close form container
        echo '</form></div>';
    }

    /**
     * Get current settings tab
     *
     * @access public
     * @return string
     */
    public static function get_tab()
    {
        $structure = RP_WCDPD_Settings::get_structure();

        // Check if we know tab identifier
        if (isset($_GET['tab']) && isset($structure[$_GET['tab']])) {
            return $_GET['tab'];
        }
        else {
            $array_keys = array_keys($structure);
            return array_shift($array_keys);
        }
    }

    /**
     * Print section info
     *
     * @access public
     * @param array $section
     * @return void
     */
    public function print_section_info($section)
    {
        // Volume Pricing Table
        if ($section['id'] === 'volume_pricing_table') {
            echo '<p>' . __('Volume pricing table displays potential savings that come with higher quantities purchased.', 'rp_wcdpd') . '<br>' .
                  __('This feature only works for bulk and tier pricing methods with no cart related conditions.', 'rp_wcdpd') . '</p>';
        }
        // Display Price Override
        else if ($section['id'] === 'display_price_override') {
            echo '<p>' .
                  __('By default, pricing rules are applied once product is added to the cart.', 'rp_wcdpd') . '<br>' .
                  __('You may choose to apply <strong>Simple</strong> rules to product display prices throughout the shop.', 'rp_wcdpd') . '<br>' .
                  __('Please note that Volume, Group and BOGO rules are not included. This functionality may slightly increase page load time.', 'rp_wcdpd') .
                  '</p>';
        }
    }

    /**
     * Render text field
     *
     * @access public
     * @param array $args
     * @param string $field_type
     * @return void
     */
    public function print_field_text($args = array(), $field_type = null)
    {
        // Get prefixed key
        $prefixed_key = 'rp_wcdpd_' . $args['field_key'];

        // Configure field
        $config = array(
            'id'            => $prefixed_key,
            'name'          => 'rp_wcdpd_settings[' . $prefixed_key . ']',
            'value'         => RP_WCDPD_Settings::get($args['field_key']),
            'class'         => 'rp_wcdpd_setting rp_wcdpd_field_long',
            'title'         => !empty($args['title']) ? $args['title'] : '',
            'placeholder'   => (isset($args['field']['placeholder']) && !RightPress_Helper::is_empty($args['field']['placeholder'])) ? $args['field']['placeholder'] : '',
        );

        // Check if field is required
        if (!empty($args['field']['required'])) {
            $config['required'] = 'required';
        }

        // Get field type
        $field_type = $field_type ?: 'text';

        // Print field
        RP_WCDPD_Form::$field_type($config);
    }

    /**
     * Render number field
     *
     * @access public
     * @param array $args
     * @return void
     */
    public function print_field_number($args = array())
    {
        self::print_field_text($args, 'number');
    }

    /**
     * Render checkbox field
     *
     * @access public
     * @param array $args
     * @return void
     */
    public function print_field_checkbox($args = array())
    {
        // Get prefixed key
        $prefixed_key = 'rp_wcdpd_' . $args['field_key'];

        // Print field
        RP_WCDPD_Form::checkbox(array(
            'id'        => $prefixed_key,
            'name'      => 'rp_wcdpd_settings[' . $prefixed_key . ']',
            'checked'   => (bool) RP_WCDPD_Settings::get($args['field_key']),
            'class'     => 'rp_wcdpd_setting',
            'title'     => !empty($args['title']) ? $args['title'] : '',
        ));
    }

    /**
     * Render select field
     *
     * @access public
     * @param array $args
     * @param bool $is_multiselect
     * @param bool $is_grouped
     * @return void
     */
    public function print_field_select($args = array(), $is_multiselect = false, $is_grouped = false)
    {
        // Get prefixed key
        $prefixed_key = 'rp_wcdpd_' . $args['field_key'];

        // Get value
        $value = RP_WCDPD_Settings::get($args['field_key']);

        // Get options
        $options = RP_WCDPD_Settings::get_options($args['field_key']);

        // Fix multiselect options
        // Note: this is designed to work with user-entered "tags" with no predefined options list
        if ($is_multiselect && empty($options)) {
            $options = $value;
        }

        // Print field
        RP_WCDPD_Form::select(array(
            'id'                    => $prefixed_key,
            'name'                  => 'rp_wcdpd_settings[' . $prefixed_key . ']' . ($is_multiselect ? '[]' : ''),
            'options'               => $options,
            'value'                 => $value,
            'class'                 => 'rp_wcdpd_setting rp_wcdpd_field_select rp_wcdpd_field_long',
            'title'                 => !empty($args['title']) ? $args['title'] : '',
            'data-rp-wcdpd-hint'    => !empty($args['data-rp-wcdpd-hint']) ? $args['data-rp-wcdpd-hint'] : '',
        ), $is_multiselect, $is_grouped);
    }

    /**
     * Render grouped select field
     *
     * @access public
     * @param array $args
     * @return void
     */
    public function print_field_grouped_select($args = array())
    {
        self::print_field_select($args, false, true);
    }

    /**
     * Render multiselect field
     *
     * @access public
     * @param array $args
     * @return void
     */
    public function print_field_multiselect($args = array())
    {
        self::print_field_select($args, true);
    }

    /**
     * Print settings field manually
     *
     * @access public
     * @param string $field_key
     * @return void
     */
    public static function print_settings_field($field_key)
    {
        $instance = RP_WCDPD_Settings::get_instance();

        // Find field
        foreach (self::get_structure() as $tab_key => $tab) {
            foreach ($tab['children'] as $section_key => $section) {
                foreach ($section['children'] as $current_field_key => $field) {
                    if ($current_field_key === $field_key) {

                        $method = 'print_field_' . $field['type'];

                        // Print field
                        $instance->$method(array(
                            'field_key' => $field_key,
                            'field'     => $field,
                            'title'     => !empty($field['hint']) ? $field['hint'] : null,
                        ));

                        return;
                    }
                }
            }
        }
    }

    /**
     * Validate settings
     *
     * @access public
     * @param array $input
     * @return void
     */
    public function validate_settings($input)
    {
        $structure = RP_WCDPD_Settings::get_structure();

        // Track if this is a first or a second call to this function
        // When settings are saved for the first time, WordPress calls
        // it twice and $input is different on a second call
        if (!defined('rp_wcdpd_settings_validated')) {
            define('rp_wcdpd_settings_validated', true);
            $settings_already_validated = false;
            $field_key_prefix = 'rp_wcdpd_';
        }
        else {
            $settings_already_validated = true;
            $field_key_prefix = '';
            $input = $input[self::$version];
        }

        // Use serialized input data if available
        if (!empty($_POST['rp_wcdpd_settings_serialized']) && !$settings_already_validated) {

            $unserialized_vars = array();

            // Explode vars
            $exploded_vars = explode('&', $_POST['rp_wcdpd_settings_serialized']);

            // Iterate over vars
            foreach ($exploded_vars as $var) {

                // Parse var
                $parsed_var = array();
                parse_str($var, $parsed_var);

                // Merge with main array
                if (!empty($parsed_var['rp_wcdpd_settings']) && is_array($parsed_var['rp_wcdpd_settings'])) {
                    $unserialized_vars = RightPress_Helper::array_merge_recursive_for_indexed_lists($unserialized_vars, $parsed_var['rp_wcdpd_settings']);
                }
            }

            $input = !empty($unserialized_vars) ? $unserialized_vars : $input;
        }

        // Set output to current settings first
        $output = $this->settings;
        $field_array = array();
        $errors = array();

        // Attempt to validate settings
        try {

            // Check if request came from a correct page
            if (empty($_POST['current_tab']) || !isset($structure[$_POST['current_tab']])) {
                throw new Exception(__('Unable to validate settings.', 'rp_wcdpd'));
            }

            // Iterate over fields and validate new values
            foreach ($structure[$_POST['current_tab']]['children'] as $section_key => $section) {
                foreach ($section['children'] as $field_key => $field) {

                    $full_key = $field_key_prefix . $field_key;

                    switch($field['type']) {

                        // Checkbox
                        case 'checkbox':
                            $output[$field_key] = empty($input[$full_key]) ? '0' : '1';
                            break;

                        // Select
                        case 'select':
                            if (isset($input[$full_key]) && isset($field['options'][$input[$full_key]])) {
                                $output[$field_key] = $input[$full_key];
                            }
                            break;

                        // Grouped select
                        case 'grouped_select':
                            if (isset($input[$full_key])) {
                                foreach ($field['options'] as $option_group) {
                                    if (isset($option_group['options'][$input[$full_key]])) {
                                        $output[$field_key] = $input[$full_key];
                                    }
                                }
                            }
                            break;

                        // Multiselect
                        // Note: this is designed to work with user-entered "tags" with no predefined options list
                        case 'multiselect':
                            $output[$field_key] = array();

                            if (!empty($input[$full_key]) && is_array($input[$full_key])) {
                                foreach ($input[$full_key] as $multiselect_value) {
                                    $sanitized = sanitize_key($multiselect_value);
                                    $output[$field_key][$sanitized] = $sanitized;
                                }
                            }

                            $output[$field_key] = array_unique($output[$field_key]);

                            break;

                        // Number
                        // Note: currently float values will be trimmed to int values
                        case 'number':
                            if (isset($input[$full_key]) && is_numeric($input[$full_key])) {
                                $output[$field_key] = (int) esc_attr(trim($input[$full_key]));
                            }
                            else {
                                $output[$field_key] = '';
                            }
                            break;

                        // Text input
                        default:
                            if (isset($input[$full_key])) {
                                $output[$field_key] = esc_attr(trim($input[$full_key]));
                            }
                            break;
                    }
                }
            }

            // Validate rules
            foreach (self::$contexts as $rule_type) {
                if ($_POST['current_tab'] === $rule_type) {
                    $output[$rule_type] = RP_WCDPD_Settings::validate_rules($rule_type, $input);
                }
            }

            // Add notice
            if (!$settings_already_validated) {
                add_settings_error(
                    'rp_wcdpd',
                    'rp_wcdpd_settings_updated',
                    __('Settings updated.', 'rp_wcdpd'),
                    'updated'
                );
            }

        } catch (Exception $e) {

            // Add error
            add_settings_error(
                'rp_wcdpd',
                'rp_wcdpd_settings_validation_failed',
                $e->getMessage()
            );
        }

        // Store new settings
        return array(self::$version => $output);
    }

    /**
     * Validate rules
     *
     * @access public
     * @param string $rule_type
     * @param array $input
     * @return array
     */
    public static function validate_rules($rule_type, $input)
    {
        $output = array();

        // Only proceed if some data was passed in
        if (!empty($input[$rule_type]) && is_array($input[$rule_type])) {

            // Iterate over rules of this type
            foreach ($input[$rule_type] as $posted) {

                $current = array();
                $is_bogo = false;

                // Unique identifier
                if (!empty($posted['uid'])) {
                    $current['uid'] = $posted['uid'];
                }
                else {
                    $current['uid'] = 'rp_wcdpd_' . RightPress_Helper::get_hash();
                }

                // Exclusivity
                if (isset($posted['exclusivity']) && RP_WCDPD_Settings::exclusivity_method_exists($posted['exclusivity'])) {
                    $current['exclusivity'] = $posted['exclusivity'];
                }
                else if ($posted['method'] === 'exclude') {
                    $current['exclusivity'] = null;
                }
                else {
                    return false;
                }

                // Title
                if (in_array($rule_type, array('cart_discounts', 'checkout_fees'), true)) {
                    if (isset($posted['title']) && !RightPress_Helper::is_empty($posted['title'])) {
                        $current['title'] = (string) $posted['title'];
                    }
                    else {
                        continue;
                    }
                }

                // Private note
                if (isset($posted['note']) && !RightPress_Helper::is_empty($posted['note'])) {
                    $current['note'] = (string) $posted['note'];
                }
                else {
                    $current['note'] = '';
                }

                // Pricing method and pricing value
                if (in_array($rule_type, array('cart_discounts', 'checkout_fees'), true) || ($rule_type === 'product_pricing' && !empty($posted['method']) && in_array($posted['method'], array('simple', 'group', 'group_repeat', 'bogo', 'bogo_repeat'), true))) {

                    $is_simple  = (in_array($rule_type, array('cart_discounts', 'checkout_fees'), true) || ($rule_type === 'product_pricing' && $posted['method'] === 'simple'));
                    $is_group   = ($rule_type === 'product_pricing' && in_array($posted['method'], array('group', 'group_repeat'), true));
                    $is_bogo    = ($rule_type === 'product_pricing' && in_array($posted['method'], array('bogo', 'bogo_repeat'), true));

                    $method_key = ($is_group ? 'group_pricing_method' : ($is_bogo ? 'bogo_pricing_method' : 'pricing_method'));
                    $value_key = ($is_group ? 'group_pricing_value' : ($is_bogo ? 'bogo_pricing_value' : 'pricing_value'));

                    $pricing_method_context = $rule_type . ($is_simple ? '_simple' : ($is_group ? '_group' : ($is_bogo ? '_bogo' : '')));

                    // Pricing method
                    if (isset($posted[$method_key]) && RP_WCDPD_Pricing::pricing_method_exists($posted[$method_key], $pricing_method_context)) {
                        $current[$method_key] = $posted[$method_key];
                    }
                    else {
                        continue;
                    }

                    // Pricing value
                    if (isset($posted[$value_key]) && !RightPress_Helper::is_empty($posted[$value_key])) {

                        // Sanitize value
                        $sanitized = RP_WCDPD_Settings::sanitize_numeric_value($posted[$value_key]);

                        // Check if sanitization succeeded
                        if ($sanitized !== false) {
                            $current[$value_key] = abs((float) $sanitized);
                        }
                        else {
                            continue;
                        }
                    }
                    else {
                        continue;
                    }
                }

                // Fields specific to current rule type
                if (method_exists('RP_WCDPD_Settings', 'validate_' . $rule_type . '_rule')) {

                    // Validate
                    $method = 'validate_' . $rule_type . '_rule';
                    $current = RP_WCDPD_Settings::$method($current, $posted);

                    // Validation failed
                    if ($current === false) {
                        continue;
                    }
                }

                // Conditions
                $current['conditions'] = RP_WCDPD_Settings::validate_conditions($rule_type, $current, $posted, 'conditions');

                // Product conditions (stored under regular conditions, separated only in UI)
                $product_conditions = RP_WCDPD_Settings::validate_conditions($rule_type, $current, $posted, 'product_conditions');
                $current['conditions'] = array_merge($current['conditions'], $product_conditions);

                // BOGO product conditions
                if ($is_bogo) {
                    $current['bogo_product_conditions'] = RP_WCDPD_Settings::validate_conditions($rule_type, $current, $posted, 'bogo_product_conditions');
                }

                // Add to output array
                $output[] = $current;
            }
        }

        return $output;
    }

    /**
     * Validate product pricing rule
     *
     * @access public
     * @param array $current
     * @param array $posted
     * @return array
     */
    public static function validate_product_pricing_rule($current, $posted)
    {
        // Rule method
        if (isset($posted['method']) && RP_WCDPD_Settings::product_pricing_method_exists($posted['method'])) {
            $current['method'] = $posted['method'];
        }
        else {
            return false;
        }

        // Quantities based on
        if (in_array($current['method'], array('bulk', 'tiered', 'bogo', 'bogo_repeat'), true)) {
            if (isset($posted['quantities_based_on']) && RP_WCDPD_Settings::quantities_based_on_method_exists($posted['quantities_based_on'])) {
                $current['quantities_based_on'] = $posted['quantities_based_on'];
            }
            else {
                return false;
            }
        }

        // Group quantities based on
        if (in_array($current['method'], array('group', 'group_repeat'), true)) {
            if (isset($posted['group_quantities_based_on']) && RP_WCDPD_Settings::group_quantities_based_on_method_exists($posted['group_quantities_based_on'])) {
                $current['group_quantities_based_on'] = $posted['group_quantities_based_on'];
            }
            else {
                return false;
            }
        }

        // BOGO options
        if (in_array($current['method'], array('bogo', 'bogo_repeat'), true)) {

            // Quantities
            foreach (array('bogo_purchase_quantity', 'bogo_receive_quantity') as $quantity_type) {
                if (isset($posted[$quantity_type]) && !RightPress_Helper::is_empty($posted[$quantity_type])) {
                    if ($sanitized = RP_WCDPD_Settings::sanitize_numeric_value($posted[$quantity_type])) {
                        $current[$quantity_type] = abs(RP_WCDPD_Settings::sanitize_quantity_value($sanitized));
                    }
                    else {
                        return false;
                    }
                }
                else {
                    return false;
                }
            }
        }

        // Quantity ranges
        if (in_array($current['method'], array('bulk', 'tiered'), true)) {
            $current['quantity_ranges'] = RP_WCDPD_Settings::validate_quantity_ranges($current, $posted);
        }

        // Products in group
        if (in_array($current['method'], array('group', 'group_repeat'), true)) {
            $current['group_products'] = RP_WCDPD_Settings::validate_group_products($current, $posted);
        }

        return $current;
    }

    /**
     * Validate conditions
     *
     * @access public
     * @param string $rule_type
     * @param array $current
     * @param array $posted
     * @param string $alias
     * @return array
     */
    public static function validate_conditions($rule_type, $current, $posted, $alias = 'conditions')
    {
        $conditions = array();

        // Iterate over posted conditions
        if (!empty($posted[$alias]) && is_array($posted[$alias])) {
            foreach ($posted[$alias] as $condition) {

                // Validate and sanitize condition
                if ($processed_condition = RP_WCDPD_Settings::validate_single_condition($condition)) {
                    $conditions[] = $processed_condition;
                }
            }
        }

        return $conditions;
    }

    /**
     * Validate single condition
     *
     * @access public
     * @param string $posted
     * @return array
     */
    public static function validate_single_condition($posted)
    {
        $single = array();

        // Unique identifier
        if (!empty($posted['uid'])) {
            $single['uid'] = $posted['uid'];
        }
        else {
            $single['uid'] = 'rp_wcdpd_' . RightPress_Helper::get_hash();
        }

        // Type
        if (isset($posted['type']) && RP_WCDPD_Conditions::condition_exists($posted['type'])) {
            $single['type'] = $posted['type'];
        }
        else {
            return false;
        }

        // Method
        if (isset($posted['method_option']) && RP_WCDPD_Conditions::condition_method_option_exists($single['type'], $posted['method_option'])) {
            $single['method_option'] = $posted['method_option'];
        }
        else {
            return false;
        }

        // Other condition field values
        $field_values = RP_WCDPD_Settings::validate_single_condition_fields($single['type'], $single['method_option'], $posted);

        // At least one field value exists
        if (!empty($field_values)) {
            $single = array_merge($single, $field_values);
        }
        // Validation error
        else if ($field_values === false) {
            return false;
        }

        return !empty($single) ? $single : false;
    }

    /**
     * Validate single condition fields
     *
     * @access public
     * @param string $condition_key
     * @param string $condition_method_key
     * @param array $posted
     * @return mixed
     */
    public static function validate_single_condition_fields($condition_key, $condition_method_key, $posted)
    {
        $single = array();

        // Get condition
        $condition = RP_WCDPD_Controller_Conditions::get_item($condition_key);

        // Validate condition fields
        if (!empty($condition['fields']) && is_array($condition['fields'])) {
            foreach ($condition['fields'] as $position => $fields) {
                foreach ($fields as $condition_field_key) {
                    if ($condition_field = RP_WCDPD_Controller_Condition_Fields::get_item($condition_field_key)) {
                        if (call_user_func($condition_field['validate_callback'], $posted, $condition, $condition_method_key)) {
                            $single[$condition_field_key] = call_user_func($condition_field['sanitize_callback'], $posted, $condition, $condition_method_key);
                        }
                        else {
                            return false;
                        }
                    }
                }
            }
        }

        return $single;
    }

    /**
     * Validate quantity ranges
     *
     * @access public
     * @param array $current
     * @param array $posted
     * @return array
     */
    public static function validate_quantity_ranges($current, $posted)
    {
        $quantity_ranges = array();

        // Check if any quantity ranges are configured
        if (!empty($posted['quantity_ranges']) && is_array($posted['quantity_ranges'])) {

            // Iterate over quantity ranges
            foreach ($posted['quantity_ranges'] as $quantity_range) {

                // Validate and sanitize quantity range
                if ($processed_quantity_range = RP_WCDPD_Settings::validate_single_quantity_range($quantity_range)) {
                    $quantity_ranges[] = $processed_quantity_range;
                }
            }
        }

        return $quantity_ranges;
    }

    /**
     * Validate single quantity range
     *
     * @access public
     * @param array $quantity_range
     * @return array
     */
    public static function validate_single_quantity_range($quantity_range)
    {
        $single = array();

        // Unique identifier
        if (!empty($quantity_range['uid'])) {
            $single['uid'] = $quantity_range['uid'];
        }
        else {
            $single['uid'] = 'rp_wcdpd_' . RightPress_Helper::get_hash();
        }

        // From
        if (isset($quantity_range['from']) && !RightPress_Helper::is_empty($quantity_range['from'])) {

            // Sanitize value
            $sanitized = RP_WCDPD_Settings::sanitize_numeric_value($quantity_range['from']);

            // Check sanitized value
            if (!RightPress_Helper::is_empty($sanitized)) {
                $single['from'] = abs(RP_WCDPD_Settings::sanitize_quantity_value($sanitized));
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }

        // To
        if (isset($quantity_range['to']) && !RightPress_Helper::is_empty($quantity_range['to'])) {
            if ($sanitized = RP_WCDPD_Settings::sanitize_numeric_value($quantity_range['to'])) {
                $single['to'] = abs(RP_WCDPD_Settings::sanitize_quantity_value($sanitized));
            }
            else {
                $single['to'] = null;
            }
        }
        else {
            $single['to'] = null;
        }

        // Pricing method
        if (isset($quantity_range['pricing_method']) && RP_WCDPD_Pricing::pricing_method_exists($quantity_range['pricing_method'], 'product_pricing_volume')) {
            $single['pricing_method'] = $quantity_range['pricing_method'];
        }
        else {
            return false;
        }

        // Pricing value
        if (isset($quantity_range['pricing_value']) && !RightPress_Helper::is_empty($quantity_range['pricing_value'])) {

            // Sanitize value
            $sanitized = RP_WCDPD_Settings::sanitize_numeric_value($quantity_range['pricing_value']);

            // Check if sanitization succeeded
            if ($sanitized !== false) {
                $single['pricing_value'] = abs((float) $sanitized);
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }

        return !empty($single) ? $single : false;
    }

    /**
     * Validate group products
     *
     * @access public
     * @param array $current
     * @param array $posted
     * @return array
     */
    public static function validate_group_products($current, $posted)
    {
        $group_products = array();

        // Iterate over group products
        if (!empty($posted['group_products']) && is_array($posted['group_products'])) {
            foreach ($posted['group_products'] as $group_product) {

                // Validate and sanitize group product
                if ($processed_group_product = RP_WCDPD_Settings::validate_single_group_product($group_product)) {
                    $group_products[] = $processed_group_product;
                }
            }
        }

        return $group_products;
    }

    /**
     * Validate single group product
     *
     * @access public
     * @param array $group_product
     * @return array
     */
    public static function validate_single_group_product($group_product)
    {
        $single = array();

        // Unique identifier
        if (!empty($group_product['uid'])) {
            $single['uid'] = $group_product['uid'];
        }
        else {
            $single['uid'] = 'rp_wcdpd_' . RightPress_Helper::get_hash();
        }

        // Quantity
        if (isset($group_product['quantity']) && !RightPress_Helper::is_empty($group_product['quantity'])) {
            if ($sanitized = RP_WCDPD_Settings::sanitize_numeric_value($group_product['quantity'])) {
                $single['quantity'] = abs(RP_WCDPD_Settings::sanitize_quantity_value($sanitized));
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }

        // Type
        if (isset($group_product['type']) && RP_WCDPD_Conditions::condition_exists($group_product['type'])) {
            $single['type'] = $group_product['type'];
        }
        else {
            return false;
        }

        // Method
        if (isset($group_product['method_option']) && RP_WCDPD_Conditions::condition_method_option_exists($group_product['type'], $group_product['method_option'])) {
            $single['method_option'] = $group_product['method_option'];
        }
        else {
            return false;
        }

        // Other condition field values
        $field_values = RP_WCDPD_Settings::validate_single_condition_fields($single['type'], $single['method_option'], $group_product);

        // At least one field value exists
        if (!empty($field_values)) {
            $single = array_merge($single, $field_values);
        }
        // Validation error
        else if ($field_values === false) {
            return false;
        }

        return !empty($single) ? $single : false;
    }

    /**
     * Get settings revision hash
     *
     * @access public
     * @return string
     */
    public static function get_settings_revision()
    {
        $instance = self::get_instance();

        // Check if we have revision in memory
        if ($instance->settings_revision === null) {

            // Get revision from database
            $instance->settings_revision = get_option('rp_wcdpd_settings_revision');

            // Reset revision if not found in database
            if (!$instance->settings_revision) {
                self::reset_settings_revision();
            }
        }

        // Return revision from memory
        return $instance->settings_revision;
    }

    /**
     * Reset settings revision hash
     *
     * @access public
     * @return string
     */
    public static function reset_settings_revision()
    {
        $instance = self::get_instance();

        // Generate revision hash and cache in memory
        $instance->settings_revision = RightPress_Helper::get_hash();

        // Update revision in database
        update_option('rp_wcdpd_settings_revision', $instance->settings_revision);

        // Return new revision hash
        return $instance->settings_revision;
    }

    /**
     * Check if current request is for a plugin's settings page
     *
     * @access public
     * @return bool
     */
    public static function is_settings_page()
    {
        return preg_match('/page=rp_wcdpd_settings/i', $_SERVER['QUERY_STRING']);
    }

    /**
     * Pass configuration to JavaScript
     *
     * @access public
     * @return void
     */
    public function configuration_to_javascript()
    {
        if (!RP_WCDPD_Settings::is_settings_page() || !RP_WCDPD_Settings::settings_page_uses_templates()) {
            return;
        }

        // Get current tab
        $current_tab = RP_WCDPD_Settings::get_tab();

        // Get configuration
        $configuration = RP_WCDPD_Settings::get_all();

        // Split "conditions" into "product conditions" and "conditions"
        foreach (array('product_pricing', 'cart_discounts', 'checkout_fees') as $context) {
            if (!empty($configuration[$context]) && is_array($configuration[$context])) {
                foreach ($configuration[$context] as $rule_key => $rule) {
                    if (!empty($rule['conditions']) && is_array($rule['conditions'])) {
                        foreach ($rule['conditions'] as $condition_key => $condition) {
                            if (RightPress_Helper::string_begins_with_substring($condition['type'], 'product__')) {
                                $configuration[$context][$rule_key]['product_conditions'][] = $condition;
                                unset($configuration[$context][$rule_key]['conditions'][$condition_key]);
                            }
                        }
                    }
                }
            }
        }

        // Reset conditions indexes
        foreach (array('product_pricing', 'cart_discounts', 'checkout_fees') as $context) {
            if (!empty($configuration[$context]) && is_array($configuration[$context])) {
                foreach ($configuration[$context] as $rule_key => $rule) {
                    foreach (array('product_conditions', 'conditions') as $alias) {
                        if (!empty($rule[$alias]) && is_array($rule[$alias])) {
                            $configuration[$context][$rule_key][$alias] = array_values($configuration[$context][$rule_key][$alias]);
                        }
                    }
                }
            }
        }

        // Pass configuration values to JS
        wp_localize_script('rp-wcdpd-rules-scripts', 'rp_wcdpd_config', $configuration);
        wp_localize_script('rp-wcdpd-rules-scripts', 'rp_wcdpd_multiselect_options', $this->get_multiselect_option_labels($current_tab, $configuration));
    }

    /**
     * Render templates in footer
     *
     * @access public
     * @return void
     */
    public function render_templates_in_footer()
    {
        // Load only on our pages that use templates
        if (RP_WCDPD_Settings::is_settings_page() && RP_WCDPD_Settings::settings_page_uses_templates()) {

            // Get current tab
            $current_tab = RP_WCDPD_Settings::get_tab();

            // Include view
            require_once RP_WCDPD_PLUGIN_PATH . 'includes/views/settings/templates.php';
        }
    }

    /**
     * Check if current settings page uses templates
     *
     * @access public
     * @return bool
     */
    public static function settings_page_uses_templates()
    {
        return in_array(RP_WCDPD_Settings::get_tab(), self::$contexts, true);
    }

    /**
     * Get tab title by tab key
     *
     * @access public
     * @param string $key
     * @return string
     */
    public static function get_tab_title($key)
    {
        if (!empty(self::$structure[$key])) {
            return self::$structure[$key]['title'];
        }

        return false;
    }

    /**
     * Get selected multiselect field option labels
     *
     * @access public
     * @param string $context
     * @param array $configuration
     * @return array
     */
    public function get_multiselect_option_labels($context, $configuration)
    {
        $labels = array();

        // Iterate over rules of current context
        if (in_array($context, self::$contexts, true) && !empty($configuration[$context])) {
            foreach ($configuration[$context] as $row_key => $row) {

                // Conditions and group products
                foreach (array('product_conditions', 'bogo_product_conditions', 'conditions', 'group_products') as $child_type) {

                    // Iterate over conditions
                    if (!empty($row[$child_type]) && is_array($row[$child_type])) {
                        foreach ($row[$child_type] as $child_key => $child) {

                            // Load condition
                            if ($condition = RP_WCDPD_Controller_Conditions::get_item($child['type'])) {

                                // Iterate over condition fields
                                if (!empty($condition['fields']) && is_array($condition['fields'])) {
                                    foreach ($condition['fields'] as $position => $fields) {
                                        foreach ($fields as $condition_field_key) {
                                            if (!empty($child[$condition_field_key])) {

                                                // Load condition field
                                                if ($condition_field = RP_WCDPD_Controller_Condition_Fields::get_item($condition_field_key)) {

                                                    // Get multiselect option labels
                                                    if (isset($condition_field['get_multiselect_option_labels_callback'])) {
                                                        $labels[$context][$row_key][$child_type][$child_key][$condition_field_key] = call_user_func($condition_field['get_multiselect_option_labels_callback'], $child[$condition_field_key]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                // BOGO products
                if ($context === 'product_pricing' && in_array($row['method'], array('bogo', 'bogo_repeat'), true)) {
                    foreach (array('products', 'product_variations', 'product_categories', 'product_attributes', 'product_tags') as $condition_field_key) {

                        $bogo_field_key = 'bogo_' . $condition_field_key;

                        if (!empty($row[$bogo_field_key]) && is_array($row[$bogo_field_key])) {

                            // Load condition field
                            if ($condition_field = RP_WCDPD_Controller_Condition_Fields::get_item($condition_field_key)) {

                                // Get multiselect option labels
                                if (isset($condition_field['get_multiselect_option_labels_callback'])) {
                                    $labels[$context][$row_key][$bogo_field_key] = call_user_func($condition_field['get_multiselect_option_labels_callback'], $row[$bogo_field_key]);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $labels;
    }

    /**
     * Sanitize numeric value
     *
     * @access public
     * @param mixed $value
     * @return mixed
     */
    public static function sanitize_numeric_value($value)
    {
        // Fix comma as decimal separator
        $sanitized = RightPress_Helper::fix_decimal_separator($value);

        // Check if resulting value appears to be numeric
        return is_numeric($sanitized) ? $sanitized : false;
    }

    /**
     * Custom capability for settings
     *
     * @access public
     * @param string $capability
     * @return string
     */
    public function custom_settings_capability($capability)
    {
        return RP_WCDPD::get_admin_capability();
    }

    /**
     * Get product pricing methods for display in admin UI
     *
     * @access public
     * @return array
     */
    public static function get_product_pricing_methods_for_display()
    {
        $for_display = array();

        // Get methods
        $methods = RP_WCDPD_Controller_Methods_Product_Pricing::get_items();

        // Iterate over method groups
        foreach ($methods as $group_key => $group) {

            // Iterate over methods
            foreach ($group['children'] as $condition_key => $condition) {

                // Add group if needed
                if (!isset($for_display[$group_key])) {
                    $for_display[$group_key] = array(
                        'label'     => $group['label'],
                        'options'  => array(),
                    );
                }

                // Push method to group
                $for_display[$group_key]['options'][$condition_key] = $condition['label'];
            }
        }

        return $for_display;
    }

    /**
     * Check if product pricing method exists
     *
     * @access public
     * @param string $method
     * @return bool
     */
    public static function product_pricing_method_exists($method)
    {
        // Get methods
        $methods = RP_WCDPD_Controller_Methods_Product_Pricing::get_items();

        // Iterate over methods
        foreach ($methods as $group_key => $group) {
            if (isset($group['children'][$method])) {
                return true;
            }
        }

        // Method not found
        return false;
    }

    /**
     * Get "Quantities based on" methods for display in admin UI
     *
     * @access public
     * @return array
     */
    public static function get_quantities_based_on_methods_for_display()
    {
        // Define methods
        if (self::$quantities_based_on_methods === null) {
            self::$quantities_based_on_methods = array(
                'individual' => array(
                    'label' => __('Individual Products', 'rp_wcdpd'),
                    'options' => array(
                        'product'       => __('Each individual product', 'rp_wcdpd'),
                        'variation'     => __('Each individual variation', 'rp_wcdpd'),
                        'configuration' => __('Each individual cart line item', 'rp_wcdpd'),
                    ),
                ),
                'cumulative' => array(
                    'label' => __('All Matched Products', 'rp_wcdpd'),
                    'options' => array(
                        //'categories'    => __('All matched product quantities split by category', 'rp_wcdpd'),
                        //'all'           => __('All matched product quantities added up', 'rp_wcdpd'),
                        'categories'    => __('Quantities added up by category', 'rp_wcdpd'),
                        'all'           => __('All quantities added up', 'rp_wcdpd'),
                    ),
                ),
            );
        }

        // Return methods
        return self::$quantities_based_on_methods;
    }

    /**
     * Check if "Quantities based on" method exists
     *
     * @access public
     * @param string $method
     * @return bool
     */
    public static function quantities_based_on_method_exists($method)
    {
        // Iterate over methods
        foreach (RP_WCDPD_Settings::get_quantities_based_on_methods_for_display() as $group_key => $group) {
            foreach ($group['options'] as $option_key => $label) {
                if (($group_key . '__' . $option_key) === $method) {
                    return true;
                }
            }
        }

        // Method not found
        return false;
    }

    /**
     * Get group "Quantities based on" methods for display in admin UI
     *
     * @access public
     * @return array
     */
    public static function get_group_quantities_based_on_methods_for_display()
    {
        // Define methods
        if (self::$group_quantities_based_on_methods === null) {
            self::$group_quantities_based_on_methods = array(
                'group_product'       => __('Each individual product', 'rp_wcdpd'),
                'group_variation'     => __('Each individual variation', 'rp_wcdpd'),
                'group_configuration' => __('Each individual cart line item', 'rp_wcdpd'),
                'group_category'      => __('Each individual category', 'rp_wcdpd'),
            );
        }

        // Return methods
        return self::$group_quantities_based_on_methods;
    }

    /**
     * Check if group "Quantities based on" method exists
     *
     * @access public
     * @param string $method
     * @return bool
     */
    public static function group_quantities_based_on_method_exists($method)
    {
        // Get methods
        $methods = RP_WCDPD_Settings::get_group_quantities_based_on_methods_for_display();

        // Check if such method exists
        return isset($methods[$method]);
    }

    /**
     * Get exclusivity methods for display in admin UI
     *
     * @access public
     * @return array
     */
    public static function get_exclusivity_methods_for_display()
    {
        // Define methods
        if (self::$exclusivity_methods === null) {
            self::$exclusivity_methods = array(
                'non_exclusive' => array(
                    'label' => __('Non-Exclusive', 'rp_wcdpd'),
                    'options' => array(
                        'all'   => __('Apply with other applicable rules', 'rp_wcdpd'),
                    ),
                ),
                'exclusive' => array(
                    'label' => __('Exclusive', 'rp_wcdpd'),
                    'options' => array(
                        'this'  => __('Apply this rule and disregard other rules', 'rp_wcdpd'),
                        'other' => __('Apply if other rules are not applicable', 'rp_wcdpd'),
                    ),
                ),
                'disabled' => array(
                    'label' => __('Disabled', 'rp_wcdpd'),
                    'options' => array(
                        'disabled' => __('Disabled', 'rp_wcdpd'),
                    ),
                ),
            );
        }

        // Return methods
        return self::$exclusivity_methods;
    }

    /**
     * Check if exclusivity method exists
     *
     * @access public
     * @param string $method
     * @return bool
     */
    public static function exclusivity_method_exists($method)
    {
        // Iterate over methods
        foreach (RP_WCDPD_Settings::get_exclusivity_methods_for_display() as $group_key => $group) {
            if (isset($group['options'][$method])) {
                return true;
            }
        }

        // Method not found
        return false;
    }

    /**
     * Get "Receive products" methods for display in admin UI
     *
     * @access public
     * @return array
     */
    public static function get_receive_products_methods_for_display()
    {
        // Define methods
        if (self::$receive_products_methods === null) {
            self::$receive_products_methods = array(
                'same' => array(
                    'label' => __('Same Products', 'rp_wcdpd'),
                    'options' => array(
                        //'matched' => __('Same as at full price', 'rp_wcdpd'),
                        'matched' => __('Same as products at full price', 'rp_wcdpd'),
                    ),
                ),
                'products' => array(
                    'label' => __('Other Products', 'rp_wcdpd'),
                    'options' => array(
                        'product__product'      => __('Other products', 'rp_wcdpd'),
                        'product__variation'    => __('Other product variations', 'rp_wcdpd'),
                        'product__category'     => __('Other products in categories', 'rp_wcdpd'),
                        'product__attributes'   => __('Other products with attributes', 'rp_wcdpd'),
                        'product__tags'         => __('Other products with tags', 'rp_wcdpd'),
                    ),
                ),
            );
        }

        // Return methods
        return self::$receive_products_methods;
    }

    /**
     * Check if "receive products" method exists
     *
     * @access public
     * @param string $method
     * @return bool
     */
    public static function receive_products_method_exists($method)
    {
        // Iterate over methods
        foreach (RP_WCDPD_Settings::get_receive_products_methods_for_display() as $group_key => $group) {
            if (isset($group['options'][$method])) {
                return true;
            }
        }

        // Method not found
        return false;
    }

    /**
     * Sanitize quantity value
     *
     * @access public
     * @param mixed $value
     * @return int|float
     */
    public static function sanitize_quantity_value($value)
    {
        $method = RP_WCDPD_Settings::get('decimal_quantities') ? 'floatval' : 'intval';
        return call_user_func($method, $value);
    }

    /**
     * Maybe display migration notice
     *
     * @access public
     * @return void
     */
    public function maybe_display_migration_notice()
    {
        // Main migration notice
        if ($notice = get_option('rp_wcdpd_migration_notice')) {
            printf('<div class="update-nag" style="display: block; border-left-color: #dc3232;"><h3 style="margin-top: 0.3em; margin-bottom: 0.6em;">Action Required!</h3>' . $notice . '<p><a href="%s">Contact Support</a>&nbsp;&nbsp;&nbsp;<a href="%s">Hide this notice</a></p></div>', 'http://url.rightpress.net/new-support-ticket', add_query_arg('rp_wcdpd_hide_migration_notice', '1'));
        }

        // "Products to adjust" migration notice
        if ($notice = get_option('rp_wcdpd_migration_notice_products_to_adjust')) {
            printf('<div class="update-nag" style="display: block; border-left-color: #dc3232;"><h3 style="margin-top: 0.3em; margin-bottom: 0.6em;">Warning!</h3>' . $notice . '<p><a href="%s">Contact Support</a>&nbsp;&nbsp;&nbsp;<a href="%s">Hide this notice</a></p></div>', 'http://url.rightpress.net/new-support-ticket', add_query_arg('rp_wcdpd_hide_migration_notice_products_to_adjust', '1'));
        }
    }

    /**
     * Hide migration notice
     *
     * @access public
     * @return void
     */
    public function hide_migration_notice()
    {
        // Main migration notice
        if (!empty($_REQUEST['rp_wcdpd_hide_migration_notice'])) {
            delete_option('rp_wcdpd_migration_notice');
            wp_redirect(remove_query_arg('rp_wcdpd_hide_migration_notice'));
            exit;
        }

        // "Products to adjust" migration notice
        if (!empty($_REQUEST['rp_wcdpd_hide_migration_notice_products_to_adjust'])) {
            delete_option('rp_wcdpd_migration_notice_products_to_adjust');
            wp_redirect(remove_query_arg('rp_wcdpd_hide_migration_notice_products_to_adjust'));
            exit;
        }
    }

    /**
     * Pre-2.1 compatibility for BOGO rule products to get
     *
     * Since version 2.1 "products to get" for BOGO rules have their own
     * conditions section. Before that, there were separate settings fields.
     * This migrates that setting from old to new format on the fly.
     *
     * @access public
     * @return void
     */
    public function fix_bogo_get_products_pre_2_1()
    {
        if (!empty($this->settings['product_pricing']) && is_array($this->settings['product_pricing'])) {

            // Condition key and multiselect field mapping
            $multiselect_keys = array(
                'product__product'      => 'products',
                'product__variation'    => 'product_variations',
                'product__category'     => 'product_categories',
                'product__attributes'   => 'product_attributes',
                'product__tags'         => 'product_tags'
            );

            foreach ($this->settings['product_pricing'] as $rule_key => $rule) {
                if (!empty($rule['bogo_receive_products'])) {

                    // Matched items
                    if ($rule['bogo_receive_products'] === 'matched') {

                        // Check if there are any conditions configured
                        if (!empty($rule['conditions']) && is_array($rule['conditions'])) {
                            foreach ($rule['conditions'] as $condition) {

                                // Check if current condition is one of the product conditions
                                if (isset($multiselect_keys[$condition['type']])) {

                                    // Unset UID
                                    $condition['uid'] = null;

                                    // Add product condition to "get" conditions list
                                    $this->settings['product_pricing'][$rule_key]['bogo_product_conditions'][] = $condition;
                                }
                            }
                        }
                    }
                    // Specific items
                    else {

                        // Iterate over possible options
                        foreach ($multiselect_keys as $condition_type => $multiselect_key) {
                            if ($rule['bogo_receive_products'] === $condition_type && !empty($rule['bogo_' . $multiselect_key]) && is_array($rule['bogo_' . $multiselect_key])) {

                                // Add condition
                                $this->settings['product_pricing'][$rule_key]['bogo_product_conditions'][] = array(
                                    'uid'               => null,
                                    'type'              => $condition_type,
                                    'method_option'     => (in_array($condition_type, array('product__attributes', 'product__tags'), true)  ? 'at_least_one' : 'in_list'),
                                    $multiselect_key    => $rule['bogo_' . $multiselect_key],
                                );
                            }
                        }
                    }
                }
            }
        }
    }




}

RP_WCDPD_Settings::get_instance();

}
