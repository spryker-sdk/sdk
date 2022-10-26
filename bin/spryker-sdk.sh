#!/bin/bash

SDK_DIR="$(dirname $(dirname $0))"

if [[ $# == 1 && ($@ == "--version" || $@ == "-V") ]]; then
    cat $SDK_DIR/VERSION
    exit 0
fi

MODE='prod'

ARGUMENTS=""
ARGUMENTS_EMPTY=0

for i in "$@"; do
  case "$i" in
    --mode=*|-m=*) MODE=${i#*=}; shift 1 ;;
    *) ARGUMENTS="${ARGUMENTS}$i "
  esac
  shift
done

if [ "$ARGUMENTS" == '' ]; then
  ARGUMENTS="list"
  ARGUMENTS_EMPTY=1
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

case $MODE in
"debug")
    echo "Ensure mutagen is running by executing: mutagen compose -f docker-compose.yml -f docker-compose.dev.yml up -d"

    docker-compose -f "${SDK_DIR}/docker-compose.dev.yml" run --rm \
      -e SPRYKER_XDEBUG_HOST_IP="${myIp}" \
      -e PHP_IDE_CONFIG="serverName=spryker-sdk" \
      spryker-sdk "$ARGUMENTS"
  ;;
"docker")
    if [[ $ARGUMENTS_EMPTY == 1 ]]; then
        ARGUMENTS='/bin/bash'
    fi
    if [[ -z "$SPRYKER_SDK_ENV" || $SPRYKER_SDK_ENV == 'prod' ]]; then
        docker-compose -f "${SDK_DIR}/docker-compose.yml" run --entrypoint="/bin/bash -c" --rm spryker-sdk "$ARGUMENTS"
    else
        docker-compose -f "${SDK_DIR}/docker-compose.dev.yml" run --entrypoint="/bin/bash -c" --rm -e XDEBUG_MODE=off spryker-sdk "$ARGUMENTS"
    fi
    ;;
*)
    if [[ -z "$SPRYKER_SDK_ENV" || $SPRYKER_SDK_ENV == 'prod' ]]; then
        docker-compose -f "${SDK_DIR}/docker-compose.yml" run --rm spryker-sdk "$ARGUMENTS"
    else
        docker-compose -f "${SDK_DIR}/docker-compose.dev.yml" run --rm -e XDEBUG_MODE=off spryker-sdk "$ARGUMENTS"
    fi
  ;;
esac
