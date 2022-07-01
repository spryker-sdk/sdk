#!/bin/bash

#Usage: create_release.sh 1.0.0

CURRENT_DIR=$(pwd)
BUILD_DIR="${CURRENT_DIR}/build"
VERSION=$1

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
mkdir -p "${BUILD_DIR}/var"
cp -R "${CURRENT_DIR}/extension" "${BUILD_DIR}/"
cp "${CURRENT_DIR}/.env.prod" "${BUILD_DIR}/.env.prod"
cp "${CURRENT_DIR}/infrastructure/sdk.Dockerfile" "${BUILD_DIR}/infrastructure/sdk.Dockerfile"
cp "${CURRENT_DIR}/infrastructure/sdk.local.Dockerfile" "${BUILD_DIR}/infrastructure/sdk.local.Dockerfile"
cp "${CURRENT_DIR}/infrastructure/sdk.debug.Dockerfile" "${BUILD_DIR}/infrastructure/sdk.debug.Dockerfile"
cp "${CURRENT_DIR}/infrastructure/debug/php/69-xdebug.ini" "${BUILD_DIR}/infrastructure/debug/php/69-xdebug.ini"
cd "${BUILD_DIR}"
tar cJf spryker-sdk.tar.gz .env.prod bin/ extension/ config/packages/workflow.yaml db/ infrastructure/sdk.Dockerfile infrastructure/sdk.local.Dockerfile \
    infrastructure/sdk.debug.Dockerfile infrastructure/debug/php/69-xdebug.ini VERSION docker-compose.yml \
    docker-compose.debug.yml docker-compose.dev.yml
cd "${CURRENT_DIR}"
cp "${CURRENT_DIR}/infrastructure/installer.sh" "${BUILD_DIR}/installer.sh"
cat "${BUILD_DIR}/spryker-sdk.tar.gz" >> "${BUILD_DIR}/installer.sh"
chmod a+x "${BUILD_DIR}/installer.sh"

DOCKER_BUILDKIT=1 docker build -f "${CURRENT_DIR}/infrastructure/sdk.Dockerfile" -t spryker/php-sdk:${VERSION} -t spryker/php-sdk:latest "${CURRENT_DIR}"

echo "Nearly done, the next steps are:"
echo "docker login"
echo "docker push php-sdk:${VERSION}"
echo "docker push php-sdk:latest"
echo "git tag ${VERSION} && git push origin ${VERSION}"
echo "create a github release (https://docs.github.com/en/repositories/releasing-projects-on-github/managing-releases-in-a-repository#creating-a-release)"
echo "upload the ${BUILD_DIR}/installer.sh to the github release"
