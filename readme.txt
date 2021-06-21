=== Plugin Name ===
Contributors: nathanbarry, growdev
Donate link: http://convertkit.com/
Tags: convertkit, email, marketing, pmpro, pmp, paid memberships pro
Requires at least: 4.0
Tested up to: 5.7.2
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin integrates ConvertKit with Paid Memberships Pro.

== Description ==

[ConvertKit](https://convertkit.com) makes it easy to capture more leads and sell more products. This plugin makes it a little bit easier for those of us using
 Paid Memberships Pro to subscribe and tag customers that signup for memberships.

== Installation ==

1. Upload `convertkit-for-paid-memberships-pro` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the settings page by clicking on the link under the plugin's name
4. Enter your ConvertKit API key, which you can find [here](https://app.convertkit.com/account/edit), and save the settings
5. Select a tag to add to customers who signup for each Membership Level
6. Save your settings


== Screenshots ==

-none yet-

== Frequently asked questions ==

= Does this plugin require a paid service? =

Yes, for it to work you must first have an account on ConvertKit.com

== Changelog ==

= 1.1 - 2021-06-21 =
* FEATURE: Updated opt-in settings. You can edit the label shown on the opt-in message at checkout.
* FEATURE: Added purchase tracking for each level in ConvertKit.
* ENHANCEMENT: Added assets for GitHub and WordPress.org.
* BUG FIX: Email addresses and names are now properly URL encoded when passed to ConvertKit.
* REFACTOR: Updated how the CK API integration works.

= 1.0.2 =
* Fixed PHP shorttag that was causing PHP parse error.

= 1.0.1 =
* Fixed PHP shorttag that was causing PHP parse error.

= 1.0 =
* Initial release


== Upgrade notice ==

None.
