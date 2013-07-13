var datetime = null,
        date = null;

var update = function () {
    date = moment(new Date());
    //datetime.html(date.format('dddd, Do   MMMM  YYYY, hh:mm:ss A')); 
    datetime.html(date.format('hh:mm:ss A'));
};


$(document).ready(function(){
	//BorderFooter();
	//auditoria();
	//segDate();
	//document.forms[0].reset

	ValidateFormSell();
	 
    datetime = $('#clock');
    update();
    setInterval(update, 1000);	 
	
	$("#txt_numero").focus();
	//$("#s1").focus();
	//$("#op_juego").focus();	
	$("#txtuser").focus();

        // Bloque para el control de logueo de usuarios
        setInterval(aunEstoyVivo, 60000);
});


function aunEstoyVivo(){

	$.get('scripts/Updatestatus.php', function(str) {
               // alert(str);
		});

}
function BorderFooter(){
	$("#container #footer").corner("6px");
}

// Para los sub-modulos
function act_mod(valor,cantidad,nivel,id,num){
	var i;
	var refer;
	
	if(nivel==2){
		refer= id+'_'+num;
	}
	else{
		refer= valor;
	}
		
	if($('#mod_'+refer).attr("checked")){
		for(i=1; i<=cantidad; i++){
			$('#mod_'+valor+'_'+i).attr("disabled","");
			$('#add_'+valor+'_'+i).attr("disabled","");
			$('#upd_'+valor+'_'+i).attr("disabled","");
			$('#del_'+valor+'_'+i).attr("disabled","");
		}
	}
	else{
		for(i=1; i<=cantidad; i++){
			$('#mod_'+valor+'_'+i).attr("disabled","disabled");
			$('#add_'+valor+'_'+i).attr("disabled","disabled");
			$('#upd_'+valor+'_'+i).attr("disabled","disabled");
			$('#del_'+valor+'_'+i).attr("disabled","disabled");
		}
	}
	
}

// Para buscar la informacion de la persona si cambia el tipo de Personal 
function auditoria(){
	$('#selectCargo').change(
		function(){
				// Datos para la busqueda de los municipios
				var cargo= $("#selectCargo").val();
				$.ajax({
					beforeSend: function(){
						$('#selectPersona').val("Cargando.....");
					},
					type: 'POST',
					url: 'ajax/Ajax.php',
					data: 'opcion=auditoria&cargo='+cargo,
					dataType: 'xml',
					success: function(xml){procesaXml(xml);}
				});
			$('#selectPersona').focus();
		}
	);
}

//  Agrega las opciones de resultado procesando el XML
function procesaXml(xml) {
	var clave= $("resultado",xml).text();
	var ident= "#"+clave;
	
	// Remueve las opciones antes creadas e ingresa una de default
	$(ident).removeOption(/./);
	
	// Ingresa las opciones de el resultado de busqueda
	$("item",xml).each(
		function(id){
			var num= $("item",xml).get(id);
			$(ident).addOption($("codigo",num).text(), $("nombre",num).text(), false);
		}
	);
}

// Para la fecha
function segDate(){
	// Referencia de idioma
	popUpCal.regional['es']= {
		clearText: '', closeText: '',
		//clearText: 'Limpiar', closeText: 'Cerrar',
		prevText: '&lt;Ant', nextText: 'Sig&gt;', currentText: '', //currentText: 'Hoy',
		dayNames: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		closeAtTop: false,
		speed: 'fast'
	};
	popUpCal.setDefaults(popUpCal.regional['es']);
	
	// Cuando se ejecute el formulario
	$('input.fecha2').calendar({
		autoPopUp: 'button', 
		buttonImageOnly: true, 
		//buttonImage: 'images/calendar2.png', 
		buttonImage: 'images/calendar.gif', 
		buttonText: 'Calendario'
	});
}

// Para validar fecha 
function ValiDate(){
		var fechaA= $('#date1').val();
		var fechaB= $('#date2').val();
		
		var fecha1= fechaA.toString();
		fecha1= fecha1.split("/");
		var dia1= fecha1[0];
		var mes1= fecha1[1];
		var anyo1= fecha1[2];
		
		var fecha2= fechaB.toString();
		fecha2= fecha2.split("/");
		var dia2= fecha2[0];
		var mes2= fecha2[1];
		var anyo2= fecha2[2];
		
		if(anyo1 < anyo2){var x=true;}
		else{
			if(mes1 > mes2){var x=false;}
			else{
				if((mes1 == mes2) && (dia1 > dia2)){var x=false;}
				else{var x=true;}
			}
		}
		
		if(!x){
			$('#fecha_incorrecta').show('slow');
			$(fechaA).focus();
			return false;
		}
		else{
		return true;
		}
}

// Para validar los formularios
function ValidarFormularios(){
	// Metodo para validar que solo sean numeros y letras
	jQuery.validator.addMethod(
		"alfanumerico",
		function(value, element) {
			return this.required(element) || /^[a-z\d]+$/i.test(value);
		},
		"Por favor solo letras y numeros, sin caracteres especiales!!"
	);
	
	// Metodo para validar que solo sean los caracteres para el rif
	jQuery.validator.addMethod(
		"codempresa", 
		function(value, element) {
			return this.required(element) || /^[a-z\-\d]+$/i.test(value);
		}, 
		"Por favor, ingrese numeros o letras (incluyendo el guion) sin caracteres especiales"
	);
	
	// Metodo para validar que solo sean los caracteres para el rif
	jQuery.validator.addMethod(
		"numerorif", 
		function(value, element) {
			return this.required(element) || /^[jve\-\d]+$/i.test(value);
		}, 
		"Por favor, ingrese correctamente el RIF!!"
	);
	
	// Metodo para validar que solo sean numeros,letras o guion
	jQuery.validator.addMethod(
		"codigofield",
		function(value, element) {
			return this.required(element) || /^[a-z\-\d]+$/i.test(value);
		},
		"Por favor solo letras, numeros o guion, sin caracteres especiales!!"
	);
	
	// Metodo para validar que solo sean numeros con comas
	jQuery.validator.addMethod(
		"numerofloat",
		function(value, element) {
			return this.required(element) || /^[.\d]+$/i.test(value);
		},
		"Por favor solo numeros y punto, sin letras ni caracteres especiales!!"
	);
	
	// Metodo para validar que solo sean numeros y letras
	jQuery.validator.addMethod(
		"loginformat",
		function(value, element) {
			return this.required(element) || /^[_\.a-z\d]+$/i.test(value);
		},
		"Por favor solo letras o numeros (se incluye el underscore y el punto)"
	);
	
	// formulario a validar
	$("#frm1").validate();
	
	// propone un indicador tomando en cuenta el email
	$("#txt_indicador").focus(function() {
		var correo= $("#txt_email").val();
		if ( correo && !this.value ){
			var posicion= correo.indexOf ("@");
			this.value= correo.substr (0, posicion);
		}
	});
}

// Para automarcar accesos
function act_access(refer){
	$('#mod_'+refer).attr("checked","checked");
}


function deleteCookie( name, path, domain ) {
	if ( getCookie( name ) ) document.cookie = name + '=' +
			( ( path ) ? ';path=' + path : '') +
			( ( domain ) ? ';domain=' + domain : '' ) +
			';expires=Thu, 01-Jan-1970 00:00:01 GMT';
}


function showUser(str){
if (str=="")
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  } 
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
	//document.getElementById("txtHint").innerHTML="";
    }
  }
xmlhttp.open("GET","ajax/cantidad_motivos.php?cantm="+str,true);
xmlhttp.send();
}

function CargarSubmit()
{
  document.frm1.submit();
  $("#txt_numero").focus();
}

function CargarReset()
{
  document.forms[0].reset();
  calcula_total();
  calcula_cambio();
  $("#txt_numero").focus();
}

function CargarReset1()
{
  $("#txt_numero").val('');
  //$("#txt_monto").val('');
  $("#txt_numero").focus();
}

function sumarTotal(c, numeroegresos){ 
	var subtotal = 0;
	if(!/^\d+(\.\d+)?$/.test(c.value)) return;
			for (var i = 1; i <= numeroegresos; i++) {
	          if (!/^\d+(\.\d+)?$/.test(document.getElementById("txt_subtotal_"+i).value)) continue;
	           	  //subtotal += parseInt(document.getElementById("txt_subtotal_"+i).value);	
	              subtotal += parseFloat(document.getElementById("txt_subtotal_"+i).value);
	              //alert(subtotal);
	              document.getElementById('txt_TOTAL').value = subtotal; 
	      }
 
} 

// agrega los numeros seleccionados al ticket (post and return data)  
function agregar_ticket(){
    // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    // Create some variables we need to send to our PHP file
    var url = "ajax/preticket.php";

    hr.open("POST", url, true);
    // Set content type header information for sending url encoded variables in the request
    hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;

		    //si regresa este mensaje apunta al campo de texto numero
		    if(return_data == "<div id='mensaje' class='mensaje' >Debe ingresar un NUMERO para jugar !!!</div>"){
		    	//alert(return_data);
		    	$("#txt_numero").focus();
		    }
		    //si regresa este mensaje apunta al campo de texto monto
		    if(return_data == "<div id='mensaje' class='mensaje' >Debe ingresar un MONTO Bs para jugar !!!</div>"){
		    	//alert(return_data);
		    	$("#txt_monto").focus();
		    }
		    //si regresa este mensaje apunta al campo de sorteos
		    if(return_data == "<div id='mensaje' class='mensaje' >Debe seleccionar un SORTEO para jugar !!!</div>"){
		    	//alert(return_data);
		    	$("#s0").focus();
		    }
		    //si regresa este mensaje apunta al campo de signos
		    if(return_data == "<div id='mensaje' class='mensaje' >Debe seleccionar SIGNO ZODIACAL para jugar !!!</div>"){
		    	//alert(return_data);
		    	$("#z0").focus();
		    }

		    //si regresa este mensaje refresca la pagina para que actualize los sorteos cerrados
		    if(return_data == "Selecciono un SORTEO ya cerrado !!!"){
		    	//alert(return_data);
		    	location.reload();
		    }

                    
                   var n=return_data.search("CONFIRMAPUESTA");
                   if(  n>0 ){
                       if (confirm("Ya este numero se encuentra registrado, desea aumentar la apuesta?")){
                            var monto = prompt("Ingrese el monto de la apuesta", "");
                            var numero = document.getElementById("txt_numero");
                            alert(numero.value);
                            
                        }
		    	location.reload();
		    }

		    //mensaje que muestra para el ticket de la derecha
			document.getElementById("ticket").innerHTML = return_data;
	    }
    }
    // taken all the inputs and answer for a one variable
	var vars = $("#frm1").serialize("ajax/preticket.php");
	//alert(vars);

    // Send the data to PHP now... and wait for response to update the status div
    hr.send(vars); // Actually execute the request
    document.getElementById("ticket").innerHTML = "processing...";	

}

// Funcion que carga el preticket
function cargarPreticket(){
    $.get('ajax/CargarTicket.php', function(str) {
	document.getElementById("ticket").innerHTML = str;
    });
    $("#txt_numero").focus();
    calcula_total();
}

//calcula el total del ticket
function calcula_total(){
	
	$.get('ajax/calculaTotal.php', function(str) {
		  $('#total').val(str);
		});	
	
}

// calcula el cambio del vuelto
function calcula_cambio()
{
	var total = $("#total").val();
	var recibido = $("#recibido").val();
	if(recibido!="")
	{
		var cambio = parseFloat(recibido-total);
		if(cambio>0){$("#cambio").val(cambio);}
		else{$("#cambio").val('');}
		
	}
}

//calcula el total del ticket
function imprimirticket(){
	
	$.get('ajax/ImprimirTicket.php', function(str) {
		  //$('#total').val(str);
                  alert(str);
                  document.getElementById("ticket").innerHTML ="";
                  CargarReset();
		});	
	
}

// Funcion para procesar el ticket al imprimir
function procesarticket()
{
    var TicketGenerado = "";

    if (confirm("Esta seguro que desea generar el ticket?")){
        
        $.get('ajax/ProcesarTicket.php', function(str) {
		 //TicketGenerado.val(str);
                 if (str== 'CeroTicketTransaccional'){
                     alert("Debe hacer por lo menos una apuesta para generar un ticket!");
                 }else{
                     if (str== 'Ok'){
                         //Imprimir el ticket
                         //alert("Imprimir el ticket");
                         imprimirticket();
                         $("#txt_numero").val('');
                         $("#txt_monto").val(''); 
                     }else if (str== 'NotOk'){
                        alert("Se presento un error al generar el ticket, por favor intente mas tarde!");
                     }
                   
                 }
                 //alert(str);
	});
    }
    

}

// Funcion para agregar los terminales automaticamente
function agregarTerminales(){
    $.get('ajax/ValidarTerminales.php', function(str) {
        if (str == "Ok"){
            var monto = prompt("Digite el monto de apuesta para los terminales:", "");
             if (monto != null && monto != 0){
                agregarMontoTerminales(monto);
             }else{
                 alert("Debe ingresar un monto de apuesta para los terminales!");
             }
        }else if (str == "NotOk"){
            alert("Debe hacer por lo menos una apuesta de triple para generar los terminales en el ticket!");
        }
         
     });
}

function agregarMontoTerminales(monto)
{
    $.get('ajax/AgregarTerminales.php?monto=' + monto, function(str) {
       document.getElementById("ticket").innerHTML = str;
            $("#txt_numero").focus();
    });
 }


function BorrarUltimaJugada()
{
    $.get('ajax/BorrarUltimaJugada.php', function(str) {
        document.getElementById("ticket").innerHTML = str;
        calcula_total();
    });
 }

function BorrarPreTicket(){
    if (confirm("Esta seguro que desea BORRAR el PreTicket?")){
         $.get('ajax/BorrarPreTicket.php', function(str) {
            if (str == "Ok"){
                document.getElementById("ticket").innerHTML ="";
                // Eliminada el Preticket
                CargarReset();
            }else if (str == "NotOk"){
                alert("No existe ninguna apuesta en el ticket!");
            }
        });
    }
}

// Funcion para llamar al formulario de buscar ticket para copiarlo
function RepetirTicket()
{
    window.location='index.php?op=copiar_ticket&accion=search';
 }

// Funcion para ajustar todos los montos de las apuestas a un prorrateado entre un monto total
function AjustarMontos(){
    $.get('ajax/ValidarAjustarMontos.php', function(str) {
        if (str == "Ok"){
            var monto = prompt("Digite el monto a ajustar entre todas las apuestas existentes: ", "");
             if (monto != null && monto != 0){
                ajustarMontoApuestas(monto);
             }else{
                 alert("Debe ingresar un monto para reajustar!");
             }
        }else if (str == "NotOk"){
            alert("Debe haber por lo menos una apuesta en el ticket!");
        }

     });
}

function ajustarMontoApuestas(monto)
{
    $.get('ajax/AjustarMontos.php?monto=' + monto, function(str) {
       document.getElementById("ticket").innerHTML = str;
            calcula_total();
            calcula_cambio();
            $("#txt_numero").focus();
    });
 }

// ESTA Funcion es para poder hacer tab like enter
//<![CDATA[

JoelPurra.PlusAsTab.setOptions({
	// Use enter instead of plus
	// Number 13 found through demo at
	// http://api.jquery.com/event.which/
	key: 13
});

$("form").submit(simulateSubmitting);

function simulateSubmitting(event)
{
	event.preventDefault();

	if (confirm("Simulating that the form has been submitted.\n\nWould you like to reload the page?"))
	{
		//location.reload();
	}

	return false;
}
//]]>


var Atl_down = false;  //Declaramos que la tecla Atl no ha sido presionada
var Atl_key = 18;  // Id de la tecla Alt


$(document).keydown(function(e) {
    if (e.keyCode == Atl_key) Atl_down = true;
}).keyup(function(e) {
    if (e.keyCode == Atl_key) Atl_down = false;
});

// Abrevituras de teclado
$(document).keydown(function(tecla){

  
   
    if (tecla.keyCode == 107) {
    	// tecla +
		agregar_ticket(); //para agregar al ticket
		CargarReset1(); //Limpiar el campo numero 
		calcula_total();
		//$("#txt_numero").focus(); // para focalizar el numero	
        //$('.a').css({ 'background-color' : 'red' });
    }else if(Atl_down && (tecla.keyCode == 74)){
    	// tecla J    	
    	$("#op_juego").focus(); // hace focus en el campo de tipo de juego
    }else if(Atl_down && (tecla.keyCode == 84)){
    	// tecla T
    	$('#op_juego').val('1');// hace focus en el campo de tipo de juego y selecciona Triple
    }else if(Atl_down && (tecla.keyCode == 80)){
    	// tecla P
    	$('#op_juego').val('2');// hace focus en el campo de tipo de juego y selecciona Permuta
    }else if(Atl_down && (tecla.keyCode == 78)){
    	// tecla N    	
    	$("#txt_numero").focus(); // hace focus en el campo de numeros
    }else if(Atl_down && (tecla.keyCode == 77)){
    	// tecla M    	
    	$("#txt_monto").focus(); // hace focus en el campo de monto Bs
    }else if(Atl_down && (tecla.keyCode == 90)){
    	// tecla Z
    	$("#z0").focus(); // hace focus         en el campo de checkboxes Zodiacales
    }else if(Atl_down && (tecla.keyCode == 83)) {
    	// tecla S
    	$('#op_juego').val('3');// hace focus en el campo de tipo de juego y selecciona Permuta
    }else if(Atl_down && (tecla.keyCode == 67)) {
    	// tecla C
    	$('#op_juego').val('4');// hace focus en el campo de tipo de juego y selecciona Permuta
    }else if(Atl_down && (tecla.keyCode == 76)) {
    	// tecla L 
    	$("#s0").focus(); // hace focus en el campo de checkboxes Sorteos
    }else if(Atl_down && (tecla.keyCode == 69)) {
    	// tecla E
    	$("#recibido").focus(); // hace focus en el campo de vuelto o cambio 
    }else if(tecla.keyCode == 113) {
    	// tecla F2
    	CargarReset(); //Limpiar Todos los campos
    }else if(tecla.keyCode == 114) {
    	// tecla F3
    	AjustarMontos(); //Ajustar los montos de todas las apuestas a un prorrateado del total
    }else if(Atl_down && (tecla.keyCode == 73)){
        // Tecla I
        procesarticket(); //para procesar y generar el ticket
	CargarReset();
    }else if(Atl_down && (tecla.keyCode == 65)){
        // Tecla A
        //Preguntamos monto de los terminales
        agregarTerminales();
    }else if(Atl_down && (tecla.keyCode == 81)){
        // Tecla Q
        //Quitar la ultima jugada
        BorrarUltimaJugada();
    }else if(Atl_down && (tecla.keyCode == 82)){
        // Tecla R
        //Repetir Ticket
        RepetirTicket();
    }else if(tecla.keyCode == 115){
        // Tecla F4
        //Borrar PreTicket
        BorrarPreTicket();
    }
	
});


//Para validar Form de Ventas
function ValidateFormSell(){
	jQuery('#txt_numero').keydown(function (event) {
	    // Allow only backspace and delete
	           if (event.keyCode == 46 || event.keyCode == 8  || event.keyCode == 9 || event.keyCode == 106 || event.keyCode == 109) {
	               // let it happen, don't do anything
	        	   // 46 = punto ; 8 = Retroceso ; 9 = tabulador
	        	   //106 = asterisco; 109 = restar
	           }
	           else {
	               // Ensure that it is a number and stop the keypress
	               if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105)) {
	                 count = jQuery('#txt_numero').val().length;
	                 juego = jQuery('#op_juego').val();
	                 
	                 // si el modo de juego es triple
	                 if (count > 2 && juego == 1) {
	                    event.preventDefault();
	                    
	                 // si el modo de juego es permuta 
	                 }
	                 if(count > 6 && juego == 2) {
                		 event.preventDefault();
                	 }
	                 // si el modo de juego es series 
	                 if(count > 2 && juego == 3) {
                		 event.preventDefault();
                	 }
    	             // si el modo de juego es corrida 	                 
	                 if(count > 6 && juego == 4) {
                		 event.preventDefault();
                	 }                 
	               }
	               else {
	                   event.preventDefault();
	               }
	           }
	});
	
	jQuery('#txt_monto').keydown(function (event) {
	    // Allow only backspace and delete
	           if (event.keyCode == 46 || event.keyCode == 8  || event.keyCode == 9 || event.keyCode == 110) {
	               // let it happen, don't do anything
	           }
	           else {
	               // Ensure that it is a number and stop the keypress
	               if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105)) {
	               }
	               else {
	                   event.preventDefault();
	               }
	           }
	});	
	
	jQuery('#recibido').keydown(function (event) {
	    // Allow only backspace and delete
	           if (event.keyCode == 46 || event.keyCode == 8  || event.keyCode == 9 || event.keyCode == 110) {
	               // let it happen, don't do anything
	           }
	           else {
	               // Ensure that it is a number and stop the keypress
	               if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105)) {
	               }
	               else {
	                   event.preventDefault();
	               }
	           }
	});	
		
}

var flag = 1;
function checkAllsorteos(chk){
	for (i = 0; i < chk.length; i++)
		if (flag == 1)
		{
			//alert('A');
			chk[i].checked = true ;
			
		}
		else 
		{
			//alert('B');
			chk[i].checked = false;				
		}
	if (flag == 1){
		flag=0;
	}else{
		flag=1;
	}
}


var flags = 1;
function checkAllZodiacales(chk){
	
	for (i = 0; i < chk.length; i++)
		if (flags == 1)
		{
			//alert('A');
			chk[i].checked = true ;
			
		}
		else 
		{
			//alert('B');
			chk[i].checked = false;				
		}
	if (flags == 1){
		flags=0;
	}else{
		flags=1;
	}	
}

var flags_1 = 1;
function checkAllTipoJugada(chk){

	for (i = 0; i < chk.length; i++)
		if (flags_1 == 1)
		{
			//alert('A');
			chk[i].checked = true ;

		}
		else
		{
			//alert('B');
			chk[i].checked = false;
		}
	if (flags_1 == 1){
		flags_1=0;
	}else{
		flags_1=1;
	}
}
