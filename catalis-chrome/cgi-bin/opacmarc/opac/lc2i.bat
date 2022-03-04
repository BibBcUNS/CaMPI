:: ========================================================================
:: LC2I.BAT
::
:: Generaci¢n de una base Isis a partir de registros MARC (archivos *.mrc)
:: bajados de LC.
::
:: (c) Fernando G¢mez, INMABB, abril de 2003
:: ========================================================================


@echo off

if %1.==. goto SYNTAX
if %2.==. goto SYNTAX

echo.
echo Eliminamos una versi¢n anterior de la base
mx seq=nul create=%21 now -all


echo.
echo Llamamos a m2i.bat para cada archivo .mrc que queremos incorporar a la base
call m2i.bat %1 %21

echo.
echo Eliminamos registros duplicados, en base al campo 001
msrt %21 12 v1
mx %21 "proc=if v1=ref(mfn-1,v1) then 'd*' fi" iso=%2.iso now -all
mx iso=%2.iso create=%2 now -all

echo.
echo Eliminamos algunos campos (ATENCION: tener en cuenta otros m s [ver con mxf0])
mx %2 proc='d35d40d42' copy=%2 now -all

echo.
echo Actualizamos campos 001 y 005
mx %2 proc='d1a1~',mfn(6),'~' proc='d5a5~',s(date).8,s(date)*9.6,'.0~' proc='s' copy=%2 now -all


echo.
echo Pr¢ximos pasos: generar archivo invertido, aplicar el gizmo, renombrar archivos
echo y copiar los archivos al servidor.
echo.

goto END

:SYNTAX
echo.
echo   Uso:      lc2i mrc_file isis_db_name
echo.
echo   Ejemplo:  lc2i "f:\mrc-files\test1.mrc" "bases\test1"
echo.

:END
