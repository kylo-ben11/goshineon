=== WooCommerce Order Shipping Tracking ===
Contributors: snapcx, ubercx
Tags: woocommerce, order tracking, shipping tracking, order track, delivery, carriers, shipping, ship orders, e-commerce, send product, product shipping, delivery notes, track carriers, track email, delivery note, order shipping, order, orders, shop, e commerce, shipping tracking, track shipping
Requires at least: 4.0
Tested up to: 4.9.7
WC requires at least: 3.2.0
WC tested up to: 3.4.4
Stable tag: 2.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
FREE WooCommerce plugin providing embedded shipping tracking for your customers & send order update emails. Increase customer satisfaction.

== Description ==

Shipping Tracking is FREE WooCommerce plugin that allows customers to track shipment of their orders. It support UPS, USPS, FEDEX, DHL, Canada Post and almost all other global shipping carriers. In latest version, it has automatic mode to detect carrier from tracking number. If you use different carriers, you can use that mode. If you have another shipping service that you are interested in then please [contact us here](https://snapcx.io/contact?utm_source=wordpress&utm_medium=landing&utm_campaign=tracking), as we are actively adding new services. Note: Active paid subscription is required, after trial period is over.

**To get started** 

1. First install this FREE plugin and Activate it. 
1. <a href="https://snapcx.io/pricing?solution=tracking">Sign up for an snapCX subscription plan</a> to get an API key, and 
1. (Subscription is monthly PAID plan but you can try out with FREE TRIAL with No Credit Card required.)
1. Go to your Plugin configuration page (inside woocommerce menu), and save your API key.
1. [Remember] After TRIAL period is over, automatic invoice will be sent for next billing cycle.

= Key Features =

* Supports USPS, UPS, FedEx, DHL, Canada Post and almost all international shipping carriers. [See list](https://snapcx.io/docs/track_carriers?utm_source=wordpress&utm_medium=landing&utm_campaign=tracking)
* Tracking information is shown on the customer order details page of his account.
* Full tracking information (detailed activity) can be displayed to the customer
* Easily see which orders have shipped or not, right from the order summary screen - saves time and effort
* No more answering emails an phone calls with customers looking for their shipping information.
* Now save your default carrier in plugin admin screen. No need to select carrier every time, when adding tracking number to order. 
* Order admin can see shipping tracking from order detail page. It enables to see you same view as customer. 
* Now it **sends email to customer**, when you add tracking number on order page.
* **NEW FEATURE** It automatically hosts tracking landing page which you or your customer can visit. Link to that page is send along with email. Hence customer doesn't need to log in to their account to check status. 
* **NEW FEATURE** Now you can customize externally hosted tracking landing page using your own domain. Example:  http://track.snapcx.io/?carrier_code=dhl&track_id=2374729361 can be yours. And yes, you can customize page with your cross sells or links or anything. 
(Custom CNAME is offered, dependending upon subscription plan level)

**Demo server with installed plugin**
<a href="http://woocommerce-demo.snapcx.io/shop/awesome-blue-shirt/?utm_source=wordpress&utm_medium=landing&utm_campaign=tracking" target="_blank">Click here</a>

= Screencast video on how to install and use =
[youtube https://www.youtube.com/watch?v=NgzDcMgCLSM]

**Related Plugins**
Enhance your checkout experience for your customers by providing them real time global address validation on shipping address. See our other plugin for [Global Address Validation and Correction](https://wordpress.org/plugins/woo-address-validation/)


== Frequently Asked Questions ==

= What shipping services does the plugin support? =
We support USPS, UPS, FedEX, DHL, Canada Post and almost any other shipping carriers. We are constantly adding new services. If you have a particular service you would like to see us include, [contact us here](https://snapcx.io/contact?utm_source=wordpress&utm_medium=landing&utm_campaign=tracking) 
See [full list here](https://snapcx.io/docs/track_carriers?utm_source=wordpress&utm_medium=landing&utm_campaign=tracking) 

= Why do I need an snapCX Account? =
Firstly the accounts are free, no credit card required. We use an account as we have a set of back-end services that provide the shipping information. We have all subscription plans with TRIAL periods. You can pick any plan to start with and you can downgrade/upgrade If you happen to be a high-volume store, then feel free to contact us for custom plan.  [contact us here](https://snapcx.io/contact?utm_source=wordpress&utm_medium=landing&utm_campaign=tracking)
See [Subscription plans here](https://snapcx.io/pricing.jsp?solution=tracking&utm_source=wordpress&utm_medium=landing&utm_campaign=tracking)

= How can I get help for this plugin? =  

snapCX provides premier level support. Simply [contact us here](https://snapcx.io/contact?utm_source=wordpress&utm_medium=landing&utm_campaign=tracking) and we'll get back to you ASAP 

= Screencast video on how to install and use =
[youtube https://www.youtube.com/watch?v=NgzDcMgCLSM]

== Installation ==
= Manual Installation =
1.	Download and unzip the plugin
2.	Go to your website's WordPress Dashboard and click on the menu "Plugins" -> "Add New"
3.	Click the "Upload Plugin" link at the top of the page.
4.	Choose the file you downloaded and click "Install Now"
		Remaining instructions are covered in the section titled "Activation"
		
= Automatic Installation =
1.	Go to your website's WordPress Dashboard and click on "Plugins" -> "Add New"
2.	In the "Search Plugins" bar enter "snapCX Shipping Tracking" or only "snapCX" to find the WooCommerce Shipping Tracking Plugin
3.	Click "Install Now" to install the plugin.
		
= Activation and Settings =
1.	Before activation please make sure that **WooCommerce is activated**. 
2.	Upon installation you will see a link titled "Activate Plugin". Click it to activate the plugin. 
3.	Locate the "snapCX Shipping Tracking" sub-menu under WooCommerce menu on the admin dashboard and enter the User Key. You can get the User Key [here](https://snapcx.io/pricing.jsp?solution=tracking&utm_source=wordpress&utm_medium=landing&utm_campaign=tracking)
4.	Select Yes for the Enabled field and click "Submit".
5. (optional) Set default carrier.
6. (optional) Upon plugin activation, your plugin get list of all possible shipping carriers list. But you can get updated list whenever you want. No need of new plugin version. 
7. Now we have shipping tracking landing page hosted by us. If you want to use your own custom cname, you can update on url field. It has to be http://track.<custom_domain>
8. Now we sent order update (along with order details), when you update tracking number. You can customize text in plugin settings too. 

= Demo server with installed plugin =
<a href="http://woocommerce-demo.snapcx.io/shop/awesome-blue-shirt/?utm_source=wordpress&utm_medium=landing&utm_campaign=tracking" target="_blank">Click here</a>

= Screencast video on how to install and use =
[youtube https://www.youtube.com/watch?v=NgzDcMgCLSM]

== Upgrade Notice ==

= 2.2.1 =
Minor fix, due to missing files in prev version.

= 2.2.0 =
Now plugin validates API key (user key), every time it is changed. 
Testing against latest version of woocommerce & wordpress.

= 2.1.0 =
Moved Settings under woocommerce settings for more intuitive user experience. 

= 2.0.4 =
Now compatible with latest wordpress (4.0+) and latest WooCommerce v3.3.1
Minor fix, to make sure, email goes out to customer.

= 2.0.3 =
Now compatible with latest wordpress (4.0+) and latest WooCommerce v3.2.6

= 2.0.2 =
Now compatible with latest wordpress (2.8+) and latest WooCommerce v3.1.1

= 2.0.1 =
Customer will not receive any email of tracking on changing Order status to Hold even if tracking number is added and Customer will receive completed order email with tracking number on changing Order status to Completed.

= 2.0.0 =
Fixed order email functionality. Now ALL order update emails include link to shipping tracking.  

== Screenshots ==

1. Plugin Settings page. Here you can customize the message that your customers get.
2. Order Summary. You can easily see which orders have shipped and which haven't - no more wasted time.
3. Order Details. Simply and easily enter the carrier & tracking number - it's as easy as that
4. This is how the order tracking information appears in the customers email - remember you control the text that is shown
5. Customer Order Detail Page. Your customized text.
6. Tracking Details. The detailed tracking information the customer can view.
7. Screenshot of email sent to Customer with hyper link to shipping tracking number.
8. Screenshot of email sent to customer only first time with hyper link to shipping tracking number when tracking number is added.
9. Screenshot of email sent to customer with hyper link to shipping tracking number on changing Order Actions to Processing order.
10. Screenshot of email sent to customer with hyper link to shipping tracking number on changing Order Actions to Completed order.

== Changelog ==

= 2.2.1 =
1. Minor fix, due to missing files in prev version.

= 2.2.0 =
1. Now plugin validates API key (user key), every time it is changed. 
2. Testing against latest version of woocommerce & wordpress.

= 2.1.0 =
1. Moved Settings under woocommerce settings for more intuitive user experience. 
2. Tested with latest versions of WooCommerce and wordpress. 

= 2.0.4 =
1. Tested with woocommerce v3.3.1
2. Minor fix, to make sure, email goes out to customer.
3. Tested with wordpress 4.9.4 version

= 2.0.3 =
1. Tested with wordpress 4.9.2 version
2. Tested with WooCommerce v3.2.6

= 2.0.2 =
1. Tested with wordpress 2.8 version
2. Tested with WooCommerce v3.1.1

= 2.0.1 =
1. Customer will not receive any email of tracking on changing Order status to Hold even if tracking number is added.
2. Customer will receive completed order email with tracking number on changing Order status to Completed.

= 2.0.0 =
1. Solve email problem now. Customer will receive email only first time when tracking number is added. 
2. Customer will receive processing order email with tracking number on changing Order Actions to Processing order.
3. Customer will receive completed order email with tracking number on changing Order Actions to Completed order.
4. Add troubleshoot link in Plugin settings page.

= 1.5.0 =
1. Added shipping tracking email functionality, upon adding tracking number to order.
2. The user can see his order's shipping tracking detail in external hosted page. Link is embedded in order update email. 
3. Admin can set email text from setting section. (also order details are sent in email body.)
4. Removed deprecated functions from the old version.
5. Now, we track almost all shipping carriers. List is updated when plugin is activated or by clicking carriers update button on plugin settings page.
6. Can use **custom cname** for hosted tracking page. 
7. Now 100+ shipping carriers are supported.

= 1.4.0 =
1. Added support for Canada Post as shipping carrier.
2. Save your default carrier in plugin admin screen. No need to select carrier every time. 
3. Order admin can see shipping tracking from order detail page. It enables to see you same view as customer. 

= 1.3.0 = 
Renamed plugin from ubercx to snapcx. ubercx.io is now snapcx. No new functionality added or no bugs fixed in this release.

= 1.2.1 =
Customer interface is reworked to look more professional
Interface is more intuitive now
Updated screen shots

= 1.1.2 =
Updated screenshots

= 1.1.1 =
Updated readme

= 1.1.0 =
* Updated to now show tracking information on the order screen and in order emails to customers

= 1.0.2 =
* The plugin has fix for uninstall event. Now it removes api key upon un-install of this plugin.

= 1.0.1 =
* The plugin has fix for uninstall event. Now it removes api key upon un-install of this plugin.

= 1.0.0 =
* First release of plugin!
* Fully functional with four shipping carriers.