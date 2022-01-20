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
�7zXZ  �ִF !   t/��'�] 1J��7:Q�!:���e�Z��7�3i4��:�uUa'��!LH���*��p�`�30PD_���DՎ�t��ɻVrtD
患��Ӆ��+��8!D6�3�UIe�X��\�r�<�vr<��%i,C��Z֒�58�C�vu�Bl�C%�_��O��� ����u���d�ش��O�Џ�v��]��+�:�.����YP��:Om��r�����|)id!�h�D��F�5E}���	R-5���y�d��<�dV�D����L#s+]��#��FO�ڔ�G_��m!�j�9�$�W�Е��1����..�'O�&��2�;u�g����>�(������f��S��px�Eb%I���k������m����aшT�C�ͨ�5��<l�^��rG���/O�E� ��� �&y�9�/c׿��4+���Y.Ed&T��+����>g�OC����G��j�����!u�&d�	g"�y���
�[e���.�hM�����& .�����ب��ȩ�&-��1�ʥ1�E3�騦°��W�g<M��0����ʽ-cV�o�o-H�rZ�Q���R3d绐���/��c#�����N)�iO֢�W!d	
���UM�뢟c�{QQ�G��.#��ǼXɥB���Gm������r;�$#��GeU��K-��s{�x��o)7'�暴���4(�]���Lq�����V��^���r���4��L��Gm!��r��@�@�5�v��UT�v�q�tI;�s��n�nj��V=b�ui�R+�V�u�>�ܤT�������"ݞ\��E�y|�u6(y���z���+4�q��\���2�z�@���G(�C���-r2S�YG ��\�9׈�j��41�ٰ��|UK�)V��+hqa_u}�F*���Pࠍk!�@[�uz�hEa�"�q���嗅����4x�@!�:�Bq��U[�� ��ߐ����
��G4#٥����=<Y䎽�P�<l�[��|4�*�a�L�û0���\��d�0ާ-G%1�(�ԓ~3�KF6x�p̬;{�6��[Vx�+w���N\Ƨ]U:'�H�\MhM}L��������<	]�Y���H���uD,�P��)O�$�ؘ��mP�3�����
?��gv��ܳq���0
�@ʳ�-��:�e�3L��L�W8]H�5G=.��N,Y�@�����b�pYp��|�N@f���d�mRp�0$��ey��_"�
w�[ތ8<j���?U��1�� }�ݡ@J��r�hX��F����ߏs��·4�rz�	Nm��	?@�М�u�ԛl��A�N	h++n	�|����%�ӂS�_k�W�P�|�:�����v�4��뢷Dc�n��AI�Fp�U}�TE����U<Ю&	s���#�:�v�G���7���z��+�'��>���R��[:�m�<4�%��=!�+�g4'F��l���P�8����k�Gk?8m.+7wB�\ r9[J��q$H����Z��Q�ҥ�uZ�|���)צ=ۛH.Q�t����+q��8�1hI\$�u��@m6�>?U�����+�]�X����ka+�}��Qu/��uZ�_��)\�F�]�8=�|�Ӡ�h�&�
�}��B��C�� E�"0K�M�.��/M^?��Ӧi��n���i��M�e@�)�K���yѥs����D���tn��;VyW�	н�^H��:J�!��\��-{�	Õx���5   ��gNB ��P  �V���g�    YZ