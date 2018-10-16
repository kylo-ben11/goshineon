/**
 * Settings Scripts
 */

jQuery(document).ready(function() {

    /**
     * Display hints in settings
     */
    jQuery('.rp_wcdpd_settings_container .rp_wcdpd_setting').each(function() {

        // Get hint
        var hint = jQuery(this).data('rp-wcdpd-hint');

        // Check if hint is set
        if (hint) {

            // Append hint element
            jQuery(this).parent().append('<div class="rp_wcdpd_settings_hint">' + hint + '</div>');
        }
    });

    /**
     * Toggle fields
     */
    jQuery('#rp_wcdpd_cart_discounts_if_multiple_applicable').change(function() {
        var display = jQuery(this).val() === 'combined';
        jQuery('#rp_wcdpd_cart_discounts_combined_title').prop('disabled', !display).closest('tr').css('display', (display ? 'table-row' : 'none'));
    }).change();
    jQuery('#rp_wcdpd_checkout_fees_if_multiple_applicable').change(function() {
        var display = jQuery(this).val() === 'combined';
        jQuery('#rp_wcdpd_checkout_fees_combined_title').prop('disabled', !display).closest('tr').css('display', (display ? 'table-row' : 'none'));
    }).change();

    /**
     * Toggle promotion fields
     */
    jQuery(['rp_wcdpd_promo_volume_pricing_table', 'rp_wcdpd_promo_display_price_override']).each(function(index, value) {
        jQuery('#' + value).change(function() {
            if (jQuery(this).is(':checked')) {
                jQuery('[id^="' + value + '_"]').closest('tr').show();
            }
            else {
                jQuery('[id^="' + value + '_"]').closest('tr').hide();
            }
        }).change();
    });





    /**
     * We are done by now, remove preloader
     */
    jQuery('#rp_wcdpd_preloader').remove();

});
