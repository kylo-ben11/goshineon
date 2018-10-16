<?php
/**
 * snapCX Shipping Tracking
 * @package UC
 * @author  snapcx Developer <ajain@jframeworks.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UC_BACKEND{

	/**
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';
	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
	
	/**
	 * Unique identifier for your plugin.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public $plugin_slug = 'uc-tracking-configuration';

    /**
     * @var UberCXTrackingAccountVerifier
     */
    protected $account_verifier;

	/**
	 * Initialize the plugin by setting localization, filters.
	 *
	 * @since     1.0.0
	 */
	function __construct() {
        $this->account_verifier = new UberCXTrackingAccountVerifier();

		// Database variables
		global $wpdb;
		$this->db 					= &$wpdb;
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_styles_n_js') );
		//Adds menu
//		add_action( 'admin_menu', array( &$this, 'uc_admin_menu'), 12 );
		//uc register settings
	    add_action( 'admin_init', array( &$this, 'uc_register_settings' ) );

        add_filter('woocommerce_settings_tabs_array', array($this, 'add_woo_settings_tab'), 51);
        add_action('woocommerce_settings_tabs_settings_tab_snapcx_shippingtracking', array($this, 'add_woo_settings_tab_options'));
        add_action('woocommerce_update_options_settings_tab_snapcx_shippingtracking', array($this, 'update_woo_settings_tab_options'));

	    //adds meta box in order overview page
		add_action('add_meta_boxes', array( &$this, 'uc_add_order_tracking_meta_box') );
		// Email is sent with tracking number on changing Order Actions to processing and complete order 
		add_action('woocommerce_email_before_order_table', array( &$this, 'add_content'),10, 4  );
		//save tracking values
		add_action( 'woocommerce_process_shop_order_meta', array(&$this, 'save_tracking_details_for_orders') );
		//Simon - Aug 2015
		//Add the hook to show the shipped icon
		add_action( 'manage_shop_order_posts_custom_column', array( $this, 'show_shipped_icon' ) );
		
		// Function to handel ajax request.
		add_action('wp_ajax_uc_update_carriers', array(&$this, 'uc_update_carriers'));
		add_action('wp_ajax_nopriv_uc_update_carriers', array(&$this, 'uc_update_carriers'));
		
       // Function to handle ajax request.
		add_action('wp_ajax_uc_update_order', array(&$this, 'uc_update_order'));
		add_action('wp_ajax_nopriv_uc_update_carriers', array(&$this, 'uc_update_order'));
		
		add_filter( 'plugin_action_links_'.plugin_basename( plugin_dir_path( __FILE__ ) . 'woocommerce-shipping-tracking.php'), array( &$this, 'plugin_add_settings_link' ) );
	}
	
	public function plugin_add_settings_link( $links ) {
	//http://woocommerce.snapcx.io/wp-admin/admin.php?page=uc-tracking-configuration
  $settings_link = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=wc-settings&tab=settings_tab_snapcx_shippingtracking') ) .'">Settings</a>';
	array_unshift( $links, $settings_link ); 
  return $links; 
}

    /**
     * Add a new plugin's settings tab to Woocommerce settings page
     *
     * @param $settings_tabs
     * @return mixed
     */
    public function add_woo_settings_tab($settings_tabs) {
        $settings_tabs['settings_tab_snapcx_shippingtracking'] = __( 'SnapCX Order Tracking', 'ubercx-shipping-tracking' );
        return $settings_tabs;
    }

    /**
     * Add options to Plugin's settings tap
     */
    public function add_woo_settings_tab_options() {
        woocommerce_admin_fields($this->get_woo_settings_tab_options());
    }

    /**
     * Function to add plugin settings link to Plugins page
     *
     */
    public function get_woo_settings_tab_options() {
        $settings = array(
            'section_title' => array(
                'name'     => __( 'Order Tracking Settings', 'ubercx-shipping-tracking' ),
                'type'     => 'title',
                'desc'     => '<p><i>Enter your snapCX User Key here. If you do not have one, <a target="_blank" href="https://snapcx.io/pricing.jsp?solution=tracking&utm_source=wordpress&utm_medium=plugin&utm_campaign=tracking">Sign up for a FREE TRIAL Subscription</a> NO Credit Card required</i></p>',
                'id'       => 'uc_settings[section_title]'
            ),
            'user_key' => array(
                'name' => __( 'User Key', 'ubercx-shipping-tracking' ),
                'type' => 'text',
                'desc' => __( '<span><a target="_blank" href="https://snapcx.io/pricing.jsp?solution=tracking&utm_source=wordpress&utm_medium=plugin&utm_campaign=tracking">Get your User Key (Get TRIAL Subscription)</a></span>', 'ubercx-shipping-tracking' ),
                'id'   => 'uc_settings[user_key]'
            ),
            'default_carrier' => array(
                'name' => __( 'Default Carrier', 'ubercx-shipping-tracking' ),
                'type' => 'select',
                'desc' => __( $this->get_update_carriers_button_html(), 'ubercx-shipping-tracking' ),
                'id'   => 'uc_settings[default_carrier]',
                'options' => $this->get_carrier_list()
            ),
            'enable' => array(
                'name' => __( 'Enable/Disable', 'ubercx-shipping-tracking' ),
                'type' => 'checkbox',
                'desc' => __( '', 'ubercx-shipping-tracking' ),
                'id'   => 'uc_settings[enable]'
            ),
            'order_text' => array(
                'name' => __( 'Order Page Text', 'ubercx-shipping-tracking' ),
                'type' => 'text',
                'desc' => __( '<br>Enter the text that will appear on the order page, use the shortcodes [carrier] and [tracking_id] as placeholders', 'ubercx-shipping-tracking' ),
                'id'   => 'uc_settings[order_text]',
                'default' => 'Your order has been shipped by [carrier]. The tracking number is: [tracking_id]',
                'css' => 'width:59%;'
            ),
            'tracking_url' => array(
                'name' => __( 'Tracking URL', 'ubercx-shipping-tracking' ),
                'type' => 'text',
                'desc' => __( 'Please enter URL in format of <b>http://track.yourdomain.com</b><br>Default URL is <b>http://track.snapcx.io/</b>', 'ubercx-shipping-tracking' ),
                'id'   => 'uc_settings[tracking_url]',
                'default' => 'http://track.snapcx.io/'
            ),
            'email_text' => array(
                'name' => __( 'Email Text', 'ubercx-shipping-tracking' ),
                'type' => 'text',
                'desc' => __( '<br>Enter the text that will appear on the order page, use the shortcodes [carrier] and [tracking_id] as placeholders', 'ubercx-shipping-tracking' ),
                'id'   => 'uc_settings[email_text]',
                'default' => 'Your order has been shipped by [carrier]. The tracking number is: [tracking_id] ',
                'css' => 'width:59%;'
            ),
            'sectionend' => array(
                'type' => 'sectionend',
                'id' => 'uc_settings[section_end]'
            ),
            'section_link_bottom' => array(
                'name'     => __( '', 'ubercx-shipping-tracking' ),
                'type'     => 'title',
                'desc' => 'If you like this plugin please leave us a <a href="https://wordpress.org/support/plugin/ubercx-shipping-tracking/reviews?rate=5#new-post" target="_blank" class="wc-rating-link" data-rated="Thanks :)">★★★★★</a> rating. Many thanks in advance!',
                'id'       => 'uc_settings[section_link_bottom]'
            ),
        );

        return apply_filters( 'woocommerce_settings_tab_snapcx_shippingtracking_settings', $settings );
    }

    public function update_woo_settings_tab_options() {
        if($this->validateUserKey()) {
            woocommerce_update_options( $this->get_woo_settings_tab_options() );
        }

        return;
    }

    public function get_carrier_list() {
        $carriers = array();
        $allCarriers = get_option('uc_all_carriers');

        foreach ($allCarriers as $carrier) {
            $carriers[$carrier->carrierCode] = $carrier->carrierName;
        }

        return $carriers;
    }

    public function get_update_carriers_button_html() {

        $html = '';
        $html .= '<button type="button" style="margin-left:5px;" class="button-primary uc_update_carriers">' . __( 'Update Carrier List', 'ubercx-shipping-tracking' ) . '</button>';
        $html .= '<div id="uc-ajax-spinner" style="width: 30px; height: 30px; margin-left: 10px; position: absolute;">';
        $html .= '<img src="'. plugins_url("ubercx-shipping-tracking/assets/ajax-loader-green.gif") .'" style="max-width:100%;"></div>';


        $jsCode = "
jQuery( '#uc-ajax-spinner' ).hide();
jQuery( 'document' ).ready(function(){
	jQuery( '.uc_update_carriers' ).on( 'click', function(){
		jQuery( '#uc-ajax-spinner' ).css({display: 'inline-block', position: 'relative', top: '10px'});
		
		jQuery.post( ajaxurl, { 'action': 'uc_update_carriers' }, function( response ){
			console.log( response );
			var response = JSON.parse( response );
			jQuery( '.uc_default_carrier' ).html( response.carrier_list );
			jQuery( '#uc-ajax-spinner' ).hide();
			jQuery( '.update_msg' ).show();
			setTimeout(function(){
				jQuery( '.update_msg' ).fadeOut();
			}, 2000);
		});
	});
});
        ";

        wc_enqueue_js($jsCode);

        return $html;

    }
	/**

	 * Function to send email on processing and completed action
	 * 
	 * @since 1.0.0
	 */
	function add_content($post) {
	   
	    
		if (isset($_REQUEST['wc_order_action']) || isset($_REQUEST['order_status'])) { 
		  $request = $_REQUEST['wc_order_action'];
		  $request_status =  $_REQUEST['order_status'];
		  $order     = wc_get_order( $post->id );
		  $processing = $post->post_status;
	
			if($request =='send_email_customer_completed_order' || $request_status == 'wc-completed'){
				$carrier_code = get_post_meta($post->id, '_ubercx_carrier_name', true);
					if($carrier_code !='')
					{
							$track_id = get_post_meta($post->id, '_ubercx_tracking_number', true);
							$tracking_url = "http://track.snapcx.io/";
							if(isset($opt['tracking_url'])){
							    $tracking_url = $opt['tracking_url'];
							} 
						$tracking_url = '<a href="'.$tracking_url.'?carrier_code='.$carrier_code.'&track_id='.$track_id.'" target="_blank">'.$track_id.' </a>';
						echo $msg = 'The order is completed via ' . $carrier_code . ' with tracking number: ' . $tracking_url;
					}
		 	}
		 	else if($request =='send_email_customer_processing_order' ||  $request_status == 'wc-processing'){
			 		$carrier_code = get_post_meta($post->id, '_ubercx_carrier_name', true);
					if($carrier_code !='')
					{
							$track_id = get_post_meta($post->id, '_ubercx_tracking_number', true);
							$tracking_url = "http://track.snapcx.io/";
							if(isset($opt['tracking_url'])){
							    $tracking_url = $opt['tracking_url'];
							} 
						$tracking_url = '<a href="'.$tracking_url.'?carrier_code='.$carrier_code.'&track_id='.$track_id.'" target="_blank">'.$track_id.' </a>';
						echo $msg = 'The order is processing via ' . $carrier_code . ' with tracking number: ' . $tracking_url;
					}
		 	}
		}
	}
	
	/**
	 * Function to register activation actions
	 * 
	 * @since 1.0.0
	 */
	function uc_plugin_activate(){
			
		//Check for WooCommerce Installment
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) and current_user_can( 'activate_plugins' ) ) {
			// Stop activation redirect and show error
			wp_die('Sorry, but this plugin requires the Woocommerce to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
			
			
		}
		// Get all cariers from API and store in DB.
		$this->uc_store_all_carriers();
		update_option('uc_plugin_activate', true);
	}	
 	/**
	 * Function to register deactivation actions
	 * 
	 * @since 1.0.0
	 */
	function uc_plugin_deactivate_plugin(){ 
		delete_option('uc_plugin_activate');
		delete_option('uc_settings');
	}
	
	/**
	 * Function to register the plugin settings options
	 * 
	 * @since 1.0.0
	 */
	public function uc_register_settings() {
		register_setting('uc_register_settings', 'uc_settings' );
	}	
	
	public function admin_enqueue_styles_n_js($hook){
		wp_enqueue_style( 'uc_fc_css', plugin_dir_url( __FILE__ ) . 'assets/css/jquery.fancybox.css' );
		wp_enqueue_script( 'uc_fc_script', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.fancybox.pack.js' );

        if(isset($hook) && $hook === 'woocommerce_page_wc-settings') {
            if(isset($_GET['tab']) && wp_strip_all_tags($_GET['tab']) === 'settings_tab_snapcx_shippingtracking') {
                wp_enqueue_script( 'ubercx-addr-settings-js',  plugins_url( 'assets/js/settings.js', __FILE__ ), 'jquery' );
            }
        }
	}
	
	/**
	 * Function to get end-point of API
	 * 
	 * @since 1.0.0
	 */
	function uc_getApiUrl(){
		if(file_exists(plugin_dir_path( __FILE__ ).'config.txt')){
			$response = file_get_contents(plugin_dir_path( __FILE__ ).'config.txt');
			$response = json_decode($response);
			if(!empty($response)){
				return $response->api_endpoint;
			}
		} 
	}
	
	/**
	 * Function to get end-point of Carrier API
	 * 
	 * @since 1.5.0
	 */
	function uc_getCarrierApiUrl(){
		if(file_exists(plugin_dir_path( __FILE__ ).'config.txt')){
			$response = file_get_contents(plugin_dir_path( __FILE__ ).'config.txt');
			$response = json_decode($response);
			if(!empty($response)){
				return $response->carrier_api;
			}
		} 
	}
	
	/**
	 * Function to store carriers from snapcx.io and store in DB.
	 * 
	 * @since 1.5.0
	 */
	function uc_store_all_carriers( $ajax = '' ) {
		
		$api_url  = $this->uc_getCarrierApiUrl();
		global $woocommerce,$UCVersion;  
		// Start cURL
		$curl = curl_init();
		// Headers
		$api_headers = array('platform: woocommerce','version:'.$woocommerce->version,'pVersion:'.$UCVersion);
		curl_setopt( $curl, CURLOPT_URL, $api_url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $api_headers );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $curl, CURLOPT_HEADER, false);
		// Get response
		$response = curl_exec($curl);
		// Get HTTP status code
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		// Close cURL
		curl_close($curl);
		// Return response from server
		
		if($response!=''){
			$response = json_decode($response);	
			update_option( 'uc_all_carriers', $response );
			if( $ajax == true )
				return $response;
		}
	}
	/**
	 * Function to update carriers from snapcx.io and store in DB.
	 * 
	 * @since 1.5.0
	 */
	function uc_update_carriers() {
	    $uc_options = get_option('uc_settings');
		$carrier_list = $this->uc_store_all_carriers( $ajax = true );
		
		$list = '<option value="">-Please Select Default Carrier-</option>';
		foreach( $carrier_list as $carrier ) {
			
			if( isset( $uc_options['default_carrier'] ) )
				$selected =  ( $carrier->carrierCode == $uc_options['default_carrier'] ) ? 'selected="selected"' : '';
			else
				$selected = '';
			
			$list .= '<option value="'.$carrier->carrierCode.'" '.$selected.'>'.$carrier->carrierName.'</option>';
		}
		echo json_encode( array( 'carrier_list' => $list ) );
		exit;
	}
	
	 /**
	 * Function to update order in DB.
	 * 
	 * @since 2.0.3
	 */
	function uc_update_order() {
     
	 if (!empty($_REQUEST['track_id']) && !empty(['carrier_code'] )) {
			update_post_meta( $_REQUEST['order_id'], '_ubercx_carrier_name', wc_clean( $_REQUEST['carrier_code'] ) );
			update_post_meta( $_REQUEST['order_id'], '_ubercx_tracking_number', wc_clean( $_REQUEST['track_id'] ) );
			$this->send_tracking_details_to_user( $_REQUEST['order_id'] );
		
		    $msg ='<h3>Your order has been updated with tracking '.$_REQUEST['track_id'].'<h3>';
       
	    }
		 echo $msg;	
		exit;
	}
	
	/**
	 * Function to get userkey
	 * 
	 * @since 1.0.0
	 */
	public function uc_getUserKey(){
		$sq_options = get_option('uc_settings');
		$user_key = $sq_options['user_key'];
		return $user_key;
	}
	
	/**
	 * Function to get userkey
	 * 
	 * @since 1.0.0
	 */
	public function uc_getDefaultCarrier(){
		$sq_options = get_option('uc_settings');
		$default_carrier = $sq_options['default_carrier'];
		return $default_carrier;
	}

	/**
	 * Function to check if plugin is enabled
	 * 
	 * @since 1.0.0
	 */
     public function uc_isEnabled(){
		$sq_options = get_option('uc_settings');
		$enable = $sq_options['enable'];

		if($enable === 'Yes' || $enable === 'yes') {
		    return true;
        }

		return false;
	}	
	
	/**
	 * Function to add order tracking details to the order overview page
	 * 
	 * @since 1.0.0
	 */
	function uc_add_order_tracking_meta_box(){
		
		$isEnable =  $this->uc_isEnabled();
		if( $isEnable ){
		  add_meta_box('woocommerce-ubercx', 'snapCX Tracking Information', array(&$this, 'uc_meta_box_view'), 'shop_order', 'side', 'high');
		}
	}
	
	/**
	 * Function to display order tracking form on order overview page
	 * 
	 * @since 1.0.0
	 */
	function uc_meta_box_view(){
		
		global $post, $wpdb;
		
		// Get all carriers from DB.
		$carrier_list = get_option( 'uc_all_carriers' );
		
		$carrier_name = get_post_meta($post->ID, '_ubercx_carrier_name', true);
		$carrier_name = (!empty($carrier_name)) ? $carrier_name : $this->uc_getDefaultCarrier();
		echo '<p class="form-field">';
		echo '<label for="ubercx_carrier">Carrier:</label><br />';
		echo '<select id="ubercx_carrier" name="ubercx_carrier">';
		echo '<option value="">-Please Select Carrier-</option>';
		foreach( $carrier_list as $carrier ) {
			$selected =  ( $carrier->carrierCode == $carrier_name ) ? 'selected="selected"' : '';
			echo '<option value="'.$carrier->carrierCode.'" '.$selected.'>'.$carrier->carrierName.'</option>';
		}
		echo '</select>';
		
		woocommerce_wp_text_input(array(
			'id' => 'ubercx_tracking_number',
			'label' => 'Tracking Number',
			'placeholder' => 'Tracking Number',
			'description' => 'Tracking Number',
			'class' => '',
			'value' => get_post_meta($post->ID, '_ubercx_tracking_number', true),
		));
		
		$this->uc_admin_tracking_display( $post->ID );
	}
	
	/**
	 * Function to save tracking details
	 * 
	 * @since 1.0.0
	 */
	function save_tracking_details_for_orders( $post_id ){
		
		if ( isset( $_POST['ubercx_tracking_number'] ) ) {
			update_post_meta( $post_id, '_ubercx_carrier_name', wc_clean( $_POST['ubercx_carrier'] ) );
			update_post_meta( $post_id, '_ubercx_tracking_number', wc_clean( $_POST['ubercx_tracking_number'] ) );
			$this->send_tracking_details_to_user( $post_id );
		}
	}
	
	/**
	 * Function to display shipping tracking details tracking button.
	 *
	 * @since     1.0.0
	 */
	 function uc_admin_tracking_display( $order_id ){

		$isEnable =  $this->uc_isEnabled();
		if( $isEnable ){
			$carrier_code = get_post_meta($order_id, '_ubercx_carrier_name', true);
			$carrier_code = (!empty($carrier_code)) ? $carrier_code : $this->uc_getDefaultCarrier();
			
			$track_id = get_post_meta($order_id, '_ubercx_tracking_number', true);
			
			//Simon get the text to add to the screen
			$opt = get_option('uc_settings');
		
				
			//if($carrier_code != '' &&  $track_id != ''){
				$onclick = "window.open(this.href, '','width=800,height=600,resizable=yes,scrollbars=yes'); return false;";
				
				$url =  admin_url( 'admin-ajax.php' ).'?ajax=true&carrier_code='.$carrier_code.'&track_id='.$track_id.'&order='.$order_id.'&action=uc_get_tracking_details&admin=true';

	echo '<a id="update-order-button" class="button view" href="#" style="margin-left: 5px;font-size: 12px;padding:0 7px 1px;">Update Order and send email.</a>';
	echo '<br/>';	
	echo '<a id="uc-track-button" class="button view" href="#"  style="font-size: 12px;padding:0 7px 1px;">Show Shipment Tracking</a>';
				$inline = '
					<div id="uc-ajax-spinner" style="display: none;">
						<img src="' . plugins_url("assets/ajax-loader.gif", __FILE__) . '">
					</div>
					<div id="uc-inline-tracker" style="display: none;">
					</div>
					<script>
					jQuery(document).ready(function(){
					    jQuery("#uc-track-button").click(function(){
						var trackDisplay = jQuery("#uc-inline-tracker").css("display");
                         //alert("display is "+trackDisplay);
						if (trackDisplay == "hidden" || trackDisplay == "none") {
						 //call the function!
						  var carrier = jQuery("#ubercx_carrier").val();
						  var track_id = jQuery("#ubercx_tracking_number").val();
						  var order_id="'.$order_id.'";
						  var url ;
						  if(carrier!="" && carrier!=""){
						   url = "'.admin_url( 'admin-ajax.php' ).'?ajax=true&carrier_code="+carrier+"&track_id="+track_id+"&order_id="+order_id+"&action=uc_get_tracking_details&admin=true";
						    }else{
							url = "'.$url.'";
							}
						  jQuery.ajax({
						 url: url ,
	
							success: function(result){
								jQuery("#uc-ajax-spinner").hide();
								jQuery.fancybox(result);
							},
							error: function(error){
								jQuery("#uc-ajax-spinner").hide();
								alert("error" + error);
							}
		
						});
						
						} else {
                          jQuery("#uc-track-button").text("Show Shipment Tracking");   
						  jQuery("#uc-inline-tracker").css("display", "none");
						}
					    });
						
						jQuery("#update-order-button").click(function(){
						 //call the function!
						  var carrier = jQuery("#ubercx_carrier").val();
						  var track_id = jQuery("#ubercx_tracking_number").val();
						  var order_id="'.$order_id.'";
						  if(carrier==""){	
						   jQuery("#ubercx_carrier").css("border","1px solid #a00");
						   return false;
						  }
						  else if(track_id==""){
						   jQuery("#ubercx_tracking_number").css("border","1px solid #a00");
						   return false;
						  }
						   else{
						   jQuery("#ubercx_carrier").css("border","1px solid #ccc");
						  jQuery("#ubercx_tracking_number").css("border","1px solid #ccc");
						 
						 jQuery.ajax({
						 url: "'.admin_url( 'admin-ajax.php' ).'?ajax=true&carrier_code="+carrier+"&track_id="+track_id+"&order_id="+order_id+"&action=uc_update_order&admin=true",
	
							success: function(result){
								jQuery("#uc-ajax-spinner").hide();
								jQuery.fancybox(result);
							},
							error: function(error){
								jQuery("#uc-ajax-spinner").hide();
								alert("error" + error);
							}
		
						});
						 
						  } 
					    });
						
					});
				
	
					</script>
				';
				
				echo $inline;
			//}
		}
	 }
	
	//Simon Aug 2015
	//Show the shipped icon on the order summary page
	function show_shipped_icon( $col ){
		if ( $col != 'order_status'  ) {
			return;
		}
		
		global $the_order;
		

		//if we don't have a shipping number for this order just return
		$carrier_code = get_post_meta($the_order->id, '_ubercx_carrier_name', true);
		$carrier_code = (!empty($carrier_code)) ? $carrier_code : $this->uc_getDefaultCarrier();
		
		$track_id = get_post_meta($the_order->id, '_ubercx_tracking_number', true);
		
		
		//if no tracking number return
		if($track_id == '' || $carrier_code == ''){
			return;
		}

		//create the tooltip message
		//TODO - need to add localization
		$msg = 'The order shipped via: ' . $carrier_code . ' with tracking number: ' . $track_id;
		//ok so lets show it!
		echo '<a class="tips" style="display:inline-block;height:30px; padding-top:0; padding-bottom:0" href="#" data-tip="' . $msg . '">';
		echo '<img  style="height:30px;" src="' . plugins_url("assets/shipped.png", __FILE__) . '" data-tip="dsfdsfdf" /></a>';
	}
	
	/**
	 * Send Tracking Details to user.
	 *
	 * @since     1.0.0
	 */
	public function send_tracking_details_to_user( $order_id ) {
		$isEnable =  $this->uc_isEnabled();
		if( $isEnable ){
			$customer_user = get_post_meta($order_id, '_customer_user', true);
			$carrier_code = get_post_meta($order_id, '_ubercx_carrier_name', true);
			//$carrier_email = get_post_meta($order_id, '_ubercx_tracking_email', true);
			$carrier_code = (!empty($carrier_code)) ? $carrier_code : $this->uc_getDefaultCarrier();
					 
			$track_id = get_post_meta($order_id, '_ubercx_tracking_number', true);
			
			if($track_id != '')
			{
				//Simon get the text to add to the screen
				$opt = get_option('uc_settings');
				$txt = '';
				if(isset($opt['email_text'])){
					$txt = $opt['email_text'];
				}
			
				$tracking_url = "http://track.snapcx.io/";
				if(isset($opt['tracking_url'])){
					$tracking_url = $opt['tracking_url'];
				} 
				
				$tracking_url = '<a href="'.$tracking_url.'?carrier_code='.$carrier_code.'&track_id='.$track_id.'" target="_blank">'.$track_id.' </a>';
				//now do the replacement
				$txt = str_replace('[carrier]', $carrier_code, $txt);
				$txt = str_replace('[tracking_id]', $tracking_url, $txt);
				$author_obj = get_user_by( 'id', $customer_user );
				$wc_emails = WC_Emails::instance();
				$order     = wc_get_order( $order_id );
		
				ob_start();
				$wc_emails->email_header( 'Order Tracking Detail' );
			?><p><?php _e( $txt ); ?></p>
			<?php 
				$wc_emails->order_details( $order );
				$wc_emails->order_schema_markup( $order ); 
				$wc_emails->order_meta( $order );
				$wc_emails->customer_details( $order );
				$wc_emails->email_addresses( $order );
				$wc_emails->email_footer();
				$message = ob_get_clean();
					
				$to = $order->billing_email; // Get user email id 
					//$sendtime = date('F jS, Y') ;
				$subject = 'Your '. get_bloginfo() .' order# '.$order_id.' is shipped';
				$body = $message;
			
			  $wc_emails->send( $to, $subject, $message, $headers = "Content-Type: text/html\r\n", $attachments = "" );			
			} 
		}
	}
	
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

    /**
     * Validate user_key via API endpoint.
     *
     * @return bool
     * @throws Exception
     */
    protected function validateUserKey() {
        WC_Admin_Notices::remove_all_notices();
        
        if (!isset($_POST['uc_settings']['user_key'])) {
            $this->showInvalidUserKeyNotice("User key cannot be empty.");
            return false;
        }

        // Do not bother API in case if user_key wasn't changed.
//        $currentOptions = get_option('uc_settings');
//        if ($_POST['uc_settings']['user_key'] == $currentOptions['user_key']) {
//            return true;
//        }

        $user_key = wp_strip_all_tags($_POST['uc_settings']['user_key']);
        $validateResult = $this->account_verifier->validateUserKey($user_key);

        if(isset($validateResult['is_valid']) && $validateResult['is_valid'] === true) {
            if (isset($validateResult['show_notice']) && isset($validateResult['message'])) {
                $this->showUserKeyNotice($validateResult['message']);
            } else {
                $this->showSuccessMessage("Your User Key is validated.");
            }
            return true;
        }

        if(isset($validateResult['message'])) {
            $this->showInvalidUserKeyNotice($validateResult['message']);
            return false;
        }

        $this->showInvalidUserKeyNotice("Invalid User Key.");
        return false;
    }

    protected function showInvalidUserKeyNotice($text = '') {
        WC_Admin_Settings::add_error(__($text, 'ubercx-shipping-tracking'));
    }

    protected function showUserKeyNotice($text = '') {
        WC_Admin_Notices::add_custom_notice('ubercx_shipping_tracking_userkey_notice',__($text, 'ubercx-shipping-tracking'));
    }

    protected function showSuccessMessage($text = '') {
        WC_Admin_Settings::add_message(__($text, 'ubercx-shipping-tracking'));
    }
}