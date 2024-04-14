FROM nginx:1.17-alpine

COPY ./docker/nginx/conf/server.conf /etc/nginx/conf.d/default.conf
COPY ./app/frontend /var/www

WORKDIR /var/www/