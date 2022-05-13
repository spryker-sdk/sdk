#DOCKER_BUILDKIT=1 docker build -f ./infrastructure/sdk.Dockerfile --build-arg UID=$(id -u) --build-arg GID=$(id -g) -t spryker/php-sdk:latest . && docker build -f ./infrastructure/sdk.debug.Dockerfile --build-arg UID=$(id -u) --build-arg GID=$(id -g) -t spryker/php-sdk-debug:latest .
ARG SPRYKER_PARENT_IMAGE=spryker/php:8.0

FROM ${SPRYKER_PARENT_IMAGE} AS application-production-dependencies
ARG UID=1000
ARG GID=1000

USER root
RUN apk update \
    && apk add --no-cache \
    curl \
    git \
    nodejs \
    npm \
    && npm install -g npm@8.4.1

RUN usermod -a -G spryker spryker \
    && usermod -g spryker spryker \
    && usermod -u ${UID} spryker \
    && groupmod -g ${GID} spryker \
    && chown spryker:spryker -R ${srcRoot}

USER spryker

COPY --chown=spryker:spryker composer.json composer.lock package.json package-lock.json ${srcRoot}/
ARG SPRYKER_COMPOSER_MODE

RUN composer install --no-scripts --no-interaction ${SPRYKER_COMPOSER_MODE} -vvv
RUN npm install

FROM application-production-dependencies AS application-production-codebase

RUN chown spryker:spryker ${srcRoot}

COPY --chown=spryker:spryker phpstan-bootstrap.php ${srcRoot}/phpstan-bootstrap.php
COPY --chown=spryker:spryker src ${srcRoot}/src
COPY --chown=spryker:spryker app ${srcRoot}/app
COPY --chown=spryker:spryker db ${srcRoot}/db
COPY --chown=spryker:spryker extension ${srcRoot}/extension
COPY --chown=spryker:spryker config ${srcRoot}/config
COPY --chown=spryker:spryker frontend ${srcRoot}/frontend
COPY --chown=spryker:spryker bin ${srcRoot}/bin
COPY --chown=spryker:spryker .env.dist ${srcRoot}/.env

RUN composer dump-autoload -o
ENV APP_ENV=prod

RUN bin/console sdk:init:sdk && \
    bin/console cache:warmup

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
