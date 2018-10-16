<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Version Control
 *
 * WARNING: Make sure to update version number here as well as in the main class name
 */
$version = '12';

global $rightpress_conditions_helper_version;

if (!$rightpress_conditions_helper_version || $rightpress_conditions_helper_version < $version) {
    $rightpress_conditions_helper_version = $version;
}

/**
 * Proxy Class
 */
if (!class_exists('RightPress_Conditions_Helper')) {

final class RightPress_Conditions_Helper
{

    /**
     * Method overload
     *
     * @access public
     * @param string $method_name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($method_name, $arguments)
    {
        // Get latest version of the main class
        global $rightpress_conditions_helper_version;

        // Get main class name
        $class_name = 'RightPress_Conditions_Helper_' . $rightpress_conditions_helper_version;

        // Call main class
        return call_user_func_array(array($class_name, $method_name), $arguments);
    }
}
}

/**
 * Main Class
 */
if (!class_exists('RightPress_Conditions_Helper_12')) {

final class RightPress_Conditions_Helper_12
{

    /**
     * Get all capabilities based on criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public static function get_all_capabilities($ids = array(), $query = '')
    {
        $items = array();

        // Groups plugin active?
        if (class_exists('Groups_User') && class_exists('Groups_Wordpress') && function_exists('_groups_get_tablename')) {

            global $wpdb;

            $capability_table = _groups_get_tablename('capability');
            $all_capabilities = $wpdb->get_results('SELECT capability FROM ' . $capability_table);

            if ($all_capabilities) {
                foreach ($all_capabilities as $capability) {

                    $capability = (string) $capability->capability;

                    // Skip this item if we don't need it
                    if (!empty($ids) && !in_array($capability, $ids, true)) {
                        continue;
                    }

                    // Add item
                    $items[] = array(
                        'id'    => $capability,
                        'text'  => $capability
                    );
                }
            }
        }

        // Get standard WP capabilities
        else {

            global $wp_roles;

            if (!isset($wp_roles)) {
                get_role('administrator');
            }

            $roles = $wp_roles->roles;

            $already_added = array();

            if (is_array($roles)) {
                foreach ($roles as $rolename => $atts) {
                    if (isset($atts['capabilities']) && is_array($atts['capabilities'])) {
                        foreach ($atts['capabilities'] as $capability => $value) {

                            $capability = (string) $capability;

                            if (!in_array($capability, $already_added, true)) {

                                // Skip this item if we don't need it
                                if (!empty($ids) && !in_array($capability, $ids, true)) {
                                    continue;
                                }

                                // Add item
                                $items[] = array(
                                    'id'    => $capability,
                                    'text'  => $capability
                                );
                                $already_added[] = $capability;
                            }
                        }
                    }
                }
            }
        }

        return $items;
    }

    /**
     * Get all countries based on criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public static function get_all_countries($ids = array(), $query = '')
    {
        $items = array();

        $countries = new WC_Countries();

        // Iterate over all countries
        if ($countries && is_array($countries->countries)) {
            foreach ($countries->countries as $country_code => $country_name) {

                // Add item
                $items[] = array(
                    'id'    => (string) $country_code,
                    'text'  => $country_name,
                );
            }
        }

        return $items;
    }

    /**
     * Get all coupons based on criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @param string $query_prefix
     * @return array
     */
    public static function get_all_coupons($ids = array(), $query = '', $query_prefix = '')
    {
        $items = array();

        // Get all coupon ids
        $args = array(
            'posts_per_page'    => -1,
            'post_type'         => 'shop_coupon',
            'post_status'       => array('publish'),
            'fields'            => 'ids',
        );

        // Query passed in
        if (!empty($query) && !empty($query_prefix)) {
            $args[$query_prefix . '_title_query'] = $query;
            $args['suppress_filters'] = false;
        }

        // Specific coupons requested
        if (!empty($ids)) {
            $args['post__in'] = $ids;
        }

        // WC31: coupons may no longer be posts
        $posts_raw = get_posts($args);

        // Format results array
        foreach ($posts_raw as $post_id) {
            $items[] = array(
                'id'    => (string) $post_id,
                'text'  => get_the_title($post_id)
            );
        }

        return $items;
    }

    /**
     * Get all weekdays based on criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public static function get_all_weekdays($ids = array(), $query = '')
    {
        $items = array();

        // Get weekdays
        foreach (RightPress_Helper::get_weekdays() as $weekday_key => $weekday) {

            // Add weekday
            $items[] = array(
                'id'    => (string) $weekday_key,
                'text'  => $weekday
            );
        }

        return $items;
    }

    /**
     * Get all product attributes based on criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public static function get_all_product_attributes($ids = array(), $query = '')
    {
        global $wc_product_attributes;

        $items = array();

        // Iterate over product attributes
        foreach ($wc_product_attributes as $attribute_key => $attribute) {

            $attribute_name = !empty($attribute->attribute_label) ? $attribute->attribute_label : $attribute->attribute_name;

            $subitems = array();

            // WC31: Product attributes may no longer be post terms
            $children_raw = get_terms(array($attribute_key), array('hide_empty' => 0));
            $children_raw_count = count($children_raw);

            foreach ($children_raw as $child_key => $child) {
                $child_name = $child->name;

                if ($child->parent) {
                    $parent_id = $child->parent;
                    $has_parent = true;

                    // Make sure we don't have an infinite loop here
                    $found = false;
                    $i = 0;

                    while ($has_parent && ($i < $children_raw_count || $found)) {

                        // Reset each time
                        $found = false;
                        $i = 0;

                        foreach ($children_raw as $parent_child_key => $parent_child) {

                            $i++;

                            if ($parent_child->term_id == $parent_id) {
                                $child_name = $parent_child->name . ' → ' . $child_name;
                                $found = true;

                                if ($parent_child->parent) {
                                    $parent_id = $parent_child->parent;
                                }
                                else {
                                    $has_parent = false;
                                }

                                break;
                            }
                        }
                    }
                }

                // Get term id
                $term_id = (string) $child->term_id;

                // Skip this item if we don't need it
                if (!empty($ids) && !in_array($term_id, $ids, true)) {
                    continue;
                }

                // Add item
                $subitems[] = array(
                    'id'    => $term_id,
                    'text'  => $child_name
                );
            }

            // Iterate over subitems and make a list of item/subitem pairs
            foreach ($subitems as $subitem) {
                $items[] = array(
                    'id'    => $subitem['id'],
                    'text'  => $attribute_name . ': ' . $subitem['text'],
                );
            }
        }

        return $items;
    }

    /**
     * Get all product categories based on criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public static function get_all_product_categories($ids = array(), $query = '')
    {
        $items = array();

        // WC31: Product categories may no longer be post terms
        $post_categories_raw = get_terms(array('product_cat'), array('hide_empty' => 0));
        $post_categories_raw_count = count($post_categories_raw);

        foreach ($post_categories_raw as $post_cat_key => $post_cat) {
            $category_name = $post_cat->name;

            if ($post_cat->parent) {
                $parent_id = $post_cat->parent;
                $has_parent = true;

                // Make sure we don't have an infinite loop here (happens with some kind of "ghost" categories)
                $found = false;
                $i = 0;

                while ($has_parent && ($i < $post_categories_raw_count || $found)) {

                    // Reset each time
                    $found = false;
                    $i = 0;

                    foreach ($post_categories_raw as $parent_post_cat_key => $parent_post_cat) {

                        $i++;

                        if ($parent_post_cat->term_id == $parent_id) {
                            $category_name = $parent_post_cat->name . ' → ' . $category_name;
                            $found = true;

                            if ($parent_post_cat->parent) {
                                $parent_id = $parent_post_cat->parent;
                            }
                            else {
                                $has_parent = false;
                            }

                            break;
                        }
                    }
                }
            }

            // Get term id
            $term_id = (string) $post_cat->term_id;

            // Skip this item if we don't need it
            if (!empty($ids) && !in_array($term_id, $ids, true)) {
                continue;
            }

            // Add item
            $items[] = array(
                'id'    => $term_id,
                'text'  => $category_name
            );
        }

        return $items;
    }

    /**
     * Get all product tags based on criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public static function get_all_product_tags($ids = array(), $query = '')
    {
        $items = array();

        // WC31: product tags may no longer be post terms
        $tags_raw = get_terms(array('product_tag'), array('hide_empty' => 0));

        // Iterate over all tags
        foreach ($tags_raw as $tag_key => $tag) {

            // Get term id
            $term_id = (string) $tag->term_id;

            // Skip this item if we don't need it
            if (!empty($ids) && !in_array($term_id, $ids, true)) {
                continue;
            }

            // Add item
            $items[] = array(
                'id'    => $term_id,
                'text'  => $tag->name,
            );
        }

        return $items;
    }

    /**
     * Get all product variations based on criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @param string $query_prefix
     * @return array
     */
    public static function get_all_product_variations($ids = array(), $query = '', $query_prefix = '')
    {
        $items = array();

        // Get all product variation ids
        $args = array(
            'posts_per_page'    => -1,
            'post_type'         => 'product_variation',
            'post_status'       => array('publish', 'pending', 'draft', 'future', 'private', 'inherit'),
            'fields'            => 'ids',
        );

        if (!empty($ids)) {
            $args['post__in'] = $ids;
        }

        // WC31: products will no longer be posts
        $posts_raw = get_posts($args);

        // Format results array
        foreach ($posts_raw as $post_id) {

            // Check parent
            // WC31: products will no longer be posts
            if ($parent_id = wp_get_post_parent_id($post_id)) {
                if (RightPress_Helper::post_exists($parent_id)) {

                    // Load product variation
                    $product = wc_get_product($post_id);

                    // Get list of variation attributes
                    $attributes = join(', ', $product->get_variation_attributes());
                    $attributes = RightPress_Helper::shorten_text($attributes, 25);

                    // Add variation
                    $items[] = array(
                        'id'    => (string) $post_id,
                        'text'  => '#' . $post_id . ' ' . get_the_title($parent_id) . ' (' . $attributes . ')',
                    );
                }
            }
        }

        return $items;
    }

    /**
     * Get all products based on criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @param string $query_prefix
     * @return array
     */
    public static function get_all_products($ids = array(), $query = '', $query_prefix = '')
    {
        $items = array();

        // Get all product ids
        $args = array(
            'posts_per_page'    => -1,
            'post_type'         => 'product',
            'post_status'       => array('publish', 'pending', 'draft', 'future', 'private', 'inherit'),
            'fields'            => 'ids',
        );

        // Query passed in
        if (!empty($query) && !empty($query_prefix)) {

            // Title query
            $args[$query_prefix . '_title_query'] = $query;

            // Strip potential hash
            $query = str_replace('#', '', $query);

            // ID query
            if (is_numeric($query)) {
                $args[$query_prefix . '_id_query'] = $query;
            }

            $args['suppress_filters'] = false;
        }

        // Specific products requested
        if (!empty($ids)) {
            $args['post__in'] = $ids;
        }

        // WC31: Products will no longer be posts
        $posts_raw = get_posts($args);

        // Format results array
        foreach ($posts_raw as $post_id) {
            $items[] = array(
                'id'    => (string) $post_id,
                'text'  => '#' . $post_id . ' ' . get_the_title($post_id)
            );
        }

        return $items;
    }

    /**
     * Get all roles based on criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public static function get_all_roles($ids = array(), $query = '')
    {
        $items = array();

        // Get roles
        global $wp_roles;

        if (!isset($wp_roles)) {
            $wp_roles = new WP_Roles();
        }

        // Iterate over roles and format results array
        foreach ($wp_roles->get_names() as $role_key => $role) {

            $role_key = (string) $role_key;

            // Skip this item if we don't need it
            if (!empty($ids) && !in_array($role_key, $ids, true)) {
                continue;
            }

            // Add item
            $items[] = array(
                'id'    => $role_key,
                'text'  => $role . ' (' . $role_key . ')',
            );
        }

        return $items;
    }

    /**
     * Get all shipping zones based on criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public static function get_all_shipping_zones($ids = array(), $query = '')
    {
        $items = array();

        // Iterate over shipping zones
        foreach (WC_Shipping_Zones::get_zones() as $shipping_zone) {

            // Add item
            $items[] = array(
                'id'    => (string) $shipping_zone['zone_id'],
                'text'  => $shipping_zone['zone_name'],
            );
        }

        // Get Rest of the World shipping zone
        $shipping_zone = WC_Shipping_Zones::get_zone(0);

        // Add Rest of the World shipping zone
        $items = array_merge(array(array(
            'id'    => (string) RightPress_WC_Legacy::shipping_zone_get_id($shipping_zone),
            'text'  => $shipping_zone->get_zone_name(),
        )), $items);

        return $items;
    }

    /**
     * Get all states based on criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public static function get_all_states($ids = array(), $query = '')
    {
        $items = array();

        $countries = new WC_Countries();
        $all_states = $countries->get_states();

        // Iterate over all countries
        if ($countries && is_array($countries->countries) && is_array($all_states)) {
            foreach ($all_states as $country_key => $states) {
                if (is_array($states) && !empty($states)) {

                    // Get country name
                    $country_name = !empty($countries->countries[$country_key]) ? $countries->countries[$country_key] : $country_key;

                    // Iterate over all states
                    foreach ($states as $state_key => $state) {

                        // Add item
                        $items[] = array(
                            'id'    => $country_key . '_' . $state_key,
                            'text'  => $country_name . ': ' . $state,
                        );
                    }
                }
            }
        }

        return $items;
    }

    /**
     * Get all users based on criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public static function get_all_users($ids = array(), $query = '')
    {
        $items = array();

        // Get users
        $users = get_users(array(
            'fields' => array('ID', 'user_login', 'user_email'),
        ));

        // Iterate over users
        foreach ($users as $user) {

            // Add item
            $items[] = array(
                'id'    => (string) $user->ID,
                'text'  => '#' . $user->ID . ' ' . $user->user_login . ' (' . $user->user_email . ')',
            );
        }

        return $items;
    }

    /**
     * Get all payment methods based on criteria
     *
     * @access public
     * @param array $ids
     * @param string $query
     * @return array
     */
    public static function get_all_payment_methods($ids = array(), $query = '')
    {
        $items = array();

        global $woocommerce;

        // Load payment gateways class
        $gateways = $woocommerce->payment_gateways();

        // Iterate over all payment gateways
        foreach ($gateways->payment_gateways() as $gateway_key => $gateway) {

            // Get method title
            $method_title = $gateway->get_method_title();

            // Get custom title
            if (!empty($gateway->title) && is_string($gateway->title) && $gateway->title !== $method_title) {
                $method_title .= ' (' . $gateway->title . ')';
            }

            // Add item
            $items[] = array(
                'id'    => (string) $gateway_key,
                'text'  => $method_title,
            );
        }

        return $items;
    }

    /**
     * Get order ids
     *
     * @access public
     * @param array $params
     * @return array
     */
    public static function get_order_ids($params = array())
    {
        global $woocommerce;

        $config = array();

        // Get customer id from params
        if (isset($params['customer_id'])) {
            $config['customer_id'] = $params['customer_id'];
        }
        // Get current user id
        else if (is_user_logged_in()) {
            $config['customer_id'] = get_current_user_id();
        }
        // Get billing email during checkout
        else if ($billing_email = RightPress_Conditions_Helper::get_checkout_billing_email()) {
            $config['billing_email'] = $billing_email;
        }
        else {
            return array();
        }

        // Get date object
        if (isset($params['date'])) {
            $config['date'] = $params['date'];
        }

        // Only paid orders are counted
        $config['status'] = RightPress_Helper::get_wc_order_is_paid_statuses(true);

        // Return order ids
        return RightPress_Helper::get_wc_order_ids($config);
    }

    /**
     * Get billing email from checkout data
     *
     * @access public
     * @return string|bool
     */
    public static function get_checkout_billing_email()
    {
        // Check for specific ajax requests
        if (!empty($_GET['wc-ajax']) && in_array($_GET['wc-ajax'], array('update_order_review', 'checkout'), true)) {

            $billing_email = null;

            // Check if request contains billing email
            if (!empty($_POST['billing_email'])) {
                $billing_email = $_POST['billing_email'];
            }
            else if (!empty($_POST['post_data'])) {

                parse_str($_POST['post_data'], $checkout_data);

                if (!empty($checkout_data['billing_email'])) {
                    $billing_email = $checkout_data['billing_email'];
                }
            }

            // Validate billing email format
            if (filter_var($billing_email, FILTER_VALIDATE_EMAIL)) {
                return $billing_email;
            }
        }

        return null;
    }

    /**
     * Get order id by timeframe
     *
     * @access public
     * @param string $timeframe_key
     * @return array
     */
    public static function get_order_ids_by_timeframe($timeframe_key)
    {
        $config = array();

        // Since specific date
        if ($timeframe_key !== 'all_time') {

            // Get timeframe field
            // TBD: calling plugin-specific class from helper
            $timeframe_field = RP_WCDPD_Controller_Condition_Fields::get_item('timeframe_span');

            // Get date from timeframe
            $config['date'] = call_user_func($timeframe_field['get_date_from_timeframe_callback'], $timeframe_key);
        }

        // Return matching order ids
        return RightPress_Conditions_Helper::get_order_ids($config);
    }

    /**
     * Check postcode
     *
     * @access public
     * @param string $value
     * @param string $condition_value
     * @return bool
     */
    public static function check_postcode($value, $condition_value)
    {
        // Neither can be empty
        if (RightPress_Helper::is_empty($value) || RightPress_Helper::is_empty($condition_value)) {
            return false;
        }

        // Break up condition postcode string
        $postcodes = explode(',', $condition_value);

        // Iterate over postcodes
        foreach ($postcodes as $postcode) {

            // Clean value
            $postcode = trim($postcode);

            // Postcode is empty
            if (RightPress_Helper::is_empty($postcode)) {
                continue;
            }

            // Postcode with wildcards
            if (strpos($postcode, '*') !== false) {

                // Prepare regex string
                $regex = '/^' . str_replace('\*', '.', preg_quote($postcode)) . '$/i';

                // Compare
                if (preg_match($regex, $value) === 1) {
                    return true;
                }
            }
            // Postcode range
            else if (strpos($postcode, '-') !== false) {

                // Split range
                $ranges = explode('-', $postcode);
                $ranges[0] = trim($ranges[0]);
                $ranges[1] = trim($ranges[1]);

                // Check if ranges are valid
                if (count($ranges) !== 2 || (empty($ranges[0]) && $ranges[0] !== '0') || (empty($ranges[1]) && $ranges[1] !== '0') || !is_numeric($ranges[0]) || !is_numeric($ranges[1]) || $ranges[0] >= $ranges[1]) {
                    continue;
                }

                // Check if post code is within ranges
                if ($ranges[0] <= $value && $value <= $ranges[1]) {
                    return true;
                }
            }
            // Full postcode
            else if ($postcode === $value) {
                return true;
            }
        }

        // Postcode doesn't match
        return false;
    }


}
}
