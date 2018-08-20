=== Content Blocks (Custom Post Widget) ===
Contributors: vanderwijk
Tags: widget, sidebar, content block, block, custom, post, shortcode, wysiwyg, wpml, featured image
Requires at least: 4.0
Tested up to: 4.9
Stable tag: 3.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin enables you to edit and display Content Blocks in a sidebar widget or using a shortcode.

== Description ==

The [Content Blocks](http://www.vanderwijk.com/wordpress/wordpress-custom-post-widget/?utm_source=wordpress&utm_medium=website&utm_campaign=custom_post_widget) allows you to display the contents of a specific custom post in a widget on in the content area using a shortcode.

Even though you could use the text widget that comes with the default WordPress install, this plugin has some major benefits:

* The Content Blocks plugin enables users to **use the WYSIWYG editor** for editing the content and adding images.
* If you are using the standard WordPress text widgets to display content on various areas of your template, this content can only be edited by users with administrator access. If you would like **non-administrator accounts to modify the widget content**, you can use this plugin to provide them access to the custom posts that provide the content for the widget areas.
* You can even use the **featured image functionality** to display them in a widget.
* The Content Blocks plugin is **compatible with the WPML** Multi-Language plugin and automatically shows the correct language in the widget area.
* The Content Blocks can be included in posts and pages using the **built-in shortcode functionality**.

This plugin creates a 'content_block' custom post type. You can choose to either display the title on the page or use it to describe the contents and widget position of the content block. Note that these content blocks can only be displayed in the context of the page. I have added 'public' => false to the custom post type which means that it is not accessible outside the page context.

To add content to a widget, drag it to the required position in the sidebar and select the title of the custom post in the widget configuration.

**Includes the following translations:**

* Swedish (sv_SE) by [Andreas Larsson](http://krokedil.se)
* Polish (pl_PL) by [Kuba Skublicki](https://www.linkedin.com/in/kubecki)
* Portuguese (pt_BR) by [Ronaldo Chevalier](http://www.hostmeta.com.br/)
* Czech (cs_CZ) by [Martin Kucera](http://jsemweb.cz/)
* Dutch (nl_NL) by [Johan van der Wijk](http://vanderwijk.nl)

[More translations are very welcome!](https://translate.wordpress.org/projects/wp-plugins/custom-post-widget)

== Screenshots ==

1. After activating the plugin a new post type called 'Content Blocks' is added.
2. The widget has a select box to choose the content block. Click on the 'Edit Content Block' link to edit the selected Content Block custom post.
3. You will find a button above the WYSIWYG editor that allows you to insert the content block using the shortcode.
4. After clicking the 'Add Content Block' button you can select a content block and insert the shortcode in the content area.

== Installation ==

1. First you will have to upload the plugin to the `/wp-content/plugins/` folder.
2. Then activate the plugin in the plugin panel.
You will see that a new custom post type has been added called Content Block.
3. Type some content for the widget. You can choose to either use the title to describe the content on the page, or to display it. Check 'Show Post Title' to display the title on the page.
4. Go to 'Appearance' > 'Widgets' and drag the Content Block widget to the required position in the sidebar.
5. Select a Content Block from the drop-down list.
6. Check the 'Show Post Title' checkbox if you would like to display the title of your Content Block
7. If you are experiencing issues with content being added automatically to your posts (Social media sharing buttons for instance), check the 'Do not apply content filters' checkbox. Use this with caution!
8. Click save.

== Frequently Asked Questions ==

= Why can't I use the default text-widget? =

Of course you can always use the default text widget, but if you prefer to use the WYSIWYG editor or if you have multiple editors and you don't want to give them administrator rights, it is recommended to use this plugin.

= How can I show the content bock on a specific page? =

It is recommended to install the [Widget Logic](http://wordpress.org/extend/plugins/widget-logic/) plugin, this will give you complete flexibility on widget placement.

= How can I display the featured image in the widget? =

This plugin has built-in support for the featured image functionality on the edit screen. Note that featured image will not be resized, so you will have to make sure it is the right size when uploading or restrict the image size via the stylesheet.

= My social sharing plugin adds buttons to all the Content Block areas =

If your social media sharing plugin adds buttons to the widget areas you could check the 'Do not apply content filters' checkbox. Note that when this is done, WordPress will also stop adding paragraph tags to your text, so use this setting with caution. It is much better to ask the developer of the social media sharing buttons plugin to correctly use the content filters (see http://pippinsplugins.com/playing-nice-with-the-content-filter/ for more information on this). If you are embedding your content block with the shortcode, add the following: `suppress_content_filters="yes"`

= The featured image is not displayed when using the shortcode =

Currently the shortcode function only outputs the post content and title of the content block, future support for displaying the attached featured image is being considered.

= I have a feature request =

Please post your feature request on [the support forum](https://wordpress.org/support/plugin/custom-post-widget)
These new features are on the to-do list:

* Display the content block featured image when using the shortcode
* Front-end editing of the content blocks
* Visual Composer integration

= How can I make advanced changes to the widget layout? =

You can create your own widget template and upload this to your theme folder. See [this support topic](http://wordpress.org/support/topic/patch-custom-widget-frontends?replies=1) for more information about this feature.

= Can I make the post type public?  =

You can make the post type public by adding the following code to your theme's functions.php file:
`function filter_content_block_init() {
	$content_block_public = true;
	return $content_block_public;
}
add_filter('content_block_post_type','filter_content_block_init');`

Alternatively you can use [this third-party plugin](http://demomentsomtres.com/english/wordpress-plugins/demomentsomtres-wpbakery-visual-composer-custom-post-widget/).

= Post ID's confuse me, can I use the post slug for embedding a content block? =

Yes, v2.6 now gives you the option to use the content block's url slug in the shortcode. Use the following syntax for doing this: `[content_block slug=my-content-block]`.
Note that if you ever change the slug of a content block, the embedding no longer works. Therefore I recommend using the post ID instead (which never changes).

= Can I specify a custom class for the embedded content blocks? =

By default the shortcode adds a div around the content block with the class content_block. If you like, you can change this class by adding it to the shortcode: [content_block id=198 slug=our-wordpress-plugins class=my-class]

= How can I embed a content block in my template file using php code? =

You can use the do_shortcode function for this: `echo do_shortcode('[content_block id= ]');`

See http://codex.wordpress.org/Function_Reference/do_shortcode for more information on this function.

= The plugin is not working for me =

Please create a support topic in the forum: http://wordpress.org/support/plugin/custom-post-widget
DO NOT click the 'Broken' button in the compatibility area of the plugin directory before creating a support ticket. It is very demotivating for me to see the plugin downloads drop dramatically without being given the chance to help you!

= I love your plugin! What can I do to help? =

Creating and supporting this plugin takes up a lot of my free time, therefore I would highly appreciate it if you could take a couple of minutes to [write a review](http://wordpress.org/support/view/plugin-reviews/custom-post-widget). This will help other WordPress users to start using this plugin and keep me motivated to maintain and support it. Also, if you have a twitter, Facebook or Google+ account, it would be fantastic if you could share the link to this plugin!

== Changelog ==

= 3.0.3 =
Added the option to show the featured image in when using the shortcode. Add featured_image=yes to the shortcode to show it: `[content_block featured_image=yes]`. By default the medium image size is displayed, you can change it like this: `[content_block featured_image=yes featured_image_size=full]`.

= 3.0.2 =
Tested for WordPress 4.8 compatibility.

= 3.0.1 =
Added html sanitizing to the Content Block post titles. Thanks to @palpatine1976 for bringing this to my attention.

= 3.0 =
Renamed the plugin to Content Blocks to better reflect the purpose.

= 2.9.2 =
Minor layout change to the Shortcodes meta box.

= 2.9 =
Removed the admin notice, added donation link to plugin overview page. Shortcodes are now displayed on the content block edit screen, thanks to [Remkus](https://forsite.media/) for suggesting this feature.

= 2.8.5 =
Fix for minor compatibility issue when using the Slider Revolution plugin

= 2.8.4 =
You can now optionally show the post title when using the shortcode by adding `title=yes` to the shortcode

= 2.8.3 =
Added Hebrew translation as provided by Daniel Cohen Gindi

= 2.8.1 =
Updated language files.

= 2.8 =
Added the option to suppress the content filters when using the shortcode. Thanks to adelval for providing the code, see https://wordpress.org/support/topic/add-option-to-not-apply-content-filters-in-shortcode?replies=4

= 2.7.9 =
Changed method for creating the widget in preparation of PHP7 support.

= 2.7.7 =
Added Czech translation.

= 2.7.6 =
Added Italian translation.

= 2.7.5 =
Updated branding and language files.

= 2.7.4 =
The shortcode now includes the slug of the content block (which is still optional), see https://wordpress.org/support/topic/shortcodes-using-slug-name-rather-than-id for more information on this.

= 2.7.2 =
The post status is now used for displaying the content blocks in the widget areas, only published content blocks are now displayed. Thanks to tmbdesign.co.uk for suggesting this change.

= 2.7 =
Added dismissable support notification.

= 2.6 =
Added the possibility for using the content block slug in the shortcode.

= 2.5.7 =
Changed the way the button above the content editor is inserted. For background see: http://wordpress.org/support/topic/soliloquy-conflict-1?replies=2

= 2.5.3 =
Added filter to 'public' value of the content_block post type to make it possible to use the Visual Composer plugin. See support topic: http://wordpress.org/support/topic/make-post-type-public

= 2.5.2 =
Changed require to require_once in custom-post-widget.php to solve a possible issue reported by John Sundberg.
Updated SE translation files as provided by Andreas Larsson.

= 2.5 =
Added 'Content Block Information' to overview page, updated styling to reflect new 3.8 interface and rewrote javaScript code for inserting shortcode tag

= 2.4.5 =
2.4.5 javaScript fix for inserting shortcode tag

= 2.4.4 =
Added unique ID to each content block that is inserted using the shortcode functionality

= 2.4.3 =
Changed the way the shortcode button above the editor is called.

= 2.4 =
You can now add a description to the content block to make it easier for content editors to find out where the block is displayed on the site. Thanks to Andreas Larsson the plugin now includes a Swedish translation.

= 2.3.5 =
Added the option to use your own widget template file as suggested by flynsarmy. See http://wordpress.org/support/topic/patch-custom-widget-frontends?replies=1 for more information.

= 2.3.4 =
Added pt_BR translation files as kindly provided by Ronaldo Chevalier.

= 2.3.2 =
Removed hardcoded plugins folder path

= 2.3.1 =
Removed call to CUSTOM_POST_WIDGET_DIR

= 2.3 =
Various bugfixes and improvements.

= 2.0.3 =
Replaced the deprecated `media_buttons_context` filter with `media_buttons`. Thanks to Baptiste Gaillard for letting me know about this.

= 2.0.2 =
Small fix for issue with the css filepath of the editor icon. Thanks to user zudobug for reporting this issue.

= 2.0.1 =
Updated Dutch translation file.

= 2.0 =
Fix for bug caused by the 'Advanced Custom Fields' plugin. Thanks to creativexperience for troubleshooting this issue.
Cleanup of lightbox popup, changed all instances of query_posts to get_post and get_posts.
Support for featured image in custom post widget area.
Changed shortcode button to reflect the new admin style of WordPress version 3.5.

= 1.9.8 =
Fix for error when using shortcode with the WPML plugin

= 1.9.7 =
Added featured image support and now using query_posts instead of get_post

= 1.9.6 =
Fixed debug notices when dragging a new content block to the widget areas and removed the add content block shortcode from the content block editing screen.

= 1.9.5 =
Added the option to disable apply_filters on the content to prevent issues with misbehaving plugins. I would have rather not added this, but it appears many plugin developers do not know how to properly use filters (see http://pippinsplugins.com/playing-nice-with-the-content-filter/).

= 1.9.4 =
Corrected a minor bug regarding translation strings.

= 1.9.3 =
Minor bugfix and added the French translation which was created by Alexandre Simard (http://brocheafoin.biz/).

= 1.9.2 =
Now includes Polish language files as created by Kuba Skublicki.

= 1.9 =
The content blocks can now be translated using the WPML plugin, thanks to Jonathan Liuti (http://unboxed-logic.com/).
Thanks to Vitaliy Kaplya (http://www.dasayt.com/) a Russian translation has been added to the plugin.

= 1.8.6 =
Minor bugfix for edit link in widget.

= 1.8.5 =
This release is to fix an issue with the WordPress plugin repository.

= 1.8.4 =
Added edit content block link to the widget editor and changed the 'view content block' message to include a 'manage widgets' link. The 'Draft' and 'Preview' buttons are now hidden via CSS, hopefully this will soon be default WordPress behaviour (see related ticket: http://core.trac.wordpress.org/ticket/18956).
Thanks to Julian Gardner-Hobbs (http://www.hobwebs.com/) for requesting this functionality.

= 1.8.3 -> rolled-back because of some reported issues with social media icons being added to the widget areas =
The widget now emulates the $post loop. This means you can now make use of WordPress functionality such as inserting a [gallery]. Thanks to Jari Pennanen for providing the code.

= 1.8.2 =
Updated German translation and various bugfixes.

= 1.8 =
Added a button above to content editor to make it easier to add the shortcode (no need for looking up the id).

= 1.7 =
This release fixes all the debug error messages Yoast discovered when [reviewing this plugin](http://yoast.com/wp-plugin-review/custom-post-widget/). As requested by Tony Allsopp the option of using the shortcode [content_block id= ] to pull in the content of a content block in a page or post has been added.

= 1.6 =
The Custom Post Widget plugin is now using the more efficient get_post instead of query_posts to display the content block on the page. A code example for this change has been graciously provided by Paul de Wouters.

= 1.5 =
Thanks to Caspar Huebinger the plugin now has its own icon and as requested by Stephen James the author field has been added to the Content Block edit screen.

= 1.4 =
The plugin has been translated into Dutch and German. Hat tip: Caspar H&uuml;binger - glueckpress.com

= 1.3 =
Now the title of the content block is displayed in the admin interface to make it easy to manage the widgets.

= 1.2.1 =
The widget title now uses $before_title and $after_title to generate the appropriate tags to display it on the page. Hat tip: Etienne Proust.

= 1.2 =
Added a checkbox in the widget to make it possible to show the custom post title in the widget area

= 1.1.1 =
Added showposts=-1 to the post query to display more than 10 custom posts in the widget configuration select box.

= 1.1 =
Fixed screenshots for plugin directory

= 1.0 =
First release
