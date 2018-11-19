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

# Sets file and folder perms
RUN find /usr/share/nginx/html -type d -exec chmod 755 {} \;
RUN find /usr/share/nginx/html -type f -exec chmod 644 {} \;

# Run our custom entrypoint first
COPY docker-pre-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-pre-entrypoint.sh

# Gets this show on the road.
ENTRYPOINT [ "docker-pre-entrypoint.sh","-db" ]
