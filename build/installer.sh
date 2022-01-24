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
�7zXZ  �ִF !   t/�����] 1J��7:Q�!:���e�Z��6�+�M�0<��� iB�cYEOP��$f�eb%�X"�\��� Ť7F�{\ʧ>��pa���S�N�r���+��P	�rv:+���D�;[o�P38d��(��$�ާ�I���y�Lc�<Π��H�p��f�!��\��
J���+rsg K�!"���f�eڐd[@� 6���L�D$�ͫ~�� 򍻠S'z� 4\Qb½:���FLr$|T������)�W�%�z�\s�C�xyJ�U�����s��!<�i�}�I���������R��e$G��8�a��荖��=v��Q�D@$����;	ε�ϨX���`��U];Uѥ�jAs�
��M6�d=4/tk��Y�j+(�����-�
�Uh���vT��#d������ھ��H��%�&��)+�Fi]/C
���a��3:we�_��b�1j���o��# Q��|X�j��e�l��Ӛ����D�q��*&�m�3�I+�qż��z-��7�����FS�<�q�o��P��R;��͕���;��/�PB >�H~���*�+i۴Avq���Z�e6H%x��)��m�q\�����v���5H?����y몆:+��"w���Cb�С�p���� n����� )�M8���)0�Ņ���	�*�x�@|:�#�-P(��G��s�"����w�W���;O���E��BX��,��ҞY��.��@��bH�����!�r��� ��>�	X�;�V�&�������dZ�M�t�yq�XL� 2فTA~|)�z^�nH:��ͼ ���K}���[���QI��"D�E��Wn���U��a�XԢ�
�7��W{�A���\~���U�*B�9�F�����C#��wh�<bO��yG_���d��ip�����t�,h��QC�r�ոo�yf
�J-Wė��	����8��:�����ip�$_�0q����O.������
�U��z�*�~��K>�,�/�5|���)��q��i��u���]��=ow8���� &�%��l�v	q1r2�|V����('���o��.1+�{�WPe����k���]h�����SU�Xz=���Fk���E�l̅������[D_$��ƅ�j�8!ez�Ks.ʰ�LjSFGՌ(���	�l�Y�O����}	V49
O�ͮ�y�����"f� �c8���;�]���C�}bJ4,GKD��#_�g�_�O}l�?��Fh|G.3���@������/o��xT��b^���ׄ�x�$@���+��O�)���������0ߝ�R�u�y�N�7�Oy��%FO8�bL��s��;��	������lЗ�w�˖��Y�i9��t4=�=�rf���O��~&��hhy,5����ֻי���E̟�W9�\�"�rl�Z�^tv��A�aO�����[��೨�m�����gɖE2�P���M�rQ1�@�'X��손�Db�؄/�mG� E�A7#Z�m�����^�_�� ����`�㧄Ȯ&�����iX�-���	��ɢHT°{�F������NvA�#�M��Bט�]C���]�.��ȓ3��Z�a�-@�������D~O�G����ͺ��ո/ea鉾X�K�~�>�>��)�~�?\=`��.�L����U���ԁǰ�N��U���	#u\�X=�:��W�`#
�[�۲ |k�_���O���5[5����ɍ��S05�����"r���X($iI���%7ŪǴyi�';)W �"Ć1�ob������1��+����eY� 4����RZ��5�����C@�yVB����4�o�Fm�h���6���	���u��5�>��/3N��P��u;��T���.��� |�N��ߡ�Tؕ�Ŗm��U�֬5��eҧ�O�⸦�v@�EK	||�n��;H��x���P�qJY`OA�r���r���'��'���K���n�4��n2C�Ѥ_�&:q��WL]�f���݉F�7��a������֟P&���:h�r͏��g�+=�=����Bg�}�A���������_�%'bW9!�4�qyئ]�d��w*�]���l��(�B]Y�Q��ؾ��.��/�u#p�r �A�Pǜ��3����I	�  ��`�ۑj�N�q�"�-�D��QG{e�Jؑ��L��(��DN[\��8��Npday���x�F�.'�(YJ<�1��ƅ���o5����1ZtBY� |����|B@N�iOQ��\=j�u���GG�Z�))���L�.����:�u��;�P�'���Or67�\��4�� I0{j�6_A���z�X����0i��-��!�5�V��{���'1�$T�MSe�ms��n4�,h�H�X;�j�ToAg��Kz�t�����9&�|x�f�	U�����u��z�b!�|�i���\� �汹��AT�o�+��R���L5���7�C��;������e�� ̨x����}��1�����:������ ���l�Z�����K��v:&|������%bn�x�M-�����"�P�MK\Ó��X|)��/��r�z4�6�*�C�#���~�����)tG��0�)H7,�� �=ʗ�ʗ�R�����	�i �z7�9���(�#����G�IUaiu�XY��bM�Ӄ�]�փ�0uT��O�8�nHE���RG��da�#WC�P��/���MAXl2��z�3�&� k(w��Л.1*��� I���W�^vc2 �'o�R���� ~�p��d�� A���� 2�M�s��d >}W��<�D�kɊ*�C�P?�,�{xq�w��=���F������cJm��M�l����4T�@`|DS���[
P��k�u��[���®xVT 4�r�g:
�x����&@�6Ěq�gd��` W��.0�L�q���dPCZaKv��v7=�X�2O�1ւP�lH���M�9*��sߝ�m8d���]Dy�(�M���GzW�E(Qͅ����2�P���!�;�r�ԅF�R� ���?~�نp���<�#����0a�����OGv�����p�$ 9�
��=b��~��ے�?�x����}�㱴�S�{��p�E��x�����������!��͊�	��G�+q�W���ѽ�u_PO;Ǽu@�G�i\�=d��D7�(VP8Y_���2����yQ
\��@Po�Xi��rO]�ݙq%�t�K�_M�(�I�8R������E��0��,"�r�"7粎��	��L��h��(���w8CE�i��J>���
�2kM���s�lc�w����g���a;b��p}����`��~`G��ׅ��b؛�!jz�"�?u�<g�N�z%P�J��Ϲ��M�r/��5���O�$�M�
YK����*�$��Ї.$!ԴJ=)��g˽aV`e�=n(����% �v����jr�y=o
q�g�����A�`b~�)�N)+���9Ag�&µ�e�շ���IQ�����Ld���#��&Bu_	�٪�9�i�`�̠�+P�I��/����V��Ob��hZ�5�`��ΘE�Lx�>�	�K4NO����n�����X�Y����\�u+,{6l�z�7��=J�g�$�$U��|��J��H��7�����S̲��pe���'�;��Q�Z|�F�>����
���?�?-IԈo�r����$������A�\;& �J��YX���K��R�8���	xy�9bVV�7���m0�N��^Afl^-6�yֆ��V��=�O�� /���I�'�'���@�暭��̫,�.f���&��5�ɸ�����H��yg��3�ϯ���|?��&�d���^$��Ǝ�W}�G��;&D6i�c�A���0<w|��7o7#"�7���,Q2��|U{����#`ԗQcS���]�i����d[S~�H2�K@�mވ���%t�����5�+�&ؗ��N�~W��=���\f��(~�&������gL"��1��m�ɼ�Z�t^����J����������m���Ȩ�Ӓ8W~��T%��.X��T�D�����o��0U�O8W���n�G����n3x��4���I�.��z��2]�Y*�QǱ���m� ���S�	��� �B�^K�=���R���D뷇���@�D	ȡ��Ҏδ���W��}¾y�*��5\N���&,:=%�ɂ� .����l�֘{IL��8�sʙ�>��듟��􁳱�`D\Ƴh�H��-a�!��H�������#�187	�g��B�:k]PJp+���B ot��%��\$u@%ԝ�U��GFLR����MJT%���/�cB�~N[�S&��
�W͢g�''L�qG\�3\��X�K3꫶o�@��lwg�����	t&\d<
�#CwC`����խa����ֵ@\i-ѐ�X_�O۸:���B����
��$4�'��a;
�Y �&]�j��% �$�� �VRI��g�    YZ