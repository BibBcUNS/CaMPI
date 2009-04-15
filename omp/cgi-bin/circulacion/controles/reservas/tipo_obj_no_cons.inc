	<!--      input (v8000): parte_id
		output (v8080): tipo de objeto distinto de CONS
		el resultado es "error" si no existe dicho tipo
	-->
	
	<!-- lista de inventarios de la parte -->
	<field action=replace tag=8000 split=occ><pft>v8000^b,'_',v8000^c,'_',v8000^p</pft></field>
	
	<field action=replace tag=8005 split=occ><pft>
		ref(['partes']l(['partes']v8000),(v2/))
	</pft></field>
	
	<!-- lista de tipo de objetos correspondiente a c/inventario -->
	<field action=replace tag=8010 split=occ><pft>
		(ref(['exist']l(['exist']v8005),v987/))
	</pft></field>

	<!-- lista de tipo de objetos excluyendo CONS -->
	<field action=replace tag=8015 split=occ><pft>
		(if v8010<>'CONS ' then v8010/ fi)
	</pft></field>

	<field action=replace tag=8080><pft>
		if nocc(v8015)=0
			then
				'Error: Tipo de objeto no definido para la parte'
			else
				v8015[1] fi
	</pft></field>