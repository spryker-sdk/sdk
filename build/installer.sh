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
�7zXZ  �ִF !   t/�����] 1J��7:Q�!:���e�Z>1���]RR7��>����0l�Sx���af`��~���E��BG��>
��O��
.}|�j��*P�%�w������3䱭�(�[��������'�� U��7�Id^E����.�`��T�ԉ&`���s�$xb�8p�xt�MZWAl�53W�1��ێ@���<�� �[������HdF �c	��5EQR9�;g�2����LIP�=�9/��B����%'���g�����JN�GRF�0��d��0N*�冓����~��%�;B�Z]�)�	D�P(�LNW7m�|�"T}���]4���s[zYcHҊX^�r�=���:������3�'_���Ї^r0킹�d��V���hI��
A>�뙴��B�'X�	0�٬���j��-���H�hi�6�:?Z���[���^3�F��RJP���V|��lұ�}����V˰ ����%݆|<֋AQm<z��Qv�9���:+%F��
�8j�մ��^#X��i)yg������Dw�k���1�M3��0/��.��_�>��^�F��OYU��iư��^������Ә�N=�\�X��>,N���@�s�@�q_ٞC�S����eJH�n3dV0��O��m�4��ڲ�-�2K���K����N?�/�9^�n�	��0�Vxf,=�S�ǋ#�8{�Q��gU�	�7���e�Gy�0d�f��(�D#���mq�iw"�Zӵ&_����ͭF�jbd�~�`��Z�$X�_oL��v ��2[��j0M�ѹ���@rF�x���wӲ�D�E�0&�$��B�`�&+�B�)l��9����0�6�O
Еn�m6�C���A#�Cz�6B;��Bޙ�h�-��a{%Y�o!�ߢnM.jc!�Ix��SӐ7⃹�3	_�U����9f7^�-���֊��Ip��Hj~~l�E_�-R!�4Qr뵶G�r��6q�J`�;E�JG(|�0���q���۳7	5��;��	tE����-���Y]
����zw�������?䍭�C�s c�]�'P�<m��V{崓;���Mi�/d$SMĕD��N`R�A�zz��?���8�?%dr�s䜿�����J)_P���*���`�ߢ�N��0���V�~C�o�:��r+���jX�Q!r:־EU/kܝW�l�ܚv�v�2��򽥠�Uf�-�����L��F�]U_qwڸ�!��/�5��2�jw�����=JY^�޵C�̷@@ũ�(�}�X\��7�s���J�����t�2��'T��d�U
�b�:�>�.��՘�~���t9�q�v���Ġ��K�V����@����C�[�d5"��Mڊ�V#��T���L��A�P���������9F�y�DݺO��:.N;|@!J�ֿBX	�ja��;�-cfj�7ϖ N^��o�k"����wT]�[[�G�3�o6㽷L���CDb�>Ҫr�5�P�>J�Z[�ѯA�:��>9�V�����S�f�]���(D��|�on$7E�� �1����u�[¢v�A;:��.�Zmf�غ�'���Y�����M)9������B��K(pL��-�E��j o�5���:G!޴���a�
!�>��n�'\����yZ	཯�sډڛ����<W��Ι�l'{�	��F��E=!tAL�K�5Rg;�B�Ĳ!�C�7�0ì��>�;�X1j㡫R���jv�`�R�bn����For�US����1re�~.3Ă��t >0�F!��;^kZ�P�yy�hd��(��������#����~��5�PE�KP���x�o����J�c~��tc�����KR���A����H��	d,�B����	�֫� h
1�e��BʲGc��ː� �� �;�I�+����`�C�u��wg�j�������g���& �õ�����f9~�	�Ι� ����i����'=�l*3[D����9�}������ 7��A����=')a�2E�Y�<��2!+Aݦ�F�D���<���Q����<J29,�b&ڤ9]�	���ũ�iU�6�����+�^�/A�w�1�"P�����8a^�����ZeNe�A����>�WPS�c��2{'*g���f��v����?2y���+Ԛ6��8��f&���l�F�U�|�6�Ƚp�d���5TXQ��%G�ʘ�m��#1z�ӄ���"����Z�$�oS�J�d�u����-&;2��9EDN La{������t ���=�@�>:Ɋiyp�+^���@�_�ފ�;6�&�PõQɾ%V}��đ�`��ˮ��^5c�v�6��jKp�_G4�.��N]��e<I),�2r+ z�n]�N��p$��)p�5����H�L�4��JCB��/����<i�JZӐ]+���,���#��n�S�����+�]su��~��v���̭�F�
�#$�AS[�g1�ٝ5f(M���f�ڽ�s���$|�'��'�"��,�m���Y��5��	��\.i$�$���s`�^�
9~��"�#�j���c��!-²��"��DlWB1��K~4�^���4�qn"��.V��`2���@V0�Cj̓t��{��p�B��x����o����G1�aW��$�Kj`�A���T*R���=\Ԭ/�Uh�������כ��}��a�gz2MCKHaQ��g�Y�6h�aL���r�g,��h%f��$�+�K���E���o��g����SI����&�N<X4wD�c��tj��}��Jx
��f� ���H�;�k���D�7�<�D��o�N"���ah{b%��p�K*�kW�]�
(��+֑r������������O�rC��'�³q.:P/��ћ?��,�d�7c��s���d%'�Z�/�C��������1N޴!��T0��9$���a`J��������a�H��aZ=��ە��'j���2Gaj�3��iӬ�nf��[d���R����k;�zҧ����b_`��kk\�Vb3�E߅8X��	r䠘䙊_��q�e�k��[���lӎ"���ۅ��n2�3c�#u��3��ؐT�,���&�'L�&J�|c���#�i��p�n�u�Ɗ"�nڳ *-@
��d�5Ѹsy�S��e*�T ���t_���Om��#����C��g���ԯ��\aڈh�* W\�{��+**���� �y�j�^�⌹(ۃ�܍��+X9,1�M)�2��XiF�{� }�����M��f���)#]�����P�q����O��ԙqs����_�cNTme�ٷ��	O�a���Ŝ��-w~�ДRvޑ��'dU�IQХ=��[�a��0��1b�M��|���q�^�'#�)3���Z���kǮ��%�"�I� 7�iu�Djp��XTOb
LePȦK��e�U!��q�2`�i)�وc-����R���'�_dғ����?w��I}<E� ����g�
�k�����6'o�	AJo��`9.�ĻU+��'8W�;e�Éð8���@��v+{߫���Z�=���o�ɛH67����dN1�r]F\C�����Y��R6ң�9��z��|;�h���-�������ɑ�W�-�*�ȶ��ml��Kk��^��{O9��Xv5�D�:��7r��:��T��;���,{8+���i�M���1o���~n�b�D?ׅ'���V�ݣ����|�|���"�r2S4&Ѕ�]�x=4���Ug� <�
�10��������}��H!D{�6�H����ޢ3�e}�e�����î6g�4 ��1�:ݭ��͋��K慏� � ����.�.Tkp[�6B(�� �D
�C1=ScK�7�6R�k�s��ɆFT4�*οW���#!ݤ���F�Dз�d#:yc��rM" z5�H<�R���c�2]�Yы��*�YF�Vn��,gm��"�x;���C�����5羟h	dYS�����֜�z��}�+�h�N��aM.��.�߂,A�W���K�j����Yz�Wo�Nv==6pl�:,>���tT9(j�M(�J�1��oZ�|^u�'A<�jI�&y���������q�*����$�,*��l9�+�tf}��M���Ȋm����$�

Ɩ?��S���!�����v!��xE-�j�����&����]�;�E���_"�s �>)�.➧��[�W�����B�Ibi��fȂDx�"�4w�֑J��?����)"*�T��p����o~�f�����9�gu�Z������+d�\����M�>t�~�roZ�n<�/�j����vM�E{u�kj(��ۅ��އ�F��r��^$�p2U��u�mv�[�C���[�9Gp*tȡ�������Q�b�TP��M�h�Ņ�]0�;�Y��̾O�R���e�*P Sl���ÔH)�LmdITslW�����Ј"�P"���ˊ��w-CwGU!@Z(��dFa���:*����fcq0~}̪��Gc��[��k�� �#qF���Fw�'	t�C;�M�5ą�z$��J�Jd�5�0�\��A�#�i�F2�����o�(�����\���!B�C i��/d�!��N��P� b�4w��'���:,^0��L�_��4N�-�v��$h��k�������ɧ�7qiE2B�>����;6?���ԋ�d���y�     .+,� �%�� Fd"ı�g�    YZ