#!/bin/bash

# Pre-Entrypoint
#
# This script allows up to do some preliminary setup before the container
# passes back to the default docker-entrypoint.sh script.
#
# Wordpress:latest script found here:
# https://github.com/docker-library/wordpress/blob/master/php7.2/apache/docker-entrypoint.sh
# set -x
root_find='T'
site_find='T'
writerule=''

# Check env vars set in docker-compose.yml and propogate as needed
if [[ -z ${root_url+x} ]] ; then
  echo "NOTICE: ENV variable 'root_url' is missing."
  root_find='F'
fi
if [[ ! -z ${root_url+x} ]] && [[ ! $root_url =~ ^https?://.+  ]]; then
  echo "ERROR: Invalid root_url given. It must match ^https?://.+ "
  echo "Given value: $root_url"
  exit 1
fi
if [[ -z ${sitename+x} ]] ; then
  echo "NOTICE: ENV variable 'sitename' is missing."
  site_find='F'
fi

if [[ $root_find != $site_find ]] ; then
  echo "ERROR: ENV variables root_url and sitename must be either both present or neither present."
  exit 1
fi
if [ $root_find == 'F' ] && [ $site_find == 'F' ] ; then
  echo "NOTICE: No ENV variables defined. Utilizing defaults."
fi
if [ $root_find == 'T' ] && [ $site_find == 'T' ] ; then
  echo "NOTICE: ENV variables confirmed."
  writerule="<IfModule mod_rewrite.c>\n  RewriteEngine On\n  RewriteRule ^$sitename(.*) "'$1'" [L]\n\n  RewriteBase \/$sitename\/\n  RewriteRule ^index"'\\'".php$ - [L]\n  RewriteCond %{REQUEST_FILENAME} !-f\n  RewriteCond %{REQUEST_FILENAME} !-d\n  RewriteRule . \/$sitename\/index.php [L]\n<\/IfModule>"
fi

# Setup the URL rewriting
sed -i "s/%%writerule%%/${writerule}/g" .htaccess

# Set proper ownership and permissions of .htaccess for WP
chmod 644 .htaccess
chown www-data:www-data .htaccess

# Return to the regularly scheduled program
# CMD arg: https://github.com/docker-library/wordpress/blob/master/php7.2/apache/Dockerfile#L60
docker-entrypoint.sh "apache2-foreground"
