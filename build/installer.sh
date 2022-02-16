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
.}|�j��TQ��;��c����lUA�B�Wwŋ�מW��d@�A*h�^N�\�F�]�n/%
d�����3�i��r���S��A)C�v��6�fE-����斨��p�}��D�b���בu��L����dx����l�<�:V�u�)`z�7�����S�:�hr���Y$-�J����S"�#���9�<ͫ�r����)��r�5�Ն�N�`<�3�Ł
�@m�7�8!�<�5{#���ƅ���ǘ&���W�L�������]��e�\CB�'���A�T�M���'���Q�nN��)`4�Q��x�x*$�������J�nL�����'�X�L5
ƨs\�j�2�wy�E�
9�Ē��y��H5�O���Ywn�ͧCx(H{�3�"q0� �n�}�0j<V�m�o�:��������	��X�<V�Fe�)�h�1zY����h0ӲP���TU;B�b��U�Y���kX��z�)NEvyг%[�`�"�!H��q[W�c޼(���2�.bnAܽ�d��&G���s�Lϯ65l�`f���j���<a��vR2Qﳩ_}�<`�����������r�b�f�{4��o0��|*����Erب�JPy��}p:��⻔���UG߹·�t���&�V3(\�v�����\�??��� �Ĺ&a��I(GਿCv�
�֜
�^�]�H��R�A��9�NhT]>�RQ��w�V�O�Ԃj�s���W��9���CF�|�`���)�	�y4�|M���J��v&	7`zj�g0e��ohV�����>�
[�F���!֡�^��{��go����uinq�"�����pIX9�sA��*ܷ�f��P�K�*.��Bԛ6����u�	�;��_����Q��^ԡX�QJ	~��
�#(F�<��4��Tg�=~�[��̄.�\���u�׋�BZ����]�b�����g��t���=��A͹?��?������Ex%L�c��̟�����R�d}���]/�g�����X�����N��YIe{�:%r�����i*}uV�]M��[�t�<�K�ܧi�gö<���9�`Z�U[�7�R4�2
M{��Fj��D�f��Ne�_�Ȗ��U/;�i���z�*#Y�)�"�t��ڗ�]oɀ�c5���������,�*�Ї*m>�䐣�P~e1T�?���^�L�Q�Rt�P`g[8�����k���κE���ҙ�av���V�P�e|p^r�w^C*�-�C)����{2Ŝ�x7��G�g�7�|Ǿ	B`�H�����$\��?�5=ȶ�b�{�O>%��F�B�uf?fMH���������%�(� ��$R���S�=ì��n���"����\[!����THx��C��L�/fv�O4+GN��b��#r!�yNæ:��Ț��*������R��$��8��Iq��J�5���^>�	]|�WHUU׿��I��ٍ $b=�O r���mT��n���	ߙ�ng�?4�X�n�5���wcOM���0����t�͍U�Q�X7+�:�W��^{O:{��W]�w�_��r灛JڰN>��08%@Þ-�Z7���H�ᛑr�?�^S{(/6M7�sAL�Ѯ�|@o*ּf�g���ך�����,��XV�!�P�&KR�c3{6��@Y纉�&�A=efgk���'���T�Br��$;���+�+>�L�Y��.�%U��ט3�5	�E��I���)��ɋ�S�5A5 �5�v��<�`G"�}@3��pw
#�=���#�z�[�p���P�4�h)G��79���K@9��l];�	�} �����;����kh���# n{����I�F�J�L��y�/c$��J#:t9��m��؆?V�`@U7��O��37`;���x	�v��V�r��3d����!��#���n���qJ��mң埒��@�u�v˓0UrW�MI��iD�y�@�M/�D	�Q�e�LkO�ª7̲�(ZA���1B�O�[��k4آ�G�V��'te��VZ�X�:	sr�����p�!1�(o�%�������w�:�f���^��^�v �P��Y����e_Y�x��?ͽ�ڼ�ھ��
�8�g��0���1w��L���f���c�U��[g�R�L���6F�z toO��I���cv�0����а��-^��v��ճ�1Z��dq	�!L��h���<�L8P��~0�i���Q��/�0U�ƈ���n0R���{X��"�XԻ��Ad�"�V�1?-�%ե���-��T;��4A%��Mʠ�4���wC�!��_����a�g9��?#�V $���ZsVĬ���/uc�|��7�i�I�G��!�4�Mo�N�� .�B��ѣ��϶T���_O,���x�Pe�+rN����e�%�����*x%4�X�ڥ����[s{�v^�\p�#`w=�3����P�H6�@��d���k��V �	#+Z���<q���k���6�̶xs���X� �3����2Ny-�{�~�	ƫ!HC5�"l��JOx�f��[�	���|,}�!�+���� L�D���A� =��Ɩ��@�3�{� �3̈���k���"�w[�?�l���"��̊��M��?=C�W�&|��2mh��h��L�ͽ�2��ܤ5�6�� ګ��y��!u���~� ���M����4'�"c\�5-r�i�h�u�����c-J��RT���5�l�RD.��C��a��]`��� q|���0,M1:˿�4ͨ}\����h�܇dD�]�-�7;�|�����|h ��Y! �B��Լ�|�S
����'rz�\vh }�����'���V�J�Ί''�iVbEcg�n��8D��ч��=�\/E�wS�$�}���Z��5=�h�:6�� ��9�^6P��5��B,?�쥘�9�d��v��W�i*��~Eu��Dn��q}�UK ŞqL���A	��� �Gk����CJ���f,��5G^D��jg�~BD�����޵�Uʒ�1rK�0k&�M��k*U�����e7~t���'� es�y	�bq�%t%N����E��g�I'!s����^��G',����Y��B��:�/�<�uQ~8��'�*�2�iQ��z/&�ष�E��$·`7�8\�ҕU�� iF����'2k)FM��;,ǳX19>�ޞ���H�3{��b���C�DD"I�|��M0�:��)B���R��˺��MA�&�ä�/�1~�t�Nz��@XO.w�Kk��\�Rf�nd���e�9���~��`6���a��N�������|�23���Z,��i��z��BƱ��E��3	,��]�@U��A"���e��J�����Ӗkz���Oo�+�I1=��,���1�7J���^�����*˞v&�y�b��4�C�o��?f�.jo��C��}KKmx땨�k��K����v��,�a�Į�Zqo��X�"�e�r ���̕%���2Qq^�Q�:��W*�:=�w5�n����J��k#ۑI�N�z?�B�v�[�,�&���|������=��xVԖ�r�%rr?} Ϳ�}��x�:���Զ���� )��m�";���Z;��:x̌D��������QB�м.�أCyd{k�����%Y�bX���]m�����/��6��/ҝ:9��zB���<��o){��Z�\+g��7�����s�8�J7Q':� Au����<l�ڷK�~�� pM�iW���Ƃ�jQ��R+U�?�e�Dx�Kc;���?���
2Q*MkWq��C��,�:Ն���1��U׻k�e�Ժ�y-�%fѸ�Ri&�
�KH�;���"����L�P))Q)D�-.�}C�Gc.���?f���D`�I>�7����ϽC;z��������y���5Ǥ��׈s#$7�N�+y�Ei���3�	�y�di���>G����WbG�v��/*��pS:�˖�o]|���_���1yԬ�p)��a�I����4�u#�ᓘ�߄�Ç���� ,A-��jϚI����$�rY��{Ц��ۋ��",�R��A��t��ݖ����U�6aQ���΍i���G�?A���O2�$ki���h~f��F���V�y��d�Zv��m;^Ϩ& s�:� &
Ғ���QE����l�����w�C	}���.�y��
���'��i��=	����ą)�������׹��"����1�Gŗ��: 
���)e�݇�4G����^��Ƭ����D�������jM�)0�l�	(vz��N6b�'�8���\�>�}�X����l���M��!��,L��;�F5����WD��A3U>'b\˯\o��UY�"G�\���W���X~��D_3:����us�V-��V�����Jj�^��m��<��&R�c�l�� :ZA��~��y�oI"���۳����7�=X�s�fw�i���o�`��.�ft2�P�cT���rQ�nw:�;NQ\G������ڠ�,�� �L:�ʯT��~- tn{5�\P�����<o]����2�$`�9��x�ȕU���ǹs�~S�?�����g�ہi�˔���I0Tp��0ׅ��=笺�����T_�#6�cfA.�淩���(R�    ��UMiGm �%�� ��,��g�    YZ