#!/bin/bash
#
#rem Anexar eunm y ucod-marc contemplando la modificación adecuada del NC.
#rem Debe contarse tambien con la base EXIST -es una actualizacón-

export CIPAR=files/actualizacion.par

echo "1) Creando la base bibliografica."
./crear_bb.bat


echo "2) Creando PARTES."
./crear_partes.bat


echo "3) Actualizando EXIST"
./actualizar_exist.bat

/opt/cisis/mx marc "fst=@marc.fst" fullinv=destino/marc/marc uctab=files/ucans.tab actab=files/acans.tab
#:fin

