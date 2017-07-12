FROM php:7.1-fpm

# Install SSH
RUN apt-get update && apt-get install -y openssh-server supervisor
RUN mkdir -p /var/run/sshd /var/log/supervisor
COPY authorized_keys /root/.ssh/authorized_keys
EXPOSE 22

# Configure supervisor to run both ssh and php-fpm
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
CMD ["/usr/bin/supervisord"]

# Install OpCache
RUN docker-php-ext-install opcache && docker-php-ext-enable opcache
RUN echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

# Install APCu
RUN pecl install apcu && docker-php-ext-enable apcu

# Install MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Install cachetool
RUN curl -sO http://gordalina.github.io/cachetool/downloads/cachetool.phar \
    && mv cachetool.phar /bin/cachetool \
    && chmod +x /bin/cachetool

# Configure php-fpm pool
COPY www.conf /usr/local/etc/php-fpm.d/www.conf
