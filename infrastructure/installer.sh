#!/bin/bash
echo ""
echo "Spryker SDK Installer"
echo ""

# Create destination folder
DESTINATION=$1
DESTINATION=${DESTINATION:-/opt/spryker-sdk}


mkdir -p "${DESTINATION}" &> /dev/null

if [ ! -d "${DESTINATION}" ]; then
    echo "Could not create ${DESTINATION}, please use a different directory to install the Spryker SDK into:"
    echo "./installer.sh /your/writeable/directory"
    exit 1
fi

# Find __ARCHIVE__ maker, read archive content and decompress it
ARCHIVE=$(awk '/^__ARCHIVE__/ {print NR + 1; exit 0; }' "${0}")
tail -n+"${ARCHIVE}" "${0}" | tar xpJ -C "${DESTINATION}"

${DESTINATION}/bin/spryker-sdk.sh sdk:init:sdk
${DESTINATION}/bin/spryker-sdk.sh sdk:update:all


if [[ -e ~/.bashrc ]]
then
    echo "alias spryker-sdk=\"${DESTINATION}/bin/spryker-sdk.sh\"" >> ~/.bashrc && source ~/.bashrc
    echo 'Created alias in ~/.bashrc';
elif [[ -e ~/.zshrc ]]
then
    echo "alias spryker-sdk=\"${DESTINATION}/bin/spryker-sdk.sh\"" >> ~/.zshrc  && source ~/.zshrc
    echo 'Created alias in ~/.zshrc';
else
  echo ""
  echo "Installation complete."
  echo "Add alias for your system spryker-sdk=\"${DESTINATION}/bin/spryker-sdk.sh\""
  echo ""
fi

# Exit from the script with success (0)
exit 0

__ARCHIVE__
