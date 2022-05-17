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
�7zXZ  �ִF !   t/�����] 1J��7:Q�!:���e�Z>4�+��8�*͙��!�c@/�~#3X"�ǡ���y�h>�Z�z�
4s"�%4T#��N���?�04z(�9j�n8�}����K�Ȅ�xo������)��*�^� ����]gޯb5�ub��d0�%�U�w_����6{]�[����]�itT����Wým�͇��Z�˥hAV�����hD\T�qy+�1�.jnE�nsCu)�����-*M��߀4��q�L���bNx�Ȧ9��h,����W.Bt��t�ƽJ�6'5K%���ǝ$�qL}[_����-�t�߉��jH��Tq���S#|㫯a�M��W-�m��pfY��*�,:�`�H���-��f�a�����!%�̂.Sw��}�۫k���֍���L�H�p��*͛YF��Es��~=�o��j�4�Q.�����n2���l쵠@rG�j���
��R*�����x���;��)w��ӿ#�>�
�Z(�k��l�I���V:�hjlA�)(��'��,��C0�KX\�lsV��f�!W��ه��<��i�V��~9_L��e���M*KǊ�'�)�Tfb@0�)����?�лZ�{CF��eI��S�i����?7;���
C�E���i����c�D"�߅9�.u�"�j�9�����ɋ������Pa��{�u{Ê���$O�8N���,W�����_����*��hI"���%U��Ӯ#������?XhE�Ug;���*BhĪ h��ij���U�YC���{�������U"7���1���Vho_s�?�(�\�����-.�����4����L�Id���T�����*[@y����#�d�		��,�`H�.���g��>�s�\���=j�. 0�ޮ�'��ǟܣ�ߐ��l%��m3W�9�ۺrr��4Y�$�%�aZ���קD@&Vɰ��u8�P�v+�ߛ�DË'9�I�Ӄ;E����b�RjV;�H�/9p�So�HW�~{,��M|�g��a	����lpf�/]miQ�5�1Q�D�aI
�s~&��O���j: ��@%����/�LM5���̖as��X�a���.�Z���1k��z�=١��X�w�uxkZ���.��1�1�Ѡ�w:��k���	���h6�yZ�:䗩j!�\�6�PqZ�kN'��a%�r����-Q�
�g2�������3Au�ڶ�Ⓘ�q/�՞F�!fU��Ӆ������kJF�~sZ|�����U�_�V
^��#a����Ʈ~:�ޏ3�f�"�9@ڸ_�C�����N����V���8�$�����|��JQ�m~D��?)�4܉<v5���[��&M��Q"���R�{�N^�R��Iki�2�o��%�t����ܔԲ��S2�h����ݼ�g���Q^�d�;+ݧ��q�??s��JY�aB�]�D!]�$ã�5q��13}r+F�o�T�{�ݼHQta���.(1>�`�`i�x��BjU��<$Z�N�\6�nYg������O���~�uS:2�/�EN�� Q�6�+-ͺ����LC�ŕK���#S�+�9D˸�$,�m1�$�y���RWGX��.:��0#���F�����q+�\c���+��2�s�������Iߏ���� d���r-b���=֍��H��U�#��stz�4Q=�Q~f~(�V��{���u?�܃:@�Q�M5%c��meO���̼؂m�<���$����:Oa �oߖ吀Ag�q�N���,���棽^o��o������G�����$�Z��r.��חF}��P%�|��i晋l�$�P#��Rr��3ptE��:R�4O.�N�𹥷=c
�Y࣯�ߊ����q������#�aw��]fBz_T��
�A~��������FM��
r7dsO�(z�F�H��qS ��q���Y�A�o",�.���f;�Q�h.ߐ'ER��n���4o]��+,�]<j�yW��I<����V����{�����w3�_퀂��7N�r�z��Jp�14��~��=�,X���w�$>�����'\�n��.�-�<�S2L����GrոwK��W%��U3n��>�'��Q	ˇ!�d��I���'�O�����d��i���7*4��֯.����&ޯJ/YC��ImR�c&Kd��M��jC��c�<���f�f7���p9�G�݆����?D�*�&+��J'Uv���9$���|2��۳7gʷ�M��9G��e�r�nY�X�_�l�i�%�
 �3ߐ{7�0�1�f��b�v��.��~�S�����l?`o<-�/b������V���Eں���7����ճ�.�S�4ca��txƼ!�6�2<9*���������_�I�<%nA����Q���Z~��tn[2W�#������C��ԙr�Z@�v�1	؂|@*���Ko\TE��@�|�i�|+(>Ҋ}`^V��eH)h?���ҫ�8��b.��#&Y�	`,�( ���D�c������<�a�Ԇ��Fq�����V��8`�b���L��h��w�ZY��q�,*ɩ��<z/�-["������-��eR�ehR5��<�̣r�=�Ơ�#`7���%����CC<�c���+�|�SE���B��3�M���_��Aie~�kF�@UC'ǎ���/���$���EG�7�. ]J�$RM<*Y��l����r�_�ui��[*N����{x�K�'壗&���~���2q����iq2�m'J�P��p�|��^�S��}�4Ua
k~�|Y,���a���MF=��b�D��dkt�*]�m�m�ySf{tKh�����G���8� > � �u�=�u�Q�����L�:$�P$�����u/�~K�+�9�Qc.ǫ��]�P!��vD�&#�l�$�lp9-�Z>y^-R�2v?�c��4�"�'��RN�2�r�,��M(�G8�����GůL�N�L3y�<|+�5��E�z�e r`jq ��V@�N@}�ֻ��ˑ����A%�yޝ(�J�3�5�b�MR�ގU>v2�{�����B��5���
F����i?�>����v`t�LE:/��u�}�o��ȋJ>�詿k��W��T���o�.��0��׊���2�`�Q�>YV}�VYj���іW1���BR���(M��D�;0I�Zӈ��3q�6\�����������~�2C�}�����4�u|"�M��@��>�%M�K�������Q���T�Kv��}�J�x�6j���qͯf��73n�#��y�J�W芣����X'qH��{��S�Ь�S��"�=2�u9���c��I����m�;{*�߁����o��ϱp�l�)�o�^����k~x���?�.�wH�]�Ky�:[X�5���(�8�H�FrX��*�-7��)�pn(�X_��|t���5���y�% ��y�݌b�nYoh}6�^sN����̧	����?�1[ƓB�\�s�SF>n
��\�-��k;�����$��H_
]�4�TZ�H�Xir�;dma8���b#��[EXNj0V~o�nR!�/��uHԷD������Dx��N!��6���uCIȦ��U��Az`�c��'����C�ѱ���z2"�A�;F��w�G�����ሚ����Qf���u*�����J�C�\�O�+�J��	�q�كV��޺���<r��|x�Z�B��AӅ]�!إ}GZ��������
J�D�8=SlD�c�3u��|L@+��G1U^@Յ6�X�LW�����f�^�쫜4��~!вjZT+ez&?D{�,�'�hJ�L&J�9����g%ۏ��<�,^���*{냘�L+��ʸ*�W*]��_/�9�W��I�I�z���HW�Q�������������$���Qz�~��h>+�1>)~�g:��s�3�uW��N��¥5f��mȥ.	�nD�>�>@{�:F+9�5䶵���r<R���sqиP��V���8B�+�:B=Аm�I�D�d���jUY5�D���&y�����w�AJ����w�l�&�p7(atфa���W��>Y��~Lz5�<S��o{<G���m���|�:	�@�6�4T���p��ZJ�-O��y`;_��,g�?Fa�,4Ȓ�>?��WG���zhp�:��jr��?Iæ�6�@O��3��]�0��[Jw�n�ġ�B��ڝ�Iq���|ۄ)ĵ;ϡ�Ljx����������Vi�n��Ґ8KW��aj��S���0F5�����0�-�!����I�B���si P��:d=ϊ�6�'o�O��3pF`x��ﹸ�?�9��G�`m�^e.������Q�lP
I���7?���n�y���mK�Ζ�2G'+��j��E@��FO��	�'�C�)ݮض �)�K�~im��-/��t�e�Y��sY0��)&	���2�F\��V���)`N��Ǹ&�!ty�WP�65DW��ǵ@0I��j�?3^V��)��j�';��泿Ѡ'e:��π��5t䉺3��c$�)߀	hr���x.!���еd2��{�b7(�`~ �GS� ?JStD��Å��{;?1/�tҖ���MւOY�Ɓ �S��n�~v���i��ĚIF���l��C�uH(H9\B\��L�E1�o���c:�ꯂH���{�_���߆T�pZ���p4��HY!YocH=4	3���}5�|��@v.KJo^҃����0�u�Ax���ƣ�uM5�.�/�>K�S[���M��2����JI��p'������i�>o�$��]k$�nv��[��
4�TH�"g��\�:ꠀ����T���=���v���T�g��}m������;��&_��1B�x���4��ܫ�V#�%V�(��S��R��9!� T:Z�Ǳn7�'>����}��c�У1�]�4�[D�R���	u6�� �+����K
�8H�'i!]�Wȇ��c1TkDh1N�r�@��>$I��C�H;a'�v�6�l�^'�`�
�zN_��3�1��Z�/� -�`���t��)G��Pœ����9�I��'�@I;��,�L&;��vs���X|��� ��vJ���F&����Á{JLӥ$�HV��	��z6q8��e�/,��3�ǅ�v��	cݐ����-�W�ͳ	�fDt\�����]Qt%·1q9_A�Y����W�V�f�ѣ-���  L�� *A9� �)�� �����g�    YZ