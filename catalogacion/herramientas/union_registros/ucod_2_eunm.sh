# USO: ucod_2_eunm nc_ucod

PATH=$PATH:/opt/cisis

script_name=$0;
nc_fuente=$1;
usuario=$2;
fuente=$3;
destino=$4;
# OJO CON ESTAS TRES!!! LINEAS
#ucod=/var/www/bases/catalis/catalis_pack_en_produccion/catalis/carpcTmp/biblio
#eunm=/var/www/bases/catalis/catalis_pack_en_produccion/catalis/carpc/biblio
#cn_txt=/var/www/bases/catalis/catalis_pack_en_produccion/catalis/carpc/cn.txt


path_fuente=/var/www/campi-catalogacion/catalis/bases/catalis/$fuente/biblio
path_destino=/var/www/campi-catalogacion/catalis/bases/catalis/$destino/biblio
cn_txt=/var/www/campi-catalogacion/catalis/bases/catalis/$destino/cn.txt

# haciendo backup de las bases
./bkup.sh $destino
./bkup.sh $fuente

nro=$(cat $cn_txt)
nro=$(expr $nro + 1) # expr permite tomar el nro como decimal a pesar de los ceros a la izquierda
nro=$(printf "%06d" $nro)
echo $nro>$cn_txt

date >> logMover.txt
date >> logerrorMover.txt
echo Moviendo registro $script_name $* >> logMover.txt
echo Moviendo registro $script_name $* >> logerrorMover.txt

echo >> logMover.txt
echo >> logerrorMover.txt
echo Obteniendo MFN del registro $nc_fuente de $fuente >> logMover.txt
echo ------------------------------------------------- >> logMover.txt
echo Obteniendo MFN del registro $nc_fuente de $fuente >> logerrorMover.txt
echo ------------------------------------------------- >> logerrorMover.txt
MFN=$(mx $path_fuente btell=0 "-NC=$nc_fuente" "pft=f(mfn,1,0)" now -all)
echo MFN=$MFN >> logMover.txt
echo MFN=$MFN >> logerrorMover.txt

echo >> logMover.txt
echo >> logerrorMover.txt
echo Desbloqueando el registro $nc_fuente, MFN:$MFN, de $fuente >> logMover.txt
echo ------------------------------------------------- >> logMover.txt
echo Desbloqueando el registro $nc_fuente, MFN:$MFN, de $fuente >> logerrorMover.txt
echo ------------------------------------------------- >> logerrorMover.txt
echo retag $fuente unlock from $MFN to $MFN
retag $path_fuente unlock from=$MFN to=$MFN >> logMover.txt 2>> logerrorMover.txt

echo Moviendo el registro $nc_fuente de $fuente a $destino >> logMover.txt
echo ---------------------------------------------- >> logMover.txt
echo Moviendo el registro $nc_fuente de $fuente a $destino >> logerrorMover.txt
echo ---------------------------------------------- >> logerrorMover.txt
mx $path_fuente from=$MFN to=$MFN "proc='d1a1~$nro~'" append=$path_destino now >> logMover.txt 2>> logerrorMover.txt

echo >> logMover.txt
echo >> logerrorMover.txt
echo Borrando el registro $nc_fuente de $fuente >> logMover.txt
echo ---------------------------------------------- >> logMover.txt
echo Borrando el registro $nc_fuente de $fuente >> logerrorMover.txt
echo ---------------------------------------------- >> logerrorMover.txt
mx $path_fuente from=$MFN to=$MFN "proc='d.'" copy=$path_fuente now >> logMover.txt 2>> logerrorMover.txt

echo >> logMover.txt
echo >> logerrorMover.txt
echo Generando el invertido de $destino >> logMover.txt
echo ---------------------------------------------- >> logMover.txt
echo Generando el invertido de $destino >> logerrorMover.txt
echo ---------------------------------------------- >> logerrorMover.txt
/var/www/campi-catalogacion/catalis/cgi-bin/fullinv.sh $path_destino >> logMover.txt 2>> logerrorMover.txt

echo ==================================================================== >> logMover.txt
echo ==================================================================== >> logerrorMover.txt
