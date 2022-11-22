ARG SDK_VERSION=latest
FROM spryker/php-sdk:${SDK_VERSION}

USER root

RUN rm -rf /data

RUN /usr/bin/install -d -m 777 /var/run/opcache/debug && docker-php-ext-enable xdebug
COPY infrastructure/debug/php/69-xdebug.ini /usr/local/etc/php/conf.d/69-xdebug.ini

RUN apk add autoconf && \
    apk --update add gcc make g++ zlib-dev && \
    pecl install xhprof && \
    docker-php-ext-enable xhprof

COPY infrastructure/debug/php/xhprof.ini /usr/local/etc/php/conf.d/docker-php-ext-xhprof.ini
COPY infrastructure/debug/sdk.sh /usr/bin/sdk

RUN chmod +x /usr/bin/sdk

ARG USER_UID
RUN if [[ ! -z "${USER_UID}" ]]; then \
        usermod -u "${USER_UID}" spryker; \
    fi

USER spryker

COPY infrastructure/debug/.bashrc /home/spryker/.bashrc

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
