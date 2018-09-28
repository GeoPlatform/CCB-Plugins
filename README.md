# CCB-Plugins
This repo holds the work of CCB themes and plugins.

### Getting Started

The GeoPlatform Community Core Bundle is an open source codebase under an Apache 2 license. Use of this package requires both docker and git to be installed on the user’s machine. Once these requirements are met, the user need only navigate to their working folder in the command line and make a clone of the repository. This can be done using either of the two methods:

```shell
git init
git clone https://github.com/GeoPlatform/CCB-Plugins.git
```

or

```shell
git init
git clone git@github.com:GeoPlatform/CCB-Plugins.git
```

Alternatively, the user may choose to download a zip file of the repository and deploy it where they require.

From this point the user need only deploy the package from the working directory by inputting the following on the command line:

```shell
docker-compose up -d ccb
```

This will create a working container on their computer, which can be accessed through a web browser by simply navigating to “localhost”.

Please see the full documentation for the CCB Repo here: https://github.com/GeoPlatform/CCB-Plugins

<br>

## Configuration
The base container for this project can be found here: https://hub.docker.com/_/wordpress/.<br>
The `docker-pre-entrypoint.sh` script is an intermediary script that runs before the default `docker-entrypoint.sh` script and any post-build initalization for the container should be placed there.


Environment variable can be defined in the `environment:` section under the `ccb` container in the `docker-compose.yml` file.
<br><br>
**Environment variables:**

| Name | Type | Description | Required |
|---|---|---|---|
| root_url | string | The root/base url where the site with be hosted.<br> Prod URL: https://communities.org/myCommunity <br> Would use:  `root_url: https://communities.org` | Yes |
| sitename | string | The name of the site (needed for deploying side by side on a host). <br> Prod URL: https://communities.org/myCommunity <br> Would use: `sitename: myCommunity` | No |

<br><br>

## Structure

### **plugins/**

Each file in this directory is a single WordPress plugin.


### **themes/**

Each file in this directory is a single WordPress theme.


<br><br>

## Dependencies

WordPress plugin depdendencies can be added as part of the `Dockerfile` and therefore made part of the build process. Dependencies should be added to the `Dockerfile` in the following format:

```shell
# MyPlugin:
RUN curl -L -o /usr/src/plugin.zip [URL to plugin download]; \
    unzip -d /usr/src/wordpress/wp-content/plugins/	\
             /usr/src/plugin.zip; \
    rm /usr/src/plugin.zip
```

> **Note:** <br>
> Please update the `.gitignore` with any additional plugin dependencies added to the `Dockerfile` to keep revision history clean and prevent confusion.

<br><br>

## Development and Supplemental Plugins
There are currently 2 plugins used to verify if the theme you have or want to use is working properly: **Theme Check** and **Theme Sniffer**. Additionally, the Portal 3 theme makes use of **Content Blocks** in the control of its widget content. These plugins are not bundled with this package, but are still very useful for theme development and and use.

### [Theme Check](https://wordpress.org/plugins/theme-check/) v20160523.1
-  a standard for checking Themes against WordPress requirements, and can be used to double check Theme Sniffer.

### [Theme Sniffer](https://github.com/WPTRT/theme-sniffer) v0.1.5
- checks any theme installed against the [Wordpress Coding Standards](https://github.com/WPTRT/WordPress-Coding-Standards) for theme development. Make sure in development that your theme does not have any errors. Notices should be dealt with accordingly but aren't critical to the site working.

### [Content Blocks (Custom Post Widget)](https://wordpress.org/plugins/custom-post-widget/) v3.0.3
- Establishes a custom post type that can be evoked as shortcode. This essentially allows the embedding of a post into any form of content where shortcode is permitted.

To use the plugins, navigate to the __Plugins->Installed Plugins__ area of the Wordpress Dashboard, and simply click the __Activate__ button under the plugin you want to use.

When activated, run and manage them from the __Appearance->Theme Check__ or __Appearance->Theme Sniffer__ areas of the Dashboard. Content Blocks creates its own section in the Dashboard for all of it functionality.


## First-Party Plugins

### GeoPlatform Community Search v1.0.7
-  Use to input searchable community asset interfaces into posts and pages using shortcode.
-  Global customization is achieved through controls in the settings page.
-  Individual customization is achieved through modifications to the shortcode itself.

### GeoPlatform Maps v1.0.10
-  Used to reference data from the GeoPlatform map databases into Wordpress instances and evoke interactive maps in pages and posts.
-  The user can create a gallery of maps in their Wordpress instance, which come with individual shortcodes.
-  These shortcodes can be placed into any location supporting them to evoke that map in an interactive interface.

### GeoPlatform Search v1.0.3
-  Creates a dedicated page hosting a fully-featured interface for perusing all GeoPlatform assets.
-  Possesses rudienmtiary controls for page recreation.
-  Provides a shortcode that evokes a search bar, which navigates to the page and initiates a search using the input keywords.

To use the plugins, navigate to the __Plugins->Installed Plugins__ area of the Wordpress Dashboard, and simply click the __Activate__ button under the plugin you want to use.

When activated, run and manage them from the __Settings->GeoPlatform Community Search__, __Settings->GeoPlatform Maps__, or __Settings->GeoPlatform Search__ areas of the Dashboard. These can also be accessed by selecting __Settings__ in the box for the desired plugin in __Plugins->Installed Plugins__.


## Bundled Plugins

### [Open ID Connect Generic](https://github.com/daggerhart/openid-connect-generic) v3.4.0
Used to connect to Geoplatform's Oauth2 IDP system. This plugin is mandatory for all CTK environments.

To configure, follow these steps:

1. Activate the plugin
2. Go to Settings -> OpenID Connect Client
3. For initial testing purposes, set the **Login type** to **OpenID Connect button on login form**. Doing otherwise before configuring fully will cause errors.
4. Contact the Geoplatform Tech team for your site's **Client ID** and **Client Secret Key**.
5. Fill in the boxes with the settings listed below:

> **Note:** <br>
> **X** indicates a check in a checkbox. **O** indicates to leave it blank.
>
| Setting | Value |
|---|---|
|**OpenID Scope**  | read |
|**Login Endpoint URL**  | https://sit-accounts.geoplatform.us/auth/authorize |
|**Userinfo Endpoint URL**  | https://sit-accounts.geoplatform.us/api/profile |
|**Token Validation Endpoint URL** | https://sit-accounts.geoplatform.us/logout |
|**End Session Endpoint URL** | https://sit-accounts.geoplatform.us/logout |
|**Identity Key** | username |
|**Disable SSL Verify** | O |
|**HTTP Request Timeout** | 5 |
|**Nickname Key** | username |
|**Email Formatting** | {email} |
|**Display Name Formatting** | {firstName} {lastName} |
|**Identify with Username** | X |
|**Start Time limit** | O |
|**Link Existing Users**| X |
|**Redirect Back to Origin Page** | X |
|**Redirect to login screen session is expired** | X |
|**Enforce Privacy** | O |
|**Alternate Redirect URI** | O |
|**Enable Logging** | X |
|**Log limit** | X |

1. Save your Changes at the bottom and test logging in with Geoplatform, by clicking **Login with OpenID Connect** instead of the regular login form.
2. Login through the Geoplatform Accounts screen. Create an account if you don't have one.
3. Once you are logged in, click **Authorize** to finish signing into your site.
4. Once you have verified this works properly, you may go back to the plugin settings and set the **Login type** to **Auto Login - SSO**. Make sure you save the changes again.
5.  Your plugin is now configured.

### [TinyMCE Advanced](https://wordpress.org/plugins/tinymce-advanced/) v4.7.11
Used to address issues presented in [DT-1628](https://geoplatform.atlassian.net/browse/DT-1628), this plugin upgrades the built in WYSIWYG editor for all areas it is present in the WordPress instance. These would be primarily in Posts and Pages in a normal Wordpress instance, as well as the Category and Banner areas in the Geoplatform CCB Theme.

There is no additional configuration needed for this plugin. Just activate as documented below and it works out of the box.

### [Easy WP SMTP](https://wordpress.org/plugins/easy-wp-smtp/) v1.3.7
Since the Wordpress instances are Dockerized, they need a plugin to handle setting up Email notifications for our users. This plugin handles setting up the SMTP connection for that.

To configure, follow these steps:
1. Activate plugin
2. Go to plugin settings (Settings->Easy WP SMTP)
3. In the **SMTP Settings** tab, set the **From Email Address** to : sit-smtp@geoplatform.us
4. Set the **SMTP Host** to : email-smtp.us-east-1.amazonaws.com
5. **Type of Encryption** should be set to **TLS**.
6. Set the **SMTP Port** to **25**.
7. Make sure **SMTP Authentication** is set to **YES**.
8. Contact the **Geoplatform Tech Team** for the **SMTP Username** and **SMTP Password**.
9. Once those are configured, go to the **Test Email** tab and send a test email to whichever email you are using to make sure the connection is setup successfully.
10. Once finished, move on to setting up and configuring the **Email Subcsribers** plugin

### [Email Subscribers](https://wordpress.org/plugins/email-subscribers/) v3.5.3
Used to address issues presented in [DT-1365](https://geoplatform.atlassian.net/browse/DT-1365), the Email Subscribers plugin is used to help site admins stay notified and also help their users stay notified of what goes on in the site.

To configure, follow these steps:
1. Make sure the **Easy WP SMTP** plugin is activated and configured
2. Activate this plugin
3. Go to the Settings page **(Email Subscribers->Settings)**, and set up the sites configuration settings for email as desired in the **Admin** tab.

### [Custom Sidebars](https://wordpress.org/plugins/custom-sidebars/) v3.1.6
This plugin replaces and enhances functionality originally featured in the Geoplatform CCB theme, in order to make custom menus and links show up in the sidebar of specifiic category pages. This plugin does that, as well as allows custom sidebars of any post and/or page on the site as well.

The plugin does not override any default functionality of the sidebars in your theme until you enable it. Each theme will have differently located and named sidebars, so for setup configuration we will act as if you're using the **Geoplatform CCB theme**.

To configure in Geoplatform CCB, follow these steps:
1. Activate the plugin
2. Go to the Widgets area (Appearance-> Widgets)
3. In the Sidebars area on the right, make sure the **Theme Sidebars**' "*Sidebar Widgets*" area has the **Allow this sidebar to be replaced** option checked.
4. Click **Create a new Sidebar**, and give it an appropriate name and description
5. You can now add any widget to this area as you normallly would to the sidebar. The difference here is you can set that sidebar's location to specific areas. For this example, we'll set it to a specific category page in Geoplatform CCB so it shows up after you click on a front page category
6. In you new sidebar, click **Sidebar Location**.
7. Click on the **For Archives** tab and set it to the specific category you want. It will now show up in your desired category page.

### [Download Manager](https://wordpress.org/plugins/download-manager/) v2.9.81
This plugin is used to enact much wider control over Wordpress content management. This is very useful for the handling and presentation of content, and controlling the upload and download permissions of various user roles. This plugin would primarily be used for content control, but can be further implemented in the categorization and sharing of documents or other uploaded content.

There is no additional configuration needed for this plugin. Just activate as documented below and it works out of the box.


<br>

## Themes
Most GeoPlatform themes utilize Bootstrap in their operation, and conflicting Bootstrap resources potentially included in 3rd-party plugins can potentially disable these operations. Current release themes provide an option to disable Bootstrap to permit smooth operation, but note should be taken.

### Geoplatform CCB - */themes/geoplatform-ccb* v3.2.1
 - Main theme for communities and new wordpress instances. This is the current community standard and backbone of the Portal 3 and NGDA 3 child themes.
 - Tested and updated against Wordpress Standards.

### Geoplatform NGDA 3 - */themes/geoplatform-ngda-child* v3.1.6
 - Theme for exclusive use with the GeoPlatform NGDA communities, including the NCC site.
 - Child theme of GeoPlatform CCB.

### Geoplatform Portal 3 - */themes/geoplatform-portal-child* v3.1.7
 - Theme for the [Main Geoplatform website](https://www.geoplatform.gov).
 - Not for public use.

### Geoplatform Portal - */themes/geoplatform-portal* deprecated
 - Theme for the [Main Geoplatform website](https://www.geoplatform.gov).
 - Tested and updated against Wordpress Standards, but not on Wordpress marketplace or publicly available

### Geoplatform NGDA  - */themes/geoplatform-ngda* deprecated
 - Theme exclusively for use with Geoplatform NGDA communities.
 - Currently standalone theme, but will soon be refactored as a Child theme of the Geoplatform CCB theme to better incorporate and stay up to date with the latest features.

### NGDA Imagery  - */themes/ngda-imagery-2017* deprecated
 - Theme exclusively for use with the NGDA Imagery site.
 - Currently standalone theme, but will soon be refactored as a Child theme of the Geoplatform CCB theme to better incorporate and stay up to date with the latest features.

### NGDA NCC - */themes/ngda-ncc-21017* deprecated
 - Theme exclusively for use with NGDA NCC site.
 - Currently standalone theme, but will soon be refactored as a Child theme of the Geoplatform CCB theme to better incorporate and stay up to date with the latest features.

<br><br>

## Authentication

The CCB-Plugins repository uses `gpoauth` for authentication. The folllowing containers are used for providing the oauth service locally:
- gpoauth
- gpidm
- mongo

This is a private GeoPlatform repository and protected container and can only be pulled by persons
with proper access rights.

To pull and run the authentication containers locally
please follow the instructions found here: <br>
https://github.com/GeoPlatform/gpoauth/wiki/Running-GP-OAUTH2.0-on-a-local-machine

<br><br>

## Publishing Themes and Plugins

Tagged versions of this repo should be pushed to the GeoPlatform CDN via the `pushToCDN.sh` script.

Get argument deatails by running `./pushToCDN.sh --help`

> **Note:** <br>
> Only users with AWC CLI and appropriate permissions are able to publish to the CDN

Example:
```shell
$ pushToCDN.sh plugins/gp-search/ 0.1
==== Uploading to CDN =====
type:  plugins
name:  gp-search
version:  0.1
===========================
updating: plugins/gp-search/ (stored 0%)
updating: plugins/gp-search/assets/ (stored 0%)
updating: plugins/gp-search/assets/css/ (stored 0%)
updating: plugins/gp-search/assets/css/gp-search-admin.css (deflated 75%)
updating: plugins/gp-search/assets/css/gp-search-core.css (deflated 84%)
updating: plugins/gp-search/assets/js/ (stored 0%)
updating: plugins/gp-search/assets/js/geoplatform.client.min.js (deflated 82%)
updating: plugins/gp-search/assets/js/geoplatform.js (deflated 50%)
updating: plugins/gp-search/gp-search.php (deflated 76%)
updating: plugins/gp-search/includes/ (stored 0%)
updating: plugins/gp-search/includes/gp-search-admin.php (deflated 81%)
updating: plugins/gp-search/includes/gp-search-core.php (deflated 76%)
updating: plugins/gp-search/LICENSE.txt (deflated 62%)
===========================
upload: ./gp-search.zip to s3://geoplatform-cdn/CCB/plugins/gp-search/0.1/gp-search.zip
===========================
Artifact pushed to CDN: http://dyk46gk69472z.cloudfront.net/CCB/plugins/gp-search/0.1/gp-search.zip
It may take a few minutes for the resource to propagate through the CDN.
```
