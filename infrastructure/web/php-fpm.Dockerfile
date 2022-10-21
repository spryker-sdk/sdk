ARG SDK_VERSION

FROM spryker/php-sdk:${SDK_VERSION}
FROM php:8.0-fpm-alpine

CMD ["php-fpm"]
