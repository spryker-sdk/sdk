ARG SDK_VERSION=latest
FROM spryker/php-sdk:${SDK_VERSION}

USER root

# To enable docker management from the SDK container
# For POC. Later in must be done automatically.
# !!! Do not merge it to master
RUN apk add --update docker openrc

ARG USER_UID
RUN if [[ ! -z "${USER_UID}" ]]; then \
        usermod -u "${USER_UID}" spryker; \
    fi \

RUN usermod -a -G docker spryker

USER spryker

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
