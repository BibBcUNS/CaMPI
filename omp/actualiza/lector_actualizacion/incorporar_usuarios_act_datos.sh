#!/bin/bash


## ERROR FUNCTION ##
error()
{
        if [ "$1" = "" ]; then
                echo "ERROR: hubo una falla, con causa desconocida." #| mail -s "Error actualizacion lector" -c "gonzalo.faraminan@uns.edu.ar" rapiriz@uns.edu.ar
        else
                echo "ERROR: $1" #| mail -s "Error actualizacion lector" -c "gonzalo.faraminan@uns.edu.ar" rapiriz@uns.edu.ar
        fi
        exit
}

# ------------------------------------------------------------------
# VERIFICAMOS PRESENCIA DEL PARAMETRO OBLIGATORIO
# ------------------------------------------------------------------
if [ "$1" = "" ]; then
        error "Debe indicar el nombre de la base."
fi

fecha=$(date +%d/%m/%Y)
backup_date=$(date +%Y%m%d)
campi_dir="/var/www/circulacion/$1"
backup_dir="/home/admin/backups/$1"


cd $campi_dir/omp/actualiza/lector_actualizacion/



echo "-------------------------------------------------------"
echo "Creacion de los registros nuevos para agregar a lector"
echo "-------------------------------------------------------"

echo "- Preparacion de los archivos necesarios para el proceso"

#Creacion de la base persona con los datos provenientes de la base mysql
cp $campi_dir/htdocs/receive_files/uploads/persona.csv origen/persona.csv
cp origen/persona.csv persona.csv
sed -i 's/|/ /g' persona.csv
sed -i 's/\[/|/g' persona.csv
mx seq=persona.csv create=persona now -all
retag persona retag.tab

#Inhabilitamos el sistema

mx $campi_dir/omp/bases/config/config "proc='d13a13~no~'" copy=$campi_dir/omp/bases/config/config now -all


#Preparacion de la base lector

cp $campi_dir/omp/bases/lector/lector.mst origen/lector.mst
cp $campi_dir/omp/bases/lector/lector.xrf origen/lector.xrf

cp origen/lector.mst lector.mst
cp origen/lector.xrf lector.xrf
mx lector "proc=if p(v1000) then 'd1000a900~',v1000,'~' fi" copy=lector now -all
mx lector "proc=if p(v1011) then 'd1011a911~',v1011,'~' fi" copy=lector now -all
mx lector iso=lector.iso now -all
mx iso=lector.iso create lector now -all
mx lector "proc=if p(v900) then 'd900a1000~',v900,'~' fi" copy=lector now -all
mx lector "proc=if p(v911) then 'd911a1011~',v911,'~' fi" copy=lector now -all
mx lector "fst=1 0 v2/" fullinv=lector now -all


echo "- Join de la base lector con persona"
mx persona join=lector=mhu,v102 create=append now -all
mx append "proc='d32001'" copy=append now -all


# Borramos los registros que coinciden para que queden unicamente los nuevos
mx append "proc=if p(v2) then 'd.d*' fi" copy=append now -all
mx append iso=append.iso now -all
mx iso=append.iso create=append now -all

echo "- Damos formato a la base resultante para dejarla lista para el append"

retag append retag2.tab
mx append "proc=if p(v17) then 'd17a17~^f$fecha^tRegistro creado por: ^d',v17,'~' fi" copy=append now -all

echo "- Append de los nuevos registros a la base lector y generacion de informe"

echo "Cantidad de Registros Inicial" > ./runtime/resultadoappend.txt
mx lector "pft=if p(v2) then mfn/ fi" now -all | wc -l >> ./runtime/resultadoappend.txt
echo -e "\nCantidad de registros a anexar" >> ./runtime/resultadoappend.txt
mx append "pft=if p(v2) then mfn/ fi" now -all | wc -l >> ./runtime/resultadoappend.txt
mx append append=lector now -all
echo -e "\nCantidad de registros a Final" >> ./runtime/resultadoappend.txt
mx lector "pft=if p(v2) then mfn/ fi" now -all | wc -l >> ./runtime/resultadoappend.txt
mx lector create=lectorappend now -all
mx lectorappend "fst=1 0 v2/" fullinv=lectorappend now -all

echo -e "\nRegistros agregados" >> ./runtime/resultadoappend.txt
echo "-------------------------------------------------------------------" >> ./runtime/resultadoappend.txt
mx append "pft=v2,'---',v3,'---',v1/" now -all >> ./runtime/resultadoappend.txt

echo "- Registros agregados!!"

echo "-------------------------------------------------------------------"
echo "Actualizacion de los datos filiatorios de lector"
echo "-------------------------------------------------------------------"

log="./runtime/$(date +%Y%m%d)_actualizacion.txt"

autor="Actualizacion AYE"
echo "- Join de la base lectorappend con persona"
mx persona "fst=1 0 v102/" fullinv=persona now -all
mx lectorappend  join=persona=mhu,v2,v102 create=lectornueva now -all
mx lectornueva "proc='d1000d32001'" copy=lectornueva now -all
mx lectornueva fst=@ fullinv=lectornueva now -all

echo "- Actualizando Apellidos y Nombres"
#mx lectornueva "pft=if p(v101) and v101<>v1 then mfn,' ~ ',v2,' ~ ',v1,' --> ',v101/ fi" now -all lw=5000000 | tee $log
#mx lectornueva "proc=if p(v101) and v101<>v1 then 'd101d1a1~',v101,'~a17~^f$fecha^tApellido y Nombre modificado por:^d$autor~' else 'd101' fi" copy=lectornueva now -all
mx lectornueva "proc=if p(v101) then 'd101' fi" copy=lectornueva now -all

#Por el momento no actualizamo categoria. TODO: chequear que es lo que debemos pisar y que no
echo "- Actualizando Categoria"
#mx lectornueva "pft=if p(v103) and v103<>v3 then mfn,' ~ ',v2,' ~ ',v3,' --> ',v103 /fi" now -all lw=5000000 | tee -a $log
#mx lectornueva "proc=if p(v103) and v103<>v3 then 'd103d3a3~',v103,'~a17~^f$fecha^tCategoria modificada por:^d$autor~' else 'd103' fi" copy=lectornueva now -all
mx lectornueva "proc=if p(v103) then 'd103' fi" copy=lectornueva now -all

echo "- Actualizando Domicilio"
#mx lectornueva "pft=if p(v106) and v106<>v6 then mfn,' ~ ',v2,' ~ ',v6,' --> ',v106 /fi" now -all lw=5000000 | tee  -a $log
#mx lectornueva "proc=if p(v106) and v106<>v6 then 'd106d6a6~',v106,'~a17~^f$fecha^tDomicilio modificado por:^d$autor~' else 'd106' fi" copy=lectornueva now -all
mx lectornueva "proc=if p(v106) then 'd106' fi" copy=lectornueva now -all

echo "- Actualizando Telefono"
#mx lectornueva "pft=if p(v107) and v107<>v7 then mfn,' ~ ',v2,' ~ ',v7,' --> ',v107 /fi" now -all lw=5000000 | tee  -a $log
#mx lectornueva "proc=if p(v107) and v107<>v7 then 'd107d7a7~',v107,'~a17~^f$fecha^tTelefono modificado por:^d$autor~' else 'd107' fi" copy=lectornueva now -all
mx lectornueva "proc=if p(v107) then 'd107' fi" copy=lectornueva now -all

echo "- Actualizando Email"
#mx lectornueva "pft=if p(v112) and v112<>v12 then mfn,' ~ ',v2,' ~ ',v12,' --> ',v112 /fi" now -all lw=5000000 | tee  -a $log
#mx lectornueva "proc=if p(v112) and v112<>v12 then 'd112d12a12~',v112,'~a17~^f$fecha^tEmail modificado por:^d$autor~' else 'd112' fi" copy=lectornueva now -all
mx lectornueva "proc=if p(v112) then 'd112' fi" copy=lectornueva now -all

echo "- Limpiando campos"
mx lectornueva "proc='d102d110d116d117'" copy=lectornueva now -all

echo "- Ordenando los campos"
mx lectornueva "proc='s'" copy=lectornueva now -all

echo "- Generando controles"

echo "- Registros" > ./runtime/controles.txt
mx lectornueva "pft=if p(v2) then mfn/ fi" now -all | wc -l >> ./runtime/controles.txt

echo "- Prestamos" >> ./runtime/controles.txt
mx lector "pft=(if p(v8) then mfn/ fi)" now -all | wc -l >> ./runtime/controles.txt
mx lectornueva "pft=(if p(v8) then mfn/ fi)" now -all | wc -l >> ./runtime/controles.txt
echo "- Sancionados" >> ./runtime/controles.txt
mx lector "pft=(if v10='Sancionado' then mfn/ fi)" now -all | wc -l >> ./runtime/controles.txt
mx lectornueva "pft=(if v10='Sancionado' then mfn/ fi)" now -all | wc -l >> ./runtime/controles.txt
echo "- Sanciones" >> ./runtime/controles.txt
mx lector "pft=(if p(v11) then mfn/ fi)" now -all  | wc -l >> ./runtime/controles.txt
mx lectornueva "pft=(if p(v11) then mfn/ fi)" now -all | wc -l >> ./runtime/controles.txt

mv lectornueva.mst lector.mst
mv lectornueva.xrf lector.xrf

echo "- Realizando backup de la base lector"

# ------------------------------- Copia de respaldo de bases a sobreescribir (exist,partes,marc)
{
mkdir -p $backup_dir/actualizacion/backup/lector && \
cp -r $campi_dir/omp/bases/lector/* $backup_dir/actualizacion/backup/lector
} || {
echo "Error: no se pudo crear el backup."
exit 1
}

# ------------------------------- Copia al sistema de circulación de bases actualizadas

cp -r lector.{mst,xrf} $campi_dir/omp/bases/lector/ && cd $campi_dir/omp/bases/lector/ && ./fullinv-lector.sh  && chown -R apache:apache *
if [ "$?" -eq 0 ]
then
    echo "- Copia de archivos actualizados (lector) correcta."
else
    cp $backup_dir/actualizacion/backup/lector/* $campi_dir/omp/bases/lector/
    chown -R apache:apache $campi_dir/omp/bases/lector/
    echo "Error: no se pudieron copiar los archivos actualizados (lector)."
    exit 1
fi

# HAbilitamos el sistema

mx $campi_dir/omp/bases/config/config "proc='d13a13~si~'" copy=$campi_dir/omp/bases/config/config now -all


cd $campi_dir/omp/actualiza/lector_actualizacion/

echo "- Eliminando archivos temporales"
rm persona.csv
rm append.{iso,xrf,mst}
rm lector.{ifp,cnt,l01,l02,n01,n02,iso}
rm persona.{ifp,cnt,l01,l02,n01,n02,mst,xrf}
rm lectornueva.{ifp,cnt,l01,l02,n01,n02}
rm lectorappend.{ifp,cnt,l01,l02,n01,n02,mst,xrf}

echo "- Actualizacion finalizada"
