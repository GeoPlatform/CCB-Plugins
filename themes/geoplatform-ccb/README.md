# Geoplatform CCB (Community Core Bundle)

**Contributors:** kevins713, imagemattersllc  
**Requires at least:** WordPress 4.6.4  
**Tested up to:** WordPress 4.9.6  
**Stable tag:** 3.0.5   
**Version:** 3.1.1  
**License:** Apache 2.0
**License URI:** http://www.apache.org/licenses/LICENSE-2.0 
**Tags:** two-columns, left-sidebar, right-sidebar, grid-layout, custom-background, custom-colors, custom-header, custom-menu, featured-images, full-width-template, theme-options, education

The Geoplatform Community Core Bundle theme is the core theme for WordPress sites hosted by Geoplatform.gov.

## Description

Geoplatform CCB offers a number a features not common for WordPress themes, including:

* enhanced user capabilities for Editors, Authors, Contributors, and Subscribers
* integration with other Geoplatform.gov services
* Page Categories and Tags
* banner WYSIWYG editor for front page, posts, and pages
* Category Specific Sidebar Links
* Front Page Categories  

### Enhanced capabilities

This theme gives higher capabilities to the default WordPress roles to give better use of the theme to those below Administrators. Default WordPress roles can be found here https://codex.wordpress.org/Roles_and_Capabilities. The enhanced capabilities will be noted in this table below, and the enhanced roles we will prefix with GP-[Role].

| Capability               |GP-Editor|GP-Author|GP-Contributor|GP-Subscriber|
|--------------------------|:-------:|--------:|-------------:|------------:|
|**edit_theme_options**    | X       |         |              |             |             
|**list_users**            | X       |         |              |             |              
|**manage_options**        | X       |         |              |             |               
|**edit_dashboard**        | X       |         |              |             |               
|**customize**             | X       |         |              |             |               
|**edit_pages**            | X       | X       |              |             |               
|**edit_published_pages**  | X       | X       |              |             |               
|**publish_pages**         | X       | X       |              |             |               
|**delete_pages**          | X       | X       |              |             |                
|**delete_published_pages**| X       | X       |              |             |                
|**edit_private_posts**    | X       | X       |              |             |                
|**read_private_posts**    | X       | X       | X            | X           |                
|**edit_private_pages**    | X       | X       |              |             |               
|**read_private_pages**    | X       | X       | X            | X           |                
|**upload_files**          | X       | X       | X            |             | |

### Geoplatform Integration
#### Map Gallery
Using the Customizer, you can add a map gallery created in https://maps.geoplatform.gov into the front page. Follow these steps to add a map gallery into the theme:

 1. Create a Map gallery in https://maps.geoplatform.gov.
 2. When viewing the gallery, the url will have the gallery ID at the end of it. Example https://maps.geoplatform.gov/galleries/ **[your Gallery ID]**.
 3. Copy the ID of the gallery.
 4. Sign into your WordPress site, open the Customizer, and go to the **Map Gallery** section.
 5. Paste your Gallery ID in the text box, and add a UAL url link to the front of it. Example: https://ual.geoplatform.gov/api/galleries/ **[your Gallery ID]**.

 If your gallery doesn't show up, try hitting "Publish" in the Customizer. If it still does not, please contact us at servicedesk@geoplatform.gov to troubleshoot the issue.

 **Note:** This version still has SIT and STG choices for development for this gallery functionality. In later versions everything will default to PROD.  

#### Map Plugin
If you would like to dynamically add single maps from https://maps.geoplatform.gov, you can use our [Maps Plugin](), soon to be out on the Wordpress Marketplace. See the link provided for it's documentation and use.

### Page Categories and Tags
Like Posts, Pages can also be assigned to one or more Categories and Tags with this theme. This works the same as adding a category or tag to a post.

### Banner WYSIWYG editor
You can edit the main banner on the front page, and for all posts, pages, and category pages.

* To edit the **Front Page** banner, navigate to the **Customizer -> Banner Area**. It uses the TinyMCE WYSIWYG editor, just like posts and pages. It also has a **Call to Action Button** that goes right below the content, that you can enable in that Customizer area.
* **Posts** and **Pages** have another editor area right below the main content area called **"Banner Area Custom Content"**. Anything put in there will show up in that **Post** or **Pages** banner.
* **Category Pages** have a **"Description"** area. Anything put in there will show up in the Category page's banner.   

### Category Specific Sidebar Links
You are able to add up to 5 links to each category page. The links can be added in each category page.

### Front Page Categories
The Front page of this theme is designed to showcase Categories of the site. Each category will show up with a placeholder image. To change the Featured Image of each category, you need to navigate to the area where you can edit Categories (**Posts/Pages->Categories->[Your Category]**). It will show up in the Banner of that category as well as the front page card of that category.

The front page organizes categories by name in ascending order, and show the first 12 categories in that organization structure. You can cycle through the categories with a **"More/Previous Categories"** Links below the cards. Later updates will incorporate dynamically being able to change the organization of these.

## Frequently Asked Questions

* What is the Geoplatform Community Core Bundle?

The Community Core Bundle (CCB) enables independent development of a self-contained community website (i.e., a Community Space) that operates on the infrastructure of the GeoPlatform. More info about CCB's and Geoplatform can be found [here.](https://www.geoplatform.gov/geoplatform-help/ccb/ccb-getting-started/#what_is_a_ccb)

* Does this theme need to be active for other Geoplatform plugins to work?

No, Geoplatform plugins work independently of the theme.


## Copyright

GeoPlatform-CCB for use with GeoPlatform.gov products and general use.
Copyright (C) 2018 Image Matters LLC

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

(http://www.apache.org/licenses/LICENSE-2.0)

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.

Geoplatform CCB bundles the following third-party resources:

Bootstrap (3.3.7). Copyright 2018 Twitter.
**License:** MIT
Source: (https://getbootstrap.com/docs/3.3/)


## Changelog

### 3.1.0
* Released: June [##], 2018

Initial release on Wordpress.org. Previous versions were developed privately for Geoplatform.gov customers.