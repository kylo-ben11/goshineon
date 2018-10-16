/**
 * WooCommerce Dynamic Pricing & Discounts - Volume Pricing Table Scripts
 */
jQuery(document).ready(function() {

    /**
     * Modal control
     */
    jQuery('.rp_wcdpd_product_page_modal_link span').click(function() {

        if (!jQuery('#rp_wcdpd_modal_overlay').length) {
            jQuery('body').append('<div id="rp_wcdpd_modal_overlay" class="rp_wcdpd_modal_overlay"></div>');
        }

        jQuery('#rp_wcdpd_modal_overlay').click(function() {
            jQuery('#rp_wcdpd_modal_overlay').fadeOut();
            jQuery('.rp_wcdpd_modal').fadeOut();
        });

        var pricing_table = jQuery('.rp_wcdpd_modal');
        jQuery('#rp_wcdpd_modal_overlay').fadeIn();
        pricing_table.css('top', '50%').css('left', '50%').css('margin-top', -pricing_table.outerHeight()/2).css('margin-left', -pricing_table.outerWidth()/2).fadeIn();

        return false;
    });

    /**
     * Variable product table control
     */
    jQuery('input:hidden[name="variation_id"]').change(function() {
        jQuery('.rp_wcdpd_pricing_table_variation').hide();
        jQuery('#rp_wcdpd_pricing_table_variation_' + jQuery(this).val()).show();
    }).change();


});
