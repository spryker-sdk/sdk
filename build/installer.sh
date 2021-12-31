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
�7zXZ  �ִF !   t/���] 1J��7:@@KS,��	�����3�Xy��nd[;i+�9�K���{�V$Cgק1�X��B(�6��Ɵ�gKˎ�
���Zo��_^�*-A��p��y��ίf�F�q����Q�EDK�P��_��8֬)ג�}n{�:c�mXmX��#�ܶ���o��r�R�ۏ5���}He$��i=F}���!0=�wC�����pip5� ��`�
��G��)�J%���X���`�v��Q��I�-6V1�rؒ�R���v�V��9�Ό��>G�\� �/(Mwn'!DH^�&YW�5��Gi�w Z���I:y���s�;�ΕZѶ@J��$��_e�"���(˩[���L8�	^�vw�'T���'�"E�=/����&BZ4���/�_ �Gd	isZ)�r%��+a����,��=�v+rgo�'��8���q�'�-R���z~U���
n�<�M�B�&F��@�dEi��-	�kUgW��C�9QL������D/t}����I/�����C:rJ�\
j�%#�K�idu�1(��8�� �m-	'��$�}=2��Pi��f�@9��B�k;��[ ��n�7t����s��yX�Ӗ��p���"`�����.��V�G����$k8<j�,�9j�!=h�Ѯ�Hg�b�w�و�!��^�(��ǖ�rs�MF� �[ۿ>�s����\���� SyVi��D�1d_�A�	�?Q����s���T��Sp�8�Ȼ��	�_�\D�����ޢn���i�dC�cl�]��7xV�,~�O�.2wI�]�R:h؎&�U��C\(��ڒ�����T��"�P�%�~�C����Wm�(`�~N���)g?�U�ARp��>�P��)(Tw���8y���l��30�s��h�N�g�]-�g�vj�F�A����I���ވ����:H�ä�`������%t��v4��YB��ǣ�$��j}^8lPRr_�8�Y���E]�V�X�i}�;��t�i���c�o�K�4Z�bCbg�&yF�e�= ��A~>d����L&۫A��SՔ�s�pO��6gh
���]*�|טP�N[d��~�'��pyy��\�2�pN̉@6��z�3&�b�rL��(4n�+!��1�KA+���g���X`�4'���	9��������y��39������xd��\\C5�\t~s^Rû=�����OM�;r)�����Ǌ)�K�mښ�u�a>##����}}{h�p���X+�F�e�������]=���	2����Q�EY8�r~};u���!l$�P��݉��J �ث0ƀA�z�LVW�T}��v-��^�����n�:�Ȑ�;��"�������,9l'y����ߟ�r�&݇�'�!��   Q�o�޾�i ��<  ����g�    YZ