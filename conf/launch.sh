#!/bin/bash

set -e

exec /usr/bin/supervisord -c /app/conf/supervisord.conf
