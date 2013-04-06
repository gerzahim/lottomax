<html>
   <head><title>jZebra Demo</title>
   <script language="javascript" type="text/javascript" src="../jscripts/jquery-latest.js"></script>
   <script language="javascript" type="text/javascript" src="PluginPrint.js"></script>       
   <script language="javascript" type="text/javascript" src="../jscripts/DefaultPrinter.js"></script>     
    
   </head>
   <body id="content" bgcolor="#FFF380">
   
   <applet name="jzebra" code="jzebra.PrintApplet.class" archive="../jscripts/jzebra.jar" width="50px" height="50px">
	  <param name="printer" value="zebra">
   </applet>
   

		   <?php $commands="VERGA DURA"; 
		   echo "<script type='text/javascript'>";
		   echo "print('".$commands."')";
		   echo "</script>";
		   ?>
   </body>

   
</html>