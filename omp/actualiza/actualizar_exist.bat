#!/bin/bash
#
#rem nc_p_i es creado por el proceso de creación de partes.
#rem Ejemplo de un registro de nc_p_i:
#rem			mfn=    80
#rem			 1  «     1»
#rem 			2  «EUNM000070»
#rem 			3  «V.1»
#rem			4  «114162/003^sPERM»

/opt/cisis/mx exist "fst=977 0 v977" fullinv=origen/exist/exist

/opt/cisis/mx nc_p_i "proc='d1d2d3d4a977~',v4^*,'~a987~',v4^s,'~'" create=exist_nueva now -all

/opt/cisis/mx exist_nueva join=exist,985,986,998=v977 "proc='d32001'" copy=exist_nueva now -all

#rem Agrego el estado PERM (de no prestado) a los ejemplares nuevos.
#rem Detecto estos por no tener definido el campo v985

/opt/cisis/mx exist_nueva "proc=if a(v985) then 'a985~PERM~' fi" copy=exist_nueva now -all

/opt/cisis/mx exist_nueva fst=@ fullinv=destino/exist/exist