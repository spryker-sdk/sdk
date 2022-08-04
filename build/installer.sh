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
�7zXZ  �ִF !   t/��s�] �'w��v���(gFC;����������c1,՟�2x��w�3��0���B0<b�0��I/� ��np��e9�3�e�x8�ט����Ḟe��M�-�LĹE��]o+On�R�C��n&�Y�?�`t6�h��Li�v�_#�o�h�{Ld��0e���<Q����.i{G���fʂ��f�p�:���}�����y�:E��'�O����D]jG�(�9έ�[�S���dA��J�_�cv_hwʺ�FO�8E��iy���nxӰ0}�\r�f��[�9o��\7�*������d�C�ǜ��J�I�섶㪡M�t�؃�Сކi'�48L7d:���-d����=����z����������f�����I<`���s� ��kՈ�s*���� ^,���KpxCN"�n�Dg������GyI�:f�����,W�GYUL,8����[�:Y:� �9Ð�J*ٮ�l��n���8��|*�A��\��%�����A'��jQF���O&�a�㯖`%�Y�u��z��'�-��5��A���蜄��]�ŵE	s�9�O���~ l�J����j��� ��A��u�%X��*.0VCH���|zr��g/s��B�/�>�H��D/+�q�~X��W�@xP���o|� ��J�x�%����Q��T�̈�u��Qt찧��V���2��H���	5�6��A\�
`W7Q��i��:9n��69�w�#y��� eW_8�T��]���$xl����m��"�)+)��q�Ο=��0}��옂�ԑ?[YP���B|ؔ�q�1#��8IŜ	zI�wԻ��w�{|�s�wο���`�0n��b|]���DP�hחs�V�K��P�=I���D؛�{��S�]�R[���g�ɋ���dJ:o!�_�n�&�|�1�Ya$zGC������-�������'>�P�~�C�P�1�(�ޏ��"\|�`�)�lSի���K�Q���I��A7s(~�old��&Q�-��P�c�{�$Q�lh��G ��Gh(O�ѐ��|d��%*�3�*c�Օ�\Hd"��#x8���,CT��F����۴+�j�zݲ��\�B�	J��ڈ�t��Q�N��{��myX����"7 Y6o�����u��wA�x��;�����OZ@H��r"���Ғ��������>�>H�@t4��M��30遲�	`*C)`.O䊸��bU�-��u�g+؄k�50R� �dw4i��vXr<-Nh9�&k;b�*�R�W�mpu۞���T�X�]70no�7�}~v͆�V Nv�9!J�F�9��ZKA��K�?i`c�z���q;AD���������s��H��4k�j%�u�b�r�[U���I��`x��pM-�����uD�Xp���R=�4�.W"�c��"C�_9��nF���s�������ƎXQ�A���0z7��� .»!#d>���%r8(h�E'��]�ٟdj��-���]�Ec�Z��|�2�D������5mk�9�]�{'n�U����~@>��@V��O�����y
p�~jґ�Ό��̜�a}kf�*���_��xcm�
�u)2�����p��Ik"�7�(�Wl?���xH+a�'N�L֚�˩�� ����T�q]���O���	P{iF @�����+=+fǌD�S:�V%d�=��>O��kg�+�ӊ$�V�V{�/�:����U7ؖJ-F��L�r�������O��L� {a}l��	�y��%V�;�*b�dds9�ɚ�x�>�(s�Lyj:a��+?k��q�~&>��Vu�ҵ�5#�o%����밢5d5Fș����M�D���t{���p��T� *��*e,��C#3'�Oܣ����3B����a�<�5�G;H$pӻ����tPQ�)ªI/A�Ld�򚛐�R�πw�ɉJR�I�wB*l \�_Ȁ��G�o�.ϔK����xa$[���7x."�A0� ��gsG@�O�,� �WiPϓ����=��8U%"VWs��n���Cj�*�c`�-P�{+��:�'���*�q�`���*�!?�Sr@�� ���:��R���G�@�7�&~_k�/uG��F6#�����}z<+6|�>�AӁ��F�'��@t?�ÿ'<A����͢0r323OK4ypZD�d)���ĭ��K�@�I�+�/{�i�VreǑ�(���О���XU�܆��f<H�f��>|�yk�F*��V�̣� �74��罌���*���f���rKb`*�Vz�����`�`W�0�D�3�mB��~&:�6D��~����=��5#b�Y�_r�t�V�OB���l�U�%�i%�_�K�]�&Z@�WiZd6�Ùx|9#�z��5��؝ۮ�&A�c���R@��c�4��1P��ae��u<p���Q�V�r�9^|�M݇�1��*m
�Ƌh<x~ty5�~�O��^d3hf ��h;��������nc�M<d�\ö��<��#�n��SV<���th:p�a�֯f�q	Gd*�A<�����i�ⱚse�r/]��/�&[�0+��V�u��[��(��:I/�> &iM����T�TZh�M��b�I]��=a@
ϝT\y��T�|E�x�=̩ٙ���̦�� �������6�0-��t�r�Z�qr�|��W���9�c�q�:>�Y����+q�Tuo@���9jq}u�%�4{�}Q=��f��x;a�h kثd�!��Z�PR��D��ى���.h9�ڵ�ƭ"�u��ϋ�<TU�$��^�N�v@벧�Jp��m/��*�; ����s�����`{��~?iY�C�w3O#��,�1��I�L�3�ʶ�ߍ���Q�XCBM&���_�.\����l��s^ez�*u,�vlP��Fd�d��/�7��S����d0U�N?�������~<�K6��6$�����W�d�)�;~[5�6~�g�\�{����'���o���������o���y�}�c,U�1b�4!��߿���Y����͡�>%"V�}j�H [�6��wv��6�%��(�l�nI�/qJ`	��6�5��s+^�ze={{��)��:��n�4��i����T-R������
�0k��R�O��T��Z^>^���ڠ��b6��-�����%g7Am�U�n�So���x��ot(U���>H"/��u�D�N��0ltU���t�B���1�0�c�3?(Ĝ|Ӎo!.����Qr xq웿�l�Z�A0d��yyF9�i�
?nN%<��wd	7F'����|�����K�܉�b�?k𜶌�jz>��4�)6j�{��&��_6�$9�g����z��=���b�n��RS�1�-��w���*}�F��3R�*��$���#A�����ð��5׼a�b��J"��S��K�(���]�AX�Í����dA�v'����=ܝy'�>�/+ځ�������7U�����	��|@�bf�@�C���,5�^v�X�L2��G�	����9f �����VL#����|f��R��]��ВF!f�S ��bۊ�U&�V� ��?���	"ٕr����yҘbZ`���J܅(�N�ۇ2EJ�k�T����h�Ou�0���>��x��/Z�,mdcp�u%����=���?npP{�p«皿�&�qI>��hbH@�ދ���9:#w��� ��]��Wsy��oa)sdE��/r����H�ĺtF�p�YdQ��Ɯ�5��p�6BN��W�ZW"8��¨/�wD��p���?%b|�=oT��+�����o�n�Re��'/�#Q�cJ���+|x/��6J�>�B�ͤ.O�{�W�1�J�bU�Ȝ򳰶G.ʭ�W;�U�f��[ƂݣV"m��T���z
�gջc 9�*��v;��c�&\_� ��la�<���=�[���l�H���������x '$;H8j��"Nڱg�k.u�uw*�p^�|Q+<ҕ����\ &�(�&vpr!���{
>S����Gv]\ѣK�52�N��L�J/N�{x�ęC�i�&�S2�tL���
	Y��`MXb�|��60f���-���0�D�g.GXί��l�ﶦ�V�o���Z!s�B�/��ȏP����W��{~�N<{и������H��A�2�S�[:��~[�����\2��}@|+п{���[�Ԭ��<��\�+�)9�U��=YfPr(���`*��W�r��pq��;U�����Zgb&ښ��{,�f8l%�����c��㰰�u0�$�@�12��kpHZ�IΠ�I!|��5Pl�Y�[�2�����������
����n5�3��@B�p������=��-ֽ&��\u3�;Mb��-�Y����g� �¥cU��g~��T`�m14g�,s�z�k�_(�&�!���FԆ?���Qcj?=8���,�ݚf��͚KW�Q!�H(PFnDQ0߶�p�1_c����r}A��%�P�&X���AB~��¬	�f�];/�!c�7yWJ�UvHm?G�DG2X�z�|��:ɶ�z
�	�$�Ü)��f2#i��.��':�+���"�ڮ��Ȅk9���QHO���������b�N���Z5�A���|I���
YPݨnek���h��8��w.��ƝHw�-qcF�d�G�$g)Z�寙H�S6�L��R��#s��6'��	`1��K���x�͆�������Jz���� �#��e�7&I�g����-9^#,�'�D���*�;"B1n,M���1��͖.K�wW	�gl�C��7D�T�,f���(|Q����O�93�Jwk�f���B��7ʆr� �ȼkW�48�y���םi��B���p���pB�.��,��"0�Q��jFz��\�֒��<��m�����p�y�oűh�5�'��l)���`c$��� �Z���R�= ���o9��3ɐz�-�ͽ6���a	sO���l�/��ͧV��(UcM�;!NR�Z��w�`����v
}x���S��r%Hč��D�z�]�5���X�O�g��K4�Ke��5]��/�JN��t��{��t���gg�n<�~C|�j8�"-va�f�p���8�:�bpY�w½�j���[�PC�M�
�o�{^��%h�Q����|%V��8����	�Gt� �Nƻ"�R�-�MWl��9y����l��.y���TΎ�<���@�֣�����\�Yv�&Ou���5L>��5L)3z����c`���br7W{T��~����`L	D�n���1�ۗ�c��Ca�BҨ��|95�#I'�]<�I�h���Y�'	Q���/:���%�R�L?}�a�[���f�g.v�-��j�Fq��x;U�MP���D@1B/�7D�B��?l�BoH�:�n��7�[��+�E�Sc@O���IG����;�Wz�~k`Rr>�%�x�n�2h��_#���i1ed�t�1�b3���$揠_�#ZN��+�sū�(L���:h�R��cv���3T���M�^�٨����!�2��� I�[2���OCqO�&�M���+$O+o'��<"�
��x�9F�����?�5)�wCNZ��@�(��� j�=a.q�-���m�s�ƫ9��-�ʁ�����!+����"��b���l��)��>^����~۲���GW>:����F���k����E�'>wQM^Q��X�E�^j�}�I���d�|����^�ck�`G�2n7�qb����n3�-�5����kz�*����DIF�u�|ڤ����\��´u�&��|ZI�"A���Pn������؟w�ȣ�(0A�� &����:**id$V�U3x�,����	W�۫,��`!lq��_ɿI��3���n~�o�����V<l���0I��WT8l�B�up����u��|���{�UfN�OUd�m8;34x��f�~O�È#$^n��w�w��ﶎ]��ɽ�`C����q~ֹ�����$B�7-�q����H��'�h�d��Na�bwJxb��FE�	@6��53�BRҗh �-#��W�o��`�W-ll�b�����1�#�8�j�t�T�PK__w�C�HfLOg����x
\��Gins}����O���Yc4�|]��9a�w�`n�e������P�|��k�L�G�CL���dh��z�ajr�QE�}�śU��1b�,*u���\�mP�7�6U�:��h}�p2o���X#�4��x�PM;�w���DM!�.�i~'̷ߞE�vtT��0p��Ek�p�~�;Y]�|�|�Ђ���O���@���:�יa�tyS2z�TwU����;[p����F���'�O�$U�@����VJi����� �FP��K
w�������m�)�� 3jb�v5A����l��\��eIBt�=�h�0�h`��×fj���&����u5����VkAS��:#z4�Q`��>�/J�t\���Ϙ��g	����� Kb��G�r�A/���L��v��W�냛뛸׼:�^��{DI�ul�H��3f��89�f(��Zɧ�51��=S��9C��R��t��: ��(=#�Y����rLY�YdοJ�ҍ����%�Q��҉wOF���[n=q4s��V�(�X��sp�?'�4$}��'	��8(��/�# ����v8���,Pw{t�/iTrG�LD^���w��C���Cb���u�]���E.��^���V�>k�b�����ܘ��˾��Z	�D�#/׈���ŜZ�\׊���3f5���~�����y��)`�b�.���5���q��&�    �珕�L}. �6�� �Xi��g�    YZ