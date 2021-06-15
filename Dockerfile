FROM ubuntu:bionic
ENV TZ=Asia/Almaty
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
LABEL authors="Temirlan Kasen temirlankasen@gmail.com"

RUN set -x \
    && apt-get update \
    && apt-get install -y \
        ca-certificates \
        wget \
        locales \
        gnupg2 \
    && rm -rf /var/lib/apt/lists/* \
    && useradd -s /bin/bash -m push \
    && echo "deb http://nginx.org/packages/ubuntu/ bionic nginx" > /etc/apt/sources.list.d/nginx.list \
    && echo "deb-src http://nginx.org/packages/ubuntu/ bionic nginx" >> /etc/apt/sources.list.d/nginx.list \
    && echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu bionic main" > /etc/apt/sources.list.d/php.list \
    && echo "deb-src http://ppa.launchpad.net/ondrej/php/ubuntu bionic main" >> /etc/apt/sources.list.d/php.list \
    && apt-key adv --keyserver keyserver.ubuntu.com --recv-keys ABF5BD827BD9BF62 \
    && apt-key adv --keyserver keyserver.ubuntu.com --recv-keys 4F4EA0AAE5267A6C \
    && apt update \
    && apt install -y \
        nginx \
        php7.4 \
        php7.4-cli \
        php7.4-common \
        php7.4-curl \
        php7.4-fpm \
        php7.4-json \
        php7.4-opcache \
        php7.4-pgsql \
        php7.4-xml \
        php7.4-bcmath \
        supervisor \
        vim \
        iputils-ping \
        composer \
        curl \
        git \
        zip \
        sudo \
        apt-transport-https

COPY project /opt/push
COPY nginx /usr/share/container_config/nginx
COPY supervisor /usr/share/container_config/supervisor
COPY init.sh /usr/local/bin/init.sh
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

RUN set -x\
    && chown -R push:push /opt/push \
    && cd /opt/push \
    && sudo -u push composer install \
    && chmod +x /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/init.sh

ENV PUSH_TIMEZONE "Utc"
ENV QUEUE_HOST "http://queue"
ENV PG_HOST postgresql
ENV PG_PORT 5432
ENV PG_DATABASE microservices
ENV PG_USER postgres
ENV PG_PASSWORD postgres
ENV PHP_PM_MAX_CHILDREN 10
ENV PHP_PM_MAX_REQUESTS 500
ENV PHP_MAX_EXECUTION_TIME 60
ENV APPLE_FILE "apple.pem"
ENV APPLE_BUNDLE_ID "app"
ENV APPLE_URL "https://api.push.apple.com:443/3/device/"
ENV GOOGLE_FILE "google.json"
ENV GOOGLE_URL "https://fcm.googleapis.com/v1/projects/myproject-b5ae1/messages:send"
ENV HUAWEI_FILE "huawei.json"
ENV HUAWEI_URL "https://push-api.cloud.huawei.com/v1/%s/messages:send"
ENV CHUNK_SIZE 300

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
