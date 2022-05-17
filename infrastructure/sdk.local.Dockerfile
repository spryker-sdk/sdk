FROM spryker/php-sdk:latest

USER root

ARG USER_UID
RUN if [[ ! -z "$USER_UID" ]]; then \
        usermod -u ${USER_UID} spryker \
        && find /home -user 1000 -exec chown -h spryker {} \; \
        && find /data/var /data/vendor -user root -exec chown -h spryker {} \; ;\
    fi

USER spryker

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
