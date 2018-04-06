# CCB-Plugins
This repo holds the work of CCB plugins.

### Getting Started

To run the CCB container simply run
> docker-compose up -d ccb

This will create and run a new CCB install inside a docker container.

<br>

**Optional variables**
| Name | Type | Description |
|---|---|---|
| sitename | string | The name of the site (needed for deploying side by side on a host)

<hr>

## Structure
### **plugins/**

Each file in this directory is a single WordPress plugin.


### **themes/**

Each file in this directory is a single WordPress theme.


<hr>

## Dependencies

WordPress plugin depdendencies can be added as part of the `Dockerfile` and therefore made part of the build process. Dependencies should be added to the `Dockerfile` in the following format:

```shell
# MyPlugin:
RUN curl -L -o /usr/src/plugin.zip [URL to plugin download]; \
    unzip -d /usr/src/wordpress/wp-content/plugins/	\
             /usr/src/plugin.zip; \
    rm /usr/src/plugin.zip
```

## Publishing

Tagged versions of this repo should be pushed to the GeoPlatform CDN via the `pushToCDN.sh` script.

> Get argument deatails by running
>`./pushToCDN.sh --help`

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