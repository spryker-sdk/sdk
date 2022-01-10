ARG SPRYKER_PARENT_IMAGE=spryker/php:8.0

FROM ${SPRYKER_PARENT_IMAGE} AS application-production-dependencies

USER root
RUN apk update \
    && apk add --no-cache \
    curl \
    git

USER spryker

COPY --chown=spryker:spryker composer.json composer.lock ${srcRoot}/
ARG SPRYKER_COMPOSER_MODE

#COPY --chown=spryker:spryker auth.json auth.json
RUN --mount=type=cache,id=composer,sharing=locked,target=/home/spryker/.composer/cache,uid=1000 \
  --mount=type=ssh,uid=1000 --mount=type=secret,id=secrets-env,uid=1000 \
    composer install --no-scripts --no-interaction ${SPRYKER_COMPOSER_MODE} -vvv

FROM application-production-dependencies AS application-production-codebase

RUN chown spryker:spryker ${srcRoot}
COPY --chown=spryker:spryker src ${srcRoot}/src
COPY --chown=spryker:spryker app ${srcRoot}/app
COPY --chown=spryker:spryker db ${srcRoot}/db
COPY --chown=spryker:spryker extension ${srcRoot}/extension
COPY --chown=spryker:spryker config ${srcRoot}/config
COPY --chown=spryker:spryker bin ${srcRoot}/bin
COPY --chown=spryker:spryker .env.dist ${srcRoot}/.env
COPY --chown=spryker:spryker infrastructure/entrypoint.sh /

RUN chmod +x /entrypoint.sh
RUN --mount=type=cache,id=composer,sharing=locked,target=/home/spryker/.composer/cache,uid=1000 \
  composer dump-autoload -o
ENV APP_ENV=prod

RUN bin/console sdk:init:sdk && \
    bin/console cache:warmup
USER root
ENTRYPOINT ["/entrypoint.sh"]
