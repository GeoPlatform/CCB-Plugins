#!/bin/bash

# Pre-Entrypoint
#
# This script allows up to do some preliminary setup before the container
# passes back to the default docker-entrypoint.sh script.
#
# Wordpress:latest script found here:
# https://github.com/docker-library/wordpress/blob/master/php7.2/apache/docker-entrypoint.sh

# Check env vars set in docker-compose.yml and propogate as needed

# Setup the .htaccess file
sed -ie "s/%%sitename%%/$sitename/g" .htaccess


# Return to the regularly scheduled program
# CMD arg: https://github.com/docker-library/wordpress/blob/master/php7.2/apache/Dockerfile#L60
docker-entrypoint.sh "apache2-foreground"