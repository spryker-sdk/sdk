ARG SDK_VERSION=latest
FROM spryker/php-sdk:${SDK_VERSION}

USER root

ARG USER_UID
RUN if [[ ! -z "${USER_UID}" ]]; then \
        usermod -u "${USER_UID}" spryker; \
        chown -R spryker /data/var; \
        chown -R spryker /data/vendor; \
    fi

USER spryker

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
