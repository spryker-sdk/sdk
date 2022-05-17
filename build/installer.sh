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
�7zXZ  �ִF !   t/�����] 1J��7:Q�!:���e�Z>3ܳXK�#Z�9:�U��̟�*��EM!98�|��s3tMi�)Q|�Z���ؚ�%�o��c��|����44�;L%�;ip�8 8;��I\ƞo)"%��!-m ŮZb�,T��љ���(�inğUEoP>y�]W�{Fl�<j�	Q�3�{EFK�R:��RzS�C�;�O���� ��jP����L�#/u�h��U��kѣF��<E����g_������|�藿$��{Ŵ/_���r�S"���Ș]���ń�jЛ[f��Cd�<����yL�M��E�tkx0h���`~�?�mCL�=��z�X�گ�V(��'�r;Pd����&I�Ƈ�u@0<��f��S�p����?�r�L����J�ٍ\S���N�_p�؃�d�&����@�b�֍�9��fnÃ��̕\&�-�j�MZ8f��e_Z��������L�=�?��9:`�!#�+PUҵ�T<��z&�ٷ,�P�$�`���qS�	"��C��X�9hk�ҩ�ǉ%���xi#j��I⧃5H�	�C����X.�ep�d��f���3"�%�s�}$��a��ø�Ӂ�4��(ؾ���pv��0�]����
{1۪�fX����Z%A��:�]��z��P�ϻL��)�#�WBL(�y,��-�Nh͡���'���L���f، :@+3�s�/�J����H.P��!+n�al�y)�J`@4���t/犮�C&�w��6���>S�Ư� ��J_Z6��"JTқu��z�u6āD<�Qo��
+\���p�VP�<V�,k�=����r�QZ�@��ȍ�8q�P��@r'C��zn?v�<��wi�s��5��y���@(��i���� x�N���]��:�����ŷ���ެ�F�U�b����am�vU$�{eN$����Z]SV)�4�����������T+�xe�����w�	�����F�����# ;������o�XI�_��{/��㕪\�z���X��3ǑKxÏ�Z]����zkOgx�Wj
���ո�l%���;	\��0�pmI���L V�`��~s6�l�k/��C=�,�>G�7}C��Qa~1o�4���;�쳨�a�� ����؎�KȤjvY��f����
�! :��RCO���ɪ��k��^Iߧ�k�Y��	�Z�2u1����	��2�N>C�q�v:�KP>���}��~*U��ac'7[ ��P�	�f��.��b��'��`<������C}A��o3����)�eV�ڙ����Xt���fU��9�ٸV�<�L�ϵ)�Y�ʌ^����,&_va��`<�
� �6��M�q�/?����դǘ�6TK!�$��!��WrA�ܬ�hR�|a�z�0���f���D�nN��-'�NWp��7�<j��I������k}Ab�2�u@�
��r3����lʌ���.�z���h�j�Ds�Z�t��`�+R~?�f�� xų�,_-xyK����5��3�EY���F�J��ĤU��"�$wC�s�>N�S�j� ��T�ܿ�FT�I�Q|�<C#�O��0ڤ3�ԛ3�ce��������73���ީ��A-!�:���!}�yxE��摗i_��Z&'�k�m�]m �#m���������J%yB.z0�|� dZ���i] !4�iF���pW���ۯ!�'����\�FsTxg ���M��:���d	����:����GV�!_��!�U�1����6d����w��Z
2t.^ob~�UEg4�H:��&����@o	��P�[^}f�厀�X�}��Y�Ȋ����r'+�l���B�#j�y�A���m��D���ǪR
�E���p��S�� ��J�>�ǅ���+��:��	s�M�v򜼢0NOn�D�
�O���������W�$�S�����<��������U�mZ�g�$����rk���C_�y��-��y{�YD�Q��D�K��
��%r����g�R��B��{�N�ME=o+�.W��/�ک9g����Աot`\Jx��\K����HjL�f1^r�+�*��d#���7��ұZ%-p�5��W����qbl�O�E2�}d+fh��^8\ܡ�mrIǏ��Mk�Z?)��[ ~�Q��2"����eg&/Լ<�]wl�gb�|�ò�G�/[��*ጾk6�>�x)HE���&b�b������\5���'���>m ��IT�4��Ϊ�L��S%x����Z��I���TZ���l}I��;Wj�ON��[�@G� ���,|oWhD��@���+iϘsd�#��41p}�>���� Ek���Lw҃��4�cA�JJ\'PĖLQw�f��7Zq]Zm�<ɟ-�e��lzJ�j4 ��ۍ9�Ʉ�����H�_�c[ܖG�.��nE1u����v�n����g_y�հ��HW�w+)�V���m�A�����j&�����8S����|B�2 �V�;�ŘL���L.��!\ZeoC����RC���OnExg=�2(͏�Y�0H�r &��2��xӿ$\3ʝ�����ˑ���/9f!�K�-�����O�2��D���l�^ևQ�q/�/���u�R	������P�}����NiDڒ���q��g��O?FmH�F5 �nk'^�3u?�( &!��S	�B�L�R�?"T4�=��v�u;f�N��8l�X�ð'9.���V�X[�>��&�"h����o���(E�>-�ι��!HJ���ekB:�λ�k�j�MU6�˒�+UY�޾��*\��)�W���� ��'���v|���e3_���s,P�4x��L����e��Y����K�����LU��î���3+!�Qq�1h}��kǶ�7�[��Ђ�[����̇dj���6�W(�|�l\t���r��rh��J$I�[�����ޒ�l���9;2J|��-�>>��΄�F�g��ߞ�V�Q$�"�4�]oi��/D�O��U镐&�|������&�z�@:��4������^(���k����	��g��0��0*F�桐 q��j7'Y�T4��-9����H�ėv���j~��R���z��t����E���2�Y2��0�(f_ӳo��X���z�7�c��XFK�����2�
�r�KAV�d+�}��/D;�9?�G��Rа|�'�̤-U@,���^L�����W��nǬ�bc�@N�\�Z$��Q�x;a�f�u��y ƾ���� �9���/N����
OK*3Õ�ZiS�A�m𖦟z�F�'��,\��,2�5TEV���,"���s�� �d�д���B[��\4��9�ʴ	j)�f�9��`���{���J�
���d�k�V��Es�+i���>d��v����+��>Yծ���B�%ND{�')y�,mS/{{�_��>�R����u���t��b<2q������]��=@���15��T��*���,e��3�=u��$"5��+�T��(��&��)�	��Ax�槿�-����#u�ފ��������[�P/��;��|�ҡY?���aS
!��M,i��f?߳*��ln37%b V��1Y�}�G���4�P�M��
���r4�5��3� ���#�&�y��4.oh�G�`aQ7p����Z<�O�ŠǏH��Ɗ���0��{���14���.� ӓ|�v�$�J��(�9AH���J������Y�D'�f'�]*Y'�2?��71�{��92��m�dH��a��m<���x
������*"�#�VH,���_�!	�E��H�����d"���q�����6��L��w fyq���w�]J@n��)#U9V���xf�!H5����(�Y�J(��7� �9EK��%����C�C��`�$_��/��8飚��^�O���|�^����e��򭤸��M�iY��|���yО �_i&Xz�K�	�$}h3hQy@,�ǳ�c�^&��Ԑ�^�DP�]�*��l�_�.	�:a~W$��G�P�#�[C�j����q]�)A�4W�mu�bPn��d?�!��l���Z�@f�^vs�d�9�C˅{!	�����SR���=�"܉����t������g��n'���E��˒\�?�,J5�Nk�mo�$`R�����h3��)��x�5��G~�d�zcZ�TlB��U=K��jo����&���!��}+m�RG��?�C�ƆGFޛzֳ���oR�Ћ���R�95�k���~c�a�6���V:W�e���iB��X܉)J�2�܊��;��RV��y��~�G�џB�vR��?�ř�˙g��<�����A�Z�hnYD�Ȋ�6��+�кYn"]�g�ͷn�dB��d%�B~��)���g~�d*<m$0^�k����X���/&<����!,����4 8f1y�����qp�v���!�!U;�,�3����/;X:{y����$�;[���Q���_�C��S�&�y�Q���S�A:R�6�d���f۠lD��@r��M�B�<�Һӣ('�Z6��y|o>Lk�h )\)�1<�eV�>鐟 м���Y[��vc��PS�Zp�l�"�,��Γ�i�Jv��I����:�����MUxذ�iDw���>�g�����������tl�Ӥ����I��{]��)��Y�<]F�G��fz��ʸG�a�]ֺqr�<���<d~T�4u���>�(�Tܑ~���#į2>�  $q�&F  �%�� ���g�    YZ