#!/bin/bash

echo "----------------------------------------------------------------------------------"
echo "Comprobando los inventarios prestados  en Origen y Destino"
echo "----------------------------------------------------------------------------------"

cd origen/exist
/opt/cisis/mx exist "pft=if v985='PRES' then v977/fi" now -all | sort > origen.txt
cd ../../destino/exist
/opt/cisis/mx exist "pft=if v985='PRES' then v977/fi" now -all | sort > destino.txt
cd /var/www/circulacion/campi-dcic/omp/actualiza
diff /var/www/circulacion/campi-dcic/omp/actualiza/origen/exist/origen.txt /var/www/circulacion/campi-dcic/omp/actualiza/destino/exist/destino.txt > diferencias.txt
diferencias=$(wc -l < diferencias.txt)
if [[ $diferencias -eq 0 ]];
then
    echo -e "\e[32m" "Prestamos - Ok \e[0m"
    rm diferencias.txt
else
    echo -e "\e[31m" "Los prestamos en origen y destino no son iguales. Chequee el archivo diferencias.txt\e[0m"
    cat diferencias.txt
fi 

echo "----------------------------------------------------------------------------------"
echo "Comprobando la cantidad de ocurrencias del campo 985 "
echo "----------------------------------------------------------------------------------"

/opt/cisis/mx origen/exist/exist "pft=if nocc(v985) > 1 then mfn/ fi" now -all > nocc985-origen.txt
noccorigen=$(wc -l < nocc985-origen.txt)
if [[ $noccorigen -eq 0 ]];
then
    echo -e "\e[32m""Ocurrencia del campo 985 en Origen - Ok\e[0m"
    rm nocc985-origen.txt
else
    echo -e "\e[31m" "Multiples ocurrencias del campo 985 en la base origen. Chequee el archivo nocc985-origen.txt\e[0m"
    cat nocc985-origen.txt
fi

/opt/cisis/mx destino/exist/exist "pft=if nocc(v985) > 1 then mfn/ fi" now -all > nocc985-destino.txt
noccdestino=$(wc -l < nocc985-destino.txt)
if [[ $noccdestino -eq 0 ]];
then
    echo -e "\e[32m""Ocurrencia del campo 985 en Destino - Ok\e[0m"
    rm nocc985-destino.txt
else
    echo -e "\e[31m" "Multiples ocurrencias del campo 985 en la base destino. Chequee el archivo nocc985-destino.txt\e[0m"
    cat nocc985-destino.txt
fi
echo "----------------------------------------------------------------------------------"
echo -e "\e[93m" "Control finalizado. Hasta Luego\e[0m"
echo "----------------------------------------------------------------------------------"