#!/bin/bash

set -e

# The schema migrations are not reachable over HTTP -- they run from here, as
# the deployment's init container ("upgrade") or by hand via kubectl exec.
case "${1:-serve}" in

	serve)
		cat > /tmp/msmtprc <<EOF
# Set default values for all following accounts.
defaults
auth           on
tls            on
tls_trust_file /etc/ssl/certs/ca-certificates.crt
logfile        /proc/self/fd/2

# myaccount
account        myaccount
host           $SMTP_HOST
port           $SMTP_PORT
from           $SMTP_FROM
user           $SMTP_USER
password       $SMTP_PASSWORD

# Set a default account
account default : myaccount
EOF

		exec /usr/bin/supervisord -c /app/conf/supervisord.conf
		;;

	# Bring the schema up to date. Idempotent, so it's safe to run on every
	# deploy.
	upgrade)
		cd /app/webroot
		exec php upgrade.php
		;;

	# First-time setup of an empty database.
	install)
		cd /app/webroot
		exec php install.php
		;;

	*)
		echo "usage: launch.sh [serve|upgrade|install]" >&2
		exit 1
		;;
esac
