ARG SPRYKER_PARENT_IMAGE=spryker/php:8.0

FROM ${SPRYKER_PARENT_IMAGE} AS application-production-dependencies

USER spryker

COPY --chown=spryker:spryker composer.json composer.lock ${srcRoot}/
ARG SPRYKER_COMPOSER_MODE

COPY --chown=spryker:spryker auth.json auth.json
RUN --mount=type=cache,id=composer,sharing=locked,target=/home/spryker/.composer/cache,uid=1000 \
  --mount=type=ssh,uid=1000 --mount=type=secret,id=secrets-env,uid=1000 \
    composer install --no-scripts --no-interaction ${SPRYKER_COMPOSER_MODE} -vvv
# ensure composer credentials are not leaked
RUN rm -f auth.json

FROM application-production-dependencies AS application-production-codebase

COPY --chown=spryker:spryker src ${srcRoot}/src
COPY --chown=spryker:spryker app ${srcRoot}/app
COPY --chown=spryker:spryker db ${srcRoot}/db
COPY --chown=spryker:spryker extension ${srcRoot}/extension
COPY --chown=spryker:spryker config ${srcRoot}/config
COPY --chown=spryker:spryker bin ${srcRoot}/bin
COPY --chown=spryker:spryker .env.dist ${srcRoot}/.env

RUN mkdir reports/

RUN --mount=type=cache,id=composer,sharing=locked,target=/home/spryker/.composer/cache,uid=1000 \
  composer dump-autoload -o
ENV APP_ENV=prod

ARG ACCESS_TOKEN
ENV ACCESS_TOKEN=${ACCESS_TOKEN:-'default'}

ARG ORGANIZATION_NAME
ENV ORGANIZATION_NAME=${ORGANIZATION_NAME:-'default'}

ARG REPOSITORY_NAME
ENV REPOSITORY_NAME=${REPOSITORY_NAME:-'default'}

RUN bin/console sdk:init:sdk && \
    bin/console cache:warmup && \
    bin/console sdk:setting:set task_dirs vendor/spryker-sdk/evaluator/src/Evaluate/Infrastructure/Task/Analyze && \
    bin/console sdk:setting:set task_dirs vendor/spryker-sdk/evaluator/src/Evaluate/Infrastructure/Task/Report && \
    bin/console sdk:setting:set task_dirs vendor/spryker-sdk/evaluator/src/Upgrade/Application/Task/UpgradeTask && \
    bin/console sdk:init:sdk

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
