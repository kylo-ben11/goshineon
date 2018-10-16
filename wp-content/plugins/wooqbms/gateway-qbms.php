<?php
/**
 * Plugin Name: Quickbooks (Intuit) Gateway for WooCommerce
 * Plugin URI: http://www.patsatech.com/
 * Description: WooCommerce Plugin for accepting payment through Quickbooks (Intuit) Gateway.
 * Version: 1.2.6
 * Author: PatSaTECH
 * Author URI: http://www.patsatech.com
 * Contributors: patsatech
 * Requires at least: 3.5
 * Tested up to: 4.1
 *
 * Text Domain: woo-quickbooks-patsatech
 * Domain Path: /lang/
 *
 * @package Quickbooks (Intuit) Gateway for WooCommerce
 * @author PatSaTECH
 */

add_action('plugins_loaded', 'init_woocommerce_qbms', 0);

function init_woocommerce_qbms() {

    if ( ! class_exists( 'WC_Payment_Gateway' ) ) { return; }
	
	load_plugin_textdomain('woo-quickbooks-patsatech', false, dirname( plugin_basename( __FILE__ ) ) . '/lang');

	class woocommerce_qbms extends WC_Payment_Gateway {

		public function __construct() {
		global $woocommerce;

	        $this->id			= 'quickbooks';
	        $this->method_title = __( 'QuickBooks', 'woo-quickbooks-patsatech' );
			$this->icon     	= apply_filters( 'woocommerce_quickbooks_icon', '' );
	        $this->has_fields 	= true;
			$this->liveurl		= 'https://webmerchantaccount.quickbooks.com/j/AppGateway';
			$this->testurl		= 'https://webmerchantaccount.ptc.quickbooks.com/j/AppGateway';
			
			$this->supports		= array(
										'products',
										'refunds'
										);

			$default_card_type_options = array(
				'VISA' => 'Visa', 
				'MC'   => 'MasterCard',
				'AMEX' => 'American Express',
				'DISC' => 'Discover',
				'JCB'  => 'JCB'
			);
			$this->card_type_options = apply_filters( 'woocommerce_quickbooks_card_types', $default_card_type_options );
			
			// Load the form fields.
			$this->init_form_fields();

			// Load the settings.
			$this->init_settings();

			$this->is_description_empty();

			// Define user set variables
			$this->title 		= $this->settings['title'];
			$this->description 	= $this->settings['description'];
			$this->app_login 	= $this->settings['applogin'];
			$this->con_ticket 	= $this->settings['conticket'];
			$this->cardtypes    = $this->settings['cardtypes'];
			$this->app_id 		= $this->settings['appid'];
			$this->showdesc		= $this->settings['showdesc'];
			$this->testmode		= $this->settings['testmode'];
	        $this->woo_version 	= $this->get_woo_version();
	        
			add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options') );
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

			if ( !$this->is_valid_for_use() ) $this->enabled = false;
	    }

			
		/**
		 * get_icon function.
		 *
		 * @access public
		 * @return string
		 */
		function get_icon() {
			global $woocommerce;
			
			$icon = '';
			if ( $this->icon ) {
				// default behavior
				$icon = '<img src="' . $this->force_ssl( $this->icon ) . '" alt="' . $this->title . '" />';
			} elseif ( $this->cardtypes ) {
				// display icons for the selected card types
				$icon = '';
				foreach ( $this->cardtypes as $cardtype ) {
					if ( file_exists( plugin_dir_path( __FILE__ ) . '/images/card-' . strtolower( $cardtype ) . '.png' ) ) {
						$icon .= '<img src="' . $this->force_ssl( plugins_url( '/images/card-' . strtolower( $cardtype ) . '.png', __FILE__ ) ) . '" alt="' . strtolower( $cardtype ) . '" />';
					}
				}
			}
			
			return apply_filters( 'woocommerce_gateway_icon', $icon, $this->id );
		}
	     /**
	     * Check if this gateway is enabled and available in the user's country
	     */
	    function is_valid_for_use() {
	        if (!in_array(get_option('woocommerce_currency'), array('AUD', 'BRL', 'CAD', 'MXN', 'NZD', 'HKD', 'SGD', 'USD', 'EUR', 'JPY', 'TRY', 'NOK', 'CZK', 'DKK', 'HUF', 'ILS', 'MYR', 'PHP', 'PLN', 'SEK', 'CHF', 'TWD', 'THB', 'GBP'))) return false;

	        return true;
	    }

	     /**
	     * To Check if Description is Empty
	     */
	    function is_description_empty() {

			$showdesc = '';

			return($showdesc);
	    }

		/**
		 * Admin Panel Options
		 * - Options for bits like 'title' and availability on a country-by-country basis
		 *
		 * @since 1.0.0
		 */
		public function admin_options() {

	    	?>
	    	<h3><?php _e('QBMS', 'woo-quickbooks-patsatech'); ?></h3>
	    	<p><?php _e('QBMS works by charging the customers Credit Card.', 'woo-quickbooks-patsatech'); ?></p>
	    	<table class="form-table">
	    	<?php
	    		if ( $this->is_valid_for_use() ) :

	    			// Generate the HTML For the settings form.
	    			$this->generate_settings_html();

	    		else :

	    			?>
	            		<div class="inline error"><p><strong><?php _e( 'Gateway Disabled', 'woo-quickbooks-patsatech' ); ?></strong>: <?php _e( 'QBMS does not support your store currency.', 'woo-quickbooks-patsatech' ); ?></p></div>
	        		<?php

	    		endif;
	    	?>
			</table><!--/.form-table-->
	    	<?php
	    } // End admin_options()

		/**
	     * Initialise Gateway Settings Form Fields
	     */
	    function init_form_fields() {
	    	$this->form_fields = array(
				'enabled' => array(
								'title' => __( 'Enable/Disable', 'woo-quickbooks-patsatech' ),
								'type' => 'checkbox',
								'label' => __( 'Enable QBMS', 'woo-quickbooks-patsatech' ),
								'default' => 'yes'
							),
				'title' => array(
								'title' => __( 'Title', 'woo-quickbooks-patsatech' ),
								'type' => 'text',
								'description' => __( 'This controls the title which the user sees during checkout.', 'woo-quickbooks-patsatech' ),
								'default' => __( 'QuickBooks', 'woo-quickbooks-patsatech' )
							),
				'showdesc' => array(
								'title' => __( 'Show Description', 'woo-quickbooks-patsatech' ),
								'type' => 'checkbox',
								'label' => __( 'To Show Description', 'woo-quickbooks-patsatech' ),
								'default' => 'no'
							),
				'description' => array(
								'title' => __( 'Description', 'woo-quickbooks-patsatech' ),
								'type' => 'textarea',
								'description' => __( 'This controls the description which the user sees during checkout.', 'woo-quickbooks-patsatech' ),
								'default' => __("Enter your Credit Card Details below.", 'woo-quickbooks-patsatech')
							),
				'applogin' => array(
								'title' => __( 'QBMS Application Login', 'woo-quickbooks-patsatech' ),
								'type' => 'text',
								'description' => __( 'Please enter your Application Login; this is needed in order to take payment.', 'woo-quickbooks-patsatech' ),
								'default' => ''
							),
				'appid' => array(
								'title' => __( 'QBMS App ID', 'woo-quickbooks-patsatech' ),
								'type' => 'text',
								'label' => __( 'Please enter your App ID; this is needed in order to take payment.', 'woo-quickbooks-patsatech' ),
								'default' => ''
							),
				'conticket' => array(
								'title' => __( 'QBMS Connection Ticket', 'woo-quickbooks-patsatech' ),
								'type' => 'text',
								'label' => __( 'Please enter your Connection Ticket; this is needed in order to take payment.', 'woo-quickbooks-patsatech' ),
								'default' => ''
							),
				'testmode' => array(
								'title' => __( 'QBMS Sandbox', 'woo-quickbooks-patsatech' ),
								'type' => 'checkbox',
								'label' => __( 'Enable QBMS Sandbox', 'woo-quickbooks-patsatech' ),
								'default' => 'yes'
							),
				'cardtypes'	=> array(
								'title' => __( 'Accepted Card Logos', 'woo-quickbooks-patsatech' ), 
								'type' => 'multiselect', 
								'description' => __( 'Select which card types you accept to display the logos for on your checkout page.  This is purely cosmetic and optional, and will have no impact on the cards actually accepted by your account.', 'woo-quickbooks-patsatech' ), 
								'default' => '',
								'options' => $this->card_type_options,
					),
				);

	    } // End init_form_fields()


	    /**
		 * There are no payment fields for nmi, but we want to show the description if set.
		 **/
	    function payment_fields() {

			if ($this->showdesc == 'yes') {
				echo wpautop(wptexturize($this->description));
			}
			else {
				$this->is_description_empty();
			}
			
			include_once('cc_form.php');
			
	    }


	    public function validate_fields()
	    {
	        global $woocommerce;

	        if (!$this->isCreditCardNumber($_POST['billing_credircard'])){
				if($this->woo_version >= 2.1){
					wc_add_notice( __('(Credit Card Number) is not valid.', 'woo-quickbooks-patsatech'), 'error' );
				}else if( $this->woo_version < 2.1 ){
					$woocommerce->add_error( __('(Credit Card Number) is not valid.', 'woo-quickbooks-patsatech') );
				}else{
					$woocommerce->add_error( __('(Credit Card Number) is not valid.', 'woo-quickbooks-patsatech') );
				}
			}

	        if (!$this->isCorrectExpireDate($_POST['billing_expdatemonth'], $_POST['billing_expdateyear'])){
				if($this->woo_version >= 2.1){
					wc_add_notice( __('(Card Expire Date) is not valid.', 'woo-quickbooks-patsatech'), 'error' );
				}else if( $this->woo_version < 2.1 ){
					$woocommerce->add_error( __('(Card Expire Date) is not valid.', 'woo-quickbooks-patsatech') );
				}else{
					$woocommerce->add_error( __('(Card Expire Date) is not valid.', 'woo-quickbooks-patsatech') );
				}
				
			}

	        if (!$_POST['billing_cvv']){
				if($this->woo_version >= 2.1){
					wc_add_notice( __('(Card CVV) is not entered.', 'woo-quickbooks-patsatech'), 'error' );
				}else if( $this->woo_version < 2.1 ){
					$woocommerce->add_error( __('(Card CVV) is not entered.', 'woo-quickbooks-patsatech') );
				}else{
					$woocommerce->add_error( __('(Card CVV) is not entered.', 'woo-quickbooks-patsatech') );
				}
			}

	    }
		
		/**
		 * Process the payment and return the result
		 **/
		function process_payment( $order_id ) {
	        global $woocommerce;
			
			$order = new WC_Order( $order_id );
			
			$app_login = $this->app_login;
			$con_ticket = $this->con_ticket;
			$app_id = $this->app_id;
			
			if ( $this->testmode == 'yes' ):
				$qbmsurl = $this->testurl;
			else :
				$qbmsurl = $this->liveurl;
			endif;
			
			$amount = number_format($order->order_total, 2, '.', '');
			$stamp = date("YdmHisB");
			$orderid = $stamp.'|'.$order_id;
			
			$qbXML = new SimpleXMLElement('<?qbmsxml version="4.5"?><QBMSXML />');
			$signOnDesktop = $qbXML->addChild('SignonMsgsRq')->addChild('SignonDesktopRq');
			$signOnDesktop->addChild('ClientDateTime', date('Y-m-d\TH:i:s'));
			$signOnDesktop->addChild('ApplicationLogin', $app_login);
			$signOnDesktop->addChild('ConnectionTicket', $con_ticket);
			$signOnDesktop->addChild('Language', 'English');
			$signOnDesktop->addChild('AppID', $app_id);
			$signOnDesktop->addChild('AppVer', '1.0');
			$cardChargeRequest = $qbXML->addChild('QBMSXMLMsgsRq')->addChild('CustomerCreditCardChargeRq');
			$cardChargeRequest->addChild('TransRequestID', $stamp);
			$cardChargeRequest->addChild('CreditCardNumber', $_POST["billing_credircard"]);
			$cardChargeRequest->addChild('ExpirationMonth', $_POST["billing_expdatemonth"]);
			$cardChargeRequest->addChild('ExpirationYear', $_POST["billing_expdateyear"]);
			$cardChargeRequest->addChild('IsECommerce', 'true');
			$cardChargeRequest->addChild('Amount', number_format( $amount, 2, '.', ''  ) );
			$cardChargeRequest->addChild('NameOnCard', $order->billing_first_name.' '.$order->billing_last_name);
			$cardChargeRequest->addChild('CreditCardAddress', $order->billing_address_1);
			$cardChargeRequest->addChild('CreditCardPostalCode', $order->billing_postcode);
			$cardChargeRequest->addChild('SalesTaxAmount', number_format( $order->get_total_tax(), 2, '.', ''  ) );
			$cardChargeRequest->addChild('CardSecurityCode', $_POST["billing_cvv"]);
			
			$xml = $qbXML->asXML();
			
			$response = wp_remote_post( 
			    $qbmsurl, 
			    array(
			        'method' => 'POST',
			        'timeout' => 60,
			        'redirection' => 5,
			        'httpversion' => '1.0',
			        'headers' => array(
			            'Content-Type' => 'application/x-qbmsxml',
						'Content-Length' => strlen($xml)
			        ),
			        'body' => $xml,
			        'sslverify' => false
			    )
			);
		
			if (!is_wp_error($response) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) { 
			
				$removeline = array("\n");
				$result = str_replace($removeline, '', $response['body']);
			
				try {
					$xml = new SimpleXMLElement($result);
				}
				catch (Exception $e) {
					$xml = null;
				}
							
				$approved = false;
				if (isset($xml->SignonMsgsRs->SignonDesktopRs['statusSeverity']) && $xml->SignonMsgsRs->SignonDesktopRs['statusSeverity'] == 'ERROR') {
					$statusCode = (string)$xml->SignonMsgsRs->SignonDesktopRs['statusCode'];
					$statusMessage = (string)$xml->SignonMsgsRs->SignonDesktopRs['statusMessage'];
				} else if (isset($xml->QBMSXMLMsgsRs->CustomerCreditCardChargeRs['statusSeverity'])) {
					$statusCode = (string)$xml->QBMSXMLMsgsRs->CustomerCreditCardChargeRs['statusCode'];
					$statusMessage = (string)$xml->QBMSXMLMsgsRs->CustomerCreditCardChargeRs['statusMessage'];
					$TransID = (string)$xml->QBMSXMLMsgsRs->CustomerCreditCardChargeRs->CreditCardTransID;
					$clientTransID = (string)$xml->QBMSXMLMsgsRs->CustomerCreditCardChargeRs->ClientTransID;
					$Auth = (string)$xml->QBMSXMLMsgsRs->CustomerCreditCardChargeRs->AuthorizationCode;
					

					if ((string)$xml->QBMSXMLMsgsRs->CustomerCreditCardChargeRs['statusCode'] == 0) {
						$approved = true;
					}
				} else {
					$statusCode = 'Unknown';
					$statusMessage = 'Unknown';
				}
						
			   	if($approved) {		
				
					// Payment completed
				    $order->add_order_note( sprintf( __('QBMS Payment %s. The QBMS Transaction Id is %s Authorization Code is %s', 'woo-quickbooks-patsatech'), $statusMessage, $TransID, $Auth ) );
					
				    $order->payment_complete($TransID.'-'.$clientTransID);
									
					return array(
						'result' 	=> 'success',
						'redirect'	=>  $this->get_return_url($order)
					);
				}
				else
				{
					if($this->woo_version >= 2.1){
						wc_add_notice( sprintf( __('Transaction Failed. %s %s', 'woo-quickbooks-patsatech'), $statusCode, $statusMessage ), 'error' );
					}else if( $this->woo_version < 2.1 ){
						$woocommerce->add_error( sprintf( __('Transaction Failed. %s %s', 'woo-quickbooks-patsatech'), $statusCode, $statusMessage ) );
					}else{
						$woocommerce->add_error( sprintf( __('Transaction Failed. %s %s', 'woo-quickbooks-patsatech'), $statusCode, $statusMessage ) );
					}
					
				    $order->add_order_note( sprintf( __('Transaction Failed. %s %s', 'woo-quickbooks-patsatech'), $statusCode, $statusMessage ) );
				}
						
			}else{
				if($this->woo_version >= 2.1){
					wc_add_notice( __('Gateway Error. Please Notify the Store Owner about this error.', 'woo-quickbooks-patsatech'), 'error' );
				}else if( $this->woo_version < 2.1 ){
					$woocommerce->add_error( __('Gateway Error. Please Notify the Store Owner about this error.', 'woo-quickbooks-patsatech') );
				}else{
					$woocommerce->add_error( __('Gateway Error. Please Notify the Store Owner about this error.', 'woo-quickbooks-patsatech') );
				}
				
				$order->add_order_note( __('Gateway Error. Please Notify the Store Owner about this error.', 'woo-quickbooks-patsatech') );
			} 	
			
		}
		
		/**
		 * Process a refund if supported
		 * @param  int $order_id
		 * @param  float $amount
		 * @param  string $reason
		 * @return  bool|wp_error True or false based on success, or a WP_Error object
		 */
		public function process_refund( $order_id, $amount = null, $reason = '' ) {
			$order = wc_get_order( $order_id );

			if ( ! $order || ! $order->get_transaction_id() ) {
				return false;
			}
			
			$app_login = $this->app_login;
			$con_ticket = $this->con_ticket;
			$app_id = $this->app_id;
			
			if ( $this->testmode == 'yes' ):
				$qbmsurl = $this->testurl;
			else :
				$qbmsurl = $this->liveurl;
			endif;
			
			if ( ! is_null( $amount ) ) {
				$transaction = explode('-',$order->get_transaction_id());
				
				$qbXML = new SimpleXMLElement('<?qbmsxml version="4.5"?><QBMSXML />');
				$signOnDesktop = $qbXML->addChild('SignonMsgsRq')->addChild('SignonDesktopRq');
				$signOnDesktop->addChild('ClientDateTime', date('Y-m-d\TH:i:s'));
				$signOnDesktop->addChild('ApplicationLogin', $app_login);
				$signOnDesktop->addChild('ConnectionTicket', $con_ticket);
				$signOnDesktop->addChild('Language', 'English');
				$signOnDesktop->addChild('AppID', $app_id);
				$signOnDesktop->addChild('AppVer', '1.0');
				
				//Normally Refund Credit Card
				$cardChargeRequest = $qbXML->addChild('QBMSXMLMsgsRq')->addChild('CustomerCreditCardTxnVoidOrRefundRq');
				$cardChargeRequest->addChild('TransRequestID', $transaction[1]);
				$cardChargeRequest->addChild('CreditCardTransID', $transaction[0]);
				$cardChargeRequest->addChild('Amount', number_format( $amount, 2, '.', '' ));
				$cardChargeRequest->addChild('ForceRefund', 'true');
			
				$xml = $qbXML->asXML();
				
			}
			$response = wp_remote_post( 
			    $qbmsurl, 
			    array(
			        'method' => 'POST',
			        'timeout' => 60,
			        'redirection' => 5,
			        'httpversion' => '1.0',
			        'headers' => array(
			            'Content-Type' => 'application/x-qbmsxml',
						'Content-Length' => strlen($xml)
			        ),
			        'body' => $xml,
			        'sslverify' => false
			    )
			);
			
			if ( is_wp_error( $response ) ) {
				return $response;
			}

			if ( empty( $response['body'] ) ) {
				return new WP_Error( 'woo-quickbooks-patsatech', __( 'Empty QBMS response.', 'woo-quickbooks-patsatech' ) );
			}
			
			$removeline = array("\n");
			$result = str_replace($removeline, '', $response['body']);
			
			try {
				$xml = new SimpleXMLElement($result);
			}
			catch (Exception $e) {
				return new WP_Error( 'woo-quickbooks-patsatech', $e->getMessage() );
			}
							
			$approved = false;
			if (isset($xml->SignonMsgsRs->SignonDesktopRs['statusSeverity']) && $xml->SignonMsgsRs->SignonDesktopRs['statusSeverity'] == 'ERROR') {
				$statusCode = (string)$xml->SignonMsgsRs->SignonDesktopRs['statusCode'];
				$statusMessage = (string)$xml->SignonMsgsRs->SignonDesktopRs['statusMessage'];
			} else if (isset($xml->QBMSXMLMsgsRs->CustomerCreditCardTxnVoidOrRefundRs['statusSeverity'])) {
				$statusCode = (string)$xml->QBMSXMLMsgsRs->CustomerCreditCardTxnVoidOrRefundRs['statusCode'];
				$statusMessage = (string)$xml->QBMSXMLMsgsRs->CustomerCreditCardTxnVoidOrRefundRs['statusMessage'];
				$TransID = (string)$xml->QBMSXMLMsgsRs->CustomerCreditCardTxnVoidOrRefundRs->CreditCardTransID;
				$clientTransID = (string)$xml->QBMSXMLMsgsRs->CustomerCreditCardTxnVoidOrRefundRs->ClientTransID;
				
				if ($statusCode == 0) {
					$approved = true;
				}
			} else {
				$statusCode = 'Unknown';
				$statusMessage = 'Unknown';
			}
					    		    
			if($approved){
				$order->add_order_note( sprintf( __( 'Refund %s - Refund ID: %s', 'woo-quickbooks-patsatech' ), $statusMessage, $TransID ) );
				return true;
			}else {
				
				$order->add_order_note( sprintf( __('Transaction Failed. %s %s', 'woo-quickbooks-patsatech'), $statusCode, $statusMessage ) );
				return true;
			}

			return false;
			
		}

		private function isCreditCardNumber($toCheck)
	    {
	        if (!is_numeric($toCheck))
	            return false;

	        $number = preg_replace('/[^0-9]+/', '', $toCheck);
	        $strlen = strlen($number);
	        $sum    = 0;

	        if ($strlen < 13)
	            return false;

	        for ($i=0; $i < $strlen; $i++)
	        {
	            $digit = substr($number, $strlen - $i - 1, 1);
	            if($i % 2 == 1)
	            {
	                $sub_total = $digit * 2;
	                if($sub_total > 9)
	                {
	                    $sub_total = 1 + ($sub_total - 10);
	                }
	            }
	            else
	            {
	                $sub_total = $digit;
	            }
	            $sum += $sub_total;
	        }

	        if ($sum > 0 AND $sum % 10 == 0)
	            return true;

	        return false;
	    }

		private function isCorrectExpireDate($month, $year)
	    {
	        $now       = time();
	        $result    = false;
	        $thisYear  = (int)date('y', $now);
	        $thisMonth = (int)date('m', $now);

	        if (is_numeric($year) && is_numeric($month))
	        {
	            if($thisYear == (int)$year)
		        {
		            $result = (int)$month >= $thisMonth;
		        }			
				else if($thisYear < (int)$year)
				{
					$result = true;
				}
	        }

	        return $result;
	    }
	    
		
		function get_woo_version() {
		    
			// If get_plugins() isn't available, require it
			if ( ! function_exists( 'get_plugins' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			
		    // Create the plugins folder and file variables
			$plugin_folder = get_plugins( '/woocommerce' );
			$plugin_file = 'woocommerce.php';
			
			// If the plugin version number is set, return it 
			if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
				return $plugin_folder[$plugin_file]['Version'];
		
			} else {
				// Otherwise return null
				return NULL;
			}
		}
		
		private function force_ssl($url){
			
			if ( 'yes' == get_option( 'woocommerce_force_ssl_checkout' ) ) {
				$url = str_replace( 'http:', 'https:', $url );
			}
			
			return $url;
		}

	}

	/**
	 * Add the gateway to WooCommerce
	 **/
	function add_qbms_gateway( $methods ) {
		$methods[] = 'woocommerce_qbms'; return $methods;
	}

	add_filter('woocommerce_payment_gateways', 'add_qbms_gateway' );

}