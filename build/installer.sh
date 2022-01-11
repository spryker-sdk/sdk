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
�7zXZ  �ִF !   t/����] 1J��7:@@KS,��	�����3�Xy�B���N=c�;62� �g���0�0�X{U��Ɨ�*���>f;pa�/X�
�Q�-�Ե��C��T��m�+�o�4s�ܫ�?�#րΟ%s������!=��Ik�\ʐ����l�Gz�{�u���ie���"4Δx�YIZ�D�e�E����͒�C����()�4g,��pqi-/�ߪ/������w2L±���,��&xR�E!�Kd��e�?q㜀���
ڞ�gӣ,�A ��`�PKm͓�G�[m>n?K�0R}��jǄ����5A�����c'	
l��6���Z�Fb��w�uM�D.�"��ڱ9$I(�A�h��A^I��2�Ԏk,u��װG)e�����KU��o)4��QW�>�)���Y�]B'Zɣ�r��R�@�z�1��b3�e� Ȟb�ؐ;�h�ݥ�1(����_� ��bj��&���h $�ξ�-���P���l����7�3f<Yj��n���d�%E8;��I��'�@WtlB���x��:�2��S��@�<�*�TW'���8�{�X�'�pZϬ��RqăLٛ���-n�p�i�t�	�I�X�p�]TxM3�5�B:�Q�a����)��^I�"M�9�|�L#�����x|�e H�v�2.q�����pD8[I�Z�ma�Y^�s �����~�aن\q6מ��y.z<Se�����b'q���/}I�����\�9ù��3��i��R
��1h�)����&�Jg�ʖ�b}EXh����΄�j_'$c3����?��4;ütS~�l@��/�EZ��`�YOl��\�0�\�X�M�'�t�����Z�NvPG�ʰ�
��p����9u�z�3�x!KY3�X��s!)O�զ�g�\���<��op�7A������`��S���Z��bGL��Kc �"f,�rI�7�T{�;�y�I �ӋUJ6�K�r37m\��7Sq�����䜒{+��3`1Dd_w��E��)L������=�'�+�'���������
��@+<��p-��{1�8�;~�B7E}d�.pru#����M2~\�W15lE����??�zc?]L��*
�fvJ�	Z��0�It0�FC��Ch��e� �5~R�{Tl9_)7�D ��!MaM��˷�!��+�:|�ޢcia�nR���f �p�&����}�ż��@�H�Xņn5���!Dt4%q�M�x�#����Ɏ� ��s���j�~DJ=lG���%=6��X�g����ZBN�M����!]�U���8�"�űIQ�����u���h�n�j�%��=7W�s�N�~����r���a< ��#�b����=%��'}�A��?�//r���i
2�o��H'RI{���Q�����O8   ��m:Hۂ ��<  �.���g�    YZ