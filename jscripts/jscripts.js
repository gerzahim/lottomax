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
	

	deshabilitarTeclasFechas();
	
	ValidateFormSell();
	 
    datetime = $('#clock');
    //update();
    setInterval(update, 1000);	 
	
    //Cambio de Turno al seleccionar por radio
    cambioTurno();
    
	$("#txt_numero").focus();
	//$("#s1").focus();
	//$("#op_juego").focus();	
	$("#txtuser").focus();
	

	// Bloque para el control de logueo de usuarios
	setInterval(aunEstoyVivo, 60000);
});


function DetectarUrlVentas(){
	//var pathname = window.location.pathname;
	//var url = 'http://'+host'/lottomax/index.php?op=ventas#final';
	
	var url = window.location.href;
	var host = window.location.host;
	//alert(host);
	//alert(host + '/index.php?op=ventas#final');
	if(url.indexOf(host + '/index.php?op=ventas') != -1) {
		 //match
		return true;  
	}else if(url.indexOf(host + '/lottomax/index.php?op=ventas') != -1) {
		 //match
		return true;  
	}else{
		 //NO match
		return false;  
	}

}

var tid = 0;
function toggleOnAbajo(){
    if(tid==0){
        tid=setInterval(saltarSigCampo, 75); // 2000 ms = start after 2sec
    }	
	//interval = setInterval(saltarSigCampo, 2000); // 2000 ms = start after 2sec 
	
}


function toggleOffAbajo(){
    if(tid!=0){
        clearInterval(tid);
        tid=0;
    }		
	//interval = 0;
	//clearInterval(interval);
}

function saltarSigCampo() {
	
	$(":input")[$(":input").index(document.activeElement) + 1].focus();	  
}



var ted = 0;
function toggleOnArriba(){
    if(ted==0){
    	ted=setInterval(retrocederSigCampo, 75); // 2000 ms = start after 2sec
    }	
	//interval = setInterval(saltarSigCampo, 2000); // 2000 ms = start after 2sec 
	
}


function toggleOffArriba(){
    if(ted!=0){
        clearInterval(ted);
        ted=0;
    }		
	//interval = 0;
	//clearInterval(interval);
}

function retrocederSigCampo() {
	if($(":input").index(document.activeElement) > '13'){
		$(":input")[$(":input").index(document.activeElement) - 1].focus();	
	}
		  
}

function aunEstoyVivo(){

	$.get('scripts/Updatestatus.php', function(str) {
               // alert(str);
		});
}

function deshabilitarTeclasFechas(){

	var ar=new Array(33,34,35,36,37,38,39,40);
	$(document).keydown(function(e) {
	     var key = e.which;
	      //console.log(key);
	      //if(key==35 || key == 36 || key == 37 || key == 39)
	      if($.inArray(key,ar) > -1) {
	          e.preventDefault();
	          return false;
	      }
	      return true;
	});	
}
function cambioTurno(){

	$("input[name='turno']").change(function() {
		//$("input[name=turno]:radio").change(function () {
			if ($("input[name='turno']:checked").val() == '1'){
				//Selecciono Turno Manana	
				$("#txt_numero").focus(); // hace focus en el campo de numeros	
		    	$('.text-label2').hide();
		    	$('.text-label3').hide();
		    	$('.text-label1').show();
				//$("#s0").focus(); // hace focus en el campo de checkboxes Sorteos			
			}else if ($("input[name='turno']:checked").val() == '2'){
				//Selecciono Turno Tarde
				$("#txt_numero").focus(); // hace focus en el campo de numeros
		    	$('.text-label1').hide();
		    	$('.text-label3').hide();
		    	$('.text-label2').show();
				//$("#s0").focus(); // hace focus en el campo de checkboxes Sorteos				
			}else if ($("input[name='turno']:checked").val() == '3'){
				//Selecciono Turno Noche
				$("#txt_numero").focus(); // hace focus en el campo de numeros		
		    	$('.text-label1').hide();
		    	$('.text-label2').hide();
		    	$('.text-label3').show();
		    	//$("#s0").focus(); // hace focus en el campo de checkboxes Sorteos
			}else if ($("input[name='turno']:checked").val() == '4'){
				//Selecciono Turno Todos
				$("#txt_numero").focus(); // hace focus en el campo de numeros			
		    	$('.text-label1').show(); // Mostrar Todos los Turnos M, T, N
		    	$('.text-label2').show();
		    	$('.text-label3').show();
				//$("#s0").focus(); // hace focus en el campo de checkboxes Sorteos	   			
			}

	     });	

}

function BorderFooter(){
	$("#container #footer").corner("6px");
}

function seleccionar_monto(){
	$("#txt_monto").select();
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
		    /*
		    //alert(return_data);
		    //classw="<div id='mensaje' class='menwsaje' >Debe seleccionar un SORTEO para jugar !!!</div>";

		    var Cadena="Ejemplo desde Intenta Blog";
		    vvv = "Blog";
		    alert(Cadena.search(classw));
		    */
		    //alert(return_data.search(classw));
		    
		    var geterror = 0;

		    //si regresa este mensaje apunta al campo de texto numero
		    $mensajillo = "<div id='mensaje' class='mensaje' >Debe ingresar un NUMERO para jugar !!!</div>";
		    if(return_data.search($mensajillo) != '-1'){
		    	//alert(return_data);
		    	$("#txt_numero").focus();
		    	
		    	geterror = 1;
		    }
		    //si regresa este mensaje apunta al campo de texto monto
		    $mensajillo = "<div id='mensaje' class='mensaje' >Debe ingresar un MONTO Bs para jugar !!!</div>";
		    if(return_data.search($mensajillo) != '-1'){
		    	//alert(return_data);
		    	$("#txt_monto").focus();
		    	geterror = 1;
		    }
		    
		    //si regresa este mensaje apunta al campo de sorteos
		    $mensajillo = "<div id='mensaje' class='mensaje' >Debe seleccionar un SORTEO para jugar !!!</div>";
		    if(return_data.search($mensajillo) != '-1'){
		    	//alert(return_data);
		    	$("#s0").focus();
		    	geterror = 1;
		    }
		    //si regresa este mensaje apunta al campo de signos
		    $mensajillo = "<div id='mensaje' class='mensaje' >Debe seleccionar SIGNO ZODIACAL para jugar !!!</div>";
		    if(return_data.search($mensajillo) != '-1'){
		    	//alert(return_data);
		    	$("#z0").focus();
		    	geterror = 1;
		    }

		    //si regresa este mensaje refresca la pagina para que actualize los sorteos cerrados
		    $mensajillo = "<div id='mensaje' class='mensaje' >Selecciono un SORTEO ya cerrado !!!</div>";
		    if(return_data.search($mensajillo) != '-1'){
		    	//alert(return_data);
		    	location.reload();
		    	geterror = 1;
		    }

            // cuando el numero ya esta jugado y se sustituye la jugada       
            //var n=return_data.search("--");
		    $mensajillo = "--";
		    if(return_data.search($mensajillo) != '-1'){
		    	//alert(return_data);
		    	 /*if 
                       var numero = document.getElementById("txt_monto");
                        alert("Ya este numero se encuentra registrado en el ticket! El monto de la apuesta va ser cambiado a: Bs. F. " + numero.value);
                      (confirm("Ya este numero se encuentra registrado, desea cambiar el valor de la apuesta?")){
                            var monto = prompt("Ingrese el monto de la apuesta", "");
                            var numero = document.getElementById("txt_monto");
                            alert(numero.value);
                            
                        }*/
                        //location.reload();
                    
                    geterror = 1;
                        
		    }
            
            //alert(geterror);

            // sino obtuvo errores borra el campo de numerosy hace focus en numero
            if(geterror == '0'){
            	  $("#txt_numero").val('');
            	  //$("#txt_monto").val(''); // en caso que quieras borrar tambien el monto
            	  $("#txt_numero").focus();            	
            }else{
            	$("#txt_numero").val('');
            	$("#txt_monto").val('');
            	$("#txt_numero").focus();  
            	
            }
            
            
		    //mensaje que muestra para el ticket de la derecha
			document.getElementById("ticket").innerHTML = return_data;
	    }
    }
    // taken all the inputs and answer for a one variable
	var vars = $("#frm1").serialize("ajax/preticket.php");
	//alert(vars);
	//op=ventas&accion=add&op_juego=1&txt_numero=234&txt_monto=234&turno=4&cant_sorteos=&ss%5B%5D=11&ss%5B%5D=12&ss%5B%5D=14&recibido=&cambio=&total=47

    // Send the data to PHP now... and wait for response to update the status div
    hr.send(vars); // Actually execute the request
    document.getElementById("ticket").innerHTML = "processing...";	
    
    calcula_total();
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

//calcula el total del ticket
function borra_txtNumero(){
	$("#txt_numero").val('');
	$("#txt_numero").focus();	
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

//Imprime el ticket
function imprimirticket(){

/*	
$.get('ajax/ip_visitor.php', function(str) {
    //alert(str);
    $.ajax({
        type: "POST",
        url: "ajax/socket.php",
  		data: {
   			 ip : str
 		}
        });
});	

document.getElementById("ticket").innerHTML ="";
CargarReset();		
 */
/*
//Metodo con Jzebra		

		document.getElementById("ticket").innerHTML ="";
        CargarReset();		
		window.open("ajax/ImprimirTicket.php", "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=400, height=400");
*/

//Metodo con Impresion directa con firefox
	document.getElementById("ticket").innerHTML ="";
        CargarReset();		
		window.open("ajax/ImprimirTicket.php", "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=400, height=400");



}

//Imprime el ticket
function ReImprimirticket(){


//Metodo con Impresion directa con firefox
	document.getElementById("ticket").innerHTML ="";
        CargarReset();		
		window.open("ajax/ReImprimirTicket.php", "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=400, height=400");

}

/*
function llamadaAjax(){

	$.get('ajax/ImprimirTicket.php', function(str) {
		  //$('#total').val(str);
                  //alert(str);
                  document.getElementById("ticket").innerHTML ="";
                  CargarReset();
		});	
		
    $.ajax({
        type: "POST",
        url: "ajax/ImprimirTicket.php"
        });
		sleep(1000);
		ventana_secundaria = window.open("ajax/ImprimirTicket.php", "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=400, height=400");
		//ventana_secundaria.close();

	  document.getElementById("ticket").innerHTML ="";
	  CargarReset();
	  
}*/

// Funcion para procesar el ticket al imprimir
function procesarticket()
{
    var TicketGenerado = "";

    //if (confirm("Esta seguro que desea generar el ticket?")){
        
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
  //  }
    

}

// Funcion para agregar los terminales automaticamente
function agregarTerminales(){
	//alert('Holaaaa');
    $.get('ajax/ValidarTerminales.php', function(str) {
    	//alert(str);
        if (str == "Ok"){
        	if(confirm("Mismo monto que los triples?")){
        		agregarMontoTerminales(0)
        	}
        	else
        	{
        		var monto = prompt("Digite el monto de apuesta para los terminales:", "");
                if (monto != null && monto != 0){
            		agregarMontoTerminales(monto)
                }else{
                    alert("Debe ingresar un monto de apuesta para los terminales!");
                }
        	}
        }else if (str == "NotOk"){
            alert("Debe hacer por lo menos una apuesta de triple para generar los terminales en el ticket!");
        }      
        calcula_total();   
     });
}

function agregarMontoTerminales(monto)
{
    $.get('ajax/AgregarTerminales.php?monto=' + monto, function(str) {
       document.getElementById("ticket").innerHTML = str;
            $("#txt_numero").focus();
            
    });
    calcula_total();
 }


//Funcion para agregar los terminales automaticamente
function agregarTerminalazo(){
	//alert('Holaaaa');
 $.get('ajax/ValidarTerminalazo.php', function(str) {
 	//alert(str);
     if (str == "Ok"){
         var monto = prompt("Digite el monto de apuesta para los terminalazos:", "");
          if (monto != null && monto != 0){
    	  agregarMontoTerminalazo(monto);
          }else{
              alert("Debe ingresar un monto de apuesta para los terminalazos!");
          }
     }else if (str == "NotOk"){
         alert("Debe hacer por lo menos una apuesta de Astral para generar los Terminalazos en el ticket!");
     }
     
     calcula_total();
      
  });
}

function agregarMontoTerminalazo(monto)
{
 //alert(monto);	
 $.get('ajax/AgregarTerminalazo.php?monto=' + monto, function(str) {
    document.getElementById("ticket").innerHTML = str;
         $("#txt_numero").focus();
         
 });
 calcula_total();
}

function BorrarUltimaJugada()
{
    $.get('ajax/BorrarUltimaJugada.php', function(str) {
        document.getElementById("ticket").innerHTML = str;
       
    });
    calcula_total();
 }

function BorrarTerminales()
{
    $.get('ajax/BorrarTerminales.php', function(str) {
        document.getElementById("ticket").innerHTML = str;
       
    });
    calcula_total();
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
 
 // Funcion para llamar al formulario de pagar ticket
function PagarTicket()
{
    window.location='index.php?op=pagar_ganador';
 }  
 
 // Funcion para llamar al formulario de anular ticket
function AnularTicket()
{
    window.location='index.php?op=anular_ticket&accion=search';
 } 

// Funcion para llamar al formulario de anular ticket
function verDetallaTicket()
{
    window.location='index.php?op=ticket_transaccional';
 } 

//Funcion para llamar al formulario de anular ticket
function verBorrarSorteoTicket()
{
    window.location='index.php?op=ticket_transaccional&accion=listarporsorteos';
 } 
 
// Funcion para llamar al formulario de Modificar detalle ticket
function verResultados()
{
    window.location='index.php?op=Rver_resultados';
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
/*
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
*/
//]]>


var Atl_down = false;  //Declaramos que la tecla Atl no ha sido presionada
var Atl_key = 18;  // Id de la tecla Alt

/*
$(document).keydown(function(e) {
//$(document).keyup(function(e){	
    if (e.keyCode == Atl_key) Atl_down = true;
}).keyup(function(e) {
    if (e.keyCode == Atl_key) Atl_down = false;
});
*/

// Abrevituras de teclado
//$(document).keydown(function(tecla){});

$(document).keyup(function(tecla){

	//alert(tecla.keyCode);
    if ( tecla.keyCode == 107) {
    	// tecla +
    	if(DetectarUrlVentas()){
    		agregar_ticket(); //para agregar al ticket
    		//CargarReset1(); //Limpiar el campo numero 
    		calcula_total();   		
    	}    	
    }else if( (tecla.keyCode == 65)){
    	// tecla A
    	//$('#op_juego').val('1');// hace focus en el campo de tipo de juego y selecciona Triple
    	$('input:radio[name=op_juego]')[0].checked = true;
    	borra_txtNumero();
    }else if( (tecla.keyCode == 66)){
    	// tecla B    	
    	$("#txt_monto").focus(); // hace focus en el campo de monto Bs
    	//$("#txt_monto").select();
    }else if( (tecla.keyCode == 67)){
    	// tecla C 
		//$('#op_juego').val('4');// hace focus en el campo de tipo de juego y selecciona Corrida
    	$('input:radio[name=op_juego]')[3].checked = true;
    	borra_txtNumero();     	
    }else if((tecla.keyCode == 68)){
        // Tecla D
    	//DISPONIBLE
    }else if((tecla.keyCode == 69)){
    	// tecla E
    	if(DetectarUrlVentas()){
    		verDetallaTicket(); 		
    	}       	
    }else if((tecla.keyCode == 70)){
    	// tecla F    	
    	var myRadio = $("input[name='turno']:checked").val();
    	//alert(myRadio);
    	
    	if(myRadio == '1'){
    		//el turno es la manana
        	if ($('input.t_manana').is(':checked')){
        		$('input.t_manana').prop("checked", false)
            }else{
            	$('input.t_manana').prop('checked', true); //seleccionar el turno de la manana
            }    		
    	}else if(myRadio == '2'){
    		//el turno es la tarde
        	if ($('input.t_tarde').is(':checked')){
        		$('input.t_tarde').prop("checked", false)
            }else{
            	$('input.t_tarde').prop('checked', true); //seleccionar el turno de la tarde
            }		
    	}else if(myRadio == '3'){
    		//el turno es la noche
        	if ($('input.t_noche').is(':checked')){
        		$('input.t_noche').prop("checked", false)
            }else{
            	$('input.t_noche').prop('checked', true); //seleccionar el turno de la Noche
            }		
    	}else if(myRadio == '4'){
    		//el turno es Todos
        	if ($('input.t_manana').is(':checked')){
        		$('input.t_manana').prop("checked", false)
            }else{
            	$('input.t_manana').prop('checked', true); //seleccionar el turno de la manana
            }     		
        	if ($('input.t_tarde').is(':checked')){
        		$('input.t_tarde').prop("checked", false)
            }else{
            	$('input.t_tarde').prop('checked', true); //seleccionar Todos los Turnos
            }
        	if ($('input.t_noche').is(':checked')){
        		$('input.t_noche').prop("checked", false)
            }else{
            	$('input.t_noche').prop('checked', true); //seleccionar el turno de la Noche
            }	        	
    	}    	 	
    }else if( (tecla.keyCode == 71)){    	
    	// tecla G
    	if(DetectarUrlVentas()){
            //Preguntamos monto de los terminales y Generamos los Terminales
            agregarTerminales();
            calcula_total();  		
    	}       	
    }else if( (tecla.keyCode == 72)){
    	// tecla H    
    	if(DetectarUrlVentas()){
            //Preguntamos monto de los terminales y Generamos los Terminales        
            agregarTerminalazo();
            calcula_total(); 		
    	}       	
    }else if((tecla.keyCode == 73)){
        // Tecla I
    	if(DetectarUrlVentas()){
    		verBorrarSorteoTicket(); 		
    	} 
    }else if((tecla.keyCode == 74)){
    	// tecla J
    	if(DetectarUrlVentas()){
    		//Ajustar los montos de todas las apuestas a un prorrateado del total
    		AjustarMontos();  		
    	}       	
    }else if((tecla.keyCode == 75)){
        // Tecla K
    	// DISPONIBLE
    }else if((tecla.keyCode == 76)) {
    	// tecla L
    	checkAllsorteos(document.getElementsByName('ss[]'));
    	$("#s0").focus(); // hace focus en el campo de checkboxes Sorteos	      	
    }else if((tecla.keyCode == 77)){
    	// tecla M    	     	
        // Turno Manana
		$("#txt_numero").focus(); // hace focus en el campo de numeros	
    	$('.text-label2').hide();
    	$('.text-label3').hide();
    	$('.text-label1').show();
    	
    	$('input:radio[name=turno]')[0].checked = true;
    	
		$("#s0").focus(); // hace focus en el campo de checkboxes Sorteos
    }else if( (tecla.keyCode == 78)){
    	// tecla N    	    	
        // Turno Noche
		$("#txt_numero").focus(); // hace focus en el campo de numeros		
    	$('.text-label1').hide();
    	$('.text-label2').hide();
    	$('.text-label3').show();
    	
    	$('input:radio[name=turno]')[2].checked = true;
    	
		$("#s0").focus(); // hace focus en el campo de checkboxes Sorteos
    }else if((tecla.keyCode == 79)) {
    	// tecla O      	
		$("#txt_numero").focus(); // hace focus en el campo de numeros			
    	$('.text-label1').show(); // Mostrar Todos los Turnos M, T, N
    	$('.text-label2').show();
    	$('.text-label3').show();
    	
    	$('input:radio[name=turno]')[3].checked = true;    	
		$("#s0").focus(); // hace focus en el campo de checkboxes Sorteos	   	
    }else if((tecla.keyCode == 80)){
    	// tecla P      	
    	//$('#op_juego').val('2');// hace focus en el campo de tipo de juego y selecciona Permuta
    	$('input:radio[name=op_juego]')[1].checked = true;
    	borra_txtNumero();
    }else if((tecla.keyCode == 81)){
    	// tecla Q      	
    	CargarReset(); //Limpiar Todos los campos
    }else if( (tecla.keyCode == 82)) {
    	// tecla R     	
    	$("#recibido").focus(); // hace focus en el campo de vuelto o cambio 
    }else if( (tecla.keyCode == 83)) {
    	// tecla S     	
    	//$('#op_juego').val('3');// hace focus en el campo de tipo de juego y selecciona Serie
    	$('input:radio[name=op_juego]')[2].checked = true;
    	borra_txtNumero();
    }else if((tecla.keyCode == 84)){
    	// tecla T     	
        // Turno Tarde
		$("#txt_numero").focus(); // hace focus en el campo de numeros
    	$('.text-label1').hide();
    	$('.text-label3').hide();
    	$('.text-label2').show();
    	
    	$('input:radio[name=turno]')[1].checked = true;
    	
		$("#s0").focus(); // hace focus en el campo de checkboxes Sorteos		
    }else if( (tecla.keyCode == 85)){
        // Tecla U     	
    	$("#txt_numero").focus(); // hace focus en el campo de numeros    	
    }else if( (tecla.keyCode == 86)){
        // Tecla V
    	// DISPONIBLE   
    }else if( (tecla.keyCode == 87)){
        // Tecla W
    	if(DetectarUrlVentas()){
    		//Quitar los terminales
        	BorrarTerminales();  	
    	}     
    }else if((tecla.keyCode == 88)){
        // Tecla X
    	if(DetectarUrlVentas()){
            //Borrar PreTicket
            BorrarPreTicket();  		
    	}       	
    }else if( (tecla.keyCode == 89)){
        // Tecla Y
    	if(DetectarUrlVentas()){
            //Quitar la ultima jugada
            BorrarUltimaJugada(); 
    	}       	
    }else if( (tecla.keyCode == 90)){
    	// tecla Z     	
    	$("#z0").focus(); // hace focus en el campo de checkboxes Zodiacales
    }else if(tecla.keyCode == 114) {
    	// tecla F3
    	// DISPONIBLE
    }else if(tecla.keyCode == 115) {
    	// tecla F4
    	PagarTicket(); //Limpiar Todos los campos
    }else if(tecla.keyCode == 116) {
    	// tecla F5
    	location.reload();
    	//CargarReset();//Refresh
    }else if(tecla.keyCode == 117) {
    	// tecla F6
	 	//Anular Ticket
        AnularTicket();
    }else if(tecla.keyCode == 118) {
    	// tecla F7
	 	//Repetir Ticket
        RepetirTicket();
    }else if(tecla.keyCode == 119) {
    	// tecla F8
    	// DISPONIBLE
    }else if(tecla.keyCode == 120) {
    	// tecla F9
    	// DISPONIBLE
    }else if(tecla.keyCode == 121) {
    	// tecla F10
	 	//Reimprimir Ticket
    	ReImprimirticket();
    }else if(tecla.keyCode == 27) {
    	// tecla ESC
    	//Procesar Ticket
        procesarticket(); //para procesar y generar el ticket
		CargarReset();
    }else if(tecla.keyCode == 32) {
    	// tecla SPACE
    	
    	//var posicion = $(":input").index(document.activeElement);
    	//alert(posicion); 
    	//alert($(":input").index(document.activeElement));
    	//$("input:checkbox[value=64]").attr("checked", true);
    	
    	//Accion para marcar y hacer salto al proximo input
    	$(":input")[$(":input").index(document.activeElement) + 1].focus();

    }else if(tecla.keyCode == 40) {
    	// tecla Flecha Abajo
    	
    	toggleOffAbajo();
    	/*

    	//alert($(":input").index(document.activeElement));
    	if( $(":input").index(document.activeElement) == '7' ){
    		$(":input")[$(":input").index(document.activeElement) + 7].focus();
    	}else{
    		
    		$(":input")[$(":input").index(document.activeElement) + 1].focus();
    	}
    	
    	return false;
    	
    	 

    	 $(":focus").each(function() {
    		    alert("Focused Elem_id = "+ this.id );
    		}); */
    }else if(tecla.keyCode == 38) {
    	// tecla Flecha Arriba
		if( $(":input").index(document.activeElement) == '7' ){
    		$(":input")[$(":input").index(document.activeElement) - 1].focus();
    	}
		    	
    	toggleOffArriba();
    	/*
    	//alert($(":input").index(document.activeElement));    	
    	if( $(":input").index(document.activeElement) == '14' ){
    		$(":input")[$(":input").index(document.activeElement) - 7].focus();
    	}else{
    		$(":input")[$(":input").index(document.activeElement) - 1].focus();
    	}    	
    	//$(":input")[$(":input").index(document.activeElement) - 1].focus();
    	 */
    }else if(tecla.keyCode == 37) {
    	// tecla Flecha Izquierda   
    	if($("*:focus").attr("id") == 'z0'){
    		$("#s0").focus();    		
    	}else{
    		$("#txt_numero").focus();
    	}
    }else if(tecla.keyCode == 39) {
    	// tecla Flecha Derecha    	
    	if($("*:focus").attr("id") == 'txt_numero'){
    		$("#s0").focus();    		
    	}else if($("*:focus").attr("id") == 'txt_monto'){
    		$("#s0").focus();
    	}else{
    		$("#z0").focus();
    	}
    }else if(tecla.keyCode == 13) {
    	// tecla ENTER 
    	if(DetectarUrlVentas()){
            //Detectar Si esta en Txt_Numero
    		//alert($(":input").index(document.activeElement));
        	if( $(":input").index(document.activeElement) == '6' ){
        		$(":input")[$(":input").index(document.activeElement) + 1].focus();
        	}else if( $(":input").index(document.activeElement) == '7' ){
        		$("#s0").focus();
        	}else{
        		$(":input")[$(":input").index(document.activeElement) + 1].focus();
        	}   		
    	}      	

    }

    
});

$(document).keydown(function(tecla){
	
	if(tecla.keyCode == 40) {
    	// tecla Flecha Abajo
    	//alert($(":input").index(document.activeElement));
		if( $(":input").index(document.activeElement) == '6' ){
    		$(":input")[$(":input").index(document.activeElement) + 1].focus();
    	}		
		else if( $(":input").index(document.activeElement) == '7' ){
    		$(":input")[$(":input").index(document.activeElement) + 7].focus();
    	}else if($(":input").index(document.activeElement) > '13'){
    		toggleOnAbajo();
    		//$(":input")[$(":input").index(document.activeElement) + 1].focus();
    	}

    }
	else if(tecla.keyCode == 38) {
    	// tecla Flecha Arriba
    	//alert($(":input").index(document.activeElement));
		if( $(":input").index(document.activeElement) == '6' ){
    		$(":input")[$(":input").index(document.activeElement)].focus();
    	}		
		else if( $(":input").index(document.activeElement) == '14' ){
    		$(":input")[$(":input").index(document.activeElement) - 7].focus();
    	}
		else if($(":input").index(document.activeElement) > '13'){
			toggleOnArriba();
    		//$(":input")[$(":input").index(document.activeElement) + 1].focus();
    	}   	

    }	
	
});

//Para validar Form de Ventas
function ValidateFormSell(){
	jQuery('#txt_numero').keydown(function (event) {
	    // Allow only backspace and delete
	           if (event.keyCode == 127 || event.keyCode == 46 || event.keyCode == 8  || event.keyCode == 9 || event.keyCode == 110 || event.keyCode == 109 || (event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105)) {
	        	   //alert(event.keyCode);
	        	   // let it happen, don't do anything
	        	   // 46 = punto ; 8 = Retroceso ; 9 = tabulador
	        	   // 106 = asterisco; 109 = menos para corrida
	        	   // 110 punto; 16 = shift; 32= space; 127= delete
	        	   
	               // Ensure that it is a number and stop the keypress
	               if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105)) {
	                 count = jQuery('#txt_numero').val().length;
	                 var juego = $("input[name='op_juego']:checked").val();
	                 //juego = jQuery('#op_juego').val();
 	                 	                 
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
	               }/*
	               else {
	            	   //alert('puta');
	                   event.preventDefault();
	               } */   	   
	           }
	           else {
	        	   //alert(event.keyCode);
	        	   event.preventDefault();
	        	   //alert('VERGA');
	           }
	});
	
	jQuery('#txt_numero').keyup(function (event) {
		var juego = $("input[name='op_juego']:checked").val();
		
		count = jQuery('#txt_numero').val().length;
        		
        //Para hacer focus en txt_monto si txt_numero es igual a 3
        // si el modo de juego es triple
        // hace focus en el campo de monto Bs 
        if (count == 3 && juego == 1){ 
       	 $("#txt_monto").focus();
       	 //$("#txt_monto").select();
        }
        
        if (count == 2 && juego == 3){ 
          	 $("#txt_monto").focus();
          	 //$("#txt_monto").select();
           }        
		
	});	
	
	jQuery('#txt_monto').keydown(function (event) {
	    // Allow only backspace and delete
		//alert(event.keyCode);
	           if (event.keyCode == 46 || event.keyCode == 8  || event.keyCode == 9 || event.keyCode == 110 || event.keyCode == 127 || event.keyCode == 45 || event.keyCode == 109 || event.keyCode == 190 || event.keyCode == 173) {
	        	   //alert(event.keyCode);
	        	   // let it happen, don't do anything
	        	   // 46 = punto ; 8 = Retroceso ; 9 = tabulador
	        	   // 109 = Negativo; 45 = guion
	        	   // 190 punto; 127 = guion o negativo
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
	if (flag == 1)
	checkear=true;
	else
	checkear=false;
	$.get('ajax/ValidarZodiacal.php', function(str) {
		//alert(str);	
		var arr = str.split('-');
		for (i = 0; i < chk.length; i++){
			sw=0;
			for(h=0;h< arr.length; h++){
				if(chk[i].value==arr[h])
				sw=1;
			}
			if(sw==0)
			chk[i].checked = checkear;	
		}
	});
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
