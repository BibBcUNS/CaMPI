:: ==============================================================
::  Genera el archivo invertido de la base bibliogr fica (ANSI)
::
::  (c) Fernando J. G¢mez, INMABB, 2003.
:: ==============================================================


set CIPAR=C:\httpd\bases\opacmarc\opac.cip
::mx biblio gizmo=ANSI2OEM create=biblio_oem now -all tell=1000
mx biblio gizmo=DICTGIZ fst=@BIBLIO.FST actab=AC-ANSI.TAB uctab=UC-ANSI.TAB stw=@BIBLIO.STW fullinv=biblio
set CIPAR=

