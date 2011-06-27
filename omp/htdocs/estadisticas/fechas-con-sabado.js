function comienzo_semana(Mes, Anio, Dia_Comienzo_Semana) {


   <!-- alert('Mes ' + Mes + '\nAño ' + Anio + '\nDia ' + Dia_Comienzo_Semana)-->
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
  if (_MesUltimoDia < 10) {_Fin = _UltimoDia.getYear() + '0' + _MesUltimoDia} else {_Fin = _UltimoDia.getYear() + '' + _MesUltimoDia}
  if (_DiaUltimoDia < 10) {_Fin += '0' + _DiaUltimoDia} else {_Fin += '' + _DiaUltimoDia}
  
  var _MesPrimerDia = _Fecha.getMonth()+1
  var _DiaPrimerDia = _Fecha.getDate()
  var _Inicio = null
  if (_MesPrimerDia < 10) {_Inicio = _Fecha.getYear() + '0' + _MesPrimerDia} else {_Inicio = _Fecha.getYear() + '' + _MesPrimerDia}
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
		
		if (_MesActual < 10) {_Actual += '^s' + _ContSemana + '^fFecha=' +  _Fecha.getYear() + '/0' + _MesActual} else {_Actual += '^s' + _ContSemana + '^fFecha=' + _Fecha.getYear() + '/' + _MesActual}    
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
		if (_MesActual < 10) {_Inicio = _Fecha.getYear() + '0' + _MesActual} else {_Inicio = _Fecha.getYear() + '' + _MesActual}
		if (_DiaActual < 10) {_Inicio += '0' + _DiaActual} else {_Inicio += '' + _DiaActual}
		
	}
// carga los valores de las fechas para las diferentes semanas (una por linea)
		for (var i=0; i<_DiasDelMes.length; i++) {
			window.document.form_stat.semana.options[i].value=_DiasDelMes[i];
		}
}
	
