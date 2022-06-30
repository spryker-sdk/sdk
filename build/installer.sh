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
�7zXZ  �ִF !   t/����] 1J��7:@C����{�X�bc��N��<�c7�L8����)�YCK�wO��� ]X�Dr�GaX�P� P��Z+�@@�K'��RfH�����#��C�r�0��|��\1�ue[���ν3�q��4�d���)c�6�)b���4%z_&0w�ŏ�漹��� �����^@� ��`^�1xY*|<b��_���-Z�]�֋���fU�Iv(�D0A�T�#f��å{X|8��I��|�R	˾]p,��P��Vd�gb��'�/�Ůw���7.0�����;|�	C=�b��)�S�o A�B�����j�dJ��n��Ɖ bP�|s$�蚩�f�h����0���f�V���L�+aB �ba��h��/�Qݔ�/W�:
�X�}���"�ث�,���o�����4ΌDk"K�=-���F��{9+mU߶Ǝ�i��f>��؃�4K"U�L6o	�J3��u�C�V���'������RWԲj@o�Q�\jkMu5;6����JA`A�|� �q�Ĵ���}�\�ʣ.<`nZr��,29EN.�x�'0����(^��]=Rc+��V�Wf�0��ބ��bU��K�����o#�$�L¢5�h�g�(P���t����:90/D
8|Rh�$Ӂ�7�5ש/	i�+��Nf��fJhtA[�فq:����@9p&�wbH�3��%�*�(v��E�r�ۥ+�1�c���OJ��gu�}ڤh�Gջ&��+�H�f���A$�B�d�B��
��~�e\�M�됄�"��Ӈ)�,	��^z=$�,`�L��>Co�Ա.~R_hRm�L]�'�������0��+ug 	������R��D5�'r��]�zsHjh�	�%G��w�_�vp=�p�tS�v3U讛@6��]�L�8��=�.�/_W��®����pqk]���g#n�f��LK��'f[b���{�-=�?C���y/�Np̡ĘD��Wj.�zAu0L��G#���Bs���{S�|�������[�Aaܸ�|��o�_��pV�c���öt�ogY,���l�[J�Ttc�<Fu�� ��2���P6����qljZ�ٕU��3�]T�I��*�A�[���@ڶ����G�4J2��&��O�� s�n���C��0'��y+|�l�L.������� J<��hE�Ć/�'��w����%}s@1Go!vj}0�9x�yn��NO�t�`�戯0��NG�Ҥ�v��
��������5�	�Jj��=��a�#�p����f��ׄ��ŵ�n\W�Z|۞��PZ��N����w�ݨB�Tf��o{N�	j��
�gbӱ�Sf�t�G@�{q�������]ؠ�i\1L;&�f��C5Ԩ���π��^`s\�wk>�L���n�bXh]8�M�M��&ޫ��*Ԣ ��a������Ɲp�d*4�bs8�1ab���������    \�T�$\T ��<  iB�ٱ�g�    YZ