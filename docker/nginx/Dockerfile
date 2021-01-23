FROM nginx:1.19-alpine

COPY docker/nginx/nginx.conf /etc/nginx/
COPY docker/nginx/default.conf /etc/nginx/conf.d/
COPY docker/nginx/nginxconfig.io /etc/nginx/nginxconfig.io
RUN ls -hal /etc/nginx