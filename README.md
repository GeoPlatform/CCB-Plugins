# CCB-Plugins
This repo holds the work of CCB themes and plugins.

### Getting Started

To run the CCB container simply run
> docker-compose up -d ccb

This will create and run a new CCB install inside a docker container.

<br>

## Configuration
The base container for this project can be found here: https://hub.docker.com/_/wordpress/.<br>
The `docker-pre-entrypoint.sh` script is an intermediary script that runs before the default `docker-entrypoint.sh` script and any post-build initalization for the container should be placed there.


Environmen variable can be defined in the `environment:` section under the `ccb` container in the `docker-compose.yml` file.
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

## Development
There are currently 2 plugin dependencies built in to verify if the theme you have or want to use is working properly. They are [Theme Check](https://wordpress.org/plugins/theme-check/) and [Theme Sniffer](https://github.com/WPTRT/theme-sniffer).
 - Theme Check is an older standard, and has not been updated for the latest version of wordpress, but will work for checking older themes with older versions of Wordpress, or to double check Theme Sniffer
 - Theme Sniffer checks any theme installed against the [Wordpress Coding Standards](https://github.com/WPTRT/WordPress-Coding-Standards) for theme development.

To use either of these, navigate to the __Plugins->Installed Plugins__ area of the Wordpress Dashboard, and simply click the __Activate__ button under the plugin you want to use.

When activated, run and manage them from the __Appearance->Theme Check__ or __Appearance->Theme Sniffer__ areas of the Dashboard.

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
