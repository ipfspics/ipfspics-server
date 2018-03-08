FROM ubuntu:16.04

MAINTAINER vincent@cloutier.co

WORKDIR /var/www/html/
RUN apt update && apt install -y apache2 libapache2-mod-php7.0 php7.0 php7.0-dev php7.0-xml php7.0-zip php7.0-curl php7.0-cli composer
RUN pecl install mongodb && echo "extension=mongodb.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
RUN a2enmod rewrite

# forward request and error logs to docker log collector
RUN ln -sf /dev/stdout /var/log/apache2/access.log
RUN ln -sf /dev/stderr /var/log/apache2/error.log

ADD docker/000-default.conf /etc/apache2/sites-enabled/000-default.conf

ENTRYPOINT ["apache2ctl", "-D", "FOREGROUND"]

EXPOSE 80

ADD . .
RUN chmod -R 775 /var/www/
RUN composer install
