#!/bin/bash

cd "$(dirname $0)"

task=$1 # More descriptive name
arg=$2
args=${*:2}

case $task in
    build)
        docker build -t nsmbot .
        ;;
    start)
        docker run \
            -it --rm \
            --name nsmbot \
            -p 0.0.0.0:80:80 \
            -v $PWD:/app \
            -v $PWD/data:/data \
            nsmbot
        ;;
    stop)
        docker stop nsmbot
        ;;
    shell)
        docker exec -i -t nsmbot bash
        ;;
    '')
        echo 'Usage: ./d action [params].'
        ;;
    *)
        echo 'Unknown action '$task'. For a list of the available actions, please use "help" action'
        ;;
esac
