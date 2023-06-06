#!/bin/bash

set -e
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
