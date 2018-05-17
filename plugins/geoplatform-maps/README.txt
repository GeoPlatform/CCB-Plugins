=== GeoPlatform Maps Plugin ===
Contributors: imagemattersllc
Tags: map, maps, GeoPlatform, Geoplatform, geoplatform, Shortcode, shortcode, Interactive, interactive, leaflet, client-api, mapcore, ngpi
Requires at least: 4.6.4
Requires PHP: 7.2.0
Tested up to: 4.9.5
Stable tag: 1.0.6
Plugin URI: https://www.geoplatform.gov/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Manage your own personal database of GeoPlatform interactive maps and use shortcode to insert them into your posts.

== Description ==

The GeoPlatform Maps Plugin is a tool that integrates the power of the GeoPlatform Maps Service with your blog. It provides you with the ability to easily compile and manage a local database of maps pulled from the GeoPlatform service. This database also generates shortcodes, which can be used to easily inject rich, layered, interactive maps into your posts. This plugin was made with use within the GeoPlatform 2018 Theme, but will work perfectly fine within whichever theme you wish to use.

This plugin makes use of the [GeoPlatform Maps Service](https://maps.geoplatform.gov/). You can feel free to use any of the maps already hosted there, or register an account to create your own. Once you find a map that is to your liking you will need the map ID, which can be found as part of the URL in the displayed area shown when "Show Embed" is selected. GeoPlatform Map IDs can also be taken from the address bar of maps in the GeoPlatform Map Viewer, accessed by selecting the interactive map to view in detail, but this will not work with other map formats.

Upon installation, the GeoPlatform Maps Plugin settings page can be accessed from from the Dashboard through either the Installed Plugins page or within the Settings menu. Adding maps to your own database is a simple as pasting the map ID of the desired map within the input map ID text field and pressing the Add Map button. If the map ID is valid, a new map will be added to your database! You can also optionally add height and width values to the map when displayed on your post.

Each map added to your database generates a shortcode. All you need is to copy this shortcode and paste it within your posts to add an interactive map! Each map will restrict itself to any custom height or width values you had set it to, but by default will expand itself to fit the region in which it was inserted.

These maps can invoked anywhere you wish to place them, be they in your blog posts or sidebar widgets! Additionally, GeoPlatform Maps come with added features in the form of the ability to view and toggle individual layers, and visit their pages in the GeoPlatform Object Editor for more details on them.

== Installation ==

**From the Marketplace**

1. Navigate to "Plugins > Add New."
2. Search for the plugin "Geoplatform Maps."
3. Select "Install" and then "Activate."

**Manual Installation Through GUI**

1. Navigate to "Plugins > Add New."
2. Select "Upload Plugin" and then "Browse..."
3. Navigate to the plugin's zip file and select it.
4. Select "Install Now" and then "Active."

**Manual Installation Through File Structure**

1. If you possess this plugin in a zipped format, unzip it.
2. Using an FTP client or command line, connect to your Wordpress server.
3. Copy the plugin's unzipped file structure into your remote plugins directory.
4. Navigate to "Plugins > Installed Plugins" and activate it.


== Frequently Asked Questions ==

= I've installed the plugin, where can I get maps? =

All of your maps can be found at [GeoPlatform Maps Service](https://maps.geoplatform.gov/). You will need the map ID, which is a hexidecimal string that can be found in the embed information for your desired map.

= Why can't I add my map to my database? =

The error response should tell you most of what you need to know. Map IDs have a set format, and must exist on the GeoPlatform server if they are to be added. It is also possible that tried to add an AGOl map using the map ID in the address bar. Because of the way AGOL maps are handled, this will not function properly.

= My map is a screenshot with no layer options! What is happening? =

Currently, the GeoPlatform Maps Plugin does not parse AGOL map information, so a still image is all that is possible. This feature may be coming in a future update.

= I have an interactive map, but where are my layer options? =

The GeoPlatform Maps Plugin will not show the layer menu if the map is too small. If you have a custom width or height value set for your map, try increasing them. If the layer menu is still not visible, try switching to a different theme.

= I added my map to the database, but I'm getting an error when I view it in a post! =

If you were able to successfully add a map to your database but not to your post, it is most likely due to an error incorporating the shortcode. Take another look at the shortcode and copy it over again if need be.


== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0.6 =
* Initial release.

== Upgrade Notice ==

Initial release.
