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
RUN rm -rf /usr/src/wordpress/wp-content/plugins/hello.php
RUN rm -rf /usr/src/wordpress/wp-content/themes/*

######## Download and install dependencies ###########
# categories-images:
RUN curl -L -o /usr/src/categories-images.zip \
					https://downloads.wordpress.org/plugin/categories-images.2.5.4.zip; \
	  unzip -d /usr/src/wordpress/wp-content/plugins/ \
					/usr/src/categories-images.zip; \
		rm /usr/src/categories-images.zip
##Developer Dependencies below here ################
# theme check:
RUN curl -L -o /usr/src/theme-check.zip \
					https://downloads.wordpress.org/plugin/theme-check.20160523.1.zip; \
	  unzip -d /usr/src/wordpress/wp-content/plugins/ \
					/usr/src/theme-check.zip; \
		rm /usr/src/theme-check.zip

# theme sniffer:
RUN curl -L -o /usr/src/theme-sniffer.zip \
					https://github.com/WPTRT/theme-sniffer/releases/download/0.1.5/theme-sniffer.0.1.5.zip; \
	  unzip -d /usr/src/wordpress/wp-content/plugins/ \
					/usr/src/theme-sniffer.zip; \
		rm /usr/src/theme-sniffer.zip; \
		rm -rf /usr/src/wordpress/wp-content/plugins/__MACOSX/;

# Open ID Connect - OAUTH :
RUN curl -L -o /usr/src/open-id-generic-master.zip \
					https://github.com/daggerhart/openid-connect-generic/archive/master.zip; \
	  unzip -d /usr/src/wordpress/wp-content/plugins/ \
					/usr/src/open-id-generic-master.zip; \
		rm /usr/src/open-id-generic-master.zip;
######################################################

# Pull the config into the final hosted directory
ADD ./config  /var/www/html/

# The /usr/src/wordpress/ dir in the container is copied to /var/www/html
# in the docker-entrypoint.sh for Wordpress
ADD ./plugins /usr/src/wordpress/wp-content/plugins/
ADD ./themes  /usr/src/wordpress/wp-content/themes/

# Run out custom entrypoint first
COPY docker-pre-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-pre-entrypoint.sh
ENTRYPOINT [ "docker-pre-entrypoint.sh" ]
