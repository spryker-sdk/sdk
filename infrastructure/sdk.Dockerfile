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

COPY --chown=spryker:spryker phpstan-bootstrap.php ${srcRoot}/phpstan-bootstrap.php
COPY --chown=spryker:spryker assets ${srcRoot}/assets
COPY --chown=spryker:spryker src ${srcRoot}/src
COPY --chown=spryker:spryker app ${srcRoot}/app
COPY --chown=spryker:spryker db ${srcRoot}/db
COPY --chown=spryker:spryker extension ${srcRoot}/extension
COPY --chown=spryker:spryker config ${srcRoot}/config
COPY --chown=spryker:spryker frontend ${srcRoot}/frontend
COPY --chown=spryker:spryker bin ${srcRoot}/bin
COPY --chown=spryker:spryker .env ${srcRoot}/.env
COPY --chown=spryker:spryker .env.prod ${srcRoot}/.env.prod
COPY --chown=spryker:spryker composer.json composer.lock package.json package-lock.json bootstrap.php phpstan-bootstrap.php ${srcRoot}/

WORKDIR ${srcRoot}

ENV APP_ENV=prod

RUN composer install --no-scripts --no-interaction --optimize-autoloader -vvv

RUN npm install

RUN composer dump-env prod

RUN bin/console cache:clear --no-debug

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
