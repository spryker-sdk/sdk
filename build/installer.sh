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
.}|�j��*Pں�ɘ�k�*L`���5z��Hr�|3ʟ=�UcM�1"Ae*g[��*��?�"��D���q"^$g��?ň��贮���2�j}�~ "푾��9�# �,�#��P��v��{�����W�n����R�}�c��܆EvTk� �8#�B� U�i���Nb�%)�W��*!��q������̅�R�>O�u
>/Y^��X!4U�s ���효����9f��i ��;�.�!�=����维��air`�F3��^�2\���G�lԍ14���̍`+�ȯh��g��*�%ޯ�j�t��0���w��"ľ1H�p��27Z�.�vm���5��ճ׍f+3s��ע��]v~I���mCt��4����ב(�Ev���չc(P�����y ���̓�_0#�ܫ*�i���`����}��XF���R���٤j��OpDj}����b����M�$`����r,�#�܅F�ˠ�^�CS�)bl��D�ߊN�e���M�ڼ5��^Ո��s~�(��C�4�$Կb��{�}����þ�7Ůs��M�Guħ 7�g�"���r�S�,_`-��~?�W@��y��etT�4�����爎҄@��d��7Q�5}�����#e$fD�.�Ⱥ����:G�F!�g�!"۹)A�f��1F&�����dn���l�b��Ř/��W%�Xm|iA�|e�?�J�	�j��q1C�|㉙,[s����؊��C'�Vȳ;�j|�|8@��c��h��� �T��v�T�q��cY�ޛ���fo�{-/2����A���9o���2�1��J�5�x���a�gc���d�	�N�ħ���v�Ѷ8�����u��x{�P���_�S�߮�����Q"���p��z��k�N�y�1y��Pﰴ�''A���2ўM�o�~]����hWt^6���ާg0��vn�D���g�GӳW�qɞ����wً����^1�� $����A�Ɗ'�I��ר)�S�3���௢=4ݑ�D�ƽn��X�M-�yQ���'����e��8.+Z�T�S�J=���5PG�����{��cC/��2e0e�\��'�ߖ��}|2�z���nI'M��c2�|skvvę�(��w���ve�j""˧��
�"�>k���@�1��}�.qB2�����\���q���z?�f��.U��M ���K��dU�-�I��~�2�mM#�_>S�ڭ�ӖV+08�Q�.(�y��]�S�M��9���cI���ǭWΑ<��r�� О@<z���9%σ��e�pu
I��_e�ӎ�iQE!�,*� q����O~&AB�XNeyH��"��G}������Nx�̾^m�d,�^�5�h&��Sޒ���1�MJ�u4�(:S?�@j�A�d6�?zfavq�u��NKN�R;���:�m�`����"[="�35�� 	0�C����C�9��s��v�GC%4�T6ъ'�a,�%d��AP����xZ��´�b���̓njPnK{�h�e܊����Lrq�{�D�~�#�1i���]�0��nb�ٖ���9.C��
}͟��"ʔ�;Ǻ�`�m>��lhRE�C+W�.��:�]�y�G���.�u������1)��5�;l�㥳�(]zy�s?��I�_�X�A�c�����"Q����'�uEԂb�O`��<	��ך���H����'y��ګ�>��ڄ4����ƆR8����ȯ���G�ݫ��c�j1�]����
y�ҍ�G3�qp�����&������$0E��0���&ǪJ7.���S�d�7����yo�	���ߴ��̬���U:��Ϙ��'��HR��"@\k��2K��FwѺ h,w�gY�|�/yೕ xs����/��ISu���/G�o�D��[�S�2*�},,��`1v2�^	��֟h��I���,LA!���XϚ&!/��dU�I�����?P�un{af�=)&������69���`�[Os�с�����t�jK*��{hN����)r�&X`Ti��Z]x��6ha;ك9����#��Ƣ�0_�sI�����n�C�^q'�z�U�0�� ȗS�*����p�Vf�6���{t�F�Y��_�����.��x�	*M���͞���&oQ2�(����-AќE��vݰu6X�ҩ$�j�&�4�v�Dp=q[�+��hY���w���L����\�H�Qk�#���M��uu�s�r{�������A���Ĳ���;��Tk���i����X�@�&Ku5$��$���@\ͪV�M�ī���7{�j��bU� �)� ����Z`]X��m
1�\}�R�1�V��IV-Û䨊�v}�ͯ�铞���0�C���|9ā*����B�g��K~o*Ԓ���I�σoAM�^�ZD���j�[���lA�o?$��Hv��S��} 4牞g����S�</Q�����y3�Z��6Z슠�d���a�yu�V'�߆g]�^��9	�:@�m�( �6�ؖ���Tқ'l?n�{�1��~����߂��mgB���yd�$kw�7���Ah�.��)�/�8��(E
��x�z�:���%,����p^W�Jw��Q��=�n{���9H����D��J��I�v4�ڠEZ�O��ֳ���f��GW�m�k/�Wκ����:�(����=0U�Y���@g�%�;h7{�
��7��+�p�(��x����J��1�L� �P���%.��Haձ'K\��a��ۆ}g��xRyؿ�h��F��]N%?Ѯ vJ,�Ʌn��	i���q��\c�M�:�v'�h�_�3��J]�G���T2�jAx���O�U�i�1c���hܺ��
���8A�d�̰�S�}z�!�]�0��sx��V��j����|9� �.�ΰM#�yT�:��^*뛅�/gT�Ϯx�0�?�|]����"����",o����JS���>�G�eC^�bj(q3�����-�R����0��!yIМ9�rA��G9������H�W����;@�Ă/��T{X�ч���̄d���z��w���$�O�I},��7�}���	J;Z�k,��'r�f=�����i - о�Q��;���$�:�̂�bp��o��J��8vy���j�m�ʽ�K��z=�4Y<ý�(�����0��'���-�r�K�NW�c7���F����`**��K�󷸺.u�M*1��-҆{�u�V�X
��BqA����мi#�R���i�4'�]78\Z��`=���L]�y�Oa�&*"�����"��W�7f{!�GB���CFn�Nո�b��&ba���!�ا��w�VM�4�{�/�7���g}���Yi��χci�#��IL_�{]JOB�(��M���<����q��f��c�F��x/{s�R������a%z����<�+���J�b�⻑h:���	�T>B�H<�����f��p&d�97��"��F�2~��W�;�&���civ����WY�r��-Z����C��+�mQQdl��SpS7RQ^�[�!բ
y�}c���� ����9AԥDP�����k���'��/���s�_�i��"�8gǷA7�"�ha��i����4��fz�W�0���ݘ蟢�	1������lO�QjL��2d�ic9hw6E�g�Ut����<Zo&���wR�'~�i��	ʊ�78�� n1�u�NJ�7U|���t-�)���&�/<Ss"#Qh����ڞ=��$�O��K{���H���D(����g�J[�8|z�ؔ�)ǒ wY�V�Q
�����k��K4��\Ǽ|�r,��-�^	�*�dM[Q}��`���1J|xBJ�[�)�:m_8��_����vډ����Dw9&��q:ҟtg2�������2�S~��_z�f[�b�o�%���#�!͸Ȧ2"��<�;����no�]vv#E]�5�XW��߽}�d�n�Z}��5L�$RȕEn�u�a��{/a��C��P�;ջ=���ޛh��wIe1կ�?�:chRʮ�eh�F~w,�∫�?��a��S�U�<����W�!;~@�}+*��t6����� 3��VJ�8�w8�G�xS���z���eC?��¦*?�n`D��o�w=5���;�Y���zn!����\t"$gt����BO��V*��3X�xLh�ȋ��pV(B�w�Yqc	j���Hc�DP���(���i�r����|tI����1�R��$��$��֞஺;t���{�=m��,:a��u��(b, T�(8�N&9�"#�YJ����ah�7|�0�Ί8)�c���ճC_߾�s�2�Hq��~�3��Ym#]����U�'���LSuAG�w�m����%���c��l����Ё���v�'�����Y��6n�iY�|�:��2+f1���'��Px�*+��YY1��K>V/�*~�L:����h,�!�9PR<�j�qa6&=&�.P����>�L��Aױ��Xt``BC׶�চ56:��:@&�B¤��G5��G��-}k�ba�)	,�~:�c�#�c�*U֮H���Ҩ!�?�F=~�o�Tʦי�0��/A�_�8(%�t�9 �6{�j�M����g���e
k���R^�A�c��:�ϓQ�J������ �L���� �&�� ��PD��g�    YZ