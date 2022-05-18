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
�7zXZ  �ִF !   t/�����] 1J��7:Q�!:���e�Z>4�,�L��L��[F�@#�V�3��-�1�)��9`h��6cѦ�%�U�I4�Cؼ����������4�WK3�Z��V�x��Dj�vK�ڄ)l�����ݞ;�pu����L��O�itNlfe|��l�(�OR�4�)��Q�J0�[EO_���T4A�J0����S�� ���ǽ�����
��`�ؾG�����?��=ȩ�MH�ⶓ��0�i�@���8�Rȏx�{���uK ;Bǟ@*z}fj�'�`�,l�G�z�s�Aq�����2f$��e[��#��ٽ(',M0�[q)1���L ����5�+���#�7�̎� n���]����24�c�M��P�@�}Z0�9/M�J����=*��Z�S�\��kl,� ����q;R��D�'���Q�6���ۋd��FC��xMg�MW�R#rw{�6�(�/͘�]3Y�8���٢��`�Qk�Z��ߓ7;�)6:r�d��b
��b��o��z##6���4"U<���ݸC}��֫3G�X�x��p`�KY:Z%�{dl�f�m��y���he���W��2�ܞ9*p�ot`�Uׅ�8�vV�H�?���j0�u���'jS�.���E���bbJ����//.g���\
�l��
���M��WR�ǀ�����;8�)�R)Sß��R�r��M�Jr��rvT������v��	�J8i�3T;�_��@.��GgR������=�?v��@�D��5��]<Ϋ�i:�_<���?e�<SC&ag<n�F����}���Ѯ!9�o�'�뢇�q��h�xѡ��>D�ơ��r�&{EQ`[����dؙ<�	��_I`W1�c����q��OH�h'
�ģ�0I�jͿ���c�����z�!P�-��؈犡��a��اdY^�PJ�M;O�l�*{�G���4K���~�ڧ8Դ� �iu�&�=�=��
�»�$I����N�kt��o�����Xќm��� ?oqw��&1��Vn��^��V`+b���p(�Ϻ5�˟(jٰ�eT@�z���M��t��5���2*�풐���w;D4���^��Npܚ�`��Ќ��������!6ΐ��S�n|��<פK$��Ň��w����c}��[̧�e�x%{h�J6����V%I�����y$��?}�Ù���ԧ�¢�S��=�D5޺yZj�ߗj�Z`/ۏ9�M�0����Q�l��izI��M�C�[G)h]Ӓ�����\����7���|���&J����Hb���'d����Y��Xu���:�m���o ф�S[D��y�[�1>MZ3t���v�s��Pֻ�`��&��_tt~�wh�?=����B6�ְ��`(\a�y����h��"�)bܟz�C�d����;u��ўf�}�����5�]�������rw���сc�p�H{F���	cM����/6�j����#ʺY1���HM��]�Fu�R�bO�P�C�Xs-LNݖ$@,,$@=j��(�w�����4G����j��?�;lcV
�ϻv�Iy�ʖ�:���d���L9����Q���_K��Ğt��M`ؾ����E����S6,�Klum�Gc�<3߫�����[5�n��V �cM�n�l�0�'��ڙ�e�۔��1�x�J�Kpi��l5�&���z�:�������{e��(��h-���@���!��OO� ���bz>��ŀ-vV�q��G��$ �_�}g9F6x�� �dQ�\��x�Kg��c'�R�*��ew�=�|5�,6o�JM�u�0�zz,��2�D���@)�fJGv�K1>JU�s���WI��x��~%��^��8_7�l�B�5S� ��]Г�iQn!��mZrн*ĩ�el�
�;�=@	���5��x	��l[���|�>�k������W@E%�iُB�o� *���S-�-�
��A��>�%��D�
��]��얚ˑS�I����OS����^�\=a<�=Z�d��K ��^�O1iV��8�ظ���k�z��U�R���x5�+n��I���Ǿj�r�yiH)�ay��h�&kq�җ����}���<Lh�?e��9v�깢e�C ���m�pa志1��M�Y��\�e�K�	y�ۤ)�?XTd��yIOO�xM�c�1uI�z��2��Y�v~;?��0��J&��O������S�p|5��?����~���P[��=̤�v�'"s�v1�*;M������6((k3�y����>��%[����ՠoYI�+r���Ɩ�qi�z$|<�9��R����;Z9�q' �'J\�??��� R@�X��5��l��$:/�
ͦҩ�)k@�H*h���Fz�������V�{7?��E��~��X��/v_f�z�����e}�AW�G���=�m�R;���F��~��\�f�q�2�b:�<��D���c�f�M������>̋���ɁF�V���l%!��*�(mGld(��B麱��q��v�y%�u7�Z����M��e�=��~�Յ�����������wt�?X�}����:�|��"��uU�l�i���jG.p�qMs���Qh��5���R\Թ�k�WMe��-O�k\Daݓ�1;��C���x �;ߘ��:m��[Ӧlz}1�q��n���=�,��	E�gNH�w���D���$��¸���Ϗ�,��\\�'( t&��d�b��4�[k�[���׆�����?��!�ow6~.�[>�o<1pʆ����3���r��`F>���$�(y���(���Ӳ�u�{&<Y��������g�� ?�0Š�5��w�тqbbo֟~~};ŋ��Ax�¢�D�R-Ϙ&�L�`1'�0J��������Lg��k/�vc�,�h��8�j�u{�䗏͚S�R�!ڶ �-�_��ap�m�z��T�^� �d�#�=�wOHm��*��h��->�*������gvA�����JrY}�,鷋�N#��Ҋ>����@U�ǽ@����/��5�6��0Zuw�KU�G�	�Ӓ�Q]qV6���;0�H��D�&�x6�' �!<Li�#W'g��e�#a�@�G
�x�t�S��k����C��<0pHߵ�T������խ��ŴX�r�l//i�je*���V/%"���7�_n`L������w��
M}�حp�b��ݼ��:��c8��	�rz� D�	ӻ`E�WH� PtF[>jz�����v�I����Z�=(��-�g������e��P6��{���ZO��B�I����/Uq�w�s���^�g�<��e����m�q�$"2YGU*W�4��Y�F�B��F):+5�F	>q㨗k������o���e�m�%M�|�Q�v�->j�>2��E���F�r��6B�5<�'�-����2�� s'e)5���¸����}])��|w��p]�lR2�����9�^~z�YYM�	����}D��9�}�s��i*��Yq_��g�Ѳ��T�p	����d��~� UҲv�Y���5�[썳G�	;�NG}G�4�8N[DL���5Z�Ϻbh77	@qy�C��D�# �{NJ��;T�Ώ���+�I���]s��^��S��޿��e��"���GɌ2�P�9H��� ����hS����BY�z������I��~�6ZOw��I^��"8>?Ѩ����
 .Gꉥ�z��(��V�QE�y5�n[�6�c#C�I�~V���f�[ 7�{O�S���]^�~��EuU�fz\*m�Pi��۝ry���Z��y��x�i/o���2���s�*��+?���/���vհ��2��wl��pyqd�9a��iXZ�*��y�"�tzho4k�#嚐�d�d��,3��q����V/�N�d��Oҧ��Q��P�.K���F�#]d)Q�,��Σ� ��0:oq���̼�����d�G��t���=p;H;�d4R�sOb�/ϵ�աrA�W}�&����g�]���+24��؀yTQ�I�0�d�'* �Ƈ���;�w�C(��A*i���&�WJ7��ʘ��h��*3��pOx��m��Z��n-w�Ǌ(���iW��f��?�c}RÑ�6��HV=J��a���ʩ�,����iW��@얬�*���)#C��Ч4`�)�f��=`�P���*_�k�4{�[���Iʛ4�$�����q�{F
o:�+�,Fڸ���+a��Y��ґ�����T��<�bhE8�x#���QMo���+ht$U�e��fRdc$������U�=�Hδ�S��n�oe�\5�Q`�@���bV6����Q��?�l[���N���U9�cH��ͅt:M�n�f���O�p�|B*���^�ՠ�r3�ߓ�&Hq>��i��r���ǐޑ�v��]�ϫ�>
-�3h���i:�<⧺��g�~��v-�aX�W�a��%~u�%���N�ܹ�?迥����@9�}�}*��f�mC�j��e�&��ה|����s�4 9� ���T-H��X`�?�MR��o���L|� ͵�F�iA�=��|��~"!-�!����A�k�qM�%Y�yA�TV���踖BE׊O=Ъ�3I%A��0 '��B�V�=�]�p�e-��~�0]L$�˘l�J�-߭���I�z��G,̦)u㹲�NC�o3��$b���m�R�|�>�de��
y��^���a'�8�ؑ�U ��Nɍ�6~ŀ����p��)���ZPُc�����|���H̜ת��[��C\��F�b�z�x��Z�'�)�R�J�؀NUod^'\�.�cJ�A� ��u{��[]�{�LW8����.U�)��%d��4���ϯ�9UI���i�d������E��o��sj�$�\���ʳ �u�ɠ��ϯ)�u-䒋�3Ȇ��M�<�1���5���A��H�4n�mE��#M�T�b`�qwP����d��Yt�{ +���	M�.��4ev�?<���b���1�r?���x�X�x�Y����	lV��77��l87��:�z���x߸�K����^i��Ĵϰe��M�(��)Ј��>Se��J�����C�+�WA�����y?�Zu�؈=���Z��cL��#R�F�q��}�\���a��Y�&C�($�rW�������+iJΕ6��ċ�
�p�o~�a�!9�}u�z:��;��
+��wB���v��.޴��  �,�JR^�� �)�� ʇSk��g�    YZ