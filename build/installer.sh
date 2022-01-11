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

if [ ! -f "$FILE" ]
then
    echo "File $FILE does not exist"
fi

if [ ! -f "~/.bashrc" ]
then
    echo "alias spryker-sdk2=\"${DESTINATION}/bin/spryker-sdk.sh\"" >> ~/.bashrc && source ~/.bashrc
    echo 'Created alias in ~/.bashrc';
elif [ ! -f "~/.zshrc" ]
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
�7zXZ  �ִF !   t/��'��] 1J��7:Q�!:���e�Z=`��L��V	�a�/��lP�[��� �/og�S	+�  DyWPi��ϲ祉OYڪE����y� �6��9��9�N�aM�99u��{<��F�)��� B~P�������b�t�'��+�[������!��>*�{�{]�8Q+��n��JnX`E���֝sw����5^�q׽��1?�����6�f6[&z��Bv���T�:�i���ܺN����v���6�M�g�5�%�����L*�b���ۀ����1�6��Z/�q�����g���p��:46yXB��UKF%��_�A��A����&�`���yo�����z#���L�Ҍ*+�N/ �枨!�4���p�������UJ�r�[��d��݋j�W�Xկ�8�%=H՗Ҟ����� 0����G�(��A���T)g/��N�q���K'n�����d�A.�����~��K\�)�w�q����n m�e��P'xu��9)CA�.��W�E�����\���-ܑ�ϵu�ž�!)Naed�W�"�����Mr�\�tt��� d�ܔ4�b�ސ���v7Y�dB�Ua�|1����fo�^`l�q-� ��Z��n��^���1�}䶫�����я~�u�`�+W�׈���]{�捴Y�?�sҗiЈ��®�}�p��*}�$�~Gk�����]S�������f����A��E�Z�`���o�����N�����Zʠ��!�7A�Łfm@̿[$�7��?US{�P���Yo��iV�MPĕ��޸��s)�FxSFSd�����ν� 1�
$$*�k�] �)c��OZ!�zΘ��Y�a�ÞK��L&�ho�`�A�Rz�a�3�f�[�w3&�n�ڜ�cX�|ݗZ��"щIJ����7����[�^ޕ�>C�R�{�e�9�~Z��r�([�����P�yj������LB3�S[���y���%��<O��RD��"�/
~+����5�<�G2�����\���O��/���vm��+���`e� \������	CI�P��YI�r�ό�n�	�ꅬ��ו�41��=1.8�����8��
����5Q��X��o�2>1C�q�����������z�=l��g7��������I��Ƴ�E�!D��>�q��'�-���&���p�H-[���U����V���bZX6ݯ�s���@i\����aX��p*j鷅�?��Sة��I���RR�1@~���Bk]��Ul�),�*^k��$��Շ��K�X4f"���zL�$�aF @�c��?����Ίu �&��N+�~�W�a�o������5��vx=��r'Qû-�F�VY�Bpb�5�x1��G�B��FOAfƽt�w=ޔ4}  �p���N&` ��P  ���ȱ�g�    YZ