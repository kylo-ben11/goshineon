<?php

/**
 * View for advanced options templates
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

?>

<!-- WOOCOMMERCE DYNAMIC PRICING & DISCOUNTS TEMPLATES -->
<div id="rp_wcdpd_templates" style="display: none;">

    <!-- NO RULES CONFIGURED -->
    <div id="rp_wcdpd_no_rows_template">
        <div id="rp_wcdpd_no_rows"><?php _e('No rules configured.', 'rp_wcdpd'); ?></div>
    </div>

    <!-- ADD RULE BUTTON -->
    <div id="rp_wcdpd_add_row_template">
        <div id="rp_wcdpd_add_row">
            <button type="button" class="button" value="<?php _e('Add Rule', 'rp_wcdpd'); ?>">
                <?php _e('Add Rule', 'rp_wcdpd'); ?>
            </button>
        </div>
    </div>

    <!-- RULE WRAPPER -->
    <div id="rp_wcdpd_rule_wrapper_template">
        <div id="rp_wcdpd_rule_wrapper"></div>
    </div>

    <!-- ROW -->
    <div id="rp_wcdpd_row_template">

        <div class="rp_wcdpd_row">

            <div class="rp_wcdpd_accordion_handle">
                <div class="rp_wcdpd_row_sort_handle"><span class="dashicons dashicons-menu"></span></div>
                <span class="rp_wcdpd_row_title">
                    <span class="rp_wcdpd_row_title_title"></span>
                    <span class="rp_wcdpd_row_title_note"></span>
                </span>
                <div class="rp_wcdpd_row_remove_handle"><span class="dashicons dashicons-no-alt"></span></div>
                <div class="rp_wcdpd_row_duplicate_handle"><span class="dashicons dashicons-admin-page"></span></div>

                <?php RP_WCDPD_Form::grouped_select(array(
                    'id'                        => 'rp_wcdpd_' . $current_tab . '_exclusivity_{i}',
                    'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][exclusivity]',
                    'class'                     => 'rp_wcdpd_' . $current_tab . '_field_exclusivity rp_wcdpd_field_exclusivity',
                    'options'                   => RP_WCDPD_Settings::get_exclusivity_methods_for_display(),
                    'data-rp-wcdpd-validation'  => 'required',
                ), false); ?>

            </div>

            <div class="rp_wcdpd_row_content">

                <?php RP_WCDPD_Form::hidden(array(
                    'id'        => 'rp_wcdpd_' . $current_tab . '_uid_{i}',
                    'name'      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][uid]',
                ), false); ?>

                <div class="rp_wcdpd_row_content_first_row">

                    <?php if ($current_tab === 'product_pricing'): ?>
                        <div class="rp_wcdpd_field rp_wcdpd_field_double rp_wcdpd_no_left_margin">
                            <?php RP_WCDPD_Form::grouped_select(array(
                                'id'                        => 'rp_wcdpd_' . $current_tab . '_method_{i}',
                                'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][method]',
                                'class'                     => 'rp_wcdpd_' . $current_tab . '_field_method',
                                'options'                   => RP_WCDPD_Settings::get_product_pricing_methods_for_display(),
                                'label'                     => __('Method', 'rp_wcdpd'),
                                'data-rp-wcdpd-validation'  => 'required',
                            ), false); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (in_array($current_tab, array('cart_discounts', 'checkout_fees'), true)): ?>
                        <div class="rp_wcdpd_field rp_wcdpd_field_double rp_wcdpd_no_left_margin">
                        <?php RP_WCDPD_Form::text(array(
                            'id'                        => 'rp_wcdpd_' . $current_tab . '_title_{i}',
                            'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][title]',
                            'class'                     => 'rp_wcdpd_' . $current_tab . '_field_title',
                            'label'                     => __('Title', 'rp_wcdpd'),
                            'data-rp-wcdpd-validation'  => 'required',
                        )); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($current_tab === 'product_pricing'): ?>
                        <div class="rp_wcdpd_field rp_wcdpd_field_single rp_wcdpd_if rp_wcdpd_if_bulk rp_wcdpd_if_tiered rp_wcdpd_if_bogo rp_wcdpd_if_bogo_repeat">
                            <?php RP_WCDPD_Form::grouped_select(array(
                                'id'                        => 'rp_wcdpd_' . $current_tab . '_quantities_based_on_{i}',
                                'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][quantities_based_on]',
                                'class'                     => 'rp_wcdpd_' . $current_tab . '_field_quantities_based_on',
                                'options'                   => RP_WCDPD_Settings::get_quantities_based_on_methods_for_display(),
                                'label'                     => __('Quantities Based On', 'rp_wcdpd'),
                                'data-rp-wcdpd-validation'  => 'required',
                            ), true); ?>
                        </div>
                        <div class="rp_wcdpd_field rp_wcdpd_field_single rp_wcdpd_if rp_wcdpd_if_group rp_wcdpd_if_group_repeat">
                            <?php RP_WCDPD_Form::select(array(
                                'id'                        => 'rp_wcdpd_' . $current_tab . '_group_quantities_based_on_{i}',
                                'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][group_quantities_based_on]',
                                'class'                     => 'rp_wcdpd_' . $current_tab . '_field_group_quantities_based_on',
                                'options'                   => RP_WCDPD_Settings::get_group_quantities_based_on_methods_for_display(),
                                'label'                     => __('Quantities Based On', 'rp_wcdpd'),
                                'data-rp-wcdpd-validation'  => 'required',
                            )); ?>
                        </div>
                    <?php endif; ?>

                    <div class="rp_wcdpd_field rp_wcdpd_field_double">
                        <?php RP_WCDPD_Form::text(array(
                            'id'        => 'rp_wcdpd_' . $current_tab . '_note_{i}',
                            'name'      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][note]',
                            'class'     => 'rp_wcdpd_' . $current_tab . '_field_note',
                            'label'     => __('Private Note', 'rp_wcdpd'),
                        )); ?>
                    </div>

                    <div style="clear: both;"></div>

                </div>

                <?php if ($current_tab === 'product_pricing'): ?>

                    <div class="rp_wcdpd_row_content_product_pricing_row rp_wcdpd_row_content_product_pricing_bogo_row rp_wcdpd_if rp_wcdpd_if_bogo rp_wcdpd_if_bogo_repeat" style="display: none;">
                        <div class="rp_wcdpd_field rp_wcdpd_field_full">
                            <label><?php _e('Quantities & Discount', 'rp_wcdpd'); ?></label>
                            <div class="rp_wcdpd_inner_wrapper">

                                <div class="rp_wcdpd_field rp_wcdpd_field_single rp_wcdpd_no_left_margin">
                                    <?php RP_WCDPD_Form::number(array(
                                        'id'                        => 'rp_wcdpd_' . $current_tab . '_bogo_purchase_quantity_{i}',
                                        'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][bogo_purchase_quantity]',
                                        'class'                     => 'rp_wcdpd_' . $current_tab . '_field_bogo_purchase_quantity',
                                        'placeholder'               => __('Quantity', 'rp_wcdpd'),
                                        'label'                     => __('Buy', 'rp_wcdpd') . ' <span class="rp_wcdpd_settings_label_extra">- ' . __('At Full Price', 'rp_wcdpd') . '</span>',
                                        'disabled'                  => 'disabled',
                                        'data-rp-wcdpd-validation'  => RP_WCDPD_Settings::get('decimal_quantities') ? 'required,number_natural' : 'required,number_min_1,number_whole',
                                    )); ?>
                                </div>
                                <div class="rp_wcdpd_field rp_wcdpd_field_single">
                                    <?php RP_WCDPD_Form::number(array(
                                        'id'                        => 'rp_wcdpd_' . $current_tab . '_bogo_receive_quantity_{i}',
                                        'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][bogo_receive_quantity]',
                                        'class'                     => 'rp_wcdpd_' . $current_tab . '_field_bogo_receive_quantity',
                                        'placeholder'               => __('Quantity', 'rp_wcdpd'),
                                        'label'                     => __('Get', 'rp_wcdpd') . ' <span class="rp_wcdpd_settings_label_extra">- ' . __('Discounted', 'rp_wcdpd') . '</span>',
                                        'disabled'                  => 'disabled',
                                        'data-rp-wcdpd-validation'  => RP_WCDPD_Settings::get('decimal_quantities') ? 'required,number_natural' : 'required,number_min_1,number_whole',
                                    )); ?>
                                </div>

                                <div class="rp_wcdpd_field rp_wcdpd_field_single">
                                    <?php RP_WCDPD_Form::grouped_select(array(
                                        'id'                        => 'rp_wcdpd_' . $current_tab . '_bogo_pricing_method_{i}',
                                        'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][bogo_pricing_method]',
                                        'class'                     => 'rp_wcdpd_' . $current_tab . '_field_bogo_pricing_method',
                                        'options'                   => RP_WCDPD_Pricing::get_pricing_methods_for_display('product_pricing_bogo'),
                                        'label'                     => __('Discount', 'rp_wcdpd'),
                                        'disabled'                  => 'disabled',
                                        'data-rp-wcdpd-validation'  => 'required',
                                    ), true); ?>
                                </div>
                                <div class="rp_wcdpd_field rp_wcdpd_field_single">
                                    <?php RP_WCDPD_Form::decimal(array(
                                        'id'                        => 'rp_wcdpd_' . $current_tab . '_bogo_pricing_value_{i}',
                                        'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][bogo_pricing_value]',
                                        'class'                     => 'rp_wcdpd_' . $current_tab . '_field_bogo_pricing_value',
                                        'placeholder'               => '0.00',
                                        'label'                     => '&nbsp;',
                                        'disabled'                  => 'disabled',
                                        'data-rp-wcdpd-validation'  => 'required,number_min_0',
                                    )); ?>
                                </div>

                                <div style="clear: both;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="rp_wcdpd_row_content_product_pricing_row rp_wcdpd_row_content_child_row rp_wcdpd_row_content_quantity_ranges_row rp_wcdpd_if rp_wcdpd_if_bulk rp_wcdpd_if_tiered" style="display: none;">
                        <div class="rp_wcdpd_field rp_wcdpd_field_full">
                            <label><?php _e('Quantity Ranges', 'rp_wcdpd'); ?></label>
                            <div class="rp_wcdpd_inner_wrapper">
                                <div class="rp_wcdpd_add_quantity_range rp_wcdpd_add_child_element">
                                    <button type="button" class="button" value="<?php _e('Add Range', 'rp_wcdpd'); ?>">
                                        <?php _e('Add Range', 'rp_wcdpd'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

                <div class="rp_wcdpd_row_content_product_pricing_row rp_wcdpd_row_content_pricing_row <?php echo ($current_tab === 'product_pricing' ? 'rp_wcdpd_if rp_wcdpd_if_simple' : ''); ?>">
                    <div class="rp_wcdpd_field rp_wcdpd_field_full">
                        <label><?php echo RP_WCDPD_Pricing::get_pricing_settings_label($current_tab); ?></label>
                        <div class="rp_wcdpd_inner_wrapper">

                            <div class="rp_wcdpd_field rp_wcdpd_field_double rp_wcdpd_no_left_margin">
                                <?php RP_WCDPD_Form::grouped_select(array(
                                    'id'                        => 'rp_wcdpd_' . $current_tab . '_pricing_method_{i}',
                                    'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][pricing_method]',
                                    'class'                     => 'rp_wcdpd_' . $current_tab . '_field_pricing_method',
                                    'options'                   => RP_WCDPD_Pricing::get_pricing_methods_for_display($current_tab . '_simple'),
                                    'data-rp-wcdpd-validation'  => 'required',
                                ), true); ?>
                            </div>
                            <div class="rp_wcdpd_field rp_wcdpd_field_double">
                                <?php RP_WCDPD_Form::decimal(array(
                                    'id'                        => 'rp_wcdpd_' . $current_tab . '_pricing_value_{i}',
                                    'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][pricing_value]',
                                    'class'                     => 'rp_wcdpd_' . $current_tab . '_field_pricing_value',
                                    'placeholder'               => '0.00',
                                    'data-rp-wcdpd-validation'  => 'required,number_min_0',
                                )); ?>
                            </div>

                            <div style="clear: both;"></div>

                        </div>
                        <div style="clear: both;"></div>
                    </div>
                    <div style="clear: both;"></div>
                </div>

                <?php if ($current_tab === 'product_pricing'): ?>

                    <div class="rp_wcdpd_row_content_product_pricing_row rp_wcdpd_row_content_pricing_row <?php echo ($current_tab === 'product_pricing' ? 'rp_wcdpd_if rp_wcdpd_if_group rp_wcdpd_if_group_repeat' : ''); ?>">
                        <div class="rp_wcdpd_field rp_wcdpd_field_full">
                            <label><?php echo RP_WCDPD_Pricing::get_pricing_settings_label($current_tab); ?></label>
                            <div class="rp_wcdpd_inner_wrapper">

                                    <div class="rp_wcdpd_field rp_wcdpd_field_double rp_wcdpd_no_left_margin">
                                        <?php RP_WCDPD_Form::grouped_select(array(
                                            'id'                        => 'rp_wcdpd_' . $current_tab . '_group_pricing_method_{i}',
                                            'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][group_pricing_method]',
                                            'class'                     => 'rp_wcdpd_' . $current_tab . '_field_group_pricing_method',
                                            'options'                   => RP_WCDPD_Pricing::get_pricing_methods_for_display('product_pricing_group'),
                                            'data-rp-wcdpd-validation'  => 'required',
                                        ), true); ?>
                                    </div>
                                    <div class="rp_wcdpd_field rp_wcdpd_field_double">
                                        <?php RP_WCDPD_Form::decimal(array(
                                            'id'                        => 'rp_wcdpd_' . $current_tab . '_group_pricing_value_{i}',
                                            'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][group_pricing_value]',
                                            'class'                     => 'rp_wcdpd_' . $current_tab . '_field_group_pricing_value',
                                            'placeholder'               => '0.00',
                                            'data-rp-wcdpd-validation'  => 'required,number_min_0',
                                        )); ?>
                                    </div>

                                <div style="clear: both;"></div>

                            </div>
                        </div>
                    </div>

                    <div class="rp_wcdpd_row_content_product_pricing_row rp_wcdpd_row_content_product_pricing_group_row rp_wcdpd_row_content_child_row rp_wcdpd_row_content_group_products_row rp_wcdpd_if rp_wcdpd_if_group rp_wcdpd_if_group_repeat" style="display: none;">
                        <div class="rp_wcdpd_field rp_wcdpd_field_full">
                            <label><?php _e('Product Group', 'rp_wcdpd'); ?></label>
                            <div class="rp_wcdpd_inner_wrapper">
                                <div class="rp_wcdpd_add_group_product rp_wcdpd_add_child_element">
                                    <button type="button" class="button" value="<?php _e('Add Product', 'rp_wcdpd'); ?>">
                                        <?php _e('Add Product', 'rp_wcdpd'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

                <div class="rp_wcdpd_row_content_child_row rp_wcdpd_row_content_product_conditions_row rp_wcdpd_if rp_wcdpd_if_simple rp_wcdpd_if_bulk rp_wcdpd_if_tiered rp_wcdpd_if_bogo rp_wcdpd_if_bogo_repeat rp_wcdpd_if_exclude" style="display: none;">
                    <div class="rp_wcdpd_field rp_wcdpd_field_full">
                        <?php if ($current_tab !== 'product_pricing'): ?>
                            <label><?php _e('Items', 'rp_wcdpd'); ?></label>
                        <?php else: ?>
                            <label class="rp_wcdpd_if rp_wcdpd_if_simple rp_wcdpd_if_bulk rp_wcdpd_if_tiered rp_wcdpd_if_exclude" style="display: none;"><?php _e('Products', 'rp_wcdpd'); ?></label>
                            <label class="rp_wcdpd_if rp_wcdpd_if_bogo rp_wcdpd_if_bogo_repeat" style="display: none;"><?php _e('Products - Buy', 'rp_wcdpd'); ?></label>
                        <?php endif; ?>

                        <div class="rp_wcdpd_inner_wrapper">
                            <div class="rp_wcdpd_add_product_condition rp_wcdpd_add_child_element">
                                <button type="button" class="button" value="<?php _e('Add Product', 'rp_wcdpd'); ?>">
                                    <?php _e('Add Product', 'rp_wcdpd'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>

                <?php if ($current_tab === 'product_pricing'): ?>
                    <div class="rp_wcdpd_row_content_child_row rp_wcdpd_row_content_bogo_product_conditions_row rp_wcdpd_if   rp_wcdpd_if_bogo rp_wcdpd_if_bogo_repeat" style="display: none;">
                        <div class="rp_wcdpd_field rp_wcdpd_field_full">
                            <label><?php _e('Products - Get', 'rp_wcdpd'); ?></label>
                            <div class="rp_wcdpd_inner_wrapper">
                                <div class="rp_wcdpd_add_bogo_product_condition rp_wcdpd_add_child_element">
                                    <button type="button" class="button" value="<?php _e('Add Product', 'rp_wcdpd'); ?>">
                                        <?php _e('Add Product', 'rp_wcdpd'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div style="clear: both;"></div>
                    </div>
                <?php endif; ?>

                <div class="rp_wcdpd_row_content_child_row rp_wcdpd_row_content_conditions_row">
                    <div class="rp_wcdpd_field rp_wcdpd_field_full">
                        <label><?php _e('Conditions', 'rp_wcdpd'); ?></label>
                        <div class="rp_wcdpd_inner_wrapper">
                            <div class="rp_wcdpd_add_condition rp_wcdpd_add_child_element">
                                <button type="button" class="button" value="<?php _e('Add Condition', 'rp_wcdpd'); ?>">
                                    <?php _e('Add Condition', 'rp_wcdpd'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>

            </div>
        </div>
    </div>

    <!-- NO PRODUCT CONDITIONS -->
    <div id="rp_wcdpd_no_product_conditions_template">
        <div class="rp_wcdpd_no_product_conditions rp_wcdpd_no_child_elements">
            <?php if ($current_tab === 'product_pricing'): ?>
                <?php _e('Applies to all products.', 'rp_wcdpd') ?>
            <?php else: ?>
                <?php _e('Applies to all items.', 'rp_wcdpd') ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- PRODUCT CONDITIONS WRAPPER -->
    <div id="rp_wcdpd_product_condition_wrapper_template">
        <div class="rp_wcdpd_product_condition_wrapper"></div>
    </div>

    <!-- PRODUCT CONDITION -->
    <div id="rp_wcdpd_product_condition_template">
        <div class="rp_wcdpd_product_condition rp_wcdpd_child_element">
            <div class="rp_wcdpd_product_condition_sort rp_wcdpd_child_element_sort">
                <div class="rp_wcdpd_product_condition_sort_handle rp_wcdpd_child_element_sort_handle">
                    <span class="dashicons dashicons-menu"></span>
                </div>
            </div>

            <div class="rp_wcdpd_product_condition_content rp_wcdpd_child_element_content">

                <div class="rp_wcdpd_product_condition_setting rp_wcdpd_product_condition_setting_single rp_wcdpd_product_condition_setting_type">
                    <?php RP_WCDPD_Form::grouped_select(array(
                        'id'                        => 'rp_wcdpd_' . $current_tab . '_product_conditions_{i}_type_{j}',
                        'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][product_conditions][{j}][type]',
                        'class'                     => 'rp_wcdpd_' . $current_tab . '_product_condition_type rp_wcdpd_child_element_field',
                        'options'                   => RP_WCDPD_Conditions::get_conditions_for_display($current_tab . '_product'),
                        'data-rp-wcdpd-validation'  => 'required',
                    ), true); ?>
                </div>

                <div class="rp_wcdpd_product_condition_setting_fields_wrapper"></div>

                <?php RP_WCDPD_Form::hidden(array(
                    'id'        => 'rp_wcdpd_' . $current_tab . '_product_conditions_{i}_uid_{j}',
                    'name'      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][product_conditions][{j}][uid]',
                ), false); ?>

                <div style="clear: both;"></div>
            </div>

            <div class="rp_wcdpd_product_condition_remove rp_wcdpd_child_element_remove">
                <div class="rp_wcdpd_product_condition_remove_handle rp_wcdpd_child_element_remove_handle">
                    <span class="dashicons dashicons-no-alt"></span>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <!-- PRODUCT CONDITION FIELDS -->
    <?php foreach(RP_WCDPD_Conditions::get_conditions_for_display($current_tab . '_product') as $group_key => $group): ?>
        <?php foreach($group['options'] as $option_key => $option): ?>

            <?php $combined_key = $group_key . '__' . $option_key; ?>

            <div id="rp_wcdpd_product_condition_setting_fields_<?php echo $combined_key ?>_template">
                <div class="rp_wcdpd_product_condition_setting_fields rp_wcdpd_product_condition_setting_fields_<?php echo $combined_key ?>">

                    <?php RP_WCDPD_Conditions::display_fields($current_tab, $combined_key, 'before', 'product_condition'); ?>

                    <div class="rp_wcdpd_product_condition_setting_fields_single">
                        <?php RP_WCDPD_Form::select(array(
                            'id'                        => 'rp_wcdpd_' . $current_tab . '_product_conditions_{i}_method_option_{j}',
                            'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][product_conditions][{j}][method_option]',
                            'class'                     => 'rp_wcdpd_' . $current_tab . '_product_condition_method rp_wcdpd_child_element_field',
                            'options'                   => RP_WCDPD_Conditions::get_condition_method_options_for_display($combined_key),
                            'data-rp-wcdpd-validation'  => 'required',
                        )); ?>
                    </div>

                    <?php RP_WCDPD_Conditions::display_fields($current_tab, $combined_key, 'after', 'product_condition'); ?>

                    <div style="clear: both;"></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>

    <?php if ($current_tab === 'product_pricing'): ?>

        <!-- NO BOGO PRODUCT CONDITIONS -->
        <div id="rp_wcdpd_no_bogo_product_conditions_template">
            <div class="rp_wcdpd_no_bogo_product_conditions rp_wcdpd_no_child_elements">
                <?php _e('Applies to all products.', 'rp_wcdpd') ?>
            </div>
        </div>

        <!-- BOGO PRODUCT CONDITIONS WRAPPER -->
        <div id="rp_wcdpd_bogo_product_condition_wrapper_template">
            <div class="rp_wcdpd_bogo_product_condition_wrapper"></div>
        </div>

        <!-- BOGO PRODUCT CONDITION -->
        <div id="rp_wcdpd_bogo_product_condition_template">
            <div class="rp_wcdpd_bogo_product_condition rp_wcdpd_child_element">
                <div class="rp_wcdpd_bogo_product_condition_sort rp_wcdpd_child_element_sort">
                    <div class="rp_wcdpd_bogo_product_condition_sort_handle rp_wcdpd_child_element_sort_handle">
                        <span class="dashicons dashicons-menu"></span>
                    </div>
                </div>

                <div class="rp_wcdpd_bogo_product_condition_content rp_wcdpd_child_element_content">

                    <div class="rp_wcdpd_bogo_product_condition_setting rp_wcdpd_bogo_product_condition_setting_single rp_wcdpd_bogo_product_condition_setting_type">
                        <?php RP_WCDPD_Form::grouped_select(array(
                            'id'                        => 'rp_wcdpd_' . $current_tab . '_bogo_product_conditions_{i}_type_{j}',
                            'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][bogo_product_conditions][{j}][type]',
                            'class'                     => 'rp_wcdpd_' . $current_tab . '_bogo_product_condition_type rp_wcdpd_child_element_field',
                            'options'                   => RP_WCDPD_Conditions::get_conditions_for_display($current_tab . '_product'),
                            'data-rp-wcdpd-validation'  => 'required',
                        ), true); ?>
                    </div>

                    <div class="rp_wcdpd_bogo_product_condition_setting_fields_wrapper"></div>

                    <?php RP_WCDPD_Form::hidden(array(
                        'id'        => 'rp_wcdpd_' . $current_tab . '_bogo_product_conditions_{i}_uid_{j}',
                        'name'      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][bogo_product_conditions][{j}][uid]',
                    ), false); ?>

                    <div style="clear: both;"></div>
                </div>

                <div class="rp_wcdpd_bogo_product_condition_remove rp_wcdpd_child_element_remove">
                    <div class="rp_wcdpd_bogo_product_condition_remove_handle rp_wcdpd_child_element_remove_handle">
                        <span class="dashicons dashicons-no-alt"></span>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>

        <!-- BOGO PRODUCT CONDITION FIELDS -->
        <?php foreach(RP_WCDPD_Conditions::get_conditions_for_display($current_tab . '_bogo_product') as $group_key => $group): ?>
            <?php foreach($group['options'] as $option_key => $option): ?>

                <?php $combined_key = $group_key . '__' . $option_key; ?>

                <div id="rp_wcdpd_bogo_product_condition_setting_fields_<?php echo $combined_key ?>_template">
                    <div class="rp_wcdpd_bogo_product_condition_setting_fields rp_wcdpd_bogo_product_condition_setting_fields_<?php echo $combined_key ?>">

                        <?php RP_WCDPD_Conditions::display_fields($current_tab, $combined_key, 'before', 'bogo_product_condition'); ?>

                        <div class="rp_wcdpd_bogo_product_condition_setting_fields_single">
                            <?php RP_WCDPD_Form::select(array(
                                'id'                        => 'rp_wcdpd_' . $current_tab . '_bogo_product_conditions_{i}_method_option_{j}',
                                'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][bogo_product_conditions][{j}][method_option]',
                                'class'                     => 'rp_wcdpd_' . $current_tab . '_bogo_product_condition_method rp_wcdpd_child_element_field',
                                'options'                   => RP_WCDPD_Conditions::get_condition_method_options_for_display($combined_key),
                                'data-rp-wcdpd-validation'  => 'required',
                            )); ?>
                        </div>

                        <?php RP_WCDPD_Conditions::display_fields($current_tab, $combined_key, 'after', 'bogo_product_condition'); ?>

                        <div style="clear: both;"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>

    <?php endif; ?>

    <!-- NO CONDITIONS -->
    <div id="rp_wcdpd_no_conditions_template">
        <div class="rp_wcdpd_no_conditions rp_wcdpd_no_child_elements"><?php _e('Applies in all cases.', 'rp_wcdpd'); ?></div>
    </div>

    <!-- CONDITIONS WRAPPER -->
    <div id="rp_wcdpd_condition_wrapper_template">
        <div class="rp_wcdpd_condition_wrapper"></div>
    </div>

    <!-- CONDITION -->
    <div id="rp_wcdpd_condition_template">
        <div class="rp_wcdpd_condition rp_wcdpd_child_element">
            <div class="rp_wcdpd_condition_sort rp_wcdpd_child_element_sort">
                <div class="rp_wcdpd_condition_sort_handle rp_wcdpd_child_element_sort_handle">
                    <span class="dashicons dashicons-menu"></span>
                </div>
            </div>

            <div class="rp_wcdpd_condition_content rp_wcdpd_child_element_content">

                <div class="rp_wcdpd_condition_setting rp_wcdpd_condition_setting_single rp_wcdpd_condition_setting_type">
                    <?php RP_WCDPD_Form::grouped_select(array(
                        'id'                        => 'rp_wcdpd_' . $current_tab . '_conditions_{i}_type_{j}',
                        'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][conditions][{j}][type]',
                        'class'                     => 'rp_wcdpd_' . $current_tab . '_condition_type rp_wcdpd_child_element_field',
                        'options'                   => RP_WCDPD_Conditions::get_conditions_for_display($current_tab),
                        'data-rp-wcdpd-validation'  => 'required',
                    ), true); ?>
                </div>

                <div class="rp_wcdpd_condition_setting_fields_wrapper"></div>

                <?php RP_WCDPD_Form::hidden(array(
                    'id'        => 'rp_wcdpd_' . $current_tab . '_conditions_{i}_uid_{j}',
                    'name'      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][conditions][{j}][uid]',
                ), false); ?>

                <div style="clear: both;"></div>
            </div>

            <div class="rp_wcdpd_condition_remove rp_wcdpd_child_element_remove">
                <div class="rp_wcdpd_condition_remove_handle rp_wcdpd_child_element_remove_handle">
                    <span class="dashicons dashicons-no-alt"></span>
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <!-- CONDITION FIELDS -->
    <?php foreach(RP_WCDPD_Conditions::get_conditions_for_display($current_tab) as $group_key => $group): ?>
        <?php foreach($group['options'] as $option_key => $option): ?>

            <?php $combined_key = $group_key . '__' . $option_key; ?>

            <div id="rp_wcdpd_condition_setting_fields_<?php echo $combined_key ?>_template">
                <div class="rp_wcdpd_condition_setting_fields rp_wcdpd_condition_setting_fields_<?php echo $combined_key ?>">

                    <?php RP_WCDPD_Conditions::display_fields($current_tab, $combined_key, 'before'); ?>

                    <div class="rp_wcdpd_condition_setting_fields_<?php echo (in_array($combined_key, array('customer__logged_in', 'other__pricing_rules_applied'), true) ? 'triple' : 'single'); ?>">
                        <?php RP_WCDPD_Form::select(array(
                            'id'                        => 'rp_wcdpd_' . $current_tab . '_conditions_{i}_method_option_{j}',
                            'name'                      => 'rp_wcdpd_settings[' . $current_tab . '][{i}][conditions][{j}][method_option]',
                            'class'                     => 'rp_wcdpd_' . $current_tab . '_condition_method rp_wcdpd_child_element_field',
                            'options'                   => RP_WCDPD_Conditions::get_condition_method_options_for_display($combined_key),
                            'data-rp-wcdpd-validation'  => 'required',
                        )); ?>
                    </div>

                    <?php RP_WCDPD_Conditions::display_fields($current_tab, $combined_key, 'after'); ?>

                    <div style="clear: both;"></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>

    <?php if ($current_tab === 'product_pricing'): ?>

        <!-- NO QUANTITY RANGES -->
        <div id="rp_wcdpd_no_quantity_ranges_template">
            <div class="rp_wcdpd_no_quantity_ranges rp_wcdpd_no_child_elements"><?php _e('No quantity ranges.', 'rp_wcdpd'); ?></div>
        </div>

        <!-- QUANTITY RANGES WRAPPER -->
        <div id="rp_wcdpd_quantity_range_wrapper_template">
            <div class="rp_wcdpd_quantity_range_wrapper"></div>
        </div>

        <!-- QUANTITY RANGE -->
        <div id="rp_wcdpd_quantity_range_template">
            <div class="rp_wcdpd_quantity_range rp_wcdpd_child_element">
                <div class="rp_wcdpd_quantity_range_sort rp_wcdpd_child_element_sort">
                    <div class="rp_wcdpd_quantity_range_sort_handle rp_wcdpd_child_element_sort_handle">
                        <span class="dashicons dashicons-menu"></span>
                    </div>
                </div>

                <div class="rp_wcdpd_quantity_range_content rp_wcdpd_child_element_content">

                    <?php $quantity_ranges_numeric_method = RP_WCDPD_Settings::get('decimal_quantities') ? 'decimal' : 'number'; ?>

                    <div class="rp_wcdpd_quantity_range_setting">
                        <div class="rp_wcdpd_field rp_wcdpd_field_full">
                            <?php RP_WCDPD_Form::$quantity_ranges_numeric_method(array(
                                'id'                        => 'rp_wcdpd_product_pricing_quantity_ranges_{i}_from_{j}',
                                'name'                      => 'rp_wcdpd_settings[product_pricing][{i}][quantity_ranges][{j}][from]',
                                'class'                     => 'rp_wcdpd_product_pricing_quantity_range_from rp_wcdpd_child_element_field',
                                'placeholder'               => __('From', 'rp_wcdpd'),
                                'data-rp-wcdpd-validation'  => RP_WCDPD_Settings::get('decimal_quantities') ? 'required,number_min_0' : 'required,number_min_1,number_whole',
                            )); ?>
                        </div>
                    </div>

                    <div class="rp_wcdpd_quantity_range_setting">
                        <div class="rp_wcdpd_field rp_wcdpd_field_full">
                            <?php RP_WCDPD_Form::$quantity_ranges_numeric_method(array(
                                'id'                        => 'rp_wcdpd_product_pricing_quantity_ranges_{i}_to_{j}',
                                'name'                      => 'rp_wcdpd_settings[product_pricing][{i}][quantity_ranges][{j}][to]',
                                'class'                     => 'rp_wcdpd_product_pricing_quantity_range_to rp_wcdpd_child_element_field',
                                'placeholder'               => __('To - No limit', 'rp_wcdpd'),
                                'data-rp-wcdpd-validation'  => RP_WCDPD_Settings::get('decimal_quantities') ? 'number_natural' : 'number_min_1,number_whole',
                            )); ?>
                        </div>
                    </div>

                    <div class="rp_wcdpd_quantity_range_setting">
                        <div class="rp_wcdpd_field rp_wcdpd_field_full">
                            <?php RP_WCDPD_Form::grouped_select(array(
                                'id'                        => 'rp_wcdpd_product_pricing_quantity_ranges_{i}_pricing_method_{j}',
                                'name'                      => 'rp_wcdpd_settings[product_pricing][{i}][quantity_ranges][{j}][pricing_method]',
                                'class'                     => 'rp_wcdpd_product_pricing_quantity_range_pricing_method rp_wcdpd_child_element_field',
                                'options'                   => RP_WCDPD_Pricing::get_pricing_methods_for_display('product_pricing_volume'),
                                'data-rp-wcdpd-validation'  => 'required',
                            ), true); ?>
                        </div>
                    </div>

                    <div class="rp_wcdpd_quantity_range_setting">
                        <div class="rp_wcdpd_field rp_wcdpd_field_full">
                            <?php RP_WCDPD_Form::decimal(array(
                                'id'                        => 'rp_wcdpd_product_pricing_quantity_ranges_{i}_pricing_value_{j}',
                                'name'                      => 'rp_wcdpd_settings[product_pricing][{i}][quantity_ranges][{j}][pricing_value]',
                                'class'                     => 'rp_wcdpd_product_pricing_quantity_range_pricing_value rp_wcdpd_child_element_field',
                                'placeholder'               => '0.00',
                                'data-rp-wcdpd-validation'  => 'required,number_min_0',
                            )); ?>
                        </div>
                    </div>

                    <?php RP_WCDPD_Form::hidden(array(
                        'id'        => 'rp_wcdpd_product_pricing_quantity_ranges_{i}_uid_{j}',
                        'name'      => 'rp_wcdpd_settings[product_pricing][{i}][quantity_ranges][{j}][uid]',
                    ), false); ?>

                    <div style="clear: both;"></div>

                </div>

                <div class="rp_wcdpd_quantity_range_remove rp_wcdpd_child_element_remove">
                    <div class="rp_wcdpd_quantity_range_remove_handle rp_wcdpd_child_element_remove_handle">
                        <span class="dashicons dashicons-no-alt"></span>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>

        <!-- NO GROUP PRODUCTS -->
        <div id="rp_wcdpd_no_group_products_template">
            <div class="rp_wcdpd_no_group_products rp_wcdpd_no_child_elements"><?php _e('No products in group.', 'rp_wcdpd'); ?></div>
        </div>

        <!-- GROUP PRODUCTS WRAPPER -->
        <div id="rp_wcdpd_group_product_wrapper_template">
            <div class="rp_wcdpd_group_product_wrapper"></div>
        </div>

        <!-- GROUP PRODUCT -->
        <div id="rp_wcdpd_group_product_template">
            <div class="rp_wcdpd_group_product rp_wcdpd_child_element">
                <div class="rp_wcdpd_group_product_sort rp_wcdpd_child_element_sort">
                    <div class="rp_wcdpd_group_product_sort_handle rp_wcdpd_child_element_sort_handle">
                        <span class="dashicons dashicons-menu"></span>
                    </div>
                </div>

                <div class="rp_wcdpd_group_product_content rp_wcdpd_child_element_content">

                    <div class="rp_wcdpd_group_product_setting rp_wcdpd_group_product_setting_single rp_wcdpd_group_product_setting_quantity">
                        <?php RP_WCDPD_Form::number(array(
                            'id'                        => 'rp_wcdpd_product_pricing_group_products_{i}_quantity_{j}',
                            'name'                      => 'rp_wcdpd_settings[product_pricing][{i}][group_products][{j}][quantity]',
                            'class'                     => 'rp_wcdpd_product_pricing_group_product_quantity rp_wcdpd_child_element_field',
                            'placeholder'               => 'Qty',
                            'data-rp-wcdpd-validation'  => RP_WCDPD_Settings::get('decimal_quantities') ? 'required,number_natural' : 'required,number_min_1,number_whole',
                        )); ?>
                    </div>

                    <div class="rp_wcdpd_group_product_setting rp_wcdpd_group_product_setting_single rp_wcdpd_group_product_setting_type">
                        <?php RP_WCDPD_Form::grouped_select(array(
                            'id'                        => 'rp_wcdpd_product_pricing_group_products_{i}_type_{j}',
                            'name'                      => 'rp_wcdpd_settings[product_pricing][{i}][group_products][{j}][type]',
                            'class'                     => 'rp_wcdpd_product_pricing_group_product_type rp_wcdpd_child_element_field',
                            'options'                   => RP_WCDPD_Conditions::get_conditions_for_display('product_pricing_group_product'),
                            'data-rp-wcdpd-validation'  => 'required',
                        ), true); ?>
                    </div>

                    <?php foreach(RP_WCDPD_Conditions::get_conditions_for_display('product_pricing_group_product') as $group_key => $group): ?>
                        <?php foreach($group['options'] as $option_key => $option): ?>

                            <?php $combined_key = $group_key . '__' . $option_key; ?>

                            <div class="rp_wcdpd_group_product_setting_fields rp_wcdpd_group_product_setting_fields_<?php echo $combined_key ?>" style="display: none;">

                                <div class="rp_wcdpd_group_product_setting_fields_single">
                                    <?php RP_WCDPD_Form::select(array(
                                        'id'                        => 'rp_wcdpd_product_pricing_group_products_{i}_method_option_{j}',
                                        'name'                      => 'rp_wcdpd_settings[product_pricing][{i}][group_products][{j}][method_option]',
                                        'class'                     => 'rp_wcdpd_product_pricing_group_product_method rp_wcdpd_child_element_field',
                                        'options'                   => RP_WCDPD_Conditions::get_condition_method_options_for_display($combined_key),
                                        'disabled'                  => 'disabled',
                                        'data-rp-wcdpd-validation'  => 'required',
                                    )); ?>
                                </div>

                                <?php if (RP_WCDPD_Conditions::uses_field($combined_key, 'products')): ?>
                                    <div class="rp_wcdpd_group_product_setting_fields_double">
                                        <?php RP_WCDPD_Form::multiselect(array(
                                            'id'                        => 'rp_wcdpd_product_pricing_group_products_{i}_products_{j}',
                                            'name'                      => 'rp_wcdpd_settings[product_pricing][{i}][group_products][{j}][products][]',
                                            'class'                     => 'rp_wcdpd_product_pricing_group_product_products rp_wcdpd_child_element_field rp_wcdpd_select2',
                                            'options'                   => array(),
                                            'disabled'                  => 'disabled',
                                            'data-rp-wcdpd-validation'  => 'required',
                                        )); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (RP_WCDPD_Conditions::uses_field($combined_key, 'product_variations')): ?>
                                    <div class="rp_wcdpd_group_product_setting_fields_double">
                                        <?php RP_WCDPD_Form::multiselect(array(
                                            'id'                        => 'rp_wcdpd_product_pricing_group_products_{i}_product_variations_{j}',
                                            'name'                      => 'rp_wcdpd_settings[product_pricing][{i}][group_products][{j}][product_variations][]',
                                            'class'                     => 'rp_wcdpd_product_pricing_group_product_product_variations rp_wcdpd_child_element_field rp_wcdpd_select2',
                                            'options'                   => array(),
                                            'disabled'                  => 'disabled',
                                            'data-rp-wcdpd-validation'  => 'required',
                                        )); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (RP_WCDPD_Conditions::uses_field($combined_key, 'product_categories')): ?>
                                    <div class="rp_wcdpd_group_product_setting_fields_double">
                                        <?php RP_WCDPD_Form::multiselect(array(
                                            'id'                        => 'rp_wcdpd_product_pricing_group_products_{i}_product_categories_{j}',
                                            'name'                      => 'rp_wcdpd_settings[product_pricing][{i}][group_products][{j}][product_categories][]',
                                            'class'                     => 'rp_wcdpd_product_pricing_group_product_product_categories rp_wcdpd_child_element_field rp_wcdpd_select2',
                                            'options'                   => array(),
                                            'disabled'                  => 'disabled',
                                            'data-rp-wcdpd-validation'  => 'required',
                                        )); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (RP_WCDPD_Conditions::uses_field($combined_key, 'product_attributes')): ?>
                                    <div class="rp_wcdpd_group_product_setting_fields_double">
                                        <?php RP_WCDPD_Form::multiselect(array(
                                            'id'                        => 'rp_wcdpd_product_pricing_group_products_{i}_product_attributes_{j}',
                                            'name'                      => 'rp_wcdpd_settings[product_pricing][{i}][group_products][{j}][product_attributes][]',
                                            'class'                     => 'rp_wcdpd_product_pricing_group_product_product_attributes rp_wcdpd_child_element_field rp_wcdpd_select2',
                                            'options'                   => array(),
                                            'disabled'                  => 'disabled',
                                            'data-rp-wcdpd-validation'  => 'required',
                                        )); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (RP_WCDPD_Conditions::uses_field($combined_key, 'product_tags')): ?>
                                    <div class="rp_wcdpd_group_product_setting_fields_double">
                                        <?php RP_WCDPD_Form::multiselect(array(
                                            'id'                        => 'rp_wcdpd_product_pricing_group_products_{i}_product_tags_{j}',
                                            'name'                      => 'rp_wcdpd_settings[product_pricing][{i}][group_products][{j}][product_tags][]',
                                            'class'                     => 'rp_wcdpd_product_pricing_group_product_product_tags rp_wcdpd_child_element_field rp_wcdpd_select2',
                                            'options'                   => array(),
                                            'disabled'                  => 'disabled',
                                            'data-rp-wcdpd-validation'  => 'required',
                                        )); ?>
                                    </div>
                                <?php endif; ?>

                                <div style="clear: both;"></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <?php RP_WCDPD_Form::hidden(array(
                        'id'        => 'rp_wcdpd_product_pricing_group_products_{i}_uid_{j}',
                        'name'      => 'rp_wcdpd_settings[product_pricing][{i}][group_products][{j}][uid]',
                    ), false); ?>

                    <div style="clear: both;"></div>

                </div>

                <div class="rp_wcdpd_group_product_remove rp_wcdpd_child_element_remove">
                    <div class="rp_wcdpd_group_product_remove_handle rp_wcdpd_child_element_remove_handle">
                        <span class="dashicons dashicons-no-alt"></span>
                    </div>
                </div>
                <div style="clear: both;"></div>

            </div>
        </div>

    <?php endif; ?>

</div>
