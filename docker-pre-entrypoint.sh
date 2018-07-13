#!/bin/bash

# Pre-Entrypoint
#
# This script allows up to do some preliminary setup before the container
# passes back to the default docker-entrypoint.sh script.
#
# Wordpress:latest script found here:
# https://github.com/docker-library/wordpress/blob/master/php7.2/apache/docker-entrypoint.sh

# Check env vars set in docker-compose.yml and propogate as needed
if [[ -z ${root_url+x} ]] ; then
  echo "ERROR: Required ENV variable 'root_url' is missing. Please add it to your docker-compose.yml file."
  exit 1
fi
if [[ ! $root_url =~ ^https?://.+  ]]; then
  echo "ERROR: Invalid root_url given. Is must match ^https?://.+ "
  echo "Given value: $root_url"
  exit 1
fi


# Setup the URL rewriting
# sed -i "s/%%sitename%%/$sitename/g" /etc/apache2/apache2.conf
sed -i "s/%%sitename%%/$sitename/g" .htaccess

# Set proper ownership and permissions of .htaccess for WP
chmod 644 .htaccess
chown www-data:www-data .htaccess

# Return to the regularly scheduled program
# CMD arg: https://github.com/docker-library/wordpress/blob/master/php7.2/apache/Dockerfile#L60
docker-entrypoint.sh "apache2-foreground"