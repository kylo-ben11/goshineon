<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin specific methods used by multiple classes
 *
 * @class RP_WCDPD_Helper
 * @package WooCommerce Dynamic Pricing & Discounts
 * @author RightPress
 */
if (!class_exists('RP_WCDPD_Helper')) {

class RP_WCDPD_Helper
{

    /**
     * Stable uasort
     *
     * Adapted from https://github.com/vanderlee/PHP-stable-sort-functions/
     *
     * Can't move it to RightPress_Helper since it can't work with references
     *
     * @access public
     * @param array $array
     * @param callable $value_compare_func
     * @return array
     */
    public static function stable_uasort(&$array, $value_compare_func)
    {
        $index = 0;

        foreach ($array as &$item) {
            $item = array($index++, $item);
        }

        $result = @uasort($array, function($a, $b) use($value_compare_func) {
            $result = call_user_func($value_compare_func, $a[1], $b[1]);
            return $result == 0 ? $a[0] - $b[0] : $result;
        });

        foreach ($array as &$item) {
            $item = $item[1];
        }

        return $result;
    }





}
}
