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
�7zXZ  �ִF !   t/��'�] 1J��7:Q�!:���e�Z��7�3i4��:�uUa'��!LH���*��p�`�30PD_���DՎ�t��ɻVrtEo�\LJ�]�Qy>�\��EFd͚�zA�y"���(��P�L\	������"�t���8�hǽ[��pO��y�MA/.A�������%6_h�&w4>Y�G�u,��R�RB��c�I�^��eSu�B,Hp��U���@�w��\�eY��s~�,Z�?1�suj)���h̑=P���C���!5�8��K9�5�4�q�gH̲��D����Z���oM	H��J�F�����T�M�*��Ö�K��y��-��P�-��|�t��A�ǘ����U���Hb���k6��٬���=�]rX�p%�h۞� tA�y���N�ti�X�Y�٣"���K��"�x��t�A�\����6�}���4��88�,�
������$KZ~��h�"$��eb���҉N �W�	 9��T�i �.u)�r���p�l����f��u�j�����Fq��Eb�l�|R��U���jjKZ$g�z[S�iؾ� <_�b�K�P�v��7�(�<��.9:eP�S�.��/]�i����]�f�pUP�Uq��a#�>2��5(���?:v���ϳZ{��^�s�k�T���8X2!���� ����x���s����v3���V���7h���7���Թ`@��۫��O!_�Õ1哤�����nM����'g�X��9�Rp��}&[�hKX�"�9�ܫ�'X��\"�Ϡ,T�/�C�rC�g�����8��S��wO�kJ͚��vޅ������BL<�������c\tB?��c��Q�8��8���{�"V�dzGV#�6EL'a{;E���On�|��9�S���� �9!��n�6��Dڢٻ�tH��y�^U�qU�����FM�(����ij,	�l%-`��ݭ�~���&W��H�(Ft�Ĕ�L��N�ZEq�'���*�)�H�Z~�m���9�]����.�	������RwI1]���X�P���g�R��0P�i� ��.m����w.q�
Qy�ζ��r�=��r�[�8�M ����E��]�J3y������|����d[����4�ؿ�M����y&���p���#��u*a����$$b������&v�h+�"��Go�3�1r]t��׆7Ucaz�}w"M	���\I�{s]�K�H��r���l���,�����Nh�4J��뾦�vOq�k�tF
�C��I��Ĳ�����?��T��u�Ml>:N��������G�X|�׬QW7����h��bFؿ�V$�t"Ic����J��ov�Ͻ�T�Ar|KaS�_d[U�Y"�����Ad`�\\�z�ƽ�%�}��L��Q.}�K�{-���=���2áy���A����pkБ�K�^4G�;왬� ��&X����}�Ӡ�M0�r:eN^S���&��1$�赒�_�]��3?�s�l�¦������⚸�2:�A?�٠��zc<���ڮHyb��5Dd��^JS���V龿���������
�@�{½���7�ٰ\�A�RP�@='rh#����%t�/, *>K�`�����m���Ú�U���}P6Y;K%�6zezYgc+�S��-�M%��l��T�X�Q����.�y� ��t�ݨ�q�*Y�-V�tq�m�-j����>� �\(��횜�RHh�e3�i�!J`8�d�4 [T9%��Bm��b�{�HI�5FϭB�bXG    ���'%��� ��P  ���ޱ�g�    YZ