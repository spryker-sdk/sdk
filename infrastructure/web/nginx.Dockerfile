FROM nginx:stable-alpine

COPY infrastructure/web/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY public/index.php /data/public/index.php

RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

EXPOSE 80 443
