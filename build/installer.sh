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
�7zXZ  �ִF !   t/�����] 1J��7:Q�!:���e�Z��6�+�M�0<��� iB�cYEOP��$f�eb%�X"�\��� Ť7F�{\ʧ>��pa���XF���p��D 測����8����U�r!����N\��;��KǎI7r���R*�C6^���Ӆ�Dw�ߖ96�0���M������eR6�Ϗ{�G�}?��O���&
���?姏i+�K%un���<t�Y�"����0�j�毕�w�q��W7`҇��f*
Jo��R����Q����S2K����Qz��X_��|��"��xC����gS�e��-t&$�������뺄�w�J?'�E��kڌV�c��G�IN��(� ���~���4��;�����/x)���Rl��U��K �wn��r~��LxKL3̒���7t��(��!���,c2	k���k��6dO�NQ��S��Q��ƌqS:a!#�J��j�<�^�J����
؏8��kә�^ܕԸ�
��`�8f�`ٌ���j�O�V������8
��.�++\����2���4&p���!Fy�8D�)Y�TN����r�J��,|X��r�I���MZ�{��vK�^�d#ɢ�?dI5<��O�����թ��VD8xfb�X�я6�LNee��]O��|�G��w�±�h�p�ہ�m������T�칸�t�i�+)�e[���!�9{�g���
U�u�J$2���	���Q�-�!�ߟ��r���顩�Ta��,k�j������r�S��>��}~	���=��X�vC��uT�4�I�/��Z��f�3A�x���T�V�\fѴ.&S� g�	M(�.��1y�4�r�C/��(��Ik'5�{UpH����*Ox
9���,7���E���!��*��G'z�����ġ���]uP��n��j֬4� �)��F�Hu���{Pq��H�x�b�}����mmQX���Έ��b�Z�l��$4w9 ��"�!m+���x��v�p^؝�*zq��̙
ʏ�ū�``���u��\��z��;.ٍ�x���f�w���Ƙd���������o�X�kY|ۂo�����x6�}��؜�Rv���A�ތx�Ìb�{=������:[��1�.�Ľ3K����$�?��{pڏ��^΃3���j�:^�n��m�@W��6���Y�z�$*��(s����blE���E5T���k��*S
����D���� �8e��)�f��h�Ӭ�����c��*vc�l'��*C��HA�2�y�^�r"����k}��K�X-&&�ٝ�\&0CBpQ�9�0�+8
i+M�l���Hx�DA����#N ����ahV>(�g�.��G+�r'��r��a(��0�V*�mq�`=�¥��4Z`�t) n�,�P�Z��Ǌ}�g�ap׌�x�ء�rxl��ڻ���DXw�h���}�gQQ�,�]ͺ�7H@��P'������[]�f��HG�r �<������E�:X��T�n��ȅwlbL����PŴM��$BH��~B�T�nͥ��6(������| nD�ݒ��Ֆ�vuF�i?�o�c�����N/���+
r�t)��_)��]�'�<bW`����/s��9����Բ�XF��R}�^;Iᮁz�g�2Yq�Z�_D��Q]в�5�#x��4�u_�E� ��F�nn.f-	K#ū|����ٹ��~"�/y�$�ҭ�]��WF�v��
`�S-\J�V��'�=<j�`'yV�P��.UMԁ�=�.�~%��}&�Yb�I2#�LB�
L��;��q��Kk8xDȹw���s�q-h�m3z��Yˡ�.#���F�]�g��0�����f{�Y��GS�(�!�c8ݻP��BbུT?���EW���G�Xõ�Fw�,��{�Ŝ��">u��%��B�cU��s����e�q���G�k��	RYW��}�ͼ*�+0&0X�eJr3�qP��:4�b0{ĸ."��������:�v��L�|����������<o�3�lP���\L՗\�p~)U�e��i�8���kaS?�=B�� �%nDUT�[7�U�	���'|�P=��Er���g�6�la7�&)p�WP�!Exϼ(�&z�%j
A^���@b�?yϏPe|t�~>?�|����\n�!(��'��O\�����-eQ�[@��$�TK	�-�x�i�KѰ��7��T�_E�nO�i�^p�e�#��C��.(��m�b�m=�rS��{+`���x<��jn�s<�5���vb#�Vm&�Ʃ���מ��h����+8L��^΃���E��5$Z9pA�I"aĂ�0�C�Vp DH����K�/�g�����XHTj����i;��/yW�Z����PAfJ�u��$�d���S���s�)a
��;5����8��E�ҭ3�u<L8�K@�R�UU�}���.�)��F��i7@
��.zvH/&o��7i��c�@W�q|�EJj����{��Ah���1螥�,`�z?���/	�ިD�.��.�V�ȏ�˔$h���Y
�����dtG$l�q��O��
��~�����0W3��}7���G��dE���)L�5{9*�]�Rz����j���DS�yӑ����0�oV�V�S����
y���(P"-@Z̻,{�Q�D97�_HOJ�?}(
/���[)���Vub�n��À�%[��p3O,���z|lp��$�ؙ�����VUhr?5��~^U�xRN䂚޻�D�j�?��tʙ�K�Tw���,���R&�T`�F�y6%�K�v����KVp��g��҈i���/@?���߶+����n]���h���T���&�0�z�_�=b�؝�p�G�j��V�����?�#L4����n�Q��2L|���r�T�?�y�V,��o_~�����|wE)r2����$�h/�8���=t�a��{�2�,pw���:V|������D�Æ��!4�K�cNz�du��DқO�v�~v��Y��S�br�BM�|e��\ظTw����[�����/:��.�M��ea�b�G��=���k���9A	;�o4HN��KG��<�
�
�y7�-��pŕ�!�Oç��ja�3I�8�M
%g�dy���#��I�û��>������z-7�����4ߪp�LX�!s�cq�Y(���?�.�N���vkχ���� Oj�1��5)X5,j�:'���o)���?��VB��Uт��S�A���94��Ϩs���R�w��$B�3,�������cT�+�$B��/n�l�pŠ�G���O{�{��¯�ZX&$I)�%7;����^�A1��8��U��}Ч'��Bm�H�r
ǁ-���A	����+�k=�e���$`���m<m^G%<�3���Ί�\߅�$Q	��(2S6s�v�cl*��˂RE�!�{L��O���ü���A��s��$W��~��n�y����|݂����0�P�� �l<��s�a�D!�A�,q8���F�,��Q,BE6�#�S�ZÐA��(�o�a��xx�ԕc��;��0x.l�w*/���#F)�	x�(�����eIGi����cD�_��#�{�߷��/�:��s���G�LV;�D�u�4��)%sH��G�f�l�x���H+*75.��̠iX� e��?}:�
*�P���34����mYd�.m�������$蓮<���*kvҚ���Y���0#�#�=���\/��h�LSs=�5@4o����H�ۡH	փ�������Qs�cT�O���	d�����;?r��x$*�r�����)�*2t,��o��QH4��I��*��<����Sv!?p7'���6�gR��JZ:��}���N�N�۞�8'6�3��;���y�����v�u��~Q�^x��G�-OT��yMd������́k�v����d�k�|\5��Q�V�%���ս�8Xu�[�j��1 5voP���ꀊ���!V�A'.��^�Isa�$��/7{s�u�,���߯�=����RVfw%L���u�nt>	ӆY�W�!�@���Cɛ��%p�@���Y���K�u��v����� �$��/'@���-7J}���#C�<�W�)��Ί�?rF�r�`"�(�'����X�$IS�VO�)3[�s��1����Q+���
�Yרe��d�r8�2vzA$K����ks
���@�%k��{��t\1�jhǷ$������3�6vG2<I*�Gd���Q�-.]H�Ȳ���jWZ��Υ���y������<N�ջ�]�W�X��٥��N<�%��?��?@O'n \��^(��&������[v���$��%884�-?)'�T�1�f@	�,����霱����e�����ε�Q��Һ�N�-n�m�����W�Susye��l��r�[�X�!p�%��٘X�S�"�a��ݔi��0�93�	0�2����Z:G�,���|hu���^�yo���0�?o�0���X�Ʌ��j0r��UlYz�p�GM��E!/   @�D�If �$�� µ���g�    YZ