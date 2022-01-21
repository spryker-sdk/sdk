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
�7zXZ  �ִF !   t/�����] 1J��7:Q�!:���e�Z��7�3i4��:�uUa'��!LH���*��p�`�30PD_���DՎ�t��ɻVrtEo�\L�]�p�:FH�3��ycM��p^�N�x\&=H�2��.x��"��%F�����M�-�(ޗi��EqiK��Nԓ��{z��;p�5��g���Rw���x����~�;>��:��؋�n��)Q6����{a�^N�n�ܩ?ڝq;�*;ѫ�Xo�	��_x��|�����W��Y3�q9�5z3��t!Ƶ�����d �y�X�xg)-׻E��R�6s/&����/����)́��B���j-�;D+Uv���0�
fZT2���m���6�a ��pq�Y�T���E@rLp|`V!��}�J�
�>�}���R�m���$K8��+ݏ���@W��|^�JBt�`�� �l��$��?cL��v���V��x�Dd\]%T����4��h�� mڌ|
�XT��,*�4��`暱�|Ul���r�ګ�XcX��/����4��HE�5�s�'r}q�o�C�s��R+?��`ٮ;�q��!A� �j��� ��dzTb�O�t:�S���'���s���>�`��5#�,2��R��jn�
���}�����Q��2��Z���m��YJٰ�u��>h}���@͌�G4 *����)-,�z��	[e�'�#F<�'�1�
�as�"	�|W���ڪR4^�U��`KD8�u�����V�e�q8�R9f1�1�u#Y�P�7��͒{�U\9���ǖȺ9����t#�~}�7�Ai�I �O���#u߄����^��89n��*3�w����[�x���Kf����ď��+�,�����IK���6�9� �@���?�]�b:�CdU!^n�x��?��~��C(e���a��Q0���Ư��z�7F���h�D�ȗ��A�5�H��˃#9߸"�/�nQ�s}+چ ԋs12� $��:s��qƥ݇:j��J�"ΐ���s�`٦��ϴ�;��K�)�A��)�o ���pk���^�hQ�g�¦�����Jo vMiDNcDv�3�q u�E�F[��f�Qx(7~�!NA.$>"��N�Pk:�[�0K� �Mzg�����P�ѠQ���l	�۱qs��Ǻ��N#_��q�;����N�����8,��f0ߜ�_�ְ*-�`v��6ګ�4�zp����\5�b7�	A_�� � $^��E�1tPҬ�]�S�����^'R3]�,uR�.��	j��#g��&[_���]�6�D�z^�?�d�_�j�1m�6Or)�����E�j�WG�7��i�Y@OW9�P�t��_9'qLx�3�+d�d�%B����V�n��搖��X�7�n��?E�EF�1C3�K^�sa,G�$`}&���(�mц[>���1�
�"d�Ư����9��x8�vE/w�Bo\��qU;b�,`�5�m�d�m��=1@���=r�o��S$��Lk���Q�S"ĬI!0��+���J��;�.=���en	�shˎ�8ݲ¥�|4�b�"蔡�l�]��Q�Z�uM"�H�n�W8��Cs©Մ���v��T��甴�}����<x/��㏦oÝ�ĂU�6�u�qS����'��YwL�Z�>뻽�E�W990�.e1a�Ȕ���#�LM��3���X��Y�&�Usm�����UM/�֭�z�]�졤�c�W�Qϙ���( 2��δH��`�Ց�U�+���ߘ(�@=7�\ዞs�>u�F0����[��x�75��hO��l��%���I�/,�����Г�c�h��9�������m�6`z�8�F��cX�"f�P�u�:>dY�M�Gw2�V�әƋXt����n�_�C���g!@�MC���ŪZ�A`��]�t��ֹۂCB���G�p(�Z���ZV�qa�������l�Xۺ;���o4\��B�Ws�,��0��I&�\� �Q'�z�ED�J���+۬:@�O\�gd0��@쮐��W���N�k{�Pn��;�6n�q~��qs�G��)s� $��?�)�[ED~���2�6�R�b���홌�����Y���2�%���붐T3����7��5E��B�#����U�/m]z�
�N3�])z���5��
e}����zʬ8��Ce ;c����	��=�[� DGL7�@�~��fq�x�?����W-Cz�;;T?����m��G�I2�U���)�2�#�B#tr��n�R��:h���S?���/Q�j��Ӻ�<*�&����j�yʜ�����C_P4^�(�� ��@��U^���ѹMXυ6������?��wd�ԕ:�_���Ө�v%���3�~��#[���?����In����j��WnN����|ͥ����{��A����d�p)�f!N�N_�n8��Q��,7]��;Z�l�c�kp�T�f�Qw)^��`��,�!r������,�t�¨\���R=�>��8��h#G��&b8l?Ϊ�k{�j;��lFט; ̭�NBB�Q��ػb�������̆ ��%�<;����~gFx��*vȆ��� I/W��:���a�a[���G<	�_�j�޻�Ƃ䝚�Q؀f��5c�&
�E�b�[ɞI�/�A��L<��F��Y�.{�yR�3/�C�Aze���O��o�@ܵ�Z@�D �rt�3gl<�M�Ƀ|��� �J�af��߂�J����-��ɏ����v�1aj4�RA�C���0��v�ٺ\v�ݠ��Fu��I��K �֋�x~��g���4q�#B�h�B�����e)CD�f�X�]w,`3PK�U~飆����, �L��a�4`9�ON`�%<�ʯX�"oi� ��^����{j��'��<�u�0M��ï]���
+��A�mxZ>jy�s���.�3��L���)�ɿ۶�p�T�9`�J���c[ŌpUtC%���`���\e냝9����܎k=�>,���򥞨O��挖�>���[z�\�]�ѽ�h�O;x��e[����\"�d&��x��gPA�v�(ʋ0
��k�t#jM�3Rm_h���	�P�@51Y<d�r�ᡬ�b��ӱ'��
���W��7��_���eNO�
�ue����𒒧*����(s3Of@�t͊��?*[q���Il��o�P��ϐ���I�#���@m2�|��`=���¯����8��p�O��2��-��	(��������� �v�����}g�7W|Ie�{t�����[�ۏ�ڛ�$�[�੨\�)4��`
_�F���Dͳȳ�`\0���;)�4��ly-N�+���^���ͪ��>-�r�V�}���ԟ��,x�dR���{�|Z�
nw�/�m,LdK���]����疜=y%�a��tU�͉�NK�$ۗ��k�D#��0K~�c�	v!�N�ٮ����H.G޿�G�FGEw�Tt=N�g��_�Ex�L�fj�!�;O�jb�1���C��b�!�z�W�/�EKNg�Ï�YL�[`�{���P�A�e�+Em��ī"�@y�* w�=����+� �g��T*�v�P�.�<�dxx�pG�g�hG�u�NA!Z��7���ƻ�_�ĮC���z�m���DV7�m3����u��Ū��-�p�������&��x"5�^�yY�K9k+27���%Qw�����*����[vR}�y�^���J`����ة��u&���{�[Q�X�}�+���|�D�P�N�C�X�SQj���R���w9Z��N��DbP��@]��$�*{H���8[����̬|'��_s��29��4���`�c�ɳ,'{ʫ�0��ȝ~]������YF[�^b�l�h�쳍>��U��ph����_щ���˼�Mtl�	���6�Xm#>|���%}��J�Җ����_����i�S�"_=Gt��)�Gȑ־�	\=�6�s,R\a�,�o��GG�n匿y������Gi|��οsE�F��8U��R{z���G�r���so���h���κ�����D��W�$�}�޹n�h���������~E���"���������bo[�OG��.\�ҿP�o�x�NuU)����M4�:>���_�ƽ�d�H��:R�g��n����J�������C{�
j�\P*�� �P穕���D��{��q1��.c��-
}�@�`�30�/��|aÈ7�嵻վ��.��ɖZ���A����-yf��#�u6�t=�)4���Y(F�i��D�K�9��)O{�bB��;�������U��x�2�'��<��a�&񫵑Ԃ�[1�������eRF�$4;O��}�š�N4u-�lH�~͖G�n�[�af~1*T=�*���T��\�qk�� bd�co����.2���+���-���"f>1����	[↢��wϲ?bjBB�*T_f����7Xa<��+d<R��e����D� �b��oI�;Z	�1�d(���S=R`����d���� 6���!�`!5* ����)�� �#�� c��g�    YZ