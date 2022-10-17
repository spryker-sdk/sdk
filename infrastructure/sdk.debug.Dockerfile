ARG SDK_VERSION=latest
FROM spryker/php-sdk:${SDK_VERSION}

USER root

ARG USER_UID
RUN if [[ ! -z "${USER_UID}" ]]; then \
        usermod -u "${USER_UID}" spryker \
        && find /home -not -user "${USER_UID}" -exec chown -h spryker {} \; \
        && find /data/var /data/vendor -user root -exec chown -h spryker {} \; ;\
    fi

RUN /usr/bin/install -d -m 777 /var/run/opcache/debug
COPY --chown=spryker:spryker infrastructure/debug/php/69-xdebug.ini /usr/local/etc/php/conf.d/69-xdebug.ini

RUN apk add autoconf && \
    apk --update add gcc make g++ zlib-dev && \
    pecl install xhprof && \
    docker-php-ext-enable xhprof

COPY infrastructure/debug/php/xhprof.ini /usr/local/etc/php/conf.d/docker-php-ext-xhprof.ini

COPY --chown=spryker:spryker infrastructure/debug/.bashrc /home/spryker/.bashrc
COPY --chown=spryker:spryker infrastructure/debug/sdk.sh /usr/bin/sdk
RUN chmod +x /usr/bin/sdk

USER spryker

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
