#!/bin/bash
# Starts all of our services, first php-fpm, then nginx.
php-fpm -D -R
nginx -g "daemon off;"
