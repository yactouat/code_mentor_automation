FROM composer:2.4.2 as vendor
WORKDIR /udacity_sl_automation
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --quiet


FROM php:8.1.8-fpm
# installing mailer system dependencies
RUN apt update && apt upgrade -y && apt install -y mailutils msmtp msmtp-mta nginx
# installing PDO
RUN docker-php-ext-install pdo pdo_mysql
# configuring mailer
# ! be sure not to push this image to a public registry as it may contain your email password
COPY ./docker/msmtp/msmtprc /etc/msmtprc
# configuring nginx
COPY ./docker/php/nginx.conf /etc/nginx/nginx.conf
# create system user ("udacity_sl_automation" with uid 1000)
RUN useradd -G www-data,root -u 1000 -d /home/udacity_sl_automation udacity_sl_automation
RUN mkdir /home/udacity_sl_automation && \
    chown -R udacity_sl_automation:udacity_sl_automation /home/udacity_sl_automation
# copy existing application directory contents
WORKDIR /var/www
COPY . .
# copy vendor dependencies
COPY --from=vendor /udacity_sl_automation /var/www/vendor
# shared PHP conf
RUN mv /var/www/docker/php/shared.ini /usr/local/etc/php/conf.d/shared.ini
# error reporting is suitable for DEV here
RUN mv /var/www/docker/php/dev.ini /usr/local/etc/php/conf.d/dev.ini

# copy existing application directory permissions
COPY --chown=udacity_sl_automation:udacity_sl_automation ./ /var/www

ENTRYPOINT ["sh", "-c", "php-fpm -D && mkdir -p /var/www/data/logs/php && chgrp -R www-data /var/www/data/logs/php && nginx -g 'daemon off;'"]