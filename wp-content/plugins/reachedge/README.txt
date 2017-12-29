=== ReachEdge ===
Contributors: ReachLocal
Tags: ReachLocal, ReachEdge, Lead Conversion, Call Tracking, Form Capture, Form Tracking, Email Capture, Email Tracking, Phone Tracking, Phone Numbers, Call Tracker, Conversion Tracking
Requires at least: 2.7
Tested up to: 4.7.2
Stable tag: 1.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

ReachEdge offers lead & call tracking, lead notifications & nurturing, ROI reports, analytics & insights, and mobile app & alerts.

== Description ==

For [ReachEdge](http://go.reachlocal.com/contact-us-edge.html) customers, [ReachLocal](http://www.reachlocal.com) provides a simple WordPress plugin that enables you to capture leads, understand your sources of leads, and provides tools to help you respond to and manage those leads.

The [ReachLocal](http://www.reachlocal.com) [ReachEdge](http://go.reachlocal.com/contact-us-edge.html) WordPress plugin adds the tracking software to the WordPress site. This plugin adds the [ReachEdge](http://go.reachlocal.com/contact-us-edge.html) tracking software on all pages of the WordPress website.   The software is loaded from a CDN and is under continuous development to provide the best performance and stability across all browsers and OS combinations.  As new features and functionality are added to ReachEdge, those updates will be rolled out without any updates of this plugin.

About ReachEdge

[ReachEdge](http://go.reachlocal.com/contact-us-edge.html), [ReachLocal's](http://www.reachlocal.com) lead conversion software, lets you manage leads and turn them into customers. The lead tracking technology in [ReachEdge](http://go.reachlocal.com/contact-us-edge.html) measures how each of your marketing sources - like search advertising, social media, and SEO - is working. Plus, it records every phone call that comes from your website so you know exactly how they found and listen to and respond to inquiries.

With [ReachEdge](http://go.reachlocal.com/contact-us-edge.html), you also get access to an online dashboard and mobile application that provide you visibility into your inbound leads and tools to follow up with these leads - such as by editing the lead status and assigning the leads to other people in your company so you an follow up with them.

== Installation ==

1. Activate plugin.
2. In the WordPress dashboard, navigate to the 'Settings' menu.
3. Select the 'ReachLocal Tracking Code' option from the menu.
4. Enter your tracking code ID into the ID field, and click the 'Save Changes' button.

== API Interaction provided by mms.js from the CDN ==

1. mms.js loads the customer's configuration data from ReachLocal 
2. Sends analytics data back to ReachLocal for performance metrics.
3. Sends visit & referrer attribution back to ReachLocal for analytics
4. Sends visit, email, and form post data back to ReachLocal to provide lead management.
5. Email links are replaced with contact forms and the form data and sending of email is offloaded to ReachLocal's servers.

== Screenshots ==

1. Modified settings panel with ReachLocal tracking.
2. ReachLocal tracking settings page.

== Changelog ==
= 1.0.0 =
* Offical Release

= 0.4.2 =
* Description Updates
* Update to make mms.js file to be loaded in head asynchronously

= 0.4.1 =
* Updated README with information about why JavaScript asset is loaded from CDN.

= 0.4.0 =
* Changed the tracking code name for greater uniqueness 
* Use enqueue_script to place capture JS on the page  

= 0.3.0 =
* Restructured plugin for easier distribution

= 0.2.0 =
* Formalize the plugin a bit more
* Added better docs

= 0.1.0 =
* Initial skeleton and start of plugin
