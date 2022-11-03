ARG SDK_VERSION

FROM php:8.0-fpm-alpine

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

ARG USER_UID=1000
ARG GROUP_UID=1000

RUN deluser www-data && addgroup -g ${GROUP_UID} -S www-data && adduser -u ${USER_UID} -D -S -G www-data www-data

CMD ["php-fpm"]
