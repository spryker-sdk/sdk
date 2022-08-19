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

# Set up default stadard for code sniffer
ARG PHPCS_STANDARD=./ruleset.xml
RUN vendor/bin/phpcs --config-set default_standard ${PHPCS_STANDARD}

USER spryker

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
