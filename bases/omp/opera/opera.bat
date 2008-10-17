:: =====================================================
:: CaMPI - opera.bat
::
:: Creacion de la base de datos de operadores + permisos
:: =====================================================

@echo off

:: Necesitamos asegurar una línea en blanco final
echo.>>opera.txt

:: Creamos la base de usuarios
id2i opera.txt create=opera

:: Generamos el diccionario
mx opera "fst=1 0 v1" fullinv=opera

echo.
echo La base opera ha sido generada
echo.

:: Detectamos operadores repetidos
mx dict=opera "pft=if val(v1^t) <> 1 then /#,'  *** ATENCION: ',v1^*, ' tiene mas de un posting!' /, fi" now -all

:: Detectamos bases sin permiso de acceso definido
mx opera "pft=(if a(v5) then /#,'  *** ATENCION: operador ',v1[1],': falta definir permiso de acceso!' /, fi)" now -all
