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

echo ""
echo "Installation complete."
echo "To use the spryker sdk execute: "
echo "echo \"alias spryker-sdk='${DESTINATION}/bin/spryker-sdk.sh'\" >> ~/.bashrc && source ~/.bashrc OR echo \"alias spryker-sdk='</path/to/install/sdk/in>/bin/spryker-sdk.sh'\" >> ~/.zshrc  && source ~/.zshrc if you use zsh"
echo ""

# Exit from the script with success (0)
exit 0

__ARCHIVE__
�7zXZ  �ִF !   t/��'��] 1J��7:Q�!:���e�Z=`��L��V	�a�/��lP�[��� �/og�S	+�  DyWPi��ϲ祉OYڪE����y�aN�<r�j��I٠0��INZ���+��:!�X�t�[Ԣ:Yq�'��EM��w�'6��rE�IwƯgJ�bpB�83��A%�z��A�H����g�G}�Q�YQn����æ`ͥ�7���8�U��Dc��f:C(U�
�~����e�e�գ�Z
������¤C.�|�:TCN������Z'憖������MO�`Ӈ���*n��.���p�����U��d�zoɟ{�±�b�	�Јt�� o2��D.`o� <�1��5W���[�=ՄE��u(�o��x�$����F��g&`<PŐ�s�6��>�G;B��)n�4�� q?V&kr��1�eR-8�zK���Z�p�����S�b~
��Ç4��.��^~Mf�@�\�ԛ����9H�S����ľ�ƹ���	�D�&��k��.P���E�Q&X������q��f�A�VW�ۑu��I�M_���D`�*l/��h�v��5nSohSk��W�R���2�۰R�
�d�D�w0������/{мe�q[ߢ����N�k��V�o��T����p�*�l��:��(>[��Lg�/��b
�@~h�,P��(ps/��J�9�h���'/��zٛL%��'���u{E+�evif<��X:qf2m*��k�!Ҵ���0d��0$ߏԷn$��wdC��TQl� o_b�A�9p��p�`Y�A�Y�\�	��3��)V��m�bJ4F`R�� FS� -8���Y�TQύ�poH�;�{=��p�k����.����a���R��)}r��v>-x|4�J|0�,�����fźw#�!�|����M@�o?��^ë���w��e�qw���k��{�rm�es��m���y��}}����]g����b9�����|����2 �d���u�z?,���������&��5j�P�����z���<�=8��!����_T�D��T%I��q
|Ո��&$����'H��� E�Y�A_P�x��\]D��@)�D���c��%eϽ�
���o��3^�|4g����/6T���׹ݺ"�h"f�LhV8k\]�<Wa5`�b��s�Q�AR��i�0��\�7�O���'�ը2��U8N����p3wI�eG��^�]�E��]^W��]-��vY/�r��Z2��_���o���tv���"2ou���vK!����(��(�Z{���R�r||D��G&��5[�#�?��Nǫ�=���
�P�P�1Le
[*�a~0z8�r#N������#��)��Ct}4�0im��Rlns"6Fx���ܚ#^g!���%M税��E��B�������c����ٰ�2c�  �ۙ��6+ ��P  ���ȱ�g�    YZ