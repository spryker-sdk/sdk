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

COPY --chown=spryker:spryker composer.json composer.lock package.json package-lock.json bootstrap.php ${srcRoot}/
ARG SPRYKER_COMPOSER_MODE

RUN --mount=type=cache,id=composer,sharing=locked,target=/home/spryker/.composer/cache,uid=1000 \
  --mount=type=ssh,uid=1000 --mount=type=secret,id=secrets-env,uid=1000 \
    composer install --no-scripts --no-interaction ${SPRYKER_COMPOSER_MODE} -vvv

RUN --mount=type=cache,id=npm,sharing=locked,target=/home/spryker/.npm,uid=1000 \
    --mount=type=ssh,uid=1000 --mount=type=secret,id=secrets-env,uid=1000 \
    npm install

FROM application-production-dependencies AS application-production-codebase

RUN chown spryker:spryker ${srcRoot}

# Authorize SSH Host
RUN mkdir -p /home/spryker/.ssh && \
    chmod 0700 /home/spryker/.ssh && \
    ssh-keyscan github.com > /home/spryker/.ssh/known_hosts

COPY --chown=spryker:spryker phpstan-bootstrap.php ${srcRoot}/phpstan-bootstrap.php

COPY --chown=spryker:spryker src ${srcRoot}/src
COPY --chown=spryker:spryker app ${srcRoot}/app
COPY --chown=spryker:spryker db ${srcRoot}/db
COPY --chown=spryker:spryker extension ${srcRoot}/extension
COPY --chown=spryker:spryker config ${srcRoot}/config
COPY --chown=spryker:spryker frontend ${srcRoot}/frontend
COPY --chown=spryker:spryker bin ${srcRoot}/bin
COPY --chown=spryker:spryker .env.prod ${srcRoot}/.env
COPY --chown=spryker:spryker docker ${srcRoot}/docker

RUN --mount=type=cache,id=composer,sharing=locked,target=/home/spryker/.composer/cache,uid=1000 \
  composer dump-autoload -o

ENV APP_ENV=prod

RUN bin/console cache:warmup

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
