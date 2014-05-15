=== wp-lazy-load ===
Contributors: dsawardekar
Donate link: http://pressing-matters.io/
Tags: lazy load, jquery lazy load, image lazy load, optimization
Requires at least: 3.5.0
Tested up to: 3.9
Stable tag: 0.1.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress integration with jQuery Lazy Load.

== Description ==

This plugin optimizes your page load performance using the jQuery Lazy
Load. The img src attributes in your posts are replaced with
placeholder tags. The plugin waits for the user to scroll to near
the image before it starts loading the image.

This improves your page speed as only images that are actually viewed by users
are loaded. And your servers will also perform better as they will need
to serve fewer images.

== Installation ==

1. Click Plugins > Add New in the WordPress admin panel.
1. Search for "wp-lazy-load" and install.

###Customization###

The `threshold` parameter controls the distance in pixels that the your
viewers need to be close to to trigger loading of images. Default is 200
pixels.

The plugin uses a transparent gif as the placeholder image by default.
This can be changed by adding a custom placeholder image to your current
themes `wp-lazy-load` subdirectory.

For eg:- if you add `red.gif` to {current_theme}/wp-lazy-load/red.gif
and then change the plugin settings to use `red.gif`.

Then the placeholder for your images will use this red.gif image
instead.

== Screenshots ==

1. Screenshot 1
2. Screenshot 2

== Credits ==

* Thanks to Mika Tuupola for [jQuery lazyload](https://github.com/tuupola/jquery_lazyload)

== Upgrade Notice ==

* WP-Lazy-Load requires PHP 5.3.2+

== Frequently Asked Questions ==

* Can I change the placeholder image?

Yes, see the customization section under Installation.

== Changelog ==

= 0.1.0 =

* Initial Release
