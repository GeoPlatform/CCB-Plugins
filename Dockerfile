FROM wordpress:latest
MAINTAINER ImageMatters admin@imagemattersllc.com

# NOTE:
# Do NOT set WORKDIR as the entrypoint script provided by WordPress
# has assumptions about the working directory when it runs and the
# build will be boken if its set here.

# Setup required build binaries
RUN apt-get update
RUN apt-get install unzip

# Pull the config into the final hosted directory
ADD ./config/apache2.conf /etc/apache2/
ADD ./config/.htaccess /var/www/html/
ADD ./config/wp-config.php /var/www/html/

# Remove the default plugins and themes
RUN rm -rf /usr/src/wordpress/wp-content/plugins/hello.php
RUN rm -rf /usr/src/wordpress/wp-content/themes/*

########### Install common dependencies ################
# tinymce-advanced:
RUN curl -L -o /usr/src/tinymce-advanced.zip \
					https://downloads.wordpress.org/plugin/tinymce-advanced.4.7.11.zip; \
	  unzip -d /usr/src/wordpress/wp-content/plugins/ \
					/usr/src/tinymce-advanced.zip; \
		rm /usr/src/tinymce-advanced.zip

# easy-wp-smtp:
RUN curl -L -o /usr/src/easy-wp-smtp.zip \
					https://downloads.wordpress.org/plugin/easy-wp-smtp.zip; \
	  unzip -d /usr/src/wordpress/wp-content/plugins/ \
					/usr/src/easy-wp-smtp.zip; \
		rm /usr/src/easy-wp-smtp.zip

# email-subscribers:
RUN curl -L -o /usr/src/email-subscribers.zip \
					https://downloads.wordpress.org/plugin/email-subscribers.3.5.3.zip; \
	  unzip -d /usr/src/wordpress/wp-content/plugins/ \
					/usr/src/email-subscribers.zip; \
		rm /usr/src/email-subscribers.zip

# custom-sidebars:
RUN curl -L -o /usr/src/custom-sidebars.zip \
					https://downloads.wordpress.org/plugin/custom-sidebars.3.1.6.zip; \
	  unzip -d /usr/src/wordpress/wp-content/plugins/ \
					/usr/src/custom-sidebars.zip; \
		rm /usr/src/custom-sidebars.zip

# download-manager:
RUN curl -L -o /usr/src/download-manager.zip \
					https://downloads.wordpress.org/plugin/download-manager.zip; \
	  unzip -d /usr/src/wordpress/wp-content/plugins/ \
					/usr/src/download-manager.zip; \
		rm /usr/src/download-manager.zip

########### Install Developer Dependencies #############
# theme check:
#RUN curl -L -o /usr/src/theme-check.zip \
#					https://downloads.wordpress.org/plugin/theme-check.20160523.1.zip; \
#	  unzip -d /usr/src/wordpress/wp-content/plugins/ \
#					/usr/src/theme-check.zip; \
#		rm /usr/src/theme-check.zip

# theme sniffer:
#RUN curl -L -o /usr/src/theme-sniffer.zip \
#					https://github.com/WPTRT/theme-sniffer/releases/download/0.1.5/theme-sniffer.0.1.5.zip; \
#	  unzip -d /usr/src/wordpress/wp-content/plugins/ \
#					/usr/src/theme-sniffer.zip; \
#		rm /usr/src/theme-sniffer.zip; \
#		rm -rf /usr/src/wordpress/wp-content/plugins/__MACOSX/;

# Open ID Connect - OAUTH :
RUN curl -L -o /usr/src/open-id-generic-master.zip \
					https://github.com/daggerhart/openid-connect-generic/archive/3.4.0.zip; \
	  unzip -d /usr/src/wordpress/wp-content/plugins/ \
					/usr/src/open-id-generic-master.zip; \
		rm /usr/src/open-id-generic-master.zip;
#########################################################

# The /usr/src/wordpress/ dir in the container is copied to /var/www/html
# in the docker-entrypoint.sh for Wordpress
ADD ./plugins /usr/src/wordpress/wp-content/plugins/
ADD ./themes  /usr/src/wordpress/wp-content/themes/

# Run out custom entrypoint first
COPY docker-pre-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-pre-entrypoint.sh
ENTRYPOINT [ "docker-pre-entrypoint.sh" ]
