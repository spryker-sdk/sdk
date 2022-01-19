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
�7zXZ  �ִF !   t/��'��] 1J��7:Q�!:���e�Z��40K �h�@��^
�^�6�Z��k�&f*Z�c��,#�O�/�+�ܷaG�f^�؋�2�!M�Q"��ٱ�}x�3@ZE�o�EjN����5��eh�;'E����9�Qr*��H�F#׸񶟭�`�0=�w��\H5d(~����64�p��xR=��[��{�e�$����,� r|�M PmB�X����C��T>��z�tF��$��x����c��n#����{@��n��yP]�q�N}�1��"����)���>ӡ�,�5L:
�_���x`l�0�
�h�ĥ{e�z�D; �ٶ��W3�{:k�{�'Ud8�k���x en7�FŁK �q���ZR;t�(.8Z�j��V�ڒ>.����k5�]ݏ��aX�����A�����2/�M�k��V� �Zb 0�S�������]U�\��ff�3!���7#�M�vÆ7΀)ϕ�ee�!�U�=`�#��kMp����.�� 	�=�U�=bd�(r~.t� ��o�V���*�޶�c���� �Fb��ȕ:6���<w������]�J`Y̝0g�p�<��~s��9�|وb�4��}Ǯ�j�Da/t��/�^�6d����r,��xr�!ۼ/D���L�*��������Ҵ��5�s��)�Ŷ4Z�N���c,��`d3��=����,
�]W9$���[L�p�5�4�M�S�@�T]k�A6��#x�u+�d�=s�řOw�CDisڡ���=;�J� �GДc[��ĭ_�(v�=��ROd3?d8+X�@e�m��d�D��ڭ��^�sv����������cҖʽ��~��qRL.}�62��Ύ�6�,�g��QI8"ѧ�&�i��P�!�7HPG�Y7Ϟ{U_��Y�j�8L�,lR!�����C�hs
�L�`���o��?ӦXX�4B�0H��&�m�K���.zW���"4ĉ	[a&h�,�����G�L���]�xC���O؝�}
L��ݑ��q���:2��1~>�ᇕ5���ۉ��x�xQ(&�и~,p��=�\���)EF����DF��؇
r)%͹z�#��XG6$�b5mɲ�p���4�I9tS`A�3�۳�9��v'���юa�癭9&����0K�r��
֖�2ӡ1,G^ĕk�,��fB�i�E��f.����t����e��V�xKI���~21����
�������̒J���1�u�?R�2��ƴ��{�|�ǹ_���st��xlc�<�x؋��ݰ��W�y��?Ƿ8XX�J뿖�h��������=8���k���O�bR��ԈQ&�;��?3)����%7ԇ�ף��%�m�c`P��{v�������Ҋ2�v�<S�q�Q�T8-*��3A�̨���s�����V���uO>���ҿ���ԁ�P��G�����)�^K���0�|�5�[@g��q,O���_E�)��|�����d},���@��E.�"&[��9���*�G&E}�Q[��LRv�],�YV���:�*��-Li� @�S$��0e�f}�v��1�$&�/�R̎�^�0��x�N��h��vgz�lʝZ�p�}��;��L�3�L<#v �D�9��8����y�c޽�+D��m_�f�e@�\� G�&7"�E�cǑ�C�F!�� �{9���A3a�8K{����g����8?�o��z�����b�G ��X�E/r�
�UqST    ��26�� ��P  �G����g�    YZ