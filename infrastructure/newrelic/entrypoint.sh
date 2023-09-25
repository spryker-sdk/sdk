#!/bin/bash

# This entrypoint is needed to wait until new relic agent be initialized.
# To do it we need to execute some php code and wait until records be written in audit.log
# More info https://docs.newrelic.com/docs/apm/agents/php-agent/troubleshooting/data-stops-reporting/


TRIES=10
ATTEMPTS=0

php -r "echo \"Initializing new relic...\n\";"

while true
do
    if [[ $ATTEMPTS -ge $TRIES ]]; then
        echo "New relic initialization timeout: $ATTEMPTS sec"
        break
    fi
    grep -E "command='connect' .*, status=200" /var/log/newrelic/audit.log > /dev/null 2>&1
    if [[ $? -ne 0 ]]; then
        php -r "echo \"...\n\";"
        sleep 1
    else
        echo 'New relic initialized'
        break
    fi
    ((ATTEMPTS++))
done

eval $@

if [[ $? -ne 0 ]]; then
    echo 'Sending error to new relic server...'
    # Sleep is needed to wait until the log record be sent into the new relic server
    sleep 6
    echo 'Execution failed.'
    exit 1
fi

echo 'Execution successful.'

exit 0

