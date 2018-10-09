=== GeoPlatform Community Core Bundle (CCB) ===

-Contributors: imagemattersllc, kevins713, lheazel
-Requires at least: WordPress 4.6.4
-Tested up to: WordPress 4.9.6
-Stable tag: 3.0.5
-Version: 3.2.2
-License: Apache 2.0
-License URI: http://www.apache.org/licenses/LICENSE-2.0
-Tags: two-columns, left-sidebar, right-sidebar, grid-layout, custom-background, custom-colors, custom-header, custom-menu, featured-images, full-width-template, theme-options, custom-logo, editor-style, translation-ready, education

The GeoPlatform Community Core Bundle (CCB) theme is the core theme for WordPress sites hosted by GeoPlatform.gov.

== Description ==

GeoPlatform CCB offers a number a features not common for WordPress themes, including:

* Enhanced user capabilities for Editors, Authors, Contributors, and Subscribers
* Integration with other GeoPlatform.gov plugins and services
* Page Categories and Tags
* Banner WYSIWYG editor for front page, posts, and pages
* Front Page Categories

**Enhanced Capabilities**

This theme assigns higher capabilities to the default WordPress roles to facilitate broader community-based contribution and collaboration while supporting access to restricted content. Default WordPress roles can be found here https://codex.wordpress.org/Roles_and_Capabilities. The enhanced capabilities are noted in the table below (enhanced roles are prefixed with "GP-").

| Capability               |GP-Editor|GP-Author|GP-Contributor|GP-Subscriber|
|--------------------------|:-------:|--------:|-------------:|------------:|
|**edit_theme_options**    | X       |         |              |             |
|**list_users**            | X       |         |              |             |
|**edit_dashboard**        | X       |         |              |             |
|**customize**             | X       |         |              |             |
|**edit_pages**            | X       | X       |              |             |
|**edit_published_pages**  | X       | X       |              |             |
|**publish_pages**         | X       | X       |              |             |
|**delete_pages**          | X       | X       |              |             |
|**delete_published_pages**| X       | X       |              |             |
|**edit_private_posts**    | X       | X       |              |             |
|**read_private_posts**    | X       | X       | X            |             |
|**edit_private_pages**    | X       | X       |              |             |
|**read_private_pages**    | X       | X       | X            |             |
|**upload_files**          | X       | X       | X            |             | |

**GeoPlatform Integration**
**Map Gallery**
Using the Customizer, you can add a Map Gallery, created with the GeoPlatform "Map Manager" application (https://maps.geoplatform.gov), into the front page. Follow these steps to add a map gallery into the theme:

 1. Create a Map Gallery with the Map Manager application (https://maps.geoplatform.gov).
 2. When viewing the gallery, the URL will have the Gallery ID at the end of it. Example https://maps.geoplatform.gov/galleries/ **[your Gallery ID]**.
 3. Copy the ID of the gallery.
 4. Sign into your WordPress site as an Administrator or Editor user, open the Customizer, and go to the **Map Gallery** section under *GeoPlatform Controls**.
 5. Paste your Gallery ID in the text box, and add a fully resolvable URL link to the front of it. Example: https://ual.geoplatform.gov/api/galleries/ **[your Gallery ID]**.

 If your gallery doesn't show up, try hitting "Publish" in the Customizer. If it still does not, make sure the you've got the correct Gallery ID or otherwise please contact us at servicedesk@geoplatform.gov to troubleshoot the issue.

**Map Plugin**
To dynamically add single "Open Maps" from the GeoPlatform Map Viewer (https://maps.geoplatform.gov) or other applications, you can use the GeoPlatform [Maps Plugin](https://wordpress.org/plugins/geoplatform-maps/), available on the Wordpress Marketplace. See the link provided there for documentation and usage guidance.

**Page Categories and Tags**
Like Posts, Pages can also be assigned to one or more Categories and Tags with this theme. This works the same as adding a category or tag to a Post.

**Banner WYSIWYG editor**
You can edit the main banner on the front page, and for all Posts, Pages, and Category Pages.

* To edit the **Front Page** banner, navigate to the **Customizer -> Banner Area**. It uses the TinyMCE WYSIWYG editor, just like posts and pages. It also has a **Call to Action Button** that goes right below the content, that you can enable in that Customizer area.
* **Posts** and **Pages** have another editor area right below the main content area called **"Banner Area Custom Content"**. Anything put in there will show up in that **Post** or **Pages** banner.
* **Category Pages** have a **"Description"** area. Anything put in there will show up in the Category page's banner.
**Front Page Categories**
The Front Page of this theme is designed to showcase Categories of the site. Each Category will appear with a default image on the front page. To change the Featured Image of each Category, navigate to the area where you can edit Categories (**Posts/Pages->Categories->[Your Category]**). The image will appear in the Banner of that category as well as the Front Page card of that Category.

The Front Page organizes categories by name in ascending order, and show the first 12 categories in that organization structure. You can cycle through the categories with a **"More/Previous Categories"** Links below the cards. Later updates will incorporate dynamically being able to change the organization of these.

== Frequently Asked Questions ==

* What is the GeoPlatform Community Core Bundle?

The Community Core Bundle (CCB) enables independent development of a self-contained community website (i.e., a "Community Space") that operates on the infrastructure of the GeoPlatform. More info about CCB's and GeoPlatform can be found [here.](https://www.geoplatform.gov/geoplatform-help/ccb/ccb-getting-started/#what_is_a_ccb)

* Does this theme need to be active for other GeoPlatform plugins to work?

No, GeoPlatform plugins work independently of the theme.

* The dropdown menu no longer functions.

This is most likely due to conflicting uses of the Bootstrap library. The GeoPlatform CCB theme utilizes Bootstrap for some of its operations, and loading that asset with a plugin as well can cause conflicts. It is advised to avoid using that plugin.


== Copyright ==

GeoPlatform-CCB for use with GeoPlatform.gov products and general use.
Copyright (C) 2018 Image Matters LLC

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

(http://www.apache.org/licenses/LICENSE-2.0)

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

GeoPlatform CCB Theme incorporates code from [Categories Images plugin](https://wordpress.org/plugins/categories-images/), Copyright 2018 Muhammad Said El Zahlan.
Categories Images plugin is distributed under the terms of the GNU GPL2

GeoPlatform CCB Theme incorporates code from [Paulunds Category Image Description](https://paulund.co.uk/add-tinymce-editor-category-description), Copyright 2014 Paulund. Category Image Description is distributed under the terms of the GNU GPL2

GeoPlatform CCB Theme incorporates code from the [Showcase Theme](https://catapultthemes.com/downloads/showcase-easy-digital-downloads-theme/), originally derived from Gareth's [Blog post on the subject](https://catapultthemes.com/adding-an-image-upload-field-to-categories/) at Catapult Themes. Copyright 2018 Catapult. Showcase Theme is distributed under the terms of the GNU GPL2

GeoPlatform CCB bundles the following third-party resources:

Bootstrap (3.3.7). Copyright 2018 Twitter.
**License:** MIT
Source: (https://getbootstrap.com/docs/3.3/)

== Changelog ==

 = 3.2.2 =
* Release October 9, 2018

 - Added the appearance of sub-categories in a parent category's page. This feature trickles down to child themes.

 = 3.2.1 =
* Release September 17, 2018

 - Added the ability to sort posts, pages, and category links by their priority values in their admin pages.
 - Added additional validity checks for Map Gallery URIs. These changes trickle down to child themes as well.
 - Modified instructional verbiage for Priority inputs and Category Links.

 = 3.2.0 =
* Release September 14, 2018

 - Added Category Links to the theme. This new post type can be inserted into Categories to provide links to external web sites.
 - Category Links support excerpts, categories, and featured images. They also leverage priority sorting and accept a redirect URL input.
 - When assigned to a category, a Category Link will appear like a post, but the More Information button will redirect to the assigned external website.

 = 3.1.10 =
* Released September 13, 2018

 - Replaced display of trimmed content for posts and pages in categories with excerpts. Trimmed content will still be presented if the excerpt is not set.

 = 3.1.9 =
* Released September 12, 2018

 - Fixed bugs and added additional validity checks to the Map Gallery Link function back-end.

 = 3.1.8 =
* Released September 11, 2018

 - Added Community Links functionality. A new menu can now exists in the header for all pages featuring one.
 - Minor background code modifications for child theme use.

= 3.1.7 =
* Released September 6, 2018

 - Posts and pages can be assigned numeric priority values on their edit pages. This will determine their ordering within Categories.
 - Added header search bar controls that permit toggling off or on.
 - If the GeoPlatform Search plugin is installed, the search bar can also be changed to leverage it instead.
 - Added disabling the header menu to the Bootstrap inclusion options.
 - Fonts, Map Gallery link, Search Bar Controls, and Bootstrap Controls have been consolidated under GeoPlatform Controls in the customizer.

= 3.1.6 =
* Released August 31, 2018

 - Added Bootstrap inclusion toggle as a temporary fix for conflicts with multiple versions loading.
 - Rebuilt the Archives template.

= 3.1.5 =
* Released August 28, 2018

- Categories can be assigned numeric priority values on their edit pages.
- Customization option added to toggle Featured card sorting between legacy by date and new by priority system.
- Removed deprecated environment variables for the front page map gallery.

= 3.1.4 =
* Released August 23, 2018

- Various tweaks and bug fixes.

= 3.1.3 =
* Released August 2, 2018

- Update checking allows user to update the theme.
- Google Analytics incorporated.
- Numerous back-end improvements and bug fixes.

 = 3.1.2 =
* Released June 29, 2018

Updated per requirements listed in [Wordpress Theme requirements](https://make.wordpress.org/themes/handbook/review/required/)

* Notable Changes
 - Removed Category links
 - Integrated Category images
 - Now Child theme ready
 - Front page template added
 - Custom logo enabled
 - Editor style enabled
 - Theme now translatable
 - Significant code refactoring, security updates, and bug fixes

 =  3.1.0 =
* Released: June 5, 2018

Initial submission on Wordpress.org. Previous versions were developed privately for GeoPlatform.gov customers.

<br>

### Contact Information
Authors:
Image Matters LLC
201 Loudoun St, SW
Leesburg, Virginia 20175
USA
(www.imagemattersllc.com)

Technical Support:
servicedesk@geoplatform.gov
