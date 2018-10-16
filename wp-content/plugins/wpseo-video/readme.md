Video SEO
=========
Requires at least: 4.8
Tested up to: 4.9.2
Stable tag: 6.2
Depends: wordpress-seo

Video SEO adds Video SEO capabilities to WordPress SEO.

Description
-----------

This plugin adds Video XML Sitemaps as well as the necessary OpenGraph markup, Schema.org videoObject markup and mediaRSS for your videos.

Installation
------------

1. Go to Plugins -> Add New.
2. Click "Upload" right underneath "Install Plugins".
3. Upload the zip file that this readme was contained in.
4. Activate the plugin.
5. Go to SEO -> Extensions and enter your license key.
6. Save settings, your license key will be validated. If all is well, you should now see the XML Video Sitemap settings.
7. Make sure to hit the "Re-index videos" button if you have videos in old posts.

Frequently Asked Questions
--------------------------

You can find the [Video SEO FAQ](https://kb.yoast.com/kb/category/video-seo/) in our knowledge base.

Changelog
=========
### 6.2 January 23rd, 2018
* Compatibility with Yoast SEO 6.2

### 6.1: January 9th, 2018
* Compatibility with Yoast SEO 6.1

### 6.0: December 20th, 2017
* Compatibility with Yoast SEO 6.0

### 5.9: December 5th, 2017
Changes:
* Removes deactivation of this plugin when Yoast SEO Premium is inactive.
* Compatibility with Yoast SEO 5.9

### 5.8: November 15th, 2017
* Compatibility with Yoast SEO 5.8

### 5.7: October 24th, 2017
* Compatibility with Yoast SEO 5.7.

### 5.6: October 10th, 2017
Changes:
* Changes the capability on which the submenu is registered to `wpseo_manage_options`
* Changes the way the submenu is registered to use the `wpseo_submenu_pages` filter

Bugfixes:
* Fixes a bug where the license check endpoint was using an incorrect URL

### 5.5: September 26th, 2017
* Updated the internationalization module to version 3.0.

### 5.4: September 6th, 2017
* Compatibility with Yoast SEO 5.4.

### 5.3: August 22nd, 2017
* Fixes a call to a deprecated method when generating the video sitemap.
* Removed `wp_installing` polyfill.

### 5.2: August 8th, 2017
* Compatibility with Yoast SEO 5.2.

### 5.1: July 25th, 2017
* Fixes a bug where the `isFamilyFriendly` meta property is not set properly.

### 5.0: July 6th, 2017
* Compatibility with Yoast SEO 5.0.
