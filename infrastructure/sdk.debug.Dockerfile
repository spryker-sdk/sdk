FROM spryker/php-sdk:latest

RUN /usr/bin/install -d -m 777 /var/run/opcache/debug
COPY infrastructure/debug/php/69-xdebug.ini /usr/local/etc/php/conf.d/69-xdebug.ini
