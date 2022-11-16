ARG SDK_VERSION=latest
FROM spryker/php-sdk:${SDK_VERSION}

USER spryker

ENTRYPOINT ["/bin/bash", "-c", "/data/bin/console $@", "--"]
