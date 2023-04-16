=== Paid Memberships Pro - ConvertKit Integration ===
Contributors: strangerstudios, kimannwall, nathanbarry, growdev, paidmembershipspro
Tags: convertkit, email, marketing, pmpro, pmp, paid memberships pro
Requires at least: 5.2
Tested up to: 6.2
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin integrates ConvertKit with Paid Memberships Pro.

== Description ==

[ConvertKit](https://convertkit.com) makes it easy to capture more leads and sell more products. This plugin subscribes and tags your Paid Memberships Pro members in ConvertKit. The plugin also adds purchase data to the subscriber for their initial membership checkout in Paid Memberships Pro.

== Installation ==

= Download, Install and Activate! =
1. Go to Plugins > Add New to find and install ConvertKit Paid Memberships Pro Integration.
2. Or, download the latest version of the plugin, then go to Plugins > Add New and click the "Upload Plugin" button to upload your .zip file.
3. Activate the plugin.

= Complete the ConvertKit Integration Setup =
Go to Settings > PMPro ConvertKit in the WordPress admin to begin setup.

1. Enter your ConvertKit API key, which you can find [here](https://app.convertkit.com/account/edit).
2. To add purchase data for subscribers, enter your ConvertKit Secret API key.
3. Save your settings.
4. Then, adjust the dropdown fields to select a tag for subscribers who sign up for each Membership Level.
5. Save your settings.

== Frequently Asked Questions ==

= Does this plugin require a paid service? =

Yes, for it to work you must first have an account on ConvertKit.com

== Screenshots ==

1. Settings > PMPro ConvertKit page in the WordPress admin: enter your API and API Secret Keys; Map Tags to Membership Levels.
2. Example ConvertKit Subscriber with Purchase data for their initial membership checkout order.

== Changelog ==
= 1.2 - 2022-06-09 =
* FEATURE: Added tag settings for non-members. This will apply a tag when a member's membership level is removed - when a member signs up for a level it will remove these non-member tags in ConvertKit.
* ENHANCEMENT: Added filter `pmpro_convertkit_subscribe_fields` to allow passing custom fields to ConvertKit when tagging a member.
* ENHANCEMENT: Added filter `pmpro_convertkit_subscribe_tags` to allow adjusting of what tags are removed when a user is subscribed.
* ENHANCEMENT: Added filter `pmpro_convertkit_unsubscribe_tags` to allow adjusting of what tags are removed when a user is unsubscribed.
* ENHANCEMENT: Added a log file when 'CK_DEBUG' is defined. Filter the location where the log file is stored by using the `pmprock_logfile` filter.
* ENHANCEMENT: Updated UI slightly to show/hide the option "Require Opt-In Label" based on the required "Require Opt-In" checkbox field.
* ENHANCEMENT: Sync member information to ConvertKit data whenever the member's email or first name is changed via the WordPress dashboard.
* REFACTOR: Refactored various methods to improve stability of storing ConvertKit meta data.

= 1.1 - 2021-06-21 =
* FEATURE: Updated opt-in settings. You can edit the label shown on the opt-in message at checkout.
* FEATURE: Added purchase tracking for each level in ConvertKit.
* ENHANCEMENT: Added assets for GitHub and WordPress.org.
* ENHANCEMENT: Added field to capture API Secret Key.
* ENHANCEMENT: Added Purchases tracking for new membership checkouts.
* ENHANCEMENT: Added filter `pmpro_convertkit_change_membership_level_remove_tags` to allow optionally removing tags on level change when the previous level is passed to the pmpro_after_change_membership_level function.
* BUG FIX: Email addresses and names are now properly URL encoded when passed to ConvertKit.
* REFACTOR: Refactored API method for retrieving tags and tagging subscribers.

= 1.0.2 =
* Fixed PHP shorttag that was causing PHP parse error.

= 1.0.1 =
* Fixed PHP shorttag that was causing PHP parse error.

= 1.0 =
* Initial release


== Upgrade notice ==

None.
