# Geoplatform CCB (Community Core Bundle)

**Contributors:** kevins713  
**Requires at least:** WordPress 4.6.4  
**Tested up to:** WordPress 4.9.6  
**Stable tag:** 3.0.5   
**Version:** 3.1.0  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  
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
Using the Customizer, you can add a map gallery created in https://maps.geoplatform.gov into the front page. Follow these steps to add a map gallery into the theme.

 1. Create a Map gallery in https://maps.geoplatform.gov.
 2. When viewing the gallery, the url will have the gallery ID at the end of it. Example https://maps.geoplatform.gov/galleries/[your gallery ID]
 3. Copy the ID of the gallery
 4. Sign into your WordPress site, open the Customizer, and go to the Custom Links section
 5. Paste your Gallery ID in the text box. If your gallery doesn't show up, try hitting "Publish" in the Customizer. If it still does not, please contact us at servicedesk@geoplatform.gov to troubleshoot the issue.

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
The Front page of this theme is designed to showcase Categories of the site. Each category will show up with a placeholder image. To change the Featured Image of each category, you need to download and activate the [Categories Images plugin](https://wordpress.org/plugins/categories-images/).

Once activated, you can add a new featured image to each category in that specific category page.

## Frequently Asked Questions

* What is the Geoplatform Community Core Bundle?

The Community Core Bundle (CCB) enables independent development of a self-contained community website (i.e., a Community Space) that operates on the infrastructure of the GeoPlatform. More info about CCB's and Geoplatform can be found [here.](https://www.geoplatform.gov/geoplatform-help/ccb/ccb-getting-started/#what_is_a_ccb)

* Does this theme need to be active for other Geoplatform plugins to work?

No, Geoplatform plugins work independently of the theme. 




## Copyright

Geoplatform CCB WordPress Theme, Copyright 2018 Geoplatform.gov.
Geoplatform CCB is distributed under the terms of the GNU GPL.

Geoplatform CCB bundles the following third-party resources:

Bootstrap (3.3.7). Copyright 2018 Twitter.
**License:** MIT
Source: https://getbootstrap.com/docs/3.3/


## Changelog

### 3.1.0
* Released: June [##], 2018

Initial release on Wordpress.org. Previous versions were developed privately for Geoplatform.gov customers.
