/* =============================================================================
 * Catalis - puntuacion.js
 *
 * Generación de la puntuación para algunos campos de datos MARC 21.
 *
 * (c) 2003-2005  Fernando J. Gómez - CONICET - INMABB
 *  Véase el archivo LICENCIA.TXT incluido en la distribución de Catalis
 * =============================================================================
 */

// Regex con lista de campos en los que se debe realizar puntuación automáitca
var AUTO_PUNCT_TAGS = /020.|[167]00.|[167]10.|[1678]11.|24[05].|250.|254.|26[04].|300.|310.|321.|4[049]0.|500.|50[0124]a|510.|670.|700[^w]|7[07][03][^xz]|830./;

// -----------------------------------------------------------------------------
function updatePunctuation(field, tag_code)
// Actualiza los datos de un campo del formulario, con la puntuación generada
// automáticamente.
// -----------------------------------------------------------------------------
{
	// Early return si el campo no esta en la lista.
	if (!AUTOMATIC_PUNCTUATION || tag_code.search(AUTO_PUNCT_TAGS) == -1){
		return;
	}

	var tag = field.tag;
	// Quitamos subcampos vacios
	//var re_empty = /\^\w(?=\^|$)/g
	var subfields = getSubfields(field); //.replace(re_empty,"");
	
	var updatedSubfields = punctuation(tag,subfields);

	//alert(newSubfields.replace(/\^/g,"\n"));
	//var container = document.getElementById("recordContainer");
	//var newField = createField(tag,ind,newSubfields);
	//alert(newField.innerHTML);
	
	// Usamos try...catch debido a que al pasar de un subcampo a otro del mismo
	// campo estaba generando un error.
	//try {container.replaceChild(newField,field)}
	//catch(err) {};
	
	
	// Actualizamos solamente los boxes no vacíos.
	// ATENCION: ¿qué se entiende por "vacío"?
	var values = updatedSubfields.substr(2).split(/\^\w/);
	// ATENCION: usamos substr(2) porque de lo contrario en Mozilla values[0] es vacío
	var boxes = field.getElementsByTagName("tr");
	var j = -1;
	for (var i=0; i < boxes.length; i++) {
		var box = childSubfieldBox(boxes[i]);
		if ( box.value.search(REGEX_EMPTY_SUBFIELD) == -1 ) {
			j = j + 1;
			box.value = values[j];
		}
	}
}


// -----------------------------------------------------------------------------
function punctuation(tag,sf)
// Genera la puntuación para un campo de datos.
// TO-DO: seguir agregando campos (e.g. notas)
//
// Fuentes: varias. Entre ellas:
//   http://tpot.ucsd.edu/Cataloging/Bib_records/punct.html
//   http://www.slc.bc.ca/cheats/marcpunc.htm
//   http://www.itcompany.com/inforetriever/punctuation.htm
//   http://ublib.buffalo.edu/libraries/units/cts/about/FINALPUNCTUATION.HTML
// -----------------------------------------------------------------------------
{
	// Como primer paso, eliminamos la puntuación presente (interna y final),
	// usando una regexp adecuada para cada campo.

	// ATENCION: usar el "g" de global (al menos en los casos de subcampos repetibles)
	// ATENCION: para la puntuación final de los campos no debe tenerse en cuenta
	// a los subcampos numéricos.
	switch (tag) {		
		case "020" :
			var re_clean = / :(?=\^|$)/g;
			sf = sf.replace(re_clean, "");
			sf = sf.replace(/\^c/, " :^c");
		break;
		
		case "100" :
			var re_clean = /( *[:\/]|,| \.)(?=\^|$)/g
            sf = sf.replace(re_clean, "");

			// Inicio colocando una coma antes de cada subcampo excepto los que pueden tener
			// otros caracteres de puntuacion (. :). 
			// Si no es correcto luego se elimina.
			const chars_to_replace_100 = /\^b|\^c|\^d|\^e|\^f|\^g|\^h|\^j|\^l|\^m|\^n|\^o|\^p|\^q|\^r|\^s|\^v|\^x|\^y|\^z|\^6|\^7|\^8|\^0/g;

			sf = sf.replace(chars_to_replace_100, match => ',' + match)

			if( !sf.includes(".^k") ){
				sf = sf.replace(/\^k/, ",^k");
			}

			if( !sf.includes(".^t")){
				sf = sf.replace(/\^t/, ",^t");
			}

			// Ticket #13186
			// Subcampo ^a
			// Sin puntuación cuando le sigue ^b
			if (/\^a[^^]*\^b/.test(sf)){
				sf = sf.replace(",^b", "^b")
			}
			// Sin puntuación cuando le sigue ^0
			if (/\^a[^^]*\^0/.test(sf)){
				sf = sf.replace(",^0", "^0")
			}
			// Sin puntuación cuando le sigue un ^c que inicia con parentesis
			if (/\^a[^^]*,\^c\(/.test(sf) ){
				sf = sf.replace(",^c", "^c");
			}

			// Subcampo ^b 
			// Finaliza en coma cuando le sigue otro subcampo (coma ya colocada anteriormente)

			// Subcampo ^c 
			// Sin puntuación cuando es el último
			// Sin puntuación cuando le sigue ^q
			if (/\^c[^^]*,\^q/.test(sf)  ){
				sf = sf.replace(",^q", "^q");
			}
			// Punto cuando le sigue ^k
			if (/\^c[^^]*\^k/.test(sf) ){
				if ( sf.includes(",^k") ){
					sf = sf.replace(",^k", ".^k")
				}else if( ! sf.includes(".^k") ){
					sf = sf.replace("^k", ".^k");
				}
			}
			
			// Subcampo d
			// Sin puntuación cuando es el último

			// Sin puntuación cuando le sigue un ^c que inicia con paréntesis
			if (/\^d[^^]*,\^c\(/.test(sf) ){
				sf = sf.replace(",^c", "^c");
			}
			// Sin puntuación cuando le sigue ^x
			if (/\^d[^^]*,\^x/.test(sf) ){
				sf = sf.replace(",^x", "^x");
			}
			// Sin puntuación cuando le sigue ^0
			if (/\^d[^^]*,\^0/.test(sf) ){
				sf = sf.replace(",^0", "^0");
			}
			// Punto cuando le sigue ^t
			if (/\^d[^^]*\^t/.test(sf) ){
				if ( sf.includes(",^t") ){
					sf = sf.replace(",^t", ".^t")
				}else if( ! sf.includes(".^t") ){
					sf = sf.replace("^t", ".^t");
				}
			}

		break;

		case "111" :
		case "611" : //ATENCION: arreglar problema con 611$2 
		case "711" :
			var re_clean1 = /( :|,|\)|\)? \.)(?=\^|$)/g;
			sf = sf.replace(re_clean1, "");
			var re_clean2 = /(\^[ndc])\(/
			sf = sf.replace(re_clean2, "$1");
			
			sf = sf.replace(/\^q/, " .^q");
			sf = sf.replace(/\^n/, "^n(");
			var aux = (sf.search(/\^n/) != -1) ? " :^d" : "^d(";
			sf = sf.replace(/\^d/,aux);
			var aux = (sf.search(/\^[nd]/) != -1) ? " :^c" : "^c(";
			sf = sf.replace(/\^c/,aux);
			if (sf.search(/\^[ndc]/) != -1) {
				sf = sf + ")";
			}
			
			var re_end = /([^\.\-\?\]\)])$/;
			sf = sf.replace(re_end, "$1 .");
		break;
			
		case "240" :
			var re_clean = / \.(?=\^|$)/g;
			sf = sf.replace(re_clean, "");
			sf = sf.replace(/\^l/g, " .^l");
		break;
			
		case "245" :
			var re_clean = /( *[:\/]|,| \.)(?=\^|$)/g;
			sf = sf.replace(re_clean, "");
			sf = sf.replace(/([^;=])\^b/, "$1 :^b");
			sf = sf.replace(/ *([;=])\^b/, " $1^b"); // corrijo espacio
			sf = sf.replace(/\^c/, " /^c");
			sf = sf.replace(/\^n/g, " .^n");
			var aux = (sf.search(/\^n/) != -1) ? "," : " .";
			sf = sf.replace(/\^p/g, aux + "^p");
			var re_end = /([^\.])$/;
			sf = sf.replace(re_end, "$1 .");
		break;
			
		case "250" :
			var re_clean = /( *\/| \.)(?=\^|$)/g;
			sf = sf.replace(re_clean, "");
			sf = sf.replace(/([^=,])\^b/, "$1 /^b");
			var re_end = /([^\.])$/;
			sf = sf.replace(re_end, "$1 .");
		break;
			
		case "254" :
			var re_clean = / \.(?=\^|$)/g;
			sf = sf.replace(re_clean, "");
			sf = sf.replace(/$/, " .");
		break;
			
		case "260" :
			// ATENCION: paréntesis al final de $a o $b no deben eliminarse!
			var regex = "("
					  + " *[:;]"  // zero or more spaces followed by a colon or semicolon,
					  + "|[,\)]"  // or a comma or right parenthesis,
					  + "| \."    // or a space followed by a period
					  + ")"
					  + "(?="     // lookahead
					  + "\\^"     // for subfield delimiter
					  + "|$)";    // or end of string
			var re_clean1 = new RegExp(regex,"g");   // Original: /( *[:;]|[,\)]| \.)(?=\^|$)/g;
			sf = sf.replace(re_clean1, "");
			var re_clean2 = /(\^[efg])\(/;
			sf = sf.replace(re_clean2,"$1");
			sf = sf.replace(/\^a/g, " ;^a");
			sf = sf.replace(/\^b/g, " :^b");
			sf = sf.replace(/\^c/, ",^c");
			sf = sf.replace(/\^e/, "^e(");
			var aux = (sf.search(/\^e/) != -1) ? " :^f" : "^f(";
			sf = sf.replace(/\^f/,aux);
			var aux = (sf.search(/\^[ef]/) != -1) ? ",^g" : "^g(";
			sf = sf.replace(/\^g/,aux);
			if (sf.search(/\^[efg]/) != -1) {
				sf = sf + ")";
			}
			var re_end = /([^\.\)\?\]\-])$/;
			sf = sf.replace(re_end,"$1 .");
		break;
			
		case "300" :
		// ATENCION: Ends with a period if there is a 4XX in the record; otherwise it ends
		// with a period unless another mark of punctuation or a closing parentheses is present.
		// TO-DO: punto final en abreviaturas, como "p." ?
		// ---------------------------------------------------------
			var re_clean = /( *[:;+]|,| \.)(?=\^|$)/g;
			sf = sf.replace(re_clean, "");
			sf = sf.replace(/\^b/, " :^b");
			sf = sf.replace(/\^c/, " ;^c");
			sf = sf.replace(/\^e/, " +^e");
			sf = sf.replace(/\^c(\d+) ?cm\.?/,"^c$1 cm.");
			
			var re_end = /([^\.\)\?\]\-])$/;
			sf = sf.replace(re_end, "$1 .");
		break;
			
		case "310" :
		case "321" :
			var re_clean = /,(?=\^|$)/g;
			sf = sf.replace(re_clean, "");
			sf = sf.replace(/\^b/, ",^b");
		break;

		case "400" :
		case "500" :
		case "700" :
			
			var re_clean = /( *[:\/]|,| \.)(?=\^|$)/g
            sf = sf.replace(re_clean, "");

			let chars_to_replace_700 = "^b,^c,^d,^e,^f,^g,^h,^j,^l,^m,^n,^o,^p,^q,^r,^s,^t,^v,^x,^y,^z,^6,^7,^8,^w";

			// Inicio colocando una coma. Si es incorrecto luego se elimina.
			chars_to_replace_700.split(",").forEach((e)=>{
				sf = sf.replaceAll(e, ","+e)
			});


			if( !sf.includes(".^k") ){
				sf = sf.replace(/\^k/, ",^k");
			}

			if( !sf.includes(".^0") ){
				sf = sf.replace(/\^0/, ",^0");
			}

			// IMPORTANTE: Tener en cuenta que a continuacion todos los sf parten de la edicion anterior 
			// en la cual se coloca una coma luego de cada subcampo.

			// Ticket #13186
			// Subcampo ^a
			// Sin puntuación cuando le sigue ^b
			if (/\^a[^^]*\^b/.test(sf)){
				sf = sf.replace(",^b", "^b")
			}
			// Sin puntuación cuando le sigue ^0
			if (/\^a[^^]*\^0/.test(sf)){
				sf = sf.replace(",^0", "^0")
			}
			// Sin puntuación cuando le sigue un ^c que inicia con parentesis
			if (/\^a[^^]*,\^c\(/.test(sf) ){
				sf = sf.replace(",^c", "^c");
			}

			// Subcampo ^b
			// Finaliza en coma cuando le sigue otro subcampo

			// Subcampo ^c
			// Sin puntuación cuando es el último

			// Sin puntuación cuando le sigue ^q
			if (/\^c[^^]*,\^q/.test(sf)  ){
				sf = sf.replace(",^q", "^q");
			}

			// Punto cuando le sigue ^k
			if (/\^c[^^]*\^k/.test(sf) ){
				if ( sf.includes(",^k") ){
					sf = sf.replace(",^k", ".^k")
				}else if( ! sf.includes(".^k") ){
					sf = sf.replace("^k", ".^k");
				}
			}

			// Subcampo d
			// Sin puntuación cuando es el último
			// Sin puntuación cuando le sigue un ^c que inicia con paréntesis
			if (/\^d[^^]*,\^c\(/.test(sf) ){
				sf = sf.replace(",^c", "^c");
			}
			// Sin puntuación cuando le sigue ^x
			if (/\^d[^^]*,\^x/.test(sf) ){
				sf = sf.replace(",^x", "^x");
			}
			// Sin puntuación cuando le sigue ^0
			if (/\^d[^^]*,\^0/.test(sf) ){
				sf = sf.replace(",^0", "^0");
			}

			// Punto cuando le sigue ^t
			if (/\^d[^^]*\^t/.test(sf) ){
				if ( sf.includes(",^t") ){
					sf = sf.replace(",^t", ".^t")
				}else if( ! sf.includes(".^t") ){
					sf = sf.replace("^t", ".^t");
				}
			}

			// Subcampo q
			// Sin puntuacion cuando es el último
			// Sin puntuación cuando le sigue un subcampo

			// Subcampo w
			if (/\^w[^^]*\^./.test(sf)){
				// Si hay un subcampo w que no es el último (si es el último no tendrá puntuación)
				let regex = /\^w.*?(?=\^)/g;

				let matches = sf.match(regex);

				matches.forEach( e => {
					let replacer = e.substr(0, e.length - 1);
					sf = sf.replaceAll(e, replacer);
				});
			}

			// Subcampo z
			if (/\^z[^^]*\^./.test(sf)){
				// Si hay un subcampo w que no es el último (si es el último no tendrá puntuación)
				let regex = /\^z.*?(?=\^)/g;

				let matches = sf.match(regex);

				matches.forEach( e => {
					let replacer = e.substr(0, e.length - 1);
					sf = sf.replaceAll(e, replacer);
				});
			}

			// Subcampo 0
			if (/\^0[^^]*\^./.test(sf)){
				// Si hay un subcampo w que no es el último (si es el último no tendrá puntuación)
				let regex = /\^0.*?(?=\^)/g;

				let matches = sf.match(regex);

				matches.forEach( e => {
					let replacer = e.substr(0, e.length - 1);
					sf = sf.replaceAll(e, replacer);
				});
			}
		break;
			
		case "440" :
			var re_clean = /( ;|,| \.)(?=\^|$)/g;
			sf = sf.replace(re_clean, "");
			sf = sf.replace(/\^n/, " .^n");
			var aux = (sf.search(/\^n/) != -1) ? "," : " .";
			sf = sf.replace(/\^p/, aux + "^p");
			sf = sf.replace(/\^v/, " ;^v");
			sf = sf.replace(/\^x/, ",^x");
		break;
			
		case "490" :
			var re_clean = /( ;|,)(?=\^|$)/g;
			sf = sf.replace(re_clean, "");
			sf = sf.replace(/\^v/g, " ;^v");
			sf = sf.replace(/\^x/, ",^x");
		break;
			
		case "600" : //ATENCION: arreglar problema con 600$2 
			var re_clean = /(,| \.)(?=\^|$)/g;
			sf = sf.replace(re_clean, "");
			sf = sf.replace(/\^c/, ",^c");
			sf = sf.replace(/\^d/, ",^d");
			var re_end = /([^\.\-\?\)])$/;
			sf = sf.replace(re_end, "$1 .");
		break;
		
		case "610" : //ATENCION: arreglar problema con 610$2 
			var re_clean = / \.(?=\^|$)/g;
			sf = sf.replace(re_clean, "");
			sf = sf.replace(/\^b/, " .^b");
			var re_end = /([^\.\-\?\)])$/;
			sf = sf.replace(re_end, "$1 .");
			break;
			// ATENCION: revisar este caso: «^axxx. .^byyy»
			
		case "670":
			var re_clean = /( *[:\/]|,| \.)(?=\^|$)/g
            sf = sf.replace(re_clean, "");
			sf = sf.replaceAll(".^", "^");
			sf = sf.replaceAll(":^", "^");

			// Si finaliza en . entonces limpiarlo
			if (sf[sf.length - 1] == '.') {
				sf = sf.substring(0, sf.length - 1);
			}

			// En el caso de que el unico subcampo que quedó luego de eliminar fue ^a (y finaliza en :)
			// entonces elimino ultimo char (luego agrega un punto)
			if ( sf.match(/\^/g).length == 1 && sf[sf.length - 1 ] == ":" ){
				sf = sf.substring(0, sf.length - 1);
			}

			// Finaliza en punto cuando no le sigue ningún subcampo
			if (!sf.split("^a")[1].includes("^")){ // Cuando no le sigue ningun subcampo...
				sf = sf + ". ";
			}

			// Subcampo ^a finaliza en 2 puntos cuando le sigue el subcampo ^b
			if ( /\^a[^^]*\^b/.test(sf) ){
				sf = sf.replace("^b", ":^b");
			}
		break;
			
		default :
			// Algunas notas llevan punto final.
			// TO-DO: Agregar más campos de notas.
			if ( tag.search(/50[124]/) != -1 ) {
				var re_end = /([^\.\-\?])(?=\^5|$)/;
				sf = sf.replace(re_end,"$1.");
			}
		break;
	}
	
	// Eliminamos "puntos dobles" que puedan quedar al final de un subcampo
	// ATENCION: ¿deberíamos limitar esto a ciertos subcampos (110, 710, etc.)?
	sf = sf.replace(/\. \.(?=\^|$)/g, ".");
	
	// Limpiamos cualquier puntuación que pudiera haber sido colocada al inicio
	// (i.e. antes del primer subcampo)
	sf = sf.replace(/^[^\^]+\^/, "^");
	
	// Et c'est fini :-)
	return sf;
}
