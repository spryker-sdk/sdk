ARG SPRYKER_PARENT_IMAGE=spryker/php:8.0

FROM ${SPRYKER_PARENT_IMAGE} AS application-production-dependencies

USER root
RUN apk update \
    && apk add --no-cache \
    curl \
    git \
    nodejs \
    npm \
    && npm install -g npm@8.4.1

COPY --chown=spryker:spryker composer.json composer.lock package.json package-lock.json ${srcRoot}/
ARG SPRYKER_COMPOSER_MODE

RUN --mount=type=cache,id=composer,sharing=locked,target=/home/spryker/.composer/cache,uid=1000 \
  --mount=type=ssh,uid=1000 --mount=type=secret,id=secrets-env,uid=1000 \
    composer install --no-scripts --no-interaction ${SPRYKER_COMPOSER_MODE} -vvv

RUN --mount=type=cache,id=npm,sharing=locked,target=/home/spryker/.npm,uid=1000 \
    --mount=type=ssh,uid=1000 --mount=type=secret,id=secrets-env,uid=1000 \
    npm install

FROM application-production-dependencies AS application-production-codebase

ARG ssh_prv_key
ARG ssh_pub_key

# Authorize SSH Host
RUN mkdir -p /root/.ssh && \
    chmod 0700 /root/.ssh && \
    ssh-keyscan github.com > /root/.ssh/known_hosts

# Add the keys and set permissions
RUN echo "$ssh_prv_key" > /root/.ssh/id_rsa && \
    echo "$ssh_pub_key" > /root/.ssh/id_rsa.pub && \
    chmod 600 /root/.ssh/id_rsa && \
    chmod 600 /root/.ssh/id_rsa.pub

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

RUN --mount=type=cache,id=composer,sharing=locked,target=/home/spryker/.composer/cache,uid=1000 \
  composer dump-autoload -o
ENV APP_ENV=prod

RUN bin/console cache:warmup && \
    bin/console sdk:init:sdk && \
    bin/console sdk:update:all

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
