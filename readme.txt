=== Paid Memberships Pro - Address For Free Levels Add On ===
Contributors: strangerstudios
Tags: pmpro, paid memberships pro, ecommerce
Requires at least: 4
Tested up to: 5.8
Stable tag: 0.5

Show address fields for free levels also with Paid Memberships Pro

== Description ==

Show address fields for free levels also with Paid Memberships Pro

== Installation ==

1. Upload the `pmpro-address-for-free-levels` directory to the `/wp-content/plugins/` directory of your site.
1. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= I found a bug in the plugin. =

Please post it in the issues section of GitHub and we'll fix it as soon as we can. Thanks for helping. https://github.com/strangerstudios/pmpro-address-for-free-levels/issues

= I need help installing, configuring, or customizing the plugin. =

Please visit our premium support site at https://www.paidmembershipspro.com for more documentation and our support forums.

Please Note: This plugin is meant as a temporary solution. Most updates and fixes will be reserved for when this functionality is built into Paid Memberships Pro. We may not fix the pmpro-addon-packages plugin itself unless it is critical.

== Changelog ==
= 0.5 - 2021-08-03 =
* ENHANCEMENT: Strings wrapped for localization.
* BUG FIX/ENHANCEMENT: Fixed issue where the "Billing" label in the heading wasn't being hidden properly for free levels.
* BUG FIX: Fixed issues with session handling when using PayPal Express/etc. Fields were not being saved on some sites.

= .4 =
* SECURITY: Sanitizing the billing fields before adding them to free orders.
* BUG FIX/ENHANCEMENT: Using pmpro_checkout_before_change_membership_level to update user meta to support gateways like PayPal Standard.
* ENHANCEMENT: Updated for some WordPress Coding Standards.

= .3.3 =
* BUG FIX: Fixed issue where name fields weren't updating WordPress when PayPal Express was used.

= .3.2 =
* BUG: Fixed a warning.

= .3.1 =
* BUG: Now filtering required billing fields after gateway actions.
* ENHANCEMENT: Now using pmpro_require_billing_fields filter instead of pmpro_required_user_fields which doesn't require fields for existing members.
* BUG: Now storing billing fields in session for Twocheckout as well.

= .3 =
* BUG: Forcing billing address fields to be included for PayPal Express, PayPal Standard, and Twocheckout. Since PMPro v1.8 these fields are not rendered for these gateways. (Thanks, many folks.)
* ENHANCEMENT: Hiding the billing fields on the review page if using PayPal Express, PayPal Standard, or Twocheckout.

= .2.1 =
* BUG: Fixed issue where existing users checking out via PayPal Express wouldn't have their data updated.
* BUG: Avoiding potential warnings when unsetting SESSION vars.

= .2 =
* Added support for PayPal Express and friends by storing address fields in $_SESSION.

= .1 =
* Initial release.
