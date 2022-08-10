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
�7zXZ  �ִF !   t/����] �'w��v���(g
�A����530�����"�k�����Uh��Bư��G#2�:�����{�ςI��,��y�!7,�j�($�e���v�T���d�S�u�� G���T��'Whl��Y�i,��;]���c��G�q����?�0���s������Ȝ{Zd�Q�~E�eb�b{#���c��u�"$�7�����tv��|\<��"7W�fY9ne2|`��P`��+b��d�f�^ǿ6�@���0�#��Tz�ׯ�i7+�Dh��r��UW�}�wǅÐ��E�'�*���b^���C�*�.a��/�h��~BېJx8�d�r1ɿ6��Y/�d$I���?q��S2���=]�@���@��<�Z�����d��/��)�z�@4�+��}'�<u�֋��
P	3A.*���P`0�*��n$�wa�Jo�W�@0V�o|L�G[ fn�I:�"K���g%@А$�`�'s��(.�70%�V�7��(�,%����5�Q ���6[�i5i#��:�8�d/z���|�/w�F2�{���OR�}mN�QR��v�U�h���`�v�����2�/�����g!�z�Y-M�5���� �V`�]�D�kw�a�C;`��vf<��� �1fo쑃X�lcxgQ`���;P+�c�d����8l�����
����IY�'�٘��m�+��l;R�����,��S*�G�M5�ɸO۬�~ܦ�W�9�ZTi�K<����l���4ƹ����v�ĳҥ�ʀ:;f~5�ㅜ�Y}��R�D3QOȠ��$�Ҍ�OI$c�2>�Y�N��V�T��8���n�+3\���rw�C^�� ސh���Q�^�:��wɟVO��&��T!A_X�����%�}Sd�_����u6hQ�$F������b��o?���Htw�.����P�e��ՙ�[Rq8�*��/~0�v?�K��	٩�#������ݛOfU���:�H�E�Ck}]�!�E��.�Z��)��D�U��R΍�KH4u�{�9>R�SYo8��e��f��o����c�/���'�i�ah\"X=�@���%T�1��*'C�a����nSCt>;s�x�'&��N��#p��:��Ģ�����=1�>_����y���W�|�Cz6�jȿ�"�w0_|�+�#s���O�mg��V��c���V�|.$�{�9��
��RngOS��3���'(]��	���،��tG�|\�o��q�6����>]O��O�[Xu��J�|�w��m��o�:�T��X�3A�0��j�%�.�<UA�y'�{���]����t���M8+)��ne;(�_�aP�5�G=����}�]T�h'�'�~B���%R^PƛX�Ȕc�{kP;��ɉ�6�~{?�F/�V��al�Ͽ��4����W��Q'qQV(�Ǧ0o�i4N��\(N�8R}���,ұ�x셬�&K@:ǈ6�O,���D���k��-���#�N�J�['�H.R�z1?�}���v@N�&� Ub����"r���T_i4�
6��~�8V^���gp����̳�,0�ټ3�%�d@��n����a�:���F&73S��Y�:�D(�[��G�t-�i���GA�ս���]�D�\�?Y�f�Z�`���w\*�h�c�-����Y%��#���j�I�����t�¶���0~.�Ê�{+1���m߻�x�l�f!{����"ۣ?.�%�ew��D�w��.m�Zk�z�$�BXD��Ȁs��6S�`�Ќzњe���ø1^�4~��F��]; �ӌ�UxV*���� h� 6FM86 ���Z�a��j+�񚨆5D�ar:i�\\��a�W�hl�G ^!�Kp��v���M��/	�ČBLMc��}jQ$�"@䥵�;�"�IR�b�U,��{��@,IRק5Ze�Z������Y��x*V����s*s�	�>A}-�Zl,?O_�ǃ3�\���r+ ��Vo�|��:���NoU��;R�#��&�ut���~�<k���-�,1�R��:$��6�E�p�߫{p�$]����"�����KFs�����N�+$�Q�"�v���44�B�e�?Nnv縻D;�4�L��{� ;���8�7[��x��l������[o�W�
D��C��V����sx!Q���s�I�E����cngخIf�]��:���Vw\�#��eb�����V�p�y�#'��=lDz�W�N�"��Z�dH��d�4�J,P^�f&͙m�槿�H�Mi4�9�̧3�tv��\,3��C� ͵���^��a6D�\X�F���l�� �k�w��v�*��N�.Gl0��.άx�}�A����<�Q�e�Muz(�ȪVQ��r�D�J��Ɂ�����pi� �Y(��&��cK�����3<�H���[����e��W�R�<�vN�Ѻ����\.j�	�s(w+P��I�?o�f�BW�ў߷9�t0A��O��(GxlA&��	20�k_&���뤦��KI�����`�u���g���Ӭ�Ö��m��M+�Br޲��v�qW+v_u���>P*ȓx��|�_|����4ͷ4�1��Gv��s��'!)r C�h#���b�Ƅ��@���(��H�n0m[��6:��jw�P��z���$QKn��JX.*��X~wrӼ�@6�JhCMfޑA�G��9�%�*_m��n/��Ƞ���8����6!�S��R�\6&�8 �c�Z���i��#t�|��C������n���˚Wo�|�&��:j -�y;4*xc�if�#}q�I���n��<�O���w<���2#�
I'd�R����%k_8%L�� �0�f�TgŗN�p�a�-R\���B�QXɣK�wT���OV������x�}��C���j�rՄ8�SŘj��"�E$q4���\��҈�[<�CbC�����m� �y�L�����M�o�ް��yK"Qe(���I�w bg��灗ED�v�O�>	�˖�������u������!�p$�?��[0��v���A�b������b�RDuv����":v|���geN�ЇL�b�T��R<#��U�(�>V��,n�~A��;�X��Jyw�����GϤ���� �Q�c���'���`�� ���=�"p%��R�q�X7v!�d-+M���0d�E\b~}},���v��l�W�X�}� ��d�o�:C��ZO#�ypH����Y��n��Ԃ�ϧ7:`�}c���Q9��іnYpY�%	C�/P�Xfb!���s�?@�B��@T!��X��p���!_{ըS��
hi��pܑ:٬;��e��� ǳ��Ր썐��(�쭱k}�OW#��>�?�M_m�^J1�۞��w����3���0@b��xQ�~R|`&~]/�2�\mp�JJ�	2n�!����x ���>�j�UJ�������*WO�c�+L)IP�?��{�j*���9Z/{���me�öA9�h��¡�'�I��*ѽ@�)�O@bTE|db��ӢT
�?�N�f�3s�&x�-P;�Z�$I<��Fސ��#!sTG}�	8��c:�_���y�c���.��"�\G2Gx��/�C��U]s������2f��s@ʌz��"�~ܗ�*鯅�Lq%M���J�iqS���@7�R��Ȧ=m��ʂ�T�r��j�D8�	����RlnFjR꒎/)��Z*��/F��gͲ�K){��n�*_2p�����t��^��/n�/�j,8����뱺!)�P�S7-HFrjb��})|���P��\=e�C�ֆg3�_�XL�rkYi����)��GMx>"��m;H-�  Mڏ�����p�ă��A+̟Sܠ�n�c�b��kImn�ɤ>���$�/x�0��R}Pzm���5��Z�}�,ZzW(�O���n�N;+N�T�ڇA���cܡ�զ��<���pm�kK�M��:t�|�ȩV�,uay�f,�V���k���$5��z�������Y<��;ٝ�}ߴ
�W��2c�ɔ�(��w�����Y8J»H�����T�	.�ձ�v%�1#e�W�ap����"�V>��J{̴�	���fN�2Q���uhh�ӱ�i-�7z�~��[�Bq(��D���Z�Υ"Wi���Q̹
�s�Q-3Va��tJY�C?��[bf��d�&�����i��A��`�l��zB��)g�Ŏ�
�g�@��j��q��F&����G������g!.	O¡��E���Nj�c���8Εh���T�{�,R��89��� E�������*�,���c��_�P	3q�Z�"�dԒG�@���ݽ�~�F�~0����Xę�:�o�����@e\�sy{?�zUB��ޫ-_X �X���yK����.�y��}�0�L�bM/=��1�c���#p���l�(���Ez���C<:QDd~��&��^}49���5JO�I���3�Hbx _@{�~���w��vx���8|�#�2�z���{�L�?�����G��p_NoD�^���t�7��
���N'��)btHb���R-5=4��K"�%�-����a����Q�!���2h�=m��,qp�4AVD�)In����=�Q�^N �v���O�t��n�U�H��gy��9�^+���ܪ�N{�^�|��T8�ʍ$���
���B���"׾x����#`z�j;?W�Ѣ"#F��;�������;S�6K'�=��%m�AS�c�li9������j�7H�O T�S� �*����^������/b���m�dw����Q-�_��(G��Y�YC�����</�Dӏ5?��X�c��"�.k�(0�m4�c�8C�3&P�P<e3����R�g�a~	��ҋ����Rh�p�үa*��U��Q���b�H�R4+luE���:bqC���w<ڌjť�0/��(��!<�|�_�����:!
2��J����Ŭ�~��ɖ���v=�'�8Aݮc=�z��z.��I��M9�B��St6��֦�'�t�����/1b��=?�MQ��8�;������}�]�榵Yt�	Ej	�=������Y@9�V��|�נzf�48�72n}�w>o��vOkM�t��վ-�@Y���o�94%�&�i��. 9I|YV�>OJ� �6[� �4�%��̉^�:��`���#>�X�4Nt]v9icd-
���_3��\�wV\9��d������YA�@B�+��XφIw;��h�N����?|T5��x�=�M���)3�<:����<�g?gX�ekp��$�-�L��w�}t�A�1�^�}�Yڽ������R�������c 1�_��7��Q�4O��,��9&���s��6�UY*-�3�e4�a�5k�Ar�QF��#w�6u����[���U��Ho�X�{ʉ��[������uJ�y/�G�H���R+�O����_+Fh��� `��������	^NO��ȃ�� 5v�9)�,���m��U��S�Вo�B����G��_����z[�D@M��6�P���-yZw?6�#����>�e+?�x��E�%ͼI�khH�����ZB���g1�\�� n�a�g@:�u��m!>ϸ�d5���6�{��`�d��Ѓ���,n�?�����&�#�2:b���|c����Vo�p/m���O���J�[��@s.�yz��g�(��6�߷��(�aY]�Zx̠�d�+\�ݕ��>Xu۵S�������ېP�Bd���Յ�Y��!���6����nO�Ky��d�FK����h��|cBQҲ.�-��f�u苜�$1�J�	�vw�?b�m�y\1�8�����	�	
6��Ȕ�U��a=��#B�!>c6T��3{Q���?F`������	��8ar�l�>"�s� �F.����r�x�q[FTIE���j�� ���^�x�+\s��.ب�K���U�J�n�b��؛�<1��q�����xSV=�sPK�/{�i� PX�x�^	垉��aPt�8�O}�,���gk�T/�^�����HiV�ʥVA��v��M=��FM��a:�M	��Y��V��΄���;�)��e���,H2���H�VP����4��wU\ժ�a5�
b;�2�V��O,=�:o=�m�R��GG#�#�֤�*斜
zlW��g�5m�qA��KX�޼˝��B����D�:�Ak�D�|��:dO����גb�z�4�5B�]·KqT]�d?	l�9$��y`�� \�����(��G�Q�*��>��ntn��U���p��x0<Ad�����1����JA�w�۟��t��߬|���Q���O��?bE�|>��8�,�/�z�{�Pjg���C��bm�9�:��y�N@�geG+��]�)6��&�\�<5�Rk�C��{���<�?�2�o�#(�ɏ㡑Cl��7ޞ_�<V�,j�f���˔�q�@B )~�$R ���1Q�D��xߓOۜ���;"-i6�X�!�Xܾ�W���O=ZY���.�Ôzz��5��}�to�eR�;�.�nw�Ӿ�$肊]߉UZ�75/���í�R�U�5/O����so���/�#)���-���+W�}S-?��f-P�V*	��I�ݴ:��z��X�u�ai���|���5���Ժ��ᰯ��l��ꮩI}���I�o�E�ZY`�ZNuq pv�B�sO�� �O<!���ގT
��C      .9� �t�� �5�� �����g�    YZ