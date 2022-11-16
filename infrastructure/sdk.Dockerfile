ARG SPRYKER_PARENT_IMAGE=spryker/php:8.0

FROM ${SPRYKER_PARENT_IMAGE} AS application-production-dependencies

USER root
RUN apk update \
    && apk add --no-cache \
    curl \
    git \
    graphviz \
    nodejs \
    npm \
    && npm install -g npm@8.4.1

RUN git config --add --system safe.directory /project

ARG SPRYKER_COMPOSER_MODE

FROM application-production-dependencies AS application-production-codebase

RUN chown spryker:spryker ${srcRoot}

USER spryker
# Authorize SSH Host
RUN mkdir -p /home/spryker/.ssh && \
    chmod 0700 /home/spryker/.ssh && \
    ssh-keyscan github.com > /home/spryker/.ssh/known_hosts

COPY phpstan-bootstrap.php ${srcRoot}/phpstan-bootstrap.php
COPY assets ${srcRoot}/assets
COPY src ${srcRoot}/src
COPY app ${srcRoot}/app
COPY db ${srcRoot}/db
COPY extension ${srcRoot}/extension
COPY config ${srcRoot}/config
COPY frontend ${srcRoot}/frontend
COPY bin ${srcRoot}/bin
COPY .env ${srcRoot}/.env
COPY .env.prod ${srcRoot}/.env.prod
COPY composer.json composer.lock package.json package-lock.json bootstrap.php phpstan-bootstrap.php ${srcRoot}/

WORKDIR ${srcRoot}

ENV APP_ENV=prod

RUN composer install --no-scripts --no-interaction --optimize-autoloader -vvv

RUN npm install

RUN composer dump-env prod

RUN bin/console cache:clear --no-debug

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
