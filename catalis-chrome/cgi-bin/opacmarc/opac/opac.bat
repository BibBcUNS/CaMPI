@echo off

:: ===================================================================
:: OPAC.BAT
::
:: Este script genera el conjunto de archivos utilizados en el OPAC.
::
:: Requiere: mx, msrt, i2id, id2i. Usa un archivo cipar.
::
:: La base de origen debe tener la codificacion "ANSI". Asi es como
:: esta codificada, por ejemplo, una base creada con Catalis.
::
:: Usamos "seq=filename.id¦" para que el poco probable
:: caracter '¦' sea tomado como delimitador de campos (y, en
:: consecuencia, no tengamos separacion en campos). Quizas
:: convenga emplear otro caracter? Revisar.
::
:: ATENCION: como hacemos si los registros ya vienen con ^9 en los
:: campos de encabezamientos?
::
:: TO-DO: Necesitamos medir el tiempo de ejecucion del script.
:: 
:: (c) 2003-2004 Fernando J. Gomez - CONICET - INMABB
:: ===================================================================


set PATH=%PATH%;C:\cisis\402r3

:: Parametros obligatorios (base de origen y directorio destino)
if .%1==. goto SYNTAX
if .%2==. goto SYNTAX

cls

:: Hay que usar el path absoluto para el cipar
set CIPAR=\httpd\cgi-bin\catalis_pack\opacmarc\opac\opac.cip
if not exist %CIPAR% goto ERROR_CIPAR

:: Verificamos la existencia de la base de origen
if not exist %1.mst goto ERROR_DB_ORIGIN
if not exist %1.xrf goto ERROR_DB_ORIGIN

:: Creamos el directorio destino, si es necesario
:: ATENCION: en Win2K a veces genera un error, sin razon aparente. Hay que reintentar,
:: o bien hacer un cd al directorio %2 seguido de un cd.. para regresar
if not exist %2\nul mkdir %2
if errorlevel 1 goto ERROR_MKDIR
if not exist %2\tmp\nul mkdir %2\tmp
if errorlevel 1 goto ERROR_MKDIR_TMP


:BIBLIO_DATABASE_1

echo Creamos una copia (texto) de la base bibliografica.
set MAXCOUNT=30000
if not %3.==. set MAXCOUNT=%3
i2id %1 count=%MAXCOUNT% tell=1000 > %2\tmp\biblio1.id

:: A partir de ahora, trabajamos en el directorio destino
cd %2
if errorlevel 1 goto ERROR_CHANGE_DIR


:: Lista de tags de los cuales vamos a extraer los encabezamientos
set SUBJ_TAGS=v600v610v611v630v650v651v653v655v656
set NAME_TAGS=v100v110v111v700v710v711

:: Valores del 2do indicador en campos 6xx que no deseamos considerar
set IGNORE_SUBJ_HEADINGS=#6

echo.
echo Intentamos normalizar la puntuacion final, filtramos encabezamientos
echo tematicos, y asignamos un numero (provisorio) a cada campo
echo de encabezamientos en el subcampo ^9.
mx "seq=tmp\biblio1.id¦" lw=3000 "pft=@HEAD.PFT" now tell=1000 > tmp\biblio2.id


:SUBJ_DATABASE

echo.
echo -----------------------------------------------------
echo  Base de encabezamientos tematicos
echo -----------------------------------------------------

echo Creamos el listado de encabezamientos tematicos.
mx "seq=tmp\biblio2.id¦" lw=1000 "pft=if getenv('SUBJ_TAGS') : v1*1.4 then @SUBJ.PFT fi" now tell=1000 > tmp\subj1.id

echo.
echo Convertimos el listado en una base (desordenada y con duplicados).
id2i tmp\subj1.id create/app=tmp\subj1 tell=1000

echo.
echo Regularizamos la puntuacion final de los encabezamientos generados.
mx tmp\subj1 "proc='d2a2¦',v1,'¦'" "proc='d1a1¦',@REGPUNT.PFT,'¦'" proc='d2' copy=tmp\subj1 now -all tell=1000

echo.
echo Almacenamos en un campo auxiliar la clave de ordenacion.
mx tmp\subj1 uctab=UC-ANSI.TAB "proc='d99a99¦',@HEADSORT.PFT,'¦'" copy=tmp\subj1 now -all tell=5000

echo.
echo Ordenamos la base de encabezamientos tematicos.
msrt tmp\subj1 100 v99 tell=5000

echo.
echo Generamos la tabla para mapear los numeros de encabezamientos.
mx tmp\subj1 "pft=if s(v1) <> ref(mfn-1,v1) then putenv('HEADING_CODE='v9) fi, v9,'|',getenv('HEADING_CODE')/" now -all tell=1000 > tmp\subjcode.seq

echo.
echo Eliminamos los encabezamientos duplicados.
mx tmp\subj1 lw=1000 "pft=@ELIMDUP2.PFT" now tell=1000 > tmp\subj.id

echo.
echo Creamos la base de encabezamientos tematicos (ordenada y sin duplicados).
id2i tmp\subj.id create/app=subj tell=1000


:NAME_DATABASE

echo.
echo -----------------------------------------------------
echo  Base de encabezamientos de nombres
echo -----------------------------------------------------

echo Creamos el listado de encabezamientos de nombres
mx "seq=tmp\biblio2.id¦" lw=1000 "pft=if getenv('NAME_TAGS') : v1*1.4 then @NAME.PFT fi" now tell=1000 > tmp\name1.id

echo.
echo Convertimos el listado en una base (desordenada y con duplicados).
id2i tmp\name1.id create/app=tmp\name1 tell=1000

echo.
echo Regularizamos la puntuacion final de los encabezamientos generados.
mx tmp\name1 "proc='d2a2¦',v1,'¦'" "proc='d1a1¦',@REGPUNT.PFT,'¦'" proc='d2' copy=tmp\name1 now -all tell=1000

echo.
echo Almacenamos en un campo auxiliar la clave de ordenacion.
mx tmp\name1 uctab=UC-ANSI.TAB "proc='d99a99¦',@HEADSORT.PFT,'¦'" copy=tmp\name1 now -all tell=5000

echo.
echo Ordenamos la base de encabezamientos de nombres.
msrt tmp\name1 100 v99 tell=5000

echo.
echo Generamos la tabla para mapear los numeros de encabezamientos.
mx tmp\name1 "pft=if s(v1) <> ref(mfn-1,v1) then putenv('HEADING_CODE='v9) fi, v9,'|',getenv('HEADING_CODE')/" now -all tell=1000 > tmp\namecode.seq

echo.
echo Eliminamos los encabezamientos duplicados.
mx tmp\name1 lw=1000 "pft=@ELIMDUP2.PFT" now tell=1000 > tmp\name.id

echo.
echo Creamos base de encabezamientos de nombres (ordenada y sin duplicados).
id2i tmp\name.id create/app=name tell=1000


echo.
:: -----------------------------------------------------------------
echo Reasignamos numeros a los encabezamientos en los registros
echo bibliograficos (subcampo 9).
:: -----------------------------------------------------------------
mx seq=tmp\subjcode.seq create=tmp\subjcode now -all
mx tmp\subjcode "fst=1 0 v1" fullinv=tmp\subjcode
mx seq=tmp\namecode.seq create=tmp\namecode now -all
mx tmp\namecode "fst=1 0 v1" fullinv=tmp\namecode

mx "seq=tmp\biblio2.id¦" lw=1000 "pft=@RECODE.PFT" now tell=1000 > tmp\biblio3.id



:TITLE_DATABASE

echo.
echo -----------------------------------------------------
echo  Base de titulos
echo -----------------------------------------------------

:: Lista de campos que se incluyen en la base TITLE. ATENCION: completar/revisar
::set TITLE_TAGS=v130~v245~v246~v700~v730~v740~v765~v773
set TITLE_TAGS=v130~v240~v245~v246~v730~v740~v765~v773

echo Creamos listado de titulos.
mx "seq=tmp\biblio3.id¦" lw=1000 "pft=if getenv('TITLE_TAGS') : v1*1.4 then ,@TITLE.PFT, fi" now tell=1000 > tmp\title1.id

echo.
echo Convertimos el listado en una base (desordenada y con duplicados).
id2i tmp\title1.id create/app=tmp\title1 tell=1000

echo.
echo Almacenamos en un campo auxiliar la clave de ordenacion de titulos.
mx tmp\title1 uctab=UC-ANSI.TAB "proc='d99a99¦',@HEADSORT.PFT,'¦'" copy=tmp\title1 now -all tell=5000

echo.
echo Ordenamos la base de titulos.
msrt tmp\title1 100 v99 tell=5000

echo.
echo Eliminamos los titulos duplicados.
mx tmp\title1 lw=1000 "pft=@ELIMDUP2.PFT" now tell=1000 > tmp\title.id

echo.
echo Creamos la base de titulos (ordenada y sin duplicados).
id2i tmp\title.id create/app=title tell=1000


:BIBLIO_DATABASE_2

echo.
:: ---------------------------------------------
echo Recreamos la base bibliografica.
:: ---------------------------------------------
id2i tmp\biblio3.id create=biblio tell=1000


echo.
:: ---------------------------------------------
echo Ordenamos la base bibliografica.
:: ---------------------------------------------
msrt biblio 100 @LOCATION_SORT.PFT tell=5000



:FULLINV

:: -------------------------------------------------------------------
:: Generacion de archivos invertidos.
:: ATENCION: AC-ANSI.TAB envia los numeros al diccionario.
:: -------------------------------------------------------------------

echo.
echo ---------------------------------------------
echo  Archivo invertido - Base de temas ...
echo ---------------------------------------------
mx subj fst=@HEADINGS.FST actab=AC-ANSI.TAB uctab=UC-ANSI.TAB fullinv=subj tell=2000

echo.
echo ---------------------------------------------
echo  Archivo invertido - Base de nombres ...
echo ---------------------------------------------
mx name fst=@HEADINGS.FST actab=AC-ANSI.TAB uctab=UC-ANSI.TAB fullinv=name tell=2000

echo.
echo ---------------------------------------------
echo  Archivo invertido - Base de titulos ...
echo ---------------------------------------------
mx title "fst=2 0 '~',@HEADSORT.PFT" actab=AC-ANSI.TAB uctab=UC-ANSI.TAB fullinv=title tell=2000

echo.
echo ---------------------------------------------
echo  Archivo invertido - Base bibliografica ...
echo ---------------------------------------------
mx biblio gizmo=DICTGIZ fst=@BIBLIO.FST actab=AC-ANSI.TAB uctab=UC-ANSI.TAB stw=@BIBLIO.STW fullinv=biblio tell=2000



:POSTINGS

echo.
:: --------------------------------------------------------
echo Asignamos postings a los terminos del indice de temas.
:: --------------------------------------------------------
mx subj "proc='d11a11#',f(npost(['BIBLIO']'_SUBJ_'v9),1,0),'#'" copy=subj now -all tell=1000

echo.
:: ----------------------------------------------------------
echo Asignamos postings a los terminos del indice de nombres.
:: ----------------------------------------------------------
mx name "proc='d11a11#',f(npost(['BIBLIO']'_NAME_'v9),1,0),'#'" copy=name now -all tell=1000



:AGREP_DICTIONARIES

echo.
:: -----------------------------------------------------
echo Generamos diccionarios para AGREP.
:: Solo nos interesan claves asociadas a ciertos tags.
:: /100 restringe la cantidad de postings (de lo contrario, da error)
:: ATENCION: los sufijos NAME, SUBJ, TITLE van en mayusculas o minusculas
:: en base a los valores que tome el parametro CGI correspondiente.
:: -----------------------------------------------------
echo    - subj
:: Para bibima usamos la base MSC; para el resto, la base SUBJ
if %2==bibima mx dict=\httpd\bases\catalis_pack\opacmarc\msc2000\msc "pft=v1^*/" k1=a k2=zz now > dictSUBJ.txt
if not %2==bibima mx dict=subj "pft=v1^*/" k1=a k2=zz now > dictSUBJ.txt

echo    - name
mx dict=name "pft=v1^*/" k1=a k2=zz now > dictNAME.txt

echo    - title
::mx dict=biblio,1,2/100  "pft=if v2^t:'204' then v1^*/ fi" k1=a now > dicttitle.txt
ifkeys biblio +tags from=a to=zzzz > tmp\titlekeys.txt
mx seq=tmp\titlekeys.txt "pft=if v2:'204' then v3/ fi" now > dictTITLE.txt


:LANG

echo.
:: -----------------------------------------------------
echo Lista de codigos de idioma.
:: TO-DO: revisar (OEM2ANSI?)
:: -----------------------------------------------------
mx seq=LANG.TXT create=LANG now -all
mx LANG fst=@LANG.FST fullinv=LANG
mx dict=biblio k1=-LANG=A k2=-LANG=ZZZ "pft=v1^**6.3,'|',v1^t/" now > langcode.txt
mx seq=langcode.txt create=langcode now -all
msrt langcode 30 "mpu,ref(['LANG']l(['LANG']v1.3),v3)"
mx langcode "pft=v1,'^p',v2,'^',/" now -all > langcode.txt
::mx LANG gizmo=OEM2ANSI copy=LANG now -all


echo.
:: -----------------------------------------------------
echo Lista de codigos de bibliotecas.
:: -----------------------------------------------------
mx dict=biblio k1=-BIB=A k2=-BIB=ZZZ "pft=v1^**5,'^p',v1^t/" now > bibcode.txt


echo.
:: -----------------------------------------------------
echo Fechas extremas.
:: -----------------------------------------------------
mx dict=biblio "k1=-F=1" "k2=-F=2999" "pft=v1^**3/" now > tmp\dates1.txt
mx tmp to=1 "proc='a1~',replace(s(cat('tmp\dates1.txt')),s(#),'&'),'~'" "pft=v1.4,'-',s(right(v1,5)).4" > dates.txt


echo.
:: -----------------------------------------------------
echo Total de registros disponibles.
:: -----------------------------------------------------
echo. > bases.par
mx biblio count=1  "pft=proc('a5001~',f(maxmfn-1,1,0),'~'),'BIBLIOGRAPHIC_TOTAL=',left(v5001,size(v5001)-3),if size(v5001) > 3 then '.' fi,right(v5001,3)/" >> bases.par
mx name count=1 "pft=proc('a5001~',f(maxmfn-1,1,0),'~'),'NAME_TOTAL=',left(v5001,size(v5001)-3),if size(v5001) > 3 then '.' fi,right(v5001,3)/" >> bases.par
mx subj count=1 "pft=proc('a5001~',f(maxmfn-1,1,0),'~'),'SUBJ_TOTAL=',left(v5001,size(v5001)-3),if size(v5001) > 3 then '.' fi,right(v5001,3)/" >> bases.par
mx title count=1 "pft=proc('a5001~',f(maxmfn-1,1,0),'~'),'TITLE_TOTAL=',left(v5001,size(v5001)-3),if size(v5001) > 3 then '.' fi,right(v5001,3)/" >> bases.par


echo.
:: -----------------------------------------------------
echo Fecha de esta actualizacion.
:: -----------------------------------------------------
mx tmp "pft=s(date)*6.2'/'s(date)*4.2'/'s(date).4" to=1 > updated.txt


echo.
:: -----------------------------------------------------------
::echo Eliminamos archivos auxiliares generados por este script
:: -----------------------------------------------------------
::del tmp\*.* 2>nul

del *.ln* 2>nul
del *.lk* 2>nul


echo.
:: --------------------------------------------------------
echo Eliminamos archivos temporales del OPAC.
:: ATENCION: esto en realidad debe hacerse en el *servidor*
:: --------------------------------------------------------
del \httpd\cgi-bin\opacmarc\temp\*.$$$ 2>nul


echo.
echo ---------------------------------------------
echo  Ejecucion de %0.bat finalizada.
echo ---------------------------------------------
echo.
echo.


:: ATENCION: 'cd..' podria no ser lo que queremos
cd ..
goto END



:SYNTAX
type %0.syn
echo.
goto END


:ERROR_CIPAR
echo.
echo ------------------------------------------------------------------
echo.   *** ATENCION ***
echo    No se ha encontrado el archivo cipar: %CIPAR%
echo ------------------------------------------------------------------
echo.
goto END

:ERROR_DB_ORIGIN
echo.
echo ------------------------------------------------------------------
echo.   *** ATENCION ***
echo    No se ha encontrado la base de datos de origen,
echo    %1
echo ------------------------------------------------------------------
echo.
goto END


:ERROR_MKDIR
echo.
echo ------------------------------------------------------------------
echo.   *** ATENCION ***
echo    No se pudo crear el directorio %2
echo    Bajo Windows 2000 a veces se genera este error, sin razon
echo    aparente; en tal caso, reintente.
echo ------------------------------------------------------------------
echo.
goto END

:ERROR_MKDIR_TMP
echo.
echo ------------------------------------------------------------------
echo.   *** ATENCION ***
echo    No se pudo crear el directorio %2\aux
echo    Bajo Windows 2000 a veces se genera este error, sin razon
echo    aparente; en tal caso, reintente.
echo ------------------------------------------------------------------
echo.
goto END

:ERROR_CHANGE_DIR
echo.
echo ------------------------------------------------------------------
echo.   *** ATENCION ***
echo    No se pudo cambiar al directorio %2
echo ------------------------------------------------------------------
echo.
goto END


:END
set CIPAR=
