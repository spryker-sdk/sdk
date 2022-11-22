ARG SDK_VERSION=latest
FROM spryker/php-sdk:${SDK_VERSION}

USER spryker
ARG USER_UID
RUN if [[ ! -z "${USER_UID}" ]]; then \
        usermod -u "${USER_UID}" spryker; \
    fi

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
