# ==========================================================================
# Script para importar registros MARC 21 en Catalis
# 
# Dos casos particulares que nos interesan:
#   - registros de IsisMarc (latin1 / "diacríticos ala")
#   - registros de LC (marc-8 / utf-8)
#
# uso:
#   import <archivo_marc> <base_de_datos>
#
# ejemplo:
#   import registros.mrc biblio
#
# Validaciones: existencia del archivo y de la base
# Hay que considerar la opción de que la base no exista y sea creada a
# partir del archivo mrc?
# Incluir un undo?
# ==========================================================================

# a partir de qué tag se almacenan los datos del leader
LEADER_BASE_TAG_1=1000 # provisorio
LEADER_BASE_TAG_2=900  # definitivo

# archivo de registros MARC
MARC_FILE=$1

# base donde almacenar los registros importados
TARGET_DB=$2 || 'biblio'

# archivo usado como contador para generar el campo 001
COUNTER='cn.txt'

# dónde encontrar mx
PATH=~/campi/cisis-5.2:$PATH
export PATH
#echo $PATH

# tenemos que asegurarnos de estar usando mx 5.x (al menos)
mx what > mxversion
mx seq=mxversion count=1 "pft=if v1 : 'CISIS Interface v5.' then 'OK' fi" now > versionOK
# TO-DO: exit con mensaje de error si mx es viejo

clear

# creamos una base isis a partir del registro MARC
# BUG: la posición 09 del leader no se almacena!! (informar a Spinak/Bireme)
mx iso=marc=$MARC_FILE isotag1=$LEADER_BASE_TAG_1 create=basemarc now -all

# eliminamos del registro importado algunos campos locales que utiliza Catalis
# TO-DO: esta lista es completa?
mx basemarc "proc='d905 d906 d907 d908 d909 d917 d918 d919'" copy=basemarc now -all

# traemos los datos del leader a los campos 9xx
mx basemarc "proc='d1005d1006d1007d1008d1009d1017d1018d1019','a905|',v1005,'|a906|',v1006,'|a907|',v1007,'|a908|',v1008,'|a909|',v1009,'|a917|',v1017,'|a918|',v1018,'|a919|',v1019,'|'" copy=basemarc now -all

# sustituir delimitadores de subcampos: hex 1F => ^
mx basemarc gizmo=delimsubcampo copy=basemarc now -all 
 
# cambiamos la codificación, si la original no es latin1
# TO-DO: cómo averiguamos cuál es la codificación original de los registros?
# TO-DO: usamos un gizmo? cómo nos aseguramos de estar usando el gizmo correcto?
mx basemarc gizmo=ansel2latin1 copy=basemarc now -all
 
# sustitución de blancos en campos de datos
mx basemarc "proc=@blancos.pft" copy=basemarc now -all

# sustitución de blancos en indicadores
i2id basemarc > basemarc.id
mx seq=basemarc.id create=basemarc-campos now -all  # OJO: que no separe en campos accidentalmente
mx basemarc-campos "proc='d1a1|',v1.6,replace(v1*6.2,' ','#'),v1*8,'|'" copy=basemarc-campos now -all
mx basemarc-campos lw=2000 "pft=v1/" now > basemarc-campos.id
id2i basemarc-campos.id create=basemarc-catalis

# ajuste del campo 001 (REVISAR rellenado con ceros)
mx basemarc-catalis "proc='d001a001|',right(s('000000',f(mfn + val(cat('$COUNTER')),1,0)),6),'|'" copy=basemarc-catalis now -all
# actualización del contador
mx basemarc-catalis "pft=right(s('000000',f(maxmfn + val(cat('$COUNTER')) - 1,1,0)),6)" count=1 now > $COUNTER

# agregamos los registros a la base destino 
mx basemarc-catalis append=$TARGET_DB now -all

# TO-DO: generar (o actualizar) el archivo invertido
