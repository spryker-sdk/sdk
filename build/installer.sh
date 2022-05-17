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
�7zXZ  �ִF !   t/�����] 1J��7:Q�!:���e�Z>4�,�L��L��[F�@#�V�3��-�1�)��9`h��6cѦ�%�U�I4�Cؼ����t2k�`�Zm�vӣ�q�������;���Wo���oW��)tP朋)(�r���*��~%B�L��^��������w���{tV%�r/��|	�/U�� μ?��}6q!
��՝�6�p�)j�z��{H�t�a��0��d����=�ϯ�5�2�
� ��*�/������}J�ʙ�;!����t��<7�/���}�����%	D�)���MJ^d�7�#(���WG�4�������WTS��%��QaEO��/+rQɈD,�cdy�>���Jl}��fI�4��s�B`k�2;���%e�o����M��"ԥ0Bdzws0��2��l�ȡG���"���,*�$r���͛JZ���k๎]V9
�h��v<����x��U)t'pײ�)�F���
�U��F�щƎ�i�aS[\�>�:���U#;�����B��M�C
�9��� e�����i�
3γYȑ�ZL�׷o_�&���͓���z�W��MY�Id��Nl�.ՆR��@�N	�f�y ��9߄ҎI��Q�C�H���b@��Iw�)'�^
�~$w�o��f�Z�#Y���s��m9�d_+�l^�V�/eePd�����d�N��_�&f������I6t�H������xJ��9�Qw�;.�f�UIvg��~�b�K+!���=��Yn�͡����'��>�{a>�W�R L3�����B�-���\�ݺ�!c ��P ����W9�Q/b`�X�����*rC7��� ��s\�������R1����iJ|dg ߲�b��<��)Z��.����ľ��*��"GPU�?̪��blaX��ʤ�?����a��I(�	E�~�9q�ؤ����+Ж��4���;��H[N�swS6.�'xg�������vJ��QH�N���x����[+k"]؍M��n�OU�h�+�f���;�^�1�G�|S�Sp"�V��-��9F��������-�Wi��=�^_��� ��e�5p
xƘ��!��������L�1�n�Y 8����$���L�gQ�5$���(�72Sx��R3�}$bQ��%�e��X奲"���rɯ8;����a����8�O�S�|Ӄbyd����M�b��*e�|�([\�6����0&��
by�S�����#�A�A����R��Tr�/�)�w�� �Ϋ�Ibu#�C=�K��H5�M�uI"}j��Ѱ�CX�I����0.�ۀ����^͗xۦށ=|�6%��'�x���zH��c$��o/��ga�?zm]-�R2Ҋ|������ʩ03�&�5�q���@�e`�p��[�#�:�@��Aed��j�'�є��V�X>�[��$^�%5�'����X{vz>����Rkj*���z;���<"�3�3 ��	B�z�c�R��Z������)��凖Q��韶�T�,@u� �V�np��M��T�"�w�q��>��v&�h�0��n`�����K�~_tԮ��ap��?G4�:6$B��e�ɱ�r@i���~6�� ��3�-ڼ��W٪V�>�⦰uHn�����}��0/_���c(\�����㒅��*��L��l���dIsp��G�A*:v���t���F�eM�`�%PZ\	���G�`��pi������)���'�ݞ�@��L^/-+��	fkU�t^%H�=|�)�d�}��b}ǲ4#M!�]<\ӡ.:��I�O���a�s�F5Utk�x����m@n@�O�
���J%*^�F�Jfc����R�4/����s�{��Xl>H��3�_HI��ǝV���u���^(�����/1�)�M6;�K�p|K-xXj���:�Z�VME��M�� ٜK�U����>�M��K�� S����Q��?��!�;�y��C���K�#fB� ���!�����j}{��YYY����7���J'�M�X��������P����B
�W.�ۂ��Fku
i��?�H�4g#��Q���""�*}�}J�,�U�X��N|�3r/��r��'�;ҵ��7��K&��+P�~�}�7�/�/���n�C��&>�d�=	�v᪍@Kn�3�w�y��c�ŶءH���������Y�n�-�PY&7��3��Yn.$��Q?|����{ns������� ��d��us뻃;��>e����P�6�T"�L�g�OL=�ݻ���|��8pɰ*���v��YC�#>�<�j��vc�W��䥄L�7�'���W��\�� ���t�~��p�ЕZ�`��5�N�.a9$'��]��I�kH�_}/�5tɁzl̤^o�5Ai�8Dy��[�W-��W->�c�Ý�%F�W�<J�f�8e� ��R�(��<>�L��?���0�Xr`�|X�T�l�y��0��N�#*�#u���T��E�br�O�1v�i�J���m�j�hJӰ��q#��H����Q�j3��"߬yG�L���D�	��CDh��ÃA�������w��2��cb��v��)�w� $w�H̈k؉��+j�>p�.�Q�����I;	�)��w�|�BE+���p��i:ux|�ck�"�w�V\��9U�0(z�ڄ��#k �"�\�-�sj�Qҏ��j. ��~��v\�Z����Og�ʬ$�`����4�]�iB���@~�wzx���>�D�� �O�-�/O��xj���+���F��gVk���;�>r�M�b׷M�q���
�"l����t��dJ!g���6ߋ@�p�Ϳۧ���}p��y��ys�6:��b�v�9�l�Rݦ"�&Ҭ'���l�s���+�W�A����l�Ç�����n��N�?M�PE	<L�f@��Y����l��ݩ��o�K��\ ���{Ic�L)=$�P!@����x�]�hMP���C�ރ�zЉby]�p���Ef�;'Z�t��B;��A~5� k�N��'���,Wl��������oՏ!���x��U�U�V:$�+WD��]��*5gd��6�#fA�G�E�tŭ��7�I�������B�?�U{-nfJ��������T>��J	�w��e�&�����&���k�.�*� �P .F���R�M�/\�`Rsq��%��c!J�lK��t?��
ؼ�ӑnv���)�Y���wd��I �
WE�<ń��r��WH�����]������T�e`gO3��
n}�h;^�E�ɭA���Ŭ�e-P�o��+�dT�Z�b�v1M>�:Ȭ�n��1`�_�����swyx���9H,�C� ��|���\v�U�9���� �cw�n�������KֽMC%�K���nw����4��͔�'�����=�M>D���^ǀ��n�]�*��{v0��z���ܷR%f�g�yR9����L������1~���G����ܱ2T��'[xzy�sF����O��|�L���f�q�jU��T�☈Ȼ�����:}m&�&���e�Rۊz,Cc��F�+�.�T+��'/��;��$Ӿ�g�1�)�۲��CWe�C�X6tqWq��f�e`KW��'��iֶ��6y`&���`����=�LiX��븈���V��\�y���țT[����y�w r�7pӢ9�;901�n��� e�Z(I&!^�̟=��H\�ɱ�.MW�Y�q���H�O廡_N�T���O���;,��^"ͤ8��� m��~����+������ɵ�Q�''4o��'ͳ*�'�m����_��*j����x���&h��Q%��*Xߗ��%��YF]�S���!�s9���9�]��$Oo5����v��"F����]��bm�7���:�7k�4��;Kk�oq�n6 Ũ���`�֕3�����חΡɱ��.�5��Au��>X��f�v&`��WQ^oH�NJ7l��g��U%�AС�id�E�\���u%mG��_�2_����Y�U��-��,`��u|<,z���\;ڂ�2�Ccb�!��iD3Q��W[M��S��3�B����5�Ϭ�� �L�]w׬ڈ��pO��Wl�����#���p4�D�dQC���L�Ű,3ç
�r�d�\��$w�:�k6�j�h6غ�U�Q"�8 6a(E-��
V�@~��X��IA��&�Y!�ⴚi��}�:\3�/��_��M���YC��j�F=js�AqA�|&����e7�ޥ�[
�@�R�r4<����P�?�=���	����`o�/g�
"/�i��Ù&�n�ؤ�b{\c��<]��	������v~�!�M7|�Xh�z:�� �X���q^�=_ V���AG��B�֩`��7��'D&�X�<ƈ��s�ig�����F��R/~7E(���p�NѮ؆��M��^� vu`(S֗�xm��9.笗��H�|c3<��pu��؜U�2�A��}`�Q<��K����O�(@�c�-��(#��U��捈�oכ"���W����T��V�qrh�~���T	��Z���ӎp�����j�r���>�׏�/���àT�V���%ٞ� F�&hL��H�~��qwZ�.���yLA�@�b�p�ӓO d�'�icW�3�*��]=��c���[Ƕ��g�����3�jp?�nB�o���1�1����� �S��q_��G�%���~Ê`�Z|�������b��
���lq*p�hC�u
�),|�zNt��d*�=9�OI@�� ���!��JE��Mꈓ��4J�JW|�:�>�� �������՘h��e'I*ld�9!����Rz?T���7���^#��
��f.�Q���Zy�y�d}E� ib��N�Ơ��c��ֲ�b8��,�1�cm��^g}Y���ctF/+94R|,���R@�����H�+̃���k1�wf)�`?���-(���^JDPP��)L�,�W��y��H/1E�ݚ�2���Wn�G:,�*�5��УX']��Q��Wr����o��<iya](y��~_�E��ˮ3�?�h�#���#���*�T��i�Qь�>�0P=�2𽗥X��߭��XHi<T�9��#P>ӡp/���7�q���^aETI)���2o�9|Dx�`�mI��#S���Ձ����\�ؚdre��%����O�}y��q�S)t׭9f(A��z
aލoj�g�6��5�`!h����BCE�����     B��"�r �)�� ���ʱ�g�    YZ