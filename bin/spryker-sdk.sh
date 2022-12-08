#!/bin/bash

if ! docker info > /dev/null 2>&1; then
    echo -e "\n\033[0;31mPlease run docker daemon or open Docker Desktop.\033[0m\n"
    exit 1
fi

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

### rest api server
case $ARGUMENTS in
rest-api-start*)
    ARGUMENTS_ARR=($ARGUMENTS)
    ARG_PORT=80
    ARG_XDEBUG_MODE='off'

    for ARG_VAL in ${ARGUMENTS_ARR[@]}; do
        if [[ "$ARG_VAL" =~ ^[0-9]+$ ]]; then
            ARG_PORT="$ARG_VAL"
        fi
        if [[ "$ARG_VAL" =~ ^--xdebug|-x$ ]]; then
            ARG_XDEBUG_MODE='debug,coverage'
        fi
    done

    export SDK_REST_API_PORT="$ARG_PORT"
    export SPRYKER_XDEBUG_HOST_IP=${myIp}
    export PHP_IDE_CONFIG=serverName=spryker-sdk
    export SDK_XDEBUG_MODE="$ARG_XDEBUG_MODE"
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
    if [[ -z "$SPRYKER_SDK_ENV" || $SPRYKER_SDK_ENV == 'prod' ]]; then
       echo "This mode is not available for production."
       exit 1
    fi
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
"docker-debug" | "dd")
    if [[ $ARGUMENTS_EMPTY == 1 ]]; then
        ARGUMENTS='/bin/bash'
    fi
    if [[ -z "$SPRYKER_SDK_ENV" || $SPRYKER_SDK_ENV == 'prod' ]]; then
        echo "This mode is not available for production."
        exit 1
    else
        docker-compose -f "${SDK_DIR}/docker-compose.dev.yml" run --entrypoint="/bin/bash -c" --rm \
        -e SPRYKER_XDEBUG_HOST_IP="${myIp}" -e PHP_IDE_CONFIG="serverName=spryker-sdk" spryker-sdk "$ARGUMENTS"
    fi
    ;;
*)
    if [[ -z "$SPRYKER_SDK_ENV" || $SPRYKER_SDK_ENV == 'prod' ]]; then
        docker-compose -f "${SDK_DIR}/docker-compose.yml" run --rm spryker-sdk "$ARGUMENTS"
    elif [[ ! -f "${SDK_DIR}/docker-compose.dev.yml" ]]; then
        echo "The development environment is not available. Please run \`unset SPRYKER_SDK_ENV\` or \`export SPRYKER_SDK_ENV=prod\` to enable \`production\` mode."
        exit 1
    else
        docker-compose -f "${SDK_DIR}/docker-compose.dev.yml" run --rm -e XDEBUG_MODE=off spryker-sdk "$ARGUMENTS"
    fi
  ;;
esac
