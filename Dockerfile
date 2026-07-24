# Dependencies are resolved in a stage of their own, so that composer -- and the
# curl/unzip it needs -- never exist in the image that faces the internet. The
# official image also saves fetching the installer over a pipe into php, which
# was unverified.
FROM composer:2 AS deps

WORKDIR /app
COPY composer.json composer.lock /app/
RUN composer install --no-dev --no-scripts --no-progress --prefer-dist


FROM ubuntu:26.04

ENV DEBIAN_FRONTEND=noninteractive

# No nano/git/curl/unzip: nothing at runtime needs them, and each one is a rung
# on the ladder out of a code-execution bug.
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        nginx supervisor msmtp msmtp-mta ca-certificates \
        php-fpm php-cli php-mysql php-curl php-xml php-mbstring php-gd && \
    rm -rf /var/lib/apt/lists/*

RUN ln -sf /app/conf/nginx.conf /etc/nginx/nginx.conf && \
    ln -sf /app/conf/php-fpm.conf /etc/php/8.5/fpm/php-fpm.conf && \
    ln -sf /app/conf/php.ini /etc/php/8.5/fpm/php.ini

WORKDIR /app

COPY --from=deps /app/vendor /app/vendor

# Install app source
COPY . /app

# Ubuntu >=24.04 ships a default "ubuntu" user occupying uid/gid 1000.
RUN userdel -r ubuntu && \
    groupadd -r app -g 1000 && \
    useradd -u 1000 -r -g app -d /app -s /bin/bash -c "Docker image user" app && \
    ln -sf /tmp/msmtprc /etc/msmtprc && \
    chown -R root:app /app && \
    chmod -R g-w /app

USER 1000

EXPOSE 8000
CMD ["/app/conf/launch.sh"]
