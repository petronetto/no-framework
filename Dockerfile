FROM alpine:3.7

LABEL maintainer="Juliano Petronetto <juliano@petronetto.com.br>"

ENV BUILD_DEPS="autoconf g++ make php7-dev openssl-dev php7-pear tzdata" \
    LC_ALL=en_US.UTF-8 \
    LANG=en_US.UTF-8 \
# XDebug configs
    XDEBUG_ENABLE=0 \
    XDEBUG_REMOTE_PORT=9000 \
    XDEBUG_HOST=localhost \
    XDEBUG_LOG=/var/log/xdebug/xdebug.log \
# New Relic
    NEWRELIC_VERSION=8.1.0.209 \
    NEWRELIC_ENABLED=0 \
    NEWRELIC_APP_NAME="PHP Application" \
    NEWRELIC_LICENSE= \
    NEWRELIC_LOG_FILE=/var/log/newrelic/newrelic.log \
    NEWRELIC_LOG_LEVEL=info \
    NEWRELIC_DAEMON_LOG_FILE=/var/log/newrelic/daemon.log \
    NEWRELIC_DAEMON_LOG_LEVEL=info \
# PHP Configs
    PHP_MEMORY_LIMIT=256M \
    PHP_PRECISION=-1 \
    PHP_OUTPUT_BUFFERING=4096 \
    PHP_SERIALIZE_PRECISION=-1 \
    PHP_MAX_EXECUTION_TIME=60 \
    PHP_ERROR_REPORTING=E_ALL \
    PHP_DISPLAY_ERRORS=0 \
    PHP_DISPLAY_STARTUP_ERRORS=0 \
    PHP_TRACK_ERRORS=0 \
    PHP_LOG_ERRORS=1 \
    PHP_LOG_ERRORS_MAX_LEN=10240 \
    PHP_ACCESS_LOG=/var/log/php/access.log \
    PHP_POST_MAX_SIZE=20M \
    PHP_MAX_UPLOAD_FILESIZE=10M \
    PHP_MAX_FILE_UPLOADS=20 \
    PHP_MAX_INPUT_TIME=60 \
    PHP_VARIABLES_ORDER=EGPCS \
    PHP_REQUEST_ORDER=GP \
    PHP_OPCACHE_ENABLE=1 \
    PHP_OPCACHE_ENABLE_CLI=0 \
    PHP_OPCACHE_MEMORY_CONSUMPTION=128 \
    PHP_OPCACHE_INTERNED_STRINGS_BUFFER=32 \
    PHP_OPCACHE_MAX_ACCELERATED_FILES=10000 \
    PHP_OPCACHE_USE_CWD=1 \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=1 \
    PHP_OPCACHE_REVALIDATE_FREQ=2 \
    PHP_OPCACHE_ENABLE_FILE_OVERRIDE=0 \
    PHP_ZEND_ASSERTIONS=-1 \
    PHP_PM_ERROR_LOG=/var/log/php/fpm-error.log \
    PHP_PM=ondemand \
    PHP_PM_MAX_CHILDREN=100 \
    PHP_PM_START_SERVERS=20 \
    PHP_PM_MIN_SPARE_SERVERS=20 \
    PHP_PM_MAX_SPARE_SERVERS=20 \
    PHP_PM_PROCESS_IDLE_TIMEOUT=60s \
    PHP_PM_MAX_REQUESTS=500

# Copying the config files
COPY ./ /app

# Installing necessary packages
RUN set -ex; \
    apk --update upgrade --no-cache; \
    apk add --no-cache --virtual .build-deps ${BUILD_DEPS}; \
    apk add --no-cache \
        curl \
        bash \
        ca-certificates \
        openssl \
        php7 \
        php7-dom \
        php7-fpm \
        php7-cgi \
        php7-mbstring \
        php7-mcrypt \
        php7-opcache \
        php7-pdo \
        php7-pdo_pgsql \
        php7-pdo_sqlite \
        php7-fileinfo \
        php7-xml \
        php7-simplexml \
        php7-xmlreader \
        php7-xmlwriter \
        php7-phar \
        php7-openssl \
        php7-json \
        php7-curl \
        php7-ctype \
        php7-session \
        php7-zlib \
        php7-tokenizer \
        php7-xdebug; \
# Configuring timezones
    cp /usr/share/zoneinfo/Etc/UTC /etc/localtime; \
    echo "UTC" | tee /etc/timezone; \
# install PHP driver for mongodb
    pecl install -f mongodb; \
    echo 'extension=mongodb.so' > /etc/php7/conf.d/00_mongodb.ini; \
# Create application folder
    mkdir -p /app; \
# Creating www-data user and group
    addgroup -g 1000 -S www-data; \
	adduser -u 1000 -D -S -h /app -s /sbin/nologin -G www-data www-data; \
# Installing New Relic Agent
    mkdir /opt && cd /opt; \
    curl -LOk http://download.newrelic.com/php_agent/archive/${NEWRELIC_VERSION}/newrelic-php5-${NEWRELIC_VERSION}-linux-musl.tar.gz; \
    gzip -dc newrelic-php5-${NEWRELIC_VERSION}-linux-musl.tar.gz | tar xf -;\
    ./newrelic-php5-${NEWRELIC_VERSION}-linux-musl/newrelic-install install;\
# Installing Composer
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer; \
# Compying PHP config files
    cp -a /app/docker/php/php.ini /etc/php7/php.ini; \
    cp -a /app/docker/php/www.conf /etc/php7/php-fpm.d/www.conf; \
    cp -a /app/docker/php/php-fpm.conf /etc/php7/php-fpm.conf; \
    cp -a /app/docker/php/xdebug.ini /etc/php7/conf.d/xdebug.ini; \
    cp -a /app/docker/php/newrelic.ini /etc/php7/conf.d/newrelic.ini; \
# Cleaning
    apk del .build-deps; \
    rm -rf /var/cache/apk/*; \
    rm -rf /app/docker;

WORKDIR /app

# The container shouldn't run as root, it can be a potential
# security issue, obviously, depending the enviroment
# but to ensure the security, we aren't going to run as root.
USER www-data

CMD ["/usr/sbin/php-fpm7"]
