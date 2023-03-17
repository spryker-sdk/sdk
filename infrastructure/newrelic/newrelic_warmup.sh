#!/bin/bash

TRIES=10
ATTEMPTS=0

php -r "echo \"Initializing new relic...\n\";"

while true
do
    if [[ $ATTEMPTS -ge $TRIES ]]; then
        echo "New relic initialization timeout: $ATTEMPTS sec"
        exit 0
    fi
    grep -E "command='connect' .*, status=200" /var/log/newrelic/audit.log > /dev/null
    if [[ $? -ne 0 ]]; then
        php -r "echo \"...\n\";"
        sleep 1
    else
        echo 'New relic initialized'
        exit 0
    fi
    ((ATTEMPTS++))
done
