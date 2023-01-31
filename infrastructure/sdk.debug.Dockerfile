ARG SDK_VERSION=latest
FROM spryker/php-sdk:${SDK_VERSION}

USER root

########################################
# New Relic Extension
# It's already in the core image.
# todo :: For tests. Please move it to the sdk.Dockerfile
########################################
ARG NEWRELIC_ENABLED
ENV NEWRELIC_ENABLED ${NEWRELIC_ENABLED}

ARG NEWRELIC_LICENSE
ENV NEWRELIC_LICENSE ${NEWRELIC_LICENSE}

ARG NEWRELIC_APPNAME='Spryker Project'
ENV NEWRELIC_APPNAME ${NEWRELIC_APPNAME}

ARG NEWRELIC_LOGLEVEL
ENV NEWRELIC_LOGLEVEL ${NEWRELIC_LOGLEVEL}

COPY infrastructure/newrelic/newrelic.ini /usr/local/etc/php/disabled/

RUN if  [ ${NEWRELIC_ENABLED} = true ]; then \
    cp /usr/local/etc/php/disabled/newrelic.ini /usr/local/etc/php/conf.d/90-newrelic.ini \
;fi

RUN rm -rf /data

ARG USER_UID
RUN if [[ ! -z "${USER_UID}" ]]; then \
        usermod -u "${USER_UID}" spryker; \
    fi

RUN /usr/bin/install -d -m 777 /var/run/opcache/debug && docker-php-ext-enable xdebug
COPY infrastructure/debug/php/69-xdebug.ini /usr/local/etc/php/conf.d/69-xdebug.ini

COPY infrastructure/debug/php/xhprof.ini /usr/local/etc/php/conf.d/docker-php-ext-xhprof.ini
COPY infrastructure/debug/sdk.sh /usr/bin/sdk

RUN chmod +x /usr/bin/sdk

USER spryker

COPY infrastructure/debug/.bashrc /home/spryker/.bashrc

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
