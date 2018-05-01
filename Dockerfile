FROM ubuntu:18.04

MAINTAINER vincent@cloutier.co

WORKDIR /var/www/html/
ENV TZ 'Europe/Tallinn'
RUN echo $TZ > /etc/timezone && \
    apt-get update && apt-get install -y tzdata && \
    rm /etc/localtime && \
    ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && \
    dpkg-reconfigure -f noninteractive tzdata && \
    apt-get clean

RUN apt update && apt install -y apache2 libapache2-mod-php7.2 php7.2 php7.2-dev php7.2-xml php7.2-zip php7.2-curl php7.2-cli openssl composer libcurl4-openssl-dev pkg-config libssl-dev
RUN pecl install mongodb && echo "extension=mongodb.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"` && echo "extension=mongodb.so" >> /etc/php/7.2/apache2/php.ini

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
ENV IPFSPICS_DB localhost:27017

ADD . .
RUN chmod -R 775 /var/www/
RUN composer install
