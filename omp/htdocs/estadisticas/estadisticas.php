<?php session_start(); 
if (isset($_SESSION["s_username"])
	&& isset($_SESSION["s_permisos"])
	&& in_array('estadisticas' , $_SESSION["s_permisos"])) {?>

<html>
<head>
<title>CaMPI - Estadísticas</title>
    <link rel="stylesheet" type="text/css" href="/omp/css/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script language="JavaScript">

function comienzo_semana(Mes, Anio, Dia_Comienzo_Semana) {


  _Fecha = new Date(Anio, Mes, 1)
  _UltimoDia = new Date(_Fecha)
  _UltimoDia.setMonth(_Fecha.getMonth() + 1)
  _UltimoDia.setHours(_UltimoDia.getHours() - 24)
  _DiasDelMes = new Array(); //''

	/*_Fecha es el primer día de la semana y _UltimoDia es el último día de la semana */
	
  if (_Fecha.getDay()=='0') {_Fecha.setHours(_Fecha.getHours() + 24)} // si hoy es domingo le suma 24 hs. para llegar al lunes
  if (_Fecha.getDay()=='2') {_Fecha.setHours(_Fecha.getHours() - 24)} // si hoy es martes le resta 24 hs. para llegar al lunes
  if (_Fecha.getDay()=='3') {_Fecha.setHours(_Fecha.getHours() - 48)} // si hoy es miércoles le resta 48 hs. para llegar al lunes
  if (_Fecha.getDay()=='4') {_Fecha.setHours(_Fecha.getHours() - 72)} // si hoy es jueves le resta 72 hs. para llegar al lunes
  if (_Fecha.getDay()=='5') {_Fecha.setHours(_Fecha.getHours() - 96)} // si hoy es viernes le resta 96 hs. para llegar al lunes
  if (_Fecha.getDay()=='6') {_Fecha.setHours(_Fecha.getHours() - 120)} // si hoy es sábado le resta 120 hs. para llegar al lunes

  if (_UltimoDia.getDay()=='0') {_UltimoDia.setHours(_UltimoDia.getHours() - 48)} // si hoy es domingo le resta 24 hs. para llegar al sábado ANTERIOR
  if (_UltimoDia.getDay()=='1') {_UltimoDia.setHours(_UltimoDia.getHours() + 120)} // si hoy es lunes le suma 120 hs. para llegar al sábado 
  if (_UltimoDia.getDay()=='2') {_UltimoDia.setHours(_UltimoDia.getHours() + 96)} // si hoy es martes le suma 92 hs. para llegar al sábado 
  if (_UltimoDia.getDay()=='3') {_UltimoDia.setHours(_UltimoDia.getHours() + 72)} // si hoy es miercoles le suma 72 hs. para llegar al sábado 
  if (_UltimoDia.getDay()=='4') {_UltimoDia.setHours(_UltimoDia.getHours() + 48)} // si hoy es jueves le suma 48 hs. para llegar al sábado 
  if (_UltimoDia.getDay()=='5') {_UltimoDia.setHours(_UltimoDia.getHours() + 24)} // si hoy es viernes le suma 24 hs. para llegar al sábado 


  var _MesUltimoDia = _UltimoDia.getMonth()+1
  var _DiaUltimoDia = _UltimoDia.getDate()
  var _Fin = null
  if (_MesUltimoDia < 10) {_Fin = _UltimoDia.getFullYear() + '0' + _MesUltimoDia} else {_Fin = _UltimoDia.getFullYear() + '' + _MesUltimoDia}
  if (_DiaUltimoDia < 10) {_Fin += '0' + _DiaUltimoDia} else {_Fin += '' + _DiaUltimoDia}
  
  var _MesPrimerDia = _Fecha.getMonth()+1
  var _DiaPrimerDia = _Fecha.getDate()
  var _Inicio = null
  if (_MesPrimerDia < 10) {_Inicio = _Fecha.getFullYear() + '0' + _MesPrimerDia} else {_Inicio = _Fecha.getFullYear() + '' + _MesPrimerDia}
  if (_DiaPrimerDia < 10) {_Inicio += '0' + _DiaPrimerDia} else {_Inicio += '' + _DiaPrimerDia}
 
 
  _ContSemana = 1
  _MesActual = _Fecha.getMonth()+1
  _DiaActual = _Fecha.getDate()
  _Actual = ''

	<!-- Puesta a "cero" las variables de las semanas -->
	for (var i = 0; i < window.document.form_stat.semana.length; i++) {
		window.document.form_stat.semana.options[i] = null;
		}
		
  _FechaAPasar = ''
    
  while (_Inicio <= _Fin) {
	
		<!-- arma _Actual con formato AAAA/MM/DD -->
		
		if (_MesActual < 10) {_Actual += '^s' + _ContSemana + '^fFecha=' +  _Fecha.getFullYear() + '/0' + _MesActual} else 
			{_Actual += '^s' + _ContSemana + '^fFecha=' + _Fecha.getFullYear() + '/' + _MesActual}    
		if (_DiaActual < 10) {_Actual += '/0' + _DiaActual} else {_Actual +=  '/' + _DiaActual}
		
		<!-- Visualización de las semanas -->
		if (_Fecha.getDay() == '1') {
			_Primer_Dia = _Actual.substring(19,21) + _Actual.substring(15,19) +_Actual.substring(11,15);
			_DiasDelMes[_ContSemana-1]='';
		}

		<!-- Arma la expresión de Fechas a pasar -->
		_DiasDelMes[_ContSemana-1] += _Actual.substring(5,21) + '\n';
			
		if (_Fecha.getDay() == '6') {
			_Ultimo_Dia = _Actual.substring(19,21) + _Actual.substring(15,19) +_Actual.substring(11,15);
			window.document.form_stat.semana.options[_ContSemana-1] = new Option(_ContSemana + 'ª)  ' + _Primer_Dia + ' al ' + _Ultimo_Dia);
			}
		<!-- fin visualización de semanas -->
		
		<!-- Cuando llega a sábado, incrementa contador y pasa al lunes próximo incrementando las horas -->
		if (_Fecha.getDay() == '6') {
			_Fecha.setHours(_Fecha.getHours() + 24);
			_ContSemana++ }
			
		<!-- Pasa al día siguiente -->
		_Fecha.setHours(_Fecha.getHours() + 24)
			
	 	<!-- Actualiza el controlador (_Inicio) del While -->
		_MesActual = _Fecha.getMonth()+1;
		_DiaActual = _Fecha.getDate();
		_Actual = '';
		if (_MesActual < 10) {_Inicio = _Fecha.getFullYear() + '0' + _MesActual} else {_Inicio = _Fecha.getFullYear() + '' + _MesActual}
		if (_DiaActual < 10) {_Inicio += '0' + _DiaActual} else {_Inicio += '' + _DiaActual}
		
	}
// carga los valores de las fechas para las diferentes semanas (una por linea)
		for (var i=0; i<_DiasDelMes.length; i++) {
			window.document.form_stat.semana.options[i].value=_DiasDelMes[i];
		}
}
	

function DiasDelMes(mm,aaaa) { // devuelve los días que trae el mes indicado por mm del año aaaa
	if (mm.valueOf()==1 || mm.valueOf()==3 || mm.valueOf()==5 || mm.valueOf()==7 || mm.valueOf()==8 || mm.valueOf()==10 || mm.valueOf()==12) {
		_DiasDelMes=31;
	}else{
		if (mm.valueOf()==2) {
			if (aaaa.valueOf()%4==0) { // si es año bisiesto
				_DiasDelMes=29;
			}else{
				_DiasDelMes=28;
			}
		}else{
			_DiasDelMes=30;
		}
	}
	return (_DiasDelMes);
}

// establece variables globales para definir las fecha mínima del período
aaaaMin="2000";
mmMin="01";
ddMin="01"

function VerificarFecha(Fecha) {
	// verifica que Fecha este bien definida, retornando true en este caso

	dd=Fecha.substring(0,2);
	mm=Fecha.substring(3,5);
	aaaa=Fecha.substring(6,10);
	estado=true;

// || aaaa.valueOf()<AnioMin.valueOf() || aaaa.valueOf()>AnioMax.valueOf()	
	if (aaaa!=parseInt(aaaa) || aaaa=="") {
		estado=false;
	}else{
		if ( mm=="" || mm.valueOf()<1 || mm.valueOf()>12) {
			estado=false;
		}else{
			dd_maximo=DiasDelMes(mm,aaaa); // DiasDelMes trae el nro. de días del mes mm
			if (dd=="" || dd.valueOf()<1 || dd.valueOf()>dd_maximo) {
				estado=false;
			}
		}
	}
	if (dd.valueOf()<ddMin.valueOf() || mm.valueOf()<mmMin.valueOf() || aaaa.valueOf()<aaaaMin.valueOf()) {
		estado=false;
	}
	return estado;
}

function VerOcultar(Elemento,Visibilidad) {
	window.document.getElementById("Layer1").style.display='none';
	window.document.getElementById("Layer2").style.display='none';
	window.document.getElementById("Layer3").style.display='none';
	window.document.getElementById("Layer4").style.display='none';
	window.document.getElementById("Layer5").style.display='none';
	window.document.getElementById("Layer6").style.display='none';
	window.document.getElementById(Elemento).style.display='';
}

function Procesar() {
	window.document.form_stat.operaciones.value='';
	for (var i = 1; i <= 6; i++) { // evalua las diferentes operaciones (operacion_1 .. operacion_6) cargandolas una por línea en operacion
		if (eval('window.document.form_stat.operacion_'+i+'.checked')=="1") {
			window.document.form_stat.operaciones.value+=eval('window.document.form_stat.operacion_'+i+'.value')+'\n';
		}
	}
	if (window.document.form_stat.operaciones.value=='') {
		alert("Debe indicar al menos una operación");
		VerOcultar("Layer1","visible");
		return false;
	}
	window.document.form_stat.turno.value='';	
	for (var i = 1; i <= 3; i++) { // evalua los diferentes turnos (turno_1 .. turno_3)
		if (eval('window.document.form_stat.turno_'+i+'.checked')=="1") {
			window.document.form_stat.turno.value+=eval('window.document.form_stat.turno_'+i+'.value')+'\n';
		}
	}
	if (window.document.form_stat.turno.value=='') {
		alert("Debe indicar al menos un turno");
		VerOcultar("Layer2","visible");
		return false;
	}

	expresion=''; // arma la expresión que indica la fecha, tendrá tantas lineas como días de consulta abarque la consulta, 
								// el formato de cada linea es Fecha=aaaa/mm/dd
	if (window.document.form_stat.reporte[3].checked) { // Hoy
		window.document.form_stat.tipo_reporte.value="diario";
	
		if (!VerificarFecha(window.document.form_stat.fecha_hoy.value)) {
			alert("Fecha incorrecta (Atención: el año debe ser posterior al "+aaaaMin+")");
			VerOcultar("Layer2","visible");
			return false;
		}else{
			fecha_hoy=window.document.form_stat.fecha_hoy.value;
			expresion="Fecha="+fecha_hoy.substring(6,10)+"/"+fecha_hoy.substring(3,5)+"/"+fecha_hoy.substring(0,2);
		}
	}else{
		if (window.document.form_stat.reporte[0].checked) { // Semanal
			window.document.form_stat.tipo_reporte.value="semanal";

			expresion=window.document.form_stat.semana.options[window.document.form_stat.semana.selectedIndex].value;
		}else{
			if (window.document.form_stat.reporte[1].checked) { // Mensual
				window.document.form_stat.tipo_reporte.value="mensual";
			
				expresion = '';
				mm=window.document.form_stat.mes.options[window.document.form_stat.mes.selectedIndex].value;
				mm++;
				if (mm.valueOf()<10) {
					mm="0"+mm;
				}
				aaaa=window.document.form_stat.anio.options[window.document.form_stat.anio.selectedIndex].value;
				dias=DiasDelMes(mm,aaaa);
				for (var i = 1; i <= dias; i++) {
					expresion += 'Fecha=' + aaaa + '/' + mm + '/';
					if (i < 10) { // agrega un cero para armar el mes con dos cifras y con un salto de linea para separar cada expresion
						expresion += '0' + i + '\n'; 
					}else{
						expresion += i + '\n';
					}
				}
			}else{
				if (window.document.form_stat.reporte[2].checked) { // Anual
					window.document.form_stat.tipo_reporte.value="anual";
				
					expresion = '';
					for (var i = 1; i <= 12; i++) {
						expresion += 'Fecha=' + window.document.form_stat.anio.options[window.document.form_stat.anio.selectedIndex].value + '/';
						if (i < 10) { // agrega un cero para armar el mes con dos cifras y con un salto de linea para separar cada expresion
							expresion += '0' + i + '/$\n'; 
						}else{
							expresion += i + '/$\n';
						}
					}
				}
			}
		}
	}

	window.document.form_stat.fecha.value=expresion; // expresión define las fechas de la consulta
	
	window.document.form_stat.inventario.value=window.document.form_stat.nroinventario.value; // carga el inventario
	window.document.form_stat.tematica.value=window.document.form_stat.clatematica.value; // carga la clasificación temática
	window.document.form_stat.doc.value=""; // inicializa el documento
	if (window.document.form_stat.NroDoc.value!="") {
		window.document.form_stat.doc.value=window.document.form_stat.NroDoc.value; // carga el nro. doc.
	}
	window.document.form_stat.material.value=""; // inicializa el tipo de material
	for (i=0; i<window.document.form_stat.tipomaterial.length; i++) {
		if (window.document.form_stat.tipomaterial[i].checked=="1") {
			window.document.form_stat.material.value=window.document.form_stat.tipomaterial[i].value;
		}
	}
	return true;
}

function Inicializar() {
	window.document.form_cargabibliotecarios.submit();
	window.document.form_cargaterminales.submit();
	CargarUsuario();
	
	comienzo_semana(window.document.form_stat.mes.options[window.document.form_stat.mes.selectedIndex].value,window.document.form_stat.anio.options[window.document.form_stat.anio.selectedIndex].value,1);
}


function VerificarUsuario() {
	NroDoc=window.document.form_stat.NroDoc.value;
	window.document.form_usuario.NroDoc.value=NroDoc;
	if (NroDoc!="") {
		window.document.form_usuario.TipoConsulta.value="VerUsuario";
		window.document.form_usuario.submit()
	}else{
		alert("Complete Nro. de Documento")
	}
}


function ListarUsuarios() {
	window.document.form_usuario.Nombre.value=window.document.form_stat.PpioIndice.value; 
	window.document.form_usuario.TipoConsulta.value="ListarUsuarios"; 
	window.document.form_usuario.submit()
}

function CargarUsuario(NroDoc,AyN) {
	if (!NroDoc) { // cancela e inicializa el lector
		window.document.form_stat.PpioIndice.value="";
		window.document.form_usuario.Nombre.value="";
		AyN="";
		NroDoc="";
	}
	window.document.form_stat.NroDoc.value=NroDoc;
	window.document.form_stat.UsuarioVerificado.value=true;
	window.document.getElementById("celdaUsuario").innerHTML=AyN; // escribe el nombre del usuario seleccionado
}

function CargarBibliotecario(AyN,KEY) { // AyN viene en formato ansi, mientras que KEY es la entrada "pura" que aparece en el diccionario de la base movi
	if (!AyN) { // cancela e inicializa el lector
		AyN="";
		KEY="";
	}
	window.document.getElementById("celdaBibliotecario").innerHTML=AyN; // escribe el nombre del bibliotecario seleccionado
	window.document.form_stat.operador.value=KEY;
}

function CargarTerminal(Ip) {
	if (!Ip) { // cancela e inicializa el lector
		Ip="";
	}
	window.document.getElementById("celdaIp").innerHTML=Ip; // escribe el nombre del bibliotecario seleccionado
	window.document.form_stat.terminal.value=Ip;
}
</script>
<link href="\mp.css" rel="stylesheet" type="text/css">

</head>

<body onload="Inicializar()">
    <div id="head"> 
		<div id="title">CaMPI > Estadísticas (OpenMarcoPolo)</div>
		<div id="logo"><img src="/omp/images/logocampi.gif" width="120" height="54"></div>
    </div> 
    <div id="body_wrapper">
      <div id="body">
					 <div id="all">
								<div class="top"></div>
								<div class="content">
<!--##########################################################-->  
<form action="/omp/cgi-bin/wxis.exe/omp/estadisticas/" method="post" name="form_stat" target="Resultados" onsubmit="return Procesar()">
	<input type="Hidden" name="IsisScript" value="estadisticas/estadistica.xis">
	<input type="hidden" name="operaciones">
	<input type="hidden" name="turno">	
	<input type="hidden" name="fecha">
	<input type="hidden" name="doc">
	<input type="hidden" name="localidad_lector">
	<input type="hidden" name="inventario">
	<input type="hidden" name="tematica">
	<input type="hidden" name="material">
	<input type="hidden" name="operador">
	<input type="hidden" name="terminal">
	<input type="hidden" name="tipo_reporte">

	<table width="596" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#D2E3AC" class="fichaencab">
 	<tr>
		<th width="109" align="center" id="Celda1" style="cursor:hand" onclick="VerOcultar('Layer1','visible')">Operaciones</th>
		<th width="87" align="center" id="Celda2" style="cursor:hand" onclick="VerOcultar('Layer2','visible')">Per&iacute;odo</th>	
		<th width="90" align="center" id="Celda3" style="cursor:hand" onclick="VerOcultar('Layer3','visible')">Lector</th>
		<th width="96" align="center" id="Celda4" style="cursor:hand" onclick="VerOcultar('Layer4','visible')">Material</th>
		<th width="113" align="center" id="Celda5" style="cursor:hand" onclick="VerOcultar('Layer5','visible')">Bibliotecario</th>
		<th width="96" align="center" id="Celda6" style="cursor:hand" onclick="VerOcultar('Layer6','visible')">Ayuda</th>
	</tr>
	</table>
	<table width="596" border="0" align="center" cellpadding="1" cellspacing="0"  bgcolor="#BEE4FF" class="fichadatos">
	<tr>
    <td height="244" colspan="6" valign="top">
		<div id="Layer1">
			<table width="100%" align="center">
			<tr>
				<td colspan="3"><h3>Indique las operaciones para analizar</h3></td>
			</tr>
			<tr>
				<td width="19%">&nbsp;</td>
				<td width="36%">
				<input type="checkbox" name="operacion_1" value="Operacion=PRESTAMO$"><a href="JavaScript:window.document.form_stat.operacion_1.click()">Préstamos</a></td>
				<td width="45%">
				<input type="checkbox" name="operacion_2" value="Operacion=DEVOLUCION_MOROSA"><a href="JavaScript:window.document.form_stat.operacion_2.click()">Devoluciones Morosas</a></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
				<input type="checkbox" name="operacion_3" value="Operacion=DEVOLUCION$"><a href="JavaScript:window.document.form_stat.operacion_3.click()">Devoluciones</a></td>
				<td>
				<input type="checkbox" name="operacion_6" value="Operacion=PRESTAMO_DOMICILIO"><a href="JavaScript:window.document.form_stat.operacion_6.click()">Préstamo a Domicilio</a></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
				<input type="checkbox" name="operacion_5" value="Operacion=SANCION"><a href="JavaScript:window.document.form_stat.operacion_5.click()">Sanciones</a></td>
				<td style="display:none">
				<input type="checkbox" name="operacion_4" value="Operacion=PRESTAMO_SALA"><a href="JavaScript:window.document.form_stat.operacion_4.click()">Préstamo en Sala</a></td>
			</tr>
			</table>
			</div>
		<div id="Layer2" style="display:none">
		<table width="100%" align="center">
    <tr>
      <td colspan="5"><h3>Indique el período del análisis</h3></TD>
    </TR>
    <tr>
      <td width="2%">&nbsp;</TD>
      <td width="30%" rowspan="4"><input type="checkbox"  name="turno_1" value="Turno=MATUTINO">        
        <a href="JavaScript:window.document.form_stat.turno_1.click()">Turno Matutino<br>
        </a>
        <input type="checkbox" name="turno_2" value="Turno=VESPERTINO">
					<a href="JavaScript:window.document.form_stat.turno_2.click()">Turno Vespertino</a><br>
        <input type="checkbox" name="turno_3" value="Turno=$">
					<a href="JavaScript:window.document.form_stat.turno_3.click()">Ambos Turnos</a></TD>
      <td width="15%" align="center">Año</TD>
      <td width="25%" align="center">Mes</TD>
      <td width="28%" align="center">Semanas</TD>
    </TR>
    <tr>
      <td>&nbsp;</TD>
      <td align="center">
          <select size="1" name="anio" 
	onchange="comienzo_semana(window.document.form_stat.mes.options[window.document.form_stat.mes.selectedIndex].value,window.document.form_stat.anio.options[window.document.form_stat.anio.selectedIndex].value,1)">
					    <option value="2010">2010</OPTION>
					    <option value="2011">2011</OPTION>
					    <option value="2012">2012</OPTION>
					    <option value="2013">2013</OPTION>					
					    <option value="2014" selected>2014</OPTION>
					    <option value="2015">2015</OPTION>
				    </SELECT></TD>
      <td align="center">
         <select size="1" name="mes" 
	onchange="comienzo_semana(window.document.form_stat.mes.options[window.document.form_stat.mes.selectedIndex].value,window.document.form_stat.anio.options[window.document.form_stat.anio.selectedIndex].value,1)">
					    <option value="0" selected>Enero</OPTION>
					    <option value="1">Febrero</OPTION>
					    <option value="2">Marzo</OPTION>
					    <option value="3">Abril</OPTION>
					    <option value="4">Mayo</OPTION>
					    <option value="5">Junio</OPTION>
					    <option value="6">Julio</OPTION>
					    <option value="7">Agosto</OPTION>
					    <option value="8">Septiembre</OPTION>
					    <option value="9">Octubre</OPTION>
					    <option value="10">Noviembre</OPTION>
					    <option value="11">Diciembre</OPTION>
          </SELECT></TD>
      <td align="center">
          <select size="1" name="semana">
          </SELECT></TD>
		</TR>
    <tr>
      <td>&nbsp;</TD>
      <td rowspan="2" align="center">Reporte</TD>
      <td colspan="2" align="center"><div align="left"><br>
            <input name="reporte" type="radio" value="semanal" checked>
            <a href="JavaScript:window.document.form_stat.reporte[0].click()">Semanal</a>
            <input name="reporte" type="radio" value="mensual">
            <a href="JavaScript:window.document.form_stat.reporte[1].click()">Mensual</a>
            <input name="reporte" type="radio" value="anual">
            <a href="JavaScript:window.document.form_stat.reporte[2].click()">Anual</a></div></TD>
      </TR>
    <tr>
      <td height="22">&nbsp;</td>
      <td colspan="2" align="center"><div align="left">
        <input name="reporte" type="radio" value="diario">
          <a href="JavaScript:window.document.form_stat.reporte[3].click()">D&iacute;a</a>
          <input name="fecha_hoy" type="text" onFocus="window.document.form_stat.reporte[3].click()" size="10">
          (dd/mm/aaaa)</div></TD>
      </TR>
    </TABLE>

	  </div>
		<div id="Layer3" style="display:none">

		<table width="100%" height="236" align="center">
    <tr>
      <td colspan="2"><h3>Identifique al lector</h3></TD>
		  </TR>
    <tr>
      <td width="294" height="8" valign="bottom"><center>Nro. Documento:
            <input name="NroDoc" size="12" onchange="window.document.form_stat.UsuarioVerificado=false">
						<input type="Hidden" name="UsuarioVerificado" value="true">
             <br>
        <a href="JavaScript:VerificarUsuario()">Verificar</a>&nbsp;&nbsp;<a href="JavaScript:CargarUsuario()">Limpiar</a><br>
      </center></TD>
      <td width="284">
				<center>Escriba las primeras letras del índice:<br>
				<input type="Text" name="PpioIndice" size="20" onkeyup="ListarUsuarios()"></center>
			</TD>
    </TR>
    <tr>
      <td height="22" valign="bottom"><center>
         Usuario
      </center></TD>
      <td rowspan="2" valign="top">
				<iframe name="listado" id="listadoUsuario" width="290" height="150" marginheight="0" scrolling="auto" frameborder="1"></iframe></TD>
    </TR>
    <tr>
      <td height="125" id="celdaUsuario" align="center" valign="top">&nbsp;</TD>
      </TR>
		</TABLE>
		</div>
		
		<div id="Layer4" style="display:none">
		<table width="100%" align="center">
    <tr>
      <td colspan="4"><h3>Indique el material a considerar en el an&aacute;lisis</h3></td>
      </tr>
    <tr>
      <td width="21%">        <div align="right">Inventario<br>        
            <a href="JavaScript:window.document.form_stat.nroinventario.value=''; void(0)">Limpiar</a> </div></td>
      <td width="33%" valign="middle"><input type="text" name="nroinventario" size="20">        </td>
      <td width="19%" rowspan="5"><center>
        Tipo de material<br>            
        <a href="JavaScript:window.document.form_stat.tipomaterial[0].click(); window.document.form_stat.tipomaterial[0].checked=false; void(0)">Limpiar</a>
      </center></td>
      <td width="27%" rowspan="5"><input type="radio" value="TEXTO" name="tipomaterial">        <a href="JavaScript:window.document.form_stat.tipomaterial[0].click()">Libros</a>        <br>
        <input type="radio" value="[COMPUTER FILE]" name="tipomaterial">        <a href="JavaScript:window.document.form_stat.tipomaterial[1].click()">Archiv.Comp.</a>        <br>
        <input type="radio" value="[GRAPHIC]" name="tipomaterial">        
        <a href="JavaScript:window.document.form_stat.tipomaterial[2].click()">Gráficos<br>
        </a>
        <input type="radio" value="[MICROFORM]" name="tipomaterial">        <a  href="JavaScript:window.document.form_stat.tipomaterial[3].click()">Microforma</a><br>        
        <input type="radio" value="[SOUND RECORDING]" name="tipomaterial">        <a href="JavaScript:window.document.form_stat.tipomaterial[4].click()">Reg.Sonoro</a><br>
        <input type="radio" value="[VIDEORECORDING]" name="tipomaterial">        <a  href="JavaScript:window.document.form_stat.tipomaterial[3].click()">Videos</a><br>
        <input type="radio" value="[REALIA]" name="tipomaterial">        <a  href="JavaScript:window.document.form_stat.tipomaterial[3].click()">Realia</a></td>
    </tr>
    <tr>
      <td><div align="right">Tem&aacute;tica<br>          
            <a href="JavaScript:window.document.form_stat.clatematica.value=''; void(0)">Limpiar</a> </div></td>
      <td valign="middle"><input type="text" name="clatematica" size="20">        </td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      </tr>
  </table>  
		</div>
		
		<div id="Layer5" style="display:none">
		  <table width="100%" align="center">
			<tr>
			  <td colspan="4"><h3>Indique el bibliotecario o terminal</h3></td>
			  </tr>
			<tr>
			  <td width="12%">&nbsp;</td>
			  <td width="48%"><center>
			    <font face="Arial">Bibliotecario</font>
			    </center></td>
			  <td width="24%"><center>
			    <font face="Arial">Terminal</font>
			    </center></td>
			  <td width="16%">&nbsp;					</td>
			</tr>
			<tr>
			  <td align="right"><a href="JavaScript:CargarBibliotecario()">Limpiar</a></td>
			  <td><center>
			    <iframe name="bibliotecarios" width="100%" marginheight="0" scrolling="auto" frameborder="1">
				</iframe>
			    </center></td>
			  <td><center>
			    <iframe name="terminales" width="100%"  marginheight="0" scrolling="auto" frameborder="1">
				</iframe>
			    </center></td>
			  <td><a href="JavaScript:CargarTerminal()">Limpiar</a></td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td id="celdaBibliotecario">&nbsp;</td>
			  <td id="celdaIp">&nbsp;</td>
			  <td>&nbsp;</td>
			  </tr>
			</table>
		</div>
		
		<div id="Layer6" style="display:none">
		<table width="100%" align="center">
		<tr>
		  <td><h3>Indicaciones para la operación del módulo</h3></td>
		</tr>
		<tr>
		  <td> Seleccione las diferentes alternativas que el módulo de estadística ofrece para analizar la circulación de los materiales.<br>
  La opción <b>Operaciones</b> permite indicar los movimientos a evaluar, en <b>Fecha</b> <u>debe</u> señalar el plazo y el turno de la consulta.<br>
  Puede además indicar filtros opcionales para ajustar los resultados a un <b>Material</b>, <b>Operador</b> o <b>Lector</b> determinado.<br>
  Debe en todos los casos dejar indicado al menos una operación, la fecha y el turno de la consulta.</td>
		</tr>
		</table>
	  </div>
	 </td>
  </tr>
  <tr>
    <td colspan="6" valign="top"><input type="submit" name="Submit" value="Enviar">      </tr>
</table>

</form>

<form name="form_usuario" action="/omp/cgi-bin/wxis.exe/omp/estadisticas/" target="listado">
	<input type="Hidden" name="IsisScript" value="estadisticas/consulta_usuario.xis">
	<input type="Hidden" name="Nombre">
	<input type="Hidden" name="NroDoc">
	<input type="Hidden" name="TipoConsulta">
</form>

<form name="form_cargabibliotecarios" action="/omp/cgi-bin/wxis.exe/omp/estadisticas/" target="bibliotecarios">
	<input type="Hidden" name="IsisScript" value="estadisticas/consulta_bibliotecarios.xis">
	<input type="Hidden" name="Nombre">
</form>

<form name="form_cargaterminales" action="/omp/cgi-bin/wxis.exe/omp/estadisticas/" target="terminales">
	<input type="Hidden" name="IsisScript" value="estadisticas/consulta_terminales.xis">
	<input type="Hidden" name="Nombre">
</form>

	  
<!--########################################################## -->
								</div>
								<div class="bottom"></div>
						</div>
        <div class="clearer">
</div>
      </div>
      <div class="clearer">
	  </div>
    </div>
    <div id="end_body"></div>

           <div id="footer"></div> 
  </body>
  
<?php
}else{
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=/omp/login_form.php>";} ?>
</html>
