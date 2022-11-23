#!/bin/bash
if [[ -e ~/.zshrc ]]
then
    echo "export SPRYKER_SDK_PATH=\"$DESTINATION\"" >> ~/.zshrc && \
    echo "alias spryker-sdk=\$SPRYKER_SDK_PATH\"/bin/spryker-sdk.sh\"" >> ~/.zshrc && \
    source ~/.zshrc
    echo 'Created alias in ~/.zshrc'
elif [[ -e ~/.bashrc ]]
then
    echo "export SPRYKER_SDK_PATH=\"$DESTINATION\"" >> ~/.bashrc && \
    echo "alias spryker-sdk=\$SPRYKER_SDK_PATH\"/bin/spryker-sdk.sh\"" >> ~/.bashrc && \
    source ~/.bashrc
    echo 'Created alias in ~/.bashrc'
else
  echo ""
  echo "Installation complete."
  echo "Add alias for your system spryker-sdk=\"$DESTINATION/bin/spryker-sdk.sh\""
  echo ""
fi
