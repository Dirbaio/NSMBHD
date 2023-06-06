#!/bin/bash

set -euxo pipefail

IMAGE=dirba.io/nsmbhd:$(date '+%Y%m%d%H%M%S')

docker build -t $IMAGE .
docker save $IMAGE | pv | ssh root@nsmbhd.net -- ctr -n=k8s.io images import /dev/stdin

sed "s@\$IMAGE@$IMAGE@g" deploy.yaml | ssh root@nsmbhd.net -- kubectl apply -f -
