<?php

/**
 * Volume Pricing Table - Inline - Horizontal
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="rp_wcdpd_product_page">
    <?php RightPress_Helper::include_template(('volume-pricing-table/horizontal'), RP_WCDPD_PLUGIN_PATH, 'wc-dynamic-pricing-and-discounts', array('data' => $data)); ?>
</div>
