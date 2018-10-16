<?php

/**
 * Volume Pricing Table - Modal - Horizontal
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<!-- Anchor -->
<div class="rp_wcdpd_product_page">
    <div class="rp_wcdpd_product_page_modal_link"><span><?php echo RP_WCDPD_Settings::get('promo_volume_pricing_table_title'); ?></span></div>
</div>

<!-- Modal -->
<div class="rp_wcdpd_modal" style="min-width: 400px;">
    <?php RightPress_Helper::include_template(('volume-pricing-table/horizontal'), RP_WCDPD_PLUGIN_PATH, 'wc-dynamic-pricing-and-discounts', array('data' => $data)); ?>
</div>
