FROM ubuntu:26.04

ENV DEBIAN_FRONTEND=noninteractive 

RUN apt-get update && apt-get install -y nano nginx php-mysql php-fpm php-curl php-xml php-mbstring supervisor git curl unzip php-gd msmtp msmtp-mta

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN ln -sf /app/conf/nginx.conf /etc/nginx/nginx.conf && \
    ln -sf /app/conf/php-fpm.conf /etc/php/8.5/fpm/php-fpm.conf && \
    ln -sf /app/conf/php.ini /etc/php/8.5/fpm/php.ini

WORKDIR /app

# Install dependencies
COPY composer.json composer.lock /app/
RUN composer install

# Install app source
COPY . /app

# Ubuntu >=24.04 ships a default "ubuntu" user occupying uid/gid 1000.
RUN userdel -r ubuntu && \
    groupadd -r app -g 1000 && \
    useradd -u 1000 -r -g app -d /app -s /bin/bash -c "Docker image user" app && \
    chown -R app:app /app /var/lib/nginx && \ 
    ln -sf /tmp/msmtprc /etc/msmtprc

USER 1000

VOLUME /var/lib/nginx
EXPOSE 8000
CMD ["/app/conf/launch.sh"]
