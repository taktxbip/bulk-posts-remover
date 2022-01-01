=== Plugin Name ===
Contributors: evnomad
Donate link: https://ko-fi.com/evnomad
Tags: bulk, posts remover, cpt remover, custom posts types
Requires at least: 4.7
Tested up to: 5.8
Stable tag: 1.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A bulk posts remover tool. Easily remove thousands of posts with few clicks.

== Description ==

Bulk posts remover allows you to remove thousands of posts, custom posts, attachments by few clicks.

You can set post type and dates to define what posts will be removed.

Time estimate is available during the process.

Watch logs in realm time to see what posts were removed.

#### How does this work?

The plugin sends an AJAX request to get all IDs matched. After confirmation, according to chunk size, it removes these posts by IDs using AJAX requests.

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/bulk-posts-remover` directory, or install plugin through the WordPress plugins screen directly.
2. Activate plugin through `Plugins` screen in WordPress Admin Panel.
3. Use `Tools -> Bulk Posts Remover` screen to start coding.

== Frequently Asked Questions ==

= Why was this plugin created? =

I created it for personal use, but since it is very handy, I decided to make it public.

== Screenshots ==

1. Confirmation modal before removing process starts
2. Removing process
3. Settings tab

== Changelog ==

= 1.0 =
* First version. Filters by post type, date. Settings: chunk size.

== Upgrade Notice ==

= 1.0 =
This is the first public stable version.