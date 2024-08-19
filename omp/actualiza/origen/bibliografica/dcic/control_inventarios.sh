#!/bin/bash

#
# SCRIPT PARA EL CONTROL DE INVENTARIOS
#


# 1. VERIFICO INVENTARIOS DUPLICADOS

# 1.a. Genero un invertido por inventario y lo exporto a texto
mx biblio "fst=1 0 (v859^p/)" fullinv=inv_dup
ifkeys inv_dup > control_invTmp.log

# 1.b. SALIDA LOG
echo "INVENTARIOS DUPLICADOS:" > control_inventarios.log
echo "Ocurrencias / Inventario" >> control_inventarios.log

# Si existen inventarios duplicados, los exporto con la cantidad de ocurrencias
awk 'BEGIN{FS="|";}{ if ($1>1) print $1,$2}' control_invTmp.log >> control_inventarios.log

# 1.c. SALIDA PANTALLA; genero variables
invDupCat=`cat control_inventarios.log | wc -l`
invDup=`expr $invDupCat - 2`

# 2. VERIFICO INVENTARIOS INVÁLIDOS 

# 2.a. Si v859^p tiene menos de 5 caracteres (necesito al menos 4: por ejemplo 1/995) 
# Descontamos registros con v856 existente, correspondientes a libros electrónicos
mx biblio "pft= (if size(v859^p) < 5 and a(v856) then mfn/ fi)" now -all > control_invTmp.log 
# Si v859^p no tiene "/" lo agrego
mx biblio "pft= (if not v859:'/' then mfn,'~',v859^p/ fi)" now -all >> control_invTmp.log

# 2.b. SALIDA LOG
echo '' >> control_inventarios.log
echo "REGISTROS CON INVENTARIOS NO VÁLIDOS:" >> control_inventarios.log
echo "MFN ~ Inventario" >> control_inventarios.log
cat control_invTmp.log >> control_inventarios.log

# 2.c. SALIDA PANTALLA; variables y salida final
invNoValido=`cat control_invTmp.log | wc -l`

echo ""
echo "La cantidad de inventarios duplicados es" $invDup 
#echo "La cantidad de inventarios inválidos es" $invNoValido
if [[ $invDup -gt 0 ]]; then
     echo -e "    :( \n Revisar control_invnetarios.log!!"
else echo -e "    :) \n Todo salió bien!!"
fi
echo ""
