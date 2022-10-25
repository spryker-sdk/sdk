FROM nginx:stable-alpine

COPY infrastructure/web/nginx/default.conf /etc/nginx/conf.d/default.conf
