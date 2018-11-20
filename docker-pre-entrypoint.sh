#!/bin/bash
# Starts all of our services, first php-fpm, then nginx.
# curl -L -o /usr/share/nginx/html/wp-content/plugins/theme-check.zip https://downloads.wordpress.org/plugin/theme-check.20160523.1.zip;
# unzip -d /usr/share/nginx/html/wp-content/plugins/ /usr/share/nginx/html/wp-content/plugins/theme-check.zip;

php-fpm -D -R
nginx -g "daemon off;"
