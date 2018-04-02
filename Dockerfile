FROM wordpress:latest
MAINTAINER ImageMatters admin@imagemattersllc.com

# NOTE:
# Do NOT set WORKDIR as the entrypoint script provided by WordPress
# has assumptions about the working directory when it runs and the
# build will be boken if its set here.

# Setup required build binaries
RUN apt-get update
RUN apt-get install unzip

# Remove the default plugins themes
# RUN rm -rf /usr/src/wordpress/wp-content/plugins/*
# RUN rm -rf /usr/src/wordpress/wp-content/themes/*

######## Download and install dependencies ###########
# categories-images:
RUN curl -L -o /usr/src/categories-images.zip \
					https://downloads.wordpress.org/plugin/categories-images.2.5.4.zip
RUN unzip -d /usr/src/wordpress/wp-content/plugins/ \
					/usr/src/categories-images.zip
RUN rm /usr/src/categories-images.zip
######################################################

# The /usr/src/wordpress/ dir in the container is copied to /var/www/html in the docker-entrypoint.sh for Wordpress
ADD ./plugins /usr/src/wordpress/wp-content/plugins/
ADD ./themes /usr/src/wordpress/wp-content/themes/

