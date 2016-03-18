=== Paid Memberships Pro - Address For Free Levels Add On ===
Contributors: strangerstudios
Tags: pmpro, paid memberships pro, ecommerce
Requires at least: 3.5
Tested up to: 4.3.1
Stable tag: .3.1

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

Please visit our premium support site at http://www.paidmembershipspro.com for more documentation and our support forums.

Please Note: This plugin is meant as a temporary solution. Most updates and fixes will be reserved for when this functionality is built into Paid Memberships Pro. We may not fix the pmpro-addon-packages plugin itself unless it is critical.

== Changelog ==
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