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
	 * Initialize the plugin by setting localization, filters.
	 *
	 * @since     1.0.0
	 */
	function __construct() {
		
		// Database variables
		global $wpdb;
		$this->db 					= &$wpdb;
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_styles_n_js') );
		//Adds menu
		add_action( 'admin_menu', array( &$this, 'uc_admin_menu'), 12 );
		//uc register settings
	    add_action( 'admin_init', array( &$this, 'uc_register_settings' ) );	
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
  $settings_link = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=uc-tracking-configuration') ) .'">Settings</a>';
	array_unshift( $links, $settings_link ); 
  return $links; 
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
							if(isset($opt['tracking_domain_url'])){
							$tracking_url = "http://track." . $opt['tracking_domain_url'] . "/";
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
							if(isset($opt['tracking_domain_url'])){
							$tracking_url = "http://track." . $opt['tracking_domain_url'] . "/";
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
	 * Function to add admin menu
	 * 
	 * @since 1.0.0
	 */
	function uc_admin_menu() {
		
		$admin_role = 'manage_options';
		add_submenu_page( 'woocommerce', 'snapCX Shipping Tracking' ,  'snapCX Shipping Tracking', $admin_role, $this->plugin_slug, array( $this, 'uc_main_admin_page' )); 
	}
	
	/**
	 * Function to show main admin setting page
	 * 
	 * @since 1.0.0
	 */
	function uc_main_admin_page() {
		$uc_options = get_option('uc_settings');
		include('assets/admin/views/uc-tracking-configuration.php');
	}
	
	
	/**
	 * Function to register the plugin settings options
	 * 
	 * @since 1.0.0
	 */
	public function uc_register_settings() {
		register_setting('uc_register_settings', 'uc_settings' );
	}	
	
	public function admin_enqueue_styles_n_js(){
		wp_enqueue_style( 'uc_fc_css', plugin_dir_url( __FILE__ ) . 'assets/css/jquery.fancybox.css' );
		wp_enqueue_script( 'uc_fc_script', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.fancybox.pack.js' );
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
		return $enable;
	}	
	
	/**
	 * Function to add order tracking details to the order overview page
	 * 
	 * @since 1.0.0
	 */
	function uc_add_order_tracking_meta_box(){
		
		$isEnable =  $this->uc_isEnabled();
		if( $isEnable == 'Yes' ){
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
		if( $isEnable == 'Yes' ){
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
		if( $isEnable == 'Yes' ){
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
				if(isset($opt['tracking_domain_url'])){
					$tracking_url = "http://track." . $opt['tracking_domain_url'] . "/";
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

	
}