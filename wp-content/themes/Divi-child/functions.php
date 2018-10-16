<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

// Display 24 products per page. Goes in functions.php
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 24;' ), 20 );
        
if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css' );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css' );
add_action('woocommerce_after_shop_loop_item_title','woocommerce_template_single_excerpt', 5);
// END ENQUEUE PARENT ACTION

