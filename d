#!/bin/bash

cd "$(dirname $0)"

task=$1 # More descriptive name
arg=$2
args=${*:2}

case $task in
    build)
        docker build -t abxd .
        ;;
    start)
        if ! docker inspect abxd_db > /dev/null 2> /dev/null; then
            mkdir -p data/mysql
            docker run -d \
                --name abxd_db \
                -e MYSQL_ROOT_PASSWORD=root \
                -e MYSQL_DATABASE=abxd \
                -e MYSQL_USER=abxd \
                -e MYSQL_PASSWORD=abxd \
                -v $PWD/data/mysql:/var/lib/mysql \
                mysql:5.7
        fi
        docker start abxd_db
        sleep 2

        docker run \
            -it --rm \
            --name abxd \
            -p 0.0.0.0:80:80 \
            --link abxd_db:db \
            -v $PWD:/app \
            -v $PWD/data:/data \
            abxd
        ;;
    stop)
        docker stop abxd_db
        docker stop abxd
        ;;
    shell)
        docker exec -i -t abxd bash
        ;;
    dbshell)
        docker exec -ti abxd_db mysql -u abxd --password=abxd abxd
        ;;
    loaddb)
        docker exec -i abxd_db mysql -u abxd --password=abxd abxd < $arg
        ;;
    dumpdb)
        docker exec -i abxd_db mysqldump --password=root abxd > $arg
        ;;
    upgrade)
        docker exec -i abxd sh -c 'echo UPGRADING DB... && php webroot/upgrade.php && echo RECALCULATING STATISTICS... && php webroot/index.php /recalc'
        ;;
    '')
        echo 'Usage: ./d action [params].'
        ;;
    *)
        echo 'Unknown action '$task'. For a list of the available actions, please use "help" action'
        ;;
esac
