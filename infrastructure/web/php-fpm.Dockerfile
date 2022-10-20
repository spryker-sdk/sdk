ARG SDK_VERSION

FROM spryker/php-sdk:${SDK_VERSION} as sdk
FROM php:8.0-fpm-alpine

RUN addgroup -S spryker && adduser -S spryker -G spryker

COPY --chown=spryker --from=sdk /data /data
COPY --chown=spryker public/index.php /data/public/index.php

WORKDIR /data

USER spryker

RUN rm -rf var/cache/${APP_ENV} && \
    mkdir -p var/cache/${APP_ENV} && \
    chmod -R 777 var/cache/${APP_ENV} var/log && \
    bin/console cache:clear

CMD ["php-fpm"]
