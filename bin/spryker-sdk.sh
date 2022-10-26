#!/bin/bash

SDK_DIR="$(dirname $(dirname $0))"

if [[ $# == 1 && ($@ == "--version" || $@ == "-V") ]]; then
    cat $SDK_DIR/VERSION
    exit 0
fi

MODE='prod'

ARGUMENTS=""

for i in "$@"; do
  case "$i" in
    --mode=*|-m=*) MODE=${i#*=}; shift 1 ;;
    *) ARGUMENTS="${ARGUMENTS}$i "
  esac
  shift
done

if [ "$ARGUMENTS" == '' ]; then
  ARGUMENTS="list"
fi

ARGUMENTS="${ARGUMENTS%"${ARGUMENTS##*[![:space:]]}"}"

myIp='host.docker.internal'

if [ "$(uname)" == "Linux" ] && [ "$(uname -a | grep -c -v Microsoft | sed 's/^ *//')" -eq 1 ]; then
  myIp=$(ip route get 1 | sed 's/^.*src \([^ ]*\).*$/\1/;q')
fi

export DOCKER_BUILDKIT=1
export COMPOSE_DOCKER_CLI_BUILD=1
export EXECUTABLE_FILE_PATH="$(cd "$(dirname "$0")";pwd)/spryker-sdk.sh"
export USER_UID="$(id -u)"
export SDK_DIR="$SDK_DIR"
export SDK_VERSION="$(cat "$SDK_DIR/VERSION")"
export UNAME_INFO="$(uname -a)"

### rest api server
case $ARGUMENTS in
rest-api-start*)
    ARGUMENTS_ARR=($ARGUMENTS)
    export SDK_REST_API_PORT=${ARGUMENTS_ARR[1]:-80}
    export SPRYKER_XDEBUG_HOST_IP=${myIp}
    export PHP_IDE_CONFIG=serverName=spryker-sdk
    docker-compose -f "${SDK_DIR}/docker-compose.rest-api.dev.yaml" up -d
    exit 0
    ;;
"rest-api-stop")
    docker-compose -f "${SDK_DIR}/docker-compose.rest-api.dev.yaml" stop
    exit 0
    ;;
"rest-api-status")
    docker-compose -f "${SDK_DIR}/docker-compose.rest-api.dev.yaml" ps
    exit 0
    ;;

"rest-api-rm")
    docker-compose -f "${SDK_DIR}/docker-compose.rest-api.dev.yaml" rm -s
    exit 0
    ;;
esac

### cli
case $MODE in
"debug")
  echo "Ensure mutagen is running by executing: mutagen compose -f docker-compose.yml -f docker-compose.dev.yml up -d"
  export SPRYKER_XDEBUG_HOST_IP=${myIp}
  export PHP_IDE_CONFIG=serverName=spryker-sdk
  docker-compose -f "${SDK_DIR}/docker-compose.yml" -f "${SDK_DIR}/docker-compose.debug.yml" run --rm -e XDEBUG_SESSION=1 -e APP_ENV=dev spryker-sdk "$ARGUMENTS"
  ;;
"dev")
  echo "Ensure mutagen is running by executing: mutagen compose -f docker-compose.yml -f docker-compose.dev.yml up -d"
  export SPRYKER_XDEBUG_HOST_IP=${myIp}
  docker-compose -f "${SDK_DIR}/docker-compose.yml" -f "${SDK_DIR}/docker-compose.dev.yml" run --rm --entrypoint="/bin/bash" -e APP_ENV=dev spryker-sdk
  ;;
"debug-prod")
  docker-compose -f "${SDK_DIR}/docker-compose.yml" run --entrypoint="/bin/bash" --rm spryker-sdk
  ;;
*)
  docker-compose -f "${SDK_DIR}/docker-compose.yml" run --rm spryker-sdk "$ARGUMENTS"
  ;;
esac
