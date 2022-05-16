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
.}|�j��*na\.��A�Z 0�g��x[٤�������H����t���L,�q���g*/��6:Ģ��_�V!�X��r_V!��PQ�w�e�QC��+�'g-07�	��񅇓�@�|�w�М<Q*�d���l�I�c�*t���)����'Լ�����;��[��x��*�c4&����g]��5��6I�C<�>n�n>_ԭ,� �I8 ޭ��BLMп�Uă	����V���C��&xE�rw�ڿ���U���)���Qڮ��rut�OY��"l~x>_�A�hǡ{]|5�X�EPL/�n��dϼA� �O�̈́�Z#%ZpFR�#��l��~��{�l\�����4����b�=5>��s��<�p� ����Tue��UC��v�N����s�
�kQ�;�6�0fv\��U�@ċ��쫠
D{l-����+q����:��`�����ؖ^��W��&��+Ŧf1�:u~��23U��>��M�I�n�l� �E�?�tb�<�y��どo2ؖ`�%�%�о~H5h�c�Ƒ���1.�������}P��𥐎�LΈ+⧩�l���#Tخ�Ρ��d�`;n����;�Q{����EG\��n�3n�@�ݓ|�/��;'��`�#��*Sd뱎��H�%��4��u�2:�i�� v�e�O�t'o�6�y��͏��ف2���4䮭jG�5��nFU��U�0�^(� ��łM�n�E͏����iF�@�Kt�t�*��f�UP�<������N��h�� G�Ӝ����I/��?f���a3P�E�^j�%x��'�hē��#�
��w������L�2MK�k.�?3Hy/��z��ᥰ�`�/ݷ `���P��DR�"�g�'�Dj,��+�x�0XÁ�d��=1����_%$k�K�2�T��\�a`h	��dؓF���b�?�)PV�z{ ��l�U�M����Yy��Ð�X���?(%'�z�ZД�5����^�0#k�-?������nw����F%}�}!4�I+��Ùg�Ob'�0��%��`@І_∥K+������^��S��!@��V���]��Y{�1�[��k86FR�N��8�\9�ρhW�>5p\y@q��թ"�j��I%`�NU�E�ZQ������w��	|�j�_�/>�в����~�{k��阅�mD�I�Qq{����%�>p�ﾍn�Y�mD����0�]"���ؘ}ڿCo��0"+w^δ{�YF�eR��سz�2�9*�#�N{�0�B�����6V��ם,Z�9�T��鈈kCU�#W|a7�eY��u��� ��K�D�Qt�Y��kT��~چ�d�0~��t�'T/����|�c�%���b*�Ňׂ�xp_�[Fs������p���R�����Ч4O<`����:��������U_t0Ko6I�x����=�x|h!�u�Ʉ�z	�3:�#n��B�'W��G2�D�{nK@mq�Pr���U���(c�c|:n�����z�@V�c��V�A�Vpx�u<���Ȏ�&��c�����D�ŷ�3�j��AE7�W%�0a�$z�\���)�r�,\c�d3��9��_�t˭�>|�0[&pȡ��tH�ڐ����v�'(�*MMf��TВaj����i!ɴ(n�������:�����tl��U�����j.��'Oz���|dT����O,� Gs'�;@�F�G�}or�n�֞�|&�9�d���O�x�6F�%�#��iw�q�S�0�5�����V<)f͎��{�__㠈F��� �yl��f��e� ���J�ژ#o@�o-ĆK��U?m��Zg�Z�	ۆ�_s�����mgr�x�|<�v�O�<<~���ʁ'��8�BǂYR��ɇ�4p�<G���c���-X+�z���>zPq�����jx��5�h�Gҹ���yT��"�`,�?hZ);h����u5���Zg���a,�N���M]i�E^�XH�mY��HL�H\���̫{��5���d?e�����p����I��hl���1���7)D�<dgF��kMHGX��Q���T���v�e/��t�H���<�Z�H����!�J���W�t?v()_�l����r�5�
��:"6�8wGer���jЩӂ!�Rb��@���2�t щ�`j"�XU���kɹ`ނ��C�b�j�˩�Ye�#1��O2��x�%�+ц_8��	y��ğ�t<�+�g�Һ�#oó1�=�z�<0YL%W�I��L1�="��(ӌ�j���H��R�ϊx�Ec�eL��w���. ���:۵�>��6)iR_�s��R妅��h2o�>ku��z�Z������\$j�2%���F�q:�* ��a"��(EwSbN���W���j���
P��%�B(��(���'��f�b=.Ok�`o����z�����f��aVãz8c�o���_��N�6El����'vZl됩���p;Ͼ�S|tJ��g���	C\9�B��t7�yi�N�6=z�M��6V��f�W<�`+H MM�>��?J�l���������eK�i])R�&�D���cu��Ϥ.�)�0q�<�U3D�����0����Y�m*�=��%�����8�~��M��2w��@����.��THG[�}�#!|�,�3ޞ�j���	1�5=?�|Yj�i�b	�!f69;�ֲ� ��r�Aխӎ��f���51���_�f��\��t�;n�My_dx���Ǽ1(_�P�+ˣ���I�i�^RwIt���Z�uv���,K�[Ġ�K���*&\��׵H��#�B�:�	1�r9?��<N�t���0u��]�F�MF�~{;9a�@�ψ�*�lp~�5�#��q�����ϻ�,��R|R3��i�*hej��$�AQ��מls��T��M����˅��X�E�8��˜�P�������9���aEȁ� &j�=��`�ae(�i_��+���i$3��Rz�f
��E��yh#�;�,gN������W����=�D��H-M4�$��HG�&��4`�1���SKt`mWAqͽ��Z�VU��m�!���	.x o�H��t�Z9C���P�]��7����?�"j#�_�r�:��_���-eK�(�>}XF��Z- R�4%��#
����$����D�E���h�vߢH�nX���'"%��Cn?�ި�$_QE�>cz^D|�h���,��}�:^�m���p���sd���$�{�!�lo�c{������Oi�zʕ?�>��7ys���ƶ�-�CQyn0�+\�dᰲ�]v��aS߷��=_Z���}-/�1�zJ�a܊7�:����0�B郞>��6U��& �u9�]�"�m2>�HX����J\I�t�HI:��Kb�& ���,\��%�ؐ5<�R����y/,������t��K���z��qH3E��`W'�|�A ��@���r'V�!-���3adz/��4:�`���a�)}G�Y�s��ȿYݜ�!�%����Q��|���r��4\�c����V��ȵp��G�ێM�����������Y��Q�.�J=d��� Z��Rsp�SE&4��1@ӊ��x����q�Ur�ȗ��� ������$꣔�-u��>P�LԲ�ld�
��k$��7�lo��W�:���΀D�f���4MɥV�$�QO�v�YF�+Ǐi>�����Y�M����J�?$�Z��� ����S��g�ԎH���=,�HҬ��8��Pv����XK�?؇�~f޼�7����N@�E����\?C�H�㍑i'&>��8 (v{l�D�<�-奦BRQo��ej?��ڈ`��O�G�##s!�4�o���ƌ^���t�0E��yM�ّ���q�U8J�`��稾��g�FK��w7w�9п?�� �\D�{�>|HV��R0k����q�=9�~���VZ�)i��ʿ��r�����5�~�Q=]B��������sB�i�Կ���$�k5L=����f�(�@��L�{���,�A§�}*�CIh��j����`s b��G�V�#�I�gqd�%?�`59�7��H�ӈ�+c8���].�w��SRNi}����m�C_�|9r��u������f�$yB
��"(	�W��ld�GȰ���Ɯ6���d����n��o��Ww�\��>�3Ƞ�&�;�}���"�i4����H�2E��X����Q�(��"[��S� �u�|�HDq&���N�Unr?���}(E#�M��ֿ�L$G
6I�r#Uͤ&v��ML�=�W�$���J����������O�Z?�h=�=���EJ���<�R��t��z�	�57m]�6�ᤚ=R첖��0��E�Ю��W�%+�ÿ;V��5{�EH�����u�Uq~%�1�`���hwHE�LqV�'���,U׌��Q��U�_z�,��CZ�E/)��Fm��g7��<aQX->iL6���jN�'��-�c�MMo���Sk���ӞB�f��f�C�}$�h�t��]Ճ$�rSs�j�{P�|J A��y:7e��`����0艿Z����`2<���Jq�b���4c�{(p�L��t�S�C}1��Q��IV��]�H�o��y�A��dzk�U.P�T�i��P����o��Vu�j  ��E���6B �%�� ���g�    YZ