# GeoPlatform Community Core Bundle (CCB)

**Contributors:** imagemattersllc, kevins713, lheazel
**Requires at least:** WordPress 4.6.4   
**Tested up to:** WordPress 4.9.6   
**Stable tag:** 4.0.2
**Version:** 4.0.2
**License:** Apache 2.0   
**License URI:** http://www.apache.org/licenses/LICENSE-2.0   
**Tags:** two-columns, left-sidebar, right-sidebar, grid-layout, custom-background, custom-colors, custom-header, custom-menu, featured-images, full-width-template, theme-options, custom-logo, editor-style, translation-ready, education   

The GeoPlatform Community Core Bundle (CCB) theme is the core theme for WordPress sites hosted by GeoPlatform.gov.

## Description

GeoPlatform CCB offers a number a features not common for WordPress themes, including:

* Enhanced capabilities for Editor, Author, Contributor, and Subscriber roles
* Integration with other GeoPlatform.gov plugins and services
* Page Categories and Tags
* Banner WYSIWYG editor for front page, posts, and pages
* Front Page Categories  

### Enhanced Capabilities

This theme assigns higher capabilities to the default WordPress roles to facilitate broader community-based contribution and collaboration while controlling access to restricted content by user role. Default WordPress roles can be found here https://codex.wordpress.org/Roles_and_Capabilities. The enhanced capabilities for the GeoPlatform CCB theme are noted in the table below (enhanced roles are prefixed with "GP-").

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

### GeoPlatform Integration
#### Map Gallery
Using the Customizer, you can add a Map Gallery, created with the GeoPlatform [Map Manager](https://maps.geoplatform.gov), into the front page. Follow these steps to add a map gallery into the theme:

 1. Create a Map Gallery with the [Map Manager](https://maps.geoplatform.gov) application .
 2. When viewing the Gallery in Map Manager, the URL will have the Gallery ID at the end of it. Example https://maps.geoplatform.gov/galleries/ **[your Gallery ID]**.
 3. Copy the ID of the Gallery from Map Manager page.
 4. Sign into your WordPress site as an Administrator or Editor user, open the Customizer, and go to the **Map Gallery** section under **GeoPlatform Controls**.
 5. Paste your Gallery ID in the text box, and add a fully resolvable URL link to the front of it. Example: https://ual.geoplatform.gov/api/galleries/ **[your Gallery ID]**.

 If your Gallery doesn't show up, try hitting "Publish" in the Customizer. If it still does not, make sure the you've got the correct Gallery ID or otherwise please contact us at servicedesk@geoplatform.gov to troubleshoot the issue.

#### Map Plugin
To dynamically add single "Open Maps" from the [GeoPlatform Map Viewer](https://maps.geoplatform.gov) or other applications, you can use the GeoPlatform [Maps Plugin](https://wordpress.org/plugins/geoplatform-maps/), available on the Wordpress Marketplace. See the link provided there for documentation and usage guidance.

### Page Categories and Tags
Like Posts, Pages can also be assigned to one or more Categories and Tags with this theme. This works the same as adding a category or tag to a Post.

### Banner WYSIWYG editor
You can edit the main banner on the front page, and for all Posts, Pages, and Category Pages.

* To edit the **Front Page** banner, navigate to the **Customizer -> Banner Area**. It uses the TinyMCE WYSIWYG editor, just like posts and pages. It also has a **Call to Action Button** that goes right below the content, that you can enable in that Customizer area.
* **Posts** and **Pages** have another editor area right below the main content area called **"Banner Area Custom Content"**. Anything put in there will show up in that **Post** or **Pages** banner.
* **Category Pages** have a **"Description"** area. Anything put in there will show up in the Category page's banner.   

### Front Page Categories
The Front Page of this theme is designed to showcase Categories of the site. Each Category will appear with a default image on the front page. To change the Featured Image of each Category, navigate to the area where you can edit Categories (**Posts/Pages->Categories->[Your Category]**). The image will appear in the Banner of that category as well as the Front Page card of that Category.

The Front Page organizes categories by name in ascending order, and show the first 12 categories in that organization structure. You can cycle through the categories with a **"More/Previous Categories"** Links below the cards. Later updates will incorporate dynamically being able to change the organization of these.

## Frequently Asked Questions

* What is the GeoPlatform Community Core Bundle?

The Community Core Bundle (CCB) enables independent development of a self-contained community website (i.e., a "Community Space") that operates on the infrastructure of the GeoPlatform. More info about CCB's and GeoPlatform can be found [here](https://www.geoplatform.gov/geoplatform-help/ccb/ccb-getting-started/#what_is_a_ccb).

* Does this theme need to be active for other GeoPlatform plugins to work?

No, GeoPlatform plugins work independently of the theme.

* The dropdown menu no longer functions.

This is most likely due to conflicting uses of the Bootstrap library. The GeoPlatform CCB theme utilizes Bootstrap for some of its operations, and loading that asset with a plugin as well can cause conflicts. It is advised to avoid using that plugin.

## Copyright

GeoPlatform-CCB for use by GeoPlatform.gov hosted communities and for general use.
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

## Changelog

## 4.0.2
* Release pending

- NGDA and Text Resource Widgets added.
- Megamenu update to accommodate lower resolutions and the header controls.

## 4.0.1
* Release March 4th

- Excluded unnecessary new function from parent theme.
- Removed automated update feature.

## 4.0.0
* Release January 17, 2019

 - Complete redesign of the user experience.

## 3.1.9
* Released October 22, 2018

 - Enabled category priority assignment and sorting just like posts.
 - Returned the style page template.

## 3.1.8
* Released October 12, 2018

 - The "My Account" button in the header now reflects the user's name if set, username if not, or "Sign In" if not signed in.

## 3.1.7
* Released September 20, 2018

 - Added feature to Announcements V2 to set "More Featured Content" post count in Widget controls.

## 3.1.6
* Released September 14, 2018

 - Added override functions for Category Links incorporation from parent theme.

## 3.1.5
* Released September 13, 2018

 - Replaced display of trimmed content for posts in the Features and Announcements widgets with excerpts.
 - Trimmed content will still be presented if the excerpt is not set.
 - The Content Block shortcode input sections for both Features and Announcement widgets controls have been removed.

## 3.1.4
* Released September 11, 2018

 - Removed option to assign menus to the content links which does do not exist in this theme.
 - Removed deprecated and obsolete menu locations.

### 3.1.3
* Released September 7, 2018

 - Removed sorting functions within this theme that have been moved to its parent.

### 3.1.2
* Released September 6, 2018

 - Added disabling the header menu to the Bootstrap inclusion options.
 - Fonts, Map Gallery link, and Bootstrap Controls have been consolidated under GeoPlatform Controls in the customizer.

### 3.1.1
* Released August 30, 2018

 - Features and Announcements V2 widget added.
 - Added Priority ordering system to Pages.

### 3.1.0
* Released August 29, 2018

 - The front page has been fully widgetized.
 - Deprecated environment options from Map Gallery sidebar option removed.

### 3.0.0
* Release pending.

 - Initial release.

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
