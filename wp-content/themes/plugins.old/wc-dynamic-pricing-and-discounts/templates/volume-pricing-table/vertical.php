<?php

/**
 * Volume Pricing Table - Vertical
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="rp_wcdpd_product_page_title"><?php echo RP_WCDPD_Settings::get('promo_volume_pricing_table_title'); ?></div>
<div class="rp_wcdpd_pricing_table">

    <table>
        <tbody>

            <?php if (count($data) > 1): ?>
                <tr>

                    <td class="rp_wcdpd_longer_cell">&nbsp;</td>

                    <?php foreach ($data as $single): ?>
                        <?php if ($single['table_data'] !== false): ?>
                            <td class="rp_wcdpd_longer_cell"><span class="rp_wcdpd_pricing_table_product_name"><?php echo wc_get_formatted_variation($single['product'], true, false); ?></span></td>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </tr>
            <?php endif; ?>

            <?php foreach ($data as $single): ?>
                <?php if ($single['table_data'] !== false): ?>
                    <?php foreach ($single['table_data'] as $range_key => $range): ?>

                        <tr>

                            <td class="rp_wcdpd_longer_cell">
                                <span class="rp_wcdpd_pricing_table_quantity<?php echo (count($data) > 1 ? '_multiple' : ''); ?>">
                                    <?php echo $range['range_label']; ?>
                                </span>
                            </td>

                            <?php foreach ($data as $current_single): ?>
                                <?php if ($current_single['table_data'] !== false): ?>
                                    <td class="rp_wcdpd_longer_cell">
                                        <span class="amount">
                                            <?php echo $current_single['table_data'][$range_key]['range_price']; ?>
                                        </span>
                                    </td>
                                <?php endif; ?>
                            <?php endforeach; ?>

                        </tr>

                    <?php endforeach; ?>
                    <?php break; ?>
                <?php endif; ?>
            <?php endforeach; ?>

        </tbody>
    </table>

</div>
