=== YouTube ===
Contributors: embedplus
Plugin Name: YouTube Embed
Tags: youtube gallery, video gallery, youtube channel, youtube live, live stream
Requires at least: 4.0
Tested up to: 5.1
Stable tag: 13.1
License: GPLv3 or later

YouTube Embed WordPress Plugin. Embed a responsive video, YouTube channel gallery, playlist gallery, or YouTube.com live stream (with GDPR options)

== Description ==

**Your WordPress YouTube embed, YouTube gallery (channel and playlist), and even YouTube live stream can be customized in a wide variety of ways with this plugin. Here are a few recently added features:**

* Compatible with the WordPress 5.0 Gutenberg block editor (it also stays backwards-compatible with the classic editor). Both the Gutenberg block selector and the Gutenberg classic block will show the YouTube wizard button.  For the Gutenberg block selector, click on the (+) sign for the block editor list. The YouTube Wizard block is located under the "Embeds" category (make sure you choose "YouTube **Wizard**"). See more on [how to embed a YouTube video, gallery, or livestream with the WordPress Gutenberg block editor here >>](https://www.embedplus.com/embed-youtube-video-gallery-livestream-wordpress-gutenberg-block-editor.aspx)
* Privacy and Consent - Improved privacy and GDPR compliance options like YouTube no cookie, YouTube API restrictions, and a customizable GDPR consent message
* YouTube gallery capability (channel and playlist) – The ability to make playlist and channel embeds have a gallery layout. By default, the plugin can generate a grid-based [responsive playlist or channel gallery >>](https://www.embedplus.com/responsive-youtube-playlist-channel-gallery-for-wordpress.aspx). Your visitors can browse through pages of video thumbnails and choose from videos that are pulled from an entire YouTube channel or playlist.
* YouTube gallery auto continuous play - embed a playlist or channel gallery and allow it to play one video after the next without requiring viewers to click a thumbnail
* YouTube Live Stream - Given a link to a YouTube channel, the plugin wizard automatically finds a livestream if one is active in that channel and generates the embed code for you. On the settings page, you can also set defaults of what to automatically display if a live stream is not active at a given moment. For example, you can have your site display a gallery of a channel's entire video library so that users can have something to watch in the meantime. We hope it's a time saver.
* Improved accessibility by using title attributes for screen reader support. It should help your site pass functional accessibility evaluations (FAE).
* Improved ajax theme support
* Site origin information with each embed code as an extra security measure. In YouTube's/Google's own words, checking this option "protects against malicious third-party JavaScript being injected into your page and hijacking control of your YouTube player." We especially recommend checking it as it adds higher security than the built-in YouTube embedding method that comes with the current version of WordPress (i.e. oembed).
* Easy "Insert" button for both the Visual and Text mode of the editor (for YouTube gallery and live stream embedding as well)
* Embed an entire YouTube channel as a (non-gallery, single embedded player) playlist
* Start video settings for playlist embeds. You can now choose to start a playlist with a specific video or have the plugin automatically start with the most recently added video. **Note: If you’re embedding a YouTube gallery, the order will be defined by the order of the channel or playlist on YouTube.com**
* [Volume level initialization](https://www.embedplus.com/mute-volume-youtube-wordpress.aspx) - helpful when autoplay is checked
* iOS playback preferences
* Automatic localization/internationalization so you can set the player's interface language from English to another
* Instant HTTPS support. It can even convert past non-HTTPS videos to HTTPS.  Did you know that Google uses HTTPS/SSL support as a ranking signal for SERP?
* "At a Glance" direct access to your YouTube posts/pages
* Shortcode support for embedding multiple videos on one line
* General playlist embedding support
* YouTube plugin migration support

**Click the Download button to start exploring now, or take a look at some more introductory details below.**

This plugin helps you easily manage the growing complexity of YouTube embedding. It provides a spectrum of basic and advanced features of the YouTube embedded player and will have you posting videos in seconds after installing it.

The settings page has plenty of default options that you can automatically apply to your embedded YouTube video, gallery, and/or live stream:

* Modest branding - hide YouTube logo while playing (note that the YouTube watermark is shown instead)
* Turn on/off all annotations by default
* Automatically center all your videos
* Automatically start playing your videos
* Autohide controls until hovering
* Loop your videos
* Show/hide related videos at the end
* Show/hide the video title and other info
* Use the light theme
* Show/hide player controls
* Turn on/off closed captions by default
* Make your video, gallery, or live stream responsive so that it dynamically fits in all screen sizes (smart phone, PC and tablet)

Customizations can be also made to each YouTube embed by adding more to the link as shown below. Adding these will override the above global defaults that you set:

* width - Sets the width of your player. If omitted, the default width will be the width of your theme's content. Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&width=500&height=350"`
* height - Sets the height of your player. If omitted, this will be calculated for you automatically. Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&width=500&height=350"`
* autoplay - Set this to 1 to autoplay the video (or 0 to play the video once). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&autoplay=1"`
* cc_load_policy - Set this to 1 to turn on closed captioning (or 0 to leave them off). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&cc_load_policy=1"`
* iv_load_policy - Set this to 3 to turn off annotations (or 1 to show them). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&iv_load_policy=3"`
* loop - Set this to 1 to loop the video (or 0 to not loop). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&loop=1"`
* modestbranding - Set this to 1 to remove the YouTube logo while playing (or 0 to show the logo). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&modestbranding=1"`
* rel - Set this to 0 to not show related videos at the end of playing (or 1 to show them). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&rel=0"`
* showinfo - Set this to 0 to hide the video title and other info (or 1 to show it). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&showinfo=0"`
* fs - Set this to 0 to hide the fullscreen button (or 1 to show it). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&fs=0"`

You can also start and end each individual video at particular times. Like the above, each option should begin with '&'

* start - Sets the time (in seconds) to start the video. Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&width=500&height=350&start=20"`
* end - Sets the time (in seconds) to stop the video. Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&width=500&height=350&end=100"`

> **About [YouTube PRO](https://www.embedplus.com/dashboard/pro-easy-video-analytics.aspx?ref=readme)**
>
> If you like this free version, you may even upgrade to a separate Pro plugin with features like:
>
> * Full visual embedding wizard 
> * [Alternate playlist and channel gallery styling >>](https://www.embedplus.com/responsive-youtube-playlist-channel-gallery-for-wordpress.aspx) (list layouts and slider layouts, popup/lightbox player, thumbnail hiding for text only paging, and more)
> * Caching to avoid making frequent requests to YouTube.com and speed up your page loads
> * Automatic video thumbnail images: each post or page that contains at least one video will have the thumbnail of its first video serve as its featured image
> * [Lazy loading YouTube embeds >>](https://www.embedplus.com/add-special-effects-to-youtube-embeds-in-wordpress.aspx) with eye-catching effects and animations
> * Automatic tagging for video SEO
> * Automatic Open Graph tagging for Facebook
> * Deleted video alerts (i.e., did Google remove or take down videos I previously embedded?) 
> * Mobile compatibility checking (i.e., see if your embeds have restrictions that can block your site's mobile visitors from viewing)
> * Alerts when visitors from different countries are blocked from viewing your embeds
> * Priority support
>
> You also get access to our deleted video alerts to help avoid showing embedded videos that are later removed from YouTube.com. You even get an embedder-centric [analytics dashboard](https://www.embedplus.com/dashboard/easy-youtube-analytics-preview.aspx?platform=sim) that adds view tracking to each of your embeds so that you can answers questions like:
>
> * How much are your visitors actually watching the videos you post?
> * How does the view activity on your site compare to other sites like it?
> * What and when are your best and worst performing YouTube embeds?
> * How much do the producers of the YouTube videos you embed rely on **your site**, versus other sites and YouTube.com, for views?
> * Are you embedding videos that are blocked in other countries?
> * Have your visitors tried to view a page and/or gallery on your site with deleted/unavailable videos?

See more details after installing. Enjoy!

[Maintained by EmbedPlus for YouTube >>](https://www.embedplus.com/)

== Installation ==

1. Use the WordPress plugin installer to install the plugin.  Alternatively, you can just extract the folder in our download package and upload it to your plugin directory.
1. Access the Plugins admin menu to activate the YouTube embed plugin.
1. Make your default settings after clicking the new YouTube menu item that shows up in your admin panel.
1. To embed videos in your post, uuse the wizard to embed the shortcode. Example: `[embedyt]https://www.youtube.com/watch?v=ABCDEFGHIJK&width=400&height=250[/embedyt]` If you don't know exactly which video you want to embed, use the free built-in search feature to find and insert one.
1. You can also [embed a playlist and channel gallery with this plugin >>](https://www.embedplus.com/responsive-youtube-playlist-channel-gallery-for-wordpress.aspx).  Please install the plugin and visit the settings page for instructions.
1. To get video SEO, an analytics dashboard and many other premium features, [sign up for one of the options here >>](https://www.embedplus.com/dashboard/pro-easy-video-analytics.aspx?ref=readme)

**Additional codes (adding these will override the default settings in the admin):**

* width - Sets the width of your player. If omitted, the default width will be the width of your theme's content. Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&width=500&height=350"`
* height - Sets the height of your player. If omitted, this will be calculated for you automatically. Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&width=500&height=350"`
* autoplay - Set this to 1 to autoplay the video (or 0 to play the video once). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&autoplay=1"`
* cc_load_policy - Set this to 1 to turn on closed captioning (or 0 to leave them off). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&cc_load_policy=1"`
* iv_load_policy - Set this to 3 to turn off annotations (or 1 to show them). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&iv_load_policy=3"`
* loop - Set this to 1 to loop the video (or 0 to not loop). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&loop=1"`
* modestbranding - Set this to 1 to remove the YouTube logo while playing (or 0 to show the logo). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&modestbranding=1"`
* rel - Set this to 0 to not show related videos at the end of playing (or 1 to show them). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&rel=0"`
* showinfo - Set this to 0 to hide the video title and other info (or 1 to show it). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&showinfo=0"`
* fs - Set this to 0 to hide the fullscreen button (or 1 to show it). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&fs=0"`
* autohide - Set this to 1 to slide away the control bar after the video starts playing. It will automatically slide back in again if you mouse over the video. (Set to  2 to always show it). Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&autohide=1"`

You can also start and end each individual video at particular times. Like the above, each option should begin with '&'

* start - Sets the time (in seconds) to start the video. Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&width=500&height=350&start=20"`
* end - Sets the time (in seconds) to stop the video. Example: `"https://www.youtube.com/watch?v=quwebVjAEJA&width=500&height=350&end=100"`

**We recommend using the wizard, but if you're manually pasting a link, always follow these rules:**

* Make sure the url is really on its own line by itself. Or, if you need multiple videos on the same line, make sure each URL is wrapped properly with the shortcode. Example: `[embedyt]https://www.youtube.com/watch?v=ABCDEFGHIJK&width=400&height=250[/embedyt]`
* Make sure the url is not an active hyperlink (i.e., it should just be plain text). Otherwise, highlight the url and click the "unlink" button in your editor.
* Make sure you did **not** format or align the url in any way. If your url still appears in your actual post instead of a video, highlight it and click the "remove formatting" button (formatting can be invisible sometimes).
* Finally, there's a slight chance your custom theme is the issue, if you have one. To know for sure, we suggest temporarily switching to one of the default WordPress themes (e.g., "Twenty Thirteen") just to see if your video does appear. If it suddenly works, then your custom theme is the issue. You can switch back when done testing.

== Screenshots ==

1. YouTube Embed Screenshot 1: Paste a YouTube link on its own line and it will become a YouTube embed on your website. Or, use the shortcode method.
2. YouTube Embed Screenshot 2: How to get to YouTube's admin settings
3. YouTube Embed Screenshot 3: Visual YouTube Wizard and Search Tool
4. YouTube Embed Screenshot 4: Wizard Insert Search Result Screenshot
5. YouTube Embed Screenshot 5: Wizard Search Results Screenshot
6. YouTube Embed Screenshot 6: Gallery layout for a playlist or channel
7. YouTube Embed Screenshot 7: YouTube playlist galleries are also easily supported.  Here's the Billboard Top 25 Songs for example.
8. YouTube Embed Screenshot 8: Localization/internationalization so you can set the player's interface language
9. YouTube Embed Screenshot 9: Optional YouTube channel subscription button above gallery thumbnails

== Changelog ==

= WordPress YouTube Embed 13.1 =
* Brings back the ability to hide related/suggested videos at end of playback
* Allows monetized sites to select multiple IAB categories
* Option to run shortcode in admin (compatibility tab)
* Lowers quota usage for live videos
* Fixes "missing dependencies" block editor CSS reference
* Fixes default dimensions issue

= WordPress YouTube Embed 13.0.1 =
* Clearer instructions
* Feature deprecation (rel, showinfo)

= WordPress YouTube Embed 13.0 =
* Compatible with the new Gutenberg block editor, and stays backwards-compatible with the classic editor
* Marked deprecated features
* Improved wizard instructions

= WordPress YouTube Embed 12.2 =
* Improved ads.txt verification management
* Fixed gallery box-sizing bug
* Remove some deprecated YouTube parameters

= WordPress YouTube Embed 12.1 =
* Improved autoplay compatibility
* Improved sign-up process for the new monetization feature

= WordPress YouTube Embed 12.0.1 =
* Force HTTPS for the YouTube API if the API is enabled
* Makes the wizard's lightbox expand/contract responsively for different size screens

= WordPress YouTube Embed 12.0 =
* Improves the admin interface, and includes a new optional feature for users that want to monetize their sites through contextual video from vi.ai.

= WordPress YouTube Embed 11.9.2 =
* Makes the GDPR consent message display more compatible with other plugins (fixes content filter)

= WordPress YouTube Embed 11.9.1 =
Improved GDPR compliance, with new Privacy section containing:
* GDPR consent mode
* YouTube no cookie
* YouTube API loading restrictions

= WordPress YouTube Embed 11.8.7 =
* Helps with GDPR compliance by allowing you to choose when YouTube.com's API is loaded

= WordPress YouTube Embed 11.8.6.1 =
* Rollback jQuery Updater plugin compatability

= WordPress YouTube Embed 11.8.6 =
* Updated YouTube API key video tutorial
* Improved channel wizard process
* Better compatibility with jQuery Updater plugin

= WordPress YouTube Embed 11.8.5 =
* Adds the YouTube wizard button to the new built-in WordPress text widget
* Fixes a Mac/iOS gallery scrolling issue

= WordPress YouTube Embed 11.8.4 =
* Fixes gallery AJAX issue
* Adds ability to hide fullscreen button
* Adds ability to hide private videos from galleries
* Changes localized script dependency to jquery

= WordPress YouTube Embed 11.8.3 =
* Improved compatability with PHP 7
* Improved compatability with Divi theme
* Improved AJAX compatibility
* Improved accessibility
* Fix gallery scrolling
* Fix "not live" content output
* Modernize and improve wizard interface (Pro)
* Ability to hide thumbnail images from galleries (Pro)

= WordPress YouTube Embed 11.8.2 =
* Fix auto-next gallery issue
* Make settings form more secure

= WordPress YouTube Embed 11.8.1 =
* Fix playlist gallery issue

= WordPress YouTube Embed 11.8 =
* Improved AJAX theme compatability
* Ability to choose which roles can use the editor wizard
* Volume + autoplay fix

= WordPress YouTube Embed 11.7.1 =
* Remove gallery thumbnail translucency
* Various bug fixes for galleries and the wizard

= WordPress YouTube Embed 11.7 =
* Separate Free and Pro codebases
* Remove deprecated features
* Clean up code

= WordPress YouTube Embed 11.6 =
* Fixed issue where Free version had some remnants of Pro analytics codebase supporting features here: https://www.embedplus.com/dashboard/easy-youtube-analytics-preview.aspx?platform=sim
* New feature: live streaming from a channel
* Optimized player and gallery loading that may improve GTMetrix reports
* Improved compatibility with pagebuilders
* Improved compatibility with later versions of jQuery
* Removed frameborder for W3C validation
* Gallery pagination enhancement
* PHP 7 compatibility testing

= WordPress YouTube Embed 11.5 =
* thumbnail stacking for mobile galleries
* better support for ajax themes
* alt text to images

= WordPress YouTube Embed 11.4 =
* (Free) Improved subscribe button CSS and a new migration option.
* (Pro) Improved accessibility for popup lightbox galleries.

= WordPress YouTube Embed 11.3.1 =
* (Free) improved debug mode messages
* (Pro) autonext for popup lightbox galleries and hide clear cache button option

= WordPress YouTube Embed 11.3 =
* Improved responsive sizing for AJAX-based themes
* Popup lightbox display option for Pro galleries

= WordPress YouTube Embed 11.2 =
* Adds improved wizard and gallery options for Free and Pro users.
* Improved handling of PHP notices.
* Clear cache shortcut added for Pro users.

= WordPress YouTube Embed 11.1 =
* Automatic continuous play for playlist and channel gallery embeds.
* Ability to add a YouTube channel subscription link to all galleries.
* Ability to hide Previous/Next buttons and page numbers.
* Featured images can now be pulled from playlists (using the thumbnail of the first video).

= WordPress YouTube Embed 11.0.1 =
Improves HTTPS support for structured data tags, and HTTPS support in Firefox.

= WordPress YouTube Embed 11.0 =
Improves playlist and gallery embedding functionality.

= WordPress YouTube Embed 10.9 =
This version offers a host of updates: compatibility fixes, higher quality featured images, selective responsive sizing, wizard enhancements, and circular shaped thumbnails.

= WordPress YouTube Embed 10.8 =
This update improves compatibility and adds features to Free and Pro galleries.

= WordPress YouTube Embed 10.7 =
Adds legacy option.

= WordPress YouTube Embed 10.6 =
Enhanced debugging support for galleries.

= WordPress YouTube Embed 10.5 =
Enhanced gallery settings for scrolling, video titles, and thumbnail styling.

= WordPress YouTube Embed 10.4 =
Addresses limitations some users were getting when pasting a YouTube channel and/or playlist gallery.

= WordPress YouTube Embed 10.3 =
Adds the ability to make playlist and channel embeds have a gallery layout. By the default, the plugin can generate a grid-based [responsive playlist and channel gallery >>](https://www.embedplus.com/responsive-youtube-playlist-channel-gallery-for-wordpress.aspx)

= WordPress YouTube Embed 10.2 =
Adds start video settings for playlist embeds. You can now choose to start a playlist with a specific video or have the plugin automatically start with the most recently added video.

= WordPress YouTube Embed 10.1 =
Adds the ability to automatically migrate from another plugin's shortcode. Caching feature now allows lifetime settings. Improved compatibility with other plugins using the YouTube API.

= WordPress YouTube Embed 10.0 =
This update includes improved tips (Free and Pro) and adds caching to the Pro version for faster page loading.

= WordPress YouTube Embed 9.8 =
Upgraded code to use YouTube API v3.

= WordPress YouTube Embed 9.7 =
Adds improved accessibility for screen readers and video thumbnail as featured image support.  

= WordPress YouTube Embed 9.5 =
Site origin information with each embed code as an extra security measure. In YouTube's/Google's own words, checking this option "protects against malicious third-party JavaScript being injected into your page and hijacking control of your YouTube player." We especially recommend checking it as it adds higher security than the built-in YouTube embedding method that comes with the current version of WordPress (i.e. oembed).

= WordPress YouTube Embed 9.4 =
Adds Autofit Widget option for Free and PRO users. Also adds slide from left animation to [PRO effects >>](https://www.embedplus.com/add-special-effects-to-youtube-embeds-in-wordpress.aspx)

= WordPress YouTube Embed 9.3 =
Improved volume functionality and interface updates (Free and PRO).  [Special effects added to PRO version >>](https://www.embedplus.com/add-special-effects-to-youtube-embeds-in-wordpress.aspx)

= WordPress YouTube Embed 9.2 =
HTTPS/SSL detection is now fully automatic. The manual checkbox is no longer needed.

= WordPress YouTube Embed 9.1 =
With this version, the plugin can now automatically detect your site's default language and set the interface of the embedded YouTube player to match.

= WordPress YouTube Embed 9.0 =
Adds automatic localization/internationalization so you can set the player's interface language from English to another.

= WordPress YouTube Embed 8.9 =
Allows volume level initialization.

= WordPress YouTube Embed 8.8 =
* Better embedplus plugin conflict notifications.
* Greater emphasis on HTTPS support due to Google's recent announcement about [HTTPS/SSL as an SEO/SERP signal](https://embedplus.com/convert-old-youtube-embeds-to-https-ssl.aspx).
* Supports latest version of WordPress (3.9.2).

= WordPress YouTube Embed 8.7 =
(PRO) Extends the plugin's existing tagging capabilities by also adding Open Graph markup to enhance Facebook sharing/discovery of your pages.

= WordPress YouTube Embed 8.6 =
Expanded HTTPS/SSL support.

= WordPress YouTube Embed 8.5 =
This update features a new iOS related option for both Free and PRO users. PRO users additionally have the new mobile compatibility checker.

= WordPress YouTube Embed 8.4 =
This update features improved responsive theme support for both Free and PRO versions.  It also adds refined schema tag support to the Pro SEO feature.

= WordPress YouTube Embed 8.3 =
Now compatible with WordPress 3.9.

= WordPress YouTube Embed 8.2 =
An at a glance regex impromevent (all users). Dashboard now warns you of embeds that are blocked from your visitors in other countries (PRO).

= WordPress YouTube Embed 8.1 =
Fixes rare YouTube ID issue.

= WordPress YouTube Embed 8.0 =
Fixes rare ajax issue.

= WordPress YouTube Embed 7.9 =
YouTube searching and inserting now works in Text mode of the editor too.

= WordPress YouTube Embed 7.8 =
"At a Glance" direct access to YouTube posts/pages.

= WordPress YouTube Embed 7.7 =
Fixes black bar issue when no height/width is specified.

= WordPress YouTube Embed 7.6 =
Added notice about Google's HD problem.

= WordPress YouTube Embed 7.5 =
Ability to embed an entire channel as a playlist embed.

= WordPress YouTube Embed 7.3 =
Change the color of the progress bar from red to white.

= WordPress YouTube Embed 7.2 =
Added music video extras to inspire your posts (PRO).

= WordPress YouTube Embed 7.1 =
Added autohide controls feature

= WordPress YouTube Embed 7.0 =
Shortcode support for embedding multiple videos on one line

= WordPress YouTube Embed 6.4 =
Fixes for some users of WordPress 3.8

= WordPress YouTube Embed 6.3 =
Removed possible e_notices.

= WordPress YouTube Embed 6.2 =
Given the increasing focus on privacy, the no-cookies options was added as a free option to all users.  Priority support enhancements also made.

= WordPress YouTube Embed 6.1 =
Easier access to general settings and dashboard.

= WordPress YouTube Embed 6.0 =
This version opens up the ability to view Internet video discussions to all wizard users.
**Also, due to numerous users being unable to find the wizard button, we moved it up next to the "Add Media" button.**
Finally, we added Video SEO tags as a PRO option. 

= WordPress YouTube Embed 5.1 =
Added ability to set default dimensions.
Enhanced compatibility with SSL sites.

= WordPress YouTube Embed 5.0 =
Built-in YouTube video search, viewing, and insertion right from your editor tab (for all users).
The ability to review the latest web discussions about a video you want to embed before embedding it (PRO users).
Other minor optimizations.

= WordPress YouTube Embed 4.8 =
Works when pasting embed link in sidebar text widgets

= WordPress YouTube Embed 4.7 =
Improved separation of PRO and Free features in the UI

= WordPress YouTube Embed 4.6 =
Added optional responsive video layout to fit all screen sizes. (smart phone, PC and tablet)

= WordPress YouTube Embed 4.5 =
Added support for playlists.
added support for wmode.

= WordPress YouTube Embed 4.1 =
Fixed spacing issue. Also added ability to fall back to old spacing format.

= WordPress YouTube Embed 4.0 =
New features for all users: lazy loading for the flash player by default and the ability to hide player controls for a cleaner look.

= WordPress YouTube Embed 3.7 =
Enhanced deleted video checker for PRO users

= WordPress YouTube Embed 3.5 =
Added ability to try to force HTML5 player to speed up page loading

= WordPress YouTube Embed 3.3 =
HTTPS: Added secure YouTube embedding

= WordPress YouTube Embed 3.2 =
Ensures video-specific height overrides defaults properly

= WordPress YouTube Embed 3.1 =
Fixed obscure height problem

= WordPress YouTube Embed 3.0 =
Added Visual YouTube Wizard for PRO users
Added autologin to analytics for PRO users
Added priority support form for PRO users

= WordPress YouTube Embed 2.6 =
Compatible with WP 3.6

= WordPress YouTube Embed 2.4 =
Added auto HD support
Support for shorthand (i.e. `"https://www.youtu.be"`)
Fixed editor issue

= WordPress YouTube Embed 2.3 =
Start/end time shortcut bug fix

= WordPress YouTube Embed 2.2 =
Minor changes

= WordPress YouTube Embed 2.1 =
By request from several users, we've added easier access to the video analytics dashboard

= WordPress YouTube Embed 2.0 =

This upgrade specifically integrates a user-friendly YouTube Analytics Dashboard to this plugin so you can learn a lot more about the videos you post.  Download it if you would like it to use your site's YouTube-related activity to help answer questions like:
 
* How much are your visitors actually watching the videos you post?
* How does the view activity on your site compare to other sites like it?
* What and when are your best and worst performers?
* How much do the producers of the YouTube videos you embed rely on **your site** for views?

We think these are all interesting questions; however, note that there's no need to upgrade if you don't.

= WordPress YouTube Embed 1.1 =
Fixed minor bug.

= WordPress YouTube Embed 1.0 =
First release uploaded to the plugin repository.

== Other Notes ==

This YouTube plugin can include premium features like animation effects using lazy loading, alternate playlist and channel gallery styles, automatic video thumbnail support, automatic schema tagging for video SEO, mobile compatibility checking, and deleted video alerts when you [upgrade to PRO](https://www.embedplus.com/dashboard/pro-easy-video-analytics.aspx?ref=readme).
