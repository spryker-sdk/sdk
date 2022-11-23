#!/bin/bash
echo ""
echo "Spryker SDK Installer"
echo ""

SELF_UPDATE=0
DESTINATION="/opt/spryker-sdk"

for i in "$@"; do
  case "$i" in
    --self-update|-u) SELF_UPDATE=1; shift 1 ;;
    *) DESTINATION="${i%/}"
  esac
  shift
done

if [[ $SELF_UPDATE == 0 ]]; then
    echo "SDK installation"

    mkdir -p "$DESTINATION" &> /dev/null
else
    echo "SDK self-update"

    if [[ -z "$SPRYKER_SDK_PATH" ]]; then
        echo "Environment variable SPRYKER_SDK_PATH is not set. Execute: export SPRYKER_SDK_PATH='<your_path>'"
        exit 1
    fi

    DESTINATION=${SPRYKER_SDK_PATH%/}
fi

if [[ ! -d "$DESTINATION" ]]; then
    echo "Could not create $DESTINATION, please use a different directory to install the Spryker SDK into:"
    echo "./installer.sh /your/writeable/directory"
    exit 1
fi

echo "SDK destination path: $DESTINATION"

# Find __ARCHIVE__ maker, read archive content and decompress it
ARCHIVE=$(awk '/^__ARCHIVE__/ {print NR + 1; exit 0; }' "${0}")
tail -n+"$ARCHIVE" "${0}" | tar xpJ -C "$DESTINATION"

$DESTINATION/bin/spryker-sdk.sh sdk:init:sdk
$DESTINATION/bin/spryker-sdk.sh sdk:update:all

if [[ $SELF_UPDATE == 1 ]]; then
  exit 0
fi

if [[ -e ~/.zshrc ]]
then
    sed -i '/^export SPRYKER_SDK/d' ~/.zshrc && \
    sed -i '/^alias spryker-sdk=/d' ~/.zshrc && \
    echo "export SPRYKER_SDK_PATH=\"$DESTINATION\"" >> ~/.zshrc && \
    echo "alias spryker-sdk=\$SPRYKER_SDK_PATH\"/bin/spryker-sdk.sh\"" >> ~/.zshrc && \
    echo 'Created alias in ~/.zshrc' && \
    echo "Run \`source ~/.zshrc\` re-open terminal"
elif [[ -e ~/.bashrc ]]
then
    sed -i '/^export SPRYKER_SDK/d' ~/.bashrc && \
    sed -i '/^alias spryker-sdk=/d' ~/.bashrc && \
    echo "export SPRYKER_SDK_PATH=\"$DESTINATION\"" >> ~/.bashrc && \
    echo "alias spryker-sdk=\$SPRYKER_SDK_PATH\"/bin/spryker-sdk.sh\"" >> ~/.bashrc && \
    echo 'Created alias in ~/.bashrc' && \
    echo "Run \`source ~/.bashrc\` or re-open terminal"
else
  echo ""
  echo "Installation complete."
  echo "Run \`export SPRYKER_SDK_PATH=\"$DESTINATION\"\`"
  echo "Add alias for your system \`spryker-sdk=\"$DESTINATION/bin/spryker-sdk.sh\"\`"
  echo 'Re-open terminal for apply changes.'
  echo ""
fi

# Exit from the script with success (0)
exit 0

__ARCHIVE__

