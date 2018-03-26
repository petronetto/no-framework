FROM nginx:1.12-alpine

ENV TIMEZONE="America/Sao_Paulo"

# Install packages
RUN set -ex; \
    apk --update add --no-cache tzdata; \
    rm -rf /var/cache/apk/*; \
    cp /usr/share/zoneinfo/${TIMEZONE} /etc/localtime; \
    echo ${TIMEZONE} > /etc/timezone; \
    apk del tzdata; \
    rm -rf /var/cache/apk/*; \
    mkdir /app; \
    chown -R nginx:nginx /app; \
    chown -R nginx:nginx /var/cache/nginx /etc/nginx; \
    chmod -R g=u /var/cache/nginx /etc/nginx

COPY nginx.conf /etc/nginx/nginx.conf
COPY nginx.vh.default.conf /etc/nginx/conf.d/default.conf

RUN procs=$(cat /proc/cpuinfo | grep processor | wc -l) && \
    sed -i -e "s/worker_processes 4/worker_processes $procs/" /etc/nginx/nginx.conf

WORKDIR /app

USER nginx
