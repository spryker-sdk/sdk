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
�7zXZ  �ִF !   t/�����] 1J��7:Q�!:���e�Z��7ӧ�L�*6���E�ejŶ$�[	��Ѝq�����SB��s/��;�=~K��E2������b�h�x�J��	�<�K��ZؾǞ�^�w( ��T������{�&y>ӏ� v���C�o���$ߚ��@�Βrp����=�Xhg�G������@7�9��R�=��Pn`Ｅ� ��������-��f�c��W�4_�����3���tr��NR ���H�!����8��E�ICx�
�.�:�;�B����`T��L�hM����L3מ�3��m*u�f��Z���,���'������]�:�	5`�7F��%Y퇴|���Q�:�ϰ<x7+�6�(��y�],X �z�dồH�:�B����Y~Wg��=�nT
JU/"��?=/��R���\7��uj<(����ə*��|wNPeY�gh�r�>�x�_|�����^,���=��A�{�6���|�T�}�s���2��!V�m��-�z�D6��� ���s�_���|_�{��i"���5��a�<￾K�gu<I�Tt��k���'�o���"���ڶ�Q�drr�q%�A�������K�uً�P=�R+���s��/���b^� �1&��֓;i�� l�K��?�c��E	��	烙���Jw�g	��:t�o�A�`g��b%	9L!.
�G�.�E����O�F��rE���9JyZ+!B�m���H�w¼��(�u�r����1{.p��]"�& q�P�_4��*�c634� ��ޝᵀ�� G�\���'^��2>7oQ�B�<�ne��6R������.���YF��yخ��֑4���~��G�B�ь�F��+@"եa+o->f�\�W7�{��\��������P�z4"��4�cl�g��`ȉ�׸?��f����cFQ��N���
�@���{���í��[3W���cݟ{�.��ݰ�M>��Q}�8��G��,�����́#���i��2̝��&�B�^џ0��8��R�f�ǩ#W��3��^T0�1k$[m�uϑq�$ϐ��>�%v�!o�s�\O������h$��>�ዣQ��x̕��Fh�z'��(��<~*���̛1�����n�_�[s� x���2����j%�Z�Xj���m�!?Ƌ�C#��15�������X��`SdG�O_v"8�3I��J�Q���}:�>��R�� B\,6�)��`������e��u��ڵ�蠶w<�N��.�V�[dh��+�62��)�¶&��-��kύ�&T<"7�:�lN���J��	����aV+ic�:b��~ɖ�������.DE���J������(r&G=N>~�M/�u����x�'B
�u	.F&*��bv��z�>��hzI1/�Xg$�Ƒg��;E֖S2���X�ȐU�C	ߓ�&��cu3#� �-�ċ�z~OZ�M,�m���Q�������Ǹ<Đ%���1�ҽ�Z7���.p# ~(AC:($�(��F�b����N�F�S�
��0o�yE>,5�_��Pz�hh��[�o�h�M��Xs���l�:����;/Ҥ�|�^����b��q��7�ߍ�M��W�����2�l��������@8P��[�dz4^�+,2�ڶ�S�eh.,n9kZk�*{_��Hm�/�_�*-�2�Άo�[�B<�5��'����$��"�,t��$��]����\R'N�"F�|mqJ*��ޡQ�EC�\����i�E��^eФ���,UD!�h�>�0p�;j�]A�0�=�95r#��R(�h2�a3%�>R<����T$�j��r�f�6I������5��.W0�F1s)��0��W�щsBG}�]���AɡP�r!#��s[Q�
�p�L���+x��� � wM����M��������&cj�f�mAU�K� c<�?+�ȡ�?��^�s\빑]��t4$U�8��7ȥ_X�#	���Q�I�vW���J��\��Tpq�� �"�%Nf/�J����A��e�M}�^-0=!�����̙R1N~�2�H�	±�Ӕ�g�?�A�Efhih��9���~k�)��.H*�._�{G�D���(n<&O�I�UB#aӠ�}\وEG�d ����>�u�r��\�Eʄ�BF��BH��`�j��9�+m��irra��2
�-D��= ��i���C���FO��䏄����gz����=�d�~���:.�.�Ӱ�q�q9�a󮧒8�&�����9�s��RP�l��@�*� �s��蛚qA�[�ga��GРLFk�Д����������_͘���P��Cۚ��:`���������)��H�%�~m��ʗ���Z�YR�.d=G�Q瞐YJ,Oa�#�a/v[-�0��:Nb��Ƞ} ����~�~��GgO��4Q�{~��gcf��s0	�C��y�帠�����S n�F�3`G��vK]-0m��R�fg�ѐ�qF bN���S0H�r���oe�B2�ƕE(�ӭݎ����_w�#�Z�q�c�m[���=�eID�jo���>�=w��)q�����la��4ټ��<D����oF%h�FL	����#�*��\�ő�ؒ�G�}x��l7�������!���qu�}��?��uf掗BS��q3�[��� ��'܍ϙ�;Ѳ�D��Șw]�,�\�9 ߮6���5���[A�&��9�nͼO'�7�ܲA�E�T!h�k�~���Z����#����Y��C~?�m���`([PY�^WN6铵����O5 � �{�?�	�g�썟�t)@H �x����"O�b�-����șCR+�Fj��r"�o|tY����h�X4���Y���~��ċy;G�V02�/��"�`����1I��2 �i\��u�O熈���D���9�J��{�U^k>�J4�e�P��-�w�U�R_K��Vh���E�`Ӧ`1[��-׏���!�+Ĵ���~#QNa���Z	��;���U����"��U�G--]4�&�8���=�>�eȭi/�ؖ��̮&���_��ZqF�'"V�#j�&$����@ra�2,O�a�W�J�}���\�����pb�'���YH�[��H��GJ���E�8������-l���S�Xy2ө��`S��>�1W9�*#p�Hr� �H ���>�RB黩�%��� �#��=A��GR����8[�v��3�?�C��7pëG�	��9A��8ެ��S<0�8,��v��:�BiWsz���ieȢa����$ɵe��*.����y�d�bC?lz�����af�9��B�=�����^`�݋�����T��*p����Q��*9����tzoa|B+�&[����YA���wC����pd��a�lZ��@c�(%�ߦY�d��QA�f�u����<�b/|��Ǚ�t�h*S�Þ)�Y-�DT�����~>�)�������X�{3����TIlEy�k�ȅ�.[�l�ފ�d#!Ԧ�}+�k`�3��D������d�q	̰������qr���K&;��:! �Ȱ%�fh���.Q�[k�:�YL-L�mHV\�`���+�����LS�I]`�/<fɆ�Ǩ8�dJ�輀�O�#��P�/��%��T�M��3���'\�?!���3���ö僊*��-"u�gD��e1� ftR_!�T[%;��q�l�T�Ȋ��q����;�J:"�n�_��y�`��v����Jd��u�ur�}���S �mARuyiå��л,��VKY�ؾ(H��㫜U��W�;?�Z;�#z"������S8�wG3=9�X���ך��B�w��Qox���\��?�ت��E����c�Z�?v��h�q�T�h[��D�zݫ}ʯ}���<|Es3!��N��y\nAH�/�I�0b`0���M��N+�g�7y�B�`Hx�L��bmeݒR���3K�}(�ާ�� �a�3��|����޹�x�������L�x�^�h�p��Zs�He�6����r�T�~)QP�g���,�Y��	���@9�6'%\����'�d&$�ra�/8�<�$l�L���e����+!� ��gZ��I�9t�+P�n@��u��r4d�TpشT�0��'�`k���6P�(�jR�/KG�J��,�x��Z]Cf>�A'����P�'<��l ���]�*��$�'8�����ti��J�k$��ƦЖ�(z�l����)��笞�PJ7<��#��1��F���-��҃y,F+�}�*���[�;��D�;)˚R�OG��E�.n�:�r����*�(ԇX��T(�7@c?Q%N��������� ְ�{�b�gm���Z�S4^x����BƮ�x�nkE��dL\]%w�y�x����
^%�˙�W/}^�'���ɔ�ߑ���+ռ��3$7 }����nY0�b��0Z'11|2�pmD�B�J;�Jy���H�2�8 ���,�����G[ ����t������L:�����e�G6���5F8i� R�D�$p�o�ӽ��{���)`�,$�pu���c�:rs�$z狕�<��?*ɠ��.��	R���9[Z��.^���~���m�Z������{[j�y��|4D}y��$����y���
|�gl�`�2Ժ��l[��<�(�w5�+/#a��^�Щ���Z
iԮl��C䩴���    U�ړ!E� �%�� P&�_��g�    YZ