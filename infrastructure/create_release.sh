#!/bin/bash

#Usage: create_release.sh 1.0.0

function version_compare () { test "$(echo "$@" | tr " " "\n" | sort -V | head -n 1)" == "$1"; }
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'
GREEN='\033[0;32m'
YELLOW='\033[0;33m'

CURRENT_DIR=$(pwd)
BUILD_DIR="${CURRENT_DIR}/build"

if (( $# != 1 )); then
    echo -e "${RED}Illegal number of parameters${NC}"
    exit 1
fi
VERSION=$1
CURRENT_VERSION="$(<$CURRENT_DIR/VERSION)"

if version_compare $VERSION $CURRENT_VERSION; then
   echo -e "${RED}SDK ${YELLOW}v$VERSION ${RED}must be higher then ${YELLOW}v$CURRENT_VERSION${NC}"
   exit 1
fi

mkdir -p "${BUILD_DIR}/bin/"
cp "${CURRENT_DIR}/bin/spryker-sdk.sh" "${BUILD_DIR}/bin/"

echo "${VERSION}" > "${CURRENT_DIR}/VERSION"
cp "${CURRENT_DIR}/VERSION" "${BUILD_DIR}/VERSION"
cp "${CURRENT_DIR}/docker-compose.dev.yml" "${BUILD_DIR}/docker-compose.dev.yml"
cp "${CURRENT_DIR}/docker-compose.debug.yml" "${BUILD_DIR}/docker-compose.debug.yml"
cp "${CURRENT_DIR}/docker-compose.yml" "${BUILD_DIR}/docker-compose.yml"

mkdir -p "${BUILD_DIR}/config/packages/"
cp "${CURRENT_DIR}/config/packages/workflow.yaml" "${BUILD_DIR}/config/packages/workflow.yaml"

mkdir -p "${BUILD_DIR}/db"
mkdir -p "${BUILD_DIR}/var/cache"
mkdir -p "${BUILD_DIR}/infrastructure/debug/php"
cp -R "${CURRENT_DIR}/extension" "${BUILD_DIR}/"
cp "${CURRENT_DIR}/.env.prod" "${BUILD_DIR}/.env.prod"
cp "${CURRENT_DIR}/.gitmodules" "${BUILD_DIR}/.gitmodules"
cp "${CURRENT_DIR}/infrastructure/sdk.Dockerfile" "${BUILD_DIR}/infrastructure/sdk.Dockerfile"
cp "${CURRENT_DIR}/infrastructure/sdk.local.Dockerfile" "${BUILD_DIR}/infrastructure/sdk.local.Dockerfile"
cp "${CURRENT_DIR}/infrastructure/sdk.debug.Dockerfile" "${BUILD_DIR}/infrastructure/sdk.debug.Dockerfile"
cp "${CURRENT_DIR}/infrastructure/debug/php/69-xdebug.ini" "${BUILD_DIR}/infrastructure/debug/php/69-xdebug.ini"
cd "${BUILD_DIR}"
tar cJf spryker-sdk.tar.gz .gitmodules .env.prod bin/ var/ extension/ config/packages/workflow.yaml db/ \
    infrastructure/sdk.Dockerfile infrastructure/sdk.local.Dockerfile infrastructure/sdk.debug.Dockerfile \
    infrastructure/debug/php/69-xdebug.ini VERSION docker-compose.yml docker-compose.debug.yml docker-compose.dev.yml
cd "${CURRENT_DIR}"
cp "${CURRENT_DIR}/infrastructure/installer.sh" "${BUILD_DIR}/installer.sh"
cat "${BUILD_DIR}/spryker-sdk.tar.gz" >> "${BUILD_DIR}/installer.sh"
chmod a+x "${BUILD_DIR}/installer.sh"

DOCKER_BUILDKIT=1 docker build -f "${CURRENT_DIR}/infrastructure/sdk.Dockerfile" -t spryker/php-sdk:${VERSION} -t spryker/php-sdk:latest "${CURRENT_DIR}"

# Clean up

rm -rf build/{bin,config,db,extension,infrastructure,var,.env.prod,.gitmodules,docker-compose*,VERSION,spryker-sdk.tar.gz}

echo -e "${GREEN}Nearly done, the next steps are:"
echo -e "${BLUE}docker login"
echo -e "docker push php-sdk:${VERSION}"
echo -e "git tag ${VERSION} && git push origin ${VERSION}"
echo -e "${YELLOW}create a github release (https://docs.github.com/en/repositories/releasing-projects-on-github/managing-releases-in-a-repository#creating-a-release)"
echo -e "upload the ${BUILD_DIR}/installer.sh to the github release${NC}"
