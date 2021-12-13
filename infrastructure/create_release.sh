#!/bin/bash

#Usage: create_release.sh 1.0.0

CURRENT_DIR=$(pwd)
BUILD_DIR="${CURRENT_DIR}/build"
VERSION=$1

mkdir -p "${BUILD_DIR}/bin/"
cp "${CURRENT_DIR}/bin/spryker-sdk.sh" "${BUILD_DIR}/bin/"
sed -i.back $(/image:/s/:[0-9].*/:"${VERSION}"/g) "${CURRENT_DIR}/docker-compose.yaml"
echo "${VERSION}" > "${CURRENT_DIR}/VERSION"
cp "${CURRENT_DIR}/docker-compose.yml" "${BUILD_DIR}/docker-compose.yml"

mkdir -p "${BUILD_DIR}/db"
mkdir -p "${BUILD_DIR}/extension"
mkdir -p "${BUILD_DIR}/infrastructure"
cp "${CURRENT_DIR}/infrastructure/sdk.Dockerfile" "${BUILD_DIR}/infrastructure/sdk.Dockerfile"
cd "${BUILD_DIR}"
tar cJf spryker-sdk.tar.gz bin/ extension/ db/ infrastructure/sdk.Dockerfile docker-compose.yml
cd "${CURRENT_DIR}"
cp "${CURRENT_DIR}/infrastructure/installer.sh" "${BUILD_DIR}/installer.sh"
cat "${BUILD_DIR}/spryker-sdk.tar.gz" >> "${BUILD_DIR}/installer.sh"
chmod a+x "${BUILD_DIR}/installer.sh"

docker-compose -f docker-compose.yml build --no-cache

echo "Nearly done, the next steps are:"
echo "docker login"
echo "docker push spryker-sdk:${VERSION}"
echo "git tag ${VERSION} && git push git push origin ${VERSION}"
echo "create a github release (https://docs.github.com/en/repositories/releasing-projects-on-github/managing-releases-in-a-repository#creating-a-release)"
echo "upload the ${BUILD_DIR}/installer.sh to the github release"
