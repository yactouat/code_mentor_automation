FROM php:8.1.8-fpm
# installing mailer system dependencies
RUN apt update && apt upgrade -y && apt install -y mailutils msmtp msmtp-mta
# ! be sure not to push this image to a public registry as it may contain your email password
COPY ./docker/msmtprc /etc/msmtprc
# create system user ("udacity_nd_sl" with uid 1000)
RUN useradd -G www-data,root -u 1000 -d /home/udacity_nd_sl udacity_nd_sl
RUN mkdir /home/udacity_nd_sl && \
    chown -R udacity_nd_sl:udacity_nd_sl /home/udacity_nd_sl
# copy existing application directory contents
RUN mkdir /udacity_nd_sl
WORKDIR /udacity_nd_sl
COPY . .
# shared PHP conf
RUN mv /udacity_nd_sl/docker/php/shared.ini /usr/local/etc/php/conf.d/shared.ini
# error reporting is suitable for DEV here
RUN mv /udacity_nd_sl/docker/php/dev.error-reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini
# copy existing application directory permissions
COPY --chown=udacity_nd_sl:udacity_nd_sl ./ /udacity_nd_sl
# changing user (because cannot run Composer as root inside container)
USER udacity_nd_sl

EXPOSE 9000