#!/bin/bash -x

docker-compose -f docker-compose.yml build --no-cache
#@todo push to docker hub

CURRENT_DIR=$(pwd)
BUILD_DIR="${CURRENT_DIR}/build"

mkdir -p "${BUILD_DIR}/bin/"
cp "${CURRENT_DIR}/bin/spryker-sdk.sh" "${BUILD_DIR}/bin/"
cp "${CURRENT_DIR}/docker-compose.yml" "${BUILD_DIR}/docker-compose.yml"
mkdir -p "${BUILD_DIR}/db"
cp -R "${CURRENT_DIR}/extension" "${BUILD_DIR}/extension"
cd "${BUILD_DIR}"
tar cJf spryker-sdk.tar.gz bin/ extension/ db/ infrastructure/ docker-compose.yml
cd "${CURRENT_DIR}"
cp "${CURRENT_DIR}/infrastructure/installer.sh" "${BUILD_DIR}/installer.sh"
cat "${BUILD_DIR}/spryker-sdk.tar.gz" >> "${BUILD_DIR}/installer.sh"
chmod a+x "${BUILD_DIR}/installer.sh"
