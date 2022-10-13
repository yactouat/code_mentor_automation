FROM php:8.1.8-fpm
# installing mailer system dependencies
RUN apt update && apt upgrade -y && apt install -y mailutils msmtp msmtp-mta
# installing PDO
RUN docker-php-ext-install pdo
# ! be sure not to push this image to a public registry as it may contain your email password
COPY ./docker/msmtprc /etc/msmtprc
# create system user ("udacity_sl_automation" with uid 1000)
RUN useradd -G www-data,root -u 1000 -d /home/udacity_sl_automation udacity_sl_automation
RUN mkdir /home/udacity_sl_automation && \
    chown -R udacity_sl_automation:udacity_sl_automation /home/udacity_sl_automation
# copy existing application directory contents
RUN mkdir /udacity_sl_automation
WORKDIR /udacity_sl_automation
COPY . .
# shared PHP conf
RUN mv /udacity_sl_automation/docker/php/shared.ini /usr/local/etc/php/conf.d/shared.ini
# error reporting is suitable for DEV here
RUN mv /udacity_sl_automation/docker/php/dev.error-reporting.ini /usr/local/etc/php/conf.d/error_reporting.ini
# copy existing application directory permissions
COPY --chown=udacity_sl_automation:udacity_sl_automation ./ /udacity_sl_automation
# changing user (because cannot run Composer as root inside container)
USER udacity_sl_automation

EXPOSE 9000