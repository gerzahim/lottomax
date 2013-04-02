/**
  @license html2canvas v0.34 <http://html2canvas.hertzen.com>
  Copyright (c) 2011 Niklas von Hertzen. All rights reserved.
  http://www.twitter.com/niklasvh

  Released under MIT License
 */
 
 function print(titulo) {
         var applet = document.jzebra;
         if (applet != null) {
			 
			alert("Titulo: "+titulo);

            // Send characters/raw commands to applet using "append"
            // Hint:  Carriage Return = \r, New Line = \n, Escape Double Quotes= \"
            //applet.append("\r wwwwwwwwwwwe");
            applet.append(titulo);			
			//alert("P1oooooooo\n "+titulo);
			applet.append("\n.\n");			
			//applet.append("P1oooooooo\nP2oooooooo\nP3oooooooo\n");
           // applet.append(hola+"\n");			
			//document.jzebra.append(chr(27) + "\x70" + "\x30" + chr(25) + chr(25) + "\r");
            
            // Send characters/raw commands to printer
            applet.print();
	 }
	 
         // *Note:  monitorPrinting() still works but is too complicated and
         // outdated.  Instead create a JavaScript  function called 
         // "jzebraDonePrinting()" and handle your next steps there.
	 monitorPrinting();
         
         /**
           *  PHP PRINTING:
           *  // Uses the php `"echo"` function in conjunction with jZebra `"append"` function
           *  // This assumes you have already assigned a value to `"$commands"` with php
           *  document.jZebra.append(<?php echo $commands; ?>);
           */
           
         /**
           *  SPECIAL ASCII ENCODING
           *  //applet.setEncoding("UTF-8");
           *  applet.setEncoding("Cp1252"); 
           *  applet.append("\xDA");
           *  applet.append(String.fromCharCode(218));
           *  applet.append(chr(218));
           */
         
      }
      
   
      // *Note:  monitorPrinting() still works but is too complicated and
      // outdated.  Instead create a JavaScript  function called 
      // "jzebraDonePrinting()" and handle your next steps there.
      function monitorPrinting() {
	var applet = document.jzebra;
	if (applet != null) {
	   if (!applet.isDonePrinting()) {
	      window.setTimeout('monitorPrinting()', 100);
	   } else {
	      var e = applet.getException();
	      alert(e == null ? "Printed Successfully" : "Exception occured: " + e.getLocalizedMessage());
	   }
	} else {
            alert("Applet not loaded!");
        }
      }
	  
	  
	  // No es necesario pero sirve para imprimir alert de nombre de impresora
	  // cuando se llama a metodo useDefaultPrinter()
	  
      /*
      function monitorFinding() {
	var applet = document.jzebra;
	if (applet != null) {
	   if (!applet.isDoneFinding()) {
	      window.setTimeout('monitorFinding()', 100);
	   } else {
	      var printer = applet.getPrinter();
              alert(printer == null ? "Printer not found" : "Printer \"" + printer + "\" found");
	   }
	} else {
            alert("Applet not loaded!");
        }
      }
      */


	  
      function useDefaultPrinter() {
         var applet = document.jzebra;
         if (applet != null) {
            // Searches for default printer
            applet.findPrinter();
         }
         
         monitorFinding();
      }
      
	  
      function jzebraReady() {
          // Change title to reflect version
          var applet = document.jzebra;
          var title = document.getElementById("title");
          if (applet != null) {
              title.innerHTML = title.innerHTML + " " + applet.getVersion();
              document.getElementById("content").style.background = "#F0F0F0";
          }
      }
      