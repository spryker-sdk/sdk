ARG SPRYKER_PARENT_IMAGE=spryker/php:8.0

FROM ${SPRYKER_PARENT_IMAGE} AS application-production-dependencies

USER spryker

COPY --chown=spryker:spryker composer.json composer.lock ${srcRoot}/
ARG SPRYKER_COMPOSER_MODE
ENV DATABASE_PASSWORD=password
ENV DATABASE_USER=user
ENV DATABASE_NAME=db
ENV DATABASE_URL="No DATABASE_URL set"

#@todo remove
COPY --chown=spryker:spryker FooTasks ${srcRoot}/FooTasks

COPY --chown=spryker:spryker auth.jso[n] ${srcRoot}/auth.json
RUN --mount=type=cache,id=composer,sharing=locked,target=/home/spryker/.composer/cache,uid=1000 \
  --mount=type=ssh,uid=1000 --mount=type=secret,id=secrets-env,uid=1000 \
    composer install --no-scripts --no-interaction ${SPRYKER_COMPOSER_MODE} -vvv
# ensure composer credentials are not leaked
RUN rm -f auth.json

FROM application-production-dependencies AS application-production-codebase

COPY --chown=spryker:spryker src ${srcRoot}/src
COPY --chown=spryker:spryker app ${srcRoot}/app
COPY --chown=spryker:spryker extension ${srcRoot}/extension
COPY --chown=spryker:spryker translations ${srcRoot}/translations
COPY --chown=spryker:spryker config ${srcRoot}/config
COPY --chown=spryker:spryker bin ${srcRoot}/bin
COPY --chown=spryker:spryker .env.dist ${srcRoot}/.env

RUN --mount=type=cache,id=composer,sharing=locked,target=/home/spryker/.composer/cache,uid=1000 \
  composer dump-autoload -o
ENV APP_ENV=prod
RUN bin/console cache:warmup

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
