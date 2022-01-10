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
echo "echo \"alias spryker-sdk='${DESTINATION}/bin/spryker-sdk.sh'\" >> ~/.bashrc && source ~/.bashrc"
echo ""

# Exit from the script with success (0)
exit 0

__ARCHIVE__
�7zXZ  �ִF !   t/��'��] 1J��7:Q�!:���e�Z=`��X6�jM��U>,]A�#%x6����#@"���b�62'���\���(��JSUSM.-��D��.�`���({~<g��<�ƹ&X���,}L��~+�z�����I��
f��G�k�6�<����3t�����*�����1�0���DZ�as*�{�̜=�V�mm~l`��MΡM�EK�d���Կ'�#���j��� О}.��c�WZf��w:�;�OYC�����g������,қ�z�v�(��1��h��Uu�{��8���nU�aN��=m��b�!J�B̔� h������6M���pρn�q7�$O	v���"׳�p3�Y�Z�C�����<�\������X��oL�R�����,��#np��g?�5���ך=
�d��5;�e�+�T����$%�}��?�c��J��X�Ҽ��Λ�o-F�\�ہ1�]F,�P��v2��f1;t^�Ee��-EO���,P]	�����U�8��q>M_��\vQիF��?�ڸ��S� wE`��a���T'D���B裐�z��n�x�=���+V��*)�s�4�p�T��G-�H2:	ys�u�h���Փw���d�U������&�>Q�����,[��Z�c1J�a������_7��q�����JŦӞW��"���Q�9O���3�su�l��j<]��129Xض�mƟbH�r)+R����p�ze(��_��N�w�hä�f��K_�a?)hG���m���F
S8UK58ڌ9�W-�Z���g�z���5[e��hsP�'VZt_��䮾ՑA����a�. �|Mܕ/i���h�WxLj���ĸ'箝��>[&I��py!�xӉ�arf�^$e.<�b�����#�4����^��Y�}��0tn=�E�yz��P�eA�
'vjv��9ӈ�<�q��H����SYZ�Y|!_��|{3���Ԭ�ֆ�l��� e�kf4򾼎؆����q�Ⳁ�H$�x��	[���PF�?S��߈>p���BN��ԣ�n550w��:�f��L��L7��̫� `���x��ܦ��Q��K�U����̘0ܕs^�����|�e5s!��`.��q�9��]�����>,6Ez�U�O_����qN�
�����hQ�u".3���,�hp2![��[BŔh�Lv��^��/��Vť@}�xcI�6w��M���t��ӱ%�q_g�y��f���3\n���}��`eG��ur}ll8�Qcn}�brc�hn�	6H�u����,knQˌ��.�&��8v�{����	��)S�3�i�S��]���t�I���=���Nm��MX�l%"����(4��,�ɽ�OpP�ߠE}�`S�7�j,�sM     _��9��� ��P  w␱�g�    YZ