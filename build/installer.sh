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
�7zXZ  �ִF !   t/�����] 1J��7:Q�!:���e�Z>4�+��8�*͙��!�c@/�~#3X"�ǡ���y�h>�Z�z�
4s"�%4T#��N՘#�<3QLR�����ƴ!"���ǲ��ӕUl*��.��}~��~U|�ˎ pm
�)j7�T�ɤ/8.Q��X�-�w4~����2|LWv�<l'��r��h4�m�,4�`���k�I����Z6@$>����3ar�G�"���6gC`���R�����L��M��=	�d<s1Q؁������?�zM�E?�&�=�N�N�<TF���l�}w˿��s�w2 �x��)^��O"�U�M�Q��Wݸ������5 T��P<f�T|��Q��|cS���t������������8��M�m����vmB;Y��+)�ib�i"�B!��e��ɇ�[㴑�a���>�k/���  ��l���o���r62m�8�`�%�-Ö�Q���7�p�S��N)���v,0��O�^��%/�@bAʊSBt���)\���$�Eq_[g���@��5�a'�2��0��r��+��K
[1�V*J��b�ʋ�/��D�OGG�6��0~�MJ�j�׮}��L��)�,����.�+j��KEq �t�e�����//8-�� 	 ^������4����0��ײ�8^:Pa0%?�%�Fa�-���E����8z���_�D&S���,z��Fiھl�dIw�Cdg��.���rD��HoKԯ�<���
H%�A���,����mv0.wv�.Ɣ���ƣ�۪W��=��<�����5Cy;A�j�V�\�L��G`�)L D��~*m��L� *;-�^ voc�����G�٫uB���SۧK��<�5M�>0g������|�ds�>]W0D��:�8�8�$��Ȟ6"�ٛ�����8����y�"���{�4�?��Fx��o��ى��&�E����UA�p����-��{������2�w������v�<���]�\m�E3|#��mqΫ$"���t��%��_eb�iGf��LM���Gq'��I*@��0�{��Xb�Ɂһ��x̙�m�Cr�����������T��H�w��[�P�snN��lRCu�g�0����Ɋ��ɨ�WM���D]��Q�ٿ�3NןA�����[��P�̀�֠�x�܌ێAƟ��^En���V����K��	�K���g�y���̧)�y��Z{�ge���n�C����6F����bĘ�w�����:�CHT2F��=��hď��(�v�mD���=I�:8��cr�f����]�Y��:�#�q�`�9���l\�a��N� �/	)=+ƿ������Oi�T�k�L�&�͕Q����o �����z5xyP��_�8{�t:a�φ��\�'���4�M����q�i�������vSl\y<�qô��Em%��Ot���z�|�	^͚?�����(n��qF���?x�iFO<_4��o.Ŏ}ʴ*g,w#����*�M$LT��E])����@��\�ۡ*ҭc�7;�]���K��L��X�h����%IW�a��5��:N�X�'.�u�1�~��� </�� �z�g^��D��d\֒�t��U�5$�c�|�I�-�姯n����)�7x��%�9-��P�g���dиV�u.A��l�����UF��*�-F�ی��<C�p��2'{�qM�����A����&��̙�X�D���J�5��7�V�!ȥ�R�ŉ�Ӈ��p�j��^n@°����j.�R]F�ã���n���;B�&��m�
� �ދ��i��Wf# ��s��^=��E��~���Z��+`��Z_t����@'�`t��a���vؔ�T����'�����ڶ_RS�}�ç$6�n�
*<�I���������}��~Ӹb�\�1�Fr����I���MB>���_�2�hG/ϭ�I!��&p���*B	_�K�f �E6Qa�b@cըMP��5�3�(~�t�6�fe�����^���s���$�Pf,�&��b|i- ����υ�$f��-����I�M�uEˠH:zɫOkUaV���G`�h�A-3$[�$q��G~豸O�1_'�q10�*`��F(<L46�����A ���*�����T8j�m	�7�X���^Kǻ
�� e��@�#� ˆ��k���oz�
�2�9aR����?��SA����Jӱ�Х�~�"zi�A:)��Z6S>��[�)w>g��鄤v�f�U��MqiMY�`Z��Z����x�?�&8S|����"�a1�X�5�'�8z�8Iz�r&�6�\��D�M���o�E=����$Zq��|,�����$��Е<h8�u-h錬� �7YF)�+;IĪ����i��W�	$��Ħg�2�p�)ەp�������XM��"!���2��o��8��L��n/vb=�j}��02�?P	�0Q(�g.���3JC�Z*�#KV�'��OdM#�Ƭe�W+���~*�fT*f���[��k���8�G�:�����^mn�����o	���ˊ
D���n�Jh	��*�����o\��K\��E�R�r��X�l#|D��O6�ٝa�?\�E����^��2 ���+T����r͗{���-1��0)���9VO�ĂN�D.�%�M��eZ"Ng@H�9Z|6�D~�9)��P��2�|�)���'!�.�Ha8�k�-a�\��f�I��e��Sm��]��$����A��.Q:w�N �'�-T���rK��[�C�A+�i��\�gRsb(�c�7��Gn<L&�b�A�Pzͷ�i|�r@�+�U{D�FAF��e�G���}?@��e`Ѱ;��X��
�����/�5�u�Ŏ�K�|��+ݹ�����@ ܗ?޹�S�E�w��[(�9�y~}�m<���\�>����z��k�"ZW������R�w����c�*S.�.F*��d�M�ƨ��!MP�'��O�������$ƹ�:�I��]�!NU�;�*O��c�����	wЊC�̩����L���܂yf���K8���ܒ��LM{�C�G֛
����-V��/�;q��M�P3Gv��P�m�!ӷ=�aګd��WbbZ��J�Y��ͯ�-��p]��2n�G�Fv�2l�Jyt��:���s<�%=~��x��h�Cl��̓0S·7ǆ�NFQ�n$Xk�	XEf�����1�B�U�d:V��Q�C��U�>[�q��yi�J)\���}[�T��4�?�N��w0�F���LKF1�(/`�^( 샫ny�I�h�Aęߡ��-�b&��7P�^A��Lg���o]Uyr�O'$���@��7�_��m}^��A2Pphǁ�W���b�9�O4�8��!�&>����~�x�R�<z��L��답���B&t#/�y�_��,�j�IR �۷���ͽ����f��Mȯ�+��z	�Do�{!1m�
�e{	=;���*]��#�ew�iS!��Y��J}�Z$�"�L���z�q�9}�p��*ݥ1����$ܼ� �*p�}�9ƖA���
�h�"YD6S��)���bv^3B��@��tz���0���P�h��$�_ \�.7�aa�{^���٦r+:k��K�Րy�]#wz嵿LO�&=�?�Oğs�$s�WX���Zj]�Q�1�O7��T���lw7�����I��),�[�\����G��8��1J,��Slv���2�⃙׵�f�MM���< H���	Ӝ-�n���Z�l���Ѣ@f�+��C��I��aA�@s2Ȋ1��M�t����W��_ӌ�5k��|���5X4[������7��om��?kM�J�E?:���P�3[�-y��&�"w�T~����R10dZo�	�x�*Kɤ�OsH5���E�|̐+���U%�ց�5_%���݇�y
���g}}�H1�4�N����6|`l�����v1�[�Z�b&� I8c'XeK��@,���҅W�w5Ӟ�EY�H�5���jPR�	yO��_�䙵����FMoo�� ��6����*i�ys��J�E���{ 
��������	m�����#]W�=�إ�J��Qzfiz�?��}����_2���������0ͅC��axWd0���5y��|+�`��ib���Ĉ�tgo���)�A���S�h�ݴ�.�Y�G2N�v���	����W�^��2��mX����7��9Tv3RpDU���:��(wy^G���2��xM��YI��S�T�?�F0|L4��B`�ԭ5��k5�'2A��(_�R�{27V`�{d\�]#���sF�,�39P�o	S��i��@O�>��N���ށ���O_ͷ1��ZƓই�1���ϐ�6a�
ҿ3��=���<oғ���(Fִ�a�`fO��-����׽�Eu~*�t�@�u`�	�D��J#�_64U����e+�rc����d�9	.�����h�㹫J��W�\�Ӯ_.�e�����)�g��pCT ˳����w^���ȡ*o�h�E���jp��\z7A�uw�&�k#F��{�A=m���l�}x������o��=5��G.R�/@T'��xXr5�SPz�{�q�F�0��s������n���{�rW�1+jDN�<�#B��.#6ux�	�
���N,���Q����	�;��'�]Ò@Y�#G�K�>�ܥf�.�j�2�c ��/:��k���������䣈H�٣Z%�X�VY��UC��EJ���2��WG=��Rj�(Iew���I��.�̉��c0"Q��*��A*ڡ�242�NO�Έ=����E1�i5�.�2 �I�e	��]�'�\�X�S�A�̊cMrOy���A��>2�N�,a����������B����&JM��7 �>�4��̗�@U��^d�P=���$K�'���*.5]�~ �fDe��ǒڨ+�k
�Nϰy#�ozƳMg��dQY�/��"�!    a�(�M�{� �'�� &7⾱�g�    YZ