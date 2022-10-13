#!/bin/bash

help_message () {
   cat << EOF
Commands and helpers that are useful for sdk development process.
The most of the commands are executed in /data directory.

    --refresh, -r           refreshes cache, vendor and db
    --composer, -c          runs sdk composer
                            accepts composer arguments like 'sdk --composer install' 'sdk -c cs-check'
    --cache-clear, -cl      alias for 'rm -rf var/cache && bin/console cache:clear'
    --cs-fix, -cf           alias for 'composer cs-fix'
    --cs-check, -cc         alias for 'composer cs-check'
    --stan, -s              alias for 'composer stan'
    --unit, -u              runs codeception unit tests
                            accepts arguments like 'sdk -u someUnitTest.php'
    --acceptance, -a        runs codeception acceptance tests
                            accepts arguments like 'sdk -u someAcceptanceTest.php'
EOF
}

CURRENT_DIR=$(pwd)
ARGS=${@:2}
OPTION=$1

cd /data

case $OPTION in
    '--refresh'|'-r'|'r')
        rm -rf var/cache && rm -rf vendor && composer install && bin/console cache:clear && rm -f db/data.db && bin/console sdk:init:sdk
        ;;
    '--composer'|'-c'|'c')
        composer $ARGS
        ;;
    '--cache-clear'|'-cl'|'cl')
        rm -rf var/cache && bin/console cache:clear
        ;;
    '--cs-fix'|'-cf'|'cf')
        composer cs-fix
        ;;
    '--cs-check'|'-cc'|'cc')
        composer cs-check
        ;;
    '--stan'|'-s'|'s')
        composer stan
        ;;
    '--unit'|'-u'|'u')
        vendor/bin/codecept build && vendor/bin/codecept run unit $ARGS
        ;;
    '--acceptance'|'-a'|'a')
        vendor/bin/codecept build && vendor/bin/codecept run acceptance $ARGS
        ;;
    '--help'|'-h'|'h')
        help_message
        ;;
    *)
        help_message
        ;;
esac

cd $CURRENT_DIR

