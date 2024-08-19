#!/bin/bash
#
echo "	1. Crear una base con ejemplar y estado"
# Crear una base con ejemplar y estado
/opt/cisis/mx marc pft=@def_ejemplares.pft now -all > temp/ej_estados/ej_estados.txt
/opt/cisis/mx seq=ej_estados.txt create=ej_estados now -all
/opt/cisis/mx ej_estados "proc='d1a1~',v1^i,'~a2~',v1^s,'~'" copy=ej_estados now -all
/opt/cisis/mx ej_estados fst=@inv.fst fullinv=temp/ej_estados/ej_estados

echo "	2. Crear una base con las partes incluyendo el estado"
# Crear una base con las partes incluyendo el estado
cisis60/mx marc fst=@nc_p_i.fst fullinv=destino/marc/marc
cisis60/ifkeys marc > temp/nc_p_i/nc_p_i.txt
/opt/cisis/mx seq=nc_p_i.txt create=nc_p_i now -all

echo "	3. nc_p_i es utilizado para crear partes y para actualizar exist"
# nc_p_i es utilizado para crear partes y para actualizar exist
/opt/cisis/mx nc_p_i "proc='d4a4~',v4,ref(['ej_estados']l(['ej_estados']v4),'^s',v2),'~'" copy=nc_p_i now -all
# borro los registros que no tienen definido el tipo de objeto.
/opt/cisis/mx nc_p_i "proc=if v4^s='' then 'd.' fi" copy=nc_p_i now -all
cisis60/mx nc_p_i fst=@nc_p.fst fullinv=temp/nc_p_i/nc_p_i
cisis60/ifkeys nc_p_i > destino/partes/partes.txt
/opt/cisis/mx seq=partes.txt create=partes now -all
cisis60/mx partes "join=nc_p_i,4=v2,'|',v3" "proc='d1d32001'" copy=partes now -all
/opt/cisis/mx partes "proc='d*a1~','^bmarc','^c',v2,'^p',v3,'~',('a2~',v4^*,'~'/)" copy=partes now -all 

/opt/cisis/mx partes fst=@ fullinv=destino/partes/partes