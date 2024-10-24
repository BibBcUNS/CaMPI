// =============================================================================
//  eventsHandlers.js
//
//  Event handler definitions for some (static) UI elements.
//
//  (c) 2003-2004  Fernando J. G�mez - CONICET - INMABB
// =============================================================================

window.onresize = function(){
  setDimensions();
};

window.onbeforeunload = function(){
  if(modifiedRecord() && globalParameter!="selDatabase" ){
    return true;
  }
}

// -----------------------------------------------------------------------------
function setSearchFormEvents()
// -----------------------------------------------------------------------------
{
  document.getElementById("searchTab").onclick = function() {
    showSearchForm("search");
  };
  
  document.getElementById("indexTab").onclick = function() {
    showSearchForm("index");
  };
  
  document.getElementById("kwSearchForm").onsubmit = function() {
    top.g_recordSelected = "first";
    handleKwSearch();
    return false;
  };

  document.getElementById("mfnSearchForm").onsubmit = function(){
    top.g_recordSelected = "first";
  }

  document.getElementById("testConditionSearchForm").onsubmit = function(){
    top.g_recordSelected = "first";
  }
  
  document.getElementById("kwSearchHelpLink").onclick = function() {
    generalHelpPopup("keyword_search");
  };
  
  document.getElementById("mfnSearchHelpLink").onclick = function() {
    generalHelpPopup("mfn_search");
  };
  
  document.getElementById("testConditionSearchHelpLink").onclick = function() {
    generalHelpPopup("testcondition_search");
  };
  
  document.getElementById("indexHelpLink").onclick = function() {
    generalHelpPopup("dictionary_keys");
  };
  
  document.getElementById("indexForm").onsubmit = function() {
    updateDictionaryList(this.dictkey.value);
    return false;
  };
  
  document.getElementById("aacrDisplayBtn").onclick = function() {
    viewRecordDetails(event,null,"aacr");
  };
  
  document.getElementById("etiqDisplayBtn").onclick = function() {
    viewRecordDetails(event,null,"etiq");
  };
  
  document.getElementById("marcDisplayBtn").onclick = function() {
    viewRecordDetails(event,null,"marc");
  };
  
  document.getElementById("editRecordBtn").onclick = function() {
    editRecord(null,event);
  };
  
  document.getElementById("btnNewRecords").onclick = showNewRecords;
}


// -----------------------------------------------------------------------------
function setEditionFormEvents()
// -----------------------------------------------------------------------------
{
  document.getElementById("postItNoteBtn").onclick = function() {
    //this.blur();
    editPostItNote();
  };
  
  document.getElementById("ejemplaresBtn").onclick = function() {
    //this.blur();
    editEjemplares();
  };

  if (document.getElementById("miniatura-imagen")) {
    document.getElementById("miniatura-imagen").onclick = function() {
      editImagenes();
    };
  }
  
  document.getElementById("btnDocHideShow").onclick = docIframeShow;
  
  document.getElementById("docForm").onsubmit = function() {
    showDoc(this.docItem.value);
    return false;
  };
}


// -----------------------------------------------------------------------------
function setControlFormEvents()
// -----------------------------------------------------------------------------
{
  // Queremos que un ENTER en los textboxes equivalga a un TAB, y que un F12
  // abra la ventana con los c�digos, si la hay.
  // ATENCION: no funciona la deshabilitaci�n del Backspace (en IE6-home parece que s�)
  var inputFields = document.getElementById("control").getElementsByTagName("input");
  for (var i=0; i < inputFields.length; i++) {
    if ( "text" == inputFields[i].type ) {
      inputFields[i].onkeydown = function(evt) {
        var evt = (evt) ? evt : window.event;
        if ( evt.keyCode == 13 ) {
          evt.keyCode = 9;     // ATENCION: keyCode es read-only en Mozilla
          return true;
        }
        else if ( evt.keyCode == 123 && this.name.search(/f008_07_10|f008_11_14/) == -1 ) {
          top.globalParameter = this.name;
          editCodedData();
          return false;   // 123 = F12
        }
        else if ( evt.keyCode == 8 ) {  // Backspace
          //alert();
          window.event.cancelBubble = true;
          return false;
        }
      };
    }
  }
  
  // Un click sobre una celda abre la ventana con los c�digos
  var allCells = document.getElementById("control").getElementsByTagName("td");
  for (var i=0; i < allCells.length; i++) {
    if ( allCells[i].id.search(/^TD_f008_|^TD_L_/) != -1 ) {
      //alert(allCells[i].id);
      allCells[i].onclick = function() {
        top.globalParameter = this.id.substr(3);
        editCodedData();
      };
      allCells[i].onmouseover = function() {
        this.style.backgroundColor = FIXEDFIELD_HL_BGCOLOR;
      };
      allCells[i].onmouseout = function() {
        this.style.backgroundColor = "#FED";  // este color aparece en catalis.css
      };
    }
  }
  
  // Fechas
  document.getElementById("f008_07_10").onchange = function() {
    if ( this.value.length > 0 ) {
      this.value = this.value.concat("uuuu").substr(0,4);
    } else {
      this.value = "####";
    }
    this.value = this.value.replace(/ /g,"u");
  };
  document.getElementById("f008_11_14").onchange = function() {
    if ( this.value.length > 0 ) {
      this.value = this.value.concat("uuuu").substr(0,4);
    } else {
      this.value = "####";
    }
    this.value = this.value.replace(/ /g,"u");
  };
}  


// -----------------------------------------------------------------------------
function setWindowEvents()
// -----------------------------------------------------------------------------
{
  // Queremos emular en Mozilla el comportamiento del objeto popup de IE.
  // ATENCION: �hay que agregar cada posible evt.target asociado a un popup?
  // TO-DO: si el click es en el IFRAME...
  if (moz) {
    console.log('moz');
    window.addEventListener("click", function(evt) {
      console.log('clicked');
      if ( evt.target != document.getElementById("btnNuevo") )
        console.log('hiding');
        hidePopup();
    });
    //window.onblur = hidePopup;  // efecto indeseado: no funciona el men� "Nuevo"
  }
}


// -----------------------------------------------------------------------------
function setToolbarEvents()
// -----------------------------------------------------------------------------
{
  document.getElementById("btnNuevo").onclick = function(event) {
    this.blur();
    showNewRecordMenu(event);
  };
  
  document.getElementById("btnImport").onclick = function() {
    this.blur();
    top.globalParameter = "newImport";
    checkModified();
  };
  
  document.getElementById("toggleLabels").onclick = function() {
    this.blur();
    toggleSubfieldLabels();
  };
  
  document.getElementById("btnFicha").onclick = function() {
    this.blur();
    viewRecord();
  };
  
  document.getElementById("btnExportar").onclick = function() {
    this.blur();
    exportRecord();
  };
  
  document.getElementById("btnGrabar").onclick = function() {
    this.blur();
    saveRecord();
  };
  
  //M.A 22/03/2023 comentolas siguientes 4 lineas ( y reescribo la funcion justo debajo )
  //document.getElementById("btnRawEdit").onclick = function() {
  //  this.blur();
  //  rawEdit(serializeRecord(false,false,true,false));
  //};

  document.getElementById("btnRawEdit").onclick = function() {
    this.blur();
    //(M.A) globalParameter almacena el estado actual del registro
    top.globalParameter = serializeRecord(false,false,true,false);
    rawEdit();
  };

  document.getElementById("btnBorrar").onclick = function() {
    this.blur();
    deleteRecord();
  };
  
  document.getElementById("btnKeys").onclick = showKeys;
  
  document.getElementById("fieldTagForm").onsubmit = function() {
    newFieldShortcut();
    return false;
  };
  
  document.getElementById("subfieldCodeForm").onsubmit = function() {
    newSubfieldShortcut(selectedField);
    return false;
  };
  
  document.getElementById("btnNewField").onclick = function() {
    this.blur();
    promptNewField();
  };
  
  document.getElementById("btnNewSubfield").onclick = function() {
    this.blur();
    globalParameter = selectedField;
    promptNewSubfield();
  };
  
  document.getElementById("btnPrevResult").onclick = function() {
    top.globalParameter = this.id;
    checkModified();
  };
  
  document.getElementById("btnNextResult").onclick = function() {
    top.globalParameter = this.id;
    checkModified();
  };
  
  document.getElementById("resultSetCounter").onfocus = function() {
    this.blur();
  };
  
  document.getElementById("btnBuscar").onclick = function() {
    top.globalParameter = this.id;

    if(top.g_recordDeleted || document.getElementById("f001").value == "[pendiente]"){
      top.g_recordSelected = "first";
    }else{
      top.g_recordSelected = document.getElementById("f001").value;
    }

    checkModified();
  };
  
  document.getElementById("btnEditar").onclick = showEditDiv;

  document.getElementById("btnOnline").addEventListener("click", function(e){
    e.preventDefault();
    let tag = document.getElementById("docItem").value;
    showDocOnline(tag)
  });
}

  
// -----------------------------------------------------------------------------
function setHeaderEvents()
// -----------------------------------------------------------------------------
{
  document.getElementById("btnFinSesion").onclick = function() {
    top.globalParameter = this.id;
    checkModified();
  };
  
  document.getElementById("selDatabase").onchange = function() {
    top.globalParameter = this.id;
    checkModified();
  };

  if(document.getElementById("goToCatauto")){ // Si existe el boton (puede que el usuario no tenga permiso de acceder al modulo y el boton no existe)
    document.getElementById("goToCatauto").addEventListener("click", function(){
      window.open("/login/php/openModule.php?modulo=catauto", "_blank").focus();
    })
  }

  if(document.getElementById("goToHerramientas")){// Si existe el boton (puede que el usuario no tenga permiso de acceder al modulo y el boton no existe)
    document.getElementById("goToHerramientas").addEventListener("click", function(){
      window.open("/login/php/openModule.php?modulo=herramientas", "_blank")
    })
  }
  
  

  document.getElementById("showHiddenData").onclick = showHiddenData;
}

function setFieldsHandlers(){
  // Agrego un keyup al documento para controlar la longitud de los subcampos del 245.
  document.addEventListener("input", (e) => {
    var element = e.target;
    // Establezco el limite en 1000 caracteres.
    let limit = 1000;

    // TO-DO Agregar control a otros campos
    if(element.closest("#field245")){
      if(element.value.length >= limit){
        element.value = element.value.substring(0, limit);
        top.catalisMessage("Ha alcanzado el l�mite de longitud del campo.", true);
        top.updateTextareaHeight();
      }
    }    
  })
}

// -----------------------------------------------------------------------------
function setEventHandlers()
// -----------------------------------------------------------------------------
{
  //setWindowEvents();
  setHeaderEvents();
  setToolbarEvents();
  setSearchFormEvents();
  setEditionFormEvents();
  setControlFormEvents();
  setFieldsHandlers();
}
