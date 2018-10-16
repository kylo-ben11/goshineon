<?php
/**
 * @package snapCX
 */
 /*
 * Plugin Name: snapCX WooCommerce Order Tracking
 * Plugin URI: https://snapcx.io/shippingTracking
 * Description: Easily manage your order tracking. Customers will get notified when the order is complete and shipped. To get started: 1) Click the "Activate" link to the left of this description, 2) <a href="https://snapcx.io/pricing?solution=tracking">Sign up for an snapCX subscription plan</a> to get an API key, and 3) Go to your Plugin configuration page (inside woocommerce menu), and save your API key.
 * Version: 2.2.1
 * Requires at least: 4.0
 * Tested up to: 4.9.6
 * WC requires at least: 3.2.0
 * WC tested up to: 3.4.4
 * Author: WooCommerce
 * Author URI: https://snapcx.io/shippingTracking
 * Developer: snapCX Team
 * Developer URI: https://snapcx.io/shippingTracking
 * Text Domain: woocommerce-extension
 * Domain Path: /languages
 */
 
 /*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 JFrameworks.com LLC.
*/

 /*
 * Copyright: © 2009-2015 JFrameworks.com LLC.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
global $UCVersion;
$UCVersion = '2.0.4';

ob_start();
 // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'UC_MAIN_FILE' ) ) {
	define( 'UC_MAIN_FILE', __FILE__ );
}

if ( ! defined( 'UC_URL' ) ) {
	define( 'UC_URL', plugin_dir_url(__FILE__) );
}

require_once( plugin_dir_path( __FILE__ ) . 'class-uc-backend.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-uc-frontend.php' );
require_once( plugin_dir_path( __FILE__ ) . 'UberCXTrackingAccountVerifier.php' );
require_once( plugin_dir_path( __FILE__ ) . 'UberCXTrackingApiClient.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( UC_BACKEND::get_instance(), 'uc_plugin_activate' ) );
//Code For Deactivation 
register_deactivation_hook( __FILE__, array( 'UC_BACKEND', 'uc_plugin_deactivate_plugin' ) );

//add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'plugin_add_settings_link' );		

UC_BACKEND::get_instance();
UC_Frontend::get_instance();

?>