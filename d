#!/bin/bash

cd "$(dirname $0)"

task=$1 # More descriptive name
arg=$2
args=${*:2}

appname=abxd

case $task in
    build)
        docker build -t ${appname} .
        ;;
    start)
        if ! docker inspect ${appname}_db > /dev/null 2> /dev/null; then
            mkdir -p data/mysql
            docker run -d \
                --name ${appname}_db \
                -e MYSQL_ROOT_PASSWORD=root \
                -e MYSQL_USER=${appname} \
                -e MYSQL_PASSWORD=${appname} \
                -e MYSQL_DATABASE=${appname} \
                -v $PWD/data/mysql:/var/lib/mysql \
                mysql:8.4.10
        fi
        docker start ${appname}_db

        # First init of an empty datadir takes ~30s, and the app user only exists
        # once it's done, so poll for it rather than guessing with a sleep.
        echo -n "Waiting for mysql"
        for i in $(seq 1 120); do
            docker exec ${appname}_db mysql -u${appname} --password=${appname} \
                -e 'SELECT 1' ${appname} > /dev/null 2>&1 && break
            echo -n .
            sleep 1
        done
        echo

        # vendor/ is gitignored, so the -v $PWD:/app below hides the copy that
        # `composer install` put in the image at build time.
        # HOME is /app for the app user, so point composer's caches elsewhere to
        # keep .cache/.config/.local out of the source tree.
        if [ ! -d vendor ]; then
            docker run --rm -v $PWD:/app -w /app \
                -e HOME=/tmp -e COMPOSER_HOME=/tmp/composer \
                ${appname} composer install
        fi

        docker run \
            -it --rm \
            --name ${appname} \
            -p 0.0.0.0:8000:8000 \
            --link ${appname}_db:db \
            -e MYSQL_HOST=${appname}_db \
            -e MYSQL_USER=${appname} \
            -e MYSQL_PASSWORD=${appname} \
            -e MYSQL_DATABASE=${appname} \
            -e ABXD_SALT=dev-salt-not-a-secret \
            -e ABXD_TURNSTILE_SITE_KEY=1x00000000000000000000AA \
            -e ABXD_TURNSTILE_SECRET_KEY=1x0000000000000000000000000000000AA \
            -e PHP_INI_SCAN_DIR=/etc/php/8.5/fpm/conf.d:/app/conf/dev \
            -v $PWD:/app \
            -v $PWD/data:/data \
            ${appname}
        ;;
    stop)
        docker stop ${appname}_db
        docker stop ${appname}
        ;;
    shell)
        docker exec -i -t ${appname} bash
        ;;
    dbshell)
        docker exec -ti ${appname}_db mysql -u ${appname} --password=${appname} ${appname}
        ;;
    loaddb)
        docker exec -i ${appname}_db mysql -u ${appname} --password=${appname} ${appname} < $arg
        ;;
    dumpdb)
        docker exec -i ${appname}_db mysqldump --password=root ${appname} > $arg
        ;;
    '')
        echo 'Usage: ./d action [params].'
        ;;
    *)
        echo 'Unknown action '$task'. For a list of the available actions, please use "help" action'
        ;;
esac
