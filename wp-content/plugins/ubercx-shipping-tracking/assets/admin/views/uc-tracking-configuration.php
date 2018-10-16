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

//TODO we need to make all the static text be translatable

if(!isset($uc_options['order_text']) || $uc_options['order_text'] == ''){
	$uc_options['order_text'] = 'Your orders has been shipped by [carrier]. The tracking number is: [tracking_id] ';
}

if(!isset($uc_options['email_text']) || $uc_options['email_text'] == ''){
	$uc_options['email_text'] = 'Your orders has been shipped by [carrier]. The tracking number is: [tracking_id] ';
}

if(!isset($uc_options['tracking_url']) || $uc_options['tracking_url'] == ''){
  $uc_options['tracking_url'] = 'http://track.snapcx.io/';
}

if(!isset($uc_options['user_key'])){
	$uc_options['user_key'] = '';
}

if(!isset($uc_options['enable'])){
	$uc_options['enable'] = 'Yes';
}

?>


<div class="wrap">
	<div id="fsb-wrap" class="fsb-help">
		<h2>snapCX Shipping Tracking Settings</h2>
		  <?php
		  if ( ! isset( $_REQUEST['updated'] ) )
			  $_REQUEST['updated'] = false;
		 ?>
		  <?php if ( false !== $_REQUEST['updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
		<?php endif; ?>
		<form method="post" action="options.php">
			<?php settings_fields( 'uc_register_settings' ); ?>
	
			<p><strong>User Key</strong></p>
			<p><i>Enter your snapCX User Key here. If you do not have one, <a href="https://snapcx.io/pricing.jsp?solution=tracking&utm_source=wordpress&utm_medium=plugin&utm_campaign=tracking">Sign up for a FREE TRIAL Subscription</a> NO Credit Card required</i></p>
					<p><input  style="width:59%;" id="uc_settings[user_key]" name="uc_settings[user_key]" type="text" value="<?php echo $uc_options['user_key']; ?>" required/>
				<span><a target="_blank" href="https://snapcx.io/pricing.jsp?solution=tracking&utm_source=wordpress&utm_medium=plugin&utm_campaign=tracking">Get your User Key (Get TRIAL Subscription)</a></span></p>
			<?php 
				// Get all carriers from DB.
				$carrier_list = get_option( 'uc_all_carriers' );
			?>
			<p><strong>Set Default Carrier</strong></p>
			<div style="width:100%; display:inline-block;"><select id="uc_settings[default_carrier]" name="uc_settings[default_carrier]" class="uc_default_carrier" style="float:left;" >
					<?php echo '<option value="">-Please Select Default Carrier-</option>';
					foreach( $carrier_list as $carrier ) {
						
						if( isset( $uc_options['default_carrier'] ) )
							$selected =  ( $carrier->carrierCode == $uc_options['default_carrier'] ) ? 'selected="selected"' : '';
						else
							$selected = '';
						
						echo '<option value="'.$carrier->carrierCode.'" '.$selected.'>'.$carrier->carrierName.'</option>';
					}
					?>
				</select>
				<input type="button" style="float:left; margin-left:5px;" class="button-primary uc_update_carriers" value="<?php _e( 'Update Carrier List' ); ?>" />
				<div id="uc-ajax-spinner" style="display: none; float:left;  width:30px; height:30px; margin-left:10px;">
					<img src="<?php echo plugins_url("ubercx-shipping-tracking/assets/ajax-loader-green.gif"); ?> " style="max-width:100%;">
				</div>
			</div>	
			<p style="margin-top:0px; color:green;"><span class="update_msg" style="display:none">Carrier list updated successfully.</span></p>
			<p><strong>Enabled: </strong></p>			
			<p><select name="uc_settings[enable]" style="width:59%;" id="uc_carriers" >
			  <option value="Yes" <?php if($uc_options['enable'] == 'Yes') echo 'selected="selected"'; ?>>Yes</option>
			  <option value="No" <?php if($uc_options['enable'] == 'No') echo 'selected="selected"'; ?>>No</option>
				 </select>
				
				</p>
			<p><strong>Order Page Text </strong><br><i>Enter the text that will appear on the order page, use the shortcodes [carrier] and [tracking_id] as placeholders
			</i></p>
			<p><input  style="width:59%;" id="uc_settings[order_text]" name="uc_settings[order_text]" type="text" value="<?php echo $uc_options['order_text']; ?>" required/>
			</p>
			
			<p>
				<strong>Tracking URL </strong>
				<br><i>Default URL is http://track.snapcx.io/</i>
				<br>If update this field, please enter only domain page. CNAME is fixed 'track'
			</p>
			<p>http://track.<input size="36" id="uc_settings[tracking_url]" name="uc_settings[tracking_url]" type="text" value="<?php echo $uc_options['tracking_url']; ?>" required/>/
			</p>
			
			<p><strong>Email Text </strong><br><i>Enter the text that will appear on the order page, use the shortcodes [carrier] and [tracking_id] as placeholders
			</i></p>
			<p><input  style="width:59%;" id="uc_settings[email_text]" name="uc_settings[email_text]" type="text" value="<?php echo $uc_options['email_text']; ?>" required/>
			</p>
			
			<!-- save the options -->
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ); ?>" />
			</p>
		</form>
	  <p>For troubleshooting your account, <a target="_blank" href="https://snapcx.io/shippingTrackingAPI">Please visit here</a>. You will need your User key to test out.</p>
	</div><!--end fsb-wrap-->
</div><!--end wrap-->
<script>
jQuery( 'document' ).ready(function(){
	jQuery( '.uc_update_carriers' ).on( 'click', function(){
		jQuery( '#uc-ajax-spinner' ).show();
		
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
</script>