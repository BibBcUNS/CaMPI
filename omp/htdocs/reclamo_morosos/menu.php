<?php
session_start(); 
header('Content-Type: text/html; charset=ISO-8859-1');
if (isset($_SESSION["s_username"])
	&& isset($_SESSION["s_permisos"])
	&& in_array('reclamo_morosos' , $_SESSION["s_permisos"])) {
?>
<html>
  <head>
    <title>CaMPI - Administración</title>
    <link rel="stylesheet" type="text/css" href="/omp/css/style.css">
		<style>
	select,input {vertical-align:middle;}
table td {border-width:0px; border-style:solid; border-color:#0099FF;}
	table th {border-color:white; border-style:solid; border-width:5px; -moz-border-radius:12px; padding: 5px 0px;}
	</style>
	
	<!--script language=javascript type=text/javascript src=js/popup_calendar.js-->
  </head>
  
  	<script>
		function enable_button(btn, texto){
			btn.disabled = '';
			if (texto!=null) {
				btn.value = texto;
			}
		}
		function disable_button(btn,texto){
			btn.disabled = 'disabled';
			if (texto!=null) {
				btn.value = texto;
			}
		}
		function aaa() {
				alert('hola');
		}
	</script>

  <body>
  <div id="head"> 
		<div id="title">CaMPI > Reclamo a morosos</div>
		<div id="logo"><img src="/omp/images/logocampi.gif" width="120" height="54"></div>
    </div> 
	
	<div id="body_wrapper">
      <div id="body">
					 <div id="all">
					 			<div class="top"></div>
								<div class="content">
   
<!--###################################################-->	



<center>
 
<table>
<tr>
    <th>Circulación Bibliográfica</th>
</tr>
<tr>
	<td align="left">
        <form name="form_listados" method="POST" action="/omp/cgi-bin/wxis.exe/omp/reclamo_morosos/">
        <input type="hidden" name="IsisScript" value="reclamo_morosos/listados.xis">
        <input type="Hidden" name="Orden" value="Prestamo">	
        <table border="1" bordercolor="#000000" width="100%" cellpadding="2"><tr><td colspan="4">
            <input type="radio" value="morosos" name="opcion">Listado de morosos<br>
            </td></tr>
            <tr><td>
            Desde: </td><td>
            Dia:<select name=diad>
                    
                    <option value=01 selected>01</option>
                    <option value=02>02</option>
                    <option value=03>03</option>
                    <option value=04>04</option>
                    <option value=05>05</option>
                    <option value=06>06</option>
                    <option value=07>07</option>
                    <option value=08>08</option>
                    <option value=09>09</option>
                    <option value=10>10</option>
                    <option value=11>11</option>
                    <option value=12>12</option>
                    <option value=13>13</option>
                    <option value=14>14</option>
                    <option value=15>15</option>
                    <option value=16>16</option>
                    <option value=17>17</option>
                    <option value=18>18</option>
                    <option value=19>19</option>
                    <option value=20>20</option>
                    <option value=21>21</option>
                    <option value=22>22</option>
                    <option value=23>23</option>
                    <option value=24>24</option>
                    <option value=25>25</option>
                    <option value=26>26</option>
                    <option value=27>27</option>
                    <option value=28>28</option>
                    <option value=29>29</option>
                    <option value=30>30</option>
                    <option value=31>31</option>
                    </select>
                    </td><td>
                    Mes:
                    <select name=mesd>
                    <option value=01 selected>01</option>
                    <option value=02>02</option>
                    <option value=03>03</option>
                    <option value=04>04</option>
                    <option value=05>05</option>
                    <option value=06>06</option>
                    <option value=07>07</option>
                    <option value=08>08</option>
                    <option value=09>09</option>
                    <option value=10>10</option>
                    <option value=11>11</option>
                    <option value=12>12</option>
                    </select>
                    </td>
                    <td>
                    Año:
                    <select name=anod>
                    <option value=2000>2000</option>
                    <option value=2001>2001</option>
                    <option value=2002>2002</option>
                    <option value=2003>2003</option>
                    <option value=2004>2004</option>
                    <option value=2005>2005</option>
                    <option value=2006>2006</option>
                    <option value=2007>2007</option>
                    <option value=2008>2008</option>
                    <option value=2009>2009</option>
                    <option value=2010>2010</option>
                    <option value=2011>2011</option>
                    <option value=2012>2012</option>
                    <option value=2013>2013</option>
		            <option value=2014>2014</option>
		            <option value=2015>2015</option>
		            <option value=2016>2016</option>
                    <option value=2017>2017</option>
                    <option value=2018>2018</option>
                    <option value=2019>2019</option>
                    <option value=2020>2020</option>
                    <option value=2021>2021</option>
                    <option value=2022>2022</option>
                    <option value=2023>2023</option>
                    <option value=2024 selected>2024</option>
                    <option value=2025>2025</option>
                    <option value=2026>2026</option>
                    <option value=2027>2027</option>
                    </select>
                    </td></tr>
                    <tr><td>
                    Hasta: </td><td>Dia:<select name=diaf>
                    
                    <option value=01 selected>01</option>
                    <option value=02>02</option>
                    <option value=03>03</option>
                    <option value=04>04</option>
                    <option value=05>05</option>
                    <option value=06>06</option>
                    <option value=07>07</option>
                    <option value=08>08</option>
                    <option value=09>09</option>
                    <option value=10>10</option>
                    <option value=11>11</option>
                    <option value=12>12</option>
                    <option value=13>13</option>
                    <option value=14>14</option>
                    <option value=15>15</option>
                    <option value=16>16</option>
                    <option value=17>17</option>
                    <option value=18>18</option>
                    <option value=19>19</option>
                    <option value=20>20</option>
                    <option value=21>21</option>
                    <option value=22>22</option>
                    <option value=23>23</option>
                    <option value=24>24</option>
                    <option value=25>25</option>
                    <option value=26>26</option>
                    <option value=27>27</option>
                    <option value=28>28</option>
                    <option value=29>29</option>
                    <option value=30>30</option>
                    <option value=31>31</option>
                    </select>
                    </td>
                    <td>
                    Mes:
                    <select name=mesf>
                    <option value=01 selected>01</option>
                    <option value=02>02</option>
                    <option value=03>03</option>
                    <option value=04>04</option>
                    <option value=05>05</option>
                    <option value=06>06</option>
                    <option value=07>07</option>
                    <option value=08>08</option>
                    <option value=09>09</option>
                    <option value=10>10</option>
                    <option value=11>11</option>
                    <option value=12>12</option>
                    </select>
                    </td>
                    <td>
                    Año:
                    <select name=anof>
                    <option value=2000>2000</option>
                    <option value=2001>2001</option>
                    <option value=2002>2002</option>
                    <option value=2003>2003</option>
                    <option value=2004>2004</option>
                    <option value=2005>2005</option>
                    <option value=2006>2006</option>
                    <option value=2007>2007</option>
                    <option value=2008>2008</option>
                    <option value=2009>2009</option>
                    <option value=2010>2010</option>
                    <option value=2011>2011</option>
                    <option value=2012>2012</option>
                    <option value=2013>2013</option>
		            <option value=2014>2014</option>
		            <option value=2015>2015</option>
		            <option value=2016>2016</option>
                    <option value=2017>2017</option>
                    <option value=2018>2018</option>
                    <option value=2019>2019</option>
                    <option value=2020>2020</option>
                    <option value=2021>2021</option>
                    <option value=2022>2022</option>
                    <option value=2023>2023</option>
                    <option value=2024 selected>2024</option>
                    <option value=2025>2025</option>
                    <option value=2026>2026</option>
                    <option value=2027>2027</option>
                    
                    </select>
        </td></tr></table>
        <input type="radio" value="prestamos" name="opcion">Préstamos del día (Estadística)<br>
        <input type="radio" value="id_recibos" name="opcion" checked>Devoluciones del día<br>
        <center  style="font-size : x-small;">
			Ordenamiento:
			<select onChange="window.document.form_listados.Orden.value=this.value;" style="font-size : xx-small;">
			<option value="Prestamo" selected>Nro. Papeleta</option>
			<option value="Devolucion">Cronológico</option>							
			</select>
        </center>
        <input type="radio" value="circulante" name="opcion">Préstamos en circulaci&oacute;n
		<br />
		<center  style="font-size : x-small;">
			Ordenamiento:
			<select name="orden" style="font-size : xx-small;">
			<option value="mfn" selected>Nº Movimiento</option>
			<option value="inv">Nº Inventario</option>
			<option value="nom">Lector</option>	
			</select>
        </center>
		<br />
        <input type="submit" value="Enviar" name="B1">
        <input type="reset" value="Restablecer" name="B2"> 
        </form>
    </td>
</tr>
</table>

<!--###################################################-->		
<br><br> 
								</div>
								<div class="bottom"></div>
						</div>
        <div class="clearer"></div>
      </div>
      <div class="clearer"></div>
    </div>
    <div id="end_body"></div>
		<div id="footer"></div>
  </body>
</html>
<?php
}else{
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=/omp/login_form.php>";
}
?>

</html>

