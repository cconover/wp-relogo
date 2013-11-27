=== Relogo ===
Contributors: cconover
Donate link: https://christiaanconover.com/code/wp-relogo#donate
Tags: logo, relogo, graphics, head
Requires at least: 3.5.2
Tested up to: 3.7.1
Stable tag: 0.4.1
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Relogo allows you to easily add support for the rel="logo" spec as described at relogo.org.

== Description ==

Relogo lets you easily add support for the rel=”logo” tag to your site. It operates in accordance with the specification published on [relogo.org](http://relogo.org).

Using this plugin you can easily add the tag to your site without having to edit any code, and without needing to change your theme’s files. Just provide the URL for your logo’s SVG file, and you’re done.

== Installation ==

1. Upload the `wp-relogo` directory to the `wp-content/plugins` directory
2. Activate the plugin through the `Plugins` menu in WordPress
3. Adjust the settings on the `Relogo` Settings page

== Frequently Asked Questions ==

= What file formats can I use? =

The rel="logo" spec requires the use of SVG (.svg) image files.

== Screenshots ==

1. Settings page for Relogo.

== Changelog ==

= 0.4.1 =
* Display admin notice if the tag is not set to Active. Version 0.4.0 adds field, but is off by default and user is not made aware.

= 0.4.0 =
* Added toggle for making rel="logo" tag active. Allows adjustment of SVG URL before showing the tag.

= 0.3.0 =
* Display HTML img tag on Options page for users to display their Relogo elsewhere
* Allow uploading SVG files to WordPress media library

= 0.2.0 =
* Validate URL in Options. Check for a valid protocol (HTTP or HTTPS) and a .svg file

= 0.1.0 =
Initial release.