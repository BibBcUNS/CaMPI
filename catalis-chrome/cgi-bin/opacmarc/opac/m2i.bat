@echo off

:: ========================================================
:: M2I -- Incorporaci¢n de registros MARC en una base ISIS
::
:: (c) Fernando Gomez, INMABB, 2003
:: ========================================================


set PHP_DIR=C:\php-4.3.1-Win32\cli

if %1.==. goto SYNTAX
if %2.==. goto SYNTAX


echo.
echo Conversi¢n .mrc a .id
%PHP_DIR%\php -q mrc2isis.php %1 %2

echo.
echo Conversi¢n .id a isis
id2i marcfile.id create=%2 tell=100


:: Default: la dejamos como vino (ANSI?)
if not %3.==oem. goto END

echo.
echo Conversi¢n ansi a oem
set CIPAR=c:\httpd\bases\opacmarc\opac.cip
mx %2 gizmo=ANSI2OEM copy=%2 -all now

goto END


:SYNTAX
type %0.syn
echo.
echo.

:END
