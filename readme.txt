=== Custom Inspect Elements ===
Contributors: giuse
Donate link:
Tags: inspect elements,jokes
Requires at least: 4.6
Tested up to: 5.5
Stable tag: 0.0.4
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

If users who are not logged in have the devstool open, Custom Inspect Elements will not show them the page but a specific content that you can define using the custom post type Inspection Content.

== Description ==

If users who are not logged in have the devstool open, Custom Inspect Elements will not show them the page but a specific content that you can define using the custom post type Inspection Content.

You will find the custom post type Inspection Content on the main admin menu.

Some users who are foxier will find a way to see the content of the page. Consider this plugin as a tool to stop most of the users, but not all of them.

Be careful, it seems Google Bot fetches the website content without problems, but it looks some online tools (e.g. GTmetrix, and Google Page Speeds Insights) trigger the devstool detection.

Checking on Google Search Console it looks the plugin causes no problems regarding the SEO, but cause the active installations are very few, there are not enough tests on the field to exclude any kind of problem with all search engine crawlers.

If you use this plugin, check your SEO, and if you suspect any problem due to this plugin, don't hesitate to open a thread on the support forum.






You can see how it works on <a href="https://josemortellaro.com/">my website</a>. Just inspect elements.


== Installation ==

1. Upload the entire custom-inspect-elements folder to the `/wp-content/plugins/` directory or install it using the usual installation button in the Plugins administration page.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. All done. Good job!


== Frequently Asked Questions ==

Are the users really not able to inspect elements? Most of users will have problems, but there are some tricks that some users may know to get the content.

== Changelog ==

= 0.0.4 =
* Removed: Code for testing purposes

= 0.0.3 =
* Added: Possibility to hide the page content if JavaScript is disabled (to be enabled in wp-config.php)

= 0.0.2 =
* Fixed: not working on Firefox

= 0.0.1 =
* Initial release