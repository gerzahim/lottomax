<?php

// ENCABEZADO DEL TICKET
$data=" <table width='100%' cellpadding='0' cellspacing='0' border='0' >";
$data.="<tr><td colspan='2' align='center'><font face='Arial' size='3' >";
$data.="SISTEMA LOTTOMAX";
$data.=" </font></td> </tr>";
$data.="<tr> <td colspan='2' align='center'><font face='Arial' size='3' >";
$data.="Agencia: ".$nombre_agencia;
$data.="</font></td> </tr>";
$data.="<tr><td colspan='2' align='center'><font face='Arial' size='3' >";
$data.="Ticket: ".$formato_id_ticket;
$data.="</font></td> </tr>";
$data.="<tr><td colspan='2' align='center'><font face='Arial' size='3' >";
$data.="Serial: ".$formato_serial;
$data.="</font></td> </tr>";
$data.="<tr><td colspan='2' align='center'><font face='Arial' size='3' >";
$data.=$dias[date('w')]." ".$fecha_hora."&nbsp;&nbsp;".$hora;
$data.="</font></td> </tr>";
$data.="<tr><td colspan='2' align='center'><font face='Arial' size='3' >";
$data.="Taquilla: ".$id_taquilla;
$data.="</font></td> </tr>";
$data.="<tr><td colspan='2' align='center'><font face='Arial' size='3' >";
$data.="Vendedor: ".$nombre;
$data.="</font></td> </tr>";

$data.="<tr height='10'><td colspan='2' align='center'></td></tr>";
$data.="<tr height='10'><td colspan='2' align='center'><font face='Arial' size='3' >Arial	1234567890</font> </td></tr>";
$data.="<tr height='10'><td colspan='2' align='center'><font face='Times' size='3' >Times	1234567890</font> </td></tr>";
$data.="<tr height='10'><td colspan='2' align='center'><font face='Candara' size='3' >Candara	1234567890</font> </td></tr>";
$data.="<tr height='10'><td colspan='2' align='center'><font face='Century' size='3' >Century	1234567890</font> </td></tr>";
$data.="<tr height='10'><td colspan='2' align='center'><font face='Courier New' size='3' >Courier New 1234567890</font> </td></tr>";
$data.="<tr height='10'><td colspan='2' align='center'><font face='DotumChe' size='3' >DotumChe  1234567890</font> </td></tr>";
$data.="<tr height='10'><td colspan='2' align='center'><font face='Lucida Sans' size='3' >Lucida Sans	1234567890</font> </td></tr>";
$data.="<tr height='10'><td colspan='2' align='center'><font face='Gulim' size='3' >Gulim	1234567890</font> </td></tr>";
$data.="<tr height='10'><td colspan='2' align='center'><font face='Ms Reference San Serif' size='3' >Ms Reference San Serif  1234567890</font> </td></tr>";
$data.="<tr height='10'><td colspan='2' align='center'><font face='San Serif' size='3' >San Serif   1234567890</font> </td></tr>";
$data.="<tr height='10'><td colspan='2' align='center'><font face='Verdana' size='3' >Verdana	1234567890</font> </td></tr>";
$data.="<tr height='10'><td colspan='2' align='center'><font face='Tahoma' size='3' >Tahoma	1234567890</font> </td></tr>";
$data.="</table>";
echo $data;


?>

<script type="text/javascript"> 
window.print();
</script>
<script language='javascript'>setTimeout('self.close();',5000)</script>
</body>
</html>