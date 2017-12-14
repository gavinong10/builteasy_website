=== dsIDXpress IDX Plugin ===
Contributors: Diverse Solutions
Tags: mls, idx, rets, housing, real estate, idxpress, dsidxpress, diverse solutions, zillow
Requires at least: 2.8
Tested up to: 4.3
Stable tag: 2.1.25

This plugin allows WordPress to embed live real estate data from an MLS directly into a blog. You MUST have a dsIDXpress account to use this plugin.

== Description ==

[Diverse Solutions]: http://www.diversesolutions.com "Diverse Solutions, plugin author"

With the [dsIDXpress plugin](http://www.dsidxpress.com), bloggers can embed **live** real estate listings (using what is known as *I*nternet *D*ata E*x*change, or IDX) into their blog's posts and pages using something WordPress calls "shortcodes" and into their sidebars using the included widgets. The plugin also functions as a full IDX solution by allowing visitors to search for, and view the details of, listings in the MLS.

*Important requirements to use this plugin*

* You must be an active member with a multiple listing service (MLS). This means that anyone other than real estate agents and brokers (and, in some MLS's, even agents are excluded) cannot use this plugin.
* The executives at the MLS must be progressive enough to allow the data to be syndicated to your blog from our ([Diverse Solutions]) API.
* Downloading and installing the dsIDXpress plugin is 100% free, but getting the data from your MLS is not. You can use the [free demo data](http://www.dsidxpress.com/tryit/) in the beginning and move on to [obtaining your MLS's data](http://www.dsidxpress.com/) after you've evaluated it.
* Your web host must be running at least PHP 5.2. PHP 5.2 has been out since 2006, so if they aren't using PHP 5.2, they're quite a ways behind the times. This is almost never an issue nowadays.
* You must be using at least WordPress 2.8. It will run faster on WordPress 2.9.1 and later.

[dsIDXpress](http://www.dsidxpress.com/) contains many advanced features that enable bloggers to create "sticky content," visitors to find properties they like, and search engines to crawl the MLS data so that the listings show up with the blogger's domain in the search engines. It is intended to be a real estate agent's / broker's all-inclusive interface between the MLS they belong to and their WordPress site / blog. Following is a very high-level overview of the plugin's functionality.

* It actually embeds the live MLS data INTO the blog -- **it does NOT use HTML "iframes!"**
* It is **extremely easy** to set up, requiring 17.43 seconds of your time on average. It's downloaded and installed like any other WordPress plugin and there's only one field to fill in (the [activation key](http://www.dsidxpress.com/tryit/)) to activate all of the plugin's functionality.
* The plugin is **exceptionally fast**. In some cases, loading the MLS data is actually faster than loading the WordPress data!
* It has fanatical attention to detail, which is reflected in search engine rankings. The HTML that the plugin outputs is semantically correct and is streamlined for speed, the HTML `title` and `meta name="description"` tags are supported (`meta name="description"` through many of the WordPress SEO packs), and the dynamic URLs reflect the content on the page. A large number of simliar details too numerous to mention are built into the core of the plugin.
* It has built-in support for WordPress shortcodes, allowing bloggers to **embed live listing data from the MLS** into their blog posts / pages. Adding / editing these shortcodes is made easy by using the tools that dsIDXpress builds into WordPress's page / post editor.
	* The `idx-listings` shortcode embeds listings for particular areas into a blog post / page. For example, if a blogger typed `[idx-listings city="Laguna Beach" count="10"]` into their post, the 10 newest listings from the MLS in Laguna Beach would show up in place of that text when the post is displayed; each listing / photo links to the full property details. The data is *live*, so whether the post is viewed the next day or the next month, the 10 newest listings would always be displayed.
	* The `idx-listing` shortcode embeds a single listing into a blog post / page. For example, putting `[idx-listing mlsnumber="U8000471"]` into the post would show the LIVE primary information for that MLS #. If the price gets changed, photos get added, the property goes off the market, or otherwise anything at all changes, the data will always reflect the changes from the MLS. A blogger could also use the `showall="true"` option (i.e. `[idx-listing mlsnumber="U8000471" showall="true"]`) to show ALL of the data for that area (extended details and features, price changes, schools, and even a map that will show up in Google Reader).
* It comes with a number of **built-in IDX widgets** that allow bloggers to rapidly start embedding the MLS data into the blog.
	* The **IDX Listings widget** allows the blog owner to show listings within an area (city, community, tract, or zip), show their own listings, show their office's listings, or show listings based on a completely customizable search. The widget can be configured to show up to 50 listings at a time and can be set to show the properties in a list, on a map, or in a detailed slideshow.
	* The **IDX Areas widget** allows the blog owner to display a simple list of links to the different areas (cities, communities, tracts, or zips) they service. This makes it super easy for both website visitors and search engines to view all of the listings in that area.
	* The **IDX Search widget** allows the blog owner to show an MLS search form. The results are displayed as HTML on the user's blog.
* The plugin has a great deal of **intelligent URL handling** functionality built-in. It supports and actively enforces canonical URLs and 301 redirects where appropriate to the functionality of the IDX. The URL structure itself is designed to be clean, simple, and readable.
	* A property URL is in the form of `/mls-<MLS_NUMBER>-address`. For example, the url for MLS # L29755 looks like this: `yourblog.com/idx/mls-l29755-2665_riviera_dr_laguna_beach_ca_92651`. If the address changes, a 301 redirect is issued to the new URL.
	* The search results URL is in the form of `/city/<CITY_NAME>`. Similar to the property URLs, 301 redirects are issued where appropriate to ensure that the base URL is always correct.
	* Canonical URLs are set for every IDX page to ensure search engines know the "true" url for the content -- even when the base URL is correct.
* ... and so much more!

If you'd like to **see the plugin in action**, you can check out our [dsIDXpress demo site](http://www.daniellecordova.com/). If you'd like to read more or purchase this plugin, please take a look at our [dsIDXpress site](http://www.dsidxpress.com/). Finally, if you'd like to obtain a **demo activation key** to use this plugin on your own blog, you can request one on our ["Try It Out!"](http://www.dsidxpress.com/tryit/) page.

*Note: If you're searching for idx press, idxpress, ds idxpress, id xpress, or id express, this is the plugin you're probably looking for.*

== Changelog ==

= 2.1.25 =
* Select template for IDX Pages
* Enable/Disable displaying of open house information
* Select multiple listing status filters when building IDX Pages and links
* Display listing status filters based on MLS capabilities
* Misc bug fixes 

= 2.1.24 =
* New shortcode for adding quick search form to posts/pages
* Updated text editor button icons for shortcodes
* Fixed google maps api included multiple times issue
* Fixed php notices for static method calls

= 2.1.23 =
* Fixing release issue

= 2.1.22 = 
* Fixed issue where some themes would include scripts at bottom of page rather than in the <head>

= 2.1.21 = 
* Fixed issue where .js would sometimes not get included

= 2.1.20 = 
* Updated search widgets
* Fixed issue where listings widgets would sometimes not load

= 2.1.19 = 
* Fixed error in older versions of php

= 2.1.18 = 
* Fixed issue with IDX Page filters not working

= 2.1.17 = 
* Cleaned up minor php warnings
* Fixed issue with non alphanumeric characters in tract names
* Enable additional sort orders

= 2.1.16 =
* Minor admin text changes and error message fixes

= 2.1.15 =
* Removed microformat tags where they conflicted with IDXpress
* Allowed custom IDX pages to use the same WordPress template used for IDXpress Result pages
* Fix issue with certain Admin API calls with large responses

= 2.1.14 =
* Canadian Spam Compliance
* Error message fixes

= 2.1.13 =
* Fixes for IDX Pages
* Thumbnail for plugin gallery
* Misc widget fixes

= 2.1.12 =
* Fix for enter/return issue in Post/Page editor
* Added ability to add content above IDX data in IDX Pages

= 2.1.11 =
* Fixes for IDX Pages

= 2.1.10 =
* Fixes for IDX Pages
* Add Radius search to IDX Pages
* Fix Yoast SEO conflict

= 2.1.9 =
* New feature, IDX Pages. More info at http://www.diversesolutions.com/announcements/idx-pages-feature-added-to-idx-plugin-8198

= 2.1.8 =
* Recent property widget was missing Property Types
* Fix for when the plugin location isn't standard
* Fix for multi-listing short code and small browsers

= 2.1.7 =
* Theme CSS compatibility fixes

= 2.1.6 =
* PHP 5.2 compatibility fix

= 2.1.5 =
* Roll back underscore changes to locations in pretty-urls
* Suppress edit link on idxpress pages

= 2.1.4 =
* Change to Affordability widget to allow property type selection
* WordPress v3.9 compatibility fixes
* Fix for Schedule a Showing request date format
* Fixes for url naming conventions for locations that include non-alpha characters

= 2.1.3 =
* Admin search builder fixes
* Misc style fixes
* Suppress SQFT in quick search if MLS doesn't support data

= 2.1.2 =
* Misc javascript and options fixes

= 2.1.1 =
* Added option for custom mobile domain support
* Responsive admin theme fixes
* Fixed XSS issue

= 2.1.0 =
* Tweaks to support responsive admin theme
* Fixes with script enqueuing
* Fix for more than one-deep sub directory WordPress installs
* Single-sign-on support between dsIDXpress & dsSearchAgent

= 2.0.39 =
* Added Facebook share meta description tag
* Fixes in prep for WordPress v3.8

= 2.0.38 =
* Visual fixes on Recent Properties widget
* Hid some options in admin unavailable to Lite users
* Bumped compatibility to WordPress v3.7

= 2.0.37 =
* Minor shortcode fixes
* Fix for school lookup to be by zip-code
* Added caching for various listing meta lookups
* Added ability to force phone # on registration
* CSS fixes for jquery-ui compatibility
* JS fixes in the linkbuilder tool

= 2.0.36 =
* Changes to print listing

= 2.0.35 =
* Fix for some admin options not saving correctly

= 2.0.34 =
* Fixed autocomplete issue on result search box pages

= 2.0.32 & 2.0.33 =
* Fixed PHP 5.2 compatibility issues in last release

= 2.0.31 =
* WordPress v3.6 compatibility fixes
* Added listing status as a filter option in the admin screen
* Add checkbox options for default property types on the Filters screen
* Prevent the coverage areas widget from breaking on the front-end if no options are set
* Added missing description for the new status type filters

= 2.0.30 =
* Removed numeric check in listings widget for agent/office ids
* Added ListingStatuses and ListingOfficeID to Listing Page Builder

= 2.0.29 =
* Fixed issue introduced in 2.0.28 where sometimes saving options throw an error and deauthorize plugin

= 2.0.28 =
* Fixed issue where sitemap options weren't showing up for BWP Google XML Sitemaps plugin
* Added SalePrice sort options to multi-listings shortcode
* Added option for disabling caching
* We now allow searches from quick-search with no location selected

= 2.0.27 =
* Added school type to schools builder
* Fixed issue where tract names with spaces didn't work in area stats widget
* Fixed merge issue from last deploy where new slideshow options were lost
* Added integration with BWP Google XML Sitemaps plugin for multi-site wp builds

= 2.0.26 =
* Added option to switch between old and new slideshow treatments
* Fixed issue with shortcode and county filtering

= 2.0.25 =

* Added password recovery feature for Visitor accounts
* Compliance changes for MREIS

= 2.0.24 =

* Fixed debug message left in 2.0.23

= 2.0.23 =

* Fix for asset paths for certain WordPress install methods
* Fixed shortcode [idx-listings] parsing where min and max home sizes are not taken into account when specified in the shortcode
* Added description for AgentID and OfficeID to the IDX general settings page

= 2.0.22 =

* Fixed issues with search widget and 3rd party themes
* Added county as an option in listings widget
* Changed state selection to dropdown in State restriction in admin

= 2.0.21 =
* Added Facebook and Twitter sharing meta infos
* Fix for search box widget styles for some themes

= 2.0.20 =
* Fix for PHP 5.2 compatibility

= 2.0.19 =
* Switched home detail page slideshow to new HTML5 slideshow
* Allow multiple cities in map-search widget
* Cleanup of price min/max in search widget
* Fix for PHP 5.2 compatibility

= 2.0.18 =
* Fixed issue with WordPress 3.5 mangling URLs in wp_enqueue when adding &ver= param

= 2.0.17 =
* Fixes to required registration when client is using "lite" package
* Added no-index on detail and result views when using sample data
* Added input validation to some admin values

= 2.0.16 =

* Fixes to detail page options not saving correctly in certain circumstances
* Change to SEO option text to alleviate confusion
* Change to auto-select first location in search-form autocomplete
* Misc visual changes and fixes to widgets

= 2.0.15 =

* Fix for certain result page parameters not working when the value was "0"

= 2.0.14 =

* Added listing status parameter to shortcode

= 2.0.13 =

* Fixes to 404 page and new compliance items

= 2.0.12 =

* Various bug fixes

= 2.0.11 =

* Various bug fixes

= 2.0.10 = 

* Cleared up PHP warning

= 2.0.9 =

* Fixed bug that was causing a warning to show

= 2.0.8 =

* Various bug fixes

= 2.0.7 =

* Various bug fixes

= 2.0.6 =

* Various bug fixes

= 2.0.5 =

* Various bug fixes

= 2.0.4 =

* Misc bug fixes

= 2.0.3 =

* Misc compatibility fixes

= 2.0.2 =

* PHP 5.2 fixes

= 2.0.1 =

* Bug fix in plugin section

= 2.0 =

* Numerous upgrades and a new "pro" tier. See blog post for full notes: http://www.diversesolutions.com/announcements/announcing-dsidxpress-pro-wordpress-idx-with-lead-capture-6441

= 1.1.41a =

* Fixed issue where shortcode and widget admin dialogs would error in a small number of wordpress installs 

= 1.1.41 =

* Support for Google XML Sitemaps v4 (in beta v8 as of commit)

= 1.1.40 =

* Added helpers for third-party tracking

= 1.1.39 =

* Fixed certain types of URLs for non-US MLS's.

= 1.1.38 =

* Fixed bug where notices were being shown and slideshow wasn't working when server was incorrectly configured.
* Fixed slideshow XML so it's syntactically valid and will show up when viewed in a browser.
* Added support for local path issues when WordPress is installed in certain software conditions.

= 1.1.37 =

* Fixed issue where some result URLs with "locations" weren't loading. 

= 1.1.36 =

* Added / updated some links in the intro notification after the initial plugin activation.
* Fixes for widgets for WordPress 3.3.
* Suppressed the disclaimer created by widgets when viewing a dsIDXpress results or details page.

= 1.1.35 =

* Bug Fixes 
* Sort by Price Ascending added to the Listings Widget

= 1.1.34 =

* Fixed bug where attempting to show the disclaimer was causing errors w/ idx-listings shortcode.

= 1.1.33 =

* Prevented printable property PDFs from getting indexed. 
* MLS compliance changes for widgets.

= 1.1.32 =

* Added search location validation to search widget.

= 1.1.31 =

* Fixed issue where result URLs with multiple schools weren't loading. 

= 1.1.30 =

* Fixed issue where some result URLs with multiple filters weren't loading. 

= 1.1.29 =

* Refined the result page loading code so that some non-location based URLs will still show results.

= 1.1.28 =

* Ensured that a location is requsted before a result page URL is served.
* Not found pages now return a 404 HTTP status code when either a) there's no location specified or b) the plugin isn't activated.
* Prevented plugin from activating itself on any URL that starts with "idx" and has other characters after it. 

= 1.1.27 =

* Edge-case roles issue

= 1.1.26 =

* Fixed dsIDXpress virtual pages so that a proper 404 is served when there's no content match. 
* Bug fix for IE shortcode editor.
* Fixed caching bug where keys were not unique enough mixing up result pages.
* Fixed issue with certain details URLs interfering with custom link URLs.

= 1.1.25 =

* Yet another URL compatibility fix for when the site isn't on the domain root 

= 1.1.24 =

* URL compatibility fix

= 1.1.23 =

* URL fix

= 1.1.22 =

* Added pages to the plugin directory to stop crawlers from looking in there and getting errors.
* Added support for alternate URL structures.

= 1.1.21 =

* Fixed bug where a 301 redirect for on a listing results page was dropping the listing sort order.

= 1.1.20 =

* Minor host configuration fixes

= 1.1.19 =

* Fixed compatibility issue for WordPress 3.1.
* Removed jquery.scrollTo dependency and resource as it was sometimes getting clobbered by other poorly written plugins and themes. It's now handled in the API response.
* Added reference to jQueryUI theme CSS hosted on Google CDN as it's used on the details page and wasn't getting included sometimes.
* Removed some dead code.
* Added rewrite rule so that props can also be loaded via an internal property ID for when MLS numbers overlap between MLS's. 

= 1.1.18 =

* Added functionality to implement EULA link on the Search Widget

= 1.1.17 =

* Added support for VOW-like listing details display

= 1.1.16b =

* Descrption correction

= 1.1.16 =

* Added ability to change WalkScore and State Restrictions options
* Added ability to sort custom links in IDX Listings widget

= 1.1.15 =

* Added functionality to 302 (temporary) redirect paged results where there are no longer results to the first page of results.

= 1.1.14 =

* Paging on /idx/ has been disabled to prevent crawlers going where they shouldn't

= 1.1.13 =

* Strip spaces from MLS #'s before making request to server
* Fixed issue with area URLs in area widget being wrong when the area name contained non-alphanumeric characters
* Fixed shortcode insert tool permissions so that non-admins can use it

= 1.1.12 =

* Fixed 404 error with Headway themes (hopefully)
* Fixed issue with results pages where the area name has an apostrophe in it
* Removed the thumbnails beneath the slideshow in favor of using the built-in thumbnails under the description
* Admin performance speedup
* Fixed issue with sitemap not rebuilding when dsIDXpress options were saved
* Fixed issue with area URLs in sitemap being wrong when the area name contained non-alphanumeric characters  

= 1.1.11 =

* Fix for jQuery UI dialogs

= 1.1.10 =

* Fix for /idx/advanced/ no longer working

= 1.1.9 =

* Minor change with the way API requests are made in order to increase the speed in some circumstances.
* Changed some JavaScript to only load when a dsIDXpress-generated page is loaded (instead of on ALL pages).
* Prevent requests to /idx/ from working since the results on that page were showing a lot of properties outside the area and were diluting indexing results.
* Removed unmaintained ThickBox lightbox-style photo viewer in favor of customized ColorBox for better photo viewing experience.

= 1.1.8 =

* Changed language on 404 page
* Fix for tracts in Search Widget

= 1.1.7 =

* Fix for search forms with mls# in them

= 1.1.6 =

* Miscellaneous compatibility fix

= 1.1.5 =

* When more than one dropdown is present in Search Widget, "All" is added to each one

= 1.1.4 =

* Fix for zip codes in search widget
* Fix for empty criteria in search widget

= 1.1.3 =

* Clear the expired items out of dsIDXpress's cache once per day
* Fixed bug where locations with an ampersand in the name weren't displaying listings correctly

= 1.1.2 =

* Fix for caching empty responses

= 1.1.1 =

* Fix for blog paths

= 1.1 =

* Added Contact form to Details page and Buttons in header for calls to action
* Added sharing ability to Twitter, Facebook, Dwellicious, Google Buzz, Email
* Added print / PDF to Details listing
* Added ability to have Community, Tract, Zip and MLS Number to Search Widget
* Added ability to have map auto-open on results pages
* Added tracking to results map being opened or close between pages
* Added icon for results map link
* Improved slideshow look & feel on details
* Added better full-size photos (just click on the photo in the slideshow to see full size photo)
* If account has dsSearchAgent Pro: Added ability to have an "advanced" link in Search Widget which leads to a new page with the dsSearchAgent frame auto generated
* Added ability to set the Template for dsSearchAgent page
* Added Bing Birds-Eye View to Details pages
* Numerous CSS/UI improvements
* Added ability to set first/last name and email in wp-admin (necessary for pdf and contact form, shares values with dsSearchAgent)

= 1.0.6.2 = 

* Fixed shortcode Price History display issue

= 1.0.6.1 = 

* Added fix for wordpress 3.0 beta 1

= 1.0.6 =

* Fixed issue with some text values (cities, communities, etc) in the url not being passed correctly to the API.
* Changed slideshow for compliance reasons w/ some of the more picky MLS's.
* Added option to "Live Listings" results to show the larger photos that show up in the normal results.
* Fixed bug where "Live Listings" results "count" wasn't saving correctly in editor when editing a pre-saved link.

= 1.0.5.2 =

* Changed debuggable-from IP.
* Fixed issue with slideshow not working w/ certain versions of PHP.

= 1.0.5.1 =

* Fixed issue with Live Listings editor not working in Internet Explorer.
* Added fixes to ensure better compatibility with PHP > 5.2 but < 5.3. 

== Installation ==

1. Go to your WordPress admin area, then go to the "Plugins" area, then go to "Add New".
2. Search for "dsidxpress" (sans quotes) in the plugin search box.
3. Click "Install" on the right, then click "Install" at the top-right in the window that comes up.
4. Go to the "Settings" -> "dsIDXpress" area.
5. Fill in the [dsIDXpress activation key](http://www.dsidxpress.com/tryit/).

For a more detailed installation guide, please take a look at the [install wiki](http://wiki.dsidxpress.com/wiki:installing).

**You should read the [getting started guide](http://wiki.dsidxpress.com/wiki:getting-started) after installing this plugin.**

== Screenshots ==

1. Example use of the `idx-listings` shortcode within a blog post.
2. Example use of the `idx-listing` shortcode within a blog post.
3. The IDX Areas widget and the IDX Search widget embedded into the sidebar.
4. The three different modes of the IDX Listings widget embedded into the sidebar.
5. Partial screenshot of the property details with photo slideshow.
6. Partial screenshot of the property results.

== Frequently Asked Questions ==

= How often will the data on my blog be updated? =

With most MLS's, the data will be pushed to your blog every 2 hours. With other MLS's, data updates could take as long as 24 hours.

= Can I use this without being a member of an MLS? =

No. Due to MLS rules, we can only provide activation keys to MLS members.

= Can I use this without an activation key if I'm a member of an MLS? =

No. The data comes from our ([Diverse Solutions'](http://www.diversesolutions.com/)) servers, and we require an activation key before any of the data is released.

= How much will an activation key cost? =

You can find the pricing information on the [dsIDXpress pricing page](http://www.dsidxpress.com/pricing/).

= How do I get more information? =

For more info on this plugin, please check out the [dsIDXpress product page](http://www.dsidxpress.com/). You can also [contact our sales department](http://www.dsidxpress.com/contact/) if you have questions.