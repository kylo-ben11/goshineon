<?php

/**
 * Volume Pricing Table - Horizontal
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

            <tr>

                <?php if (count($data) > 1): ?>
                    <td>&nbsp;</td>
                <?php endif; ?>

                <?php foreach ($data as $single): ?>
                    <?php if ($single['table_data'] !== false): ?>
                        <?php foreach ($single['table_data'] as $range): ?>
                            <td>
                                <span class="rp_wcdpd_pricing_table_quantity<?php echo (count($data) > 1 ? '_multiple' : ''); ?>">
                                    <?php echo $range['range_label']; ?>
                                </span>
                            </td>
                        <?php endforeach; ?>
                        <?php break; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

            </tr>

            <?php foreach ($data as $single): ?>
                <?php if ($single['table_data'] !== false): ?>

                    <tr>

                        <?php if (count($data) > 1): ?>
                            <td><span class="rp_wcdpd_pricing_table_product_name"><?php echo wc_get_formatted_variation($single['product'], true, false); ?></span></td>
                        <?php endif; ?>

                        <?php foreach ($single['table_data'] as $range): ?>
                            <td>
                                <span class="amount">
                                    <?php echo $range['range_price']; ?>
                                </span>
                            </td>
                        <?php endforeach; ?>

                    </tr>

                <?php endif; ?>
            <?php endforeach; ?>

        </tbody>
    </table>

</div>
