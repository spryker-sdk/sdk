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
�7zXZ  �ִF !   t/��'�] 1J��7:Q�!:���e�Z��7�3i4��:�uUa'��!LH���*��p�`�30PD_���DՎ�t��ɻVrtD
�>Z�#�(���\7�:��r�jk�H\� �ݢ�� �%��p��/��A��iՄg�V^�K8#�
�1�I��;A�W(��6{�R1WBQ V6]`ۗ��cǎ�;5
�j�{!i�ZKVu�'�c�3~ɔ�)lŦNC��*մgJ�.\8Ouj�!��R��i)�Zt��d�!e!�����֙`|��R��49Q�sasL�~��B����Vz��<�����Y�ę�	�w�e��L�K�����8e�~���KN�C5gj�g4�y}"ddH��8kV�2���L���c]�x��=	���ͺe�آ���K�!��jH�]����=7�=`y�d5���K�2.�:��^Ig�;$�N�r�x)~�0Slw����q��%�J`&�P�`?�枭�A{D�ĵ�(���.SUض#EQF�+�d.�~�$�Wf�:�v�I�]�Zզ�Y��y��
�������FĤQj��������L�9x�i�Ģ����	��D�=J��'C���S���h<�@�.5��\�\�C�s���/�ϒ�h/sY������i��PF�_���y�&��xtc'�aK|����A�ë|���Ll� $�ThFF[�oi:u/�N�Ï�A�'�KV>e�i���6�v���1�nɎG6w�f�y/���|�L[�wȜ ����@3S��&!�&`���>kѢ������`�w_���6�$�F W�&�)w��p%�7�UTg�m�p]��B�6�=�����oU'|��9K�����P��9N��qx�����=�*3��X�vo'h%m��E+�q�l�1���<_���.����?�h�̃wQo, }�زȱ~�����Vb,�X~WcH3�\B�6���,&�O�P/`_f�`b���1���Y_��.���d@�5j7�N��1?�V��qp�.b��8bK�<���HFsXě�|���~��k
��m���u�'�.���j�(}/%(_m�Hu�V�x���H���;s��3���16��q��&��);�؉E3�D�g�/5�>�f H��)�H	��i����}ñ(�O'<,>E��V���Wc�ȊN�mc�*���k[ڞ���01	w�x�(��1]�,�5ZQ#.]�6��^�9�|f�mˣנG>�K�����I��=�+�R�����,�P���]�݈?9%#da��з|F2F�T�خ-͊��҆6j��#ȉE�CA-�S�;�<F���2}c��,�9Xo�9��t��
�
�� �̄E<�lV��)�v��ј��:�˯CX�hߣj��B�ʻ�3�3�;Cp������&o�A��)��u�nglR@:}fU��v�$�Tk�Z[��,�#2�.L�.	���S��eO�@_���E�O�4��I��с̸rv[���id݉�
ɲp-��3E.L��RT�I�]�WO��M����K|��zv�~.�_B�#���f;��v�?)�o�[P5����C�T5,���H�o�x͈w���k��Θ�-�7��G�J���а,�ڑw�w�5�ㆷ#ϖ@q�����qU2��"e�e17����ѿ{�R���`\A�=nS��:��Wn��'{�`� �I���;��-�݅۟r6�V�\�L��H�#[FRt�6����dR�k�;�c��%�Ct'E�_Ӻ����)C     T���m	j ��P  ��x\��g�    YZ