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
                mysql:5.7
        fi
        docker start ${appname}_db
        sleep 2

        docker run \
            -it --rm \
            --name ${appname} \
            -p 0.0.0.0:80:80 \
            --link ${appname}_db:db \
            -e MYSQL_HOST=${appname}_db \
            -e MYSQL_USER=${appname} \
            -e MYSQL_PASSWORD=${appname} \
            -e MYSQL_DATABASE=${appname} \
            -e ABXD_SALT=VMQeVLNlKXJPZxAf \
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
