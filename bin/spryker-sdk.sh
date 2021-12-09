#!/bin/bash

MODE='prod'

SDK_DIR=$(realpath "$(dirname "$0")")

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

case $MODE in
"dev")
  echo "Ensure mutagen is running by executing: mutagen compose -f docker-compose.yml -f docker-compose.dev.yml up -d"
  docker-compose -f "${SDK_DIR}/../docker-compose.yml" -f "${SDK_DIR}/../docker-compose.dev.yml" run --rm -e APP_ENV=dev spryker-sdk "$ARGUMENTS"
  ;;
"debug")
  echo "Ensure mutagen is running by executing: mutagen compose -f docker-compose.yml -f docker-compose.dev.yml up -d"
  docker-compose -f "${SDK_DIR}/../docker-compose.yml" -f "${SDK_DIR}/../docker-compose.dev.yml" run --rm --entrypoint="/bin/bash" -e APP_ENV=dev spryker-sdk
  ;;
"debug-prod")
  docker-compose -f "${SDK_DIR}/../docker-compose.yml" run --entrypoint="/bin/bash" --rm spryker-sdk
  ;;
*)
  docker-compose -f "${SDK_DIR}/../docker-compose.yml" run --rm spryker-sdk "$ARGUMENTS"
  ;;
esac
