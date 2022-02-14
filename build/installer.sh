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
�7zXZ  �ִF !   t/����] 1J��7:@C����{�X�bn5���Iv��Эѫ�[O	��	��ƲaF���r��$�0��Jf���7Zh�
�0s5A��;0e���a�p��ZT��˗�����U:ӓmSBMQ��zr+����&S"��g]�,ImA��K��
�z� j�A��"( �|�3P��6����W��+삇����1>WX�ْ3��8���;d����Z?t梄�G֮����8Sν|<n]!�g���G��(�ť��c�Ff��vN�[~�.5����{J}��;=�CX�ߞ���-�W2.$'�6T�|��^��ob8zO}r�"W��-dt�4o�?�Q��(����/�R��5�AX1���u2c-� ���I�Z��%e�����k1B=j۪B8�'���h|���AQ�K�V��gO�5��7�\�F8ک`\J و�Gʢ��VCZو�ēN˰�k�R�:nK`k��H�>��7���&88���%�U|�d�~f�.�T>x�"Ne�>�3�>T<@�Ǫ���N��9>]�|�2�,����v��SH�#�����M����%����<j���Ej"���H
ͦ���g�H9�u�t9�2�hJ��u!+�qK��qg�L��U{�����սK��0���%���_�9I���ϧ�A�B�宩��g��&ђ�V��� C�M�SZ�?�:{W.�>t�Vt����[c (ޑ��d���L�	�;��7���K��"��A��Nf��ֈ�]�a瑍2q�e�n�6�tͿݝ?�������y)�2%�,VT�Xb��B�|;rj��$Vc��_X�1Scה��������ͅ����l7!����W�1������Dg�����gb�N}�y��W�r�:ڣ����hx&T�j�=�O��C2%�>�y�B�e���C&|1%�L��u����1;u�J���slhE���=E��� *�]2��莮a9<o#���g�S�DI#f O�";D� "uNQOd5�G�GM1'i���P�b���"�*P��s���MZ{7��d�DApe}��[������`�#54l��7[=	�U�b���Ð5�#�rN�˴�?��K�A���iU���ބ�T�r���G�ٌ��tl��|vÉR>:�c��'T�j�yu��Ʃ{�W;/	#:��z�E���߀�}W��u�L�z��8	a��~�Ha�N���,�13����[t����)c��
�3�5FC��VC��|��#�����C����M���O���}1(���A��/a�w�.��&�*Ķ��۔d,vR�K��a������X�V`PG��
9��v{$R��q�b=�5��]#:�Y���	J�JG̈D��dYynUzL�Gp<���B_�İ&�%��E���!��h۫�M'��M����(����D���)m�9�&�@`1��|E�E	��-(&R�'�Zq�8�S��	���6R=����ZA������ p��X>��s�I5��҂F����si73' �S�Z��TW�e|�!�A�=��y�k���uK�����A�?��}y*)0��L2^�{��2o/Բ(Q*�����*�42��k8��a'p�xeU����"ꦒz�]���"�	�������(4xq��������w�=YT7�u�_Ӟ�Y�)�Nb`�z�^���2����h��,�ߡMO=�5�aB�>hE���̑p^�w�)�wkn�fu��vOȧK�V����,�.]U|���/��R�~M���QM��ķT�)�I[�������#*������ _��8<'0�����c+�V]��$�p�,�y�St%��`�����r�6D,L)D�5�"=U�x�Z1Y��#�23wWa�ws�TBK4��i����G�S���� Jq��#I��.�R+�Iy`� ���o�8p#Z���v�qC\��JZi���Ė�??%ǿiN�|��k%E0��48�t��D�W]pT�"�q�cxj�q�%g��
��&�(�H�X�`N�8!`Z:oM��g���}���`�{8is�Ďl熷�x!�_㣒 �ޑ�W|u��sЎl��& _��5߯G�U�z�yB���T��/�iAJ3�����"�-��KA=Z�W�r��)��	���эc�i�<��T����-�����J��:T	�o�g%��M��"K��Rb���v�Ŧ�����mB���|������x��)'8�X9��]fT3D�Lx�:��8G�]�c�	�T�y
��n���
DA0�]�j����G��=J@�e
�z��<�/V���(m�5�~\�����
���V&� D�T�q6�Dt�\�q�q��������k����^�5�@��L{��\��@g*����]�� 3p���#�BeШ���v[�9�"�#�� ԰Aה��$��M�9�@~��S���^MeF�-7�����q�6<�I��Ю�6?�]vﾠ����
=�Tx��W;Gęr�P��1�D�ޥHZ�?J���pj���s�-�o{gNT��%G�&X��K��ZD,��Z�բ���zO�O�u�h�h�%��&�\-����2��t٠�jz�r��iw��[ёb��ly_�5��3ڂ�g��A��o��-����������{�yB�{����&�w����<���4yH��:��S�On �:�����@���i�׷\���R3C<�MkbA�'�\�F��Ѷ�c��Y��r4�X���#��
��֥�Av�9�������j;c}d��@�����1:��|H�|N�d	���i��+&�r����{��"��ɉEc ����ݕ�>��`#q{�U\ܾQ#���*�Wo����EF�����	� U�}�����v����_Kp3��O�Բ^�s+�˖�R���n�X]G�"@M�O�����HW�t��0H.ʱܾp���U�4���%{b�$�?>���()PF��0�Y�ņ
�h��u��i>������sF�]l�e�#���b�T�-��T]H�Cmt�!�%N���l�Ʉhq0p�Xj!u�f�TT~��9'��',�U��(R�r����#�Nݕā����5���O|D��bW�����b��H�^YN37�z0�;��ppd��q��X�*�*~��v�h &{F��Fx⠖�	iu��wuY���N���~��P�L��4\Ha[!�����"�7�ޡ�̻6'&a��I�X��K�\K�:lMM��Q��H����"���w2���aP����>�� ��j���&�����Ϥ͏(���J��*�a�}Y�JG�K�˚�g��u2��2%/
嫴C7����=,65��J��N9�Tڷ�{`P�;O�����	s���
�n1��&Ep���?"�1�[5&O\�LD��N�����z#>��O�z���e���s�̪�2�k��Ip���ਾ^��.����V뵀[$�@�8� �����2����H�sW�Y���7�sa��O�. !�C���x8d*���f1�����ey��	�rr>��Ɖ�����n��B�w0B���"H	xF��{��ŏOhn[9a]�^�0:�v����F�~�`���Y�T�f8Lc�r�$� �d�@@��f��wг˽!�{lF�܂�b�l�F]�e��ڰe�ɕęx4�	O;}�T�,vG|?�����H��sU�(@�%�g="�މ[!$��߉��"�)>�U�ݰ����L�@�L����n��+lWJ�Z4a��?.�,��*�>�yg��."&D��1k�j=M�����L�'���T�d��+���F�d�o`��-&�Ӷ�#��x�u�k�3_�M^�k���ٯ�3l�%� ��(�1��P����{���ǃb`�,:�K�������&��S��m3� ���T�.���x*B�LA�4�h�m��f�z�&�]�Zir������lV�L�޾~�HvH=����� :�P��oq�K`�HN�����n0%K|��%T}h�-���Äd��õT+�`	W_�8�:m��=d�s�[���mF�S���2�R)3P�r.ͦtBDv���Ks#��i�^�H*՜���E��:a����(ϯ��U��L��O�DnL�Me�������WB�;:"!���aN���J�9O��I(J��(E���I�>���g������o8�A��4�Z�(��!^�g�3�@�Bfh��Z�������j�v`�ڌ%W�AY18��X�V�;7��Z)D��@�;�(��	rLd�;�I�M������k�����?��`�-�G���`�e��}��^�7�^aU�����`�6"^��}�2��N�v(�0OѼvչ����#/�Pt�T�[�t�}}��2���([@��#���Yuh��2H�P/H6"�A�9XQ
t�G�M7����B4��$���a�x?J�k��I��r��6/E°uȏ�<�!��Fx����֩���sJ�T���]:���^VyܹM(W����<�8��l��vʢ�fS�[��j*hb�z�'�ሟP�V�͕4��Vr ���]/3l��9��}���-~���}���\��y�
�[`Y.�Zp}yZ���a����i�+��_��Y�,��)�T�"$缌�������`�t.��4 !�P-�����6 D�_T�a��>OI8-'����Q�:    %E"�� t �%�� �F���g�    YZ