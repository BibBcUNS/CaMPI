<html>
<head>
<title>Open MarcoPolo - Módulo Estadísticas: Resultados</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	
<SCRIPT language="JavaScript">
<!--
	function graficar(Tipo_Grafico) {
		window.document.form_graficos.Tipo_Grafico.value=Tipo_Grafico;
//		Layer1.style="visibility: visible";
		window.document.getElementById("Layer1").style.visibility = "visible";
 		window.document.form_graficos.submit();
		}
		
	function Ver_Registros(Expresion) {
		window.document.form_VerRegistros.Expresion.value=Expresion;
		window.open("","Registros","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,copyhistory=0,width=650,height=500");
 		window.document.form_VerRegistros.submit();
		}
		
-->
</SCRIPT>
</head>
<body topmargin="0" bgcolor="#E8E8D0" onload="this.focus();graficar([pft]"'bars'"n0[/pft])">
<link rel="stylesheet" href="[pft]getenv('PATH_INFO')[/pft]estilos.css" type="text/css">
<table width="100%">
	<tr>
	  <td width="50%">BIBLIOTECA JERONIMO SPADACCIOLI</td>
	  <td width="50%" align="right">
	    <h5>Open MarcoPolo - Módulo Estadísticas<br>
	    Fecha:
	[pft]select s(date)*16.1
		     case '0': ,'Domingo, ',
		     case '1': ,'Lunes, ',
		     case '2': ,'Martes, ',
		     case '3': ,'Miércoles, ',
		     case '4': ,'Jueves, ',
		     case '5': ,'Viernes, ',
		     case '6': ,'Sábado, ',
		   endsel,
		   mid(date,7,2),'/',mid(date,5,2),'/',mid(date,1,4)
	[/pft]</h5>
		</td>
	</tr>
</table><hr>
[pft]|<p align="center">Otros datos: |v904|</p>|[/pft]
<table border="1" align="center">
	<tr>
		<td class="celda_encabezado" align="center" colspan="2">Movimiento</td>
		[pft](v4002)[/pft]</td>
		<td class="celda_encabezado">Totales</td></tr>
[pft]
	(if p(v4001) then 
		'<tr>',
		if nocc(v4001)=1 then 
			'<td align="center" class="celda_titulo" colspan="2">'
		else
			'<td bgcolor="',v4008,'">&nbsp;</td>' 
			'<td align="center" class="celda_titulo">'
		fi,
		,v4001,v4000,'</td><td align="center" class="celda_titulo">',f(rsum(v3001),1,0),'</td></tr>'
	fi)
[/pft]
</table>
<br>
<table border="0" align="center">
<tr><td>
	<a href="JavaScript:graficar([pft]"'lines'"n0[/pft])"><img src="[pft]getenv('PATH_INFO')[/pft]graficos/lineas.gif" alt="Grafico de Lineas" border="0"></a>&nbsp;
	<a href="JavaScript:graficar([pft]"'bars'"n0[/pft])"><img src="[pft]getenv('PATH_INFO')[/pft]graficos/barras.gif" alt="Grafico de Barras" border="0"></a>&nbsp;
[pft]
	if v9999<>'mensual' then
		'<a href="JavaScript:graficar(',"'pie'"n0,')"><img src="',getenv('PATH_INFO'),'graficos/torta.gif" alt="Grafico Circular" border="0"></a>&nbsp;'
	else
		if nocc(v4001)> 1 then  /* si es una consulta mensual se dispone del grafico de torta unicamente si hay mas de una operación */
			'<a href="JavaScript:graficar(',"'pie'"n0,')"><img src="',getenv('PATH_INFO'),'graficos/torta.gif" alt="Grafico Circular" border="0"></a>&nbsp;'
	  fi
	fi
[/pft]
<a href="JavaScript:print()"><img src="[pft]getenv('PATH_INFO')[/pft]graficos/imprimir.gif" alt="Imprimir" border="0"></a>
<a href="[pft]getenv('PATH_INFO')[/pft]ranking_index.htm" target="grafico"><img src="[pft]getenv('PATH_INFO')[/pft]graficos/ranking.gif" alt="Máximos" border="0"></a>&nbsp;
</td></tr></table>
<div id="Layer1" align="center">
	<iframe width="600" height="320" name="grafico">
	</iframe>	
</div>	
<form action="/phplot/OpenMP/lineas.php" name="form_graficos" target="grafico">
		<input type="Hidden" name="Series[0]" value="[pft]v4006+|,|[/pft]">
[pft]
	(if p(v3001) then 
		/,x6'<input type="Hidden" name="Series[',f(iocc,1,0),']" value="',replace(v3001,'|',','),'">'
   fi/)
[/pft]
	<input type="Hidden" name="Leyendas" value="[pft]v4001+|,|[/pft]">
	<input type="Hidden" name="Tipo_Grafico">
</form>
 
<form action="/cgi-bin/wxis.exe/circula/estadisticas/" method="post" name="form_VerRegistros" target="Registros">
	<input type="hidden" name="IsisScript" value="circula/estadisticas/verregistros.xis">
	<input type="Hidden" name="Expresion">
</form>
<form action="/cgi-bin/wxis.exe/circula/estadisticas/" method="post" name="form_Ranking" target="frame_datos">
	<input type="hidden" name="IsisScript" value="circula/estadisticas/ranking.xis">
	<input type="Hidden" name="campo">
[pft]
(
if p(v3000) then
'  <input type="Hidden" name="tag3000" value="',v3000,'">'/
fi
)
[/pft]
</form>
</body>
</html>
