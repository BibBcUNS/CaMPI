# USO: ./unir.sh <base> <nros_de_control>
# los <nros_de_control> deben estar separados por espacio.

PATH=$PATH:/opt/cisis

# registrando la actividad en el LOG

# se realiza la copia de seguridad.
sh ./bkup.sh $1

date >> backups/$1/log-unir.txt
date >> backups/$1/logerror-unir.txt
echo $0 $* >> backups/$1/log-unir.txt
echo $0 $* >> backups/$1/logerror-unir.txt
echo ---------------------------------------- >> backups/$1/log-unir.txt
echo ---------------------------------------- >> backups/$1/logerror-unir.txt

base=/var/www/campi-catalogacion/catalis/bases/catalis/$1/biblio
base_backup_count=$(cat backups/$1/count.txt)
base_backup=/var/www/campi-catalogacion/catalis/htdocs/herramientas/union_registros/backups/$1/$base_backup_count/biblio

# muevo el campo de un registro al otro y borro el registro origen.
# =ref(['biblio']l(['biblio']'-NC=011300'),v859)
for NC in $2
do

echo >> backups/$1/log-unir.txt
echo >> backups/$1/logerror-unir.txt
echo Obteniendo MFN del registro origen NC:$NC de la base $1 >> backups/$1/log-unir.txt
echo ----------------------------------------------------------------- >> backups/$1/log-unir.txt
echo Obteniendo MFN del registro origen NC:$NC de la base $1 >> backups/$1/logerror-unir.txt
echo ----------------------------------------------------------------- >> backups/$1/logerror-unir.txt
MFN_ORIGEN=$(mx $base btell=0 "-NC=$NC" "pft=f(mfn,1,0)" now -all)
echo MFN=$MFN_ORIGEN >> backups/$1/log-unir.txt
echo MFN=$MFN_ORIGEN >> backups/$1/logerror-unir.txt

echo >> backups/$1/log-unir.txt
echo >> backups/$1/logerror-unir.txt
echo Desbloqueando el registro origen $NC, MFN:$MFN_ORIGEN, de la base $1 >> backups/$1/log-unir.txt
echo ----------------------------------------------------------------- >> backups/$1/log-unir.txt
echo Desbloqueando el registro origen $NC, MFN:$MFN_ORIGEN, de la base $1 >> backups/$1/logerror-unir.txt
echo ----------------------------------------------------------------- >> backups/$1/logerror-unir.txt
retag $base unlock from=$MFN_ORIGEN to=$MFN_ORIGEN >> backups/$1/log-unir.txt 2>>backups/$1/logerror-unir.txt

echo >> backups/$1/log-unir.txt
echo >> backups/$1/logerror-unir.txt
echo Obteniendo MFN del registro destino NC:$NC de la base $1 >> backups/$1/log-unir.txt
echo ----------------------------------------------------------------- >> backups/$1/log-unir.txt
echo Obteniendo MFN del registro destino NC:$NC de la base $1 >> backups/$1/logerror-unir.txt
echo ----------------------------------------------------------------- >> backups/$1/logerror-unir.txt
MFN_DESTINO=$(mx $base btell=0 "-NC=$3" "pft=f(mfn,1,0)" now -all)
echo MFN=$MFN_DESTINO >> backups/$1/log-unir.txt
echo MFN=$MFN_DESTINO >> backups/$1/logerror-unir.txt 

echo >> backups/$1/log-unir.txt
echo >> backups/$1/logerror-unir.txt
echo Desbloqueando el registro destino $3, MFN:$MFN_DESTINO, de la base $1 >> backups/$1/log-unir.txt
echo ----------------------------------------------------------------- >> backups/$1/log-unir.txt
echo Desbloqueando el registro destino $3, MFN:$MFN_DESTINO, de la base $1 >> backups/$1/logerror-unir.txt
echo ----------------------------------------------------------------- >> backups/$1/logerror-unir.txt
retag $base unlock from=$MFN_DESTINO to=$MFN_DESTINO >> backups/$1/log-unir.txt 2>>backups/$1/logerror.unir.txt

echo >> backups/$1/log-unir.txt
echo >> backups/$1/logerror-unir.txt
echo Agregando al registro $3 el campo v859 del registro $NC >> backups/$1/log-unir.txt
echo ----------------------------------------------------------------- >> backups/$1/log-unir.txt
echo Agregando al registro $3 el campo v859 del registro $NC >> backups/$1/logerror-unir.txt
echo ----------------------------------------------------------------- >> backups/$1/logerror-unir.txt
mx $base from=$MFN_DESTINO to=$MFN_DESTINO "proc=ref(['$base_backup']$MFN_ORIGEN,('a859~',v859,'~'))" copy=$base now >> backups/$1/log-unir.txt 2>> backups/$1/logerror-unir.txt

echo >> backups/$1/log-unir.txt
echo >> backups/$1/logerror-unir.txt
echo Borrando el registro $NC de la base $1 >> backups/$1/log-unir.txt
echo ----------------------------------------------------------------- >> backups/$1/log-unir.txt
echo Borrando el registro $NC de la base $1 >> backups/$1/logerror-unir.txt
echo ----------------------------------------------------------------- >> backups/$1/logerror-unir.txt
mx $base from=$MFN_ORIGEN to=$MFN_ORIGEN "proc='d.'" copy=$base now >> backups/$1/log-unir.txt 2>> backups/$1/logerror-unir.txt

echo >> backups/$1/log-unir.txt
echo >> backups/$1/logerror-unir.txt

done

# reinversion de la base

# fin
echo ============================================================================= >> backups/$1/log-unir.txt
echo ============================================================================= >> backups/$1/logerror-unir.txt
