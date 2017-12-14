=== Newsletter Sign-Up ===
Contributors: DvanKooten
Donate link: https://dannyvankooten.com/donate/
Tags: newsletter,sign-up,mailchimp,aweber,newsletter signup,checkbox,ymlp,email,phplist,icontact,mailinglist,checkbox,form widget,widget,newsletter widget,subscribe widget,form shortcode,mailchimp api
Requires at least: 3.6
Tested up to: 4.1.1
Stable tag: 2.0.5
License: GPL2

Integrate your WordPress site with 3rd-party newsletter services like Aweber and YMLP. Adds various sign-up methods to your site.

== Description ==

= Newsletter Sign-Up =

This plugin adds various sign-up methods to your WordPress website, like sign-up checkboxes in your comment form and a sign-up form to show in posts, pages or widget areas.


> **MailChimp user?**
>
> Use [MailChimp for WordPress](https://wordpress.org/plugins/mailchimp-for-wp/) instead. It's free & so much better.

This plugin works with *almost all* third-party email marketing services including MailChimp, CampaignMonitor, ConstantContact, YMLP, Aweber, iContact, PHPList and Feedblitz. With the right configuration settings, you can make this plugin work with *any* newsletter service around.

**Features:**

* Add a "sign-up to our newsletter" checkbox to your comment form or registration form
* Easy customizable Newsletter Sign-Up Form Widget
* Embed a sign-up form in your posts with a simple shortcode `[nsu_form]`.
* Embed a sign-up form in your template files by calling `nsu_form();`
* Use the MailChimp or YMLP API or any other third-party newsletter service.
* Works with most major mailinglist services like Aweber, Constant Contact, iContact, etc.
* Compatible with BuddyPress, MultiSite and bbPress.

**More information**

* [MailChimp for WordPress plugin](https://mc4wp.com/)
* [Newsletter Sign-Up for WordPress](https://dannyvankooten.com/wordpress-plugins/newsletter-sign-up/)
* Check out more [WordPress plugins](https://dannyvankooten.com/wordpress-plugins/) by Danny van Kooten
* Follow [@DannyvanKooten](https://twitter.com/DannyvanKooten) on Twitter

= Sign-up checkboxes =
Most of your commenters will be interested in your newsletter as well. This plugin makes it extremely easie for them to subscribe to your mailinglist. With the sign-up checkbox, all they have to do is check it and the plugin will subscribe them to your mailinglist.

You can also add the sign-up checkbox to your WP registration form, your BuddyPress registration form, your MultiSite sign-up forms or your bbPress new topic and new reply forms.

= Sign-up forms =
Easily configure a sign-up form and show it in various places on your website using the sign-up form widget, the `[nsu_form]` shortcode or the `nsu_form()` template function. 

You can set your own messages and even choose to redirect the visitor to a certain page after signing-up. 

== Installation ==

1. Upload the contents of newsletter-sign-up.zip to your plugins directory.
1. Activate the plugin
1. Specify your newsletter service settings. For more info head over to: [Newsletter Sign-Up for WordPress](https://dannyvankooten.com/wordpress-plugins/newsletter-sign-up/)
1. That's all. You're done!

== Frequently Asked Questions ==

= What does this plugin do? =

This plugin adds various sign-up methods to your WordPress website, like a sign-up checkbox at your comment form and a sign-up form to show in various places like your posts, pages and widget areas.

= What is the shortcode to display a sign-up form in my posts or pages? =

`[nsu_form]`

= Where can I get the form action of my sign-up form? =

Look at the source code of your sign-up form and check for `<form action="http://www.yourmailinglist.com/signup?a=asd123"...`. The action attribute is what you need here.

= Where can I get the email identifier of my sign-up form? =

Take a look at the source code of your sign-up form and look for the input field for the email address. You'll need the `name` attribute of this input field, eg: `<input type="text" name="emailid"....`

= Can I let my users subscribe with their name too? =

Yes. Just provide your name identifier (finding it is much like the email identifier) and the plugin will add the users' name to the sign-up request.

= Can I show a sign-up form by calling a function in my template files? =

Yes, use the following code snippet in your theme files to display a sign-up form.

`if(function_exists('nsu_form')) nsu_form();`

For more questions and answers go have a look at the [Newsletter Sign-Up](https://dannyvankooten.com/wordpress-plugins/newsletter-sign-up/) page on my website.

== Screenshots ==

1. The mailinglist configuration page of Newsletter Sign-Up in the WordPress admin panel.
2. The form configuration page in the WP Admin panel.
3. The sign-up checkbox in the Twenty Eleven theme

== Changelog ==

= 2.0.5 - February 19, 2015 =

**Improvements**

- Updated all links to use HTTPS protocol

= 2.0.4 - October 4, 2014 =

* Minor code improvements and WP 4.0+ compatibility.
* Prevent direct file access

= 2.0.3 =
* Fixed broken link to settings pages after widget form
* Improved: better stylesheet loading, encouraged browser caching
* Improved: Some UI improvements
* Improved: better default checkbox CSS
* Improved: better default form CSS

= 2.0.2 =
* Improved: UI improvement, added some HTML5 to settings pages
* Improved: Code improvement
* Improved: Config extractor

= 2.0.1 =
* Fixed: not being able to uncheck "Use HTML 5" on form settings page
* Added: compatibility with bbPress, you can now add a sign-up checkbox to the post new topic and post new reply forms.
* Fixed: not being able to uncheck "add to comment form" in checkbox settings
* Fixed: compatibility with other plugins who use a 'functions.php' file (like GDE).

= 2.0 =
* Fixed: spam comments not being filtered
* Added: HTML 5 form fields (option)
* Added: Validation texts (option)
* Fixed: last update broke template functions
* Added: navigation tabs in back-end

= 1.9 = 
* Improved: Code refactoring, less memory usage
* Improved: Admin panel clean-up
* Fixed: YMLP API
* Added: Translation filters to form shortcode output
* Added: Translation filters to checkbox label
* Added: SPAM Honeypot to sign-up form to prevent bot subscribers
* Removed: paragraph tags around hidden fields

= 1.8.1 =
* Improved: automatic guessing of first and last names.
* Removed backwards compatibility for v1.6 and below
* Removed unnecessary code, options, etc..
* Improved: Code clean-up
* Changed links to show your appreciation for this plugin.

= 1.8 =
* Fixed W3C error because of empty "action" attribute on form tag.
* Added notice for MailChimp users to switch to my newer plugin, [MailChimp for WordPress](https://mc4wp.com/).
* Further improved the CSS reset for the comment form checkbox

= 1.7.9 =
* Improved CSS Reset for comment checkbox

= 1.7.8 =
* Improved enqueue call to load stylesheet on frontend
* Fixed notice after submitting widget form (undefined variable $name)
* Fixed %%IP%% value in widget form
* Added debugging option. When `_nsu_debug` is in the POST or GET data it will echo the result of the sign-up request.

= 1.7.7 =
* Improved Improved HTML output for forms
* Improved code indentation
* Added OnBlur attribute to form input's. Default value now reappears after losing focus (while empty).
* Added replacement value's for additional data (`%%NAME%%` and `%%IP%%`)

= 1.7.6 =
* Fixed: The plugin now works with PHPList again. Thanks ryanjlaw.

= 1.7.5 =
* Fixed: Hidden inputs are now wrapped by a block element too, so the form output validates as XHTML 1.0 STRICT.

= 1.7.4 =
* Added: Ability to turn off double opt-in (MailChimp API users only).
* Improved: Various CSS improvements

= 1.7.3 =
* Fixed: Actual fix for previous two plugin updates. My bad, sorry everone.

= 1.7.2 =
* Fixed: Bug after submitting comment or registration form.

= 1.7.1 =
* Fixed: Bug where you coudln't configure mailinglist specific settings (like MC API).

= 1.7 =
* Added: add subscribers to certain interest group(s) (limited to 1 grouping at the moment). (MailChimp API users only)
* Improvement: Slightly better code readability

= 1.6.1 =
* Fixed notice on frontend when e-mail field not filled in
* Fixed provided values for First and Lastname field for MailChimp when using both.

= 1.6 =
* Improvement: Huge backend changes. Everything is split up for increased maintainability.
* Improvement: Better code documenting
* Improvement: Consistent function names (with backwards compatibility for old function names)
* Improvement: Only load frontend CSS file if actually needed / asked to.
* Added: Added CSS class to text after signing up
* Added: Added option to automatically add paragraph's to text after signing up.
* Added: Added option to set default value for e-mail and name field.
* Added: Option to redirect to a given url after signing-up
* Added: More elegant error handling.
* Fix: "Hide checkbox for subscribers" did not work after version 1.5.1