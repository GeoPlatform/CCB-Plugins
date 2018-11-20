FROM php:7.2-fpm
MAINTAINER ImageMatters admin@imagemattersllc.com

# Installs updates and reqs
RUN apt-get update
RUN apt-get install -y --no-install-recommends unzip libjpeg-dev libpng-dev

# Install NGINX
RUN apt-get install -y --no-install-recommends nginx
RUN /etc/init.d/nginx start

# Install Wordpress
RUN curl -o wordpress.tar.gz -fSL "https://wordpress.org/wordpress-4.9.8.tar.gz"
RUN tar -xzf wordpress.tar.gz
RUN mv wordpress/* /usr/share/nginx/html
RUN rm -r wordpress

# Run our custom entrypoint first
COPY docker-pre-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-pre-entrypoint.sh

# Remove the default plugins and themes.
# RUN rm -rf /usr/share/nginx/html/wp-content/plugins/hello.php
# RUN rm -rf /usr/share/nginx/html/wp-content/plugins/__MACOSX/;
# RUN rm -rf /usr/share/nginx/html/wp-content/themes/*

########### Download common dependencies ################

# Make temp folders
# RUN mkdir /var/www/html/plugins
# RUN mkdir /var/www/html/plugins



# tinymce-advanced:
RUN curl -L -o /usr/src/tinymce-advanced.zip \
					https://downloads.wordpress.org/plugin/tinymce-advanced.4.7.11.zip; \
	  unzip -d /usr/share/nginx/html/wp-content/plugins/ \
					/usr/src/tinymce-advanced.zip; \
		rm /usr/src/tinymce-advanced.zip

# easy-wp-smtp:
RUN curl -L -o /usr/src/easy-wp-smtp.zip \
					https://downloads.wordpress.org/plugin/easy-wp-smtp.zip; \
	  unzip -d /usr/share/nginx/html/wp-content/plugins/ \
					/usr/src/easy-wp-smtp.zip; \
		rm /usr/src/easy-wp-smtp.zip

# email-subscribers:
RUN curl -L -o /usr/src/email-subscribers.zip \
					https://downloads.wordpress.org/plugin/email-subscribers.3.5.3.zip; \
	  unzip -d /usr/share/nginx/html/wp-content/plugins/ \
					/usr/src/email-subscribers.zip; \
		rm /usr/src/email-subscribers.zip

# custom-sidebars:
RUN curl -L -o /usr/src/custom-sidebars.zip \
					https://downloads.wordpress.org/plugin/custom-sidebars.3.1.6.zip; \
	  unzip -d /usr/share/nginx/html/wp-content/plugins/ \
					/usr/src/custom-sidebars.zip; \
		rm /usr/src/custom-sidebars.zip

# download-manager:
RUN curl -L -o /usr/src/download-manager.zip \
					https://downloads.wordpress.org/plugin/download-manager.zip; \
	  unzip -d /usr/share/nginx/html/wp-content/plugins/ \
					/usr/src/download-manager.zip; \
		rm /usr/src/download-manager.zip

########### Install Developer Dependencies #############
# theme check:
# RUN curl -L -o /usr/src/theme-check.zip \
#					https://downloads.wordpress.org/plugin/theme-check.20160523.1.zip; \
#	  unzip -d /usr/share/nginx/html/wp-content/plugins/ \
#					/usr/src/theme-check.zip; \
#		rm /usr/src/theme-check.zip

# theme sniffer:
# RUN curl -L -o /usr/src/theme-sniffer.zip \
#					https://github.com/WPTRT/theme-sniffer/releases/download/0.1.5/theme-sniffer.0.1.5.zip; \
#	  unzip -d /usr/share/nginx/html/wp-content/plugins/ \
#					/usr/src/theme-sniffer.zip; \
#		rm /usr/src/theme-sniffer.zip; \

# Open ID Connect - OAUTH :
RUN curl -L -o /usr/src/open-id-generic-master.zip \
					https://github.com/daggerhart/openid-connect-generic/archive/master.zip; \
	  unzip -d /usr/share/nginx/html/wp-content/plugins/ \
					/usr/src/open-id-generic-master.zip; \
		rm /usr/src/open-id-generic-master.zip;
#########################################################


ADD ./plugins /usr/share/nginx/html/wp-content/plugins/
ADD ./themes  /usr/share/nginx/html/wp-content/themes/

# Sets file and folder perms
RUN find /usr/share/nginx/html -type d -exec chmod 755 {} \;
RUN find /usr/share/nginx/html -type f -exec chmod 644 {} \;

# Gets this show on the road.
ENTRYPOINT [ "docker-pre-entrypoint.sh","-db" ]
